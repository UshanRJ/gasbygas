<div class="w-full max-w-7xl py-8 px-4 sm:px-6 lg:px-8 mx-auto">
	<!-- Header -->
	<div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-8 mb-6 rounded-xl shadow-sm">
		<div class="flex items-center justify-between">
			<div>
				<h1 class="text-2xl font-bold text-white">Gas Order</h1>
				<p class="text-blue-100 mt-1">Place your order</p>
			</div>
			<div class="hidden md:block">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-10" fill="none"
					viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
				</svg>
			</div>
		</div>
	</div>

	<!-- No Products Available Alert -->
	@if($noProductsAvailable)
	<div x-data="{ open: true }" x-show="open" class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-5 mb-6 rounded-lg shadow-sm" role="alert">
		<div class="flex">
			<div class="flex-shrink-0">
				<svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
				</svg>
			</div>
			<div class="ml-3">
				<h3 class="text-sm font-medium text-yellow-800">No Products Available</h3>
				<div class="mt-1 text-sm text-yellow-700">
					<p>We currently don't have any products available for your selection based on the criteria. We'll notify you when new stock arrives.</p>
				</div>
			</div>
			<div class="ml-auto pl-3">
				<div class="-mx-1.5 -my-1.5">
					<button @click="open = false" class="inline-flex bg-yellow-50 rounded-md p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
						<span class="sr-only">Dismiss</span>
						<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>
			</div>
		</div>
	</div>
	@endif

	<!-- Session Flash Messages -->
	@if (session()->has('message'))
		<div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
			<div class="flex">
				<div class="flex-shrink-0">
					<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
					</svg>
				</div>
				<div class="ml-3">
					<p class="text-sm font-medium text-green-800">Success</p>
					<p class="text-sm text-green-700">{{ session('message') }}</p>
				</div>
			</div>
		</div>
	@endif
	
	@if (session()->has('error'))
		<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
			<div class="flex">
				<div class="flex-shrink-0">
					<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
					</svg>
				</div>
				<div class="ml-3">
					<p class="text-sm font-medium text-red-800">Error</p>
					<p class="text-sm text-red-700">{{ session('error') }}</p>
				</div>
			</div>
		</div>
	@endif

	<div class="grid grid-cols-12 gap-6">
		<div class="md:col-span-12 lg:col-span-8 col-span-12">
			<!-- Order Form Card -->
			<div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
				<!-- Form Header -->
				<div class="mb-6 flex items-center">
					<div class="flex-shrink-0 bg-blue-100 rounded-lg p-3 mr-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
							viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
						</svg>
					</div>
					<div>
						<h2 class="text-xl font-semibold text-gray-800">Order Details</h2>
						<p class="text-sm text-gray-500">Choose your product</p>
					</div>
				</div>

				<!-- Gas Outlet Selection with Required Indicator -->
				<div class="mb-5">
					<label class="block text-sm font-medium text-gray-700 mb-1" for="gasOutlet">
						Select Gas Outlet <span class="text-red-500">*</span>
					</label>
					<div class="relative rounded-md shadow-sm h-10">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none ">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 h-10" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h10" />
							</svg>
						</div>
						<select id="gasOutlet" wire:model.live="selectedOutletId" required
							class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm h-10 @error('selectedOutletId') border-red-300 @enderror">
							<option value="" disabled selected aria-required="true">Select Gas Outlet</option>
							@foreach($gasOutlets as $outlet)
								<option value="{{ $outlet->id }}">{{ $outlet->name }} - {{ $outlet->location }}</option>
							@endforeach
						</select>
					</div>
					@error('selectedOutletId')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<!-- Gas Category Selection -->
				<div class="mb-5">
					<label class="block text-sm font-medium text-gray-700 mb-1" for="gasCategory">
						Gas Category
					</label>
					<div class="relative rounded-md shadow-sm h-10">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
							</svg>
						</div>
						<select id="gasCategory" wire:model.live="selectedCategoryId"
							class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm h-10">
							<option value="" disabled selected>Select a Gas Category</option>
							@foreach($gasCategories as $category)
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<!-- Product Selection with Required Indicator -->
				<div class="mb-6">
					<label class="block text-sm font-medium text-gray-700 mb-2" for="products">
						Product Type <span class="text-red-500">*</span>
					</label>
					
					@if($filteredProducts->isNotEmpty())
						<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
							@foreach($filteredProducts as $product)
								<label class="cursor-pointer">
									<input type="radio" id="product_{{ $product->id }}" name="product"
										value="{{ $product->id }}" class="hidden peer" wire:model.live="selectedProductId"
										required />
									<div class="peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all 
								rounded-lg p-4 border border-gray-200 shadow-sm flex flex-col items-center h-full">
										<img src="{{ asset('storage/' . $product->image[0]) }}" alt="{{ $product->name }}"
											class="w-16 h-16 object-cover rounded-full mb-3" />
										<span
											class="text-sm font-medium text-center text-gray-800 line-clamp-2">{{ $product->name }}</span>
										<span class="text-xs font-semibold text-blue-600 mt-1">LKR
											{{ number_format($product->price, 2) }}</span>
									</div>
								</label>
							@endforeach
						</div>
					@elseif($selectedCategoryId)
						<div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
							</svg>
							<h3 class="text-gray-700 font-medium mb-1">No Products Available</h3>
							<p class="text-gray-500 text-sm">There are currently no products available in this category or at the selected outlet.</p>
						</div>
					@else
						<div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
							</svg>
							<h3 class="text-gray-700 font-medium mb-1">Select a Category</h3>
							<p class="text-gray-500 text-sm">Please select a category to view available products</p>
						</div>
					@endif
					
					@error('selectedProductId')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<!-- Your Information Section -->
				<div class="bg-gray-50 p-4 rounded-lg mb-6">
					<div class="flex items-center justify-between mb-4">
						<div class="flex items-center">
							<div class="flex-shrink-0 bg-indigo-100 rounded-full p-2 mr-3">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
									viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
								</svg>
							</div>
							<h3 class="text-sm font-semibold text-gray-800">Your Information</h3>
						</div>
						<div class="text-xs text-gray-500">Pre-filled from your profile</div>
					</div>

					<!-- Display User Info (Read-only) -->
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div class="flex flex-col">
							<span class="text-xs text-gray-500 mb-1">Full Name</span>
							<span class="text-sm text-gray-800">{{ Auth::user()->first_name }}
								{{ Auth::user()->last_name }}</span>
						</div>
						<div class="flex flex-col">
							<span class="text-xs text-gray-500 mb-1">Email Address</span>
							<span class="text-sm text-gray-800">{{ Auth::user()->email }}</span>
						</div>
						<div class="flex flex-col">
							<span class="text-xs text-gray-500 mb-1">Phone Number</span>
							<span class="text-sm text-gray-800">{{ Auth::user()->mobile }}</span>
						</div>
						<div class="flex flex-col">
							<span class="text-xs text-gray-500 mb-1">Delivery Address</span>
							<span class="text-sm text-gray-800">{{ Auth::user()->address }}</span>
						</div>
					</div>
				</div>

				<!-- Special Instructions -->
				<div class="mb-5">
					<label class="block text-sm font-medium text-gray-700 mb-1" for="special_instructions">
						Special Instructions (Optional)
					</label>
					<div class="relative rounded-md shadow-sm">
						<textarea id="special_instructions" wire:model="specialInstructions" rows="3"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
							placeholder="Any special delivery instructions, landmarks, etc."></textarea>
					</div>
				</div>
			</div>
		</div>

		<div class="md:col-span-12 lg:col-span-4 col-span-12">
			<!-- Order Summary Card -->
			<div class="bg-white rounded-xl shadow-sm p-5 sm:p-6 border border-gray-100 sticky top-6">
				<div class="flex items-center mb-4">
					<div class="flex-shrink-0 bg-green-100 rounded-lg p-3 mr-3">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
							viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
						</svg>
					</div>
					<h2 class="text-lg font-semibold text-gray-800">Order Summary</h2>
				</div>

				@if($selectedProductId)
					<div class="bg-gray-50 rounded-lg p-4 mb-4">
						<div class="flex items-center mb-3">
							<img src="{{ asset('storage/' . $selectedProductImage) }}" alt="{{ $selectedProductName }}"
								class="w-12 h-12 rounded-lg object-cover mr-4" />
							<div>
								<h3 class="text-sm font-medium text-gray-800">{{ $selectedProductName }}</h3>
								<p class="text-xs text-gray-500">{{ $selectedCategoryName }}</p>
							</div>
						</div>
					</div>
				@endif

				<div class="space-y-3">
					<div class="flex justify-between text-sm">
						<span class="text-gray-600">Subtotal</span>
						<span class="font-medium text-gray-800">LKR {{ number_format($subtotal, 2) }}</span>
					</div>
					<div class="flex justify-between text-sm">
						<span class="text-gray-600">Taxes</span>
						<span class="font-medium text-gray-800">LKR {{ number_format($taxes, 2) }}</span>
					</div>
					<div class="flex justify-between text-sm">
						<span class="text-gray-600">Shipping Cost</span>
						<span class="font-medium text-gray-800">LKR {{ number_format($shippingCost, 2) }}</span>
					</div>
					<hr class="my-3 border-gray-200">
					<div class="flex justify-between">
						<span class="font-medium text-gray-800">Grand Total</span>
						<span class="font-bold text-blue-600 text-lg">LKR {{ number_format($grandTotal, 2) }}</span>
					</div>
				</div>

				<!-- Modified Place Order Button with Tooltip -->
				<div class="relative">
					<button wire:click="placeOrder"
						class="mt-6 w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium py-3 px-4 rounded-lg shadow-sm flex items-center justify-center transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
						@if(!$selectedProductId || !$selectedOutletId || $noProductsAvailable) 
							disabled 
							title="Please select both a Gas Outlet and an available Product to proceed" 
						@endif>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
							stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
						</svg>
						Place Order
					</button>
					@if(!$selectedProductId || !$selectedOutletId)
						<div
							class="absolute -top-10 left-0 right-0 mx-auto bg-gray-800 text-white text-xs rounded py-1 px-2 text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-64 pointer-events-none">
							Gas Outlet and Product selection are mandatory to proceed with the order
						</div>
					@elseif($noProductsAvailable)
						<div
							class="absolute -top-10 left-0 right-0 mx-auto bg-gray-800 text-white text-xs rounded py-1 px-2 text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-64 pointer-events-none">
							No products are currently available that meet the criteria
						</div>
					@endif
				</div>

				<!-- Required Fields Note -->
				<div class="mt-4 text-xs text-gray-500 text-center">
					<span class="text-red-500">*</span> Required fields must be completed to place your order
				</div>

				<div class="mt-4 text-center text-xs text-gray-500">
					By placing your order, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms of
						Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Script for redirect if user is not logged in -->
<script>
	document.addEventListener('DOMContentLoaded', function () {
		@guest
			window.location.href = "{{ route('login') }}?redirect=order";
		@endguest
	});
</script>