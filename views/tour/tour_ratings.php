<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

$this->title = 'Edit tour ratings: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['Ratings'],
];

$ratingList = [];
for ($i = 0; $i <= 10; $i ++) {
    $ratingList[$i * 10] = $i;
    if ($i != 10) {
        $ratingList[$i * 10 + 5] = $i + 0.5;
    }
}

?>
<div class="col-md-8">
    <? $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theForm, 'tour_points')->dropdownList($ratingList, ['class'=>'form-control'])->label('Số điểm khách đánh giá') ?></div>
    </div>  
    <div><?= Html::submitButton('Save tour', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>