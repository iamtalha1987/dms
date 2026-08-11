<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add Supplier</h2></x-slot>
    <form method="POST" action="{{ route('suppliers.store') }}" class="bg-white p-6 rounded-lg shadow-sm max-w-md space-y-4">
        @csrf
        @include('suppliers._form')
        <x-primary-button>Save</x-primary-button>
    </form>
</x-app-layout>
