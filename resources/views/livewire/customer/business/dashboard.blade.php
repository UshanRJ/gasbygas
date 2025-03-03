{{-- resources/views/livewire/customer/business/dashboard.blade.php --}}
<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">Business Customer Dashboard</h1>
                    
                    <div class="bg-green-50 p-4 rounded-lg mb-6">
                        <h2 class="font-semibold text-lg text-green-800">Welcome, {{ $user->first_name }}!</h2>
                        <p class="text-green-600">Welcome to your business customer dashboard. Here you can manage your business orders and profile.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-semibold mb-4">Business Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Business Contact:</span> {{ $user->first_name }} {{ $user->last_name }}
                                </div>
                                <div>
                                    <span class="font-medium">Email:</span> {{ $user->email }}
                                </div>
                                <div>
                                    <span class="font-medium">Business ID:</span> {{ $user->business_id }}
                                </div>
                                <div>
                                    <span class="font-medium">Mobile:</span> {{ $user->mobile }}
                                </div>
                                <div>
                                    <span class="font-medium">Address:</span> {{ $user->address }}
                                </div>
                                @if($user->certificate)
                                <div>
                                    <span class="font-medium">Certificate:</span> 
                                    <a href="{{ asset('storage/' . $user->certificate) }}" target="_blank" class="text-blue-600 hover:underline">View Certificate</a>
                                </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('customer.profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-semibold mb-4">Recent Business Orders</h3>
                            <p class="text-gray-500">
                                No recent orders found.
                            </p>
                            <div class="mt-4">
                                <a href="/ordergas" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Place New Business Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>