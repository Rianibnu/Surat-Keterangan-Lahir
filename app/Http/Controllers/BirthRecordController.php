<?php

namespace App\Http\Controllers;

use App\Models\BirthRecord;
use App\Models\Doctor;
use App\Models\Skl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BirthRecordController extends Controller
{
    /**
     * Display a listing of birth records.
     */
    public function index(): Response
    {
        $records = BirthRecord::with('skl')->latest()->get();

        return Inertia::render('BirthRecord/Index', [
            'records' => $records,
        ]);
    }

    /**
     * Show the form for creating a new birth record.
     */
    public function create(): Response
    {
        $doctors = Doctor::all();

        return Inertia::render('BirthRecord/Create', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * Store a newly created birth record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $record = BirthRecord::create($validated);

        $documentNumber = $this->generateDocumentNumber();

        // Auto create SKL entry
        $doctor = Doctor::find($request->doctor_id);
        $record->skl()->create([
            'document_number' => $documentNumber,
            'issue_date' => now(),
            'signer_name' => $doctor->name,
            'signer_role' => 'Dokter Penanggung Jawab Persalinan',
        ]);

        return redirect()->route('birth-records.show', $record->id);
    }

    /**
     * Display the specified birth record.
     */
    public function show(BirthRecord $birthRecord): Response
    {
        $birthRecord->load(['doctor', 'skl']);

        return Inertia::render('BirthRecord/Show', [
            'record' => $birthRecord,
        ]);
    }

    /**
     * Show the form for editing the specified birth record.
     */
    public function edit(BirthRecord $birthRecord): Response
    {
        $doctors = Doctor::all();

        return Inertia::render('BirthRecord/Edit', [
            'record' => $birthRecord,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Update the specified birth record.
     */
    public function update(Request $request, BirthRecord $birthRecord): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $birthRecord->update($validated);

        return redirect()->route('birth-records.index')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified birth record.
     */
    public function destroy(BirthRecord $birthRecord): RedirectResponse
    {
        $birthRecord->delete();

        return redirect()->route('birth-records.index');
    }

    /**
     * Export birth records as CSV.
     */
    public function export(): StreamedResponse
    {
        $fileName = 'data_kelahiran_' . date('Y-m-d_H-i-s') . '.csv';
        $records = BirthRecord::with(['skl', 'doctor'])->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'No. RM Bayi',
            'Nama Bayi',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Jam Lahir',
            'Berat (gram)',
            'Panjang (cm)',
            'Dokter',
            'No. SKL',
            'Nama Ibu',
            'Nama Ayah',
        ];

        $callback = function () use ($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($records as $record) {
                fputcsv($file, [
                    $record->medical_record_no,
                    $record->baby_name,
                    $record->gender,
                    $record->birth_date->format('Y-m-d'),
                    $record->birth_time,
                    $record->weight,
                    $record->length,
                    $record->doctor->name,
                    $record->skl ? $record->skl->document_number : 'Belum Terbit',
                    $record->mother_name,
                    $record->father_name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate SKL for a birth record.
     */
    public function generateSkl(BirthRecord $birthRecord): RedirectResponse
    {
        try {
            if ($birthRecord->skl) {
                return redirect()->back()->with('error', 'SKL already exists.');
            }

            $documentNumber = $this->generateDocumentNumber();
            $doctor = $birthRecord->doctor;

            $birthRecord->skl()->create([
                'document_number' => $documentNumber,
                'issue_date' => now(),
                'signer_name' => $doctor ? $doctor->name : 'Dokter',
                'signer_role' => 'Dokter Penanggung Jawab Persalinan',
            ]);

            return redirect()->back()->with('success', 'SKL generated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate SKL: ' . $e->getMessage());
        }
    }

    /**
     * Get validation rules for birth record.
     */
    private function validationRules(): array
    {
        return [
            'baby_name' => 'required|string',
            'birth_date' => 'required|date',
            'birth_time' => 'required',
            'medical_record_no' => 'required|string',
            'gender' => 'required|string',
            'child_order' => 'required|integer',
            'delivery_method' => 'required|string',
            'weight' => 'required|numeric',
            'length' => 'required|numeric',
            'head_circ' => 'required|numeric',
            'chest_circ' => 'required|numeric',
            'mother_name' => 'required|string',
            'mother_address' => 'required|string',
            'father_name' => 'required|string',
            'doctor_id' => 'required|exists:doctors,id',
            'mother_medical_record_no' => 'nullable|string',
            'mother_ktp' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'mother_blood_type' => 'nullable|string',
            'father_ktp' => 'nullable|string',
            'father_address' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'father_blood_type' => 'nullable|string',
            'baby_blood_type' => 'nullable|string',
        ];
    }

    /**
     * Generate a unique SKL document number.
     */
    private function generateDocumentNumber(): string
    {
        $count = Skl::whereYear('created_at', now()->year)->count() + 1;
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanMonth = $romanMonths[now()->month - 1];

        return sprintf("%03d/SKL/RM-RSUKM/%s/%s", $count, $romanMonth, date('Y'));
    }
}
