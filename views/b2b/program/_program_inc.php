<?
use yii\helpers\Html;

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Production', 'b2b'],
    ['Tour programs', SEG2 != 'programs' ? 'b2b/programs' : null],
    // in_array(SEG3, ['print-b2b']) ? ['View', 'b2b/programs/r/'.$theProgram['id']] : null,
];
Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>'New', 'link'=>'b2b/programs/c', 'active'=>SEG3 == 'c'],
        ['submenu'=>[
            ['icon'=>'plus', 'label'=>'New (B2B)', 'link'=>'b2b/programs/c', 'active'=>SEG3 == 'c' && Yii::$app->request->get('b2b') == 'yes'],
            ['icon'=>'plus', 'label'=>'New (B2B PROD)', 'link'=>'b2b/programs/c?type=b2b-prod', 'active'=>SEG3 == 'c' && Yii::$app->request->get('type') == 'b2b-prod'],
            ],
        ],
    ],
];

if (isset($theProgram['id']) && in_array(SEG3, ['r', 'u', 'd', 'print-old', 'copy', 'print', 'upload'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'b2b/programs/r/'.$theProgram['id'], 'active'=>SEG3 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'b2b/programs/u/'.$theProgram['id'], 'active'=>SEG3 == 'u'],
        ['submenu'=>[
            ['icon'=>'dollar', 'label'=>'Make proposal', 'link'=>'bookings/c?product_id='.$theProgram['id'], 'visible'=>$theProgram['offer_count'] == 0],
            ['icon'=>'files-o', 'label'=>'Copy as new', 'link'=>'products/copy/'.$theProgram['id'], 'active'=>SEG3 == 'copy'],
            ['icon'=>'paperclip', 'label'=>'Upload attachments', 'link'=>'products/upload/'.$theProgram['id'], 'active'=>SEG3 == 'upload'],
            ['icon'=>'file-word-o', 'label'=>'Export Word file (EN)', 'link'=>'http://www.amica-travel.com/imsprint-b2b'.($theProgram['language'] == 'en' ? '-en/' : '/').$theProgram['id'].'/'.md5($theProgram['created_at'])],
            // ['icon'=>'print', 'label'=>'Print (English)', 'link'=>'ct/print-old/'.$theProgram['id'].'/en', 'active'=>SEG3 == 'print'],
            // ['icon'=>'print', 'label'=>'Print (Francais)', 'link'=>'products/print/'.$theProgram['id'], 'active'=>SEG3 == 'print'],
            ['-'],
            ['icon'=>'trash-o', 'label'=>Yii::t('app', 'Delete'), 'link'=>'products/d/'.$theProgram['id'], 'active'=>SEG3 == 'd', 'class'=>'text-danger'],
            ],
        ],
    ];
}

$languageList = [
    'en'=>'English',
    'fr'=>'Francais',
    'it'=>'Italiano',
    'vi'=>'Tiếng Việt',
];

$ctTypeList = [
    'private'=>'Private tour',
    'vpc'=>'VPC tour',
    'tcg'=>'TCG tour',
    'agent'=>'GIT tour',
    'b2b-prod'=>'B2B PROD',
    'combined2016'=>'Combined tour',
    ''=>'Other',
];

$this->params['icon'] = 'gift';

if (isset($theProgram['id'])) {
    $productViewTabs = [
        ['label'=>'Product overview', 'link'=>'products/r/'.$theProgram['id']],
        ['label'=>'Sales & Bookings', 'link'=>'products/sb/'.$theProgram['id']],
        ['label'=>'Operation', 'link'=>'products/op/'.$theProgram['id']],
    ];
}

if (isset($theProgram)) {
    if ($theProgram['op_status'] == 'op') {
        if (isset($theTour)) {
            Yii::$app->params['page_title'] = Html::a($theProgram['op_code'], '@web/tours/r/'.$theTour['id'], ['style'=>'background-color:#ffc; padding:0 3px; color:#148040;']). ' ';
        }
    }

    if ($theProgram['op_finish'] == 'canceled') {
        Yii::$app->params['page_title'] .= '<span style="color:#c00;">(CXL)</span> ';
    }
    if ($theProgram['offer_type'] == 'combined2016') {
        Yii::$app->params['page_title'] .= '<span class="text-uppercase text-light" style="background-color:#cff; padding:0 3px; color:#148040;">Combined</span> ';
    }

    Yii::$app->params['page_title'] .= $theProgram['title'];
}

Yii::$app->params['page_meta_title'] = strip_tags(Yii::$app->params['page_title']);

$b2bBannerFiles = [
    ['Cham South Vietnam', '1-cham_south_vietnam.jpg'],
    ['Lolo North Vietnam', '2-lolo_north_vietnam.jpg'],
    ['Uxo North Laos', '3-uxo_north_laos.jpg'],
    ['Mekong Delta South Vietnam', '4-mekong_delta_south_vietnam.jpg'],
    ['Boping Cambodia', '5-boping_cambodia.jpg'],
    ['Lolo North Vietnam2', '6-lolo_north_vietnam2.jpg'],
    ['Opa North Laos', '7-opa_north_laos.jpg'],
    ['Amanoi', '8-amanoi.jpg'],
    ['Plain Of Jars North Laos', '9-plain_of_jars_north_laos.jpg'],
    ['South Laos Expedition', '10-south_laos_expedition.jpg'],
    ['Stung Sen Cambodia', '11-stung_sen_cambodia.jpg'],
    ['Angkor Vat Cambodia', '12-angkor_vat_cambodia.jpg'],
    ['Lolo North Vietnam3', '13-lolo_north_vietnam3.jpg'],
    ['Red Delta North Vietnam', '14-red_delta_north_vietnam.jpg'],
    ['Halong Bay North Vietnam', '15-halong_bay_north_vietnam.jpg'],
    ['Angkor Thom Cambodia', '16-angkor_thom_cambodia.jpg'],
    ['Katu South Laos', '17-katu_south_laos.jpg'],
    ['Opa North Laos', '18-opa_north_laos.jpg'],
    ['Song Saa', '19-song_saa.jpg'],
    ['Binh Thuan Province Vietnam', '20-binh_thuan_province_vietnam.jpg'],
    ['Opa North Laos 3', '21-opa_north_laos 3.jpg'],
    ['Mekong River Luang Prabang', '22-mekong_river_luang_prabang.jpg'],
    ['Laotian Secret War Tour', '23-laotian_secret_war_tour.jpg'],
    ['Stung Sen Cambodia', '24-stung_sen_cambodia.jpg'],
    ['Cia Road South Laos', '25-cia_road_south_laos.jpg'],
    ['Si Phan Don Laos', '26-si_phan_don_laos.jpg'],
    ['Devata Cambodia', '27-devata_cambodia.jpg'],
    ['Halong Bay Vietnam', '28-halong_bay_vietnam.jpg'],
    ['Tonle Sap Cambodia', '29-tonle_sap_cambodia.jpg'],
    ['Hanoi Potter Work', '30-hanoi_potter_work.jpg'],
    ['White HmÃ´ng Cao Bang', '31-white_hmÃ´ng_cao_bang.jpg'],
    ['Koh Sdach Cambodia', '32-koh_sdach_cambodia.jpg'],
    ['Mondulkiri Cambodia', '33-mondulkiri_cambodia.jpg'],
    ['Luma Phongsaly Laos', '34-luma_phongsaly_laos.jpg'],
    ['Katu South Laos', '35-katu_south_laos.jpg'],
    ['Uxo Laos', '36-uxo_laos.jpg'],
    ['Khmer Cambodia', '37-khmer_cambodia.jpg'],
    ['Ha Giang North Vietnam', '38-ha_giang_north_vietnam.jpg'],
    ['Khmer Cambodia', '39-khmer_cambodia.jpg'],
    ['Saoch Cambodia', '40-saoch_cambodia.jpg'],
    ['Luma Phongsaly Laos', '41-luma_phongsaly_laos.jpg'],
    ['Thong Nong Nord Vietnam', '42-thong_nong_nord_vietnam.jpg'],
    ['Khmer Cambodia', '43-khmer_cambodia.jpg'],
    ['Koh Ker Cambodia', '44-koh_ker_cambodia.jpg'],
    ['Ha Giang North Vietnam', '45-ha_giang_north_vietnam.jpg'],
    ['Mondulkiri Cambodia2', '46-mondulkiri_cambodia2.jpg'],
    ['Halong Bay Vietnam', '47-halong_bay_vietnam.jpg'],
    ['Hmong North Vietnam', '48-hmong_north_vietnam.jpg'],
    ['Upper Xekong South Laos', '49-upper_xekong_south_laos.jpg'],
    ['Tam Coc Vietnam', '50-tam_coc_vietnam.jpg'],
    ['Lak Lak', '51-lak_lak.jpg'],
    ['Buddha Statues Laos', '52-buddha_statues_laos.jpg'],
    ['Cai Rang Floating Village Vietnam', '53-cai_rang_floating_village_vietnam.jpg'],
    ['Si Phan Don Laos', '54-si_phan_don_laos.jpg'],
    ['Amanoi Vinh Hy Vietnam', '55-amanoi_vinh_hy_vietnam.jpg'],
    ['Angkor Vat Cambodia2', '56-angkor_vat_cambodia2.jpg'],
    ['Babe Lake North Vietnam', '57-babe_lake_north_vietnam.jpg'],
    ['Cordillere Annamitique', '58-cordillere_annamitique.jpg'],
    ['Devata2 Cambodia', '59-devata2_cambodia.jpg'],
    ['Tomo Temple Laos', '60-tomo_temple_laos.jpg'],
    ['Bac Giang Pagoda Vietnam', '61-bac_giang_pagoda_vietnam.jpg'],
    ['Mekong Delta Fishermen', '62-mekong_delta_fishermen.jpg'],
    ['Bouddha', '63-bouddha.jpg'],
    ['Gong Festival Highlands Vietnam', '64-gong_festival_highlands_vietnam.jpg'],
    ['Flower Hmong Vietnam', '65-flower_hmong_vietnam.jpg'],
    ['Fusion Cam Ranh', '66-fusion_cam_ranh.jpg'],
    ['Indochina War', '67-indochina_war.jpg'],
    ['Ha Giang North Vietnam', '68-ha_giang_north_vietnam.jpg'],
    ['Ha Long Vietnam2', '69-ha_long_vietnam2.jpg'],
    ['Hmong Vietnam', '70-hmong_vietnam.jpg'],
    ['Katu Ht Xekong', '71-katu_ht_xekong.jpg'],
    ['Katu Ht Xekong2', '72-katu_ht_xekong2.jpg'],
    ['Khmer Rouge Cambodia', '73-khmer_rouge_cambodia.jpg'],
    ['Laos Secret War', '74-laos_secret_war.jpg'],
    ['Laxuyen Village Vietnam', '75-laxuyen_village_vietnam.jpg'],
    ['Vat Phu Laos', '76-vat_phu_laos.jpg'],
    ['Elephant Mount Laos', '77-elephant_mount_laos.jpg'],
    ['Southern Vietnam Lost Forest', '78-southern_vietnam_lost_forest.jpg'],
    ['Nam Ngu North Vietnam', '79-nam_ngu_north_vietnam.jpg'],
    ['Nam Ou River Norht Laos', '80-nam_ou_river_norht_laos.jpg'],
    ['Opa North Laos', '81-opa_north_laos.jpg'],
    ['Uxo Laos2', '82-uxo_laos2.jpg'],
    ['Hmong Laos2', '83-hmong_laos2.jpg'],
    ['Phum Baitang Cambodia', '84-phum_baitang_cambodia.jpg'],
    ['Topas Eco Lodge Sapa Vietnam', '85-topas_eco_lodge_sapa_vietnam.jpg'],
    ['Katu South Laos', '86-katu_south_laos.jpg'],
    ['Tam Coc Garden', '87-tam_coc_garden.jpg'],
    ['Yeak Cambodia', '88-yeak_cambodia.jpg'],
    ['Vietnam War', '89-vietnam_war.jpg'],
    ['Phat Sanday Cambodia', '90-phat_sanday_cambodia.jpg'],
    ['Hoi An', '92-Hoi_An.jpg'],
    ['Hue', '93-Hue.jpg'],
    ['Hue 2', '94-Hue_2.jpg'],
    ['Water Puppets Hanoi', '95-Water_puppets_Hanoi.jpg'],
    ['Hue Tu Duc', '96-Hue_Tu_Duc.jpg'],
    ['Hue Khai Dinh', '97-Hue_Khai_Dinh.jpg'],
    ['Hoi An 2', '98-Hoi_An_2.jpg'],
    ['Hoi An 3', '99-hoi_An_3.jpg'],
    ['Hoi An 4', '100-Hoi_An_4.jpg'],
    ['Hoi An Ancient House Village', '101-Hoi_An_Ancient_House_Village.jpg'],
    ['Cap Padaran Dunes', '102-Cap_Padaran_dunes.jpg'],
    ['Nui Chua', '103-Nui_Chua.jpg'],
    ['Mai Hich', '104-Mai_Hich.jpg'],
    ['Gates', '105-gates.jpg'],
    ['Garden', '106-tam-coc-garden.jpg'],
    ['Couleur', '107-tam-coc-camille-couleur.jpg'],
    ['Chua 2', '108-nui-chua 2.jpg'],
    ['Vietnam', '109-binh-thuan-south-vietnam.jpg'],
    ['Chi 1', '110-cao-bang-san-chi 1.jpg'],
    ['Cambodia', '111-cardamomes-monts-cambodia.jpg'],
    ['Vietnam', '112-cau-maa-south-vietnam.jpg'],
    ['Vietnam', '113-delta-du-fleuve-rouge-north-vietnam.jpg'],
    ['Laos', '114-lu-ma-north-laos.jpg'],
    ['Vietnam', '115-hmong-fleur-north-vietnam.jpg'],
    ['Laos', '116-hmong-kho-north-laos.jpg'],
    ['Laos', '117-katu-south-laos.jpg'],
    ['Cambodia', '118-koh-kong-cambodia.jpg'],
    ['Vietnam', '119-lolo-north-vietnam.jpg'],
    ['Vietnam2', '120-lolo-north-vietnam2.jpg'],
    ['Vietnam3', '121-lolo-north-vietnam3.jpg'],
    ['Laos3', '122-lu-ma-north-laos3.jpg'],
    ['Laos4', '123-lu-ma-north-laos4.jpg'],
    ['Vietnam', '124-mu-cang-chai-northwest-vietnam.jpg'],
    ['Cambodia', '125-mondulkiri-cambodia.jpg'],
    ['Cambodia', '126-pursat-cambodia.jpg'],
    ['Bang', '127-sanchi-cao-bang.jpg'],
    ['Bang2', '128-sanchi-cao-bang2.jpg'],
    ['Vietnam', '129-north-vietnam.jpg'],
];

// REad photos from dir
if (USER_ID == 1 && isset($_GET['getphotos'])) {
    echo '<pre>';
    $files = \yii\helpers\FileHelper::findFiles('/var/www/my.amicatravel.com/www/upload/devis-banners/b2b');
    $result = [];
    foreach ($files as $file) {
        $name = substr(strrchr($file, '/'), 1);
        $part = explode('-', $name);
        $cap = substr(strrchr($name, '-'), 1);
        $cap = str_replace(['_', '-', '.jpg'], [' ', ' ', ''], $cap);
        $cap = ucwords($cap);
        $result[(int)$part[0]] = [
            'name'=>$name,
            'caption'=>$cap
        ];
    }
    ksort($result);
    \fCore::expose($result);
    foreach ($result as $img) {
        echo '[\'', $img['caption'], '\', \'', $img['name'], '\'],', "\n";
    }
    echo '</pre>';
    exit;
}