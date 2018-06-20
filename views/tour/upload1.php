<?

$baseUrl = Yii::getAlias('@webroot');
$this->registerCssFile('');

$this->registerCss('

');
$this->registerJsFile('/js/plugins/fileupload/vendor/jquery.ui.widget.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.iframe-transport.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload.js', ['depends'=>'app\assets\MainAsset']);
?>
<div class="col-md-12">
	<input id="fileupload" type="file" name="files[]" data-url="/fileupload/index" multiple>
	<div id="progress">
	    <div class="bar" style="width: 0%;"></div>
	</div>
</div>

<?
$js = <<<TXT
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        progressall: function (e, data) {
        	var progress = parseInt(data.loaded / data.total * 100, 10);
	        $('#progress .bar').css(
	            'width',
	            progress + '%'
	        );
	    },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
});
TXT;
$this->registerJs($js);
?>