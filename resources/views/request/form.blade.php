@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Enter Key') }}</div>
                @if(Session::has('alert'))
                    <p class="alert alert-danger">{{ Session::get('alert') }}</p>
                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('requestKey.download', $req->id) }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="key" class="col-md-4 col-form-label text-md-end">{{ __('Key') }}</label>

                            <div class="col-md-6">
                                <input id="key" type="text" class="form-control @error('key') is-invalid @enderror" name="key" required autofocus>
                                @error('key')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Download') }}
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