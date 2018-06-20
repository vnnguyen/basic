<?

namespace app\controllers\acp;

use common\models\Country;
use common\models\Group;
use common\models\Message;
use common\models\Contact;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class ContactController extends \app\controllers\MyController
{
	public function actionIndex($account = 0, $name = '')
	{
		$query = Contact::find()
			->where(['app'=>'crm', 'account_id'=>ACCOUNT_ID]);

		if ($name != '') {
			$query->andWhere(['like', 'name', $name]);
		}

		$countQuery = clone $query;
    	$pagination = new Pagination([
    		'totalCount' => $countQuery->count(),
    		'pageSize'=>25,
    	]);

		$theContacts = $query
			->with([
				'updatedBy'=>function($q) {
					$q->select(['id', 'name']);
				},
				'account'=>function($q) {
					$q->select(['id', 'name', 'role']);
				},
			])
			->orderBy('updated_dt DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();

		return $this->render('contact_index', [
			'theContacts'=>$theContacts,
			'pagination'=>$pagination,
			'name'=>$name,
		]);
	}

	public function actionC($id = 0)
	{
		$theAccount = Group::find()
			->where(['account_id'=>ACCOUNT_ID, 'role'=>['account_i', 'account_c'], 'id'=>$id])
			->one();
		if (!$theAccount) {
			throw new HttpException(404, 'Account not found.');
		}

		$theContact = new Contact;
		$theContact->scenario = 'contact/c';
		$theContact->account_id = ACCOUNT_ID;
		$theContact->app = 'crm';
		$theContact->group_id = $theAccount['id'];
		$theContact->group_rel = 'account_contact';
		$theContact->addr_country = 'vn';
		if ($theAccount->primary_contact_id == 0) {
			$theContact->is_primary_contact = 'yes';
		} else {
			$theContact->is_primary_contact = 'no';
		}

		if ($theContact->load(Yii::$app->request->post()) && $theContact->validate()) {
			$theContact->created_dt = NOW;
			$theContact->created_by = USER_ID;
			$theContact->updated_dt = NOW;
			$theContact->updated_by = USER_ID;
			$theContact->save(false);

			if ($theContact->is_primary_contact == 'yes') {
				$theAccount->primary_contact_id = $theContact['id'];
				$theAccount->save(false);
			}

			Yii::$app->session->setFlash('success', 'Contact added: '.$theContact['name']);
			if (Yii::$app->request->post('save') == 'continue') {
				return $this->redirect('@web/acp/contacts/r/'.$theContact['id']);
			} else {
				return $this->redirect('@web/acp/clients/r/'.$theAccount['id']);
			}
		}

		$countryList = Country::find()
			->select(['name_en', 'code'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('contact_c', [
			'theAccount'=>$theAccount,
			'theContact'=>$theContact,
			'countryList'=>$countryList,
		]);
	}


	public function actionR($id = 0)
	{
		$theContact = Contact::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID])
			->with([
				'createdBy'=>function($q) {
					return $q->select(['id', 'name']);
				},
				'updatedBy'=>function($q) {
					return $q->select(['id', 'name']);
				},
				'account'=>function($q) {
					return $q->select(['id', 'name', 'role']);
				},
				])
			->asArray()
			->one();

		if (!$theContact) {
			throw new HttpException(404, 'Contact not found.');
		}

		$theMessage = new Message;
		if ($theMessage->load(Yii::$app->request->post()) && $theMessage->validate()) {
			$theMessage->created_dt = NOW;
			$theMessage->created_by = USER_ID;
			$theMessage->updated_dt = NOW;
			$theMessage->updated_by = USER_ID;
			$theMessage->account_id = ACCOUNT_ID;
			$theMessage->rtype = 'contact';
			$theMessage->rid = $theContact['id'];
			$theMessage->save(false);
			return $this->redirect(DIR.URI);
		}

		$theMessages = Message::find()
			->where(['rtype'=>'contact', 'rid'=>$theContact['id']])
			->with([
				'updatedBy'=>function($q) {
					$q->select(['id', 'name', 'image']);
				}
			])
			->orderBy('updated_dt')
			->asArray()
			->all();

		return $this->render('contact_r', [
			'theContact'=>$theContact,
			'theMessage'=>$theMessage,
			'theMessages'=>$theMessages,
		]);
	}

	public function actionU($id = 0)
	{
		$theContact = Contact::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'group_rel'=>'account_contact'])
			->one();

		if (!$theContact) {
			throw new HttpException(404, 'Contact not found.');
		}

		$theAccount = Group::find()
			->where(['account_id'=>ACCOUNT_ID, 'role'=>['account_i', 'account_c'], 'id'=>$theContact['group_id']])
			->one();

		if (!$theContact) {
			throw new HttpException(404, 'Account not found.');
		}

		$theContact->scenario = 'contact/u';

		if ($theAccount['primary_contact_id'] == $theContact['id']) {
			$theContact['is_primary_contact'] = 'yes';
		} else {
			$theContact['is_primary_contact'] = 'no';
		}

		$uploadPath = '/upload/contacts/'.substr($theContact['created_dt'], 0, 7).'/'.$theContact['id'];
		\yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$uploadPath);
		Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
		Yii::$app->session->set('ckfinder_resource_name', 'upload');

		if ($theContact->load(Yii::$app->request->post()) && $theContact->validate()) {
			$theContact->updated_dt = NOW;
			$theContact->updated_by = USER_ID;
			$theContact->save(false);

			if ($theContact->is_primary_contact == 'yes' && $theAccount->primary_contact_id != $theContact['id']) {
				$theAccount->primary_contact_id = $theContact['id'];
				$theAccount->save(false);
			}
			if ($theContact->is_primary_contact == 'no' && $theAccount->primary_contact_id == $theContact['id']) {
				$theAccount->primary_contact_id = 0;
				$theAccount->save(false);
			}
			Yii::$app->session->setFlash('success', 'Contact updated: '.$theContact['name']);
			if (Yii::$app->request->post('save') == 'continue') {
				return $this->redirect('@web/acp/contacts/r/'.$theContact['id']);
			} else {
				return $this->redirect('@web/acp/clients/r/'.$theAccount['id']);
			}
		}

		$countryList = Country::find()
			->select(['name_en', 'code'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('contact_u', [
			'theContact'=>$theContact,
			'theAccount'=>$theAccount,
			'countryList'=>$countryList,
		]);
	}

	public function actionD($id = 0)
	{
		$theContact = Contact::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'group_rel'=>'account_contact'])
			->one();

		if (!$theContact) {
			throw new HttpException(404, 'Contact not found.');
		}

		if (isset($_POST['answer']) && $_POST['answer'] == 'delete') {
			Yii::$app->db
				->createCommand()
				->update('contacts', ['status'=>'deleted'], ['id'=>$id])
				->execute();
			Yii::$app->session->setFlash('success', 'Contact has been deleted: '.$theContact['name']);
			return $this->redirect('/acp/clients/r/'.$theContact['group_id']);
		}
		return $this->render('contact_d', [
			'theContact'=>$theContact,
		]);
	}
}
