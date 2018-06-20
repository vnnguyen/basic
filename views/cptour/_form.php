<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\CpTour */
/* @var $form yii\widgets\ActiveForm */

$this->registerCss('
    .panel {
        padding: 20px 15px;
    }
    .ui-datepicker table td.ui-datepicker-today .ui-state-highlight{ color: #000;}
    .ui-datepicker table td.ui-datepicker-current-day .ui-state-active{ color: blue}
   
    .select2-selection--single .select2-selection__arrow::after {right: 5px;}
    .select2-container .select2-selection--single{height:36px}
    .select2{ width: 100% };
    .cp-comment {
        display: inline-block !important;
        cursor: pointer !important;
    }
    #ct_tour {display: none;}
    .fixed {position:fixed; top:0; right:0;}
    .cp_form{ z-index: 999}
    #ct_tour { display: none}
    #ui-datepicker-div {z-index: 999999999!important}
');

// $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/cpt.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs('
     $("#cptour-ngay_tt").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true, 
        altField: "#actualDate",
        closeText: "Clear",
        yearRange: "c-30:c+30",
        onClose: function () {
                var event = arguments.callee.caller.caller.arguments[0];
                // If "Clear" gets clicked, then really clear it
                if ($(event.delegateTarget).hasClass("ui-datepicker-close")) {
                    $(this).val("");
                }
            }

    });
');
$status_pre_booking = ['not pre booking' => 'not pre booking', 'pre booking' => 'pre booking']
?>
<div class="buttons pull-right">
    <a class="tgl_tour btn btn-default">toggle Tour</a>
</div>
<div class="clearfix"></div>
<div class="col-md-6" id="ct_tour">
    <?php 
    // echo $this->render('/ngaymau/detail', [
    //     'pages' => $pages,
    //     'dataProvider' => $dataProvider,
    //     'tour_model' => $tour_model]); 
        ?>
</div>
<div class="col-md-6" id="cp_tour">
    <div class="row">
        <div class="col-md-6 cp_form">
            <div class="panel row entry_info">
                <div class="cp-tour-form col-md-12">

                    <?php $form = ActiveForm::begin(); ?>

                    <div class="col-md-6 wrap-ncc">
                        <?= $form->field($model, 'ncc')->dropDownList([]) ?>
                        <!-- <div id="list_ncc" class="search-suggest"></div> -->
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'dv')->widget(Select2::classname(), [
                        // 'language' => 'de',
                        // 'options' => ['placeholder' => 'Select'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-2">
                        <?= $form->field($model, 'sl')->textInput() ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($model, 'dem')->textInput() ?>
                    </div>

                    <?php if (isset($days)): ?>
                        <div class="col-md-3">
                            <?= $form->field($model, 'ngay_sd')->dropDownList($days) ?>
                        </div>
                    <?php endif ?>

                    <div class="col-md-3">
                        <?= $form->field($model, 'gia')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($model, 'unit')->dropDownList(['VND' => 'VND', 'USD' => 'USD']) ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'vp_dat')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'vp_tra')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'status_book')->dropDownList($status_pre_booking)  ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-3">
                        <?= $form->field($model, 'ngay_tt')->widget(DatePicker::className(),['dateFormat' => 'yyyy-MM-dd' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']]) ?>
                    </div>

                    <div class="col-md-3">
                        <?= $form->field($model, 'who_pay')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Note</label>
                            <?= Html::textarea( 'note_dv', '', ['class'=>'form-control']);?>
                        </div>

                        <div class="clearfix"></div>

                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="cp_table">
            <div class="tabbable">
                <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                    <li class="active"><a href="#justified-badges-tab1" data-toggle="tab"><i class="fa fa-home"></i> Hotel</a></li>
                    <li><a href="#justified-badges-tab2" data-toggle="tab">Inactive <span class="badge bg-slate position-right">23</span></a></li>
                    <!-- <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-info">34</span> <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="#justified-badges-tab3" data-toggle="tab">Dropdown tab</a></li>
                            <li><a href="#justified-badges-tab4" data-toggle="tab">Another tab</a></li>
                        </ul> -->
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="justified-badges-tab1">
                        <div class="panel col-md-12">
                            <div class="data-result table-responsive">
                                <table class="table table-bordered table-framed">
                                    <thead>
                                        <tr>
                                            <th width="100">Date</th>
                                            <th>Venue</th>
                                            <th>Service</th>
                                            <th>Quality</th>
                                            <th>No days</th>
                                            <th>Day use</th>
                                            <th>Price</th>
                                            <!-- <th>Currency</th>
                                            <th>Book off</th>
                                            <th>Pay off</th>
                                            <th>Pay date</th>
                                            <th>Pay person</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="tr-services">
                                            <td>data <span class="cp-comment"><i class="fa fa-comment-o"></i></span></td>
                                            <td><a class="venue_update" data-toggle="modal" data-target="#cpt_update_modal" title="">data</a></td>
                                            <td>data</td>
                                            <td>data</td>
                                            <td>data</td>
                                            <td>data</td>
                                            <td>data</td>
                                            <!-- <td>data</td>
                                            <td>data</td>
                                            <td>data</td>
                                            <td>data</td>
                                            <td>data</td> -->
                                        </tr>
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
</div>

<div class="modal fade" id="cpt_comment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">New message</h4>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="message-text" class="form-control-label">Message:</label>
              <textarea class="form-control" id="message-text"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save change</button>
        </div>
      </div>
    </div>
</div>
<!-- <div class="modal fade" id="cpt_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">New message</h4>
        </div>
        <div class="modal-body">
            <div class="cpt_update_update_modal">
                
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save change</button>
        </div>
      </div>
    </div>
</div> -->

