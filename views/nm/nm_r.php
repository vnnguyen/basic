<?php
use yii\helpers\Html;

include('_nm_inc.php');
Yii::$app->params['page_title'] = $theDay['title'];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Day info</h6>
        </div>
        <table class="table table-condensed table-bordered">
            <tbody>
                <tr>
                    <th>Update</th>
                    <td><?= $theDay['updatedBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theDay['updated_dt'], 'php:j/n/Y (l) H:i') ?></td>
                </tr>
                <tr>
                    <th>Nội dung</th>
                    <td><?
                if ($theDay['image'] != '') echo '<img style="float:right; margin:0 0 20px 20px;" src="/upload/devis-days/'.$theDay['image'].'" width="150" height="113" />';
                echo str_replace(['class=', 'style='], ['c=', 's='], $theDay['body']); ?>
                    </td>
                </tr>
                <tr>
                    <th>Tags</th>
                    <td><?= $theDay['tags'] ?></td>
                </tr>
                <tr><th>Ăn</th><td><?=$theDay['meals']?></td></tr>
                <tr><th>Tàu xe</th><td><?=$theDay['transport']?></td></tr>
                <tr><th class="text-nowrap" ">Hướng dẫn</th><td><?=$theDay['guides']?></td></tr>
                <tr><th>Note</th><td><?=$theDay['note']?></td></tr>
            </tbody>
        </table>
    </div>
</div>
<? if (!empty($theDays)) { ?>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Tour segments</h6>
        </div>
        <div class="panel-body">
            <ol>
                <? foreach ($theDays as $day) { ?>
                <li><?= $day['title'] ?> <em><?= $day['meals'] ?></em></li>
                <? } ?>
            </ol>
        </div>
    </div>
</div>
<? } ?>