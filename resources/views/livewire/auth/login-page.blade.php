<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
          <div class="text-center">
            <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Don't have an account yet?
              <a wire:navigate class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/signup">
                Sign up here
              </a>
            </p>
          </div>

          <hr class="my-5 border-slate-300">

          <!-- Form -->
          <form>
          <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
  <!-- Email Address -->
  <div class="relative">
    <label for="email" class="block text-sm mb-2 dark:text-white">Email address</label>
    <input type="email" id="email" name="email"
      class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
      aria-describedby="email-error" required>
    
    <!-- Error Icon -->
    @error('email')
    <div class="absolute inset-y-0 right-3 flex items-center">
      <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
        <path
          d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
        </path>
      </svg>
    </div>
    @enderror

    <!-- Error Message -->
    @error('email')
    <p class="text-xs text-red-600 mt-1" id="email-error">{{ $message }}</p>
    @enderror
  </div>

  <!-- Password -->
  <div class="relative">
    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
    <input type="password" id="password" name="password"
      class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
      required>

    <!-- Error Icon -->
    @error('password')
    <div class="absolute inset-y-0 right-3 flex items-center">
      <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
        <path
          d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
        </path>
      </svg>
    </div>
    @enderror

    <!-- Error Message -->
    @error('password')
    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
  </div>
</div>

          </form>
          <!-- End Form -->
        </div>
      </div>
  </div>
</div>