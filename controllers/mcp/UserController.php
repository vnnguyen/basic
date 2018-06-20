<?

namespace app\controllers;

use common\models\Country;
use common\models\Group;
use common\models\Message;
use common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class UserController extends MyController
{
	public function actionIndex($account = 0, $name = '')
	{
		$query = User::find();

		if ($name != '') {
			$query->andWhere(['like', 'name', $name]);
		}

		$countQuery = clone $query;
    	$pagination = new Pagination([
    		'totalCount' => $countQuery->count(),
    		'pageSize'=>15,
    	]);

		$theUsers = $query
			->with([
				'updatedBy'=>function($q) {
					$q->select(['id', 'name']);
				},
				'account'=>function($q) {
					$q->select(['id', 'name']);
				},
			])
			->orderBy('updated_dt DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();

		return $this->render('user_index', [
			'theUsers'=>$theUsers,
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

		$theUser = new User;
		$theUser->scenario = 'contact/c';
		$theUser->account_id = ACCOUNT_ID;
		$theUser->app = 'crm';
		$theUser->group_id = $theAccount['id'];
		$theUser->group_rel = 'account_contact';
		$theUser->addr_country = 'vn';
		if ($theAccount->primary_user_id == 0) {
			$theUser->is_primary_contact = 'yes';
		} else {
			$theUser->is_primary_contact = 'no';
		}

		if ($theUser->load(Yii::$app->request->post()) && $theUser->validate()) {
			$theUser->created_dt = NOW;
			$theUser->created_by = USER_ID;
			$theUser->updated_dt = NOW;
			$theUser->updated_by = USER_ID;
			$theUser->save(false);

			if ($theUser->is_primary_contact == 'yes') {
				$theAccount->primary_user_id = $theUser['id'];
				$theAccount->save(false);
			}

			Yii::$app->session->setFlash('success', 'Contact added: '.$theUser['name']);
			if (Yii::$app->request->post('save') == 'continue') {
				return $this->redirect('@web/contacts/r/'.$theUser['id']);
			} else {
				return $this->redirect('@web/accounts/r/'.$theAccount['id']);
			}
		}

		$countryList = Country::find()
			->select(['name_en', 'code'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('user_c', [
			'theAccount'=>$theAccount,
			'theUser'=>$theUser,
			'countryList'=>$countryList,
		]);
	}


	public function actionR($id = 0)
	{
		$theUser = User::find()
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

		if (!$theUser) {
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
			$theMessage->rid = $theUser['id'];
			$theMessage->save(false);
			return $this->redirect(DIR.URI);
		}

		$theMessages = Message::find()
			->where(['rtype'=>'contact', 'rid'=>$theUser['id']])
			->with([
				'updatedBy'=>function($q) {
					$q->select(['id', 'name', 'image']);
				}
			])
			->orderBy('updated_dt')
			->asArray()
			->all();

		return $this->render('user_r', [
			'theUser'=>$theUser,
			'theMessage'=>$theMessage,
			'theMessages'=>$theMessages,
		]);
	}

	public function actionU($id = 0)
	{
		$theUser = User::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'group_rel'=>'account_contact'])
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'Contact not found.');
		}

		$theAccount = Group::find()
			->where(['account_id'=>ACCOUNT_ID, 'role'=>['account_i', 'account_c'], 'id'=>$theUser['group_id']])
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'Account not found.');
		}

		$theUser->scenario = 'contact/u';

		if ($theAccount['primary_user_id'] == $theUser['id']) {
			$theUser['is_primary_contact'] = 'yes';
		} else {
			$theUser['is_primary_contact'] = 'no';
		}

		$uploadPath = '/upload/contacts/'.substr($theUser['created_dt'], 0, 7).'/'.$theUser['id'];
		\yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$uploadPath);
		Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
		Yii::$app->session->set('ckfinder_resource_name', 'upload');

		if ($theUser->load(Yii::$app->request->post()) && $theUser->validate()) {
			$theUser->updated_dt = NOW;
			$theUser->updated_by = USER_ID;
			$theUser->save(false);

			if ($theUser->is_primary_contact == 'yes' && $theAccount->primary_user_id != $theUser['id']) {
				$theAccount->primary_user_id = $theUser['id'];
				$theAccount->save(false);
			}
			if ($theUser->is_primary_contact == 'no' && $theAccount->primary_user_id == $theUser['id']) {
				$theAccount->primary_user_id = 0;
				$theAccount->save(false);
			}
			Yii::$app->session->setFlash('success', 'Contact updated: '.$theUser['name']);
			if (Yii::$app->request->post('save') == 'continue') {
				return $this->redirect('@web/contacts/r/'.$theUser['id']);
			} else {
				return $this->redirect('@web/accounts/r/'.$theAccount['id']);
			}
		}

		$countryList = Country::find()
			->select(['name_en', 'code'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('user_u', [
			'theUser'=>$theUser,
			'theAccount'=>$theAccount,
			'countryList'=>$countryList,
		]);
	}

	public function actionD($id = 0)
	{
		$theUser = User::find()
			->where(['id'=>$id, 'account_id'=>ACCOUNT_ID, 'group_rel'=>'account_contact'])
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'Contact not found.');
		}

		if (isset($_POST['answer']) && $_POST['answer'] == 'delete') {
			Yii::$app->db
				->createCommand()
				->update('at_persons', ['status'=>'deleted'], ['id'=>$id])
				->execute();
			Yii::$app->session->setFlash('success', 'Contact has been deleted: '.$theUser['name']);
			return $this->redirect('/accounts/r/'.$theUser['group_id']);
		}
		return $this->render('user_d', [
			'theUser'=>$theUser,
		]);
	}
}
