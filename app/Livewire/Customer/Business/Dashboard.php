<?php

namespace App\Livewire\Customer\Business;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Dashboard extends Component
{
    public ?User $user;

    public function mount()
    {
        $user = Auth::user();
        
        // Check if user exists and is a business customer
        if (!$user || !$user instanceof User || !$user->isBusinessCustomer()) {
            return redirect()->route('login');
        }
        
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.customer.business.dashboard')
            ->layout('layouts.customer');
    }
}