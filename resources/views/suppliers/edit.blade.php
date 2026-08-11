<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Supplier</h2></x-slot>
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="bg-white p-6 rounded-lg shadow-sm max-w-md space-y-4">
        @csrf @method('PUT')
        @include('suppliers._form', ['supplier' => $supplier])
        <x-primary-button>Update</x-primary-button>
    </form>
</x-app-layout>
