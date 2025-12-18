<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BirthRecord extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the activity log options for this model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data kelahiran ({$this->baby_name}) telah di-{$eventName}");
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function skl()
    {
        return $this->hasOne(Skl::class);
    }
}
