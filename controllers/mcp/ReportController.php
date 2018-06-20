<?

namespace app\controllers;

use common\models\Job;
use common\models\Group;
use common\models\Person;
use common\models\Property;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class ReportController extends McpController
{
    public function actionIndex()
    {
        return $this->render('report_index');
    }

    public function actionOne()
    {
        return $this->render('report_one');
    }

    public function actionTwo()
    {
        return $this->render('report_two');
    }

    public function actionPhpinfo()
    {
        return $this->render('sys_stats_phpinfo');
    }

}
