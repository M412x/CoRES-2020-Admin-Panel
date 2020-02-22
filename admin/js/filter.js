$(document).ready(function(){
	var tableName=new Array();
	var primaryId=new Array();
	var ajaxDataString='',formAjaxString=''
	$('.filters').click(function(){
		
		$(this).each(function(index, element) {
           
		   if($(this).is(':checked')==true)
		   {
		   	tableName.push($(this).attr('bel'));
			primaryId.push($(this).attr('rel'));
			
			
			}
		    
        });
		
		
		//ajaxDataString=JSON.stringify(productObject);
		//console.log(ajaxDataString);
		
		//ajax call for product data
		var url='ajax-product.php';
		$.ajax({
			url: url,
			type: "POST",
			data: 'tableName='+tableName+'&primaryId='+primaryId,
			success: function(data){
			console.clear();
			console.log(data);
		
		}
			
			})
		
	})
	//click function end



})