<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $beneficiaryName = '';

    #[Validate('required|numeric|min:1|max:150')]
    public string $age = '';

    #[Validate('required|in:Male,Female,Other')]
    public string $sex = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string|max:11')]
    public string $phone = '';

    #[Validate('required|string')]
    public string $address = '';

    #[Validate('required|string|max:100')]
    public string $civilStatus = '';

    #[Validate('required|string|max:255')]
    public string $occupation = '';

    #[Validate('required|string')]
    public string $education = '';

    #[Validate('required|string')]
    public string $familyComposition = '';

    #[Validate('required|string')]
    public string $programsInterested = '';

    #[Validate('required|date')]
    public string $dateRegistered = '';

    #[Validate('required|string|max:255')]
    public string $registeredBy = '';

    public string $submitted = '';
    public bool $showSuccess = false;

    public function submit()
    {
        $this->validate();
        
        $this->submitted = now()->format('F d, Y h:i A');
        $this->showSuccess = true;
        
        $this->reset('beneficiaryName', 'age', 'sex', 'email', 'phone', 'address',
                     'civilStatus', 'occupation', 'education', 'familyComposition',
                     'programsInterested', 'dateRegistered', 'registeredBy');
        
        $this->dispatch('form-submitted', ['type' => 'B-CES-001']);
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
                        Beneficiary registration submitted successfully on {{ $submitted }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-8">
        <!-- Personal Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-violet-700 to-violet-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Personal Information</h3>
                <span class="text-xs font-semibold bg-violet-600 text-violet-100 px-3 py-1 rounded-full">Section 1</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="beneficiaryName" value="Full Name *" />
                        <input type="text" id="beneficiaryName" wire:model="beneficiaryName" placeholder="Enter full name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600">
                        @error('beneficiaryName') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="age" value="Age *" />
                        <input type="number" id="age" wire:model="age" placeholder="Enter age" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600">
                        @error('age') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="sex" value="Sex *" />
                        <select id="sex" wire:model="sex" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600">
                            <option value="">Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('sex') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="civilStatus" value="Civil Status *" />
                        <input type="text" id="civilStatus" wire:model="civilStatus" placeholder="Single/Married/etc" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600">
                        @error('civilStatus') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="occupation" value="Occupation *" />
                        <input type="text" id="occupation" wire:model="occupation" placeholder="Current occupation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600">
                        @error('occupation') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="address" value="Address *" />
                    <input type="text" id="address" wire:model="address" placeholder="Street, Barangay, Municipality" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600">
                    @error('address') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Contact Information</h3>
                <span class="text-xs font-semibold bg-emerald-600 text-emerald-100 px-3 py-1 rounded-full">Section 2</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="email" value="Email Address *" />
                        <input type="email" id="email" wire:model="email" placeholder="user@example.com" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-600 focus:ring-emerald-600">
                        @error('email') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="phone" value="Phone Number *" />
                        <input type="tel" id="phone" wire:model="phone" placeholder="+63 9XX XXX XXXX" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-600 focus:ring-emerald-600">
                        @error('phone') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Educational Background Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-sky-700 to-sky-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Educational Background</h3>
                <span class="text-xs font-semibold bg-sky-600 text-sky-100 px-3 py-1 rounded-full">Section 3</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="education" value="Education Level *" />
                    <textarea id="education" wire:model="education" placeholder="Elementary, High School, College, etc." rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-600 focus:ring-sky-600"></textarea>
                    @error('education') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Family Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-rose-700 to-rose-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Family Composition</h3>
                <span class="text-xs font-semibold bg-rose-600 text-rose-100 px-3 py-1 rounded-full">Section 4</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="familyComposition" value="Family Members/Dependents *" />
                    <textarea id="familyComposition" wire:model="familyComposition" placeholder="List family members (names, ages, relationships)" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-rose-600 focus:ring-rose-600"></textarea>
                    @error('familyComposition') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Programs & Interest Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-700 to-amber-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Programs of Interest</h3>
                <span class="text-xs font-semibold bg-amber-600 text-amber-100 px-3 py-1 rounded-full">Section 5</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="programsInterested" value="Interested Programs *" />
                    <textarea id="programsInterested" wire:model="programsInterested" placeholder="List programs of interest or training needs" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600"></textarea>
                    @error('programsInterested') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Registration Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Registration Details</h3>
                <span class="text-xs font-semibold bg-slate-600 text-slate-100 px-3 py-1 rounded-full">Section 6</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="dateRegistered" value="Date Registered *" />
                        <input type="date" id="dateRegistered" wire:model="dateRegistered" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-slate-600 focus:ring-slate-600">
                        @error('dateRegistered') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="registeredBy" value="Registered By *" />
                        <input type="text" id="registeredBy" wire:model="registeredBy" placeholder="Full name of staff member" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-slate-600 focus:ring-slate-600">
                        @error('registeredBy') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
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
                Register Beneficiary
            </button>
        </div>
    </form>
</div>