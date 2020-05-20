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

// $('.popovers').popover();
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

Yii::$app->params['js'] = $js;