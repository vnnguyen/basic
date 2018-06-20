<?
use yii\helpers\Html;
?>
<div id="tinymceDiv">
	<div id="alert-wrapper"></div>
	<form id="form-tinymce" method="post" action="">
		
		<input type="hidden" name="stype" value="default"/>
		<input type="hidden" name="via" value="web"/>
		<input type="hidden" name="from_id" value="1"/>
		<input type="hidden" name="rtype" value="tour"/>
		<input type="hidden" name="rid" value="10689"/>
		<input type="hidden" name="rname" value="  F1404052 - Charlotte Ernoult - 5 pax - Hoa Nhung - Đức Anh"/>
		<input type="hidden" name="rurl" value="https://my.amicatravel.com/tours/r/10689"/>
 
		<div class="row mb-1em">
			<div id="container" class="col-md-9">
				<div>
					<a id="pickfiles" href="#" class="fw-b">Click to attach files</a>
					<span id="engine">Sorry, you can't upload files at the moment!</span>
				</div>
				<div id="div-filelist"></div>
			</div>
			<div class="col-md-3 text-right"><?= Html::a('Cancel', '#', ['class'=>'text-danger']) ?></div>
			<div class="col-md-12">
				<table id="table-filelist" style="width:90%">
					<tr><td width="50%"></td><td></td></tr>
				</table>
			</div>
		</div>
<?
$js = <<<TXT
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash',
		browse_button : 'pickfiles',
		container : 'container',
		max_size : '10mb',
		url : '/assets/js/plupload/__upload.php',
		flash_swf_url : '/assets/js/plupload/js/plupload.flash.swf',
		unique_names : true,
		filters : [{title : "Image / Doc / Zip files", extensions : "jpg,jpeg,gif,tif,tiff,png,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rar,zip,gz,tar,7z,bgz,swf|mp3|mpeg"}]
	});
	uploader.bind('Init', function(up, params) {
		$('#engine').html('<!--' + params.runtime + '-->');
	});

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#div-filelist').append(
				'<div id="' + file.id + '">File: ' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>'
			);
		});
	});
	
	uploader.bind('QueueChanged', function(up) {
		uploader.start();	
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html('Uploading...');
	});

	uploader.bind('FileUploaded', function(up, file, res) {
		$.post('/files/ajax', {name:file.name, filegroup:1, action:'create', size:file.size, id:file.id}, function(data){
			if (data.status == 'OK') {
				$('#' + file.id).remove();
				var html = '<tr id="file-' + data.id + '"><td><input type="hidden" name="file_id[]" value="' + data.id + '" />+ <a href="/files/r/'+data.id+'">' + file.name + '</a></td><td class="ta-r">' + data.sizeh + ' (<a rel="' + data.id + '" class="file-del" href="#delete">Delete</a>)</td></tr>';
				$('#table-filelist tr:first').after(html);
			} else {
				$('#' + file.id + " b").html(data.status);
			}
		}, 'json');
	});

	uploader.init();
	
	// Delete uploaded files
	$('.file-del').live('click', function(){
		if (confirm('Delete this file?')) {
			var rel = $(this).attr('rel');
			$.post('/files/ajax', {filegroup:1, action:'delete', id:rel}, function(data){
				if (data.status && data.message) {
					if (data.status == 'OK') {
						$('tr#file-' + rel).remove();
					} else {
						alert(data.message);
					}
				} else {
					alert('Error: data error.');
				}
			}, 'json');
		}
		return false;
	});
TXT;
$this->registerJsFile('/assets/js/plupload/js/plupload.full.js', ['app\config\MetronicAsset']);
$this->registerJs($js);
?>
		<div class="mb-1em"><textarea id="textarea-body" class="form-control redactor" name="body" rows="10"></textarea></div>
		<div class="row mb-1em">
			<div class="col-md-9">
				<input type="text" class="form-control" id="input-title" name="title" value="" placeholder="Add a title (optional)" />
			</div>
			<div class="col-md-3">
				<select class="form-control" name="priority">
					<option value="A1">Normal priority</option>
					<option value="B2">Important</option>
					<option value="C3">Urgent</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<select class="select2 form-control" data-placeholder="Gửi email thông báo cho những người này..." name="m_to[]" xmultiple>
					<option value="1904">Alexandre (alexandre.d@amicatravel.com)</option>
					<option value="8162">Anh (duc.anh@amicatravel.com)</option>
					<option value="695">Anh (phuong.anh@amicatravel.com)</option>
					<option value="12952">Anh (ngoc.anh@amicatravel.com)</option>
					<option value="3404">Bunthol (prim.bunthol@amicatravel.com)</option>
					<option value="8">Chinh (tuyet.chinh@amicatravel.com)</option>
					<option value="9146">Douangpaseuth (feuang.pakse@amicatravel.com)</option>
					<option value="9881">Dương (tran.duong@amicatravel.com)</option>
					<option value="7036">Emeline (emeline.r@amicatravel.com)</option>
					<option value="1353">Giang (ductruonggiang@gmail.com)</option>
					<option value="1351">Hà (pham.ha@amicatravel.com)</option>
					<option value="4829">Hà (nguyen.ha@amicatravel.com)</option>
					<option value="15860">Hà (ta.ha@amicatravel.com)</option>
					<option value="1087">Hà (doan.ha@amicatravel.com)</option>
					<option value="2382">Hà (duong.ha@amicatravel.com)</option>
					<option value="1502">Hải (dinh.hai@amicatravel.com)</option>
					<option value="15861">Hằng (dinh.hang@amicatravel.com)</option>
					<option value="4432">Hằng (ngo.hang@amicatravel.com)</option>
					<option value="17">Hạnh (duc.hanh@amicatravel.com)</option>
					<option value="11">Hiền (thu.hien@amicatravel.com)</option>
					<option value="1350">Hiệu (casaugamthet@facebook.com)</option>
					<option value="3">Hiếu (hieu@amicatravel.com)</option>
					<option value="16079">Hiếu (nguyen.hieu@amicatravel.com)</option>
					<option value="13">Hoa (bearez.hoa@amicatravel.com)</option>
					<option value="4">Hường (thu.huong@amicatravel.com)</option>
					<option value="1352">Huy (lucdanghuy@gmail.com)</option>
					<option value="18519">Huyền (dinh.huyen@amicatravel.com)</option>
					<option value="1850">Khôi (khoinv2287@gmail.com)</option>
					<option value="3868">Khởi (chery2080@yahoo.com)</option>
					<option value="15081">Khuê (nguyen.khue@amicatravel.com)</option>
					<option value="16">Lan (truong.lan@amicatravel.com)</option>
					<option value="11723">Liên (phung.lien@amicatravel.com)</option>
					<option value="6">Linh (ngoc.linh@amicatravel.com)</option>
					<option value="7756">Linh (linhnd@amicatravel.com)</option>
					<option value="14671">Linh (nguyen.linh@amicatravel.com)</option>
					<option value="7766">Loan (nguyen.loan@amicatravel.com)</option>
					<option value="5046">Lý (nguyen.ly@amicatravel.com)</option>
					<option value="751">Mẫn (man37p2@yahoo.fr)</option>
					<option value="2">Mạnh (haducmanh@gmail.com)</option>
					<option value="17089">Marine (marine.dagorne@amicatravel.com)</option>
					<option value="9120">Medsanh (medsanh@amicatravel.com)</option>
					<option value="772">Nga (nga.nt@amicatravel.com)</option>
					<option value="4186">Ngọc (dangngoctuonglam@gmail.com)</option>
					<option value="12665">Ngọc (bui.ngoc@amicatravel.com)</option>
					<option value="18724">Ngọc (trinh.ngoc@amicatravel.com)</option>
					<option value="118">Ngọc (bich.ngoc@amicatravel.com)</option>
					<option value="17097">Nhi (xuan.nhi@amicatravel.com)</option>
					<option value="11724">Nhung (hoa.nhung@amicatravel.com)</option>
					<option value="18598">Nhung (cao.nhung@amicatravel.com)</option>
					<option value="5388">Nicolas (nicolas.vidal@amicatravel.com)</option>
					<option value="4125">Oanh (oanh.dt@amicatravel.com)</option>
					<option value="1033">Phúc (nguyen.phuc@amicatravel.com)</option>
					<option value="1677">Phương (mai.phuong@amicatravel.com)</option>
					<option value="7">Phương (thu.phuong@amicatravel.com)</option>
					<option value="1906">Potrotha (potrotha.k@amicatravel.com)</option>
					<option value="16226">Quỳnh (nguyen.quynh@amicatravel.com)</option>
					<option value="15955">Quỳnh (quynh181989@gmail.com)</option>
					<option value="15007">Ratey (phich.ratey@amicatravel.com)</option>
					<option value="3066">Thảo (nguyen.thao@amicatravel.com)</option>
					<option value="17090">Thơ (anh.tho@amicatravel.com)</option>
					<option value="14030">Thoeun (prim.thoeun@amicatravel.com)</option>
					<option value="9127">Thu (nguyen.thu@amicatravel.com)</option>
					<option value="9198">Thu (pham.thu@amicatravel.com)</option>
					<option value="3635">Thương (thanhthatvoitinhyeu_842001@yahoo.fr)</option>
					<option value="5270">Thuý (kim.thuy@amicatravel.com)</option>
					<option value="4065">Tuấn (do.tuan@amicatravel.com)</option>
					<option value="15931">Tuấn (anhtuan912.mta@gmail.com)</option>
				</select>
			</div>
			<div class="col-md-3">
				<?= Html::submitButton('Submit', ['class'=>'btn btn-primary btn-block']) ?>
			</div>
		</div>
	</form>
</div>

<div class="clear note-list-clear"></div>
 
<script type="text/javascript" src="/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js?v=3"></script>
<script>
function removeTinyMCE() {
	$('#tinymceDiv').hide();
	$('#clickToWrite').show();
}

$(function(){
	/*
	$('.redactor').redactor({
		minHeight: 100,
		convertLinks: false,
		colors: [
    '#ffffff', '#000000', '#666666', '#cccccc',
    '#ffffcc', '#ffff00', '#148040', '#009900',
		'#0033cc', '#ff3333', '#cc00cc']
	});
*/
	$('#clickToWrite').on('focus click', function(){
		$(this).hide();
		$('#tinymceDiv').show();
		//$('.redactor').setFocus();
		
		if (typeof tinyMCE === 'undefined') {
			$('#textarea-body').tinymce({
				// Location of TinyMCE script
				script_url : '/js/tinymce/jscripts/tiny_mce/tiny_mce.js?v=3',
				// Example content CSS (should be your site CSS)
				body_id : "body_work",
				content_css : "/css/style_tinymce_work.css",

				// General options
				autofocus: 'textarea-body',
				theme : "advanced",
				plugins : "advlink,advimage,autolink,autoresize,contextmenu,inlinepopups,noneditable,paste,table",

				theme_advanced_buttons1 : 'formatselect,|,bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,forecolor,backcolor,|,link,unlink,|,pastetext,pasteword,|,image,removeformat,|,code,help',
				theme_advanced_buttons2 : '',
				theme_advanced_buttons3 : '',
				
				entity_encoding : "raw",
				fix_list_elements : true,
				forced_root_block : 'p',
				verify_css_classes : true,
				verify_html : true,
				relative_urls : false,
				paste_strip_class_attributes : 'mso',
				paste_text_sticky : true,
				paste_text_use_dialog : false,
				paste_text_linebreaktype : 'p',

				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location: 'none',
				
				//file_browser_callback : "myCustomFileBrowser",
				
				//height: "300",
				width: "100%"
			});
		} else {
			tinyMCE.execCommand('mceFocus', false, 'textarea-body');
		}
		
		
	});

	$("#form-tinymce").submit(function(event) {
		event.preventDefault();
		$.post(
			'/ajax/n/c',
			$('#form-tinymce').serialize(),
			function(data) {
				if (data.status && data.status == 'OK') {
					$('#alert-wrapper').html('');
					$('#input-title').val('');
					$('#textarea-body').val('');
					removeTinyMCE();
					// $('.redactor').setCode('');
					$("#clickToWriteDiv").after(data.message);
					$(".new-n:eq(0)").animate({ backgroundColor:'#fff'}, 2000);
				}
				if (data.status && data.status == 'NOK') {
					$('#alert-wrapper').html(data.message);
				}
			},
			'json'
		);
	});
});
</script>