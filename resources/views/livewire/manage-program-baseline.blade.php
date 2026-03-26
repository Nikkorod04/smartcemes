<div class="py-2 px-3 relative">
    <!-- About Program Baseline Section -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <h3 class="font-semibold text-gray-800 mb-2">About Program Baseline</h3>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>• <strong>Community Baseline:</strong> Document the community's existing conditions before the program starts</li>
            <li>• <strong>Target Indicators:</strong> Set literacy, income, and skill targets that the program aims to achieve</li>
            <li>• <strong>Baseline Date:</strong> Use this date for comparison with endline assessments later</li>
            <li>• <strong>Status Tracking:</strong> Mark as "Draft" while collecting data, "Approved" when finalized</li>
        </ul>
    </div>

    <!-- Baseline Records -->
    <div class="bg-white rounded-lg shadow-md overflow-visible">
        <div class="bg-gray-50 p-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Baseline Records</h2>
            <button wire:click="$set('showModal', true)" type="button"
                class="px-4 py-2 bg-blue-900 text-white font-semibold rounded hover:bg-blue-800 hover:text-yellow-400 transition text-sm">
                + New Baseline
            </button>
        </div>

        @if ($baselines && count($baselines) > 0)
            <div class="divide-y">
                @foreach ($baselines as $baseline)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold {{ $baseline->status_badge }} rounded px-2 py-1">
                                        {{ ucfirst($baseline->status) }}
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        {{ $baseline->baseline_assessment_date?->format('M d, Y') }}
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $baseline->community->name ?? 'Community not specified' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    Target Beneficiaries: {{ $baseline->target_beneficiaries_count ?? 'N/A' }} | 
                                    Target Income: ₱{{ $baseline->target_average_income ? number_format($baseline->target_average_income, 2) : 'N/A' }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $baseline->id }})" type="button"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Edit</button>
                                <button wire:click="delete({{ $baseline->id }})" type="button" onclick="return confirm('Delete this baseline?')"
                                    class="text-red-600 hover:text-red-800 font-semibold text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                <p class="text-sm">No baseline assessments yet. Create one to get started.</p>
            </div>
        @endif
    </div>

    <!-- New/Edit Baseline Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white p-6 flex justify-between items-center sticky top-0">
                    <h3 class="text-lg font-bold">{{ $editingId ? 'Edit Baseline' : 'New Baseline' }}</h3>
                    <button 
                        wire:click="resetForm"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <!-- Community Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Community *</label>
                        <select wire:model="community_id" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Community</option>
                            @foreach ($communities as $community)
                                <option value="{{ $community->id }}">{{ $community->name }}</option>
                            @endforeach
                        </select>
                        @error('community_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Assessment Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Assessment Date *</label>
                        <input type="date" wire:model="baseline_assessment_date" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('baseline_assessment_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Target Beneficiaries -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Beneficiaries</label>
                        <input type="number" wire:model="target_beneficiaries_count" placeholder="e.g., 100" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Target Literacy Level -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Literacy Level (1-5)</label>
                        <input type="number" wire:model="target_literacy_level" min="1" max="5" placeholder="1-5" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Target Average Income -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Avg Income (₱)</label>
                        <input type="number" wire:model="target_average_income" placeholder="e.g., 15000" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Target Skills -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Skills to Develop</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="new_skill" placeholder="Add skill..." @keydown.enter="addSkill" class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="button" wire:click="addSkill" class="px-2 py-2 bg-blue-900 text-white rounded text-sm hover:bg-blue-800 hover:text-yellow-400">+</button>
                        </div>
                        @if ($target_skills && count($target_skills) > 0)
                            <div class="space-y-1 text-xs">
                                @foreach ($target_skills as $index => $skill)
                                    <div class="flex justify-between items-center bg-blue-50 p-2 rounded">
                                        <span>{{ $skill }}</span>
                                        <button type="button" wire:click="removeSkill({{ $index }})" class="text-red-600 text-xs font-bold">×</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="draft">Draft</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Notes</label>
                        <textarea wire:model="notes" rows="2" placeholder="Additional notes..." class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button type="button" wire:click="resetForm" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition">
                            {{ $editingId ? 'Update' : 'Create' }} Baseline
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
