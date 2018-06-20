<?

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\helpers\FileHelper;

use common\models\Package;
use common\models\Message;
use common\models\Tour;
use common\models\Destination;
use common\models\Supplier;

/*
RESET SEARCH
delete from at_search where rtype="package";
INSERT INTO at_search (rtype, rid, search, found) (SELECT "package", id, name, CONCAT(name, ", ", (SELECT name_en FROM at_destinations d WHERE d.id=destination_id LIMIT 1)) FROM packages);
UPDATE at_search SET search=REPLACE(search," ", "") WHERE rtype="package";
*/

class PackageController extends MyController
{
    public function actionIndex() {

        $query = Package::find();

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $thePackages = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('package_index', [
            'pagination'=>$pagination,
            'thePackages'=>$thePackages,
        ]);
    }

    public function actionC()
    {
        if (!in_array(MY_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Chức năng đang tạm bị hạn chế. Liên hệ Mr Huân để biết thêm chi tiết.');
        }
        $thePackage = new Package;
        $thePackage->scenario = 'package/c';

        if ($thePackage->load(Yii::$app->request->post()) && $thePackage->validate()) {
            $thePackage['created_dt'] = NOW;
            $thePackage['created_by'] = USER_ID;
            $thePackage['updated_dt'] = NOW;
            $thePackage['updated_by'] = USER_ID;
            if ($thePackage->save(false)) {
                return $this->redirect('@web/packages/r/'.$thePackage['id']);
            }
        }

        return $this->render('package_u', [
            'thePackage'=>$thePackage,
        ]);
    }

    public function actionR($id = 0)
    {
        $thePackage = Package::find()
            ->where(['id'=>$id])
            // ->with([
            //     'messages',
            //     ])
            ->asArray()
            ->one();
        if (!$thePackage) {
            throw new HttpException(404, 'Package not found');
        }

        return $this->render('package_r', [
            'thePackage'=>$thePackage,
        ]);
    }

    public function actionU($id = 0) {
        if (!in_array(MY_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        $thePackage = Package::findOne($id);
        if (!$thePackage) {
            throw new HttpException(404, 'Package not found');
        }

        $thePackage->scenario = 'package/u';

        if ($thePackage->load(Yii::$app->request->post()) && $thePackage->validate()) {
            $thePackage->updated_dt = NOW;
            $thePackage->updated_by = MY_ID;
            if ($thePackage->save(false)) {
                return $this->redirect('@web/packages');
            }
        }

        $uploadDir = 'packages/'.substr($thePackage['created_dt'], 0, 7).'/'.$thePackage['id'];
        FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/'.$uploadDir);
        $ckfSessionName = 'package'.$thePackage['id'];
        $ckfSessionValue = [
            'ckfResourceName'=>'upload',
            'ckfResourceDirectory'=>$uploadDir,
        ];
        Yii::$app->session->set('ckfAuthorized', true);
        Yii::$app->session->set('ckfRole', 'user');
        Yii::$app->session->set($ckfSessionName, $ckfSessionValue);


        return $this->render('package_u', [
            'thePackage'=>$thePackage,
        ]);
    }

    public function actionD($id = 0) {
        $thePackage = Package::findOne($id);
        if (!$thePackage) {
            throw new HttpException(404, 'Package not found');
        }

        throw new HttpException(403, 'Under development.');
        

        return $this->render('package_d', [
            'thePackage'=>$thePackage,
        ]);
    }

}
