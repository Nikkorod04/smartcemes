<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;

class ManageCommunities extends Component
{
    use WithPagination;

    #[Validate('required|string|max:255|unique:communities,name')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $municipality = '';

    #[Validate('required|string|max:255')]
    public string $province = '';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    #[Validate('nullable|string|max:255')]
    public string $contact_person = '';

    #[Validate('nullable|string|max:20')]
    public string $contact_number = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:500')]
    public string $address = '';

    #[Validate('required|in:active,inactive')]
    public string $status = 'active';

    #[Validate('nullable|string|max:1000')]
    public string $notes = '';

    public string $searchTerm = '';
    public string $filterStatus = '';
    public string $filterProvince = '';
    public ?int $editingId = null;
    public string $pageSize = '10';
    
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public bool $showAssessmentModal = false;
    public ?int $deleteId = null;
    public ?int $viewingSummaryId = null;
    public string $notification = '';
    public string $notificationType = '';

    public function render()
    {
        $query = Community::with('assessmentSummary');

        if (!empty($this->searchTerm)) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('municipality', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('province', 'like', '%' . $this->searchTerm . '%');
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        if (!empty($this->filterProvince)) {
            $query->where('province', $this->filterProvince);
        }

        $communities = $query->orderBy('name')
            ->paginate((int)$this->pageSize);

        $viewingCommunity = null;
        if ($this->viewingSummaryId) {
            $viewingCommunity = Community::with('assessmentSummary')->find($this->viewingSummaryId);
        }

        return view('livewire.manage-communities', [
            'communities' => $communities,
            'provinces' => $this->getProvinces(),
            'viewingCommunity' => $viewingCommunity,
        ]);
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function createCommunity()
    {
        $this->validate();

        try {
            Community::create([
                'name' => $this->name,
                'municipality' => $this->municipality,
                'province' => $this->province,
                'description' => $this->description,
                'contact_person' => $this->contact_person,
                'contact_number' => $this->contact_number,
                'email' => $this->email,
                'address' => $this->address,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            $this->notification = 'Community created successfully!';
            $this->notificationType = 'success';
            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            $this->notification = 'Error creating community: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    public function editCommunity($id)
    {
        $community = Community::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $community->name;
        $this->municipality = $community->municipality;
        $this->province = $community->province;
        $this->description = $community->description ?? '';
        $this->contact_person = $community->contact_person ?? '';
        $this->contact_number = $community->contact_number ?? '';
        $this->email = $community->email ?? '';
        $this->address = $community->address ?? '';
        $this->status = $community->status;
        $this->notes = $community->notes ?? '';
        
        $this->showForm = true;
    }

    public function updateCommunity()
    {
        if (!$this->editingId) {
            return;
        }

        // Customize validation to allow current name
        $this->validate([
            'name' => 'required|string|max:255|unique:communities,name,' . $this->editingId,
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $community = Community::findOrFail($this->editingId);
            
            $community->update([
                'name' => $this->name,
                'municipality' => $this->municipality,
                'province' => $this->province,
                'description' => $this->description,
                'contact_person' => $this->contact_person,
                'contact_number' => $this->contact_number,
                'email' => $this->email,
                'address' => $this->address,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            $this->notification = 'Community updated successfully!';
            $this->notificationType = 'success';
            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            $this->notification = 'Error updating community: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    public function save()
    {
        if ($this->editingId) {
            $this->updateCommunity();
        } else {
            $this->createCommunity();
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCommunity()
    {
        if (!$this->deleteId) {
            return;
        }

        try {
            $community = Community::findOrFail($this->deleteId);
            $community->delete();

            $this->notification = 'Community deleted successfully!';
            $this->notificationType = 'success';
            $this->showDeleteModal = false;
            $this->deleteId = null;
        } catch (\Exception $e) {
            $this->notification = 'Error deleting community: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    public function closePage()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
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
            'editingId',
        ]);
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterStatus = '';
        $this->filterProvince = '';
    }

    public function viewAssessmentSummary($communityId)
    {
        $this->viewingSummaryId = $communityId;
        $this->showAssessmentModal = true;
    }

    public function closeAssessmentModal()
    {
        $this->showAssessmentModal = false;
        $this->viewingSummaryId = null;
    }

    private function getProvinces()
    {
        return Community::distinct()
            ->whereNotNull('province')
            ->pluck('province')
            ->sort()
            ->values();
    }
}
