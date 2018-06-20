<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model['name'];
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Campaigns', 'campaigns'],
	['View', URI],
];

include('campaigns__inc.php');

?>
<div class="col-lg-8">
	<p><strong>STATUS:</strong> <?=$model['status']?></p>
	<p><strong>START:</strong> <?=$model['start_dt']?> | <strong>END:</strong> <?=$model['end_dt']?></p>
	<p><strong>ABOUT:</strong></p>
	<div><?=$model['info']?></div>
</div>
