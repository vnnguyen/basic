<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;

$this->title = 'Tour calendar (30 days)';

$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
    ['Tours', '@web/tours'],
    ['Calendar', '@web/tours/calendar'],
];

// Calculate the time of notes and emails
$myTimeZone = Yii::$app->user->identity->timezone;
if (!in_array($myTimeZone, ['UTC', 'Europe/Paris', 'Asia/Ho_Chi_Minh'])) {
    $myTimeZone = 'Asia/Ho_Chi_Minh';
}

?>
<div class="col-md-12">
    <? if (USER_ID == 1) { ?>
    <div id="visualization"></div>    
<?

$js = <<<'TXT'
var container = document.getElementById('visualization');
var items = new vis.DataSet([DATA_SET]);
var options = {
    zoomMax:1600000000,
    //zoomMin:3600
};
var timeline = new vis.Timeline(container, items, options);
TXT;

$dataSet = '';
$cnt = 0;
foreach ($theTours as $tour) {
    $cnt ++;
    $dataSet .= '{id: '. $tour['id']. ', content: "'. $tour['op_code'] .'", start: "' . $tour['day_from'] . '", end: "'. date('Y-m-d', strtotime('+'. ($tour['day_count'] - 1).' days', strtotime($tour['day_from']))) .'"},';
}
$dataSet .= '{id: 99999999, content: "TODAY", start: "'.date('Y-m-d').'", style:"background-color:#ffc;"}';

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs(str_replace(['DATA_SET'], [$dataSet], $js));
?>
    <? } else { ?>
    <form class="form-inline well well-sm">
        <?= Html::a('Previous month', '@web/tours/calendar-month?date='.$prevMonth) ?>
        <input type="text" id="date" style="width:100px; display:inline-block;" class="form-control" name="date" value="<?= $thisMonth ?>">
        <button type="submit" class="btn btn-primary">Go</button>
        <?= Html::a('Next month', '@web/tours/calendar-month?date='.$nextMonth) ?>
        |
        <?= Html::a('Back to today', '/tours/calendar-month') ?>
    </form>
    <div class="table-responsive">
        <table id="tbl-calendar" class="table table-bordered table-condensed">
            <tbody>
                <tr>
                    <th width="30"></th>
                    <th width="200" class="text-center bg-warning">Tour \ Ngày</th>
                    <? for ($i = 0; $i < 30; $i ++) { ?>
                    <th width="200" class="text-center bg-warning">
                    <?= date('j/n', strtotime('+ '.$i.' days', strtotime($thisMonth))) ?>
                    </th>
                    <? } ?>
                </tr>
<?
$cnt = 0;
foreach ($theTours as $tour) {
    $trClass = 'tour-user';
    $names = [];
    foreach ($tour['bookings'] as $booking) {
        $names[] = Html::a($booking['createdBy']['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$booking['createdBy']['id']]);
        $trClass .= ' tour-user-'.$booking['createdBy']['id'];
    }
    if ($tour['tour']['operators']) {
        foreach ($tour['tour']['operators'] as $user) {
            $names[] = Html::a($user['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$user['id']]);
            $trClass .= ' tour-user-'.$user['id'];
        }
    }
?>
                <tr class="<?= $trClass ?>">
                    <td class="text-muted text-center cnt"><?= ++ $cnt ?></td>
                    <td class="bg-warning">
                        <div class="text-nowrap">
                            <?= Html::a($tour['op_code'], '@web/products/op/'.$tour['id'], ['title'=>$tour['op_name'], 'rel'=>'external']) ?>
                            <?= $tour['pax'] ?>p <?= $tour['day_count'] ?>d <?= date('j/n', strtotime($tour['day_from'])) ?>
                        </div>
                        <div><?= $tour['op_name'] ?></div>
                        <div>
                            <?= implode(', ', $names) ?>
                        </div>
                    </td>
<?
// Sai lệch so với ngày tour khởi hành
$diff = date_diff(date_create($tour['day_from']), date_create($thisMonth))->format('%R%a');
$dayIdList = explode(',', $tour['day_ids']); 
for ($i = 0; $i < 30; $i ++) {
    $thisDay = date('Y-m-d', strtotime('+ '.$i.' days', strtotime($thisMonth)));
    $class = '';
    if ($thisDay == date('Y-m-d')) {
        $class = 'bg-success';
    }
    echo '<td class="td-day text-center '.$class.'">';
    $index = (int)$diff + $i;
    foreach ($tour['days'] as $day) {
        if (isset($dayIdList[$index]) && $day['id'] == $dayIdList[$index]) {
?>
                        <div class="text-muted" title="<?= $tour['op_code'] ?> <?= $day['name'] ?> <?= $day['meals'] ?>"><?= date('j/n', strtotime($thisDay)) ?></div>
<?
            if (isset($tour['tour']['cpt'])) {
                foreach ($tour['tour']['cpt'] as $cpt) {
                    if ($cpt['dvtour_day'] == $thisDay) {
?>
                        <div title="<?= $cpt['venue']['name'] ?>: <?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>">
                            <i class="fa fa-bed text-muted"></i>
                        </div>
<?
                        break;
                    }
                }
            }
        }
    }

    $date = strtotime($thisDay);

    // Tour guides
    foreach ($tourGuides as $guide) {
        if ($guide['tour_id'] == $tour['id'] && strtotime(substr($guide['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($guide['use_until_dt'], 0, 10))) {
?>
        <div style="color:#fb8c00" title="<?= $guide['namephone'] ?>"><i class="fa fa-user"></i></div>
<?
        }
    }

    // Tour guides
    foreach ($tourDrivers as $driver) {
        if ($driver['tour_id'] == $tour['id'] && strtotime(substr($driver['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($driver['use_until_dt'], 0, 10))) {
?>
            <div style="color:#00897b" title="<?= $driver['vehicle_type']?> <?= $driver['driver_company'] ?> <?= $driver['driver_name'] ?>"><i class="fa fa-car"></i></div>
<?
        }
    }
    // Tour notes by TOp

    if (isset($tour['tournotes'])) {
        foreach ($tour['tournotes'] as $note) {
            $lines = explode(PHP_EOL, $note['body']);
            foreach ($lines as $line) {
                $parts = explode('>>>', $line);
                if (isset($parts[1])) {
                    $parts[0] = trim($parts[0]);
                    $parts[1] = trim($parts[1]);
                    if ($parts[0] == date('j/n', strtotime($thisDay))) {
                        $color = 'blue';
                        $icon = 'edit';
                        foreach (['(red)', '(green)', '(purple)', '(pink)', '(blue)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $color = str_replace(['(', ')'], ['', ''], $code);
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }
                        foreach (['(car)', '(xe)', '(driver)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $icon = 'car';
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }
                        foreach (['(air)', '(flight)', '(plane)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $icon = 'plane';
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }
                        foreach (['(clock)', '(time)', '(plane)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $icon = 'clock-o';
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }
                        foreach (['(call)', '(phone)', '(tel)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $icon = 'phone';
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }
                        foreach (['(train)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $icon = 'train';
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }
                        foreach (['(guide)', '(hdv)'] as $code) {
                            if (strpos($parts[1], $code) !== false) {
                                $icon = 'user';
                                $parts[1] = str_replace($code, '', $parts[1]);
                            }
                        }

?>
                        <div title="<?= trim($parts[1]) ?> | <?= $note['updatedBy']['name'] ?> <?= DateTimeHelper::convert($note['updated_at'], 'j/n/Y H:i', 'UTC', $myTimeZone); ?>" style="color:<?= $color ?>;">
                        <i class="fa fa-<?= $icon ?>"></i>
                        </div>
<?
                    }
                }
            }
        }
    }

?>
                    </td>
<?
}
?>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <? } ?>
</div>
<style type="text/css">
.text-bold {font-weight:bold;}
</style>
<?
$js = <<<'TXT'
$('h3.page-title').append(' (<span id="tour-count">TOUR_COUNT</span>)');
$('a.tour-user').click(function(){
    var id = $(this).data('id');
    if ($(this).hasClass('text-bold')) {
        $('a.tour-user[data-id='+id+']').removeClass('text-bold');
        $('tr.tour-user').show();
    } else {
        $('a.tour-user').removeClass('text-bold');
        $('a.tour-user[data-id='+id+']').addClass('text-bold');
        $('tr.tour-user').hide();
        $('tr.tour-user-'+id).show();
    }
    cnt = 0;
    $('#tour-count').html($('tr.tour-user:visible').length);
    $('td.cnt:visible').each(function(index){
        $(this).html(1 + index);
    });
    return false;
});
TXT;
if (USER_ID != 1) {
$this->registerJs(str_replace(['TOUR_COUNT'], [count($theTours)], $js));
$js = <<<TXT
$('#date').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    autoclose: true
});
$('td.td-day').has('div').css('background-color', '#f6f0f0');
TXT;
$this->registerCssFile(DIR.'assets/bootstrap-datepicker_1.3.1/css/datepicker3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-datepicker_1.3.1/js/bootstrap-datepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-datepicker_1.3.1/js/locales/bootstrap-datepicker.vi.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs(str_replace(['{dt}'], [$thisMonth], $js));
}