@props(['title' => null])

<div class="mb-4 flex flex-wrap items-center justify-end gap-3">
    @if ($title)
        <h2 class="flex-1 text-lg font-semibold text-slate-800">{{ $title }}</h2>
    @endif
    <div class="flex flex-wrap gap-2 ms-auto">
        {{ $slot }}
    </div>
</div>
