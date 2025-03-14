@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Offline') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <img src="/images/icons/icon-192x192.png" alt="App Logo" style="width: 80px; margin-bottom: 20px;">
                        <h3>{{ __('You are currently offline') }}</h3>
                        <p>{{ __('Please check your internet connection and try again.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection