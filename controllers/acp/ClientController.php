<?

namespace app\controllers\acp;

use common\models\Country;
use common\models\Group;
use common\models\Listt;
use common\models\ListItem;
use common\models\Message;
use common\models\Person;
use common\models\Contact;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class ClientController extends \app\controllers\MyController
{
	public function actionIndex($type = '', $name = '', $orderby = 'updated', $sort = 'desc', $output = 'view')
	{
		$query = Group::find()
			->where(['account_id'=>ACCOUNT_ID, 'status'=>'on']);

		if ($type == '') {
			$query->andWhere(['role'=>['account_i', 'account_c']]);
		} elseif ($type == 'individual') {
			$query->andWhere(['role'=>'account_i']);
		} else {
			$query->andWhere(['role'=>'account_c', 'stype'=>$type]);
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

		$theClients = $query
			->with([
				'primaryContact'=>function($q) {
					return $q->select(['id', 'group_id', 'name', 'tel', 'email', 'group_role']);
				},
				])
			->offset($pagination->offset)
			->limit($pagination->limit)
			->orderBy($orderText.$sortText)
			->asArray()
			->all();

		return $this->render('client_index', [
			'theClients'=>$theClients,
			'pagination'=>$pagination,
			'type'=>$type,
			'name'=>$name,
		]);
	}

	public function actionC($individual = 'no')
	{
		$theClient = new Group;
		$theClient->scenario = 'account/c';
		$theClient->account_id = ACCOUNT_ID;
		$theClient->app = 'crm';
		$theClient->status = 'on';
		$theClient->role = 'account_c';
		$theClient->addr_country = 'vn';
		$theClient->primary_contact_id = 0;

		$theContact = new Contact;
		$theContact->scenario = 'account/c';
		$theContact->account_id = ACCOUNT_ID;
		$theContact->app = 'crm';
		$theContact->status = 'on';
		$theContact->role = 'contact';
		$theContact->group_rel = 'account_contact';
		$theContact->addr_country = 'vn';

		if ($individual == 'yes') {
			$theClient->scenario = 'account/c/individual';
			$theClient->role = 'account_i';
		}

		if (
			$theContact->load(Yii::$app->request->post()) && $theContact->validate()
			&& $theClient->load(Yii::$app->request->post()) && $theClient->validate()
			) {

			$theClient->created_dt = NOW;
			$theClient->created_by = USER_ID;
			$theClient->updated_dt = NOW;
			$theClient->updated_by = USER_ID;
			$theClient->save(false);

			$theContact->created_dt = NOW;
			$theContact->created_by = USER_ID;
			$theContact->updated_dt = NOW;
			$theContact->updated_by = USER_ID;
			$theContact->group_id = $theClient['id'];
			$theContact->save(false);

			if ($individual == 'yes') {
				$theClient->name = $theContact['name'];
			}
			$theClient->primary_contact_id = $theContact->id;
			$theClient->save(false);

			Yii::$app->session->setFlash('success', 'Client created: '.$theClient['name']);
			return $this->redirect('@web/acp/clients/r/'.$theClient['id']);
		}

		$accountTypeList = ListItem::find()
			->select(['name'])
			->where(['list_id'=>1, 'account_id'=>[0, ACCOUNT_ID], 'status'=>'on'])
			->asArray()
			->all();

		$countryList = Country::find()
			->select(['code', 'name_en'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('client_c', [
			'individual'=>$individual,
			'theClient'=>$theClient,
			'theContact'=>$theContact,
			'countryList'=>$countryList,
			'accountTypeList'=>$accountTypeList,
		]);
	}

	public function actionR($id = 0)
	{
		$theClient = Group::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'app'=>'crm'])
			->with([
				'jobs',
				'jobs.owner'=>function($q) {
					return $q->select(['id', 'name']);
				},
				'contacts'=>function($q) {
					return $q->select(['id', 'name', 'tel', 'email', 'group_id', 'group_role']);
				},
				])
			->asArray()
			->one();

		if (!$theClient) {
			throw new HttpException(404, 'Client not found.');
		}

		$theMessage = new Message;
		if ($theMessage->load(Yii::$app->request->post()) && $theMessage->validate()) {
			$theMessage->created_dt = NOW;
			$theMessage->created_by = USER_ID;
			$theMessage->updated_dt = NOW;
			$theMessage->updated_by = USER_ID;
			$theMessage->account_id = ACCOUNT_ID;
			$theMessage->rtype = 'client';
			$theMessage->rid = $theClient['id'];
			$theMessage->save(false);
			return $this->redirect(DIR.URI);
		}

		$theMessages = Message::find()
			->where(['rtype'=>'client', 'rid'=>$theClient['id']])
			->with([
				'updatedBy'=>function($q) {
					$q->select(['id', 'name', 'image']);
				}
			])
			->orderBy('updated_dt')
			->asArray()
			->all();

		return $this->render('client_r', [
			'theClient'=>$theClient,
			'theMessage'=>$theMessage,
			'theMessages'=>$theMessages,
		]);
	}

	public function actionU($id = 0)
	{
		$theClient = Group::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'app'=>'crm'])
			->one();

		if ($id == ACCOUNT_ID || !$theClient) {
			throw new HttpException(404, 'Client not found.');
		}

		$theClient->scenario = 'account/u';
		if ($theClient->load(Yii::$app->request->post()) && $theClient->validate()) {
			$theClient->updated_dt = NOW;
			$theClient->updated_by = USER_ID;
			$theClient->save(false);
			Yii::$app->session->setFlash('success', 'Client updated: '.$theClient['name']);
			return $this->redirect('@web/acp/clients');
		}

		$countryList = Country::find()
			->select(['code', 'name_en'])
			->orderBy('name_en')
			->asArray()
			->all();

		$accountTypeList = ListItem::find()
			->select(['name'])
			->where(['list_id'=>1, 'account_id'=>[0, ACCOUNT_ID], 'status'=>'on'])
			->asArray()
			->all();

		$contactList = Person::find()
			->select(['id', new \yii\db\Expression('CONCAT_WS(", ", name, group_role, email, tel) AS name')])
			->where(['group_id'=>$theClient['id'], 'status'=>'on'])
			->orderBy('fname, lname')
			->asArray()
			->all();
			//\fCore::expose($accountTypeList['items']); //exit;

		return $this->render('client_u', [
			'theClient'=>$theClient,
			'countryList'=>$countryList,
			'accountTypeList'=>$accountTypeList,
			'contactList'=>$contactList,
		]);
	}

	public function actionD($id = 0)
	{
		$theClient = Group::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'app'=>'crm'])
			->one();

		if ($id == ACCOUNT_ID || !$theClient) {
			throw new HttpException(404, 'Client account not found.');
		}

		if (isset($_POST['answer']) && $_POST['answer'] == 'delete') {
			Yii::$app->db
				->createCommand()
				->update('at_groups', ['status'=>'deleted'], ['id'=>$id])
				->execute();
			Yii::$app->session->setFlash('success', 'Client account has been deleted: '.$theClient['name']);
			return $this->redirect('/acp/clients');
		}

		return $this->render('client_d', [
			'theClient'=>$theClient,
		]);
	}


	// Send one-time instruction input link
	public function actionSendOtl($id = 0)
	{
		$theClient = Client::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID])
			->one();

		if ($id == ACCOUNT_ID || !$theClient) {
			throw new HttpException(404, 'Client account not found.');
		}

		$theForm = new \app\models\SendOtlForm;
		if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
			if ($theForm->sendEmail($theClient, $theForm)) {
				Yii::$app->session->setFlash('success', 'Email has been successfully sent.');
			} else {
				Yii::$app->session->setFlash('error', 'Error! Email has not been sent.');
			}
			return $this->redirect('@web/acp/clients/r/'.$theClient['id']);
		}

		return $this->render('client_send-otl', [
			'theClient'=>$theClient,
			'theForm'=>$theForm,
		]);
	}
}
