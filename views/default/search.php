<?

// Display avatar
if ( ! function_exists('avatar')) {
	function avatar($id, $size, $alt = 'Avatar', $attrs = '') {
		if (file_exists('/var/www/my.amicatravel.com/upload/user-avatars/user-'.$id.'.jpg')) {
			$avatar = DIR.'upload/user-avatars/user-'.$id.'.jpg';
		} else {
			$avatar = 'http://0.gravatar.com/avatar/'.md5('user-'.$id).'.jpg?s='.$size.'&d=wavatar';
		}
		return '<img src="'.$avatar.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" '.$attrs.'/>';
	}
}

$db = new fDatabase('mysql', 'amica_my', 'amica_my', '2w#E4r%T', 'localhost');
define('myID', Yii::$app->user->id);
define('myName', Yii::$app->user->identity->name);

/* Prefixes
c h Cases
p n People
t t Toure
v d Venues
y y Companies
*/

// Tìm bất kỳ từ form tìm kiếm
$tim = fRequest::get('tim', 'string', '', true);
$tim = str_replace('"', '\"', trim($tim));
$tim = str_replace('.', 'x1dot1x', $tim);
$tim = str_replace('@', 'x1atmark1x', $tim);
$tim = str_replace('-', 'x1dash1x', $tim);
$tim = fURL::makeFriendly($tim, '_');
$tim = str_replace('x1dot1x', '.', $tim);
$tim = str_replace('x1atmark1x', '@', $tim);
$tim = str_replace('x1dash1x', '-', $tim);
$tim = str_replace('_', '', $tim);
if (strlen($tim) < 2) exit('');

$prefix = substr($tim, 0, 2);
if ($prefix == 'c ' || $prefix == 'h ') {
	$tim = substr($tim, 2);
  $q = $db->query('SELECT rtype, rid, found FROM at_search WHERE rtype="case" AND LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', $tim);
  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
} elseif ($prefix == 't ') {
	$tim = substr($tim, 2);
  $q = $db->query('SELECT rtype, rid, found FROM at_search WHERE rtype="tour" AND LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', $tim);
  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
} elseif ($prefix == 'p ' || $prefix == 'n ') {
	$tim = substr($tim, 2);
  $q = $db->query('SELECT rtype, rid, found, (SELECT image FROM persons u WHERE u.id=rid LIMIT 1) AS image FROM at_search WHERE rtype=%s AND LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', 'user', $tim);
  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
} elseif ($prefix == 'v ' || $prefix == 'd ') {
	$tim = substr($tim, 2);
	
	$q = $db->query('SELECT rtype, rid, found FROM at_search WHERE rtype="venue" AND LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', $tim);
  	$found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
	/*
	$whereName = 'LOCATE("'.$tim.'", found)!=0';
	$whereSearch = '';
	$timArray = explode(' ', $tim);
	foreach ($timArray as $timStr) {
		if ($whereSearch != '') $whereSearch .= ' AND ';
		$whereSearch .= 'LOCATE(" '.$timStr.'", search)!=0';
	}
	$where = $whereName;
	if ($whereSearch != '') $where = $whereName . ' OR ('. $whereSearch .')';
  $q = $db->query('SELECT rtype, rid, found FROM at_search WHERE rtype="venue" AND ('.$where.') GROUP BY rid LIMIT 20');
  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();*/
} elseif ($prefix == 'y ') {
	$tim = substr($tim, 2);
  $q = $db->query('SELECT rtype, rid, found FROM at_search WHERE rtype="company" AND LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', $tim);
  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
} else {
  $q = $db->query('SELECT rtype, rid, found, IF(rtype=%s, (SELECT image FROM persons u WHERE u.id=rid LIMIT 1), %s) AS image  FROM at_search WHERE LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', 'user', '', $tim);
  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
}

//$db->query('INSERT INTO at_searchlog (dt, user_id, c, s) VALUES (%s, %i, %s, %s)', NOW, myID, $from, $tim);

if (!empty($found)) {
  foreach ($found as $f) {
		if ($f['rtype'] == 'user') echo '<a class="td-n" href="/users/r/'.$f['rid'].'"><img style="width:20px; height:20px" src="'.($f['image'] == '' ? 'http://www.gravatar.com/avatar/'.md5($f['rid']).'?d=wavatar' : '/timthumb.php?w=100&h=100&zc=1&src='.$f['image']).'"> '.$f['found'].'</a>';
		if ($f['rtype'] == 'case') echo '<a class="td-n" href="/cases/r/'.$f['rid'].'"><i class="fa fa-briefcase"></i> '.$f['found'].'</a>';
		if ($f['rtype'] == 'tour') echo '<a class="td-n" href="/tours/r/'.$f['rid'].'"><i class="fa fa-car"></i> '.$f['found'].'</a>';
		if ($f['rtype'] == 'venue') echo '<a class="td-n" href="/venues/r/'.$f['rid'].'"><i class="fa fa-map-marker"></i> '.$f['found'].'</a>';
		if ($f['rtype'] == 'company') echo '<a class="td-n" href="/companies/r/'.$f['rid'].'"><i class="fa fa-home"></i> '.$f['found'].'</a>';
  }
} else {
  exit('');
}
