<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Beneficiary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'beneficiaries';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'age',
        'gender',
        'email',
        'phone',
        'address',
        'barangay',
        'municipality',
        'province',
        'community_id',
        'program_ids',
        'beneficiary_category',
        'monthly_income',
        'occupation',
        'educational_attainment',
        'marital_status',
        'number_of_dependents',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'program_ids' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(ExtensionProgram::class, 'beneficiary_program', 'beneficiary_id', 'program_id')
            ->withPivot('enrollment_status', 'enrollment_date', 'completion_date', 'participation_rate', 'notes', 'created_by', 'updated_by')
            ->withTimestamps();
    }

    public function impactRecords(): HasMany
    {
        return $this->hasMany(BeneficiaryImpact::class);
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
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    public function getStatusBadgeAttribute()
    {
        $statusConfig = [
            'active' => ['class' => 'bg-green-100 text-green-800', 'label' => 'Active'],
            'inactive' => ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Inactive'],
            'graduated' => ['class' => 'bg-blue-100 text-blue-800', 'label' => 'Graduated'],
            'dropout' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Dropout'],
        ];

        $config = $statusConfig[$this->status] ?? $statusConfig['inactive'];
        
        return '<span class="inline-block px-3 py-1 rounded text-xs font-semibold ' . $config['class'] . '">' . $config['label'] . '</span>';
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('beneficiary_category', $category);
    }

    public function scopeByProgram($query, $programId)
    {
        return $query->whereJsonContains('program_ids', $programId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
    }

    /**
     * Methods
     */
    public function getLatestImpact()
    {
        return $this->impactRecords()->latest()->first();
    }

    public function getImpactSummary()
    {
        $records = $this->impactRecords;
        if ($records->isEmpty()) {
            return null;
        }

        return [
            'income_change' => $records->last()->post_intervention_income - $records->first()->baseline_income ?? 0,
            'skills_acquired' => $records->pluck('skills_acquired')->flatten()->unique()->count(),
            'behavioral_change' => $records->where('behavioral_change_observed', true)->count(),
            'total_records' => $records->count(),
        ];
    }
}
