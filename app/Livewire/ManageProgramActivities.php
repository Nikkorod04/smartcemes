<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\ProgramActivity;
use App\Models\ActivityAttendance;
use App\Models\ActivityFacilitator;
use App\Models\ExtensionProgram;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;

class ManageProgramActivities extends Component
{
    use WithPagination;

    public ?int $program_id = null;
    public ?ExtensionProgram $program = null;
    public array $beneficiaries = [];
    public array $selectedBeneficiaries = [];

    // Activity Form Properties
    #[Validate('required|string|max:255')]
    public string $activity_name = '';

    #[Validate('required|date')]
    public string $activity_date = '';

    #[Validate('required|date_format:H:i')]
    public string $start_time = '';

    #[Validate('required|date_format:H:i')]
    public string $end_time = '';

    #[Validate('nullable|string|max:255')]
    public string $location = '';

    #[Validate('nullable|integer|min:0')]
    public int $target_attendees = 0;

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|string')]
    public string $notes = '';

    #[Validate('required|in:planned,in_progress,completed,cancelled')]
    public string $status = 'planned';

    // Facilitators
    public array $facilitators = [];
    public string $new_facilitator_name = '';
    public string $new_facilitator_role = 'instructor';

    // UI State
    public bool $showActivityForm = false;
    public bool $showAttendanceModal = false;
    public bool $showAttendanceSummary = false;
    public bool $showSuccessModal = false;
    public string $successMessage = '';
    public bool $showViewActivitiesModal = false;
    public ?int $editingActivityId = null;
    public ?int $selectedActivityId = null;
    public string $searchTerm = '';
    public string $filterStatus = '';
    public string $pageSize = '10';
    public string $notification = '';
    public string $notificationType = '';

    // Attendance State
    public array $attendanceData = [];

    public function mount($program_id = null)
    {
        $this->program_id = $program_id;
        if ($this->program_id) {
            $this->program = ExtensionProgram::findOrFail($this->program_id);
            $this->loadBeneficiaries();
        }
    }

    public function loadBeneficiaries()
    {
        if (!$this->program) return;

        $beneficiaries = Beneficiary::whereIn('id', function ($query) {
            $query->select('beneficiary_id')
                ->from('beneficiary_program')
                ->where('program_id', $this->program->id);
        })
        ->select('id', 'first_name', 'middle_name', 'last_name')
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

        // Map beneficiaries using the full_name accessor
        $this->beneficiaries = $beneficiaries->pluck('full_name', 'id')->toArray();
    }

    public function render()
    {
        $query = ProgramActivity::query();

        if ($this->program_id) {
            $query->where('program_id', $this->program_id);
        }

        if (!empty($this->searchTerm)) {
            $query->where('activity_name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $activities = $query->latest('activity_date')
            ->paginate((int)$this->pageSize);

        return view('livewire.activity-tracker-inline', [
            'activities' => $activities,
        ]);
    }

    public function openActivityForm()
    {
        $this->resetActivityForm();
        $this->showActivityForm = true;
        $this->editingActivityId = null;
    }

    public function closeActivityForm()
    {
        $this->showActivityForm = false;
        $this->resetActivityForm();
    }

    public function openViewActivitiesModal()
    {
        $this->showViewActivitiesModal = true;
    }

    public function closeViewActivitiesModal()
    {
        $this->showViewActivitiesModal = false;
    }

    public function editActivity($id)
    {
        $activity = ProgramActivity::findOrFail($id);
        $this->editingActivityId = $id;
        $this->activity_name = $activity->activity_name;
        $this->activity_date = $activity->activity_date->toDateString();
        $this->start_time = $activity->start_time;
        $this->end_time = $activity->end_time;
        $this->location = $activity->location ?? '';
        $this->target_attendees = $activity->target_attendees;
        $this->description = $activity->description ?? '';
        $this->notes = $activity->notes ?? '';
        $this->status = $activity->status;
        
        $this->facilitators = $activity->facilitatorRecords()
            ->get(['facilitator_name', 'facilitator_role', 'contact_info'])
            ->toArray();
        
        $this->showActivityForm = true;
    }

    public function saveActivity()
    {
        $this->validate();

        $data = [
            'program_id' => $this->program_id,
            'activity_name' => $this->activity_name,
            'activity_date' => $this->activity_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'target_attendees' => $this->target_attendees,
            'description' => $this->description,
            'notes' => $this->notes,
            'status' => $this->status,
            'updated_by' => Auth::id(),
        ];

        if ($this->editingActivityId) {
            $activity = ProgramActivity::findOrFail($this->editingActivityId);
            $activity->update($data);
            $this->successMessage = 'Activity updated successfully!';
            $this->showSuccessModal = true;
        } else {
            $data['created_by'] = Auth::id();
            $activity = ProgramActivity::create($data);
            $this->successMessage = 'Activity created successfully!';
            $this->showSuccessModal = true;
        }

        // Update facilitators
        if ($activity) {
            $activity->facilitatorRecords()->delete();
            foreach ($this->facilitators as $facilitator) {
                ActivityFacilitator::create([
                    'program_activity_id' => $activity->id,
                    'facilitator_name' => $facilitator['facilitator_name'],
                    'facilitator_role' => $facilitator['facilitator_role'],
                    'contact_info' => $facilitator['contact_info'] ?? null,
                ]);
            }
        }

        $this->notificationType = 'success';
        $this->resetActivityForm();
        $this->showActivityForm = false;
    }

    public function deleteActivity($id)
    {
        ProgramActivity::findOrFail($id)->delete();
        $this->notification = 'Activity deleted successfully!';
        $this->notificationType = 'success';
    }

    public function addFacilitator()
    {
        if (blank($this->new_facilitator_name)) return;

        $this->facilitators[] = [
            'facilitator_name' => $this->new_facilitator_name,
            'facilitator_role' => $this->new_facilitator_role,
            'contact_info' => '',
        ];

        $this->new_facilitator_name = '';
        $this->new_facilitator_role = 'instructor';
    }

    public function removeFacilitator($index)
    {
        unset($this->facilitators[$index]);
        $this->facilitators = array_values($this->facilitators);
    }

    public function openAttendanceModal($id)
    {
        $this->selectedActivityId = $id;
        $this->showAttendanceModal = true;
        $this->loadAttendanceData();
    }

    public function closeAttendanceModal()
    {
        $this->showAttendanceModal = false;
        $this->selectedActivityId = null;
        $this->attendanceData = [];
    }

    public function loadAttendanceData()
    {
        if (!$this->selectedActivityId) return;

        $activity = ProgramActivity::findOrFail($this->selectedActivityId);
        $this->attendanceData = [];

        foreach ($this->beneficiaries as $beneficiary_id => $beneficiary_name) {
            $attendance = ActivityAttendance::where('program_activity_id', $this->selectedActivityId)
                ->where('beneficiary_id', $beneficiary_id)
                ->first();

            $this->attendanceData[$beneficiary_id] = [
                'name' => $beneficiary_name,
                'status' => $attendance?->attendance_status ?? 'absent',
                'notes' => $attendance?->notes ?? '',
            ];
        }
    }

    public function saveAttendance()
    {
        if (!$this->selectedActivityId) return;

        $activity = ProgramActivity::findOrFail($this->selectedActivityId);

        foreach ($this->attendanceData as $beneficiary_id => $data) {
            ActivityAttendance::updateOrCreate(
                [
                    'program_activity_id' => $this->selectedActivityId,
                    'beneficiary_id' => $beneficiary_id,
                ],
                [
                    'attendance_status' => $data['status'],
                    'notes' => $data['notes'],
                    'created_by' => Auth::id(),
                ]
            );
        }

        $this->notification = 'Attendance saved successfully!';
        $this->notificationType = 'success';
        $this->closeAttendanceModal();
    }

    public function toggleAllPresent()
    {
        foreach ($this->attendanceData as &$data) {
            $data['status'] = 'present';
        }
    }

    public function toggleAllAbsent()
    {
        foreach ($this->attendanceData as &$data) {
            $data['status'] = 'absent';
        }
    }

    public function openAttendanceSummary($id)
    {
        $this->selectedActivityId = $id;
        $this->showAttendanceSummary = true;
    }

    public function closeAttendanceSummary()
    {
        $this->showAttendanceSummary = false;
        $this->selectedActivityId = null;
    }

    public function resetActivityForm()
    {
        $this->resetValidation();
        $this->activity_name = '';
        $this->activity_date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->location = '';
        $this->target_attendees = 0;
        $this->description = '';
        $this->notes = '';
        $this->status = 'planned';
        $this->facilitators = [];
        $this->new_facilitator_name = '';
        $this->new_facilitator_role = 'instructor';
        $this->editingActivityId = null;
    }
}
