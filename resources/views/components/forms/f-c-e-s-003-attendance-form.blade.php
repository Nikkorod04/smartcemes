<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $certifiedStaffName = '';

    #[Validate('required|string|max:255')]
    public string $certifiedRole = '';

    #[Validate('required|string|max:255')]
    public string $certifiedActivityTitle = '';

    #[Validate('required|numeric|min:1|max:31')]
    public string $issuedDay = '';

    #[Validate('required|string')]
    public string $issuedMonth = '';

    #[Validate('required|numeric|min:2000|max:2100')]
    public string $issuedYear = '';

    #[Validate('required|string|max:255')]
    public string $certifierHeadOfInstitution = '';

    #[Validate('required|string|max:255')]
    public string $certifierCommunityServicesDirector = '';

    public string $submitted = '';
    public bool $showSuccess = false;

    public function submit()
    {
        $this->validate();
        
        $this->submitted = now()->format('F d, Y h:i A');
        $this->showSuccess = true;
        
        // Reset form
        $this->reset('certifiedStaffName', 'certifiedRole', 'certifiedActivityTitle',
                     'issuedDay', 'issuedMonth', 'issuedYear', 'certifierHeadOfInstitution',
                     'certifierCommunityServicesDirector');
        
        $this->dispatch('form-submitted', ['type' => 'F-CES-003']);
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
                        Certificate issued successfully on {{ $submitted }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-8">
        <!-- I. Activity and Participant Information -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Activity and Participant Information</h3>
                <span class="text-xs font-semibold bg-blue-700 text-blue-100 px-3 py-1 rounded-full">Section I</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="certifiedActivityTitle" value="Activity/Training Title *" />
                    <input type="text" id="certifiedActivityTitle" wire:model="certifiedActivityTitle" placeholder="Enter activity or training title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                    @error('certifiedActivityTitle') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="certifiedStaffName" value="Name of Participant/Staff *" />
                        <input type="text" id="certifiedStaffName" wire:model="certifiedStaffName" placeholder="Full name of faculty, staff, or student" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('certifiedStaffName') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="certifiedRole" value="Role in Activity *" />
                        <input type="text" id="certifiedRole" wire:model="certifiedRole" placeholder="e.g., Resource Speaker, Participant, Facilitator" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('certifiedRole') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- II. Date of Issuance -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Date of Issuance</h3>
                <span class="text-xs font-semibold bg-green-600 text-green-100 px-3 py-1 rounded-full">Section II</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="issuedDay" value="Day *" />
                        <input type="number" id="issuedDay" wire:model="issuedDay" min="1" max="31" placeholder="1-31" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                        @error('issuedDay') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="issuedMonth" value="Month *" />
                        <select id="issuedMonth" wire:model="issuedMonth" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            <option value="">Select month...</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        @error('issuedMonth') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="issuedYear" value="Year *" />
                        <input type="number" id="issuedYear" wire:model="issuedYear" min="2000" max="2100" placeholder="YYYY" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                        @error('issuedYear') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- III. Signatories -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Signatories</h3>
                <span class="text-xs font-semibold bg-yellow-600 text-yellow-100 px-3 py-1 rounded-full">Section III</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="certifierHeadOfInstitution" value="Head of Institution/Agency (Signature over Printed Name) *" />
                    <input type="text" id="certifierHeadOfInstitution" wire:model="certifierHeadOfInstitution" placeholder="Full name and title of head" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600">
                    @error('certifierHeadOfInstitution') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="certifierCommunityServicesDirector" value="Community Extension Services Director (Signature over Printed Name) *" />
                    <input type="text" id="certifierCommunityServicesDirector" wire:model="certifierCommunityServicesDirector" placeholder="Full name and title of director" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600">
                    @error('certifierCommunityServicesDirector') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                Back
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                Issue Certificate
            </button>
        </div>
    </form>
</div>
