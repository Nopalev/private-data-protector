@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>
                <div class="card-body">

                    <div class="row mb-3">
                        <span for="username" class="col-md-4 col-form-span text-md-end">{{ __('Username') }}</span>

                        <div class="col-md-6">
                            <span>{{ $profile->username }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <span for="email" class="col-md-4 col-form-span text-md-end">{{ __('Email') }}</span>

                        <div class="col-md-6">
                            <span>{{ $profile->email }}</span>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="button" class="btn btn-primary">
                                <a class="nav-link active" aria-current="page" href="{{ route('profile.edit') }}">
                                    {{ __('Edit') }}
                                </a>
                            </button>

                            <button type="button" class="btn btn-danger">
                                <a class="nav-link active" aria-current="page" href="{{ route('password.change') }}">
                                    <strong>    
                                        {{ __('Change Password') }}
                                    </strong>
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection