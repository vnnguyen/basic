<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

require_once('_tours_inc.php');

// require_once('/var/www/vendor/textile/php-textile/Parser.php');
// $parser = new \Netcarver\Textile\Parser();

Yii::$app->params['page_layout'] = '-s';
Yii::$app->params['page_title'] = 'In lịch xe cho tour: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['In lịch xe'],
];

$dayIdList = explode(',', $theTour['day_ids']);

$dviList = [
    'km'=>'Km',
    'db'=>'Ngày ĐB',
    'tb'=>'Ngày TB',
    'chang'=>'Lượt',
];

$vpList = [
    'hanoi'=>'VP Hà Nội',
    'saigon'=>'VP Sài Gòn',
];

$giaList = [
    'Xe công ty'=>[
        '4c'=>'4 chỗ / Xe hãng',
        '7c'=>'7 chỗ / Xe hãng',
        '7cl'=>'7 chỗ (Limousine) / Xe hãng',
        '16c'=>'16 chỗ / Xe hãng',
        '24c'=>'24-30 chỗ / Xe hãng',
        '30cs'=>'30 chỗ (Samco) / Xe hãng',
        '35cs'=>'35 chỗ (Samco) / Xe hãng',
        '45cu'=>'45 chỗ (Universe) / Xe hãng',
        '45ch'=>'45 chỗ (Hyundai) / Xe hãng',
    ],
    'Xe lẻ'=>[
        '4l'=>'4 chỗ / Xe lẻ',
        '7l'=>'7 chỗ / Xe lẻ',
        '16l'=>'16 chỗ / Xe lẻ',
        '24l'=>'24-30 chỗ / Xe lẻ',
    ],
];

if ($action == 'edit') {
    // Nhung ngay co lich xe
    $cntList = [];
    foreach ($theLichxeContent as $line) {
        $cntList[] = $line[0];
    }
}

?>
<style>
.table td, .table th {vertical-align:top!important}
.table-tight td {padding:2px!important; vertical-align:middle!important}
.table-tight th {vertical-align:middle!important}
.table-tight input, .table-tight select, .table-tight textarea {padding:2px!important; border:1px solid #eee!important;}
.table-tight input:focus, .table-tight select:focus, .table-tight textarea:focus {border:1px solid #1E88E5!important;}
</style>


<? $form = ActiveForm::begin(['id'=>'lxForm']); ?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <? if ($action == 'add') { ?>
                Thêm lịch xe mới
                <? } else { ?>
                Sửa lịch xe
                <? } ?></h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Chú ý:</strong></p>
                    <ul>
                        <li>Giá xe miền Bắc (VND) sẽ dùng trong trang in ở bước tiếp theo</li>
                        <li>Ở bảng chi tiết dưới đây, click <i class="fa fa-plus"></i> để copy dòng xuống dưới (vd trường hợp một ngày có nhiều mức giá)</li>
                        <li>Trường hợp đơn vị tính là "chặng" thì ĐH tự nhập giá của chặng.</li>
                    </ul>                    
                </div>
                <div class="col-md-6">
                    <? if (empty($theLichxes)) { ?>
                    <div class="alert alert-warning">
                        Chưa có lịch xe nào được tạo. Để thêm lịch xe mới cho tour này, hãy điền form dưới đây. Một mục chi phí tour tương ứng cũng sẽ được tạo ra và link với lịch xe.
                    </div>
                    <? } else { ?>
                    <p><strong>Các lịch xe của tour này</strong></p>
                    <? foreach ($theLichxes as $lx) { ?>
                    <div class="actions"><i class="fa fa-car text-muted"></i>
                        <? if (in_array(USER_ID, [$lx['created_by'], $lx['updated_by']])) { ?>
                        [<?= $lichxe == $lx['id'] ? 'Đang sửa' : Html::a('Sửa', '?action=edit&lichxe='.$lx['id']) ?>]
                        <? } ?>
                        [<?= Html::a('In LX', '?action=print&not-fit&&lichxe='.$lx['id'], ['title'=>'In cho nhà xe: gồm lịch trình và phần phát sinh']) ?>]
                        [<?= Html::a('In LX+PL rời', '?action=print&not-fit&paxlist=yes&lichxe='.$lx['id'], ['title'=>'In cho nhà xe: gồm lịch trình và danh sách khách, danh sách khách trên trang mới']) ?>]
                        [<?= Html::a('In LX+PL liền', '?action=print&not-fit&lien&paxlist=yes&lichxe='.$lx['id'], ['title'=>'In cho nhà xe: gồm lịch trình và danh sách khách, danh sách khách liền lịch trình']) ?>]
                        [<?= Html::a('In LX cho KT', '?action=print&not-fit&ketoan=yes&lichxe='.$lx['id'], ['title'=>'In cho kế toán: chỉ gồm lịch trình']) ?>]
                        [<?= Html::a('Xem cpt', '/cpt/r/'.$lx['cpt_id']) ?>]
                        [<?= Html::a('Copy lịch xe', '#', ['class' => 'copy_lx', 'data-id' => $lx['id']]) ?>]
                        Lịch xe #<?= $lx['id'] ?> cập nhật ngày <?= date('j/n/Y', strtotime($lx['updated_dt'])) ?> bởi <?= $lx['updatedBy']['name'] ?>
                    </div>
                    <? } ?>
                    <div class="copy-wrapper hidden">
                        <div class="wrapper_form hidden">
                        <form>
                            <div class="col-lg-4 col-md-offset-4">
                                <div class="wrap_input" style="padding: 10px; background: #f3f3f3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tour code..." name="tour_code">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary search_tour_code" type="button">Go!</button>
                                            <button class="btn btn-secondary cancel_form" type="button">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </form>
                        </div>
                    </div>
                    <? if ($action != 'add') { ?>
                    <div><i class="fa fa-plus text-muted"></i> <?= Html::a('Thêm lịch xe mới', '?action=add') ?></div>
                    <? } ?>
                    <? } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><?= $form->field($theForm, 'days')->label('In các ngày (vd 1-4,6,10)') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'vp')->dropdownList($vpList)->label('In theo địa chỉ của vp') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'pax', ['inputOptions'=>['type'=>'number', 'min'=>0, 'max'=>999, 'class'=>'form-control']])->label('Số pax') ?></div>
                    </div>
                    <div class="alert alert-info">
                        Số pax ngày: <span id="pcount"><?= (int)$theForm['pax'] ?></span>p x <span id="dcount"><?= count($theTour['days']) ?></span>d = <span id="pdcount" class="text-pink"><?= (int)$theForm['pax'] * count($theTour['days']) ?></span>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><?= $form->field($theForm, 'dieuhanh')->dropdownList(ArrayHelper::map($theTourOld['operators'], 'name', 'name'), ['prompt'=>'( Không chọn )'])->label('Điều hành') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'huongdan')->dropdownList(ArrayHelper::map($theTour['guides'], 'guide_name', 'guide_name'), ['prompt'=>'( Không chọn )'])->label('Hướng dẫn viên') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'name')->dropdownList($giaList, ['prompt'=>'- Chọn -'])->label('Điền sẵn giá xe MB dưới đây') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><?= $form->field($theForm, 'loaixe')->label('Loại xe') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'chuxe')->label('Chủ xe') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'laixe')->label('Lái xe') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><?= $form->field($theForm, 'giakm')->label('Giá km') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'giadb')->label('Giá ngày Đông Bắc') ?></div>
                        <div class="col-md-4"><?= $form->field($theForm, 'giatb')->label('Giá ngày Tây Bắc') ?></div>
                    </div>

                </div>
                <div class="col-md-6">
                    <?= $form->field($theForm, 'note')->textArea(['rows'=>10])->label('Ghi chú được in kèm lịch xe') ?>
                    <p><strong>Thưởng lái xe</strong> Chú ý: Chi phí này in vào lịch xe nhưng trên IMS vẫn cần nhập chi phí riêng như cũ</p>
                    <div class="row">
                        <div class="col-sm-4"><?= $form->field($theForm, 'cpkhac_ten')->label('Tên chi phí') ?></div>
                        <div class="col-sm-3"><?= $form->field($theForm, 'cpkhac_dvi')->label('Đơn vị') ?></div>
                        <div class="col-sm-2"><?= $form->field($theForm, 'cpkhac_sl', ['inputOptions'=>['type'=>'number', 'class'=>'form-control', 'min'=>0, 'max'=>100]])->label('Số lượng') ?></div>
                        <div class="col-sm-3"><?= $form->field($theForm, 'cpkhac_gia', ['inputOptions'=>['type'=>'number', 'class'=>'form-control', 'min'=>0, 'max'=>999999999]])->label('Giá (VND/đơn vị)') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix visible-block"></div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Chi tiết các ngày
                <small>Chú ý: các ngày bôi đỏ sẽ không được in</small>
            </h6>
        </div>
        <div class="table-responsive">
            <table id="tblLichxe" class="table-tight table table-borderless table-xxs">
                <thead>
                    <tr>
                        <th width="10">TT</th>
                        <th width="60">Ngày</th>
                        <th>Nội dung</th>
                        <th width="60">SL</th>
                        <th width="100">Đ/vị</th>
                        <th width="100">Giá tiền</th>
                        <th width="10"></th>
                    </tr>
                </thead>
                <tbody>
                    <? if ($action == 'add') { ?>
<?
$cnt = 0;
foreach ($dayIdList as $id) {
    foreach ($theTour['days'] as $day) {
        if ($id == $day['id']) {
            $cnt ++;
            $date = date('j/n', strtotime('+'.($cnt - 1).' days', strtotime($theTour['day_from'])));
?>
                    <tr data-id="<?= $id ?>" data-cnt="<?= $cnt ?>" class="tr<?= $cnt ?>">
                        <?= Html::hiddenInput('tt[]', $cnt) ?>
                        <td class="text-muted text-center"><?= $cnt ?></td>
                        <td><?= Html::textInput('ngay[]', $date, ['class'=>'field_ngay form-control text-center']) ?></td>
                        <td><?= Html::textInput('noidung[]', $day['name'], ['class'=>'field_noidung form-control autogrow']) ?></td>
                        <td><?= Html::textInput('sl[]', 1, ['class'=>'field_sl form-control text-right']) ?></td>
                        <td><?= Html::dropdownList('dvi[]', '', $dviList, ['class'=>'field_dvi form-control']) ?></td>
                        <td><?= Html::textInput('gia[]', '', ['class'=>'field_gia form-control text-right', 'readonly'=>'readonly']) ?></td>
                        <td class="text-nowrap">
                            <i data-day="<?= $cnt ?>" title="Copy dòng" class="add cursor-pointer fa fa-plus"></i>
                        </td>
                    </tr>
<?
        }
    }
}
?>                  <? } // action add ?>
                    <? if ($action == 'edit') { ?>
<?
$cnt = 0;
foreach ($dayIdList as $id) {
    foreach ($theTour['days'] as $day) {
        if ($id == $day['id']) {
            $cnt ++;
            $date = date('j/n', strtotime('+'.($cnt - 1).' days', strtotime($theTour['day_from'])));
            if (in_array($cnt, $cntList)) {
                foreach ($theLichxeContent as $line) {
                    if ($cnt == $line[0]) {
                        $theLine = $line;
                        ?>
                    <tr data-id="<?= $id ?>" data-cnt="<?= $theLine[0] ?>" class="tr<?= $theLine[0] ?>">
                        <?= Html::hiddenInput('tt[]', $theLine[0]) ?>
                        <td class="text-muted text-center"><?= $theLine[0] ?></td>
                        <td><?= Html::textInput('ngay[]', $theLine[1], ['class'=>'field_ngay form-control text-center']) ?></td>
                        <td><?= Html::textInput('noidung[]', $theLine[2], ['class'=>'field_noidung form-control autogrow']) ?></td>
                        <td><?= Html::textInput('sl[]', $theLine[3], ['class'=>'field_sl form-control text-right']) ?></td>
                        <td><?= Html::dropdownList('dvi[]', $theLine[4], $dviList, ['class'=>'field_dvi form-control']) ?></td>
                        <td><?= Html::textInput('gia[]', $theLine[5], ['class'=>'field_gia form-control text-right', 'readonly'=>'readonly']) ?></td>
                        <td class="text-nowrap">
                            <i data-day="<?= $cnt ?>" title="Copy dòng" class="add cursor-pointer fa fa-plus"></i>
                        </td>
                    </tr>
                        <?
                    }
                }
            } else {
                $theLine = [
                    $cnt, $date, $day['name'], 1, '', '',
                ];
                ?>
                    <tr data-id="<?= $id ?>" data-cnt="<?= $theLine[0] ?>" class="tr<?= $theLine[0] ?>">
                        <?= Html::hiddenInput('tt[]', $theLine[0]) ?>
                        <td class="text-muted text-center"><?= $theLine[0] ?></td>
                        <td><?= Html::textInput('ngay[]', $theLine[1], ['class'=>'field_ngay form-control text-center']) ?></td>
                        <td><?= Html::textInput('noidung[]', $theLine[2], ['class'=>'field_noidung form-control autogrow']) ?></td>
                        <td><?= Html::textInput('sl[]', $theLine[3], ['class'=>'field_sl form-control text-right']) ?></td>
                        <td><?= Html::dropdownList('dvi[]', $theLine[4], $dviList, ['class'=>'field_dvi form-control']) ?></td>
                        <td><?= Html::textInput('gia[]', $theLine[5], ['class'=>'field_gia form-control text-right', 'readonly'=>'readonly']) ?></td>
                        <td class="text-nowrap">
                            <i data-day="<?= $cnt ?>" title="Copy dòng" class="add cursor-pointer  fa fa-plus"></i>
                        </td>
                    </tr>
                <?
            }
        }
    }
}
?>
                    <? } // action edit ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <?= Html::submitButton('Ghi và in lịch xe', ['class'=>'btn btn-primary']) ?>
            hoặc <a href="/tours/r/<?= $theTourOld['id'] ?>">Thôi và quay lại tour</a>
        </div>
    </div>
</div>
<? ActiveForm::end(); ?>

<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Chương trình tour</h6>
        </div>
        <div class="table-responsive">
            <table id="tblCurrentProg" class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="10" class="text-center"></th>
                        <th class="no-padding-left">
                            Activity
                            (<a href="#" class="toggle-day-contents">Ẩn/hiện mọi ngày</a>)
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?
                $cnt = 0;
                foreach ($dayIdList as $dayId) {
                    foreach ($theTour['days'] as $day) {
                        if ($dayId == $day['id']) {
                            $dayDate = date('Y-m-d', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
                            $cnt ++;
                ?>
                    <tr class="tr-day" data-id="<?= $day['id'] ?>" id="ngay_<?= $day['id'] ?>">
                        <td class="text-center" width="20">
                            <span class="text-muted"><?= $cnt ?></span>
                        </td>
                        <td class="no-padding-left">
                            <div class="day-actions text-nowrap text-right pull-right position-right">
                            </div>
                            <span class="day-date"><?= Yii::$app->formatter->asDate($dayDate, 'php:j/n/Y D') ?></span>
                            <a class="day-name" href="/days/r/<?= $day['id'] ?>"><?= $day['name'] == '' ? '(no name)' : $day['name'] ?></a>
                            <em class="day-meals text-nowrap"><?= $day['meals'] ?></em>
                            <div class="day-content mt-20" style="display:none;">
                                <p>
                                    <span class="day-guides"><?= $day['guides'] == '' ? '' : '<i class="fa fa-user"></i> '.$day['guides'] ?></span>
                                    <span class="day-transport"><?= $day['transport'] == '' ? '' : '<i class="fa fa-car"></i> '.$day['transport']?></span>
                                </p>
                                <div class="day-body" id="day-body-<?= $day['id'] ?>">
                                <?
                                if (substr($day['body'], 0, 1) == '<') {
                                    echo $day['body'];
                                } else {
                                    echo $parser->parse($day['body']);
                                }
                                ?>
                                </div>
                            </div>    
                        </td>
                    </tr>
                <?
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?
$sql = 'SELECT CONCAT_WS(" #", name, abbr, amount) AS xyz FROM km_xe WHERE status="on" ORDER BY name';
$kmTable = \Yii::$app->db->createCommand($sql)->queryAll();
$js = 'var kmTable = [';
foreach ($kmTable as $line) {
    $js .= PHP_EOL.'"'.$line['xyz'].'",';
}
$js .= PHP_EOL.'];';

$js .= <<<'TXT'
var autocompleteOptions = {
    minLength: 0,
    source: kmTable,
    focus: function( event, ui ) {
        $(this).val(ui.item.label.split(' #')[0]);
        return false;
    },
    select: function( event, ui ) {
        var sl = ui.item.label.split(' #')[2];
        var noidung = ui.item.label.split(' #')[0];
        var tr = $(this).parent().closest('tr');

        $(this).val(noidung);
        tr.find(':input.field_sl').val(sl)
        tr.find(':input.field_dvi').val('km')
        tr.find(':input.field_gia').val(sl * $('#tourinlxform-giakm').val()).focus()
        return false;
    }
};

$('#tblLichxe').on('focus', '.field_noidung' ,function(){
    $(this).autocomplete(autocompleteOptions);
});
// $('.field_noidung').autocomplete(autocompleteOptions);

$('#tblLichxe').on('click', 'i.fa-plus', function(){
    var tr = $(this).parent().parent();
    var tr2 = tr.clone(false, false).insertAfter(tr);
    tr2.find('.field_ngay').val('')
    tr2.find('i.fa.fa-plus').removeClass('fa-plus').addClass('fa-trash-o').attr('title', 'Xoá')
});

$('#tblLichxe').on('click', 'i.fa-trash-o', function(){
    $(this).parent().parent().remove();
});

$('#tblLichxe').on('change', '.field_dvi', function(){
    if ($(this).val() == 'chang') {
        $(this).parent().parent().find('input:last').prop('readonly', false).focus();
    } else {
        $(this).parent().parent().find('input:last').val('').prop('readonly', true);
    }
});
$('#lxForm').submit(function() {
    $('tr.danger').remove();
});

// $('.autogrow').autogrow({vertical: true, horizontal: false});

$('a.toggle-day-contents').on('click', function(){
    if ($('#tblCurrentProg .day-content:visible').length > 0){
        $('.day-content').hide();
    } else {
        $('#tblCurrentProg .day-content').toggle();
    }
    return false;
});

$('#tblCurrentProg').on('click', '.day-name', function(){
    $(this).closest('td').find('.day-content').toggle();
    return false;
});

$('#tblLichxe').on('focus', ':input', function(){
    var cnt = $(this).closest('tr').data('cnt') - 1;
    $('#tblCurrentProg tbody .day-content:not(:eq('+cnt+'))').hide();
    $('#tblCurrentProg tbody tr:eq('+cnt+')').find('.day-content').show();
    return false;
});

var giaXeMb = {
    '4c':[4300,'',''],
    '7c':[4700,1150000,1100000],
    '7cl':[10000,'',''],
    '16c':[5200,1330000,1280000],
    '24c':[7200,1620000,1570000],
    '30cs':[7500,'',''],
    '35cs':[9200,'',''],
    '45cu':[11500,'',''],
    '45ch':[10500,'',''],
    '4l':[4100,'',''],
    '7l':[4500,1150000,1100000],
    '16l':[5000,1330000,1280000],
    '24l':[7000,1620000,1570000],
}

$('#tourinlxform-name').on('change', function(){
    var val = $(this).val();
    var text = $(this).find('option:selected').text();
    var gia = giaXeMb[val];
    if (typeof gia != 'undefined') {
        $('#tourinlxform-loaixe').val(text.split(' / ')[0]);
        $('#tourinlxform-chuxe').val(text.split(' / ')[1]);
        $('#tourinlxform-giakm').val(gia[0]);
        $('#tourinlxform-giadb').val(gia[1]);
        $('#tourinlxform-giatb').val(gia[2]);
    }
})


$('#tourinlxform-pax').on('change', function(){
    pax = $(this).val();
    $('#pcount').html(pax)
    $('#pdcount').html(pax * $('#dcount').html())
});
$('#tourinlxform-days').on('change', function(){
    nums = parseRange($(this).val());
    if (nums.length > 0) {
        $('#tblLichxe tbody tr').each(function(i){
            cnt = $(this).data('cnt');
            if ($.inArray(cnt, nums) === -1) {
                $(this).addClass('danger hidden-print');
            } else {
                $(this).removeClass('danger hidden-print');
            }
        });
    }
    $('#dcount').html(nums.length)
    $('#pdcount').html(nums.length * $('#pcount').html())
})
$('.copy_lx').click(function(){
    var lx_id = $(this).data('id');
    var parent_clicked = $(this).closest('.actions');
    $(parent_clicked).find('.wrapper_form').remove();
    var wrap_form = $('.copy-wrapper .wrapper_form').clone();
    $(wrap_form).removeClass('hidden').hide().data('id', lx_id);
    $(parent_clicked).append(wrap_form);
    $(wrap_form).slideDown('slow', function(e){
        $(this).show();
    });
    $(wrap_form).find('[name="tour_code"]').val('').focus();
    return false;
});
$(document).on('click', '.search_tour_code', function(){
    var input_code = $(this).closest('.wrapper_form').find('[name="tour_code"]');
    var tour_code = $(input_code).val();
    var lx_id = $(this).closest('.wrapper_form').data('id');
    if(tour_code != ''){
        $.ajax({
            method: 'GET',
            url: '/tours/copy_lx',
            data: {tour_code: tour_code, lx_id},
            dataType: 'json'
        }).done(function(response){
            console.log(response);
            if (response.err != '') {
                alert(response.err);
            }
        });
        if($(this).closest('.wrapper_form').length > 0) $(this).closest('.wrapper_form').slideUp(500);
    } else {
        $(input_code).focus();
    }
});

$(document).on('click', '.cancel_form', function(){
    if($(this).closest('.wrapper_form').length > 0) $(this).closest('.wrapper_form').slideUp(500);
});
TXT;

if ($action == 'edit') {
    // Danh dau nhung ngay khong can in
    $js .= "\n$('#tourinlxform-days').trigger('change');";
}

$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.ns-autogrow/1.1.6/jquery.ns-autogrow.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/stickyfloat/7.5.0/stickyfloat.min.js', ['depends'=>'yii\web\JqueryAsset']);
