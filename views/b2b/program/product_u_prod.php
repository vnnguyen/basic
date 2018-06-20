<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_program_inc.php');

if ($theProgram->isNewRecord) {
	$this->title = 'New tour product (B2B PROD)';
	$this->params['breadcrumb'][] = ['New', 'products/c?b2b=prod'];
} else {
	$this->title = 'Edit: '.$theProgram['title'];
	$this->params['breadcrumb'][] = ['View', 'products/r/'.$theProgram['id']];
	$this->params['breadcrumb'][] = ['Edit', 'products/u/'.$theProgram['id']];
}

// cac file anh banner
$files = scandir(Yii::getAlias('@webroot').'/upload/devis-banners/', 1);
asort($files);
$fileNameList = [];
foreach ($files as $k=>$v) {
	if ($v != '.' && $v != '..') {
		$fileNameList[] = ['name'=>$v];
	}
}

$conds = <<<TXT
h3. Ce prix comprend :

* Hébergement pour tout le parcours dans les hôtels listés au programme ou, en cas d’indisponibilité de ceux-ci, dans des hôtels équivalents. 
* Tous les déplacements selon le programme en véhicule privatif.
* Les repas comme mentionnés dans le programme (B = Petit Déjeuner ; L = Déjeuner ; D = Dîner).
* Guides accompagnateurs francophones pour tout le circuit.
* Droits d'entrée des sites à visiter.
* Les billets de volss domestiques : Hué - Hanoi, Buon Me Thuoc – Da Nang par Vietnam Airlines (la plus grande compagnie aérienne du Vietnam)
* Un bateau collectif avec une cabine privée à deux dans la baie d’Halong, 
* Les frais de dossier
* Les taxes
* Tous les services logistiques nécessaires pour l'organisation du programme.

h3. Ce prix ne comprend pas :

* Vols et taxes d'aéroport internationaux depuis/ vers votre pays.
* Pourboire, boissons, téléphone et tout ce qui n’est pas clairement mentionné dans la rubrique « Le prix comprend ». (Pour le pourboire pour guide et chauffeur, à prévoir environ de 3 à 4 Euros par jour par personne, si vous êtes contents de leurs services).
TXT;

$more = <<<TXT
h3. Les plus d’Amica Travel 

* Un petit guide culturel (de 80 pages) du Vietnam mis à la disposition de chaque voyageur dès l’arrivée
* Cadeau de bienvenue
* Boissons fraiches durant les transferts routiers
* Suivi 24h/24 du voyage, depuis le bureau de Hanoi, par un agent clientèle dédié
* Présence téléphonique en France

h3. Conditions de paiement

* Si vous souhaitez payez en Euros, la somme à payer sera reconvertie en Euros selon le taux de change de référence  publié par la Banque Centrale Européenne à la date la plus proche de celle du paiement. Ce taux sera consulté sur le Site Internet de cette Banque, en cliquant sur le lien : "http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html":http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html
* Un acompte de 25% du prix total est à verser par virement bancaire ou par carte bancaire via Internet dès la réservation
* Le solde de 75% est à payer au commencement du voyage, en liquide ou par carte bancaire
* Les frais bancaires liés au paiement sont à la charge du client

h3. Conditions d’annulation

En cas d’annulation du voyage, le client doit payer des pénalités qui correspondent:

* à 3% du prix total du voyage, si son annulation parvient à Amica Travel dans un délai égal ou supérieur à 45 jours avant le commencement du voyage ;
* à 5% du prix total du voyage, si son annulation parvient à Amica Travel de 31 à 45 jours avant le commencement du voyage ;
* à 10% du prix total du voyage, si son annulation parvient à Amica Travel de 15 à 30 jours avant le commencement du voyage ;
* à 15% du prix total du voyage, si son annulation parvient à Amica Travel de 7 à 14 jours avant le commencement du voyage ;
* à 20% du prix total du voyage, si son annulation parvient à Amica Travel de 72 heures à 6 jours avant le commencement du voyage ;
* à 25 % du prix total du voyage, si son annulation parvient à Amica Travel moins de 72 heures avant le commencement du voyage.
TXT;
?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-6"><?= $form->field($theProgram, 'title') ?></div>
		<div class="col-md-6"><?= $form->field($theProgram, 'about') ?></div>
	</div>
	<?= $form->field($theProgram, 'tags') ?>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theProgram, 'language')->dropdownList($languageList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-1">Days<br><?= $theProgram['day_count'] ?></div>
		<div class="col-md-2"><?= $form->field($theProgram, 'pax') ?></div>
	</div>
	<?= $form->field($theProgram, 'intro')->textArea(['rows'=>3]) ?>

	<p><strong>PRICES AND PROMOTIONS</strong></p>
	<?= $form->field($theProgram, 'prices')->textArea(['rows'=>15]) ?>
	<?= $form->field($theProgram, 'promo')->textArea(['rows'=>10]) ?>

	<?= $form->field($theProgram, 'conditions')->textArea(['rows'=>10]) ?>
	<?= $form->field($theProgram, 'others')->textArea(['rows'=>10]) ?>

	<p><strong>NOTE (FOR AMICA ONLY)</strong></p>
	<?= $form->field($theProgram, 'summary')->textArea(['rows'=>3]) ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
	<?= $form->field($theProgram, 'image')->dropdownList(ArrayHelper::map($fileNameList, 'name', 'name'), ['id'=>'header-image', 'prompt'=>'- Select -']) ?>
	<div id="image-preview" class="mb-1em">
		<? if ($theProgram['image'] != '') { ?>
		<img class="img-responsive thumbnail" src="<?= DIR ?>upload/devis-banners/small/<?= $theProgram['image'] ?>" />
		<? } ?>
	</div>
	<p><strong>CHỈ DẪN</strong></p>
	<p>Các trường miêu tả text dài có thể đánh dấu chữ đậm nghiêng như sau:</p>
	<p>*đậm* --> <b>đậm</b>
	<br />_nghiêng_ --> <i>nghiêng</i>
	<br />* List item --> &middot; List item
	</p>

	<p>Thông tin giá nhập vào cần tuân theo dạng thức cố định, cách nhau bằng các dấu hai chấm. Mỗi thông tin viết trên một dòng.</p>
	<p><code>
	OPTION: Giải thích về option<br />
	+ Ville : Hotel : Chambre : www.abcd.com<br />
	+ Ville : Hotel : Chambre : www.abcd.com<br />
	- Prix / personne en chambre double : 1234<br />
	- Prix / personne en chambre individuelle : 2345<br />
	OPTION: Giải thích về option<br />
	+ Ville : Hotel : Chambre : www.xyzt.com<br />
	+ Ville : Hotel : Chambre : www.xyzt.com<br />
	- Prix / personne en chambre double : 5678<br />
	- Prix / personne en chambre individuelle : 9012<br />
	</code></p>
	<p>Chọn giá đại diện và đơn vị tính giá ở bên cạnh</p>

	<? if (isset($theDays)) { ?>
	<p><strong>ITINERARY</strong></p>
	<ol>
		<? foreach ($theDays as $day) { ?>
		<li><?= $day['name'] ?> (<?= $day['meals'] ?>)</li>
		<? } ?>
	</ol>
	<? } ?>
</div>
<? ActiveForm::end(); ?>
<?
$js = <<<TXT
$('#header-image').change(function(){
	var image = $(this).val();
	if (image == '') {
		$('#image-preview').html('<img class="img-responsive thumbnail" src="http://placehold.it/300x100" />');
	} else {
		$('#image-preview').html('<img class="img-responsive thumbnail" src="/upload/devis-banners/small/'+image+'" />');
	}
});
$('#product-day_from, #product-price_until').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2050-01-01',
	//startDate:moment(),
	format:'YYYY-MM-DD',
	showDropdowns:true,
	singleDatePicker:true
});
TXT;
$this->registerCssFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);