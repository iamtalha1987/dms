<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add Role</h2></x-slot>
    <form method="POST" action="{{ route('roles.store') }}" class="bg-white p-6 rounded-lg shadow-sm">
        @csrf
        @include('admin.roles._form')
        <div class="mt-4"><x-primary-button>Save</x-primary-button></div>
    </form>
</x-app-layout>
