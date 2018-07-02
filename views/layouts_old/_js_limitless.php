<?

$js = <<<'TXT'

//
// $('#element').donetyping(callback[, timeout=1000])
// Fires callback when a user has finished typing. This is determined by the time elapsed
// since the last keystroke and timeout parameter or the blur event--whichever comes first.
//   @callback: function to be called when even triggers
//   @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
//			  caused by blur.
// Requires jQuery 1.7+
//
;(function($){
	$.fn.extend({
		donetyping: function(callback,timeout){
			timeout = timeout || 1e3; // 1 second default timeout
			var timeoutReference,
				doneTyping = function(el){
					if (!timeoutReference) return;
					timeoutReference = null;
					callback.call(el);
				};
			return this.each(function(i,el){
				var $el = $(el);
				// Chrome Fix (Use keyup over keypress to detect backspace)
				// thank you @palerdot
				$el.is(':input') && $el.on('keyup keypress',function(e){
					// This catches the backspace button in chrome, but also prevents
					// the event from triggering too premptively. Without this line,
					// using tab/shift+tab will make the focused element fire the callback.
					if (e.type=='keyup' && e.keyCode!=8) return;
					
					// Check if timeout has been set. If it has, "reset" the clock and
					// start over again.
					if (timeoutReference) clearTimeout(timeoutReference);
					timeoutReference = setTimeout(function(){
						// if we made it here, our timeout has elapsed. Fire the
						// callback
						doneTyping(el);
					}, timeout);
				}).on('blur',function(){
					// If we can, fire the event since we're leaving the field
					doneTyping(el);
				});
			});
		}
	});
})(jQuery);

var tim = '';
var t;

$('#qx').click(function(){
	if ($(this).find('#qi').hasClass('icon-close')) {
		$('#suggest').empty();
		$('#q').val('');
		$('#qi').toggleClass('icon-magnifier icon-close');
	} else {
		location.href='/search';
	}
});

$('#q').donetyping(function(){
	var go = $(this).val();
	if (go != tim) {
		tim = go;
		if (tim != '') {
			if ($('#qi').hasClass('icon-magnifier')) {
				$('#qi').toggleClass('icon-magnifier icon-close');
			}
			$.post('/default/search', {tim:tim}, function(text){
				$('#suggest').empty().append(text);
			}, 'text');
		} else {
			$('#suggest').empty();
		}
	}
}, 500);

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

function formatRepo (repo) {
    if (repo.loading) return repo.text;
    var markup = "<a href='" + repo.url + "' class='select2-result display-block clearfix'>" + repo.avatar_url + repo.found + "</a>"
	return markup;
}

function formatRepoSelection (repo) {
	return repo.found || repo.text;
}

$("#livesearch").select2({
    ajax: {
        // url: "https://api.github.com/search/repositories",
        url: "https://my.amicatravel.com/default/search",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                u: 1, // user id 1
                q: params.term, // search term
                page: params.page
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
        cache: true
    },
    closeOnSelect: false,
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 3,
    templateResult: formatRepo,
    // templateSelection: formatRepoSelection
});

$('#livesearch').on('select2:selecting', function (evt) {
    return false;
    // alert($(this).val());
    // location.href = $(this).val();
});

$('#livesearch').on('select2:select', function (evt) {
    return false;
    // alert($(this).val());
    // location.href = $(this).val();
});
TXT;

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/jquery.gotop_1.1.2/jquery.gotop.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);