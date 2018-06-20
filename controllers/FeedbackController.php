<?

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

use common\models\Feedback;
use common\models\Product;

class FeedbackController extends MyController
{

	public function actionIndex()
	{
		$getCase = Yii::$app->request->get('case', 0);
		$getOwner = Yii::$app->request->get('owner', '');
		$getUb = Yii::$app->request->get('ub', 0);
		$getProduct = Yii::$app->request->get('product', 0);
		$getLanguage = Yii::$app->request->get('language', '');
		$getStatus = Yii::$app->request->get('status', '');
		$getPayment = Yii::$app->request->get('payment', 0);

		$query = Feedback::find();
/*
		if ($getCase != 0) {
			$query->andWhere(['case_id'=>$getCase]);
		}

		if ((int)$getOwner > 0) {
			$query->andWhere(['created_by'=>$getOwner]);
		}

		if (in_array($getStatus, ['canceled'])) {
			$query->where(['finish'=>$getStatus]);
		}

		if ((int)$getUb != 0) {
			$query->andWhere(['ub'=>$getUb]);
		}

		if ((int)$getProduct != 0) {
			$query->andWhere(['product_id'=>$getProduct]);
		}*/

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);

		$theFeedbacks = $query
			->orderBy('created_dt DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->with([
				'updatedBy'=>function($query) {
					return $query->select(['id', 'name', 'image']);
				},
				'tour'=>function($query) {
					return $query->select(['id', 'op_name', 'op_code']);
				}
				])
			->asArray()
			->all();

		return $this->render('feedbacks', [
			'getStatus'=>$getStatus,
			'getCase'=>$getCase,
			'getOwner'=>$getOwner,
			'getProduct'=>$getProduct,
			'theFeedbacks'=>$theFeedbacks,
			'pagination'=>$pagination,
		]);
	}

	public function actionC($product_id = 0)
	{
		$theProduct = Product::findOne($product_id);

		if (!$theProduct) {
			return $this->render('bookings_c_noprod');
		}

		$theBooking = new Booking;
		$theBooking->scenario = 'bookings_c';

		$theBooking->start_date = $theProduct['day_from'];
		$theBooking->pax = $theProduct['pax'];
		$theBooking->price = $theProduct['price'];
		$theBooking->currency = $theProduct['price_unit'];

		// Find existing related cases
		$existingBookings = Booking::find()
			->select(['id', 'case_id'])
			->where(['product_id'=>$product_id])
			->asArray()
			->all();

		if ($theBooking->load(Yii::$app->request->post())) {
			if (Yii::$app->user->id == 1) {
				foreach ($existingBookings as $booking) {
					if ($booking['case_id'] == $theBooking->case_id) {
						throw new HttpException(403, 'This booking already exists');
					}
				}
			}

			$theBooking->product_id = $theProduct['id'];
			$theBooking->created_at = NOW;
			$theBooking->created_by = Yii::$app->user->id;
			$theBooking->updated_at = NOW;
			$theBooking->updated_by = Yii::$app->user->id;
			$theBooking->status = 'pending';
			$theBooking->status_dt = NOW;

			if ($theBooking->save()) {
				Yii::$app->db->createCommand('UPDATE at_ct SET offer_count=offer_count+1 WHERE id=:id', [':id'=>$theProduct['id']])->execute();

				return $this->redirect('@web/products/sb/'.$theProduct['id']);
			}
		}

		$existingCaseIds = [0];
		foreach ($existingBookings as $booking) {
			$existingCaseIds[] = $booking['case_id'];
		}

		$theCases = Kase::find()
			->select(['id', 'name'])
			->where(['status'=>'open', 'owner_id'=>MY_ID])
			->andWhere(['not in', 'id', $existingCaseIds])
			->orderBy('name')
			->asArray()
			->all();

		if (empty($theCases)) {
			throw new HttpException(404, 'You have no available cases for this booking.');			
		}

		return $this->render('bookings_c', [
			'theBooking'=>$theBooking,
			'existingBookings'=>$existingBookings,
			'theProduct'=>$theProduct,
			'theCases'=>$theCases,
		]);
	}

	public function actionR($id = 0)
	{
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with([
				'createdBy',
				'updatedBy',
				'product',
				'product.tour',
				'product.days',
				'case',
				'case.owner',
				'case.people'=>function($q) {
					return $q->select(['id', 'name', 'email']);
				},
				'invoices'=>function($q) {
					return $q->orderBy('due_dt');
				},
				'payments',
				'people',
			])
			->asArray()
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		$theProduct = Product::find()->where(['id'=>$theBooking['product_id']])->asArray()->one();

		$bookingOwner = User::find()
			->where(['id'=>$theBooking['created_by']])
			->asArray()
			->one();

		if (isset($theBooking['product']['tour']['id'])) {
			$tourPeople = Yii::$app->db
				->createCommand('SELECT u.email, u.fname, u.lname FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:id', [':id'=>$theBooking['product']['tour']['id']])
				->queryAll();
		}

		$theInvoice = new Invoice();
		$theInvoice->scenario = 'invoices_c';
		if ($theInvoice->load(Yii::$app->request->post()) && $theInvoice->validate()) {

			$theInvoice->booking_id = $theBooking['id'];
			$theInvoice->created_at = NOW;
			$theInvoice->created_by = Yii::$app->user->id;
			$theInvoice->updated_at = NOW;
			$theInvoice->updated_by = Yii::$app->user->id;
			$theInvoice->status = 'on';

			if ($theInvoice->save(false)) {
				Yii::$app->session->setFlash('success', 'Invoice has been added: '.number_format($theInvoice['amount'], 2).' '.$theInvoice['currency']);
				return $this->redirect('@web/bookings/r/'.$theBooking['id']);
			}
		}

		$thePayment = new Payment;		
		$thePayment->scenario = 'payments_c';

		if ($thePayment->load(Yii::$app->request->post()) && $thePayment->validate()) {

			$thePayment->booking_id = $theBooking['id'];
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
							'theBooking'=>$theBooking,
						],
						$args
					);
				}

				Yii::$app->session->setFlash('success', 'Payment has been added: '.number_format($thePayment['amount'], 2).' '.$thePayment['currency']);
				return $this->redirect('@web/bookings/r/'.$theBooking['id']);
			}
		}

		// Delete pax from booking
		if (isset($_GET['action']) && $_GET['action'] == 'delete-user-booking' && isset($_GET['user_id'])) {
			// Huan, CSKH
			if (in_array(Yii::$app->user->id, [1, 9881, 1351])) {
				Yii::$app->db->createCommand()
					->delete('at_booking_user', [
						'booking_id'=>$theBooking['id'],
						'user_id'=>$_GET['user_id'],
					])
					->execute();
				return $this->redirect('@web/bookings/r/'.$theBooking['id']);
			}
		}

		// Cancel pax from booking
		if (isset($_GET['action']) && $_GET['action'] == 'cancel-user-booking' && isset($_GET['user_id'])) {
			// Huan, CSKH
			if (in_array(Yii::$app->user->id, [1, 9881, 1351])) {
				Yii::$app->db->createCommand()
					->update('at_booking_user',
						[
						'updated_at'=>NOW,
						'updated_by'=>Yii::$app->user->id,
						'status'=>'canceled',
						], [
						'booking_id'=>$theBooking['id'],
						'user_id'=>$_GET['user_id'],
						]
					)
					->execute();
				return $this->redirect('@web/bookings/r/'.$theBooking['id']);
			}
		}

		// Add pax
		// Format 1 : id
		// Format 2 : email
		// Format 3 : name
		// Format 4 : lname; fname; gender; country; birthdate; email
		if (in_array(MY_ID, [1, 9881, 1351, 29123]) && isset($_POST['action'], $_POST['name']) && $_POST['action'] == 'add-pax') {
			$name = \fUTF8::ucwords(\fUTF8::lower(\fUTF8::trim($_POST['name'])));

			if (strpos($_POST['name'], '/') === false) {
				if ((int)$name > 0) {
					// Format 1 : id
					$theUsers = User::find()
						->where(['id'=>$name])
						->all();
				} elseif (strpos($name, '@') !== false) {
					// Format 2 : email
					$theUsers = User::findBySql('SELECT u.* FROM persons u, at_meta m WHERE m.rtype="user" AND m.rid=u.id AND m.k="email" AND m.v=:email GROUP BY u.id', [':email'=>\fUTF8::lower($name)])
						->asArray()
						->all();
				} else {
					// Format 2 : name
					$theUsers = User::find()
						->where(['name'=>$name])
						->orWhere('CONCAT(fname, " ", lname)=:name', [':name'=>$name])
						->orWhere('CONCAT(lname, " ", fname)=:name', [':name'=>$name])
						->asArray()
						->all();
				}
			} else {
				// Format 4 : family long:family short / given / gender country birthdate email
				$_parts = explode('/', $name);
				if (strpos($_parts[0], ':') === false) {
					$_name = trim($_parts[0]).' '.trim($_parts[1]);
				} else {
					$_fn = explode(':', $_parts[0]);
					$_name = trim($_parts[0]).' '.trim($_fn[1]);
				}
				$theUsers = User::find()
					->where(['name'=>$_name])
					->orWhere('CONCAT(fname, " ", lname)=:name', [':name'=>$_name])
					->orWhere('CONCAT(lname, " ", fname)=:name', [':name'=>$_name])
					->asArray()
					->all();
			}

			if (!$theUsers) {
				if (\fUTF8::len($name) > 6) {
					// Add pax if this is a name First Last
					$newUser = new User;
					$newUser->created_at = NOW;
					$newUser->created_by = MY_ID;
					$newUser->updated_at = NOW;
					$newUser->updated_by = MY_ID;
					$newUser->status = 'on';
					$newUser->name = $name;

					if (strpos($name, '/') !== false) {
						$nameParts = explode('/', $name);
						$newUser->fname = \fUTF8::trim($nameParts[0]);
						$newUser->lname = \fUTF8::trim($nameParts[1]);
						if (strpos($newUser->fname, ':') === false) {
							$newUser->name = $newUser->lname.' '.$newUser->fname;
						} else {
							$_fn = explode(':', $newUser->fname);
							$newUser->fname = trim($_fn[0]);
							$newUser->name = $newUser->lname.' '.trim($_fn[1]);
						}
						if (isset($nameParts[2])) {
							$_px = explode(' ', $nameParts[2]);
							foreach ($_px as $_p) {
								$item = strtolower(trim($_p));
								if ($item == 'm') {
									$newUser->gender = 'male';
								}
								if ($item == 'f') {
									$newUser->gender = 'female';
								}
								if (strpos($item, '@') !== false) {
									$_email = trim(strtolower($item));
									$newUser->email = $_email;
								}
								if (strlen($_p) == 2) {
									$newUser->country_code = $item;
								}
								if (strpos($item, '-') !== false && strpos($item, '@') === false) {
									$dmy = explode('-', $item);
									if (count($dmy) == 3) {
										$newUser->bday = trim($dmy[0]);
										$newUser->bmonth = trim($dmy[1]);
										$newUser->byear = trim($dmy[2]);
									}
								}

							}
						}

						//\fCore::expose($nameParts);
						//\fCore::expose($newUser);
						//exit;
					}

					if ($newUser->save(false)) {
						Yii::$app->db->createCommand()
							->insert('at_booking_user', [
								'created_at'=>NOW,
								'created_by'=>MY_ID,
								'updated_at'=>NOW,
								'updated_by'=>MY_ID,
								'booking_id'=>$theBooking['id'],
								'user_id'=>$newUser['id'],
								])
							->execute();
						Yii::$app->db->createCommand()
							->insert('at_search', [
								'rtype'=>'user',
								'rid'=>$newUser->id,
								'search'=>str_replace('-', '', \fURL::makeFriendly($newUser->name, '-')),
								'found'=>$newUser->name,
								])
							->execute();
						if (isset($_email)) {
							Yii::$app->db->createCommand()
								->insert('at_meta', [
									'rtype'=>'user',
									'rid'=>$newUser->id,
									'k'=>'email',
									'v'=>$_email,
									])
								->execute();
						}
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
							'booking_id'=>$theBooking['id'],
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
						//die('<p>Insert one of user IDs above or add new user by adding a plus sign before name, eg. "+Nguyen Van A"</p>');
					}
					// Yii::$app->session->set('searchUsers', $theUsers);
				}
			}
			return $this->redirect('@web/bookings/r/'.$theBooking['id']);
		}

		$thePeople = Yii::$app->db->createCommand('SELECT u.id, u.fname, u.lname, u.byear, u.email, u.gender, u.country_code, u.name, bu.status FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.booking_id=:id ORDER BY bu.status', [':id'=>$theBooking['id']])
			->queryAll();

		$methodList = Yii::$app->db->createCommand('SELECT method FROM at_payments GROUP BY method ORDER BY method')
			->queryAll();

		return $this->render('bookings_r', [
			'theBooking'=>$theBooking,
			'theProduct'=>$theProduct,
			'thePeople'=>$thePeople,
			'theInvoice'=>$theInvoice,
			'thePayment'=>$thePayment,
			'methodList'=>$methodList,
		]);
	}

	public function actionU($id = 0)
	{
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}
		$theBooking->scenario = 'bookings_u';

		if (!$theBooking['product']) {
			throw new HttpException(404, 'Product not found');
		}

		if (!in_array(Yii::$app->user->id, [$theBooking['product']['updated_by']])) {
			throw new HttpException(403, 'Access denied');
		}

		if ($theBooking->load(Yii::$app->request->post())) {
			$theBooking->updated_at = NOW;
			$theBooking->updated_by = MY_ID;
			if ($theBooking->save()) {
				return $this->redirect('@web/products/sb/'.$theBooking['product']['id']);
			}
		}

		return $this->render('bookings_u', [
			'theBooking'=>$theBooking,
		]);
	}

	public function actionD($id = 0)
	{
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		// Must be case owner
		if (!in_array(Yii::$app->user->id, [1, $theBooking['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot delete WON
		if ($theBooking['status'] == 'won') {
			throw new HttpException(403, 'Access denied. Cannot delete a WON booking.');
		}

		if (!in_array(Yii::$app->user->id, [1, $theBooking['created_by']])) {
			throw new HttpException(403, 'Access denied');
		}

		if (Yii::$app->request->post('confirm') == 'delete') {
			// Delete users
			Yii::$app->db->createCommand()
				->delete('at_booking_user', ['booking_id'=>$theBooking['id']])
				->execute();
			// Delete booking
			$theBooking->delete();
			// Change booking count
			Yii::$app->db->createCommand()
				->update('at_ct', ['offer_count'=>$theBooking['product']['offer_count'] - 1], ['id'=>$theBooking['product']['id']])
				->execute();

			return $this->redirect('@web/cases/r/'.$theBooking['case']['id']);
		}

		return $this->render('bookings_d', [
			'theBooking'=>$theBooking,
		]);
	}

	public function actionMp($id = 0)
	{
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		$theBooking->scenario = 'bookings_mp';

		// Must be case owner
		if (!in_array(MY_ID, [1, $theBooking['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON
		if ($theBooking['status'] == 'won') {
			throw new HttpException(403, 'Access denied. Cannot delete a WON booking.');
		}

		// Cannot change LOST
		if ($theBooking['status'] == 'pending') {
			throw new HttpException(403, 'Access denied. The status is already PENDING.');
		}

		$theBooking->status = 'pending';
		$theBooking->status_dt = NOW;
		$theBooking->updated_at = NOW;
		$theBooking->updated_by = Yii::$app->user->id;
		$theBooking->save(false);

		// Mark as LOST
		return $this->redirect('@web/cases/r/'.$theBooking['case']['id']);
	}

	public function actionMl($id = 0)
	{
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		$theBooking->scenario = 'bookings_ml';

		// Must be case owner
		if (!in_array(MY_ID, [1, $theBooking['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON
		if ($theBooking['status'] == 'won') {
			throw new HttpException(403, 'Access denied. Cannot delete a WON booking.');
		}

		// Cannot change LOST
		if ($theBooking['status'] == 'lost') {
			throw new HttpException(403, 'Access denied. The status is already LOST.');
		}

		$theBooking->status = 'lost';
		$theBooking->status_dt = NOW;
		$theBooking->updated_at = NOW;
		$theBooking->updated_by = Yii::$app->user->id;
		$theBooking->save(false);

		// Mark as LOST
		return $this->redirect('@web/cases/r/'.$theBooking['case']['id']);
	}

	public function actionMw($id = 0)
	{
		// Mark as won / confirmed

		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		$theBooking->scenario = 'bookings_mw';

		// Must be case owner
		if (!in_array(Yii::$app->user->id, [1, $theBooking['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON
		if ($theBooking['status'] == 'won') {
			throw new HttpException(403, 'Access denied. The status is already WON.');
		}

		if ($theBooking->load(Yii::$app->request->post()) && $theBooking->validate()) {
			// Update booking
			$theBooking->status = 'won';
			$theBooking->status_dt = NOW;
			$theBooking->updated_at = NOW;
			$theBooking->updated_by = MY_ID;
			$theBooking->save(false);

			// Update case
			Yii::$app->db->createCommand()
				->update('at_cases', [
					'updated_at'=>NOW,
					'updated_by'=>MY_ID,
					'deal_status'=>'won',
					'deal_status_date'=>NOW,
					], ['id'=>$theBooking['case_id']])
				->execute();

			// Create a tour
			$theTour = new Tour;
			$theTour->scenario = 'bookings_mw';

			$theTour->status = 'draft';
			$theTour->uo = NOW;
			$theTour->ub = MY_ID;
			$theTour->code = 'TOUR-'.$theBooking['product']['id'];
			$theTour->name = 'New tour from '.$theBooking['start_date'];
			$theTour->se = $theBooking['product']['created_by'];
			$theTour->owner = 118;
			$theTour->ct_id = $theBooking['product']['id'];

			$theTour->save();

			return $this->redirect('@web/tours/r/'.$theTour['id']);
		}

		return $this->render('bookings_mw', [
			'theBooking'=>$theBooking,
		]);
	}

	public function actionCxl($id = 0)
	{
		// Finish as canceled
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->with(['product', 'case'])
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		$theBooking->scenario = 'bookings_cxl';

		// Must be case owner
		if (!in_array(Yii::$app->user->id, [1, $theBooking['case']['owner_id']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Cannot change WON or CANCELED
		if ($theBooking['status'] != 'won' || $theBooking['finish'] == 'canceled') {
			throw new HttpException(403, 'Invalid action.');
		}

		$theBooking->finish = 'canceled';
		$theBooking->finish_dt = NOW;
		$theBooking->save(false);

		return $this->redirect('@web/cases/r/'.$theBooking['case']['id']);
	}

	public function actionReport($id = 0)
	{
		$theBooking = Booking::find()
			->with(['product', 'case'])
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found');
		}

		// Must be case owner
		if (!in_array(MY_ID, [1, 1087, $theBooking['created_by'], $theBooking['updated_by']])) {
			throw new HttpException(403, 'Access denied');
		}

		// Must be WON and not CANCELED
		if ($theBooking['status'] != 'won' || $theBooking['finish'] == 'canceled') {
			throw new HttpException(403, 'Invalid action.');
		}

		$theReport = BookingReport::find()
			->where(['booking_id'=>$theBooking['id']])
			->one();

		if (!$theReport) {
			$theReport = new BookingReport;
			$theReport->created_at = NOW;
			$theReport->created_by = MY_ID;
			$theReport->booking_id = $theBooking['id'];
		}

		if ($theReport->load(Yii::$app->request->post()) && $theReport->validate()) {
			$theReport->updated_at = NOW;
			$theReport->updated_by = MY_ID;
			$theReport->save(false);
			return $this->redirect('@web/bookings/reports');
		}

		return $this->render('bookings_report', [
			'theBooking'=>$theBooking,
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

		$sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_bookings b WHERE u.id=b.created_by GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
		$sellerList = Yii::$app->db->createCommand($sql)->queryAll();
		$sql = 'SELECT SUBSTRING(p.day_from,1,7) AS ym FROM at_ct p, at_bookings b WHERE p.id=b.product_id AND b.status="won" GROUP BY ym ORDER BY ym DESC';
		$listKhoiHanh = Yii::$app->db->createCommand($sql)->queryAll();
		$sql = 'SELECT SUBSTRING(b.status_dt,1,7) AS ym FROM at_bookings b WHERE b.status="won" GROUP BY ym ORDER BY ym DESC';
		$listBanTour = Yii::$app->db->createCommand($sql)->queryAll();

		$query = Booking::find()
			->andWhere(['at_bookings.status'=>'won']);

		if ((int)$getSeller != 0) {
			$query->andWhere(['at_bookings.created_by'=>$getSeller]);
		}
		if ($getKhoihanh == 0 && $getBantour == 0) {
			$getBantour = date('Y-m');
		}
		if ($getBantour != 0) {
			$query->andWhere('SUBSTRING(at_bookings.status_dt,1,7)=:ym', [':ym'=>$getBantour]);
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

		$theBookings = $query
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

		return $this->render('bookings_reports', [
			'getKhoihanh'=>$getKhoihanh,
			'getBantour'=>$getBantour,
			'getSeller'=>$getSeller,
			'getCurrency'=>$getCurrency,
			'getB2b'=>$getB2b,
			'theBookings'=>$theBookings,
			'sellerList'=>$sellerList,
			'listKhoiHanh'=>$listKhoiHanh,
			'listBanTour'=>$listBanTour,
		]);
	}

	public function actionRegInfo($id = 0)
	{
		$theBooking = Booking::find()
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theBooking) {
			throw new HttpException(404, 'Booking not found.');
		}

		$sql1 = 'SELECT * FROM at_booking_pax WHERE booking_id=:id';
		$regInfo['travellers'] = Yii::$app->db->createCommand($sql1, [':id'=>$id])->queryAll();
		$sql2 = 'SELECT * FROM at_booking_flights WHERE booking_id=:id ORDER BY departure_dt';
		$regInfo['flights'] = Yii::$app->db->createCommand($sql2, [':id'=>$id])->queryAll();
		$sql3 = 'SELECT * FROM at_booking_rooms WHERE booking_id=:id';
		$regInfo['rooms'] = Yii::$app->db->createCommand($sql3, [':id'=>$id])->queryAll();
		$countryList = Country::find()->select(['code', 'name'=>'name_en'])->asArray()->all();
		return $this->render('bookings_reg-info', [
			'theBooking'=>$theBooking,
			'regInfo'=>$regInfo,
			'countryList'=>$countryList,
		]);
	}
}
