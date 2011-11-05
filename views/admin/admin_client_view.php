<?php
$user = new Login();
$chat = new Chat();
$settings = new Settings();
$options = $settings->urlParameters(5);

if ($user->isLogged() && $user->getUserRole() > 1) {
    ?>

    <style>
        #sales{overflow: auto; margin: auto;  width: 350px; }
        #editUsersDiv{left: 0;  width: 100%;}
        #userRoomList{ margin: auto; width: 380px;}
        #userList{margin: auto; width: 250px;}
        h2,h3,p, table{margin-left: 15px;}
        thead{font-size: 105%; font-weight: bold;}
        .room{margin: 20px auto 10px auto; display: none; width: 380px; border:1px solid black; padding: 5px;}
    </style>

    <script type="text/javascript">
                                                        
        $(document).ready(function() {
                                    
        });
                            
        function submitForm(roomid){
                                
            var loc = '<?= $settings->siteurl ?>';  
            var formid = "#updateRoom"+roomid;
            //var newName = $(""+formid).children('#nameRoom').val();
                            
            $.ajax({
                type: 'POST',
                url: loc+'/controllers/chat_controller.php' ,
                data: $(""+formid).serialize(),
                success: function(data) {
                    if(data=='OK'){
                        $(window.location).attr('href', loc+"/en/admin/client/correct");
                        //$(""+formid).parent().children("#result").html("Updated :-)").hide().fadeIn(500);
                        //$("#tdRoom"+roomid).html(""+newName); 
                    }
                    else{
                        $(""+formid).parent().children("#result").html("Error while update :-(").hide().fadeIn(500);
                    }
                }
            })
        }    
                        
        function submitCreateForm(){
                                
            var loc = '<?= $settings->siteurl ?>';  
                            
            $.ajax({
                type: 'POST',
                url: loc+'/controllers/chat_controller.php' ,
                data: $("#createRoomForm").serialize(),
                success: function(data) {
                    if(data=='OK'){
                        $(window.location).attr('href', loc+"/en/admin/client/correct");
                    }
                    else{
                        $("#createRoomForm").parent().children("#result").html("Error inserting :-(").hide().fadeIn(500);
                    }
                }
            })
        }  
                        
                        
        function deleteRoom(roomid){
            if (confirm('Are you sure you want to delete this?')) {    
                var loc = '<?= $settings->siteurl ?>';
                $.ajax({
                    type: 'POST',
                    url: loc+'/controllers/chat_controller.php' ,
                    data:  { deleteRoom: ''+roomid },
                    success: function(data) {
                        if(data=='OK'){
                            $(window.location).attr('href', loc+"/en/admin/client/correct");
                        }
                        else{
                            alert("Error deleting :-(");
                        }
                    }
                })
            }
        }
                       
        function submitUserRoomForm(roomid, userid){
                                
            var loc = '<?= $settings->siteurl ?>';  
            var formid = "#userRoomForm"+userid;
                            
            $.ajax({
                type: 'POST',
                url: loc+'/controllers/chat_controller.php' ,
                data: $(""+formid).serialize(),
                success: function(data) {
                    if(data=='OK'){
                        $(window.location).attr('href', loc+"/en/admin/client/correct");
                    }
                    else if (data=='ALREDY'){
                        $(""+formid).parent().children("#result").html("This user is already in the room :-(").hide().fadeIn(500);
                    }
                    else{
                        $(""+formid).parent().children("#result").html("Error :-(").hide().fadeIn(500);
                    }
                }
            })
        }            
                       
        function submitRemoveUserRoomForm(roomid, userid){
            if (confirm('Are you sure you want remove this user from this room?')) {    
                var loc = '<?= $settings->siteurl ?>';  
                var formid = "#userRemoveRoomForm"+userid;

                $.ajax({
                    type: 'POST',
                    url: loc+'/controllers/chat_controller.php' ,
                    data: $(""+formid).serialize(),
                    success: function(data) {
                        if(data=='OK'){
                            $(window.location).attr('href', loc+"/en/admin/client/correct");
                        }
                        else{
                            $(""+formid).parent().children("#result").html("Error :-(").hide().fadeIn(500);
                        }
                    }
                })
            }
        } 
            
        function showCreateRoom(){
            $("#createRoomDiv").fadeIn(500);
        }
                        
        function closeCreateRoom(){
            $("#createRoomDiv").fadeOut(500);
        }
                        
        function showRoom(roomid){
            $( "#sala-"+roomid).fadeIn(500);
        }
                        
        function showUserRoom(userid){
            $( "#userRoomList-"+userid).fadeIn(500);
        }                          
                    
        function closeRoom(roomid){
            $("#sala-"+roomid).fadeOut(500);
        }
                        
        function closeUserRoom(userid){
            $("#userRoomList-"+userid).fadeOut(500);
        }    
                        
        jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
        }
                                                        
    </script>
    <?php
    if (!empty($options)) {
        if ($options == "correct")
            echo '<h3 style="color: green;">Request completed succesfully!</h3>';
    }
    ?>
    <h2>Room Edition</h2>
    <div id="createRoomDiv" class="room" >
        <button style="float: right;" onClick="closeCreateRoom()">Close</button>
        <form id="createRoomForm" onSubmit="submitCreateForm(); return false;" >
            Name: <br><input type="text" id="nameRoom" name="nameRoom"/><br><br>
            Description: <br><textarea cols="30" rows="3" name="descriptionRoom"></textarea><br><br>
            <input type="hidden" name="updateRoom"/>
            <input type="submit" value="Create">  
        </form>
        <div id="result"></div>
    </div>
    <?php
    foreach ($chat->listRooms() as $room)
        echo '<div id="sala-' . $room->roomid . '" class="room" >
                <button style="float: right;" onClick="closeRoom(' . $room->roomid . ')">Close</button>
                <form id="updateRoom' . $room->roomid . '" onSubmit="submitForm(' . $room->roomid . '); return false;" >
                    Name: <br><input type="text" id="nameRoom" name="nameRoom" value="' . $room->name . '"/><br><br>
                    Description: <br><textarea cols="30" rows="3" name="descriptionRoom">' . $room->description . '</textarea><br><br>
                    <input type="hidden" name="roomid" value="' . $room->roomid . '"/>
                    <input type="hidden" name="updateRoom"/>
                    <input type="submit" value="Update">  
                </form>
                <div id="result"></div>
             </div>';
    ?>

    <div id="sales">
        <table style="width:90%;">
            <thead>
            <td style="width: 40%;">Name</td>
            <td style="width: 20%;">Edit</td>
            <td style="width: 20%;">Delete</td>
            </thead>
            <tbody>
                <?php
                foreach ($chat->listRooms() as $room)
                    echo '
            <tr>
              <td id="tdRoom' . $room->roomid . '">' . $room->name . '</td>
              <td><a href="#" onClick="showRoom(' . $room->roomid . ')"><img src="' . $settings->siteurl . '/img/admin/edit.gif" border=0 alt="editar sala" /></a></td>
              <td><a href="#" onClick="deleteRoom(' . $room->roomid . ');"><img src="' . $settings->siteurl . '/img/admin/delete.gif" border=0 alt="borrar sala" /></td>
            </tr>';
                ?>
            </tbody>
        </table>
        <p>Add room: <a href="#" onClick="showCreateRoom()"><img style="vertical-align: top;" src="<?= $settings->siteurl ?>/img/admin/add.png" border=0 alt="add sala" /></a></p>
    </div>
    <br>

    <h2>Users Edition</h2>
    <div id="editUsersDiv">
        <div id="userRoomList">
            <?php
            foreach ($chat->listUsers() as $user) {
                echo '<div id="userRoomList-' . $user->id . '" class="room">
                         <button style="float: right;" onClick="closeUserRoom(' . $user->id . ')">Close</button>
                            <b>' . $user->username . '</b><br><br>
                                <div id="rooms">Is on the following rooms: <ul id="#ulRooms">';
                foreach ($chat->listUserRooms($user->id) as $room) {
                    echo '<li>' . $room->roomname . '</li>';
                }
                echo '</ul></div>
                        <form id="userRoomForm' . $user->id . '" onSubmit="submitUserRoomForm(' . $room->roomid . ', ' . $user->id . '); return false;">
                            <img style="vertical-align: top;" src="' . $settings->siteurl . '/img/admin/add.png" border=0 alt="add sala" /> Add user to the room: <select name="roomid" size="1">
                               <option selected value="">Select...</option>';
                foreach ($chat->listUserNoRooms($user->id) as $room) {
                    echo '<option value="' . $room->roomid . '">' . $room->roomname . '</option>';
                }

                echo '<input type="hidden" name="userid" value="' . $user->id . '"><input type="hidden" name="insertUserRoom"> <input type="submit" value="Add"></select>
                         </form>
                         <form id="userRemoveRoomForm' . $user->id . '" onSubmit="submitRemoveUserRoomForm(' . $room->roomid . ', ' . $user->id . '); return false;">
                            <img style="vertical-align: top;" src="' . $settings->siteurl . '/img/admin/error.png" border=0 alt="add sala" /> Remove user from the rooms: <select name="roomid" size="1">
                               <option selected value="">Select...</option>';
                foreach ($chat->listUserRooms($user->id) as $room) {
                    echo '<option value="' . $room->roomid . '">' . $room->roomname . '</option>';
                }

                echo '<input type="hidden" name="userid" value="' . $user->id . '"><input type="hidden" name="removeUserRoom"> <input type="submit" value="Remove"></select>
                         </form>
                         <div id="result"></div>
                    </div>';
            }
            ?>
        </div>   
        <div id="userList">
            <table style="width:80%;">
                <thead>
                <td style="width: 80%;">Name</td>
                <td style="width: 20%;">Edit</td>
                </thead>
                <tbody>
                    <?php
                    foreach ($chat->listUsers() as $user) {
                        echo '<tr>
                                <td>' . $user->username . '</td>
                                <td><a style="cursor: pointer;" onClick="showUserRoom(' . $user->id . ')"><img src="' . $settings->siteurl . '/img/admin/edit.gif" border=0 alt="editar sala" /></a></td>
                            <tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>   

    </div>

    <?php
} else {
    echo 'No tienes permiso para acceder a esta seccion :-(';
}
?>