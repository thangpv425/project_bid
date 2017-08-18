$(function(){
	$('.input-bid button').on('click', function(){
		var amount = $('.input-bid input[name="amount"]').val();
		var name = $('.input-bid input[name="name"]').val();
		var id = $('.input-bid input[name="id"]').val();
		var bidId = $('input[name="bid_id"]').val();
		var current_price = parseInt($('.bid-current-info').attr('data'), 10);

		if (!isInteger(amount)) {
            alert("data input must be a number");
            return;
		}

        if(amount < current_price){
            alert('Nhap gia lon hon '+current_price);
            return;
        }

        $.post("/bid-current/"+bidId,
            {
                user_name: name,
                user_id: id,
                real_bid_amount: amount
            },

            function(respont){
        		if (respont.type === 'error') {
        			alert(respont.data);
				} else {
                    $('.bid-current-info').html(respont.data.current_price);
                    $('.hightest-bid-user').html(respont.data.current_highest_bidder_name);
                    $('.input-bid input[name="amount"]').val("");
                    $('.input-bid input[name="amount"]').attr('placeholder','Đặt giá tối thiểu từ '+respont.data.current_price+' hoặc hơn')
				}
            });
	});

	function isInteger(number) {
		return /^\+?(0|[1-9]\d*)$/.test(number);
	}
})

