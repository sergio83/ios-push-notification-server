function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$("#send").click(function() {
	
	var error = false;

	if($('#certificate').val().length==0){
		$('#form-group-certificate').attr('class', 'form-group has-error');
		error = true;
	}else $('#form-group-certificate').attr('class', 'form-group has-success');

	if($('#tokens').val().length==0){
		$('#form-group-tokens').attr('class', 'form-group has-error');
		error = true;
	}else $('#form-group-tokens').attr('class', 'form-group has-success');

	if($('#json').val().length==0){
		$('#form-group-json').attr('class', 'form-group has-error');
		error = true;
	}else if(!isJsonString($('#json').val())){
		alert("JSON wrong format");
		$('#form-group-json').attr('class', 'form-group has-error');
		return;
	}else $('#form-group-json').attr('class', 'form-group has-success');

	if(error){
		alert("All fields are required");
		return;
	}
		
	$.blockUI({ message: null });

	var formData = new FormData();
	formData.append('action', 'push');
	formData.append('payload', $("#json").val());	
	formData.append('tokens', $("#tokens").val());	
	formData.append('server', $('input[name=optionsServer]:checked').val());
	formData.append('certificate', $('input[type=file]')[0].files[0]); 

	$.ajax({

	    url : "services.php",

        data :  formData,
    
        type : 'POST',
    
        dataType : 'json',
        contentType: false,
    	processData: false,

        success : function(json) {	 
	       alert("The push notification has been sent successfully.");

        },
        error : function(jqXHR, status, error) {
	        alert("Error: "+error);
        },
        complete : function(jqXHR, status) {
         $.unblockUI();
         
        }
    }); 

});