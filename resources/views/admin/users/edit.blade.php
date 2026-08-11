<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit User</h2></x-slot>
    <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white p-6 rounded-lg shadow-sm max-w-2xl space-y-4">
        @csrf @method('PUT')
        @include('admin.users._form', ['user' => $user])
        <x-primary-button>Update</x-primary-button>
    </form>
</x-app-layout>
