<?

namespace app\controllers\sys;

use \common\models\Account;
use \common\models\File;
use \common\models\Folder;
use \common\models\User;

use Yii;
use yii\web\HttpException;

class SysController extends \app\controllers\MyController
{
	public function actionIndex()
	{
		return $this->render('sys_index');
	}
}
