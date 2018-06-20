<?

namespace app\controllers;

use common\models\Product;
use app\models\Service;


use Yii;
use yii\data\ActiveDataProvider;
use yii\data\GridView;
use yii\data\Pagination;
use yii\web\HttpException;

class ServiceController extends MyController
{
	public function actionIndex()
	{
		$query = Service::find();


		$countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theServices = $query
        ->orderBy('created_dt DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
		return $this->render('service_index', [
			'pages' => $pages,
			'theServices' => $theServices
		]);

	}
	public function actionC()
	{
		$theService = new Service;
		if ($theService->load(Yii::$app->request->post())) {
			$theTour = Product::find()->where('UPPER(op_code)=:code', [':code' => strtoupper($theService->code)])->one();
			if (!$theTour) {
				throw new HttpException(404, "The tour not found");
			}
			$theService->code = $theTour->op_code;
			$theService->created_dt = NOW;
			$theService->created_by = USER_ID;

			if (!$theService->save(false)) {
				throw new HttpException(401, "The service is not saved");
			}
			$this->redirect('/service/u/'.$theService['id']);
		}
		return $this->render('service_form', [
			'theService' => $theService
		]);

	}
	public function actionU($id = 0)
	{
		$theService = Service::findOne($id);
		if (!$theService) {
			throw new HttpException(404, "The service not found");
		}
		if ($theService->load(Yii::$app->request->post())) {
			$theService->updated_dt = NOW;
			$theService->updated_by = USER_ID;
			if (!$theService->save(false)) {
				throw new HttpException(401, "The service is not saved");
			}
			$this->redirect('/service/v/'.$theService['id']);
		}
		return $this->render('service_form', [
			'theService' => $theService
		]);
	}
	public function actionV($id = 0)
	{
		$theService = Service::findOne($id);
		if (!$theService) {
			throw new HttpException(404, "The service not found");
		}
		return $this->render('service_v', [
			'theService' => $theService
		]);
	}
	public function actionD($id = 0)
	{
		$theService = Service::findOne($id);
		if ($theService) {
			$theService->delete();
		}
		$this->redirect('/service/index');
	}
}