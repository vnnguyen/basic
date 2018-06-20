<?

namespace app\controllers;

use common\models\Account;
use common\models\File;
use common\models\Folder;
use common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class SysController extends MyController
{
	public function actionIndex()
	{
		return $this->render('//undercon');
	}

	public function actionStats()
	{
		return $this->render('//undercon');
	}

	public function actionStatsPhpinfo()
	{
		return $this->render('stats_phpinfo');
	}

}
