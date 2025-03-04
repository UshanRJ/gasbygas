<x-layouts.app>
    <x-slot name="title">Order Details</x-slot>
    
    <div class="w-full max-w-7xl py-8 px-4 sm:px-6 lg:px-8 mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-8 mb-6 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Order Details</h1>
                    <p class="text-blue-100 mt-1">Order #{{ $order->id }}</p>
                </div>
                <div class="hidden md:block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Order Status Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex items-start">
            <svg class="h-6 w-6 text-blue-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3">
                <p class="text-sm text-blue-700">Your order is <span class="font-semibold">{{ ucfirst($order->status) }}</span>.</p>
                <a href="{{ route('orders.index') }}" class="text-blue-700 hover:text-blue-600 text-sm font-medium">View all orders &rarr;</a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-8">
                <!-- Order Details Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Information</h2>
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm text-gray-800">
                        <div><span class="text-gray-500">Order Number:</span> #{{ $order->id }}</div>
                        <div><span class="text-gray-500">Date Placed:</span> {{ $order->created_at->format('M d, Y') }}</div>
                        <div><span class="text-gray-500">Order Status:</span> {{ ucfirst($order->status) }}</div>
                        <div><span class="text-gray-500">Payment Status:</span> {{ ucfirst($order->payment_status) }}</div>
                    </div>

                    <!-- Delivery Information -->
                    <h3 class="text-md font-semibold text-gray-800 mb-3">Delivery Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-700"><span class="font-medium">Gas Outlet:</span> {{ $order->outlet->name }}</p>
                        <p class="text-sm text-gray-700"><span class="font-medium">Location:</span> {{ $order->outlet->location }}</p>
                        <p class="text-sm text-gray-700"><span class="font-medium">Delivery Address:</span> {{ $order->user->address ?? $order->address->full_address }}</p>
                    </div>

                    <!-- Order Items -->
                    <h3 class="text-md font-semibold text-gray-800 mb-3">Order Items</h3>
                    @foreach($order->orderItems as $item)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 flex items-center">
                            <img src="{{ asset('storage/' . $item->product->image[0]) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Gas Product' }}</p>
                                <p class="text-sm text-gray-700">Qty: {{ $item->quantity }} | {{ $order->currency }} {{ number_format($item->unit_amount, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Summary Card -->
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Summary</h2>
                    <div class="text-sm text-gray-800 space-y-3">
                        @php $subtotal = $order->price / 1.1; $tax = $order->price - $subtotal; @endphp
                        <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-medium">{{ $order->currency }} {{ number_format($subtotal, 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Taxes (10%)</span><span class="font-medium">{{ $order->currency }} {{ number_format($tax, 2) }}</span></div>
                        <hr class="my-3 border-gray-200">
                        <div class="flex justify-between text-lg font-semibold text-gray-900"><span>Total</span><span>{{ $order->currency }} {{ number_format($order->price, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>