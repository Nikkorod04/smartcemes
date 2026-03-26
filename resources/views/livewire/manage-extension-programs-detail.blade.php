<div>
    <!-- Program Information Section -->
    <div class="space-y-6">
        <!-- Header with Status -->
        <div class="flex items-start justify-between pb-6 border-b-2 border-gray-200">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
                <p class="text-gray-600 mt-2 max-w-2xl">{{ $description }}</p>
            </div>
            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ 
                $status === 'active' ? 'bg-green-100 text-green-800' :
                ($status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                ($status === 'planning' ? 'bg-yellow-100 text-yellow-800' :
                'bg-blue-100 text-blue-800'))
            }}">
                {{ ucfirst($status) }}
            </span>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Program Details -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Program Details
                        </h3>
                        <button 
                            type="button"
                            wire:click="openEditDetailsModal"
                            class="inline-flex items-center gap-1 px-3 py-1 text-blue-600 hover:text-yellow-400 hover:bg-blue-50 rounded transition text-xs font-semibold"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1">Title</label>
                            <p class="text-gray-700 text-sm">{{ $title }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1">Status</label>
                            <p class="text-gray-700 text-sm capitalize">{{ $status }}</p>
                        </div>
                    </div>
                </div>

                <!-- Goals & Objectives -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Planned Dates
                        </h3>
                        <button 
                            type="button"
                            wire:click="openEditTimelineModal"
                            class="inline-flex items-center gap-1 px-3 py-1 text-blue-600 hover:text-yellow-400 hover:bg-blue-50 rounded transition text-xs font-semibold"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Start Date</label>
                            <p class="text-gray-600 text-sm">{{ $planned_start_date ? \Carbon\Carbon::parse($planned_start_date)->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">End Date</label>
                            <p class="text-gray-600 text-sm">{{ $planned_end_date ? \Carbon\Carbon::parse($planned_end_date)->format('M d, Y') : 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Beneficiary Categories -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3.5a1.5 1.5 0 01-1.5-1.5V5.5A1.5 1.5 0 013.5 4h15A1.5 1.5 0 0120 5.5v12a1.5 1.5 0 01-1.5 1.5zm0 0h4.5a1.5 1.5 0 001.5-1.5v-7a1.5 1.5 0 00-1.5-1.5h-4.5" />
                            </svg>
                            Beneficiary Categories
                        </h3>
                        <button 
                            type="button"
                            wire:click="openEditCategoriesModal"
                            class="inline-flex items-center gap-1 px-3 py-1 text-blue-600 hover:text-yellow-400 hover:bg-blue-50 rounded transition text-xs font-semibold"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($beneficiary_category_options as $key => $label)
                            @if(in_array($key, $selected_beneficiary_categories))
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    {{ $label }}
                                </span>
                            @endif
                        @empty
                            <p class="text-gray-500 text-sm">No categories selected</p>
                        @endforelse
                    </div>
                </div>

                <!-- Communities -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.5m0 0H9m0 0H3.5m0 0H1" />
                            </svg>
                            Related Communities
                        </h3>
                        <button 
                            type="button"
                            wire:click="openEditCommunitiesModal"
                            class="inline-flex items-center gap-1 px-3 py-1 text-blue-600 hover:text-yellow-400 hover:bg-blue-50 rounded transition text-xs font-semibold"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="space-y-2">
                        @if(count($related_communities) > 0)
                            @foreach($communities as $id => $name)
                                @if(in_array($id, $related_communities))
                                    <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded text-sm font-semibold">
                                        {{ $name }}
                                    </span>
                                @endif
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm">No communities selected</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Button -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <button 
                type="button"
                wire:click="confirmDelete"
                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition"
            >
                Delete Program
            </button>
        </div>
    </div>

    <!-- MODALS -->

    <!-- Edit Program Details Modal -->
    @if ($showEditDetailsModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold">Edit Program Details</h2>
                    <button 
                        wire:click="$set('showEditDetailsModal', false)"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="updateProgram" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Title *</label>
                        <input 
                            type="text" 
                            wire:model="title" 
                            placeholder="Enter program title"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        @error('title') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea 
                            wire:model="description" 
                            placeholder="Enter program description"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                        @error('description') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select 
                            wire:model="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="planning">Planning</option>
                            <option value="completed">Completed</option>
                        </select>
                        @error('status') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="$set('showEditDetailsModal', false)"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Timeline Modal -->
    @if ($showEditTimelineModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold">Edit Timeline</h2>
                    <button 
                        wire:click="$set('showEditTimelineModal', false)"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="updateProgram" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input 
                            type="date" 
                            wire:model="planned_start_date" 
                            placeholder="Select start date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input 
                            type="date" 
                            wire:model="planned_end_date" 
                            placeholder="Select end date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="$set('showEditTimelineModal', false)"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Beneficiary Categories Modal -->
    @if ($showEditCategoriesModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold">Edit Beneficiary Categories</h2>
                    <button 
                        wire:click="$set('showEditCategoriesModal', false)"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="updateProgram" class="p-6 space-y-4">
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse ($beneficiary_category_options as $key => $label)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox"
                                    wire:change="toggleBeneficiaryCategory('{{ $key }}')"
                                    @checked(in_array($key, $selected_beneficiary_categories))
                                    class="rounded border-gray-300"
                                />
                                <span class="ml-2 text-gray-700 text-sm">{{ $label }}</span>
                            </label>
                        @empty
                            <p class="text-gray-500 text-sm">No categories available</p>
                        @endforelse
                    </div>

                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="$set('showEditCategoriesModal', false)"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Communities Modal -->
    @if ($showEditCommunitiesModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold">Edit Communities</h2>
                    <button 
                        wire:click="$set('showEditCommunitiesModal', false)"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="updateProgram" class="p-6 space-y-4">
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse ($communities as $id => $name)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox"
                                    wire:change="toggleCommunity({{ $id }})"
                                    @checked(in_array($id, $related_communities))
                                    class="rounded border-gray-300"
                                />
                                <span class="ml-2 text-gray-700 text-sm">{{ $name }}</span>
                            </label>
                        @empty
                            <p class="text-gray-500 text-sm">No communities available</p>
                        @endforelse
                    </div>

                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="$set('showEditCommunitiesModal', false)"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if ($showSuccessModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full">
                <div class="flex justify-center mb-4">
                    <div class="rounded-full bg-green-100 p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-center text-gray-800 font-semibold mb-4">{{ $notification }}</p>
                <button 
                    wire:click="handleSuccessModalClose"
                    class="w-full bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition"
                >
                    OK
                </button>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full">
                <div class="border-b-4 border-yellow-500 px-6 py-4 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Delete Program</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-6">Are you sure you want to delete this program? This action cannot be undone.</p>
                    
                    <div class="flex gap-3">
                        <button 
                            wire:click="closeDeleteModal"
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
</div>

