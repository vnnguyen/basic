<?

namespace app\controllers\mcp;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class RbacController extends \app\controllers\MyController
{
    public function actionIndex()
    {
        $theRoles = Yii::$app->authManager->getRoles();
        $thePermissions = Yii::$app->authManager->getPermissions();
        return $this->render('rbac_index', [
            'theRoles'=>$theRoles,
            'thePermissions'=>$thePermissions,
        ]);
    }
}