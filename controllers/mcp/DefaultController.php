<?

namespace app\controllers\mcp;

use \common\models\Account;
use \common\models\Job;
use \common\models\Group;
use \common\models\Hit;
use \common\models\Person;
use \common\models\Property;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

use yii\db\Schema;
use yii\db\Migration;

class DefaultController extends McpController
{
    public function actionEvp()
    {
        return $this->render('evp');
    }
    public function actionIndex()
    {
        $recentJobs = Job::find()
            ->where(['account_id'=>ACCOUNT_ID])
            ->with([
                'client'=>function($q) {
                    $q->select(['id', 'name']);
                },
                'owner'=>function($q) {
                    $q->select(['id', 'name']);
                },
                ])
            ->orderBy('in_date DESC')
            ->limit(5)
            ->asArray()
            ->all();

        $recentProjects = Property::find()
            ->select(['id', 'name', 'updated_by', 'addr_district', 'addr_city'])
            ->where(['account_id'=>ACCOUNT_ID, 'is_project'=>'yes'])
            ->with([
                'featureImage',
                'updatedBy'=>function($q) {
                    $q->select(['id', 'name']);
                },
                ])
            ->orderBy('created_dt DESC')
            ->limit(5)
            ->asArray()
            ->all();

        // Online users
        $recentlyCreatedAccounts = Account::find()
            ->select(['id', 'name', 'subscriptions', 'created_dt', 'created_by'])
            ->with([
                'createdBy'=>function($q){
                    return $q->select(['id', 'name']);
                }
                ])
            ->orderBy('created_dt DESC')
            ->limit(6)
            ->asArray()
            ->all();

        return $this->render('home', [
            'recentJobs'=>$recentJobs,
            'recentProjects'=>$recentProjects,
            'recentlyCreatedAccounts'=>$recentlyCreatedAccounts,
        ]);
    }

    // Access log (table hits)
    public function actionLog($account_id = 0, $user_id = 0)
    {
        $query = Hit::find();

        if ($account_id != 0) {
            $query->andWhere(['account_id'=>ACCOUNT_ID]);
        }

        if ($user_id != 0) {
            $query->andWhere(['user_id'=>$user_id]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);

        $theHits = $query
            ->with([
                'account'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'user'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('hit_dt DESC')
            ->asArray()
            ->all();

        return $this->render('log', [
            'theHits'=>$theHits,
            'pagination'=>$pagination,
            'account_id'=>$account_id,
            'user_id'=>$user_id,
        ]);
    }

    public function actionPhpinfo()
    {
        return $this->render('phpinfo');
    }
}
