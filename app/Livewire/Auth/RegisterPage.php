<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Sign Up Page - GasByGas')]
class RegisterPage extends Component
{
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
