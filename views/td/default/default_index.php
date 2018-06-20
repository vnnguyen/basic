<?php

use yii\helpers\Html;

Yii::$app->params['page_title'] = 'CSDL hệ thống tuyến điểm tour';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tuyến điểm', 'td'],
];
$cacTuyen = [];

?>
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Bản đồ tuyến điểm</h6>
        </div>
        <div class="panel-body">
            <p class="text-center"><img alt="Mien bac/Dong bang 161114" src="/upload/td/tuyen_mb_dongbang_161114.png" class="table-responsive"><br><a href="/upload/td/tuyen_mb_dongbang_161114.png">Download</a></p>
            <p class="text-center"><img alt="Mien bac/Mien nui 161114" src="/upload/td/tuyen_mb_miennui_161114.png" class="table-responsive"><br><a href="/upload/td/tuyen_mb_miennui_161114.png">Download</a></p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <th width="">Country</th>
                    <th width="">Region</th>
                    <th width="">Starting point</th>
                    <th width="">Ending point</th>
                    <th width="">Km</th>
                    <th width="">Note</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($cacTuyen as $tuyen) { ?>
                <tr>
                    <td><?= $tuyen['id'] ?></td>
                    <td><?= $tuyen['id'] ?></td>
                    <td><?= $tuyen['id'] ?></td>
                    <td><?= $tuyen['id'] ?></td>
                    <td><?= $tuyen['id'] ?></td>
                    <td><?= $tuyen['id'] ?></td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>
