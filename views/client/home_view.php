<?php
$settings = new Settings();
$chat = new Chat();      
echo '<h3>Welcome to client main page</h3> <p>Select a room:</p>';
$rooms = $chat->listUserRooms($_SESSION['userid']);
echo "<ul>";
foreach ($rooms as $room){
    $formattedName =  substr($room->roomname, 1, strlen($room->roomname));
    echo '<li><a href="'.$settings->siteurl.'/en/client/'.$formattedName.'"> '.$room->roomname.'</a></li>';
}
echo "</ul>";

