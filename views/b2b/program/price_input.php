<?php 
$baseUrl = Yii::$app->request->baseUrl;
$css = <<<TXT

.table-editable { position: relative; }
.table-editable .glyphicon {font-size: 20px;
  }
.table-remove {color: #700; cursor: pointer; }
.table-remove :hover {color: #f00; }

.table-up, .table-down, .table-merge {color: #007; cursor: pointer; }
.table-up:hover, .table-down:hover, .table-merge:hover {color: #00f; }
.table-add {color: #070; cursor: pointer; position: absolute; top: 5px; right: 0; }
.table-add:hover {color: #0b0; }
td.focus, th.focus {background: #fff!important; border: 1px solid #3399FF!important; box-shadow: 0px 0px 3px #cdcdcd!important}
#gen_table { padding: 3px 5px}
 table tr#th_row { background: #f3f3f3}
 table tr td.th_column { background: #f3f3f3}
 table tr td, table tr th { width: 100px!important;}
TXT;
$this->registerCss($css);
?>
<div id="custormtable">
    <label>Colunm No: </label>
    <input id="num_of_col" type="number" value="" placeholder="">
    <button id="gen_table" class="btn btn-primary">Generate table</button>
</div>
<div id="table" class="table-responsive table-editable">
<span class="table-add"><i class="fa fa-plus" aria-hidden="true"></i></span>
<table  class="table table-narrow">
    <tbody id="sortable-list-second"></tbody>
</table>
</div>

<button id="export-btn" class="btn btn-primary">Export Data</button>
<p id="export"></p>



<!-- <div class="clearfix"></div>
<ul id="sortable-list-second" class="selectable-demo-list">
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
    <li class="ui-sortable-handle">avxxxx</li>
</ul> -->
<?php
$js = <<<TXT
var NUM_OF_COL;
var TABLE = $('#table');
var BTN = $('#export-btn');
var EXPORT = $('#export');
var FIRST_TH_CONTENT = '#';
var COUNT_ROWS;
$( "#sortable-list-second" ).sortable({
    handle: ".actions_td",
    // start: function( event, ui ) {
    //     index_b = ui.item.find('.i-count').text();
    // },
    stop: function( event, ui ) {
        setUpDown();
    },
});

$(document).ready(function(){
    $('.table-add').click(function () {
        var tr = '<tr>';
        for(var i = 0; i < NUM_OF_COL; i++) {
            if (i == 0) {
                tr += '<td class="text-center th_column" contenteditable="true">'+i+'</td>';
            } else if (i == (NUM_OF_COL-1)) {
                tr += '<td class="text-center text-right actions_td"> <span class="table-remove"><i class="fa fa-trash" aria-hidden="true"></i></span> <span class="table-up"><i class="fa fa-arrow-up" aria-hidden="true"></i></span> <span class="table-down"><i class="fa fa-arrow-down" aria-hidden="true"></i></span> <span class="table-merge"><i class="fa fa-compress" aria-hidden="true"></i></span></td>';
            } else {
                tr += '<td class="text-center" contenteditable="true" >'+i+'</td>';
            }
        }
        tr += '</tr>';
        TABLE.find('table tbody').append(tr);
        setUpDown();
        var last_tr = TABLE.find('table tbody tr:last');
        last_tr.find('td:first').focus();
    });
    // A few jQuery helpers for exporting only
    jQuery.fn.pop = [].pop;
    jQuery.fn.shift = [].shift;

    BTN.click(function () {
        var rows = TABLE.find('tr:not(:hidden)');
        var headers = [];
        var data = [];

        // Get the headers (add special header logic here)
        $(rows.shift()).find('th:not(:empty)').each(function () {
            headers.push($(this).text().toLowerCase());
        });

        // Turn all existing rows into a loopable array
        rows.each(function () {
            var td = $(this).find('td');
            var h = {};
            // Use the headers from earlier to name our hash keys
            headers.forEach(function (header, i) {
                h[header] = td.eq(i).text();
                if (td.eq(i).prop('colspan') != '' && parseInt(td.eq(i).prop('colspan')) > 1) {
                     h[header] = td.eq(i).text()+"/"+td.eq(i).prop('colspan');
                }
            });

            data.push(h);
        });
        // Output the result
        EXPORT.text(JSON.stringify(data));
    });
    $('#gen_table').click(function(){
        TABLE.find('table tbody').empty();
        NUM_OF_COL = parseInt($('#num_of_col').val()) + 2;
        var tr = '<tr id="th_row">';
        // console.log(NUM_OF_COL); return;
        if (NUM_OF_COL > 2) {
            for(var i = 0; i < NUM_OF_COL; i++) {
                if (i == 0) {
                    tr += '<th class="text-center">'+FIRST_TH_CONTENT+'</th>';
                } else if (i == (NUM_OF_COL-1)) {
                    tr += '<th  class="text-center" width="90px"></th>';
                } else {
                    tr += '<th  class="text-center" contenteditable="true" >'+i+'</th>';
                }
            }
            tr += '</tr>';
            TABLE.find('table tbody').append(tr);
        }

    });
});
//////////////////////out ready////////////////
$(document).on('keydown', 'td, th', function(event){
    if(13 == event.which) { // press ENTER-key
        var row = $(this).closest('tr'),
            row_next = row.next();
        if (row_next == null || row_next.length == 0){
            var tr = '<tr>';
            for(var i = 0; i < NUM_OF_COL; i++) {
                if (i == 0) {
                    tr += '<td class="text-center th_column" contenteditable="true">'+i+'</td>';
                } else if (i == (NUM_OF_COL-1)) {
                    tr += '<td class="text-center text-right"> <span class="table-remove"><i class="fa fa-trash" aria-hidden="true"></i></span> <span class="table-up"><i class="fa fa-arrow-up" aria-hidden="true"></i></span> <span class="table-down"><i class="fa fa-arrow-down" aria-hidden="true"></i></span> <span class="table-merge"><i class="fa fa-compress" aria-hidden="true"></i></span></td>';
                } else {
                    tr += '<td class="text-center" contenteditable="true" >'+i+'</td>';
                }
            }
            tr += '</tr>';
            TABLE.find('table tbody').append(tr);
            setUpDown();
            var last_tr = TABLE.find('table tbody tr:last');
            last_tr.find('td:first').focus();
            return false;
        }
        row_next.find('td:first').focus();
        return false;
    } else if(9 == event.which) {  // press TAB-key
        if ($(this).index() == NUM_OF_COL-1) {
            var row = $(this).closest('tr'),
                row_next = row.next();
            if (row_next != null && row_next.length == 1) {
                row_next.find('td:first').focus();
                return false;
            } else {
                BTN.focus();
            }
        }
    } else if(38 == event.which) {  // press UP-key
        var td_index = $(this).index();
        var row = $(this).closest('tr'),
            row_prev = row.prev();
        if (row_prev != null && row_prev.length == 1) {
            row_prev.find('td:eq('+td_index+')').focus();
        }
        return false;
    } else if(40 == event.which) {  // press DOWN-key
        var td_index = $(this).index();
        var row = $(this).closest('tr'),
            row_next = row.next();
        if (row_next != null && row_next.length == 1) {
            row_next.find('td:eq('+td_index+')').focus();
        }
        return false;
    }
    // else if(27 == event.which) {  // press ESC-key
    //     tdObj.html(preText);
    // }//{ console.log(event.which); return;}
});
$(document).on('focus', 'th, td', function(){
    $(this).addClass('focus');
});
$(document).on('blur', 'th, td', function(){
    $(this).removeClass('focus');
});
$(document).on('click','.table-remove', function () {
    $(this).parents('tr').detach();
    setUpDown();
});

$(document).on('click','.table-up', function () {
    var row_clicked = $(this).parents('tr');
    if (row_clicked.index() === 1) return; // Don't go above the header
    row_clicked.prev().before(row_clicked.get(0));
    setUpDown();
});
$(document).on('click', '.table-merge', function(){
    var clicked = $(this);
    var tr = $(clicked).closest('tr');
    var rang = prompt('what column want merge? (ex:0-2) ');
    if (rang != null) {
        var arr_col = rang.split('-');
        var check_exist_col = true;
        if (arr_col.length == 2 && arr_col[0] != arr_col[1]) {
            if (isNaN(arr_col[0]) || isNaN(arr_col[1]))
                return false;
            if (parseInt(arr_col[0]) < parseInt(arr_col[1])) {
                var min = arr_col[0];
                var max = arr_col[1];
            } else {
                var max = arr_col[0];
                var min = arr_col[1];
            }
            if ($(tr).find('td:eq('+arr_col[0]+')').length > 0 
                && $(tr).find('td:eq('+arr_col[1]+')').length > 0 
            && max < NUM_OF_COL) {
                var td_prev = $(tr).find('td:eq('+min+')').prev();
                var td_next = $(tr).find('td:eq('+max+')').next();
                var colspan = max - min + 1;
                var arr_to_merge = [];
                jQuery.each($(tr).find('td'), function(index, td){
                    if (index >= min && index <= max) {
                        arr_to_merge[index] = td;
                    }
                });
                var html = '<td colspan="'+colspan+'" contenteditable="true"></td>';
                if(min > 0){
                    $(html).insertAfter(td_prev);
                }
                else{
                    $(html).insertBefore(td_next);
                }
                jQuery.each(arr_to_merge, function(i, item){
                    if (item != undefined) {
                        $(item).detach();
                    }
                });
            }
        } else { alert('undefined')}
    }
});
$(document).on('click','.table-down', function () {
    var row = $(this).parents('tr');
    row.next().after(row.get(0));
    setUpDown();
});
function setUpDown()
{
    var trs = TABLE.find('table tr:not(#th_row)');
    if (trs.length == 1) {
        var tr = trs[0];
        $(tr).find('.table-up').hide();
        $(tr).find('.table-down').hide();
    } else {
        if (trs.length > 0) {
            jQuery.each($(trs), function(index, row){
                var action_up = $(row).find('.table-up');
                var action_down = $(row).find('.table-down');
                action_up.show();
                action_down.show();
                if (index == 0) {
                    action_up.hide();
                    action_down.show();
                }
                if (index == trs.length - 1) {
                    action_up.show();
                    action_down.hide();
                }
            });
        }
    }
}
function setSelection(element) {
  // code for selection from http://stackoverflow.com/questions/3805852/select-all-text-in-contenteditable-div-when-it-focus-click#answer-3806004
  setTimeout(function() {
    var sel, range;
        if (window.getSelection && document.createRange) {
            range = document.createRange();
            range.selectNodeContents(element);
            sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        }
  }, 0)
}
TXT;
$this->registerJsFile($baseUrl.'/js/core/libraries/jquery_ui/interactions.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs($js);
?>