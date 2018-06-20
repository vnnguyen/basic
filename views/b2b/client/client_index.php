<?
use yii\helpers\Html;

$this->title = 'B2B - Client Accounts ('.count($theAccounts).')';
$this->params['breadcrumb'] = [
    ['B2B', 'b2b'],
    ['Clients'],
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-responsive table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>Company name</th>
                        <th>Login name</th>
                        <th>Website</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theAccounts as $account) { ?>
                    <tr>
                        <td><?= Html::a($account['name'], '@web/b2b/clients/r/'.$account['id']) ?></td>
                        <td><? if ($account['profileTA']) { echo $account['profileTA'][0]['login']; } ?></td>
                        <td><? foreach ($account['metas'] as $meta) { if ($meta['k'] == 'website') { echo Html::a($meta['v'], $meta['v'], ['target'=>'_blank']); } } ?></td>
                        <td><!--
                            <?= Html::a('View company', '@web/companies/r/'.$account['id']) ?>
                            - -->
                            <?= Html::a('Cases', '@web/b2b/clients/r/'.$account['id']) ?>
                            -
                            <?= Html::a('Tours', '@web/b2b/clients/r/'.$account['id'].'?view=tour') ?>
                            -
                            <?= Html::a('Edit login', '@web/b2b/clients/login/'.$account['id']) ?>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
