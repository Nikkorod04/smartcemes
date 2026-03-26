<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityFacilitator extends Model
{
    protected $fillable = [
        'program_activity_id',
        'facilitator_name',
        'facilitator_role',
        'contact_info',
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

    // Scopes
    public function scopeInstructor($query)
    {
        return $query->where('facilitator_role', 'instructor');
    }

    public function scopeAssistant($query)
    {
        return $query->where('facilitator_role', 'assistant');
    }

    public function scopeCoordinator($query)
    {
        return $query->where('facilitator_role', 'coordinator');
    }
}
