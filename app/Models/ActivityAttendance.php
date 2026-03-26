<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityAttendance extends Model
{
    protected $table = 'activity_attendance';

    protected $fillable = [
        'program_activity_id',
        'beneficiary_id',
        'attendance_status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ProgramActivity::class, 'program_activity_id');
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('attendance_status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('attendance_status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('attendance_status', 'late');
    }

    public function scopeExcused($query)
    {
        return $query->where('attendance_status', 'excused');
    }
}
