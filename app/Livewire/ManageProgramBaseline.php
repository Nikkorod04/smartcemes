<?php

namespace App\Livewire;

use App\Models\ProgramBaseline;
use App\Models\ExtensionProgram;
use App\Models\Community;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class ManageProgramBaseline extends Component
{
    public $program_id;
    public $program;
    public $baselines = [];
    public $communities = [];
    public $editingId = null;
    public $showModal = false;

    // Form Fields
    #[Validate('required|numeric')]
    public $community_id = '';

    #[Validate('required|date')]
    public $baseline_assessment_date = '';

    #[Validate('nullable|integer')]
    public $target_beneficiaries_count = '';

    #[Validate('nullable|integer|min:1|max:5')]
    public $target_literacy_level = '';

    #[Validate('nullable|numeric')]
    public $target_average_income = '';

    #[Validate('nullable|array')]
    public $target_skills = [];

    public $new_skill = '';

    #[Validate('nullable|string')]
    public $existing_capacities = '';

    #[Validate('nullable|string')]
    public $existing_challenges = '';

    #[Validate('nullable|string')]
    public $partner_info = '';

    #[Validate('nullable|string')]
    public $assessment_methodology = '';

    #[Validate('nullable|string')]
    public $key_findings = '';

    #[Validate('nullable|string')]
    public $notes = '';

    #[Validate('required|in:draft,approved,completed,archived')]
    public $status = 'draft';

    public function mount($program_id)
    {
        $this->program_id = $program_id;
        $this->program = ExtensionProgram::findOrFail($program_id);
        $this->baselines = ProgramBaseline::where('program_id', $program_id)->get();
        $this->communities = Community::all();
    }

    public function resetForm()
    {
        $this->reset([
            'editingId',
            'community_id',
            'baseline_assessment_date',
            'target_beneficiaries_count',
            'target_literacy_level',
            'target_average_income',
            'target_skills',
            'new_skill',
            'existing_capacities',
            'existing_challenges',
            'partner_info',
            'assessment_methodology',
            'key_findings',
            'notes',
            'status',
        ]);
        $this->showModal = false;
    }

    public function edit($id)
    {
        $baseline = ProgramBaseline::findOrFail($id);
        $this->editingId = $baseline->id;
        $this->community_id = $baseline->community_id;
        $this->baseline_assessment_date = $baseline->baseline_assessment_date?->format('Y-m-d');
        $this->target_beneficiaries_count = $baseline->target_beneficiaries_count;
        $this->target_literacy_level = $baseline->target_literacy_level;
        $this->target_average_income = $baseline->target_average_income;
        $this->target_skills = $baseline->target_skills ?? [];
        $this->existing_capacities = $baseline->existing_capacities;
        $this->existing_challenges = $baseline->existing_challenges;
        $this->partner_info = $baseline->partner_info;
        $this->assessment_methodology = $baseline->assessment_methodology;
        $this->key_findings = $baseline->key_findings;
        $this->notes = $baseline->notes;
        $this->status = $baseline->status;
        $this->showModal = true;
    }

    public function addSkill()
    {
        if (blank($this->new_skill)) return;

        if (!in_array($this->new_skill, $this->target_skills)) {
            $this->target_skills[] = $this->new_skill;
        }
        $this->new_skill = '';
    }

    public function removeSkill($index)
    {
        unset($this->target_skills[$index]);
        $this->target_skills = array_values($this->target_skills);
    }

    public function save()
    {
        $this->validate();

        // Type conversions for optional fields
        $communityId = !empty($this->community_id) && is_numeric($this->community_id)
            ? (int) $this->community_id
            : null;

        $targetBeneficiaries = !empty($this->target_beneficiaries_count) && is_numeric($this->target_beneficiaries_count)
            ? (int) $this->target_beneficiaries_count
            : null;

        $targetLiteracy = !empty($this->target_literacy_level) && is_numeric($this->target_literacy_level)
            ? (int) $this->target_literacy_level
            : null;

        $targetIncome = !empty($this->target_average_income) && is_numeric($this->target_average_income)
            ? (float) $this->target_average_income
            : null;

        $data = [
            'program_id' => $this->program_id,
            'community_id' => $communityId,
            'baseline_assessment_date' => $this->baseline_assessment_date,
            'target_beneficiaries_count' => $targetBeneficiaries,
            'target_literacy_level' => $targetLiteracy,
            'target_average_income' => $targetIncome,
            'target_skills' => count($this->target_skills) > 0 ? $this->target_skills : null,
            'existing_capacities' => $this->existing_capacities,
            'existing_challenges' => $this->existing_challenges,
            'partner_info' => $this->partner_info,
            'assessment_methodology' => $this->assessment_methodology,
            'key_findings' => $this->key_findings,
            'notes' => $this->notes,
            'status' => $this->status,
            'updated_by' => Auth::id(),
        ];

        if ($this->editingId) {
            $baseline = ProgramBaseline::findOrFail($this->editingId);
            $baseline->update($data);
            $this->dispatch('notify', message: 'Baseline updated successfully');
        } else {
            $data['created_by'] = Auth::id();
            ProgramBaseline::create($data);
            $this->dispatch('notify', message: 'Baseline created successfully');
        }

        $this->baselines = ProgramBaseline::where('program_id', $this->program_id)->get();
        $this->resetForm();
    }

    public function delete($id)
    {
        ProgramBaseline::findOrFail($id)->delete();
        $this->baselines = ProgramBaseline::where('program_id', $this->program_id)->get();
        $this->dispatch('notify', message: 'Baseline deleted successfully');
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.manage-program-baseline', [
            'program' => $this->program,
            'communities' => $this->communities,
        ]);
    }
}
