@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Biodata') }}</div>
                <div class="card-body">

                    <div class="row mb-3">
                        <span for="name" class="col-md-4 col-form-span text-md-end">{{ __('Name') }}</span>

                        <div class="col-md-6">
                            <span>{{ $biodata->name }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <span for="gender" class="col-md-4 col-form-span text-md-end">{{ __('Gender') }}</span>

                        <div class="col-md-6">
                            <span>{{ $biodata->gender }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <span for="nationality" class="col-md-4 col-form-span text-md-end">{{ __('Nationality') }}</span>

                        <div class="col-md-6">
                            <span>{{ $biodata->nationality }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <span for="religion" class="col-md-4 col-form-span text-md-end">{{ __('Religion') }}</span>

                        <div class="col-md-6">
                            <span>{{ $biodata->religion }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <span for="marital_status" class="col-md-4 col-form-span text-md-end">{{ __('Marital Status') }}</span>
                        <div class="col-md-6">
                            <span>{{ $biodata->marital_status }}</span>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="button" class="btn btn-primary">
                                <a class="nav-link active" aria-current="page" href="{{ route('biodata.edit') }}">
                                    {{ __('Edit') }}
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