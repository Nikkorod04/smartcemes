<div class="py-2 px-3">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Outputs & Key Indicators</h1>
            <button 
                wire:click="openOutputForm"
                class="px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:text-yellow-400 transition"
            >
                + Record Output
            </button>
        </div>

        <!-- Community & Baseline Information -->
        @if ($program && !empty($communities))
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-300 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-blue-900 mb-4">Community Overview & Baseline Data</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($communities as $community)
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <p class="text-sm font-semibold text-gray-600 mb-2">Community</p>
                            <p class="text-lg font-bold text-blue-900 mb-3">{{ $community['name'] }}</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Baseline Date:</span>
                                    <span class="font-semibold text-gray-800">{{ $community['baseline']->baseline_assessment_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold text-blue-900">{{ ucfirst($community['baseline']->status) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Outputs:</span>
                                    <span class="font-bold text-blue-900">{{ $community['output_count'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- AI Insights Section -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white rounded-lg p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold mb-2">AI Insights</h3>
                    <p class="text-blue-100 leading-relaxed">
                        Strong program momentum detected. The Tara Basa Tutoring Program is achieving 95% of beneficiary targets across all four communities. Materials distribution and community engagement are running ahead of schedule. Recommend scaling the parent awareness workshops given their high effectiveness rating (4.8/5). Monitor literacy baseline improvements closely - early indicators suggest 12% advancement in reading comprehension by program midpoint.
                    </p>
                    <div class="mt-3 flex gap-2">
                        <span class="inline-block px-3 py-1 bg-blue-700 rounded-full text-xs font-semibold">On Track</span>
                        <span class="inline-block px-3 py-1 bg-blue-700 rounded-full text-xs font-semibold">High Impact</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Targets Summary -->
        @if ($program)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-600 mb-1">Target Trainees/Beneficiaries</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $targets['trainees'] ?? 'Not set' }}</p>
                </div>
                <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-600 mb-1">Target Materials</p>
                    <p class="text-2xl font-bold text-green-900">{{ $targets['materials'] ?? 'Not set' }}</p>
                </div>
                <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-600 mb-1">Target Services</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $targets['services'] ?? 'Not set' }}</p>
                </div>
            </div>
        @endif

        <!-- KPI Dashboard Section -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-3">
            <!-- Trainees/Beneficiaries KPI -->
            <div class="rounded-lg border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-6">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-blue-900">Beneficiaries Reached</h3>
                </div>
                <div class="mb-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-blue-900">{{ $kpis['actual_beneficiaries'] ?? 0 }}</span>
                        <span class="text-lg text-blue-700">/ {{ $kpis['target_beneficiaries'] ?? 'N/A' }}</span>
                    </div>
                    <p class="text-xs text-blue-600 mt-1">Target: {{ $kpis['target_beneficiaries'] ?? 'Not set' }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-blue-700 font-medium">Coverage</span>
                        <span class="font-bold text-blue-900">{{ $kpis['beneficiary_coverage_percent'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div 
                            class="bg-gradient-to-r from-blue-600 to-blue-700 h-2 rounded-full transition-all duration-300"
                            style="width: {{ $kpis['beneficiary_coverage_percent'] ?? 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials KPI -->
            <div class="rounded-lg border border-yellow-200 bg-gradient-to-br from-yellow-50 to-yellow-100 p-6">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-blue-900">Materials Produced</h3>
                </div>
                <div class="mb-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-blue-900">{{ $kpis['actual_materials'] ?? 0 }}</span>
                        <span class="text-lg text-blue-700">/ {{ $kpis['target_materials'] ?? 'N/A' }}</span>
                    </div>
                    <p class="text-xs text-blue-600 mt-1">Target: {{ $kpis['target_materials'] ?? 'Not set' }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-blue-700 font-medium">Coverage</span>
                        <span class="font-bold text-blue-900">{{ $kpis['materials_coverage_percent'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-yellow-200 rounded-full h-2">
                        <div 
                            class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full transition-all duration-300"
                            style="width: {{ $kpis['materials_coverage_percent'] ?? 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services KPI -->
            <div class="rounded-lg border border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-6">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-blue-900">Services Delivered</h3>
                </div>
                <div class="mb-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-blue-900">{{ $kpis['actual_services'] ?? 0 }}</span>
                        <span class="text-lg text-blue-700">/ {{ $kpis['target_services'] ?? 'N/A' }}</span>
                    </div>
                    <p class="text-xs text-blue-600 mt-1">Target: {{ $kpis['target_services'] ?? 'Not set' }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-blue-700 font-medium">Coverage</span>
                        <span class="font-bold text-blue-900">{{ $kpis['services_coverage_percent'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-green-200 rounded-full h-2">
                        <div 
                            class="bg-gradient-to-r from-green-600 to-green-700 h-2 rounded-full transition-all duration-300"
                            style="width: {{ $kpis['services_coverage_percent'] ?? 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Progress and Statistics Section -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Overall Target Achievement -->
            <div class="rounded-lg border border-blue-300 bg-white p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-blue-900">Overall Target Achievement</h3>
                </div>
                <div class="mb-6">
                    <div class="flex items-baseline justify-center gap-2 mb-4">
                        <span class="text-5xl font-bold text-blue-900">{{ $kpis['overall_target_met_percent'] ?? 0 }}</span>
                        <span class="text-2xl text-blue-700">%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div 
                            class="bg-gradient-to-r from-blue-600 via-yellow-400 to-blue-600 h-3 rounded-full transition-all duration-500"
                            style="width: {{ $kpis['overall_target_met_percent'] ?? 0 }}%">
                        </div>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-700">
                        <span class="font-semibold text-blue-900">Total Outputs:</span>
                        <span class="text-blue-700">{{ $kpis['total_outputs_recorded'] ?? 0 }} recorded</span>
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold text-blue-900">Total Reach:</span>
                        <span class="text-blue-700">{{ $kpis['total_beneficiaries_reached'] ?? 0 }} beneficiaries</span>
                    </p>
                </div>
            </div>

            <!-- Output Summary by Type -->
            <div class="rounded-lg border border-blue-300 bg-white p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-blue-900">Outputs by Type</h3>
                </div>
                <div class="space-y-3">
                    @forelse($kpis['outputs_by_type'] ?? [] as $type => $count)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-blue-600"></span>
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $type) }}</span>
                            </div>
                            <span class="text-sm font-bold text-blue-900">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No outputs recorded yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input 
                    type="text" 
                    wire:model.live="searchTerm" 
                    placeholder="Search outputs..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <select 
                    wire:model.live="filterType"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Types</option>
                    <option value="training">Training Session</option>
                    <option value="materials">Materials Produced</option>
                    <option value="services">Services Delivered</option>
                    <option value="mentoring">Mentoring Session</option>
                    <option value="assessment">Assessment/Evaluation</option>
                    <option value="other">Other</option>
                </select>
                <select 
                    wire:model.live="filterStatus"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Status</option>
                    <option value="planned">Planned</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select 
                    wire:model.live="pageSize"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Outputs Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Output</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Quantity</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Beneficiaries</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($outputs as $output)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $output->output_title }}</p>
                            @if ($output->description)
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($output->description, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $output->output_type === 'training' ? 'bg-blue-100 text-blue-800' : 
                                   ($output->output_type === 'materials' ? 'bg-green-100 text-green-800' :
                                   ($output->output_type === 'services' ? 'bg-orange-100 text-orange-800' :
                                   ($output->output_type === 'mentoring' ? 'bg-purple-100 text-purple-800' :
                                   ($output->output_type === 'assessment' ? 'bg-indigo-100 text-indigo-800' :
                                   'bg-gray-100 text-gray-800')))) }}">
                                {{ $output->getOutputTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $output->output_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            {{ $output->quantity }} {{ $output->unit }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $output->beneficiaries_reached }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $output->status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($output->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                   ($output->status === 'planned' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-red-100 text-red-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $output->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button 
                                    wire:click="editOutput({{ $output->id }})"
                                    class="px-3 py-1 bg-blue-900 text-white rounded text-xs hover:bg-blue-800 hover:text-yellow-400 transition"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="deleteOutput({{ $output->id }})"
                                    onclick="return confirm('Delete this output?')"
                                    class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <p class="text-lg font-semibold">No outputs recorded yet</p>
                            <p class="text-sm mt-1">Click "Record Output" to start documenting program outputs</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $outputs->links() }}
        </div>
    </div>

    <!-- Output Form Modal -->
    @if ($showOutputForm)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full my-8">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold">{{ $editingOutputId ? 'Edit Output' : 'Record New Output' }}</h2>
                    <button 
                        wire:click="closeOutputForm"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="saveOutput" class="p-6 space-y-4">
                    <!-- Output Title & Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Output Title *</label>
                            <input 
                                type="text"
                                wire:model="output_title"
                                placeholder="e.g., Farmer Training Workshop"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('output_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Output Type *</label>
                            <select 
                                wire:model="output_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="training">Training Session</option>
                                <option value="materials">Materials Produced</option>
                                <option value="services">Services Delivered</option>
                                <option value="mentoring">Mentoring Session</option>
                                <option value="assessment">Assessment/Evaluation</option>
                                <option value="other">Other</option>
                            </select>
                            @error('output_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea 
                            wire:model="description"
                            rows="2"
                            placeholder="Brief description of the output..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Output Date *</label>
                            <input 
                                type="date"
                                wire:model="output_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('output_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                            <input 
                                type="time"
                                wire:model="start_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                            <input 
                                type="time"
                                wire:model="end_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Quantity & Unit -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                            <input 
                                type="number"
                                wire:model="quantity"
                                min="0"
                                placeholder="e.g., 50"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                            <input 
                                type="text"
                                wire:model="unit"
                                placeholder="e.g., farmers, modules, sessions"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Beneficiaries -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Beneficiaries Reached *</label>
                        <input 
                            type="number"
                            wire:model="beneficiaries_reached"
                            min="0"
                            placeholder="Total number of beneficiaries"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        @error('beneficiaries_reached') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Select Specific Beneficiaries -->
                    @if (!empty($beneficiaries))
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Beneficiaries (Optional)</label>
                            <select 
                                wire:model="selected_beneficiary_ids"
                                multiple
                                size="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                @foreach ($beneficiaries as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple beneficiaries</p>
                        </div>
                    @endif

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
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
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Outcomes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Outcomes/Results</label>
                        <textarea 
                            wire:model="outcomes"
                            rows="3"
                            placeholder="What was achieved? Key results, learnings, etc..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea 
                            wire:model="notes"
                            rows="2"
                            placeholder="Additional notes, challenges, recommendations..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button 
                            type="submit"
                            class="flex-1 px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:text-yellow-400 transition"
                        >
                            {{ $editingOutputId ? 'Update Output' : 'Record Output' }}
                        </button>
                        <button 
                            type="button"
                            wire:click="closeOutputForm"
                            class="flex-1 px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition"
                        >
                            Cancel
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
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Success</h3>
                <p class="text-gray-700 text-center mb-6">{{ $successMessage }}</p>
                <button 
                    wire:click="$set('showSuccessModal', false)"
                    class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 hover:text-yellow-400 transition"
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
