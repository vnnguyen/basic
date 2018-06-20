<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use common\models\Event;
use common\models\User;
use common\models\Comment;

class EventController extends MyController
{
	public function actionIndex()
	{
		$getMonth = Yii::$app->request->get('month', 'fromnow');
		$getPerson = Yii::$app->request->get('person', '');
		$getType = Yii::$app->request->get('type', 'all');
		$getStatus = Yii::$app->request->get('status', 'all');
		$getName = Yii::$app->request->get('name', '');

		$monthList = Yii::$app->db
			->createCommand('SELECT SUBSTRING(from_dt,1,7) AS ym FROM at_events GROUP BY ym ORDER BY ym DESC')
			->queryAll();

		$query = Event::find();

		if ($getMonth == 'fromnow') {
			$query->andWhere('from_dt>NOW()');
		} elseif (strlen($getMonth) == 7) {
			$query->andWhere('SUBSTRING(from_dt,1,7)=:ym', [':ym'=>$getMonth]);
		}

		if ((int)$getPerson != 0) {
			$query->andWhere(['']);
		}

		if ($getType != 'all') {
			$query->andWhere(['stype'=>$getType]);
		}

		if ($getStatus != 'all') {
			$query->andWhere(['status'=>$getStatus]);
		}

		if (strlen($getName) > 2) {
			$query->andWhere(['like', 'title', $getName]);
		}

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);

		$theEvents = $query
			->with([
				'people'=>function($q) {
					return $q->select(['id', 'name']);
				}
			])
			->orderBy('from_dt, until_dt')
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();

		return $this->render('events', [
			'pages'=>$pages,
			'theEvents'=>$theEvents,
			'getMonth'=>$getMonth,
			'getPerson'=>$getPerson,
			'getType'=>$getType,
			'getStatus'=>$getStatus,
			'getName'=>$getName,
			'monthList'=>$monthList,
		]);
	}

	public function actionC() {
		$theEvent = new Event;
		$theEvent->scenario = 'events/c';

		if (isset($_POST['Event'])) {
			//\fCore::expose($_POST);
			//exit;
		}

		if ($theEvent->load(Yii::$app->request->post())) {
			$theEvent->created_at = NOW;
			$theEvent->created_by = Yii::$app->user->id;
			$theEvent->updated_at = NOW;
			$theEvent->updated_by = Yii::$app->user->id;
			$theEvent->status = 'on';
			$theEvent->stype = 'on';
			if ($theEvent->save()) {
				return $this->redirect('events');
			}
		}

		$allPeople = User::find()
			->select(['id', 'name'])
			->where(['is_member'=>'yes'])
			->orderBy('lname', 'fname')
			->asArray()
			->all();

		return $this->render('events_c', [
			'theEvent'=>$theEvent,
			'allPeople'=>$allPeople,
		]);
	}

	public function actionR($id = 0) {
		$theEvent = Event::findOne($id);

		if (!$theEvent) {
			throw new HttpException(404, 'Event not found');
		}

		$theComments = Comment::find()
			->where(['rtype'=>'event', 'rid'=>$theEvent['id']])
			->with(['createdBy'])
			->orderBy('created_at')
			->asArray()
			->all();

		$theComment = new Comment;
		$theComment->scenario = 'events/r';

		if ($theComment->load(Yii::$app->request->post())) {
			$theComment->rtype = 'event';
			$theComment->rid = $theEvent['id'];
			$theComment->status = 'on';
			if ($theComment->save()) {
				return $this->redirect(URI);
			}
		}

		return $this->render('events_r', [
			'theEvent'=>$theEvent,
			'theComments'=>$theComments,
			'theComment'=>$theComment,
		]);
	}

	public function actionU($id = 0) {
		$theEvent = Event::find()
			->where(['id'=>$id])
			->with(['people'])
			->one();
		if (!$theEvent) {
			throw new HttpException(404, 'Entry not found');
		}

		$theEvent->scenario = 'events/u';

		$theEvent->users = [];
		foreach ($theEvent['people'] as $user) {
			$theEvent->users[] = $user['id'];
		}

		if (isset($_POST['Event'])) {
			//\fCore::expose($_POST);
			//exit;
		}

		if ($theEvent->load(Yii::$app->request->post())) {
			$theEvent->updated_at = NOW;
			$theEvent->updated_by = Yii::$app->user->id;
			if ($theEvent->save()) {
				foreach ($_POST['Event']['users'] as $user) {/*
					$theUser = User::find($user);
					$theEvent->link('people', $theUser, [
						'updated_at'=>NOW,
						'updated_by'=>Yii::$app->user->id,
						'status'=>'ok',
					]);*/
				}
				return $this->redirect('events?month='.substr($theEvent['from_dt'], 0, 7));
			}
		}

		$allPeople = User::find()
			->select(['id', 'name'])
			->where(['is_member'=>'yes'])
			->orderBy('lname', 'fname')
			->asArray()
			->all();

		return $this->render('events_c', [
			'theEvent'=>$theEvent,
			'allPeople'=>$allPeople,
		]);
	}

	public function actionD($id = 0) {
		$theEvent = Event::findOne($id);
		if (!$theEvent) {
			throw new HttpException(404, 'Entry not found');
		}

		return $this->render('events_d', [
			'theEvent'=>$theEvent,
		]);
	}

}
