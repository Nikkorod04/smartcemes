<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ExtensionProgram;
use App\Models\Community;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ManageExtensionPrograms extends Component
{
    use WithPagination, WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|max:2000')]
    public string $description = '';

    #[Validate('nullable|image|max:5120')]
    public $cover_image = null;

    #[Validate('required|in:active,inactive,planning,completed')]
    public string $status = 'active';

    #[Validate('nullable|string|max:1000')]
    public string $notes = '';

    #[Validate('nullable|date')]
    public string $planned_start_date = '';

    #[Validate('nullable|date|after_or_equal:planned_start_date')]
    public string $planned_end_date = '';

    #[Validate('nullable|integer|min:0')]
    public int $target_beneficiaries = 0;

    public array $beneficiary_categories = [];
    public array $selected_beneficiary_categories = [];

    #[Validate('nullable|string|max:255')]
    public ?string $program_lead_id = null;

    public array $related_communities = [];
    public array $existing_communities = [];
    
    // Beneficiary category options
    public array $beneficiary_category_options = [
        'women' => 'Women',
        'youth' => 'Youth',
        'farmers' => 'Farmers',
        'indigenous' => 'Indigenous Peoples',
        'pwd' => 'Persons with Disabilities',
        'seniors' => 'Senior Citizens',
        'students' => 'Students',
        'general' => 'General Community',
    ];
    
    public string $searchTerm = '';
    public string $filterStatus = '';
    public ?int $editingId = null;
    public string $pageSize = '12';
    
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public bool $showSuccessModal = false;
    public bool $showProgramPlanModal = false;
    public bool $showStatusCheckModal = false;
    public bool $showActivitiesModal = false;
    public ?int $deleteId = null;
    public ?int $selectedProgramId = null;
    public string $notification = '';
    public string $notificationType = '';

    public function mount()
    {
        // Allow access for admin and secretary roles
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'secretary'])) {
            throw new AuthorizationException('Unauthorized access to extension programs.');
        }

        $this->existing_communities = Community::where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function render()
    {
        $query = ExtensionProgram::query();

        if (!empty($this->searchTerm)) {
            $query->where('title', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $programs = $query->latest('created_at')
            ->paginate((int)$this->pageSize);

        // Ensure related_communities is always an array
        $programs->getCollection()->transform(function ($program) {
            if (is_string($program->related_communities)) {
                $program->related_communities = json_decode($program->related_communities, true) ?? [];
            }
            return $program;
        });

        return view('livewire.manage-extension-programs', [
            'programs' => $programs,
            'communities' => $this->existing_communities,
        ]);
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openProgramPlanModal($id)
    {
        $this->selectedProgramId = $id;
        $this->showProgramPlanModal = true;
    }

    #[On('closeProgramPlanModal')]
    public function closeProgramPlanModal()
    {
        $this->showProgramPlanModal = false;
        $this->selectedProgramId = null;
    }

    public function openStatusCheckModal($id)
    {
        $this->selectedProgramId = $id;
        $this->showStatusCheckModal = true;
    }

    public function closeStatusCheckModal()
    {
        $this->showStatusCheckModal = false;
        $this->selectedProgramId = null;
    }

    public function openActivitiesModal($id)
    {
        $this->selectedProgramId = $id;
        $this->showActivitiesModal = true;
    }

    public function closeActivitiesModal()
    {
        $this->showActivitiesModal = false;
        $this->selectedProgramId = null;
    }

    public function createProgram()
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'planned_start_date' => !empty($this->planned_start_date) ? $this->planned_start_date : null,
                'planned_end_date' => !empty($this->planned_end_date) ? $this->planned_end_date : null,
                'target_beneficiaries' => !empty($this->target_beneficiaries) ? $this->target_beneficiaries : null,
                'beneficiary_categories' => !empty($this->selected_beneficiary_categories) ? $this->selected_beneficiary_categories : null,
                'program_lead_id' => $this->program_lead_id,
                'related_communities' => !empty($this->related_communities) ? array_values($this->related_communities) : null,
                'status' => $this->status,
                'notes' => $this->notes,
                'created_by' => Auth::id(),
            ];

            if ($this->cover_image) {
                $data['cover_image'] = $this->cover_image->store('programs/covers', 'public');
            }

            ExtensionProgram::create($data);

            $this->notification = 'Extension Program created successfully!';
            $this->notificationType = 'success';
            $this->showSuccessModal = true;
            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            $this->notification = 'Error creating program: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    public function editProgram($id)
    {
        $program = ExtensionProgram::findOrFail($id);
        
        $this->editingId = $id;
        $this->title = $program->title;
        $this->description = $program->description;
        
        // Timeline
        $this->planned_start_date = $program->planned_start_date ? $program->planned_start_date->format('Y-m-d') : '';
        $this->planned_end_date = $program->planned_end_date ? $program->planned_end_date->format('Y-m-d') : '';
        
        // Beneficiaries
        $this->target_beneficiaries = $program->target_beneficiaries ?? 0;
        $this->selected_beneficiary_categories = is_array($program->beneficiary_categories) ? $program->beneficiary_categories : [];
        $this->program_lead_id = $program->program_lead_id;
        
        // Handle related_communities - ensure it's an array
        $communities = $program->related_communities;
        if (is_string($communities)) {
            $this->related_communities = json_decode($communities, true) ?? [];
        } else {
            $this->related_communities = is_array($communities) ? $communities : [];
        }
        
        $this->status = $program->status;
        $this->notes = $program->notes ?? '';
        
        $this->showForm = true;
    }

    public function updateProgram()
    {
        if (!$this->editingId) {
            return;
        }

        $this->validate([
            'title' => 'required|string|max:255|unique:extension_programs,title,' . $this->editingId,
            'description' => 'required|string|max:2000',
            'cover_image' => 'nullable|image|max:5120',
            'status' => 'required|in:active,inactive,planning,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $program = ExtensionProgram::findOrFail($this->editingId);
            
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'planned_start_date' => !empty($this->planned_start_date) ? $this->planned_start_date : null,
                'planned_end_date' => !empty($this->planned_end_date) ? $this->planned_end_date : null,
                'target_beneficiaries' => !empty($this->target_beneficiaries) ? $this->target_beneficiaries : null,
                'beneficiary_categories' => !empty($this->selected_beneficiary_categories) ? $this->selected_beneficiary_categories : null,
                'program_lead_id' => $this->program_lead_id,
                'related_communities' => !empty($this->related_communities) ? array_values($this->related_communities) : null,
                'status' => $this->status,
                'notes' => $this->notes,
                'updated_by' => Auth::id(),
            ];

            if ($this->cover_image) {
                $data['cover_image'] = $this->cover_image->store('programs/covers', 'public');
            }

            $program->update($data);

            $this->notification = 'Extension Program updated successfully!';
            $this->notificationType = 'success';
            $this->showSuccessModal = true;
            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            $this->notification = 'Error updating program: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    public function save()
    {
        if ($this->editingId) {
            $this->updateProgram();
        } else {
            $this->createProgram();
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProgram()
    {
        if (!$this->deleteId) {
            return;
        }

        try {
            $program = ExtensionProgram::findOrFail($this->deleteId);
            $program->delete();

            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->showSuccessModal = true;
        } catch (\Exception $e) {
            $this->notification = 'Error deleting program: ' . $e->getMessage();
            $this->notificationType = 'error';
            $this->showDeleteModal = false;
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }

    public function closePage()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'title',
            'description',
            'planned_start_date',
            'planned_end_date',
            'target_beneficiaries',
            'selected_beneficiary_categories',
            'program_lead_id',
            'cover_image',
            'related_communities',
            'status',
            'notes',
            'editingId',
        ]);
        $this->status = 'active';
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterStatus = '';
    }

    public function toggleCommunity($communityId)
    {
        if (in_array($communityId, $this->related_communities)) {
            $this->related_communities = array_filter($this->related_communities, fn($id) => $id !== $communityId);
        } else {
            $this->related_communities[] = $communityId;
        }
    }

    public function addPartner()
    {
        if (!empty($this->new_partner) && !in_array($this->new_partner, $this->partners)) {
            $this->partners[] = $this->new_partner;
            $this->new_partner = '';
        }
    }

    public function removePartner($index)
    {
        unset($this->partners[$index]);
        $this->partners = array_values($this->partners);
    }

    public function toggleBeneficiaryCategory($category)
    {
        if (in_array($category, $this->selected_beneficiary_categories)) {
            $this->selected_beneficiary_categories = array_filter(
                $this->selected_beneficiary_categories,
                fn($cat) => $cat !== $category
            );
        } else {
            $this->selected_beneficiary_categories[] = $category;
        }
    }
}
