
<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$id= Yii::$app->request->get('id', 0);
$data_days = json_encode($days_in_tour);
$data_dt = json_encode($compair_dt);
$data_op = json_encode($operators);
$COLORS = ["#0d38b7", "#0a7d99", "#09742e", "#0c67aa", "#0650f0", "#03a086"];

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
						<span class="hidden" id="data_days"><?= $data_days?></span>
						<span class="hidden" id="data_dt"><?= $data_dt?></span>
						<span class="hidden" id="data_ops"><?= $data_op?></span>
						<?php if ($id > 0){ ?>
						<span class="hidden" id="data_form_content"><?= json_encode($form_content)?></span>
						<?php } ?>
						<?
						if ($id > 0) {

							foreach ($form_content as $user_id => $dts) {

						?>
						<div class="wrap-items">
							<div class="row wrap-item">
								<div class="col-md-5">
									<div class="form-group">
										<select name="operator[]" class="form-control"  placeholder="select">
											<option value="">Select</option>
											<?
											$name_selected = '';
											foreach ($operators as $nid => $name) { 
												if ($user_id==$nid) {
													$name_selected = $name;
												}
											?>
											<option value="<?= $nid?>" <?= ($user_id==$nid)? 'selected="selected"': ''?>><?= $name?></option>
											<? } ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<input type="text" name="dt_number[]" value="<?=$dts?>" data-name="<?= $name_selected?>" class="form-control" placeholder="days" data-user_id="<?= $user_id?>">
									</div>
								</div>
							<span class="btn btn-success add_btn">+</span>
							<span class="btn btn-default remove_btn">-</span>
							</div>
						</div>
						<?	}
						} else {
						?>
						<div class="wrap-items">
							<div class="row wrap-item">
								<div class="col-md-5">
									<div class="form-group">
										<select name="operator[]" class="form-control"  placeholder="select">
											<option value="">Select</option>
											<? foreach ($operators as $user_id => $name) {?>
											<option value="<?= $user_id?>"><?= $name?></option>
											<? } ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<input type="text" name="dt_number[]" value="" class="form-control" placeholder="days" data-user_id="">
									</div>
								</div>
							<span class="btn btn-success add_btn">+</span>
							<span class="btn btn-default remove_btn">-</span>
							</div>
						</div>
						<?
						}
						?>
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
				<table class="table-tight table table-bordered table-xxs"  id="tbl_review">
					<thead>
						<tr>
							<th width="10" class="th_number">TT</th>
							<?
							if ($id > 0) {
								foreach ($form_content as $user_id => $dts) {
							?>
							<th width="150"><?= $operators[$user_id]?></th>
							<?
								}
							}
							?>
							<th class="th_title">Ngày</th>
						</tr>
					</thead>
					<tbody>
						<? 
						$cnt = 1;
						foreach ($days_in_tour as $dt => $title) {?>
						<tr data-cnt="<?=$cnt?>" data-dt="<?=$dt?>">
							<td class="text-muted text-center number_day"><?=$cnt?></td>
							<?
							if ($id > 0) {
								$color = 0;
								foreach ($form_content as $user_id => $dts) {
								if ($color == count($COLORS) - 1) { $color = 0;
									# code...
								}
							?>
							<td style="background-color:<?= (array_search($cnt, explode(',', $dts)) !== false)?$COLORS[$color]:''?>"></td>
							<? $color++;
								}
							}
							?>
							<td class="td_title"><?=$title?></td>
						</tr>
						<? 
							$cnt++;
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?
$js = <<<TXT
var zData = {};
var arrDataName = {};
var data_days = JSON.parse($('#data_days').html());
var data_dts = JSON.parse($('#data_days').html());

var data_form = ($('#data_form_content').html() != undefined) ? JSON.parse($('#data_form_content').html()): '';
if(data_form != '') {console.log(data_form);return false;
	$('#zoneForm').find('input[name="data"]').val(JSON.stringify(data_form));
}
var COLORS = [];
while (COLORS.length < 100) {
    do {
        var color = Math.floor((Math.random()*1000000)+1);
    } while (COLORS.indexOf(color) >= 0);
    COLORS.push("#" + ("000000" + color.toString(16)).slice(-6));
}
$(document).on('change', '#zoneForm select[name="operator[]"]', function(){
	var user_id =$(this).val();
	var op_name = $(this).find("option:selected").text();
	var parent = $(this).closest('.wrap-item');
	$(parent).find('input[name="dt_number[]"]').data('user_id', user_id);
	$(parent).find('input[name="dt_number[]"]').data('name', op_name).focus();
	$(parent).find('.remove_btn').data('user_id', user_id);
	arrDataName[user_id] = op_name;
});
$(document).on('blur', '#zoneForm input[type="text"]', function(){
	var user_id =$(this).data('user_id');
	var op_name = $(this).data("name");
	arrDataName[user_id] = op_name;
	update_arr();

	print_rows();

	$('#zoneForm').find('input[name="data"]').val(JSON.stringify(zData));
});


$(document).on('click', '.add_btn', function(){
	var clicked = $(this);
	var parent_clicked = $(clicked).closest('.wrap-item');
	var copy = parent_clicked.clone();
	$(copy).find('span.select2').remove();
	$(copy).insertAfter(parent_clicked);
	$(copy).find('select.select_zone').select2({
		placeholder: "select zone"

	});
	$(copy).find('select.select_date').select2({
		placeholder: "select zone"

	});
	$(copy).find('.select2, .select2-search__field').css('width', '100%');
});


$(document).on('click', '.remove_btn', function(){
	var clicked = $(this);
	var parent_clicked = $(clicked).closest('.wrap-item');
	var user_id = $(this).data('user_id');
	if(user_id > 0){
		delete arrDataName[user_id];
	}
	if ($(clicked).closest('.wrapper').find('.wrap-item').length > 1) {
		$(parent_clicked).remove();
		update_arr();
		print_rows();
	}

});
function update_arr() {
	zData = {};
	jQuery.each($('.wrap-item'), function(index, item){
		var user_id = $(item).find('input[name="dt_number[]"]').data('user_id');
		var user_name = $(item).find('input[name="dt_number[]"]').data('name');
		var str_dates = $(item).find('input[name="dt_number[]"]').val();
		var dates = str_dates.split(',');
		arrDataName[user_id] = user_name;
		if(! parseInt(user_id) > 0 || str_dates == '') {
			alert("item false"); return false;
		}
		if(!Array.isArray(zData[user_id])) {
			zData[user_id] = [];
		}
		$.each(dates, function(ind, number){
			if (number.indexOf('-') == -1) {
				if (zData[user_id].indexOf(parseInt(number)) == -1) {
					zData[user_id].push(parseInt(number));
				}
			}
			if (number.indexOf('-') != -1) {
				var dt_range = number.split('-');
				for(var i = parseInt(dt_range[0]); i <= parseInt(dt_range[1]); i++){
					if (zData[user_id].indexOf(parseInt(i)) == -1) {
						zData[user_id].push(parseInt(i));
					}
				}
			}
		});

	});
	$('#zoneForm').find('input[name="data"]').val(JSON.stringify(zData));
}
function print_rows(){
	$('#tbl_review thead').empty();
	$('#tbl_review tbody').empty();

	//add head
	var html_tr_thead = '<tr> <th class="th_number" width="10">TT</th>';
	$.each(arrDataName, function(user_id, name){
		html_tr_thead += '<th width="150">'+ name+'</th>';
	});
	html_tr_thead += '<th class="th_title">Ngày</th></tr>';
	$('#tbl_review thead').append($(html_tr_thead));


	//add body
	var cnt = 1;
	$.each(data_days, function(dt, title){
		var html_tr_body = '<tr data-cnt="'+ cnt +'" data-dt="'+ dt +'"> <td class="text-muted text-center number_day">'+ cnt +'</td>';
		var color = 0;
		$.each(arrDataName, function(user_id, name){
			var style = '';
			if(zData[user_id].indexOf(cnt) != -1){
				style = 'background-color:'+ COLORS[color];
			}
			html_tr_body +='<td style="' + style + '"></td>';
			color++;
		});
		html_tr_body += '<td class="td_title">'+ title +'</td> </tr>';
		$('#tbl_review tbody').append(html_tr_body);
		cnt++;

	});
	return false;
}

TXT;
$this->registerJs($js);
?>