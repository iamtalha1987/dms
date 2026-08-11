<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Domain</h2></x-slot>
    <form method="POST" action="{{ route('domains.update', $domain) }}" class="bg-white rounded-lg shadow-sm p-6 max-w-4xl">
        @csrf @method('PUT')
        @include('domains._form', ['domain' => $domain])
        <div class="mt-4"><x-primary-button>Update</x-primary-button></div>
    </form>
</x-app-layout>
