<?php

use yii\helpers\Html;

$searchInList = [
    'sd'=>'Sample days',
    // 'sp'=>'Sample programs',
    'mp'=>'My programs',
    'ap'=>'All programs',
];

$searchLangList = [
    'en'=>'EN',
    'fr'=>'FR',
    'it'=>'IT',
    'vi'=>'VI',
];

?>
<style>#letsGo:focus {background-color:brown; border-color:brown}</style>
<div class="modal fade modal-primary" id="help-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h6 class="modal-title text-pink text-semibold"><?= Yii::t('p', 'Instruction / Hướng dẫn') ?></h6>
            </div> 
            <div class="modal-body">
                <ol>
                    <li>Click day's name to reveal or hide its detail / <span class="text-info">Click tên ngày để hiện hoặc ẩn nội dung chi tiết ngày đó</span></li>
                    <li>Click "Toggle all days" on top of left panel to toggle all days' content / <span class="text-info">Click link "Toggle all days" ở trên cùng để hiện hay ẩn nội dung chi tiết của mọi ngày trong chương trình</span></li>
                    <li>Click day number on the left of each day to drag and reorder it / <span class="text-info">Click chuột vào con số thứ tự ngày ở bên trái mỗi ngày và kéo lên hoặc xuống để đổi thứ tự ngày</span></li>
                    <li>Hover mouse over each day to see editing buttons / <span class="text-info">Di chuột qua tiêu đề ngày để thấy các nút chỉnh sửa</span></li>
                    <li>Click editing buttons on the right of each day's name to add/edit/delete day / <span class="text-info">Click các nút chỉnh sửa ở bên phải mỗi tiêu đề ngày để thêm, sửa hoặc xoá ngày</span></li>
                    <li>There are 3 ways to add a new day to the tour program: add blank day, copy content from the day, or add day from the database (sample days or existing tour programs) / <span class="text-info">Có 3 cách để thêm ngày vào chương trình: thêm ngày trống, copy ngày hiện tại và thêm xuống dưới, và copy ngày từ CSDL ngày mẫu hoặc từ các tour có sẵn</span></li>
                    <li>If you click (+) button to add day from database, a popup window will appear, you search and select day from that window / <span class="text-info">Nếu bạn chọn thêm ngày từ dữ liệu ngày mẫu hay tour có sẵn, một cửa sổ sẽ mở để bạn chọn ngày hay chương trình theo tag rồi thêm ngày bạn muốn</span></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-primary" id="huan2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h6 class="modal-title text-pink text-semibold">Copy day from sample database or tour programs</h6>
            </div> 
            <div class="modal-body">
                <div class="form-inline mb-20">
                    <?= Html::dropdownList('search_lang', 'fr', $searchLangList, ['class'=>'form-control']) ?>
                    <?= Html::dropdownList('search_b2cb', 'b2c', ['b2c'=>'B2C', 'b2b'=>'B2B'], ['class'=>'form-control']) ?>
                    <?= Html::dropdownList('search_in', '', $searchInList, ['class'=>'form-control']) ?>
                    <?= Html::textInput('search_tags', '', ['class'=>'form-control', 'placeholder'=>'Search tag']) ?>
                    <?= Html::textInput('search_name', '', ['class'=>'form-control', 'placeholder'=>'Search name/tour code']) ?>
                    <?= Html::submitButton('Go', ['class'=>'btn btn-primary', 'data-page'=>0, 'id'=>'letsGo']) ?>
                </div>
                <div class="table-responsive" id="loaded">
                    <table id="tblProgList" class="table table-striped table-condensed">
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="panel-body text-center">
                    <ul class="pagination">
                        <li class="first"><a id="prev_page" data-page="0" href="#">&laquo; Previous page</a></li>
                        <li class="active"><a id="current_page" href="#">&nbsp;</a></li>
                        <li class="last"><a id="next_page" data-page="1" href="#">Next page &raquo;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
// Pagination
$('#letsGo, #prev_page, #next_page').on('click', function(e){
    var search_page = $(this).data('page');
    var search_lang = $('[name="search_lang"]').val();
    var search_name = $('[name="search_name"]').val();
    var search_tags = $('[name="search_tags"]').val();
    var search_b2cb = $('[name="search_b2cb"]').val();
    var search_in = $('[name="search_in"]').val();
    $('#letsGo').html('Searching...').prop('disabled', true);
    $('#prev_page, #next_page').parent().addClass('disabled');
    e.preventDefault();
    $.ajax({
        url: '?action=json&xh',
        method: "post",
        data: {
            search_in: search_in,
            search_lang: search_lang,
            search_name: search_name,
            search_tags: search_tags,
            search_b2cb: search_b2cb,
            search_page: search_page
        }
    })
    .done(function(data, textStatus, jqXHR) {
        $("#tblProgList tbody").empty();
        if (data.prev_page === false) {
            $('.pagination li.first').hide();
        } else {
            $('.pagination li.first').show();
            $('#prev_page').show().prop('data-page', data.prev_page).data('page', data.prev_page);
        }
        if (data.next_page === false) {
            $('.pagination li.last').hide();
        } else {
            $('.pagination li.last').show();
            $('#next_page').show().prop('data-page', data.next_page).data('page', data.next_page);
        }
        $('#current_page').html('Page ' + (1 + data.page));
        drawTable(data.data);
    })
    .fail(function(data) {
        alert(data.message);
    })
    .always(function() {
        $('#letsGo').html('Go').prop('disabled', false);
        $('#prev_page, #next_page').parent().removeClass('disabled');
    })
    ;
});

// $('#tblCurrentProg').on('dblclick', 'div.day-body', function(){
//     $('#tblCurrentProg div.day-body').not($(this)).attr('contenteditable', false);
//     $(this).attr('contenteditable', true).css('background-color', '#ffd').css('padding', '16px');
// })

function drawTable(data) {
    for (var i = 0; i < data.length; i++) {
        drawRow(data[i]);
    }
    if (data.length == 0) {
        $('#tblProgList tbody').html('<tr><td class="text-danger">No data found.</td></tr>');
    }
}

function drawRow(rowData) {
    if (rowData.is == 'prog') {
        // Program
        var row = $('<tr class="row_prog info" id="row_prog_' + rowData.id + '" />')
        row.appendTo($('#tblProgList tbody'));
        row.append($('<td width="10"><i data-id="' + rowData.id + '" class="text-muted toggle-prog-day fa fa-list cursor-pointer"></i></td>'));
        row.append($('<td class="no-padding-left">' + (rowData.op_code == '' ? '' : '<a style="background-color:#ffc; padding:2px;" class="text-success" title="View tour" target="_blank" href="/products/op/' + rowData.id + '">' + rowData.op_code + '</a> ') + '<strong><a title="View detail" href="#" onclick="$(\'.row_day[data-prog=' + rowData.id + ']\').toggle(); return false;">' + rowData.title + '</a></strong> ' + rowData.pax_count + 'p ' + rowData.day_count + 'd <span class="text-muted">' + rowData.about + '</span> <span title="Updated '+rowData.updated_at_time+'" class="text-muted"><i class="fa fa-clock-o"></i> '+rowData.updated_by_name+'</span></td>'));
    } else {
        // Day
        var row = $('<tr class="row_day" id="row_day_' + rowData.id + '" data-prog="' + rowData.prog_id + '" style="' + (rowData.prog_id != 0 ? 'display:none;' : '') + '" />')
        row.appendTo($('#tblProgList tbody'));
        row.append($('<td width="10"><i data-id="' + rowData.id + '" class="insert-day fa fa-plus cursor-pointer"></i></td>'));
        row.append($('<td class="no-padding-left"><a title="View detail" href="#" onclick="$(this).siblings(\'.day-content\').toggle(); return false;">' + rowData.title + '</a> <em>' + rowData.meals + '</em><div class="day-content mt-20" style="display:none">'+ rowData.body +'</div></td>'));
    }
}

// Insert day to left
$('#tblProgList').on('click', 'i.insert-day', function(){
    $(this).removeClass('insert-day fa-plus cursor-pointer').addClass('text-success fa-check');
    // Add day {id} to {prog} at {pos}
    var search_in = $('[name="search_in"]').val();
    var id = $(this).data('id');
    var idx = insertAt;
    $('#tblProgList, #tblCurrentProg').block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    }); 
    $.ajax({
        method: "POST",
        url: "?action=insert-day&xh",
        data: {from: search_in, id: id, at: idx + 1}
    })
    .done(function( response ) {
        $(this).removeClass('fa-plus cursor-pointer').addClass('fa-check text-success');
        insertDay(response, idx);
        insertAt ++;
        recountDays();
    })
    .fail(function( msg ) {
        alert('Error! Day could not be added.');
    })
    .always(function(){
        $('#tblProgList, #tblCurrentProg').unblock();
    })
    return false;
});
TXT;

$this->registerJs($js);