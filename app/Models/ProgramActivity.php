<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramActivity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'program_id',
        'logic_model_id',
        'activity_name',
        'activity_date',
        'start_time',
        'end_time',
        'location',
        'facilitators',
        'target_attendees',
        'description',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'facilitators' => 'array',
    ];

    // Relationships
    public function program(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class);
    }

    public function logicModel(): BelongsTo
    {
        return $this->belongsTo(ProgramLogicModel::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ActivityAttendance::class, 'program_activity_id');
    }

    public function facilitatorRecords(): HasMany
    {
        return $this->hasMany(ActivityFacilitator::class, 'program_activity_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors & Mutators
    public function getAttendanceSummary(): array
    {
        $attendance = $this->attendance()->get();
        $total = $attendance->count();
        $present = $attendance->where('attendance_status', 'present')->count();
        $late = $attendance->where('attendance_status', 'late')->count();
        $absent = $attendance->where('attendance_status', 'absent')->count();
        $excused = $attendance->where('attendance_status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? round((($present + $late) / $total) * 100, 2) : 0,
        ];
    }
}
