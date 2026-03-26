<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\ExtensionProgram;
use Illuminate\Support\Facades\Auth;

class ManageBeneficiaries extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_status = '';
    public $filter_category = '';
    public $filter_community = '';
    public $sortBy = 'created_at';
    public $sortDir = 'desc';

    // Form properties
    public $showForm = false;
    public $editingId = null;
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $date_of_birth = '';
    public $age = '';
    public $gender = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $barangay = '';
    public $municipality = '';
    public $province = '';
    public $community_id = '';
    public $beneficiary_category = '';
    public $monthly_income = '';
    public $occupation = '';
    public $educational_attainment = '';
    public $marital_status = '';
    public $number_of_dependents = '';
    public $status = 'active';
    public $notes = '';

    public $showDeleteModal = false;
    public $deleteId = null;

    // Program-Beneficiary Management
    public $selectedProgramId = null;
    public $showAssignModal = false;
    public $showAddBeneficiaryModal = false;
    public $showEditBeneficiaryModal = false;
    public $editingBeneficiaryId = null;
    public $assignSearch = '';
    public $selectedBeneficiaries = [];
    public $programSearch = '';
    public bool $showSuccessModal = false;
    public string $successMessage = '';

    public function updatedProgramSearch()
    {
        $this->resetPage();
    }

    #[Computed]
    public function statsActive()
    {
        return Beneficiary::where('status', 'active')->count();
    }

    #[Computed]
    public function statsGraduated()
    {
        return Beneficiary::where('status', 'graduated')->count();
    }

    #[Computed]
    public function statsDropout()
    {
        return Beneficiary::where('status', 'dropout')->count();
    }

    public function getBeneficiaries()
    {
        $query = Beneficiary::query();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->filter_status) {
            $query->byStatus($this->filter_status);
        }

        if ($this->filter_category) {
            $query->byCategory($this->filter_category);
        }

        if ($this->filter_community) {
            $query->where('community_id', $this->filter_community);
        }

        return $query->orderBy($this->sortBy, $this->sortDir)->paginate(10);
    }

    public function getCommunities()
    {
        return Community::orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->reset([
            'first_name', 'middle_name', 'last_name', 'date_of_birth', 'age', 'gender',
            'email', 'phone', 'address', 'barangay', 'municipality', 'province',
            'community_id', 'beneficiary_category', 'monthly_income',
            'occupation', 'educational_attainment', 'marital_status', 'number_of_dependents',
            'status', 'notes', 'editingId'
        ]);
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);
        $this->editingId = $beneficiary->id;
        $this->first_name = $beneficiary->first_name;
        $this->middle_name = $beneficiary->middle_name;
        $this->last_name = $beneficiary->last_name;
        $this->date_of_birth = $beneficiary->date_of_birth?->format('Y-m-d');
        $this->age = $beneficiary->age;
        $this->gender = $beneficiary->gender;
        $this->email = $beneficiary->email;
        $this->phone = $beneficiary->phone;
        $this->address = $beneficiary->address;
        $this->barangay = $beneficiary->barangay;
        $this->municipality = $beneficiary->municipality;
        $this->province = $beneficiary->province;
        $this->community_id = $beneficiary->community_id;
        $this->beneficiary_category = $beneficiary->beneficiary_category;
        $this->monthly_income = $beneficiary->monthly_income;
        $this->occupation = $beneficiary->occupation;
        $this->educational_attainment = $beneficiary->educational_attainment;
        $this->marital_status = $beneficiary->marital_status;
        $this->number_of_dependents = $beneficiary->number_of_dependents;
        $this->status = $beneficiary->status;
        $this->notes = $beneficiary->notes;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:beneficiaries,email,' . ($this->editingId ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'community_id' => 'nullable|exists:communities,id',
            'beneficiary_category' => 'nullable|string',
            'monthly_income' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,graduated,dropout',
        ]);

        // Calculate age from date_of_birth if available, otherwise use provided age
        $calculatedAge = null;
        if ($this->date_of_birth) {
            $calculatedAge = \Carbon\Carbon::parse($this->date_of_birth)->age;
        } elseif (!empty($this->age) && is_numeric($this->age)) {
            $calculatedAge = (int) $this->age;
        }

        // Convert empty number_of_dependents to 0
        $numberOfDependents = !empty($this->number_of_dependents) && is_numeric($this->number_of_dependents) 
            ? (int) $this->number_of_dependents 
            : 0;

        // Convert empty community_id to NULL
        $communityId = !empty($this->community_id) && is_numeric($this->community_id)
            ? (int) $this->community_id
            : null;

        // Convert empty monthly_income to NULL
        $monthlyIncome = !empty($this->monthly_income) && is_numeric($this->monthly_income)
            ? (float) $this->monthly_income
            : null;

        if ($this->editingId) {
            $beneficiary = Beneficiary::findOrFail($this->editingId);
            $beneficiary->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'date_of_birth' => $this->date_of_birth ? \Carbon\Carbon::parse($this->date_of_birth) : null,
                'age' => $calculatedAge,
                'gender' => $this->gender,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'barangay' => $this->barangay,
                'municipality' => $this->municipality,
                'province' => $this->province,
                'community_id' => $communityId,
                'beneficiary_category' => $this->beneficiary_category,
                'monthly_income' => $monthlyIncome,
                'occupation' => $this->occupation,
                'educational_attainment' => $this->educational_attainment,
                'marital_status' => $this->marital_status,
                'number_of_dependents' => $numberOfDependents,
                'status' => $this->status,
                'notes' => $this->notes,
                'updated_by' => Auth::id(),
            ]);
            $this->dispatch('notify', message: 'Beneficiary updated successfully');
        } else {
            Beneficiary::create([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'date_of_birth' => $this->date_of_birth ? \Carbon\Carbon::parse($this->date_of_birth) : null,
                'age' => $calculatedAge,
                'gender' => $this->gender,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'barangay' => $this->barangay,
                'municipality' => $this->municipality,
                'province' => $this->province,
                'community_id' => $communityId,
                'beneficiary_category' => $this->beneficiary_category,
                'monthly_income' => $monthlyIncome,
                'occupation' => $this->occupation,
                'educational_attainment' => $this->educational_attainment,
                'marital_status' => $this->marital_status,
                'number_of_dependents' => $numberOfDependents,
                'status' => $this->status,
                'notes' => $this->notes,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            $this->dispatch('notify', message: 'Beneficiary registered successfully');
        }

        $this->closeForm();
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            Beneficiary::findOrFail($this->deleteId)->delete();
            $this->dispatch('notify', message: 'Beneficiary deleted successfully');
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->resetPage();
        }
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filter_status', 'filter_category', 'filter_community']);
        $this->resetPage();
    }

    // Program-Beneficiary Management Methods
    public function getPrograms()
    {
        $query = ExtensionProgram::query();

        if ($this->programSearch) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->programSearch . '%')
                  ->orWhere('description', 'like', '%' . $this->programSearch . '%');
            });
        }

        $paginated = $query->orderBy('title')->paginate(9);
        
        // Fix pagination path to prevent duplicate 'secretary'
        $paginated->setPath('/secretary/beneficiaries');
        
        return $paginated;
    }

    public function selectProgram($programId)
    {
        $this->selectedProgramId = (int)$programId;
    }

    public function deselectProgram()
    {
        $this->selectedProgramId = null;
        $this->selectedBeneficiaries = [];
    }

    public function getProgramBeneficiaries()
    {
        if (!$this->selectedProgramId) return collect();
        
        return Beneficiary::whereIn('id', function ($query) {
            $query->select('beneficiary_id')
                ->from('beneficiary_program')
                ->where('program_id', $this->selectedProgramId);
        })
        ->select('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone', 'status', 'beneficiary_category')
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();
    }

    public function getAvailableBeneficiaries()
    {
        if (!$this->selectedProgramId) return collect();
        
        $query = Beneficiary::whereNotIn('id', function ($query) {
            $query->select('beneficiary_id')
                ->from('beneficiary_program')
                ->where('program_id', $this->selectedProgramId);
        });

        if ($this->assignSearch) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->assignSearch . '%')
                  ->orWhere('last_name', 'like', '%' . $this->assignSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->assignSearch . '%');
            });
        }

        return $query->selectRaw("id, CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) as full_name")
            ->orderBy('full_name')
            ->get();
    }

    public function openAssignModal()
    {
        $this->showAssignModal = true;
        $this->selectedBeneficiaries = [];
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->selectedBeneficiaries = [];
        $this->assignSearch = '';
    }

    public function openAddBeneficiaryModal()
    {
        $this->resetForm();
        $this->showAddBeneficiaryModal = true;
    }

    public function closeAddBeneficiaryModal()
    {
        $this->showAddBeneficiaryModal = false;
        $this->resetForm();
    }

    public function saveBeneficiaryAndAssignToProgram()
    {
        $this->validate([
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'middle_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'nullable|email|unique:beneficiaries,email',
            'phone' => 'nullable|string|regex:/^09\d{9}$/',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'community_id' => 'nullable|exists:communities,id',
            'beneficiary_category' => 'nullable|string',
            'monthly_income' => 'nullable|numeric|min:0',
            'occupation' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'number_of_dependents' => 'nullable|integer|min:0|max:20',
            'status' => 'required|in:active,inactive,graduated,dropout',
        ], [
            'first_name.regex' => 'First name must only contain letters.',
            'middle_name.regex' => 'Middle name must only contain letters.',
            'last_name.regex' => 'Last name must only contain letters.',
            'phone.regex' => 'Phone number must be 11 digits in format 09xxxxxxxxx.',
        ]);

        // Calculate age from date_of_birth if available
        $calculatedAge = null;
        if ($this->date_of_birth) {
            $calculatedAge = \Carbon\Carbon::parse($this->date_of_birth)->age;
        } elseif (!empty($this->age) && is_numeric($this->age)) {
            $calculatedAge = (int) $this->age;
        }

        // Create the beneficiary
        $beneficiary = Beneficiary::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth ? \Carbon\Carbon::parse($this->date_of_birth) : null,
            'age' => $calculatedAge,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'barangay' => $this->barangay,
            'municipality' => $this->municipality,
            'province' => $this->province,
            'community_id' => !empty($this->community_id) ? (int) $this->community_id : null,
            'beneficiary_category' => $this->beneficiary_category,
            'monthly_income' => !empty($this->monthly_income) ? (float) $this->monthly_income : null,
            'occupation' => $this->occupation,
            'educational_attainment' => $this->educational_attainment,
            'marital_status' => $this->marital_status,
            'number_of_dependents' => !empty($this->number_of_dependents) ? (int) $this->number_of_dependents : 0,
            'status' => $this->status,
            'notes' => $this->notes,
            'program_ids' => [$this->selectedProgramId],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Assign to program (pivot table)
        \DB::table('beneficiary_program')->insert([
            'beneficiary_id' => $beneficiary->id,
            'program_id' => $this->selectedProgramId,
            'enrollment_status' => 'enrolled',
            'enrollment_date' => now(),
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('notify', message: 'Beneficiary created and assigned to program successfully');
        $this->closeAddBeneficiaryModal();
    }

    public function assignBeneficiariesToProgram()
    {
        if (!$this->selectedProgramId || empty($this->selectedBeneficiaries)) {
            return;
        }

        foreach ($this->selectedBeneficiaries as $beneficiaryId) {
            // Add to pivot table
            \DB::table('beneficiary_program')->updateOrCreate(
                [
                    'beneficiary_id' => $beneficiaryId,
                    'program_id' => $this->selectedProgramId,
                ],
                [
                    'enrollment_status' => 'enrolled',
                    'enrollment_date' => now(),
                    'created_by' => Auth::id(),
                ]
            );

            // Update program_ids JSON column in beneficiaries table
            $beneficiary = Beneficiary::findOrFail($beneficiaryId);
            $programIds = $beneficiary->program_ids ?? [];
            if (!in_array($this->selectedProgramId, $programIds)) {
                $programIds[] = $this->selectedProgramId;
                $beneficiary->update(['program_ids' => $programIds]);
            }
        }

        $this->dispatch('notify', message: count($this->selectedBeneficiaries) . ' beneficiary(ies) assigned to program');
        $this->closeAssignModal();
    }

    public function removeBeneficiaryFromProgram($beneficiaryId)
    {
        if ($this->selectedProgramId) {
            // Remove from pivot table
            \DB::table('beneficiary_program')
                ->where('beneficiary_id', $beneficiaryId)
                ->where('program_id', $this->selectedProgramId)
                ->delete();

            // Update program_ids JSON column in beneficiaries table
            $beneficiary = Beneficiary::findOrFail($beneficiaryId);
            $programIds = $beneficiary->program_ids ?? [];
            $programIds = array_filter($programIds, fn($id) => $id !== $this->selectedProgramId);
            $beneficiary->update(['program_ids' => array_values($programIds)]);

            $this->dispatch('notify', message: 'Beneficiary removed from program');
        }
    }

    public function editBeneficiaryInProgram($beneficiaryId)
    {
        $beneficiary = Beneficiary::findOrFail($beneficiaryId);
        $this->editingBeneficiaryId = $beneficiary->id;
        $this->first_name = $beneficiary->first_name;
        $this->middle_name = $beneficiary->middle_name;
        $this->last_name = $beneficiary->last_name;
        $this->date_of_birth = $beneficiary->date_of_birth?->format('Y-m-d');
        $this->age = $beneficiary->age;
        $this->gender = $beneficiary->gender;
        $this->email = $beneficiary->email;
        $this->phone = $beneficiary->phone;
        $this->address = $beneficiary->address;
        $this->barangay = $beneficiary->barangay;
        $this->municipality = $beneficiary->municipality;
        $this->province = $beneficiary->province;
        $this->community_id = $beneficiary->community_id;
        $this->beneficiary_category = $beneficiary->beneficiary_category;
        $this->monthly_income = $beneficiary->monthly_income;
        $this->occupation = $beneficiary->occupation;
        $this->educational_attainment = $beneficiary->educational_attainment;
        $this->marital_status = $beneficiary->marital_status;
        $this->number_of_dependents = $beneficiary->number_of_dependents;
        $this->status = $beneficiary->status;
        $this->notes = $beneficiary->notes;
        $this->showEditBeneficiaryModal = true;
    }

    public function updateBeneficiaryInProgram()
    {
        $this->validate([
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'middle_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'nullable|email|unique:beneficiaries,email,' . $this->editingBeneficiaryId,
            'phone' => 'nullable|string|regex:/^09\d{9}$/',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'community_id' => 'nullable|exists:communities,id',
            'beneficiary_category' => 'nullable|string',
            'monthly_income' => 'nullable|numeric|min:0',
            'occupation' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'number_of_dependents' => 'nullable|integer|min:0|max:20',
            'status' => 'required|in:active,inactive,graduated,dropout',
        ], [
            'first_name.regex' => 'First name must only contain letters.',
            'middle_name.regex' => 'Middle name must only contain letters.',
            'last_name.regex' => 'Last name must only contain letters.',
            'phone.regex' => 'Phone number must be 11 digits in format 09xxxxxxxxx.',
        ]);

        // Calculate age from date_of_birth if available
        $calculatedAge = null;
        if ($this->date_of_birth) {
            $calculatedAge = \Carbon\Carbon::parse($this->date_of_birth)->age;
        } elseif (!empty($this->age) && is_numeric($this->age)) {
            $calculatedAge = (int) $this->age;
        }

        $beneficiary = Beneficiary::findOrFail($this->editingBeneficiaryId);
        $beneficiary->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth ? \Carbon\Carbon::parse($this->date_of_birth) : null,
            'age' => $calculatedAge,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'barangay' => $this->barangay,
            'municipality' => $this->municipality,
            'province' => $this->province,
            'community_id' => !empty($this->community_id) ? (int) $this->community_id : null,
            'beneficiary_category' => $this->beneficiary_category,
            'monthly_income' => !empty($this->monthly_income) ? (float) $this->monthly_income : null,
            'occupation' => $this->occupation,
            'educational_attainment' => $this->educational_attainment,
            'marital_status' => $this->marital_status,
            'number_of_dependents' => !empty($this->number_of_dependents) ? (int) $this->number_of_dependents : 0,
            'status' => $this->status,
            'notes' => $this->notes,
            'updated_by' => Auth::id(),
        ]);

        $this->successMessage = 'Beneficiary updated successfully!';
        $this->showSuccessModal = true;
        $this->closeEditBeneficiaryModal();
    }

    public function closeEditBeneficiaryModal()
    {
        $this->showEditBeneficiaryModal = false;
        $this->editingBeneficiaryId = null;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.manage-beneficiaries', [
            'beneficiaries' => $this->getBeneficiaries(),
            'communities' => $this->getCommunities(),
            'programs' => $this->getPrograms(),
            'programBeneficiaries' => $this->getProgramBeneficiaries(),
            'availableBeneficiaries' => $this->getAvailableBeneficiaries(),
            'statsActive' => $this->statsActive,
            'statsGraduated' => $this->statsGraduated,
            'statsDropout' => $this->statsDropout,
        ]);
    }
}
