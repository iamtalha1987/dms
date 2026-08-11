<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add User</h2></x-slot>
    <form method="POST" action="{{ route('users.store') }}" class="bg-white p-6 rounded-lg shadow-sm max-w-2xl space-y-4">
        @csrf
        @include('admin.users._form')
        <x-primary-button>Save</x-primary-button>
    </form>
</x-app-layout>
