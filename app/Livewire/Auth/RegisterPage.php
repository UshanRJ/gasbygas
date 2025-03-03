<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;

#[Title('Sign Up Page - GasByGas')]
class RegisterPage extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $mobile;
    public $user_type;
    public $nic;
    public $business_id;
    public $certificate;
    public $password;
    public $confirm_password;
    public $showPassword = false;
    public $showConfirmPassword = false;

    protected $rules = [
        'password' => 'required|min:8',
        'confirm_password' => 'required|same:password',
    ];

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function save()
    {
        // Validate
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'address' => 'required|string|max:500',
            'mobile' => 'required|digits_between:10,15',
            'user_type' => 'required',
            'nic' => 'required_if:user_type,personal',
            'business_id' => 'required_if:user_type,business',
            'certificate' => 'required_if:user_type,business|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'confirm_password' => 'required|string|min:8'
        ]);

        // Create a new user in the database
        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'user_type' => $this->user_type,
            'nic' => $this->user_type === 'personal' ? $this->nic : null,
            'business_id' => $this->user_type === 'business' ? $this->business_id : null,
            'certificate' => $this->user_type === 'business' ? ($certificatePath ?? null) : null,
            'password' => Hash::make($this->password),
        ]);

        // Login using the user
        auth()->login($user);

        // redirect to home page
        return redirect()->intended();

    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
