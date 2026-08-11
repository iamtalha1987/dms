<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add Client</h2></x-slot>
    <form method="POST" action="{{ route('clients.store') }}" class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-2xl">
        @csrf
        @include('clients._form')
        <x-primary-button>Save</x-primary-button>
    </form>
</x-app-layout>
