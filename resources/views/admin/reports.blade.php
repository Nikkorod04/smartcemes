<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-3xl text-gray-900">
                M&E Reports & Analytics
            </h2>
            <div class="text-sm text-gray-600">
                AI-powered insights and performance analysis
            </div>
        </div>
    </x-slot>

    <div class="py-2 px-3">
        <div class="w-full bg-white rounded-lg shadow-md p-6">
            @livewire('admin-reports')
        </div>
    </div>
</x-app-layout>
