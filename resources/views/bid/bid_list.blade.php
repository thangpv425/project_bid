@extends('layouts.app')
@section('content')
    @include('layouts.slide')
    <div class="home">
        <div class="container">
            <h1 class="text-center">Danh sách cuộc đấu giá</h1>
            <div class="row">
                <div class="col-md-3" style="margin: 20px 0px 50px 0px;">
                   <select class="form-control" id="bid_type">
                       <option selected value="all">Tất cả</option>
                       <option value="bidding">Đang đấu giá</option>
                       <option value="end">Đã kết thúc</option>
                   </select>
                </div>

            </div>

            @if (count($bids) > 0)
                <div class="row list-item" id="bid_list">
                    @foreach($bids as $bid)
                        <div class="col-sm-3 col-md-3">
                            @include('bid.item')
                        </div>
                    @endforeach
                </div>
            @endif
            <div id="refresh">
                {{$bids->links()}}
            </div>
        </div>
    </div>
    <script type="text/javascript" >
        $(document).ready(function(){
            $('#bid_type').change(function() {
                var bid_type = $('#bid_type option:selected').val();
                $.ajax({
                    url: '?bid_type=' + bid_type,
                    type: 'get',
                })
                    .done(function(data) {
                        $('#bid_list').html(data.html);
                        $('#refresh').html(data.paginate);
                        console.log(data.paginate);
                    })
            });
        });
    </script>
@endsection
