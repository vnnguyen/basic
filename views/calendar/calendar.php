<?php
use yii\helpers\Html;

include('events__inc.php');
include('/var/www/my.amicatravel.com/views/fdb.php');

$getCal = \fRequest::getValid('cal', array_keys($eventCalendars));

if (SEG2 == 'get') {
	$getStart = \fRequest::get('start');
	$getEnd = \fRequest::get('end');
	
	$startMonth = date('n', $getStart);
	$startYear = date('Y', $getStart);
	if ($startMonth == 11) {
		$inMonth = array(11, 12, 1);
		$endYear = $startYear + 1;
	} elseif ($startMonth == 12) {
		$inMonth = array(12, 1, 2);
		$endYear = $startYear + 1;
	} else {
		$inMonth = array($startMonth, $startMonth + 1, $startMonth + 2);
		$endYear = $startYear;
	}
	
	$return = array();
	
	if ($getCal == 'tours') {
		// Tours
		$q = $db->query('SELECT t.id, t.code, t.name, ct.pax, ct.day_count, ct.day_from FROM at_tours t, at_ct ct WHERE ct.id=t.ct_id AND ((ct.day_from>=%d AND ct.day_from<=%d) OR (DATE_ADD(ct.day_from, INTERVAL ct.day_count DAY)>=%d AND DATE_ADD(ct.day_from, INTERVAL ct.day_count DAY)<=%d))', $getStart, $getEnd, $getStart, $getEnd);
		$theTours = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		$cnt = 0;
		foreach ($theTours as $te) {
			$cnt ++;
			$return[] = array(				
				'title'=>$te['code'].' '.$te['name'].' - '.$te['pax'].' pax',
				'start'=>$te['day_from'],
				'end'=>date('Y-m-d', strtotime('+ '.($te['day_count'] - 1).' days', strtotime($te['day_from']))),
				'url'=>DIR.'tours/r/'.$te['id'],
				'allDay'=>true,
				'color'=>'#'.rand(10, 99).rand(10, 99).rand(10, 99),
				'className'=>'event-status-confirm event-type-tours',
				'editable'=>false,
			);
		}
	}

	if ($getCal == 'all' || $getCal == 'birthdays') {
		// Birthdays
		$q = $db->query('SELECT id, fname, lname, bday, bmonth, byear FROM persons WHERE is_member=%s AND bmonth IN ('.implode(',', $inMonth).')', 'yes');
		$amicaBirthdays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		
		foreach ($amicaBirthdays as $te) {
			if ($endYear != $startYear && $te['bmonth'] <= 2) {
				$bd = date('Y-m-d', mktime(0, 0, 0, $te['bmonth'], $te['bday'], $endYear));
			} else {
				$bd = date('Y-m-d', mktime(0, 0, 0, $te['bmonth'], $te['bday'], $startYear));
			}
			$return[] = array(				
				'title'=>'SN '.$te['fname'].' '.$te['lname'],
				'start'=>$bd,
				'url'=>DIR.'users/r/'.$te['id'],
				'allDay'=>true,
				'color'=>'#f60',
				'className'=>'event-status-confirm event-type-birthday',
				'editable'=>false,
			);
		}
	}
	
	if ($getCal == 'my-tasks') {
		// My tasks
		$q = $db->query('SELECT tk.due_dt, tk.rtype, tk.rid, tk.status, tk.description FROM at_tasks tk, persons u, at_task_user tu WHERE tk.status!=%s AND tu.user_id=u.id AND tu.task_id=tk.ub AND tu.user_id=%i AND tk.due_dt>=%d AND tk.due_dt<=%d ORDER BY tk.description LIMIT 1000', myID, 'xon', $getStart, $getEnd);
		$theTasks = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($theTasks as $tk) {
			$return[] = array(
				'title'=>htmlspecialchars_decode($tk['description']),
				'start'=>$tk['due_dt'],
				'url'=>$tk['rid'] == 0 ? DIR.'tasks' : DIR.$tk['rtype'].'s/r/'.$tk['rid'],
				'allDay'=>true,
				'color'=>$tk['status'] != 'on' ? '#ccc' : '#'.rand(10, 99).rand(10, 99).rand(10, 99),
				'className'=>'event-status-confirm event-type-task',
				'editable'=>false,
			);
		}
	}

	if ($getCal == 'customer-care') {
		$taskBGColors = array(
			'PC'=>'#b5b',
			'BV'=>'#55b',
			'FB'=>'#992',
			'AC'=>'#5b5',
			'A1'=>'#b55',
			'A2'=>'#b55',
			'A3'=>'#b55',
			'A4'=>'#b55',
			'A5'=>'#b55',
			'SV'=>'#5bb',
		);
		// Customer care tasks
		$q = $db->query('SELECT tk.*, t.code, t.id AS tour_id, t.name AS tour_name, u.name, UPPER(SUBSTRING(tk.description,1,2)) AS description
			FROM at_tasks tk, at_tours t, persons u WHERE u.id=tk.ub AND
			SUBSTRING(tk.description,1,2) IN (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) AND
			tk.rtype=%s AND tk.rid=t.id AND tk.due_dt>=%d AND tk.due_dt<=%d ORDER BY tk.description LIMIT 1000', "PC", "BV", "FB", "AC", "A1", "A2", "A3", "A4", "A5", "SV", 'tour', $getStart, $getEnd);
		$theTasks = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($theTasks as $tk) {
			$return[] = array(
				'title'=>$tk['description'].':'.$tk['code'].':'.$tk['name'],
				'start'=>$tk['due_dt'],
				'url'=>DIR.'tours/r/'.$tk['tour_id'],
				'allDay'=>true,
				'color'=>$tk['status'] == 'on' ? '#ccc' : $taskBGColors[$tk['description']],
				'className'=>'event-status-confirm event-type-customer-care',
				'editable'=>false,
			);
		}
	}
	
	if ($getCal == 'reception-hanoi') {
		$q = $db->query('SELECT tk.*, CONCAT(t.code, %s, t.name) AS tour_name, u.name, description, (SELECT name FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name,
			(SELECT CONCAT(uu.name, %s, uu.about) FROM persons uu, at_tour_guide tg WHERE uu.id=tg.user_id AND tg.tour_id=tk.rid AND tg.day=SUBSTRING(tk.due_dt,1,10) LIMIT 1) AS guide, 1
			FROM at_tasks tk, at_tours t, persons u WHERE u.id=tk.ub AND
			(SUBSTRING(tk.description,1,3) = %s OR tk.description=%s) AND
			tk.rtype=%s AND tk.rid=t.id AND tk.due_dt>=%d AND tk.due_dt<=%d ORDER BY tk.due_dt LIMIT 1000',
			' - ', ' - ', 'AC', 'AC ', 'tour',
			$getStart, $getEnd);
		$theTasks = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($theTasks as $tk) {
			$return[] = array(
				'title'=>$tk['tour_name'].' ('.$tk['se_name'].') '.substr($tk['description'], 2),
				'start'=>$tk['due_dt'],
				'url'=>DIR.'tours/r/'.$tk['rid'],
				'allDay'=>$tk['fuzzy'] != 'none' ? true : false,
				'color'=>$tk['status'] == 'on' ? '#ccc' : '#5b5',
				'className'=>'event-status-confirm event-type-reception-hanoi',
				'editable'=>false,
			);
		}
	}
	
	if ($getCal == 'birthdays-customers') {
		// Birthdays
		$q = $db->query('SELECT u.id, u.name, u.bday, u.bmonth, u.byear FROM persons u, at_pax p WHERE p.user_id=u.id AND bmonth IN ('.implode(',', $inMonth).') GROUP BY user_id');
		$customerBirthdays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		
		foreach ($customerBirthdays as $te) {
			if ($endYear != $startYear && $te['bmonth'] <= 2) {
				$bd = date('Y-m-d', mktime(0, 0, 0, $te['bmonth'], $te['bday'], $endYear));
			} else {
				$bd = date('Y-m-d', mktime(0, 0, 0, $te['bmonth'], $te['bday'], $startYear));
			}
			$return[] = array(				
				'title'=>$te['name'],
				'start'=>$bd,
				'url'=>DIR.'users/r/'.$te['id'],
				'allDay'=>true,
				'color'=>'#f60',
				'className'=>'event-status-confirm event-type-birthday',
				'editable'=>false,
			);
		}
	}

	if ($getCal == 'birthdays-guides') {
		// Birthdays
		$q = $db->query('SELECT id, name, bday, bmonth, byear FROM persons u, at_user_role ug WHERE ug.user_id=u.id AND ug.role_id=%i AND bmonth IN ('.implode(',', $inMonth).')', 11);
		$guideBirthdays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : [];
		
		foreach ($guideBirthdays as $te) {
			if ($endYear != $startYear && $te['bmonth'] <= 2) {
				$bd = date('Y-m-d', mktime(0, 0, 0, $te['bmonth'], $te['bday'], $endYear));
			} else {
				$bd = date('Y-m-d', mktime(0, 0, 0, $te['bmonth'], $te['bday'], $startYear));
			}
			$return[] = array(				
				'title'=>'SN guide: '.$te['name'],
				'start'=>$bd,
				'url'=>DIR.'users/r/'.$te['id'],
				'allDay'=>true,
				'color'=>'#f60',
				'className'=>'event-status-confirm event-type-birthday',
				'editable'=>false,
			);
		}
	}
	
	if ($getCal == 'all') {
		// Events
		$q = $db->query('SELECT id, updated_by, status, stype, name, from_dt, until_dt FROM at_events WHERE status IN(%s, %s) AND from_dt>=%d AND until_dt<=%d LIMIT 1000', 'on', 'draft', $getStart, $getEnd);
		$amicaEvents = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		if (!empty($amicaEvents)) {
			$eventIdList = array();
			foreach ($amicaEvents as $te) $eventIdList[] = $te['id'];
			$q = $db->query('SELECT u.id, u.name, eu.event_id FROM persons u, at_event_user eu WHERE u.id=eu.user_id AND eu.event_id IN ('.implode(',', $eventIdList).') ORDER BY u.lname LIMIT 1000');
			$eventUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		}
		
		foreach ($amicaEvents as $te) {
			$userCnt = 0;
			$userName = '';
			foreach ($eventUsers as $eu) {
				if ($eu['event_id'] == $te['id']) {
					$userCnt ++;
					if ($userCnt == 3) {
						$userName .= '...';
						break;
					}
					if ($userCnt > 1) $userName .= ',';
					$userName .= $eu['name'];
				}
			}

			$return[] = array(				
				'title'=>$userName.' : '.$te['name'],
				'start'=>$te['from_dt'],
				'end'=>$te['until_dt'],
				'url'=>DIR.'events/r/'.$te['id'],
				'allDay'=>false,
				'color'=>$eventTypeColors[$te['stype']],
				'className'=>'event-status-'.$te['status'].' event-type-'.$te['stype'],
				'editable'=>false,
			);
		}
	}
	
	if ($getCal == 'public-holidays') {
		// Events
		$q = $db->query('SELECT id, updated_by, status, stype, name, from_dt, until_dt FROM at_events WHERE status IN(%s, %s) AND stype=%s AND from_dt>=%d AND until_dt<=%d LIMIT 1000', "on", "draft", 'nghile', $getStart, $getEnd);
		$amicaEvents = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		foreach ($amicaEvents as $te) {
			$return[] = array(				
				'title'=>$te['name'],
				'start'=>$te['from_dt'],
				'end'=>$te['until_dt'],
				'url'=>DIR.'events/r/'.$te['id'],
				'allDay'=>false,
				'color'=>$eventTypeColors[$te['stype']],
				'className'=>'event-status-'.$te['status'].' event-type-'.$te['stype'],
				'editable'=>false,
			);
		}
	}

	
	echo json_encode($return);
	exit;
}


$getMonth = \fRequest::get('month', 'string', date('Y-m'), true);
if ($getMonth == 'all' || $getMonth == 'from') $getMonth = date('Y-m');
$startDay = strtotime('last Monday', strtotime($getMonth.'-01'));

$this->title = 'Lịch Amica';
$this->params['small'] = $eventCalendars[$getCal];
$this->params['icon'] = 'calendar';
$pageB = array(	
	Html::a('events', 'Các sự kiện'),
	Html::a('calendar', 'Calendar'),
	Html::a('calendar?cal='.$getCal, $eventCalendars[$getCal]),
);

?>
<div class="col-lg-12">
	<div id="calendar"></div>
</div>

<?
$jsCode = <<<TXT
	$('#calendar').fullCalendar({
		events: '/calendar/get?cal=:cal',
		cache: false,
		firstDay:1,
		weekMode:'variable',
		year: :y,
		month: :m,
		monthNames: ['Tháng giêng', 'Tháng hai', 'Tháng ba', 'Tháng tư', 'Tháng năm', 'Tháng sáu', 'Tháng bảy', 'Tháng tám', 'Tháng chín', 'Tháng mười', 'Tháng mười một', 'Tháng mười hai'],
		monthNamesShort: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
		dayNames: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
		dayNamesShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
		buttonText: {
			prev:     '&nbsp;&#9668;&nbsp;',  // left triangle
			next:     '&nbsp;&#9658;&nbsp;',  // right triangle
			prevYear: '&nbsp;&lt;&lt;&nbsp;', // <<
			nextYear: '&nbsp;&gt;&gt;&nbsp;', // >>
			today:    'hiện tại',
			month:    'tháng',
			week:     'tuần',
			day:      'ngày'
		},
		firstHour: 8,
		minTime:8,
		maxTime:'17:30',
		allDayText: 'cả ngày',
		theme:false,
		header: {
			left: 'prev,next,today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		columnFormat: {
			month: 'dddd',    // Mon
			week: 'dddd d-M', // Mon 9/7
			day: 'dddd d-M'  // Monday 9/7
		}
	});
TXT;

$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.css', ['depends'=>'app\assets\MainAsset', 'media'=>'screen']);
$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.print.css', ['depends'=>'app\assets\MainAsset', 'media'=>'print']);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.min.js', ['depends'=>'app\assets\MainAsset']);

$this->registerJs(str_replace([':y', ':m', ':cal'], [substr($getMonth, 0, 4), (int)substr($getMonth, -2) - 1, $getCal], $jsCode));