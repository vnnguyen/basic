<?
namespace app\controllers\mcp;

use \common\models\ListItem;
use \common\models\Listt;

use Yii;
use yii\web\HttpException;

class ListController extends \app\controllers\MyController
{
	/**
	 * Display all lists for user
	 * @return null
     */
	public function actionIndex()
	{
		$theLists = Listt::find()
			->where(['account_id'=>0])
			->orderBy('grouping, sorder, name')
			->asArray()
			->all();
		return $this->render('list_index', [
			'theLists'=>$theLists,
		]);
	}

	// Add, edit lists
	public function actionAdmin()
	{
		if (1 != USER_ID) {
			throw new HttpException(403);
		}

		$theLists = Listt::find()
			->where(['account_id'=>0])
			->orderBy('sorder, name')
			->asArray()
			->all();

		return $this->render('list_admin', [
			'theLists'=>$theLists,
		]);
	}

	public function actionC()
	{
		if (USER_ID > 4) {
			throw new HttpException(403, 'Access denied.');
		}
		$theList = new Listt;
		$theList->scenario = 'list/c';

		if ($theList->load(Yii::$app->request->post()) && $theList->validate()) {
			$theList->created_dt = NOW;
			$theList->created_by = USER_ID;
			$theList->updated_dt = NOW;
			$theList->updated_by = USER_ID;
			$theList->account_id = 0;
			$theList->status = 'on';
			$theList->save(false);
			return $this->redirect('/mcp/lists');
		}
		return $this->render('list_u', [
			'theList'=>$theList,
		]);
	}

	public function actionR($id = 0)
	{
		$theList = Listt::find()
			->where(['account_id'=>0, 'id'=>$id])
			->with([
				'items',
				'items.parent',
				])
			->one();
		if (!$theList) {
			throw new HttpException(404, 'List not found');
		}

		$theItem = new ListItem;
		$theItem->scenario = 'item/c';
		$theItem->parent_listitem_id = 0;

		if ($theItem->load(Yii::$app->request->post()) && $theItem->validate()) {
			$theItem->account_id = 0;
			$theItem->created_dt = NOW;
			$theItem->created_by = USER_ID;
			$theItem->updated_dt = NOW;
			$theItem->updated_by = USER_ID;
			$theItem->list_id = $theList['id'];
			$theItem->status = 'on';
			$theItem->save(false);
			return $this->redirect('/mcp/lists/r/'.$theList['id']);
		}

		return $this->render('list_r', [
			'theList'=>$theList,
			'theItem'=>$theItem,
			//'theItems'=>$theItems,
		]);
	}

	public function actionU($id = 0)
	{
		$theList = Listt::find()
			->where(['account_id'=>0, 'id'=>$id])
			->one();
		if (!$theList) {
			throw new HttpException(404, 'List not found');
		}
		if (USER_ID > 4) {
			throw new HttpException(404, 'Access denied');
		}
		$theList->scenario = 'list/u';

		if ($theList->load(Yii::$app->request->post()) && $theList->validate()) {
			$theList->updated_dt = NOW;
			$theList->updated_by = USER_ID;
			$theList->save(false);
			return $this->redirect('/mcp/lists');
		}
		return $this->render('list_u', [
			'theList'=>$theList,
		]);
	}

	public function actionD($id = 0)
	{
		$theList = false;
		return $this->render('list_d', [
			'theList'=>$theList,
		]);
	}

}
