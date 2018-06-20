<?php
use yii\helpers\Html;
$this->title = 'Advanced search';
$this->params['icon'] = 'search';
$this->params['breadcrumb'] = [
    ['Advanced search', 'search'],
];
?>
<div class="col-lg-12">
    <div class="tabbable tabbable-custom tabbable-full-width">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#t0" data-toggle="tab">Toàn bộ</a></li>
            <li><a href="#t1" data-toggle="tab">Hồ sơ</a></li>
            <li><a href="#t2" data-toggle="tab">Booking</a></li>
            <li><a href="#t3" data-toggle="tab">Người</a></li>
            <li><a href="#t4" data-toggle="tab">Dịch vụ</a></li>
            <li><a href="#t5" data-toggle="tab">Ghi chú</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="t0">
                <div id="sr"></div>
                <? if (USER_ID == 1) { ?>
                <div id="remote">
                    <input class="typeahead" type="text" placeholder="Search for anything on IMS">
                </div>
                <? } ?>
            </div>
            <div class="tab-pane" id="t1">...</div>
            <div class="tab-pane" id="t2">...</div>
            <div class="tab-pane" id="t3">...</div>
            <div class="tab-pane" id="t4">...</div>
            <div class="tab-pane" id="t5">...</div>
        </div>
    </div>
</div>
<?
$js = <<<TXT
var searchResults = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    // prefetch: '../data/films/post_1960.json',
    remote: {
        url: 'https://my.amicatravel.com/default/search?q=%QUERY',
        wildcard: '%QUERY'
    }
});

$('#remote .typeahead').typeahead(null, {
    name: 'search-results',
    display: 'found',
    source: searchResults
});

TXT;

if (USER_ID == 1) {
    $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js', ['depends'=>'yii\web\JqueryAsset']);
    $this->registerJs($js);
}