<?php
use yii\helpers\Html;

$db = new fDatabase('mysql', 'amica_my', 'amica_my', '2w#E4r%T', 'localhost');
define('myID', Yii::$app->user->id);
define('myName', Yii::$app->user->identity->name);

$getM = fRequest::get('m', 'string', date('m'), true);
$getY = fRequest::get('y', 'string', date('Y'), true);

// Case count of months
$q = $db->query('SELECT MONTH(ao) AS m, YEAR(ao) AS y, COUNT(*) AS total FROM at_cases WHERE owner_id=%i GROUP BY SUBSTRING(ao, 1, 7)', myID);
$mx = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$ymc = array();

foreach ($mx as $m) {
    $ymc[$m['y']][$m['m']] = $m['total'];
}

// Cases of selected month
$q = $db->query('SELECT *, status, deal_status, owner_id AS seid FROM at_cases WHERE MONTH(ao)=%s AND YEAR(ao)=%s HAVING seid=%i ORDER BY ao DESC LIMIT 1000', $getM, $getY, myID);
$cx = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

/* ======================= Bảng 2 ====================== */
// Won cases of selected month
$q = $db->query('SELECT *, status, deal_status, owner_id AS seid FROM at_cases WHERE deal_status=%s AND MONTH(deal_status_date)=%s AND YEAR(deal_status_date)=%s HAVING seid=%i ORDER BY deal_status_date DESC LIMIT 1000', 'won', $getM, $getY, myID);
$wcx = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

/* ======================= Bảng 3 ====================== */
// Danh sách người bán hàng nhận hồ sơ trong tháng
$q = $db->query('SELECT u.fname, u.lname, u.email, u.id, COUNT(*) AS total FROM persons u, at_cases c WHERE owner_id=u.id AND MONTH(ao)=%i AND YEAR(ao)=%i GROUP BY u.id ORDER BY lname', $getM, $getY);
$allSellers = $q->fetchAllRows();
// Tất cả hồ sơ bán thêm thành công trong tháng
$q = $db->query('SELECT status, deal_status, owner_id FROM at_cases WHERE deal_status=%s AND MONTH(deal_status_date)=%s AND YEAR(deal_status_date)=%s LIMIT 1000', 'won', $getM, $getY);
$allWonCases = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
foreach ($allWonCases as $c) {
    if (isset($newWon[$c['owner_id']])) {
        $newWon[$c['owner_id']] ++;
    } else {
        $newWon[$c['owner_id']] = 1;
    }
}

// Tất cả hồ sơ đóng k thành công trong tháng
$q = $db->query('SELECT id, name, closed, status, deal_status, owner_id FROM at_cases WHERE status=%s AND deal_status!=%s AND MONTH(closed)=%s AND YEAR(closed)=%s LIMIT 1000', 'closed', 'won', $getM, $getY);
$allLostCases = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
foreach ($allLostCases as $c) {
    if (isset($newLost[$c['owner_id']])) {
        $newLost[$c['owner_id']] ++;
    } else {
        $newLost[$c['owner_id']] = 1;
    }
}

$alk = [];
foreach ($allLostCases as $lk) {
    if ($lk['owner_id'] == USER_ID) {
        $alk[] = $lk;
    }
}
$this->title = 'Thống kê bán hàng tháng '.$getM.'/'.$getY.' - '.myName;
$this->params['icon'] = 'bar-chart-o';
$this->params['breadcrumb'] = [
    ['Me', 'me'],
    ['My reports', 'me/reports'],
];
$this->params['actions'] = [
    [
        ['label'=>'Sales results', 'icon'=>'money', 'class'=>'text-success', 'link'=>'me/sales-results'],
    ],
];
?>
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Các tháng được giao hồ sơ, con số là số hồ sơ được giao</h6>
        </div>
        <table class="table table-xxs table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Năm / Tháng</th>
                    <? for ($m = 1; $m <= 12; $m ++) { ?>               
                    <th class="text-center">Th <?=$m?></th>
                    <? } ?>
                    <th class="text-center">Tổng số</th>
                </tr>
            </thead>
            <tbody>
                <? for ($y = date('Y'); $y >= 2010; $y --) { ?>
                <tr>
                    <td class="text-center"><?=$y?></td>
                    <? for ($m = 1; $m <= 12; $m ++) { ?>
                    <td class="text-center"><?=isset($ymc[$y][$m]) ? Html::a($ymc[$y][$m], DIR.URI.'?m='.$m.'&y='.$y, ['class'=>$y == $getY && $m == $getM ? 'text-bold text-pink' : '']) : ''?></td>
                    <? } ?>
                    <td class="text-center">-</td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-tabs nav-tabs-bottom" data-tabs="tabs">
                <li class="active"><a data-toggle="tab" href="#assigned">HS nhận trong tháng (<?= count($cx)?>)</a></li>
                <li><a data-toggle="tab" href="#won">HS bán được thêm trong tháng (<?= count($wcx) ?>)</a></li>
                <li><a data-toggle="tab" href="#lost">HS đóng không thành công trong tháng (<?= count($alk) ?>)</a></li>
                <li><a data-toggle="tab" href="#allwon">Tổng hợp sellers</a></li>
            </ul>
            <div id="tab-content" class="tab-content">
                <div id="assigned" class="active tab-pane">
                    <table class="table table-xxs table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">STT</th>
                                <th>Nhận</th>
                                <th>Tên hồ sơ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if (empty($cx)) { ?><tr><td colspan="5">Không có hồ sơ trong tháng này</td></tr><? } ?>
                            <? $cnt = 0; foreach ($cx as $c) { $cnt ++; ?>
                            <tr>
                                <td class="text-center"><?=$cnt?></td>
                                <td><?= date('j/n/Y', strtotime($c['ao'])) ?></td>
                                <td>
                                    <?= Html::a($c['name'], '@web/cases/r/'.$c['id'])?>
                                    <? if ($c['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
                                    <? if ($c['deal_status'] == 'lost' || $c['status'] == 'deleted') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
                                </td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
                <div id="won" class="tab-pane">
                    <table class="table table-xxs table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">STT</th>
                                <th>Nhận</th>
                                <th>Tên hồ sơ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if (empty($wcx)) { ?><tr><td colspan="5">Không có hồ sơ bán được trong tháng này</td></tr><? } ?>
                            <? $cnt = 0; foreach ($wcx as $c) { $cnt ++; ?>
                            <tr>
                                <td class="text-center"><?=$cnt?></td>
                                <td><?= date('j/n/Y', strtotime($c['ao'])) ?></td>
                                <td>
                                    <?=Html::a($c['name'], '@web/cases/r/'.$c['id'])?>
                                    <? if ($c['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
                                    <? if ($c['deal_status'] == 'lost' || $c['status'] == 'deleted') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
                                </td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
                <div id="lost" class="tab-pane">
                    <h3>BẢNG 3: Các hồ sơ đóng và bán hàng không thành công</h3>
                    <table class="table table-xxs table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">STT</th>
                                <th>Đóng</th>
                                <th>Tên hồ sơ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if (empty($allLostCases)) { ?><tr><td colspan="5">Không có hồ sơ</td></tr><? } ?>
                            <? $cnt = 0; foreach ($allLostCases as $c) { if ($c['owner_id'] == USER_ID) { $cnt ++; ?>
                            <tr>
                                <td class="text-center"><?=$cnt?></td>
                                <td><?= date('j/n/Y', strtotime($c['closed'])) ?></td>
                                <td>
                                    <?= Html::a($c['name'], '@web/cases/r/'.$c['id']) ?>
                                    <? if ($c['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
                                    <? if ($c['deal_status'] == 'lost' || $c['status'] == 'deleted') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
                                </td>
                            </tr>
                            <? } } ?>
                        </tbody>
                    </table>
                </div>
                <div id="allwon" class="tab-pane">
                    <table class="table table-xxs table-bordered">
                        <thead>
                        <tr>
                            <th width="5%">STT</th>
                            <th>Người bán</th>
                            <th class="hidden-xs"></th>
                            <th>Số HS nhận trong tháng</th>
                            <th>Số HS bán được thêm</th>
                            <th>Số HS đóng trong tháng (LOST)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? $cnt = 0; foreach ($allSellers as $aSeller) { $cnt ++; ?>
                        <tr>
                            <td><?=$cnt?></td>
                            <td><?=Html::a($aSeller['fname'].' '.$aSeller['lname'], '@web/users/r/'.$aSeller['id'])?></td>
                            <td class="hidden-xs"><?=$aSeller['email']?></td>
                            <td class="text-center"><?=$aSeller['total']?></td>
                            <td class="text-center"><?=isset($newWon[$aSeller['id']]) ? $newWon[$aSeller['id']] : ''?></td>
                            <td class="text-center"><?=isset($newLost[$aSeller['id']]) ? $newLost[$aSeller['id']] : ''?></td>
                        </tr>
                        <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>