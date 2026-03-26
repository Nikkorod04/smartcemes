<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phase 2: Activity Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Activity Tracking Management</h1>
                    <p class="text-gray-600 mb-6">Click the button below to open the Activity Tracking Modal</p>
                </div>

                <!-- Livewire Component -->
                @livewire('ManageProgramActivities', ['program_id' => $program_id])
            </div>
        </div>
    </div>
</x-app-layout>
