<?php
use yii\helpers\Html;

include('_qhkh_inc.php');

Yii::$app->params['page_title'] = Yii::t('x', 'Club Amba - Ampo');

?>

<div class="col-md-12">
    <div class="card">
        <table class="table table-narrow table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= Yii::t('x', 'Name') ?></th>
                    <th><?= Yii::t('x', 'Age') ?></th>
                    <th><?= Yii::t('x', 'Country') ?></th>
                    <th><?= Yii::t('x', 'Email') ?></th>
                    <th><?= Yii::t('x', 'Tel') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($thePax as $pax) { ?>
                <tr>
                    <td class="text-muted"><?= $pax['id'] ?></td>
                    <td><?= Html::a($pax['name'], '/contacts/'.$pax['id']) ?></td>
                    <td><?= $pax['byear'] == 0 ? '' : date('Y') - $pax['byear'] ?></td>
                    <td><span class="flag-icon flag-icon-<?= $pax['country_code'] ?>"></span> <?= strtoupper($pax['country_code']) ?></td>
                    <td>
                        <?php
                        foreach ($pax['metas'] as $meta) {
                            if ($meta['format'] == 'email') { ?>
                                <?= $meta['value'] ?>
                        <!-- div -- ><?= $meta['value'] ?><!-- /div --><?php
                            }
                        }
                        ?>
                    </td>
                    <!-- td>
                        <?php
                        foreach ($pax['metas'] as $meta) {
                            if ($meta['format'] == 'tel') { ?>
                        <div><?= $meta['value'] ?></div><?php
                            }
                        }
                        ?>
                    </td -->
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>