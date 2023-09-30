@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Biodata') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('biodata.update') }}">
                        @method('PUT')
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $biodata->name }}" required autocomplete="name" autofocus>

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
                                    @foreach($genders as $gender)
                                    @if($gender === $biodata->gender)
                                    <option value={{$gender}} selected>{{$gender}}</option>
                                    @else
                                    <option value={{$gender}}>{{$gender}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nationality" class="col-md-4 col-form-label text-md-end">{{ __('Nationality') }}</label>

                            <div class="col-md-6">
                                <input id="nationality" type="text" class="form-control @error('nationality') is-invalid @enderror" name="nationality" required value="{{ $biodata->nationality }}">

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
                                    @foreach($religions as $religion)
                                    @if($religion === $biodata->religion)
                                    <option value={{$religion}} selected>{{$religion}}</option>
                                    @else
                                    <option value={{$religion}}>{{$religion}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="marital_status" class="col-md-4 col-form-label text-md-end">{{ __('Marital Status') }}</label>
                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="marital_status">
                                    @foreach($marital_statuses as $marital_status)
                                    @if($marital_status === $biodata->marital_status)
                                    <option value={{$marital_status}} selected>{{$marital_status}}</option>
                                    @else
                                    <option value={{$marital_status}}>{{$marital_status}}</option>
                                    @endif
                                    @endforeach
                                </select>
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