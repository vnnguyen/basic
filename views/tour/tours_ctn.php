<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;
$this->title = 'Tour day notes - '.$theTour['op_code'].' - '.$theTour['op_name'];

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'products/op/'.$theTour['id']],
    ['Day notes'],
];

$tourdayIds = explode(',', $theTour['day_ids']);

$form = ActiveForm::begin();
?>
    <? if (USER_ID == 1) { ?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            
<?
$cnt = 0;
foreach ($tourdayIds as $id) {
    foreach ($theTour['days'] as $day) {
        if ($day['id'] == $id) {
            $dmY = date('j/n/Y', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
?>
            <p><strong><span class="text-muted"><?= ++$cnt ?></span> (<?= $dmY ?>) <?= $day['name'] ?> <em><?= $day['meals'] ?></em></strong> <a class="addhere" href="#">+Note</a></p>
            <div style="display:none; padding:0 0 2em 1em;"><?= Markdown::process($day['body']) ?></div>
<?
        }
    }
}
?>
        </div>
    </div>
</div>

    <div class="row" id="addhereform" style="display:none; margin-bottom:20px;">
        <div class="col-md-2">
            <select class="form-control" name="icon">
                <option value="">No icon</option>
                <option value="flight">Flight</option>
                <option value="train">Train</option>
                <option value="car">Car</option>
                <option value="user">Guide</option>
                <option value="phone">Telephone</option>
                <option value="clock-o">Time</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" name="color">
                <option value="">No color</option>
                <option value="blue">Blue</option>
                <option value="red">Red</option>
                <option value="pink">Pink</option>
                <option value="purple">Purple</option>
            </select>
        </div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="note" placeholder="Enter note" autocomplete="off" value="">
        </div>
        <div class="col-md-1">
            <button id="addme" class="btn btn-primary btn-block">+</button>
        </div>
    </div>
</div>
<div class="col-md-4">
    
</div>
<?
$js = <<<'TXT'
$('a.addhere').on('click', function(){
    $('#addhereform').insertAfter($(this).parent()).show();
    return false;
});

var jqxhr = $.ajax({
    method: "POST",
    url: "/tours/ctn?id=TOUR_ID&ajax=true",
    data: {
        name: "John",
        location: "Boston"
    }
}).done(function() {
    alert( "success" );
}).fail(function() {
    alert( "error" );
}).always(function() {
    alert( "complete" );
});
TXT;
$this->registerJs($js);
?>
    <? } ?>

<div class="col-md-6">

    <p><strong>ICON LIST</strong></p>
    <p>
        <i class="fa fa-plane"></i> (flight) (plane) (air)
        <i class="fa fa-train"></i> (train)
        <i class="fa fa-car"></i> (car)
        <i class="fa fa-user"></i> (guide) (hdv)
        <i class="fa fa-phone"></i> (phone) (tel)
        <i class="fa fa-clock-o"></i> (time)
        <br>Colors: (blue) (red) (green) (purple)
    </p>
    <?= $form->field($theNote, 'body')->textArea(['rows'=>25, 'class'=>'form-control', 'placeholder'=>'d/m >>> (car) Note content']) ?>
    <div class="text-right"><?= Html::submitButton('Save note', ['class'=>'btn btn-primary']) ?></div>
</div>
<? ActiveForm::end(); ?>
<div class="col-md-6">
    <p><strong>ITINERARY</strong></p>
    <p>Last update <?= $theTour['updated_at'] ?></p>
    <div style="height:600px;width:100%; overflow:scroll">
<?
$cnt = 0;
foreach ($tourdayIds as $id) {
    foreach ($theTour['days'] as $day) {
        if ($day['id'] == $id) {
            $dmY = date('j/n/Y', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
?>
    <hr>
    <p><span class="badge"><?= ++$cnt ?></span> <strong class="text-info"><?= $dmY ?> | <?= $day['name'] ?></strong> <em><?= $day['meals'] ?></em></p>
    <div style="display:xnone; padding-left:2em;"><?= Markdown::process($day['body']) ?></div>
<?
        }
    }
}
?>
    </div>
</div>
