<?
use yii\helpers\Html;
$ownerAt = '';
?>
				<div style="height:44px;" class="write-toggle" onclick="$('.write-toggle').toggle(); $('#redactor').redactor('core.getObject').focus.setEnd();">Click here or press <kbd>p</kbd> to post</div>
				<div style="display:none;" class="write-toggle">
					<form method="post" action="">
						<div id="files-list"></div>
						<p id="files-container">
							<a href="javascript:;" onclick="$('.write-toggle').toggle(); return false;" class="text-danger pull-right">Cancel post</a>
							<a id="files-browse" href="javascript:;">Upload files</a>
							<span id="files-console" class="text-danger"></span>
						</p>
						<p><textarea id="xredactor" class="ckeditor form-control" name="body" rows="8" style="min-height:200px;"></textarea></p>
						<p><input type="text" id="to" class="form-control" name="to" value="<?= $ownerAt ?>" autocomplete="off" placeholder="Type @ to select recipients"></p>
						<div class="row">
							<div class="col-md-10"><input type="text" id="title" class="form-control" name="title" value="" autocomplete="off" placeholder="#urgent #important Title (optional)"></div>
							<div class="col-md-2"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary btn-block']) ?></div>
						</div>
					</form>
				</div>