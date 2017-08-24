@foreach($bids as $bid)
    <div class="col-sm-3 col-md-3">
        @include('bid.item')
    </div>
@endforeach