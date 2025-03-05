<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Role;
use App\Notifications\CustomerRegistered;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

#[Title('Sign Up Page - GasByGas')]

class RegisterPage extends Component
{
    use WithFileUploads;

    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $address = '';
    public $mobile = '';
    public $user_type = 'personal';
    public $nic = '';
    public $business_id = '';
    public $certificate;
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'user_type' => 'required|in:personal,business',
            'password' => 'required|string|confirmed|min:8',
        ];

        if ($this->user_type === 'personal') {
            $rules['nic'] = 'required|string|max:20';
        } else {
            $rules['business_id'] = 'required|string|max:50';
            $rules['certificate'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        return $rules;
    }

    public function register()
    {
        $this->validate();

        $certificatePath = null;
        if ($this->user_type === 'business' && $this->certificate) {
            $certificatePath = $this->certificate->store('certificates', 'public');
        }

        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'user_type' => $this->user_type,
            'nic' => $this->user_type === 'personal' ? $this->nic : null,
            'business_id' => $this->user_type === 'business' ? $this->business_id : null,
            'certificate' => $this->user_type === 'business' ? $certificatePath : null,
            'password' => Hash::make($this->password),
        ]);

        // Assign role based on user type
        $roleSlug = $this->user_type === 'personal' ? 'personal-customer' : 'business-customer';
        $role = Role::where('slug', $roleSlug)->first();
        
        if ($role) {
            $user->roles()->attach($role->id);
        }

        // Send welcome notification
        $user->notify(new CustomerRegistered($user));

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user type
        if ($user->isPersonalCustomer()) {
            return redirect()->route('personal.dashboard');
        } elseif ($user->isBusinessCustomer()) {
            return redirect()->route('business.dashboard');
        }

        return redirect()->route('home');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.auth.register-page')
            ->layout('layouts.guest-page');
    }
}
