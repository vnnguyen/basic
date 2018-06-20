<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\ArrayHelper;

include('_person_inc.php');
include('_inc__huanhn_pax-form.php');
$social_network = ['facebook', 'linkedin', 'yahoo', 'tripadvisor'];
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_breadcrumbs'] = [
    ['People', 'persons'],
    [$theUser['name']]
];
if (in_array($theUser['country_code'], ['vn', 'la', 'kh'])) {
    $this->title = $theUser['fname'].' '.$theUser['lname'];
    $userName = $theUser['fname'].' '.$theUser['lname'];
} else {
    $this->title = $theUser['lname'].' '.$theUser['fname'];
    $userName = $theUser['lname'].' '.$theUser['fname'];
}
yap('page_small_title', $theUser['about']);
$userMetaList = [];

$sql = 'SELECT t.name, t.id FROM at_terms t, at_term_rel r WHERE t.taxonomy_id=2 AND r.term_id=t.id AND rtype="user" AND rid=:id';
$userTags = Yii::$app->db->createCommand($sql, [':id'=>$theUser['id']])->queryAll();



if ($theUser['is_member'] != 'yes') {
    if ($theUser['image'] == '') {
        $theUser['image'] = 'https://secure.gravatar.com/avatar/'.md5($theUser['email'] == '' ? $theUser['id'] : $theUser['email']).'?s=300&d=mm';
    } else {
        $theUser['image'] = str_replace('http://', 'https://', $theUser['image']);
    } 
}
$metas = [];
if (!empty($theUser['metas'])) {
    foreach ($theUser['metas'] as $k=>$item) {
        if ($item['value'] == '') {
            // continue;
            $item['value'] = '1,2,3';

            if ($item['name'] == 'future_travel_wishlist') {
               $item['value'] = 'vn,fr';
            }
        }
        if ($item['name'] == 'email' || $item['name'] == 'tel') {
            $theUser['metas'][$k]['value'] = '1,2,3';
            $metas[$item['name']][] = $item;
        } else {
            $metas[$item['name']] = $item;
        }
   }
}
$this->registerCss('
    .btn_edit{ display: none; }
    .panel:hover .btn_edit{ display: block; float: right; color: #167FD1 !important;}
    .panel:hover .wrap_item .btn_edit{ display: none;}
    .panel:hover .wrap_item:hover .btn_edit{ display: block; float: right; color: #167FD1 !important;}
    .box_search {width: 100% !important;}
    .wrap_item span.item_v { padding: 3px; display: inline-block;}
    .wrap_item span.item_v::after{content: ","; color: #000 !important;}
    .wrap_item span.item_v:last-child::after{content: "";}
    .wrap_item {overflow: hidden;}
    .card_case .card-body { padding: 10px 15px 10px 20px}
    .wrap_card .wrap_index { text-align: center; }
    .wrap_card .wrap_content {text-align: center}
    .wrap_card .wrap_index .span_index {color: blue; display: inline-block; font-size: 36px; border-bottom: 1px solid #cdcdcd; padding:0 15px}
    .wrap_card .wrap_content .span_content{display: inline-block; font-size: 15px; padding: 10px; text-transform: capitalize; }
');

if (USER_ID != 0) {
?>
<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('p', 'About this person') ?><a id="basic_info" class="btn_edit" title="edit">Edit</a></h6>

        </div>
        <div class="text-center">
            <img class="img-responsive" style="display: inline-block; max-width: 100%;" src="<?= $theUser['image'] ?>" alt="Avatar">
        </div>
        <table class="table table-narrow">
            <tbody>
                <? if ($theUser['is_member'] != 'yes') { ?>
                <tr>
                    <td colspan="2">
                    </td>
                </tr>
                <? } ?>
                <tr><td><strong><?= Yii::t('p', 'Full name')?>:</strong></td><td colspan="2"><?= $theUser['fname'] ?> / <?= $theUser['lname'] ?></td></tr>
                <tr><td><strong><?= Yii::t('p', 'Gender')?>:</strong></td><td colspan="2"><?= Yii::t('p', $theUser['gender']); ?></td></tr>
                <tr><td><strong><?= Yii::t('p', 'Date of birth')?>:</strong></td><td colspan="2">
                <?= ($theUser['bday'] > 0 && $theUser['bmonth'] > 0) ?  $theUser['bday'].' / '.$theUser['bmonth']:'';?>
                <?= ($theUser['byear'] > 0) ? ($theUser['bday'] > 0 && $theUser['bmonth'] > 0 ?'/ '.$theUser['byear']: $theUser['byear']): ''; ?>
                <?php if ($theUser['byear'] > 0): ?>
                    (<?= date('Y') - $theUser['byear']; ?>)
                <?php endif ?>
                </td></tr>
                <?php if (isset($metas['pob']) && $metas['pob']['value'] != ''): ?>
                <tr><td><strong><?= Yii::t('p', 'Place of birth')?>:</strong></td><td colspan="2">
                <?php
                    $infos = $metas['pob'];
                    echo $infos['value'];
                    if (isset($metas['pob_country']) && $metas['pob_country']['value'] != ''){
                        $country = ArrayHelper::map($countryList, 'code', 'name');
                        $pob_country = (isset($country[$metas['pob_country']['value']]))? $country[$metas['pob_country']['value']]: '';
                        echo ', '.$pob_country;
                    }
                ?>
                </td></tr>
                <?php endif ?>
                <tr><td><strong><?= Yii::t('p', 'Nationality')?>:</strong></td><td colspan="2"><span class="flag-icon flag-icon-<?= $theUser['country_code'] ?>"></span> <?= $theUser['country']['name_en'] ?></td></tr>
                <tr><td><strong><?= Yii::t('p', 'Language');?>:</strong></td><td colspan="2"><?= $languageList[$theUser['language']] ?? strtoupper($theUser['language']); ?></td></tr>

                <?php
                $v = '';
                if (isset($metas['marital']) && $metas['marital']['value'] != ''): 
                        $info = $metas['marital'];
                        $v = (isset($maritalStatusList[$info['value']]))? $maritalStatusList[$info['value']]: 'unknown';
                ?>
                <?php endif ?>
                <tr><td><strong><?= Yii::t('p', 'Marital status');?>:</strong></td><td colspan="2"><?= Yii::t('p', $v); ?></td></tr>
                <tr><td><strong><?= Yii::t('p', 'Relations');?>:</strong></td><td colspan="2"></td></tr>
                <tr><td><strong><?= Yii::t('p', 'Tags');?>:</strong></td>
                <?if (!empty($userTags)) {
                    foreach ($userTags as $tag) {
                        echo Html::a($tag['name'], '@web/users/tags?tag='.$tag['id']). ', ';
                    }
                } ?>
                <td colspan="2"></td></tr>
                <tr>
                    <td>
                        <div class="wrap_card">
                            <div class="wrap_index">
                                <span class="span_index">252</span>
                            </div>
                            <div class="wrap_content">
                                <span class="span_content">abc</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wrap_card">
                            <div class="wrap_index">
                                <span class="span_index">252</span>
                            </div>
                            <div class="wrap_content">
                                <span class="span_content">abc</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wrap_card">
                            <div class="wrap_index">
                                <span class="span_index">252</span>
                            </div>
                            <div class="wrap_content">
                                <span class="span_content">abc</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-9">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"><?= Yii::t('p', 'Contact information') ?><a id="contact_info" class="btn_edit" title="edit">Edit</a></h6>
                </div>
                <div class="panel-body">
                    <p><strong><?= Yii::t('p', 'CONTACT INFORMATION')?></strong></p>

                    <?php if (isset($metas['tel']) && $metas['tel'][0]['value'] != ''): 
                            $infos = $metas['tel'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Tel')?>: </strong>
                            <?php foreach ($infos as $m): ?>
                                <?= '<span class="item_v">'.$m['value'].'</span>';?>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($metas['email']) && $metas['email'][0]['value'] != ''): 
                            $infos = $metas['email'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Email')?>: </strong>
                            <?php foreach ($infos as $m): ?>
                            <?= '<span class="item_v">'.$m['value'].'</span>';?>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <?php $address = [];
                        if (isset($metas['addr_street']) && $metas['addr_street']['value'] != '') {
                            $address[] = $metas['addr_street']['value'];
                        }
                        if (isset($metas['addr_city']) && $metas['addr_city']['value'] != '') {
                            $address[] = $metas['addr_city']['value'];
                        }
                        if (isset($metas['addr_state']) && $metas['addr_state']['value'] != '') {
                            $address[] = $metas['addr_state']['value'];
                        }
                        if (isset($metas['addr_country']) && $metas['addr_country']['value'] != '') {
                            $country = ArrayHelper::map($countryList, 'code', 'name');
                            if (isset($country[$metas['addr_state']['value']])) {
                                $address[] = $country[$metas['addr_state']['value']];
                            }
                        }
                        if (!empty($address)) {
                        ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Address')?>: </strong>
                            <?= '<span class="item_v">'.implode(', ', $address).'</span>';?>
                        </div>
                        <? } ?>

                    <?php if (isset($metas['website']) && $metas['website']['value'] != ''): 
                            $info = $metas['website'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Website')?>: </strong>
                            <?= '<span class="item_v">'.$info['value'].'</span>';?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($metas['website2']) && $metas['website2']['value'] != ''): 
                            $info = $metas['website2'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Website')?> 2: </strong>
                            <?= '<span class="item_v">'.$info['value'].'</span>';?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($metas['profession']) && $metas['profession']['value'] != ''): 
                            $info = $metas['profession'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'profession')?>: </strong>
                            <?= '<span class="item_v">'.$info['value'].'</span>';?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($metas['job_title']) && $metas['job_title']['value'] != ''): 
                            $info = $metas['job_title'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Job title')?>: </strong>
                            <?= '<span class="item_v">'.$info['value'].'</span>';?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($metas['active_social_networks']) && $metas['active_social_networks']['value'] != ''): 
                            $info = $metas['active_social_networks'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Active social networks')?>: </strong>
                            <?php foreach (explode(',', $info['value']) as $v): ?>
                                <?= '<span class="item_v">'.$social_network[$v].'</span>';?>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($metas['rel_with_amica']) && $metas['rel_with_amica']['value'] != ''): 
                            $info = $metas['rel_with_amica'];
                    ?>
                        <div class="wrap_item"><strong><?= Yii::t('p', 'Relationship with AMICA TRAVEL')?>: </strong>
                            <?= '<span class="item_v">'.$info['value'].'</span>';?>
                        </div>
                    <?php endif ?>
                    <br>
                    <p><strong><?= Yii::t('p', 'NOTE');?></strong></p>
                    <p><?=fHTML::convertNewLines($theUser['info'])?></p>
                    <?
                    if (!empty($theUser['roles'])) { ?>
                    <br>
                    <p><strong><?= Yii::t('p', 'ROLES & GROUPS')?></strong></p><?
                        foreach ($theUser['roles'] as $role) { ?>
                    <div><?= Html::a($role['name'], '@web/roles/r/'.$role['id']) ?></div><?
                        } // foreach
                    } // if not empty

                    if (!empty($userTags)) { ?>
                    <br>
                    <p><strong>TAGS:</strong> <?
                        foreach ($userTags as $tag) {
                            echo Html::a($tag['name'], '@web/users/tags?tag='.$tag['id']). ', ';
                        } ?>
                    </p><?
                    } ?>
                    <p><?= Html::a('View all user tags', '@web/users/tags') ?></p>
                </div>
            </div>
            <? if ($theUser['ref']) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"><?= Yii::t('p', 'Referral cases')?><a class="heading-elements-toggle"> <i class="icon-more"></i> </a></h6>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse" data-popup="tooltip" title="Collapse" class=""></a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <? foreach ($theUser['ref'] as $ref) {
                        $case = $ref['case'];
                    ?>
                    <div>
                        <i class="fa fa-fw fa-briefcase text-muted"></i>
                        <?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
                        <? if ($case['status'] == 'closed') { ?><i class="fa fa-lock text-muted"></i><? } ?>
                        <? if ($case['status'] == 'onhold') { ?><i class="fa fa-clock-o text-muted"></i><? } ?>
                        <? if ($case['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
                        <? if ($case['deal_status'] == 'lost') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
                        <?= date('n/Y', strtotime($case['created_at'])) ?>
                        <?= $case['owner']['name'] ?>
                        <?= ($ref['points'] > 0)? '<span class="badge badge-flat border-success text-success-600">'.$ref['points'].'</span>' : '' ?>
                    </div>
                    <? } ?>
                    <br>
                </div>
            </div>
            <? } // if cases ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"><?= Yii::t('p', 'Sales') ?></h6>
                </div>
                <div class="panel-body">
                    <p class="text-semibold text-uppercase"><?= Yii::t('p', 'Recent inquiries and bookings')?></p>
                    <? foreach ($theUser['cases'] as $case) {
                        $has_booking = false;
                    ?>
                    <div class="card_case">
                        <div class="card-header">
                            <i class="fa fa-fw fa-briefcase text-muted"></i>
                            <?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
                            <? if ($case['status'] == 'closed') { ?><i class="fa fa-lock text-muted"></i><? } ?>
                            <? if ($case['status'] == 'onhold') { ?><i class="fa fa-clock-o text-muted"></i><? } ?>
                            <? if ($case['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
                            <? if ($case['deal_status'] == 'lost') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
                            <?= date('n/Y', strtotime($case['created_at'])) ?>
                            <?= $case['owner']['name'] ?>
                        </div>
                        <div class="card-body">
                            <?php if ($case['deal_status'] == 'won'): ?>
                                <? foreach ($theUser['bookings'] as $booking) { ?>
                                <? if ($booking['case_id'] == $case['id'] && $booking['product']) {
                                    $has_booking = true;
                                ?>
                                <div>
                                    <i class="fa fa-car text-muted"></i> 
                                    <?= Html::a($booking['product']['op_code'], '@web/tours/r/'.$booking['product']['tour']['id']) ?>
                                    (
                                    <?= $booking['product']['day_count'] ?>d
                                    <?= $booking['pax'] ?>p
                                    <?= date('j/n/Y', strtotime($booking['product']['day_from'])) ?>
                                    <?= $booking['product']['tour']['tour_type'] ?>
                                    <?= $booking['product']['tour']['tour_type'] ?>
                                    <?= (intVal($booking['price']) > 0)? $booking['price'].$booking['currency'] : '' ?>
                                    )
                                </div>
                                <? } ?>
                                <? } ?>
                                <?php if (!$has_booking): ?>
                                    <div>
                                    (
                                    <?= ($case['stats']['pax_count_max'] > 0) ? ($case['stats']['pax_count_min']. '-' . $case['stats']['pax_count_max']).'p' : (($case['stats']['pax_count_min'] > 0)? $case['stats']['pax_count_min'].'p': '')?>
                                    <?= ($case['stats']['day_count_max'] > 0) ? ($case['stats']['day_count_min']. '-' . $case['stats']['day_count_max']).'d' : (($case['stats']['day_count_min'] > 0)? $case['stats']['day_count_min'].'d': '')?>
                                    <?= ($case['stats']['tour_start_day'] > 0)? $case['stats']['tour_start_day'].'/': '';?><?= ($case['stats']['tour_start_month'] > 0)? $case['stats']['tour_start_month'].'/': '';?><?= ($case['stats']['tour_start_year'] > 0)? $case['stats']['tour_start_year']: '';?>
                                    <?= ($case['stats']['pa_tour_type'] != '') ? $case['stats']['pa_tour_type']: ''?>
                                    <?= $case['stats']['req_countries']?>
                                    )
                                </div>
                                <?php endif ?>
                            <?php endif ?>
                            <?php if ($case['deal_status'] == 'lost'): ?>
                                <div>
                                    (
                                    <?= ($case['stats']['pax_count_max'] > 0) ? ($case['stats']['pax_count_min']. '-' . $case['stats']['pax_count_max']).'p' : (($case['stats']['pax_count_min'] > 0)? $case['stats']['pax_count_min'].'p': '')?>
                                    <?= ($case['stats']['day_count_max'] > 0) ? ($case['stats']['day_count_min']. '-' . $case['stats']['day_count_max']).'d' : (($case['stats']['day_count_min'] > 0)? $case['stats']['day_count_min'].'d': '')?>
                                    <?= ($case['stats']['tour_start_day'] > 0)? $case['stats']['tour_start_day'].'/': ''?> <?= ($case['stats']['tour_start_month'] > 0)? $case['stats']['tour_start_month'].'/': ''?> <?= ($case['stats']['tour_start_year'] > 0)? $case['stats']['tour_start_year']: ''?>
                                    <?= date('d/m/Y', strtotime($case['closed']))?>
                                    <?= $case['closed_note']?>
                                    )
                                </div>
                            <?php endif ?>
                            <?php if ($case['deal_status'] == 'pending'): ?>
                                <div>
                                    (
                                    <?= ($case['stats']['pax_count_max'] > 0) ? ($case['stats']['pax_count_min']. '-' . $case['stats']['pax_count_max']).'p' : (($case['stats']['pax_count_min'] > 0)? $case['stats']['pax_count_min'].'p': '')?>
                                    <?= ($case['stats']['day_count_max'] > 0) ? ($case['stats']['day_count_min']. '-' . $case['stats']['day_count_max']).'d' : (($case['stats']['day_count_min'] > 0)? $case['stats']['day_count_min'].'d': '')?>
                                    <?= ($case['stats']['tour_start_day'] > 0)? $case['stats']['tour_start_day'].'/': ''?><?= ($case['stats']['tour_start_month'] > 0)? $case['stats']['tour_start_month'].'/': ''?><?= ($case['stats']['tour_start_year'] > 0)? $case['stats']['tour_start_year']: ''?>
                                    <?= ($case['stats']['pa_tour_type'] != '') ? $case['stats']['pa_tour_type']: ''?>
                                    <?= $case['stats']['req_countries']?>
                                    <?= ($case['stats']['budget'] > 0)? ($case['stats']['budget'].$case['stats']['budget_currency']) : ''?>
                                    )
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <? } ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"><?= Yii::t('p', 'More info') ?></h6>
                </div>
                <div class="panel-body">
                    <?php if (!empty($metas)): ?>
                        <?php
                        if (isset($metas['likes']) && $metas['likes']['value'] != ''): 
                            $info = $metas['likes'];
                        ?>

                        <?php
                        if (isset($metas['ambassaddor_potentiality']) && $metas['ambassaddor_potentiality']['value'] != ''): 
                            $info = $metas['ambassaddor_potentiality'];
                            $v = ($info['value'] == 1)? 'Amba': 'Ampo';
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Ambassador potentiality')?>: </strong><a id="customer_ranking_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                        echo '<span class="item_v">'.$v.'</span>';
                                ?>
                            </div>
                        <?php endif ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Likes')?>: </strong><a id="likes_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    foreach (explode('|', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$likeList[intVal($op)].'</span>';
                                    }?>
                            </div>
                        <?php endif ?>
                        <?php
                        if (isset($metas['dislikes']) && $metas['dislikes']['value'] != ''): 
                            $info = $metas['dislikes'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Dislikes')?>: </strong><a id="dislikes_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$dislikeList[intVal($op)].'</span>';
                                    }?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['allergies']) && $metas['allergies']['value'] != ''): 
                            $info = $metas['allergies'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Allergies')?>: </strong><a id="allergies_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                        echo '<span class="item_v">'.$info['value'].'</span>';
                                ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['transportation']) && $metas['transportation']['value'] != ''): 
                            $info = $metas['transportation'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Transportation')?>: </strong><a id="transportation_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$transportationList[intVal($op)].'</span>';
                                    }
                                    if (isset($metas['transportation_note']) && $metas['transportation_note']['value'] != '') {
                                        echo '<br><span class="item_note">(note: '.$metas['transportation_note']['value'].')</span>';
                                    }
                                    ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['health_condition']) && $metas['health_condition']['value'] != ''): 
                            $info = $metas['health_condition'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Health condition')?>: </strong><a id="health_condition_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$healthList[intVal($op)].'</span>';
                                    }
                                    if (isset($metas['health_note']) && $metas['health_note']['value'] != '') {
                                        echo '<br><span class="item_note">(note: '.$metas['health_note']['value'].')</span>';
                                    }
                                    ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['diet']) && $metas['diet']['value'] != ''): 
                            $info = $metas['diet'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Diet')?>: </strong><a id="diet_info" class="btn_edit" title="edit">Edit</a>
                                <?php 
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$dietList[intVal($op)].'</span>';
                                    }
                                    if (isset($metas['diet_note']) && $metas['diet_note']['value'] != '') {
                                        echo '<br><span class="item_note">(note: '.$metas['diet_note']['value'].')</span>';
                                    }
                                ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['travel_preferences']) && $metas['travel_preferences']['value'] != ''): 
                            $info = $metas['travel_preferences'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Travel preferences')?>: </strong><a id="travel_preferences_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$travelPrefList[intVal($op)].'</span>';
                                    }?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['traveler_profile']) && $metas['traveler_profile']['value'] != ''): 
                            $info = $metas['traveler_profile'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Traveler profile')?>: </strong><a id="traveler_profile_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$customerProfileList[intVal($op)].'</span>';
                                    }?>
                            </div>
                        <?php endif ?>

                         <?php
                        if (isset($metas['traveler_profile_assoc_names']) && $metas['traveler_profile_assoc_names']['value'] != ''): 
                            $info = $metas['traveler_profile_assoc_names'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Traveler profile assoc names')?>: </strong><a id="traveler_profile_assoc_names_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                        echo '<span class="item_v">'.$info['value'].'</span>';
                                ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['customer_ranking']) && $metas['customer_ranking']['value'] != ''): 
                            $info = $metas['customer_ranking'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Customer ranking')?>: </strong><a id="customer_ranking_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                        echo '<span class="item_v">'.$info['value'].'</span>';
                                ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (isset($metas['future_travel_wishlist']) && $metas['future_travel_wishlist']['value'] != ''): 
                            $info = $metas['future_travel_wishlist'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Future travel wish')?>: </strong><a id="future_travel_wishlist_info" class="btn_edit" title="edit">Edit</a>
                                <?php
                                    $country = ArrayHelper::map($countryList, 'code', 'name');
                                    foreach (explode(',', $info['value']) as $op) {
                                        echo '<span class="item_v">'.$country[trim($op)].'</span>';
                                    }?>
                            </div>
                        <?php endif ?>

                        <?php if (isset($metas['active_social_networks']) && $metas['active_social_networks']['value'] != ''): 
                                $info = $metas['active_social_networks'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Active social networks')?>: </strong>
                                <?php foreach (explode(',', $info['value']) as $v): ?>
                                    <?= '<span class="item_v">'.$social_network[$v].'</span>';?>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>

                        <?php if (isset($metas['rel_with_amica']) && $metas['rel_with_amica']['value'] != ''): 
                                $info = $metas['rel_with_amica'];
                        ?>
                            <div class="wrap_item"><strong><?= Yii::t('p', 'Relationship with AMICA TRAVEL')?>: </strong>
                                <?= '<span class="item_v">'.$info['value'].'</span>';?>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"><?= Yii::t('p', 'Communication') ?></h6>
                </div>
                    <div class="panel-body">
                    <p><strong><?= Yii::t('p', 'LATEST EMAIL & NOTES')?></strong> <?= Html::a('View all notes by this person', '@web/notes?from='.$theUser['id']) ?></p>
                    <? foreach ($userMails as $mail) { ?>
                    <div class="mb">
                        <i class="fa text-muted fa-envelope-o"></i>
                        <?= Html::a($mail['subject'] == '' ? '( No subject )' : $mail['subject'], '@web/mails/r/'.$mail['id']) ?>
                        <span class="text-muted"><?= Yii::$app->formatter->asDatetime($mail['sent_dt'], 'php:j/n/Y H:i') ?></span>
                    </div>
                    <? } ?>

                    <? foreach ($userNotes as $note) { ?>
                    <div class="mb">
                        <? if ($note['via'] == 'email') { ?>
                        <i class="fa text-muted fa-envelope-o"></i>
                        <? } else { ?>
                        <i class="fa text-muted fa-edit"></i>
                        <? } ?>
                        <?= Html::a($note['title'] == '' ? '( No title )' : $note['title'], '@web/notes/r/'.$note['id']) ?>
                        <span class="text-muted"><?= Yii::$app->formatter->asDatetime($note['co'], 'php:j/n/Y H:i') ?></span>
                    </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?
}
?>
<!-- Modal -->
<div class="modal fade" id="userModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
        <div class="modal-content">
            <form method="POST" accept-charset="utf-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('p', 'Person info')?></h4>
                </div>
                <div class="modal-body">
                    <div id="wrap_fieldset"></div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-primary" id="save_user">Save</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="extra" class="hidden">
    <fieldset class="wrap_input" id="user_basic_info">
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= Html::label('First name', ['class' => 'control-label']) ?>
                <?= Html::input('text','fname', '', ['class' => 'form-control']); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?= Html::label('Last name', ['class' => 'control-label']) ?>
                <?= Html::input('text','fname', '', ['class' => 'form-control']); ?>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?= Html::label('Gender', ['class' => 'control-label']) ?>
                <?= Html::dropDownList('gender', '', $genderList, ['class' => 'form-control']) ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <?= Html::label('Date of birth (day/month/year)', ['class' => 'control-label']) ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= Html::input('number','d_birth', '', ['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= Html::input('number','m_birth', '', ['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= Html::input('number','y_birth', '', ['class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-group">
                    <?= Html::label('Gender', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('gender', '', $genderList, ['class' => 'form-control']) ?>
                </div>
            </div> -->
            <div class="col-md-3">
                <div class="form-group">
                    <?= Html::label('Nationality', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('country', '', ArrayHelper::map($countryList, 'code', 'name'), ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?= Html::label('Language', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('lang', '', $languageList, ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?= Html::label('Marital status', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('marital', '', $maritalStatusList, ['class' => 'form-control']) ?>
                </div>
            </div>
        </div>
        <div class="row">
        </div>
    </fieldset>
    <fieldset class="wrap_input" id="user_contact_info">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::label('Who relation', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('who_relation', '', [], ['class' => 'form-control box_search', 'style' => 'width: 100%']) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::label('Relatives', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('relation', '', $relationList, ['class' => 'form-control']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <?= Html::label('Relatives', ['class' => 'control-label']) ?>
                    <?= Html::dropDownList('gender', '', $relationList, ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <?= Html::label('Date of birth (day/month/year)', ['class' => 'control-label']) ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= Html::input('number','d_birth', '', ['class' => 'form-control']); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= Html::input('number','m_birth', '', ['class' => 'form-control']); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= Html::input('number','y_birth', '', ['class' => 'form-control']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="wrap_input" id="user_likes_info">
        
    </fieldset>
    <fieldset class="wrap_input" id="user_disLikes_info">
        
    </fieldset>
</div>
<?
$js = <<<TXT
    $('.btn_edit').click(function(e){
        importForm(e.target);
        $('#userModal').modal('show');
    });
    $("#userModal").on('hide.bs.modal', function () {
        exportForm();
    });
    $('.box_search').select2({
        ajax:{
            url: "/default/search",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                console.log(params);
              return {
                    u:1,
                    q: params.term, // search term
                    page: params.page || 1
              };
            },
            processResults: function (data, params) {
              params.page = params.page || 1;
              return {
                results: data.items,
                pagination: {
                  more: (params.page * 30) < data.total_count
                }
              };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 3,
        templateResult: formatRepo,
        // templateSelection: formatRepoSelection
    });

    function importForm(element){
        if ($(element).attr('id') == 'basic_info') {
            $('#wrap_fieldset').append($('#user_basic_info'));
        }
        if ($(element).attr('id') == 'contact_info') {
            $('#wrap_fieldset').append($('#user_contact_info'));
        }
        if ($(element).attr('id') == 'likes_info') {
            $('#wrap_fieldset').append($('#user_likes_info'));
        }
        if ($(element).attr('id') == 'disLikes_info') {
            $('#wrap_fieldset').append($('#user_disLikes_info'));
        }
    }
    function exportForm(){
        if ($('#wrap_fieldset').find('.wrap_input').length > 0) {
            $('#extra').append($('#wrap_fieldset .wrap_input'));
        }
    }
TXT;
//$this->registerCssFile(DIR.'assets/fancyapps/fancybox/jquery.fancybox.css');
//$this->registerJsFile(DIR.'assets/fancyapps/fancybox/lib/jquery.mousewheel-3.0.6.pack.js', ['depends'=>'app\assets\MetronicAsset']);
//$this->registerJsFile(DIR.'assets/fancyapps/fancybox/jquery.fancybox.pack.js', ['depends'=>'app\assets\MetronicAsset']);
$this->registerJs($js);

