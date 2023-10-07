@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Encryption Settings') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('encryption.set') }}">
                        @method('PATCH')
                        @csrf

                        <div class="row mb-3">
                            <label for="method" class="col-md-4 col-form-label text-md-end">{{ __('Method') }}</label>

                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="method">
                                    @foreach($methods as $method)
                                    @if($loop->first)
                                    <option value={{$method}} selected>{{$method}}</option>
                                    @else
                                    <option value={{$method}}>{{$method}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="mode" class="col-md-4 col-form-label text-md-end">{{ __('Mode') }}</label>

                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="mode">
                                    @foreach($modes as $mode)
                                    @if($loop->first)
                                    <option value={{$mode}} selected>{{$mode}}</option>
                                    @else
                                    <option value={{$mode}}>{{$mode}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Set') }}
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