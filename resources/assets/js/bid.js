$(function(){
	$('.input-bid button').on('click', function(){
		var amount = $('.input-bid input[name="amount"]').val();
		var name = $('.input-bid input[name="name"]').val();
		var id = $('.input-bid input[name="id"]').val();
		console.log(amount, name, id);
		var current_price = parseInt($('.bid-current-info').attr('data'), 10) ;
		if (/^\+?(0|[1-9]\d*)$/.test(amount)){
		    if(amount < current_price){
				alert('Nhap gia lon hon '+current_price);
			}else{
				$.post("/bid-current/14",
		        {
		          	user_name: name,
		          	user_id: id,
		          	real_bid_amount: amount
		        },
		        
		        function(respont){
		        	console.log(respont);
		            $('.bid-current-info').html(respont.current_price);
		            $('.hightest-bid-user').html(respont.current_highest_bidder_name);
		            $('.input-bid input[name="amount"]').val("");
		            $('.input-bid input[name="amount"]').attr('placeholder','Đặt giá tối thiểu từ '+respont.current_price+' hoặc hơn')
		        });
			}
		}else
		    alert("data input must be a number");
		
	})
})
