<div class="min-h-screen bg-gradient-to-br from-white to-gray-50 px-8 py-6">
    <!-- Header -->
    <div class="mb-2 animate-fade-in">
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8 animate-slide-up">
        <!-- Total Programs -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300 transform hover:scale-105 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 font-semibold">Total Programs</h3>
                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm11-4a1 1 0 10-2 0 1 1 0 002 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalPrograms }}</p>
            <p class="text-sm text-gray-500 mt-2">Across all categories</p>
        </div>

        <!-- Active Programs -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300 transform hover:scale-105 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 font-semibold">Active</h3>
                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ $activePrograms }}</p>
            <p class="text-sm text-gray-500 mt-2">Currently active</p>
        </div>

        <!-- Planning Programs -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300 transform hover:scale-105 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 font-semibold">Planning</h3>
                <svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6 2a2 2 0 012-2h6a2 2 0 012 2v3h2a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2h2V2zM8 4h4V2H8v2z"></path>
                    <path fill-rule="evenodd" d="M5 9a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 2a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ $planningPrograms }}</p>
            <p class="text-sm text-gray-500 mt-2">In planning phase</p>
        </div>

        <!-- Completed Programs -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300 transform hover:scale-105 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 font-semibold">Completed</h3>
                <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-purple-600">{{ $completedPrograms }}</p>
            <p class="text-sm text-gray-500 mt-2">Successfully completed</p>
        </div>

        <!-- Communities -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300 transform hover:scale-105 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 font-semibold">Communities</h3>
                <svg class="w-8 h-8 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalCommunities }}</p>
            <p class="text-sm text-gray-500 mt-2">Active communities</p>
        </div>
    </div>

    <!-- Charts and Data Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Programs Status Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6 animate-slide-up" style="animation-delay: 0.2s">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Programs by Status</h3>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Community Participation Top 5 -->
        <div class="bg-white rounded-lg shadow-md p-6 animate-slide-up" style="animation-delay: 0.3s">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Top Communities</h3>
            <div class="space-y-3">
                @forelse($communityParticipation as $index => $community)
                    <div 
                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition duration-200 transform hover:translate-x-1"
                        style="animation: slideInRight 0.5s ease-out {{ ($index * 0.1) }}s both"
                    >
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ $community['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $community['programs'] }} programs</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                            {{ $community['programs'] }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No community data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6 animate-slide-up" style="animation-delay: 0.4s">
            <h3 class="text-xl font-bold text-gray-900 mb-4">6-Month Trend</h3>
            <div class="relative h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Statistics -->
        <div class="space-y-4 animate-slide-up" style="animation-delay: 0.5s">
            <!-- Status Distribution -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Status Breakdown</h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-sm text-gray-600">Active</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $activePrograms }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-sm text-gray-600">Planning</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $planningPrograms }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                            <span class="text-sm text-gray-600">Completed</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $completedPrograms }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                            <span class="text-sm text-gray-600">Inactive</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $totalPrograms - $activePrograms - $planningPrograms - $completedPrograms }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg shadow-md p-6 border border-indigo-100">
                <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Quick Stats</h4>
                <div class="space-y-2 text-sm">
                    <p class="flex justify-between"><span class="text-gray-600">Avg per month:</span> <span class="font-bold text-gray-900">{{ round($totalPrograms / 12, 1) }}</span></p>
                    <p class="flex justify-between"><span class="text-gray-600">Completion rate:</span> <span class="font-bold text-gray-900">{{ $totalPrograms > 0 ? round(($completedPrograms / $totalPrograms) * 100, 1) : 0 }}%</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Programs Table -->
    <div class="bg-white rounded-lg shadow-md p-6 animate-slide-up" style="animation-delay: 0.6s">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Programs</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Communities</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentPrograms as $program)
                        <tr class="hover:bg-blue-50 transition duration-150 hover:shadow-sm cursor-pointer">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ \Illuminate\Support\Str::limit($program['title'], 30) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $program['status'] === 'active' ? 'bg-green-100 text-green-800' :
                                    ($program['status'] === 'inactive' ? 'bg-gray-100 text-gray-800' :
                                    ($program['status'] === 'planning' ? 'bg-blue-100 text-blue-800' :
                                    'bg-purple-100 text-purple-800'))
                                }}">
                                    {{ ucfirst($program['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                    {{ $program['communities_count'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $program['created_at'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No programs available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Animations -->
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.6s ease-out both;
    }
</style>

<!-- Initialize Charts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: @json($programStatusData),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                            }
                        }
                    }
                }
            });
        }

        // Trend Chart
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: @json($monthlyTrendData),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
    });

    // Refresh on Livewire update
    document.addEventListener('livewire:updated', function() {
        location.reload();
    });
</script>
