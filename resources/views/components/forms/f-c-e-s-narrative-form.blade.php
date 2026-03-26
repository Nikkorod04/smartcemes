<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use App\Models\Community;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $projectTitle = '';

    #[Validate('required|string|max:255')]
    public string $location = '';

    #[Validate('required|date')]
    public string $dateOfImplementation = '';

    #[Validate('required|string|max:255')]
    public string $implementingCollege = '';

    #[Validate('required|string|max:255')]
    public string $partnerAgency = '';

    #[Validate('required|string')]
    public string $natureOfService = '';

    #[Validate('required|string')]
    public string $beneficiaries = '';

    #[Validate('required|string')]
    public string $facultyInvolved = '';

    #[Validate('required|string')]
    public string $objectives = '';

    #[Validate('required|string')]
    public string $narrative = '';

    #[Validate('required|in:excellent,very_good,good,fair,poor')]
    public string $activityRating = '';

    #[Validate('required|in:excellent,very_good,good,fair,poor')]
    public string $timelinessRating = '';

    #[Validate('required|string|max:255')]
    public string $preparedBy = '';

    #[Validate('required|date')]
    public string $dateSigned = '';

    #[Validate('nullable|string|max:500')]
    public string $photosCaption = '';

    #[Validate('nullable|string|max:255')]
    public string $reviewedByRemarks = '';

    public string $submitted = '';
    public bool $showSuccess = false;

    #[Computed]
    public function communities()
    {
        return Community::all(['id', 'name', 'municipality', 'province'])
            ->map(fn($community) => [
                'id' => $community->id,
                'name' => "{$community->name} - {$community->municipality}, {$community->province}"
            ]);
    }

    public function submit()
    {
        $this->validate();
        
        $this->submitted = now()->format('F d, Y h:i A');
        $this->showSuccess = true;
        
        // Reset form
        $this->reset('projectTitle', 'location', 'dateOfImplementation', 'implementingCollege', 
                     'partnerAgency', 'natureOfService', 'beneficiaries', 'facultyInvolved',
                     'objectives', 'narrative', 'activityRating', 'timelinessRating',
                     'preparedBy', 'dateSigned', 'photosCaption', 'reviewedByRemarks');
        
        $this->dispatch('form-submitted', ['type' => 'F-CES #11']);
    }
};
?>

<div class="space-y-6">
    @if($showSuccess)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        Form submitted successfully on {{ $submitted }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-8">
        <!-- I. Project Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Project Information</h3>
                <span class="text-xs font-semibold bg-blue-700 text-blue-100 px-3 py-1 rounded-full">Section I</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="projectTitle" value="Project Title *" />
                        <input type="text" id="projectTitle" wire:model="projectTitle" placeholder="Enter project title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('projectTitle') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="location" value="Location *" />
                        <select id="location" wire:model="location" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                            <option value="">Select a community...</option>
                            @foreach($this->communities as $community)
                                <option value="{{ $community['name'] }}">{{ $community['name'] }}</option>
                            @endforeach
                        </select>
                        @error('location') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="dateOfImplementation" value="Date of Implementation *" />
                        <input type="date" id="dateOfImplementation" wire:model="dateOfImplementation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('dateOfImplementation') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="implementingCollege" value="Implementing College/Office *" />
                        <input type="text" id="implementingCollege" wire:model="implementingCollege" placeholder="Enter college/office" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('implementingCollege') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="partnerAgency" value="Partner Agency *" />
                    <input type="text" id="partnerAgency" wire:model="partnerAgency" placeholder="Enter partner agency" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                    @error('partnerAgency') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- II. Activity Details Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Activity Details</h3>
                <span class="text-xs font-semibold bg-green-600 text-green-100 px-3 py-1 rounded-full">Section II</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="natureOfService" value="Nature of Extension Service *" />
                    <textarea id="natureOfService" wire:model="natureOfService" placeholder="Describe the nature of extension service" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    @error('natureOfService') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="beneficiaries" value="Number and Type of Beneficiaries *" />
                    <textarea id="beneficiaries" wire:model="beneficiaries" placeholder="List number and type (e.g., 50 OSY, 30 Women)" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    @error('beneficiaries') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="facultyInvolved" value="Faculty Members Involved *" />
                    <textarea id="facultyInvolved" wire:model="facultyInvolved" placeholder="List project leader and coordinators" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    @error('facultyInvolved') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="objectives" value="Objectives *" />
                    <textarea id="objectives" wire:model="objectives" placeholder="State project objectives" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    @error('objectives') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="narrative" value="Narrative of the Activity *" />
                    <textarea id="narrative" wire:model="narrative" placeholder="Provide short narrative of activities conducted" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    @error('narrative') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- III. Evaluation and Documentation Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Evaluation and Documentation</h3>
                <span class="text-xs font-semibold bg-yellow-600 text-yellow-100 px-3 py-1 rounded-full">Section III</span>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <x-input-label for="activityRating" value="Activity Rating *" />
                    <select id="activityRating" wire:model="activityRating" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        <option value="">Select rating...</option>
                        <option value="excellent">Excellent</option>
                        <option value="very_good">Very Good</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                    @error('activityRating') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="timelinessRating" value="Timeliness Rating *" />
                    <select id="timelinessRating" wire:model="timelinessRating" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        <option value="">Select rating...</option>
                        <option value="excellent">Excellent</option>
                        <option value="very_good">Very Good</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                    @error('timelinessRating') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="photosCaption" value="Photos with Caption" />
                    <textarea id="photosCaption" wire:model="photosCaption" placeholder="Describe the photos attached or reference to photo documentation" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    <p class="text-sm text-gray-500 mt-1">Include file references or descriptions of attached photos</p>
                    @error('photosCaption') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- IV. Certification and Approval Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-red-700 to-red-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Certification and Approval</h3>
                <span class="text-xs font-semibold bg-red-600 text-red-100 px-3 py-1 rounded-full">Section IV</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="preparedBy" value="Prepared by (Name) *" />
                    <input type="text" id="preparedBy" wire:model="preparedBy" placeholder="Full name of project leader" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                    @error('preparedBy') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="dateSigned" value="Date Signed *" />
                    <input type="date" id="dateSigned" wire:model="dateSigned" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                    @error('dateSigned') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="reviewedByRemarks" value="Reviewed by - Remarks" />
                    <textarea id="reviewedByRemarks" wire:model="reviewedByRemarks" placeholder="Reviewer's remarks and observations" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900"></textarea>
                    @error('reviewedByRemarks') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                Back
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                Submit Form
            </button>
        </div>
    </form>
</div>