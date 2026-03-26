<div class="min-h-screen bg-gradient-to-br from-white to-gray-50 px-8 py-6">
    <!-- Notification -->
    @if($notification)
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition:leave="transition ease-in duration-300"
            :class="@json($notificationType) === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'"
            class="max-w-7xl mx-auto mb-6 border rounded-lg p-4 flex items-start gap-3"
        >
            <div>
                @if($notificationType === 'success')
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </div>
            <p :class="@json($notificationType) === 'success' ? 'text-green-800' : 'text-red-800'">
                {{ $notification }}
            </p>
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="max-w-7xl mx-auto mb-6 bg-white rounded-lg shadow-md p-4 md:p-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input 
                    wire:model.live="searchTerm"
                    type="text" 
                    placeholder="Search by name, municipality..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select 
                    wire:model.live="filterStatus"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                <select 
                    wire:model.live="filterProvince"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
                    <option value="">All Provinces</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Items per Page</label>
                <div class="flex items-center gap-2">
                    <select 
                        wire:model.live="pageSize"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <div class="relative group">
                        <button 
                            wire:click="openForm"
                            class="w-10 h-10 bg-blue-900 hover:bg-blue-950 text-white font-bold rounded-full shadow-lg transition duration-300 transform hover:scale-110 hover:shadow-xl flex items-center justify-center flex-shrink-0"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                        </button>
                        <div class="absolute right-0 top-full mt-2 bg-gray-900 text-white text-sm font-semibold py-2 px-3 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                            Add a new community
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($searchTerm) || !empty($filterStatus) || !empty($filterProvince))
            <button 
                wire:click="clearFilters"
                class="mt-4 text-sm text-purple-600 hover:text-purple-800 font-medium"
            >
                Clear Filters
            </button>
        @endif
    </div>

    <!-- Communities Table -->
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        @if($communities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Community Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Municipality</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Province</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contact</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($communities as $community)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $community->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $community->municipality }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $community->province }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($community->contact_person)
                                        <div>{{ $community->contact_person }}</div>
                                    @endif
                                    @if($community->contact_number)
                                        <div class="text-xs text-gray-500">{{ $community->contact_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $community->status_badge }}" style="background-color: {{ $community->status === 'active' ? '#d1fae5' : '#f3f4f6' }}; color: {{ $community->status === 'active' ? '#047857' : '#374151' }};">
                                        {{ $community->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button 
                                            wire:click="viewAssessmentSummary({{ $community->id }})"
                                            class="@if($community->assessmentSummary) text-green-600 hover:text-green-800 @else text-gray-400 cursor-not-allowed @endif font-medium text-sm" 
                                            title="@if($community->assessmentSummary) View Assessment Summary @else No assessment summaries available @endif"
                                            @if(!$community->assessmentSummary) disabled @endif
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                                        </button>
                                        <button 
                                            wire:click="editCommunity({{ $community->id }})"
                                            class="text-blue-600 hover:text-blue-800 font-medium text-sm" title="Edit"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $community->id }})"
                                            class="text-red-600 hover:text-red-800 font-medium text-sm" title="Delete"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $communities->links(data: ['scrollTo' => false]) }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-600 text-lg">No communities found</p>
                <button 
                    wire:click="openForm"
                    class="mt-4 text-purple-600 hover:text-purple-800 font-medium"
                >
                    Create the first community
                </button>
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white p-6 border-b-4 border-yellow-500 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold">
                            {{ $editingId ? 'Edit Community' : 'Add New Community' }}
                        </h2>
                        <button 
                            wire:click="closePage"
                            class="text-white hover:text-gray-200"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body - Scrollable -->
                <div class="p-6 space-y-6 overflow-y-auto">
                    <!-- Row 1: Name & Municipality -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Community Name *</label>
                            <input 
                                wire:model="name"
                                type="text" 
                                placeholder="e.g., Barangay San Juan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Municipality *</label>
                            <input 
                                wire:model="municipality"
                                type="text" 
                                placeholder="e.g., Ormoc City"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('municipality')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Province & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Province *</label>
                            <input 
                                wire:model="province"
                                type="text" 
                                placeholder="e.g., Leyte"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('province')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select 
                                wire:model="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Contact Person & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                            <input 
                                wire:model="contact_person"
                                type="text" 
                                placeholder="Barangay Captain name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('contact_person')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input 
                                wire:model="email"
                                type="email" 
                                placeholder="barangay@example.com"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 4: Contact Number & Address -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input 
                                wire:model="contact_number"
                                type="tel" 
                                placeholder="+63 (555) 123-4567"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('contact_number')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                            <input 
                                wire:model="address"
                                type="text" 
                                placeholder="Street address and landmarks"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                            @error('address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            wire:model="description"
                            rows="3"
                            placeholder="Brief description of the community..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        ></textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secretary Notes</label>
                        <textarea 
                            wire:model="notes"
                            rows="3"
                            placeholder="Internal notes for reference..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        ></textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button 
                        wire:click="closePage"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="save"
                        class="px-6 py-2 bg-gradient-to-r from-blue-900 to-blue-800 hover:from-blue-950 hover:to-blue-900 text-white font-medium rounded-lg"
                    >
                        {{ $editingId ? 'Update Community' : 'Create Community' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $deleteId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full">
                <div class="border-b-4 border-yellow-500 px-6 py-4 bg-gray-50">
                    <h3 class="text-lg font-bold text-blue-900">Confirm Deletion</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-center mb-6">
                        This action cannot be undone. The community will be permanently deleted.
                    </p>
                    <div class="flex gap-3 justify-center">
                        <button 
                            wire:click="$set('showDeleteModal', false)"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="deleteCommunity"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Assessment Summary Modal -->
    @if($showAssessmentModal && $viewingCommunity && $viewingCommunity->assessmentSummary)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 rounded-t-lg flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Assessment Summary - {{ $viewingCommunity->name }}</h2>
                    <button 
                        wire:click="closeAssessmentModal"
                        class="text-white hover:text-yellow-400 transition-colors duration-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6 overflow-y-auto">
                    <x-community-assessment-summary :summary="$viewingCommunity->assessmentSummary" />
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button 
                        wire:click="closeAssessmentModal"
                        class="px-6 py-2 bg-gradient-to-r from-blue-900 to-blue-800 hover:from-blue-950 hover:to-blue-900 text-white hover:text-yellow-400 font-medium rounded-lg transition-colors duration-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>