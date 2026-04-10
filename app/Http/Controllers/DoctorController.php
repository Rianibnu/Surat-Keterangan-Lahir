<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Doctor::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('hospital', 'like', "%{$search}%")
                  ->orWhere('license_no', 'like', "%{$search}%");
        }

        $doctors = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Doctors/Index', [
            'doctors' => $doctors,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Doctors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hospital' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:255',
            'signature' => 'nullable|string',
        ]);

        if ($request->filled('signature')) {
            $path = $this->saveSignature($request->signature);
            if ($path) {
                $validated['signature_path'] = $path;
            }
        }
        unset($validated['signature']);

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor): Response
    {
        $doctor->load('birthRecords');

        return Inertia::render('Doctors/Show', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor): Response
    {
        return Inertia::render('Doctors/Edit', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hospital' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:255',
            'signature' => 'nullable|string',
        ]);

        if ($request->filled('signature')) {
            // Delete old signature if exists
            if ($doctor->signature_path && Storage::disk('public')->exists($doctor->signature_path)) {
                Storage::disk('public')->delete($doctor->signature_path);
            }

            $path = $this->saveSignature($request->signature);
            if ($path) {
                $validated['signature_path'] = $path;
            }
        }
        unset($validated['signature']);

        $doctor->update($validated);

        return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    private function saveSignature($base64_image_string)
    {
        // Check if string is base64
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image_string, $type)) {
            $data = substr($base64_image_string, strpos($base64_image_string, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, etc.

            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return null;
            }

            $data = base64_decode($data);
            if ($data === false) {
                return null;
            }

            $filename = 'signatures/' . Str::random(40) . '.' . $type;
            Storage::disk('public')->put($filename, $data);

            return $filename;
        }

        return null;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor): RedirectResponse
    {
        try {
            // Delete signature file if exists
            if ($doctor->signature_path && Storage::disk('public')->exists($doctor->signature_path)) {
                Storage::disk('public')->delete($doctor->signature_path);
            }

            $doctorName = $doctor->name;
            $doctor->delete();

            return redirect()->route('doctors.index')
                ->with('success', "Data dokter '{$doctorName}' berhasil dihapus.");

        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return redirect()->route('doctors.index')
                    ->with('error', 'Tidak dapat menghapus dokter ini karena masih terkait dengan data lain.');
            }

            return redirect()->route('doctors.index')
                ->with('error', 'Gagal menghapus dokter: ' . $e->getMessage());

        } catch (\Exception $e) {
            return redirect()->route('doctors.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
