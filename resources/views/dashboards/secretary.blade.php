<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-3xl text-gray-900">
                Data Entry Portal
            </h2>
            <div class="text-end">
                <p class="text-gray-600 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-blue-900 font-semibold">CESO Staff</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Programs Entered -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Programs Entered</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Beneficiaries Registered -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Beneficiaries Registered</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.75A2.75 2.75 0 003 4.25v11.5A2.75 2.75 0 005.75 18.5h8.5a2.75 2.75 0 002.75-2.75V9.5m-13-4a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm9.06 10.44a3.5 3.5 0 10-7.12 0"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Forms -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Pending Forms</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.414l4 4V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Data Entry Forms -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Entry Forms</h3>
                    <div class="space-y-3">
                        <a href="#" class="flex items-center p-4 border-2 border-blue-200 rounded-lg hover:bg-blue-50 transition">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Create New Program</p>
                                <p class="text-sm text-gray-600">Register a new extension program</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-4 border-2 border-green-200 rounded-lg hover:bg-green-50 transition">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H5.75A2.75 2.75 0 003 4.25v11.5A2.75 2.75 0 005.75 18.5h8.5a2.75 2.75 0 002.75-2.75V9.5m-13-4a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm9.06 10.44a3.5 3.5 0 10-7.12 0"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Register Beneficiary</p>
                                <p class="text-sm text-gray-600">Add a new beneficiary to the system</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-4 border-2 border-purple-200 rounded-lg hover:bg-purple-50 transition">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.414l4 4V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Submit Monitoring Form</p>
                                <p class="text-sm text-gray-600">Submit monthly monitoring data</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-4 border-2 border-orange-200 rounded-lg hover:bg-orange-50 transition">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V7z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Submit Evaluation Form</p>
                                <p class="text-sm text-gray-600">Submit periodic evaluation reports</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Quick Guidelines -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">📋 Guidelines</h3>
                    <div class="space-y-3 text-sm text-blue-800">
                        <div>
                            <p class="font-semibold">✓ Before Data Entry:</p>
                            <ul class="list-disc list-inside text-xs mt-1 space-y-1">
                                <li>Collect all necessary data from field</li>
                                <li>Verify accuracy of information</li>
                                <li>Check all required fields are complete</li>
                            </ul>
                        </div>
                        <hr class="border-blue-300 my-3" />
                        <div>
                            <p class="font-semibold">✓ Data Entry:</p>
                            <ul class="list-disc list-inside text-xs mt-1 space-y-1">
                                <li>Enter data carefully and accurately</li>
                                <li>Use correct date formats</li>
                                <li>Review before submitting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <p class="text-blue-900 font-medium">Data Entry Portal is under development</p>
                <p class="text-blue-700 text-sm">More features will be added soon</p>
            </div>
        </div>
    </div>
</x-app-layout>
