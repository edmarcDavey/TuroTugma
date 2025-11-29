<div {{ $attributes->merge(['class' => 'p-4 border rounded bg-white shadow-sm']) }}>
  <div class="text-sm text-slate-500">{{ $title }}</div>
  <div class="mt-2 text-2xl font-semibold">{{ $value }}</div>
  @if(! empty($description))
    <div class="mt-1 text-xs text-slate-400">{{ $description }}</div>
  @endif
</div>
