@use('App\Models\Beneficiary')

<div class="min-h-screen bg-gradient-to-br from-white to-gray-50 px-8 py-6">
    <div class="w-full">
        <!-- PROGRAMS LIST VIEW -->
        @if(!$selectedProgramId)
            <div class="space-y-3">
                <!-- Search Bar -->
                <div class="mb-6">
                    <input 
                        type="text" 
                        wire:model.live="programSearch" 
                        placeholder="Search programs by name or description..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <!-- Programs Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($programs as $program)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition border border-gray-200 overflow-hidden">
                            <!-- Program Card -->
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $program->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($program->description, 80) }}</p>
                                    </div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ 
                                        $program->status === 'active' ? 'bg-green-100 text-green-800' :
                                        ($program->status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                                        ($program->status === 'planning' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-blue-100 text-blue-800'))
                                    }}">
                                        {{ ucfirst($program->status) }}
                                    </span>
                                </div>

                                <!-- Beneficiary Count -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-2xl font-bold text-blue-600">
                                                {{ \App\Models\Beneficiary::whereIn('id', function($q) use($program) { $q->select('beneficiary_id')->from('beneficiary_program')->where('program_id', $program->id); })->count() }}
                                            </p>
                                            <p class="text-sm text-gray-600">Beneficiaries</p>
                                        </div>
                                        <button type="button" wire:click="selectProgram({{ $program->id }})" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white hover:text-yellow-400 rounded-lg font-medium text-sm transition">
                                            Manage
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white rounded-lg shadow p-8 text-center">
                            <p class="text-gray-500">No programs found</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $programs->links('vendor.pagination.custom') }}
                </div>
            </div>

        <!-- BENEFICIARIES IN PROGRAM VIEW -->
        @else
            @php
                $program = \App\Models\ExtensionProgram::find($selectedProgramId);
            @endphp
            
            <div class="space-y-4">
                <!-- Header with Back Arrow -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button 
                            wire:click="deselectProgram"
                            class="text-blue-900 hover:text-blue-700 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $program->title }}</h2>
                            <p class="text-gray-600 text-sm mt-1">Manage beneficiaries enrolled in this program</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <button 
                            wire:click="openAddBeneficiaryModal"
                            class="w-10 h-10 bg-blue-900 hover:bg-blue-950 text-white font-bold rounded-full shadow-lg transition duration-300 transform hover:scale-110 hover:shadow-xl flex items-center justify-center"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                        </button>
                        <div class="absolute right-0 top-full mt-2 bg-gray-900 text-white text-sm font-semibold py-2 px-3 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                            Add beneficiary
                        </div>
                    </div>
                </div>

                <!-- Program Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Status</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">{{ ucfirst($program->status) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Total Beneficiaries</p>
                            <p class="text-lg font-bold text-blue-600 mt-1">{{ $programBeneficiaries->count() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Target</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">{{ $program->target_beneficiaries ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Progress</p>
                            @php
                                $progress = $program->target_beneficiaries > 0 ? round(($programBeneficiaries->count() / $program->target_beneficiaries) * 100) : 0;
                            @endphp
                            <p class="text-lg font-bold text-green-600 mt-1">{{ $progress }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Beneficiaries Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Contact</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Category</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-right font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($programBeneficiaries as $beneficiary)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $beneficiary->full_name }}</td>
                                        <td class="px-6 py-4 text-gray-600">
                                            <div class="text-xs">{{ $beneficiary->email ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $beneficiary->phone ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold">
                                                {{ ucfirst($beneficiary->beneficiary_category ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ 
                                                $beneficiary->status === 'active' ? 'bg-green-100 text-green-800' :
                                                ($beneficiary->status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                                                ($beneficiary->status === 'graduated' ? 'bg-blue-100 text-blue-800' :
                                                'bg-red-100 text-red-800'))
                                            }}">
                                                {{ ucfirst($beneficiary->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button 
                                                    wire:click="editBeneficiaryInProgram({{ $beneficiary->id }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-blue-600 hover:bg-blue-50 rounded transition text-xs font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button 
                                                    wire:click="removeBeneficiaryFromProgram({{ $beneficiary->id }})"
                                                    onclick="return confirm('Remove this beneficiary from the program?')"
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-red-600 hover:bg-red-50 rounded transition text-xs font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H4a1 1 0 000 2h1l1.867 12.142a2 2 0 001.995 1.858h8.276a2 2 0 001.995-1.858L19 9h1a1 1 0 100-2z" />
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            No beneficiaries enrolled in this program yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Assign Beneficiaries Modal -->
    @if($showAssignModal && $selectedProgramId)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full my-8">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-400">
                    <h2 class="text-xl font-bold">Add Beneficiaries to {{ $program->title ?? 'Program' }}</h2>
                    <button 
                        wire:click="closeAssignModal"
                        class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Beneficiaries</label>
                        <input 
                            type="text" 
                            wire:model.live="assignSearch" 
                            placeholder="Search by name, email..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Available Beneficiaries List -->
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($availableBeneficiaries as $beneficiary)
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                                <input 
                                    type="checkbox" 
                                    wire:model="selectedBeneficiaries" 
                                    value="{{ $beneficiary->id }}"
                                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                />
                                <div class="ml-3 flex-1">
                                    <p class="font-medium text-gray-900">{{ $beneficiary->full_name }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                No available beneficiaries. All beneficiaries are already enrolled in this program.
                            </div>
                        @endforelse
                    </div>

                    <!-- Selected Count -->
                    @if(!empty($selectedBeneficiaries))
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-sm text-blue-800"><strong>{{ count($selectedBeneficiaries) }}</strong> beneficiary(ies) selected</p>
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            wire:click="closeAssignModal"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                            Cancel
                        </button>
                        <button 
                            wire:click="assignBeneficiariesToProgram"
                            :disabled="empty($selectedBeneficiaries)"
                            class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Assign Beneficiaries
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Beneficiary Modal -->
    @if($showAddBeneficiaryModal && $selectedProgramId)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full my-8">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-400">
                    <h2 class="text-xl font-bold">Add New Beneficiary</h2>
                    <button 
                        wire:click="closeAddBeneficiaryModal"
                        class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" wire:model="first_name" placeholder="First name" pattern="^[a-zA-Z\s]+$" title="Only letters are allowed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" wire:model="middle_name" placeholder="Middle name" pattern="^[a-zA-Z\s]*$" title="Only letters are allowed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" wire:model="last_name" placeholder="Last name" pattern="^[a-zA-Z\s]+$" title="Only letters are allowed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="email" placeholder="Email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" wire:model="phone" placeholder="09xxxxxxxxx" pattern="^09\d{9}$" maxlength="11" inputmode="numeric" title="Phone number must be 11 digits (09xxxxxxxxx)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" wire:model="date_of_birth" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select wire:model="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Address Information</h4>
                        <input type="text" wire:model="address" placeholder="Street address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent mb-3" />
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                <input type="text" wire:model="barangay" placeholder="Barangay" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                                <input type="text" wire:model="municipality" placeholder="Municipality" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <input type="text" wire:model="province" placeholder="Province" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                        </div>
                    </div>

                    <!-- Community & Category Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Community & Category</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Community</label>
                                <select wire:model="community_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select community</option>
                                    @foreach($communities as $community)
                                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Beneficiary Category</label>
                                <select wire:model="beneficiary_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select category</option>
                                    <option value="Farmers">Farmers</option>
                                    <option value="Women">Women</option>
                                    <option value="Youth">Youth</option>
                                    <option value="Indigenous People">Indigenous People</option>
                                    <option value="PWD">PWD (Persons with Disability)</option>
                                    <option value="Elderly">Elderly</option>
                                    <option value="Children">Children</option>
                                    <option value="Students">Students</option>
                                    <option value="Out of School Youth">Out of School Youth</option>
                                    <option value="Urban Poor">Urban Poor</option>
                                    <option value="Small Entrepreneurs">Small Entrepreneurs</option>
                                    <option value="Unemployed">Unemployed</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Economic Profile Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Economic Profile</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Income (₱)</label>
                                <input type="number" wire:model="monthly_income" placeholder="Monthly income" step="0.01" min="0" max="9999999" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                <input type="text" wire:model="occupation" placeholder="Current occupation/livelihood" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                        </div>
                    </div>

                    <!-- Education & Family Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Education & Family</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Educational Attainment</label>
                                <select wire:model="educational_attainment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select education level</option>
                                    <option value="no_formal_education">No Formal Education</option>
                                    <option value="elementary">Elementary</option>
                                    <option value="high_school">High School</option>
                                    <option value="vocational">Vocational</option>
                                    <option value="college">College</option>
                                    <option value="graduate_studies">Graduate Studies</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                                <select wire:model="marital_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select marital status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Dependents</label>
                            <input type="number" wire:model="number_of_dependents" placeholder="Number of dependents" min="0" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="border-t pt-4 mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="graduated">Graduated</option>
                            <option value="dropout">Dropout</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 pt-4 px-6 pb-6 border-t">
                    <button 
                        wire:click="closeAddBeneficiaryModal"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button 
                        wire:click="saveBeneficiaryAndAssignToProgram"
                        class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Create & Assign
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Beneficiary Modal -->
    @if($showEditBeneficiaryModal && $editingBeneficiaryId)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full my-8">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-400">
                    <h2 class="text-xl font-bold">Edit Beneficiary</h2>
                    <button 
                        wire:click="closeEditBeneficiaryModal"
                        class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" wire:model="first_name" placeholder="First name" pattern="^[a-zA-Z\s]+$" title="Only letters are allowed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" wire:model="middle_name" placeholder="Middle name" pattern="^[a-zA-Z\s]*$" title="Only letters are allowed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" wire:model="last_name" placeholder="Last name" pattern="^[a-zA-Z\s]+$" title="Only letters are allowed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="email" placeholder="Email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" wire:model="phone" placeholder="09xxxxxxxxx" pattern="^09\d{9}$" maxlength="11" inputmode="numeric" title="Phone number must be 11 digits (09xxxxxxxxx)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" wire:model="date_of_birth" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select wire:model="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Address Information</h4>
                        <input type="text" wire:model="address" placeholder="Street address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent mb-3" />
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                <input type="text" wire:model="barangay" placeholder="Barangay" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                                <input type="text" wire:model="municipality" placeholder="Municipality" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <input type="text" wire:model="province" placeholder="Province" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                        </div>
                    </div>

                    <!-- Community & Category Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Community & Category</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Community</label>
                                <select wire:model="community_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select community</option>
                                    @foreach($communities as $community)
                                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Beneficiary Category</label>
                                <select wire:model="beneficiary_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select category</option>
                                    <option value="Farmers">Farmers</option>
                                    <option value="Women">Women</option>
                                    <option value="Youth">Youth</option>
                                    <option value="Indigenous People">Indigenous People</option>
                                    <option value="PWD">PWD (Persons with Disability)</option>
                                    <option value="Elderly">Elderly</option>
                                    <option value="Children">Children</option>
                                    <option value="Students">Students</option>
                                    <option value="Out of School Youth">Out of School Youth</option>
                                    <option value="Urban Poor">Urban Poor</option>
                                    <option value="Small Entrepreneurs">Small Entrepreneurs</option>
                                    <option value="Unemployed">Unemployed</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Economic Profile Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Economic Profile</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Income</label>
                                <input type="number" wire:model="monthly_income" placeholder="0" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                <input type="text" wire:model="occupation" placeholder="Occupation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                            </div>
                        </div>
                    </div>

                    <!-- Education & Family Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Education & Family</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Educational Attainment</label>
                                <select wire:model="educational_attainment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select level</option>
                                    <option value="No formal education">No formal education</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="High School">High School</option>
                                    <option value="College">College</option>
                                    <option value="Vocational">Vocational</option>
                                    <option value="Graduate">Graduate</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                                <select wire:model="marital_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Dependents</label>
                            <input type="number" wire:model="number_of_dependents" placeholder="0" min="0" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent" />
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Status</h4>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="graduated">Graduated</option>
                            <option value="dropout">Dropout</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 pt-4 px-6 pb-6 border-t">
                    <button 
                        wire:click="closeEditBeneficiaryModal"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button 
                        wire:click="updateBeneficiaryInProgram"
                        class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Update
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full">
                <!-- Modal Content -->
                <div class="p-8 text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="w-16 h-16 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Success!</h2>
                    <p class="text-gray-600 mb-6">{{ $successMessage }}</p>
                    <button 
                        wire:click="$set('showSuccessModal', false)"
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition">
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
