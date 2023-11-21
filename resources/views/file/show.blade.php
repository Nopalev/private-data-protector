@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('File Upload') }}</div>

                <div class="card-body">
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
                        <button type="button" class="btn btn-primary">
                            <a class="nav-link active" aria-current="page" href="{{ route('file.download', $file->id) }}">
                                {{ __('Download') }}
                            </a>
                        </button>
                    </div>
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