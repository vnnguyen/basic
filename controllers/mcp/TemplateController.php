<?
namespace app\controllers\mcp;

use \common\models\Template;

use Yii;
use yii\web\HttpException;

class TemplateController extends \app\controllers\MyController
{
    /**
     * Display all templates for system
     * @return null
     */

    public function actionIndex()
    {
        $theTemplates = Template::find()
            ->where(['account_id'=>null])
            ->orderBy('name')
            ->asArray()
            ->all();
        return $this->render('template_index', [
            'theTemplates'=>$theTemplates,
        ]);
    }

    // Add, edit lists
    public function actionAdmin()
    {
        if (1 != USER_ID) {
            throw new HttpException(403);
        }

        $theTemplates = Listt::find()
            ->where(['account_id'=>ACCOUNT_ID])
            ->orderBy('sorder, name')
            ->asArray()
            ->all();

        return $this->render('list_admin', [
            'theTemplates'=>$theTemplates,
        ]);
    }

    public function actionC()
    {
        if (USER_ID > 4) {
            throw new HttpException(403, 'Access denied.');
        }
        $theTemplate = new Template;
        $theTemplate->scenario = 'template/c';

        if ($theTemplate->load(Yii::$app->request->post()) && $theTemplate->validate()) {
            $theTemplate->account_id = null;
            $theTemplate->created_dt = NOW;
            $theTemplate->created_by = USER_ID;
            $theTemplate->updated_dt = NOW;
            $theTemplate->updated_by = USER_ID;
            $theTemplate->status = 'on';
            $theTemplate->save(false);
            return $this->redirect('/mcp/templates');
        }
        return $this->render('template_u', [
            'theTemplate'=>$theTemplate,
        ]);
    }

    public function actionR($id = 0)
    {
        $theTemplate = Template::find()
            ->where(['account_id'=>null, 'id'=>$id])
            ->with([
                'updatedBy',
                ])
            ->asArray()
            ->one();
        if (!$theTemplate) {
            throw new HttpException(404, 'Template not found');
        }

        return $this->render('template_r', [
            'theTemplate'=>$theTemplate,
        ]);
    }

    public function actionU($id = 0)
    {
        $theTemplate = Template::find()
            ->where(['account_id'=>null, 'id'=>$id])
            ->one();
        if (!$theTemplate) {
            throw new HttpException(404, 'Template not found.');
        }
        if (USER_ID > 4) {
            throw new HttpException(404, 'Access denied.');
        }

        $theTemplate->scenario = 'template/u';

        if ($theTemplate->load(Yii::$app->request->post()) && $theTemplate->validate()) {
            $theTemplate->updated_dt = NOW;
            $theTemplate->updated_by = USER_ID;
            $theItem->save(false);
            return $this->redirect('/mcp/templates/r/'.$theTemplate['id']);
        }

        return $this->render('template_u', [
            'theTemplate'=>$theTemplate,
        ]);
    }

    public function actionD($id = 0)
    {
        $theTemplate = false;
        return $this->render('list_d', [
            'theTemplate'=>$theTemplate,
        ]);
    }

}
