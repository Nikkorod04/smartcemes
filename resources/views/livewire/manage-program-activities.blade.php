<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Activity Tracking</h1>
                <p class="text-gray-600 mt-1">{{ $program->title ?? 'Program Activities' }}</p>
            </div>
            <button 
                wire:click="openActivityForm"
                class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-lg transition"
            >
                + New Activity
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input 
                    type="text"
                    wire:model.live="searchTerm"
                    placeholder="Search activities..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <!-- Status Filter -->
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

            <!-- Page Size -->
            <div>
                <select 
                    wire:model.live="pageSize"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
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

    <!-- Activities List -->
    <div class="px-6 py-6 space-y-4">
        @forelse ($activities as $activity)
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Activity Header -->
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $activity->activity_name }}</h3>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ 
                                $activity->status === 'completed' ? 'bg-green-100 text-green-800' :
                                ($activity->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                ($activity->status === 'planned' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-gray-100 text-gray-800'))
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                            </span>
                        </div>

                        <!-- Activity Details Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-3 text-sm">
                            <div>
                                <p class="text-gray-500">Date & Time</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $activity->activity_date->format('M d, Y') }}<br>
                                    {{ $activity->start_time }} - {{ $activity->end_time }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Location</p>
                                <p class="font-semibold text-gray-900">{{ $activity->location ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Target Attendees</p>
                                <p class="font-semibold text-gray-900">{{ $activity->target_attendees }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Facilitators</p>
                                <p class="font-semibold text-gray-900">{{ $activity->facilitatorRecords()->count() }}</p>
                            </div>
                        </div>

                        <!-- Attendance Summary Badge -->
                        @php
                            $summary = $activity->getAttendanceSummary();
                        @endphp
                        @if ($summary['total'] > 0)
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm font-medium text-blue-900">
                                    Attendance: <span class="font-bold">{{ $summary['present'] + $summary['late'] }}/{{ $summary['total'] }}</span> 
                                    ({{ $summary['attendance_rate'] }}%)
                                </p>
                                <div class="flex gap-2 mt-2 text-xs">
                                    <span class="text-green-700">✓ Present: {{ $summary['present'] }}</span>
                                    <span class="text-orange-700">⏱ Late: {{ $summary['late'] }}</span>
                                    <span class="text-red-700">✕ Absent: {{ $summary['absent'] }}</span>
                                    <span class="text-gray-700">~ Excused: {{ $summary['excused'] }}</span>
                                </div>
                            </div>
                        @else
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">No attendance data yet</p>
                            </div>
                        @endif

                        <!-- Description -->
                        @if ($activity->description)
                            <p class="mt-3 text-gray-700 text-sm">{{ $activity->description }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2 ml-4">
                        <button 
                            wire:click="editActivity({{ $activity->id }})"
                            class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-3 py-1 rounded text-sm font-medium transition"
                        >
                            Edit
                        </button>
                        <button 
                            wire:click="openAttendanceModal({{ $activity->id }})"
                            class="bg-green-100 text-green-800 hover:bg-green-200 px-3 py-1 rounded text-sm font-medium transition"
                        >
                            Log Attendance
                        </button>
                        <button 
                            wire:click="openAttendanceSummary({{ $activity->id }})"
                            class="bg-purple-100 text-purple-800 hover:bg-purple-200 px-3 py-1 rounded text-sm font-medium transition"
                        >
                            Summary
                        </button>
                        <button 
                            wire:click="deleteActivity({{ $activity->id }})"
                            onclick="return confirm('Delete this activity?')"
                            class="bg-red-100 text-red-800 hover:bg-red-200 px-3 py-1 rounded text-sm font-medium transition"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No activities yet. Create one to get started!</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    </div>

    <!-- Activity Form Modal -->
    @if ($showActivityForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $editingActivityId ? 'Edit Activity' : 'New Activity' }}
                    </h2>
                    <button 
                        wire:click="$set('showActivityForm', false)"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
                    >
                        ✕
                    </button>
                </div>

                <!-- Modal Content -->
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Activity Date *</label>
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
                                    class="flex-1 py-2 px-3 rounded-lg font-semibold transition duration-200 border-2 {{ $status === $val ? 'bg-blue-600 text-white border-blue-700' : 'bg-gray-50 text-gray-800 border-gray-300 hover:bg-gray-100' }}"
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
                            rows="3"
                            placeholder="Activity details..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Facilitators Section -->
                    <div class="border-t pt-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Facilitators</h4>
                        <div class="flex gap-2 mb-3">
                            <input 
                                type="text"
                                wire:model="new_facilitator_name"
                                placeholder="Facilitator name"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                            />
                            <select 
                                wire:model="new_facilitator_role"
                                class="px-4 py-2 border border-gray-300 rounded-lg"
                            >
                                <option value="instructor">Instructor</option>
                                <option value="assistant">Assistant</option>
                                <option value="coordinator">Coordinator</option>
                            </select>
                            <button 
                                type="button"
                                wire:click="addFacilitator"
                                class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg"
                            >
                                Add
                            </button>
                        </div>

                        @if (!empty($facilitators))
                            <div class="space-y-2">
                                @foreach ($facilitators as $index => $facilitator)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                        <span class="text-gray-800">
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

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea 
                            wire:model="notes"
                            rows="2"
                            placeholder="Additional notes..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="$set('showActivityForm', false)"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition"
                        >
                            {{ $editingActivityId ? 'Update Activity' : 'Create Activity' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Attendance Modal -->
    @if ($showAttendanceModal && $selectedActivityId)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
                    <h2 class="text-2xl font-bold text-gray-900">Log Attendance</h2>
                    <button 
                        wire:click="closeAttendanceModal"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
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
                            class="bg-green-100 hover:bg-green-200 text-green-800 px-4 py-2 rounded-lg font-medium"
                        >
                            Mark All Present
                        </button>
                        <button 
                            wire:click="toggleAllAbsent"
                            class="bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded-lg font-medium"
                        >
                            Mark All Absent
                        </button>
                    </div>

                    <!-- Attendance Checkboxes -->
                    <div class="space-y-2 max-h-[60vh] overflow-y-auto">
                        @foreach ($attendanceData as $beneficiary_id => $record)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $record['name'] }}</p>
                                </div>
                                <div class="flex gap-2">
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
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Save Attendance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Attendance Summary Modal -->
    @if ($showAttendanceSummary && $selectedActivityId)
        @php
            $activity = \App\Models\ProgramActivity::find($selectedActivityId);
            $summary = $activity->getAttendanceSummary();
            $attendance = \App\Models\ActivityAttendance::where('program_activity_id', $selectedActivityId)->get();
        @endphp
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">Attendance Summary</h2>
                    <button 
                        wire:click="closeAttendanceSummary"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
                    >
                        ✕
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <p class="text-green-600 font-bold text-2xl">{{ $summary['present'] }}</p>
                            <p class="text-gray-600 text-sm">Present</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg text-center">
                            <p class="text-orange-600 font-bold text-2xl">{{ $summary['late'] }}</p>
                            <p class="text-gray-600 text-sm">Late</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg text-center">
                            <p class="text-red-600 font-bold text-2xl">{{ $summary['absent'] }}</p>
                            <p class="text-gray-600 text-sm">Absent</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-gray-600 font-bold text-2xl">{{ $summary['excused'] }}</p>
                            <p class="text-gray-600 text-sm">Excused</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <p class="text-blue-600 font-bold text-2xl">{{ $summary['attendance_rate'] }}%</p>
                            <p class="text-gray-600 text-sm">Rate</p>
                        </div>
                    </div>

                    <!-- Attendance List -->
                    <div class="space-y-2 max-h-[60vh] overflow-y-auto">
                        <h4 class="font-semibold text-gray-900 mb-3">Attendance Details</h4>
                        @foreach ($attendance as $record)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $record->beneficiary->full_name }}</p>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ 
                                    $record->attendance_status === 'present' ? 'bg-green-100 text-green-800' :
                                    ($record->attendance_status === 'late' ? 'bg-orange-100 text-orange-800' :
                                    ($record->attendance_status === 'absent' ? 'bg-red-100 text-red-800' :
                                    'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst($record->attendance_status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Close Button -->
                    <div class="mt-6 pt-4 border-t">
                        <button 
                            wire:click="closeAttendanceSummary"
                            class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
