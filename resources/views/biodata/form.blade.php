@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Biodata') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('biodata') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="gender">
                                    <option selected>Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Non-Binary">Non-Binary</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nationality" class="col-md-4 col-form-label text-md-end">{{ __('Nationality') }}</label>

                            <div class="col-md-6">
                                <input id="nationality" type="text" class="form-control @error('nationality') is-invalid @enderror" name="nationality" required>

                                @error('nationality')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="religion" class="col-md-4 col-form-label text-md-end">{{ __('Religion') }}</label>
                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="religion">
                                    <option selected>Islam</option>
                                    <option value="Christian">Christian</option>
                                    <option value="Protestant">Protestant</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="marital_status" class="col-md-4 col-form-label text-md-end">{{ __('Marital Status') }}</label>
                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="marital_status">
                                    <option selected>Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            
                            <div class="col-md-6">
                                <p class="alert alert-info">Please confirm your password.</p>
                                @if(Session::has('alert'))
                                <p class="alert alert-danger">{{ Session::get('alert') }}</p>
                                @endif
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection