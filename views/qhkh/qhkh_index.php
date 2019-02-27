<?php
use yii\helpers\Html;

include('_qhkh_inc.php');

Yii::$app->params['page_title'] = 'Không gian nhóm QHKH';

?>
<div class="col-md-4"><?= Html::a('Chốt tour', '/qhkh/chot-tour') ?></div>
<div class="col-md-4"><?= Html::a('Quỹ QHKH', '/qhkh/quy-qhkh') ?></div>
<div class="col-md-4"><?= Html::a('Quy trình và Thư mẫu', '/qhkh/quy-trinh-thu-mau') ?></div>