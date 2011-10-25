function IsValidEmail(email)

{
		
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		
    return filter.test(email);
		
}

	
function Check_first() {
    timer = setTimeout("Check()", 2000);
    return false;
}
	
$(document).ready(function() {
		
    $("input").focus(function () {
			
        $(this).next("span").attr('class', '');
        
        $(this).next("span").css('display','none');
			
        $(this).next("span").html($(this).attr("title"));
				
        $(this).next("span").fadeIn(500);
			
    });
		
    $("input").blur(function () {
			
			
        if($(this).val().length >= 3)
        {
            $(this).next("span").css('display','none');
				
				
            /* SI ES EL PASSWORD1 O PASSWORD2 */
				
            if($(this).attr('id') == "password")
            {
                if($(this).val().length >= 6){
						
                    $(this).next("span").attr('class', 'correcto');
						
                    $(this).next("span").html("OK");
					
                    $(this).next("span").fadeIn(500);
						
                }
                else{
						
                    $(this).next("span").attr('class', 'incorrecto');
						
                    $(this).next("span").html("Demasiado corto");
						
                    $(this).next("span").fadeIn(500);
						
                }
					
            }
				
            /* SI ES EL USUARIO */
			
            else if($(this).attr('id') == "nick")
            {
					
                $(this).next("span").css('display','inline');
					
                $(this).next("span").attr('class', 'cargando');
					
                $(this).next("span").html("Comprobando usuario...");
					
                $(this).next("span").fadeIn(500);
					
                Check_first();
					
            }
				
				
				
            /* SI LA PASSWORD 2 DIFERENTE  */
            else if($(this).attr('id') == "password2")
            {
                if ($(this).val() == $("#password").val() && $(this).val().length >= 6)
                {
                    $(this).next("span").attr('class', 'correcto');
					
                    $(this).next("span").html("OK");
					
                    $(this).next("span").fadeIn(500);
                }
                else{
						
                    $(this).next("span").attr('class', 'incorrecto');
						
                    $(this).next("span").html("Incorrecto");
						
                    $(this).next("span").fadeIn(500);
                }
					
            }
			
			
            /* RAGEX DEL EMAIL */
				
            else if($(this).attr('id') == "email")
            {
                if(IsValidEmail($(this).val()))
                {
                    $(this).next("span").attr('class', 'correcto');
					
                    $(this).next("span").html("OK");
					
                    $(this).next("span").fadeIn(500);
                }
                else{
						
                    $(this).next("span").attr('class', 'incorrecto');
						
                    $(this).next("span").html("Incorrecto");
						
                    $(this).next("span").fadeIn(500);
                }
            }
				
				
            else{
					
                $(this).next("span").attr('class', 'correcto');
					
                $(this).next("span").html("OK");
					
                $(this).next("span").fadeIn(500);
            }
			
				
        }
        else if($(this).val().length > 0 && $(this).val().length < 6){
				
            $(this).next("span").css('display','none');
				
            $(this).next("span").attr('class', 'incorrecto');
				
            $(this).next("span").html("Demasiado corto");
				
            $(this).next("span").fadeIn(500);
         
        }
			
			
        else {
				
				
            $(this).next("span").attr('class', 'correcto');
				
            $(this).next("span").html("");
				
        }
			
    });
	
    $('#register-form').submit(function() {
		
        validated = true;
			
        $('input').each(function(index) {
				
            if ($(this).next("span").text() == "Incorrecto" || $(this).next("span").text() == "Demasiado corto" || $(this).next("span").text() == "Ya existe" || $(this).next("span").text() == "Comprueba este campo" ||   $(this).next("span").text().length == 0 && $(this).attr('id') != "submit")
            {
					
                $(this).next("span").fadeIn(500);
					
                $(this).next("span").attr('class', 'incorrecto');
				
                $(this).next("span").html("Comprueba este campo");
					
                validated = false;
					
            }
				
        });			

        if (validated == true)
        {
            doRegistration();
        } 
			
        return false;
    });
		
});