<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
    public function create()
    {
        return Inertia::render('Doctors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hospital' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:255',
            'signature' => 'nullable|string', // Base64 string
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
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return Inertia::render('Doctors/Edit', [
            'doctor' => $doctor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Doctor update started', [
                'doctor_id' => $doctor->id,
                'request_data_keys' => array_keys($request->all()),
                'has_signature' => $request->filled('signature'),
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'hospital' => 'required|string|max:255',
                'license_no' => 'nullable|string|max:255',
                'signature' => 'nullable|string', // Base64 form new signature
            ]);

            \Illuminate\Support\Facades\Log::info('Validation passed', ['validated_keys' => array_keys($validated)]);

            if ($request->filled('signature')) {
                \Illuminate\Support\Facades\Log::info('Processing new signature');
                
                // Delete old signature if exists
                if ($doctor->signature_path && Storage::disk('public')->exists($doctor->signature_path)) {
                    Storage::disk('public')->delete($doctor->signature_path);
                }

                $path = $this->saveSignature($request->signature);
                if ($path) {
                    $validated['signature_path'] = $path;
                    \Illuminate\Support\Facades\Log::info('Signature saved', ['path' => $path]);
                }
            }
            unset($validated['signature']);

            $doctor->update($validated);
            \Illuminate\Support\Facades\Log::info('Doctor updated successfully', ['doctor_id' => $doctor->id]);

            return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::warning('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Doctor update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['name' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
        }
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
    public function destroy(Doctor $doctor)
    {
        try {
            // Check if doctor has related birth records
            $birthRecordCount = $doctor->birthRecords()->count();
            
            if ($birthRecordCount > 0) {
                // Option 1: Prevent deletion
                // return redirect()->route('doctors.index')
                //     ->with('error', "Tidak dapat menghapus dokter ini karena masih memiliki {$birthRecordCount} data kelahiran terkait.");
                
                // Option 2: Allow deletion with cascade (current behavior)
                // Birth records will be deleted due to ON DELETE CASCADE in migration
            }

            // Delete signature file if exists
            if ($doctor->signature_path && Storage::disk('public')->exists($doctor->signature_path)) {
                Storage::disk('public')->delete($doctor->signature_path);
            }

            $doctorName = $doctor->name;
            $doctor->delete();

            return redirect()->route('doctors.index')
                ->with('success', "Data dokter '{$doctorName}' berhasil dihapus.");
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle foreign key constraint violation
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
