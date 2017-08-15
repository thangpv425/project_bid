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
				$.post("/bid-current/9",
		        {
		          	user_name: name,
		          	user_id: id,
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
