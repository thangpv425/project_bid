$(function(){
	$('.input-bid button').on('click', function(){
		var amount = $('.input-bid input').val();
		var current_price = parseInt($('.bid-current-info').attr('data'), 10) ;
		if (/^\+?(0|[1-9]\d*)$/.test(amount)){
		    if(amount < current_price){
				alert('Nhap gia lon hon '+current_price);
			}else{
				$.post("/bid-current/2",
		        {
		          	user_id: "10",
		          	user_name: "Hoang",
		          	real_bid_amount: amount
		        },
		        
		        function(respont){
		            console.log(respont);
		        });
			}
		}else
		    alert("data input must be a number");
		
	})
})
