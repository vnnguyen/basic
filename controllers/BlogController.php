<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use common\models\Blogpost;

class BlogController extends MyController
{

	public function actionIndex() {
		$this->layout = 'community';
		$models = Blogpost::find()
			->select(['id', 'online_from', 'title', 'summary'])
			->with('author')
			->orderBy('online_from DESC')
			->all();
		return $this->render('blog', [
			'models'=>$models,
		]);
	}
	public function actionPostsr($id = 0) {
		$this->layout = 'community';
		$model = Blogpost::find()
			->where(['id'=>$id])
			->with('author')
			->one();
		return $this->render('blog_posts_r', [
			'model'=>$model,
		]);
	}
}
