<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_qhkh_inc.php');

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'qhkh/service-plus?action=add', 'active'=>Yii::$app->request->get('action') == 'add'],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'qhkh/service-plus?action=edit&id='.$theService['id'], 'active'=>Yii::$app->request->get('action') == 'edit'],
        ['icon'=>'trash-o', 'title'=>Yii::t('app', 'Delete'), 'class'=>'text-danger', 'link'=>'qhkh/service-plus?action=delete&id='.$theService['id'], 'active'=>Yii::$app->request->get('action') == 'delete'],
    ],
];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <p>Confirm delete?</p>
            <form method="post" action="">
                <input type="hidden" name="confirm" value="delete">
                <button type="submit" class="btn btn-danger">Delete service plus</button>
            </form>
        </div>
    </div>
</div>
