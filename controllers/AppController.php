<?php

namespace app\controllers;

use Yii;
use common\models\Note;
use common\models\User;

class AppController extends MyController
{
	public function actionIndex() {
		// List of sellers
		$sellerList = [];
		if (\app\helpers\User::inGroups('any:it,lanhdao,banhang')) {
			$sql = 'SELECT u.id, u.fname, u.lname, u.email FROM persons u, at_cases c WHERE u.status="on" AND c.owner_id=u.id GROUP BY c.owner_id ORDER BY u.lname, u.fname';
			$sellerList = Yii::$app->db->createCommand($sql)->queryAll();
		}
		// New tour, Huan & NgocHB
		$theTours = [];
		if (in_array(Yii::$app->user->id, [1, 118])) {
			$theTours = Yii::$app->db
				->createCommand('select t.id, t.code, t.se, t.uo, ct.day_from, ct.day_count, (select name from persons where id=se limit 1) as se_name from at_tours t, at_ct ct where ct.id=t.ct_id AND t.status="draft" AND op!=1 order by ct.day_from')
				->queryAll();
		}

		if (in_array(Yii::$app->user->id, [1,2,3,4,695,4432])) {
			// All notes
			$theNotes = Note::find()
				->select('via, id, co, cb, uo, ub, title, from_id, rtype, rid, priority')
				->with(['from', 'to', 'relatedCase', 'relatedTour'])
				->orderBy('uo DESC')
				->limit(20)
				->asArray()
				->all();
		} else {
			$theNotes = Note::find()
				->select('at_messages.via, at_messages.id, at_messages.co, at_messages.cb, at_messages.uo, at_messages.ub, at_messages.title, at_messages.from_id, at_messages.rtype, at_messages.rid, at_messages.priority')
				->innerJoinWith([
					'sto'=>function($q) {
						$q->andWhere(['persons.id'=>Yii::$app->user->id]);
						return $q;
					},
				])
				->with(['from', 'to', 'relatedCase', 'relatedTour'])
				->orderBy('uo DESC')
				->limit(20)
				->asArray()
				->all();
		}

		// The tasks
		$theTasks = Yii::$app->db
			->createCommand('SELECT t.*, u.name AS ub_name FROM at_tasks t, persons u, at_task_user tu WHERE t.status="on" AND tu.completed_dt=0 AND u.id=t.ub AND tu.task_id=t.id AND tu.user_id=:id ORDER BY fuzzy, SUBSTRING(t.due_dt,1,16), t.is_priority LIMIT 15', [':id'=>Yii::$app->user->id])
			->queryAll();

		// Task id list
		foreach ($theTasks as $t) {
			$theTaskIdList[] = $t['id'];
		}

		// The task users
		if (empty($theTaskIdList)) {
			$theTaskUsers = [];
		} else {
			$theTaskUsers = Yii::$app->db
				->createCommand('SELECT u.name AS user_name, tu.* FROM persons u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $theTaskIdList).') ORDER BY lname')
				->queryAll();
		}

		// Online users
		$onlineUsers = Yii::$app->db
			->createCommand('SELECT u.id, u.name, u.image FROM persons u, at_hits h WHERE h.user_id=u.id AND h.created_at > DATE_SUB(:now, INTERVAL 15 MINUTE) GROUP BY u.id LIMIT 100', [':now'=>NOW])
		 	->queryAll();

		// Starred items
		$theStarredItems = Yii::$app->db
			->createCommand('SELECT rtype, rid, name FROM at_stars WHERE stype="s" AND ub=:id ORDER BY uo DESC LIMIT 10', [':id'=>Yii::$app->user->id])
		 	->queryAll();

		$theViewedItems = Yii::$app->db
			->createCommand('SELECT rtype, rid, name FROM at_stars WHERE stype="v" AND ub=:id ORDER BY uo DESC LIMIT 10', [':id'=>Yii::$app->user->id])
			->queryAll();

		// onLeaves
		$absentPeople = Yii::$app->db
			->createCommand('SELECT u.id, u.image, u.name, e.name AS e_name, e.from_dt, e.until_dt FROM persons u, at_events e, at_event_user eu WHERE e.status="on" AND u.id=eu.user_id AND eu.event_id=e.id AND e.from_dt>=:today AND e.from_dt<=:tomorrow ORDER BY from_dt LIMIT 20', [':today'=>date('Y-m-d 00:00:00', strtotime('- 1 days')), ':tomorrow'=>date('Y-m-d 23:59:59', strtotime('+ 2 days'))])
			->queryAll();

		// Newly-asigned cases
		$newlyAssignedCases = Yii::$app->db
			->createCommand('SELECT c.id, c.name, c.ao FROM at_cases c WHERE c.owner_id!=0 ORDER BY c.id DESC LIMIT 5')
			->queryAll();
		// Newly-asigned tours
		// New payments
		$newPayments = Yii::$app->db
			->createCommand('SELECT p.*, u.name AS updated, t.code AS tour_code, t.id AS tour_id FROM at_payments p, at_bookings b, at_tours t, persons u WHERE u.id=p.updated_by AND b.id=p.booking_id AND t.ct_id=b.product_id AND b.created_by=:id ORDER BY p.updated_at DESC LIMIT 5', [':id'=>Yii::$app->user->id])
			->queryAll();

		return $this->render('app_index', [
			'theNotes'=>$theNotes,
			'theTours'=>$theTours,
			'onlineUsers'=>$onlineUsers,
			'theTasks'=>$theTasks,
			'theTaskUsers'=>$theTaskUsers,
			'theStarredItems'=>$theStarredItems,
			'theViewedItems'=>$theViewedItems,
			'absentPeople'=>$absentPeople,
			'newlyAssignedCases'=>$newlyAssignedCases,
			'newPayments'=>$newPayments,
			'sellerList'=>$sellerList,
		]);
	}

	public function actionChangesite($site = 'work')
	{
		if (in_array($site, array_keys(Yii::$app->params['allSites'])))
			Yii::$app->session->set('activeSite', $site);
		return $this->redirect('@web');
	}

	public function actionChangelanguage($lang = 'en') {
		if (in_array($lang, array_keys(Yii::$app->params['allLanguages'])))
			Yii::$app->session->set('activeLanguage', $lang);
		return $this->redirect('@web');
	}

	public function actionCkfinder()
	{
		return $this->renderPartial('ckfinder');
	}
}
