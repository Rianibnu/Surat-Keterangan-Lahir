<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

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

        return Inertia::render('ActivityLog/Show', [
            'activity' => $activity,
        ]);
    }
}
