<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExtensionProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'extension_programs';

    protected $fillable = [
        'title',
        'description',
        'goals',
        'objectives',
        'planned_start_date',
        'planned_end_date',
        'target_beneficiaries',
        'beneficiary_categories',
        'allocated_budget',
        'program_lead_id',
        'partners',
        'cover_image',
        'gallery_images',
        'activities',
        'related_communities',
        'attachments',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_end_date' => 'date',
        'target_beneficiaries' => 'integer',
        'allocated_budget' => 'decimal:2',
        'beneficiary_categories' => 'array',
        'partners' => 'array',
        'gallery_images' => 'array',
        'related_communities' => 'array',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function logicModel()
    {
        return $this->hasOne(ProgramLogicModel::class, 'program_id');
    }

    public function baselines()
    {
        return $this->hasMany(ProgramBaseline::class, 'program_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProgramActivity::class, 'program_id');
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(ProgramOutput::class, 'program_id');
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_program', 'program_id', 'beneficiary_id')
            ->withPivot('enrollment_status', 'enrollment_date', 'completion_date', 'participation_rate', 'notes', 'created_by', 'updated_by')
            ->withTimestamps();
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days))->latest();
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereRaw("MATCH(title, description, goals, objectives) AGAINST(? IN BOOLEAN MODE)", [$search]);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'planning' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-purple-100 text-purple-800',
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
    public function getCommunities()
    {
        if (empty($this->related_communities)) {
            return collect();
        }
        return Community::whereIn('id', $this->related_communities)->get();
    }

    public function getGalleryCount()
    {
        return count($this->gallery_images ?? []);
    }

    public function getAttachmentCount()
    {
        return count($this->attachments ?? []);
    }
}
