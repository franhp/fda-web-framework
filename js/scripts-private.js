$(document).ready(function() {
    
    $("button").click(function(){ 
        var btnId = $(this).attr("id");
        var user = $("#username").val();
      
        switch(btnId)
        {
            case "joinBtn":
                $('#messageTxt').val("/join ");
                break;
            case "leaveBtn":
                $('#messageTxt').val("/leave ");
                break;
            case "listBtn":
                $('#messageTxt').val("/list ");
                break;
            case "kickBtn":
                $('#messageTxt').val("/kick " + user);
                break;
            case "banBtn":
                $('#messageTxt').val("/ban " + user);
                break;
            case "unbanBtn":
                $('#messageTxt').val("/unban " + user);
                break;
            case "cleanBtn":
                $('#messageTxt').val("");
                break;
        }  
      
    }) 
                
    $("#messageTxt").bind(($.browser.opera ? "keypress" : "keydown"), function (e) {
        
        if(e.keyCode==13){
            if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 3){   
                var text = $("#messageTxt").val();
                if (text != ""){
                    if (text[0] == '/'){
                        $("#messageTxt").val("");  
                        $.ajax({
                            type: 'POST',
                            url: '/web.xinxat.com/controllers/chat_controller.php' ,
                            data: {
                                command: 'true',
                                line: text
                            },
                            success: function(data) {
                                $("#chatMonitor").append("<p style='color: red;'><b>SYSTEM</b> " +  data + "</p>" );
                            }
                        })
                    } 
                    else {
                        $("#messageTxt").val("");
                        $.ajax({
                            type: 'POST',
                            url: '/web.xinxat.com/controllers/chat_controller.php' ,
                            data: {
                                msg_sent: text,
                                to: $("#targetUser").val()
                            },
                            success: function(data) {
                                if($.trim(data) !== "WRONG"){
                                    $("#chatMonitor").append("<p style='color: darkblue;'><b>&lt;"+$("#sourceUser").val()+"&gt;</b> " +  text + "</p>" );
                                }
                                else if($.trim(data) === "WRONG"){
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
        }
    }); 
    
    setInterval( "updateMonitor()", 5000 );
                   
});     

var access = false;

function updateMonitor(){
    if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 5 && $("#targetUser").val() != "" && $("#targetUser").val().length > 3){    
        $.ajax({
            type: 'POST',
            url: '/web.xinxat.com/controllers/chat_controller.php' ,
            data: {
                msg_req: "",
                to: $("#sourceUser").val()
            },
            success: function(data) {
                if (access == false ){
                    if($.trim(data) !== "WRONG"){
                        $("#loading").fadeOut(500);
                        $('#messageTxt').prop('disabled', false);
                        $("#chatMonitor").append("<p style='color: green; font-weight: bold;'>Welcome to Xinxat!</p>");
                        if($.trim(data) !== "NULL"){
                            $("#chatMonitor").append(data);
                            $("#chatMonitor").prop({
                                scrollTop: $("#chatMonitor").prop("scrollHeight")
                            });
                        }
                        access = true;
                    }
                } else {
                    if($.trim(data) !== "NULL"){
                        $("#chatMonitor").append(data);
                        $("#chatMonitor").prop({
                            scrollTop: $("#chatMonitor").prop("scrollHeight")
                        });
                    }
                
                }
            }	
        })
    }
}

