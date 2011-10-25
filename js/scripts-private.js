$(document).ready(function() {
                
    $("#sentButton").click( function(){
        
        if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 5){   
                  
            var text = $("#messageTxt").val();
                  
            if (text != ""){
                        
                $("#messageTxt").val("");  
                        
                $.ajax({
                    type: 'POST',
                    url: '/web.xinxat.com/controllers/chat_controller.php' ,
                    //url: '/web.xinxat.com/scripts/scripts.php' ,
                    data: {
                        msg_sent: text,
                        to: $("#targetUser").val()
                    },
                    success: function(data) {
                        if($.trim(data) === "OK"){
                            $("#monitor").append("<p><b>"+$("#sourceUser").val()+"</b>: " +  text + "</p>" );
                        }
                        else if($.trim(data) !== "OK"){
                            $("#monitor").append("<p style='color:red;'><b>"+$("#sourceUser").val()+"</b>: Error sending message: " +  text + "</p>" );
                        }
                        $("#monitor").prop({
                            scrollTop: $("#monitor").prop("scrollHeight")
                        });
                    }	
                })
            }  
        }       
    })
                
    $("#messageTxt").bind('keypress', function(e) {
        
        if(e.keyCode==13){
            if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 5){   
                // Enter pressed... do anything here...
                var text = $("#messageTxt").val();
                
                  
                if (text != ""){
                        
                    $("#messageTxt").val("");  
                        
                    $.ajax({
                        type: 'POST',
                        url: '/web.xinxat.com/controllers/chat_controller.php' ,
                        //url: '/xinxat.com/scripts/scripts.php' ,
                        data: {
                            msg_sent: text,
                            to: $("#targetUser").val()
                        },
                        success: function(data) {
                            if($.trim(data) === "OK"){
                                $("#monitor").append("<p><b>"+$("#sourceUser").val()+"</b>: " +  text + "</p>" );
                            }
                            else if($.trim(data) !== "OK"){
                                $("#monitor").append("<p style='color:red;'><b>"+$("#sourceUser").val()+"</b>: Error sending message: " +  text + "</p>" );
                            }
                            $("#monitor").prop({
                                scrollTop: $("#monitor").prop("scrollHeight")
                            });
                        }	
                    })
                }
            }
        }
    }); 
                
                
    //setInterval( "printN()", 1000 );
    setInterval( "updateMonitor()", 2000 );
                   
});     
                
function updateMonitor(){
    if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 5){    

        $.ajax({
            type: 'POST',
            url: '/web.xinxat.com/controllers/chat_controller.php' ,
            //url: '/xinxat.com/scripts/scripts.php' ,
            data: {
                msg_req: "",
                to: $("#sourceUser").val()
            },
            success: function(data) {
                if($.trim(data) != "nullchat"){
                    $("#monitor").append("<p><b>"+$("#targetUser").val()+"</b>: " +  data + "</p>" );
                    $("#monitor").prop({
                        scrollTop: $("#monitor").prop("scrollHeight")
                    });
                }
            }	
        })
    }
}
            
var i=1;
            
function printN(){
    if (i<0) i=2;
    $("#updateMonitorSpan").text(i);
    i--;
}

