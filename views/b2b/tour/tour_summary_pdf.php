<?php

use yii\helpers\Html;
use yii\helpers\Inflector;

// \fCore::expose($_POST); exit;

?>
<htmlpageheader name="myheader"><img src="/upload/huan/si_header.jpg"/></htmlpageheader>

<div id="content">
    <h1 style="color:#222"><?= $_POST['SiTourSummaryForm']['tour_code'] ?> <?= $_POST['SiTourSummaryForm']['tour_name'] ?> | <?= $_POST['SiTourSummaryForm']['tour_company'] ?></h1>

    <?php if (is_array($_POST['d_date']) && count($_POST['d_date']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'PROGRAM IN BRIEF') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Day') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Date') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Itinerary') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Guide & Driver') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Meals') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['d_date']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td width="30" class="text-center text-muted"><?= $i ?></td>
                <td><?= $_POST['d_date'][$i] ?></td>
                <td><?= $_POST['d_name'][$i] ?></td>
                <td><?= $_POST['d_guides'][$i] ?></td>
                <td><?= $_POST['d_meals'][$i] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (is_array($_POST['p_name']) && count($_POST['p_name']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'LIST OF TRAVELLERS') ?></h3>
    <table class="table table-bordered _table-condensed">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'No.') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Title') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Full name') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Age') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Rooming') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['p_name']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td width="30" class="text-muted"><?= $i ?></td>
                <td><?= $_POST['p_title'][$i] ?></td>
                <td><?= $_POST['p_name'][$i] ?></td>
                <td><?= $_POST['p_age'][$i] ?></td>
                <td><?= $_POST['p_rooming'][$i] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (is_array($_POST['h_dest']) && count($_POST['h_dest']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'ACCOMMODATIONS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Destination') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Hotel/Resort & Website') ?></th>
                <!-- th><?= Yii::t('si_tour_summary', 'No. of nights') ?></th -->
                <th><?= Yii::t('si_tour_summary', 'Room type') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['h_dest']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td><?= $_POST['h_dest'][$i] ?></td>
                <td><?= $_POST['h_url'][$i] == '' ? $_POST['h_name'][$i] : Html::a($_POST['h_name'][$i], $_POST['h_url'][$i]) ?></td>
                <td><?= $_POST['h_room'][$i] ?></td>
            </tr>
            <?php } ?>
    </table>
    <?php } ?>

    <?php if (is_array($_POST['f_route']) && count($_POST['f_route']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'FLIGHTS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Route') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Number') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Departure time') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Arrival time') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['f_route']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td><?= $_POST['f_route'][$i] ?></td>
                <td><?= $_POST['f_number'][$i] ?></td>
                <td><?= $_POST['f_departure'][$i] ?></td>
                <td><?= $_POST['f_arrival'][$i] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (is_array($_POST['t_route']) && count($_POST['t_route']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'TRAINS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Train route') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Number') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Departure time') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Arrival time') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['t_route']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td><?= $_POST['t_route'][$i] ?></td>
                <td><?= $_POST['t_number'][$i] ?></td>
                <td><?= $_POST['t_departure'][$i] ?></td>
                <td><?= $_POST['t_arrival'][$i] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (is_array($_POST['g_name']) && count($_POST['g_name']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'TOUR GUIDE DETAILS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Tour guide') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Contact number') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Dates') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['g_name']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td><?= $_POST['g_name'][$i] ?></td>
                <td><?= $_POST['g_tel'][$i] ?></td>
                <td><?= $_POST['g_time'][$i] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (is_array($_POST['s_name']) && count($_POST['s_name']) > 1) { ?>
    <h3><?= Yii::t('si_tour_summary', 'EMERGENCY CONTACTS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Person in charge') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Contact number') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($_POST['s_name']); $i ++) { ?>
            <tr class="<?= $i%2 == 0 ? 'bg1': 'bg0' ?>">
                <td><?= $_POST['s_name'][$i] ?></td>
                <td><?= $_POST['s_tel'][$i] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (isset($_POST['SiTourSummaryForm']['tour_note']) && $_POST['SiTourSummaryForm']['tour_note'] != '') { ?>
    <h3><?= Yii::t('si_tour_summary', 'NOTES') ?></h3>
    <p><?= nl2br($_POST['SiTourSummaryForm']['tour_note']) ?></p>
    <?php } ?>
</div>

<htmlpagefooter name="myfooter"><img src="/upload/huan/si_footer.png"/></htmlpagefooter>
