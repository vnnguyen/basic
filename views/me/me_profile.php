<?
use yii\helpers\Html;
use common\models\User;
Yii::$app->params['page_title'] = 'My profile information';
Yii::$app->params['page_layout'] = '-h -s';

$my = Yii::$app->user->identity;

?>
<style>
.content:first-child {padding-top:0;}
</style>
<div class="col-md-8 col-md-push-2">
    <div class="profile-cover">
        <div class="profile-cover-img" style="background-image: url(https://images.unsplash.com/photo-1438755582627-221038b62986?fit=crop&fm=jpg&h=800&q=80&w=1200)"></div>
        <div class="media">
            <div class="media-left">
                <a href="#" class="profile-thumb">
                    <img src="<?= $my['image'] ?>" class="img-circle" alt="">
                </a>
            </div>

            <div class="media-body">
                <h1><?= $my['name'] ?> <small class="display-block"><?= $my['nickname'] ?></small></h1>
            </div>
            <!--
            <div class="media-right media-middle">
                <ul class="list-inline list-inline-condensed no-margin-bottom text-nowrap">
                    <li><a href="#" class="btn btn-default"><i class="icon-file-picture position-left"></i> Cover image</a></li>
                    <li><a href="#" class="btn btn-default"><i class="icon-file-stats position-left"></i> Statistics</a></li>
                </ul>
            </div>
            -->
        </div>
    </div>
    <div class="row clearfix bg-white pt-20" style="margin:0;">
        <div class="col-md-5 col-sm-6">
            <div class="panel panel-default">
                <table class="table">
                    <tbody>
                        <tr><td><strong>Full name:</strong></td><td><?= $my['fname'] ?> / <?= $my['lname'] ?></td></tr>
                        <tr><td><strong>Gender:</strong></td><td><?= $my['gender'] ?></td></tr>
                        <tr><td><strong>Date of birth:</strong></td><td><?= $my['bday'] ?> / <?= $my['bmonth'] ?> / <?= $my['byear'] ?> (<?= $my['byear'] != 0 ? date('Y') - $my['byear'] : '?' ?> tuổi)</td></tr>
                        <tr><td><strong>Nationality:</strong></td><td><?= Html::img(DIR.'assets/img/flags/16x11/'.$my['country_code'].'.png') ?> <?= $my['country']['name_en'] ?></td></tr>
                        <tr><td><strong>Language:</strong></td><td><?= $my['language'] ?></td></tr>
                        <tr><td><strong>Timezone:</strong></td><td><?= $my['timezone'] ?></td></tr>
                        <tr><td><strong>About:</strong></td><td><?= $my['about'] ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-7 col-sm-6">
            <p><strong>PERSONAL INFORMATION</strong></p>
            <?= Html::a('Edit information', '@web/me/preferences')?> | <?= Html::a('Change password', '@web/me/account')?>
            <hr>
            <p><strong>CONTACT INFORMATION</strong></p>
            <ul>
                <? foreach ($metas as $mli) { ?>
                <li><strong><?= isset(User::$metaLabels[$mli['k']]) ? User::$metaLabels[$mli['k']] : $mli['k'] ?></strong>: <?=$mli['v']?> <?=$mli['x'] == '' ? '' : ' ('.$mli['x'].')' ?></li>
                <? } ?>
            </ul>
        </div>
    </div>
</div>
<!--
    <p><strong>PERSONAL BIO</strong></p>
    <p><strong>EMPLOYEE PROFILE</strong></p>
    <ul class="list-unstyled">
        <li><strong>Current position:</strong> Position</li>
        <li><strong>Company:</strong> ----------</li>
        <li><strong>Department:</strong> ----------</li>
        <li><strong>Reports to:</strong> ----------</li>
        <li><strong>:</strong> ----------</li>
        <li><strong>:</strong> ----------</li>
        <li><strong>:</strong> ----------</li>
        <li><strong>:</strong> ----------</li>
        <li><strong>:</strong> ----------</li>
        <li><strong>:</strong> ----------</li>
    </ul>
-->



