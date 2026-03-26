<div>
    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    wire:model.live="searchTerm"
                    placeholder="Search forms..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-transparent"
                >
            </div>
            <div>
                <select 
                    wire:model.live="filterType"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-transparent"
                >
                    <option value="">All Types</option>
                    <option value="Evaluation">Evaluation</option>
                    <option value="Assessment">Assessment</option>
                    <option value="Monitoring">Monitoring</option>
                    <option value="Registration">Registration</option>
                </select>
            </div>
            @if(!empty($searchTerm) || !empty($filterType))
                <button 
                    wire:click="clearFilters"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition duration-200"
                >
                    Clear Filters
                </button>
            @endif
        </div>
    </div>

    <!-- Forms Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Form Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Frequency</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($forms as $form)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $form['name'] }}</p>
                                <p class="text-xs text-gray-600">{{ $form['description'] }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-900">
                                {{ $form['code'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $form['type'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $form['frequency'] }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <!-- Edit/Fill Form Button -->
                                <a href="{{ route('forms.show', $form['id']) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-200 transition duration-200"
                                   title="Fill Form">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Download Template Button -->
                                @if($form['id'] == 2)
                                    <!-- Community Needs Assessment - downloads from template in form page -->
                                    <a href="{{ route('forms.show', $form['id']) }}#download-template" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-900 hover:bg-green-200 transition duration-200"
                                       title="Download Template">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @elseif($form['id'] == 1)
                                    <!-- F-CES Narrative Report -->
                                    <a href="/templates/F-CES%20Narrative%20Report.docx" 
                                       download
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-900 hover:bg-green-200 transition duration-200"
                                       title="Download Template">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @elseif($form['id'] == 3)
                                    <!-- F-CES-003 Attendance Monitoring Form -->
                                    <a href="/templates/F-CES-003-Attendance%20Monitoring%20Form%20%28A4%20version%29.doc" 
                                       download
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-900 hover:bg-green-200 transition duration-200"
                                       title="Download Template">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @elseif($form['id'] == 4)
                                    <!-- F-CES-004 Training Evaluation Form -->
                                    <a href="/templates/F-CES-004-Training%20Evaluation%20Form.docx" 
                                       download
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-900 hover:bg-green-200 transition duration-200"
                                       title="Download Template">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @elseif($form['id'] == 5)
                                    <!-- F-CES-005 Project Monitoring and Evaluation Form -->
                                    <a href="/templates/F-CES-005-Project%20Monitoring%20and%20Evaluation%20Form%20A4.docx" 
                                       download
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-900 hover:bg-green-200 transition duration-200"
                                       title="Download Template">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-600">
                            No forms available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
