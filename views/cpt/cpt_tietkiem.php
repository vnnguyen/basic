<?

use yii\helpers\Html;
use \common\models\Cpt;

Yii::$app->params['page_title'] = 'Các chi phí tour tiết kiệm theo tháng tour kết thúc';

?>
<div class="col-md-12">
    <form class="form-inline mb-1em">
        <?= Html::textInput('month', $month, ['class'=>'form-control', 'placeholder'=>'Tháng yyyy-mm']) ?>
        <?= Html::textInput('operator', $operator, ['class'=>'form-control', 'placeholder'=>'Điều hành (chưa OK)']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '?') ?>
    </form>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Tour code</th>
                        <th class="text-center">Bắt đầu</th>
                        <th class="text-center">Kết thúc</th>
                        <th>Tên khoản tiết kiệm</th>
                        <th class="text-right">Chi phí ban đầu</th>
                        <th class="text-right">Chi phí thực tế</th>
                        <th class="text-right">Tiết kiệm được</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <? if (empty($theTkx)) { ?>
                    <tr>
                        <td colspan="9">Chưa có thông tin</td>
                    </tr>
                    <? } else { ?>
                    <?
                    $tongTk = [];
                    $cnt = 0; foreach ($theTkx as $tk) { $cnt ++; ?>
<?
$sql1 = 'SELECT cpt_id FROM cpt_tietkiem_link WHERE tkiem_id=:id';
$cptIdList = \Yii::$app->db->createCommand($sql1, [':id'=>$tk['id']])->queryColumn();
$cpx = Cpt::find()
    ->select(['dvtour_id', 'dvtour_name', 'qty', 'price', 'unitc'])
    ->where(['dvtour_id'=>$cptIdList])
    ->asArray()
    ->all();

?>

                    <tr>
                        <td class="text-center text-muted"><?= $cnt ?></td>
                        <? foreach ($theTours as $tour) { if ($tour['id'] == $tk['tour_id']) { ?>
                        <td><?= Html::a($tour['op_code'], '/products/op/'.$tk['tour_id']) ?></td>
                        <td class="text-center"><?= date('j/n/Y', strtotime($tour['day_from'])) ?></td>
                        <td class="text-center"><?= date('j/n/Y', strtotime('+'.($tour['day_count'] - 1).' days', strtotime($tour['day_from']))) ?></td>
                        <? } } ?>
                        <td>
                            <span title="<?= $tk['updated_dt'] ?> by <?= $tk['updated_by'] ?>" href="#"><?= $tk['name'] ?></span>
                            <? if ($tk['note'] != '') { ?><i title="<?= $tk['note'] ?>" class="fa fa-info-circle"></i><? } ?>
                        </td>
                        <td class="text-right text-nowrap" title="<?= $tk['qty'] ?> &times; <?= $tk['amount'] ?>"><?= number_format($tk['qty'] * $tk['amount']) ?> <span class="text-muted"><?= $tk['currency'] ?></span></td>
                        <?
                        $cpThucte = 0;
                        $cpNote = 'CHI TIẾT:';
                        foreach ($cpx as $cp) {
                            $cpThucte += $cp['qty'] * $cp['price'];
                            $cpNote .= "\n".$cp['dvtour_name'].' ('.number_format($cp['qty']).' x '.number_format($cp['price']).') = '.number_format($cp['qty'] * $cp['price']);
                        }
                        if (!isset($tongTk[$cp['unitc']])) {
                            $tongTk[$cp['unitc']] = $cpThucte;
                        } else {
                            $tongTk[$cp['unitc']] += $cpThucte;
                        }

                        ?>
                        <td class="text-right text-nowrap" title="<?= $cpNote ?>"><?= number_format($cpThucte) ?> <span class="text-muted"><?= $tk['currency'] ?></span></td>
                        <td class="text-right text-nowrap"><?= number_format($tk['qty'] * $tk['amount'] - $cpThucte) ?> <span class="text-muted"><?= $tk['currency'] ?></span></td>
                        <td class="text-muted"><?= Yii::$app->formatter->asRelativetime($tk['updated_dt']) ?></td>
                    </tr>
                    <? } ?>
                    <tr>
                        <th colspan="7" class="text-right">Tổng</th>
                        <th class="text-right">
                            <? foreach ($tongTk as $tien=>$tong) { ?>
                            <div><?= number_format($tong) ?> <span class="text-muted"><?= $tien ?></span></div>
                            <? } ?>
                        </th>
                        <th></th>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>