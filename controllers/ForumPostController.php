<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\ForumPost;

class ForumPostController extends MyController
{
	public function actionIndex() {
		$thePosts = ForumPost::find()
			->all();
		return $this->render('//forum/forum_posts_index', [
			'thePosts'=>$thePosts,
		]);
	}
}
