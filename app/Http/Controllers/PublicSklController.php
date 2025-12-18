<?php

namespace App\Http\Controllers;

use App\Models\Skl;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicSklController extends Controller
{
    public function verify($uuid)
    {
        $skl = Skl::where('uuid', $uuid)->with(['birthRecord.doctor'])->firstOrFail();

        // Log the verification activity
        activity('verifikasi')
            ->event('verified')
            ->causedByAnonymous() // Publicly accessed, no authenticated user
            ->performedOn($skl)
            ->withProperties([
                'uuid' => $skl->uuid,
                'document_number' => $skl->document_number,
                'baby_name' => $skl->birthRecord->baby_name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('SKL diverifikasi melalui QR Code');

        return Inertia::render('Public/VerifySkl', [
            'record' => [
                'skl' => $skl,
                'uuid' => $skl->uuid,
                'document_number' => $skl->document_number,
                
                // Masukkan data flatten dari birthRecord agar formatnya sama dengan Show.vue
                'baby_name' => $skl->birthRecord->baby_name,
                'medical_record_no' => $skl->birthRecord->medical_record_no,
                'gender' => $skl->birthRecord->gender,
                'child_order' => $skl->birthRecord->child_order,
                'delivery_method' => $skl->birthRecord->delivery_method,
                'baby_blood_type' => $skl->birthRecord->baby_blood_type,
                'weight' => $skl->birthRecord->weight,
                'length' => $skl->birthRecord->length,
                'head_circ' => $skl->birthRecord->head_circ,
                'chest_circ' => $skl->birthRecord->chest_circ,
                
                'birth_date' => $skl->birthRecord->birth_date->format('Y-m-d'),
                'birth_time' => $skl->birthRecord->birth_time,

                'mother_name' => $skl->birthRecord->mother_name,
                'mother_medical_record_no' => $skl->birthRecord->mother_medical_record_no,
                'mother_ktp' => $skl->birthRecord->mother_ktp,
                'mother_address' => $skl->birthRecord->mother_address,
                'mother_occupation' => $skl->birthRecord->mother_occupation,
                'mother_blood_type' => $skl->birthRecord->mother_blood_type,

                'father_name' => $skl->birthRecord->father_name,
                'father_ktp' => $skl->birthRecord->father_ktp,
                'father_address' => $skl->birthRecord->father_address,
                'father_occupation' => $skl->birthRecord->father_occupation,
                'father_blood_type' => $skl->birthRecord->father_blood_type,

                'doctor' => $skl->birthRecord->doctor,
            ]
        ]);
    }
}

