<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$arr_lang = ['vi' => 'VietNam', 'en' => 'English', 'fr' => 'French'];
?>
<style>
	.wrap-links { list-style-type: none; }
</style>
<div class="col-md-6">
	<div class="panel">
		<div class="panel-heading">
			<form class="form-inline">
				<?= Html::label('Language');?>
                <?= Html::dropdownList('lang', $lang, $arr_lang, ['class'=>'form-control']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
            </form>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table-tight table table-bordered table-xxs" id="tbl_review">
					<thead>
						<tr>
							<th width="10">TT</th>
							<th>Ngày</th>
						</tr>
					</thead>
					<tbody>
						<?
						$cnt = 0;
						foreach ($days_of_tour as $day_id => $dt) {
							$cnt++;
							foreach ($theTour['days'] as $theDay) {
								if ($theDay['id'] == $day_id) {
									$translate_title = $theDay['name'];
									$translate_body = $theDay['body'];
									if ($days_translates) {//var_dump($days_translates);die;
										foreach ($days_translates as $trans_day) {
											if ($trans_day['day_id'] == $day_id) {
												$translate_title = $trans_day['title'];
												$translate_body = $trans_day['content'];
											}
										}
									}
						?>
						<tr class="tr-day" data-id="<?= $day_id?>" id="ngay_1008468">
							<td class="text-center" width="20">
								<span class="text-muted"><?= $cnt?></span>
							</td>
							<td class="no-padding-left">
								<div class="day-actions text-nowrap text-right pull-right position-right">
									<div class="bottom-link text-right">
										<ul class="wrap-links">
											<li><a class="btn btn-default translate-item" data-popup="tooltip" title="<?= ($translate_title != $theDay['name']) ? 'Edit translate': 'Translate'?>" data-title_t="<?= $translate_title?>" data-body_t="<?= $translate_body?>" data-day_id="<?= $day_id?>" data-lang="<?= $lang?>">T</a></li>
										</ul>
									</div>
								</div>
								<span class="day-date"><?=$dt?></span>
								<a class="day-name"><?= $translate_title?></a>
								<em class="day-meals text-nowrap"><?= $theDay['meals']?></em>
								<div class="day-content mt-20" style="display:none;">
									<div class="day-body"><?= $translate_body?></div
								</div>
							</td>
						</tr>
						<?
								}
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="translate-modal" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Translate</h4>
			</div>
			<form id="t_form">
				<div class="modal-body">
					<div class="translate-form">
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" class="form-control" name="title" value="" placeholder="">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<textarea  class="form-control" name="content" rows="10"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary translate-save" name="submitbtn" data-dismiss="modal">Save</button>
					<button class="btn btn-default pull-right translate-cancel" name="close" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?
$js = <<<TXT
var LANG = '';
var DAY_ID = 0;
var TITLE = '';
var CONTENT = '';
var Parent_tr = null;
$(document).on('click', '.day-name', function(){
	var clicked = $(this);
	var parent_td = $(clicked).closest('td');
	$(parent_td).find('.day-content').toggle();
	return false;
});


$(document).on('click', '.translate-item', function(){
	DAY_ID = $(this).data('day_id');
	LANG = $(this).data('lang');
	TITLE = $(this).data('title_t');
	CONTENT = $(this).data('body_t');

	Parent_tr = $(this).closest('tr');

	$('#translate-modal').find('[name="title"]').val(TITLE);
	$('#translate-modal').find('[name="content"]').val(CONTENT);
	$('#translate-modal').modal('show');
});

$(document).on('blur', '[name="title"], [name="content"]', function(){
	TITLE =  $('#t_form').find('[name="title"]').val();
	CONTENT = $('#t_form').find('[name="content"]').val();
});
$(document).on('click', '[name="submitbtn"]', function(){
	$(Parent_tr).find('.day-name').text(TITLE);
	$(Parent_tr).find('.day-body').text(CONTENT);
	$(Parent_tr).find('.translate-item').data('title_t', TITLE);
	$(Parent_tr).find('.translate-item').data('body_t', CONTENT);
	$(Parent_tr).find('.translate-item').attr('title', 'Edit translate');
	$.ajax({
		method: 'POST',
		url: '/demo/translate',
		data: {day_id: DAY_ID, lang: LANG, title: TITLE, content: CONTENT},
		dataType: 'json'
	}).done(function(response){
		if(response.err != undefined) {
			console.log(response.err);
		}
	});
});
TXT;
$this->registerJs($js);
?>