@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$message['title']}}</div>
                    <div class="panel-body">

                        @if ($message['type'] == 'success')
                            <div class="alert alert-success">
                                {{$message['data']}}
                            </div>
                        @else
                            <div class="alert alert-danger">
                                {{$message['data']}}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
