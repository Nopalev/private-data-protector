@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Uploaded Files') }}</div>

                <div class="card-body">
                    @if(Session::has('alert'))
                    <p class="alert alert-danger">{{ Session::get('alert') }}</p>
                    @elseif(Session::has('status'))
                    <p class="alert alert-info">{{ Session::get('status') }}</p>
                    @endif
                    @if (!empty($status))
                    <div class="alert alert-info" role="alert">
                        {{ $status }}
                    </div>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($files as $file)
                        <li class="list-group-item">
                            <div class="row mb-3">
                                <span for="gender" class="col-md-4 col-form-span text-md-end">{{ $file->filetype }}</span>

                                <div class="col-md-6">
                                    <span>{{ $file->filename }}</span>
                                    <form method="POST" action="{{ route('file.show') }}" class="hidden">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-primary" name="file_id" value="{{ $file->id }}">
                                            {{ __('Details') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <div class="col-md-8 offset-md-4">
                        <button type="button" class="btn btn-primary">
                            <a class="nav-link active" aria-current="page" href="{{ route('file.add') }}">
                                {{ __('Add A File') }}
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection