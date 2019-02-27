<?
use yii\helpers\Html;
use yii\helpers\Markdown;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

$dayIdList = explode(',', $theProduct['day_ids']);

?><html lang="vi">
<head>
    <meta charset="utf-8">
    <title></title>
    <style>
html, body {font-family:Bell MT; font-size:11pt; line-height:16pt;}
#wrap {margin:0 2.5cm;}
h1, h2, h3, h4, h5, h6 {font-variant:small-caps;}
h2 {padding:0; margin:0; font-size:14pt; margin-bottom:14pt;}
h3 {padding:0; margin:0; font-size:13pt; margin-bottom:13pt;}
h4 {padding:0; margin:0; font-size:11pt; margin-bottom:11pt;}
p {padding:0; margin:0; margin-bottom:11pt; text-align:justify;}
li {padding-left:24pt; list-style-type:square;}
.pink {color:#e43a92}
.text-center {text-align:center;}
.text-right {text-align:right;}
.text-bold {font-weight:bold;}
.lined {padding-bottom:2px; border-bottom:1px solid #e43a92;}
table {border-collapse:collapse;}
.table {width:100%;}
.table-bordered {border:1px solid #ddd;}
.table-bordered th, .table-bordered td {padding:8px 6px; border:1px solid #ddd;}
.mb {margin-bottom:11pt;}
.mb2 {margin-bottom:22pt;}
.newpage {page-break-before:always;}

@page {
    margin-left:0;
    margin-right:0;
    margin-header:0;
    margin-top:2.8cm;
    margin-footer:0;
    margin-bottom:2.2cm;
    header:myHTMLHeader;
    footer:myHTMLFooter;
}
    </style>
</head>
<body>
    <htmlpageheader name="myHTMLHeader" style="display:none">
        <img src="/upload/huan/sic_header_fr_170622.jpg"/>
    </htmlpageheader>
    <htmlpagefooter name="myHTMLFooter" style="display:none">
        <img src="/upload/huan/sic_footer_170622.jpg"/>
    </htmlpagefooter>

    <? //require_once('_pdf_header_footer_fr.php') ?>
    <img style="margin:-0.3cm 0 0.5cm" src="https://www.amica-travel.com/upload/banner_2_devis/b2b/banner_new/54-si_phan_don_laos.jpg">
    <div id="wrap">
        <h2 id="" class="pink"><?= $theProduct['title'] ?></h2>
        <h3 id=""><?= $theProduct['title'] ?></h3>

        <h4>Esprit</h4>
        <div id="text-esprit">
            <?= $theProduct['metas']['text_esprit']['value'] ?? '' ?>
        </div>

        <h4>Points forts</h4>
        <div class="text">
            <?= $theProduct['metas']['text_points']['value'] ?? '' ?>
        </div>

        <h3 class="pink newpage">Programme en bref</h3>
        <table class="table table-bordered mb2">
            <thead>
                <tr>
                    <th class="pink text-bold text-center">Jour</th>
                    <th class="pink text-bold text-center">Date</th>
                    <th class="pink text-bold text-center">Itinéraire</th>
                    <th class="pink text-bold text-center">Accompagnement</th>
                    <th class="pink text-bold text-center">Repas inclus</th>
                </tr>
            </thead>
            <tbody>
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
                    <td class="text-center">Jour <?= $cnt ?></td>
                    <td class="text-center"><?= $ngay ?></td>
                    <td><?= $ng['name'] ?></td>
                    <td class="text-center"><?= $ng['guides'] ?></td>
                    <td class="text-center">
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
            </tbody>
        </table>
        <p class="text-right"><strong class="pink">Légende des repas</strong> | <strong class="pink">B</strong> Petit-déjeuner | <strong class="pink">L</strong> Déjeuner | <strong class="pink">D</strong> Dîner</p>

        <h3 class="pink newpage">(Carte)</h3>
        <table class="table">
            <tr>
                <td style="height:25cm; vertical-align:middle;"><img src="https://my.amicatravel.com/upload/products/57445/image/map-to-devis.png"></td>
            </tr>
        </table>
        

        <h3 class="pink newpage">Programme en details</h3>
        <div class="mb2">
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
                <h4>Jour <?= $cnt ?> - <?= $ngay ?><span style=""> | <?= $ng['name'] ?> (<?= implode(', ',str_split($ng['meals'])) ?>)</span></h4>
                <div class="mb2">
                <?= $ng['transport'] == '' ? '' : '<p>' . $ng['transport'] . '</p>' ?>
                <?
                $txxt = $parser->parse($ng['body']);
                // $txxt = str_replace('<p>', '<p style="font-family:Bell MT; font-size:11pt;line-height: 20pt; text-align: justify;">', $txxt);
                // $txxt = str_replace('<li>', '<li style="font-family:Bell MT; font-size:11pt;">', $txxt);
                // $txxt = str_replace('<strong>', '<strong style="font-family:Bell MT; font-size:11pt;">', $txxt);
                // $txxt = str_replace('<em>', '<em style="font-family:Bell MT; font-size:11pt;">', $txxt);
                // $txxt = str_replace(['<span class="caps">', '</span>'], ['', ''], $txxt);
                echo $txxt;
                ?>
                </div>
                        <?
                    }
                }
            }
            ?>
        </div>

        <h3 class="pink">Hébergements</h3>
        <div class="mb2">
            <table class="table table-bordered">
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
                        if ($optcnt != 1) echo '</table>' . chr(10) . '<table class="table table-bordered">';
                        echo '<p style="font-family:Bell MT; font-size:11pt;color:rgb(228,58,146);"><strong>' . trim(substr($theProductp, 7)) . '</strong></p>';
                    ?>
                    <tr style="background-color:#c8c8c8">
                        <th class="text-bold text-center pink">Destinations</th>
                        <th class="text-bold text-center pink">Hôtel/Resort & Website</th>
                        <th class="text-bold text-center pink">Nuitée(s)</th>
                        <th class="text-bold text-center pink">Type de chambre</th>
                    </tr><?
                    }
                    if (substr($theProductp, 0, 2) == '+ ') {
                        $line = trim(substr($theProductp, 2));
                        $line = explode(':', $line);
                        for ($i = 0; $i < 5; $i ++) if (!isset($line[$i])) $line[$i] = '';
                    ?>
                    <tr style="background-color:<?= $count % 2 == 0 ? 'auto' : '#f0f0f0' ?>">
                        <td class="text-center"><?= $line[0] ?></td>
                        <td class="text-center"><?= trim($line[3]) != '' ? Html::a( trim($line[1]), 'http://'.trim($line[3]), ['class' => 'profile-link','style'=>'color: black;']) : trim($line[1]) ?></td>
                        <td class="text-center"><?= $line[4] ?></td>
                        <td class="text-center"><?= trim($line[2]) ?></td>
                    </tr><?
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

        <h3 class="pink">Tarifs</h3>
        <div id='devis-condition'>
            <p style="font-family:Bell MT; font-size:11pt;">
                Valable jusqu’au ...................... . Tarifs en <b>dollar américain/personne</b> hors des frais bancaires.
            </p>
            <table class="table table-bordered mb2">
                <tr style="background-color:#c8c8c8;">
                    <td class="pink text-bold text-center">Taille du groupe</td>
                    <td class="pink text-bold text-center">2<br>pax</td>
                    <td class="pink text-bold text-center">3<br>pax</td>
                    <td class="pink text-bold text-center">4<br>pax</td>
                    <td class="pink text-bold text-center">5<br>pax</td>
                    <td class="pink text-bold text-center">6<br>pax</td>
                    <td class="pink text-bold text-center">7<br>pax</td>
                    <td class="pink text-bold text-center">8<br>pax</td>
                    <td class="pink text-bold text-center">9<br>pax</td>
                    <td class="pink text-bold text-center">10<br>pax</td>
                    <td class="pink text-bold text-center">11<br>pax</td>
                    <td class="pink text-bold text-center">12<br>pax</td>
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

            <table class="table table-bordered">
                <tr>
                       
<?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
?>
<?
$condText = $parser->parse($theProduct['conditions']);
//var_dump($condText);exit;
$condText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend', 'Ce prix ne comprend pas', '</ul>'], ['<td style="vertical-align: top; width: 50%;"><h3 style="font-size:11pt; color:black;font-family:Bell MT; text-align:center;">', '<ul style="margin:0 0 0 0; list-style-type: square;">', '<p style="font-family:Bell MT; font-size:11pt; margin:0; line-height: 20pt;">', '<li style="font-family:Bell MT; font-size:11pt; line-height: 20pt;">', 'INCLUSIONS', 'EXCLUSIONS','</ul></td>'], $condText);
//var_dump($condText);exit;
echo $condText;

?>
                         
                </tr>
            </table>    
        </div>

        <h3 class="pink">Notes</h3>
        <div id='devis-others'>
            <?
                $otherText = $parser->parse($theProduct['others']);
                //var_dump($otherText);exit;
                $otherText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend', 'Ce prix ne comprend pas'], ['<div class="text-center text-bold">', '<ul style="margin:0; padding:0;">', '<p>', '<li>', 'INCLUSIONS', 'EXCLUSIONS'], $otherText);
                echo $otherText;
            ?>
        </div>
    </div><!-- #wrap -->
</body>
</html>
