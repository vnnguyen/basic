<?php
use yii\helpers\Html;
// use yii\widgets\ActiveForm;
$this->registerCssFile('/FlexBox/css/jquery.flexbox.css');
?>
<?php
$dayIdList = explode(',', $theTour['day_ids']);

$arr_date = [];
$cnt = 0;
foreach ($dayIdList as $dayId) {
    foreach ($theTour['days'] as $day) {
        if ($dayId == $day['id']) {

            $arr_date[] = date('Y-m-d', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
            $cnt ++;
        }
    }
}

?>
<style>
    .wrapDvs { padding-left: 15px; font-size: 15px}
    .dv_name {color: #66BB6A;}
    .dv_name:before {content: "* ";}
    .wrap_tmp_dv .dv_name {color: #EF5350;}
    .wrap_tmp_dv .dv_name:before {content: "+ ";}
</style>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Chương trình tour</h6>
        </div>
        <div class="table-responsive">
            <table id="tblCurrentProg" class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="10" class="text-center"></th>
                        <th class="no-padding-left">
                            Activity
                            (<a href="#" class="toggle-day-contents">Ẩn/hiện mọi ngày</a>)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $cnt = 0;
                    foreach ($dayIdList as $dayId) {
                        foreach ($theTour['days'] as $day) {
                            if ($dayId == $day['id']) {
                                $dayDate = date('Y-m-d', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
                                $cnt ++;
                    ?>
                    <tr class="tr-day" data-id="<?= $day['id'] ?>" id="ngay_<?= $day['id'] ?>">
                        <td class="text-center" width="20">
                            <span class="text-muted"><?= $cnt ?></span>
                        </td>
                        <td class="no-padding-left">
                            <div class="day-actions text-nowrap text-right pull-right position-right">
                            </div>
                            <span class="day-date"><?= Yii::$app->formatter->asDate($dayDate, 'php:j/n/Y D') ?></span>
                            <a class="day-name" href="/days/r/<?= $day['id'] ?>"><?= $day['name'] == '' ? '(no name)' : $day['name'] ?></a>
                            <em class="day-meals text-nowrap"><?= $day['meals'] ?></em>

                            <?php
                            $use_dt = Yii::$app->formatter->asDate($dayDate, 'php:j/n/Y');
                            ?>
                            <div class="wrapDvs" data-date_use="<?= Yii::$app->formatter->asDate($dayDate, 'php:Y/m/d')?>">
                            <?php foreach ($cpts as $cpt){
                                if(strpos($cpt['use_dt'], $use_dt) !== false) {?>
                                <div class="dv_name" id="cpt<?=$cpt['id']?>" data-cpt_id="<?= $cpt['id']?>"> <?= $cpt['dv_name']?>
                                </div>
                                <?php }
                            } ?>

                                <div class="wrap_tmp_dv"></div>
                            </div>
                            <div class="day-content mt-20" style="display:none;">
                                <p>
                                    <span class="day-guides"><?= $day['guides'] == '' ? '' : '<i class="fa fa-user"></i> '.$day['guides'] ?></span>
                                    <span class="day-transport"><?= $day['transport'] == '' ? '' : '<i class="fa fa-car"></i> '.$day['transport']?></span>
                                </p>
                                <div class="day-body" id="day-body-<?= $day['id'] ?>">
                                    <?
                                    if (substr($day['body'], 0, 1) == '<') {
                                        echo $day['body'];
                                    } else {
                        //echo $parser->parse($day['body']);
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card">
        <div id="cp_tour">
            <div class="" id="cp_table">
                <div class="col-md-12 row">
                    <div class="data-result table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body-list-cpt">
                                <?php foreach ($cpts as $cpt){ ?>
                                    <tr class="tr-services" data-cpt_id="<?= $cpt->id?>">
                                        <td><div class="cpt-name-wrap">
                                            <a class="venue_update"><span class="cpt-name"><?= ($cpt->dv)? $cpt->dv->name: $cpt->dv_name?></span></a>
                                        </div></td>
                                        <td>
                                            <div class="wrap-actions">
                                                <!-- <span class="span-add_cpt"><i class="fa fa-plus" aria-hidden="true"></i></span> -->
                                                <span class="span-edit_cpt" data-cpt-id="<?= $cpt->id?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                                <span class="span-remove_cpt" data-cpt-id="<?= $cpt->id?>"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .wrap-actions span:hover{ cursor: pointer; }
</style>
<div class="col-md-4 card" id="wrap-cptForm">
    <div class="card-body">
        <form>
        <input id="tour-id" class="form-control" name="tour_id" type="hidden" value="<?= $theTour['id']?>">
        <input id="Cpt-id" class="form-control" name="cpt_id" type="hidden" value="">
        <div class="row">
            <div class="form-group col-md-12">
                <label> Venue </label>
                <div id="cptour-ncc_id"  style="border: 1px solid #ddd;"> </div>
            </div>
            <div class="form-group col-md-8 " id="wrap_dv_id">
                <label> Service </label>
                <input type="text" class="form-control" name="services" id="cptour-dv_id" required>
            </div>
            <!-- <input id="cptour-id" class="form-control" name="CpTourid" type="hidden" value=""> -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Style</label>
                    <input type='text' class="form-control" name="style" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="cptour-qty">Date time</label>
                    <input type='text' class="form-control" value="4,5 1000-1200" name="dt_from" id='dtp_from' required/>
                    <div class="help-block"></div>
                    <span id="help-block" class="help-block text-danger" style="display: none"> errors format: </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label" for="cptour-qty">Quantity</label>
                <input id="cptour-qty" class="form-control" name="qty" type="text" required>
            </div>
            <div class="form-group col-md-6">
                <label class="control-label" for="cptour-price">Price</label>
                <input id="cptour-price" name="price" class="text-right form-control numberOnly" type="text" required>
                <div class="invalid-feedback">Example invalid feedback text</div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Currency</label>
                    <select id="cptour-currency" class="form-control" name="currency">
                        <option value="VND">VND</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label" for="cptour-num_day">Amount</label>
                <input id="cptour-num_day" class="form-control" name="amount" type="text">
            </div>
            <div class="col-md-12 text-right">
                <?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?>
                <?=Html::a(Yii::t('mn', 'reset'), '', ['class' => 'btn btn-secondary']); ?>
            </div>
        </div>
        </form>
    </div>
</div>
<style>
/*#cptour-ncc_id {padding: 0;}*/
.ffb-input { height: 38px; width: 100% !important;  padding-left: 15px; }
.wrap_items {padding: 10px 15px;}
.ffb-arrow {display: none}
.ffb .page, .ffb a.page {font-size: 85%; padding: 9px; padding-left: 7px; border: solid 1px #339; background-color: #eef; margin: 2px; width: 40px; height: 40px; display: inline-block; border-radius: 50%; padding-left: 9px;
    background-color: #f9f9f9; border-color: #fff;
}
.ffb .paging {text-align: center;}
</style>
<?php
$js = <<<TXT
var AR_DATE = 'DATE_ARRAY';
AR_DATE = AR_DATE.split(',');
var ARR_FORM_CPT = [];
var NCC_ID = 0;
var arr_date_converted = [];
var dvName = [];
var divWrapDV = '';

$(function () {
    $(document).on('blur', '#wrap-cptForm input', function(){
    ARR_FORM_CPT[$(this).prop('name')] = $(this).val();
    });
    var FB = $('#cptour-ncc_id').flexbox('/cptour/search_ncc_fb', {
        width: $('#cptour-ncc_id').width(),
        minChars: 2,
        selectFirstMatch: false,
        contentClass: 'wrap_items',
        queryDelay: 300,
        resultTemplate: '<div class="w-75 p-1"><img style="width:48px; height:48px; float:left; border-radius: 50%; margin-right:10px" class="img-circle" src="{image}"><div class="text-capitalize select2_text">{name}</div><span class="other_info">detail</span></div>',
        paging: {
            style: 'links',             // or 'links'
            pageSize: 5,               // acts as a threshold.  if <= pageSize results, paging doesn't appear
            showSummary: true,          // whether to show 'displaying 1-10 of 200 results' text
            maxPageLinks: 3             // used only if style is 'links'
            },
        onSelect: function() {
            NCC_ID = 0;
            NCC_ID = $('input[name=cptour-ncc_id]').val();
            if (NCC_ID > 0) {
                $.ajax({
                    url: "/cptour/list_dv",
                    type: "GET",
                    data: {id_ncc: NCC_ID},
                    dataType: "json",
                    success: function(response){
                        data_source_dv = $.map(response.dv, function (obj) {
                            obj.id = obj.id;
                            obj.text = obj.text || obj.name; // replace name with the property used for the text
                            return obj;
                        });
                        $('#cptour-dv_id').html('');
                        if(data_source_dv.length == 0) {
                            if ($('#cptour-dv_id').hasClass("select2-hidden-accessible")) {
                                $('#cptour-dv_id').select2('destroy');
                            }
                            console.log("service null");
                            $('#cptour-dv_id').focus();
                            return false;
                        }
                        $('#cptour-dv_id').select2({
                            placeholder: "Select service",
                            data: data_source_dv,
                            tags: "true",
                            maximumInputLength: 20
                        }).on('select2:select', function(e){
                            console.log($(this).val());
                            ARR_FORM_CPT['services'] = $(this).val();
                        });
                        $('#cptour-dv_id').select2('open');

                    },
                    error: function(xhr, ajaxOptions, thrownError) { alert('No response from server');
                    }
                });
            }
        },
    });
    $(document).on('blur', '#cptour-ncc_id_input', function(e) {
        if(isNaN($('input[name=cptour-ncc_id]').val()) || $('input[name=cptour-ncc_id]').val() == ''){
            if ($('#cptour-dv_id').hasClass("select2-hidden-accessible")){
                $('#cptour-dv_id').select2('destroy');
            }
        } else {
            if ($('#cptour-dv_id').hasClass("select2-hidden-accessible")){
                $('#wrap_dv_id .select2').trigger('focus');
            } else {
                $('#cptour-dv_id').focus();
            }
        }
    });
    $(document).on('focus', '#wrap_dv_id .select2', function() {
        $(this).siblings('select').select2('open');
    });
    $('a.toggle-day-contents').on('click', function(){
        if ($('#tblCurrentProg .day-content:visible').length > 0){
            $('.day-content').hide();
        } else {
            $('#tblCurrentProg .day-content').toggle();
        }
        return false;
    });
    $('#tblCurrentProg').on('click', '.day-name', function(){
        $(this).closest('td').find('.day-content').toggle();
        return false;
    });
    $('#cptour-dv_id').on('blur', function(){
        dvName = $(this).val();
    });


    // var forms = document.getElementsByClassName('needs-validation');
    // // Loop over them and prevent submission
    // var validation = Array.prototype.filter.call(forms, function(form) {
    //   form.addEventListener('submit', function(event) {
    //     if (form.checkValidity() === false) {
    //       event.preventDefault();
    //       event.stopPropagation();
    //     }
    //     form.classList.add('was-validated');
    //   }, false);
    // });
    function fill_data(dataSource) {
        var wrap_dvs = $('.wrapDvs'),
            bodyListCpt = $('#body-list-cpt');
        $('form').find('input[type="text"]').val('');
        $('form').find('#cptour-ncc_id_input').val('');
        $('form').find('#Cpt-id').val('');
        wrap_dvs.find('.dv_name').remove();
        wrap_dvs.find('.wrap_tmp_dv').empty();
        bodyListCpt.empty();
        $.each(dataSource, function(i, cpt){
            var tr_html = '<tr class="tr-services" data-cpt_id="'+cpt.id+'"> <td><div class="cpt-name-wrap"> <a class="venue_update"><span class="cpt-name">'+cpt.dv_name+'</span></a> </div></td> <td> <div class="wrap-actions"> <span class="span-edit_cpt" data-cpt-id="'+cpt.id+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <span class="span-remove_cpt" data-cpt-id="'+cpt.id+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span> </div> </td> </tr>';
            bodyListCpt.append(tr_html);
            $.each(wrap_dvs, function(i, item){
                var data_dt = $(item).data("date_use");
                var wrap_tmp_dv = $(item).find('.wrap_tmp_dv');
                if(cpt.use_dt.indexOf(new Date(data_dt).dmyyyy()) != -1) {
                    $('<div class="dv_name" id="cpt'+cpt.id+'" data-cpt_id="'+cpt.id+'">').append(cpt.dv_name).insertBefore(wrap_tmp_dv);
                }
            });
        });
    }

    $('form').on('submit', function(event)
    {
        event.stopPropagation();
        event.preventDefault();
        FORM = $(event.target);

        // Serialize the form data
        var formData = FORM.serialize();

        $.ajax({
            url: '/cptour/cpt_ajax',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.cpts);
                    fill_data(data.cpts);
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS_SUBMIT: ' + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS_SUBMIT_FORM: ' + textStatus);
            }
        });
    });

    $(document).on('click', '.span-remove_cpt', function(){
        var clicked = $(this);
        var cpt_id = clicked.data('cpt-id');
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
                            // $('#cpt'+cpt_id).remove();
                        });
                    }
                });
            }
        });
    });
    $(document).on('click', '.span-add_cpt, .span-edit_cpt', function(){
        var clicked = $(this);
        var tr_clicked = clicked.closest('tr');
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
                var cpt = response.cpt;
                var venue = response.venue;
                var dvs = response.dvs;
                // console.log(response);
                // return false;
                $('#cptour-ncc_id_input').val(venue.name);

                var data_dv = $.map(dvs, function (obj) {
                        // obj.name = obj.name.allReplace({'{': '(', '}': ')'});
                        obj.id = obj.id;
                        obj.text = obj.text || obj.name; // replace name with the property used for the text
                        return obj;
                    });
                console.log(cpt);
                $("input[name=cpt_id]").val(cpt.id);
                $("input[name=services]").val((cpt.dv_id <= 0)? cpt.dv_name : dv_id);
                $("input[name=style]").val(cpt.style);
                $("input[name=dt_from]").val(cpt.use_dt);
                $("input[name=qty]").val(cpt.qty);
                $("input[name=price]").val(cpt.price);
                $("input[name=currency]").val(cpt.currency);
                $("input[name=amount]").val(cpt.qty*cpt.price);
                // $.each(cpts, function(i, cpt){
                //     var elements_input = $('#wrap-input').find('.wrap-cpt').clone();
                //     $('#wrap-cpts').append(elements_input);
                //     $(elements_input).find('.cptour-dv_id').html('')
                //                                             .append($('<option>', {value: '', text : ''}))
                //                                             .select2({
                //                                                 placeholder: "Select a service",
                //                                                 data: data_dv,
                //                                                 tags: "true",
                //                                                 maximumInputLength: 20
                //                                             }).val(cpt.dv_id)
                //                                             .trigger("change");
                //     $(elements_input).find('.cptour-use_day').val(dt.toString());
                //     $(elements_input).find('.cptour-id').val(cpt['id']);
                //     $(elements_input).find('.cptour-qty').val(cpt['qty']);
                //     $(elements_input).find('.cptour-num_day').val(cpt['num_day']);
                //     $(elements_input).find('.cptour-plusminus').val(cpt['plusminus']);
                //     $(elements_input).find('.cptour-use_day').val(new Date(cpt['use_day']).yyyymmdd());
                //     $(elements_input).find('.cptour-price').val(cpt['price']);
                //     $(elements_input).find('.cptour-currency').val(cpt['currency']);
                //     $(elements_input).find('.remove_dv').data('cpt-id', cpt.id);
                // });
                // $('#cptourForm #cptour-dv_id').val(cpt['dv_id']).trigger('change');
            });
        }
    });
    var ERRORS = [];
    $(document).on('blur', '#dtp_from', function(){
        var dt_text = $(this).val();
        if (dt_text != '') {
            arr_date_converted = [];
            ERRORS = [];
            var arr = format_text(dt_text);
            $(this).val(arr.join(','));
            var wrap_errors = $('#help-block');
            if(ERRORS.length > 0) {

                wrap_errors.empty().text('errors format: ');
                $.each(ERRORS, function(i, error){
                    var html_err = $('<span class="err_format" style="background:#f3f3f3; padding: 2px 6px; margin-right: 5px; font-size: 15px">').text(error);
                    wrap_errors.append(html_err);
                });
                wrap_errors.show();
            } else {
                wrap_errors.hide();
            }
        }
        $('.wrap_tmp_dv').each(function(i, day){
            var self = $(this);
            self.empty();
            var use_dt = self.closest('.wrapDvs').data("date_use");
            console.log(arr_date_converted);
            if(arr_date_converted.indexOf(use_dt.trim()) != -1 && dvName != '') {

                var html_dv = $('<div class="dv_name">').text(dvName);
                self.append(html_dv);
            }
        });
    });
    function format_text(TEXT)
    {
        var arrDay = [];
        var arrDay1 = [];
        $.each(TEXT.split(','), function(index, dt){
            if(dt.trim() != '') {
                arrDay[index] = dt.trim();
            }
        });
        $.each(arrDay, function(index, dt){
            var convert_date = '';
            if (dt.indexOf('-') != -1) {
                var range_dt = dt.split('-'); //256 0800 - 266 20H30
                convert_date = format_dt(range_dt[0]) + ' - ' + format_dt(range_dt[1]);
            } else {
                convert_date = format_dt(dt);
            }
            arrDay1.push(convert_date);
        });
        return arrDay1;
    }
    function format_dt(dateTime){
        dateTime = dateTime.trim();
        var str = dateTime.replace(/\s{2,}/gm, ' ');
        var cv_dt = '';
        var regex_dt = /^([1-9]|[12][0-9]|3[01])\/([1-9]|1[0-2])\/(\d{4})$/g;
        if(dateTime.indexOf(' ') != -1){ //256 0800 || 266 20H30

            var arr_date_time = str.split(' ');
            var text_date = regDate(arr_date_time[0]);
            if(!regex_dt.test(text_date)) {
                 ERRORS.push(text_date);
            }
            var text_time = regTime(arr_date_time[1]);
            if(!checkTime(text_time)) {
                ERRORS.push(text_time);
            }

            cv_dt = text_date + ' ' + text_time;
        } else {
            cv_dt = regDate(dateTime);
            if(!regex_dt.test(cv_dt)) {
                var text_time = regTime(dateTime);
                if(!checkTime(text_time)) {
                    ERRORS.push(text_time);
                } else {
                    cv_dt = text_time;
                }
            }

        }
        return cv_dt;
    }
    function regDate(t_date){
        var date = new Date();
        if(/^([1-9]|[12][0-9]|3[01])\/([1-9]|1[0-2])\/(\d{4})$/g.test(t_date)) {
            var dt = t_date.formatToYYYYMMDD();
            arr_date_converted.push(dt);
            return new Date(dt).dmyyyy();
        } else if(/^(\d{8})$/g.test(t_date)) {
            var dt_text = t_date.substr(0, 2) +'/'+ t_date.substr(2, 2) + '/' + t_date.substr(4);
            arr_date_converted.push(dt_text.formatToYYYYMMDD());
            return new Date(dt_text).dmyyyy();
        } else if(/^(\d{4})$/g.test(t_date)) {
            var valid = false;
            var cv_dt = t_date;
            $.each(AR_DATE, function(index, d_text){
                d_text = d_text.replace(/\s+/g, '');
                var ar_d = d_text.split('-');
                var ar_d_tmp = [ar_d[0], t_date.substr(2), t_date.substr(0, 2)];

                if(ar_d_tmp.join('-').formatToYYYYMMDD() == d_text.formatToYYYYMMDD()) {
                    valid = true;
                    cv_dt = new Date(d_text.formatToYYYYMMDD()).dmyyyy();
                }
                if(valid) return;
            });
            if ( !valid ) {
                return t_date;
            } else {
                return cv_dt;
            }
        } else if(/^(\d{3})$/g.test(t_date)) {
            var valid = false;
            var cv_dt = t_date;
            $.each(AR_DATE, function(index, d_text){
                d_text = d_text.replace(/\s+/g, '');
                var ar_d = d_text.split('-');
                var ar_d_tmp = [ar_d[0], t_date.substr(1), t_date.substr(0, 1)];

                if(ar_d_tmp.join('-').formatToYYYYMMDD() == d_text.formatToYYYYMMDD()) {
                    valid = true;
                    cv_dt = new Date(d_text.formatToYYYYMMDD()).dmyyyy();
                }
                if(valid) return;
            });
            if ( !valid ) {
                $.each(AR_DATE, function(index, d_text){
                    d_text = d_text.replace(/\s+/g, '');
                    var ar_d = d_text.split('-');
                    var ar_d_tmp = [ar_d[0], t_date.substr(2), t_date.substr(0, 2)];;
                    if(ar_d_tmp.join('-').formatToYYYYMMDD() == d_text.formatToYYYYMMDD()) {
                        valid = true;
                        cv_dt = new Date(d_text.formatToYYYYMMDD()).dmyyyy();
                    }
                    if(valid) return;
                });
                if ( !valid ) {
                    return t_date;
                }
            }
            return cv_dt;
        } else if(/^(\d{1,2})$/g.test(t_date)) {
            if(AR_DATE[t_date - 1] !== undefined) {
                var dt_text = AR_DATE[t_date - 1].formatToYYYYMMDD();
                arr_date_converted.push(dt_text);
                return new Date(dt_text).dmyyyy();
            } else {
                if(t_date.length == 2) {
                    var valid = false;
                    var cv_dt = t_date;
                    $.each(AR_DATE, function(index, d_text){
                        d_text = d_text.replace(/\s+/g, '');
                        var ar_d = d_text.split('-');
                        var ar_d_tmp = [ar_d[0], t_date.substr(1), t_date.substr(0, 1)];
                        if(ar_d_tmp.join('-').formatToYYYYMMDD() == d_text.formatToYYYYMMDD()) {
                            valid = true;
                            cv_dt = new Date(d_text.formatToYYYYMMDD()).dmyyyy();
                        }
                        if(valid) return;
                    });
                    if ( !valid ) {
                        $.each(AR_DATE, function(index, d_text){
                            d_text = d_text.replace(/\s+/g, '');
                            var ar_d = d_text.split('-');
                            var ar_d_tmp = [ar_d[0], ar_d[1], t_date.substr(0)];
                            if(ar_d_tmp.join('-').formatToYYYYMMDD() == d_text.formatToYYYYMMDD()) {
                                valid = true;
                                cv_dt = new Date(d_text.formatToYYYYMMDD()).dmyyyy();
                            }
                            if(valid) return;
                        });
                        if ( !valid ) {
                            return t_date;
                        }
                    }
                    return cv_dt;


                } else {
                    var cv_dt = t_date;
                    var valid = false;
                    $.each(AR_DATE, function(index, d_text){
                        d_text = d_text.replace(/\s+/g, '');
                        var ar_d = d_text.split('-');
                        var ar_d_tmp = [ar_d[0], ar_d[1], t_date.substr(0)];
                        if(ar_d_tmp.join('-').formatToYYYYMMDD() == d_text.formatToYYYYMMDD()) {
                            valid = true;
                            cv_dt = new Date(ar_d_tmp.formatToYYYYMMDD()).dmyyyy();
                        }
                        if(valid) return;
                    });
                    return cv_dt;
                }

                // arr_date_converted.push(dt_text.formatToYYYYMMDD());
                return new Date(dt_text).dmyyyy();
            }
        } else {
            return t_date;
        }
    }
    function regTime(time){
        time = time.trim().toLowerCase().replace(/h/g, ':');
        if(/^(([01][0-9])|(2[0-3])):[0-5][0-9]$/.test(time)) {
            return time;
        } else if (/^(\d{4})$/g.test(time)) {
            return time.substr(0, 2) + ':' + time.substr(2, 2);
        } else  {
            return time;
        }
    }
    function checkTime(time)
    {
        var errorMsg = "";

        // regular expression to match required time format
        re = /^(\d{1,2}):(\d{2})(:00)?([ap]m)?$/;

        if(time.value != '') {
          if(regs = time.match(re)) {
            if(regs[4]) {
              // 12-hour time format with am/pm
              if(regs[1] < 1 || regs[1] > 12) {
                return false;
              }
            } else {
              // 24-hour time format
              if(regs[1] > 23) {
                return false;
              }
            }
            if(!errorMsg && regs[2] > 59) {
              return false;
            }
          } else {
            return false;
          }
        }

        return true;
    }
    String.prototype.formatToYYYYMMDD = function() {
        var text = this.replace(/-/g, '\/');
        var arr_dt = text.split('/');

        if (arr_dt.length != 3) {
            // console.log(arr_dt);
            return false;
        }
        if (arr_dt[2].length == 4) {
            var dt = new Date(arr_dt[2]+'/'+arr_dt[1]+'/'+arr_dt[0]);
        } else {
            var dt = new Date(arr_dt[0]+'/'+arr_dt[1]+'/'+arr_dt[2]);
        }
        var yyyy = dt.getFullYear();
        var mm = dt.getMonth() < 9 ? "0" + (dt.getMonth() + 1) : (dt.getMonth() + 1); // getMonth() is zero-based
        var dd  = dt.getDate() < 10 ? "0" + dt.getDate() : dt.getDate();
        return yyyy+"/"+mm+"/"+dd;

    }
    Date.prototype.dmyyyy = function() {
        var yyyy = this.getFullYear();
        var mm = (this.getMonth() + 1); // getMonth() is zero-based
        var dd  = this.getDate();
        return dd+"/"+mm+"/"+yyyy;
    };


});



TXT;
$this->registerJsFile('/FlexBox/js/jquery.flexbox.js', ['depends'=>'app\assets\MainAsset']);
$js = str_replace('DATE_ARRAY', implode(',', $arr_date), $js);
$this->registerJs($js);
?>