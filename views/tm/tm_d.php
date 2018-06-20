<?php

use yii\helpers\Html;

include('_nm_inc.php');

Yii::$app->params['page_title'] = 'Confirm deletion: '.$theDay['title'];

?>
<div class="col-md-8">
    <form method="post" action="">
        <?= Html::hiddenInput('confirm', 'delete') ?>
        <div class="alert alert-danger">Are you sure you want to delete this?</div>
        <?= Html::submitButton('Delete', ['class'=>'btn btn-danger']) ?>
        or <?= Html::a('Cancel', '/nm') ?>
    </form>
</div>
<div class="col-md-4">
</div>
