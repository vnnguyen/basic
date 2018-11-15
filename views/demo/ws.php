<?php
        // $recipientUid = isset($_REQUEST["recipientUid"]) ? (int)$_REQUEST["recipientUid"] : USER_ID;
        $displayedNotificationNum = isset($_REQUEST["displayedNotificationNum"]) ? (int)$_REQUEST["displayedNotificationNum"]: 0;
 ?>

<div class="comments">
    <p>Recipient id: <?= USER_ID ?></p>
    <p>Notifications: <input id="notificationNum" size="4" name="some" value="<?= $displayedNotificationNum?>" /></p>
    <p>Last event arrived at: <input id="time" size="12" name="some" value="0" /></p>

<?php
$js = <<<TXT
    var UID = USER_ID;

    $.NotifierLongPolling = (function() {
        var _stateNode = $('#notificationNum'), _timeNode = $('#time');
        return {
            onMessage : function(data) {
                _stateNode.val(data.updatedNotificationNum);
                _timeNode.val(data.time);
                setTimeout($.NotifierLongPolling.send, 2000);
            },
            send : function() {
                $.ajax({
                        url: '/demo/r_server',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            recipientUid: UID,
                            displayedNotificationNum:

                             _stateNode.val()
                        },
                        success: function(data) {
                            console.log(data);
                            $.NotifierLongPolling.onMessage(data);
                        }
                });
            }
        }
    }());
    window.onload = function setDataSource() {
        setTimeout($.NotifierLongPolling.send, 40);
    };
    // Document is ready
    $(document).ready(function() {
       // setTimeout($.NotifierLongPolling.send, 10000);
    });
TXT;
$js = str_replace('USER_ID', USER_ID, $js);
$this->registerJs($js);
?>
</div>