<div class="w-full max-w-[85rem] py-8 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="bg-white font-poppins rounded-xl shadow-sm overflow-hidden">
    <div class="container mx-auto">
      <!-- Page Header -->
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-8 mb-8 rounded-t-xl">
        <h1 class="text-3xl font-bold text-white">Browse Our Products</h1>
        <p class="text-blue-100 mt-2">Find the perfect gas solutions for your needs</p>
      </div>
      
      <div class="flex flex-wrap px-4 pb-8">
        <!-- Sidebar Filters - Desktop -->
        <div class="w-full lg:w-1/4 pr-0 lg:pr-6 mb-8 lg:mb-0">
          <div class="sticky top-20">
            <!-- Categories Filter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
              <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Categories</h2>
              </div>
              <div class="p-6">
                <ul class="space-y-3">
                  @foreach($gasCategories as $category)
                    <li>
                      <label class="flex items-center space-x-3 cursor-pointer group">
                        <div class="relative flex items-center">
                          <input type="checkbox" id="{{ $category->slug }}" name="categories[]" value="{{ $category->id }}"
                            wire:model="selectedCategories"
                            class="form-checkbox h-5 w-5 text-blue-600 border-gray-300 rounded cursor-pointer focus:ring-blue-500">
                        </div>
                        <span class="text-gray-700 group-hover:text-blue-600 transition-colors">{{ $category->name }}</span>
                      </label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            
            <!-- Product Status Filter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
              <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Availability</h2>
              </div>
              <div class="p-6">
                <ul class="space-y-3">
                  <li>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                      <div class="relative flex items-center">
                        <input type="checkbox" id="in-stock" name="status[]" value="in-stock"
                          wire:model="filterInStock"
                          class="form-checkbox h-5 w-5 text-blue-600 border-gray-300 rounded cursor-pointer focus:ring-blue-500">
                      </div>
                      <span class="text-gray-700 group-hover:text-blue-600 transition-colors">In Stock</span>
                    </label>
                  </li>
                  <li>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                      <div class="relative flex items-center">
                        <input type="checkbox" id="on-sale" name="status[]" value="on-sale"
                          wire:model="filterOnSale"
                          class="form-checkbox h-5 w-5 text-blue-600 border-gray-300 rounded cursor-pointer focus:ring-blue-500">
                      </div>
                      <span class="text-gray-700 group-hover:text-blue-600 transition-colors">On Sale</span>
                    </label>
                  </li>
                </ul>
              </div>
            </div>
            
            <!-- Price Range Filter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
              <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Price Range</h2>
              </div>
              <div class="p-6">
                <div class="mb-4">
                  <input type="range" id="price-range" 
                    wire:model="priceRange" wire:change="applyPriceFilter"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                    min="1000" max="500000" value="100000" step="1000">
                </div>
                <div class="flex justify-between items-center">
                  <div class="text-sm font-medium text-gray-500">LKR 1,000</div>
                  <div class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                    LKR <span id="price-display">100,000</span>
                  </div>
                  <div class="text-sm font-medium text-gray-500">LKR 500,000</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Product Grid -->
        <div class="w-full lg:w-3/4">
          <!-- Sort Controls -->
          <div class="flex items-center justify-between mb-6 bg-gray-50 rounded-lg p-4 border border-gray-100">
            <div class="text-sm text-gray-600">
              Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> - 
              <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of 
              <span class="font-medium">{{ $products->total() }}</span> products
            </div>
                          <div class="flex items-center">
              <label class="text-sm text-gray-600 mr-2" for="sort-by">Sort by:</label>
              <select id="sort-by" name="sort" wire:model="sortBy"
                class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                <option value="latest">Latest</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
              </select>
            </div>
          </div>
          
          <!-- Product Grid -->
          <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
              <div class="group relative bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 transition-all hover:shadow-md h-[450px] flex flex-col" wire:key="{{ $product->id }}">
                <!-- Product Image -->
                <div class="relative bg-gray-200 group-hover:opacity-90 transition-opacity h-[200px] w-full overflow-hidden">
                  <img src="{{ asset('storage/' . $product->image[0]) }}" alt="{{ $product->name }}"
                    class="object-cover w-full h-full">
                    
                  <!-- Image hover effect only, no action buttons -->
                  <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition-all">
                  </div>
                </div>
                
                <!-- Product Info -->
                <div class="p-4 flex-grow">
                  <h3 class="font-medium text-gray-900 text-lg mb-2 group-hover:text-blue-600 transition-colors line-clamp-2 h-[56px]">
                    {{ $product->name }}
                  </h3>
                  <div class="flex justify-between items-center">
                    <div class="font-semibold text-lg text-blue-600">
                      LKR {{ number_format($product->price, 2) }}
                    </div>
                    @if(isset($product->old_price))
                      <div class="text-sm text-gray-500 line-through">
                        LKR {{ number_format($product->old_price, 2) }}
                      </div>
                    @endif
                  </div>
                  
                  <!-- Product Details -->
                  <div class="mt-3">
                    <div class="flex items-center text-sm text-gray-500 mb-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      In stock
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      Delivery available
                    </div>
                  </div>
                </div>
                
                <!-- Simple footer -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 mt-auto text-center">
                  <span class="text-sm text-gray-500">{{ $product->category->name ?? 'Gas Product' }}</span>
                </div>
              </div>
            @endforeach
          </div>
          
          <!-- Pagination -->
          <div class="mt-8">
            {{ $products->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  // Price range slider
  const priceRange = document.getElementById('price-range');
  const priceDisplay = document.getElementById('price-display');
  
  function updatePriceDisplay() {
    priceDisplay.textContent = Number(priceRange.value).toLocaleString();
  }
  
  // Initial update
  updatePriceDisplay();
  
  // Update on range input change
  priceRange.addEventListener('input', updatePriceDisplay);
  
  // Livewire hook for when the price display updates from the server
  document.addEventListener('livewire:load', function () {
    Livewire.on('priceRangeUpdated', function (value) {
      priceDisplay.textContent = Number(value).toLocaleString();
    });
  });
</script>