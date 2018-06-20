<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\CpTour */
/* @var $form yii\widgets\ActiveForm */
$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.1/jquery-confirm.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.2.1/pnotify.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.2.1/pnotify.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCss('
    .select2-selection--single .select2-selection__arrow::after {right: 5px;}
    .select2-container .select2-selection--single{height:36px}
    .select2{ width: 100% };
    /* Custom styled notice CSS */
    .ui-pnotify-container {
        background-color: #fff !important;
        background-image: none !important;
        border: 1px solid #ccc !important;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }
    .tooltip_span::before{content: "* "}

    .modal-header .close { top: 10%}
    .modal-dialog{
          overflow-y: initial !important
    }
    .modal-body{
      max-height: 750px;
      overflow-y: auto;
    }
    .wrap-actions span { cursor: pointer}
    #wrap-cpts{ display:inline-block;}
    // .wrap-cpt { display:inline-block; border: 1px solid #cdcdcd; padding: 3px; border-radius: 3px}
    .modal-content {background-color: #f3f3f3;}
    // .wrap-ct {max-height: 750px; overflow-y: auto;}

    ');
$this->registerJsFile('http://malsup.github.com/jquery.form.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('/css/formValidation.min.css');
$this->registerJsFile('/js/formValidation.min.js', ['depends' => 'yii\web\JqueryAsset']);
$status_pre_booking = ['not pre booking' => 'not pre booking', 'pre booking' => 'pre booking'];
$options_data = [];
?>
<div class="" id="cp_tour" >
    <div class="" id="cp_table">
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                <li class="active"><a href="#justified-badges-tab1" data-toggle="tab"><i class="fa fa-home"></i> Hotel</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="justified-badges-tab1">
                    <div class="col-md-12 row">
                        <div class="data-result table-responsive">
                            <table class="table table-framed table-xxs table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th width="100">Date</th>
                                        <th>Service</th>
                                        <th>Quality</th>
                                        <th>No days</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="body-list-cpt">
                                    <?
                                    $allDays = []; // Tat ca cac ngay co dich vu, hoac nam trong chuong trinh tour

                                    foreach ($cpts as $cpt) {
                                        $allDays[$cpt['use_day']] = '';
                                    }

                                    $cnt = 0;
                                    $dayIdList = explode(',', $theTour['day_ids']);
                                    foreach ($dayIdList as $di) {
                                        foreach ($theTour['days'] as $ng) {
                                            if ($ng['id'] == $di) {
                                                $cnt ++;
                                                $ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
                                                $allDays[$ngay] = $ng;
                                            }
                                        }
                                    }

                                    ksort($allDays);
                                    foreach ($allDays as $k=>$v) { ?>
                                    <tr id="day<?= $k ?>" class="info" data-dt="<?= $k ?>">
                                        <td colspan="5">
                                            <?= Yii::$app->formatter->asDate($k, 'php:j/n/Y l') ?>
                                            <? if ($v == '') { ?>
                                            Ngày này không nằm trong chương trình tour chính thức
                                            <? } else { ?>
                                            <? echo Html::a($v['name'].' ('.$v['meals'].')', '#tours/ngaytour/'.SEG3, ['class'=>"fw-b", 'title'=>str_replace('"', '`', $v['body'])]) ?>
                                            <? } ?>
                                        </td>
                                        <td><?= in_array(USER_ID, [1, 34718]) ? '<a class="dvt-c" href="#cpt-c" day="'.$k.'"><span class="span-add_cpt">+New</span></a>' : '' ?></td>
                                    </tr>
                                    <?php foreach ($cpts as $cpt) {?>
                                    <?php if (strtotime($k) == strtotime($cpt['use_day'])){ ?>
                                    <tr class="tr-services" data-cpt_id="<?= $cpt->id?>" data-dt="<?= $k ?>">
                                        <td colspan="2"><div class="cpt-name-wrap">
                                            <a class="venue_update"><span class="cpt-name"><?= $cpt->dv->name?></span> - <?= $cpt->venue->name?></a>
                                        </div></td>
                                        <td><?= $cpt->qty?></td>
                                        <td><?= $cpt->num_day?></td>
                                        <td><?= $cpt->price?> <span class="text-muted"><?= $cpt->currency?></span></td>
                                        <td>
                                            <div class="wrap-actions">
                                                <span class="span-edit_cpt" data-cpt-id="<?= $cpt->id?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                                <span class="span-remove_cpt" data-cpt-id="<?= $cpt->id?>"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                    <?} // foreach $allDays ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="justified-badges-tab2">
                    Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid laeggin.
                </div>

                <div class="tab-pane" id="justified-badges-tab3">
                    DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
                </div>

                <div class="tab-pane" id="justified-badges-tab4">
                    Aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthet.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="cptModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;">
        <form id="cptourForm" action="" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div id="wrap-ncc" class="col-md-12" style="z-index: 9999999">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('cpt', 'Provider')?></label>
                            <select id="cptour-venue_id" class="form-control cptour-venue_id" name="venue_id"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6" id="wrap-cptForm">
                        <input name="tour_id" value="<?= $theTour['id']?>" type="hidden">

                        <div id="wrap-cpts"></div>
                        <div class="actions">
                            <span class="btn btn-primary save_btn"><?= Yii::t('cpt', 'Save')?></span>
                            <!-- <span class="btn btn-default" id="cancel_btn"><?= Yii::t('cpt', 'Cancel')?></span> -->
                            <span class="btn btn-success pull-right add_more">+ <?= Yii::t('cpt', 'Add more cpt')?></span>
                        </div>
                    </div>

                    <div class="col-md-6 wrap-ct">
                        <div id="divsortable">
                            <table id="tblCurrentProg" class="table table-striped table-condensed">
                                <tbody id="sortable" style="overflow:auto" class="ui-sortable">
                                    <?foreach ($allDays as $k=>$v) { ?>
                                    <tr class="tr-day">
                                        <td>
                                            <span class="day-date"><?= Yii::$app->formatter->asDate($k, 'php:j/n/Y l') ?></span>
                                            <a class="day-name"><?=$v['name']?></a>
                                            <em class="day-meals text-nowrap"><?= $v['meals']?></em>
                                            <div class="day-content mt-20 collapse">
                                                <p>
                                                    <span class="day-guides"><i class="fa fa-user"></i> Chauffeur uniquement</span>
                                                    <span class="day-transport"></span>
                                                </p>
                                                <div class="day-body"><?= str_replace('"', '`', $v['body'])?></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?}?>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="hidden" id="wrap-input">
    <div class="wrap-cpt">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">CPT</h6>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="close" class="remove_dv"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body row">
                <input class="form-control cptour-id" name="cpt_id[]" value="" type="hidden">

                <div class="col-md-12 wrap-use_day">
                    <div class="form-group">
                        <label class="control-label"><?= Yii::t('cpt', 'Use Day')?></label>
                        <select class="form-control cptour-use_day" name="use_day[]">
                            <?foreach ($days as $dt => $d) {?>
                            <option value="<?= $dt?>"><?= $d?></option>
                            <?}?>
                        </select>
                    </div>
                </div>

                <div class="col-md-12 wrap-dv">
                    <div class="form-group">
                        <label class="control-label" for="cptour-dv_id"><?= Yii::t('cpt', 'Service')?></label>
                        <select class="form-control cptour-dv_id" name="dv_id[]"></select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group wrap-qty">
                        <label class="control-label"><?= Yii::t('cpt', 'Quantity')?></label>
                        <input class="form-control cptour-qty" name="qty[]" type="text">
                    </div>
                </div>

                <div class="col-md-4 wrap-num_day">
                    <div class="form-group">
                        <label class="control-label"><?= Yii::t('cpt', 'Number Day')?></label>
                        <input class="form-control cptour-num_day" name="num_day[]" type="text">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group field-cptour-plusminus">
                        <label class="control-label"><?= Yii::t('cpt', 'Plusminus')?></label>
                        <select class="form-control cptour-plusminus" name="plusminus[]">
                            <option value="plus">+</option>
                            <option value="minus">-</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-8 wrap-price">
                    <div class="form-group">
                        <label class="control-label"><?= Yii::t('cpt', 'Price')?></label>
                        <input class="text-right form-control numberOnly cptour-price" name="price[]" type="text">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label"><?= Yii::t('cpt', 'Currency')?></label>
                        <select class="form-control cptour-currency" name="currency[]">
                            <option value="VND">VND</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var weekdays = new Array(7);
    weekdays[0] = "<?= Yii::t('cpt', 'Sunday')?>";
    weekdays[1] = "<?= Yii::t('cpt', 'Monday')?>";
    weekdays[2] = "<?= Yii::t('cpt', 'Tuesday')?>";
    weekdays[3] = "<?= Yii::t('cpt', 'Wednesday')?>";
    weekdays[4] = "<?= Yii::t('cpt', 'Thursday')?>";
    weekdays[5] = "<?= Yii::t('cpt', 'Friday')?>";
    weekdays[6] = "<?= Yii::t('cpt', 'Saturday')?>";
</script>
<?
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/cptour.js', ['depends'=>'yii\web\JqueryAsset']);
?>