$(document).ready(function() {
    
    updateMonitor();
    
    $("button").click(function(){ 
        var btnId = $(this).attr("id");
        var user = $("#username").val();
        var sala = $("#target").val();
      
        switch(btnId)
        {
            case "joinBtn":
                $('#messageTxt').val("/join " + sala);
                break;
            case "leaveBtn":
                $('#messageTxt').val("/leave " + sala);
                break;
            case "listBtn":
                $('#messageTxt').val("/list " + sala);
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
            case "privateBtn":
                if (!$.exists("#dialog-"+user)) { 
                    $('#privates').append('<div id="dialog-'+user+'" class="dialog" title="Dialog Title!">'
                        +'<div id="privateMonitor-'+user+'" style="height: 150px; width: 100%; overflow-x: hidden; overflow-y: auto; text-align:left; font-family: courier;">'
                        +'</div><input id="privateMessageTxt-'+user+'" style="bottom: 0; width: 100%;" type="text"/>'
                        +'</div><script type="text/javascript">$("#dialog-'+user+'").dialog('
                        +'{title: \''+user+'\',close: function(event, ui){$(this).dialog(\'destroy\').remove();}}); $(\'input\').keydown(function(event) {if (event.keyCode == \'13\') { if ($(this).attr("id") != "messageTxt") sentPrivate("'+user+'");}});</script>');
                }
                break;
            case "cleanBtn":
                $('#messageTxt').val("");
                break;
            case "resetBtn":
                $('#chatMonitor').html("");
                break;
        }  
    }) 
                
    $("#messageTxt").bind(($.browser.opera ? "keypress" : "keydown"), function (e) {
        if(e.keyCode==13){
            if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 3 && $("#target").val() != "" && $("#target").val().length > 3){   
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
                                $("#chatMonitor").prop({
                                    scrollTop: $("#chatMonitor").prop("scrollHeight")
                                });
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
                                to: $("#target").val()
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
    var sala = $("#target").val();
    setInterval( "updateRoster('"+sala+"')", 30000 );
});     

var access = false;

function updateMonitor(){
    if($("#sourceUser").val() != "" && $("#sourceUser").val().length > 3 && $("#target").val() != "" && $("#target").val().length > 3){    
        $.ajax({
            type: 'POST',
            url: '/web.xinxat.com/controllers/chat_controller.php' ,   
            dataType: "json",
            data: {
                msg_req: "",
                to: $("#sourceUser").val()
            },
            success: function(data) {
                if (access == false ){
                    if(data[0].state != "WRONG"){
                        $("#loading").fadeOut(500);
                        $('#messageTxt').prop('disabled', false);
                        $("#chatMonitor").append("<p style='color: green; font-weight: bold;'>Welcome to Xinxat!</p>");
                        if(data[0].state != "NULL"){
                            $.each(data, function(index) {
                                var from = data[index].from;
                                var type = data[index].type;
                                var msg = data[index].msg;
                                if(type == "chat") {
                                    if (!$.exists("#dialog-"+from)) { 
                                        $('#privates').append('<div id="dialog-'+from+'" class="dialog" title="Dialog Title!">'
                                            +'<div id="privateMonitor-'+from+'" style="height: 150px; width: 100%; overflow-x: hidden; overflow-y: auto; text-align:left; font-family: courier;">'
                                            +'</div><input id="privateMessageTxt-'+from+'" style="bottom: 0; width: 100%;" type="text"/>'
                                            +'</div><script type="text/javascript">$("#dialog-'+from+'").dialog('
                                            +'{title: \''+from+'\',close: function(event, ui){$(this).dialog(\'destroy\').remove();}}); $(\'input\').keydown(function(event) {if (event.keyCode == \'13\') { if ($(this).attr("id") != "messageTxt") sentPrivate("'+from+'");}});</script>');
                                        $('#privateMonitor-'+from).append('<p><b>&lt;' + from + '&gt;</b> ' + msg + '</p>');
                                    } else {
                                        $('#privateMonitor-'+from).append('<p><b>&lt;' + from + '&gt;</b> ' + msg + '</p>');
                                    }

                                } else if (type == "groupchat") {
                                    $("#chatMonitor").append("<p><b>&lt;" + from + "&gt;</b> " + msg + "</p>");
                                    $("#chatMonitor").prop({
                                        scrollTop: $("#chatMonitor").prop("scrollHeight")
                                    });
                                }
                            });
                        }
                        access = true;
                    }
                } else {
                    if($.trim(data) !== "NULL"){
                        $.each(data, function(index) {
                            var from = data[index].from;
                            var type = data[index].type;
                            var msg = data[index].msg;
                            if(type == "chat") {
                                if (!$.exists("#dialog-"+from)) { 
                                    $('#privates').append('<div id="dialog-'+from+'" class="dialog" title="Dialog Title!">'
                                        +'<div id="privateMonitor-'+from+'" style="height: 150px; width: 100%; overflow-x: hidden; overflow-y: auto; text-align:left; font-family: courier;">'
                                        +'</div><input id="privateMessageTxt-'+from+'" style="bottom: 0; width: 100%;" type="text"/>'
                                        +'</div><script type="text/javascript">$("#dialog-'+from+'").dialog('
                                        +'{title: \''+from+'\',close: function(event, ui){$(this).dialog(\'destroy\').remove();}}); $(\'input\').keydown(function(event) {if (event.keyCode == \'13\') { if ($(this).attr("id") != "messageTxt") sentPrivate("'+from+'");}});</script>');
                                    $('#privateMonitor-'+from).append('<p><b>&lt;' + from + '&gt;</b> ' + msg + '</p>');
                                } else {
                                    $('#privateMonitor-'+from).append('<p><b>&lt;' + from + '&gt;</b> ' + msg + '</p>');
                                }
                                
                            } else if (type == "groupchat") {
                                $("#chatMonitor").append("<p><b>&lt;" + from + "&gt;</b> " + msg + "</p>");
                                $("#chatMonitor").prop({
                                    scrollTop: $("#chatMonitor").prop("scrollHeight")
                                });
                            }
                        });
                    }
                }
            }	
        })
    }
}

function sentPrivate(user){
    var text = $("#privateMessageTxt-"+user).val();
    if (text != ""){
        $("#privateMessageTxt-"+user).val("");
        $.ajax({
            type: 'POST',
            url: '/web.xinxat.com/controllers/chat_controller.php' ,
            data: {
                msg_sent: text,
                to: user
            },
            success: function(data) {
                if($.trim(data) !== "WRONG"){
                    $("#privateMonitor-"+user).append("<p style='color: darkblue;'><b>&lt;"+$("#sourceUser").val()+"&gt;</b> " +  text + "</p>" );
                }
                else if($.trim(data) === "WRONG"){
                    $("#privateMonitor-"+user).append("<p style='color:red;'><b>&lt;"+$("#sourceUser").val()+"&gt;</b> Error sending message: " +  text + "</p>" );
                }
                $("#privateMonitor-"+user).prop({
                    scrollTop: $("#privateMonitor-"+user).prop("scrollHeight")
                });
            }	
        })
    }
    
}

function updateRoster(sala){
    if (sala != ""){
        $.ajax({
            type: 'POST',
            url: '/web.xinxat.com/controllers/chat_controller.php' ,
            data: {
                roster: 'true',
                room: ''+sala
            },
            success: function(data) {
                if($.trim(data) !== ""){
                    $("#chatRoster").html(''+data);
                }
            }	
        })
    }
}

jQuery.exists = function(selector) {
    return ($(selector).length > 0);
}
