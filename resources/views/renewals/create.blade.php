<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add Renewal — {{ $domain->domain_name }}</h2></x-slot>
    <form method="POST" action="{{ route('domains.renewals.store', $domain) }}" class="bg-white rounded-lg shadow-sm p-6 max-w-2xl space-y-4">
        @csrf
        @include('renewals._form')
        <x-primary-button>Save Renewal</x-primary-button>
    </form>
</x-app-layout>
