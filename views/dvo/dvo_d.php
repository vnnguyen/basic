<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Markdown;

include('_dvo_inc.php');

$this->title = 'Delete: '.$theDvo['name'];
if ($theDvo['venue_id'] != 0) {
    $this->title .= ' @'.Html::a($theDvo['venue']['name'], 'venues/r/'.$theDvo['venue_id']);
}
$this->params['breadcrumb'][] = ['Xem', 'dvo/r/'.$theDvo['id']];

?>
<div class="col-md-8">
    <div class="alert alert-danger">
        <i class="fa fa-warning"></i>
        Chi phí dịch vụ và các bảng giá của nó sẽ bị xoá.<br>
        Are you sure you want to delete this?
        <form method="post" action="" class="form-inline">
            <input type="hidden" name="confirm" value="delete">
            <?= Html::submitButton('Submit', ['class'=>'btn btn-danger']) ?>
        </form>
    </div>
</div>
