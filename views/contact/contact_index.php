<?php
use app\widgets\LinkPager;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

include('_contact_inc.php');

?>
<div class="col-md-12">
    <form action="" class="form-inline mb-2">
        <?= Html::textInput('fname', $fname, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Surname']) ?>
        <?= Html::textInput('lname', $lname, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Given name']) ?>
        <?= Html::dropdownList('gender', $gender, ['all'=>'All genders', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('country', $country, ArrayHelper::map($countryList, 'code', 'name'), ['class'=>'form-control', 'prompt'=>'All countries']) ?>
        <?= Html::textInput('email', $email, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Email']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <?php if (empty($theContacts)) { ?>
    <p class="text-danger"><?= Yii::t('x', 'No data found.') ?></p>
    <?php } else { ?>
    <div class="card table-responsive">
        <table class="table table-narrow table-striped">
            <thead>
                <tr>
                    <th width="15"></th>
                    <th width="44"></th>
                    <th width="350">ID, Name</th>
                    <th>Date of birth</th>
                    <th width="">Email</th>
                    <th width="">Phone</th>
                    <!-- <th width="">Groups</th> -->
                    <th>Cases / Tours</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($theContacts as $contact) { ?>
                <?php $contact['image'] = $contact['image'] == '' ? '/assets/img/placeholder.jpg' : $contact['image']; ?>
                <tr>
                    <td><?= Html::a('<i class="fa fa-edit"></i>', '/contacts/'.$contact['id'].'/u', ['class'=>'text-muted', 'title'=>Yii::t('x', 'Edit')]) ?></td>
                    <td><?= Html::img('/timthumb.php?w=100&h=100&src='.$contact['image'], ['class'=>'rounded-circle', 'style'=>'width:48px;height:48px;', 'alt'=>Yii::t('x', 'Avatar')]) ?></td>
                    <td class="text-nowrap">
                        <div><?=Html::a($contact['fname'], '/contacts/'.$contact['id'])?> &middot; <?=Html::a($contact['lname'], '/contacts/'.$contact['id'])?></div>
                        <div>
                            <?php if ($contact['gender'] == 'male') { ?><i class="fa fa-male text-primary"></i><?php } ?>
                            <?php if ($contact['gender'] == 'female') { ?><i class="fa fa-female text-pink"></i><?php } ?>
                            <?php if ($contact['country_code'] != '--') { ?><span class="flag-icon flag-icon-<?= $contact['country_code'] ?>"></span><?php } ?>
                        </div>
                    </td>
                    <td><?php if ($contact['bday'] != 0 && $contact['bmonth'] != 0 && $contact['byear'] != 0) { ?>
                        <?= $contact['bday'] ?>/<?= $contact['bmonth'] ?>/<?= $contact['byear'] ?>
                        <?php } ?>
                    </td>
                    <td><?php
                    foreach ($contact['metas'] as $meta) {
                        if ($meta['format'] == 'email') {
                            echo '<div>', Html::a($meta['value'], 'mailto:'.$meta['value']), '</div>';
                        }
                    }
                    ?></td>
                    <td><?php
                    foreach ($contact['metas'] as $meta) {
                        if ($meta['format'] == 'tel') {
                            echo '<div>', $meta['value'], '</div>';
                        }
                    }
                    ?></td>
                    <td>
                        <?
                        if ($contact['cases']) {
                            foreach ($contact['cases'] as $case) {
                                echo '<i class="text-muted fa fa-briefcase"></i> ';
                                echo Html::a($case['name'], '/cases/r/'.$case['id']);
                                echo '&nbsp; ';
                            }
                        }
                        if ($contact['bookings']) {
                            foreach ($contact['bookings'] as $booking) {
                                echo '<i class="text-muted fa fa-truck text-success"></i> ';
                                echo Html::a($booking['product']['op_code'], '/bookings/r/'.$booking['id'], ['class'=>'text-success', 'style'=>'font-weight:bold;']);
                                echo '&nbsp; ';
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
    <?php } // if theContacts ?>
</div>
