<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\NestedSet;


class CatController extends MyController
{
	public function actionIndex()
	{
$countries = NestedSet::findOne(1);
if (!$countries) {
	$countries = new NestedSet();
	$countries->makeRoot();
	$russia = new NestedSet();
	$russia->prependTo($countries);
	$australia = new NestedSet();
	$australia->appendTo($countries);
	$vietnam = new NestedSet();
	$vietnam->appendTo($countries);
	$hanoi = new NestedSet();
	$hanoi->appendTo($vietnam);
	$hoankiem = new NestedSet();
	$hoankiem->appendTo($hanoi);
	$dongda = new NestedSet();
	$dongda->appendTo($hanoi);
} else {
	$cat = new NestedSet(['rtype'=>'hello', 'rid'=>4]);
	$cat->makeRoot();
}

$items = $countries->children()->asArray()->all();
\fCore::expose($items);

echo '<hr>';
foreach ($items as $item) {
	echo '<br>', str_repeat(' &mdash; ', $item['depth']), $item['id'], ' : ', $item['rtype'], ' : ', $item['rid'];
}

exit;

		return $this->render('cats', [
			'cats'=>$cats,
			'rootCat'=>$rootCat,
			]
		);
	}

	public function actionC()
	{
		return $this->render('days_u', [
			'theDay'=>$theDay,
			]);
	}

	public function actionR($id = 0) {
		return $this->render('days_r', [
			'theDay'=>$theDay,
		]);
	}

}
