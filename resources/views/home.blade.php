@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List User and Files') }}</div>

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
                        @foreach($users as $user)
                        <li class="list-group-item">
                            <div class="row mb-3">
                                <span for="gender" class="col-md-4 col-form-span text-md-end">{{ $user->username }}</span>

                                @foreach($user->files as $item)
                                <ul class="list-group-item">
                                    <div class="row mb-3">
                                        <span for="gender" class="col-md-4 col-form-span text-md-end">{{ $item->filetype }}</span>
                                        <div class="col-md-6">
                                            @if($user->id == $isMe->id)
                                            <button type="button" class="btn btn-primary">
                                                <a class="nav-link active" aria-current="page" href="{{ route('file.show', $item->id) }}">
                                                    {{ __('Details') }}
                                                </a>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-success">
                                                <a class="nav-link active" aria-current="page" href="#">
                                                    {{ __('Request') }}
                                                </a>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </ul>
                                @endforeach
                            </div>
                        </li>
                        @endforeach
                        <div class="d-flex justify-content-end my-2">
                            <button type="button" class="btn btn-primary">
                                <a class="nav-link active" aria-current="page" href="{{ route('file.add') }}">
                                    {{ __('Add A File') }}
                                </a>
                            </button>
                        </div>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection