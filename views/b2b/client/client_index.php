<?php
use yii\helpers\Html;

include('_client_inc.php');

$idList = [];
foreach ($theClients as $client) {
    $idList[] = $client['id'];
}
    // \Yii::$app->db->createCommand()
    //     ->update('metas', ['rtype'=>'client'], ['rtype'=>'company', 'rid'=>$idList])
    //     ->execute();

?>
<div class="col-md-12">
    <div class="form-inline mb-2">
        <?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Search name')]) ?>
        <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), '?') ?>
    </div>
    <div class="card mb-0">
        <div class="table-responsive">
            <table class="table card-table table-striped table-narrow">
                <thead>
                    <tr>
                        <th width="16"></th>
                        <th colspan="2">Company name & Login</th>
                        <th>Country</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th colspan="2" class="text-center">Stats</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theClients as $client) { 
                        if ($client['image'] == '') {
                            $client['image'] = '/assets/img/placeholder.jpg';
                        }
                        ?>
                    <tr>
                        <td><?= Html::a('<i class="fa fa-edit"></i>', '/b2b/clients/u/'.$client['id'], ['class'=>'text-muted']) ?></td>
                        <td style="padding-right:0!important;" width="60"><?= Html::img('/timthumb.php?w=100&h=100&zc=2&src='.$client['image'], ['class'=>'img-responsive img-lg']) ?></td>
                        <td>
                            <?= Html::a($client['name'], '@web/b2b/clients/r/'.$client['id']) ?>
                            <div class="text-muted"><?= $client['login'] ?></div>
                        </td>
                        <td><?php foreach ($client['metas'] as $meta) { if ($meta['name'] == 'address') { echo '<div>', $meta['value'], '</div>'; } } ?></td>
                        <td><?php foreach ($client['metas'] as $meta) { if ($meta['name'] == 'tel') { echo '<div>', $meta['value'], '</div>'; } } ?></td>
                        <td><?php foreach ($client['metas'] as $meta) { if ($meta['name'] == 'email') { echo '<div>', Html::a($meta['value'], 'mailto:'.$meta['value']), '</div>'; } } ?></td>
                        <td class="text-center">
                            <div style="font-size:1.5em"><?= count($client['cases']) ?></div>
                            <?= Html::a('cases', '/b2b/cases?company='.$client['id']) ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $client['tours'] = 0;
                            foreach ($client['cases'] as $case) {
                                foreach ($case['bookings'] as $booking) {
                                    if ($booking['status'] == 'won') {
                                        $client['tours'] ++;
                                    }
                                }
                            } ?>
                            <div style="font-size:1.5em" class="<?= $client['tours'] == 0 ? 'text-muted' : '' ?>"><?= $client['tours'] ?></div>
                            <?= Html::a('tours', '/b2b/tours?company='.$client['id']) ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
