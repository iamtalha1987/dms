<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Role — {{ $role->name }}</h2></x-slot>
    <form method="POST" action="{{ route('roles.update', $role) }}" class="bg-white p-6 rounded-lg shadow-sm">
        @csrf @method('PUT')
        @include('admin.roles._form', ['role' => $role])
        <div class="mt-4"><x-primary-button>Update</x-primary-button></div>
    </form>
</x-app-layout>
