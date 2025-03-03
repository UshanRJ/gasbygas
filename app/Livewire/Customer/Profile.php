<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Profile extends Component
{
    use WithFileUploads;

    public ?User $user;
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $mobile;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $nic = null;
    public $business_id = null;
    public $new_certificate = null;

    protected function rules()
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->user->id ?? ''),
            'address' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
        ];

        if ($this->user && $this->user->isPersonalCustomer()) {
            $rules['nic'] = 'required|string|max:20';
        } elseif ($this->user && $this->user->isBusinessCustomer()) {
            $rules['business_id'] = 'required|string|max:50';
            $rules['new_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        if (filled($this->password)) {
            $rules['current_password'] = 'required|current_password';
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    public function mount()
    {
        $user = Auth::user();
        
        // Check if user exists and is an instance of our User model
        if (!$user || !$user instanceof User) {
            return redirect()->route('login');
        }
        
        $this->user = $user;
        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->address = $this->user->address;
        $this->mobile = $this->user->mobile;
        $this->nic = $this->user->nic;
        $this->business_id = $this->user->business_id;
    }

    public function updateProfile()
    {
        $this->validate();

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'mobile' => $this->mobile,
        ];

        if ($this->user->isPersonalCustomer()) {
            $data['nic'] = $this->nic;
        } elseif ($this->user->isBusinessCustomer()) {
            $data['business_id'] = $this->business_id;

            if ($this->new_certificate) {
                $data['certificate'] = $this->new_certificate->store('certificates', 'public');
            }
        }

        if (filled($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        $this->reset(['current_password', 'password', 'password_confirmation', 'new_certificate']);
        session()->flash('message', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.customer.profile')
            ->layout('layouts.customer');
    }
}