<?php

namespace app\controllers\b2b;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\User;
use common\models\Search;
use common\models\Ct;
use common\models\Cpt;
use common\models\Day;
use common\models\Kase;
use common\models\Inquiry;
use common\models\Message;
use common\models\ProfileTA;
use common\models\Sysnote;
use common\models\Tour;
use common\models\Product;
use common\models\Booking;
use common\models\Task;
use common\models\SampleTourDay;
use common\models\SampleTourProgram;
use Mailgun\Mailgun;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\GridView;
use yii\data\Pagination;
use yii\web\HttpException;

class ClientController extends \app\controllers\MyController
{
    // Login cho client SI
    public function actionIndex()
    {
        $sql = 'SELECT company_id FROM at_cases GROUP BY company_id';
        $theList = Yii::$app->db->createCommand($sql)->queryAll();
        $idList = [];
        foreach ($theList as $item) {
            $idList[] = $item['company_id'];
        }
        $theAccounts = Company::find()
            ->where(['id'=>$idList])
            ->with([
                'metas',
                'profileTA',
                ])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('client_index', [
            'theAccounts'=>$theAccounts,
        ]);
    }

    // Login cho client SI
    public function actionR($id = 0, $view = 'cases')
    {
        $theAccount = Company::find()
            ->where(['id'=>$id])
            ->with([
                'cases'=>function($q) {
                    $q->orderBy('created_at DESC');
                },
                'cases.bookings'=>function($q) {
                    $q->andWhere(['status'=>'won']);
                },
                'cases.bookings.product'=>function($q) {
                    $q->select(['id', 'day_from', 'day_count', 'pax', 'op_name', 'op_code', 'op_finish', 'client_ref'])->andWhere(['op_status'=>'op']);
                },
                'cases.owner',
                'metas',
                'profileTA',
                ])
            ->asArray()
            ->one();
        if (!$theAccount) {
            throw new HttpException(404, 'Account not found');
        }
        
        return $this->render('client_r', [
            'theAccount'=>$theAccount,
            'view'=>$view,
        ]);
    }


    // Login cho client SI
    public function actionLogin($id = 0)
    {
        $theAccount = Company::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theAccount) {
            throw new HttpException(404, 'Account not found');
        }

        $theProfile = profileTA::find()
            ->where(['company_id'=>$theAccount['id']])
            ->one();
        if (!$theProfile) {
            $theProfile = new ProfileTA;
            $theProfile->name = $theAccount['name'];
            $theProfile->login = \yii\helpers\Inflector::slug($theAccount['name']);
        }

        $theProfile->scenario = 'profile/u';

        if ($theProfile->load(Yii::$app->request->post()) && $theProfile->validate()) {
            if ($theProfile->isNewRecord) {
                $theProfile->created_dt = NOW;
                $theProfile->created_by = USER_ID;
                $theProfile->company_id = $theAccount['id'];
            }
            $theProfile->updated_dt = NOW;
            $theProfile->updated_by = USER_ID;

            if ($theProfile->newpassword != '') {
                $theProfile->password = Yii::$app->security->generatePasswordHash($theProfile->newpassword);
            }
            $theProfile->save(false);
            return $this->redirect('@web/b2b/clients');
        }

        return $this->render('client_login', [
            'theAccount'=>$theAccount,
            'theProfile'=>$theProfile,
        ]);
    }

}