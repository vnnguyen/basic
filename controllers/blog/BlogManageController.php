<?

namespace app\controllers\blog;

use common\models\Post;
use common\models\Comment;
use common\models\User;
use yii\data\Pagination;
use yii\web\HttpException;
use Yii;

class BlogManageController extends \app\controllers\MyController
{

	public function actionIndex($cat = 0, $tag = '', $author = 0)
	{
		// Lanh dao, DM, cong doan, nhan su
		if (!in_array(MY_ID, [1,2,3,4,22447,24229,18598])) {
			throw new HttpException(403, 'Access denied.');
		}

		$query = Post::find()->where(['channel'=>'blog']);

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
			'pageSize'=>50,
			]);

		$blogPosts = $query
			->select(['id', 'cats', 'tags', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image'])
			->with(['author'])
			->offset($pagination->offset)
			->limit($pagination->limit)
			->orderBy('online_from DESC')
			->all();

		$latestComments = Comment::find()
			->select(['id', 'created_at', 'created_by', 'rid'])
			->with(['createdBy', 'blogpost'])
			->where(['status'=>'on', 'rtype'=>'blogpost'])
			->orderBy('created_at DESC')
			->limit(5)
			->all();

		return $this->render('blog_manage', [
			'blogPosts'=>$blogPosts,
			'pagination'=>$pagination,
			'cat'=>$cat,
			'tag'=>$tag,
			'author'=>$author,
			'latestComments'=>$latestComments,
		]);
	}

	public function actionMyPosts($cat = 0, $tag = '', $year = 0, $blog = 0)
	{
		$query = Post::find()
			->where(['status'=>'on', 'author_id'=>MY_ID]);

		if ($cat != 0) {
			$query->andWhere(['cats'=>$cat]);
		}
		if ($tag != '') {
			$query->andWhere(['like', 'tags', $tag]);
		}

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>50,
			]);

		$blogPosts = $query
			->select(['id', 'cats', 'tags', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image'])
			->with(['author'])
			->offset($pagination->offset)
			->limit($pagination->limit)
			->orderBy('online_from DESC')
			->all();

		return $this->render('blog_my-posts', [
			'blogPosts'=>$blogPosts,
			'pagination'=>$pagination,
			'cat'=>$cat,
			'tag'=>$tag,
			'year'=>$year,
			'blog'=>$blog,
		]);
	}
}
