$(document).ready(function() {
		
    $("input").focus(function () {
			
        $(this).next("span").attr('class', '');
			
        $(this).next("span").html($(this).attr("title"));
				
        $(this).next("span").fadeIn(500);
			
    });
    
    $("input").blur(function () {
            $(this).next("span").css('display','none');
    });
		
    $("select").focus(function () {
			
        $('span[name="fecha"]').css('display','none');
			
        $('span[name="fecha"]').attr('class', '');
			
        $('span[name="fecha"]').html($(this).attr("title"));

        $('span[name="fecha"]').fadeIn(500);
			
    });

		
	
    $('#register-form').submit(function() {
        
        doUpdate();
			
        return false;
    });
		
});