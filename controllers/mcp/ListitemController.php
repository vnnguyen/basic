<?

namespace app\controllers\mcp;

use common\models\ListItem;
use common\models\Listt;
use common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class ListitemController extends \app\controllers\MyController
{
	public function actionU($id = 0)
	{
		$theItem = Listitem::find()
			->where(['account_id'=>0, 'id'=>$id])
			->one();
		if (!$theItem) {
			throw new HttpException(404, 'Item not found');
		}
		$theItem->scenario = 'item/u';

		$theList = Listt::find()
			->where(['account_id'=>0, 'id'=>$theItem['list_id']])
			->with([
				'items'=>function($q) {
					return $q->select(['id', 'name', 'list_id'])
						->andWhere(['status'=>'on', 'parent_listitem_id'=>0])
						->andWhere('parent_listitem_id=0');
				}
				])
			->asArray()
			->one();
		if (!$theList) {
			throw new HttpException(404, 'Item not found');
		}

		if ($theItem->load(Yii::$app->request->post()) && $theItem->validate()) {
			$theItem->updated_dt = NOW;
			$theItem->updated_by = USER_ID;
			$theItem->save(false);
			return $this->redirect('/mcp/lists/r/'.$theItem['list_id']);
		}
		return $this->render('//mcp/list/listitem_u', [
			'theItem'=>$theItem,
			'theList'=>$theList,
		]);
	}

	public function actionD($id = 0)
	{
		$theItem = Listitem::find()
			->where(['account_id'=>0, 'id'=>$id])
			->one();
		if (!$theItem) {
			throw new HttpException(404, 'Item not found');
		}
		if ($theItem->status == 'deleted') {
			$theItem->status = 'on';
		} else {
			$theItem->status = 'deleted';
		}
		$theItem->save(false);
		return $this->redirect('/mcp/lists/r/'.$theItem['list_id']);
	}

}
