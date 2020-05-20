<?

$js = <<<'TXT'

// https://github.com/euank/node-parse-numeric-range

function parsePart(str) {
  // just a number
  if(/^-?\d+$/.test(str)) {
    return parseInt(str, 10);
  }
  var m;
  // 1-5 or 1..5 (equivilant) or 1...5 (doesn't include 5)
  if((m = str.match(/^(-?\d+)(-|\.\.\.?|\u2025|\u2026|\u22EF)(-?\d+)$/))) {
    var lhs = m[1];
    var sep = m[2];
    var rhs = m[3];
    if(lhs && rhs) {
      lhs = parseInt(lhs);
      rhs = parseInt(rhs);
      var res = [];
      var incr = lhs < rhs ? 1 : -1;

      // Make it inclusive by moving the right 'stop-point' away by one.
      if(sep == '-' || sep == '..' || sep == '\u2025') {
        rhs += incr;
      }

      for(var i=lhs; i != rhs; i += incr) res.push(i);
      return res;
    }
  }
  return [];
}

function parseRange(str) {
  var parts = str.split(',');

  var toFlatten = parts.map(function(el) {
    return parsePart(el);
  });

  // reduce can't handle single element arrays
  if(toFlatten.length === 0) return [];
  if(toFlatten.length === 1) {
    if(Array.isArray(toFlatten[0]))
      return toFlatten[0];
    return toFlatten;
  }

  return toFlatten.reduce(function(lhs, rhs) {
    if(!Array.isArray(lhs)) lhs = [lhs];
    if(!Array.isArray(rhs)) rhs = [rhs];
    return lhs.concat(rhs);
  });
};

// Add NLBR
function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

$('.popovers').popover();
$('a[rel="external"]').on('click', function(){$(this).attr('target', '_blank')});
$('a[href="#back"]').click(function(){history.go(-1); return false;});


$('#goTop').goTop({
    appear: 200,
    scrolltime: 800,
    src: "fa fa-arrow-circle-o-up",
    width: 32,
    place: "right",
    fadein: 500,
    fadeout: 500,
    opacity: 0.5,
    marginX: 2,
    marginY: 2
});

function formatSearch (repo) {
    if (repo.loading) return repo.text;
    var markup = '<a class="select2-result display-block clearfix" href="' + repo.url + '">' + repo.avatar_url + repo.found + '</a>'
    return markup;
}

var search_for = '' // anything

$("#livesearch").select2({
    // language: "vi",
    ajax: {
        url: function(){
            return '/home/search?for=' + search_for
        },
        width: 'style',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                u: 1, // user 1
                q: params.term,
                page: params.page || 1,
                // for: $('#search-for').data('search')
            };
        },
        processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
                results: data.items,
                pagination: {
                    more: (params.page * 30) < data.total_count
                }
            };
        },
        // cache: true
    },
    closeOnSelect: false,
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 2,
    templateResult: formatSearch,
    width:'100%',
    placeholder:'Type to search',
});

$('#livesearch').on('select2:selecting', function (evt) {
    return false;
});

$('#livesearch').on('select2:select', function (evt) {
    return false;
});

$('a[data-search]').on('click', function(e){
    e.preventDefault()
    var val = $(this).data('search')
    var text = $(this).text()
    $('#search-for').attr('data-search', val)
    $('#search-for').text(text)
    search_for = val
})
//-------------demo polling-----///
    console.log("Connection");
    // var UID = USER_ID;
    // var ntyNum = 0, repeatNtySound = 0;

    // $.NotifierLongPolling = (function() {
    //     return {
    //         onMessage : function(data) {
    //             if(ntyNum != data.updatedNotificationNum) {
    //                 ntyNum = data.updatedNotificationNum;
    //                 repeatNtySound = 0;
    //             }
    //             if(ntyNum > 0) {
    //                 $('#headNotificationNum').data('load_ids', data.ids.join(',')).text(ntyNum).fadeIn('slow', function(){
    //                         $(this).show();
    //                         if(repeatNtySound == 0) {
    //                             playSound('/sound/light');
    //                             repeatNtySound ++;
    //                         }
    //                     });

    //             }
    //             if(!ntyNum> 0) {
    //                 $('#headNotificationNum').text(0).hide();
    //             }
    //             setTimeout($.NotifierLongPolling.send, 2000);
    //         },
    //         send : function() {
    //             $.ajax({
    //                     url: '/demo/r_server',
    //                     type: 'POST',
    //                     dataType: 'json',
    //                     data: {
    //                         recipientUid: UID,
    //                         displayedNotificationNum:

    //                          ntyNum                            },
    //                     success: function(data) {
    //                         $.NotifierLongPolling.onMessage(data);
    //                     }
    //             });
    //         }
    //     }
    // }());
    // $(document).ready(function() {
    //    setTimeout($.NotifierLongPolling.send, 40);
    // });
    // function playSound(filename){
    //     var mp3Source = '<source src="' + filename + '.mp3" type="audio/mpeg">';
    //     var oggSource = '<source src="' + filename + '.ogg" type="audio/ogg">';
    //     var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3">';
    //     document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
    // }
///----------end sse----------////
//-------------demo sse-----///
    // (function(){
    //     var isNews = 0;
    //     if (!!window.EventSource) {
    //         var E_SOURCE = new EventSource("/demo/sse_server1");
    //     } else {
    //         alert("Your browser does not support Server-sent events! Please upgrade it!");
    //     }
    //     E_SOURCE.addEventListener("message", function(e) {
    //         console.log(e.data);
    //     }, false);
    //     E_SOURCE.addEventListener("new-msgs", function(event) {
    //         var jon_obj = $.parseJSON(event.data);
    //             if(isNews !== jon_obj['newMsgs']){
    //                 isNews = jon_obj['newMsgs'];
    //                 alert("new notification: " + isNews);
    //             }
    //             console.log(jon_obj);
    //             document.getElementById("log").innerHTML += event.data + "<br />";
    //     }, false);

    //     E_SOURCE.addEventListener("open", function(e) {
    //         console.log("Connection was opened.");
    //     }, false);

    //     E_SOURCE.addEventListener("error", function(e) {
    //         console.log("Error - connection was lost.");
    //     }, false);
    //     // E_SOURCE.close();
    // });
///----------end sse----------////

TXT;

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/jquery.gotop_1.1.2/jquery.gotop.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);