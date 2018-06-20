<?
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_kase_inc.php');

$this->title = $theCase['name'];

$this->params['breadcrumb'][] = ['View', 'cases/r/'.$theCase['id']];

?>
<div class="col-md-8">
	<?= $postedHtml ?>
</div>