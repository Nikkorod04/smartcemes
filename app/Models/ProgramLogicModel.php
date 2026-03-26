<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramLogicModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'program_logic_models';

    protected $fillable = [
        'program_id',
        'inputs',
        'activities',
        'outputs',
        'outcomes',
        'impacts',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'inputs' => 'array',
        'activities' => 'array',
        'outputs' => 'array',
        'outcomes' => 'array',
        'impacts' => 'array',
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
            'active' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'archived' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getInputsDisplayAttribute()
    {
        if (!$this->inputs) return null;
        return is_array($this->inputs) ? $this->inputs : json_decode($this->inputs, true);
    }

    public function getActivitiesDisplayAttribute()
    {
        if (!$this->activities) return null;
        return is_array($this->activities) ? $this->activities : json_decode($this->activities, true);
    }

    public function getOutputsDisplayAttribute()
    {
        if (!$this->outputs) return null;
        return is_array($this->outputs) ? $this->outputs : json_decode($this->outputs, true);
    }

    public function getOutcomesDisplayAttribute()
    {
        if (!$this->outcomes) return null;
        return is_array($this->outcomes) ? $this->outcomes : json_decode($this->outcomes, true);
    }

    public function getImpactsDisplayAttribute()
    {
        if (!$this->impacts) return null;
        return is_array($this->impacts) ? $this->impacts : json_decode($this->impacts, true);
    }
}
