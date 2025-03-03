<?php

namespace App\Livewire\Customer\Personal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Dashboard extends Component
{
    public ?User $user;

    public function mount()
    {
        $user = Auth::user();
        
        // Check if user exists and is a personal customer
        if (!$user || !$user instanceof User || !$user->isPersonalCustomer()) {
            return redirect()->route('login');
        }
        
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.customer.personal.dashboard')
            ->layout('layouts.customer');
    }
}