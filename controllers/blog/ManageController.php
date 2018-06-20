<?php

namespace app\controllers\blog;

use Yii;
use common\models\Blogpost;
use common\models\Comment;
use common\models\User;
use yii\data\Pagination;
use yii\web\HttpException;

class ManageController extends \app\controllers\MyController
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
		$model = new Blogpost(['scenario'=>'create']);

		if ($model->load($_POST) && $model->validate()) {
			$model->author_id = Yii::$app->user->id;
			$model->status = 'draft';
			$model->save();
			return $this->redirect(['blogpost/u', 'id'=>$model->id]);
		}

		return $this->render('blogposts_c', [
			'model'=>$model,
		]);
	}

	public function actionR($id = 0)
	{
		$model = Blogpost::find()
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

		if (!$model)
			throw new HttpException(404, 'Entry not found');

		if ($model->status != 'on' && MY_ID != $model->author_id) {
			throw new HttpException(403);
		}

		$model->updateCounters(['hits'=>1]);

		$postComment = new Comment;
		$postComment->scenario = 'create';

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

		if (MY_ID == 1) {
			$this->layout = 'newspaper';
			return $this->render('blogposts_r_newspaper', [
				'model'=>$model,
				'postComment'=>$postComment,
			]);
		}

		return $this->render('blogposts_r', [
			'model'=>$model,
			'postComment'=>$postComment,
		]);
	}

	public function actionU($id = 0)
	{
		$this->layout = 'main';
		$model = Blogpost::findOne($id);
		if (!$model) {
			throw new HttpException(404);
		}

		if (Yii::$app->user->id != 1 && Yii::$app->user->id != $model->author_id && Yii::$app->user->id != $model->updated_by)
			throw new HttpException(403, 'You are not allowed to edit this post');

		$model->setScenario('update');

		$authorList = User::find()
			->select(['id', 'name'])
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orWhere(['id'=>$model->author_id])
			->orderBy('lname, fname')
			->asArray()
			->all();

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return $this->redirect(['blogpost/r', 'id'=>$id]);
		}

		@mkdir('/var/www/my.amicatravel.com/upload/blog/posts/'.substr($model->created_at, 0, 7));
		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', 'http://my.amicatravel.com/upload/blog/posts/'.substr($model->created_at, 0, 7).'/'.$model->id);
		Yii::$app->session->set('ckfinder_base_dir', '/var/www/my.amicatravel.com/upload/blog/posts/'.substr($model->created_at, 0, 7).'/'.$model->id);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'blog/posts/'.substr($model->created_at, 0, 7).'/'.$model->id);
		Yii::$app->session->set('ckfinder_resource_name', 'blog_posts');

		return $this->render('blogposts_u', [
			'model'=>$model,
			'authorList'=>$authorList,
		]);
	}
}
