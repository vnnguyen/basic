<?

$baseUrl = Yii::getAlias('@webroot');
$this->registerCssFile('/css/plugins/croppie/demo.css');

$this->registerCss('
	body {
	  font: 14px/1.5 helvetica-neue, helvetica, arial, san-serif;
	  padding:10px;
	}
	 
	h1 {
	  margin-top:0;
	}
	 
	#main {
	  width: 300px;
	  margin:auto;
	  background: #ececec;
	  padding: 20px;
	  border: 1px solid #ccc;
	}
	 
	#image-list {
	  list-style:none;
	  margin:0;
	  padding:0;
	}
	#image-list li {
	  background: #fff;
	  border: 1px solid #ccc;
	  text-align:center;
	  padding:20px;
	  margin-bottom:19px;
	}
	#image-list li img {
	  width: 258px;
	  vertical-align: middle;
	  border:1px solid #474747;
	}
');
// $this->registerJsFile('', ['depends'=>'app\assets\MainAsset']);
?>
<div id="mainn">
	<h1>Upload Your Images</h1>
	<form method="post" enctype="multipart/form-data"  action="">
		<input type="file" name="images" id="images" multiple />
		<button type="submit" id="btn">Upload Files!</button>
	</form>

	<div id="response"></div>
	<ul id="image-list">

	</ul>
</div>

<?
$js = <<<TXT
var input = document.getElementById("images"),
	formdata = false;

if (window.FormData) {
	formdata = new FormData();
	document.getElementById("btn").style.display = "none";
}
if (input.addEventListener) {
	input.addEventListener("change", function (evt) {
		var i = 0, len = this.files.length, img, reader, file;

		document.getElementById("response").innerHTML = "Uploading . . .";

		for ( i = 0; i < len; i++ ) {
			file = this.files[i];

			if (!!file.type.match(/image.*/)) {
				if ( window.FileReader ) {
					reader = new FileReader();
					reader.onloadend = function (e) {
						showUploadedItem(e.target.result);
					};
					reader.readAsDataURL(file);
				}
				if (formdata) {
					formdata.append("images[]", file);
				}
				if (formdata) {
					$.ajax({
						url: "/tours/upload",
						type: "POST",
						data: formdata,
						processData: false,
						contentType: false,
						success: function (res) {
							document.getElementById("response").innerHTML = res; 
						}
					});
				}
			}
		}

	}, false);
}



function showUploadedItem (source) {
	var list = document.getElementById("image-list"),
		li   = document.createElement("li"),
		img  = document.createElement("img");
	img.src = source;
	li.appendChild(img);
	list.appendChild(li);
}
TXT;
$this->registerJs($js);
?>