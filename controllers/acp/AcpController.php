<?

namespace app\controllers\acp;

use \common\models\Account;
use \common\models\Job;
use \common\models\Group;
use \common\models\Hit;
use \common\models\Person;
use \common\models\Property;
use \common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

use yii\db\Schema;
use yii\db\Migration;

class AcpController extends \app\controllers\MyController
{
    public function actionIndex()
    {
        return $this->render('acp_index', [
        ]);
    }

    // Access log (table hits)
    public function actionLog($user_id = 0)
    {
        $query = Hit::find();
            // ->andWhere(['account_id'=>ACCOUNT_ID]);
        
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
                'user'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('hit_dt DESC')
            ->asArray()
            ->all();

        return $this->render('acp_log', [
            'theHits'=>$theHits,
            'pagination'=>$pagination,
            'user_id'=>$user_id,
        ]);
    }
}
