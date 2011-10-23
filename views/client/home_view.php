<?php
?>

Yourname: <input id="sourceUser" class="sourceUser" type="text" value="<?=$_SESSION['username']?>"/>
Targetname: <input id="targetUser" class="userSource" type="text" value="<?=$_SESSION['username']?>" />
<br><br>
<div id="requestMonitor">
    Set your name and the name of the target user - Autoupdate is <b><span class="updateMonitorSpan" id="updateMonitorSpan">2</span> </b>seconds 
</div>
<div id="chatWrapper">

    <div id="monitor">
    </div>

    <input id="messageTxt" class="chatText" type="text" /><input class="sentButton" id="sentButton" type="button" value="Sent"/>
    <!--<input class="requestButton" id="requestButton" type="button" value="Read"/>-->

</div>
