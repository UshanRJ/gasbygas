<?php

namespace App\Livewire;

use App\Models\GasCategory;
use App\Models\Outlets;
use App\Models\Products;
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

    public function mount()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => 'ordergas']);
        }
        
        // Get all active gas categories
        $allCategories = GasCategory::where('is_active', 1)->get();
        
        // Filter categories based on user type
        $user = Auth::user();
        $this->gasCategories = $this->filterCategoriesByUserType($allCategories, $user);
        
        $this->products = Products::where('is_active', 1)->get(['id', 'name', 'slug', 'price', 'image', 'category_id']);
        
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

        // Fetch gas outlets from the database
        $this->gasOutlets = Outlets::all();
        
        // Default shipping cost
        $this->shippingCost = 0; // Example fixed shipping cost
        
        // Calculate tax rate (12%)
        $this->taxes = $this->subtotal * 1;
        
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

    // Helper function to filter products
    private function filterProductsByCategory($categoryId)
    {
        $this->filteredProducts = $this->products->filter(function ($product) use ($categoryId) {
            return $product->category_id == $categoryId;
        });

        \Log::debug('Filtered products count: ' . $this->filteredProducts->count());
        \Log::debug('First few products: ' . json_encode($this->filteredProducts->take(2)->toArray()));
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

        // Calculate tax (12% of subtotal)
        $this->taxes = $this->subtotal * 0;
        
        $this->calculateGrandTotal();
    }

    public function calculateGrandTotal()
    {
        $this->grandTotal = $this->subtotal + $this->taxes + $this->shippingCost;
    }
    
    // Add place order method

    public function render()
    {
        return view('livewire.order-gas-page');
    }

    public function placeOrder()
    {
        try {
            // Log start of method
            \Log::info('Starting placeOrder method');
            
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
            
            // Get the current user
            $user = Auth::user();
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