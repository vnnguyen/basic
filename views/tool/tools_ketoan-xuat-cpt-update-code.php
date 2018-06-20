<?php
use yii\helpers\Html;

Yii::$app->params['body_class'] = 'sidebar-xs';
$this->title = 'Update code người cung cấp / thanh toán dịch vụ';
$this->params['breadcrumb'] = [
    ['Tools', '@web/tools']
];

$sql = 'SELECT id, name, (SELECT code FROM at_atuan_codes WHERE venue_id=venues.id LIMIT 1) AS code, (SELECT vat FROM at_atuan_codes WHERE venue_id=venues.id LIMIT 1) AS vat FROM venues ORDER BY name';
$theVenues = \Yii::$app->db->createCommand($sql)->queryAll();
?>
<div class="col-md-6">
    <p><strong>NHÀ CUNG CẤP <span class="text-danger">KHÔNG</span> CÓ LINK TRÊN IMS</strong> (<a href="#" id="add-item">+Thêm</a>)</p>
    <div class="panel panel-body table-responsive no-padding">
        <table id="tbl-item" class="table table-narrow">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Mã tên</th>
                    <th>VAT</th>
                    <th width="30">Sửa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $item) { if ($item['venue_id'] == 0) { ?>
                <tr id="tr-item-<?= $item['id'] ?>">
                    <td id="td-item-name-<?= $item['id'] ?>"><?= $item['name'] ?></td>
                    <td id="td-item-code-<?= $item['id'] ?>"><?= strtoupper($item['code']) ?></td>
                    <td id="td-item-vat-<?= $item['id'] ?>"><?= $item['vat'] ?></td>
                    <td class="text-nowrap">
                        <i data-id="<?= $item['id'] ?>" class="edit-item fa fa-edit text-muted cursor-pointer"></i>
                        <i data-id="<?= $item['id'] ?>" class="del-item fa fa-trash-o text-danger cursor-pointer"></i>
                    </td>
                </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-6">
    <p><strong>NHÀ CUNG CẤP CÓ LINK TRÊN IMS</strong></p>
    <div class="table-responsive panel panel-body no-padding">
        <table id="tbl-venue" class="table table-narrow">
            <thead>
                <tr>
                    <th>Nhà cung cấp</th>
                    <th>Mã tên</th>
                    <th>VAT</th>
                    <th width="30">Sửa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($theVenues as $item) { ?>
                <tr id="tr-venue-<?= $item['id'] ?>">
                    <td id="td-venue-name-<?= $item['id'] ?>"><?= Html::a($item['name'], '/venues/r/'.$item['id'], ['target'=>'_blank']) ?></td>
                    <td id="td-venue-code-<?= $item['id'] ?>"><?= strtoupper($item['code']) ?></td>
                    <td id="td-venue-vat-<?= $item['id'] ?>"><?= $item['vat'] ?></td>
                    <td class="text-nowrap">
                        <i data-id="<?= $item['id'] ?>" class="edit-venue fa fa-edit text-muted cursor-pointer"></i>
                        <i data-id="<?= $item['id'] ?>" class="del-venue fa fa-trash-o text-danger cursor-pointer"></i>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit code</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">Tên nhà cung cấp<br><input id="input-name" type="text" class="form-control" autocomplete="off"><strong id="text-name" class="form-control"></strong></div>
                    <div class="col-sm-3">Code<br><input id="input-code" type="text" class="form-control" autocomplete="off"></div>
                    <div class="col-sm-3">VAT<br><input id="input-vat" type="text" class="form-control" autocomplete="off"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="action-save" type="button" class="btn btn-primary">Ghi thông tin</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Thôi</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
$js = <<<'JS'
var action = ''
var id = ''
var name = ''
var code = ''

$('#add-item').on('click', function(e){
    e.preventDefault();
    id = 0
    action = 'add-item'
    $('#text-name').hide()
    $('#input-name').show().val('')
    $('#input-code').val('')
    $('#input-vat').val('')
    $('#myModal').modal('show')
})

$('#tbl-item').on('click', '.edit-item', function(e){
    e.preventDefault();
    id = $(this).data('id')
    action = 'edit-item'
    name = $('#td-item-name-' + id).html()
    code = $('#td-item-code-' + id).html()
    vat = $('#td-item-vat-' + id).html()
    $('#text-name').hide()
    $('#input-name').show().val(name)
    $('#input-code').val(code)
    $('#input-vat').val(vat)
    $('#myModal').modal('show')
})

$('.edit-venue').on('click', function(e){
    e.preventDefault();
    id = $(this).data('id')
    action = 'edit-venue'
    name = $('#td-venue-name-' + id).html()
    code = $('#td-venue-code-' + id).html()
    vat = $('#td-venue-vat-' + id).html()
    $('#input-name').hide()
    $('#text-name').show().html(name)
    $('#input-code').val(code)
    $('#input-vat').val(vat)
    $('#myModal').modal('show')
})

$('#action-save').on('click', function(){
    if (action == 'add-item') {
        name = $('#input-name').val()
        code = $('#input-code').val()
        vat = $('#input-vat').val()
        if (name == '' || code == '') {
            return
        }
        var jqxhr = $.ajax({
            url: '?action=add-item&id=' + id,
            method: 'post',
            data: {
                name: name,
                code: code,
                vat: vat
            }
        })
        .done(function(data) {
            var tr = '<tr id="tr-item-' +data.id+ '">'
                + '<td id="td-item-name-' +data.id+ '">'+name+'</td>'
                + '<td id="td-item-code-' +data.id+ '">'+code+'</td>'
                + '<td id="td-item-vat-' +data.id+ '">'+vat+'</td>'
                + '<td class="text-nowrap">'
                + '<i data-id="' +data.id+ '" class="edit-item fa fa-edit text-muted cursor-pointer"></i> '
                + '<i data-id="' +data.id+ '" class="del-item fa fa-trash-o text-danger cursor-pointer"></i>'
                + '</td></tr>'
            $('#tbl-item tbody').prepend(tr)
        })
        .fail(function() {
            alert('Request failed. Please try again.')
        })
    }
    if (action == 'edit-item') {
        name = $('#input-name').val()
        code = $('#input-code').val()
        vat = $('#input-vat').val()
        var jqxhr = $.ajax({
            url: '?action=edit-item&id=' + id,
            method: 'post',
            data: {
                name: name,
                code: code,
                vat: vat
            }
        })
        .done(function() {
            $('#td-item-name-' + id).html(name);
            $('#td-item-code-' + id).html(code);
            $('#td-item-vat-' + id).html(vat);
            $('#myModal').modal('hide')
        })
        .fail(function() {
            alert('Request failed. Please try again.')
        })
    }
    if (action == 'edit-venue') {
        name = $('#text-name a').text()
        code = $('#input-code').val()
        vat = $('#input-vat').val()
        var jqxhr = $.ajax({
            url: '?action=edit-venue&id=' + id,
            method: 'post',
            data: {
                name: name,
                code: code,
                vat: vat
            }
        })
        .done(function() {
            $('#td-venue-code-' + id).html(code);
            $('#td-venue-vat-' + id).html(vat);
            $('#myModal').modal('hide')
        })
        .fail(function() {
            alert('Request failed. Please try again.')
        })
    }
})

$('#tbl-item').on('click', '.del-item', function(e){
    e.preventDefault();
    if (!confirm('Delete item?')) {
        return false
    }
    var id = $(this).data('id')
    var jqxhr = $.ajax('?action=del-item&id=' + id)
    .done(function() {
        $('#tr-item-' + id).remove();
    })
    .fail(function() {
        alert('Request failed. Please try again.')
    })
})

$('.del-venue').on('click', function(e){
    e.preventDefault();
    if (!confirm('Delete item?')) {
        return false
    }
    var id = $(this).data('id')
    var jqxhr = $.ajax('?action=del-venue&id=' + id)
    .done(function() {
        $('#td-venue-code-' + id).empty();
    })
    .fail(function() {
        alert('Request failed. Please try again.')
    })
})
JS;
$this->registerJs($js);