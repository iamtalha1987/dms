@props(['label', 'value'])

<div style="background:#fff;border-radius:0.75rem;padding:1.25rem;border:1px solid #e2e8f0;box-shadow:0 1px 2px rgb(0 0 0 / 0.05);" {{ $attributes->merge(['class' => 'rounded-xl bg-white p-5 shadow-sm border border-slate-200/80']) }}>
    <p style="margin:0;font-size:0.875rem;font-weight:500;color:#64748b;" class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p style="margin:0.5rem 0 0;font-size:1.5rem;font-weight:700;color:#0f172a;" class="mt-2 text-2xl font-bold text-slate-900">{{ $value }}</p>
</div>
