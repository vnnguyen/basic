<?php

use yii\helpers\Markdown;
use yii\helpers\Html;
use yii\helpers\FileHelper;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

$dayIdList = explode(',', $theProduct['day_ids']);

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

$this->title = $theProduct['title'];

$theProduct['gender'] = 'f';
$theProduct['avatar'] = 'avatar';
function getImageFile($dir, $folderName){
    if (file_exists($dir)) {
        $photos = FileHelper::findFiles($dir, ['recursive' => false, 'only' => ['*.jpg', '*.png']]);
        foreach ($photos as $pt) {
            $pt = trim(strrchr($pt, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR); 
        	echo "<li>
                    <p>".str_replace(['-', '.png', '.jpg'], [' ', '', ''], $pt)."</p>
                    <img class='img-devis' style='width: 150px' src='".DIR."upload/banner_date_tour_devis/$folderName/$pt'>
                    <input type='checkbox' value='$folderName/".str_replace(['.png', '.jpg'], [''], $pt)."'/>
                </li>";
        };
    }
}
function vn_str_filter($str) {

        $unicode = array(
            'a' => 'á|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|à',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|�?|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|�?|õ|�?|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|�?|ở|ỡ|ợ|ọ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => '�?|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => '�?',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => '�?|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|�?|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => '�?|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        return $str;
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $theProduct['title'] ?> - <?= count($dayIdList) ?> jours - devis - <?= $theProduct['created_at'] ?></title>
    </head>
    <body style="font-family:Calibri; font-size:9pt;">
        <div style="width:800px; margin:auto;">
            <h1 style="color:036; font-size:40px; margin:0 0 20px;">Xuất file devis tự động</h1>
            <div>
                <p style=" color: #09f;font-size: 20px">Để xuất ra file word hãy chọn 1 mẫu devis và <a id="download-word" url='<?= DIR ?>ajaxphpdocx/ims_to_word_ajax.php' href="javascript:void(0)">CLICK VÀO ĐÂY</a></p>
				<div class="download-devis"><a id="downloadword" url='<?= DIR ?>ajaxphpdocx/ims_to_word_ajax.php' href="javascript:void(0)">DOWNLOAD DEVIS</a></div>
                <p>Để xuất Devis TIẾNG ANH chọn <a href="/tools/imsprint-en/<?=SEG2?>/<?=SEG3?>">ĐÂY</a></p>
                <!-- p>B2B tiếng PHÁP New, click vào <a href="<?=DIR?>imsprint-b2b/<?=SEG2?>/<?=SEG3?>">ĐÂY</a></p -->
				<p>B2B tiếng ANH New click vào <a href="/tools/imsprint-b2b-en?id-<?=SEG2?>&code=<?=SEG3?>">ĐÂY</a></p>
				<!-- p>B2B tiếng PHÁP Format cũ, chọn vào đây <input type="checkbox" id="b2b-fr-options"/></p -->
                <p id='loading' style='display:none;'>Please Wait <img src='https://www.amica-travel.com/assets/img/devis-ims/loading.gif'/></p>
                <? include "_inc_data_template.php" ?>
                <div class="option-devis">
                    <p>
                        <select id='prix-option' style='width: 110px;'>
                            <option>prix à partir de</option>
                            <option>prix</option>
                        </select>
                        <span type='text' id='devis-prix'><?= $theProduct['price'] ?></span>
                        <span id='prix-money'><?= $theProduct['price_unit'] ?></span>
                    </p>
                    
                    <? foreach ($temArr as $key => $value) : ?>
                        <ul>
                        <h2><?=$key ?></h2>
                          <? foreach ($value as $ki => $vi) :?>
                              <li>
                                    <p><?=$vi['name'] ?></p>
                                    <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/tools/docx/thumb/<?=$vi['thumb'] ?>.JPG'>
                                    <input type="checkbox" value="<?=$vi['file'] ?>"/>
                                </li>
                          <? endforeach; ?>      
                        </ul>
                    <? endforeach; ?>
                </div>
                <h2>Nếu muốn thay đổi ảnh banner số 2 thì chọn ở đây! <input type="checkbox" id="option-img-banner-2-fr"/><br>(không áp dụng cho B2B tiếng pháp)</h2>
                <div class="option-img-banner-2">
                    <ul>
                     <?
                                   // $dir = '/var/www/www.amica-travel.com/upload/image_devis_secretindochina/';
                                    $dir = '/var/www/my.amica-travel.com/www/assets/tools/docx/banner_2_devis/b2c';
                                    $imageFolders = glob($dir.'/*', GLOB_ONLYDIR);
                                    foreach ($imageFolders as $key => $value) {
                                       $folder = str_replace($dir.'/', '', $value);
                                       echo "<h3>".strtoupper($folder)."</h3>";
                                       $photos = FileHelper::findFiles($value, ['recursive' => false, 'only' => ['*.jpg','*.png']]);
                                        foreach ($photos as $pt) :
                                            $pt = trim(strrchr($pt, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR); 
                                            echo "<li>
                                                <p>".ucwords(str_replace(['-', '.png', '.jpg'], [' ', '', ''], $pt))."</p>
                                                <img class='img-devis' style='width: 150px' src='/timthumb.php?src=/upload/banner_2_devis/".$pt."&w=150&h=60&zc=1'>
                                                <input type='checkbox' value='".$pt."'/>
                                                </li>";
                                        endforeach;

                                    }

                                   
                                    ?>
                    </ul>
                </div>
				
				 <!-- Banner anh ngay tour devis -->
                <div>
                        <a href="#image-footer" class="fancybox add-image-footer">Thay thế ảnh trong footer</a>
                        <a href="javascript:void(0)" class="topopup add-image-jours">Thêm ảnh vào ngày tour</a>
                        <a href="javascript:void(0)" class="topopup add-image-tableau">Thay ảnh Tableau</a>
                        
    
                        <div id="toPopup">
                        <div class="close"></div>
                            <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

                            <div id="popup_content"> <!--your content start-->
								<div class="image-tableau"><!--Start Image-->
                                     <p style="background: #e3f2e1; font-weight: bold;">List ảnh banner Tableau </p>
                                   <ul>
                                    <?
                                   // $dir = '/var/www/www.amica-travel.com/upload/image_devis_secretindochina/';
                                    $dir = '/var/www/my.amicatravel.com/www/assets/tools/docx/banner_2_devis/image_tableau';
                                    if (file_exists($dir)) {
                                        $photos = FileHelper::findFiles($dir, ['recursive' => true, 'only' => ['*.jpg','*.png']]);

                                        foreach ($photos as $pt) :
                                            $pt = trim(strrchr($pt, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR); 
                                            echo "<li>
                                                <p>".str_replace(['-', '.png', '.jpg'], [' ', '', ''], $pt)."</p>
                                                <img class='img-devis' style='width: 150px' src='".DIR."timthumb.php?src=".DIR."upload/banner_2_devis/image_tableau/".$pt."&w=150&h=60&zc=1'>
                                                <input type='checkbox' value='".$pt."'/>
                                                </li>";
                                               
                                        endforeach;
                                    }
                                    ?>
                                        </ul>
                                </div><!--End image-->
                                <div class="test">
                                        <span>Chọn ngày muốn thêm ảnh : </span>
                                        <select id="jour-test" name="" style="width: 500px;">

                                                 <? $cnt=0;
                                                foreach ($dayIdList as $di) {
                                                 foreach ($theProduct['days'] as $ng) {
                                                    if ($ng['id'] == $di) {
                                                        $cnt ++;
                                                        $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                                                        $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                                                        $ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
                                                        $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                                                        $ngay = str_replace('|', '<span style="font-weight:normal">|</span>', $ngay);
                                                ?>
                                            <option value="jour-<?=$cnt?>">Jour <?= $cnt ?> - <?= $ngay ?><span style="font-weight:normal"> | <?= $ng['name'] ?> (<?= $ng['meals'] ?>)</option>                         
                                                <?    }         
												}
											} ?>
                                        </select>
										 <div id="myList" style="color:rgb(172,0,132); font-family: Calibri" >
                                            
                                        </div>
                                        <p style="background: #e3f2e1; font-weight: bold;">Ảnh Việt Nam</p>
                                  <ul style="padding:0;">
                                  			<? getImageFile('upload/banner_date_tour_devis/vietnam', 'vietnam') ?>
                                        </ul>
                                        <p style="background: #e3f2e1; font-weight: bold;">Ảnh Laos</p>
                                  <ul style="padding:0;">
                                            <? getImageFile('upload/banner_date_tour_devis/laos', 'laos') ?>
                                        </ul>
                                        <p style="background: #e3f2e1; font-weight: bold;">Ảnh Cambodge</p>
                                  <ul style="padding:0;">
                                            <? getImageFile('upload/banner_date_tour_devis/cambodge', 'cambodge') ?>
                                        </ul>
										<p style="background: #e3f2e1; font-weight: bold;">Ảnh Birmanie</p>
                                  <ul style="padding:0;">
                                            <? getImageFile('upload/banner_date_tour_devis/birmanie', 'birmanie') ?>
                                        </ul>
                                   </div>
                            </div>
                        </div> <!--toPopup end-->

                        <div class="loader"></div>
                        <div id="backgroundPopup"></div>
                    
                </div><!--test-->
				
            </div>
            <h3 style="color: #09f; border-bottom: 1px solid #09f;font-size: 30px;">Saler 's description | Thông tin saler</h3>
            <div id='sale-detail'>
                <p style='margin: 0; padding: 0;font-size: 9pt;'><img width='130' height='130' src='<?= $theProduct['createdBy']['image'] ?>'/></p>
                <br>
                <p style='margin: 0; padding: 0; font-size: 11pt; color: #BD3920;font-family:Calibri;'>Votre conseillère Amica Travel</p>
                <h2 id='devis-sale-name' style='margin: 0; padding: 0;font-family:Calibri; font-size: 11pt;color:black;'>Mlle. <?=vn_str_filter($theProduct['createdBy']['fname'] . ' ' . $theProduct['createdBy']['lname']) ?></h2>
                
                <p id='devis-sale-email' style='margin: 0; padding: 0;font-family:Calibri; font-size: 11pt;'><a href="mailto:<?= $theProduct['createdBy']['email'] ?>"><?= $theProduct['createdBy']['email'] ?></a></p>
                <p id='devis-sale-phone' style='margin: 0; padding: 0;font-family:Calibri; font-size: 11pt;'>+84 4 62 73 44 55</p>
            </div>

            <div style="display: inline-block; width: 268pt;" id="sale-detail-style2">
                <table style="margin: 0;padding: 0;border: none;border-collapse: collapse;width: 268pt;">
                    <tr>
                        <td align="center" style="text-align: center;padding-top: 5pt;">
                            <p style="margin: 0; padding: 0; font-size: 9pt; color: #BD3920;font-family:Calibri;">Votre conseillère Amica Travel</p>
                            <h2 style="margin: 0; padding: 0;font-family:Calibri; font-size: 14pt;color:black;" id="devis-sale-name">Mlle. <?= $theProduct['createdBy']['fname'] . ' ' . $theProduct['createdBy']['lname'] ?></h2>
                            <p style="margin: 0; padding: 0;font-family:Calibri; font-size: 11pt;" id="devis-sale-email"><a href="maito:<?= $theProduct['createdBy']['email'] ?>"><?= $theProduct['createdBy']['email'] ?></a></p>
                            <p style="margin: 0; padding: 0;font-family:Calibri; font-size: 11pt;" id="devis-sale-phone">+84 4 62 73 44 55</p>
                        </td>
                        <td style="text-align: left; padding: 0;margin: 0;width: 130px">
                            <img width="130" height="130" src='<?= $theProduct['createdBy']['image'] ?>'/>
                        </td>
                    </tr>
                </table>
            </div>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Front page | Trang bìa</h2>
            <h3 id="devis-name" style="font-family:Calibri; font-size: 20pt;text-transform: uppercase; margin: 0; padding: 0; "><?=strtoupper(preg_replace("/[^a-zA-Z]*devis\d[^a-zA-Z]*/","",$theProduct['title'])) ?></h3>
            <p style="font-family:Calibri; font-size:9pt;">
                <strong>Devis No.<span id='devis-number' style='font-family:Calibri; font-size: 14pt;font-weight:bold;'><?= preg_replace("/[^0-9]/","",$theProduct['title'])?></span></strong>

                <strong>Type du voyage:</strong> en individuel
                <br /><strong>Devis personnalisé pour:</strong><span id='devis-guest' style='font-family:Calibri; font-size: 11pt;'><strong><?= $theProduct['about'].' | '.$theProduct['pax'].' pers'?></strong></span>
                <br /><strong>Durée & Date du voyage:</strong><span id='devis-date' style='font-family:Calibri; font-size: 9pt;'><strong> <?= count($dayIdList) ?> jours sur place, du <?= str_replace('/', '|', date('d/m/Y', strtotime($theProduct['day_from']))) ?> au <?= str_replace('/', '|', date('d/m/Y', strtotime('+ ' . (count($dayIdList) - 1) . ' day', strtotime($theProduct['day_from'])))) ?></strong></span>
            </p>

            <h3 style="font-family:Calibri; font-size:11pt;font-weight: bold;">Les points forts du programme</h3>
            <div id='devis-description' style='font-family:Calibri; font-size: 10pt;'>
<?
$points = $parser->parse($theProduct['points']);
$points = str_replace('<p style="font-family:Calibri;">', '<p style="font-family:Calibri; font-size:11pt;">', $points);
$points = str_replace('+', '&#8226;', $points);
echo $points;
?>
            </div>


            <h2 style="font-family: Calibri;border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Tableau synthétique du programme</h2>
            <p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
            <table width="650" id='devis-table-programe' style='margin: 0; padding: 0; width: 166mm;border-collapse: collapse;'>
                <thead>
                    <tr style="margin: 2mm 0;">
                        <th width="80" style="text-align:left; width: 8.6%; padding: 2mm 1mm 2mm 0;"><h3 style="font-family:Calibri; font-size:9pt; color:#BD3920; margin:0;">Jour</h3></th>
                <th width="110" style="text-align:left;width:  20%;"><h3 style="font-family:Calibri; font-size:9pt; color:#BD3920; margin:0; padding-right: 1mm;">Date</h3></th>
                <th width="250" style="text-align:left;width:  41%;"><h3 style="font-family:Calibri; font-size:9pt; color:#BD3920; margin:0;padding-right: 1mm;">Itinéraire</h3></th>
                <th width="120" style="text-align:left;width: 22.6%;"><h3 style="font-family:Calibri; font-size:9pt; color:#BD3920; margin:0;padding-right: 1mm;">Accompagnement</h3></th>
                <th width="90" style="text-align:left;width: 14.5%;"><h3 style="font-family:Calibri; font-size:9pt; color:#BD3920; margin:0;">Repas inclus</h3></th>
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
//$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
                            $ngay_fr = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
                            $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                            ?>
                            <tr>
                                <td style="font-family:Calibri; font-size:9pt;font-weight: bold;  padding: 5pt 0;"><strong>Jour <?= $cnt ?></strong></td>
                                <td width="200" style="font-family:Calibri; font-size:9pt;padding: 5pt 0;"><?= $ngay ?></td>
                                <td style="font-family:Calibri; font-size:9pt;font-weight: bold;padding: 5pt 0;" ><strong><?= $ng['name'] ?></strong></td>
                                <td style="font-family:Calibri; font-size:9pt;padding: 5pt 0;" ><?= $ng['guides'] ?></td>
                                <td style="font-family:Calibri; font-size:9pt;padding: 5pt 0;">
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
            <p style="font-family:Calibri; font-size:11pt;">END COPY</p>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Descriptif détaillé du programme</h2>
            <p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
            <div id='devis-detail' style="font-size: 10pt;">


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
                                    $ngay = str_replace('|', '<span style="font-weight:normal">|</span>', $ngay);
                                    ?>
                            <h4 style="font-size:10pt; color:#BD3920;font-family:Calibri;">Jour <?= $cnt ?> - <?= $ngay ?><span style="font-weight:normal"> | <?= $ng['name'] ?> (<?= $ng['meals'] ?>)</span></h4>
                            <div style="font-family:Calibri; font-size:9pt;">
            <?= $ng['transport'] == '' ? '' : '<p style="font-family:Calibri; font-size:10pt;">' . $ng['transport'] . '</p>' ?>
                            <?
                            $txxt = $parser->parse($ng['body']);
							$txxt = str_replace('<p> </p>', '', $txxt);
                            $txxt = str_replace('<p>', '<p style="font-family:Calibri; font-size:10pt;">', $txxt);
                            $txxt = str_replace('<li>', '<li style="font-family:Calibri; font-size:10pt;">', $txxt);
                            $txxt = str_replace('<strong>', '<strong style="font-family:Calibri; font-size:10pt;">', $txxt);
                            $txxt = str_replace('<em>', '<em style="font-family:Calibri; font-size:10pt;">', $txxt);
                            $txxt = str_replace(['<span class="caps">', '</span>'], ['', ''], $txxt);
                            echo $txxt;
                            ?>
                            </div>
							<BR CLEAR="left"/> 
							
							<div class="jour jour-<?=$cnt?>"><img alt="" style="width: 100%;" WIDTH=1050 height="340" src="" ALIGN="left"/> <div class="remove"></div></div>
                            <?
                        }
                    }
                }
                ?>
            </div>
            <p style="font-family:Calibri; font-size:11pt;">END COPY</p>
			<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Titre tableau devis</h2>
            <p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
            <table width="600" cellspacing="0" cellpadding="0" id='tableau-devis' class="simple" border="0" style="width: 600px; margin: 0; padding: 0;border-collapse: collapse; border-color: #BD3920;">  
                <thead>
                    <tr>
                        <th valign="bottom" colspan="4" width="600" height="50" style="text-align:left; padding: 0;"><img align="left" width="820" src="<?=DIR?>assets/img/head-table-title.jpg"></th>
                        
                </tr>
                </thead>
                <tr>
                    <td colspan="4">
                                 <table width="600" cellspacing="0" cellpadding="10" id='tableau-devis' class="simple" border="0" style="width: 600px; margin: 0; padding: 0;border-collapse: collapse; border-color: #BD3920;">  
                                        <?
                    $cnt = 0;
                    foreach ($theProduct['tableau-devis'] as $k => $v) {
                        ?>
                         <tr>
                            
                             <td width="100" style="padding-top: 10px; padding-bottom: 10px; text-align: left;border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt;font-weight: bold;color:#bd3920;"><span style="background-color: none;"><?=$v[0]?></span></td>
                                <td width="200"  style="padding-top: 10px; padding-bottom: 10px;border-bottom: 1px solid #B0A47A; padding: 3mm 0;font-family:Calibri; font-size:9pt;"><span style="background-color: none;"><?= $v[1] ?></span></td>
                                <td width="310" style="padding-top: 10px; padding-bottom: 10px;border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt;font-weight: normal;" ><span style="background-color: none;"><?= $v[2]?></span></td>
                                <td  width="150" style="padding-top: 10px; padding-bottom: 10px;border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt;" ><span style="background-color: none;"><?= $v[3] ?></span></td>
                          </tr>      
                <?
                    }
                ?>
                                 </table>
                            </td>    
                </tr>
                
             </table>   
            <p style="font-family:Calibri; font-size:11pt;">END COPY</p>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Les tarifs</h2>
            <p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
            <div id='devis-table-tarif'>

                <p style="font-family:Calibri; font-size:9pt;">Conditions tarifaires établies le <?= date('d-m-Y', strtotime($theProduct['price_from'])) ?> et valables jusqu’au <?= date('d-m-Y', strtotime($theProduct['price_until'])) ?></p>
                <table class="simple" style="width: 170.3mm; margin: 0; padding: 0;border-collapse: collapse;">
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
                            echo '<p style="font-family:Calibri; font-size:11pt;color:#BD3920;"><strong>' . trim(substr($theProductp, 7)) . '</strong></p>';
                            echo '<tr style="height: 10mm;">
                <th style="padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid #B0A47A;width: 17.8%; text-align: left;"><h3 style="font-family:Calibri; color:#BD3920; font-size:9pt;">Ville</h3></th>
                <th style="padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid #B0A47A; width: 20.5%;text-align: left;"><h3 style="font-family:Calibri; color:#BD3920; font-size:9pt;">Hébergement</h3></th>
                <th style="padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid #B0A47A; width: 22%;text-align: left;"><h3 style="font-family:Calibri; color:#BD3920; font-size:9pt;">Catégorie chambre</h3></th >
                <th style="padding-left: 2mm;border-bottom: 1px solid #B0A47A;text-align: left;width: 39.6%;font-size:9pt;"><h3 style="font-family:Calibri; font-size:9pt;">Référence</h3></th>
                </tr>';
                        }
                        if (substr($theProductp, 0, 2) == '+ ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 5; $i ++) if (!isset($line[$i])) $line[$i] = '';
							if (trim($line[3]) != '') {
                                if (isset($line[4]) && in_array(trim($line[3]), ['http', 'https'])) {
                                    $line[3] = trim($line[3]).':'.trim($line[4]);
                                }
                                else {
                                    $line[3] = 'http://'.trim($line[3]);
                                }
                            }
                            echo '<tr>
<td style="border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt;padding: 5pt 2mm; color: #BD3920;"><strong>' . $line[0] . '</strong></td>
<td style="border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt; padding: 5pt 2mm;">' . $line[1] . '</td>
<td style="border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt;  padding: 5pt 2mm;">' . $line[2] . '</td>
<td style="border-bottom: 1px solid #B0A47A;font-family:Calibri; font-size:9pt; padding: 5pt 2mm;" class="a-href">' . (filter_var(trim($line[3]), FILTER_VALIDATE_URL) == true ? Html::a( trim($line[3]), trim($line[3]), ['class' => 'profile-link']) : str_replace('http://','',trim($line[3]))) . '</td></tr>';
                        }

                        if (substr($theProductp, 0, 2) == '- ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            $line[1] = trim($line[1]);
                            if (($count == count($theProductpx)) || in_array($count, $last)) {
                                echo '<tr><td style="font-family:Calibri; font-size:9pt;  padding: 2mm 1mm 2mm 2mm;color:#000;" colspan="3" class="ta-r"><strong>' . $line[0]. '</strong></td><td style="text-align: center;"><h3 style="font-size:12pt;font-family:Calibri; color:#BD3920;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            } else {
                                echo '<tr><td style="border-bottom: 1px solid black;font-family:Calibri; font-size:9pt;  padding: 2mm 1mm 2mm 2mm;color:#BD3920;" colspan="3" class="ta-r"><strong>' . $line[0] . '</strong></td><td style="text-align: center;border-bottom: 1px solid black;"><h3 style="font-size:12pt;font-family:Calibri; color:#BD3920;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            }
                        }
                    }
                    ?>
                </table>

            </div>
            <p style="font-family:Calibri; font-size:11pt;">END COPY</p>

            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Promotion</h2>
            <p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
            <div id='devis-promotion' style='text-align: center;'>
                    <? if ($parser->parse($theProduct['promo'])): ?>
                    <span style='font-weight:bold; color: #BD3920; font-size: 14pt; font-family:Calibri;'>Promotion: </span><span style='font-weight:bold; color: #BD3920; font-size: 11pt; font-family:Calibri;'><?= $parser->parse($theProduct['promo']) ?></span>
                    <? else: echo "";
                    endif;
                    ?>
            </div>
            <p style="font-family:Calibri; font-size:9pt;">END COPY</p>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Conditions tarifaires</h2>
            <p style="font-family:Calibri; font-size:9pt;">BEGIN COPY</p>
            <div id='devis-condition'>
<?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
?>
<?
$condText = $parser->parse($theProduct['conditions']);
$condText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend :', 'Ce prix ne comprend pas :'], ['<h3 style="font-size:10pt; color:#BD3920;font-family:Calibri;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Calibri; font-size:10pt; margin:0;">', '<li style="font-family:Calibri; font-size:10pt;">', 'Ce prix comprend', 'Ce prix ne comprend pas'], $condText);
echo $condText;
$otherText = $parser->parse($theProduct['others']);
$otherText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend :', 'Ce prix ne comprend pas :'], ['<h3 style="font-size:10pt; color:#BD3920;font-family:Calibri;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Calibri; font-size:10pt; margin:0;">', '<li style="font-family:Calibri; font-size:10pt;">', 'Ce prix comprend', 'Ce prix ne comprend pas'], $otherText);
echo $otherText;
?>
            </div>
            <p style="font-family:Calibri; font-size:11pt;">END COPY</p>
        </div>
    </body>
    <div id="image-footer" style="display: none;"><!--Start Image-->
                                     <h2 style="background: #e3f2e1; font-weight: bold;">List ảnh footer </h2>
                                   <ul>
                                    <?

                                    $dir = '/var/www/my.amicatravel.com/www/assets/tools/docx/img-devis/footer';
                                    if (file_exists($dir)) {
                                        $photos = FileHelper::findFiles($dir, ['recursive' => false, 'only' => ['*.jpg','*.png']]);

                                        foreach ($photos as $pt) :
                                            $pt = trim(strrchr($pt, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR); 
                                            echo "<li>
                                                <p>".str_replace(['-', '.png', '.jpg'], [' ', '', ''], $pt)."</p>
                                                <img class='img-devis' style='width:150px' src='/timthumb.php?src=assets/tools/docx/img-devis/footer/".$pt."&w=150&h=60&zc=1'>
                                                <input type='checkbox' value='".$pt."'/>
                                                </li>";
                                               
                                        endforeach;
                                    }
                                    ?>
                                        </ul>
                                </div><!--End image-->
</html>
<style>
    
#image-footer li {
    display: inline-block;
    list-style: outside none none;
    padding: 15px;
    text-align: center;
    cursor: pointer;
}
#image-footer li:hover{
    opacity: 0.8;
}
#image-footer input {
    display: block;
    margin: 0 auto;
    text-align: center;
}
#image-footer > ul {
    width: 600px;
}
    .option-devis ul{
        margin: 0;
        padding: 0;
    }
    .option-devis ul h2{
        margin: 0;
    }
    .option-devis ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 150px;
        cursor: pointer;
    }
    .option-devis ul li:not(:last-of-type){
        border-right: 1px dotted #000000;
    }
    .option-devis ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .option-devis ul li img{
        cursor: pointer;
    }

    .option-devis ul li:hover{
        background: #E3F2E1;
    }
    #sale-detail-style2{
        display: none !important;
    }
    
    
     .option-img-banner-2 ul{
        margin: 0;
        padding: 0;
    }
    .option-img-banner-2 ul h2{
        margin: 0;
    }
    .option-img-banner-2 ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 150px;
        cursor: pointer;
    }
    .option-img-banner-2 ul li:not(:last-of-type){
       // border-right: 1px dotted #000000;
        margin-right: 5px;
    }
    .option-img-banner-2 ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .option-img-banner-2 ul li img{
        cursor: pointer;
    }

    .option-img-banner-2 ul li:hover{
        background: #E3F2E1;
    }
    .option-img-banner-2{ display: none;}
    //.option-img-banner-2.active{display: block;}
	
	//css-banner-anh-ngay-tour-devis
	
	 #toPopup #popup_content .test ul{
        margin: 0;
        padding: 0;
    }
    .test ul h2{
        margin: 0;
    }
    .test ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 185px;
        cursor: pointer;
        text-transform: capitalize;
    }
    .test ul li img{
        width: 185px !important;
        height: 90px;
    }
    .test ul li:not(:last-of-type){
       // border-right: 1px dotted #000000;
        margin-right: 5px;
    }
    .test ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .test ul li img{
        cursor: pointer;
    }

    .test ul li:hover{
        background: #E3F2E1;
    }
    .jour{display: none; position: relative;}
    .jour .remove{
        color: red;
        cursor: pointer;
        font-size: 50px;
        height: 40px;
        position: absolute;
        right: 10px;
        text-align: center;
        top: 10px;
        width: 40px;
        display: none;
        background: url('https://www.amica-travel.com/assets/img/xx.png') center center no-repeat;
        background-size: 100% auto;
        border-radius: 50%; 
    }
    .jour:hover .remove{
        display: block;
    }
   // .test{ display: none;}
    .download-devis{
        position: fixed;
        top: 50%;
        right: 20px;
        width: 120px;
        height: 50px;
    }
    .download-devis #downloadword{
        text-align: center;
        display: block;
        font-size: 20px;
        font-weight: bold;
    }
    //.option-img-banner-2.active{display: block;}
    #backgroundPopup {
    z-index:1;
    position: fixed;
    display:none;
    height:100%;
    width:100%;
    background:#000000;
    top:0px;
    left:0px;
}
.topopup {
    display: block;
    font-size: 20px;
    position: fixed;
    right: 20px;
    text-align: center;
    top: 40%;
    width: 120px;
}
#toPopup {
    font-family: arial,sans-serif;
    background: none repeat scroll 0 0 #FFFFFF;
    border: 10px solid #ccc;
    border-radius: 3px 3px 3px 3px;
    color: #333333;
    display: none;
    font-size: 14px;
   // left: 25%;
    //margin-left: -410px;
    position: fixed;
    top: 10%;
    width: 820px;
    z-index: 2;
}
div.loader {
    background: url("https://www.amica-travel.com/assets/img/loading.gif") no-repeat scroll 0 0 transparent;
    height: 32px;
    width: 32px;
    display: none;
    z-index: 9999;
    top: 50%;
    left: 50%;
    position: fixed;
    margin-left: -10px;
}
div.close {
    background: url("https://www.amica-travel.com/assets/img/closebox.png") no-repeat scroll -4px -3px transparent;
    cursor: pointer;
    height: 30px;
    position: absolute;
    right: -27px;
    top: -24px;
    width: 30px;
}
span.ecs_tooltip {
    background: none repeat scroll 0 0 #000000;
    border-radius: 2px 2px 2px 2px;
    color: #FFFFFF;
    display: none;
    font-size: 11px;
    height: 16px;
    opacity: 0.7;
    padding: 4px 3px 2px 5px;
    position: absolute;
    right: -62px;
    text-align: center;
    top: -51px;
    width: 93px;
}
span.arrow {
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 7px solid #000000;
    display: block;
    height: 1px;
    left: 40px;
    position: relative;
    top: 3px;
    width: 1px;
}
div#popup_content {
    margin: 4px 7px;
    /* remove this comment if you want scroll bar */
    overflow-y:auto;
    height:600px
    
}
	
/*CSS POPUP IMAGE_TABLEAU */
.topopup.add-image-tableau{
    top:30%;
}
.add-image-footer{
    display: block;
    font-size: 20px;
    position: fixed;
    right: 20px;
    text-align: center;
    top: 40%;
    width: 120px;
    top:20%;
}
.image-tableau ul{
        margin: 0;
        padding: 0;
    }
    .image-tableau ul h2{
        margin: 0;
    }
    .image-tableau ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 185px;
        cursor: pointer;
    }
    .image-tableau ul li img{
       // width: 185px !important;
       // height: 90px;
    }
    .image-tableau ul li:not(:last-of-type){
       // border-right: 1px dotted #000000;
        margin-right: 5px;
    }
    .image-tableau ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .image-tableau ul li img{
        cursor: pointer;
    }

    .image-tableau ul li:hover{
        background: #E3F2E1;
    }	
	
</style>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css">
<script>
    $(function() {
         $("a.fancybox").fancybox();
            $('#image-footer li').click(function(){
                $('#image-footer li input').prop('checked', false);
                $(this).find('input').prop('checked', true);
            })    
        $('.option-devis ul li').click(function() {
            $('.option-devis ul li input[type=checkbox]').prop('checked', false);
            $(this).find('input[type=checkbox]').prop('checked', true);
        });
        
         $('.option-img-banner-2 ul li').click(function() {
            $('.option-img-banner-2 ul li input[type=checkbox]').prop('checked', false);
            $(this).find('input[type=checkbox]').prop('checked', true);
        });
       $("#option-img-banner-2-fr").click(function(){
         if($("#option-img-banner-2-fr").is(':checked')){
            $('.option-img-banner-2').show();
        }
         if(!$("#option-img-banner-2-fr").is(':checked')){
            $('.option-img-banner-2').hide();
        }
        });
		
		$('.image-tableau ul li').click(function() {
            $('.image-tableau ul li input[type=checkbox]').prop('checked', false);
            $(this).find('input[type=checkbox]').prop('checked', true);
        });
		
		 $('.test ul li').click(function() {
            // $('.test ul li input[type=checkbox]').prop('checked', false);
            // $('#devis-detail .jour').hide();
            // $('#devis-detail .jour img').attr('src','');
            $(this).find('input[type=checkbox]').prop('checked', true);
            var jourdate = $('#jour-test').val();
            var img = $('.test ul li input[type=checkbox]:checked').val();
            $('.'+jourdate).show();
            $('.' + jourdate + ' img').attr('src','<?= DIR ?>upload/banner_date_tour_devis/'+img+'.jpg');
             alert('Thêm ảnh banner thành công');
           // var titlejour = $('.'+jourdate).parent().children('.title-'+jourdate).text();
		    var titlejour = $('#jour-test option:selected').text();
            var node = document.createElement("P");
            var textnode = document.createTextNode(titlejour + ' // image:'+ img );
            node.appendChild(textnode);
            $(node).addClass(jourdate);
            
            document.getElementById("myList").appendChild(node);
            $(node).append('<span class="del" style="color: red; float: right; margin-right: 20px; cursor: pointer;">Delete</span>');
        });
		 $(document).on('click', '#myList .del', function() {
             $(this).parent().remove();
            $('.test ul li input[type=checkbox]').prop('checked', false);
             var jour = $(this).parent().attr('class');
            $('.'+jour+' img').attr('src','').parent().hide();
            });
         $('.remove').click(function() {
             $('.test ul li input[type=checkbox]').prop('checked', false);
			 var jour = $(this).parent().attr('class').split(" ")[1];
             $(this).parent().hide();
             $(this).parent().children().attr('src','');
			 $('#myList .'+jour).remove();
         });
      $('#jour-test').change(function() {
              $('.test ul li input[type=checkbox]').prop('checked', false);
         });
         
          $("#option-image-jour").click(function(){
                if($("#option-image-jour").is(':checked')){
                   $('.test').show();
               }
                if(!$("#option-image-jour").is(':checked')){
                   $('.test').hide();
               }
          });
		
      //$('p em').parent().css('color','rgb(155, 46, 119)');

		$('.add-image-jours').click(function() {
            $('#popup_content .test').show();
            $('#popup_content .image-tableau').hide();
        });
         $('.add-image-tableau').click(function() {
            $('#popup_content .test').hide();
            $('#popup_content .image-tableau').show();
        });
    });
	//popup-option-add-image-date-tour
	jQuery(function($) {
     
    $("a.topopup").click(function() {
            loading(); // loading
            setTimeout(function(){ // then show popup, deley in .5 second
                loadPopup(); // function show popup
            }, 500); // .5 second
    return false;
    });
     
    /* event for close the popup */
    $("div.close").hover(
                    function() {
                        $('span.ecs_tooltip').show();
                    },
                    function () {
                        $('span.ecs_tooltip').hide();
                      }
                );
     
    $("div.close").click(function() {
        disablePopup();  // function close pop up
    });
     
    $(this).keyup(function(event) {
        if (event.which == 27) { // 27 is 'Ecs' in the keyboard
            disablePopup();  // function close pop up
        }      
    });
     
    $("div#backgroundPopup").click(function() {
        disablePopup();  // function close pop up
    });
     
    $('a.livebox').click(function() {
        alert('Hello World!');
    return false;
    });
     
 
     /************** start: functions. **************/
    function loading() {
        $("div.loader").show();  
    }
    function closeloading() {
        $("div.loader").fadeOut('normal');  
    }
     
    var popupStatus = 0; // set value
     
    function loadPopup() {
        if(popupStatus == 0) { // if value is 0, show popup
            closeloading(); // fadeout loading
            $("#toPopup").fadeIn(0500); // fadein popup div
            $("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
            $("#backgroundPopup").fadeIn(0001);
            popupStatus = 1; // and set value to 1
        }    
    }
         
     
    function disablePopup() {
        if(popupStatus == 1) { // if value is 1, close popup
            $("#toPopup").fadeOut("normal");  
            $("#backgroundPopup").fadeOut("normal");  
            popupStatus = 0;  // and set value to 0
        }
    }
    /************** end: functions. **************/
}); // jQuery End
	
    //end
    $('#download-word,#downloadword').click(function() {

        var template = $('.option-devis ul li input[type=checkbox]:checked').val();
         var img_banner_2 = $('.option-img-banner-2 ul li input[type=checkbox]:checked').val();
         
		 var img_banner_tableau = $('.image-tableau ul li input[type=checkbox]:checked').val(); 
         
        if (!template && !$("#b2b-fr-options").is(':checked')) {
            alert('Hãy chọn mẫu devis');
            return false;
        }
        var prix_option = $('#prix-option').val();
        if($("#en-options").is(':checked')){
            template = template+'_en';
            prix_option = 'Tour price';
        }
        if($("#b2b-fr-options").is(':checked')){
            template = 'B2B-FR';
        }
        if(!$("#option-img-banner-2-fr").is(':checked')){
            img_banner_2 = 'no';
        }
		
		 if(!$(".image-tableau ul li input[type=checkbox]").is(':checked')){
            img_banner_tableau = 'no';
        }
		
        if($("#option-img-banner-2-fr").is(':checked')){
            if(!img_banner_2){
                alert('Hãy chọn ảnh banner số 2!');
                return false;
            }
        }
       
        
        var file_name = '<?= SEG2.'-'.strtotime("now") ?>';
        var devis_prix = $('#devis-prix').text();

        devis_prix = '<span style="font-family: Calibri; font-size: 14pt; color:#BD3920;">' + prix_option + '</span> <b style="font-size: 14pt; font-family: Calibri; color: #BD3920;"><b> ' + devis_prix + ' </b>' + $('#prix-money').text() + '</b> <span style="font-family: Calibri; font-size: 14pt; color: #BD3920;">/</span> <span style="font-family: Calibri; font-size: 14pt; color: #BD3920;"> personne</span>';
        var url = $(this).attr('url');
        var devis_name = $('#devis-name').clone().wrap('<p>').parent().html();
        var devis_number = $('#devis-number').html();
        var devis_guest = $('#devis-guest').html();
        var devis_date = $('#devis-date').html();

        var devis_description = $('#devis-description').clone().wrap('<p>').parent().html();
        var sale_detail = '<div style="text-align: center;">' + $('#sale-detail').html() + '</div>';
        if (template.indexOf("multipays") > -1) {
            sale_detail = $('#sale-detail-style2').html();
        }
        var devis_table_programe = $('#devis-table-programe').clone().wrap('<p>').parent().html();
        var devis_detail = $('#devis-detail').clone().wrap('<p>').parent().html();
		var tableau_devis = $('#tableau-devis').clone().wrap('<p>').parent().html();
        var devis_table_tarif = $('#devis-table-tarif').clone().wrap('<p>').parent().html();
        var devis_promotion = $('#devis-promotion').clone().wrap('<p>').parent().html();
        var devis_condition = $('#devis-condition').clone().wrap('<p>').parent().html();
        var footer_image = false;
        if($('#image-footer li input[type=checkbox]:checked').length  > 0){
            footer_image = $('#image-footer li input[type=checkbox]:checked').val()
        }
        $('#loading').show();
        $.ajax({
            url: url,
            type: 'post',
            data: {
                footer_image: footer_image,
                file_name: file_name,
                template: template,
                img_banner_2: img_banner_2,
                devis_name: devis_name,
                devis_number: devis_number,
                devis_guest: devis_guest,
                devis_date: devis_date,
                devis_prix: devis_prix,
                devis_description: devis_description,
                sale_detail: sale_detail,
                devis_table_programe: devis_table_programe,
                devis_detail: devis_detail,
				tableau_devis: tableau_devis,
				img_banner_tableau : img_banner_tableau,
                devis_table_tarif: devis_table_tarif,
                devis_promotion: devis_promotion,
                devis_condition: devis_condition
            },
			async:true,
            dataType: 'json',
            success: function(data) {
                location.href = '/upload/output/devis-ims/' + file_name + '.docx';
                $('body').html('<div style="font:26px/36px Arial, sans-serif; margin:100px auto; text-align:center;"><a href="/upload/output/devis-ims/' + file_name + '.docx">DOWNLOAD DOESN\'T START?<br>USE THIS DIRECT LINK TO DOWNLOAD YOUR FILE</a></div><div style="text-align:center; font:14px/20px Arial, sans-serif"><a href="?" style="color:red;">OR CLICK HERE TO START OVER</a></div>');
            },
            async: false

        });
        return false;
    })
</script>
<script>
    
  $('#tableau-devis tr:nth-of-type(2n) td').css("background-color","rgb(250,233,244)");
  
</script> 