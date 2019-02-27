
<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\FileHelper;

require_once(Yii::getAlias('@webroot') . '/../textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

include('_program_inc.php');

$dayIdList = explode(',', $theProduct['day_ids']);

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

Yii::$app->params['page_title'] = 'Export to Word file: '.$theProduct['title'];
Yii::$app->params['body_class'] = 'sidebar-xs';


$b2bBannerFiles = [
    ['Song Saa', '19-song_saa.jpg'],
    ['South Laos expedition', '10-south_laos_expedition.jpg'],
    ['Koh Ker Cambodia', '44-koh_ker_cambodia.jpg'],
    ['Si Phan Don Laos', '26-si_phan_don_laos.jpg'],
    ['Angkor Wat Cambodia', '12-angkor_vat_cambodia.jpg'],
    ['Amanoi', '8-amanoi.jpg'],
    ['Hoi An', '100_Hoi_An_4.jpg'],
    ['Katu South Laos', '35-katu_south_laos.jpg'],
    ['Mekong River Luang Prabang', '22-mekong_river_luang_prabang.jpg'],
    ['Binh Thuan Vietnam', '20-binh_thuan_province_vietnam.jpg'],
    ['Gong Festival Highlands Vietnam', '64-gong_festival_highlands_vietnam.jpg'],
    ['Uxo Laos', '82-uxo_laos2.jpg'],
    ['Topas Eco Lodge Sapa Vietnam', '85-topas_eco_lodge_sapa_vietnam.jpg'],
    ['Opa North Laos', '18-opa_north_laos.jpg'],
    ['Ha Giang North Vietnam', '45-ha_giang_north_vietnam.jpg'],
    ['CIA road South Laos', '25-cia_road_south_laos.jpg'],
    ['Nam Ngu North Vietnam', '79-nam_ngu_north_vietnam.jpg'],
    ['Mondulkiri Cambodia', '33-mondulkiri_cambodia.jpg'],
    ['Koh Sdach Cambodia', '32-koh_sdach_cambodia.jpg'],
    ['Phum Baitang Cambodia', '84-phum_baitang_cambodia.jpg'],
    ['Lak Lak', '51-lak_lak.jpg'],
    ['Laos secret war', '74-laos_secret_war.jpg'],
    ['Uxo North Laos', '3-uxo_north_laos.jpg'],
    ['Devata Cambodia', '59-devata2_cambodia.jpg'],
    ['Saoch Cambodia', '40-saoch_cambodia.jpg'],
    ['Hue, Vietnam', '93_Hue.jpg'],
    ['Luma Phongsaly Laos', '41-luma_phongsaly_laos.jpg'],
    ['Red river delta Vietnam', '36-red_river_delta_vietnam.jpg'],
    ['Plain of Jars North Laos', '9-plain_of_jars_north_laos.jpg'],
    ['Tam Coc Garden', '87-tam_coc_garden.jpg'],
    ['Khmer Cambodia', '37-khmer_cambodia.jpg'],
    ['Uxo Laos', '36-uxo_laos.jpg'],
    ['Hanoi pottery work', '30-hanoi_potter_work.jpg'],
    ['Water puppets Hanoi', '95_Water_puppets_Hanoi.jpg'],
    ['Luma Phongsaly Laos', '34-luma_phongsaly_laos.jpg'],
    ['Katu South Laos', '17-katu_south_laos.jpg'],
    ['Halong Bay Vietnam', '47-halong_bay_vietnam.jpg'],
    ['Hue', '94_Hue_2.jpg'],
    ['Katu South Laos', '86-katu_south_laos.jpg'],
    ['Hoi An Ancient House Village', '101_Hoi_An_Ancient_House_Village.jpg'],
    ['Hmong Vietnam', '70-hmong_vietnam.jpg'],
    ['Red river Delta Vietnam', '14-red_delta_north_vietnam.jpg'],
    ['Stung Sen Cambodia', '24-stung_sen_cambodia.jpg'],
    ['Lolo North Vietnam', '2-lolo_north_vietnam.jpg'],
    ['Cap Padaran dunes', '102_Cap_Padaran_dunes.jpg'],
    ['Ba Be lake Vietnam', '57-babe_lake_north_vietnam.jpg'],
    ['Mai Hich', '104_Mai_Hich.jpg'],
    ['Fusion Cam Ranh', '66-fusion_cam_ranh.jpg'],
    ['La Xuyen village Vietnam', '75-laxuyen_village_vietnam.jpg'],
    ['Tam Coc Vietnam', '50-tam_coc_vietnam.jpg'],
    ['Vat Phu Laos', '76-vat_phu_laos.jpg'],
    ['Mondulkiri Cambodia', '46-mondulkiri_cambodia2.jpg'],
    ['Opa North Laos', '21-opa_north_laos 3.jpg'],
    ['Lolo North Vietnam', '6-lolo_north_vietnam2.jpg'],
    ['Mekong Delta fishermen', '62-mekong_delta_fishermen.jpg'],
    ['Hmong North Vietnam', '48-hmong_north_vietnam.jpg'],
    ['Devata Cambodia', '27-devata_cambodia.jpg'],
    ['Bouddha', '63-bouddha.jpg'],
    ['Yeak Cambodia', '88-yeak_cambodia.jpg'],
    ['Khai Dinh Mausoleum, Hue', '97_Hue_Khai_Dinh.jpg'],
    ['Southern Vietnam lost forest', '78-southern_vietnam_lost_forest.jpg'],
    ['Katu Ht Xekong', '72-katu_ht_xekong2.jpg'],
    ['Ha Long Vietnam', '69-ha_long_vietnam2.jpg'],
    ['Vietnam War', '89-vietnam_war.jpg'],
    ['Hoian Vietnam', '99_hoi_An_3.jpg'],
    ['Hoian Vietnam', '92_Hoi_An.jpg'],
    ['Tu Duc Mausoleum, Hue', '96_Hue_Tu_Duc.jpg'],
    ['Halong Vietnam', '28-halong_bay_vietnam.jpg'],
    ['Siphandon Laos', '54-si_phan_don_laos.jpg'],
    ['Laotian secret war tour', '23-laotian_secret_war_tour.jpg'],
    ['Khmer Cambodia', '39-khmer_cambodia.jpg'],
    ['Pagoda in Bac Giang, Vietnam', '61-bac_giang_pagoda_vietnam.jpg'],
    ['Angkor Wat, Cambodia', '56-angkor_vat_cambodia2.jpg'],
    ['Boping Cambodia', '5-boping_cambodia.jpg'],
    ['Khmer Cambodia', '43-khmer_cambodia.jpg'],
    ['Cordillere annamitique', '58-cordillere_annamitique.jpg'],
    ['Halong Vietnam', '15-halong_bay_north_vietnam.jpg'],
    ['Ope North Laos', '7-opa_north_laos.jpg'],
    ['Phat Sanday Cambodia', '90-phat_sanday_cambodia.jpg'],
    ['Hoian', '98_Hoi_An_2.jpg'],
    ['Hmong Laos', '83-hmong_laos2.jpg'],
    ['Cai Rang floating village', '53-cai_rang_floating_village_vietnam.jpg'],
    ['Elephant Mount Laos', '77-elephant_mount_laos.jpg'],
    ['Nam Ou River Laos', '80-nam_ou_river_norht_laos.jpg'],
    ['Upper Xekong South Laos', '49-upper_xekong_south_laos.jpg'],
    ['Ha Giang North Vietnam', '68-ha_giang_north_vietnam.jpg'],
    ['Tomo temple Laos', '60-tomo_temple_laos.jpg'],
    ['White Hmong Cao Bang', '31-white_hmÃ´ng_cao_bang.jpg'],
    ['Flower Hmong Vietnam', '65-flower_hmong_vietnam.jpg'],
    ['Opa North Laos', '81-opa_north_laos.jpg'],
    ['Mekong Delta Vietnam', '4-mekong_delta_south_vietnam.jpg'],
    ['Cham South Vietnam', '1-cham_south_vietnam.jpg'],
    ['Indochina war', '67-indochina_war.jpg'],
    ['Tonle Sap Cambodia', '29-tonle_sap_cambodia.jpg'],
    ['Amanoi Vietnam', '55-amanoi_vinh_hy_vietnam.jpg'],
    ['Nui Chua Vietnam', '103_Nui_Chua.jpg'],
    ['Stung Sen Cambodia', '11-stung_sen_cambodia.jpg'],
    ['Katu ht Xekong', '71-katu_ht_xekong.jpg'],
    ['Buddha statues Laos', '52-buddha_statues_laos.jpg'],
    ['Ha Giang, Vietnam', '38-ha_giang_north_vietnam.jpg'],
    ['Lolo Vietnam', '13-lolo_north_vietnam3.jpg'],
    ['Angkor Thom, Cambodia', '16-angkor_thom_cambodia.jpg'],
    ['Khmer Rouge, Cambodia', '73-khmer_rouge_cambodia.jpg'],
    ['Thong Nong, Vietnam', '42-thong_nong_nord_vietnam.jpg'],
];

?>
<div class="col-md-8">
    <div class="panel panel-body" style="font-family:Bell MT; font-size:11pt;">
        <p>
            <a id="select-header-image" href="#">Change header image</a>
            <a id="cancel-header-image" href="#" style="display:none;">Cancel</a>
        </p>
        <div id="header-image-list-wrapper" style="display:none; font:12px/16px Arial, sans-serif; height:500px; overflow-y:scroll;">
            <p>Click on an image to set as header image.</p>
            <div class="row header-image-list">
                <? $cnt = 0; foreach ($b2bBannerFiles as $img) { $cnt ++; ?>
                <div class="col-sm-3 text-center header-image-list-item">
                    <div><img class="cursor-pointer img-responsive" title="<?= $img[0] ?>" src="/timthumb.php?w=180&h=120&src=/upload/devis-banners/b2b/<?= $img[1] ?>"></div>
                    <div><?= $img[0] ?></div>
                </div>
                <? if ($cnt == 4) { $cnt = 0; ?><div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div><? } ?>
                <? } ?>
            </div>
        </div>

        <!-- BEGIN DOC -->

        <p id="header-image">
            <img class="img-responsive" src="/upload/devis-banners/b2b/12-angkor_vat_cambodia.jpg">
        </p>
        <span id="devis-guest" style='font-family:Bell MT; font-size: 14pt;  font-weight: bold;  color: rgb(228,58,146); line-height: 25pt; font-variant: small-caps;'><?= $theProduct['about'] ?></span>
        <h3 id="devis-name" style="font-family:Bell MT; font-size: 13pt; margin: 0;  padding: 0;  font-weight: bold; font-variant: small-caps;"><?=preg_replace("/[^a-zA-Z]*devis\d[^a-zA-Z]*/","",$theProduct['title']) ?></h3>
        <br>

        <p><strong>ESPRIT</strong></p>
        <div id="text-esprit">
            <div style='font-family:Bell MT; font-size: 9pt;'><?= $theProduct['metas']['text_esprit']['value'] ?? '' ?></div>
        </div>

        <p><strong>POINTS FORTS</strong></p>
        <div id="text-points">
            <div style='font-family:Bell MT; font-size: 9pt; '><?= $theProduct['metas']['text_points']['value'] ?? '' ?></div>
        </div>

        <h2 style="font-family: Bell MT;border-bottom:1px solid rgb(228,58,146); color:rgb(228,58,146); font-size: 13pt; font-variant: small-caps;">Programme en bref </h2>
        <table border="1" id='devis-table-programe' style='margin: 0; padding: 0; width: 100%;border-collapse: collapse;border-color: rgb(191,191,191);'>
            <thead>
                <tr style="margin: 2mm 0;">
                    <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid; border-color: rgb(191,191,191);width: 10%; padding: 2mm 1mm 2mm 0;"><h3 style="font-family:Bell MT; font-size:11pt; color:rgb(228,58,146); margin:0;">Jour</h3></th>
                    <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191); width:  18%;"><h3 style="font-family:Bell MT; font-size:11pt; color:rgb(228,58,146); margin:0; padding-right: 1mm;">Date</h3></th>
                    <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);width:  39.1%;"><h3 style="font-family:Bell MT; font-size:11pt; color:rgb(228,58,146); margin:0;padding-right: 1mm;">Itinéraire</h3></th>
                    <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);width: 22.6%;"><h3 style="font-family:Bell MT; font-size:11pt; color:rgb(228,58,146); margin:0;padding-right: 1mm;">Accompagnement</h3></th>
                    <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);width: 17%;"><h3 style="font-family:Bell MT; font-size:11pt; color:rgb(228,58,146); margin:0;">Repas inclus</h3></th>
                </tr>
            </thead>
            <?
            $cnt = 0;
            foreach ($dayIdList as $di) {
                foreach ($theProduct['days'] as $ng) {
                    if ($ng['id'] == $di) {
                        $cnt ++;
                        $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                        $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                        $ngay_fr = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
                        $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                        ?>
                        <tr>
                            <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT; font-size:11pt;padding: 5pt 2pt;">Jour <?= $cnt ?></td>
                            <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT; font-size:11pt;padding: 5pt 2pt;"><?= $ngay ?></td>
                            <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT; font-size:11pt;padding: 5pt 2pt;"><?= $ng['name'] ?></td>
                            <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT; font-size:11pt;padding: 5pt 2pt;"><?= $ng['guides'] ?></td>
                            <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT; font-size:11pt;padding: 5pt 2pt;">
                            <?
                            if (strpos($ng['meals'], 'B') !== false) {
                                echo 'B ';
                            } else {
                                echo '&mdash; ';
                            }
                            if (strpos($ng['meals'], 'L') !== false) {
                                echo 'L ';
                            } else {
                                echo '&mdash; ';
                            }
                            if (strpos($ng['meals'], 'D') !== false) {
                                echo 'D ';
                            } else {
                                echo '&mdash; ';
                            }
                            ?>
                            </td>
                        </tr>
                        <?
                    }
                }
            }
            ?>
        </table>
            <h2 style="font-family: Bell MT; border-bottom:1px solid rgb(228,58,146); color:rgb(228,58,146); font-size: 13pt; font-variant: small-caps;">Programme en details</h2>
            <div id='devis-detail' style="font-size: 12pt;">
                <?
                $cnt = 0;
                foreach ($dayIdList as $di) {
                    foreach ($theProduct['days'] as $ng) {
                        if ($ng['id'] == $di) {
                            $cnt ++;
                            $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                            $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                            $ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
                            $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                            $ngay = str_replace('|', '<span style="">|</span>', $ngay);
                            ?>
                    <h4 style="font-size:11pt;font-family:Bell MT;font-variant: small-caps;">Jour <?= $cnt ?> - <?= $ngay ?><span style=""> | <?= $ng['name'] ?> (<?= implode(', ',str_split($ng['meals'])) ?>)</span></h4>
                    <div style="font-family:Bell MT; font-size:11pt;">
                    <?= $ng['transport'] == '' ? '' : '<p style="font-family:Bell MT; font-size:11pt;">' . $ng['transport'] . '</p>' ?>
                    <?
                    $txxt = $parser->parse($ng['body']);
                    $txxt = str_replace('<p>', '<p style="font-family:Bell MT; font-size:11pt;line-height: 20pt; text-align: justify;">', $txxt);
                    $txxt = str_replace('<li>', '<li style="font-family:Bell MT; font-size:11pt;">', $txxt);
                    $txxt = str_replace('<strong>', '<strong style="font-family:Bell MT; font-size:11pt;">', $txxt);
                    $txxt = str_replace('<em>', '<em style="font-family:Bell MT; font-size:11pt;">', $txxt);
                    $txxt = str_replace(['<span class="caps">', '</span>'], ['', ''], $txxt);
                    echo $txxt;
                    ?>
                    </div>
                            <?
                        }
                    }
                }
                ?>
            </div>
            <h2 style="font-family: Bell MT; border-bottom:1px solid rgb(228,58,146); color:rgb(228,58,146); font-size: 13pt; font-variant: small-caps;">Hebergements</h2>
            <div id='devis-table-tarif'>
                <table border="1" class="simple" style="width: 100%; margin: 0; padding: 0;border-collapse: collapse; border-color: rgb(191,191,191);">
                    <?
                    // Gia va cac options
                    $theProductpx = $theProduct['prices'];
                    $theProductpx = explode(chr(10), $theProductpx);
                    $last = array();
                    for ($i = 1; $i < count($theProductpx); $i++) {
                        if (substr($theProductpx[$i], 0, 7) == 'OPTION:') {
                            $last[] = $i;
                        }
                    }
                    $optcnt = 0;
                    $count = 0;
                    foreach ($theProductpx as $theProductp) {
                        $count++;

                        if (substr($theProductp, 0, 7) == 'OPTION:') {
                            $optcnt ++;
                            if ($optcnt != 1) echo '</table>' . chr(10) . '<table class="simple" style="width: 100%; margin: 0;border-collapse: collapse;border-spacing: 1mm;">';
                            echo '<p style="font-family:Bell MT; font-size:11pt;color:rgb(228,58,146);"><strong>' . trim(substr($theProductp, 7)) . '</strong></p>';
                            echo '<tr style="height: 10mm;">
                            <th style="text-align:center;padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid rgb(191,191,191); border-right: 1px solid rgb(191,191,191);width: 22%;"><h3 style="background: none;font-family:Bell MT; color:rgb(228,58,146); font-size:11pt;">Destinations</h3></th>
                            <th style="text-align:center;padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid rgb(191,191,191); border-right: 1px solid rgb(191,191,191); width: 30%;"><h3 style="background: none;font-family:Bell MT; color:rgb(228,58,146); font-size:11pt;">Hôtel/Resort & Website</h3></th>
                            <th style="text-align:center;padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid rgb(191,191,191); border-right: 1px solid rgb(191,191,191); width: 18%;"><h3 style="background: none;font-family:Bell MT; color:rgb(228,58,146); font-size:11pt;">Nuitée(s)</h3></th >
                            <th style="text-align:center;padding-left: 2mm;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); width: 30%;font-size:11pt;"><h3 style="background: none;font-family:Bell MT; color:rgb(228,58,146); font-size:11pt;">Type de chambre</h3></th>
                            </tr>';
                        }
                        if (substr($theProductp, 0, 2) == '+ ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 5; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            echo '<tr>
                                <td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT; font-size:11pt;padding: 5pt 2mm;"><span style="background-color: none;">'.$line[0].'</span></td>
                                <td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT; font-size:11pt; padding: 5pt 2mm;"><span style="background-color: none;">'.(trim($line[3]) != '' ? Html::a( trim($line[1]), 'http://'.trim($line[3]), ['class' => 'profile-link','style'=>'color: black;']) : trim($line[1])). '</span></td>
                                <td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT; font-size:11pt;  padding: 5pt 2mm;"><span style="background-color: none;">' . $line[4] . '</span></td>
                                <td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT; font-size:11pt; padding: 5pt 2mm;" class="a-href"><span style="background-color: none;">' . trim($line[2]) . '</span></td></tr>';
                        }

                        if (substr($theProductp, 0, 2) == '- ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            $line[1] = trim($line[1]);
                            if (($count == count($theProductpx)) || in_array($count, $last)) {
                                echo '<tr><td style="font-family:Bell MT; font-size:11pt;  padding: 2mm 1mm 2mm 2mm;border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);" colspan="3" class="ta-r"><span style="background-color: none;">' . $line[0] . '</span></td><td style="text-align: center;border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);"><h3 style="font-size:11pt;font-family:Bell MT; background-color: none;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            } else {
                                echo '<tr><td style="border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);font-family:Bell MT; font-size:11pt;  padding: 2mm 1mm 2mm 2mm;" colspan="3" class="ta-r"><span style="background-color: none;">' . $line[0] . '</span></td><td style="text-align: center;border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);"><h3 style="font-size:11pt;font-family:Bell MT; background-color: none;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            }
                        }
                    }
                    ?>
                </table>
            </div>

            <h2 style="font-family: Bell MT; border-bottom:1px solid rgb(228,58,146); color:rgb(228,58,146); font-size: 13pt;font-variant: small-caps;">Tarifs</h2>
            <div id='devis-condition'>
                <p style="font-family:Bell MT; font-size:11pt;">
                    Valable jusqu’au ...................... . Tarifs en <b>dollar américain/personne</b> hors des frais bancaires.
                </p>
                <table border="1" cellspacing="0" cellpadding="0" width="" class="table-price" style="width: 100%; margin: 0;border-collapse: collapse; margin: 0; padding: 0; border-color: rgb(191,191,191);">
                    <tr>
                        <td style="background-color: rgb(200,200,200); color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">Taille du groupe</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">2<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">3<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">4<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">5<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">6<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">7<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">8<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">9<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">10<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">11<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(228,58,146); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">12<br>pax</span></strong></td>
                    </tr>
                    <tr>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);">Prix/pax</td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(240,240,240); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><span style="background-color: none;">SGL sup</span></td>
                        <td style="background-color: rgb(240,240,240);font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);" colspan="11"></td>
                    </tr>
                </table>
                <br>
                <table border="1" cellspacing="0" cellpadding="0" width="" style="width: 100%; margin: 0;border-collapse: collapse; margin: 0; padding: 0;border-color: rgb(191,191,191);">
                    <tr>

<?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
?>
<?
$condText = $parser->parse($theProduct['conditions']);
//var_dump($condText);exit;
$condText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend', 'Ce prix ne comprend pas', '</ul>'], ['<td style="vertical-align: top; width: 50%; padding-bottom: 20px;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><h3 style="font-size:11pt; color:black;font-family:Bell MT; text-align:center;">', '<ul style="margin:0 0 0 0; list-style-type: square;">', '<p style="font-family:Bell MT; font-size:11pt; margin:0; line-height: 20pt;">', '<li style="font-family:Bell MT; font-size:11pt; line-height: 20pt;">', 'INCLUSIONS', 'EXCLUSIONS','</ul></td>'], $condText);
//var_dump($condText);exit;
echo $condText;

?>
                    </tr>
                </table>
            </div>
            <h2 style="font-family: Bell MT; border-bottom:1px solid rgb(228,58,146); color:rgb(228,58,146); font-size: 13pt; font-variant: small-caps;">Notes</h2>
            <div id='devis-others'>
                <?
                    $otherText = $parser->parse($theProduct['others']);
                    //var_dump($otherText);exit;
                    $otherText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend', 'Ce prix ne comprend pas'], ['<h3 style="font-size:11pt; color:rgb(228,58,146);font-family:Bell MT;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Bell MT; font-size:11pt; margin:0; line-height: 20pt;">', '<li style="font-family:Bell MT; font-size:11pt; line-height: 20pt;">', 'INCLUSIONS', 'EXCLUSIONS'], $otherText);
                    echo $otherText;
                ?>
            </div>
<!-- END DOC -->
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-body">
        <p><img class="img-responsive" src="https://www.amica-travel.com/assets/img/devis-ims/secretindochina/Devis_B2b_Fr_New.png"></p>
        <p><button id="download-word-file" type="button" class="btn btn-primary btn-block">Download Word file</button></p>
        <!-- <p class="text-center"><a href="?pdf" class="text-danger" target="_blank">Want PDF? Try the beta PDF file.</a></p> -->
        <p class="text-center"><a href="https://www.amica-travel.com/imsprint-b2b-en/<?= $theProduct['id'] ?>/<?= md5($theProduct['created_at']) ?>" target="_blank">Want to use B2B English template? Click here.</a></p>
    </div>
</div>
<script>
var file_name = 'Devis_<?= $theProduct['id'] ?>_<?= date('Ymd-His') ?>';
var template = '_SecretIndochina.docx';
var header_image = '12-angkor_vat_cambodia.jpg';
var devis_name = '<p style="font-family:Bell MT; font-size: 13pt; margin: 0; padding: 0; font-weight: bold; font-variant: small-caps;"><?= $theProduct['title'] ?></p>';
</script>
<?
$js = <<<'TXT'
$('#download-word-file').on('click', function(){
    $(this).html('Generating file. Please wait...').addClass('disabled')

    var devis_guest = $('#devis-guest').clone().wrap('<p>').parent().html();
    var devis_intro = $('#text-esprit').clone().wrap('<p>').parent().html();
    var devis_description = $('#text-points').clone().wrap('<p>').parent().html();
    var devis_table_programe = $('#devis-table-programe').clone().wrap('<p>').parent().html();
    var devis_detail = $('#devis-detail').clone().wrap('<p>').parent().html();
    var devis_table_tarif = $('#devis-table-tarif').clone().wrap('<p>').parent().html();
    var devis_condition = $('#devis-condition').clone().wrap('<p>').parent().html();
    var devis_others = $('#devis-others').clone().wrap('<p>').parent().html();

    var jqxhr = $.ajax({
        url: '?docx',
        type: 'post',
        data: {
            file_name: file_name,
            template: template,
            header_image: header_image,
            devis_name: devis_name,
            devis_guest: devis_guest,
            devis_intro: devis_intro,
            devis_description: devis_description,
            devis_table_programe: devis_table_programe,
            devis_detail: devis_detail,
            devis_table_tarif: devis_table_tarif,
            devis_condition: devis_condition,
            devis_others: devis_others,
        },
        dataType: 'json'
    }).
    done(function(data) {
        var file_url = '/phpdocx-trial-basic-7.0/examples/' + file_name + '.docx';
        location.href = file_url;
        // $('body').html('<div style="font:26px/36px Arial, sans-serif; margin:100px auto; text-align:center;"><a href="' + file_url + '">DOWNLOAD DOESN\'T START?<br>USE THIS DIRECT LINK TO DOWNLOAD YOUR FILE</a></div><div style="text-align:center; font:14px/20px Arial, sans-serif"><a href="?" style="color:red;">OR CLICK HERE TO START OVER</a></div>');
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
        $('#download-word-file').html('Download Word file').removeClass('disabled')
    })
})
$('#select-header-image').on('click', function(){
    $(this).hide()
    $('#header-image img').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7')
    $('#header-image').hide()
    $('#header-image-list-wrapper').show();
    $('#cancel-header-image').show()
    return false;
})
$('.header-image-list-item img.img-responsive').on('click', function(){
    var src = $(this).attr('src').replace('/timthumb.php?w=180&h=120&src=', '');
    $('#header-image img').attr('src', src)
    header_image = src.replace('/upload/devis-banners/b2b/', '')
    $('#header-image-list-wrapper').hide();
    $('#header-image').show()
    $('#cancel-header-image').hide()
    $('#select-header-image').show()
})
$('#cancel-header-image').on('click', function(){
    $('#header-image img').attr('src', '/upload/devis-banners/b2b/' + header_image)
    $('#header-image-list-wrapper').hide();
    $('#header-image').show()
    $('#cancel-header-image').hide()
    $('#select-header-image').show()
    return false;
})
TXT;

$this->registerJs($js);