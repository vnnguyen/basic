<?

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

use common\models\BAccount;
use common\models\Product;
use common\models\Invoice;
use common\models\Payment;

class BaccountController extends MyController
{

	public function actionIndex()
	{
		$theBaccounts = BAccount::find()
			->orderBy('name')
			->asArray()
			->all();

		return $this->render('baccounts', [
			'theBaccounts'=>$theBaccounts,
		]);
	}

	public function actionC()
	{
		$theBaccount = new Baccount;
		$theBaccount->scenario = 'baccount/c';

		if ($theBaccount->load(Yii::$app->request->post()) && $theBaccount->validate()) {
			$theBaccount->created_at = NOW;
			$theBaccount->created_by = MY_ID;
			$theBaccount->updated_at = NOW;
			$theBaccount->updated_by = MY_ID;

			if ($theBaccount->save(false)) {
				return $this->redirect(['baccount/index']);
			}
		}

		return $this->render('baccounts_u', [
			'theBaccount'=>$theBaccount,
		]);
	}

	public function actionR($id = 0)
	{
		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with([
				'createdBy',
				'updatedBy',
				'product',
				'product.tour',
				'product.days',
				'case',
				'case.owner',
				'invoices'=>function($q) {
					return $q->orderBy('due_dt');
				},
				'payments',
				'people',
			])
			->asArray()
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		$theProduct = Product::find()->where(['id'=>$theBaccount['product_id']])->asArray()->one();

		$bookingOwner = User::find()
			->where(['id'=>$theBaccount['created_by']])
			->asArray()
			->one();

		if (isset($theBaccount['product']['tour']['id'])) {
			$tourPeople = Yii::$app->db
				->createCommand('SELECT u.email, u.fname, u.lname FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:id', [':id'=>$theBaccount['product']['tour']['id']])
				->queryAll();
		}

		$theInvoice = new Invoice();
		$theInvoice->scenario = 'invoices_c';
		if ($theInvoice->load(Yii::$app->request->post()) && $theInvoice->validate()) {

			$theInvoice->booking_id = $theBaccount['id'];
			$theInvoice->created_at = NOW;
			$theInvoice->created_by = Yii::$app->user->id;
			$theInvoice->updated_at = NOW;
			$theInvoice->updated_by = Yii::$app->user->id;
			$theInvoice->status = 'on';

			if ($theInvoice->save(false)) {
				Yii::$app->session->setFlash('success', 'Invoice has been added: '.number_format($theInvoice['amount'], 2).' '.$theInvoice['currency']);
				return $this->redirect('@web/baccounts/r/'.$theBaccount['id']);
			}
		}

		$thePayment = new Payment;		
		$thePayment->scenario = 'payments_c';

		if ($thePayment->load(Yii::$app->request->post()) && $thePayment->validate()) {

			$thePayment->booking_id = $theBaccount['id'];
			$thePayment->created_at = NOW;
			$thePayment->created_by = Yii::$app->user->id;
			$thePayment->updated_at = NOW;
			$thePayment->updated_by = Yii::$app->user->id;
			$thePayment->status = 'on';

			if ($thePayment->save(false)) {
				if ($bookingOwner) {
					$args = [
						['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
						['to', $bookingOwner['email'], $bookingOwner['lname'], $bookingOwner['fname']],
						['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
						// ['attachment', 'filePath', 'fileName'],
					];
					if (isset($tourPeople) && !empty($tourPeople)) {
						foreach ($tourPeople as $user) {
							$args[] = ['cc', $user['email'], $user['lname'], $user['fname']];
						}
					}
					$this->mgIt(
						'ims | Payment received: '.$thePayment['ref'].' / '.$thePayment['method'].' / '.number_format($thePayment['amount'], 0).' '.$thePayment['currency'],
						'//payment_received',
						[
							'thePayment'=>$thePayment,
							'theBaccount'=>$theBaccount,
						],
						$args
					);
				}

				Yii::$app->session->setFlash('success', 'Payment has been added: '.number_format($thePayment['amount'], 2).' '.$thePayment['currency']);
				return $this->redirect('@web/baccounts/r/'.$theBaccount['id']);
			}
		}

		// Delete pax from booking
		if (isset($_GET['action']) && $_GET['action'] == 'delete-user-booking' && isset($_GET['user_id'])) {
			// Huan, CSKH
			if (in_array(Yii::$app->user->id, [1, 7756, 9881, 1351])) {
				Yii::$app->db->createCommand()
					->delete('at_booking_user', [
						'booking_id'=>$theBaccount['id'],
						'user_id'=>$_GET['user_id'],
					])
					->execute();
				return $this->redirect('@web/baccounts/r/'.$theBaccount['id']);
			}
		}

		// Cancel pax from booking
		if (isset($_GET['action']) && $_GET['action'] == 'cancel-user-booking' && isset($_GET['user_id'])) {
			// Huan, CSKH
			if (in_array(Yii::$app->user->id, [1, 7756, 9881, 1351])) {
				Yii::$app->db->createCommand()
					->update('at_booking_user',
						[
						'updated_at'=>NOW,
						'updated_by'=>Yii::$app->user->id,
						'status'=>'canceled',
						], [
						'booking_id'=>$theBaccount['id'],
						'user_id'=>$_GET['user_id'],
						]
					)
					->execute();
				return $this->redirect('@web/baccounts/r/'.$theBaccount['id']);
			}
		}

		// Add pax
		if (isset($_POST['action']) && $_POST['action'] == 'add-pax' && isset($_POST['name'])) {
			// Yii::$app->session->remove('searchUsers');
			$name = trim($_POST['name']);
			if ((int)$name > 0) {
				$theUsers = User::find()
					->where(['id'=>$name])
					->all();
			} elseif (false !== strpos($name, '@')) {
				$theUsers = User::findBySql('SELECT u.* FROM persons u, at_meta m WHERE m.rtype="user" AND m.rid=u.id AND m.k="email" AND m.v=:email', [':email'=>$name])
					->asArray()
					->all();
			} else {
				$theUsers = User::find()
					->where(['name'=>$name])
					->orWhere('CONCAT(fname, " ", lname)=:name', [':name'=>$name])
					->orWhere('CONCAT(lname, " ", fname)=:name', [':name'=>$name])
					->asArray()
					->all();
			}
			if (!$theUsers) {
				if (strpos($name, ' ') !== false && strlen($name) > 6) {
					// Add pax if this is a name First Last
					$newUser = new User;
					$newUser->created_at = NOW;
					$newUser->created_by = MY_ID;
					$newUser->updated_at = NOW;
					$newUser->updated_by = MY_ID;
					$newUser->status = 'on';
					$newUser->name = $name;
					if ($newUser->save(false)) {
						Yii::$app->db->createCommand()
							->insert('at_booking_user', [
								'created_at'=>NOW,
								'created_by'=>MY_ID,
								'updated_at'=>NOW,
								'updated_by'=>MY_ID,
								'booking_id'=>$theBaccount['id'],
								'user_id'=>$newUser['id'],
								])
							->execute();
						//return $this->redirect('@web/users/u/'.$newUser['id']);
					}
				} else {
					Yii::$app->session->setFlash('error', 'User not found: #'.$name. '. A new pax name must be of format "First Last" and longer than 6 characters.');
				}
			} else {
				if (count($theUsers) == 1) {
					Yii::$app->db->createCommand()
						->insert('at_booking_user', [
							'created_at'=>NOW,
							'created_by'=>MY_ID,
							'updated_at'=>NOW,
							'updated_by'=>MY_ID,
							'booking_id'=>$theBaccount['id'],
							'user_id'=>$theUsers[0]['id'],
							])
						->execute();
				} else {
					$searchUsers = $theUsers;
	if (!empty($searchUsers)) {
		echo '<div class="alert alert-info"><strong>The following users were found with same name / email</strong>';
		foreach ($searchUsers as $user) {
			echo '<br>ID: <a href="/users/r/', $user['id'], '">', $user['id'], '</a> | Name: ', $user['fname'], ' / ', $user['fname'], ' (', $user['name'], ')';
		}
		echo '</div>';
		exit;
		die('Insert one of user IDs above or add new user by adding a plus sign before name, eg. "+Nguyen Van A"');
	}
					Yii::$app->session->set('searchUsers', $theUsers);
				}
			}
			return $this->redirect('@web/baccounts/r/'.$theBaccount['id']);
		}

		$thePeople = Yii::$app->db->createCommand('SELECT u.id, u.fname, u.lname, u.byear, u.email, u.gender, u.country_code, u.name, bu.status FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.booking_id=:id ORDER BY bu.status', [':id'=>$theBaccount['id']])
			->queryAll();

		$methodList = Yii::$app->db->createCommand('SELECT method FROM at_payments GROUP BY method ORDER BY method')
			->queryAll();

		return $this->render('baccounts_r', [
			'theBaccount'=>$theBaccount,
			'theProduct'=>$theProduct,
			'thePeople'=>$thePeople,
			'theInvoice'=>$theInvoice,
			'thePayment'=>$thePayment,
			'methodList'=>$methodList,
		]);
	}

	public function actionU($id = 0)
	{
		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}
		$theBaccount->scenario = 'baccounts_u';

		if (!$theBaccount['product']) {
			throw new HttpException(404, 'Product not found');
		}

		if (!in_array(Yii::$app->user->id, [$theBaccount['product']['updated_by']])) {
			throw new HttpException(403, 'Access denied');
		}

		if ($theBaccount->load(Yii::$app->request->post())) {
			$theBaccount->updated_at = NOW;
			$theBaccount->updated_by = MY_ID;
			if ($theBaccount->save()) {
				return $this->redirect('@web/products/sb/'.$theBaccount['product']['id']);
			}
		}

		return $this->render('baccounts_u', [
			'theBaccount'=>$theBaccount,
		]);
	}

	public function actionD($id = 0)
	{
		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		// Must be case owner
		if (!in_array(Yii::$app->user->id, [1, $theBaccount['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot delete WON
		if ($theBaccount['status'] == 'won') {
			throw new HttpException(403, 'Access denied. Cannot delete a WON booking.');
		}

		if (!in_array(Yii::$app->user->id, [1, $theBaccount['created_by']])) {
			throw new HttpException(403, 'Access denied');
		}

		if (Yii::$app->request->post('confirm') == 'delete') {
			// Delete users
			Yii::$app->db->createCommand()
				->delete('at_booking_user', ['booking_id'=>$theBaccount['id']])
				->execute();
			// Delete booking
			$theBaccount->delete();
			// Change booking count
			Yii::$app->db->createCommand()
				->update('at_ct', ['offer_count'=>$theBaccount['product']['offer_count'] - 1], ['id'=>$theBaccount['product']['id']])
				->execute();

			return $this->redirect('@web/cases/r/'.$theBaccount['case']['id']);
		}

		return $this->render('baccounts_d', [
			'theBaccount'=>$theBaccount,
		]);
	}

	public function actionMp($id = 0)
	{
		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		$theBaccount->scenario = 'baccounts_mp';

		// Must be case owner
		if (!in_array(MY_ID, [1, $theBaccount['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON
		if ($theBaccount['status'] == 'won') {
			throw new HttpException(403, 'Access denied. Cannot delete a WON booking.');
		}

		// Cannot change LOST
		if ($theBaccount['status'] == 'pending') {
			throw new HttpException(403, 'Access denied. The status is already PENDING.');
		}

		$theBaccount->status = 'pending';
		$theBaccount->status_dt = NOW;
		$theBaccount->updated_at = NOW;
		$theBaccount->updated_by = Yii::$app->user->id;
		$theBaccount->save(false);

		// Mark as LOST
		return $this->redirect('@web/cases/r/'.$theBaccount['case']['id']);
	}

	public function actionMl($id = 0)
	{
		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		$theBaccount->scenario = 'baccounts_ml';

		// Must be case owner
		if (!in_array(MY_ID, [1, $theBaccount['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON
		if ($theBaccount['status'] == 'won') {
			throw new HttpException(403, 'Access denied. Cannot delete a WON booking.');
		}

		// Cannot change LOST
		if ($theBaccount['status'] == 'lost') {
			throw new HttpException(403, 'Access denied. The status is already LOST.');
		}

		$theBaccount->status = 'lost';
		$theBaccount->status_dt = NOW;
		$theBaccount->updated_at = NOW;
		$theBaccount->updated_by = Yii::$app->user->id;
		$theBaccount->save(false);

		// Mark as LOST
		return $this->redirect('@web/cases/r/'.$theBaccount['case']['id']);
	}

	public function actionMw($id = 0)
	{
		// Mark as won / confirmed

		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		$theBaccount->scenario = 'baccounts_mw';

		// Must be case owner
		if (!in_array(Yii::$app->user->id, [1, $theBaccount['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON
		if ($theBaccount['status'] == 'won') {
			throw new HttpException(403, 'Access denied. The status is already WON.');
		}

		if ($theBaccount->load(Yii::$app->request->post()) && $theBaccount->validate()) {
			// Update booking
			$theBaccount->status = 'won';
			$theBaccount->status_dt = NOW;
			$theBaccount->updated_at = NOW;
			$theBaccount->updated_by = MY_ID;
			$theBaccount->save(false);

			// Update case
			Yii::$app->db->createCommand()
				->update('at_cases', [
					'updated_at'=>NOW,
					'updated_by'=>MY_ID,
					'deal_status'=>'won',
					'deal_status_date'=>NOW,
					], ['id'=>$theBaccount['case_id']])
				->execute();

			// Create a tour
			$theTour = new Tour;
			$theTour->scenario = 'baccounts_mw';

			$theTour->status = 'draft';
			$theTour->uo = NOW;
			$theTour->ub = MY_ID;
			$theTour->code = 'TOUR-'.$theBaccount['product']['id'];
			$theTour->name = 'New tour from '.$theBaccount['start_date'];
			$theTour->se = $theBaccount['product']['created_by'];
			$theTour->owner = 118;
			$theTour->ct_id = $theBaccount['product']['id'];

			$theTour->save();

			return $this->redirect('@web/tours/r/'.$theTour['id']);
		}

		return $this->render('baccounts_mw', [
			'theBaccount'=>$theBaccount,
		]);
	}

	public function actionCxl($id = 0)
	{
		// Finish as canceled
		$theBaccount = Baccount::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		$theBaccount->scenario = 'baccounts_cxl';

		// Must be case owner
		if (!in_array(Yii::$app->user->id, [1, $theBaccount['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON or CANCELED
		if ($theBaccount['status'] != 'won' || $theBaccount['finish'] == 'canceled') {
			throw new HttpException(403, 'Invalid action.');
		}

		$theBaccount->finish = 'canceled';
		$theBaccount->finish_dt = NOW;
		$theBaccount->save(false);

		return $this->redirect('@web/cases/r/'.$theBaccount['case']['id']);
	}

	public function actionReport($id = 0)
	{
		$theBaccount = Baccount::find()
			->with(['product', 'case'])
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theBaccount) {
			throw new HttpException(404, 'Baccount not found');
		}

		// Must be case owner
		if (!in_array(MY_ID, [1, 4432, $theBaccount['created_by']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Must be WON and not CANCELED
		if ($theBaccount['status'] != 'won' || $theBaccount['finish'] == 'canceled') {
			throw new HttpException(403, 'Invalid action.');
		}

		$theReport = BaccountReport::find()
			->where(['booking_id'=>$theBaccount['id']])
			->one();

		if (!$theReport) {
			$theReport = new BaccountReport;
			$theReport->created_at = NOW;
			$theReport->created_by = MY_ID;
			$theReport->booking_id = $theBaccount['id'];
		}

		if ($theReport->load(Yii::$app->request->post()) && $theReport->validate()) {
			$theReport->updated_at = NOW;
			$theReport->updated_by = MY_ID;
			$theReport->save(false);
			return $this->redirect('@web/baccounts/reports');
		}

		return $this->render('baccounts_report', [
			'theBaccount'=>$theBaccount,
			'theReport'=>$theReport,
		]);
	}

	public function actionReports()
	{
		$getKhoihanh = Yii::$app->request->get('khoihanh', 0);
		$getBantour = Yii::$app->request->get('bantour', 0);
		$getSeller = Yii::$app->request->get('seller', 0);
		$getCurrency = Yii::$app->request->get('currency', 0);
		$getB2b = Yii::$app->request->get('b2b', 'b2c');

		$sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_baccounts b WHERE u.id=b.created_by GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
		$sellerList = Yii::$app->db->createCommand($sql)->queryAll();
		$sql = 'SELECT SUBSTRING(p.day_from,1,7) AS ym FROM at_ct p, at_baccounts b WHERE p.id=b.product_id AND b.status="won" GROUP BY ym ORDER BY ym DESC';
		$listKhoiHanh = Yii::$app->db->createCommand($sql)->queryAll();
		$sql = 'SELECT SUBSTRING(b.status_dt,1,7) AS ym FROM at_baccounts b WHERE b.status="won" GROUP BY ym ORDER BY ym DESC';
		$listBanTour = Yii::$app->db->createCommand($sql)->queryAll();

		$query = Baccount::find()
			->andWhere(['at_baccounts.status'=>'won']);

		if ((int)$getSeller != 0) {
			$query->andWhere(['at_baccounts.created_by'=>$getSeller]);
		}
		if ($getKhoihanh == 0 && $getBantour == 0) {
			$getBantour = date('Y-m');
		}
		if ($getBantour != 0) {
			$query->andWhere('SUBSTRING(at_baccounts.status_dt,1,7)=:ym', [':ym'=>$getBantour]);
		}

		if (in_array($getCurrency, ['EUR', 'USD', 'VND'])) {
			$query->andWhere(['currency'=>$getCurrency]);
		}

		$query->joinWith([
			'product'=>function($q) {
				$getKhoihanh = Yii::$app->request->get('khoihanh', 0);
				if ($getKhoihanh != 0) {
					$q->andWhere('SUBSTRING(day_from,1,7)=:ym', [':ym'=>$getKhoihanh]);
				}
			}
		]);

		$theBaccounts = $query
			->orderBy('at_ct.day_from')
			->with([
				'report',
				'product',
				'product.tour',
				'updatedBy'=>function($query) {
					return $query->select(['id', 'name', 'image']);
				},
				'case'=>function($query) {
					return $query->select(['id', 'name', 'owner_id', 'is_b2b']);
				},
				'case.owner'=>function($query) {
					return $query->select(['id', 'name']);
				}
				])
			->asArray()
			->all();

		return $this->render('baccounts_reports', [
			'getKhoihanh'=>$getKhoihanh,
			'getBantour'=>$getBantour,
			'getSeller'=>$getSeller,
			'getCurrency'=>$getCurrency,
			'getB2b'=>$getB2b,
			'theBaccounts'=>$theBaccounts,
			'sellerList'=>$sellerList,
			'listKhoiHanh'=>$listKhoiHanh,
			'listBanTour'=>$listBanTour,
		]);
	}
}
