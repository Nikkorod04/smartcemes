<div class="py-2 px-3">
    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <form wire:submit.prevent="save">
            <!-- PROGRAM OVERVIEW Section -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Program Overview</h2>
                    <div class="ml-auto text-sm text-gray-500">Goals, objectives, and budget</div>
                </div>
                <div class="border-l-4 border-gray-500 pl-4 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Goals</label>
                        <textarea wire:model="program_goals" rows="3" placeholder="What long-term impact does this program aim to achieve?"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Objectives</label>
                        <textarea wire:model="program_objectives" rows="3" placeholder="What specific objectives will the program achieve?"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Allocated Budget</label>
                        <input type="number" wire:model="allocated_budget" placeholder="e.g., 50000" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <!-- INPUTS Section -->
            <div id="inputs" class="mb-8">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">1. INPUTS</h2>
                    <div class="ml-auto text-sm text-gray-500">Resources needed to implement the program</div>
                </div>
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Personnel</label>
                            <input type="text" wire:model="inputs_personnel" placeholder="e.g., 2 project managers, 5 facilitators"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Partners</label>
                            <input type="text" wire:model="inputs_partners" placeholder="e.g., Department of Education, NGO X"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Resources/Materials</label>
                            <input type="text" wire:model="inputs_resources" placeholder="e.g., Training materials, equipment"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <hr class="my-6">

            <!-- OUTPUTS Section -->
            <div id="outputs" class="mb-8">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">2. OUTPUTS</h2>
                    <div class="ml-auto text-sm text-gray-500">Expected direct results</div>
                </div>
                <div class="border-l-4 border-orange-500 pl-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Trainees/Beneficiaries</label>
                            <input type="text" wire:model="outputs_trainees" placeholder="e.g., 200 farmers trained"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Materials Produced</label>
                            <input type="text" wire:model="outputs_materials" placeholder="e.g., 5 training manuals"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Services Delivered</label>
                            <input type="text" wire:model="outputs_services" placeholder="e.g., 10 counseling sessions"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <!-- OUTCOMES Section -->
            <div id="outcomes" class="mb-8">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">3. OUTCOMES</h2>
                    <div class="ml-auto text-sm text-gray-500">Short-term changes (knowledge, skills, behavior)</div>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <div class="flex gap-2 mb-4">
                        <input type="text" wire:model="new_outcome" placeholder="Enter expected outcome (e.g., Improved farming techniques)"
                            @keydown.enter="addOutcome"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <button type="button" wire:click="addOutcome" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Add Outcome
                        </button>
                    </div>

                    @if ($outcomes && count($outcomes) > 0)
                        <div class="space-y-2">
                            @foreach ($outcomes as $index => $outcome)
                                <div class="flex items-center justify-between bg-purple-50 p-3 rounded">
                                    <span class="text-gray-700">{{ $outcome }}</span>
                                    <button type="button" wire:click="removeOutcome({{ $index }})"
                                        class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">No outcomes added yet</p>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <!-- IMPACTS Section -->
            <div id="impacts" class="mb-8">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">4. IMPACTS</h2>
                    <div class="ml-auto text-sm text-gray-500">Long-term changes (community, development goals)</div>
                </div>
                <div class="border-l-4 border-red-500 pl-4">
                    <div class="flex gap-2 mb-4">
                        <input type="text" wire:model="new_impact" placeholder="Enter expected impact (e.g., Poverty reduction, improved livelihoods)"
                            @keydown.enter="addImpact"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <button type="button" wire:click="addImpact" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Add Impact
                        </button>
                    </div>

                    @if ($impacts && count($impacts) > 0)
                        <div class="space-y-2">
                            @foreach ($impacts as $index => $impact)
                                <div class="flex items-center justify-between bg-red-50 p-3 rounded">
                                    <span class="text-gray-700">{{ $impact }}</span>
                                    <button type="button" wire:click="removeImpact({{ $index }})"
                                        class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">No impacts added yet</p>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <!-- Metadata Section -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Additional Information</h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Program Status</label>
                    <div class="px-4 py-2 rounded-lg bg-gray-50 border border-gray-300">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $program->status === 'active' ? 'bg-green-100 text-green-800' : ($program->status === 'inactive' ? 'bg-gray-100 text-gray-800' : ($program->status === 'planning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($program->status) }}
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea wire:model="notes" rows="4" placeholder="Additional notes or remarks..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:text-yellow-400 transition">
                    Save Program Plan
                </button>
            </div>
        </form>
    </div>

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
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Success</h3>
                <p class="text-gray-700 text-center mb-6">{{ $successMessage }}</p>
                <button 
                    wire:click="$dispatch('closeProgramPlanModal'); $set('showSuccessModal', false)"
                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                >
                    OK
                </button>
            </div>
        </div>
    @endif

    <!-- Error Modal -->
    @if ($showErrorModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full">
                <div class="flex justify-center mb-4">
                    <div class="rounded-full bg-red-100 p-3">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Error</h3>
                <p class="text-gray-700 text-center mb-6">{{ $errorMessage }}</p>
                <button 
                    wire:click="$set('showErrorModal', false)"
                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                >
                    Close
                </button>
            </div>
        </div>
    @endif
</div>
