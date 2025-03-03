<div class="w-full max-w-[100rem] py-10 px-8 sm:px-10 lg:px-10 mx-auto">
  <div class="flex h-full items-center">
  <main class="w-full max-w-5xl lg:w-3/4 mx-auto p-4">
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
          <div class="text-center">
            <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign up</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Already have an account?
              <a wire:navigate
                class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                href="/login">
                Sign in here
              </a>
            </p>
          </div>
          <hr class="my-5 border-slate-300">
          <!-- Form -->
          <form wire:submit.prevent='save' >
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- First Name -->
<div class="relative mt-4">
    <label for="first_name" class="block text-sm mb-2 dark:text-white">First Name</label>
    <div class="relative">
        <input type="text" id="first_name" wire:model="first_name"
            class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
            aria-describedby="first_name-error">

        <!-- Error Icon -->
        @error('first_name')
            <div class="absolute inset-y-0 right-3 flex items-center">
                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                    aria-hidden="true">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                </svg>
            </div>
        @enderror
    </div>

    <!-- Error Message -->
    @error('first_name')
        <p class="text-xs text-red-600 mt-2" id="first_name-error">{{ $message }}</p>
    @enderror
</div>



              <!-- Last Name -->
              <div class="relative mt-4">
                <label for="last_name" class="block text-sm mb-2 dark:text-white">Last Name</label>
                <div class="relative">
                  <input type="text" id="last_name" wire:model="last_name"
                    class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                    aria-describedby="email-error">

                  @error('last_name')
            <div class="absolute inset-y-0 end-0 flex items-center pe-3">
            <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
              aria-hidden="true">
              <path
              d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
            </svg>
            </div>
          @enderror


                  @error('last_name')
            <p class="text-xs text-red-600 mt-2" id="last_name-error">{{ $message }}</p>
          @enderror
                </div>
              </div>

              <!-- Email -->
              <div class="relative mt-4">
                <label for="email" class="block text-sm mb-2 dark:text-white">Email</label>
                <div class="relative">
                  <input type="text" id="email" wire:model="email"
                    class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                    aria-describedby="email-error">

                  @error('email')
            <div class="absolute inset-y-0 end-0 flex items-center pe-3">
            <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
              aria-hidden="true">
              <path
              d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
            </svg>
            </div>
          @enderror
                </div>

                @error('email')
          <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
        @enderror
              </div>

<!-- Address -->
<div class="relative mt-4">
    <label for="address" class="block text-sm mb-2 dark:text-white">Address</label>
    <div class="relative">
        <input type="text" id="address" wire:model="address" rows="3"
            class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"></textarea>

        <!-- Error Icon -->
        @error('address')
            <div class="absolute inset-y-0 right-3 flex items-center">
                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                    aria-hidden="true">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                </svg>
            </div>
        @enderror
    </div>

    <!-- Error Message -->
    @error('address')
        <p class="text-xs text-red-600 mt-2" id="address-error">{{ $message }}</p>
    @enderror
</div>


<!-- Mobile -->
<div class="relative mt-4">
    <label for="mobile" class="block text-sm mb-2 dark:text-white">Mobile</label>
    <div class="relative">
        <input type="tel" id="mobile" wire:model="mobile"
            class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
            aria-describedby="mobile-error">

        @error('mobile')
        <div class="absolute inset-y-0 end-0 flex items-center pe-3">
            <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                aria-hidden="true">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
            </svg>
        </div>
        @enderror
    </div>

    @error('mobile')
    <p class="text-xs text-red-600 mt-2" id="mobile-error">{{ $message }}</p>
    @enderror
</div>

<div class="relative mt-4">
    <!-- User Type -->
    <label for="user_type" class="block text-sm mb-2 dark:text-white">User Type</label>
    <select id="user_type" wire:model.live="user_type"
        class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
        <option value="">Select User Type</option>
        <option value="personal">Personal Customer</option>
        <option value="business">Business Customer</option>
    </select>

    @error('user_type')
    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>

<!-- NIC (Visible only for Personal Customer) -->
@if($user_type === 'personal')
<div class="relative mt-4">
    <label for="nic" class="block text-sm mb-2 dark:text-white">NIC</label>
    <input type="text" id="nic" wire:model="nic"
        class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">

    @error('nic')
    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>
@endif

<!-- Business ID (Visible only for Business Customer) -->
@if($user_type === 'business')
<div class="relative mt-4">
    <label for="business_id" class="block text-sm mb-2 dark:text-white">Business ID</label>
    <input type="text" id="business_id" wire:model="business_id"
        class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">

    @error('business_id')
    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>
@endif

<!-- Certificate Document (Visible only for Business Customer) -->
@if($user_type === 'business')
<div class="relative mt-4">
    <label for="certificate" class="block text-sm mb-2 dark:text-white">Certificate Document</label>
    <input type="file" id="certificate" wire:model="certificate" accept=".pdf,.jpg,.jpeg,.png"
        class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">

    @error('certificate')
    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>
@endif


<!-- Password -->
<div class="relative mt-4">
    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
    <div class="relative">
        <input type="{{ $showPassword ? 'text' : 'password' }}" id="password" wire:model="password"
            class="py-3 px-4 pr-10 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
        
        <!-- Toggle Password Visibility -->
        <button type="button" wire:click="togglePassword" class="absolute inset-y-0 right-3 flex items-center">
            @if($showPassword)
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.5c-7.5 0-11 7.5-11 7.5s3.5 7.5 11 7.5 11-7.5 11-7.5-3.5-7.5-11-7.5zm0 13.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                </svg>
            @else
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.5 12a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0zm-11.7.5a10 10 0 0 1 18.4 0m-2.9 3.6a7 7 0 0 1-12.6 0"/>
                </svg>
            @endif
        </button>
    </div>
    @error('password')
        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>

<!-- Confirm Password -->
<div class="relative mt-4">
    <label for="confirm_password" class="block text-sm mb-2 dark:text-white">Confirm Password</label>
    <div class="relative">
        <input type="{{ $showConfirmPassword ? 'text' : 'password' }}" id="confirm_password" wire:model="confirm_password"
            class="py-3 px-4 pr-10 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
        
        <!-- Toggle Confirm Password Visibility -->
        <button type="button" wire:click="toggleConfirmPassword" class="absolute inset-y-0 right-3 flex items-center">
            @if($showConfirmPassword)
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.5c-7.5 0-11 7.5-11 7.5s3.5 7.5 11 7.5 11-7.5 11-7.5-3.5-7.5-11-7.5zm0 13.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                </svg>
            @else
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.5 12a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0zm-11.7.5a10 10 0 0 1 18.4 0m-2.9 3.6a7 7 0 0 1-12.6 0"/>
                </svg>
            @endif
        </button>
    </div>
    @error('confirm_password')
        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror

    @if($password !== $confirm_password && $confirm_password !== null)
        <p class="text-xs text-red-600 mt-2">Passwords do not match</p>
    @endif
</div>

      <!-- Submit Button -->
      <div class="relative mt-11">
       <button type="submit"
        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Sign
        up</button>

       </div>
      
  </div>
  </form>
</div>
</div>
</div>
</div>



<!-- JavaScript to toggle fields based on User Type -->
<!-- <script>
  function toggleUserFields(selectElement) {
    const nicField = document.getElementById('nic_field');
    const businessIdField = document.getElementById('business_id_field');
    const certificateField = document.getElementById('certificate_field');

    const userType = selectElement.value;

    nicField.classList.toggle('hidden', userType !== 'personal');
    businessIdField.classList.toggle('hidden', userType !== 'business');
    certificateField.classList.toggle('hidden', userType !== 'business');
  }
</script> -->