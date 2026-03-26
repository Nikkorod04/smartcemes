<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Communities') }}
        </h2>
    </x-slot>

    <div class="py-2 px-3">
        <div class="w-full">
            @livewire('manage-communities')
        </div>
    </div>
</x-app-layout>
