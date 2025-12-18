<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\BirthRecord;

class Doctor extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    /**
     * Get the activity log options for this model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Dokter ({$this->name}) telah di-{$eventName}");
    }

    public function birthRecords()
    {
        return $this->hasMany(BirthRecord::class);
    }
}
