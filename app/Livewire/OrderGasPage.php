<?php

namespace App\Livewire;

use App\Models\GasCategory;
use App\Models\Products;
use Livewire\Attributes\Title;
use Livewire\Component;

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

    public function mount()
    {
        $this->gasCategories = GasCategory::where('is_active', 1)->get();
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
    }

        // Helper function to filter products
        private function filterProductsByCategory($categoryId)
        {
            $this->filteredProducts = $this->products->filter(function($product) use ($categoryId) {
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
        $this->subtotal = 0;
        
        // Filter products by the selected category
        $this->filteredProducts = $this->products->filter(function($product) use ($categoryId) {
            return $product->category_id == $categoryId;
        });
        
        \Log::debug('Filtered products count: ' . $this->filteredProducts->count());
        
        $this->calculateGrandTotal();
    }

    public function updatedSelectedProductId($productId)
    {
        \Log::debug('Product ID: ' . $productId);
        
        if ($productId) {
            $product = $this->products->firstWhere('id', $productId);
            
            if ($product) {
                $this->subtotal = $product->price;
            } else {
                $this->subtotal = 0;
            }
        } else {
            $this->subtotal = 0;
        }
        
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
}