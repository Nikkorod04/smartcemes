<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.index') }}" class="text-blue-900 hover:text-blue-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-2xl text-gray-900">{{ $form['name'] }}</h2>
                    <p class="text-sm text-gray-600">{{ $form['description'] }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-900">
                    {{ $form['code'] }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="border-l-4 border-blue-900 pl-4">
                        <p class="text-gray-600 text-sm font-medium">Form Code</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $form['code'] }}</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="text-gray-600 text-sm font-medium">Type</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $form['type'] }}</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <p class="text-gray-600 text-sm font-medium">Frequency</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $form['frequency'] }}</p>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700">{{ $form['description'] }}</p>
                </div>
            </div>

            <!-- Form Implementation -->
            @switch($form['id'])
                @case(1)
                    <livewire:forms.f-c-e-s-narrative-form />
                    @break
                @case(2)
                    <livewire:forms.community-needs-form />
                    @break
                @case(3)
                    <livewire:forms.f-c-e-s-003-attendance-form />
                    @break
                @case(4)
                    <livewire:forms.f-c-e-s-004-training-eval-form />
                    @break
                @case(5)
                    <livewire:forms.f-c-e-s-005-project-me-form />
                    @break
                @default
                    <div class="bg-white rounded-lg shadow p-8">
                        <div class="text-center">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Form Not Found</h3>
                            <a href="{{ route('forms.index') }}" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition inline-block">
                                Back to Forms
                            </a>
                        </div>
                    </div>
            @endswitch
        </div>
    </div>
</x-app-layout>
