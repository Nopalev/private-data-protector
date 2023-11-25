@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List Request') }}</div>

                <div class="card-body">
                    @if(Session::has('alert'))
                        <p class="alert alert-danger">{{ Session::get('alert') }}</p>
                    @elseif(Session::has('status'))
                        <p class="alert alert-info">{{ Session::get('status') }}</p>
                    @endif
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>Request Accepted! Please share the key below:</p>
                            {!! \Session::get('success') !!}
                        </div>
                    @endif
                    @if (!empty($status))
                    <div class="alert alert-info" role="alert">
                        {{ $status }}
                    </div>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($requests as $request)
                        <li class="list-group-item">
                            <div class="row mb-3">
                                <p>From: {{ $request->user_req->username }}</p>
                                @foreach($files as $file)
                                    @if($request->file->id == $file->id)
                                        <p>Requested File: {{ $file->filename }}</p>
                                        @if($request->status == 'waiting')
                                        <form method="post" action="{{ route('requestKey.update') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $request->id }}">
                                            <button type="submit" class="btn btn-success">{{ __('Accept') }}</button>
                                        </form>
                                        @elseif($request->status == 'accepted')
                                        <p class="text-success">ACCEPTED</p>
                                        @else
                                        <p class="text-danger">DECLINED</p>
                                        @endif
                                        @break
                                    @endif
                                @endforeach
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection