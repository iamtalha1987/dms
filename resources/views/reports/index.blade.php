<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Reports</h2></x-slot>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($reports as $report)
            <a href="{{ route('reports.show', $report['type']) }}" class="block bg-white rounded-lg shadow-sm p-5 hover:shadow-md border">
                <h3 class="font-medium text-gray-800">{{ $report['label'] }}</h3>
            </a>
        @endforeach
    </div>
</x-app-layout>
