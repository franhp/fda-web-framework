$(document).ready(function() {
    /*      
    $("#sentButton").click( function(){
        
        if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 3){   
                  
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
                            $("#chatMonitor").append("<p><b>"+$("#sourceUser").val()+"</b>: " +  text + "</p>" );
                        }
                        else if($.trim(data) !== "OK"){
                            $("#chatMonitor").append("<p style='color:red;'><b>"+$("#sourceUser").val()+"</b>: Error sending message: " +  text + "</p>" );
                        }
                        $("#chatMonitor").prop({
                            scrollTop: $("#chatMonitor").prop("scrollHeight")
                        });
                    }	
                })
            }  
        }       
    })
    */
                
    $("#messageTxt").bind(($.browser.opera ? "keypress" : "keydown"), function (e) {
        
        if(e.keyCode==13){
            if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 3){   
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
                                $("#chatMonitor").append("<p style='color: darkblue;'><b>&lt;"+$("#sourceUser").val()+"&gt;</b> " +  text + "</p>" );
                            }
                            else if($.trim(data) !== "OK"){
                                $("#chatMonitor").append("<p style='color:red;'><b>&lt;"+$("#sourceUser").val()+"&gt;</b> Error sending message: " +  text + "</p>" );
                            }
                            $("#chatMonitor").prop({
                                scrollTop: $("#chatMonitor").prop("scrollHeight")
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
    if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 3){    

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
                    $("#chatMonitor").append(data);
                    $("#chatMonitor").prop({
                        scrollTop: $("#chatMonitor").prop("scrollHeight")
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

