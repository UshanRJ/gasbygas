<div>
    <select
        wire:model="outletId"
        wire:change="filterByOutlet($event.target.value)"
        class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
    >
        <option value="">All Outlets</option>
        @foreach ($outlets as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>