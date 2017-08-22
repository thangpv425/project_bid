@foreach($currentBids as $bid)
    <div class="col-sm-3 col-md-3">
        <div class="item">
            <div class="img-cover">
                <a href="#">
                    <img src="{{asset('/storage/image/zippo.jpg')}}" class="img-responsive">
                </a>
            </div>
            <div class="item-info">
                <div class="item-info-top">
                    <p><a href="#" class="description">Zippo world cup</a></p>
                    <div class="clearfix">
                        <div class="pull-left">
                            <a href="#">
                                <img src="{{asset('/storage/image/zippo.jpg')}}" class="img-circle" style="width: 20px;height: 20px">
                                Nguoi ban
                            </a>
                        </div>
                        <div class="pull-right text-right">
                            <span class="current-time">10:10:10</span>
                            <span class="bid-count">8 luot dau</span>
                        </div>
                    </div>
                </div>
                <div class="item-info-bottom">
                    <div class="clearfix">
                        <div class="pull-left">
                            <span class="cost-current">670K</span>
                            <span class="hightest-bid-user">Linh Dang</span>
                        </div>
                        <div class="pull-right">
                            <a href="{{route('bid-current', $bid->id)}}" class="btn price-button view-more">Xem phiên đấu giá</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach