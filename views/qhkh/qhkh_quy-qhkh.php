<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_qhkh_inc.php');

Yii::$app->params['page_title'] = 'Quỹ Quan hệ khách hàng';

$tongThu = 0;
foreach ($thuQuyQhkh as $thu) {
    $tongThu += $thu['tong'];
}

$chiQuy = [];

$sql = 'SELECT qty, price, unitc, SUBSTRING(dvtour_day,1,7) AS mo FROM cpt WHERE crfund="yes"';
$theCptx = Yii::$app->db->createCommand($sql)->queryAll();


foreach ($thuQuyQhkh as $thu) {
    foreach ($theCptx as $cpt) {
        if ($cpt['mo'] == $thu['thang']) {
            if (isset($chiQuy[$cpt['mo']][$cpt['unitc']])) {
                $chiQuy[$cpt['mo']][$cpt['unitc']] += $cpt['qty'] * $cpt['price'];
            } else {
                $chiQuy[$cpt['mo']][$cpt['unitc']] = $cpt['qty'] * $cpt['price'];
            }
        }
    }
}
// \fCore::expose($chiQuy); exit;

?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Thông tin</h6>
        </div>
        <div class="panel-body">
            <p>INFO: Quỹ này được lập từ cuối năm 2016. Theo đó bộ phận QHKH trích từ các tour số tiền như sau làm quỹ để phục vụ các chi tiêu liên quan đến bộ phận:</p>
            <ul>
                <li>Với các tour tháng 11 và 12/2016: trích mỗi tour mỗi khách 10 USD vào quỹ</li>
                <li>Với các tour từ tháng 1/2017 về sau: tour ngắn hơn 5 ngày trích mỗi tour mỗi khách 5 USD, tour dài từ 5 ngày trở lên trích mỗi tour 10 USD mỗi khách vào quỹ</li>
            </ul>
            <p>Nguyên tắc thu:</p>
            <ul>
                <li>Thu theo đầu khách, không phân biệt độ tuổi của khách</li>
                <li>Số đầu khách tính tại thời điểm confirm tour để tính số tiền trích vào quỹ</li>
                <li>(ĐỢI CFM VỚI QHKH) Số khách hay số ngày có thay đổi trong quá trình đi tour cũng không tính lại số tiền</li>
            </ul>
            <p>Trách nhiệm ghi thu chi trên IMS:</p>
            <ul>
                <li>Bán hàng ghi số tiền thu vào quỹ tại thời điểm confirm booking của mình</li>
                <li>Điều hành ghi các khoản chi từ quỹ vào chi phí tour nếu chi cho riêng tour đó</li>
                <li>QHKH ghi các khoản chi từ quỹ vào bảng chi phí chung nếu chi cho công việc khác</li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Thống kê thu chi</h6>
        </div>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <th>Tháng</th>
                <th class="text-nowrap text-right">Số thu</th>
                <th class="text-nowrap text-right">Số chi</th>
                <th width="80%"></th>
            </thead>
            <tbody>
                <tr class="warning">
                    <th>Tổng</th>
                    <th class="text-nowrap text-right"><?= number_format($tongThu) ?> USD</th>
                    <th class="text-nowrap text-right">-</th>
                    <td></td>
                </tr>
                <?
                foreach ($thuQuyQhkh as $thu) {
                    ?>
                <tr>
                    <td class="text-nowrap text-center"><?= $thu['thang'] ?></td>
                    <td class="text-nowrap text-right"><?= Html::a(number_format($thu['tong']), '?action=view-month&month='.$thu['thang']) ?> <span class="text-muted">USD</span></td>
                    <td class="text-nowrap text-right">
                        <?
                        foreach ($chiQuy as $thang=>$chi) {
                            if ($thang == $thu['thang']) {
                                foreach ($chi as $tien=>$tong) {
                                    echo '<div>', Html::a(number_format($tong), '/cpt?crfund=yes&dvtour='.$thang), ' <span class="text-muted">', $tien, '</span></div>';
                                }
                            }
                        }
                        ?>
                    </td>
                    <td></td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>

<?
/*

?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
    <form method="get" action="" class="well well-sm form-inline">
        <select class="form-control w-auto" name="language">
            <option value="all">All languages</option>
            <? foreach ($languageList as $k => $v) { ?>
            <option value="<?=$k?>" <?=$getLanguage == $k ? 'selected="selected"' : ''?>><?=$v?></option>
            <? } ?>
        </select>
        <select class="form-control w-auto" name="type">
            <option value="private">Private tour</option>
            <option value="agent" <?=$getType == 'agent' ? 'selected="selected"' : ''?>>Tour hãng</option>
            <option value="vpc" <?=$getType == 'vpc' ? 'selected="selected"' : ''?>>VPC tour</option>
            <option value="tcg" <?=$getType == 'tcg' ? 'selected="selected"' : ''?>>TCG tour</option>
        </select>
        <select name="ub" class="form-control w-auto">
            <option value="all">Updated by</option>
            <option value="<?=Yii::$app->user->id?>" <?=$getUb == Yii::$app->user->id ? 'selected="selected"' : ''?>>Tôi (<?= Yii::$app->user->identity->name ?>)</option>
            <? foreach ($ubList as $ub) { if (Yii::$app->user->id != $ub['id']) { ?>
            <option value="<?= $ub['id'] ?>" <?=$getUb == $ub['id'] ? 'selected="selected"' : ''?>><?=$ub['lname']?>, <?=$ub['email']?></option>
            <? } } ?>
        </select>
        <select class="form-control w-auto" name="month">
            <option value="all">Start date</option>
            <? foreach ($startDateList as $thu) { ?>
            <option value="<?= $thu['ym'] ?>" <?= $getMonth == $thu['ym'] ? 'selected="selected"' : ''?>><?= $thu['ym'] ?></option>
            <? } ?>
        </select>
        <select class="form-control w-auto" name="proposal">
            <option value="all">Trạng thái bán</option>
            <option value="yes" <?=$getProposal == 'yes' ? 'selected="selected"' : ''?>>Đang bán</option>
            <option value="no" <?=$getProposal == 'no' ? 'selected="selected"' : ''?>>Chưa bán</option>
        </select>
        <select class="form-control w-auto" name="days">
            <option value="all">Days</option>
            <option value="10" <?=$getDays == '10' ? 'selected="selected"' : ''?>>1-10 ngày</option>
            <option value="20" <?=$getDays == '20' ? 'selected="selected"' : ''?>>11-20 ngày</option>
            <option value="30" <?=$getDays == '30' ? 'selected="selected"' : ''?>>21-30 ngày</option>
            <option value="31" <?=$getDays == '31' ? 'selected="selected"' : ''?>>Trên 30 ngày</option>
        </select>
        <input type="text" class="form-control w-auto" name="name" placeholder="Search name or tag" value="<?=fHTML::encode($getName)?>" />
        <select class="form-control w-auto" name="order">
            <option value="uo">Order by: Updated</option>
            <option value="day_from" <?=$getOrder == 'day_from' ? 'selected="selected"' : ''?>>Order by: Tour date</option>
            <option value="days" <?=$getOrder == 'days' ? 'selected="selected"' : ''?>>Order by: Days</option>
            <option value="pax" <?=$getOrder == 'pax' ? 'selected="selected"' : ''?>>Order by: Pax</option>
            <option value="title" <?=$getOrder == 'title' ? 'selected="selected"' : ''?>>Order by: Name</option>
        </select>
        <select class="form-control w-auto" name="sort">
            <option value="desc">Descending</option>
            <option value="asc" <?=$getSort == 'asc' ? 'selected="selected"' : ''?>>Ascending</option>
        </select>
        <button type="submit" class="btn btn-primary">Go</button>
        <?= Html::a('Reset', 'ct') ?>
    </form>
    <div class="table-responsive">
        <table class="table table-striped table-condensed table-bordered table-hover">
            <thead>
                <tr>
                    <th width="100" class="text-center">Lang/Type</th>
                    <th>Name</th>
                    <th width="80">Start date</th>
                    <th width="40" class="text-center">Days</th>
                    <th width="40" class="text-center">Pax</th>
                    <th>Price</th>
                    <th>Updated by</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($models as $thu) { ?>
                <tr>
                    <td class="text-muted text-center"><?= strtoupper($thu['language']) ?> | <?= strtoupper($thu['offer_type']) ?></td>
                    <td>
                        <i class="fa fa-file-text-o popovers pull-right text-muted"
                            data-trigger="hover"
                            data-title="<?= $thu['title'] ?>"
                            data-placement="left"
                            data-html="true"
                            data-content="
                        <?
                        $dayIds = explode(',', $thu['day_ids']);
                        if (count($dayIds) > 0) {
                            $cnt = 0;
                            foreach ($dayIds as $id) {
                                foreach ($thu['days'] as $day) {
                                    if ($day['id'] == $id) {
                                        $cnt ++;
                                        echo '<strong>', $cnt, ':</strong> ', $day['name'], ' (', $day['meals'], ')<br>';
                                    }
                                }
                            }
                        }
                        ?>
                        "></i>
                        <? if ($thu['offer_count'] == 0) { ?><?= Html::a('+', 'ct/propose/'.$thu['id'], ['title'=>'+ New proposal']) ?><? } else { ?>
                        <?= !isset($thu['cases'][0]) ?: Html::a('<i class="fa fa-briefcase"></i>', 'cases/r/'.$thu['cases'][0]['id'], ['class'=>'text-warning', 'title'=>'View case: '.$thu['cases'][0]['name']]) ?>
                        <?= Html::a($thu['tour']['code'], 'tours/r/'.$thu['tour']['id'], ['title'=>'View tour: '.$thu['tour']['name'], 'style'=>'background-color:#ffc; color:#060; padding:0 5px;']) ?>
                        <? } ?>
                        <?= Html::a($thu['title'], 'ct/r/'.$thu['id']) ?>
                        <span class="text-muted"><?= $thu['about'] ?></span>
                    </td>
                    <td><?= $thu['day_from'] ?></td>
                    <td class="text-center"><?= count($thu['days']) ?></td>
                    <td class="text-center"><?= $thu['pax'] ?></td>
                    <td class="text-right"><?= number_format($thu['price'], 0) ?> <span class="text-muted"><?= $thu['price_unit'] ?></span></td>
                    <td><?= Html::a($thu['updatedBy']['name'], 'users/r/'.$thu['updatedBy']['id']) ?></td>
                    <td>
                        <?= Html::a('<i class="fa fa-edit"></i>', 'ct/u/'.$thu['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', 'ct/d/'.$thu['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <? if ($pages->totalCount > $pages->limit) { ?>
    <div class="text-center">
    <?=LinkPager::widget([
        'pagination' => $pages,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]);?>
    </div>
    <? } ?>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>*/