<?

namespace app\controllers\eventful;

use common\models\Blogpost;
use common\models\Comment;
use common\models\Event2;
use common\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class EventController extends \app\controllers\MyController
{

	public function actionIndex($cat = 0, $tag = '', $author = 0)
	{
		$query = Event2::find();
			//->where(['status'=>'on']);
/*
		if ($cat != 0) {
			$query->andWhere(['cats'=>$cat]);
		}
		if ($tag != '') {
			$query->andWhere(['like', 'tags', $tag]);
		}
		if ($author != 0) {
			$query->andWhere(['author_id'=>$author]);
		}
*/
		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>12,
			]);

		$theEvents = $query
			//->select(['id', 'cats', 'tags', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image'])
			//->with(['author'])
			->offset($pagination->offset)
			->limit($pagination->limit)
			->orderBy('from_dt DESC')
			->all();

		return $this->render('events', [
			'theEvents'=>$theEvents,
			'pagination'=>$pagination,
		]);
	}

	public function actionC()
	{
		$theEvent = new Event2(['scenario'=>'event/c']);
		$theEvent->from_dt = date('Y-m-d 08:00');
		$theEvent->until_dt = date('Y-m-d 22:00');
		$theEvent->timezone = 'Asia/Ho_Chi_Minh';

		if ($theEvent->load(Yii::$app->request->post()) && $theEvent->validate()) {
			$theEvent->created_dt = NOW;
			$theEvent->created_by = MY_ID;
			$theEvent->updated_dt = NOW;
			$theEvent->updated_by = MY_ID;
			$theEvent->is_sticky = 'no';
			$theEvent->status = 'planned';
			$theEvent->save(false);
			return $this->redirect('@web/eventful/events/u/'.$theEvent['id']);
		}

		return $this->render('events_c', [
			'theEvent'=>$theEvent,
		]);
	}

	public function actionR($id = 0)
	{
		$theEvent = Event2::find()
			->where(['id'=>$id])
			->with([
				'createdBy'=>function($q) {
					return $q->select(['id', 'name', 'image']);
				},
			])
			->one();

		if (!$theEvent) {
			throw new HttpException(404, 'Entry not found');
		}

		//if ($theEvent->status != 'on' && !in_array(MY_ID, [1, $theEvent->author_id])) {
		//	throw new HttpException(403);
		//}

		$theEvent->updateCounters(['hits'=>1]);

		if (isset($_POST['email']) && $_POST['email'] != '' && strpos($_POST['email'], '@amicatravel.com') !== false) {
			$this->mgIt(
				'news | '.$theEvent['title'],
				'//mg/blogpost_notification',
				[
					'theEvent'=>$theEvent,
				],
				[
					['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'News'],
					//['to', $_POST['email']],
					['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
					['to', 'duc.manh@amicatravel.com', 'Manh', 'H.'],
				]
			);
			Yii::$app->session->setFlash('success', 'Email has been sent to '.$_POST['email']);
			return $this->redirect(DIR.URI);
		}

		return $this->render('events_r', [
			'theEvent'=>$theEvent,
		]);
	}

	public function actionU($id = 0)
	{
		$theEvent = Event2::findOne($id);
		if (!$theEvent) {
			throw new HttpException(404, 'Event not found');
		}

		if (!in_array(MY_ID, [1, $theEvent->created_by, $theEvent->updated_by])) {
			throw new HttpException(403, 'You are not allowed to edit this event');
		}

		$theEvent->setScenario('event/u');

		if ($theEvent->load(Yii::$app->request->post()) && $theEvent->validate()) {
			$theEvent->updated_dt = NOW;
			$theEvent->updated_by = MY_ID;
			$theEvent->save(false);
			return $this->redirect('@web/eventful/events/r/'.$theEvent['id']);
		}

		$uploadPath = 'upload/eventful/events/'.substr($theEvent['created_dt'], 0, 7).'/'.$theEvent['id'];
		\yii\helpers\FileHelper::createDirectory('/var/www/my.amicatravel.com/'.$uploadPath);
		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', 'https://my.amicatravel.com/'.$uploadPath);
		Yii::$app->session->set('ckfinder_base_dir', '/var/www/my.amicatravel.com/'.$uploadPath);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
		Yii::$app->session->set('ckfinder_resource_name', 'upload');

		return $this->render('events_u', [
			'theEvent'=>$theEvent,
		]);
	}
}
