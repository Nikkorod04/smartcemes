<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeneficiaryImpact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'beneficiary_impacts';

    protected $fillable = [
        'beneficiary_id',
        'program_id',
        'assessment_date',
        'assessment_type',
        'baseline_income',
        'post_intervention_income',
        'income_change_percentage',
        'skills_acquired',
        'skills_level',
        'knowledge_gained',
        'knowledge_level',
        'behavioral_change_observed',
        'behavioral_change_description',
        'health_status_before',
        'health_status_after',
        'confidence_level_before',
        'confidence_level_after',
        'satisfaction_rating',
        'testimonial',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'skills_acquired' => 'array',
        'behavioral_change_observed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class, 'program_id');
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
     * Scopes
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    public function scopeByBeneficiary($query, $beneficiaryId)
    {
        return $query->where('beneficiary_id', $beneficiaryId);
    }

    public function scopeByAssessmentType($query, $type)
    {
        return $query->where('assessment_type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('assessment_date', '>=', now()->subDays($days))->latest();
    }

    /**
     * Accessors
     */
    public function getImpactLevelAttribute()
    {
        $score = 0;
        if ($this->income_change_percentage > 0) $score += 25;
        if ($this->skills_level >= 3) $score += 25;
        if ($this->behavioral_change_observed) $score += 25;
        if ($this->satisfaction_rating >= 4) $score += 25;

        if ($score == 0) return 'No Impact';
        if ($score <= 25) return 'Low';
        if ($score <= 50) return 'Moderate';
        if ($score <= 75) return 'High';
        return 'Very High';
    }

    public function getSatisfactionBadgeAttribute()
    {
        return match($this->satisfaction_rating) {
            1, 2 => 'bg-red-100 text-red-800',
            3 => 'bg-yellow-100 text-yellow-800',
            4, 5 => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Methods
     */
    public function calculateIncomeChange()
    {
        if ($this->baseline_income == 0) {
            return $this->post_intervention_income > 0 ? 100 : 0;
        }
        return round((($this->post_intervention_income - $this->baseline_income) / $this->baseline_income) * 100, 2);
    }
}
