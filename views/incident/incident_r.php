<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_incident_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';

Yii::$app->params['page_title'] = $theIncident['name'];
?>
<style>
	.owner::after{content: ",";}
	.owner:last-child::after { content: ""; }
	.action::after{content: ",";}
	.action:last-child::after { content: ""; }
	.message {color: #666; font-weight: 400; margin: 0 5px 20px; text-align: justify; }
	.message-title .actions{display: none;}
	.message:hover .message-title .actions{display: inline-block; padding-left: 10px;}
	.message-title .actions span:hover{ cursor: pointer; }
	.active_edit:hover .message-title .actions{display: none;}
	.active_edit .message-body {background: #fff; border: 1px solid #cdcdcd; padding: 5px; border-radius: 2px; min-height: 100px}
	#action_message {margin-top: 10px;} 
</style>
<div class="col-md-7 <?= (empty($messages)) ? 'col-sm-push-5' : ''?>">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title text-semiold">Discussion<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
				<div class="heading-elements">
					<ul class="list-inline list-inline-separate heading-text text-muted">
						<li><span id="count_message"><?= count($messages)?></span> comments</li>
					</ul>
		    	</div>
			</div>

			<div class="panel-body">
				<ul class="media-list stack-media-on-mobile">
					<?php if (!empty($messages)): ?>
						<?php foreach ($messages as $message): ?>
						<li class="media">
							<div class="media-left">
								<a href="#"><img src="/img/cat.jpg" class="img-circle img-sm" alt=""></a>
							</div>

							<div class="media-body message"  data-id="<?= $message['id']?>">
								<div class="media-heading message-title">
									<a href="#" class="pull-left text-semibold"><?= $staffList[$message['cb']]?></a>
									<span class="pull-left media-annotation dotted date_created"><small><?= date('d-m-Y', strtotime($message['uo']));?></small></span>
									<div class="pull-left actions" data-id="<?= $message['id']?>">
										<?php if ($message['cb'] == USER_ID): ?>
										<span class="mes-edit" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
										<span class="mes-remove" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
										<?php endif ?>
									</div>
								</div>
								<div class="clearfix"></div>

								<div class="message-body">
									<?= $message['body']?>
								</div>
							</div>
						</li>
						<?php endforeach ?>
					<?php endif ?>
				</ul>
			</div>
			<div class="clearfix"></div>

			<hr class="no-margin">

			<div class="panel-body">
				<form id="messageForm" onsubmit="return checkField();" action="" method="post" accept-charset="utf-8">
				<h6 class="no-margin-top content-group">Add comment</h6>
				<div class="content-group">
					<div class="form-group">
					<?= Html::textarea('message', '', ['class' => 'form-control', 'id' => 'cke_add-comment', 'rows' => 8, 'required'=> 'required'])?>
					</div>
				</div>
				<div class="text-right">
					<div class="text-right btn-actions">
						<?= Html::submitButton('<i class="icon-plus22"></i> Add comment', ['class' => 'btn bg-blue', 'name' => 'save_message']) ?>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="col-md-5 <?= (empty($messages)) ? 'col-sm-pull-7' : ''?>">
    <div class="panel panel-default">
        <div>
            <div class="table-responsive">
            	<table  class="table table-condensed table-bordered">
            		<tbody>
            			<tr>
            				<th width="40%"><?= Yii::t('incident', 'Name of incident')?></th>
            				<td><?= $theIncident['name']?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Description')?></th>
            				<td><?= $theIncident['description']?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Tour code')?></th>
            				<td><?= $theIncident['tour']['op_code']?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Date of incident')?></th>
            				<td><?= $theIncident['incident_date']?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Type of incident')?></th>
            				<td><?= (isset($incidentTypeList[$theIncident['stype']]))? $incidentTypeList[$theIncident['stype']]: '--'?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Severity')?></th>
            				<td><?= (isset($severityList[$theIncident['severity']]))? $severityList[$theIncident['severity']]: '--'?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Location of incident (if applicable)')?></th>
            				<td><?= $theIncident['incident_location']?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'In charge')?></th>
            				<td><?= $staffList[$theIncident['owner_id']]?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Other staff participating')?></th>
            				<td><? foreach ($theIncident['owners'] as $owner) {
            					if (isset($staffList[$owner])) {
            						echo '<span class="owner">'.$staffList[$owner].'</span>';
            					}
            				}?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Actions taken')?></th>
            				<td><? foreach ($theIncident['actions'] as $action) {
            					if (isset($incidentActionList[$action])) {
            						echo '<span class="action">'.$incidentActionList[$action].'</span>';
            					}
            				}?></td>
            			</tr>
            			<tr>
            				<th><?= Yii::t('incident', 'Status')?></th>
            				<td><?= $incidentStatusList[$theIncident['status']]?></td>
            			</tr>
            			<?php if (!empty($theIncident['complaint'])): ?>
            			<tr>
            				<th><?= Yii::t('incident', 'Complaint')?></th>
            				<td><?= $theIncident['complaint'][0]['name'].' - '.$theIncident['complaint'][0]['status']?></td>
            			</tr>
            			<?php endif ?>
            		</tbody>
            	</table>
            </div>
        </div>
    </div>
</div>
<div class="wrap-action hidden">
	<div id="action_message">
		<span id="save_add" class="btn btn-success update_message"><?= Yii::t('incident', 'Save')?></span>
		<span class="btn btn-default cancel_update"><?= Yii::t('incident', 'Cancel')?></span>
	</div>
</div>
<div class="wrap-alert hidden">
	<div id="alert_error">
		<div class="alert alert-danger no-border">
			<span class="text-semibold"><?= Yii::t('incident', 'This field is requred')?></span>
	    </div>
	</div>
</div>
<script>
	var t_delete = "<?= Yii::t('incident', 'Confirm delete')?>";
</script>
<?php
$js = <<<TXT
var COPY_MES = '';
var CNT_MES = parseInt($('#count_message').text());
$(document).ready(function(){
	CKEDITOR.replace( 'cke_add-comment', {
	    //uiColor: '#9AB8F3'
	});//CKEDITOR.inline('#cke_add-comment');
});
$('.mes-remove').click(function(){
	if ($('#abc').length > 0) {
		$('.cancel_update').trigger('click');
	}
	if (!confirm(t_delete)) {
		return false;
	}
	if (CNT_MES > 0) {
		CNT_MES --;
		$('#count_message').text(CNT_MES);
	}
	var message_id = $(this).closest('.actions').data('id');
	$(this).closest('.media').fadeOut('slow', function(e){
		$(this).remove();
	});
	$.ajax({
  		url: "/incident/i_delete",
		method: "GET",
		data: { id : message_id },
		// dataType: "html"
	}).done(function( msg ) {
		console.log(msg);return;
	}).fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
});
$('.mes-edit').click(function(){
	if ($('#abc').length > 0) {
		$('.cancel_update').trigger('click');
	}
	var Message = $(this).closest('.message');
	var MES_body = $(Message).find('.message-body');
	COPY_MES = $(MES_body).clone();
	$(MES_body).attr('contenteditable','true').focus();
	$(MES_body).attr('id', 'abc');
	$(Message).addClass('active_edit');
	$('#action_message').insertAfter($(MES_body));
	CKEDITOR.inline('abc');
});
$('.update_message').click(function(){
	var Mess = $(this).closest('.message');
	var mes_id = $(Mess).data('id');
	$(Mess).removeClass('active_edit');
	var tmp_content = $(Mess).find('.message-body').html();
	$(COPY_MES).html('');
	$(COPY_MES).html(tmp_content);
	$(Mess).find('#abc').remove();
	$(Mess).append($(COPY_MES));
	$('.wrap-action').append($('#action_message'));
	$.ajax({
  		url: "/incident/i_update",
		method: "GET",
		data: { id : mes_id, body:  tmp_content},
		dataType: "json"
	}).done(function( msg ) {
		if (msg['err'] != undefined) {
			alert(msg['err']); return;
		}
		var dt = new Date(msg['uo']);
		$(Mess).find('.date_created small').text(dt.ddmmyyyy());
	}).fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
});
$('.cancel_update').click(function(){
	var Mess = $(this).closest('.message');
	$(Mess).removeClass('active_edit');
	$(Mess).find('#abc').remove();
	$(Mess).append($(COPY_MES));
	$('.wrap-action').append($('#action_message'));
});
document.getElementById("messageForm").onsubmit = function() {
	    return checkField();
	};
function checkField()
{
	if (jQuery("#cke_1_contents iframe").contents().find("body").text() == '') {
		jQuery("#cke_cke_add-comment").css({
			borderColor: 'red',
		});
		jQuery("#cke_1_contents iframe").contents().find("body").focus();
		jQuery("#cke_cke_add-comment").closest('.form-group').append($('#alert_error'));
		return false;
	}
	return true;
}
Date.prototype.ddmmyyyy = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return dd+"-"+mm+"-"+yyyy;
};

TXT;
$this->registerJsFile ( 'https://cdn.ckeditor.com/4.7.1/basic/ckeditor.js', [
  'depends' => 'yii\web\JqueryAsset'
] );
$this->registerJs($js);
?>