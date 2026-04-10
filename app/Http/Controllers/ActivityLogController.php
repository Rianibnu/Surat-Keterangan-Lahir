<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')
            ->latest();

        // Filter by log name/type
        if ($request->filled('type')) {
            $query->where('log_name', $request->type);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Filter by causer (user)
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        // Filter by source (user or public/anonymous)
        if ($request->filled('source')) {
            if ($request->source === 'user') {
                $query->whereNotNull('causer_id');
            } elseif ($request->source === 'public') {
                $query->whereNull('causer_id');
            }
        }

        $activities = $query->paginate(20)->withQueryString();

        // Get unique log names for filter dropdown
        $logTypes = Activity::distinct()->pluck('log_name')->filter();

        // Get users who have caused activities
        $users = \App\Models\User::select('id', 'name')
            ->whereIn('id', Activity::distinct()->pluck('causer_id')->filter())
            ->get();

        return Inertia::render('ActivityLog/Index', [
            'activities' => $activities,
            'logTypes' => $logTypes,
            'users' => $users,
            'filters' => $request->only(['type', 'from', 'to', 'user_id', 'search', 'source']),
        ]);
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');

        // Check if this is a deleted record that can be restored
        $canRestore = false;
        if ($activity->event === 'deleted' && $activity->subject_type && isset($activity->properties['old'])) {
            $canRestore = true;
        }

        return Inertia::render('ActivityLog/Show', [
            'activity' => $activity,
            'canRestore' => $canRestore,
        ]);
    }

    /**
     * Restore a deleted record from activity log.
     */
    public function restore(Activity $activity)
    {
        // Validate this is a delete event
        if ($activity->event !== 'deleted') {
            return redirect()->back()->with('error', 'Hanya data yang dihapus yang dapat dipulihkan.');
        }

        // Get the old data from properties
        $oldData = $activity->properties['old'] ?? null;
        
        if (!$oldData) {
            return redirect()->back()->with('error', 'Data lama tidak ditemukan di log aktivitas.');
        }

        // Get the model class
        $modelClass = $activity->subject_type;
        
        if (!$modelClass || !class_exists($modelClass)) {
            return redirect()->back()->with('error', 'Model tidak ditemukan: ' . $modelClass);
        }

        try {
            DB::beginTransaction();

            // Remove timestamps and id from old data for recreation
            $dataToRestore = collect($oldData)->except(['id', 'created_at', 'updated_at'])->toArray();
            
            // Handle foreign keys - check if related records still exist
            $foreignKeyErrors = $this->validateForeignKeys($modelClass, $dataToRestore);
            if (!empty($foreignKeyErrors)) {
                return redirect()->back()->with('error', 'Tidak dapat memulihkan: ' . implode(', ', $foreignKeyErrors));
            }

            // Create new record with old data
            $restoredRecord = $modelClass::create($dataToRestore);

            // Log the restoration
            activity()
                ->performedOn($restoredRecord)
                ->causedBy(auth()->user())
                ->withProperties([
                    'restored_from_activity_id' => $activity->id,
                    'attributes' => $restoredRecord->toArray(),
                ])
                ->log('Data dipulihkan dari Activity Log #' . $activity->id);

            DB::commit();

            // Determine redirect URL based on model type
            $redirectUrl = $this->getRedirectUrl($modelClass, $restoredRecord);

            return redirect($redirectUrl)->with('success', 'Data berhasil dipulihkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Validate foreign key constraints before restoring.
     */
    private function validateForeignKeys(string $modelClass, array $data): array
    {
        $errors = [];

        // Check doctor_id for BirthRecord
        if ($modelClass === 'App\Models\BirthRecord' && isset($data['doctor_id'])) {
            $doctorExists = \App\Models\Doctor::find($data['doctor_id']);
            if (!$doctorExists) {
                $errors[] = 'Dokter terkait sudah dihapus (ID: ' . $data['doctor_id'] . ')';
            }
        }

        // Check created_by_user_id
        if (isset($data['created_by_user_id'])) {
            $userExists = \App\Models\User::find($data['created_by_user_id']);
            if (!$userExists) {
                // This is nullable, so we can just remove it
                unset($data['created_by_user_id']);
            }
        }

        return $errors;
    }

    /**
     * Get redirect URL based on restored model type.
     */
    private function getRedirectUrl(string $modelClass, $record): string
    {
        return match ($modelClass) {
            'App\Models\Doctor' => '/doctors',
            'App\Models\BirthRecord' => '/birth-records/' . $record->id,
            'App\Models\User' => '/users',
            'App\Models\Skl' => '/birth-records',
            default => '/activity-logs',
        };
    }
}

