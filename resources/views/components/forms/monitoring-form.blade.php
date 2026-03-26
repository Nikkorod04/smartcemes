<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $programTitle = '';

    #[Validate('required|date')]
    public string $reportMonth = '';

    #[Validate('required|string')]
    public string $objectives = '';

    #[Validate('required|numeric|min:0')]
    public string $targetOutput = '';

    #[Validate('required|numeric|min:0')]
    public string $actualOutput = '';

    #[Validate('required|string')]
    public string $accomplishments = '';

    #[Validate('required|string')]
    public string $issues = '';

    #[Validate('required|string')]
    public string $meansOfVerification = '';

    #[Validate('required|string|max:255')]
    public string $submittedBy = '';

    #[Validate('required|date')]
    public string $submittedDate = '';

    #[Validate('required|string|max:255')]
    public string $submittedTo = '';

    public string $submitted = '';
    public bool $showSuccess = false;

    public function calculateGap()
    {
        if (!$this->targetOutput) {
            return 0;
        }
        return abs($this->targetOutput - $this->actualOutput);
    }

    public function isGapNegative()
    {
        if (!$this->targetOutput) {
            return false;
        }
        return $this->actualOutput > $this->targetOutput;
    }

    public function calculateProgress()
    {
        if (!$this->targetOutput || $this->targetOutput == 0) {
            return 0;
        }
        $progress = round(($this->actualOutput / $this->targetOutput) * 100);
        return min($progress, 100);
    }

    public function submit()
    {
        $this->validate();
        
        $this->submitted = now()->format('F d, Y h:i A');
        $this->showSuccess = true;
        
        $this->reset('programTitle', 'reportMonth', 'objectives', 'targetOutput', 
                     'actualOutput', 'accomplishments', 'issues', 'meansOfVerification',
                     'submittedBy', 'submittedDate', 'submittedTo');
        
        $this->dispatch('form-submitted', ['type' => 'M-CES-001']);
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
        <!-- I. Program Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-700 to-cyan-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Program Information</h3>
                <span class="text-xs font-semibold bg-cyan-600 text-cyan-100 px-3 py-1 rounded-full">Section I</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="programTitle" value="Program Title *" />
                        <input type="text" id="programTitle" wire:model="programTitle" placeholder="Enter program title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600">
                        @error('programTitle') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="reportMonth" value="Report Month *" />
                        <input type="month" id="reportMonth" wire:model="reportMonth" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600">
                        @error('reportMonth') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="objectives" value="Program Objectives *" />
                    <textarea id="objectives" wire:model="objectives" placeholder="State program objectives" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600"></textarea>
                    @error('objectives') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- II. Performance Metrics -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-700 to-orange-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Performance Metrics</h3>
                <span class="text-xs font-semibold bg-orange-600 text-orange-100 px-3 py-1 rounded-full">Section II</span>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="targetOutput" value="Target Output *" />
                        <input type="number" id="targetOutput" wire:model="targetOutput" placeholder="0" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600">
                        @error('targetOutput') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="actualOutput" value="Actual Output *" />
                        <input type="number" id="actualOutput" wire:model.live="actualOutput" placeholder="0" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600">
                        @error('actualOutput') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="outputGap" value="Output Gap" />
                        <div class="mt-1 px-4 py-2 bg-gray-100 rounded-lg font-semibold @if($this->isGapNegative()) text-green-700 @else text-red-700 @endif">
                            {{ $this->calculateGap() }}
                        </div>
                    </div>
                    <div>
                        <x-input-label for="progress" value="Progress %" />
                        <div class="mt-1 px-4 py-2 bg-gray-100 rounded-lg font-semibold text-cyan-700">
                            {{ $this->calculateProgress() }}%
                        </div>
                    </div>
                </div>

                @if($this->calculateProgress() > 0)
                <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2 rounded-full transition-all" style="width: {{ $this->calculateProgress() }}%"></div>
                </div>
                @endif
            </div>
        </div>

        <!-- III. Activity Report -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-700 to-teal-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Activity Report</h3>
                <span class="text-xs font-semibold bg-teal-600 text-teal-100 px-3 py-1 rounded-full">Section III</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="accomplishments" value="Accomplishments *" />
                    <textarea id="accomplishments" wire:model="accomplishments" placeholder="List what has been accomplished" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-600 focus:ring-teal-600"></textarea>
                    @error('accomplishments') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="issues" value="Issues/Problems Encountered *" />
                    <textarea id="issues" wire:model="issues" placeholder="Describe any issues or problems" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-600 focus:ring-teal-600"></textarea>
                    @error('issues') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="meansOfVerification" value="Means of Verification *" />
                    <textarea id="meansOfVerification" wire:model="meansOfVerification" placeholder="How is this verified? (receipts, photos, reports, etc.)" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-600 focus:ring-teal-600"></textarea>
                    @error('meansOfVerification') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- IV. Signatories and Approval -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-700 to-pink-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Signatories and Approval</h3>
                <span class="text-xs font-semibold bg-pink-600 text-pink-100 px-3 py-1 rounded-full">Section IV</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="submittedBy" value="Prepared by (Signature over Printed Name) *" />
                        <input type="text" id="submittedBy" wire:model="submittedBy" placeholder="Full name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                        @error('submittedBy') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="submittedDate" value="Date *" />
                        <input type="date" id="submittedDate" wire:model="submittedDate" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                        @error('submittedDate') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <div>
                        <x-input-label for="submittedTo" value="Submitted to (Signature over Printed Name) *" />
                        <input type="text" id="submittedTo" wire:model="submittedTo" placeholder="Head/Director name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
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