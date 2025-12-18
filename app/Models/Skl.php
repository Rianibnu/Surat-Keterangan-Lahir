<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Skl extends Model
{
    use HasUuids, LogsActivity;

    protected $guarded = ['id'];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected $casts = [
        'issue_date' => 'date',
        'is_signed' => 'boolean',
    ];

    /**
     * Get the activity log options for this model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "SKL ({$this->document_number}) telah di-{$eventName}");
    }

    public function birthRecord()
    {
        return $this->belongsTo(BirthRecord::class);
    }
}
