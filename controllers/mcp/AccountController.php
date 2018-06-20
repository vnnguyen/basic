<?

namespace app\controllers\mcp;

use \common\models\Country;
use \common\models\Account;
use \common\models\Listt;
use \common\models\ListItem;
use \common\models\Message;
use \common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class AccountController extends McpController
{
    public function actionIndex($status = '', $name = '', $orderby = 'updated', $sort = 'desc')
    {
        $query = Account::find();

        if ($status != '') {
            $query->andWhere(['status'=>$status]);
        }

        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $orderText = 'updated_dt';
        if ($orderby == 'name') {
            $orderby = 'name';
        } elseif ($orderby == 'type') {
            $orderby = 'stype';
        }

        if ($sort == 'desc') {
            $sortText = ' DESC';
        } else {
            $sortText = '';
        }

        $theAccounts = $query
            ->with(['users'=>function($q) {
                return $q->select(['account_id']);
            }])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy($orderText.$sortText)
            ->asArray()
            ->all();

        return $this->render('account_index', [
            'theAccounts'=>$theAccounts,
            'pagination'=>$pagination,
            'status'=>$status,
            'name'=>$name,
        ]);
    }

    // Resend activation email
    public function actionRsae($id)
    {
        $theAccount = Account::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theAccount) {
            throw new \yii\web\HttpNotFoundException('Account not found.');
        }
        $this->mgIt('Activate your EVP account', 'account_rsae', [
                'from'=>'EVP',
                'to'=>'hn.huan@gmail.com',
                'activation_link'=>'https://my.evalpro.vn/register/'.\Yii::$app->security->generateRandomString(10),
            ]);
        Yii::$app->session->setFlash('success', Yii::t('mcp', 'Activation email has been sent to the address you specified.'));
        return $this->redirect('/mcp/accounts/r/'.$id);
    }

    public function actionC($individual = 'no')
    {
        $theAccount = new Account;
        $theAccount->scenario = 'account/c';
        $theAccount->status = 'on';

        $theUser = new User;
        $theUser->scenario = 'account/c';
        $theUser->account_id = ACCOUNT_ID;
        $theUser->status = 'on';
        $theUser->addr_country = 'vn';

        if (
            /*$theUser->load(Yii::$app->request->post()) && $theUser->validate()
            &&*/ $theAccount->load(Yii::$app->request->post()) && $theAccount->validate()
            ) {

            $theAccount->created_dt = NOW;
            $theAccount->created_by = USER_ID;
            $theAccount->updated_dt = NOW;
            $theAccount->updated_by = USER_ID;
            $theAccount->stype = 'normal';
            $theAccount->subscriptions = is_array($theAccount->subscriptions) ? implode(',', $theAccount->subscriptions) : '';
            $theAccount->save(false);
/*
            $theUser->created_dt = NOW;
            $theUser->created_by = USER_ID;
            $theUser->updated_dt = NOW;
            $theUser->updated_by = USER_ID;
            $theUser->account_id = $theAccount['id'];
            $theUser->save(false);

            $theAccount->primary_contact_id = $theUser->id;
            $theAccount->save(false);
*/
            Yii::$app->session->setFlash('success', 'Account created: '.$theAccount['name']);
            return $this->redirect('/mcp/accounts/r/'.$theAccount['id']);
        }

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('account_c', [
            'individual'=>$individual,
            'theAccount'=>$theAccount,
            'theUser'=>$theUser,
            'countryList'=>$countryList,
            'accountTypeList'=>['$accountTypeList'=>'x'],
        ]);
    }

    public function actionR($id = 0)
    {
        $theAccount = Account::find()
            ->where(['id'=>$id])
            ->with([
                //'subscriptions',
                'users',
                ])
            ->asArray()
            ->one();

        if (!$theAccount) {
            throw new HttpException(404, 'Account not found.');
        }

        $theMessage = new Message;
        if ($theMessage->load(Yii::$app->request->post()) && $theMessage->validate()) {
            $theMessage->created_dt = NOW;
            $theMessage->created_by = USER_ID;
            $theMessage->updated_dt = NOW;
            $theMessage->updated_by = USER_ID;
            $theMessage->account_id = ACCOUNT_ID;
            $theMessage->rtype = 'account';
            $theMessage->rid = $theAccount['id'];
            $theMessage->save(false);
            return $this->redirect(DIR.URI);
        }

        $theMessages = Message::find()
            ->where(['rtype'=>'account', 'rid'=>$theAccount['id']])
            ->with([
                'updatedBy'=>function($q) {
                    $q->select(['id', 'name', 'image']);
                }
            ])
            ->orderBy('updated_dt')
            ->asArray()
            ->all();

        return $this->render('account_r', [
            'theAccount'=>$theAccount,
            'theMessage'=>$theMessage,
            'theMessages'=>$theMessages,
        ]);
    }

    public function actionU($id = 0)
    {
        $theAccount = Account::find()
            ->where(['id'=>$id])
            ->one();

        if ($id == ACCOUNT_ID || !$theAccount) {
            throw new HttpException(403, 'You cannot edit this account directly.');
        }

        $theAccount->scenario = 'account/u';
        $theAccount->subscriptions = explode(',', $theAccount->subscriptions);

        if ($theAccount->load(Yii::$app->request->post()) && $theAccount->validate()) {
            $theAccount->updated_dt = NOW;
            $theAccount->updated_by = USER_ID;
            $theAccount->subscriptions = is_array($theAccount->subscriptions) ? implode(',', $theAccount->subscriptions) : '';
            $theAccount->save(false);

            if ($theAccount->fu_name != '' && $theAccount->fu_email != '' && $theAccount->fu_password != '') {
                $theUser = new User;
                $theUser->created_dt = NOW;
                $theUser->created_by = USER_ID;
                $theUser->updated_dt = NOW;
                $theUser->updated_by = USER_ID;
                $theUser->account_id = $theAccount['id'];
                $theUser->name = $theAccount->fu_name;
                $theUser->email = $theAccount->fu_email;
                $theUser->password = Yii::$app->getSecurity()->generatePasswordHash($theAccount->fu_password);
                $theUser->status = 'on';
                $theUser->save(false);
            }

            Yii::$app->session->setFlash('success', 'Account updated: '.$theAccount['name']);
            return $this->redirect('/mcp/accounts');
        }

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        $contactList = User::find()
            ->select(['id', new \yii\db\Expression('CONCAT_WS(", ", name, email, tel) AS name')])
            ->where(['account_id'=>$theAccount['id'], 'status'=>'on'])
            ->orderBy('fname, lname')
            ->asArray()
            ->all();
            //\fCore::expose($accountTypeList['items']); //exit;

        return $this->render('account_u', [
            'theAccount'=>$theAccount,
            'countryList'=>$countryList,
            'contactList'=>$contactList,
        ]);
    }

    public function actionD($id = 0)
    {
        $theAccount = Account::find()
            ->where(['id'=>$id])
            ->one();

        if ($id == ACCOUNT_ID || !$theAccount) {
            throw new HttpException(404, 'Account not found.');
        }

        if (isset($_POST['answer']) && $_POST['answer'] == 'delete') {
            Yii::$app->db
                ->createCommand()
                ->update('accounts', ['status'=>'deleted'], ['id'=>$id])
                ->execute();
            Yii::$app->session->setFlash('success', 'Account has been deleted: '.$theAccount['name']);
            return $this->redirect('/mcp/accounts');
        }

        return $this->render('account_d', [
            'theAccount'=>$theAccount,
        ]);
    }


    // Send one-time instruction input link
    public function actionSendOtl($id = 0)
    {
        $theAccount = Account::find()
            ->where(['id'=>$id, 'account_id'=>ACCOUNT_ID])
            ->one();

        if ($id == ACCOUNT_ID || !$theAccount) {
            throw new HttpException(404, 'Account account not found.');
        }

        $theForm = new \app\models\SendOtlForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if ($theForm->sendEmail($theAccount, $theForm)) {
                Yii::$app->session->setFlash('success', 'Email has been successfully sent.');
            } else {
                Yii::$app->session->setFlash('error', 'Error! Email has not been sent.');
            }
            return $this->redirect('/mcp/accounts/r/'.$theAccount['id']);
        }

        return $this->render('account_send-otl', [
            'theAccount'=>$theAccount,
            'theForm'=>$theForm,
        ]);
    }
}
