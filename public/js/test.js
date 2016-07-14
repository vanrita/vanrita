$(document).ready(function () {    
    $("#sign").click(function() {
   		var username = $("#username").val();
   		var password = $("#password").val();
   		var data = "";
   		//$.get('/test/test',{'user_accountname':1,'user_password':1,},function(data){alert(data.name)});
    	//alert('test');
    	//ajaxPost('/test/test',username,password);
    	$.ajax({
			type: 'POST',
			url: '/test/test',
			data: { 'name' : username, 'password': password},
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			},
			success: function(data){
				alert(data.name);
			},
			error: function(xhr, type){
				alert('Ajax error!')
			}
		});
    }); 
});
