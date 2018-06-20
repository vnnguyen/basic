<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dvc_inc.php');

Yii::$app->params['page_title'] = 'Hợp đồng dịch vụ: '.$theDvc['name'].' / '.$theDvc['venue']['name'];

$range = [];
foreach ($theDvc['dvd'] as $dvd) {
    if ($dvd['stype'] == 'date') {
        $subRange = explode(';', $dvd['def']);
        foreach ($subRange as $sr) {
            $arr = [
                'range'=>$sr,
                'code'=>$dvd['code'],
                'name'=>$dvd['desc'],
            ];
            $range[] = $arr;
        }
    }
}
// \fCore::expose($range);
?>
<div class="col-md-8">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Service contract') ?></h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-xxs">
                <thead>
                    <tr><th width="25%"><?= Yii::t('dv', 'Code') ?></th><td><?= $theDvc['name'] ?></td></tr>
                </thead>
                <tbody>
                    <tr><th><?= Yii::t('dv', 'Number') ?></th><td><?= $theDvc['number'] ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Date signed') ?></th><td><?= date('j/n/Y', strtotime($theDvc['signed_dt'])) ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Service supplier') ?></th><td><?= Html::a($theDvc['venue']['name'], '/venues/r/'.$theDvc['venue']['id']) ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Validity') ?></th><td><?= date('j/n/Y', strtotime($theDvc['valid_from_dt'])) ?> - <?= date('j/n/Y', strtotime($theDvc['valid_until_dt'])) ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Attachments') ?></th><td>
                    <?
                    $uploadDir = Yii::getAlias('@webroot').'/upload/dvc/'.substr($theDvc['created_dt'], 0, 7).'/'.$theDvc['id'];
                    if (file_exists($uploadDir)) {
                        $uploadFiles = \yii\helpers\FileHelper::findFiles($uploadDir);
                        if (empty($uploadFiles)) {
                            echo 'No files uploaded.';
                        } else {
                            foreach ($uploadFiles as $file) {
                                ?>
                                <div>
                                    <i class="fa fa-download text-muted"></i>
                                    <?= Html::a(substr(strrchr($file, '/'), 1), '?action=download&file='.substr(strrchr($file, '/'), 1)) ?>
                                    <? if (in_array(USER_ID, [1, 9198])) { ?>
                                    <?= Html::a('<i title="'.Yii::t('app', 'Delete').'" class="fa fa-trash-o text-danger"></i>', '?action=delete&file='.substr(strrchr($file, '/'), 1)) ?>
                                    
                                    <? } ?>
                                </div><?
                            }
                        }
                    } else {
                        echo 'No files uploaded.';
                    }
                    ?>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Definitions') ?></h6>
            <div class="heading-elements">
                <span class="heading-text"><a href="#" class="dvd_add_toggle">Add</a></span>
            </div>
        </div>
        <div class="panel-body dvd_add" style="display:none;">
            <form method="post" action="">
                <?= Html::hiddenInput('action', 'dvd_add') ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('dv', 'Type') ?></label>
                            <?= Html::dropdownList('stype', '', ['date'=>'Date', 'conds'=>'Conditions'], ['class'=>'form-control']) ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('dv', 'Code') ?></label>
                            <?= Html::textInput('code', '', ['class'=>'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?= Yii::t('dv', 'Definition') ?></label>
                    <?= Html::textInput('def', '', ['class'=>'form-control']) ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?= Yii::t('dv', 'Description') ?></label>
                    <?= Html::textInput('desc', '', ['class'=>'form-control']) ?>
                </div>
                <?= Html::submitButton('Add', ['class'=>'btn btn-primary']) ?>
                or <?= Html::a('Cancel', '#', ['class'=>'dvd_add_cancel']) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-xxs">
                <thead>
                    <tr>
                        <th><?= Yii::t('dv', 'Code') ?></th>
                        <th><?= Yii::t('dv', 'Definition') ?></th>
                        <th><?= Yii::t('dv', 'Description') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theDvc['dvd'] as $dvd) { ?>
                    <tr>
                        <td><?= Html::a($dvd['code'], '/dvd/u/'.$dvd['id'], ['class'=>$dvd['stype'] == 'date' ? 'text-slate' : 'text-pink']) ?></td>
                        <td><?= str_replace(';', '<br>', $dvd['def']) ?></td>
                        <td><?= $dvd['desc'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Service prices') ?></h6>
            <div class="heading-elements">
                <span class="heading-text"><a href="/cp/c?venue_id=<?= $theDvc['venue']['id'] ?>&dvc_id=<?= $theDvc['id'] ?>">Add</a></span>
            </div>
        </div>
        <div class="panel-body">
            <p id="show">Service date: <?= date('j/n/Y') ?></p>
            <div id="visualization"></div>
            <style>
            #visualization * {font-size:12px; font-family:Roboto;}
            .vis-item.vis-background.bg1 {background-color:#E6BABA}
            .vis-item.vis-background.bg2 {background-color:#C6BAE6}
            .vis-item.vis-background.bg3 {background-color:#BAD4E6}
            .vis-item.vis-background.bg4 {background-color:#BAE6E3}
            .vis-item.vis-background.bg5 {background-color:#E0BAE6}
            .vis-item.vis-background.bg6 {background-color:#BAE6CA}
            .vis-item.vis-background.bg7 {background-color:#E4E6BA}
            .vis-item.vis-background.bg8 {background-color:#E6CEBA}
            .vis-item.vis-background.bg9 {background-color:#E6BCBA}
            </style>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-xxs" id="tbl-cp">
                <thead>
                    <tr>
                        <th><?= Yii::t('dv', 'Service') ?></th>
                        <th><?= Yii::t('dv', 'Period') ?></th>
                        <th><?= Yii::t('dv', 'Conditions') ?></th>
                        <th><?= Yii::t('dv', 'Price') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($theDvc['cp'] as $cp) {
                        $def = '';
                        $class = '';
                        foreach ($theDvc['dvd'] as $dvd) {
                            if ($dvd['stype'] == 'date' && $dvd['code'] == $cp['period']) {
                                $def = $dvd['def'];
                                break;
                            }
                        }
                        foreach ($range as $i=>$rg) {
                            if (strpos($def, $rg['range']) !== false) {
                                $class .= ' range'.$i;
                            }
                        }
                        ?>
                    <tr class="range <?= $class ?>">
                        <td>
                            <? if ($cp['dv']['is_dependent'] == 'yes') { ?> &mdash; <? } ?>
                            <?
                            $cp['dv']['name'] = str_replace(
                                [
                                    '[', ']', '{', '}', '|',
                                ], [
                                    '', '', '(<span class="text-light text-pink">', '</span>)', '/',
                                    ], $cp['dv']['name']);
                            echo $cp['dv']['name'];
                            ?>
                            </td>
                        <td class="text-slate" data-period="<?= $def ?>"><?= $cp['period'] ?></td>
                        <td class="text-pink"><?= $cp['conds'] ?></td>
                        <td class="text-right text-nowrap">
                            <?= Html::a(number_format($cp['price'], intval($cp['price']) == $cp['price'] ? 0 : 2), '/cp/u/'.$cp['id']) ?>
                            <span class="text-muted"><?= $cp['currency'] ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Contract terms') ?></h6>
        </div>
        <div class="panel-body">
            <?= $theDvc['body'] ?>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Related contracts') ?></h6>
            <div class="heading-elements">
                <span class="heading-text"><a href="/dvc/c?venue_id=<?= $theDvc['venue']['id'] ?>">Add</a></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th><?= Yii::t('dv', 'Name') ?></th>
                        <th><?= Yii::t('dv', 'Description') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($relatedDvcx as $dvc) { ?>
                    <tr>
                        <td><?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id'], ['class'=>$dvc['id'] == $theDvc['id'] ? 'text-bold' : '' ]) ?></td>
                        <td><?= $dvc['description'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?
$cnt = 0;
$data_set = '';
$range_set = '';
foreach ($range as $i=>$rg) {
    $cnt ++;
    $r = explode('-', $rg['range']);
    if (!isset($r[1])) {
        $r[1] = $r[0];
    }

    $bg = 9;
    foreach ($theDvc['dvd'] as $j=>$dvd) {
        if ($dvd['stype'] == 'date' && strpos($dvd['def'], $rg['range']) !== false) {
            $bg = $j + 1;
        }
    }

    $from = \DateTime::createFromFormat('j/n/Y', $r[0])->format('Y-m-d');
    $until = \DateTime::createFromFormat('j/n/Y', $r[1])->format('Y-m-d');
    $data_set .= "{id: $cnt, group: '{$theDvc['name']}', content: '{$rg['code']}', start: '$from 00:00:00', end: '$until 23:59:59', type: 'background', className: 'bg$bg'}";
    $range_set .= "ranges.push ( moment.range(moment('{$from} 00:00:00'), moment('{$until} 23:59:59')));";
    if ($cnt < count($range)) {
        $data_set .= ",\n";
    }
}

$js = <<<'TXT'
$('a.dvd_add_toggle, a.dvd_add_cancel').on('click', function(){
    $('div.dvd_add').toggle();
    return false;
})

    // DOM element where the Timeline will be attached
    var container = document.getElementById('visualization');

    // Create a DataSet (allows two way data-binding)
    var items = new vis.DataSet([
        {id:999, content: 'Promo XYZ', editable: false, start: '2017-08-26', end: '2017-09-02'},
        {$DATASET}
    ]);

    // Configuration for the Timeline
    var options = {
        // type: 'background',
        stack: false,
        zoomMin: 1000000000,
        zoomMax: 32000000000,
    };

    // Create a Timeline
    var timeline = new vis.Timeline(container, items, options);
    timeline.addCustomTime(moment().format('YYYY-MM-DD 12:00:00'));

    var ranges = [];

    {$RANGES}

    // add event listener

    timeline.on('click', function (props) {
        timeline.setCustomTime(props.time);
        $('#show').html("Service date: " + moment(props.time).format('D/M/Y'));
        $('tr.range').hide();
        cnt = 0;
        ranges.forEach(function(element) {
            if (element.contains(props.time)) {
                $('tr.range.range'+cnt).show();
            }
            cnt ++;
        });
    });

    timeline.on('timechange', function (event) {
        $('#show').html("Service date: " + moment(event.time).format('D/M/Y'));
        $('tr.range').hide();
        cnt = 0;
        ranges.forEach(function(element) {
            if (element.contains(event.time)) {
                $('tr.range.range'+cnt).show();
            }
            cnt ++;
        });
    });
TXT;

$this->registerJs(str_replace(['{$DATASET}', '{$RANGES}'], [$data_set, $range_set], $js));

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.css', ['depends'=>'yii\web\JqueryAsset']);
