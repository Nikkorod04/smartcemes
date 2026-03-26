<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-3xl text-gray-900">
                M&E Dashboard
            </h2>
            <div class="text-end">
                <p class="text-gray-600 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-blue-900 font-semibold">Extension Director</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Programs Card -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Programs</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Beneficiaries Card -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Beneficiaries</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.75A2.75 2.75 0 003 4.25v11.5A2.75 2.75 0 005.75 18.5h8.5a2.75 2.75 0 002.75-2.75V9.5m-13-4a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm9.06 10.44a3.5 3.5 0 10-7.12 0"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Programs Card -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Active Programs</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Forms Submitted Card -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Forms Submitted</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.414l4 4V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Reports Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="#" class="block p-3 border border-blue-900 text-blue-900 font-medium rounded hover:bg-blue-50 transition">
                            📊 View M&E Reports
                        </a>
                        <a href="#" class="block p-3 border border-yellow-500 text-yellow-600 font-medium rounded hover:bg-yellow-50 transition">
                            🎯 View Performance Metrics
                        </a>
                        <a href="#" class="block p-3 border border-green-500 text-green-600 font-medium rounded hover:bg-green-50 transition">
                            📈 Export Data
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 pb-4 border-b">
                            <div class="w-2 h-2 bg-blue-900 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Forms submitted by secretary</p>
                                <p class="text-xs text-gray-500">No recent activity yet</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 pb-4 border-b">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Data entry updates</p>
                                <p class="text-xs text-gray-500">No recent activity yet</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">System alerts</p>
                                <p class="text-xs text-gray-500">No alerts at this time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <p class="text-blue-900 font-medium">Dashboard is under development</p>
                <p class="text-blue-700 text-sm">More features will be added soon</p>
            </div>
        </div>
    </div>
</x-app-layout>
