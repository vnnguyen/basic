<?
$pluploadJs = <<<'TXT'
var uploader = new plupload.Uploader({
	runtimes: 'html5,flash,silverlight',
	container: document.getElementById('files-container'),
	flash_swf_url : '/assets/plupload_2.1.9/js/Moxie.swf',
	silverlight_xap_url : '/assets/plupload_2.1.9/js/Moxie.xap',
	browse_button: 'files-browse',
	url: '/assets/plupload_2.1.9/upload.php',
	filters: {
		max_file_size : '25mb',
		prevent_duplicates: true,
		mime_types : [
			{ title : "Image files", extensions : "jpg,jpeg,gif,tif,tiff,png" },
			{ title : "Zip files", extensions : "zip,rar,tar,gz,7z,bz2,bgz,tgz" },
			{ title : "Doc files", extensions : "doc,docx,docm,xls,xlsx,ppt,pptx,pdf,txt,csv" },
			{ title : "Media files", extensions : "swf,mp3,mpeg,mp4" }
		]
	},
	unique_names: true,
	init: {
		PostInit: function() {
			$('#files-list').empty();
			/*
			$('files-upload').onclick = function() {
				uploader.start();
				return false;
			};
			*/
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				$('#files-list').append('<div id="' + file.id + '"><input type="hidden" name="fileid[]" value="'+file.id+'"><input type="hidden" name="filename[]" value="'+file.name+'">+ ' + file.name + ' (' + plupload.formatSize(file.size) + ') <span class="text-info"></span></div>');
			});
		},

		FileUploaded: function(up, file, res) {
			/*
			$.post('/files/ajax', {name:file.name, filegroup:1, action:'create', size:file.size, id:file.id}, function(data){
				if (data.status == 'OK') {
					$('#' + file.id).remove();
					var html = '<tr id="file-' + data.id + '"><td><input type="hidden" name="file_id[]" value="' + data.id + '" />+ <a href="/files/r/'+data.id+'">' + file.name + '</a></td><td class="ta-r">' + data.sizeh + ' (<a rel="' + data.id + '" class="file-del" href="#delete">Delete</a>)</td></tr>';
					$('#table-filelist tr:first').after(html);
				} else {
					$('#' + file.id + " span").html(data.status);
				}
			}, 'json');
			*/
			$('#'+file.id+' span').empty();
			$('#'+file.id).append(' (<a class="files-file-remove" href="javascript:;" rel="">Remove</a>)');
		},

		UploadProgress: function(up, file) {
			$('#'+file.id+' span').html(' - Uploading ' + file.percent + '%');
		},

		Error: function(up, err) {
			$('#files-console').append("<br>Error" + err.code + ": " + err.message);
		},

		QueueChanged: function(up) {
			uploader.start();
		}
	}
});

uploader.init();

$(document).on('click', '.files-file-remove', function(e) {
	$(this).parent().remove();
})
TXT;

$this->registerJsFile(DIR.'assets/plupload_2.1.9/js/plupload.full.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($pluploadJs);