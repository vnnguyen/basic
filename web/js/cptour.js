//////////////////////global variable////////////////////////////////////////////

var FORM = $('#cptourForm');
var id_dv = 0;
var day_use = '';
var venue_id = 0;
var tour_id;
var form_status = 'create';
var data_source_dv = null;

var op= {
	dataType: 'json',
    beforeSubmit: function(arr, form, options) {
        var validate_status = true;
        $(FORM).find('.form-group').each(function(i, item){
			if ($(item).hasClass('has-error')) {
				$(item).removeClass('has-error');
			}
		});
		var venue_id = $(FORM).find('#cptour-venue_id').val();
        if (venue_id == null) {
            $(FORM).find('#cptour-venue_id').closest('.form-group').addClass('has-error');
            validate_status = false;
        }
        $(form).find('.wrap-cpt').each(function(i, wrap_cpt){
	        var dv_id = $(wrap_cpt).find('.cptour-dv_id').val();
	        var qty = $(wrap_cpt).find('.cptour-qty').val();
	        var num_day = $(wrap_cpt).find('.cptour-num_day').val();
	        var price = $(wrap_cpt).find('.cptour-price').val();
	        if (dv_id == null && !dv_id > 0) {
	            $(wrap_cpt).find('.cptour-dv_id').closest('.form-group').addClass('has-error');
	            validate_status = false;
	        }
	        if (isNaN(qty) || qty.length == 0) {
	            $(wrap_cpt).find('.cptour-qty').closest('.form-group').addClass('has-error');
	            validate_status = false;
	        }
	        if (num_day == '') {
	            $(wrap_cpt).find('.cptour-num_day').closest('.form-group').addClass('has-error');
	            validate_status = false;
	        }
	        if (price == '') {
	            $(wrap_cpt).find('.cptour-price').closest('.form-group').addClass('has-error');
	            validate_status = false;
	        }
        });
        if (!validate_status) {
            return false;
        }
    },
    success: function(result){
    	$('#cancel_btn').trigger('click');
    	var cpts = result.cpts;
    	var days = result.days;
    	$('#body-list-cpt').empty();
    	$.each(days, function(dt, day){
    		var name_of_date = weekdays[new Date(dt).getDay()];
    		var day_tr = '<tr id="day'+ dt +'" class="info" data-dt="'+ dt +'"> <td colspan="5"> '+ dt +' '+ name_of_date +' <a class="fw-b" href="#tours/ngaytour/12511" title="'+ day.body +'">'+ day.name +' ('+ day.meals +')</a></td> <td><a class="dvt-c" href="#cpt-c" day="'+ dt +'"><span class="span-add_cpt">+New</span></a></td> </tr>';
    		$('#body-list-cpt').append(day_tr);
    		$.each(cpts, function(index, cpt){
    			if (dt == cpt.use_day) {
    				var cpt_tr = '<tr class="tr-services" data-cpt_id="'+ cpt.id +'" data-dt="'+ dt +'"> <td colspan="2"><div class="cpt-name-wrap"> <a class="venue_update"><span class="cpt-name">'+ cpt.dv.name +'</span> - '+ cpt.venue.name +'</a> </div></td> <td>'+ cpt.qty +'</td> <td>'+ cpt.num_day +'</td> <td>'+ cpt.price +' <span class="text-muted">'+ cpt.currency +'</span></td> <td> <div class="wrap-actions"> <span class="span-edit_cpt" data-cpt-id="22"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <span class="span-remove_cpt" data-cpt-id="22"><i class="fa fa-trash-o" aria-hidden="true"></i></span> </div> </td> </tr>';
    				$('#body-list-cpt').append(cpt_tr);
    			}
    		});
    	});
    	$('#cptModal').modal('hide');
    	console.log(result); return false;
    }
};
$(document).on('click', '.save_btn', function(){
	$(FORM).ajaxSubmit(op);
});
    tooltip = new PNotify({
        title: "Group",
        text: "none",
        hide: false,
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        },
        animate_speed: "fast",
        opacity: .9,
        icon: "fa fa-commenting",
        // Setting stack to false causes PNotify to ignore this notice when positioning.
        stack: false,
        auto_display: false,
        // width: "270px"
    });
    // Remove the notice if the user mouses out it.
    tooltip.get().mouseout(function() {
        tooltip.remove();
    });
////////////////////////////////form nhap dong////////////////////////////////////
	$('#cptModal').on('show.bs.modal', function () {
    });
    $('#cptModal').on('hide.bs.modal', function () {
    	$('#wrap-cpts').empty();
    	$('#cptour-venue_id').html('');
    });
	$(document).on('click', '.span-add_cpt, .span-edit_cpt', function(){
		resetVar();
		var clicked = $(this);
		var tr_clicked = clicked.closest('tr');
		var dt = $(this).closest('tr').data('dt');

		//add inputs to form
		var elements_input = $('#wrap-input').find('.wrap-cpt').clone();
		$('#wrap-cpts').append(elements_input);
		$(elements_input).find('.cptour-use_day').val(dt.toString());

		$(FORM).find('.select2').css('width', '100%');
		$('#cptModal').modal("show");
		$(FORM).find('.add_more').data('dt', dt);

		// $('<tr>').data('dt', dt).append($('<td colspan="8">').append(new_form)).insertAfter(tr_clicked);
		if (clicked.data('cpt-id') != '' && clicked.data('cpt-id') > 0) {
			form_status = 'update';
			var cpt_id = clicked.data('cpt-id');
			$.ajax({
				method: 'GET',
				url: '/cptour/get_cpt',
				data: {cpt_id: cpt_id},
				dataType: 'json'
			}).done(function(response){
				if (response.err != undefined) { console.log(response.err); return;}
				var cpts = response.cpts;
				var venue = response.venue;
				var dvs = response.dvs;
				// console.log(response);
				// return false;
				$('#cptourForm #cptour-venue_id').append($('<option>', {value: venue.id, text : venue.name}));
				$('#wrap-cpts').empty();

				var data_dv = $.map(dvs, function (obj) {
						// obj.name = obj.name.allReplace({'{': '(', '}': ')'});
						obj.id = obj.id;
						obj.text = obj.text || obj.name; // replace name with the property used for the text
						return obj;
					});
				$.each(cpts, function(i, cpt){
					var elements_input = $('#wrap-input').find('.wrap-cpt').clone();
					$('#wrap-cpts').append(elements_input);
					$(elements_input).find('.cptour-dv_id').html('')
															.append($('<option>', {value: '', text : ''}))
															.select2({
																placeholder: "Select a service",
																data: data_dv,
																tags: "true",
																maximumInputLength: 20
															}).val(cpt.dv_id)
    														.trigger("change");
					$(elements_input).find('.cptour-use_day').val(dt.toString());
					$(elements_input).find('.cptour-id').val(cpt['id']);
					$(elements_input).find('.cptour-qty').val(cpt['qty']);
					$(elements_input).find('.cptour-num_day').val(cpt['num_day']);
					$(elements_input).find('.cptour-plusminus').val(cpt['plusminus']);
					$(elements_input).find('.cptour-use_day').val(new Date(cpt['use_day']).yyyymmdd());
					$(elements_input).find('.cptour-price').val(cpt['price']);
					$(elements_input).find('.cptour-currency').val(cpt['currency']);
					$(elements_input).find('.remove_dv').data('cpt-id', cpt.id);
				});
				// $('#cptourForm #cptour-dv_id').val(cpt['dv_id']).trigger('change');
			});
		} else {
			form_status = 'create';
		}
	});
	$(document).on('click', '#cancel_btn', function(){
		resetVar();
	});
	$(document).on('click', '.add_more', function(){
		var dt = $(this).data('dt');
		//add inputs to form
		var elements_input = $('#wrap-input').find('.wrap-cpt').clone();
		$(elements_input).find('.cptour-use_day').val(dt.toString());
		$('#wrap-cpts').append(elements_input);
		if (data_source_dv == null) {return false;}
		$(elements_input)
						.find('.cptour-dv_id')
						.append($('<option>', {value: '', text : ''}))
						.select2({
							placeholder: "Select a service",
							data: data_source_dv,
							tags: "true",
							maximumInputLength: 20
						});
	});

	$(document).on('click', '.day-name', function(){
		$(this).closest('td').find('.day-content').toggleClass('collapse');
		return false;
	});
	$(document).on('click', '.span-remove_cpt', function(){
		var clicked = $(this);
		var cpt_id = clicked.data('cpt-id');
		(new PNotify({
		    title: 'Confirmation Needed',
		    text: 'Are you sure?',
		    icon: false,
		    // hide: false,
		    styling: 'bootstrap3',
		    confirm: {
	            confirm: true,
	            buttons: [{
	                text: 'Ok!',
	                addClass: 'confirm-ok',
	             //    click: function(notice) {
		            //     notice.remove();
		            // }
	            }, {
	                text: 'Cancel!',
	                addClass: 'no'
	            }]
	        },
		    buttons: {
		        closer: false,
		        sticker: false
		    },
		    history: {
		        history: false
		    },
		    addclass: 'stack-modal',
		    stack: {
		        'dir1': 'down',
		        'dir2': 'right',
		        'modal': true
		    },
            after_open: function (notify) {
	            $(".confirm-ok", notify.container).focus();
	        }
		})).get().on('pnotify.confirm', function(notify) {
		    $.ajax({
				method: 'GET',
				url: '/cptour/remove_cpt',
				data: {cpt_id: cpt_id},
				dataType: 'json'
			}).done(function(response){
				console.log(response);
				if (response.success) {
					// $(clicked).closest('tr').remove();
					$('#body-list-cpt').find('tr').each(function(idex, tr){
						var cpt_id = $(tr).data('cpt_id');
						if (response.success.indexOf(cpt_id) != -1) {
							$(tr).fadeOut(400, function(){
								$(this).remove();
							});
						}
					});
				}
			});
		})

	});
	//remove cpt
	$(document).on('click', '.remove_dv', function(){
		var cpt_id = $(this).data('cpt-id');
		if (cpt_id > 0) {
			var clicked = $(this);
			(new PNotify({
			    title: 'Confirmation Needed',
			    text: 'Are you sure?',
			    icon: false,
			    // hide: false,
			    styling: 'bootstrap3',
			    confirm: {
		            confirm: true,
		            buttons: [{
		                text: 'Ok!',
		                addClass: 'confirm-ok',
		             //    click: function(notice) {
			            //     notice.remove();
			            // }
		            }, {
		                text: 'Cancel!',
		                addClass: 'no'
		            }]
		        },
			    buttons: {
			        closer: false,
			        sticker: false
			    },
			    history: {
			        history: false
			    },
			    addclass: 'stack-modal',
			    stack: {
			        'dir1': 'down',
			        'dir2': 'right',
			        'modal': true
			    },
	            after_open: function (notify) {
		            $(".confirm-ok", notify.container).focus();
		        }
			})).get().on('pnotify.confirm', function(notify) {
			    $.ajax({
					method: 'GET',
					url: '/cptour/remove_cpt',
					data: {cpt_id: cpt_id},
					dataType: 'json'
				}).done(function(response){
					console.log(response);
				});
			})
		}
		$(this).closest('.wrap-cpt').fadeOut('slow', function(){
			$(this).remove();
		});
	});
///////////////////////////////////end form nhap dong///////////////////////////////
	$(document).on('focus', '#wrap-ncc .select2, .wrap-dv .select2', function() {
	    $(this).siblings('select').select2('open');
	});

	//set data source venue
	$('#cptour-venue_id').select2({
		placeholder: "Search",
		minimumInputLength: 2,
		dropdownParent: $("#cptModal"),
		ajax: {
		    url: "/cptour/search_ncc",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
				return {
					q: params.term,
					page: params.page || 1
				};
		    },
		    processResults: function (data, params) {
				params.page = params.page || 1;
				return  {
				    results: $.map(data.items, function (obj) {
									obj.id = obj.id;
									obj.text = obj.text || obj.name;
									return obj;
								}),
				    pagination: {
				     	more: (params.page * 20) < data.total_count
				    }
				};
			},
			cache: true
		},
	});
$(document).ready(function(){
////////////////////////////////form nhap nhanh////////////////////////////////////

	$(document).on('change', '#cptour-venue_id', function(){
		venue_id = $(this).val();
		if (venue_id != '') {
			$.ajax({
				url: "/cptour/list_dv",
				type: "GET",
				data: {id_ncc: venue_id},
				dataType: "json",
				success: function(response){
					data_source_dv = $.map(response.dv, function (obj) {
						// obj.name = obj.name.allReplace({'{': '(', '}': ')'});
						obj.id = obj.id;
						obj.text = obj.text || obj.name; // replace name with the property used for the text
						return obj;
					});
					$('#cptourForm .cptour-dv_id').html('');
					$('#cptourForm .cptour-dv_id')
									.append($('<option>', {value: '', text : ''}))
									.select2({
										placeholder: "Select a service",
										data: data_source_dv,
										tags: "true",
										maximumInputLength: 20
									}).on("load", function(e) {
									     // $(this).prop('tabindex',2);
									 }).trigger('load');
					if (response.options) {
						dv_options = response.options;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
			});
		}
	});
	$(document).on('change', '#cptourForm .cptour-dv_id', function(){
		// $('#cptourForm .cptour-qty').focus();
	});
///////////////////////////////////end form nhap nhanh/////////////////////////////

});
function resetVar() {
	id_dv = 0;
	day_use = '';
	venue_id = 0;
	form_status = 'create';
	//reset elements in form
	$(FORM).find('.form-group').each(function(i, item){
		if ($(item).hasClass('has-error')) {
			$(item).removeClass('has-error');
		}
	});
}
/////////////////////////////////format number/////////////////////////////////////
	$(document).on('keydown', '.numberOnly', function(e){

		if(this.selectionStart || this.selectionStart == 0){
			// selectionStart won't work in IE < 9

			var key = e.which;
			var prevDefault = true;

			var thouSep = ",";  // your seperator for thousands
			var deciSep = ".";  // your seperator for decimals
			var deciNumber = 2; // how many numbers after the comma

			var thouReg = new RegExp(thouSep,"g");
			var deciReg = new RegExp(deciSep,"g");

			function spaceCaretPos(val, cPos){
				/// get the right caret position without the spaces

				if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
					cPos = cPos-1;

				if(val.substring(0,cPos).indexOf(thouSep) >= 0){
					cPos = cPos - val.substring(0,cPos).match(thouReg).length;
				}

				return cPos;
			}

			function spaceFormat(val, pos){
				/// add spaces for thousands

				if(val.indexOf(deciSep) >= 0){
					var comPos = val.indexOf(deciSep);
					var int = val.substring(0,comPos);
					var dec = val.substring(comPos);
				} else{
					var int = val;
					var dec = "";
				}
				var ret = [val, pos];

				if(int.length > 3){

					var newInt = "";
					var spaceIndex = int.length;

					while(spaceIndex > 3){
						spaceIndex = spaceIndex - 3;
						newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
						if(pos > spaceIndex) pos++;
					}
					ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
				}
				return ret;
			}

			$(this).on('keyup', function(ev){

				if(ev.which == 8){
					// reformat the thousands after backspace keyup

					var value = this.value;
					var caretPos = this.selectionStart;

					caretPos = spaceCaretPos(value, caretPos);
					value = value.replace(thouReg, '');

					var newValues = spaceFormat(value, caretPos);
					this.value = newValues[0];
					this.selectionStart = newValues[1];
					this.selectionEnd   = newValues[1];
				}
			});

			if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
			   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
				prevDefault = false;

			if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
				e.preventDefault();

				if(!e.altKey && !e.shiftKey && !e.ctrlKey){

					var value = this.value;
					if((key > 95 && key < 106)||(key > 47 && key < 58) ||
						(deciNumber > 0 && (key == 110 || key == 188 || key == 190))){

						var keys = { // reformat the keyCode
							48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
							96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
							110: deciSep, 188: deciSep, 190: deciSep
						};

						var caretPos = this.selectionStart;
						var caretEnd = this.selectionEnd;

						if(caretPos != caretEnd) // remove selected text
							value = value.substring(0,caretPos) + value.substring(caretEnd);

						caretPos = spaceCaretPos(value, caretPos);

						value = value.replace(thouReg, '');

						var before = value.substring(0,caretPos);
						var after = value.substring(caretPos);
						var newPos = caretPos+1;

						if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
							if(before.indexOf(deciSep) >= 0){ newPos--; }
							before = before.replace(deciReg, '');
							after = after.replace(deciReg, '');
						}
						var newValue = before + keys[key] + after;

						if(newValue.substring(0,1) == deciSep){
							newValue = "0"+newValue;
							newPos++;
						}

						while(newValue.length > 1 &&
							newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
							newValue = newValue.substring(1);
						newPos--;
					}

					if(newValue.indexOf(deciSep) >= 0){
						var newLength = newValue.indexOf(deciSep)+deciNumber+1;
						if(newValue.length > newLength){
							newValue = newValue.substring(0,newLength);
						}
					}

					newValues = spaceFormat(newValue, newPos);

					this.value = newValues[0];
					this.selectionStart = newValues[1];
					this.selectionEnd   = newValues[1];
				}
			}
		}

		$(this).on('blur', function(e){

			if(deciNumber > 0){
				var value = this.value;

				var noDec = "";
				for(var i = 0; i < deciNumber; i++)
					noDec += "0";

				if(value == "0"+deciSep+noDec)
					this.value = ""; //<-- put your default value here
				else
					if(value.length > 0){
						if(value.indexOf(deciSep) >= 0){
							var newLength = value.indexOf(deciSep)+deciNumber+1;
							if(value.length < newLength){
								while(value.length < newLength){ value = value+"0"; }
								this.value = value.substring(0,newLength);
							}
						}
						else this.value = value;// + deciSep + noDec;
					}
				}
			});
		}
	});
///////////////////////////////////////////////////////////////////////////////////
function importInputs(){
	//add inputs to form
	var elements_input = $('#wrap-input').find('.wrap-cpt').clone();
	$('#wrap-cpts').append(elements_input);
};
Date.prototype.ddmmyyyy = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return dd+"/"+mm+"/"+yyyy;
};
Date.prototype.yyyymmdd = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return yyyy+"-"+mm+"-"+dd;
};
String.prototype.formatToDate = function() {
	var arr_dt = this.split('/');
	if (arr_dt.length != 3) {
		return false;
	}
	return new Date(arr_dt[2]+'/'+arr_dt[1]+'/'+arr_dt[0]);
}
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
String.prototype.allReplace = function(obj) {
    var retStr = this;
    for (var x in obj) {
        retStr = retStr.replace(new RegExp(x, 'g'), obj[x]);
    }
    return retStr;
};



/* ajax
var gitElement = $("#cptour-venue_id").select2({
    ajax: {
        url: "/cptour/search_ncc",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term,
            page: params.page || 1
          };
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: $.map(data.items, displayItem),
                pagination: {
                    more: (params.page * 20) < data.total_count
                }
            };
        }
    },
    cache: true,
    placeholder: "Search",
    // data: $.map(selected, displayItem),
    escapeMarkup: function (markup) { return markup; },
    templateResult: function(data) {
        return data.html;
    },
    templateSelection: function(repo) {
        return repo.name || repo.text;
    },
    minimumInputLength: 2

});
function displayItem(repo) {
    return {
        id : repo.id,
      text :    repo.name,
      html : '<div style="color:red">bug</div><div><small>This is some small text on a new line</small></div>'
    };
}
 */