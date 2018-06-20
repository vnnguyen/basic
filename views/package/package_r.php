<?
use yii\helpers\Html;
use app\helpers\DateTimeHelper;

include('_package_inc.php');

Yii::$app->params['page_title'] = $thePackage['name'];

$relatedList = [
    1=>['Go Cong', 'Vam Xang'],
    3=>['Lunch in Hoi An countryside'],
    4=>['lantern', 'đèn lồng'],
    5=>['làm diều','kite making'],
    6=>['silk painting'],
    9=>['Son Tra'],
];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Information</h6>
        </div>
        <div class="panel-body">
            <?= $thePackage['info'] ?>
        </div>
    </div>
    <? if (in_array($thePackage['id'], array_keys($relatedList))) { ?>
<?
$where = ['or'];
foreach ($relatedList[$thePackage['id']] as $word) {
    $where[] = ['like', 'dvtour_name', $word];
}
$query = \common\models\Cpt::find()
    ->select(['dvtour_id', 'tour_id', 'dvtour_name', 'dvtour_day', 'qty', 'price', 'unitc'])
    ->where(['venue_id'=>0])
    ->andWhere($where)
    ->with([
        'tour'=>function($q){
            return $q->select(['id', 'code']);
        }
        ])
    ->orderBy('dvtour_day DESC');
$cptx = $query->asArray()->all();
?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Tours using this package</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">Tour</th>
                        <th class="text-center">Day</th>
                        <th>Service</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($cptx as $cpt) { $cpt['total'] = $cpt['qty'] * $cpt['price']; ?>
                    <tr>
                        <td><?= Html::a($cpt['tour']['code'], '/tours/services/'.$cpt['tour']['id']) ?></td>
                        <td class="text-center"><?= date('j/n/Y', strtotime($cpt['dvtour_day'])) ?></td>
                        <td><?= Html::a($cpt['dvtour_name'], '/cpt/r/'.$cpt['dvtour_id']) ?></td>
                        <td class="text-center"><?= number_format($cpt['qty'], intval($cpt['qty']) == $cpt['qty'] ? 0 : 2) ?></td>
                        <td class="text-right"><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                        <td class="text-right"><?= number_format($cpt['total'], intval($cpt['total']) == $cpt['total'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
    <? } ?>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Files</h6>
        </div>
        <div class="panel-body">
        <?
        $path = Yii::getAlias('@webroot').'/upload/packages/'.substr($thePackage['created_dt'], 0, 7).'/'.$thePackage['id'];
        if (file_exists($path)) {
            $files = @\yii\helpers\FileHelper::findFiles($path, ['recursive'=>false]);
        } else {
            $files = [];
        }
        if (!empty($files)) {
            foreach ($files as $file) {
                $fileName = strrchr($file, '/');
        ?>
        <div>+ <?= Html::a(ltrim($fileName, '/'), Yii::getAlias('@web').'/upload/packages/'.substr($thePackage['created_dt'], 0, 7).'/'.$thePackage['id'].$fileName) ?></div>
        <?
            }
        } else {
            echo 'No files found.';
        }
        ?>
        </div>
    </div>
</div>