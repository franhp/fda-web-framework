    Yourname: <input id="sourceUser" class="sourceUser" type="text" readonly="readonly" value="<?=$_SESSION['username']?>"/>
    Targetname: <input id="targetUser" class="userSource" type="text" value="<?=$_SESSION['username']?>" />
    <br><br>
    <div id="chatWrapper">
        <div id="chatRooms">
            <ul style="list-style: none; margin: 2px 0px 0px 5px;padding:0;" >
                <li>general</li>
                <li>marketing</li>
            </ul>
        </div>
        <div id="chatDiv">
            <div id="chatMonitor">
                <p><b>&lt;hektor&gt;</b> lol</p>
                <p><b>&lt;franhp&gt;</b> como va gango?</p>
                <p><b>&lt;igango&gt;</b> pffff... muy mal, me han suspendido en secretariat</p>
            </div>
        </div>
        <div id="chatRoster">
            <ul style="list-style: none; margin: 2px 0px 0px 5px;padding:0;" >
                <li>@hektor</li>
                <li>_pepito</li>
                <li>+igango</li>
                <li>+franhp</li>
            </ul>
        </div>
        <div id="chatText"><input id="messageTxt" class="chatText" type="text" /><!--<input class="sentButton" id="sentButton" type="button" value="Sent"/>--></div>
    </div>
