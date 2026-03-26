<div class="space-y-4">
    <!-- Trigger Button (to open modal from page) -->
    <button 
        wire:click="openViewActivitiesModal"
        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition"
    >
        📊 View Activities
    </button>

    <!-- VIEW ACTIVITIES MODAL -->
    @if ($showViewActivitiesModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-500">
                    <div>
                        <h2 class="text-2xl font-bold">Activity Tracking</h2>
                        <p class="text-blue-100 text-sm">{{ $program->title ?? 'Program Activities' }}</p>
                    </div>
                    <button 
                        wire:click="closeViewActivitiesModal"
                        class="text-white hover:text-gray-200 text-2xl font-bold transition"
                    >
                        ✕
                    </button>
                </div>

                <!-- Filters & Search -->
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input 
                                type="text"
                                wire:model.live="searchTerm"
                                placeholder="Search activities..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <select 
                                wire:model.live="filterStatus"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Status</option>
                                <option value="planned">Planned</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <button 
                                wire:click="openActivityForm"
                                class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition"
                            >
                                + New Activity
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notification -->
                @if (!empty($notification))
                    <div class="mx-6 mt-4 px-4 py-3 bg-{{ $notificationType }}-100 text-{{ $notificationType }}-800 rounded-lg flex items-center justify-between">
                        <span>{{ $notification }}</span>
                        <button wire:click="$set('notification', '')" class="text-{{ $notificationType }}-600 hover:text-{{ $notificationType }}-800">✕</button>
                    </div>
                @endif

                <!-- Activities List (Scrollable) -->
                <div class="overflow-y-auto flex-1 px-6 py-4 space-y-3">
                    @forelse ($activities as $activity)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <!-- Activity Header -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-base font-bold text-gray-900">{{ $activity->activity_name }}</h3>
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ 
                                            $activity->status === 'completed' ? 'bg-green-100 text-green-800' :
                                            ($activity->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                            ($activity->status === 'planned' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-gray-100 text-gray-800'))
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                        </span>
                                    </div>

                                    <!-- Activity Details -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-gray-600 mb-2">
                                        <div>
                                            <p class="font-medium">{{ $activity->activity_date->format('M d, Y') }}</p>
                                            <p class="text-gray-500">{{ $activity->start_time }} - {{ $activity->end_time }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $activity->location ?? 'N/A' }}</p>
                                            <p class="text-gray-500">Location</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $activity->target_attendees }}</p>
                                            <p class="text-gray-500">Target</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $activity->facilitatorRecords()->count() }} Facilitators</p>
                                        </div>
                                    </div>

                                    @if ($activity->description)
                                        <p class="text-xs text-gray-600 line-clamp-2">{{ $activity->description }}</p>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-1">
                                    <button 
                                        wire:click="editActivity({{ $activity->id }})"
                                        class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-2 py-1 rounded text-xs font-medium transition whitespace-nowrap"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="openAttendanceModal({{ $activity->id }})"
                                        class="bg-green-100 text-green-800 hover:bg-green-200 px-2 py-1 rounded text-xs font-medium transition whitespace-nowrap"
                                    >
                                        Log
                                    </button>
                                    <button 
                                        wire:click="deleteActivity({{ $activity->id }})"
                                        onclick="return confirm('Delete this activity?')"
                                        class="bg-red-100 text-red-800 hover:bg-red-200 px-2 py-1 rounded text-xs font-medium transition whitespace-nowrap"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No activities found. Create one to get started!</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if ($activities->hasPages())
                        <div class="mt-4">
                            {{ $activities->links() }}
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                    <button 
                        wire:click="closeViewActivitiesModal"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ADD/EDIT ACTIVITY MODAL -->
    @if ($showActivityForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-500">
                    <h2 class="text-2xl font-bold">
                        {{ $editingActivityId ? 'Edit Activity' : 'New Activity' }}
                    </h2>
                    <button 
                        wire:click="closeActivityForm"
                        class="text-white hover:text-gray-200 text-2xl font-bold transition"
                    >
                        ✕
                    </button>
                </div>

                <!-- Form Content -->
                <form wire:submit.prevent="saveActivity" class="p-6 space-y-4">
                    <!-- Activity Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity Name *</label>
                        <input 
                            type="text"
                            wire:model="activity_name"
                            placeholder="e.g., Farmer Training Workshop"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                        />
                        @error('activity_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                            <input 
                                type="date"
                                wire:model="activity_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('activity_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                            <input 
                                type="time"
                                wire:model="start_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                            <input 
                                type="time"
                                wire:model="end_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Location & Target -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input 
                                type="text"
                                wire:model="location"
                                placeholder="e.g., Barangay Hall"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Attendees</label>
                            <input 
                                type="number"
                                wire:model="target_attendees"
                                min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <div class="flex gap-2">
                            @foreach (['planned' => 'Planned', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                                <button 
                                    type="button"
                                    wire:click="$set('status', '{{ $val }}')"
                                    class="flex-1 py-2 px-3 rounded-lg font-semibold transition duration-200 border-2 {{ $status === $val ? 'bg-green-600 text-white border-green-700' : 'bg-gray-50 text-gray-800 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            wire:model="description"
                            rows="2"
                            placeholder="Activity details..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Facilitators -->
                    <div class="border-t pt-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Facilitators</h4>
                        <div class="flex gap-2 mb-3">
                            <input 
                                type="text"
                                wire:model="new_facilitator_name"
                                placeholder="Facilitator name"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm"
                            />
                            <select 
                                wire:model="new_facilitator_role"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm"
                            >
                                <option value="instructor">Instructor</option>
                                <option value="assistant">Assistant</option>
                                <option value="coordinator">Coordinator</option>
                            </select>
                            <button 
                                type="button"
                                wire:click="addFacilitator"
                                class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg font-medium text-sm"
                            >
                                Add
                            </button>
                        </div>

                        @if (!empty($facilitators))
                            <div class="space-y-2">
                                @foreach ($facilitators as $index => $facilitator)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                        <span class="text-sm text-gray-800">
                                            {{ $facilitator['facilitator_name'] }} 
                                            <span class="text-xs text-gray-500">({{ ucfirst($facilitator['facilitator_role']) }})</span>
                                        </span>
                                        <button 
                                            type="button"
                                            wire:click="removeFacilitator({{ $index }})"
                                            class="text-red-600 hover:text-red-800 font-bold"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Form Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="closeActivityForm"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                        >
                            {{ $editingActivityId ? 'Update Activity' : 'Add Activity' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ATTENDANCE MODAL -->
    @if ($showAttendanceModal && $selectedActivityId)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-5 flex justify-between items-center border-b-4 border-yellow-500">
                    <h2 class="text-2xl font-bold">Log Attendance</h2>
                    <button 
                        wire:click="closeAttendanceModal"
                        class="text-white hover:text-gray-200 text-2xl font-bold transition"
                    >
                        ✕
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Bulk Actions -->
                    <div class="flex gap-2 mb-4">
                        <button 
                            wire:click="toggleAllPresent"
                            class="bg-green-100 hover:bg-green-200 text-green-800 px-4 py-2 rounded-lg font-medium text-sm"
                        >
                            Mark All Present
                        </button>
                        <button 
                            wire:click="toggleAllAbsent"
                            class="bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded-lg font-medium text-sm"
                        >
                            Mark All Absent
                        </button>
                    </div>

                    <!-- Attendance List -->
                    <div class="space-y-2 max-h-[50vh] overflow-y-auto">
                        @foreach ($attendanceData as $beneficiary_id => $record)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $record['name'] }}</p>
                                </div>
                                <select 
                                    wire:model="attendanceData.{{ $beneficiary_id }}.status"
                                    class="px-3 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500"
                                >
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 mt-6 pt-4 border-t">
                        <button 
                            wire:click="closeAttendanceModal"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="saveAttendance"
                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Save Attendance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
