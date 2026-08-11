<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add Domain</h2></x-slot>
    <form method="POST" action="{{ route('domains.store') }}" class="bg-white rounded-lg shadow-sm p-6 max-w-4xl">
        @csrf
        @include('domains._form')
        <div class="mt-4"><x-primary-button>Save</x-primary-button></div>
    </form>
</x-app-layout>
