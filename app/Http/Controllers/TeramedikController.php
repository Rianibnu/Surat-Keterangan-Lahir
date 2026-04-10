<?php

namespace App\Http\Controllers;

use App\Services\Teramedik\TeramedikClient;
use App\Services\Teramedik\PatientService;
use App\Services\Teramedik\DoctorService;
use App\Services\Teramedik\EncounterService;
use App\Services\Teramedik\BirthRecordSyncService;
use App\Models\BirthRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeramedikController extends Controller
{
    public function __construct(
        protected TeramedikClient $client,
        protected PatientService $patientService,
        protected DoctorService $doctorService,
        protected EncounterService $encounterService,
        protected BirthRecordSyncService $birthRecordSyncService,
    ) {}

    /**
     * Check connection to Teramedik API.
     */
    public function healthCheck(): JsonResponse
    {
        $result = $this->client->healthCheck();

        return response()->json([
            'success' => $result['status'] === 'ok',
            'data' => $result,
        ]);
    }

    /**
     * Search patients in Teramedik.
     */
    public function searchPatients(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        try {
            $patients = $this->patientService->search($request->q);

            return response()->json([
                'success' => true,
                'data' => array_map(fn($p) => $p->toArray(), $patients),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari data pasien: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get patient by Medical Record Number.
     */
    public function getPatientByRM(Request $request): JsonResponse
    {
        $request->validate([
            'rm' => 'required|string',
        ]);

        try {
            $patient = $this->patientService->getByMedicalRecordNo($request->rm);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien tidak ditemukan dengan No. RM: ' . $request->rm,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $patient->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pasien: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get patient by NIK.
     */
    public function getPatientByNIK(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        try {
            $patient = $this->patientService->getByNIK($request->nik);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien tidak ditemukan dengan NIK: ' . $request->nik,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $patient->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pasien: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get mother data for BirthRecord form autofill.
     */
    public function getMotherData(Request $request): JsonResponse
    {
        $request->validate([
            'rm' => 'nullable|string',
            'nik' => 'nullable|string|size:16',
        ]);

        if (!$request->rm && !$request->nik) {
            return response()->json([
                'success' => false,
                'message' => 'Harus mengisi No. RM atau NIK',
            ], 422);
        }

        try {
            $patient = $request->rm
                ? $this->patientService->getByMedicalRecordNo($request->rm)
                : $this->patientService->getByNIK($request->nik);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data ibu tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $patient->toMotherFormData(),
                'source' => 'teramedik',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data ibu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get father data for BirthRecord form autofill.
     */
    public function getFatherData(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        try {
            $patient = $this->patientService->getByNIK($request->nik);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data ayah tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $patient->toFatherFormData(),
                'source' => 'teramedik',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data ayah: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all doctors from Teramedik.
     */
    public function getDoctors(): JsonResponse
    {
        try {
            $doctors = $this->doctorService->getAll();

            return response()->json([
                'success' => true,
                'data' => array_map(fn($d) => $d->toArray(), $doctors),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dokter: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync doctors from Teramedik to local database.
     */
    public function syncDoctors(): JsonResponse
    {
        try {
            $result = $this->doctorService->syncToLocal();

            return response()->json([
                'success' => true,
                'message' => "Berhasil sync {$result['total']} dokter ({$result['created']} baru, {$result['updated']} diupdate)",
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data dokter: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get active maternity encounters.
     */
    public function getActiveMaternityEncounters(): JsonResponse
    {
        try {
            $encounters = $this->encounterService->getActiveMaternityEncounters();

            return response()->json([
                'success' => true,
                'data' => array_map(fn($e) => $e->toArray(), $encounters),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data rawat inap: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Push birth record to Teramedik.
     */
    public function pushBirthRecord(BirthRecord $birthRecord): JsonResponse
    {
        try {
            $result = $this->birthRecordSyncService->pushToTeramedik($birthRecord);

            return response()->json([
                'success' => true,
                'message' => 'Data kelahiran berhasil disinkronkan ke Teramedik',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal push data ke Teramedik: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get sync status for a birth record.
     */
    public function getSyncStatus(BirthRecord $birthRecord): JsonResponse
    {
        $status = $this->birthRecordSyncService->getSyncStatus($birthRecord);

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    /**
     * Get pending (unsynced) birth records.
     */
    public function getPendingSync(): JsonResponse
    {
        $pending = $this->birthRecordSyncService->getPendingRecords();

        return response()->json([
            'success' => true,
            'data' => $pending,
            'count' => count($pending),
        ]);
    }
}
