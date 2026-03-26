<div class="space-y-6">
    <!-- Tab Navigation -->
    <div class="flex gap-4 border-b border-gray-200 mb-6">
        <button 
            wire:click="$set('activeTab', 'ai-insights')"
            class="px-4 py-3 font-medium text-gray-700 border-b-2 @if($activeTab === 'ai-insights') border-blue-900 text-blue-900 @else border-transparent hover:text-gray-900 @endif transition"
        >
            AI Insights
        </button>
        <button 
            wire:click="$set('activeTab', 'programs')"
            class="px-4 py-3 font-medium text-gray-700 border-b-2 @if($activeTab === 'programs') border-blue-900 text-blue-900 @else border-transparent hover:text-gray-900 @endif transition"
        >
            Programs
        </button>
        <button 
            wire:click="$set('activeTab', 'communities')"
            class="px-4 py-3 font-medium text-gray-700 border-b-2 @if($activeTab === 'communities') border-blue-900 text-blue-900 @else border-transparent hover:text-gray-900 @endif transition"
        >
            Communities
        </button>
    </div>

    <!-- Programs Tab -->
    @if($activeTab === 'programs')
        <div class="space-y-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Extension Programs Report</h3>
                <p class="text-gray-600 text-sm mb-6">Detailed analysis of all extension programs by status and location.</p>
                
                <!-- Programs Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <!-- Total Programs -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 mb-1">Total Programs</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $programsStats['total'] }}</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Active Programs -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 mb-1">Active</p>
                                <p class="text-2xl font-bold text-green-900">{{ $programsStats['active'] }}</p>
                            </div>
                            <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Planned Programs -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-600 mb-1">Planned</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ $programsStats['planned'] }}</p>
                            </div>
                            <svg class="w-10 h-10 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>

                    <!-- Completed Programs -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600 mb-1">Completed</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $programsStats['completed'] }}</p>
                            </div>
                            <svg class="w-10 h-10 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Programs by Status -->
                <div class="space-y-8">
                    @foreach($programsByStatus as $status => $programs)
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 capitalize">
                                {{ $status }} Programs ({{ count($programs) }})
                            </h4>
                            @if(count($programs) > 0)
                                <div class="space-y-4">
                                    @foreach($programs as $program)
                                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 hover:shadow-md transition">
                                            <h5 class="text-base font-semibold text-gray-900 mb-2">{{ $program->title }}</h5>
                                            <div class="space-y-2 text-gray-700 text-sm">
                                                @if($program->description)
                                                    <p class="text-gray-600 mb-3">{{ $program->description }}</p>
                                                @endif
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                    <div>
                                                        <span class="font-semibold text-gray-900">Duration:</span>
                                                        <p class="text-gray-600">{{ $program->planned_start_date?->format('M d, Y') ?? 'N/A' }} to {{ $program->planned_end_date?->format('M d, Y') ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-900">Budget:</span>
                                                        <p class="text-gray-600">{{ $program->allocated_budget ? '₱ ' . number_format($program->allocated_budget) : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-900">Beneficiaries:</span>
                                                        <p class="text-gray-600">{{ $program->target_beneficiaries ?? 0 }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-900">Program Lead:</span>
                                                        <p class="text-gray-600">{{ $program->program_lead_id ?? 'Unassigned' }}</p>
                                                    </div>
                                                </div>

                                                @if(is_array($program->related_communities) && count($program->related_communities) > 0)
                                                    <div class="pt-2">
                                                        <span class="font-semibold text-gray-900">Communities Served:</span>
                                                        <div class="flex flex-wrap gap-2 mt-2">
                                                            @foreach($program->related_communities as $community)
                                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">{{ $community }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($program->objectives)
                                                    <div class="pt-2">
                                                        <span class="font-semibold text-gray-900">Objectives:</span>
                                                        <p class="text-gray-600 mt-1">{{ is_array($program->objectives) ? implode(', ', $program->objectives) : $program->objectives }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-8 border-2 border-dashed border-gray-300 text-center">
                                    <p class="text-gray-600">No {{ $status }} programs at this time</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Communities Tab -->
    @if($activeTab === 'communities')
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Communities Report</h3>
            <p class="text-gray-600 text-sm mb-6">Community engagement statistics and needs assessment summaries.</p>
            
            <!-- Placeholder for detailed communities report -->
            <div class="bg-gray-50 rounded-lg p-8 border-2 border-dashed border-gray-300 text-center">
                <p class="text-gray-600">Communities report data will be displayed here</p>
            </div>
        </div>
    @endif

    <!-- AI Insights Tab -->
    @if($activeTab === 'ai-insights')
        <div class="space-y-6">
            <!-- Generate Insights Button -->
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">AI-Powered Insights</h3>
                <button 
                    wire:click="generateAIInsights"
                    @if($loadingInsights) disabled @endif
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-purple-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    @if($loadingInsights)
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.581 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    @endif
                    {{ $loadingInsights ? 'Generating...' : 'Generate Insights' }}
                </button>
            </div>

            @if(!empty($aiInsights))
                <!-- AI Insights Results -->
                @if(isset($aiInsights['error']))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <p class="text-red-700">{{ $aiInsights['error'] }}</p>
                    </div>
                @else
                    <!-- Summary -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200 p-6">
                        <h4 class="text-lg font-semibold text-purple-900 mb-2">Summary</h4>
                        <p class="text-purple-700">{{ $aiInsights['summary'] ?? 'No summary available' }}</p>
                    </div>

                    <!-- Highlights -->
                    @if(!empty($aiInsights['highlights']))
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Key Highlights</h4>
                            <ul class="space-y-2">
                                @foreach($aiInsights['highlights'] as $highlight)
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Recommendations -->
                    @if(!empty($aiInsights['recommendations']))
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Recommendations</h4>
                            <ul class="space-y-2">
                                @foreach($aiInsights['recommendations'] as $recommendation)
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-gray-700">{{ $recommendation }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Trends -->
                    @if(!empty($aiInsights['trends']))
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Observed Trends</h4>
                            <ul class="space-y-2">
                                @foreach($aiInsights['trends'] as $trend)
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8L5.586 19.414a2 2 0 01-2.828 0L.172 16.242a2 2 0 010-2.828L11 2.172A2 2 0 0113.828 2z" />
                                        </svg>
                                        <span class="text-gray-700">{{ $trend }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            @else
                <div class="bg-gray-50 rounded-lg p-12 border-2 border-dashed border-gray-300 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5h.01v.01H12v-.01z" />
                    </svg>
                    <p class="text-gray-600 mb-4">Click the button above to generate AI-powered insights based on your M&E data</p>
                </div>
            @endif
        </div>
    @endif
</div>
