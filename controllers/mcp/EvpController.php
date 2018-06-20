<?

namespace app\controllers\mcp;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class EvpController extends \app\controllers\MyController
{
    public function actionIndex()
    {
        return $this->render('evp_index');
    }
}
