<?php

namespace App\Livewire;

use App\Models\GasCategory;
use App\Models\Products;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Products::where('is_active', 1);

        return view('livewire.products-page', [

            'products' => $products->paginate(5),
            'gasCategories' => GasCategory::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
