@extends('user.index')
@section('user-page')
    <div class="home">
        <h1>Các sản phẩm bị hủy</h1>
        @if (count($bids) > 0)
            <div class="row list-item" id="current_bid">
                @foreach($bids as $bid)
                    <div class="col-sm-4 col-md-4">
                        @include('bid.item_bid_done')
                    </div>
                @endforeach
            </div>
            {{$bids->links()}}
        @endif
    </div>
@endsection
