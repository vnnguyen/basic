<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

$theDv['name'] = str_replace(
    [
        '_o', '_v', '_p', '_f', '_t',
    ], [
        '<span class="text-pink">'.$theDv['id'].'</span>',
        '<span class="text-pink">'.$theDv['venue']['name'].'</span>',
        '<span class="text-pink">'.$theDv['supplier']['name'].'</span>',
        '<span class="text-pink">'.$theDv['id'].'</span>',
        '<span class="text-pink">'.$theDv['id'].'</span>',
    ], $theDv['name']);

Yii::$app->params['page_title'] = 'Dịch vụ: '.$theDv['name'];

$matches = [];
//preg_match_all('/\{([^}]+)\}/', $input, $matches);
preg_match_all('/\[([^\]]+)\]/', $theDv['name'], $matches);


?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Dịch vụ</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theDv as $key=>$val) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td><?= $val ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <? if (USER_ID == 1) { ?>
        <div class="panel-body">
            <? foreach ($matches[1] as $match) {
                if (substr($match, 0, 1) == '?') {
                    $args = explode(':', substr($match, 1));
                    if (isset($args[1])) {
                        $vals = explode('|', $args[1]);
                    } else {
                        $vals = false;
                    }
                ?>
                <div class="rowx">
                    <label class="control-label"><?= $args[0] ?></label>
                    <? if ($vals === false) { ?>
                    <input type="text" class="form-control" name="x" value="">
                    <? } else { ?>
                    <select class="form-control" name="v">
                        <? foreach ($vals as $val) { ?>
                        <option value="<?= $val ?>"><?= $val ?></option>
                        <? } ?>
                    </select>
                    <? } ?>
                </div>
                <?
                }
            }
            ?>
        <? \fCore::expose($matches); ?>               
        </div>
        <? } ?>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Giá dịch vụ</h6>
            <div class="heading-elements">
                <span class="heading-text"><a href="/cp/c?dv_id=<?= $theDv['id'] ?>">+</a></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Time</th>
                        <th>Conditions</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theDv['cp'] as $cp) { ?>
                    <tr>
                        <td><?= Html::a('<i class="fa fa-edit"></i>', '/cp/u/'.$cp['id']) ?></td>
                        <td><?= $cp['period'] ?></td>
                        <td><?= $cp['conds'] ?></td>
                        <td class="text-right text-nowrap">
                            <?= number_format($cp['price'], intval($cp['price']) == $cp['price'] ? 0 : 2) ?>
                            <span class="text-muted"><?= $cp['currency'] ?></span>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>