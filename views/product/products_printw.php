<?
use yii\helpers\Html;
use yii\helpers\Markdown;

require_once('/var/www/my.amicatravel.com/lib/flourish/hxTextile.php');
$txt = new hxTextile;

$dayIdList = explode(',', $theProduct['day_ids']);

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

$this->title = $theProduct['title'];

$theProduct['gender'] = 'f';
$theProduct['avatar'] = 'avatar';
/*
$data = array(
	'file_name' => $_POST['file_name'],
    'template' => $_POST['template'],
    'devis_name' => $_POST['devis_name'],
    'devis_number' => $_POST['devis_number'],
    'devis_guest' => '<span style="font-family:Candara; color:white; font-size: 11pt;font-weight:bold;">'.$_POST['devis_guest'].'</span>',
    'devis_date' => '<span style="font-family:Candara; color:white; font-size: 10pt;font-weight:bold;">'.$_POST['devis_date'].'</span>',
	'devis_prix' => $_POST['devis_prix'],
    'devis_description' => $_POST['devis_description'],
    'sale_detail' => '<div style="text-align: right;">'.$_POST['sale_detail'].'</div>',

    'devis_table_programe' => $_POST['devis_table_programe'],
    'devis_detail' => $_POST['devis_detail'],
    'devis_table_tarif' => $_POST['devis_table_tarif'],
    'devis_promotion' => $_POST['devis_promotion'],
    'devis_condition' => $_POST['devis_condition']
);
*/
?>
<style type="text/css">
img.img-devis {height:150px;}
</style>
<div class="col-md-6">
	
<form method="post" action="http://www.amica-travel.com/ajaxphpdocx/ims_to_word_huan.php" class="form">
	<input type="hidden" name="file_name" value="test.docx">
	<input type="hidden" name="devis_name" value="Name of devis">
	<input type="hidden" name="devis_guest" value="4">
	<input type="hidden" name="devis_date" value="12/5/2014">
	<input type="hidden" name="devis_prix" value="1234 EUR">
	<input type="hidden" name="devis_description" value="Nothign toi say">
	<input type="hidden" name="sale_detail" value="XXXX">
	<input type="hidden" name="devis_table_programe" value="XXXX">
	<input type="hidden" name="devis_detail" value="XXXX">
	<input type="hidden" name="devis_promotion" value="<?= Html::encode($txt->textileThis($theProduct['promo'])) ?>">
	<input type="hidden" name="devis_condition" value="<?= Html::encode($txt->textileThis($theProduct['conditions'])) ?>">

	<p><strong>Vietnam</strong></p>
	<div class="row">
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
	</div>

	<p><strong>Laos</strong></p>
	<div class="row">
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3"></div>
		<div class="col-md-3"></div>
	</div>

	<p><strong>Cambodia</strong></p>
	<div class="row">
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_01vietnam_classique"> Vietnam Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3"></div>
	</div>

	<p><strong>Thailand</strong></p>
	<div class="row">
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_13_thailande_classique"> Thailand Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/thailand/devis_base_13_thailande_classique.jpg"></p>
		</div>
	</div>

	<p><strong>Multiple countries</strong></p>
	<div class="row">
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_010_MULTIPAYS_classique"> Multipays Classique</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_011_MULTIPAYS_aventure"> Multipays Aventure</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3">
			<p><label><input type="radio" name="template" value="devis_base_012_multipays_luxury"> Multipays Luxury</label></p>
			<p class="thumbnail"><img class="img-devis" class="img-responsive" src="http://www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg"></p>
		</div>
		<div class="col-md-3"></div>
	</div>
<? if (1==0): ?>
	<div class="option-devis">
		<p>Hãy chọn 1 mẫu devis, điền giá hiển thị trang bìa và điền tên file word để khởi tạo</p>
		<p>
		<select id='prix-option' style='width: 110px;'>
		<option>prix à partir de</option>
		<option>prix</option>
		</select>
		<input type='text' id='devis-prix' placeholder='Điền giá tại đây!' style='width:100px;'/>
		<select id='prix-money'>
		<option>€</option>
		<option>$</option>
		</select>
		</p>

		<input type='text' id='file-name' placeholder='Viết tên file tại đây' style='width: 200px;'/>
		<ul>
		<h2>Việt Nam</h2>
		<li>
		<p>Vietnam Classique</p>
		
		
		</li>
		<li>
		<p>Vietnam Immersion</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_02vietnam_IMMERSION.jpg'>
		<input type="checkbox" value="devis_base_02vietnam_IMMERSION"/>
		</li>
		<li>
		<p>Vietnam Ethnies</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_03vietnam_ETHNIES.jpg'>
		<input type="checkbox" value="devis_base_03vietnam_ETHNIES"/>
		</li>
		<li>
		<p>Vietnam Ethnies</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/vietnam/devis_base_04vietnam_balneaire.jpg'>
		<input type="checkbox" value="devis_base_04vietnam_balneaire"/>
		</li>
		</ul>
		<ul>
		<h2>Laos</h2>
		<li>
		<p>Laos Classique</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/laos/devis_base_04LAOS_classique.jpg'>
		<input type="checkbox" value="devis_base_01vietnam_classique"/>
		</li>
		<li>
		<p>Laos Aventure</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/laos/devis_base_05LAOS_AVENTURE.jpg'>
		<input type="checkbox" value="devis_base_05LAOS_AVENTURE"/>
		</li>
		</ul>
		<ul>
		<h2>Cambodge</h2>
		<li>
		<p>Cambodge Classique</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/cambodge/devis_base_06CAMBODGE_classique.jpg'>
		<input type="checkbox" value="devis_base_06CAMBODGE_classique"/>
		</li>
		<li>
		<p>Cambodge Aventure</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/cambodge/devis_base_07CAMBODGE_aventure.jpg'>
		<input type="checkbox" value="devis_base_07CAMBODGE_aventure"/>
		</li>
		<li>
		<p>Cambodge Balneaire</p>
		<img class="img-devis" style="width: 70px" src='//www.amica-travel.com/assets/img/devis-ims/cambodge/devis_base_08CAMBODGE_BALNEAIRE.jpg'>
		<input type="checkbox" value="devis_base_08CAMBODGE_BALNEAIRE"/>
		</li>
		</ul>
		<ul>


		</ul>
		<ul>
		<h2>Thailand</h2>
		<li>
		<p>Thailand Classique</p>
		<img class="img-devis" style="width: 70px" src='/assets/img/devis-ims/thailand/devis_base_13_thailande_classique.jpg'>
		<input type="checkbox" value="devis_base_13_thailande_classique"/>
		</li>
		</ul>
		</div>
	</div>
<? endif; ?>
    <p><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></p>
</form>
</div>

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
<? $points = $txt->textileThis($theProduct['points']);
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
$txxt = $txt->textileThis($ng['body']);
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
$line[1] = trim($line[1]);
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
<?=$txt->textileThis($theProduct['promo'])?>
</div>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>
<h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Conditions tarifaires</h2>
<p style="font-family:Calibri; font-size:11pt;">BEGIN COPY</p>
<?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
?>
<?
$condText = $txt->textileThis($theProduct['conditions']);
$condText = str_replace(['<h3>', '<ul>', '<p>','<li>','Ce prix comprend :','Ce prix ne comprend pas :'], ['<h3 style="font-size:12pt; color:rgb(172,0,132);font-family:Calibri;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Calibri; font-size:9pt; margin:0;">','<li style="font-family:Calibri; font-size:9pt;">','Ce prix comprend','Ce prix ne comprend pas'], $condText);
echo $condText;
$otherText = $txt->textileThis($theProduct['others']);
$otherText = str_replace(['<h3>', '<ul>', '<p>','<li>','Ce prix comprend :','Ce prix ne comprend pas :'], ['<h3 style="font-size:12pt; color:rgb(172,0,132);font-family:Calibri;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Calibri; font-size:9pt; margin:0;">','<li style="font-family:Calibri; font-size:9pt;">','Ce prix comprend','Ce prix ne comprend pas'], $otherText);
echo $otherText;
?>
<p style="font-family:Calibri; font-size:11pt;">END COPY</p>
</div>