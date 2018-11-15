<?php
use yii\helpers\Html;

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
<?php
$this->registerCssFile('/assets/limitless_2_0/css/perfect-scrollbar.css');
$this->registerCss('
    .page-content { overflow: hidden; padding: 0; }
    .wrap-fix-height { position: relative; height: 100%; overflow: hidden;  }
    .content-fixed {height: 100%; overflow-y: auto; overflow-x: hidden;}
    .cat-icon {color: pink;}
    .wrap-actions span:hover{ cursor: pointer; }
    .fa {vertical-align: middle;}
    .wrap-data {padding: 1.25rem; }
    .bg_custom {background-color: #f9f9f9}

    .toggle-sidebars {
        position: fixed;
        top: 11%;
        // right: 0;
        bottom: 0;
        right: -301px;
        width: 300px;
        background: #46759b;
        z-index: 9999;
        min-height: 300px;
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
    }
    // .contents {padding: 20px;}

    a.trigger-toggle {
        position: absolute;
        left: -50px;
        top: 30px;
        background: #46759b;
        color: #fff;
        font-size: 17px;
        padding: 19px;
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
    }
    a.trigger-toggle:hover{
        cursor:pointer
    }
    .toggle-slide {
        right: 0 !important
    }
    .ct_tour {overflow:auto}
    .content-ct_tour tr td {width: auto !important}

');
?>
<?php
$stype_data = [];
$hotel = 'hotel';
$flight = 'flight';
$guide = 'guide';
foreach ($cpts as $cpt){
    if($cpt['stype_cp'] == $hotel || $cpt['stype_cp'] == ''){
        $stype_data[$hotel][] = $cpt;
    }
    if($cpt['stype_cp'] == $flight){
        $stype_data[$flight][] = $cpt;
    }
    if($cpt['stype_cp'] == $guide){
        $stype_data[$guide][] = $cpt;
    }
}
?>
<div class="row w-100 wrap-fix-height">

    <div class="card pr-0 col-12 col-lg-7 col-xl-6 h-100 content-fixed">
        <div class="card-header sticky-top bg-white">
            <p><?= Yii::t('app', 'Add Services Category')?></p>
            <div class="wrap-add-cat">
                <a href="#cat1" class="btn a_cat" data-id="#cat1" data-icon="plane"><i class="fa fa-plane fa-2x"></i> <?= Yii::t('app', 'Air ticket')?></a>
                <a href="#cat2" class="btn a_cat" data-id="#cat2" data-icon="hotel"><i class="fa fa-hotel fa-2x"></i> <?= Yii::t('app', 'Hotel')?></a>
                <a href="#cat3" class="btn a_cat" data-id="#cat3" data-icon="user"><i class="fa fa-user fa-2x"></i> <?= Yii::t('app', 'Guide')?></a>
            </div>
        </div>
        <form>
        <div id="wrap-cat-datas" >
            <?php if(isset($stype_data[$flight])) {?>
            <div id="cat1" class="wrap-data">
                <div class="d-flex align-items-center justify-content-between cat-title">
                    <span class="cat-icon"><i class="fa fa-plane fa-3x"></i></span>
                    <a href="#cat1form" class="btn btn-info add-detail">+ <?= Yii::t('app', 'Add service')?></a>
                </div>
                <div class="wrap-data-items">
                    <div class="data-result table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('app', 'Service')?></th>
                                    <th><?=Yii::t('app', 'Actions')?></th>
                                </tr>
                            </thead>
                            <tbody id="body-list-cat1">
                                <?php foreach ($stype_data['flight'] as $cpt){ ?>
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
                <div class="wrap-form-detail pt-3 pb-3" id="cat1form"></div>
            </div>
            <?php } ?>
            <?php if(isset($stype_data[$hotel])) {?>
            <div id="cat2" class="wrap-data">
                <div class="d-flex align-items-center justify-content-between cat-title">
                    <span class="cat-icon"><i class="fa fa-hotel fa-3x"></i></span>
                    <a href="#cat2form" class="btn btn-info add-detail">+ <?= Yii::t('app', 'Add service')?></a>
                </div>
                <div class="wrap-data-items">
                    <div class="data-result table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('app', 'Service')?></th>
                                    <th><?=Yii::t('app', 'Actions')?></th>
                                </tr>
                            </thead>
                            <tbody id="body-list-cat1">
                                <?php foreach ($stype_data[$hotel] as $cpt){ ?>
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
                <div class="wrap-form-detail pt-3 pb-3" id="cat2form"></div>
            </div>
            <?php } ?>
            <?php if(isset($stype_data[$guide])) {?>
            <div id="cat3" class="wrap-data">
                <div class="d-flex align-items-center justify-content-between cat-title">
                    <span class="cat-icon"><i class="fa fa-user fa-3x"></i></span>
                    <a  class="btn btn-info add-detail">+ <?= Yii::t('app', 'Add service')?></a>
                </div>
                <div class="wrap-data-items">
                    <div class="data-result table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('app', 'Service')?></th>
                                    <th><?=Yii::t('app', 'Actions')?></th>
                                </tr>
                            </thead>
                            <tbody id="body-list-cat1">
                                <?php foreach ($stype_data[$guide] as $cpt){ ?>
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
                <div class="wrap-form-detail pt-3 pb-3" id="cat3form"></div>
            </div>
            <?php } ?>
        </div>

        </form>
        <div class="d-none wrap-tmp-forms">
            <div id="form-cat1">
                <input id="tour-id" class="form-control" name="tour_id" type="hidden" value="<?= $theTour['id']?>">
                <input id="Cpt-id" class="form-control" name="cpt_id" type="hidden" value="">
                <div class="row">
                    <div class="form-group col-md-12 " id="wrap_dv_id">
                        <label> <?= Yii::t('app', 'Service')?> </label>
                        <input type="text" class="form-control" name="services" id="cptour-dv_id" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Type')?></label>
                            <input type='text' class="form-control" name="stype" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Flight number')?></label>
                            <input type='text' class="form-control" name="fly_number" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Provider')?></label>
                            <input type='text' class="form-control" name="provide_name" />
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="cptour-qty"><?= Yii::t('app', 'Date time')?></label>
                            <input type='text' class="form-control" value="4,5 1000-1200" name="dt_from" id='dtp_from' required/>
                            <div class="help-block"></div>
                            <span id="help-block" class="help-block text-danger" style="display: none"><?= Yii::t('app', 'errors format')?> : </span>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label" for="cptour-qty"><?= Yii::t('app', 'Quantity')?></label>
                        <input id="cptour-qty" class="form-control" name="qty" type="text" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label" for="cptour-price"><?= Yii::t('app', 'Price')?></label>
                        <input id="cptour-price" name="price" class="text-right form-control numberOnly" type="text" required>
                        <div class="invalid-feedback"><?= Yii::t('app', 'error')?></div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label" for="cptour-num_day"><?= Yii::t('app', 'Amount')?></label>
                        <input id="cptour-num_day" class="form-control" name="amount" type="text">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label" for="cptour-qty"><?= Yii::t('app', 'Status Book')?></label>
                        <select name="status" class="form-control">
                            <option value="ok"><?= Yii::t('app', 'success')?></option>
                            <option value="closed"><?= Yii::t('app', 'closed')?></option>
                            <option value="open"><?= Yii::t('app', 'drap')?></option>
                        </select>
                    </div>
                </div>
                <input id="stype_data" value="flight" class="form-control" name="stype_data" type="hidden">
            </div>
            <div id="form-cat2">
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
                </div>
                <input id="stype_data" value="hotel" class="form-control" name="stype_datastype_data" type="hidden">
            </div>
            <div id="form-cat3">
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
                </div>
                <input id="stype_data" value="guide" class="form-control" name="stype_data" type="hidden">
            </div>
        </div>
    </div>
    <div class="h-100 card d-none d-md-block col-12 col-lg-5 col-xl-6 content-fixed ct_tour">
        <div class="card-heading sticky-top bg-white">
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
                                    <td class="text-center" width="10">
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
    <div class="position-fixed toggle-sidebars bg-white " style="height: 89%">
        <a class="trigger-toggle bg-white"><i class="fa fa-wpforms"></i></a>
        <div class="content-ct_tour h-100" id="content-ct_tour">
        </div>
    </div>
</div>
<?php
$js = <<<TXT

    var wrapOffset = $(".page-content").offset().top;
    var wrapOffset = $(window).height() - wrapOffset;
    $(".page-content").css('max-height', wrapOffset);

    customView();
    $( window ).resize(function() {
        wrapOffset = $(".page-content").offset().top;
        wrapOffset = $(window).height() - wrapOffset;
        $(".page-content").css('max-height', wrapOffset);

        customView();
    });
    $('.sidebar').addClass('sidebar-fixed');

    var FixedSidebarCustomScroll = function() {
        var _componentPerfectScrollbar = function() {
            if (typeof PerfectScrollbar == 'undefined') {
                console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
                return;
            }
            var ps = new PerfectScrollbar('.sidebar-fixed .sidebar-content', {
                wheelSpeed: 1,
                // wheelPropagation: true
                });
            };
            return {
                init: function() {
                    _componentPerfectScrollbar();
                }
            }
    }();

    var FixedContentCustomScroll = function() {
        var _componentPerfectScrollbar = function() {
            if (typeof PerfectScrollbar == 'undefined') {
                console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
                return;
            }
            var className = '.content-fixed';
            var elements = $(className);
            $.each(elements, function(index, item){
                var ps = new PerfectScrollbar(item, {
                    // wheelSpeed: 2,
                    wheelPropagation: true,
                    minScrollbarLength: 20
                    });
            });
        };
        return {
            init: function() {
                _componentPerfectScrollbar();
            }
        }
    }();


    $(function () {
        FixedSidebarCustomScroll.init();
        // FixedContentCustomScroll.init();
    });

    function customView() {
        if($(window).width() < 1200) {
            $('body').addClass('sidebar-xs');
        }

        if($(window).width() <= 990) {
            $('.toggle-sidebars').show();
            var ct_tour =  $('.ct_tour').clone();
            $(ct_tour).removeClass('card d-none d-md-block col-12 col-md-6 content-fixed');
            $('#content-ct_tour').find('.ct_tour').remove();
            $('#content-ct_tour').append(ct_tour);

        } else {
            $('.toggle-sidebars').hide();
            $('#content-ct_tour').empty();
        }
    }
    $(document).on('click', '.trigger-toggle', function(){
        var clicked = $(this);
        var parent = $(clicked).closest('.toggle-sidebars');
        if($(parent).css('right') == '0px') {
            $(parent).animate({
                right: "-301px"
            });
        } else {
            $(parent).animate({
                right: "0px"
            });
        }
    });


    $('.a_cat').on('click', function(){
        var clicked = $(this);
        var href = clicked.data('id');
        if($(href).length > 0) {
            return true;
        } else {
            var id = href.substr(1);
            var icon = clicked.data('icon');
            var html_tmp = '<div id="'+id+'" class="wrap-data"> <div class="d-flex align-items-center justify-content-between cat-title"> <span class="cat-icon"><i class="fa fa-'+icon+' fa-3x"></i></span> <a class="btn btn-info add-detail" href="#'+ id +'form">+ Add service</a> </div> <div class="wrap-data-items"> </div> <div class="wrap-form-detail pt-3 pb-3" id="'+ id +'form"></div> </div>';
            $('#wrap-cat-datas').append(html_tmp);
            return true;
        }
    });
    $(document).on('click', '.add-detail', function(){
        var clicked = $(this);
        var parent = clicked.closest('.wrap-data');
        var prop_id = parent.prop('id');
        var wrap_form = $(parent).find('.wrap-form-detail');
        // wrap_form.empty();
        $('.wrap-form-detail').empty();
        if ($('#form-'+prop_id).length == 0) {
            console.log('tmplate form not found');
        }
        var form_content = $('#form-'+prop_id).clone();
        $(form_content).find('.row').append('<div class="col-md-12 text-right"> <button type="submit" class="btn btn-primary btn_action_submit">Submit</button> <a class="btn btn-secondary btn_action_cancel" >close</a></div>');
        $(wrap_form).append(form_content.removeAttr('id'));
        $('.bg_custom').removeClass('bg_custom');
        parent.addClass('bg_custom');
        // console.log($(parent).find('.wrap-form-detail').position().top);
        var d = $(parent).find('.wrap-form-detail')
                                .position().top;
        // console.log(d);
        // console.log($(parent).find('.sticky-top').height());
        $(parent).closest('.content-fixed').animate({
            scrollTop: d
        }, 1000);
    });
    $(document).on('click', '.span-edit_cpt', function(){
        var clicked = $(this);
        var tr_clicked = clicked.closest('tr');
        var parent = clicked.closest('.wrap-data');
        $(parent).find('.add-detail').trigger('click');
        var formDetail = $(parent).find('.wrap-form-detail');

        if (clicked.data('cpt-id') != '' && clicked.data('cpt-id') > 0) {
            var cpt_id = clicked.data('cpt-id');
            $(formDetail).find("input[name=cpt_id]").val(cpt_id);
            console.log();
            $.ajax({
                method: 'GET',
                url: '/cptour/get_cpt',
                data: {cpt_id: cpt_id},
                dataType: 'json'
            }).done(function(response){
                if (response.err != undefined) { console.log(response.err); return;
                }
                console.log(response);
            });
        }
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
                    $(clicked).closest('tbody').find('tr').each(function(idex, tr){
                        var cpt_id = $(tr).data('cpt_id');
                        if (response.success.indexOf(cpt_id) != -1) {
                            $(tr).fadeOut(400, function(){
                                $(this).remove();
                            });
                        }
                    });
                }
            });
        });
    $(document).on('click', '.btn_action_cancel', function(){
        $(this).closest('.wrap-form-detail').empty();
    });

    $(document).on('submit', $('form'), function(event)
    {
        event.stopPropagation();
        event.preventDefault();
        FORM = $(event.target);

        var wrap_parent = $('.bg_custom');
        var prop_id = wrap_parent.prop('id');
        console.log(1);

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
                    fill_data(data.cpts, $('#body-list-' + prop_id));
                    wrap_parent.find('.btn_action_cancel').trigger('click');

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
    function fill_data(dataSource, bodyListCpt) {
        bodyListCpt.empty();
        $.each(dataSource, function(i, cpt){
            var tr_html = '<tr class="tr-services" data-cpt_id="'+cpt.id+'"> <td><div class="cpt-name-wrap"> <a class="venue_update"><span class="cpt-name">'+cpt.dv_name+'</span></a> </div></td> <td> <div class="wrap-actions"> <span class="span-edit_cpt" data-cpt-id="'+cpt.id+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <span class="span-remove_cpt" data-cpt-id="'+cpt.id+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span> </div> </td> </tr>';
            bodyListCpt.append(tr_html);
        });
    }
    //dateTime
    var AR_DATE = 'DATE_ARRAY'.split(',');
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
        // $('.wrap_tmp_dv').each(function(i, day){
        //     var self = $(this);
        //     self.empty();
        //     var use_dt = self.closest('.wrapDvs').data("date_use");
        //     console.log(arr_date_converted);
        //     if(arr_date_converted.indexOf(use_dt.trim()) != -1 && dvName != '') {

        //         var html_dv = $('<div class="dv_name">').text(dvName);
        //         self.append(html_dv);
        //     }
        // });
    });
    function format_text(TEXT)
    {
        ///////////////Functions relate]]]]]]]]]]]]]]]]]\
        function format_dt(dateTime)
        {
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
        function regDate(t_date)
        {
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
        function regTime(time)
        {
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
        //////////////end Function///////////////////////
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

TXT;
$js = str_replace('DATE_ARRAY', implode(',', $arr_date), $js);
$this->registerJsFile('/assets/limitless_2_0/js/plugins/ui/perfect_scrollbar.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);

?>