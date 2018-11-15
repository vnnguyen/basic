<?php
$this->registerCss('
    H1 {
        text-align: center;
        font-size: 150%;
        margin-bottom: 60px;
    }

    H2 {
        text-align: center;
        font-size: 125%;
        margin-bottom: 15px;
    }

    DIV#tickets {
        margin: 10px auto 80px auto;
    }

    DIV.ticket {
        margin: 5px auto;
        width: 160px;
        font-size: 115%;
    }

    DIV.name {
        display: inline-block;
        width: 80px;
        padding: 3px;
    }

    DIV.price {
        display: inline-block;
        width: 60px;
        padding: 3px;
        text-align: right;
        transition: all 0.2s ease-out;
    }

    DIV#log {
        margin: 10px auto;
        width: 600px;
        height: 200px;
        background: gainsboro;
        padding: 5px;
        overflow-y: scroll;
    }

    DIV#notSupported {
        display: none;
        margin: 10px auto;
        text-align: center;
        color: red;
        font-size: 150%;
    }

    P.hint {
        width: 600px;
        margin: 10px auto;
        text-align: justify;
        text-indent: 20px;
        line-height: 135%;
    }

    DIV#download {
        margin: 50px auto;
        text-align: center;
    }

    DIV#download A {
        padding: 10px 25px;
        background: #F1592A;
        color: white;
        text-decoration: none;
        font-size: 20px;
        border-radius: 5px 5px;
    }

    DIV#download A:hover {
        text-decoration: underline;
        background: #FF592A;
    }
');
?>
<div class="flex-row">
    <div id="notSupported">
    Your browser does not support Server Sent Events.
    Please use <a href="https://www.mozilla.org/firefox/new/">Firefox</a>
    or <a href="https://www.google.com/chrome/browser">Google Chrome</a>.
</div>

<div id="tickets">
    <div class="ticket"><div class="name">IBM</div><div class="price" id="t_IBM">161.57</div></div>
    <div class="ticket"><div class="name">AAPL</div><div class="price" id="t_AAPL">114.45</div></div>
    <div class="ticket"><div class="name">GOOG</div><div class="price" id="t_GOOG">532.94</div></div>
    <div class="ticket"><div class="name">MSFT</div><div class="price" id="t_MSFT">47.12</div></div>
</div>


<h2>Simple Log Console</h2>
<p class="hint">
    This is simple log console. It is useful for testing purposes and to understand better how SSE works.
    Event id and data are logged for each event.
</p>
<div id="log"> </div>
</div>
<!-- <script>
    var isNews = 0;
    if (typeof (EventSource) !== 'undefined') {
        var source = new EventSource("/demo/sse_server");
        source.onopen = function (event) {
            console.log('onopen', event);
        };
        source.onerror = function (event) {
            console.log('onerror', event);
        };
        source.addEventListener('new-msgs', function (event) {
            var jon_obj = $.parseJSON(event.data);
            if(isNews !== jon_obj['newMsgs']){
                isNews = jon_obj['newMsgs'];
                alert("new notification: " + isNews);
            }
            document.getElementById("log").innerHTML += event.data + "<br />";
        });
    } else {
        document.getElementById("log").innerHTML = 'Sorry, your browser does not support server-sent events...';
    }
</script> -->
<?php

$js = <<<TXT

(function(){
    var isNews = 0;
    if (!!window.EventSource) {
        var E_SOURCE = new EventSource("/demo/sse_server");
    } else {
        alert("Your browser does not support Server-sent events! Please upgrade it!");
    }
    E_SOURCE.addEventListener("message", function(e) {
        console.log("Connection was opened.");
    }, false);
    E_SOURCE.addEventListener("new-msgs", function(event) {
        var jon_obj = $.parseJSON(event.data);
            if(isNews !== jon_obj['newMsgs']){
                isNews = jon_obj['newMsgs'];
                alert("new notification: " + isNews);
            }
            document.getElementById("log").innerHTML += event.data + "<br />";
    }, false);

    E_SOURCE.addEventListener("open", function(e) {
        console.log("Connection was opened.");
    }, false);

    E_SOURCE.addEventListener("error", function(e) {
        console.log("Error - connection was lost.");
    }, false);
    E_SOURCE.close();
}());


TXT;
$this->registerJs($js);
?>