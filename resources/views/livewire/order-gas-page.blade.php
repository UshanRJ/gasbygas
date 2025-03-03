<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
	<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
		Request Gas
	</h1>
	<div class="grid grid-cols-12 gap-4">
		<div class="md:col-span-12 lg:col-span-8 col-span-12">
			<!-- Card -->
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<!-- Shipping Address -->
				<div class="mb-6">
					<h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
						Order Form
					</h2>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="gasCategory">
							Gas Category
						</label>
						<select id="gasCategory" wire:model.live="selectedCategoryId"
							class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none">
							<option value="" disabled selected>Select a Gas Category</option>
							@foreach($gasCategories as $category)
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endforeach
							<!-- Add more options as needed -->
						</select>
					</div>

					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="products">
							Product Type
						</label>
						<div class="grid grid-cols-3 sm:grid-cols-4 gap-4 mt-2">
							@foreach($filteredProducts as $product)
									<label class="flex items-center space-x-3 cursor-pointer">
										<input type="radio" id="product_{{ $product->id }}" name="product"
											value="{{ $product->id }}" class="hidden peer" wire:model.live="selectedProductId" />
										<span class="peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:bg-blue-100 
							dark:peer-checked:ring-gray-600 peer-checked:bg-gray-700 rounded-lg p-2 
							flex flex-col items-center">
											<img src="{{ asset('storage/' . $product->image[0]) }}" alt="{{ $product->name }}"
												class="w-16 h-16 object-cover rounded-full mb-2" />
											<span class="text-sm text-center dark:text-white">{{ $product->name }}</span>
										</span>
									</label>
							@endforeach
						</div>
					</div>
					<div class="grid grid-cols-2 gap-4 mt-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="first_name">
								First Name
							</label>
							<input
								class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
								id="first_name" type="text">
							</input>
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="last_name">
								Last Name
							</label>
							<input
								class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
								id="last_name" type="text">
							</input>
						</div>
					</div>
					<div class="grid grid-cols-2 gap-4 mt-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="phone">
								Phone
							</label>
							<input
								class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
								id="phone" type="text">
							</input>
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="email">
								Email
							</label>
							<input
								class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
								id="phone" type="text">
							</input>
						</div>
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="address">
							Address
						</label>
						<input
							class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
							id="address" type="text">
						</input>
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="district">
							District
						</label>
						<input
							class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
							id="city" type="text">
						</input>
					</div>

				</div>

			</div>
			<!-- End Card -->
		</div>
		<div class="md:col-span-12 lg:col-span-4 col-span-12">
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					ORDER SUMMARY
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>Subtotal</span>
					<span>LKR {{ number_format($subtotal, 2) }}</span>
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>Taxes</span>
					<span>LKR {{ number_format($taxes, 2) }}</span>
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>Shipping Cost</span>
					<span>LKR {{ number_format($shippingCost, 2) }}</span>
				</div>
				<hr class="bg-slate-400 my-4 h-1 rounded">
				<div class="flex justify-between mb-2 font-bold">
					<span>Grand Total</span>
					<span>LKR {{ number_format($grandTotal, 2) }}</span>
				</div>
			</div>
			<button class="bg-green-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-green-600">
				Place Order
			</button>
			<div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					BASKET SUMMARY
				</div>
				<!-- <ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
					<li class="py-3 sm:py-4">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<img alt="Neil image" class="w-12 h-12 rounded-full"
									src="https://iplanet.one/cdn/shop/files/iPhone_15_Pro_Max_Blue_Titanium_PDP_Image_Position-1__en-IN_1445x.jpg?v=1695435917">
								</img>
							</div>
							<div class="flex-1 min-w-0 ms-4">
								<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
									Apple iPhone 15 Pro Max
								</p>
								<p class="text-sm text-gray-500 truncate dark:text-gray-400">
									Quantity: 1
								</p>
							</div>
							<div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
								$320
							</div>
						</div>
					</li>
					<li class="py-3 sm:py-4">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<img alt="Neil image" class="w-12 h-12 rounded-full"
									src="https://iplanet.one/cdn/shop/files/iPhone_15_Pro_Max_Blue_Titanium_PDP_Image_Position-1__en-IN_1445x.jpg?v=1695435917">
								</img>
							</div>
							<div class="flex-1 min-w-0 ms-4">
								<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
									Apple iPhone 15 Pro Max
								</p>
								<p class="text-sm text-gray-500 truncate dark:text-gray-400">
									Quantity: 1
								</p>
							</div>
							<div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
								$320
							</div>
						</div>
					</li>
					<li class="py-3 sm:py-4">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<img alt="Neil image" class="w-12 h-12 rounded-full"
									src="https://iplanet.one/cdn/shop/files/iPhone_15_Pro_Max_Blue_Titanium_PDP_Image_Position-1__en-IN_1445x.jpg?v=1695435917">
								</img>
							</div>
							<div class="flex-1 min-w-0 ms-4">
								<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
									Apple iPhone 15 Pro Max
								</p>
								<p class="text-sm text-gray-500 truncate dark:text-gray-400">
									Quantity: 1
								</p>
							</div>
							<div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
								$320
							</div>
						</div>
					</li>
				</ul> -->
			</div>
		</div>
	</div>
</div>