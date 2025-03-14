{{-- resources/views/livewire/auth/register.blade.php --}}
<div>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-8 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Create your account</h2>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 border border-gray-200">
                <form wire:submit.prevent="register" class="space-y-6">
                    <!-- Account Type Selector -->
                    <div class="grid grid-cols-2 gap-4">
                        <div wire:click="$set('user_type', 'personal')"
                            class="relative cursor-pointer rounded-lg border p-4 flex flex-col items-center shadow-sm {{ $user_type === 'personal' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-300 bg-white' }}">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full {{ $user_type === 'personal' ? 'bg-blue-600' : 'bg-gray-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 {{ $user_type === 'personal' ? 'text-white' : 'text-gray-500' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <span
                                class="mt-2 font-medium {{ $user_type === 'personal' ? 'text-blue-900' : 'text-gray-900' }}">Personal</span>
                            @if($user_type === 'personal')
                                <span class="absolute top-2 right-2 h-5 w-5 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                        <div wire:click="$set('user_type', 'business')"
                            class="relative cursor-pointer rounded-lg border p-4 flex flex-col items-center shadow-sm {{ $user_type === 'business' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-300 bg-white' }}">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full {{ $user_type === 'business' ? 'bg-blue-600' : 'bg-gray-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 {{ $user_type === 'business' ? 'text-white' : 'text-gray-500' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <span
                                class="mt-2 font-medium {{ $user_type === 'business' ? 'text-blue-900' : 'text-gray-900' }}">Business</span>
                            @if($user_type === 'business')
                                <span class="absolute top-2 right-2 h-5 w-5 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First
                                    name</label>
                                <div class="mt-1">
                                    <input wire:model="first_name" id="first_name" type="text"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="John">
                                </div>
                                @error('first_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                                <div class="mt-1">
                                    <input wire:model="last_name" id="last_name" type="text"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Doe">
                                </div>
                                @error('last_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <div class="mt-1">
                                <input wire:model="email" id="email" type="email"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="your.email@example.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <div class="mt-1">
                                <input wire:model="address" id="address" type="text"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="123 Main Street, City">
                            </div>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile number</label>
                            <div class="mt-1">
                                <input wire:model="mobile" id="mobile" type="tel" maxlength="10"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="0771234567">
                                <p class="mt-1 text-xs text-gray-500">Format: 0771234567 (10 digits starting with 0)</p>
                            </div>
                            @error('mobile')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="mobile-validation-message" class="mt-2 text-sm text-red-600 hidden"></div>
                        </div>
                    </div>

                    <!-- Conditional Fields -->
                    @if($user_type === 'personal')
                        <div class="space-y-4">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="bg-white px-3 text-sm font-semibold text-gray-900">Personal
                                        Information</span>
                                </div>
                            </div>

                            <div>
                                <label for="nic" class="block text-sm font-medium text-gray-700">NIC Number</label>
                                <div class="mt-1">
                                    <input wire:model="nic" id="nic" type="text" maxlength="12"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="123456789V or 000123456789">
                                    <p class="mt-1 text-xs text-gray-500">Format: 123456789V (9 digits + 'V') or 000123456789 (12 digits)</p>
                                </div>
                                @error('nic')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="nic-validation-message" class="mt-2 text-sm text-red-600 hidden"></div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="bg-white px-3 text-sm font-semibold text-gray-900">Business
                                        Information</span>
                                </div>
                            </div>

                            <div>
                                <label for="business_id" class="block text-sm font-medium text-gray-700">Business ID</label>
                                <div class="mt-1">
                                    <input wire:model="business_id" id="business_id" type="text"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="BUS-12345678">
                                </div>
                                @error('business_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="certificate" class="block text-sm font-medium text-gray-700">Business
                                    Certificate</label>
                                <div class="mt-1">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="certificate"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to
                                                        upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500">PDF, PNG, JPG or GIF (MAX. 10MB)</p>
                                            </div>
                                            <input wire:model="certificate" id="certificate" type="file" class="hidden" />
                                        </label>
                                    </div>
                                    <div wire:loading wire:target="certificate" class="mt-2 text-sm text-blue-600">
                                        <div class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Uploading...
                                        </div>
                                    </div>
                                </div>
                                @error('certificate')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Password Section -->
                    <div class="space-y-5">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-3 text-sm font-semibold text-gray-900">Security</span>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1">
                                <input wire:model="password" id="password" type="password"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                password</label>
                            <div class="mt-1">
                                <input wire:model="password_confirmation" id="password_confirmation" type="password"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Already have an account? Sign in
                            </a>
                        </div>
                        <button type="submit"
                            class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Client-side validation script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // NIC validation for format: 359506417V or 011523445678
            const nicInput = document.getElementById('nic');
            if (nicInput) {
                // Use blur instead of input to validate only when user leaves the field
                nicInput.addEventListener('blur', function() {
                    const value = this.value.trim();
                    const oldNicPattern = /^\d{9}[Vv]$/;
                    const newNicPattern = /^\d{12}$/;
                    const nicValidationMsg = document.getElementById('nic-validation-message');
                    
                    if (value === '') {
                        // Empty input - hide validation message
                        nicValidationMsg.classList.add('hidden');
                        this.classList.remove('border-red-500', 'border-green-500');
                    } else if (oldNicPattern.test(value) || newNicPattern.test(value)) {
                        // Valid format
                        nicValidationMsg.classList.add('hidden');
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                    } else {
                        // Invalid format
                        nicValidationMsg.textContent = 'NIC must be in format 123456789V (9 digits + V) or 000123456789 (12 digits)';
                        nicValidationMsg.classList.remove('hidden');
                        this.classList.remove('border-green-500');
                        this.classList.add('border-red-500');
                    }
                });
                
                // Optional: Clear error styling when user starts typing again
                nicInput.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500')) {
                        document.getElementById('nic-validation-message').classList.add('hidden');
                        this.classList.remove('border-red-500');
                    }
                });
            }

            // Mobile number validation for format: 0112445789
            const mobileInput = document.getElementById('mobile');
            if (mobileInput) {
                // Use blur instead of input to validate only when user leaves the field
                mobileInput.addEventListener('blur', function() {
                    const value = this.value.trim();
                    const mobilePattern = /^0\d{9}$/;
                    const mobileValidationMsg = document.getElementById('mobile-validation-message');
                    
                    if (value === '') {
                        // Empty input - hide validation message
                        mobileValidationMsg.classList.add('hidden');
                        this.classList.remove('border-red-500', 'border-green-500');
                    } else if (mobilePattern.test(value)) {
                        // Valid format
                        mobileValidationMsg.classList.add('hidden');
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                    } else {
                        // Invalid format
                        mobileValidationMsg.textContent = 'Mobile number must be in format 0771234567 (10 digits starting with 0)';
                        mobileValidationMsg.classList.remove('hidden');
                        this.classList.remove('border-green-500');
                        this.classList.add('border-red-500');
                    }
                });
                
                // Optional: Clear error styling when user starts typing again
                mobileInput.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500')) {
                        document.getElementById('mobile-validation-message').classList.add('hidden');
                        this.classList.remove('border-red-500');
                    }
                });
            }
        });
    </script>
</div>