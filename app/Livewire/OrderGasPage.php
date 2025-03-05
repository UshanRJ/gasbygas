<?php

namespace App\Livewire;

use App\Models\GasCategory;
use App\Models\Outlets;
use App\Models\Products;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Order Page - GasByGas')]

class OrderGasPage extends Component
{
    public $selectedProductId;
    public $selectedCategoryId;
    public $subtotal = 0;
    public $taxes = 0;
    public $shippingCost = 0;
    public $grandTotal = 0;
    public $products = [];
    public $filteredProducts = [];
    public $gasCategories = [];
    
    public $gasOutlets = [];
    public $selectedOutletId = '';
    
    // Add these new properties for the product display in summary
    public $selectedProductImage = null;
    public $selectedProductName = null;
    public $selectedCategoryName = null;
    
    // Optional: For special instructions
    public $specialInstructions = '';
    
    // Flag to indicate if products are available
    public $noProductsAvailable = false;

    public function mount()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => 'ordergas']);
        }
        
        // Get current user
        $user = Auth::user();
        
        // Check if user has active orders
        $activeOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['new', 'processing', 'rescheduled'])
            ->count();
            
        // If user has active orders, redirect with error message
        if ($activeOrders > 0) {
            session()->flash('error', 'You already have an active order. You cannot place a new order until your current order is completed or cancelled.');
            return redirect()->route('orders.index');
        }
        
        // Get all active gas categories
        $allCategories = GasCategory::where('is_active', 1)->get();
        
        // Filter categories based on user type
        $this->gasCategories = $this->filterCategoriesByUserType($allCategories, $user);
        
        // Fetch gas outlets from the database
        $this->gasOutlets = Outlets::all();
        
        // Get all active, in stock, and on sale products
        $this->products = Products::where('is_active', 1)
            ->where('is_stock', 1)
            ->where('on_sale', 1)
            ->get(['id', 'name', 'slug', 'price', 'image', 'category_id']);
        
        // Set default category if available
        if ($this->gasCategories->isNotEmpty()) {
            $this->selectedCategoryId = $this->gasCategories->first()->id;
            \Log::debug('Default category set to: ' . $this->selectedCategoryId);

            // Filter products for the default category
            $this->filterProductsByCategory($this->selectedCategoryId);
        } else {
            // If no categories, show all products
            $this->filteredProducts = $this->products;
        }

        // Check if products are available after filtering
        $this->checkProductAvailability();
        
        // Default shipping cost
        $this->shippingCost = 0; // Example fixed shipping cost
        
        // Calculate tax rate (0% as per the original code)
        $this->taxes = $this->subtotal * 0;
        
        // Calculate initial grand total
        $this->calculateGrandTotal();
    }
    
    /**
     * Filter gas categories based on user type
     * 
     * @param \Illuminate\Database\Eloquent\Collection $categories
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function filterCategoriesByUserType($categories, $user)
    {
        // Assuming user has a field 'user_type' that can be 'personal' or 'business'
        // If this field is named differently, adjust accordingly
        
        if ($user->user_type === 'personal') {
            // Personal users can only select Domestic gas category
            return $categories->filter(function ($category) {
                return strtolower($category->slug) === 'domestic-cooking-gas';
            });
        } elseif ($user->user_type === 'business') {
            // Business users can only select Commercial gas category
            return $categories->filter(function ($category) {
                return strtolower($category->slug) === 'commercial-cooking-gas';
            });
        }
        
        // If user type is not specified or is something else, 
        // you could either return all categories or an empty collection
        // depending on your requirements
        return $categories;
    }

    // Helper function to filter products and check stock availability
    private function filterProductsByCategory($categoryId)
    {
        // Step 1: Filter by category
        $categoryFilteredProducts = $this->products->filter(function ($product) use ($categoryId) {
            return $product->category_id == $categoryId;
        });

        // Step 2: Prepare to check stock in selected outlet
        if ($this->selectedOutletId) {
            $outlet = Outlets::find($this->selectedOutletId);
            
            // If outlet is selected, further filter by stock availability
            if ($outlet) {
                $this->filteredProducts = $categoryFilteredProducts->filter(function ($product) use ($outlet) {
                    // Check if product is in stock at the selected outlet
                    // This would need a relationship or method to check stock levels
                    // The implementation will depend on your database structure
                    return $this->isProductInStockAtOutlet($product->id, $outlet->id);
                });
            } else {
                $this->filteredProducts = $categoryFilteredProducts;
            }
        } else {
            $this->filteredProducts = $categoryFilteredProducts;
        }

        \Log::debug('Filtered products count: ' . $this->filteredProducts->count());
        
        // Check if there are any products available after filtering
        $this->checkProductAvailability();
    }
    
    /**
     * Check if a product is in stock at a specific outlet
     *
     * @param int $productId
     * @param int $outletId
     * @return bool
     */
    private function isProductInStockAtOutlet($productId, $outletId)
    {
        // This implementation depends on your database structure
        // You might have a stock table or a relationship between products and outlets
        // Here's a basic example assuming you have a stock table:
        
        // Example: 
        // return \App\Models\Stock::where('product_id', $productId)
        //     ->where('outlet_id', $outletId)
        //     ->where('quantity', '>', 0)
        //     ->exists();
        
        // If you don't have a dedicated stock table, you might need to check another way
        // For now, let's assume all products are in stock at all outlets
        return true;
    }

    /**
     * Check if any products are available after filtering
     */
    private function checkProductAvailability()
    {
        $this->noProductsAvailable = $this->filteredProducts->isEmpty();
        \Log::debug('No products available flag: ' . ($this->noProductsAvailable ? 'true' : 'false'));
    }

    public function updatedSelectedOutletId($outletId)
    {
        \Log::debug('Outlet ID selected: ' . $outletId);
        
        // Reset product selection when outlet changes
        $this->selectedProductId = null;
        $this->selectedProductImage = null;
        $this->selectedProductName = null;
        $this->subtotal = 0;
        
        // Re-filter products based on category and check stock at selected outlet
        if ($this->selectedCategoryId) {
            $this->filterProductsByCategory($this->selectedCategoryId);
        }
        
        $this->calculateGrandTotal();
    }

    public function updatedSelectedCategoryId($categoryId)
    {
        \Log::debug('Category ID selected: ' . $categoryId);

        // Reset product selection when category changes
        $this->selectedProductId = null;
        $this->selectedProductImage = null;
        $this->selectedProductName = null;
        $this->selectedCategoryName = null;
        $this->subtotal = 0;

        // Filter products by the selected category
        $this->filterProductsByCategory($categoryId);

        \Log::debug('Filtered products count: ' . $this->filteredProducts->count());

        // Update the category name
        $category = $this->gasCategories->firstWhere('id', $categoryId);
        if ($category) {
            $this->selectedCategoryName = $category->name;
        }

        $this->calculateGrandTotal();
    }

    public function updatedSelectedProductId($productId)
    {
        \Log::debug('Product ID: ' . $productId);

        if ($productId) {
            $product = $this->products->firstWhere('id', $productId);

            if ($product) {
                $this->subtotal = $product->price;
                
                // Set the product image and name for display in the summary
                $this->selectedProductImage = $product->image[0] ?? null;
                $this->selectedProductName = $product->name;
                
                // Get category name if not already set
                if (!$this->selectedCategoryName) {
                    $category = $this->gasCategories->firstWhere('id', $product->category_id);
                    $this->selectedCategoryName = $category ? $category->name : 'Gas Product';
                }
            } else {
                $this->subtotal = 0;
                $this->selectedProductImage = null;
                $this->selectedProductName = null;
            }
        } else {
            $this->subtotal = 0;
            $this->selectedProductImage = null;
            $this->selectedProductName = null;
        }

        // Calculate tax (0% of subtotal as per original code)
        $this->taxes = $this->subtotal * 0;
        
        $this->calculateGrandTotal();
    }

    public function calculateGrandTotal()
    {
        $this->grandTotal = $this->subtotal + $this->taxes + $this->shippingCost;
    }

    public function render()
    {
        return view('livewire.order-gas-page');
    }

    public function placeOrder()
    {
        try {
            // Log start of method
            \Log::info('Starting placeOrder method');
            
            // Get current user
            $user = Auth::user();
            
            // Check again if user has active orders (in case user had multiple tabs open)
            $activeOrders = Order::where('user_id', $user->id)
                ->whereIn('status', ['new', 'processing', 'rescheduled'])
                ->count();
                
            // If user has active orders, show error and return
            if ($activeOrders > 0) {
                session()->flash('error', 'You already have an active order. You cannot place a new order until your current order is completed or cancelled.');
                return redirect()->route('orders.index');
            }
            
            // Validate required fields
            $this->validate([
                'selectedProductId' => 'required',
                'selectedOutletId' => 'required',
            ], [
                'selectedProductId.required' => 'Please select a product',
                'selectedOutletId.required' => 'Please select a gas outlet',
            ]);
            
            \Log::info('Validation passed');
            
            // Generate unique order token
            $orderToken = $this->generateUniqueId();
            \Log::info('Generated order token: ' . $orderToken);
            
            \Log::info('User ID: ' . $user->id);
            
            // Set default values
            $paymentMethod = 'payAtStore'; // Default payment method
            $paymentStatus = 'pending';
            $status = 'new';
            $currency = 'LKR';
            $cylinderStatus = 'pending';
            $shippingMethod = 'pickup';
            
            \Log::info('Creating order with outlet ID: ' . $this->selectedOutletId . ', grand total: ' . $this->grandTotal);
            
            // Create order record
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'outlet_id' => $this->selectedOutletId,
                'price' => $this->grandTotal,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'currency' => $currency,
                'cylinder_status' => $cylinderStatus,
                'shipping_method' => $shippingMethod,
                'notes' => $this->specialInstructions
            ]);
            
            \Log::info('Order created with ID: ' . ($order->id ?? 'NULL - Order not created'));
            
            // Calculate scheduled date (+7 days from now)
            $scheduledDate = now()->addDays(7);
            
            // Create order item record
            $orderItem = \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $this->selectedProductId,
                'quantity' => 1, // Default quantity
                'unit_amount' => $this->subtotal,
                'total_amount' => $this->subtotal,
                'order_token' => $orderToken,
                'scheduled_date' => $scheduledDate
            ]);
            
            \Log::info('Order item created with ID: ' . ($orderItem->id ?? 'NULL - Order item not created'));
            
            // Log success
            \Log::info('Order placed successfully by user: ' . $user->id);
            \Log::info('Order ID: ' . $order->id);
            \Log::info('Product: ' . $this->selectedProductId);
            \Log::info('Outlet: ' . $this->selectedOutletId);
            \Log::info('Total: ' . $this->grandTotal);
            \Log::info('Scheduled Date: ' . $scheduledDate);
            
            // Show success message
            session()->flash('message', 'Your order has been placed successfully!');
            
            // Check if the orders.show route exists
            if (!\Route::has('orders.show')) {
                \Log::error('Route orders.show does not exist!');
                session()->flash('error', 'Order placed but could not navigate to order details.');
                return null;
            }
            
            // Redirect to orders page or confirmation page
            return redirect()->route('orders.show', $order->id);
        } 
        catch (\Exception $e) {
            // Log any exceptions
            \Log::error('Exception in placeOrder: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Flash error message to user
            session()->flash('error', 'There was a problem placing your order: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate unique identifier for order token
     *
     * @return string
     */
    protected function generateUniqueId()
    {
        return \Illuminate\Support\Str::uuid()->toString();
    }
}