{{-- resources/views/livewire/auth/forgot-password.blade.php --}}
<div>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h1 class="text-2xl font-bold text-center mb-6">Forgot Password</h1>
            
            @if($status)
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $status }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetPasswordLink">
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input wire:model="email" id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" autofocus>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to login</a>
                    
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Email Password Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>