<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Client</h2></x-slot>
    <form method="POST" action="{{ route('clients.update', $client) }}" class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-2xl">
        @csrf @method('PUT')
        @include('clients._form', ['client' => $client])
        <x-primary-button>Update</x-primary-button>
    </form>
</x-app-layout>
