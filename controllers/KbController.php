<?php

namespace app\controllers;

use Yii;
use common\models\Kbpost;
use common\models\Kblist;
use common\models\Kbbook;

class KbController extends MyController
{

	public function actionIndex()
	{
		$kbPosts = Kbpost::find()
			->select(['id', 'title', 'author_id', 'created_at'])
			->with(['author'])
			->limit(20)
			->orderBy('created_at DESC')
			->asArray()
			->all();
		$kbLists = Kblist::find()
			->select(['id', 'title', 'created_at', 'alias'])
			//->with(['author'])
			->limit(20)
			->orderBy('created_at DESC')
			->asArray()
			->all();
		return $this->render('kb', [
			'kbPosts'=>$kbPosts,
			'kbLists'=>$kbLists,
			//'kbbooks'=>$kbbooks,
		]);
	}
}
