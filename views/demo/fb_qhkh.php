<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <!-- <title>Hello, world!</title> -->
    <style>
		.text-sologan{
		    background: rgba(0,0,0, 0.6);
		    position: relative;
		    z-index: 1;
		}
		.area-btn-list-menu{
		    background: rgba(255,255,255, 0.8);
		    z-index: 1;
		}
		.contain{
		    float: left;
		    clear: left;
		    width: 100%;
		    height: auto;
		    font-size: 15.5px;
		    font-family: "LatoLatin-Regular", sans-serif;
		}
		.contain .amc-column{
		    width: 960px;
		    margin: 0 auto;
		    padding: 0 10px;
		}

		.contain .amc-column-fixpadding{
		    
		    padding: 0 0px;
		}
		.contain .amc-column .rows {
		    clear: left;
		    width: 100%;
		    float: left;
		}
		.container-1 .row-1{
		     background: rgba(0,0,0, 0.5);
		    position: absolute;
		    z-index: 1;
		    width: 100%;
		    top: 150px;
		}
		.container-1 .breadcrumb{
		    width: 960px;
		    margin: 0 auto;
		    padding: 0 10px;
		    height: 23px;
		    background: none;
		}

		.container-1 .breadcrumb a, .container-1 .breadcrumb span{
		    font-size: 13px;
		    color: white;
		    font-family: inherit;
		}
		.container-1 .breadcrumb a:hover{
		    text-decoration: underline;
		}
		.container-1{
		    margin-top: -150px;
		    position: relative;
		    z-index: 0;
		}
		.container-1 .row-2{
		    position: absolute;
		    bottom: 0;
		    left: 0;
		    right: 0;
		}
		.container-1 .amc-column .title{
		    color: white;
		    text-transform: uppercase;
		    font-size: 40px;
		    text-align: center;
		    margin: 0 0 35px 0;
		}
		.container-2{
		    z-index: 2;
		}

		input, select{
		      border: 1px solid #d9d9d9;
		    height: 42px;
		    line-height: 44px;
		    padding: 0 10px;
		}
		textarea{
		    border: 1px solid #d9d9d9;
		    width: 100%;
		    padding: 10px;
		}
		.form{
		    width: 870px;
		    margin: 0 auto;
		}
		.form .amc-col{
		    padding: 0 10px;
		}
		.text .tt{
		    font-size: 18px;
		    font-family: "LatoLatin-Bold", sans-serif;
		    margin: 40px 0 20px;
		    text-transform: uppercase;
		}
		table td{
		    padding: 10px 0;
		}
		.input_full{
		    width: 100%;
		}
		.code-anti{
		    vertical-align: top;
		}
		.fix-text-code{
		    margin: 20px 0 0;
		}
		#btn-valider-big{
		   text-transform: uppercase;
		    color: white;
		    font-size: 22px;
		    background: #da521f url(../../img/page2016/arrow_white.png) 328px center no-repeat;
		    display: inline-block;
		/*    width: 396px;
		    line-height: 59px;
		    height: 57px;
		    float: left;*/
		    text-align: center;
		    font-family: "LatoLatin-Bold", sans-serif;
		    margin: 30px 0 35px;
		    border-radius: 5px;
		    padding-right: 32px;
		}
		#btn-valider-big:hover{
		    opacity: 0.8;
		}
		.error-summary ul li{
		     color: #e26640;
		    
		}

		.fix-arrow{
		     background: url(../../img/page2016/arrow_up_down_cam.png) no-repeat scroll right 15px center;
		    -moz-appearance: none !important;
		     padding-right: 27px;
		}
		.form .fix-arrow{
		     background: url(../../img/page2016/arrow_up_down_cam.png) no-repeat scroll right 15px center;
		    -moz-appearance: none !important;
		    -webkit-appearance: none !important;
		     appearance: none !important;
		     -ms-accelerator: none !important;
		    
		     padding-right: 27px;
		}
		.form .rdv .fix-arrow{
		     background: #fff url(../../img/page2016/arrow_down_black.png) no-repeat scroll right center;
		    -moz-appearance: none !important;
		    -webkit-appearance: none !important;
		     appearance: none !important;
		     -ms-accelerator: none !important;
		     padding-right: 27px;
		}
		span.field-contactform-calldate  {
		    background: rgba(0, 0, 0, 0) url("../../img/page2016/icon_input_datepicker.png") no-repeat scroll right 5px;
		    display: inline-block;
		    height: 100%;
		    padding-right: 40px;
		}
		.fix-tt{
		  margin: 0 0px 0 20px;  
		}
		.has-error input, .has-error select, .has-error textarea{
		   
		    border: 1px solid #e25825;
		}
		.text{
		    width: 870px;
		    margin: 60px auto 0;
		    padding: 0 10px;
		    text-align: center;
		}
		.text .fix{
		    padding: 0 10px;
		}
		.input-fullname {
		    width: 100%;
		}
		.amc-col-fix-align {
		    text-align: right;
		}

		.fix-middle-text {
		    display: inline-block;
		    margin-top: 12px;
		}
		.has-error .text-label {
		  /*  color: #e26640; */
		}
		.input-region {
		    width: 100%;
		}
		.input-country {
		    width: 100%;
		}
		.input-ville {
		    width: 100%;
		}
		.fix-col-left {
		    display: table;
		    height: 100%;
		}
		.fix-col-left .middle-text {
		    display: table-cell;
		    padding: 0 10px;
		    vertical-align: middle;
		}
		/* CUSTOM FORM RADIO */
		input[type="radio"], input[type="checkbox"] {
		    display:none;
		}

		input[type="radio"] + span, input[type="checkbox"] + span {
		    display:inline-block;
		    width:15px;
		    height:19px;
		    margin:-3px 0px 0 0;
		    vertical-align:middle;
		    cursor:pointer;
		    -moz-border-radius:  50%;
		    border-radius:  50%;
		}

		input[type="checkbox"] + span{
		    border-radius: 0px;
		}

		input[type="radio"] + span {
		     background: url("../../img/page2016/bg_radio.png") no-repeat scroll left center;
		}
		input[type="checkbox"] + span{
		    background: url("../../img/page2016/bg_checkbox.png") no-repeat scroll left center;
		}

		input[type="radio"]:checked + span{
		     background: url("../../img/page2016/bg_list_active.png") no-repeat scroll left center;
		}
		input[type="checkbox"]:checked + span{
		    background: url("../../img/page2016/bg_checkbox_active.png") no-repeat scroll left center;
		}

		input[type="radio"] + label span,
		input[type="radio"]:checked + label span,
		input[type="checkbox"] + label span,
		input[type="checkbox"]:checked + label span{
		  -webkit-transition:background-color 0.4s linear;
		  -o-transition:background-color 0.4s linear;
		  -moz-transition:background-color 0.4s linear;
		  transition:background-color 0.4s linear;
		}
		label {
		    font-family: "LatoLatin-Regular",sans-serif;
		    display: block;
		    margin: 0 15px 0 0;
		    font-size: 15.5px;
		    font-weight: normal;
		}
		.form .help-block {
		    color: #e25825;
		    font-family: "Lato-Regular";
		    font-size: 13px;
		    font-style: italic;
		    margin: 3px 0 0;
		}
		.field-contactform-verificationcode .help-block{
		    position: absolute;
		    left: 8px;
		    top: 40px;
		}
		.field-contactform-calltime .help-block{
		    position: absolute;
		    left: 21px;
		    bottom: 0;
		}
	</style>
  </head>
  <body>
	<div class="contain container-2">
	    <div class="amc-column">
	        <div class="text">
	           <p class="fix">Un conseiller à l'écoute de votre projet vous rappelle gratuitement !</p>
	        </div>
			 <?php
				//include_once '_form_rdv.php';
			?>
	    </div>
	</div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
  </body>
</html>

