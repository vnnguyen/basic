<?php

namespace app\controllers;

use Yii;
use common\models\Blogpost;
use common\models\Comment;
use common\models\User;
use yii\data\Pagination;
use yii\web\HttpException;

class BlogpostController extends MyController
{

	public function actionIndex()
	{
		$query = Blogpost::find()
			->where(['status'=>'on']);
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>10,
			]);
		$blogPosts = $query
			->select(['id', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image'])
			->with(['author'])
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy('online_from DESC')
			->all();
		$latestComments = Comment::find()
			->select(['id', 'created_at', 'created_by', 'rid'])
			->with(['createdBy', 'blogpost'])
			->where(['status'=>'on', 'rtype'=>'blogpost'])
			->orderBy('created_at DESC')
			->limit(5)
			->all();
		return $this->render('blogposts', [
			'blogPosts'=>$blogPosts,
			'pages'=>$pages,
			'latestComments'=>$latestComments,
		]);
	}

	public function actionC()
	{
		$theEntry = new Blogpost(['scenario'=>'create']);

		if ($theEntry->load($_POST) && $theEntry->validate()) {
			$theEntry->created_at = NOW;
			$theEntry->created_by = MY_ID;
			$theEntry->updated_at = NOW;
			$theEntry->updated_by = MY_ID;
			$theEntry->blog_id = 1;
			$theEntry->author_id = MY_ID;
			$theEntry->is_sticky = 'no';
			$theEntry->status = 'draft';
			$theEntry->save(false);
			return $this->redirect(['blogpost/u', 'id'=>$theEntry['id']]);
		}

		return $this->render('blogposts_c', [
			'theEntry'=>$theEntry,
		]);
	}

	public function actionR($id = 0)
	{
		$theEntry = Blogpost::find()
			->where(['id'=>$id])
			->with([
				'comments',
				'author',
				'author.profileMember',
				'comments.createdBy'=>function($q) {
					return $q->select(['id', 'name', 'image']);
				},
			])
			->one();

		if (!$theEntry) {
			throw new HttpException(404, 'Entry not found');
		}

		if ($theEntry->status != 'on' && !in_array(MY_ID, [1, $theEntry->author_id])) {
			throw new HttpException(403);
		}

		$theEntry->updateCounters(['hits'=>1]);

		$postComment = new Comment;
		$postComment->scenario = 'create';

		$latestPosts = Blogpost::find()
			->select(['id', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image'])
			->with(['author'])
			->limit(4)
			->orderBy('online_from DESC')
			->all();

		if ($postComment->load($_POST) && $postComment->validate()) {
			$postComment->updated_at = NOW;
			$postComment->updated_by = MY_ID;
			$postComment->status = 'on';
			$postComment->rtype = 'blogpost';
			$postComment->rid = $id;
			$postComment->ip = Yii::$app->request->getUserIP();
			$postComment->save();
			// TODO Notify author and other commenters
			return $this->redirect(URI.'#comment-id-'.$postComment->id);
		}

		if (MY_ID == 1111) {
			$this->layout = 'canvas';
			return $this->render('blogposts_r_canvas', [
				'theEntry'=>$theEntry,
				'postComment'=>$postComment,
				'latestPosts'=>$latestPosts,
			]);
		}

		return $this->render('blogposts_r', [
			'theEntry'=>$theEntry,
			'postComment'=>$postComment,
		]);
	}

	public function actionU($id = 0)
	{
		$this->layout = 'main';
		$theEntry = Blogpost::findOne($id);
		if (!$theEntry) {
			throw new HttpException(404);
		}

		if (!in_array(MY_ID, [1, $theEntry->author_id, $theEntry->updated_by])) {
			throw new HttpException(403, 'You are not allowed to edit this post');
		}

		$theEntry->setScenario('update');

		$authorList = User::find()
			->select(['id', 'name'])
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orWhere(['id'=>$theEntry->author_id])
			->orderBy('lname, fname')
			->asArray()
			->all();

		if ($theEntry->load($_POST) && $theEntry->validate()) {
			if (MY_ID != 1) {
			$theEntry->updated_at = NOW;
			$theEntry->updated_by = MY_ID;
			}
			$theEntry->save(false);
			return $this->redirect(['blogpost/r', 'id'=>$theEntry['id']]);
		}

		@mkdir('/var/www/my.amicatravel.com/upload/blog/posts/'.substr($theEntry['created_at'], 0, 7));
		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', 'https://my.amicatravel.com/upload/blog/posts/'.substr($theEntry['created_at'], 0, 7).'/'.$theEntry['id']);
		Yii::$app->session->set('ckfinder_base_dir', '/var/www/my.amicatravel.com/upload/blog/posts/'.substr($theEntry['created_at'], 0, 7).'/'.$theEntry['id']);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'blog/posts/'.substr($theEntry['created_at'], 0, 7).'/'.$theEntry['id']);
		Yii::$app->session->set('ckfinder_resource_name', 'blog_posts');

		return $this->render('blogposts_u', [
			'theEntry'=>$theEntry,
			'authorList'=>$authorList,
		]);
	}
}
