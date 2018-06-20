<?php

use yii\helpers\Html;

$timeList = [
    't'=>'Cụ thể',
    'm'=>'Sáng',
    'a'=>'Chiều',
    'e'=>'Chưa biết',
];

$purpose = ['#c'];
$purposeList = [
    't'=>'Thu/Trả lại tiền',
    's'=>'Tổ chức SN',
    'q'=>'Tặng quà SN',
    'c'=>'Tặng quà khách cũ',
];
$table = '#1';
$tableList = [
    ''=>'Không có',
    '1'=>'Bàn 1',
    '2'=>'Bàn 2',
    '3'=>'Bàn 3',
    '4'=>'Bàn 4',
];

$atList = [
    'all'=>'All locations',
    'hanoi'=>'Hanoi office',
    'saigon'=>'Saigon office',
    'luangprabang'=>'Luang Prabang office',
];

?>

    <div class="modal fade" id="taskModal" tabindex="-1" xdata-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><?= Yii::t('app','Sửa thông tin') ?></h6>
                </div>
                <div class="modal-body">
                    <form id="taskForm" method="post" class="">
                        <?= Html::hiddenInput('id', 0) ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('app', 'Thời gian') ?></label>
                                    <?= Html::dropdownList('time_fuzzy', '', $timeList, ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3" id="time_detail">
                                <div class="form-group">
                                    <label class=" control-label"><?= Yii::t('app', 'Giờ') ?></label>
                                    <?= Html::textInput('time', '09:00', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('app', 'Số phút') ?></label>
                                    <?= Html::textInput('mins', '60', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('app', 'Vị trí') ?></label>
                                    <?= Html::dropdownList('table', '', $tableList, ['class'=>'form-control']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Mục đích đặc biệt') ?></label>
                            <?= Html::checkboxList('purpose', [], $purposeList) ?>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Ghi chú') ?></label>
                            <?= Html::textInput('note', '', ['class'=>'form-control']) ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Ghi các thay đổi</button>
                            hoặc <a href="javascript:;" data-dismiss="modal">Thôi</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>