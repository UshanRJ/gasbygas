<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

#[Title('Login Page - GasByGas')] 
class LoginPage extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        // Only allow non-admin users to login via this form
        if ($user && $user->user_type === 'admin') {
            $this->addError('email', 'Admin users should login through the admin panel.');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            // Redirect based on user type
            if (Auth::user()->isPersonalCustomer()) {
                return redirect()->intended(route('personal.dashboard'));
            } elseif (Auth::user()->isBusinessCustomer()) {
                return redirect()->intended(route('business.dashboard'));
            }
            
            return redirect()->intended(route('home'));
        }

        $this->addError('email', trans('auth.failed'));
    }

    public function render()
    {
        return view('livewire.auth.login-page')
            ->layout('layouts.guest-page');
    }
}