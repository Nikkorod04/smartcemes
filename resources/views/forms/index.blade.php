<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Forms & Reports
        </h2>
    </x-slot>

    <div class="py-2 px-3">
        <div class="w-full bg-white rounded-lg shadow-md p-6">
            @livewire('ManageForms')
        </div>
    </div>
</x-app-layout>
