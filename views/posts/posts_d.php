<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_posts_inc.php');

Yii::$app->params['page_title'] = 'Confirm deletion: '.$thePost['title'];

?>
<div class="col-md-8">
    <div class="alert alert-danger">
        <p><i class="fa fa-fw fa-warning"></i> You're about to delete a message.</p>
        <p>All related attachments and replies will also be deleted. This action is cannot be undone.</p>
        <p>Delete message now?</p>
    </div>
    <form method="post" action="" class="form-inline">
        <input type="hidden" name="confirm" value="delete">
        <button class="btn btn-danger" type="submit">Delete message</button>
        or <?= Html::a('Cancel', $thePost['rtype'].'s/r/'.$thePost['rid']) ?>
    </form>
</div>