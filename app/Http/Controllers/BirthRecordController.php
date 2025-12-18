<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BirthRecordController extends Controller
{
    public function create()
    {
        $doctors = \App\Models\Doctor::all();
        return \Inertia\Inertia::render('BirthRecord/Create', [
            'doctors' => $doctors
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            // Add other nullable fields as needed
            'mother_medical_record_no' => 'nullable|string',
            'mother_ktp' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'mother_blood_type' => 'nullable|string',
            'father_ktp' => 'nullable|string',
            'father_address' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'father_blood_type' => 'nullable|string',
            'baby_blood_type' => 'nullable|string',
        ]);

        $record = \App\Models\BirthRecord::create($validated);

        $count = \App\Models\Skl::whereYear('created_at', now()->year)->count() + 1;
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanMonth = $romanMonths[now()->month - 1];
        
        $documentNumber = sprintf("%03d/SKL/RM-RSUKM/%s/%s", $count, $romanMonth, date('Y'));

        // Auto create SKL entry
        $doctor = \App\Models\Doctor::find($request->doctor_id);
        $record->skl()->create([
            'document_number' => $documentNumber,
            'issue_date' => now(),
            'signer_name' => $doctor->name,
            'signer_role' => 'Dokter Penanggung Jawab Persalinan',
        ]);

        return redirect()->route('birth-records.show', $record->id);
    }

    public function index()
    {
        $records = \App\Models\BirthRecord::with('skl')->latest()->get();
        return \Inertia\Inertia::render('BirthRecord/Index', [
            'records' => $records
        ]);
    }

    public function show($id)
    {
        $record = \App\Models\BirthRecord::with(['doctor', 'skl'])->findOrFail($id);
        return \Inertia\Inertia::render('BirthRecord/Show', [
            'record' => $record
        ]);
    }

    public function edit($id)
    {
        $record = \App\Models\BirthRecord::findOrFail($id);
        $doctors = \App\Models\Doctor::all();
        
        return \Inertia\Inertia::render('BirthRecord/Edit', [
            'record' => $record,
            'doctors' => $doctors
        ]);
    }

    public function update(Request $request, $id)
    {
        $record = \App\Models\BirthRecord::findOrFail($id);
        
        $validated = $request->validate([
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
            
            // Nullable fields
            'mother_medical_record_no' => 'nullable|string',
            'mother_ktp' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'mother_blood_type' => 'nullable|string',
            'father_ktp' => 'nullable|string',
            'father_address' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'father_blood_type' => 'nullable|string',
            'baby_blood_type' => 'nullable|string',
        ]);

        $record->update($validated);
        
        return redirect()->route('birth-records.index')->with('success', 'Data updated successfully');
    }
    
    public function destroy($id)
    {
        $record = \App\Models\BirthRecord::findOrFail($id);
        $record->delete();
        return redirect()->route('birth-records.index');
    }

    public function export()
    {
        $fileName = 'data_kelahiran_'.date('Y-m-d_H-i-s').'.csv';
        $records = \App\Models\BirthRecord::with(['skl', 'doctor'])->latest()->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(
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
            'Nama Ayah'
        );

        $callback = function() use($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($records as $record) {
                $row['Medical Record No']  = $record->medical_record_no;
                $row['Baby Name']    = $record->baby_name;
                $row['Gender']    = $record->gender;
                $row['Birth Date']  = $record->birth_date->format('Y-m-d');
                $row['Birth Time']  = $record->birth_time;
                $row['Weight']  = $record->weight;
                $row['Length']  = $record->length;
                $row['Doctor']  = $record->doctor->name;
                $row['SKL Number']  = $record->skl ? $record->skl->document_number : 'Belum Terbit';
                $row['Mother Name'] = $record->mother_name;
                $row['Father Name'] = $record->father_name;

                fputcsv($file, array(
                    $row['Medical Record No'],
                    $row['Baby Name'],
                    $row['Gender'],
                    $row['Birth Date'],
                    $row['Birth Time'],
                    $row['Weight'],
                    $row['Length'],
                    $row['Doctor'],
                    $row['SKL Number'],
                    $row['Mother Name'],
                    $row['Father Name']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function generateSkl($id)
    {
        try {
            $record = \App\Models\BirthRecord::findOrFail($id);

            if ($record->skl) {
                return redirect()->back()->with('error', 'SKL already exists.');
            }

            $count = \App\Models\Skl::whereYear('created_at', now()->year)->count() + 1;
            $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romanMonths[now()->month - 1];
            
            $documentNumber = sprintf("%03d/SKL/RM-RSUKM/%s/%s", $count, $romanMonth, date('Y'));

            $doctor = $record->doctor;
            
            $record->skl()->create([
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
}
