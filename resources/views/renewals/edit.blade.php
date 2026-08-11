<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Renewal — {{ $renewal->domain->domain_name }}</h2></x-slot>
    <form method="POST" action="{{ route('renewals.update', $renewal) }}" class="bg-white rounded-lg shadow-sm p-6 max-w-2xl space-y-4">
        @csrf @method('PUT')
        @include('renewals._form', ['renewal' => $renewal])
        <x-primary-button>Update</x-primary-button>
    </form>
</x-app-layout>
