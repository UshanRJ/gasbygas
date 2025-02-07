<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Forgot Password Page - GasByGas')]
class ForgotPasswordPage extends Component
{
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
