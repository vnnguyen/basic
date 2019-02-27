<?php
use yii\helpers\Html;

if ($item['object'] == 'feedback-sent') { ?>
<li class="note-list-item clearfix">
    <div class="note-avatar"><i class="fa fa-comment-o text-info fa-3x note-author-avatar"></i></div>
    <div class="note-content">
        <h5 class="note-heading">
            <i class="fa fa-comment-o"></i>
            <?= Html::a(Html::encode($onlfb['createdBy']['name']), '@web/users/'.$onlfb['createdBy']['id'], ['class'=>'note-author-name']) ?>
            sent feedback link to <?= Html::a($onlfb['customer_name'], '/contacts/'.$onlfb['customer_id']) ?>
        </h5>
        <div class="mb-1em">
            <span class="text-muted"><?= $time ?></span>
        </div>
        <div class="note-body"><?= $onlfb['message'] ?></div>
    </div>
</li>

<?php
} else {
    $fbData = unserialize($onlfb['fb_data']);
    $questions_point['q2']  = $fbData['q2'];
        $questions_point['q3']  = isset($fbData['q3']) ? $fbData['q3'] : null;
        $questions_point['q4']  = isset($fbData['q4']) ? $fbData['q4'] : null;
        $questions_point['q8']  = $fbData['q8'];
        $questions_point['q10'] = $fbData['q10'];
        $poins = [
            'q2'  => [
                'Votre appréciation générale' => 30,
                "Notre disponibilité (tél, courriel, etc.)" => 5,
                "Compétences de votre conseiller(e) (compréhension, adaptation du programme)" => 5,
                "Informations délivrées pour le voyage (devis, programme, informations pratiques, etc.) :" => 3,
                "Rapport Qualité/Prix" => 5,
                "Suivi et résolution des problèmes pendant le voyage" => 6,
                "Originalité de nos prestations" => 5,
                "Qualité des prestations::Programmation du voyage" => 2.5,
                "Qualité des prestations::Hébergements" => 2.5,
                "Qualité des prestations::Restauration" => 2.5,
                "Qualité des prestations::Véhicule" => 2.5,
                "Qualité des prestations::Bateau" => 1,
                "Ce séjour était-il adapté à votre degré d'immersion que vous avez souhaité (participation aux acitivités, séjours et contacts chez l'habitant, etc.)" => 5,
            ],
            'q3' => [
                "Niveau de français" => 2,
                "Connaissances" => 2,
                "Capacité d’organisation" => 2,
                "Serviabilité - Disponibilité - Aimabilité" => 2,
                "Capacité d’assurer le contact du voyageur avec les habitants et la vie locale" => 2,

            ],
            'q4' => [
                "Qualité de la conduite" => 1,
                "Serviabilité" => 1,
                "Concentration" => 1,
                "Relationnel (avec le guide et les voyageurs)" => 1,
                "Propreté du véhicule" => 1,

            ],
            'q8' => 5,
            'q10' => 5
        ];
        $table = [];
        $cpLink_point = [];
        $arr_point = [];
        foreach ($questions_point as $k_q => $questions) {
            if ($k_q == 'q2') {
                $arr_ = [];
                foreach ($questions as $q => $column_id) {
                    if ($column_id == '') {
                        $column_id = 5;
                    }
                    if (!isset($poins[$k_q][$q])) {
                        continue;
                    }
                    $arr_[] = ($column_id - 1) * $poins[$k_q][$q];
                }
                $table['q2'] = array_sum($arr_);
            }
            if ($k_q == 'q3' || $k_q == 'q4') {
                if ( $k_q == 'q3' ) {
                    $arr_points = [
                        'Niveau de français' =>[0, 10, 30, 40, 50],
                        'Connaissances' => [0, 15, 45, 60, 75],
                        'Capacité d’organisation' => [0, 15, 45, 60, 75],
                        'Serviabilité - Disponibilité - Aimabilité' => [0, 20, 60, 80, 100],
                        'Capacité d’assurer le contact du voyageur avec les habitants et la vie locale' => [0, 20, 60, 80, 100]
                    ];
                }
                if ( $k_q == 'q4' ) {
                    $arr_points = [
                        'Qualité de la conduite' => [0, 10, 30, 40, 50],
                        'Serviabilité' => [0, 10, 30, 40, 50],
                        'Concentration' => [0, 10, 30, 40, 50],
                        'Relationnel (avec le guide et les voyageurs)' => [0, 10, 30, 40, 50],
                        'Propreté du véhicule' => [0, 10, 30, 40, 50]
                    ];
                }
                if ( !$questions ) {
                    $table[$k_q] = 20;
                    continue;
                }

                $arr_ = [];

                $cpLink_ids = array_keys(current($questions));
                $keys_q = array_keys($questions);


                foreach ($cpLink_ids as $id_l) {
                    foreach ($keys_q as $key_q) {
                        if ($questions[$key_q][$id_l] == '') {
                            $questions[$key_q][$id_l] = 5;
                        }
                        if (!isset($poins[$k_q][$key_q])) {
                            continue;
                        }
                        $arr_[$id_l][$key_q] = ($questions[$key_q][$id_l]-1) * $poins[$k_q][$key_q];
                        $arr_point[$id_l][$key_q] = $arr_points[$key_q][$questions[$key_q][$id_l]-1];
                    }
                }
                $total[$k_q] = 0;
                foreach ($arr_ as $cpLink_id => $ar_v) {
                    $cpLink_point[$k_q][$cpLink_id] = array_sum($ar_v);
                    $total[$k_q] += $cpLink_point[$k_q][$cpLink_id];
                }
                $table[$k_q] = ceil($total[$k_q]/count($arr_));
            }
            if (in_array($k_q, ['q8', 'q10'])) {
                $column_id = 2;
                if (isset($questions['Oui'])) {
                    $column_id = 4;
                }
                if (isset($questions['Non'])) {
                    $column_id = 0;
                }
                $table[$k_q] = $column_id * $poins[$k_q];
            }
        }
        if (isset($cpLink_point['q3'])) {
            foreach ($cpLink_point['q3'] as $user_id => $point) {
                $scores['guides'][$user_id] = isset($arr_point[$user_id]) ? array_sum($arr_point[$user_id]) : 0;
            }
        }
        if (isset($cpLink_point['q4'])) {
            foreach ($cpLink_point['q4'] as $user_id => $point) {
                $scores['drives'][$user_id] = isset($arr_point[$user_id]) ? array_sum($arr_point[$user_id]) : 0;
            }
        }
        $scores['totals'] = number_format(array_sum($table) / 40);
        if ($onlfb['fb_point'] === null || $onlfb['fb_point'] <= 5) {
            Yii::$app->db->createCommand('UPDATE cplinks SET fb_point=:pts WHERE id=:id LIMIT 1', [':pts'=>$scores['totals'], ':id'=>$onlfb['id']])->execute();
        }

?>
<li class="note-list-item clearfix" data-id="<?= $onlfb['id'] ?>">
    <a name="anchor-note-<?= $onlfb['id'] ?>"></a>
    <div class="note-avatar">
    <?= Html::a(Html::img('/assets/img/placeholder.jpg', ['class'=>'img-circle note-author-avatar']), '@web/contacts/'.$onlfb['customer_id']) ?>
    </div>
    <div class="note-content">
        <h5 class="note-heading">
            <i class="fa fa-comment-o"></i>
            <?= Html::a($onlfb['customer_name'], '@web/contacts/'.$onlfb['customer_id'], ['class'=>'note-author-name']) ?>:
            <?= Html::a(Yii::t('x', 'submitted a feedback'), '@web/feedbacks/'.$onlfb['id'].'/view-submission', ['class'=>'note-title text-semibold']) ?>
        </h5>
        <div class="mb-1em">
            <span class="text-muted">
                <?= date('j/n/Y H:i', strtotime($time)) ?>
            </span>
        </div>
        <?php
        $uploadRoot = '/var/www/client.amica-travel.com/www/';
        $uploadDir = 'upload/'.$onlfb['booking_id'].'/'.$onlfb['customer_id'].'/feedback';
        if (file_exists($uploadRoot.$uploadDir)) {
            $files = yii\helpers\FileHelper::findFiles($uploadRoot.$uploadDir);
            if (!empty($files)) { ?>
        <div class="note-file-list row"><?php
                foreach ($files as $file) {
                    $fileName = substr(strrchr($file, '/'), 1);
                ?>
            <div class="col-4"><?= Html::a(Html::img('/timthumb.php?src=https://client.amica-travel.com/'.$uploadDir.'/'.$fileName), str_replace($uploadRoot, 'https://client.amica-travel.com/', $file)) ?></div>
            <?php
                } ?>
        </div><?php
            }
        }
        ?>
        <div class="note-body">
            <p>(<?= Yii::t('x', 'To view feedback details, click the link above.') ?>)</p>
            <div class="feedback_scores">
                <div class="mt-3" style="margin:2rem 0 1rem"><strong><?= Yii::t('x', 'Tour ratings') ?></strong></div>
                <div class="fb-testimonial pl-3 mb-3" style="padding-left:2rem; border-left:2px solid #4caf50">
                    <div><strong><?= Yii::t('x', 'Total score') ?>:</strong> <span class="text-pink font-weight-bold"><?= $scores['totals'] ?></span>/10</div>
                    <div><strong><?= Yii::t('x', 'Tour guides') ?>:</strong>
                        <?php if (isset($scores['guides'])) { ?>
                        <?php foreach ($scores['guides'] as $uid => $score) { ?>
                        <?php
                        $guideName = Yii::$app->db->createCommand('SELECT (IF(guide_user_id=0, guide_name, (SELECT name FROM contacts c WHERE c.id=guide_user_id) )) AS name FROM at_tour_guides WHERE id=:id LIMIT 1', [':id'=>$uid])->queryScalar();
                        ?>
                        <div>&mdash; <?= $guideName ?>: <span class="scores"><?= $score ?></span></div>
                        <?php } ?>
                        <?php } else { ?>
                        <span class="text-danger"><?= Yii::t('x', 'No data available.') ?></span>
                        <?php } ?>
                    </div>
                    <div>
                        <strong><?= Yii::t('x', 'Tour drivers') ?>:</strong>
                        <?php if(isset($scores['drives'])) { ?>
                            <?php foreach ($scores['drives'] as $uid => $score){ ?>
                        <?php
                        $driverName = Yii::$app->db->createCommand('SELECT (IF(driver_user_id=0, driver_name, (SELECT name FROM contacts c WHERE c.id=driver_user_id) )) AS name FROM at_tour_drivers WHERE id=:id LIMIT 1', [':id'=>$uid])->queryScalar();
                        ?>
                        <div>&mdash; <?= $driverName ?>: <span class="scores"><?= $score?></span></div>
                            <?php } ?>
                        <?php } else { ?>
                        <span class="text-danger"><?= Yii::t('x', 'No data available.') ?></span>
                        <?php } ?>
                    </div>
                </div>            
            </div>
            <?php if (!empty($fbData['q5']['autre'])) { ?>
            <div class="mt-3" style="margin:2rem 0 1rem"><strong>Vos autres remarques et suggestions d'amélioration de nos services ?</strong></div>
            <div class="fb-testimonial pl-3 mb-3" style="padding-left:2rem; border-left:2px solid #4caf50">
                <div class="fb-content"><?= nl2br($fbData['q5']['autre']) ?></div>
            </div>
            <?php } ?>
            <?php if (!empty($fbData['contact_2'])) { ?>
            <div class="mt-3" style="margin:2rem 0 1rem"><strong><?= Yii::t('x', 'Testimonial') ?></strong>: <span class="fb-title"><?= $fbData['contact_1'] ?? '' ?></span></div>
            <div class="fb-testimonial pl-3" style="padding-left:2rem; border-left:2px solid #4caf50">
                <div class="fb-content"><?= nl2br($fbData['contact_2']) ?></div>
            </div>
            <?php } ?>
        </div>
    </div>
</li>

<?php }