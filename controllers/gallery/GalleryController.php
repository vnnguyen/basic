<?
namespace app\controllers\gallery;

use common\models\Collection;

class GalleryController extends \app\controllers\MyController
{
	public function actionIndex()
	{
		$theCollections = Collection::find()
			->where(['status'=>'on'])
			->orderBy('event_date DESC')
			->asArray()
			->all();
		return $this->render('gallery', [
			'theCollections'=>$theCollections,
		]);
	}

	public function actionManage()
	{
		$theCollections = Collection::find()
			->where(['status'=>'on'])
			->orderBy('event_date DESC')
			->asArray()
			->all();
		return $this->render('gallery_manage', [
			'theCollections'=>$theCollections,
		]);
	}
}
