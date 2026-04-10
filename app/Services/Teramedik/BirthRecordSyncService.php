<?php

namespace App\Services\Teramedik;

use App\Models\BirthRecord;

class BirthRecordSyncService
{
    public function __construct(
        protected TeramedikClient $client
    ) {}

    /**
     * Push birth record to Teramedik SIMRS.
     * This creates a new patient record for the baby in Teramedik.
     */
    public function pushToTeramedik(BirthRecord $birthRecord): array
    {
        $endpoint = config('teramedik.endpoints.birth.create');

        // Map local data to Teramedik format
        $data = $this->mapToTeramedikFormat($birthRecord);

        $response = $this->client->post($endpoint, [], $data);

        // If successful, save the Teramedik ID to local record
        if (!empty($response['data']['id'])) {
            \DB::table('teramedik_sync_records')->updateOrInsert(
                [
                    'local_table' => 'birth_records',
                    'local_id' => $birthRecord->id,
                ],
                [
                    'teramedik_id' => $response['data']['id'],
                    'teramedik_rm' => $response['data']['no_rm_bayi'] ?? null,
                    'sync_status' => 'synced',
                    'last_synced_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return $response;
    }

    /**
     * Map local BirthRecord to Teramedik API format.
     */
    protected function mapToTeramedikFormat(BirthRecord $birthRecord): array
    {
        $mapping = config('teramedik.field_mapping.patient');

        return [
            // Baby Data
            'nama_bayi' => $birthRecord->baby_name,
            'tgl_lahir' => $birthRecord->birth_date->format('Y-m-d'),
            'jam_lahir' => $birthRecord->birth_time,
            'jenis_kelamin' => $birthRecord->gender === 'Laki-laki' ? 'L' : 'P',
            'gol_darah' => $birthRecord->baby_blood_type,
            'berat_lahir' => $birthRecord->weight,
            'panjang_lahir' => $birthRecord->length,
            'lingkar_kepala' => $birthRecord->head_circ,
            'lingkar_dada' => $birthRecord->chest_circ,
            'anak_ke' => $birthRecord->child_order,
            'cara_persalinan' => $birthRecord->delivery_method,

            // Mother Reference
            'no_rm_ibu' => $birthRecord->mother_medical_record_no,
            'nama_ibu' => $birthRecord->mother_name,
            'nik_ibu' => $birthRecord->mother_ktp,

            // Father Reference
            'nama_ayah' => $birthRecord->father_name,
            'nik_ayah' => $birthRecord->father_ktp,

            // Doctor Reference
            'id_dokter' => $this->getTeramedikDoctorId($birthRecord->doctor_id),

            // SKL Reference (if exists)
            'no_skl' => $birthRecord->skl?->document_number,

            // Metadata
            'rumah_sakit' => $birthRecord->hospital_name,
            'created_by' => auth()->user()?->name,
        ];
    }

    /**
     * Get Teramedik doctor ID from local doctor.
     */
    protected function getTeramedikDoctorId(int $localDoctorId): ?string
    {
        $mapping = \DB::table('teramedik_sync_records')
            ->where('local_table', 'doctors')
            ->where('local_id', $localDoctorId)
            ->first();

        return $mapping?->teramedik_id;
    }

    /**
     * Get sync status for a birth record.
     */
    public function getSyncStatus(BirthRecord $birthRecord): array
    {
        $record = \DB::table('teramedik_sync_records')
            ->where('local_table', 'birth_records')
            ->where('local_id', $birthRecord->id)
            ->first();

        if (!$record) {
            return [
                'synced' => false,
                'teramedik_id' => null,
                'teramedik_rm' => null,
                'last_synced_at' => null,
            ];
        }

        return [
            'synced' => $record->sync_status === 'synced',
            'teramedik_id' => $record->teramedik_id,
            'teramedik_rm' => $record->teramedik_rm,
            'last_synced_at' => $record->last_synced_at,
            'status' => $record->sync_status,
        ];
    }

    /**
     * Get all pending (unsynced) birth records.
     */
    public function getPendingRecords(): array
    {
        $syncedIds = \DB::table('teramedik_sync_records')
            ->where('local_table', 'birth_records')
            ->where('sync_status', 'synced')
            ->pluck('local_id');

        return BirthRecord::whereNotIn('id', $syncedIds)
            ->with(['doctor', 'skl'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }
}
