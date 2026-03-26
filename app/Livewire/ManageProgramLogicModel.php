<?php

namespace App\Livewire;

use App\Models\ProgramLogicModel;
use App\Models\ExtensionProgram;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Validation\ValidationException;

class ManageProgramLogicModel extends Component
{
    public $program_id;
    public $program;
    public $logicModel;
    public $editingId = null;

    // Program Overview
    #[Validate('nullable|string|max:2000')]
    public $program_goals = '';

    #[Validate('nullable|string|max:2000')]
    public $program_objectives = '';

    #[Validate('nullable|numeric|min:0')]
    public $allocated_budget = '';

    // Inputs Section
    #[Validate('nullable|string')]
    public $inputs_personnel = '';

    #[Validate('nullable|string')]
    public $inputs_partners = '';

    #[Validate('nullable|string')]
    public $inputs_resources = '';

    // Activities Section
    #[Validate('nullable|array')]
    public $activities = [];  public $new_activity = '';

    // Outputs Section
    #[Validate('nullable|string')]
    public $outputs_trainees = '';

    #[Validate('nullable|string')]
    public $outputs_materials = '';

    #[Validate('nullable|string')]
    public $outputs_services = '';

    // Outcomes Section
    #[Validate('nullable|array')]
    public $outcomes = [];

    #[Validate('nullable|string')]
    public $new_outcome = '';

    // Impacts Section
    #[Validate('nullable|array')]
    public $impacts = [];

    #[Validate('nullable|string')]
    public $new_impact = '';

    // Metadata
    #[Validate('nullable|string')]
    public $notes = '';

    // Modal States
    public bool $showSuccessModal = false;
    public bool $showErrorModal = false;
    public string $successMessage = '';
    public string $errorMessage = '';

    public function mount($program_id)
    {
        $this->program_id = $program_id;
        $this->program = ExtensionProgram::findOrFail($program_id);
        $this->logicModel = ProgramLogicModel::where('program_id', $program_id)->first();

        if ($this->logicModel) {
            $this->editingId = $this->logicModel->id;
            $this->loadLogicModelData();
        }
    }

    public function loadLogicModelData()
    {
        // Load program overview from program itself
        $this->program_goals = $this->program->goals ?? '';
        $this->program_objectives = $this->program->objectives ?? '';
        $this->allocated_budget = $this->program->allocated_budget ?? '';

        if (!$this->logicModel) return;

        // Load inputs
        $inputs = $this->logicModel->inputs ?? [];
        $this->inputs_personnel = $inputs['personnel'] ?? '';
        $this->inputs_partners = $inputs['partners'] ?? '';
        $this->inputs_resources = $inputs['resources'] ?? '';

        // Load activities
        $this->activities = $this->logicModel->activities ?? [];

        // Load outputs
        $outputs = $this->logicModel->outputs ?? [];
        $this->outputs_trainees = $outputs['trainees'] ?? '';
        $this->outputs_materials = $outputs['materials'] ?? '';
        $this->outputs_services = $outputs['services'] ?? '';

        // Load outcomes & impacts
        $this->outcomes = $this->logicModel->outcomes ?? [];
        $this->impacts = $this->logicModel->impacts ?? [];
        $this->notes = $this->logicModel->notes ?? '';
    }

    public function addActivity()
    {
        if (blank($this->new_activity)) return;

        $this->activities[] = [
            'title' => $this->new_activity,
            'description' => '',
        ];
        $this->new_activity = '';
    }

    public function removeActivity($index)
    {
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
    }

    public function addOutcome()
    {
        if (blank($this->new_outcome)) return;

        $this->outcomes[] = $this->new_outcome;
        $this->new_outcome = '';
    }

    public function removeOutcome($index)
    {
        unset($this->outcomes[$index]);
        $this->outcomes = array_values($this->outcomes);
    }

    public function addImpact()
    {
        if (blank($this->new_impact)) return;

        $this->impacts[] = $this->new_impact;
        $this->new_impact = '';
    }

    public function removeImpact($index)
    {
        unset($this->impacts[$index]);
        $this->impacts = array_values($this->impacts);
    }

    public function save()
    {
        try {
            // First validate - this will throw ValidationException if validation fails
            // ValidationException is handled by Livewire to show field errors
            $validated = $this->validate([
                'program_goals' => 'nullable|string|max:2000',
                'program_objectives' => 'nullable|string|max:2000',
                'allocated_budget' => 'nullable|numeric|min:0',
                'inputs_personnel' => 'nullable|string',
                'inputs_partners' => 'nullable|string',
                'inputs_resources' => 'nullable|string',
                'activities' => 'nullable|array',
                'outputs_trainees' => 'nullable|string',
                'outputs_materials' => 'nullable|string',
                'outputs_services' => 'nullable|string',
                'outcomes' => 'nullable|array',
                'impacts' => 'nullable|array',
                'notes' => 'nullable|string',
            ]);

            $inputs = [

                'personnel' => $this->inputs_personnel,
                'partners' => $this->inputs_partners,
                'resources' => $this->inputs_resources,
            ];

            $outputs = [
                'trainees' => $this->outputs_trainees,
                'materials' => $this->outputs_materials,
                'services' => $this->outputs_services,
            ];

            $data = [
                'program_id' => $this->program_id,
                'inputs' => $inputs,
                'activities' => count($this->activities) > 0 ? $this->activities : null,
                'outputs' => $outputs,
                'outcomes' => count($this->outcomes) > 0 ? $this->outcomes : null,
                'impacts' => count($this->impacts) > 0 ? $this->impacts : null,
                'status' => 'draft',
                'notes' => $this->notes,
                'updated_by' => Auth::id(),
            ];

            // Also update the program with goals, objectives, and budget
            $this->program->update([
                'goals' => $this->program_goals,
                'objectives' => $this->program_objectives,
                'allocated_budget' => !empty($this->allocated_budget) ? $this->allocated_budget : null,
                'partners' => $inputs['partners'] ? array_map('trim', explode(',', $inputs['partners'])) : null,
                'updated_by' => Auth::id(),
            ]);

            if ($this->editingId) {
                $logicModel = ProgramLogicModel::findOrFail($this->editingId);
                $logicModel->update($data);
                $this->successMessage = 'Program Plan updated successfully!';
            } else {
                $data['created_by'] = Auth::id();
                ProgramLogicModel::create($data);
                $this->successMessage = 'Program Plan created successfully!';
                $this->editingId = ProgramLogicModel::where('program_id', $this->program_id)->latest()->first()->id;
            }

            $this->logicModel = ProgramLogicModel::findOrFail($this->editingId);
            $this->loadLogicModelData();
            $this->showSuccessModal = true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation exceptions are handled by Livewire automatically
            // They display field-level errors, no need to show error modal
            throw $e;
        } catch (\Exception $e) {
            // Other exceptions show error modal
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
    }

    public function render()
    {
        return view('livewire.manage-program-logic-model', [
            'program' => $this->program,
            'logicModel' => $this->logicModel,
        ]);
    }
}
