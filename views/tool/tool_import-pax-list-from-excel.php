<?php
use yii\helpers\Html;
use yii\helpers\Inflector;

Yii::$app->params['page_title'] = $theTour['op_code'].' - Copy/paste thông tin khách tour từ file';

Yii::$app->params['page_breadcrumbs'] = [
    ['Điều hành'],
];

Yii::$app->params['page_icon'] = 'users';
Yii::$app->params['body_class'] = 'sidebar-xs';


$arr_fix = [
    'gender' => 'Giới tính',
    'fname' => 'Họ',
    'lname' => 'Tên',
    'dob' => 'Ngày sinh',
    'country' => 'Quốc tịch',
    'passport' => 'Số hộ chiếu',
    'passport_ep' => 'Thời hạn hộ chiếu',
];

?>
<style>
.table-special td {padding:1px!important;}
.table-special th {padding:6px 1px!important;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <? if (!isset($_POST['data']) && !is_array($arr_results)) { ?>
            <form method="post" action="" enctype="multipart/form-data">
                <fieldset>
                    <legend>Bước 1 - Copy paste dữ liệu từ file Excel</legend>
                    <div class="form-group">
                        <label class="control-label">Dữ liệu Excel - Bắt đầu từ cột "STT" và kết thúc ở cột "Hạn Hộ chiếu"</label>
                        <?= Html::textArea('data', '', ['rows'=>20, 'class'=>'form-control']) ?>
                    </div>
                </fieldset>
                <?= Html::submitButton('Bắt đầu', ['class'=>'btn btn-primary']) ?>
                <?= Html::input('file', 'import', '', ['class' => 'file-styled-primary pull-right']);?>
            </form>
            <? } ?>

            <? if (is_array($results) && !is_array($arr_results)) {?>
            <form method="post" action="">
                <fieldset>
                    <legend>Bước 2 - Cập nhật thông tin "Danh sách khách" của tour <?= $theTour['op_code'] ?></legend>
                    <table class="mb-20 table table-borderless table-special">
                        <thead>
                            <th width="20">#</th>
                            <th><?= Html::dropDownList('order[]', 'gender', $arr_fix, ['class' => 'form-control']); ?></th>
                            <th><?= Html::dropDownList('order[]', 'fname', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'lname', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'dob', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'country', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'passport', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'passport_ep', $arr_fix, ['class' => 'form-control']) ?></th>
                        </thead>
                        <tbody>
                            <?
                            $cnt = 0;
                            foreach ($results as $result) {
                                if ($cnt == 6) {
                                    break;
                                }
                                $cnt ++;
                                $fname = ucwords(mb_strtolower(trim($result[1])));
                                $lname = ucwords(mb_strtolower(trim($result[2])));
                                $gender = (strpos(strtolower(trim($result[0])), 'madame') !== false)? 'female' : 'male';
                                ?>
                            <tr>
                                <td class="text-muted text-center"><?= $cnt ?></td>
                                <td><?= $gender ?></td>
                                <td><?= $fname ?></td>
                                <td><?= $lname ?></td>
                                <td><?= $result[3] ?></td>
                                <td><?= $result[4] ?></td>
                                <td><?= $result[5] ?></td>
                                <td><?= $result[6] ?></td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>

                    <p>Chú ý: <input type="checkbox" name="check_row_1" value="1"> Remove first row</p>
                    <?= Html::submitButton('Next', ['class'=>'btn btn-primary']) ?>
                    hoặc <a href="?">Huỷ và quay lại</a>
                </fieldset>
            </form>
            <? } ?>
            <?php
                if (is_array($arr_results)) {
                    if (count($arr_results) == 0) {
                        echo 'errors data!';
                    } else {
            ?>
            <form method="post" action="">
                <fieldset>
                    <legend>Bước 3 - Xác nhận ghi dữ liệu sau đây vào phần "Danh sách khách" của tour <?= $theTour['op_code'] ?></legend>
                    <table class="mb-20 table table-borderless table-special">
                        <thead>
                            <th width="20">#</th>
                            <?php
                                $data_head = array_keys(array_pop($arr_results));
                                foreach ($data_head as $v) {
                            ?>
                            <th><?= $arr_fix[$v]?></th>
                            <?php
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?
                            $cnt = 0;
                            foreach ($arr_results as $result) {//var_dump($result);die;
                                $cnt ++;
                                $fname = ucwords(mb_strtolower(trim($result['fname'])));
                                $lname = ucwords(mb_strtolower(trim($result['lname'])));
                                $gender = (strpos(strtolower(trim($result['gender'])), 'madame') !== false) ? 'female' : 'male';
                                ?>
                            <tr>
                                <td class="text-muted text-center"><?= $cnt ?></td>
                                <td><?= $gender ?></td>
                                <td><?= $fname ?></td>
                                <td><?= $lname ?></td>
                                <td><?= date('d-m-Y', strtotime($result['dob'])) ?></td>
                                <td><?= $result['country'] ?></td>
                                <td><?= $result['passport'] ?></td>
                                <td><?= date('d-m-Y', strtotime($result['passport_ep'])) ?></td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                    <?= Html::submitButton('Save', ['name' => 'save_btn', 'class'=>'btn btn-primary']) ?>
                </fieldset>
            </form>
            <?php
                    }
                }
            ?>
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
$('[name="paid[]"]').trigger('change');
$('[name="paid[]"]').on('change', function(){
    var val = $(this).val();
    var tr = $(this).closest('tr');
    if (val == '') {
        tr.removeClass('warning').addClass('danger');
    }
    if (val == 'yes') {
        tr.removeClass('danger warning');
    }
    if (val == 'no') {
        tr.removeClass('danger').addClass('warning');
    }
});
TXT;

$this->registerJs($js);