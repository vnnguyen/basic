<?
$baseUrl = Yii::getAlias('@webroot');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/croppie/2.4.1/croppie.css');
$this->registerCssFile('/css/plugins/croppie/demo.css');

$this->registerCss('
	.demo-wrap {
	    height: 800px;
	    margin: 0 auto;
	    width: 800px;
	}
	.image-uploader {
	    position:relative;
	    cursor:pointer;
	    background:#fff;
	  	box-shadow:5px 5px 0 rgba(0,0,0,0.1) inset;
	  	
	  }
	');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/croppie/2.4.1/croppie.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/croppie/exif.js', ['depends'=>'app\assets\MainAsset']);
?>
<div class="col-md-12">
	<div class="image-uploader filled" data-base-height="250" data-base-width="250" style="width: 250px; height: 250px;">
    <div class="image" style="width: 250px; height: 250px;">
      <label>Click or drag a file to change your image</label>
      <img>
    </div>
    <input id="uploader" type="file">
    <div class="zoom" style="display: block;">
      <div class="plus"></div>
      <div class="minus"></div>
      <div class="close"></div>
    </div>
  </div>
	<div class="demo-wrap">
	</div>

	<?
$js = <<<TXT

var basic = $('.demo-wrap').croppie({
    viewport: {
        width: 320,
        height: 320
    },
	showZoomer: false,
    enableOrientation: true
});
basic.croppie('bind', {
    url: '/img/cat.jpg',
    points: [77,469,280,739]
});
//on button click
basic.croppie('result', 'html').then(function(html) {
    // html is div (overflow hidden)
    // with img positioned inside.
});
$('#upload').on('change', function () { readFile(this); });


function readFile(input) {
	if (input.files && input.files[0]) {
	    var reader = new FileReader();

	    reader.onload = function (e) {
			$('.upload-demo').addClass('ready');
	    	basic.croppie('bind', {
	    		url: e.target.result
	    	}).then(function(){
	    		console.log('jQuery bind complete');
	    	});

	    }

	    reader.readAsDataURL(input.files[0]);
	}
	else {
	    swal("Sorry - you're browser doesn't support the FileReader API");
	}
}
TXT;
$this->registerJs($js);
?>
</div>