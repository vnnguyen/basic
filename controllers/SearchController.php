<?php

namespace app\controllers;

use Yii;
use common\models\ContactForm;

class SearchController extends MyController
{
	public function actionIndex()
	{
		return $this->render('search');
	}

	public function actionAjax($tim = '')
	{
		/* Prefixes
		c h Cases
		p n People
		t t Toure
		v d Venues
		y y Companies
		*/
		$tim = isset($_POST['tim']) ? $_POST['tim'] : '';

		// Tìm bất kỳ từ form tìm kiếm
		$tim = str_replace('"', '\"', trim($tim));
		$tim = str_replace('.', 'x1dot1x', $tim);
		$tim = str_replace('@', 'x1atmark1x', $tim);
		$tim = str_replace('-', 'x1dash1x', $tim);
		$tim = \fURL::makeFriendly($tim, ' ');
		$tim = str_replace('x1dot1x', '.', $tim);
		$tim = str_replace('x1atmark1x', '@', $tim);
		$tim = str_replace('x1dash1x', '-', $tim);
		if (strlen($tim) < 2) {
			exit('');
		}

		$prefix = substr($tim, 0, 2);
		if ($prefix == 'c ' || $prefix == 'h ') {
			$tim = substr($tim, 2);
			$sql = 'SELECT rtype, rid, found FROM at_search WHERE rtype="case" AND LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
			$found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
		} elseif ($prefix == 't ') {
			$tim = substr($tim, 2);
			$sql = 'SELECT rtype, rid, found FROM at_search WHERE rtype="tour" AND LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
			$found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
		} elseif ($prefix == 'p ' || $prefix == 'n ') {
			$tim = substr($tim, 2);
			$sql = 'SELECT rtype, rid, found FROM at_search WHERE rtype="user" AND LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
			$found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
		} elseif ($prefix == 'v ' || $prefix == 'd ') {
			$tim = substr($tim, 2);
			$sql = 'SELECT rtype, rid, found FROM at_search WHERE rtype="venue" AND LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
			$found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
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
		  $q = 'SELECT rtype, rid, found FROM at_search WHERE rtype="venue" AND ('.$where.') GROUP BY rid LIMIT 20');
		  $found = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();*/
		} elseif ($prefix == 'y ') {
			$tim = substr($tim, 2);
			$sql = 'SELECT rtype, rid, found FROM at_search WHERE rtype="company" AND LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
			$found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
		} else {
			$sql = 'SELECT rtype, rid, found FROM at_search WHERE LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
			$found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
		}

		//'INSERT INTO at_searchlog (dt, user_id, c, s) VALUES (:tim, %i, :tim, :tim)', NOW, myID, $from, $tim);

		if (!empty($found)) {
			foreach ($found as $f) {
				if ($f['rtype'] == 'user') echo '<a class="td-n" href="/users/r/'.$f['rid'].'"><i class="fa fa-user"></i> '.$f['found'].'</a>';
				if ($f['rtype'] == 'case') echo '<a class="td-n" href="/cases/r/'.$f['rid'].'"><i class="fa fa-briefcase"></i> '.$f['found'].'</a>';
				if ($f['rtype'] == 'tour') echo '<a class="td-n" href="/tours/r/'.$f['rid'].'"><i class="fa fa-flag"></i> '.$f['found'].'</a>';
				if ($f['rtype'] == 'venue') echo '<a class="td-n" href="/venues/r/'.$f['rid'].'"><i class="fa fa-map-marker"></i> '.$f['found'].'</a>';
				if ($f['rtype'] == 'company') echo '<a class="td-n" href="/companies/r/'.$f['rid'].'"><i class="fa fa-home"></i> '.$f['found'].'</a>';
			}
		} else {
			exit('');
		}
	}
}
