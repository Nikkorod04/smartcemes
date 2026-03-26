<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'communities';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'municipality',
        'province',
        'description',
        'contact_person',
        'contact_number',
        'email',
        'address',
        'status',
        'notes',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(CommunityNeedsAssessment::class);
    }

    public function needsAssessments(): HasMany
    {
        return $this->hasMany(CommunityNeedsAssessment::class);
    }

    public function assessmentSummary()
    {
        return $this->hasOne(CommunityAssessmentSummary::class)->latest('last_calculated_at');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    public function scopeByMunicipality($query, $municipality)
    {
        return $query->where('municipality', $municipality);
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereRaw("MATCH(name, municipality, province) AGAINST(? IN BOOLEAN MODE)", [$search]);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Methods
     */
    public function assessmentCount()
    {
        return $this->assessments()->count();
    }

    public function recentAssessments($limit = 5)
    {
        return $this->assessments()
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->limit($limit)
            ->get();
    }
}
