@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
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
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 border border-blue-700 rounded">
                            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">
                                {{ __('Back') }}
                            </a>
                        </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection