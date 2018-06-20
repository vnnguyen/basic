<?

$q = $db->query('SELECT * FROM at_ngaymau WHERE id=%i LIMIT 1', seg3);
$nm = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404);

if (
    ($nm['owner'] == 'si' && !in_array(myID, [1, 3, 26052, 29013])) // Jonathan, Alain
    || ($nm['owner'] == 'at' && !in_array(myID, [1, 3, 17401])) // Hieu, Nguyen
) {
    show_error(403, '(As of 160831) Access denied!');
}

$tm = new hxTagManager();
$tags = $tm->getItemTags($hxGroups['tag-ct']['id'], 'nm', $nm['id']);


if (!isset($_POST['ngaymau_title'])) $_POST['ngaymau_title'] = '';
if (!isset($_POST['ngaymau_body'])) $_POST['ngaymau_body'] = '';
if (!isset($_POST['ngaymau_tags'])) $_POST['ngaymau_tags'] = '';
if (!isset($_POST['ngaymau_meals'])) $_POST['ngaymau_meals'] = '';
if (!isset($_POST['ngaymau_image'])) $_POST['ngaymau_image'] = '';
if (!isset($_POST['ngaymau_guides'])) $_POST['ngaymau_guides'] = 'Guide et chauffeur';
if (!isset($_POST['ngaymau_transport'])) $_POST['ngaymau_transport'] = '';
if (!isset($_POST['ngaymau_hotels'])) $_POST['ngaymau_hotels'] = '';

$fv = new hxFormValidation();
$fv->setRules('ngaymau_title', 'Tiêu đề', 'trim|required|max_length[128]|htmlspecialchars');
$fv->setRules('ngaymau_body', 'Nội dung chính', 'trim|required|htmlspecialchars');
$fv->setRules('ngaymau_tags', 'Tags', 'trim|required|htmlspecialchars');
$fv->setRules('ngaymau_meals', 'Các bữa ăn', 'trim|required|max_length[3]|htmlspecialchars');
$fv->setRules('ngaymau_image', 'Hình ảnh đại diện', 'trim|max_length[128]|htmlspecialchars');
$fv->setRules('ngaymau_guides', 'Hướng dẫn viên', 'trim|max_length[128]|htmlspecialchars');
$fv->setRules('ngaymau_transport', 'Phương tiện vận chuyển', 'trim|max_length[128]|htmlspecialchars');
$fv->setRules('ngaymau_hotels', 'Khách sạn', 'trim|max_length[128]|htmlspecialchars');
$fv->setRules('language', 'Ngôn ngữ', 'trim|required');

if (fRequest::isPost()) {
  if ($fv->run()):
    // Update tags
		$q = $db->query('UPDATE at_ngaymau SET updated_dt=%s, updated_by=%i, language=%s, ngaymau_title=%s, ngaymau_body=%s, ngaymau_tags=%s, ngaymau_image=%s, ngaymau_meals=%s, ngaymau_transport=%s, ngaymau_hotels=%s, ngaymau_guides=%s, ngaymau_services=%s WHERE id=%i LIMIT 1', 
			NOW,
			myID,
			$_POST['language'],
			$_POST['ngaymau_title'],
			$_POST['ngaymau_body'],
			$_POST['ngaymau_tags'],
			$_POST['ngaymau_image'],
			$_POST['ngaymau_meals'],
			$_POST['ngaymau_transport'],
			$_POST['ngaymau_hotels'],
			$_POST['ngaymau_guides'],
			$_POST['ngaymau_services'],
			$nm['id']
		);
    $tm->updateItemTags($hxGroups['tag-ct']['id'], 'nm', $nm['id'], $_POST['ngaymau_tags'], true);
    if ($nm['owner'] == 'si') {
	    redirect('sample-tour-days/b2b');
    } else {
	    redirect('sample-tour-days');
    }
    exit;
    endif;
} else {
    foreach ($nm as $k=>$v) $_POST[$k] = $v;
    $_POST['ngaymau_tags'] = implode(', ', get_from_array('name', $tags));
    if ($_POST['ngaymau_tags'] == '') $_POST['ngaymau_tags'] = $nm['ngaymau_tags'];
    $_POST['language'] = 'fr';
}

// Cac file anh
$files = scandir(UPLOAD_PATH.'devis-days/', 1);

$pageT = 'Edit sample tour day';
$pageM = 'ct';
$pageB = array(
	anchor('nm', 'Ngày tour mẫu'),
	anchor('nm/r/'.$nm['id'], $nm['ngaymau_title']),
);

include_once('_hd_limitless.php');?>
<div class="col-md-8">
	<?=$fv->getErrorMessage('<div class="alert alert-error">', '</div>')?>
	<form method="post" action="">
		<h3>This is what the customer will see</h3>
		<div class="row">
    		<div class="col-md-9">
    			<label>Title</label>
    			<input type="text" class="title form-control" name="ngaymau_title" value="<?=$_POST['ngaymau_title']?>" />
    		</div>
    		<div class="col-md-3">
    			<label>Language</label>
    			<select class="form-control" name="language">
    				<? foreach ($ctLanguages as $k=>$v) { ?>
    				<option value="<?=$k?>" <?=$_POST['language'] == $k ? 'selected="selected"' : ''?>><?=$v?></option>
    				<? } ?>
    			</select>
    		</div>
		</div>
		<p>Main content:<br /><textarea class="form-control" rows="10" id="day-body" name="ngaymau_body"><?=$_POST['ngaymau_body']?></textarea></p>
		<p>Tags:<br /><input type="text" class="form-control" name="ngaymau_tags" value="<?=$_POST['ngaymau_tags']?>" /></p>
		<p>Image:<br /><select class="form-control" name="ngaymau_image">
			<option value="">- None -</option>
			<? asort($files);
			foreach ($files as $k=>$v):
				echo '<option value="'.$v.'">', $v, '</option>';
			endforeach; ?>
		</select></p>
		<div class="row">
			<div class="col-md-3">Meals (B/L/D):<br />
				<select class="form-control" name="ngaymau_meals">
					<option>---</option>
					<option>B--</option>
					<option>-L-</option>
					<option>--D</option>
					<option>BL-</option>
					<option>B-D</option>
					<option>-LD</option>
					<option>BLD</option>
				</select>
			</div>
			<div class="col-md-3">Guide/Driver?<br /><input type="text" class="form-control" name="ngaymau_guides" value="<?=$_POST['ngaymau_guides']?>" /></div>
			<div class="col-md-3">Flights?<br /><input type="text" class="form-control" name="ngaymau_transport" value="<?=$_POST['ngaymau_transport']?>" /></div>
			<div class="col-md-3">Hotels<br /><input type="text" class="form-control" name="ngaymau_hotels" value="<?=$_POST['ngaymau_hotels']?>" /></div>
		</div>
		<!-- THEM CAC DICH VU - BAT DAU -->
		<!--
		<div class="clearfix mb-5 span-10">Dịch vụ</div>
		<div class="clearfix mb-5 span-4">Ghi chú</div>
		<div class="clearfix mb-5 span-4 last"></div>
		<div class="clear"></div>
		<div id="dv-sortable" class="clear clearfix">
			<div class="dv-line drag-me" style="display:none;"><span class="handle b-ffc c-060" style="cursor:pointer">[+]</span> &nbsp; <input type="text" style="width:360px;" name="" value="" /> &nbsp; <input type="text" style="width:180px;" name="" value="" /> &nbsp; <?=anchor('#', 'Xoá', 'class="dv-del"')?></div>
		</div>
		<div class="clear clearfix mb-10"><?=anchor('#', 'Thêm dịch vụ', 'id="dv-add"')?></div>
		-->
		<!-- THEM CAC DICH VU - KET THUC -->
		<p><button type="submit" class="btn btn-primary">Ghi các thay đổi</button> hoặc <?=anchor('##', 'Thôi, quay lại')?></p>
	</form>
</div>
<div class="col-md-4">
    <h2>Preview image</h2>
    <p id="image-preview">
    <? if ($_POST['ngaymau_image'] != ''): ?>
    <img src="<?= UPLOAD_URL ?>devis-days/<?=$_POST['ngaymau_image']?>" />
    <? endif; ?>
    </p>
</div>
<script src="https://cdn.ckeditor.com/4.5.11/basic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/4.5.11/basic/adapters/jquery.js"></script>
<script>
$(function(){
    $('[name=ngaymau_image]').val('<?=$_POST['ngaymau_image']?>');
    $('[name=ngaymau_meals]').val('<?=$_POST['ngaymau_meals']?>');
    $('[name=ngaymau_image]').change(function(){
        var image = $(this).val();
        $('#image-preview').html('<img src="<?=SITE_HOME?>upload/devis-days/'+image+'" />');
    });

    $('#dv-add').click(function(){
        $('.dv-line:first').clone(true).show(0).insertAfter('.dv-line:last');
    });
    $('.dv-del').click(function(){
        $(this).parent().parent().remove();
    });
    $('#day-body').ckeditor({
        allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
        basicEntities: false,
        entities: false,
        entities_greek: false,
        entities_latin: false,
        uiColor: '#ffffff',
        height:400,
        contentCss:'/assets/css/ckeditor_160828.css'
    });
});
</script>

<? include_once('_ft_limitless.php');