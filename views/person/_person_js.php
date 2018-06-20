<?php
$yn = [
		1 => 'yes',
		0 => 'No'
];

$dt_email = [
		1 => 'Ca nhan',
		2 => 'Cong ty',
];

$dt_web = [
		1 => 'Facebook',
		2 => 'Website',
		3 => 'Blog',
		4 => 'Other'
];
$dt_phone = [
		1 => 'Mobi',
		2 => 'Work',
		3 => 'Home',
		4 => 'Fax',
		5 => 'Other'
];
$dt_relation = [
		1 => 'Kết hôn',
		2 => 'Li dị',
		3 => 'Độc thân',
		4 => 'Sống chung',
		5 => 'Li dị',
		6 => 'Mối quan hệ mở'
];
$dt_family = [
		1 => 'Bố/mẹ',
		2 => 'Anh/em',
		3 => 'Chị/em',
		4 => 'Vợ/chồng'
];
$dt_email_js = json_encode ( $dt_email );
$dt_web_js = json_encode ( $dt_web );
$dt_phone_js = json_encode ( $dt_phone );
$dt_relation_js = json_encode ( $dt_relation );
$dt_family_js = json_encode ( $dt_family );


if($theForm->tel != ''){
	$count_p = count($theForm->tel);
}else{
	$count_p = 0;
}
$js_w = <<<TXT

function formatRepo (repo) {
    if (repo.loading) return repo.text;
    var markup = "<a href='" + repo.url + "' class='select2-result display-block clearfix'>" + repo.avatar_url + repo.found + "</a>"
	return markup;
}

function formatRepoSelection (repo) {
	return repo.found || repo.text;
}


//ADD EMAIL
			var email_id = 1;
			var op_em = $dt_email_js;
			function email_fields() {
				var evalue = document.getElementById("email_0").value;
				if(evalue == ''){
					alert('Email not blank');
					return false;
				}else{
					var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
					if (!filter.test(evalue)) {
						alert("Hay nhap dia chi email hop le. Example@gmail.com");
						return false;
					}
				}
				
				id = email_id++;
				fields = 'email_fields';
				rdiv = 'removeclass_email'+id;
				div_f = '<div class="col-md-12" style="margin-bottom: 6px;"><div class="col-md-3 col-sm-10 nopadding"><select name="id_email[]" class="form-control" id="id_email_'+id+'" style="width: 100%"><option value="">-- Select --</option></select></div><div class="col-sm-6 nopadding"><div class="form-group"><input type="text" class="form-control" name="email[]" placeholder="example@...." id="email_'+id+'" /></div></div><div class="col-sm-3 nopadding"><div class="form-group"><div class="input-group"><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_email_fields ('+ id +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button><div class="form-group"></div></div></div>'
				add_fields(id, fields , rdiv , div_f);
				$.each(op_em,function(i,item)
				{
					$('#id_email_'+id).append($('<option>').text(item).attr('value', item));
				});
			}
			function remove_email_fields(rid) {
				$('.removeclass_email'+rid).remove();
			}
//END

//ADD WEBSITE
			var website_id = 1;
			var op_w = $dt_web_js;
			
			function website_fields() {
			
				var w_pvalue = document.getElementById("id_website_0").value;
				var wvalue = document.getElementById("website").value;
				if(w_pvalue == ''){
					alert('Select type of website');
					return false;
				}else if(wvalue == ''){
					alert('Insert website');
					return false;
				}
				
				id = website_id++;
				fields = 'website_fields';
				rdiv = 'removeclass_web'+id;
				div_f = '<div class="col-md-12" style="margin-bottom: 6px;"><div class="col-md-3 col-sm-10 nopadding"><select name="id_website[]" class="form-control" id="id_website_'+id+'" style="width: 100%"><option value=""> -- Select -- </option></select>	</div><div class="col-md-6 col-sm-10 nopadding"><div class="form-group"><input type="text" class="form-control" name="website[]" placeholder="http://... Or https://...." id="website_'+id+'"  /></div></div><div class="col-md-3 col-sm-10 nopadding"><div class="form-group"><div class="input-group"><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_website_fields ('+ id +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div></div></div></div>';
				add_fields(id, fields , rdiv , div_f);
				
				$.each(op_w,function(i,item)
				{
					$('#id_website_'+id).append($('<option>').text(item).attr('value', item));
				});
				
				$('#website_'+website_id).formValidation();
			}
			
			function remove_website_fields(rid) {
				$('.removeclass_web'+rid).remove();
			}
//END

//ADD SO DIEN THOAI
			
			if($count_p > 0){
				var phone_id = $count_p + 1;
			}else{
				var phone_id = 1;
			}
			var op_dp = $dt_phone_js;
			function phone_fields() {
				var ptype = document.getElementById("phone_format_0").value;
				var p_value = document.getElementById("phone_number_0").value;
				if(ptype == ''){
					alert('Select type phonenumber');
					return false;
				}else if(p_value == '' ){
					alert('Phonenumber not null');
					return false;
				}
				
				id = phone_id++;
				fields = 'phone_fields';
				rdiv = 'removeclass_phone'+id;
				div_f = '<div class="col-md-12" style="margin-bottom: 6px;" id="tel_input_s_"+id><div class="col-md-3 col-sm-10 nopadding"><select name="phone_format[]" class="select form-control phone_s" id="phone_format_'+id+'" style="width: 100%"><option value=""> -- Select -- </option></select></div><div class="col-md-6 col-sm-10 nopadding"><div class="form-group"><input class="form-control" type="tel" name="phone[]" id="phone_number_'+id+'" style="width: 100%" /></div></div><div class="col-md-3 col-sm-10 nopadding"><div class="form-group"><div class="input-group"><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_phone_fields ('+ id +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div></div></div><input class="hide" name="dial_code_input[]" id="dial_code_input_'+id+'"></div>';
				add_fields(id, fields , rdiv , div_f);
				
				$.each(op_dp,function(i,item)
				{
					$('#phone_format_'+id).append($('<option>').text(item).attr('value', item));
				});
				
				$("#phone_number_"+id).intlTelInput({
					  // allowDropdown: false,
				      // autoHideDialCode: false,
				      // autoPlaceholder: "on",
				      // dropdownContainer: "body",
				      // excludeCountries: ["us"],
				      // formatOnDisplay: true,
				       geoIpLookup: function(callback) {
				         $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				           var countryCode = (resp && resp.country) ? resp.country : "";
				           callback(countryCode);
				         });
				       },
				       initialCountry: "auto",
				       nationalMode: false,
				      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
				      // placeholderNumberType: "MOBILE",
				      // preferredCountries: ['cn', 'jp'],
				      separateDialCode: true,
						utilsScript: "/js/inputTel/utils.js"
					});


				}
			function remove_phone_fields(rid) {
				$('.removeclass_phone'+rid).remove();
			}
//END

//ADD RELATION FARMILY
			var family_id = 1;
			var op_fa = $dt_family_js;
					
			function family_fields() {
					
				var re_value = document.getElementById('relationship_family').value;
				var fa_value = document.getElementById('person_family_0').value;
					
				if( re_value == '' ){
					alert('Select relationship');
					return false;
				}
				if(fa_value == 'Search' ){
					alert('Select people');
					return false;
				}
					
				id = family_id++;
				fields = 'family_fields';
				rdiv = 'removeclass_fam'+id;
				div_f = '<div class="col-md-12" style="margin-bottom: 6px;"><div class="col-md-3 col-sm-10 nopadding"><select class="form-control family_select" name="relationship_family[]" id="relationship_family_'+id+'" style="width:100%;"><option value=""> -- Select -- </option></select></div><div class="col-md-6 col-sm-10 nopadding"><div class="form-group"><select name="person_family[]" class="person_family form-control" id="person_family_'+id+'" style="width:100%;"><option> Search </option></select></div></div><div class="col-md-3 col-sm-10 nopadding"><div class="form-group"><div class="input-group"><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_family_fields('+ id +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div></div></div></div></div>';
				add_fields(id, fields , rdiv , div_f);
					
				$.each(op_fa,function(i,item)
				{
					$('#relationship_family_'+id).append($('<option>').text(item).attr('value', item));
				});
					
				$('#person_family_'+id).select2({
					ajax: {
					    url: "http://amica.dev/default/search",
					    dataType: 'json',
					    delay: 250,
					    data: function (params) {
					      return {
				                p: 1, // user id 1
				                n: params.term, // search term
				                page: params.page
				          };
					    },
					    processResults: function (data, params) {
					      params.page = params.page || 1;
					      return {
					        results: data.items,
					        pagination: {
					          more: (params.page * 30) < data.total_count
					        }
					      };
					    },
					    cache: true
					},
				  	escapeMarkup: function (markup) { return markup; },
					minimumInputLength: 1,
					templateResult: formatRepo,
					templateSelection: formatRepoSelection
				});
			}
					
			function remove_family_fields(rid) {
				$('.removeclass_fam'+rid).remove();
			}			
//

//ADD DIA CHI
			var address_id = 1;
			function address_fields() {
				var a_value = document.getElementById('address').value;
				var c_value = document.getElementById('city').value;
				var n_value = document.getElementById('national').value;
			 	if(a_value == '' && c_value == '' && n_value == ''){
					alert('Enter address or cty or nation');
					return false;
				}
				
			    id = address_id++;
				fields = 'address_fields';
				rdiv = 'removeclass_address'+id;
				div_f = '<div class="col-md-12" style="margin-bottom: 6px;" ><div class="col-sm-3 nopadding"><div class="form-group"><input type="text" class="form-control" id="address_'+id+'" name="address[]" value="" placeholder="Address"></div></div><div class="col-sm-3 nopadding"><div class="form-group"><input type="text" class="form-control" id="city_'+id+'" name="city[]" value="" placeholder="City"></div></div><div class="col-sm-3 nopadding"><div class="form-group"><input type="text" class="form-control" id="national_'+id+'" name="nation[]" value="" placeholder="Nation"></div></div><div class="col-sm-3 nopadding"><div class="form-group"><div class="input-group"><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_address_fields('+ id +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div></div></div></div><div class="clear"></div></div>';
				add_fields(id, fields , rdiv , div_f);
			}
			function remove_address_fields(rid) {
				$('.removeclass_address'+rid).remove();
			}
//					
					
//FORM ADD
			function add_fields(id, fields , rdiv , div_f){
			    var objTo = document.getElementById(fields)
			    var divtest = document.createElement("div");
				divtest.setAttribute("class", "form-group " + rdiv );
				var rdiv = rdiv;
			    divtest.innerHTML = div_f ;
			    objTo.appendChild(divtest);
			}
//


//REMOVE OLD FIELD
			function remove_email_old_fields(rid) {				
				remove_old_fields(rid, 'email');
			}
			function remove_website_old_fields(rid) {				
				remove_old_fields(rid, 'website');
			}
			function remove_phone_number_old_fields(rid){
				remove_old_fields(rid, 'phone_number');
			}
			function remove_family_old_fields(rid){
				remove_old_fields(rid, 'family');
			}
			function remove_address_old_fields(rid){
				remove_old_fields(rid, 'address');
			}

			function remove_old_fields(rid, type){
				if(type == 'address'){							
					var input_a = document.getElementById(type+'_old_'+rid).value;
					var input_c = document.getElementById('city_old_'+rid).value;
					var input_n = document.getElementById('national_old_'+rid).value;
					var input_d = input_a + '/n' +input_c+ '/n' + input_n;
				}else if(type == 'phone_number'){
					var input_cod = document.getElementById('dial_code_input_'+rid).value;
					var input_phone = document.getElementById(type+'_old_'+rid).value;
					var input_d = input_cod + '&nbsp;' +input_phone.replace(/\D/g,'') ; //.replace(/\D/g,'') bo tat ca cac ki tu dac biet 
				}else{
					var input_d = document.getElementById(type+'_old_'+rid).value;					
				}					
				var input_del = document.getElementById(type+'_del').value;
				var cls = '#'+type+'_del';
			 	var a = $(cls).val(input_d +"^"+ input_del );	
				//alert(a.val());			
				$('.removeclass_'+type+'_old'+rid).remove();
			}


//					

				
	
					
TXT;
$this->registerJs ( $js_w, yii\web\View::POS_HEAD, 'my-button-handler' );
?>



<?
$count_phon = count($theForm->tel);
$js = <<<'TXT'
$(document).ready(function() {

	$('#website').formValidation();

	$('#person_family_0').select2({
					ajax: {				   
					url: "http://amica.dev/default/search",
				    dataType: 'json',
				    delay: 250,
				    data: function (params) {
				      return {
			                p: 1, // user id 1
			                n: params.term, // search term
			                page: params.page
			          };
				    },
				    processResults: function (data, params) {
				      // parse the results into the format expected by Select2
				      // since we are using custom formatting functions we do not need to
				      // alter the remote JSON data, except to indicate that infinite
				      // scrolling can be used
				      params.page = params.page || 1;
				
				      return {
				        results: data.items,
				        pagination: {
				          more: (params.page * 30) < data.total_count
				        }
				      };
				    },
				    cache: true
				  },
				  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
				  minimumInputLength: 1,
				  templateResult: formatRepo, // omitted for brevity, see the source of this page
				  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
				});

	
	var cnt = 0;
	var form_v = $('#w0')
				.formValidation({
		            framework: 'bootstrap',
		
		            fields: {
		                'email[]': {
						 verbose: false,
		                    validators: {
		                        stringLength: {
		                           max: 512,
                            	   message: 'Cannot exceed 512 characters'
		                        },
								emailAddress: {
		                            message: 'The input is not a valid email address'
		                        },
		                    }
		                },
						'phone[]':{							
							validators: {
		                        callback: {										                       
		                            callback: function(value , validator, $field) {									
	                                	return value === '' || $field.intlTelInput('isValidNumber');
	                            	}
	                        	}
							}
						},
						'website[]': {
			                validators: {
			                    uri: {
			                        message: 'The website address is not valid'
			                    }
			                }
			            }
		            }
		        })
				.on('err.field.fv', function(e, data) {
		            if (data.fv.getSubmitButton()) {
		                data.fv.disableSubmitButtons(true);
		            }
		        })
		        .on('success.field.fv', function(e, data) {
		            if (data.fv.getSubmitButton()) {
		                data.fv.disableSubmitButtons(false);
						 $('.btn-sub').removeAttr('disabled');  
		            }
		        });			

				jQuery(".btn-sub").on('click','', function(){
					
					var bvalue = document.getElementById("usersuuform-bday").value;
					var mvalue = document.getElementById("usersuuform-bmonth").value;
					var yvalue = document.getElementById("usersuuform-byear").value;
					var d = new Date();				

					if(bvalue < 0 || bvalue > 31){
						alert('Error Date');
						return false;
					}if(mvalue < 0 || mvalue > 12){
						alert('Error Month');
						return false;
					}if(yvalue < 0 || yvalue > d.getFullYear()){
						alert('Error Year');
						return false;
					}if( yvalue == d.getFullYear() ) {
						if( mvalue > d.getMonth()+1 ){
							alert('Error Month');
							return false;
						}if( bvalue > d.getDate() ){
							alert('Error Day');
							return false;
						}
					}					
				});				

				$('#w0').on('beforeSubmit', function (e) {
					cnt++;
					if(cnt == 1) {						
						return true;
					}
				    return false;
				});

				$('#w0').submit(function(){
					 $('.btn-sub').removeAttr('disabled');  
					document.getElementById("submitbtn").classList.remove('disabled');
				});			
								
				//alert(phone_id);
			if(phone_id > 0){
				var i = 0;
				for(i ; i < phone_id ; i++){
					$("#phone_number_old_"+i).intlTelInput({
					  // allowDropdown: false,
				      // autoHideDialCode: false,
				  	  //autoPlaceholder: "on",
				      // dropdownContainer: "body",
				      // excludeCountries: ["us"],
		  			  //formatOnDisplay: true,
				       geoIpLookup: function(callback) {
				         $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				           var countryCode = (resp && resp.country) ? resp.country : "";
				           callback(countryCode);
				         });
				       },
				       initialCountry: "auto",
				       nationalMode: false,
				      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
				      // placeholderNumberType: "MOBILE",
				      // preferredCountries: ['cn', 'jp'],
				      	separateDialCode: true, //(tach rieng ma quoc gia) 
						utilsScript: "/js/inputTel/utils.js"
					});
				}
			}

				$("#phone_number_0").intlTelInput({
				  // allowDropdown: false,
			      // autoHideDialCode: false,
			  	  //autoPlaceholder: "on",
			      // dropdownContainer: "body",
			      // excludeCountries: ["us"],
	  			  //formatOnDisplay: true,
			       geoIpLookup: function(callback) {
			         $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			           var countryCode = (resp && resp.country) ? resp.country : "";
			           callback(countryCode);
			         });
			       },
			       initialCountry: "auto",
			       nationalMode: false,
			      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			      // placeholderNumberType: "MOBILE",
			      // preferredCountries: ['cn', 'jp'],
			      	separateDialCode: true, //(tach rieng ma quoc gia) 
					utilsScript: "/js/inputTel/utils.js"
				});
		
	});


TXT;
$this->registerJsFile ( '/js/inputTel/intlTelInput.js', [ 
		'depends' => 'yii\web\JqueryAsset' 
] );
$this->registerJsFile ( '/js/formValidation.min.js', [ 
		'depends' => 'yii\web\JqueryAsset' 
] );
$this->registerJsFile ( 'http://formvalidation.io/vendor/formvalidation/js/framework/bootstrap.min.js', [ 
		'depends' => 'yii\web\JqueryAsset' 
] );
$this->registerJs ( $js );
?>