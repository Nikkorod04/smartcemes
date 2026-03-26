<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $projectTitle = '';

    #[Validate('required|string')]
    public string $objectiveGoal = '';

    #[Validate('required|numeric|min:0')]
    public string $outputTarget = '';

    #[Validate('required|numeric|min:0')]
    public string $outputActual = '';

    #[Validate('required|string')]
    public string $meansOfVerification = '';

    #[Validate('required|string')]
    public string $accomplishments = '';

    #[Validate('required|string')]
    public string $issuesProblems = '';

    #[Validate('required|string|max:255')]
    public string $preparedBy = '';

    #[Validate('required|string|max:255')]
    public string $submittedTo = '';

    public string $submitted = '';
    public bool $showSuccess = false;

    public function calculateGap()
    {
        if (!$this->outputTarget) {
            return 0;
        }
        return abs($this->outputTarget - $this->outputActual);
    }

    public function isGapNegative()
    {
        if (!$this->outputTarget) {
            return false;
        }
        return $this->outputActual > $this->outputTarget;
    }

    public function calculateProgress()
    {
        if (!$this->outputTarget || $this->outputTarget == 0) {
            return 0;
        }
        $progress = round(($this->outputActual / $this->outputTarget) * 100);
        return min($progress, 100);
    }

    public function submit()
    {
        $this->validate();
        
        $this->submitted = now()->format('F d, Y h:i A');
        $this->showSuccess = true;
        
        // Reset form
        $this->reset('projectTitle', 'objectiveGoal', 'outputTarget', 'outputActual',
                     'meansOfVerification', 'accomplishments', 'issuesProblems',
                     'preparedBy', 'submittedTo');
        
        $this->dispatch('form-submitted', ['type' => 'F-CES-005']);
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
                        Monitoring report submitted successfully on {{ $submitted }}
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
                <div>
                    <x-input-label for="projectTitle" value="Activity/Project Title *" />
                    <input type="text" id="projectTitle" wire:model="projectTitle" placeholder="Enter project/activity title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                    @error('projectTitle') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- II. Monitoring Details Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Monitoring Details</h3>
                <span class="text-xs font-semibold bg-green-600 text-green-100 px-3 py-1 rounded-full">Section II</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="objectiveGoal" value="Activity Goal/Objectives *" />
                    <textarea id="objectiveGoal" wire:model="objectiveGoal" placeholder="State the activity goal and objectives" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700"></textarea>
                    @error('objectiveGoal') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="outputTarget" value="Target Output *" />
                        <input type="number" id="outputTarget" wire:model="outputTarget" placeholder="0" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                        @error('outputTarget') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="outputActual" value="Actual Output *" />
                        <input type="number" id="outputActual" wire:model.live="outputActual" placeholder="0" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                        @error('outputActual') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="outputGap" value="Output Gap" />
                        <div class="mt-1 px-4 py-2 bg-gray-100 rounded-lg font-semibold @if($this->isGapNegative()) text-green-700 @else text-red-700 @endif">
                            {{ $this->calculateGap() }}
                        </div>
                    </div>
                    <div>
                        <x-input-label for="progress" value="Progress %" />
                        <div class="mt-1 px-4 py-2 bg-gray-100 rounded-lg font-semibold text-blue-700">
                            {{ $this->calculateProgress() }}%
                        </div>
                    </div>
                </div>

                @if($this->calculateProgress() > 0)
                <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all" style="width: {{ $this->calculateProgress() }}%"></div>
                </div>
                @endif
            </div>
        </div>

        <!-- III. Verification and Documentation Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Verification and Documentation</h3>
                <span class="text-xs font-semibold bg-yellow-600 text-yellow-100 px-3 py-1 rounded-full">Section III</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="meansOfVerification" value="Means of Verification *" />
                    <textarea id="meansOfVerification" wire:model="meansOfVerification" placeholder="Describe how this is verified (receipts, photos, reports, etc.)" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600"></textarea>
                    @error('meansOfVerification') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="accomplishments" value="Accomplishments *" />
                    <textarea id="accomplishments" wire:model="accomplishments" placeholder="What has been accomplished in this activity?" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600"></textarea>
                    @error('accomplishments') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="issuesProblems" value="Issues/Problems/Reasons for Under Achievement *" />
                    <textarea id="issuesProblems" wire:model="issuesProblems" placeholder="Describe any issues, problems, or challenges encountered" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600"></textarea>
                    @error('issuesProblems') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- IV. Signatories and Approval Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-red-700 to-red-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Signatories and Approval</h3>
                <span class="text-xs font-semibold bg-red-600 text-red-100 px-3 py-1 rounded-full">Section IV</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="preparedBy" value="Prepared by (Signature over Printed Name) *" />
                        <input type="text" id="preparedBy" wire:model="preparedBy" placeholder="Full name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-700 focus:ring-red-700">
                        @error('preparedBy') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="submittedTo" value="Submitted to (Signature over Printed Name) *" />
                        <input type="text" id="submittedTo" wire:model="submittedTo" placeholder="Head/Director name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-700 focus:ring-red-700">
                        @error('submittedTo') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                Back
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                Submit Report
            </button>
        </div>
    </form>
</div>
