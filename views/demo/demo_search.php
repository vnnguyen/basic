<?php
use yii\helpers\Html;
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
    .wrapItems { padding-left: 15px; font-size: 15px}
    .dv_name {color: #66BB6A;}
    .dv_name:before {content: "* ";}
    .tmp_item .dv_name {color: #EF5350;}
    .tmp_item .dv_name:before {content: "+ ";}
</style>
<div class="col-md-8">
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
                            <div class="wrapItems" data-date_use="<?= Yii::$app->formatter->asDate($dayDate, 'php:Y/m/d')?>">
                                <div class="tmp_item"></div>
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
<div class="col-md-4 card" id="wrap_form">
    <div class="card-body">
        <form>
        <div class="row">
            <div class="form-group col-md-12">
                <label> Venue </label>
                <div id="field_id"  style="border: 1px solid #ddd;"> </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="cptour-qty">Date time</label>
                    <input type='text' class="form-control" value="4,5 1000-1200" name="dt_from" id='dtp_from' required/>
                    <div class="help-block"></div>
                    <span id="help-block" class="help-block text-danger" style="display: none"> errors format: </span>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        </form>
    </div>
</div>
<style>
.ffb-input { height: 38px; width: 100% !important;  padding-left: 15px; }
.wrap_items {padding: 10px 15px;}
.ffb-arrow {display: none}
.ffb .page, .ffb a.page {font-size: 85%; padding: 9px; padding-left: 7px; border: solid 1px #339; background-color: #eef; margin: 2px; width: 40px; height: 40px; display: inline-block; border-radius: 50%; padding-left: 9px;
    background-color: #f9f9f9; border-color: #fff;
}
.ffb .paging {text-align: center;}
#field_id {border-radius: 4px;}
.remove_tmp { float: right; font-size: 21px; height: 20px; width: 20px; line-height: 24px; text-align: center;
}
.remove_tmp:hover {cursor: pointer;}
</style>
<?php
$js = <<<TXT
var AR_DATE = 'DATE_ARRAY';
AR_DATE = AR_DATE.split(',');
var NCC_ID = 0;
var arr_date_converted = [];
var inputValue = '';
var inputId = '';

$(function () {
    var FB = $('#field_id').flexbox('/cptour/search_ncc_fb', {
        width: $('#field_id').width(),
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
        onSelect: function(e) {
            var self = $(this);
            inputValue = self.val();
            inputId = $('#field_id_hidden').val();
            console.log(inputValue);
            $('#field_id_input').hide();
            if ($('#selected_tmp').length == 0) {
                $('#field_id').prepend($('<div id="selected_tmp">'));
            }
            $('#selected_tmp').empty();
            var html_tmp_content = '<div class="w-90 p-1"> <img style="width:25px; height:25px; float:left; border-radius: 50%; margin-right:10px" class="img-circle" src="https://secure.gravatar.com/avatar/679185b8d4c3ad74555f48ca99fa86bf?d=wavatar"><div class="text-capitalize select2_text"> ' + inputValue + ' <span class="remove_tmp"><span aria-hidden="true">&times;</span></span></div>';
            $('#selected_tmp').html(html_tmp_content);
        },
    });
    $(document).on('click', '.remove_tmp', function(){
        $('#selected_tmp').remove();
        $('#field_id_hidden').val('');
        $('#field_id_input').val('').show().focus();
        inputValue = '';
        inputId = '';
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
    $(document).on('blur', '#field_id_input', function(){
        inputValue = $(this).val();
        console.log(inputValue);
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
        $('.tmp_item').each(function(i, day){
            var self = $(this);
            self.empty();
            var use_dt = self.closest('.wrapItems').data("date_use");
            console.log(arr_date_converted);
            if(arr_date_converted.indexOf(use_dt.trim()) != -1 && inputValue != '') {

                var html_dv = $('<div class="dv_name">').text(inputValue);
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