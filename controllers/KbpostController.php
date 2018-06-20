<?

namespace app\controllers;

use common\models\Kbpost;
use common\models\User;
use common\models\Comment;
use yii\data\Pagination;
use yii\web\HttpException;
use Yii;

class KbpostController extends MyController
{

	public function actionIndex($cat = 0, $tag = '', $author = 0)
	{
		$query = Kbpost::find()
			->where(['status'=>'on']);

		if ($cat != 0) {
			$query->andWhere(['cats'=>$cat]);
		}
		if ($tag != '') {
			$query->andWhere(['like', 'tags', $tag]);
		}
		if ($author != 0) {
			$query->andWhere(['author_id'=>$author]);
		}

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);
		$kbPosts = $query
			->select(['id', 'created_at', 'status', 'title', 'author_id', 'online_from'])
			->with(['author'])
			->offset($pagination->offset)
			->limit($pagination->limit)
			->orderBy('created_at DESC')
			->all();
		return $this->render('kbposts', [
			'kbPosts'=>$kbPosts,
			'pagination'=>$pagination,
			'cat'=>$cat,
			'tag'=>$tag,
			'author'=>$author,
		]);
	}

	public function actionC()
	{
		$theEntry = new Kbpost();
		$theEntry->scenario = 'create';

		if ($theEntry->load($_POST) && $theEntry->validate()) {
			$theEntry->created_at = NOW;
			$theEntry->created_by = MY_ID;
			$theEntry->updated_at = NOW;
			$theEntry->updated_by = MY_ID;
			$theEntry->author_id = MY_ID;
			$theEntry->online_from = NOW;
			$theEntry->status = 'draft';
			$theEntry->save(false);
			return $this->redirect(['kbpost/u', 'id'=>$theEntry->id]);
		}

		return $this->render('kbposts_c', [
			'model'=>$theEntry,
		]);
	}

	public function actionR($id = 0)
	{
		$theEntry = Kbpost::find()
			->where(['id'=>$id])
			->with(['comments', 'author', 'comments.createdBy'])
			->one();

		if (!$theEntry)
			throw new HttpException(404);

		if ($theEntry->status != 'on' && MY_ID != $theEntry->author_id) {
			throw new HttpException(403, 'This post is not publicly viewable.');
		}

		$postComment = new Comment;
		$postComment->scenario = 'create';

		if ($postComment->load($_POST) && $postComment->validate()) {
			$postComment->updated_at = NOW;
			$postComment->updated_by = MY_ID;
			$postComment->status = 'on';
			$postComment->rtype = 'kbpost';
			$postComment->rid = $id;
			$postComment->ip = Yii::$app->request->getUserIP();
			$postComment->save();
			// TODO Notify author and other commenters
			return $this->redirect(DIR.URI.'#comment-id-'.$postComment->id);
		}

		return $this->render('kbposts_r', [
			'theEntry'=>$theEntry,
			'postComment'=>$postComment,
		]);
	}

	public function actionU($id = 0)
	{
		$theEntry = Kbpost::findOne($id);
		if (!$theEntry) {
			throw new HttpException(404);
		}

		if (!in_array(MY_ID, [1,2,3,4,$theEntry->author_id,$theEntry->updated_by])) {
			throw new HttpException(403, 'You are not allowed to edit this post');
		}

		$theEntry->scenario = 'update';

		$authorList = User::find()
			->select(['id', 'name', 'email'])
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orWhere(['id'=>$theEntry['author_id']])
			->orderBy('lname, fname')
			->asArray()
			->all();

		@mkdir('/var/www/my.amicatravel.com/www/upload/kb/posts/'.substr($theEntry->created_at, 0, 7));
		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', 'https://my.amicatravel.com/upload/kb/posts/'.substr($theEntry->created_at, 0, 7).'/'.$theEntry->id);
		Yii::$app->session->set('ckfinder_base_dir', '/var/www/my.amicatravel.com/www/upload/kb/posts/'.substr($theEntry->created_at, 0, 7).'/'.$theEntry->id);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'kb/posts/'.substr($theEntry->created_at, 0, 7).'/'.$theEntry->id);
		Yii::$app->session->set('ckfinder_resource_name', 'kb_posts');


		if ($theEntry->load($_POST) && $theEntry->validate()) {
			if (MY_ID != 1) {
				$theEntry->updated_at = NOW;
				$theEntry->updated_by = MY_ID;
			}
			$theEntry->save(false);
			return $this->redirect('@web/kb/posts/r/'.$theEntry['id']);
		}

		return $this->render('kbposts_u', [
			'theEntry'=>$theEntry,
			'authorList'=>$authorList,
		]);
	}
}
