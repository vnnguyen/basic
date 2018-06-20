<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Nhập thông tin thanh toán invoice qua Excel';

Yii::$app->params['page_breadcrumbs'] = [
    ['Kế toán'],
];

Yii::$app->params['page_icon'] = 'calculator';
Yii::$app->params['body_class'] = 'sidebar-xs';


/*
- Hình thức thanh toán
- Tour code
- Ref. hoá đơn
- Số tiền được thanh toán
- Loại tiền
- Tỉ giá với VND
- Ngày hạch toán
- Note
Onepay]|[F1702055]|[F170205503]|[3,737,364]|[VND]|[1]|[27/12/2016]|[Note1
*/
$arr_fix = [
    'method' => 'Hình thức thanh toán',
    'tourcode' => 'Tour',
    'invoice' => 'Hóa đơn',
    'amount' => 'Số tiền TT',
    'currency' => 'Loại tiền',
    'xrate' => 'Tỉ giá với VND',
    'payment_dt' => 'Ngày TT',
    'note' => 'note',
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <? if (!isset($_POST['data']) && !is_array($arr_results) && !is_array($results)) { ?>
            <form method="post" action="" enctype="multipart/form-data">
                <fieldset>
                    <legend>Bước 1 - Copy paste dữ liệu từ file Excel</legend>
                    <div class="form-group">
                        <label class="control-label">Dữ liệu Excel - Bắt đầu từ cột "Tour code" và kết thúc ở cột "Ngày hạch toán"</label>
                        <?= Html::textArea('data', '', ['rows'=>20, 'class'=>'form-control']) ?>
                    </div>
                </fieldset>
                <?= Html::submitButton('Bắt đầu', ['class'=>'btn btn-primary']) ?>
                <?= Html::input('file', 'import', '', ['class' => 'file-styled-primary pull-right']);?>
            </form>
            <? } ?>

            <? if (is_array($results) && !is_array($arr_results)) {
            ?>
            <form method="post" action="">
                <fieldset>
                    <legend>Bước 2 - Xác nhận các trường dữ liệu sau đây</legend>
                    <table class="mb-20 table table-condensed table-bordered">
                        <thead>
                            <th width="20">#</th>
                            <th><?= Html::dropDownList('order[]', 'method', $arr_fix, ['class' => 'form-control']); ?></th>
                            <th><?= Html::dropDownList('order[]', 'tourcode', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'invoice', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'amount', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'currency', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'xrate', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'payment_dt', $arr_fix, ['class' => 'form-control']) ?></th>
                            <th><?= Html::dropDownList('order[]', 'note', $arr_fix, ['class' => 'form-control']) ?></th>
                        </thead>
                        <tbody>
                            <?
                            $cnt = 0;
                            foreach ($results as $result) {
                                if ($cnt == 6) {
                                    break;
                                }
                                $cnt ++;
                                $amount = trim(str_replace(',', '', $result[3]));
                                $dateParts = explode('/', $result[6]);
                                $date = !isset($dateParts[2]) ? '' : implode('-', [trim($dateParts[2]), trim($dateParts[1]), trim($dateParts[0])]);
                                if ($date != '') {
                            ?>
                            <tr>
                                <td class="text-muted text-center"><?= $cnt ?></td>
                                <td><?= $result[0] ?></td>
                                <td><?= $result[1] ?></td>
                                <td><?= $result[2] ?></td>
                                <td class="text-right text-nowrap">
                                    <?= number_format($amount, intval($amount) == $amount ? 0 : 2) ?>
                                </td>
                                <td><span class="text-muted"><?= $result[4] ?></span></td>
                                <td><?= $result[5] ?></td>
                                <td><?= date('j/n/Y', strtotime($date)) ?></td>
                                <td><?= trim($result[7]) ?></td>
                            </tr>
                            <?
                        } // if not blank date
                    // $sql = 'INSERT INTO colliers_sales_data (updated_dt, updated_by, data, source_file) VALUES (NOW(), 1, :data, :source)';
                    // Yii::$app->db->createCommand($sql, [
                    //     ':data'=>str_replace(chr(9), ']|[', $line),
                    //     ':source'=>$source,
                    // ])->execute();
                            }
                            ?>
                        </tbody>
                    </table>
                    <p>Chú ý: <input type="checkbox" name="check_row_1" value="1"> Remove first row</p>
                    <?= Html::submitButton('Next', ['class'=>'btn btn-primary']) ?>
                    hoặc <?= Html::submitButton('Huỷ và quay lại', ['name' => 'cancel_next', 'class' => 'btn btn-default']) ?>
                </fieldset>
            </form>
            <? } ?>
            <? if (is_array($arr_results)) {
                    if (count($arr_results) == 0) {
                        echo 'errors data!';
                    } else {
            ?>
            <form method="post" action="">
                <fieldset>
                    <legend>Bước 3 - Ghi thông tin</legend>
                    <table class="mb-20 table table-condensed table-bordered">
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
                            foreach ($results as $result) {
                                if ($cnt == 6) {
                                    break;
                                }
                                $cnt ++;
                                $amount = trim(str_replace(',', '', $result[3]));
                                $dateParts = explode('/', $result[6]);
                                $date = !isset($dateParts[2]) ? '' : implode('-', [trim($dateParts[2]), trim($dateParts[1]), trim($dateParts[0])]);
                                if ($date != '') {
                            ?>
                            <tr>
                                <td class="text-muted text-center"><?= $cnt ?></td>
                                <td><?= $result[0] ?></td>
                                <td><?= $result[1] ?></td>
                                <td><?= $result[2] ?></td>
                                <td class="text-right text-nowrap">
                                    <?= number_format($amount, intval($amount) == $amount ? 0 : 2) ?>
                                </td>
                                <td><span class="text-muted"><?= $result[4] ?></span></td>
                                <td><?= $result[5] ?></td>
                                <td><?= date('j/n/Y', strtotime($date)) ?></td>
                                <td><?= trim($result[7]) ?></td>
                            </tr>
                            <?
                        } // if not blank date
                    // $sql = 'INSERT INTO colliers_sales_data (updated_dt, updated_by, data, source_file) VALUES (NOW(), 1, :data, :source)';
                    // Yii::$app->db->createCommand($sql, [
                    //     ':data'=>str_replace(chr(9), ']|[', $line),
                    //     ':source'=>$source,
                    // ])->execute();
                            }
                            ?>
                        </tbody>
                    </table>
                    <?= Html::submitButton('Save', ['name' => 'save_btn', 'class'=>'btn btn-primary']) ?>
                    hoặc <?= Html::submitButton('Huỷ và quay lại', ['name' => 'cancel_save', 'class' => 'btn btn-default']) ?>
                </fieldset>
            </form>
            <?
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