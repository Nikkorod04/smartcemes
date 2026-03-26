<div class="min-h-screen bg-gradient-to-br from-white to-gray-50 px-8 py-6">
    <div class="w-full">
        <!-- Error Notification -->
        @if ($notification && $notificationType === 'error')
            <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">
                {{ $notification }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input 
                        type="text" 
                        wire:model.live="searchTerm"
                        placeholder="Search programs..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select 
                        wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="planning">Planning</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button 
                        wire:click="clearFilters"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200"
                    >
                        Clear Filters
                    </button>
                    <div class="relative group">
                        <button 
                            wire:click="openForm"
                            class="w-10 h-10 bg-blue-900 hover:bg-blue-950 text-white font-bold rounded-full shadow-lg transition duration-300 transform hover:scale-110 hover:shadow-xl flex items-center justify-center"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                        </button>
                        <div class="absolute right-0 top-full mt-2 bg-gray-900 text-white text-sm font-semibold py-2 px-3 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                            Add a new program
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse ($programs as $program)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <!-- Cover Image -->
                    @if ($program->cover_image)
                        <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                            </svg>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $program->title }}</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                $program->status === 'active' ? 'bg-green-100 text-green-800' :
                                ($program->status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                                ($program->status === 'planning' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-blue-100 text-blue-800'))
                            }}">
                                {{ ucfirst($program->status) }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $program->description }}</p>

                        @php
                            $communityIds = $program->related_communities;
                            // Ensure it's an array
                            if (is_string($communityIds)) {
                                $communityIds = json_decode($communityIds, true) ?? [];
                            }
                            $communityIds = is_array($communityIds) ? $communityIds : [];
                        @endphp

                        @if (count($communityIds) > 0)
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Communities:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($communityIds as $communityId)
                                        @php
                                            $community = $communities[$communityId] ?? null;
                                        @endphp
                                        @if ($community)
                                            <span class="px-2 py-1 text-xs bg-blue-50 text-blue-700 rounded">{{ $community }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Single Manage Program Button -->
                        <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                            <a 
                                href="{{ route('programs.manage', $program->id) }}"
                                class="text-blue-600 hover:text-yellow-400 font-semibold text-sm transition duration-200"
                            >
                                Manage Program
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No extension programs found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($programs->hasPages())
            <div class="mb-6">
                {{ $programs->links() }}
            </div>
        @endif

        <!-- Create/Edit Modal -->
        @if ($showForm)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-screen flex flex-col">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-500 flex-shrink-0">
                        <h2 class="text-xl font-bold">
                            @if ($editingId)
                                Edit Extension Program
                            @else
                                Create New Extension Program
                            @endif
                        </h2>
                        <button 
                            wire:click="closePage"
                            class="text-white hover:text-gray-200"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body - Scrollable -->
                    <form wire:submit="save" class="p-6 space-y-4 overflow-y-auto">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Status *</label>
                            <div class="flex gap-2">
                                <button 
                                    type="button"
                                    wire:click="$set('status', 'active')"
                                    class="flex-1 py-2 px-3 rounded-lg font-semibold transition duration-200 border-2 {{ $status === 'active' ? 'bg-green-500 text-white border-green-600' : 'bg-green-50 text-green-800 border-green-200 hover:bg-green-100' }}"
                                >
                                    Active
                                </button>
                                <button 
                                    type="button"
                                    wire:click="$set('status', 'inactive')"
                                    class="flex-1 py-2 px-3 rounded-lg font-semibold transition duration-200 border-2 {{ $status === 'inactive' ? 'bg-gray-500 text-white border-gray-600' : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-gray-200' }}"
                                >
                                    Inactive
                                </button>
                                <button 
                                    type="button"
                                    wire:click="$set('status', 'planning')"
                                    class="flex-1 py-2 px-3 rounded-lg font-semibold transition duration-200 border-2 {{ $status === 'planning' ? 'bg-yellow-500 text-white border-yellow-600' : 'bg-yellow-50 text-yellow-800 border-yellow-200 hover:bg-yellow-100' }}"
                                >
                                    Planning
                                </button>
                                <button 
                                    type="button"
                                    wire:click="$set('status', 'completed')"
                                    class="flex-1 py-2 px-3 rounded-lg font-semibold transition duration-200 border-2 {{ $status === 'completed' ? 'bg-blue-600 text-white border-blue-700' : 'bg-blue-50 text-blue-800 border-blue-200 hover:bg-blue-100' }}"
                                >
                                    Completed
                                </button>
                            </div>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Title *</label>
                            <input 
                                type="text" 
                                wire:model="title"
                                placeholder="Enter program title"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea 
                                wire:model="description"
                                placeholder="Enter program description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Timeline Section -->
                        <div class="border-t-2 border-gray-200 pt-4 mt-4">
                            <h3 class="font-semibold text-gray-900 mb-3">Program Timeline</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Planned Start Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Planned Start Date</label>
                                    <input 
                                        type="date" 
                                        wire:model="planned_start_date"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                                    />
                                    @error('planned_start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Planned End Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Planned End Date</label>
                                    <input 
                                        type="date" 
                                        wire:model="planned_end_date"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                                    />
                                    @error('planned_end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Beneficiaries Section -->
                        <div class="border-t-2 border-gray-200 pt-4 mt-4">
                            <h3 class="font-semibold text-gray-900 mb-3">Beneficiaries</h3>
                            
                            <!-- Target Beneficiaries -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Number of Beneficiaries</label>
                                <input 
                                    type="number" 
                                    wire:model="target_beneficiaries"
                                    placeholder="Enter target number"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                                />
                                @error('target_beneficiaries') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Beneficiary Categories -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Beneficiary Categories</label>
                                <div class="space-y-2 border border-gray-300 rounded-lg p-3 bg-gray-50 max-h-40 overflow-y-auto">
                                    @forelse ($beneficiary_category_options as $key => $label)
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox"
                                                wire:change="toggleBeneficiaryCategory('{{ $key }}')"
                                                @checked(in_array($key, $selected_beneficiary_categories))
                                                class="rounded border-gray-300"
                                            />
                                            <span class="ml-2 text-gray-700">{{ $label }}</span>
                                        </label>
                                    @empty
                                        <p class="text-gray-500 text-sm">No categories available</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>





                        <!-- Cover Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                            <input 
                                type="file" 
                                wire:model="cover_image"
                                accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                            />
                            @error('cover_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Related Communities -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Related Communities</label>
                            <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50">
                                @foreach ($communities as $id => $name)
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox"
                                            wire:change="toggleCommunity({{ $id }})"
                                            @checked(in_array($id, $related_communities))
                                            class="rounded border-gray-300"
                                        />
                                        <span class="ml-2 text-gray-700">{{ $name }}</span>
                                    </label>
                                @endforeach
                                @if (empty($communities))
                                    <p class="text-gray-500 text-sm">No communities available</p>
                                @endif
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea 
                                wire:model="notes"
                                placeholder="Additional notes"
                                rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button 
                                type="button"
                                wire:click="closePage"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                @if ($editingId)
                                    Update Program
                                @else
                                    Create Program
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($showDeleteModal)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full">
                    <div class="border-b-4 border-yellow-500 px-6 py-4 bg-gray-50">
                        <h3 class="text-lg font-bold text-blue-900">Confirm Deletion</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-6">Are you sure you want to delete this extension program? This action cannot be undone.</p>
                        
                        <div class="flex gap-3">
                            <button 
                                wire:click="$set('showDeleteModal', false)"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                Cancel
                            </button>
                            <button 
                                wire:click="deleteProgram"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Modal -->
        @if ($showSuccessModal)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full">
                    <div class="border-b-4 border-yellow-500 px-6 py-4 bg-gradient-to-r from-blue-50 to-transparent">
                        <h3 class="text-lg font-bold text-blue-900">Success!</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-blue-500 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-gray-600 mb-6">{{ $notification }}</p>
                            
                            <button 
                                wire:click="closeSuccessModal"
                                class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Program Plan Modal -->
        @if ($showProgramPlanModal && $selectedProgramId)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
                <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full my-8">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-400 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h2 class="text-xl font-bold">Program Plan</h2>
                    </div>
                        <button 
                            type="button"
                            wire:click="closeProgramPlanModal"
                            class="text-white hover:text-gray-200"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="overflow-y-auto max-h-[calc(100vh-200px)]">
                        @livewire('ManageProgramLogicModel', ['program_id' => $selectedProgramId])
                    </div>
                </div>
            </div>
        @endif

        <!-- Status Check Modal -->
        @if ($showStatusCheckModal && $selectedProgramId)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
                <div class="bg-white rounded-lg shadow-2xl max-w-5xl w-full my-8">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-400 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <h2 class="text-xl font-bold">Program Baseline Assessments</h2>
                    </div>
                        <button 
                            type="button"
                            wire:click="closeStatusCheckModal"
                            class="text-white hover:text-gray-200"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="overflow-y-auto max-h-[calc(100vh-200px)]">
                        @livewire('ManageProgramBaseline', ['program_id' => $selectedProgramId])
                    </div>
                </div>
            </div>
        @endif

        <!-- Activities Modal -->
        @if ($showActivitiesModal && $selectedProgramId)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
                <div class="bg-white rounded-lg shadow-2xl max-w-5xl w-full my-8">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-400 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h2 class="text-xl font-bold">Phase 2: Activity Tracking</h2>
                        </div>
                        <button 
                            type="button"
                            wire:click="closeActivitiesModal"
                            class="text-white hover:text-gray-200"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="overflow-y-auto max-h-[calc(100vh-200px)]">
                        @livewire('ManageProgramActivities', ['program_id' => $selectedProgramId])
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
