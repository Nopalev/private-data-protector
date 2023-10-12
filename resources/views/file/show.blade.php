@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('File Upload') }}</div>

                <div class="card-body">
                    @if ($file->filetype === 'image')
                    <img src="{{ asset('storage/images/'.$file->filename) }}">
                    @elseif ($file->filetype === 'video')
                    <video controls width="800" height="600">
                        <source src="{{ asset('storage/videos/'.$file->filename) }}" type="video/mp4">
                    </video>
                    @endif
                    <ul>
                        <li>File Name: {{ $file->filename }}</li>
                        <li>File Type: {{ $file->filetype }}</li>
                        <li>Uploaded By: {{ Auth::user()->username }}</li>
                        <li>Uploaded At: {{ $file->created_at }}</li>
                    </ul>
                    <div class="col-md-8 offset-md-4">
                        <button type="button" class="btn btn-primary">
                            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">
                                {{ __('Back') }}
                            </a>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('file.download', $file->id) }}" class="hidden">
                        @csrf
                        @method('GET')
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
                                    {{ __('Download') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('file.delete', $file->id) }}" class="hidden">
                        @csrf
                        @method('DELETE')<div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-danger">
                                    <strong>
                                        {{ __('Delete') }}
                                    </strong>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection