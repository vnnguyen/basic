<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\ForumPost;
use common\models\User;

class ForumTopicController extends MyController
{
	public function actionIndex()
	{
		$theTopics = ForumPost::find()
			->where(['is_topic'=>'yes'])
			->with(['author'])
			->orderBy('id DESC')
			->asArray()
			->all();
		return $this->render('//forum/forum_topics_index', [
			'theTopics'=>$theTopics,
		]);
	}

	public function actionC() {
		$theTopic = new ForumPost;
		$theTopic->scenario = 'forum/topics/c';
		if ($theTopic->load(Yii::$app->request->post()) && $theTopic->validate()) {
			$theTopic->created_at = NOW;
			$theTopic->created_by = Yii::$app->user->id;
			$theTopic->updated_at = NOW;
			$theTopic->updated_by = Yii::$app->user->id;
			$theTopic->status = 'on';
			$theTopic->is_topic = 'yes';
			$theTopic->author_id = Yii::$app->user->id;
			$theTopic->save(false);
			return $this->redirect('forum/topics/'.$theTopic->id);
		}
		return $this->render('//forum/forum_topics_u', [
			'theTopic'=>$theTopic,
		]);
	}

	public function actionR($id = 0)
	{
		$theTopic = ForumPost::find()
			->where(['is_topic'=>'yes', 'id'=>$id])
			->with(['author'])
			->asArray()
			->one();
		$theReplies = ForumPost::find()
			->where(['parent_post_id'=>$theTopic['id']])
			->with(['author'])
			->orderBy('updated_at')
			->asArray()
			->all();
		$theReply = new ForumPost;
		$theReply->scenario = 'forum/topics/r';
		if ($theReply->load(Yii::$app->request->post()) && $theReply->validate()) {
			$theReply->created_at = NOW;
			$theReply->created_by = Yii::$app->user->id;
			$theReply->updated_at = NOW;
			$theReply->updated_by = Yii::$app->user->id;
			$theReply->status = 'on';
			$theReply->is_topic = 'no';
			$theReply->author_id = Yii::$app->user->id;
			$theReply->parent_post_id = $theTopic['id'];
			$theReply->save(false);
			// TODO: Send email to people
			return $this->redirect('forum/topics/'.$theTopic['id']);
		}
		$thePeople = User::find()
			->select(['id', 'name', 'fname', 'lname', 'email'])
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orderBy('lname, fname')
			->asArray()
			->all();
		return $this->render('//forum/forum_topics_r', [
			'theTopic'=>$theTopic,
			'theReplies'=>$theReplies,
			'theReply'=>$theReply,
			'thePeople'=>$thePeople,
		]);
	}

	public function actionU($id = 0) {
		$theTopic = ForumPost::find()
			->where(['is_topic'=>'yes', 'id'=>$id])
			->with(['author'])
			->one();
		if (!$theTopic) {
			throw new HttpException(404, 'Topic not found');
		}
		$theTopic->scenario = 'forum/topics/u';
		if ($theTopic->load(Yii::$app->request->post()) && $theTopic->validate()) {
			$theTopic->updated_at = NOW;
			$theTopic->updated_by = Yii::$app->user->id;
			$theTopic->save(false);
			return $this->redirect('forum/topics/'.$theTopic->id);
		}
		return $this->render('//forum/forum_topics_u', [
			'theTopic'=>$theTopic,
		]);
	}
}
