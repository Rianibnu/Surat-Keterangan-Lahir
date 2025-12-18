<?php

namespace App\Http\Controllers;

use App\Models\BirthRecord;
use App\Models\Skl;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class SklPdfController extends Controller
{
    /**
     * Generate QR Code as base64 SVG
     */
    private function generateQrCode(string $data): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(70),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($data);
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Download SKL as PDF.
     */
    public function download(BirthRecord $birthRecord)
    {
        $birthRecord->load(['doctor', 'skl']);

        if (!$birthRecord->skl) {
            return back()->with('error', 'SKL belum dibuat untuk data kelahiran ini.');
        }

        // Generate QR Code
        $qrCode = null;
        if ($birthRecord->skl->uuid) {
            $verifyUrl = url('/verify-skl/' . $birthRecord->skl->uuid);
            $qrCode = $this->generateQrCode($verifyUrl);
        }

        // Log the PDF download activity
        activity()
            ->performedOn($birthRecord->skl)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'download_pdf'])
            ->log('SKL PDF diunduh');

        $pdf = Pdf::loadView('pdf.skl', [
            'record' => $birthRecord,
            'skl' => $birthRecord->skl,
            'doctor' => $birthRecord->doctor,
            'qrCode' => $qrCode,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'SKL-' . $birthRecord->skl->document_number . '.pdf';
        $filename = str_replace('/', '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Stream/Preview SKL as PDF.
     */
    public function stream(BirthRecord $birthRecord)
    {
        $birthRecord->load(['doctor', 'skl']);

        if (!$birthRecord->skl) {
            return back()->with('error', 'SKL belum dibuat untuk data kelahiran ini.');
        }

        // Generate QR Code
        $qrCode = null;
        if ($birthRecord->skl->uuid) {
            $verifyUrl = url('/verify-skl/' . $birthRecord->skl->uuid);
            $qrCode = $this->generateQrCode($verifyUrl);
        }

        $pdf = Pdf::loadView('pdf.skl', [
            'record' => $birthRecord,
            'skl' => $birthRecord->skl,
            'doctor' => $birthRecord->doctor,
            'qrCode' => $qrCode,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'SKL-' . $birthRecord->skl->document_number . '.pdf';
        $filename = str_replace('/', '-', $filename);

        return $pdf->stream($filename);
    }
}
