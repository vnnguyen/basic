<?
$id = Yii::$app->request->get('id', 0);
$zone_to_visit = ["Bắc VN", "Trung VN", "Nam VN", "Lào", "Cambodia", "Myanmar", "Thái Lan"];
?>
<style>
	.add_btn:hover {background: #cdcdcd}
	.select2 {width: 100%;}
	td.paint{ background-color: green; }
</style>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<form method="POST" id="zoneForm">
					<div class="wrapper">
						<input type="hidden" name="id" value="<?= $id?>">
						<div class="wrap-item">
							<div class="col-md-2">
								<label>Bắc VN</label>
								<div class="form-group">
									<input type="text" name="zbac_vn" value="<?= isset($form_content['zbac_vn'])? $form_content['zbac_vn']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-md-2">
								<label>Trung VN</label>
								<div class="form-group">
									<input type="text" name="ztrung_vn" value="<?= isset($form_content['ztrung_vn'])? $form_content['ztrung_vn']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-md-2">
								<label>Nam VN</label>
								<div class="form-group">
									<input type="text" name="znam_vn" value="<?= isset($form_content['znam_vn'])? $form_content['znam_vn']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-md-2">
								<label>Lào</label>
								<div class="form-group">
									<input type="text" name="zlao" value="<?= isset($form_content['zlao'])? $form_content['zlao']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-md-2">
								<label>Cambodia</label>
								<div class="form-group">
									<input type="text" name="zcambodia" value="<?= isset($form_content['zcambodia'])? $form_content['zcambodia']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-md-2">
								<label>Myanmar</label>
								<div class="form-group">
									<input type="text" name="zmyanmar" value="<?= isset($form_content['zmyanmar'])? $form_content['zmyanmar']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<div class="col-md-2">
								<label>Thái lan</label>
								<div class="form-group">
									<input type="text" name="zthailan" value="<?= isset($form_content['zthailan'])? $form_content['zthailan']: ''?>" placeholder="" class="form-control">
								</div>
							</div>
							<!-- <span class="btn btn-default add_btn">+</span>
							<span class="btn btn-default remove_btn">-</span> -->
						</div>
					</div>
					<div id="data">
						<input type="hidden" name="data" value="">
					</div>
					<div class="clearfix"></div>
					<div class="actions pull-right">
						<button type="submit" class="btn btn-primary" name="submit">Save</button>
						<button type="submit" class="btn btn-default">Cancel</button>
					</div>
				</form>
			</div>
			<div class="table-responsive">
				<table class="table-tight table table-bordered table-xxs" id="tbl_review">
					<thead>
						<tr>
							<th width="10">TT</th>
							<? foreach ($zone_to_visit as $zone_name) {?>
								<th width="10"><?= $zone_name?></th>
							<? }?>
							<th>Ngày</th>
						</tr>
					</thead>
					<tbody>
						<tr class="tr-day" data-id="1008468" id="ngay_1008468">
							<td class="text-center" width="20">
								<span>1</span>
							</td>
							<td class="no-padding-left">
								<div class="day-actions text-nowrap text-right pull-right position-right">
								</div>
								<span class="day-date">1/1/2018 Mon</span>
								<a class="day-name" href="/days/r/1008468">Hanoi - vol vers Phu Quoc</a>
								<em class="day-meals text-nowrap">---</em>
								<div class="day-content mt-20" style="display:none;">
									<p>
										<span class="day-guides"><i class="fa fa-user"></i> Chauffeur uniquement</span>
										<span class="day-transport"></span>
									</p>
									<div class="day-body" id="day-body-1008468">
										<p>Transfert de votre hôtel (dans le centre-ville d'Hanoi) à l'aéroport de Noi Bai pour le vol vers Phu Quoc. Accueil en arrivant par chauffeur et transfert à l’hôtel.</p><p>Le reste de la journée est libre pour votre détente.</p><p>Nuit à l’hôtel à Phu Quoc. &nbsp;</p><ul><li><em>Vol Hanoi - Phu Quoc: 9h30 - 11h40</em></li><li><em>Transfert à l'aéroport d'Hanoi en voiture privée</em></li><li><em>Trasnfert de&nbsp;l'aéroport de Phu Quoc à l'hôtel en bus collectif arrangé par l'hôtel</em></li></ul>
									</div>
									<div class="day-summary" style="padding-left:20px; border-left:4px solid #fcc;" id="day-summary-1008468"> </div>
									<img id="day-image-1008468" src="data:image/gif;base64,R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=">
									<div style="display:none">
										<div class="day-image"></div>
										<div class="day-note"></div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?
$js = <<<TXT
var zData = {};
$('#zoneForm input[type="text"]').on('blur', function(){
	var name = $(this).attr('name');
	var str_dates = $(this).val();
	
	var dates = str_dates.split(',');
	if (str_dates == '') {
		$.each($('#tbl_review tbody tr'), function(index, tr){
			$(tr).find('td').each(function(id_td, td){
				if ($(this).data('name') == name) {
					$(this).removeClass('paint');
				}
			});
		});
	} else {








		if(!Array.isArray(zData[name])) {
			zData[name] = [];
		}

		$.each(dates, function(i, number){
			if (number.indexOf('-') == -1) {
				$.each($('#tbl_review tbody tr'), function(index, tr){
					if ($(tr).data('cnt') == number) {
						if (zData[name].indexOf($(tr).data('dt')) == -1) {
							zData[name].push($(tr).data('dt'));
						}
						$(tr).find('td').each(function(id_td, td){
							if ($(this).data('name') == name) {
								$(this).addClass('paint');
							}
						});
					}
				});
			}
			if (number.indexOf('-') != -1) {
				var dt_range = number.split('-');
				$.each($('#tbl_review tbody tr'), function(index, tr){
					if ($(tr).data('cnt') >= dt_range[0] && $(tr).data('cnt') <= dt_range[1]) {
						if (zData[name].indexOf($(tr).data('dt')) == -1) {
							zData[name].push($(tr).data('dt'));
						}
						$(tr).find('td').each(function(id_td, td){
							if ($(this).data('name') == name) {
								$(this).addClass('paint');
							}
						});
					}
				});
			}
		});
	}
	$('#zoneForm').find('input[name="data"]').val(JSON.stringify(zData));
});





























TXT;
$this->registerJs($js);
?>