@extends('layouts.app')
@section('content')
    @include('layouts.slide')
    <div class="home">
        <div class="container">
            <h1>Các sản phẩm đang đấu giá</h1>
            @if (count($bids) > 0)
                <div class="row list-item" id="current_bid">
                    @foreach($bids as $bid)
                        <div class="col-sm-3 col-md-3">
                             @include('bid.item')
                        </div>
                    @endforeach
                </div>
            @endif
            {{$bids->links()}}
        </div>
    </div>
@endsection
