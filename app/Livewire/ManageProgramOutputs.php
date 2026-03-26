<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\ProgramOutput;
use App\Models\ProgramActivity;
use App\Models\ExtensionProgram;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;

class ManageProgramOutputs extends Component
{
    use WithPagination;

    public ?int $program_id = null;
    public ?ExtensionProgram $program = null;
    public array $activities = [];
    public array $beneficiaries = [];

    // Output Form Properties
    #[Validate('required|string|max:255')]
    public string $output_title = '';

    #[Validate('required|in:training,materials,services,mentoring,assessment,other')]
    public string $output_type = 'training';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    #[Validate('required|date')]
    public string $output_date = '';

    #[Validate('nullable|date_format:H:i')]
    public string $start_time = '';

    #[Validate('nullable|date_format:H:i')]
    public string $end_time = '';

    #[Validate('required|integer|min:0')]
    public int $quantity = 0;

    #[Validate('nullable|string|max:100')]
    public string $unit = '';

    #[Validate('required|integer|min:0')]
    public int $beneficiaries_reached = 0;

    #[Validate('nullable|array')]
    public array $selected_beneficiary_ids = [];

    #[Validate('nullable|string|max:2000')]
    public string $outcomes = '';

    #[Validate('nullable|string|max:1000')]
    public string $notes = '';

    #[Validate('required|in:planned,in_progress,completed,cancelled')]
    public string $status = 'completed';

    #[Validate('nullable|integer')]
    public ?int $activity_id = null;

    // UI State
    public bool $showOutputForm = false;
    public bool $showSuccessModal = false;
    public bool $showErrorModal = false;
    public string $successMessage = '';
    public string $errorMessage = '';
    public ?int $editingOutputId = null;
    public string $searchTerm = '';
    public string $filterStatus = '';
    public string $filterType = '';
    public string $pageSize = '10';

    public function mount($program_id = null)
    {
        $this->program_id = $program_id;
        if ($this->program_id) {
            $this->program = ExtensionProgram::findOrFail($this->program_id);
            $this->loadFormData();
        }
    }

    public function loadFormData()
    {
        if (!$this->program) return;

        // Load related activities
        $this->activities = $this->program->activities()
            ->orderBy('activity_date', 'desc')
            ->get(['id', 'activity_name', 'activity_date'])
            ->toArray();

        // Load beneficiaries linked to program
        $this->beneficiaries = Beneficiary::whereIn('id', function ($query) {
            $query->select('beneficiary_id')
                ->from('beneficiary_program')
                ->where('program_id', $this->program->id);
        })
        ->select('id', 'first_name', 'middle_name', 'last_name')
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get()
        ->pluck('full_name', 'id')
        ->toArray();
    }

    public function render()
    {
        $query = ProgramOutput::query();

        if ($this->program_id) {
            $query->where('program_id', $this->program_id);
        }

        if (!empty($this->searchTerm)) {
            $query->where('output_title', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        if (!empty($this->filterType)) {
            $query->where('output_type', $this->filterType);
        }

        $outputs = $query->latest('output_date')->paginate((int)$this->pageSize);

        // Calculate targets from logic model
        $targets = [];
        $logicModel = null;
        if ($this->program) {
            $logicModel = $this->program->logicModel();
            if ($logicModel) {
                $outputs_data = $logicModel->outputs ?? [];
                $targets = [
                    'trainees' => $outputs_data['trainees'] ?? 'Not set',
                    'materials' => $outputs_data['materials'] ?? 'Not set',
                    'services' => $outputs_data['services'] ?? 'Not set',
                ];
            }
        }

        // Calculate KPIs
        $kpis = $this->calculateKPIs($logicModel);

        // Get community baseline data
        $communities = [];
        if ($this->program) {
            $baselines = \App\Models\ProgramBaseline::where('program_id', $this->program_id)->get();
            foreach ($baselines as $baseline) {
                $community = \App\Models\Community::find($baseline->community_id);
                if ($community) {
                    $communityOutputs = ProgramOutput::where('program_id', $this->program_id)
                        ->where('status', 'completed')
                        ->get();
                    
                    $communities[] = [
                        'id' => $community->id,
                        'name' => $community->name,
                        'baseline' => $baseline,
                        'output_count' => $communityOutputs->count(),
                    ];
                }
            }
        }

        return view('livewire.manage-program-outputs', [
            'program' => $this->program,
            'outputs' => $outputs,
            'targets' => $targets,
            'kpis' => $kpis,
            'communities' => $communities,
        ]);
    }

    private function calculateKPIs($logicModel)
    {
        // Get all outputs for this program (not filtering by status initially for accurate counts)
        $allOutputs = ProgramOutput::where('program_id', $this->program_id)->get();

        // Extract numeric values from targets
        $targetBeneficiaries = $this->extractNumber($logicModel?->outputs['trainees'] ?? '0');
        $targetMaterials = $this->extractNumber($logicModel?->outputs['materials'] ?? '0');
        $targetServices = $this->extractNumber($logicModel?->outputs['services'] ?? '0');

        if (!$allOutputs->count()) {
            return [
                'total_outputs_recorded' => 0,
                'total_beneficiaries_reached' => 0,
                'outputs_by_type' => [],
                'target_beneficiaries' => $targetBeneficiaries,
                'target_materials' => $targetMaterials,
                'target_services' => $targetServices,
                'beneficiary_coverage_percent' => 0,
                'materials_coverage_percent' => 0,
                'services_coverage_percent' => 0,
                'overall_target_met_percent' => 0,
            ];
        }

        // Calculate actual values based on output types
        $completedOutputs = $allOutputs->where('status', 'completed');
        
        // Beneficiaries: sum from training, mentoring, and services outputs
        $trainingOutputs = $completedOutputs->whereIn('output_type', ['training', 'mentoring', 'assessment'])->toArray();
        $materialOutputs = $completedOutputs->where('output_type', 'materials')->toArray();
        $serviceOutputs = $completedOutputs->where('output_type', 'services')->toArray();

        // For beneficiaries, use unique beneficiary count or beneficiaries_reached sum
        $totalBeneficiariesReached = collect($trainingOutputs)->sum('beneficiaries_reached');
        if ($totalBeneficiariesReached == 0) {
            // Fallback: count unique beneficiaries reached across all completed outputs
            $totalBeneficiariesReached = $completedOutputs->sum('beneficiaries_reached');
        }

        // For materials: sum quantities from materials type outputs
        $totalMaterialsProduced = collect($materialOutputs)->sum('quantity') ?: 0;

        // For services: sum quantities from services type outputs
        $totalServicesDelivered = collect($serviceOutputs)->sum('quantity') ?: 0;

        // Calculate coverage percentages
        $beneficiaryPercent = $targetBeneficiaries > 0 
            ? min(100, round(($totalBeneficiariesReached / $targetBeneficiaries) * 100, 1))
            : 0;
        $materialsPercent = $targetMaterials > 0 
            ? min(100, round(($totalMaterialsProduced / $targetMaterials) * 100, 1))
            : 0;
        $servicesPercent = $targetServices > 0 
            ? min(100, round(($totalServicesDelivered / $targetServices) * 100, 1))
            : 0;

        // Calculate overall target met
        $targetsSet = 0;
        $percentageSum = 0;
        if ($targetBeneficiaries > 0) {
            $targetsSet++;
            $percentageSum += $beneficiaryPercent;
        }
        if ($targetMaterials > 0) {
            $targetsSet++;
            $percentageSum += $materialsPercent;
        }
        if ($targetServices > 0) {
            $targetsSet++;
            $percentageSum += $servicesPercent;
        }

        $overallPercent = $targetsSet > 0 ? round($percentageSum / $targetsSet, 1) : 0;

        // Output breakdown by type
        $outputsByType = [];
        foreach (['training', 'materials', 'services', 'mentoring', 'assessment', 'other'] as $type) {
            $count = $allOutputs->where('output_type', $type)->count();
            if ($count > 0) {
                $outputsByType[$type] = $count;
            }
        }

        return [
            'total_outputs_recorded' => $allOutputs->count(),
            'total_beneficiaries_reached' => $totalBeneficiariesReached,
            'outputs_by_type' => $outputsByType,
            'target_beneficiaries' => $targetBeneficiaries,
            'target_materials' => $targetMaterials,
            'target_services' => $targetServices,
            'actual_beneficiaries' => $totalBeneficiariesReached,
            'actual_materials' => $totalMaterialsProduced,
            'actual_services' => $totalServicesDelivered,
            'beneficiary_coverage_percent' => $beneficiaryPercent,
            'materials_coverage_percent' => $materialsPercent,
            'services_coverage_percent' => $servicesPercent,
            'overall_target_met_percent' => $overallPercent,
        ];
    }

    private function extractNumber($value)
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        // Extract first number from string like "200 farmers trained"
        preg_match('/\d+/', $value, $matches);
        return isset($matches[0]) ? (int)$matches[0] : 0;
    }

    public function openOutputForm()
    {
        $this->resetOutputForm();
        $this->showOutputForm = true;
        $this->editingOutputId = null;
    }

    public function closeOutputForm()
    {
        $this->showOutputForm = false;
        $this->resetOutputForm();
    }

    public function resetOutputForm()
    {
        $this->output_title = '';
        $this->output_type = 'training';
        $this->description = '';
        $this->output_date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->quantity = 0;
        $this->unit = '';
        $this->beneficiaries_reached = 0;
        $this->selected_beneficiary_ids = [];
        $this->outcomes = '';
        $this->notes = '';
        $this->status = 'completed';
        $this->activity_id = null;
    }

    public function editOutput($id)
    {
        try {
            $output = ProgramOutput::findOrFail($id);
            $this->editingOutputId = $id;
            $this->output_title = $output->output_title;
            $this->output_type = $output->output_type;
            $this->description = $output->description ?? '';
            $this->output_date = $output->output_date->toDateString();
            $this->start_time = $output->start_time ?? '';
            $this->end_time = $output->end_time ?? '';
            $this->quantity = $output->quantity;
            $this->unit = $output->unit ?? '';
            $this->beneficiaries_reached = $output->beneficiaries_reached;
            $this->selected_beneficiary_ids = $output->beneficiary_ids ?? [];
            $this->outcomes = $output->outcomes ?? '';
            $this->notes = $output->notes ?? '';
            $this->status = $output->status;
            $this->activity_id = $output->activity_id;
            $this->showOutputForm = true;
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to load output: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function saveOutput()
    {
        try {
            $this->validate();

            $data = [
                'program_id' => $this->program_id,
                'activity_id' => $this->activity_id,
                'output_type' => $this->output_type,
                'output_title' => $this->output_title,
                'description' => $this->description,
                'output_date' => $this->output_date,
                'start_time' => !empty($this->start_time) ? $this->start_time : null,
                'end_time' => !empty($this->end_time) ? $this->end_time : null,
                'quantity' => $this->quantity,
                'unit' => $this->unit,
                'beneficiaries_reached' => $this->beneficiaries_reached,
                'beneficiary_ids' => !empty($this->selected_beneficiary_ids) ? $this->selected_beneficiary_ids : null,
                'outcomes' => $this->outcomes,
                'notes' => $this->notes,
                'status' => $this->status,
                'updated_by' => Auth::id(),
            ];

            if ($this->editingOutputId) {
                $output = ProgramOutput::findOrFail($this->editingOutputId);
                $output->update($data);
                $this->successMessage = 'Output updated successfully!';
            } else {
                $data['created_by'] = Auth::id();
                ProgramOutput::create($data);
                $this->successMessage = 'Output recorded successfully!';
            }

            $this->showSuccessModal = true;
            $this->closeOutputForm();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function deleteOutput($id)
    {
        try {
            $output = ProgramOutput::findOrFail($id);
            $output->delete();
            $this->successMessage = 'Output deleted successfully!';
            $this->showSuccessModal = true;
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to delete output: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
    }
}
