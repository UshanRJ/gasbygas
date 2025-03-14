{{-- resources/views/filament/widgets/outlet-dropdown.blade.php --}}
<div>
    <select
        id="outlet-filter-{{ $widgetId }}"
        wire:change="filterByOutlet($event.target.value === 'all' ? null : $event.target.value)"
        class="block w-full h-9 py-1 text-sm border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
    >
        @foreach ($outlets as $id => $name)
            <option value="{{ $id }}" {{ $outletId == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>