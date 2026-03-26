<?php

namespace App\Livewire;

use App\Models\ExtensionProgram;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ManageExtensionProgramsDetail extends Component
{
    #[Validate('required|string|max:255')]
    public $title;

    #[Validate('required|string|max:1000')]
    public $description;

    #[Validate('required|in:active,inactive,planning,completed')]
    public $status = 'active';

    public $related_communities = [];
    public $cover_image;
    public $program_id;
    public $editingId;
    public $communities = [];
    public $selected_beneficiary_categories = [];

    public $showDeleteModal = false;
    public $showSuccessModal = false;
    public $notification = '';

    // Modal states
    public $showEditDetailsModal = false;
    public $showEditTimelineModal = false;
    public $showEditCategoriesModal = false;
    public $showEditCommunitiesModal = false;
    public $showEditProgramPlanModal = false;

    // Timeline fields
    public $planned_start_date;
    public $planned_end_date;

    // Program Plan fields
    public $program_objectives = '';
    public $program_activities = '';
    public $expected_outcomes = '';
    public $evaluation_methods = '';

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

    public function mount($program_id)
    {
        $this->program_id = $program_id;
        $program = ExtensionProgram::find($program_id);
        
        if ($program) {
            $this->title = $program->title;
            $this->description = $program->description;
            $this->status = $program->status;
            $this->planned_start_date = $program->planned_start_date;
            $this->planned_end_date = $program->planned_end_date;
            $this->related_communities = is_array($program->related_communities) 
                ? $program->related_communities 
                : json_decode($program->related_communities, true) ?? [];
            $this->selected_beneficiary_categories = is_array($program->beneficiary_categories)
                ? $program->beneficiary_categories
                : json_decode($program->beneficiary_categories, true) ?? [];
            $this->program_objectives = $program->program_objectives ?? '';
            $this->program_activities = $program->program_activities ?? '';
            $this->expected_outcomes = $program->expected_outcomes ?? '';
            $this->evaluation_methods = $program->evaluation_methods ?? '';
            $this->editingId = $program_id;
        }

        // Load communities for dropdown
        $this->communities = \App\Models\Community::pluck('name', 'id')->toArray();
    }

    public function toggleBeneficiaryCategory($key)
    {
        if (in_array($key, $this->selected_beneficiary_categories)) {
            $this->selected_beneficiary_categories = array_diff($this->selected_beneficiary_categories, [$key]);
        } else {
            $this->selected_beneficiary_categories[] = $key;
        }
    }

    public function toggleCommunity($id)
    {
        if (in_array($id, $this->related_communities)) {
            $this->related_communities = array_diff($this->related_communities, [$id]);
        } else {
            $this->related_communities[] = $id;
        }
    }

    public function openEditDetailsModal()
    {
        $this->showEditDetailsModal = true;
    }

    public function openEditTimelineModal()
    {
        $this->showEditTimelineModal = true;
    }

    public function openEditCategoriesModal()
    {
        $this->showEditCategoriesModal = true;
    }

    public function openEditCommunitiesModal()
    {
        $this->showEditCommunitiesModal = true;
    }

    public function openEditProgramPlanModal()
    {
        $this->showEditProgramPlanModal = true;
    }

    public function updateProgram()
    {
        $this->validate();

        try {
            $program = ExtensionProgram::find($this->editingId);
            
            if ($program) {
                $program->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'status' => $this->status,
                    'planned_start_date' => $this->planned_start_date,
                    'planned_end_date' => $this->planned_end_date,
                    'related_communities' => $this->related_communities,
                    'beneficiary_categories' => $this->selected_beneficiary_categories,
                ]);

                // Close all modals
                $this->showEditDetailsModal = false;
                $this->showEditCategoriesModal = false;
                $this->showEditCommunitiesModal = false;
                $this->showEditTimelineModal = false;
                $this->showEditProgramPlanModal = false;

                $this->notification = 'Program updated successfully!';
                $this->showSuccessModal = true;
            }
        } catch (\Exception $e) {
            $this->notification = 'Error updating program: ' . $e->getMessage();
        }
    }

    public function updateProgramPlan()
    {
        try {
            $program = ExtensionProgram::find($this->editingId);
            
            if ($program) {
                $program->update([
                    'program_objectives' => $this->program_objectives,
                    'program_activities' => $this->program_activities,
                    'expected_outcomes' => $this->expected_outcomes,
                    'evaluation_methods' => $this->evaluation_methods,
                ]);

                // Close modal
                $this->showEditProgramPlanModal = false;

                $this->notification = 'Program Plan updated successfully!';
                $this->showSuccessModal = true;
            }
        } catch (\Exception $e) {
            $this->notification = 'Error updating program plan: ' . $e->getMessage();
        }
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function deleteProgram()
    {
        try {
            ExtensionProgram::find($this->editingId)->delete();
            $this->notification = 'Program deleted successfully!';
            $this->showSuccessModal = true;
            $this->showDeleteModal = false;
            
            // Dispatch a browser event to redirect after modal is shown
            $this->dispatch('program-deleted');
        } catch (\Exception $e) {
            $this->notification = 'Error deleting program: ' . $e->getMessage();
            $this->showSuccessModal = true;
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }

    public function handleSuccessModalClose()
    {
        // Check if it's a deletion message
        if (str_contains($this->notification, 'deleted successfully')) {
            // Close the modal first
            $this->showSuccessModal = false;
            // Then redirect to extension programs page
            $this->redirect(route('secretary.extension-programs'), navigate: true);
        } else {
            // For other success messages, just close the modal
            $this->showSuccessModal = false;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.manage-extension-programs-detail', [
            'communities' => $this->communities,
        ]);
    }
}
