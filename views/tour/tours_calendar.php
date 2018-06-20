<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;

Yii::$app->params['page_layout'] = '-h';
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'calendar';
Yii::$app->params['page_title'] = 'Tour calendar';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tours', '@web/tours'],
    ['Calendar'],
];

// Calculate the time of notes and emails
$myTimeZone = Yii::$app->user->identity->timezone;
if (!in_array($myTimeZone, ['UTC', 'Europe/Paris', 'Asia/Ho_Chi_Minh'])) {
    $myTimeZone = 'Asia/Ho_Chi_Minh';
}
?>
<style type="text/css">
.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {padding:8px;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>TOUR CALENDAR (WEEK)</strong> - View 
            <?= Html::a('Previous week', '@web/tours/calendar?date='.$prevWeek) ?>
            <input type="text" id="date" style="width:100px; display:inline-block;" class="form-control" name="date" value="<?= $thisWeek ?>">
            <button type="submit" class="btn btn-primary">Go</button>
            <?= Html::a('Next week', '@web/tours/calendar?date='.$nextWeek) ?>
            |
            <?= Html::a('Back to this week', '/tours/calendar') ?>
            <!-- // TODO 
            |
            <?= Html::a('Today only', '#', ['id'=>'today-only']) ?>
            |
            <?= Html::a('Me only', '#', ['id'=>'me-only']) ?>
            -->
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed no-border-top">
                <thead>
                    <tr class="info no-border-top">
                        <th width="30"></th>
                        <th width="200" class="text-center">Tour \ Ngày</th>
                        <? for ($i = 0; $i < 7; $i ++) { ?>
                        <th width="200" class="text-center">
                        <?= date('j/n/Y D', strtotime('+ '.$i.' days', strtotime($thisWeek))) ?>
                        </th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
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
    if ($tour['tour']['cskh']) {
        foreach ($tour['tour']['cskh'] as $user) {
            $names[] = Html::a($user['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$user['id']]);
            $trClass .= ' tour-user-'.$user['id'];
        }
    }
?>
                <tr class="<?= $trClass ?>">
                    <td class="text-muted text-center cnt"><?= ++ $cnt ?></td>
                    <td style="background-color:#E0F7FA">
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
$diff = date_diff(date_create($tour['day_from']), date_create($thisWeek))->format('%R%a');
$dayIdList = explode(',', $tour['day_ids']); 
for ($i = 0; $i < 7; $i ++) {
    $thisDay = date('Y-m-d', strtotime('+ '.$i.' days', strtotime($thisWeek)));
    $class = '';
    if ($thisDay == date('Y-m-d')) {
        $class = 'bg-today';
    }
    echo '<td class="'.$class.'">';
    $index = (int)$diff + $i;
    foreach ($tour['days'] as $day) {
        if (isset($dayIdList[$index]) && $day['id'] == $dayIdList[$index]) {
?>
                        <strong><span class="text-muted"><?= date('j/n', strtotime($thisDay)) ?></span> <?= $tour['op_code'] ?></strong>
<?
            foreach ($paxWithBirthdays as $pax) {
                if ($pax['bday'].'/'.$pax['bmonth'] == date('j/n', strtotime($thisDay)) && $pax['product_id'] == $tour['id']) {
                    echo ' <a href="/users/r/'.$pax['user_id'].'"><i title="Birthday: '.Html::encode($pax['name']).' ('.(1 + date('Y') - $pax['byear']).')" class="fa text-danger fa-gift"></i></a>';
                }
            }
?>
                        <div style="font-size:90%;" title="<?= $day['name'] ?> <?= $day['meals'] ?>"><?= $day['name'] ?> <em><?= $day['meals'] ?></em></div>
<?
            if (isset($tour['tour']['cpt'])) {
                foreach ($tour['tour']['cpt'] as $cpt) {
                    if ($cpt['dvtour_day'] == $thisDay) {
?>
                        <div style="font-size:90%; height:20px; overflow:hidden;" title="<?= $cpt['venue']['name'] ?>; <?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>">
                            <i class="fa fa-bed text-muted"></i>
                            <?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id'], ['rel'=>'external', 'class'=>'text-danger']) ?>
                            <?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>
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
            echo '<div style="font-size:90%; color:#fb8c00"><i class="fa fa-user"></i> ';
            if ($guide['booking_status'] == 'confirmed') {
                echo '[cfm] ';
            } else {
                echo '['.strtoupper($guide['booking_status']).'] ';
            }
            echo $guide['namephone'];
            echo '</div>';
        }
    }

    // Tour guides
    foreach ($tourDrivers as $driver) {
        if ($driver['tour_id'] == $tour['id'] && strtotime(substr($driver['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($driver['use_until_dt'], 0, 10))) {
            echo '<div style="font-size:90%; color:#00897b"><i class="fa fa-car"></i> ';
            if ($driver['booking_status'] == 'confirmed') {
                echo '[cfm] ';
            } else {
                echo '['.strtoupper($driver['booking_status']).'] ';
            }
            echo $driver['vehicle_type'].', '.$driver['driver_company'].', '.$driver['driver_name'];
            echo '</div>';
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
                        <div title="<?= $note['updatedBy']['name'] ?> <?= DateTimeHelper::convert($note['updated_at'], 'j/n/Y H:i', 'UTC', $myTimeZone); ?>" style="font-size:90%; color:<?= $color ?>;">
                        <? if (in_array(MY_ID, [$note['created_by'], $note['updated_by']])) { ?><a title="Edit" class="text-muted" href="/tours/ctn/<?= $note['product_id'] ?>"><i class="fa fa-edit"></i></a><? } ?>
                        <? if ($icon != '') { ?><i class="fa fa-<?= $icon ?>"></i><? } ?>
                        <?= trim($parts[1]) ?>
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
    </div>
</div>
<style type="text/css">
th, td {vertical-align:top!important;}
.text-bold {font-weight:bold;}
.bg-th {background-color:#5bc0de;}
.bg-today {background-color:#fcf8e3;}
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

TXT;
$this->registerCssFile(DIR.'assets/bootstrap-datepicker_1.3.1/css/datepicker3.css', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-datepicker_1.3.1/js/bootstrap-datepicker.js', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-datepicker_1.3.1/js/locales/bootstrap-datepicker.vi.js', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJs(str_replace(['{dt}'], [$thisWeek], $js));