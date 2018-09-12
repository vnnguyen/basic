<?
use yii\helpers\Markdown;

// require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

$dayIdList = explode(',', $theProduct['day_ids']);

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

$this->title = $theProduct['title'];

$theProduct['gender'] = 'f';
$theProduct['avatar'] = 'avatar';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title><?= $theProduct['title'] ?> - <?= count($dayIdList) ?> jours - devis - <?= $theProduct['created_at'] ?></title>
</head>
<body style="font-family:Calibri; font-size:11pt;">
<div style="width:800px; margin:auto;">
<h1 style="color:036; font-size:40px; margin:0 0 20px;">Text dùng cho file devis</h1>
<p>Designer: Elodie | Date: 22/11/2013</p>
<p style="font-family:Calibri; font-size:11pt;">ĐÂY LÀ TEXT DÙNG CHO FILE MẪU DEVIS MỚI NHẤT CỦA AMICA (13 TEMPLATE WORD KHÁC NHAU)
<br />Để xem hướng dẫn cách in devis theo mẫu mới, <a href="/kb/posts/r/21">hãy click vào đây</a>.
<br />Các trường hợp phải dùng mẫu in cũ, <a href="/ct/print-old/<?= SEG3 ?>">hãy click vào đây</a>.
<br />In chương trình bằng tiếng Anh, <a href="/ct/print-old/<?= SEG3 ?>/en">hãy click vào đây</a>.
<br />Nếu muốn mẫu in cũ hơn nữa (version đầu tiên), <a href="/ct/print-old/<?= SEG3 ?>?old">hãy click vào đây</a>.
</p>
<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Front page | Trang bìa</h2>
<h3 style="font-family:Calibri; font-size: 11pt"><?=$theProduct['title']?></h3>
<p style="font-family:Calibri; font-size:11pt;">
<strong>Type du voyage:</strong> en individuel
<br /><strong>Devis personnalisé pour:</strong> <?= $theProduct['about'] ?> | ten pax ONLY
<br /><strong>Durée & Date du voyage:</strong> <?= count($dayIdList) ?> jours sur place, du <?=str_replace('/','|',date('d/m/Y', strtotime($theProduct['day_from'])))?> au <?=str_replace('/','|',date('d/m/Y', strtotime('+ '.(count($dayIdList) - 1).' day', strtotime($theProduct['day_from']))))?>
</p>

<h3 style="font-family:Calibri; font-size:11pt;font-weight: bold;">Les points forts du programme</h3>
<? $points = $parser->parse($theProduct['points']);
$points = str_replace('<p style="font-family:Calibri;">', '<p style="font-family:Calibri; font-size:11pt;">', $points);
$points = str_replace('+', '&#8226;', $points);
echo $points;
?>

<h2 style="font-family: Calibri;border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Tableau synthétique du programme</h2>
<p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
<table style="width: 100%;">
<thead>
<tr style="margin: 2mm 0;">
<th style="text-align:left; width: 11mm; padding: 2mm 1mm 2mm 0;"><h3 style="font-family:Calibri; font-size:11pt; color:#a00d80; margin:0;">Jour</h3></th>
<th style="text-align:left;width:  25mm;"><h3 style="font-family:Calibri; font-size:11pt; color:#a00d80; margin:0; padding-right: 1mm;">Date</h3></th>
<th style="text-align:left;"><h3 style="font-family:Calibri; font-size:11pt; color:#a00d80; margin:0;padding-right: 1mm;">Itinéraire</h3></th>
 <th style="text-align:left;width: 28mm;"><h3 style="font-family:Calibri; font-size:11pt; color:#a00d80; margin:0;padding-right: 1mm;">Accompagnement</h3></th>
<th style="text-align:left;width: 22mm;"><h3 style="font-family:Calibri; font-size:11pt; color:#a00d80; margin:0;">Repas inclus</h3></th>
</tr>
</thead>
<? $cnt = 0;
foreach ($dayIdList as $di) {
foreach ($theProduct['days'] as $ng) {
if ($ng['id'] == $di) {
$cnt ++;
$ngay = date('D d|m|Y', strtotime($theProduct['day_from'].' + '.($cnt - 1).'days'));
$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
//$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
$ngay_fr = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
$ngay = str_replace($ngay_en, $ngay_fr, $ngay);
?>
<tr>
<td style="font-family:Calibri; font-size:9pt;font-weight: bold;  padding: 2mm 0;"><strong>Jour <?=$cnt?></strong></td>
<td style="font-family:Calibri; font-size:9pt;"><?=$ngay?></td>
<td style="font-family:Calibri; font-size:9pt;font-weight: bold;  padding: 2mm 0;" ><strong><?=$ng['name']?></strong></td>
<td style="font-family:Calibri; font-size:9pt;" ><?=$ng['guides']?></td>
<td style="font-family:Calibri; font-size:9pt;">
<?
if (strpos($ng['meals'], 'B') !== false) {echo 'B ';} else {echo '&mdash; ';}
if (strpos($ng['meals'], 'L') !== false) {echo 'L ';} else {echo '&mdash; ';}
if (strpos($ng['meals'], 'D') !== false) {echo 'D ';} else {echo '&mdash; ';}
?>
</td>
</tr>
<? } } } ?>
</table>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>
<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Descriptif détaillé du programme</h2>
<p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
<? $cnt = 0;
foreach ($dayIdList as $di) {
foreach ($theProduct['days'] as $ng) {
if ($ng['id'] == $di) {
$cnt ++;
$ngay = date('D d|m|Y', strtotime($theProduct['day_from'].' + '.($cnt - 1).'days'));
$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
$ngay = str_replace($ngay_en, $ngay_fr, $ngay);
$ngay = str_replace('|', '<span style="font-weight:normal">|</span>', $ngay);
?>
<h4 style="font-size:13pt; color:rgb(172,0,132);font-family:Calibri;">Jour <?=$cnt?> - <?=$ngay?><span style="font-weight:normal"> | <?=$ng['name']?> (<?=$ng['meals']?>)</span></h4>
<div>
<?=$ng['transport'] == '' ? '' : '<p style="font-family:Calibri; font-size:11pt;">'.$ng['transport'].'</p>'?>
<?
$txxt = $parser->parse($ng['body']);
$txxt = str_replace('<p>', '<p style="font-family:Calibri; font-size:11pt;">', $txxt);
$txxt = str_replace('<li>', '<li style="font-family:Calibri; font-size:11pt;">', $txxt);
$txxt = str_replace('<strong>', '<strong style="font-family:Calibri; font-size:11pt;">', $txxt);
$txxt = str_replace('<em>', '<em style="font-family:Calibri; font-size:11pt;">', $txxt);
$txxt = str_replace(['<span class="caps">', '</span>'], ['', ''], $txxt);
echo $txxt;
?>
</div>
<? } } } ?>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>
<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Les tarifs</h2>
<p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
<p style="font-family:Calibri; font-size:11pt;">Conditions tarifaires établies le <?=date('d-m-Y', strtotime($theProduct['created_at']))?></p>
<table class="simple" style="width: 100%; margin: 0;border-collapse: collapse;border-spacing: 1mm;">
<? // Gia va cac options
$theProductpx = $theProduct['prices'];
$theProductpx = explode(chr(10), $theProductpx);
$last = array();
for($i=1; $i< count($theProductpx); $i++){
    if(substr($theProductpx[$i], 0, 7) == 'OPTION:'){
        $last[] = $i;
    }
}
$optcnt = 0;
$count = 0;
foreach ($theProductpx as $theProductp) {
$count++; 
   
if (substr($theProductp, 0, 7) == 'OPTION:') {
$optcnt ++;
if ($optcnt != 1) echo '</table>'.chr(10).'<table class="simple" style="width: 100%; margin: 0;border-collapse: collapse;border-spacing: 1mm;">';
echo '<p style="font-family:Calibri; font-size:11pt;color:#a00d80;"><strong>'.trim(substr($theProductp, 7)).'</strong></p>';
echo '<tr style="height: 16mm;">
                <th style="border-right: 1px solid black;padding-right: 1mm;border-bottom: 1px solid black;width: 20mm;"><h3 style="font-family:Calibri; color:#a00d80; font-size:11pt;">Ville</h3></th>
                <th style="border-right: 1px solid black;padding-right: 1mm;border-bottom: 1px solid black; width: 40mm"><h3 style="font-family:Calibri; color:#a00d80; font-size:11pt;">Hôtel</h3></th>
                <th style="border-right: 1px solid black;padding-right: 1mm;border-bottom: 1px solid black; width: 38mm;"><h3 style="font-family:Calibri; color:#a00d80; font-size:11pt;">Catégorie chambre</h3></th >
                <th style="border-bottom: 1px solid black;"><h3 style="font-family:Calibri; color:#a00d80; font-size:11pt; ">Référence</h3></th>
                </tr>';
}
if (substr($theProductp, 0, 2) == '+ ') {
$line = trim(substr($theProductp, 2));
$line = explode(':', $line);
for ($i = 0; $i < 4; $i ++) if (!isset($line[$i])) $line[$i] = '';
echo '<tr>
<td style="border-right: 1px solid black;border-bottom: 1px solid black;font-family:Calibri; font-size:9pt;  padding: 4mm 2mm 4mm 0;"><strong>'.$line[0].'</strong></td>
<td style="border-right: 1px solid black;border-bottom: 1px solid black;font-family:Calibri; font-size:9pt; padding: 4mm 2mm;">'.$line[1].'</td>
<td style="border-right: 1px solid black;border-bottom: 1px solid black;font-family:Calibri; font-size:9pt;  padding-left: 2mm;padding-right: 2mm;">'.$line[2].'</td>
<td style="border-bottom: 1px solid black;font-family:Calibri; font-size:9pt; padding: 4mm 0 4mm 2mm;" class="a-href">'.trim($line[3]).'</td></tr>';
}

if (substr($theProductp, 0, 2) == '- ') {
$line = trim(substr($theProductp, 2));
$line = explode(':', $line);
for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
$line[1] = (int)trim($line[1]);
if(($count == count($theProductpx)) || in_array($count, $last)){
    echo '<tr><td style="border-right: 1px solid black;font-family:Calibri; font-size:11pt;  padding: 4mm 1mm 4mm 0;" colspan="3" class="ta-r"><strong>'.$line[0].'</strong></td><td style="text-align: center;"><h3 style="font-size:12pt;font-family:Calibri; color:#a00d80;">'.number_format($line[1]).' '.str_replace('EUR','&euro;',$theProduct['price_unit']).'</h3></td></tr>';
}
else{
    echo '<tr><td style="border-right: 1px solid black;border-bottom: 1px solid black;font-family:Calibri; font-size:11pt;  padding: 4mm 1mm 4mm 0;" colspan="3" class="ta-r"><strong>'.$line[0].'</strong></td><td style="text-align: center;border-bottom: 1px solid black;"><h3 style="font-size:12pt;font-family:Calibri; color:#a00d80;">'.number_format($line[1]).' '.str_replace('EUR','&euro;',$theProduct['price_unit']).'</h3></td></tr>';
}

}
}
?>
</table>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>

<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Promotion</h2>
<p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
<div>
<?=$parser->parse($theProduct['promo'])?>
</div>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>
<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Conditions tarifaires</h2>
<p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
<?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
?>
<?
$condText = $parser->parse($theProduct['conditions']);
$condText = str_replace(['<h3>', '<ul>', '<p>','<li>','Ce prix comprend :','Ce prix ne comprend pas :'], ['<h3 style="font-size:12pt; color:rgb(172,0,132);font-family:Calibri;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Calibri; font-size:9pt; margin:0;">','<li style="font-family:Calibri; font-size:9pt;">','Ce prix comprend','Ce prix ne comprend pas'], $condText);
echo $condText;
$otherText = $parser->parse($theProduct['others']);
$otherText = str_replace(['<h3>', '<ul>', '<p>','<li>','Ce prix comprend :','Ce prix ne comprend pas :'], ['<h3 style="font-size:12pt; color:rgb(172,0,132);font-family:Calibri;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Calibri; font-size:9pt; margin:0;">','<li style="font-family:Calibri; font-size:9pt;">','Ce prix comprend','Ce prix ne comprend pas'], $otherText);
echo $otherText;
?>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>
</div>
</body>
</html>