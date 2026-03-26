<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramOutput extends Model
{
    use SoftDeletes;

    protected $table = 'program_outputs';

    protected $fillable = [
        'program_id',
        'activity_id',
        'output_type',
        'output_title',
        'description',
        'quantity',
        'unit',
        'beneficiaries_reached',
        'beneficiary_ids',
        'output_date',
        'start_time',
        'end_time',
        'outcomes',
        'attachments',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'output_date' => 'date',
        'beneficiary_ids' => 'array',
        'attachments' => 'array',
    ];

    // Relationships
    public function program()
    {
        return $this->belongsTo(ExtensionProgram::class);
    }

    public function activity()
    {
        return $this->belongsTo(ProgramActivity::class);
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(
            Beneficiary::class,
            'program_output_beneficiaries',
            'output_id',
            'beneficiary_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper Methods
    public function getOutputTypeLabel()
    {
        $labels = [
            'training' => 'Training Session',
            'materials' => 'Materials Produced',
            'services' => 'Services Delivered',
            'mentoring' => 'Mentoring Session',
            'assessment' => 'Assessment/Evaluation',
            'other' => 'Other',
        ];
        return $labels[$this->output_type] ?? ucfirst($this->output_type);
    }

    public function getStatusColor()
    {
        $colors = [
            'planned' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }
}
