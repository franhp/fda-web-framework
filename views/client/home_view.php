<?php
$settings = new Settings();
$chat = new Chat();
?>

<div id="loading" style="position: absolute; margin-left: 220px; margin-top: -15px; ">
<img src="<?= $settings->siteurl ?>/img/loading-big.gif" border="0"></img></div>
Yourname: <input id="sourceUser" class="sourceUser" type="text" readonly="readonly" value="<?= $_SESSION['username'] ?>"/>
Targetname: <input id="targetUser" class="userSource" type="text" value="<?= $_SESSION['username'] ?>" />
<br><br>
<div id="chatWrapper">
    <div id="chatRooms">
        <ul style="list-style: none; margin: 2px 0px 0px 5px;padding:0;" >
            <li><button id="joinBtn" style="width: 96%;">Join </button></li>
            <li><button id="leaveBtn" style="width: 96%;">Leave </button></li>
            <li><button id="listBtn" style="width: 96%;">List </button></li>  
            <li><button id="kickBtn" style="width: 96%;">Kick </button></li>      
            <li><button id="banBtn" style="width: 96%;">Ban </button></li>
            <li><button id="unbanBtn" style="width: 96%;">Unban</button></li>   
            <br>
            <li><button id="cleanBtn" style="width: 96%;">Clean</button></li>   
        </ul>
    </div>
    <div id="chatDiv">
        <div id="chatMonitor">
        </div>
    </div>
    <div id="chatRoster">
        <?php
        $chat->roster("") . "aa";
        ?>
    </div>
    <div id="chatText"><input id="messageTxt" class="chatText" type="text" disabled="true" /><!--<input class="sentButton" id="sentButton" type="button" value="Sent"/>--></div>
</div>
