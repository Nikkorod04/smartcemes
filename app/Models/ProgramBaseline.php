<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramBaseline extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'program_baselines';

    protected $fillable = [
        'program_id',
        'community_id',
        'baseline_assessment_date',
        'target_beneficiaries_count',
        'target_literacy_level',
        'target_average_income',
        'target_skills',
        'community_demographics',
        'existing_capacities',
        'existing_challenges',
        'partner_info',
        'available_resources',
        'assessment_methodology',
        'key_findings',
        'data_sources',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'baseline_assessment_date' => 'date',
        'target_skills' => 'array',
        'community_demographics' => 'array',
        'available_resources' => 'array',
        'data_sources' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class, 'program_id');
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'archived' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTargetSkillsDisplayAttribute()
    {
        if (!$this->target_skills) return [];
        return is_array($this->target_skills) ? $this->target_skills : json_decode($this->target_skills, true);
    }

    public function getCommunityDemographicsDisplayAttribute()
    {
        if (!$this->community_demographics) return null;
        return is_array($this->community_demographics) ? $this->community_demographics : json_decode($this->community_demographics, true);
    }
}
