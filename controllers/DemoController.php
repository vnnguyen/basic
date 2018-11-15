<?
namespace app\controllers;

use Yii;
use common\models\Payment;
use common\models\Booking;
use common\models\Invoice;
use common\models\Tour;
use common\models\Product;
use common\models\Cpt;
use common\models\Kase;
use common\models\Venue;


use yii\web\HttpException;
use yii\web\Response;
use yii\data\Pagination;
use common\models\Customer;
use common\models\Country;
use common\models\Person;
use common\models\Ct;
use common\models\Task;

use common\models\Contact;

use common\models\Dvt;
use common\models\Dv;
use common\models\Dvg;
use common\models\Destination;
use common\models\Ncc2;
use common\models\CpTour;

use app\models\Translate;
use app\models\UserNotification;

use yii\web\Controller;
use yii\web\UploadedFile;

use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Worksheet_Drawing;
use \PHPExcel_Helper_HTML;
use \PHPExcel_Style_Alignment;




use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\Update;

use app\models\Job;
use common\models\User;
use app\jobqueues\SendNotif;

use app\models\MyMailQueueModel;

use app\components\MyMailHandler;


class DemoController extends MyController
{
    public function actionCptour($id = 0)
    {

        $model = new CpTour();
        $theTour = Product::find()//12537
            ->where(['id' => 12537, 'op_status' => 'op'])
            ->with([
                'bookings',
                'bookings.people'       => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'bday', 'bmonth', 'byear', 'gender', 'country_code'])
                        ->orderBy('byear, bmonth, bday');
                },
                'bookings.people.metas' => function ($q) {
                    return $q->select(['rid', 'value'])
                        ->where(['name' => 'passport']);
                },
                'days',
                'updatedBy',
                'guides',
                'tour.cskh',
                'tour.operators',
            ])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($theTour->day_ids) {
            $dayIdList = explode(',', $theTour->day_ids);
            $start_date = $theTour->day_from;
            $arr_day = [];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $id) {
                foreach ($theTour->days as $day) {
                    if ($day['id'] == $id) {
                        $cnt ++;
                        $arr_day[date('Y/m/d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt;
                        //echo date('j/n/Y D', strtotime('+'.($cnt - 1).' days', strtotime($start_date))).'<br/>';
                    }
                }
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $cptour = $_POST['CpTour'];
            if ($cptour['id'] == '') {
                $model->tour_id = $query->id;
                $model->venue_id = $cptour['venue_id'];
                $model->dv_id = $cptour['dv_id'];
                $model->qty = $cptour['qty'];
                $model->currency = $cptour['currency'];
                $model->use_day = $cptour['use_day'];
                $model->payment_dt = $cptour['payment_dt'];
                $model->who_pay = $cptour['who_pay'];
                $model->num_day = $cptour['num_day'];
                $model->book_of = $cptour['book_of'];
                $model->pay_of = $cptour['pay_of'];
                $model->status_book = $cptour['status_book'];
                $model->parent_id = 0;
                $model->use_day = date('Y/m/d', strtotime($cptour['use_day']));
                $model->price = str_replace(',', '', $cptour['price']);
                // check group
                if (isset($_POST['cpt-group'])) {
                    $cpt_group = CpTour::findOne($_POST['cpt-group']);
                    if ($cpt_group != null) {
                        if ($cpt_group->group_id == 0) {
                            $cpt_group->group_id = $cpt_group->id;
                            if ($cpt_group->save()) {
                                $model->group_id = $cpt_group->group_id;
                            }
                        } else {
                            $model->group_id = $cpt_group->group_id;
                        }
                    }
                }
                if ($model->validate() && $model->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Saved cpt success');
                    if (isset($_POST['chk_option'])) {
                        $chk_options = $_POST['chk_option'];
                        foreach ($chk_options as $option) {
                            $option = json_decode($option);
                            $cpt = new CpTour();
                            $cpt->dv_id = $option->dv_id;
                            $cpt->qty = $option->qty;
                            $cpt->price = str_replace(',', '',$option->price);
                            $cpt->currency = $option->currency;
                            $cpt->tour_id = $model->tour_id;
                            $cpt->venue_id = $model->venue_id;
                            $cpt->use_day = $model->use_day;
                            $cpt->payment_dt = $model->payment_dt;
                            $cpt->who_pay = $model->who_pay;
                            $cpt->num_day = $model->num_day;
                            $cpt->book_of = $model->book_of;
                            $cpt->pay_of = $model->pay_of;
                            $cpt->status_book = $model->status_book;
                            $cpt->parent_id = $model->id;
                            if (!$cpt ->save()) {
                                Yii::$app->getSession()->setFlash('err', 'Saved cpt fail');
                            }
                        }
                    }
                }

                return $this->redirect(Yii::$app->request->referrer);
            } else {
                $cpt_updated = CpTour::findOne($cptour['id']);
                if ($cpt_updated == null) {
                    throw new HttpException(404, 'cpt not found.');
                }
                if (isset($cptour['venue_id']) && $cptour['venue_id'] != '') {
                    $cpt_updated->venue_id = $cptour['venue_id'];
                }
                $cpt_updated->dv_id = $cptour['dv_id'];
                $cpt_updated->qty = $cptour['qty'];
                $cpt_updated->currency = $cptour['currency'];
                $cpt_updated->use_day = $cptour['use_day'];
                $cpt_updated->payment_dt = $cptour['payment_dt'];
                $cpt_updated->who_pay = $cptour['who_pay'];
                $cpt_updated->num_day = $cptour['num_day'];
                $cpt_updated->book_of = $cptour['book_of'];
                $cpt_updated->pay_of = $cptour['pay_of'];
                $cpt_updated->status_book = $cptour['status_book'];
                $cpt_updated->use_day = date('Y/m/d', strtotime($cptour['use_day']));
                $cpt_updated->price = str_replace(',', '', $cptour['price']);
                // check group
                $cpt_in_group = CpTour::find()->where('group_id = '.$cpt_updated->group_id.' AND group_id > 0  AND id != '. $cpt_updated->id)->all();
                if ($cpt_in_group != null) {
                    if (isset($_POST['cpt-group']) && $_POST['cpt-group'] != '') {
                        if ($cpt_updated->group_id != $_POST['cpt-group']) {
                            $cpt_grouped = CpTour::findOne($_POST['cpt-group']);
                            if ($cpt_grouped != null) {
                                if ($cpt_grouped->group_id == 0) {
                                    $cpt_grouped->group_id = $cpt_grouped->id;
                                    if ($cpt_grouped->save()) {
                                        $cpt_updated->group_id = $cpt_grouped->group_id;
                                    }
                                } else {
                                    $cpt_updated->group_id = $cpt_grouped->group_id;
                                }
                            }
                            if ($cpt_updated->id == $cpt_in_group[0]->group_id && count($cpt_in_group) > 0) {
                                if (count($cpt_in_group) == 1) {
                                    $cpt_in_group[0]->group_id = 0;
                                    $cpt_in_group[0]->save();
                                } else {
                                    foreach ($cpt_in_group as $cpt_g) {
                                        $cpt_g->group_id = $cpt_in_group[0]->id;
                                        $cpt_g->save();
                                    }
                                }
                            }
                        }
                    } else {
                        if ($cpt_updated->id == $cpt_in_group[0]->group_id && count($cpt_in_group) > 0) {
                            if (count($cpt_in_group) == 1) {
                                $cpt_in_group[0]->group_id = 0;
                                $cpt_in_group[0]->save();
                            } else {
                                foreach ($cpt_in_group as $cpt_g) {
                                    $cpt_g->group_id = $cpt_in_group[0]->id;
                                    $cpt_g->save();
                                }
                            }
                        }
                        $cpt_updated->group_id = 0;
                    }
                } else {
                    if (isset($_POST['cpt-group'])) {
                        $cpt_grouped = CpTour::findOne($_POST['cpt-group']);
                        if ($cpt_grouped != null) {
                            if ($cpt_grouped->group_id == 0) {
                                $cpt_grouped->group_id = $cpt_grouped->id;
                                if ($cpt_grouped->save()) {
                                    $cpt_updated->group_id = $cpt_grouped->group_id;
                                }
                            } else {
                                $cpt_updated->group_id = $cpt_grouped->group_id;
                            }
                        }
                    }
                }
                // save and add or update options
                if ($cpt_updated->validate() && $cpt_updated->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Saved cpt success');
                    $cpt_has_parent = CpTour::find()->select(['id'])->where('parent_id = '.$cpt_updated->id)->indexBy('id')->column();
                    if ($cpt_has_parent != null) {
                        ///check remove or update exist option
                        $arr_diff = [];
                        $arr_updated_ids = [];
                         if (isset($_POST['chk_option'])) {
                            $chk_options = $_POST['chk_option'];
                            foreach ($chk_options as $option) {
                                $option = json_decode($option);
                                if ($option->id != '') {
                                    ///update option
                                    $cpt = CpTour::findOne($option->id);
                                    if ($cpt != null) {
                                        $arr_updated_ids[] = $cpt->id;
                                        $cpt->qty = $option->qty;
                                        $cpt->price = str_replace(',', '', $option->price);
                                        $cpt->currency = $option->currency;
                                        $cpt->tour_id = $cpt_updated->tour_id;
                                        $cpt->venue_id = $cpt_updated->venue_id;
                                        $cpt->use_day = $cpt_updated->use_day;
                                        $cpt->payment_dt = $cpt_updated->payment_dt;
                                        $cpt->who_pay = $cpt_updated->who_pay;
                                        $cpt->num_day = $cpt_updated->num_day;
                                        $cpt->book_of = $cpt_updated->book_of;
                                        $cpt->pay_of = $cpt_updated->pay_of;
                                        $cpt->status_book = $cpt_updated->status_book;
                                        $cpt->parent_id = $cpt_updated->id;
                                        if (!$cpt ->save()) {
                                            Yii::$app->getSession()->setFlash('err', 'Saved cpt option fail');
                                        }
                                    }
                                } else {
                                    ///add new option
                                    $cpt = new CpTour();
                                    $cpt->dv_id = $option->dv_id;
                                    $cpt->qty = $option->qty;
                                    $cpt->price = str_replace(',', '', $option->price);
                                    $cpt->currency = $option->currency;
                                    $cpt->tour_id = $cpt_updated->tour_id;
                                    $cpt->venue_id = $cpt_updated->venue_id;
                                    $cpt->use_day = $cpt_updated->use_day;
                                    $cpt->payment_dt = $cpt_updated->payment_dt;
                                    $cpt->who_pay = $cpt_updated->who_pay;
                                    $cpt->num_day = $cpt_updated->num_day;
                                    $cpt->book_of = $cpt_updated->book_of;
                                    $cpt->pay_of = $cpt_updated->pay_of;
                                    $cpt->status_book = $cpt_updated->status_book;
                                    $cpt->parent_id = $cpt_updated->id;
                                    if (!$cpt ->save()) {
                                        Yii::$app->getSession()->setFlash('err', 'Saved cpt option fail');
                                    }
                                }
                            }
                            $arr_diff = array_diff($cpt_has_parent,$arr_updated_ids);
                         } else {
                            $arr_diff = $cpt_has_parent;
                         }
                         // var_dump($_POST);die();
                         if (count($arr_diff) > 0) {
                             $cpt_removeds = CpTour::find()->where(['id' => $arr_diff])->all();
                             foreach ($cpt_removeds as $cpt_op) {
                                 $cpt_op->delete();
                             }
                         }
                    } else {
                        if (isset($_POST['chk_option'])) {
                            ///insert new options
                            $chk_options = $_POST['chk_option'];
                            foreach ($chk_options as $option) {
                                $option = json_decode($option);
                                $cpt = new CpTour();
                                $cpt->dv_id = $option->dv_id;
                                $cpt->qty = $option->qty;
                                $cpt->price = str_replace(',', '', $option->price);
                                $cpt->currency = $option->currency;
                                $cpt->tour_id = $cpt_updated->tour_id;
                                $cpt->venue_id = $cpt_updated->venue_id;
                                $cpt->use_day = $cpt_updated->use_day;
                                $cpt->payment_dt = $cpt_updated->payment_dt;
                                $cpt->who_pay = $cpt_updated->who_pay;
                                $cpt->num_day = $cpt_updated->num_day;
                                $cpt->book_of = $cpt_updated->book_of;
                                $cpt->pay_of = $cpt_updated->pay_of;
                                $cpt->status_book = $cpt_updated->status_book;
                                $cpt->parent_id = $cpt_updated->id;
                                if (!$cpt ->save()) {
                                    Yii::$app->getSession()->setFlash('err', 'Saved cpt option fail');
                                }
                            }
                        }
                    }
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        $cpts = CpTour::find()
            ->with([
                'venue',
                'dv'
            ])
            ->where('tour_id = 12537');
        // var_dump($cpts->all());die;

        // $model = new CpTour();
        // return $this->render('cptour', []);
        // return $this->render('demo_search');
        return $this->render('demo_search_flexbox', [
            'theTour'=> $theTour,
            'cpts' => $cpts->all(),
            'model' => $model,
        ]);
    }
    public function actionSearch($id = 0)
    {

        $model = new CpTour();
        $theTour = Product::find()//12537
            ->where(['id' => 12537, 'op_status' => 'op'])
            ->with([
                'bookings',
                'bookings.people'       => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'bday', 'bmonth', 'byear', 'gender', 'country_code'])
                        ->orderBy('byear, bmonth, bday');
                },
                'bookings.people.metas' => function ($q) {
                    return $q->select(['rid', 'value'])
                        ->where(['name' => 'passport']);
                },
                'days',
                'updatedBy',
                'guides',
                'tour.cskh',
                'tour.operators',
            ])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($theTour->day_ids) {
            $dayIdList = explode(',', $theTour->day_ids);
            $start_date = $theTour->day_from;
            $arr_day = [];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $id) {
                foreach ($theTour->days as $day) {
                    if ($day['id'] == $id) {
                        $cnt ++;
                        $arr_day[date('Y/m/d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt;
                    }
                }
            }
        }
        return $this->render('demo_search', [
            'theTour'=> $theTour,
        ]);
    }
    public function actionFulltour($id = 0)
    {

        $model = new CpTour();
        $theTour = Product::find()
            ->where(['id' => 12537, 'op_status' => 'op'])
            ->with([
                'bookings',
                'bookings.people'       => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'bday', 'bmonth', 'byear', 'gender', 'country_code'])
                        ->orderBy('byear, bmonth, bday');
                },
                'bookings.people.metas' => function ($q) {
                    return $q->select(['rid', 'value'])
                        ->where(['name' => 'passport']);
                },
                'days',
                'updatedBy',
                'guides',
                'tour.cskh',
                'tour.operators',
            ])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($theTour->day_ids) {
            $dayIdList = explode(',', $theTour->day_ids);
            $start_date = $theTour->day_from;
            $arr_day = [];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $id) {
                foreach ($theTour->days as $day) {
                    if ($day['id'] == $id) {
                        $cnt ++;
                        $arr_day[date('Y/m/d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt;
                    }
                }
            }
        }
        $cpts = CpTour::find()
            ->with([
                'venue',
                'dv'
            ])
            ->where('tour_id = 12537');
        return $this->render('full_tour', [
            'theTour'=> $theTour,
            'cpts' => $cpts->all(),
            'model' => $model,
        ]);
    }
    public function actionWs()
    {
        //long polling
        return $this->render('ws');
        //SSE
        // return $this->render('ws1');
        // return $this->renderPartial('ws2');

    }
    public function actionSse_server()
    {
        session_write_close();
        ignore_user_abort(true);
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Connection: keep-alive");

        $sse = new SSE();
        $sse->start(new Update(function(){
            $model = new UserNotification;
            $tickets = $model->fetchDataToSend(USER_ID);
            $newMsgs = count($tickets);
            if (!empty($newMsgs)) {
                return json_encode(['newMsgs' => $newMsgs]);
            }
            return false;
        }), 'new-msgs');

    }
    public function actionSse_server1()
    {
        // session_start();
        session_write_close();

        // disable default disconnect checks
        ignore_user_abort(true);

        // set headers for stream
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");
        $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
        if ($lastEventId == 0) {
            $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
        }

        echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
        echo "retry: 2000\n";

        // start stream
        while(true){

            if(connection_aborted()){
                die('not ok');
                exit();
            } else {

                // here you will want to get the latest event id you have created on the server, but for now we will increment and force an update
                $latestEventId = $lastEventId+1;

                if($lastEventId < $latestEventId) {
                    echo "id: " . $latestEventId . "\n";
                    echo "data: Howdy (".$latestEventId.") \n\n";
                    $lastEventId = $latestEventId;
                    ob_flush();
                    flush();

                } else {
                    echo ": heartbeat\n\n";
                    ob_flush();
                    flush();
                }

            }

            // 2 second sleep then carry on
            sleep(3);

        }

    }
    public function actionR_server()
    {
        session_write_close();
        ignore_user_abort(true);
        $model = new UserNotification;

        $recipientUid = isset($_REQUEST["recipientUid"]) ? (int)$_REQUEST["recipientUid"] : USER_ID;
        $displayedNotificationNum = isset($_REQUEST["displayedNotificationNum"]) ? (int)$_REQUEST["displayedNotificationNum"]: 0;
        $secCount = 0;

        do {
            sleep(3);
            $ids = [];
            $updatedNotification = $model->fetchDataToSend($recipientUid);
            foreach ($updatedNotification as $nty) {
                $ids[] = $nty['id'];
            }
            $updatedNotificationNum = count($ids);
            // var_dump($updatedNotification);die;
            // $updatedNotificationNum = $model->fetchNumberByRecipientUid($recipientUid);
            // if(connection_aborted()){exit();}
        } while ($updatedNotificationNum == $displayedNotificationNum);
        return json_encode([
            // 'updatedNotification' => $updatedNotification,
            'ids' => $ids,
            'updatedNotificationNum' => $updatedNotificationNum
        ]);

    }

    public function actionRun_queue()
    {
        session_write_close();
        // ignore_user_abort(true);

        try {
            Yii::$app->db->close();
            Yii::$app->db->open();
            echo 'Sendding.. <br>';
            Yii::$app->queue->run(2,3);
            echo '<br>Sended! <br>';
        } catch (Exception $e) {
            Yii::error(LoggerMessage::log($e), __METHOD__);
        }

        // session_write_close();
        // ignore_user_abort(true);

    }

    public function actionPush_queue()
    {
        /*
        notification1 fullcustommer use (queue, send mail, send noti screen)
        */

        $id = Yii::$app->ntyModel->add([34718,34718,34718,34718,34718,34718,34718], 1, 'create', 'email test', 'url', 'all');


        /*
        notification2 use (queue, send mail, send noti screen)
        */

        // $user = User::findOne(34718);
        // $noti = Yii::$app->userNty::create(Yii::$app->userNty::KEY_NEW_ACCOUNT, [
        //     'datas' => [
        //         'user' => $user,
        //         'channels' => null
        // ]])->send();



        // $id = Yii::$app->queue->delay(1 * 60)->push(new SendNotif([
        //     'datas' => $noti
        // ]));






        // Yii::$app->ntyModel->sendAllMailQueue();
        // die('hello !!!');
        // var_dump(Yii::$app->queue->isDone($id));die;
    }


    public function actionClients()
    {
        $theContacts = Contact::find()
            ->innerJoinWith('bookings')
            ->join('INNER JOIN', 'at_ct', 'at_bookings.product_id = at_ct.id')
            ->where('YEAR(contacts.created_at)= 2018 AND MONTH(contacts.created_at) = 8 AND at_bookings.status = "won" AND at_ct.status = "on" AND SUBSTRING(at_ct.op_code, 1, 1) = "F"')
            ->with([
                'metas'=>function($q){
                    return $q->select(['id', 'rid', 'name', 'value', 'format']);
                },
            ])
            ->asArray()
            ->all();

        // $countryList = Country::find()
        //     ->select(['code', 'name'=>'name_'.Yii::$app->language])
        //     ->orderBy('name')
        //     ->asArray()
        //     ->all();

        return $this->render('clients', [
            'theContacts'=>$theContacts,
            // 'countryList'=>$countryList,
        ]);
    }


    //fb qhkh
    public function actionFb_qhkh(){
        return $this->renderPartial('fb_qhkh', []);
    }

    //List Reports of Sell
    public function actionReport_index($year = '')
    {
        if ($year == '') {
            $year = intval(date('Y', strtotime(NOW)));
        }
        $indexList1 = [
            'c_total'=>Yii::t('re', 'Số hồ sơ mới được giao xử lí trong tháng'),
            'b_total'=>Yii::t('re', 'Số tours bán thêm được trong tháng'),
            'day_total'=>Yii::t('re', 'Số ngày tours bán thêm'),
            'pax_total'=>Yii::t('re', 'Số pax bán thêm'),
            'dt_total'=>Yii::t('re', 'Tổng doanh thu bán thêm trong tháng'),
            'cp_total'=>Yii::t('re', 'Tổng Giá vốn tours bán thêm trong tháng'),
            'lg_total'=>Yii::t('re', 'Tổng lãi gộp tours bán thêm trong tháng'),
            'pc_total'=>Yii::t('re', 'Tỷ lệ lãi gộp (đối với các tours bán thêm)'),
        ];
        $indexList2 = [
            'c_end_dt_total'=>Yii::t('re', 'Số hồ sơ theo tháng thực hiện'),
            'c_lost_total'=>Yii::t('re', 'Số hồ sơ đã đóng'),
            'c_won_total'=>Yii::t('re', 'Số tours (hồ sơ) bán được'),
            'c_won_pc_total'=>Yii::t('re', 'Tỷ lệ thành công đã đạt được'),
            'c_pax_total'=>Yii::t('re', 'Tổng số lượng khách'),
            'c_day_total'=>Yii::t('re', 'Tổng số ngày tours'),
            'c_dt_total'=>Yii::t('re', 'Tổng doanh số'),
            'c_cp_total'=>Yii::t('re', 'Tổng giá vốn'),
            'c_laigop_total'=>Yii::t('re', 'Tổng Lãi Gộp'),
            'c_ltdt_total'=>Yii::t('re', 'Tổng Lãi Gộp/Doanh Thu'),
            'c_lgcp_total'=>Yii::t('re', 'Tổng Lãi Gộp/Giá vốn'),
        ];

        $links_howfound = [
            //khack cu
            'returning' => 'https://my.amicatravel.com/reports/b2c-conversion-rate?view=created&how_found=returning&owner_id='.USER_ID.'&year='.$year,
            'returning_tourend' => 'https://my.amicatravel.com/reports/b2c-conversion-rate?view=tourend&how_found=returning&owner_id='.USER_ID.'&year='.$year,
            //khack moi
            'new' => 'https://my.amicatravel.com/reports/b2c-conversion-rate?view=created&how_found=new&owner_id='.USER_ID.'&year='.$year,
            'new_tourend' => 'https://my.amicatravel.com/reports/b2c-conversion-rate?view=tourend&how_found=new&owner_id='.USER_ID.'&year='.$year,
            //khack gioi thieu
            'referred' => 'https://my.amicatravel.com/reports/b2c-conversion-rate?view=created&how_found=referred&owner_id='.USER_ID.'&year='.$year,
            'referred_tourend' => 'https://my.amicatravel.com/reports/b2c-conversion-rate?view=tourend&how_found=referred&owner_id='.USER_ID.'&year='.$year,
        ];
        $links_howcontacted = [
            'web/adwords/google' => 'link&year='.$year,
            'web/adwords/google_tourend' => 'link&year='.$year,

            'web/adwords/bing' => 'link&year='.$year,
            'web/adwords/bing_tourend' => 'link&year='.$year,

            'web/adwords/other' => 'link&year='.$year,
            'web/adwords/other_tourend' => 'link&year='.$year,

            'web/search/google' => 'link&year='.$year,
            'web/search/google_tourend' => 'link&year='.$year,

            'web/search/bing' => 'link&year='.$year,
            'web/search/bing_tourend' => 'link&year='.$year,

            'web/search/yahoo' => 'link&year='.$year,
            'web/search/yahoo_tourend' => 'link&year='.$year,

            'web/search/other' => 'link&year='.$year,
            'web/search/other_tourend' => 'link&year='.$year,


            'web/link' => 'link&year='.$year,////////
            'web/link_tourend' => 'link&year='.$year,

            'web/link/360' => 'link&year='.$year,
            'web/link/360_tourend' => 'link&year='.$year,

            'web/link/facebook' => 'link&year='.$year,
            'web/link/facebook_tourend' => 'link&year='.$year,

            'web/link/other' => 'link&year='.$year,
            'web/link/other_tourend' => 'link&year='.$year,

            'web/adonline' => 'link&year='.$year,
            'web/adonline_tourend' => 'link&year='.$year,

            'web/adonline/facebook' => 'link&year='.$year,
            'web/adonline/facebook_tourend' => 'link&year='.$year,

            'web/adonline/voyageforum' => 'link&year='.$year,
            'web/adonline/voyageforum_tourend' => 'link&year='.$year,

            'web/adonline/routard' => 'link&year='.$year,
            'web/adonline/routard_tourend' => 'link&year='.$year,

            'web/adonline/sitevietnam' => 'link&year='.$year,
            'web/adonline/sitevietnam_tourend' => 'link&year='.$year,

            'web/adonline/other' => 'link&year='.$year,
            'web/adonline/other_tourend' => 'link&year='.$year,

            'web/email' => 'link&year='.$year,
            'web/email_tourend' => 'link&year='.$year,

            'web/direct' => 'link&year='.$year,
            'web/direct_tourend' => 'link&year='.$year,

            'nweb' => 'link&year='.$year,
            'nweb_tourend' => 'link&year='.$year,

            'nweb/email' => 'link&year='.$year,
            'nweb/email_tourend' => 'link&year='.$year,

            'nweb/email/tripconn' => 'link&year='.$year,
            'nweb/email/tripconn_tourend' => 'link&year='.$year,

            'nweb/email/other' => 'link&year='.$year,
            'nweb/email/other_tourend' => 'link&year='.$year,

            'nweb/walk-in' => 'link&year='.$year,
            'nweb/walk-in_tourend' => 'link&year='.$year,

            'nweb/other' => 'link&year='.$year,
            'nweb/other_tourend' => 'link&year='.$year,

            'agent' => 'link&year='.$year,
            'agent_tourend' => 'link&year='.$year,

        ];
        $links_prospect = [
            '1' => 'link&year='.$year,
            '1_tourend' => 'link&year='.$year,

            '2' => 'link&year='.$year,
            '2_tourend' => 'link&year='.$year,

            '3' => 'link&year='.$year,
            '3_tourend' => 'link&year='.$year,

            '4' => 'link&year='.$year,
            '4_tourend' => 'link&year='.$year,

            '5' => 'link&year='.$year,
            '5_tourend' => 'link&year='.$year,
        ];
        $links_day_count = [
            '1-4' => 'link&year='.$year,
            '1-4_tourend' => 'link&year='.$year,

            '5-10' => 'link&year='.$year,
            '5-10_tourend' => 'link&year='.$year,

            '11-15' => 'link&year='.$year,
            '11-15_tourend' => 'link&year='.$year,

            '16-20' => 'link&year='.$year,
            '16-20_tourend' => 'link&year='.$year,

            '21-100' => 'link&year='.$year,
            '21-100_tourend' => 'link&year='.$year,
        ];
        $links_pax_count = [
            '1' => 'link&year='.$year,
            '1_tourend' => 'link&year='.$year,

            '2' => 'link&year='.$year,
            '2_tourend' => 'link&year='.$year,

            '3' => 'link&year='.$year,
            '3_tourend' => 'link&year='.$year,

            '4-5' => 'link&year='.$year,
            '4-5_tourend' => 'link&year='.$year,

            '6-8' => 'link&year='.$year,
            '6-8_tourend' => 'link&year='.$year,

            '9-50' => 'link&year='.$year,
            '9-50_tourend' => 'link&year='.$year,
        ];
        $links_req_travel = [
            'Family' => 'link&year='.$year,
            'Family_tourend' => 'link&year='.$year,

            'Couple' => 'link&year='.$year,
            'Couple_tourend' => 'link&year='.$year,

            'Friends' => 'link&year='.$year,
            'Friends_tourend' => 'link&year='.$year,

            'Group' => 'link&year='.$year,
            'Group_tourend' => 'link&year='.$year,

            'Business' => 'link&year='.$year,
            'Business_tourend' => 'link&year='.$year,

            'Other' => 'link&year='.$year,
            'Other_tourend' => 'link&year='.$year,
        ];
        $link_destinations = [
            'vn' => 'link&year='.$year,
            'vn_tourend' => 'link&year='.$year,
            'la' => 'link&year='.$year,
            'la_tourend' => 'link&year='.$year,
            'kh' => 'link&year='.$year,
            'kh_tourend' => 'link&year='.$year,
            'vn|kh' => 'link&year='.$year,
            'vn|kh_tourend' => 'link&year='.$year,
            'la|kh' => 'link&year='.$year,
            'la|kh_tourend' => 'link&year='.$year,
            'vn|la' => 'link&year='.$year,
            'vn|la_tourend' => 'link&year='.$year,
            'th|la' => 'link&year='.$year,
            'th|la_tourend' => 'link&year='.$year,
            'th|kh' => 'link&year='.$year,
            'th|kh_tourend' => 'link&year='.$year,
            'vn|kh|mm' => 'link&year='.$year,
            'vn|kh|mm_tourend' => 'link&year='.$year,
            'vn|la|kh' => 'link&year='.$year,
            'vn|la|kh_tourend' => 'link&year='.$year,
            'vn|mm ' => 'link&year='.$year,
            'vn|mm_tourend' => 'link&year='.$year,
            'kh|mm' => 'link&year='.$year,
            'kh|mm_tourend' => 'link&year='.$year,
            'la|mm' => 'link&year='.$year,
            'la|mm_tourend' => 'link&year='.$year,
            'vn|la|mm' => 'link&year='.$year,
            'vn|la|mm_tourend' => 'link&year='.$year,
            'la|kh|mm' => 'link&year='.$year,
            'la|kh|mm_tourend' => 'link&year='.$year,
            'mm' => 'link&year='.$year,
            'mm_tourend' => 'link&year='.$year,
            'th' => 'link&year='.$year,
            'th_tourend' => 'link&year='.$year,
        ];
        $link_france_ids = [
            '13' => 'link&year='.$year,
            '13_tourend' => 'link&year='.$year,
            '5246' => 'link&year='.$year,
            '5246_tourend' => 'link&year='.$year,
        ];

        $totalCases = [];
        $totalCasesEndDate = [];
        $totalCasesLostEndDate = [];
        $totalCasesWonEndDate = [];
        $totalCasesWonPc = [];
        $cases_ao = Kase::find()
                ->with([
                    'owner'=>function($query) {
                        return $query->select(['id', 'name']);
                    },
                ])
                ->where('is_b2b = "no" AND owner_id=4829')//USER_ID/4829
                ->andWhere(['YEAR(ao)' => $year])
                ->innerJoinWith('stats')->asArray()->all();
        if (!$cases_ao) {
            // throw new HttpException(403,"Not found any case ao");
        }
        $cases_t_end = Kase::find()->select(['*'])
                ->with([
                    'bookings',
                    'bookings.product',
                    'bookings.payments',
                    'bookings.report',
                ])
                ->where(['is_b2b' => "no", 'YEAR(tour_end_date)' => $year, 'owner_id' => 4829])//USER_ID/4829
                ->innerJoinWith('stats')->asArray()->all();
        if (!$cases_t_end) {
            // throw new HttpException(403,"Not found any case end date");
        }

        $totalDoanhThu_c = [];
        $totalChiPhi_c = [];
        $totalLaiGop_c = [];
        $totalPc_c_dt = [];
        $totalPc_c_cp = [];
        $totalDay_c = [];
        $totalPax_c = [];


        //count table 3->10
        $totalCases_howFound = [];
        $totalCases_howcontacted = [];
        $totalCases_prospect = [];
        $totalCases_dayCount = [];
        $totalCases_paxCount = [];
        $totalCases_reqTravelType = [];
        $totalCases_end_date = [];
        $totalCases_destination = [];
        $totalCases_cofr_france = [];

        //total count
        $totalCases_statusInMonth = [];

        $rates = 1.14;


        $cofr_ids = [];
        foreach ($cases_ao as $case) {
            $m = intVal(date('m',strtotime($case['ao'])));
            $totalCases[$year][$m][] = $case['id'];
            // how found
            $category_howfound = $case['how_found'];
            if (strpos($category_howfound, 'referred') !== false ) {
                $category_howfound = 'referred';
            }
            $totalCases_howFound[$year][$m][$category_howfound][] = $case['id'];
            $k_stype = 'how_found:'.$category_howfound;
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            //how contacted
            $category_howcontacted = $case['how_contacted'];
            $totalCases_howcontacted[$year][$m][$category_howcontacted][] = $case['id'];
            $k_stype = 'how_contacted:'.$category_howcontacted;
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            //how contacted
            $category_prospect = $case['stats']['prospect'];
            $totalCases_prospect[$year][$m][$category_prospect][] = $case['id'];
            $k_stype = 'prospect:'.$category_prospect;
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            //days tour
            if (isset($case['stats']['day_count']) && $case['stats']['day_count'] != '') {
                foreach ($links_day_count as $key => $value) {
                    if (strpos($key, '_') !== false) {
                        continue;
                    }
                    $day = explode('-', $key);
                    if ($day[0] <= $case['stats']['day_count_max'] && $day[1] >= $case['stats']['day_count_min']) {
                        $totalCases_dayCount[$year][$m][$key][] = $case['id'];
                        $k_stype = 'day_count:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }
            }
            //pax tour
            if (isset($case['stats']['pax_count']) && $case['stats']['pax_count'] != '') {
                foreach ($links_pax_count as $key => $value) {
                    if (strpos($key, '_') !== false) {
                        continue;
                    }
                    $pax = explode('-', $key);
                    if (count($pax) == 1) {
                        if ($pax[0] <= $case['stats']['pax_count_max'] && $pax[0] >= $case['stats']['pax_count_min']) {
                           $totalCases_paxCount[$year][$m][$key][] = $case['id'].' / '.$case['stats']['pax_count'];
                           $k_stype = 'pax_count:'.$key;
                            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                        }
                    } else {
                        if ($pax[0] <= $case['stats']['pax_count_max'] && $pax[1] >= $case['stats']['pax_count_min']) {
                           $totalCases_paxCount[$year][$m][$key][] = $case['id'].' / '.$case['stats']['pax_count'];
                           $k_stype = 'pax_count:'.$key;
                            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                        }
                    }
                }
            }
            //request travel type
            if (isset($case['stats']['req_travel_type'])) {//
                $req_trave = $case['stats']['req_travel_type'];
                if ($req_trave == '') {
                    $req_trave = 'Other';
                }
                foreach ($links_req_travel as $key => $value) {
                    if (strpos($key, '_') !== false) {
                        continue;
                    }
                    if (strpos($req_trave, $key) !== false) {
                        $totalCases_reqTravelType[$year][$m][$key][] = $case['id'];
                        $k_stype = 'req_travel_type:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }
            }
            //theo điểm đến
            if (isset($case['stats']['req_countries'])) {//
                $req_countries_str = strtolower(str_replace([' ', ','], ['','|'],$case['stats']['req_countries']));

                foreach ($link_destinations as $key => $value) {
                    if (strpos($key, '_') !== false) {
                        continue;
                    }
                    $arr_des = explode('|', strtolower($key));
                    $req_countries_arr = array_unique(explode('|', $req_countries_str));
                    $valid = (count( $arr_des ) == count( $req_countries_arr ) && !array_diff( $arr_des , $req_countries_arr ) ? true : false);
                    if ($valid) {
                        $totalCases_destination[$year][$m][$key][] = $case['id'];
                        $k_stype = 'req_countries:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }
            }
            // theo nguoi tư vấn
            if ($case['cofr'] > 0) {
                foreach ($link_france_ids as $key => $value) {
                    if (strpos($key, '_') !== false) {
                        continue;
                    }
                    if ($key == $case['cofr']) {
                        $totalCases_cofr_france[$year][$m][$key][] = $case['id'];
                        $k_stype = 'cofr:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                        // get owner info
                        $cofr_ids[] = $case['cofr'];
                    }
                }

            }

            //theo thang ket thuc
            if (date('Y',strtotime($case['stats']['tour_end_date'])) == $year) {
                $month_end_dt = intVal(date('m',strtotime($case['stats']['tour_end_date'])));
                $totalCases_end_date[$year][$m][$month_end_dt][] = $case['id'];
                $k_stype = 'tour_end_date:'.$month_end_dt;
                $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            }


        }
        //case end date
        foreach ($cases_t_end as $case) {
            $y = $year;
            $m = intVal(date('m',strtotime($case['tour_end_date'])));
            $totalCasesEndDate[$y][$m][] = $case['id'];
            $k_stype = 'total_tour_end_date:';
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            // how found tour end
            $category_howfound = $case['how_found'];
            if (strpos($category_howfound, 'referred') !== false ) {
                $category_howfound = 'referred';
            }
            $totalCases_howFound[$y][$m][$category_howfound . '_tourend'][] = $case['id'];
            $k_stype = 'how_found:'.$category_howfound . '_tourend';
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            //how contacted tour end
            $category_howcontacted = $case['how_contacted'];
            $totalCases_howcontacted[$year][$m][$category_howcontacted . '_tourend'][] = $case['id'];
            $k_stype = 'how_contacted:'.$category_howcontacted . '_tourend';
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
            //prospect tour end
            $category_prospect = $case['stats']['prospect'];
            $totalCases_prospect[$year][$m][$category_prospect . '_tourend'][] = $case['id'];
            $k_stype = 'prospect:'.$category_prospect . '_tourend';
            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];


            //days tour
            if (isset($case['stats']['day_count']) && $case['stats']['day_count'] != '') {
                foreach ($links_day_count as $key => $value) {
                    if (strpos($key, '_') === false) {
                        continue;
                    }
                    $r_key = str_replace('_tourend', '', $key);
                    $day = explode('-', $r_key);
                    if ($day[0] <= $case['stats']['day_count_max'] && $day[1] >= $case['stats']['day_count_min']) {
                       $totalCases_dayCount[$year][$m][$key][] = $case['id'];
                       $k_stype = 'day_count:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }
            }

            //pax tour
            if (isset($case['stats']['pax_count']) && $case['stats']['pax_count'] != '') {
                foreach ($links_pax_count as $key => $value) {
                    if (strpos($key, '_') === false) {
                        continue;
                    }
                    $r_key = str_replace('_tourend', '', $key);
                    $pax = explode('-', $r_key);
                    if (count($pax) == 1) {
                        if ($pax[0] <= $case['stats']['pax_count_max'] && $pax[0] >= $case['stats']['pax_count_min']) {
                           $totalCases_paxCount[$year][$m][$key][] = $case['id'].' / '.$case['stats']['pax_count'];
                           $k_stype = 'pax_count:'.$key;
                            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                        }
                    } else {
                        if ($pax[0] <= $case['stats']['pax_count_max'] && $pax[1] >= $case['stats']['pax_count_min']) {
                            $totalCases_paxCount[$year][$m][$key][] = $case['id'].' / '.$case['stats']['pax_count'];
                            $k_stype = 'pax_count:'.$key;
                            $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                        }
                    }
                }
            }

            //request travel type
            if (isset($case['stats']['req_travel_type'])) {//
                $req_trave = $case['stats']['req_travel_type'];
                if ($req_trave == '') {
                    $req_trave = 'Other';
                }
                foreach ($links_req_travel as $key => $value) {
                    if (strpos($key, '_') === false) {
                        continue;
                    }
                    $r_key = str_replace('_tourend', '', $key);
                    if (strpos($req_trave, $r_key) !== false) {
                        $totalCases_reqTravelType[$year][$m][$key][] = $case['id'];
                        $k_stype = 'req_travel_type:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }
            }

            //theo điểm đến
            if (isset($case['stats']['req_countries'])) {
                $req_countries_str = strtolower(str_replace([' ', ','], ['','|'],$case['stats']['req_countries']));

                foreach ($link_destinations as $key => $value) {
                    if (strpos($key, '_') === false) {
                        continue;
                    }
                    $r_key = str_replace('_tourend', '', $key);
                    $arr_des = explode('|', strtolower($r_key));
                    $req_countries_arr = array_unique(explode('|', $req_countries_str));
                    $valid = (count( $arr_des ) == count( $req_countries_arr ) && !array_diff( $arr_des , $req_countries_arr ) ? true : false);
                    if ($valid) {
                        $totalCases_destination[$year][$m][$key][] = $case['id'];
                        $k_stype = 'req_countries:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }
            }

            // theo nguoi tư vấn
            if ($case['cofr'] > 0) {
                foreach ($link_france_ids as $key => $value) {
                    if (strpos($key, '_') === false) {
                        continue;
                    }
                    $r_key = str_replace('_tourend', '', $key);
                    if ($r_key == $case['cofr']) {
                        $totalCases_cofr_france[$year][$m][$key][] = $case['id'];
                        $k_stype = 'cofr:'.$key;
                        $totalCases_statusInMonth[$year][$m][$k_stype][$case['deal_status']][] = $case['id'];
                    }
                }

            }


            if ($case['deal_status'] == 'lost') {
                $totalCasesLostEndDate[$y][$m][] = $case['id'];
            }
            if ($case['deal_status'] == 'won') {
                $totalCasesWonEndDate[$y][$m][] = $case['id'];
                if ($case['bookings'] ) {
                    foreach ($case['bookings'] as $booking) {
                        if ($booking['status'] == 'won' && $booking['payments']) {
                            foreach ($booking['payments'] as $payment) {
                                if (intval($payment['amount']) > 0) {
                                    $totalCasesWonPc[$y][$m][] = $case['id'];
                                    break;
                                }
                            }
                        }
                    }
                    foreach ($case['bookings'] as $booking) {
                        if ($booking['report']) {
                            $ngay = $booking['report']['day_count'];
                            $pax = $booking['report']['pax_count'];
                            if ($booking['report']['price_unit'] == 'USD') {
                                $doanhThu = $booking['report']['price'] / $rates;
                                $chiPhi = $booking['report']['cost'] / $rates;
                            } else {
                                $doanhThu = $booking['report']['price'];
                                $chiPhi = $booking['report']['cost'];
                            }
                            $totalDoanhThu_c[$y][$m][] = $doanhThu;
                            $totalChiPhi_c[$y][$m][] = $chiPhi;
                            $totalLaiGop_c[$y][$m][] = $laiGop = $doanhThu - $chiPhi;
                            $totalPc_c_dt[$y][$m][] = $doanhThu == 0 ? 0 : 100 * $laiGop / $doanhThu;
                            $totalPc_c_cp[$y][$m][] = $doanhThu == 0 ? 0 : 100 * $laiGop / $chiPhi;
                            $totalDay_c[$y][$m][] = $ngay;
                            $totalPax_c[$y][$m][] = $pax;
                        }
                    }
                }
            }
        }
        $bookings = Booking::find()
            ->select([
                'at_bookings.created_by', 'at_bookings.updated_by',
                'at_bookings.id', 'at_bookings.pax', 'at_bookings.currency', 'at_bookings.status_dt', 'at_bookings.case_id', 'at_bookings.product_id', 'at_bookings.updated_by', 'at_bookings.note',
                'start_date'=>'at_ct.day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->innerJoinWith(['product'])
            ->innerJoinWith(['case'])
            ->andWhere([
                'at_bookings.status'=>'won',
                'at_ct.op_status'=>'op',
                'YEAR(at_bookings.status_dt)'=> $year,
                ])->with([
                'report',
                'product',
                'case'=>function($query) {
                    return $query->select(['id', 'name', 'owner_id', 'is_b2b']);
                },
                'case.owner'=>function($query) {
                    return $query->select(['id', 'name']);
                }
                ])
                ->andWhere(['at_cases.owner_id' => 4829])
                ->asArray() ->all();
        if (!$bookings) {
            // throw new HttpException(403,"Not found any booking");
        }
        $totalBookings = [];
        $totalDoanhThu = [];
        $totalChiPhi = [];
        $totalLaiGop = [];
        $totalPc = [];
        $totalDay = [];
        $totalPax = [];

        foreach ($bookings as $booking) {
            $y = intVal($year);
            $m = intVal(date('m',strtotime($booking['status_dt'])));
            $totalBookings[$y][$m][]  = $booking['id'];
            if ($booking['report'] && $booking['product']['op_status'] != 'canceled') {
                $ngay = $booking['report']['day_count'];
                $pax = $booking['report']['pax_count'];
                if ($booking['report']['price_unit'] == 'USD') {
                    $doanhThu = $booking['report']['price'] / $rates;
                    $chiPhi = $booking['report']['cost'] / $rates;
                } else {
                    $doanhThu = $booking['report']['price'];
                    $chiPhi = $booking['report']['cost'];
                }
                $totalDoanhThu[$y][$m][] = $doanhThu;
                $totalChiPhi[$y][$m][] = $chiPhi;
                $totalLaiGop[$y][$m][] = $laiGop = $doanhThu - $chiPhi;
                $totalPc[$y][$m][] = $doanhThu == 0 ? 0 : 100 * $laiGop / $doanhThu;
                $totalDay[$y][$m][] = $ngay;
                $totalPax[$y][$m][] = $pax;
            }
        }
        $cntInMonth = [];
        for ($mo = 1; $mo <= 12 ; $mo++) {
            //count case
            $total_cnt_case = (isset($totalCases[$year][$mo]))? $totalCases[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_total'] = count($total_cnt_case);
            //count booking
            $total_cnt_booking = (isset($totalBookings[$year][$mo]))? $totalBookings[$year][$mo]: [];
            $cntInMonth[$year][$mo]['b_total'] = count($total_cnt_booking);
            // doanh thu
            $total_dt = (isset($totalDoanhThu[$year][$mo]))? $totalDoanhThu[$year][$mo]: [];
            $cntInMonth[$year][$mo]['dt_total'] = number_format(array_sum($total_dt), 0) . ' <span class="text-muted">EUR</span>';
            // chi phi
            $total_cp = (isset($totalChiPhi[$year][$mo]))? $totalChiPhi[$year][$mo]: [];
            $cntInMonth[$year][$mo]['cp_total'] = number_format(array_sum($total_cp), 0). ' <span class="text-muted">EUR</span>';;
            //lai gop
            $total_lg = (isset($totalLaiGop[$year][$mo]))? $totalLaiGop[$year][$mo]: [];
            $cntInMonth[$year][$mo]['lg_total'] = number_format(array_sum($total_lg), 0). ' <span class="text-muted">EUR</span>';;

            // ty le lai gop
            $total_pc = (isset($totalPc[$year][$mo]))? $totalPc[$year][$mo]: [];
            $cntInMonth[$year][$mo]['pc_total'] = number_format(array_sum($total_pc), 0);
            // tong so ngay
            $total_day = (isset($totalDay[$year][$mo]))? $totalDay[$year][$mo]: [];
            $cntInMonth[$year][$mo]['day_total'] = array_sum($total_day);
            // tong to pax
            $total_pax = (isset($totalPax[$year][$mo]))? $totalPax[$year][$mo]: [];
            $cntInMonth[$year][$mo]['pax_total'] = array_sum($total_pax);




            // tong ho so theo thang ket thuc
            $total_case_end_dt = (isset($totalCasesEndDate[$year][$mo]))? $totalCasesEndDate[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_end_dt_total'] = count($total_case_end_dt);
            // Ho so lost
            $total_c_lost = (isset($totalCasesLostEndDate[$year][$mo]))? $totalCasesLostEndDate[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_lost_total'] = count($total_c_lost);
            // Ho so won
            $total_c_won = (isset($totalCasesWonEndDate[$year][$mo]))? $totalCasesWonEndDate[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_won_total'] = count($total_c_won);
            // ty le ho so won
            $total_c_won_pc = (isset($totalCasesWonPc[$year][$mo]))? $totalCasesWonPc[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_won_pc_total'] = count($total_c_won_pc);
            //paxs cases won
            $total_c_pax = (isset($totalPax_c[$year][$mo]))? $totalPax_c[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_pax_total'] = array_sum($total_c_pax);
            // days cases won
            $total_c_day = (isset($totalDay_c[$year][$mo]))? $totalDay_c[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_day_total'] = array_sum($total_c_day);
            // doanh thu case won
            $total_c_dt = (isset($totalDoanhThu_c[$year][$mo]))? $totalDoanhThu_c[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_dt_total'] = number_format(array_sum($total_c_dt), 0). ' <span class="text-muted">EUR</span>';
            // chi phi case won
            $total_c_cp = (isset($totalChiPhi_c[$year][$mo]))? $totalChiPhi_c[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_cp_total'] = number_format(array_sum($total_c_cp), 0). ' <span class="text-muted">EUR</span>';
            // lai gop case won
            $total_c_laiGop = (isset($totalLaiGop_c[$year][$mo]))? $totalLaiGop_c[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_laigop_total'] = number_format(array_sum($total_c_laiGop), 0). ' <span class="text-muted">EUR</span>';
            // lai gop case won
            $total_c_laiGop_dt = (isset($totalPc_c_dt[$year][$mo]))? $totalPc_c_dt[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_ltdt_total'] = number_format(array_sum($total_c_laiGop_dt), 0);
            // lai gop case won
            $total_c_laiGop_cp = (isset($totalPc_c_cp[$year][$mo]))? $totalPc_c_cp[$year][$mo]: [];
            $cntInMonth[$year][$mo]['c_lgcp_total'] = number_format(array_sum($total_c_laiGop_cp), 0);


            // phan loai khach
            foreach ($links_howfound as $key => $name) {
                $cntInMonth[$year][$mo][$key] = 0;
                if (!isset($totalCases_howFound[$year][$mo][$key])) {
                     $totalCases_howFound[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo][$key] = count($totalCases_howFound[$year][$mo][$key]);
            }
            // phan loai nguon liên hệ
            foreach ($links_howcontacted as $key => $name) {
                $cntInMonth[$year][$mo][$key] = 0;
                if (!isset($totalCases_howcontacted[$year][$mo][$key])) {
                     $totalCases_howcontacted[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo][$key] = count($totalCases_howcontacted[$year][$mo][$key]);
            }
            // phan loai prospect
            foreach ($links_prospect as $key => $name) {
                $cntInMonth[$year][$mo]['prospect_' . $key] = 0;
                if (!isset($totalCases_prospect[$year][$mo][$key])) {
                     $totalCases_prospect[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo]['prospect_' . $key] = count($totalCases_prospect[$year][$mo][$key]);
            }
            // phan loai day tour
            foreach ($links_day_count as $key => $name) {
                $cntInMonth[$year][$mo][$key] = 0;
                if (!isset($totalCases_dayCount[$year][$mo][$key])) {
                     $totalCases_dayCount[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo][$key] = count($totalCases_dayCount[$year][$mo][$key]);
            }
            // phan loai pax tour
            foreach ($links_pax_count as $key => $name) {
                $cntInMonth[$year][$mo]['pax_' . $key] = 0;
                if (!isset($totalCases_paxCount[$year][$mo][$key])) {
                     $totalCases_paxCount[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo]['pax_' . $key] = count($totalCases_paxCount[$year][$mo][$key]);
            }
            // phan loai khach
            foreach ($links_req_travel as $key => $name) {
                $cntInMonth[$year][$mo][$key] = 0;
                if (!isset($totalCases_reqTravelType[$year][$mo][$key])) {
                     $totalCases_reqTravelType[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo][$key] = count($totalCases_reqTravelType[$year][$mo][$key]);
            }
            // phan loai theo tháng kêt thúc
           for ($m_end = 1; $m_end <= 12 ; $m_end++) {
                $cntInMonth[$year][$mo]['tour_end_mo_' . $m_end] = 0;
                if (!isset($totalCases_end_date[$year][$mo][$m_end])) {
                     $totalCases_end_date[$year][$mo][$m_end] = [];
                }
                $cntInMonth[$year][$mo]['tour_end_mo_' . $m_end] = count($totalCases_end_date[$year][$mo][$m_end]);
            }
            // theo điêm đến
            foreach ($link_destinations as $key => $value) {
                $cntInMonth[$year][$m][$key] = 0;
                if (!isset($totalCases_destination[$year][$mo][$key])) {
                     $totalCases_destination[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo][$key] = count($totalCases_destination[$year][$mo][$key]);
            }
            //theo tư vấn pháp
            //
            foreach ($link_france_ids as $key => $value) {
                $cntInMonth[$year][$m][$key] = 0;
                if (!isset($totalCases_cofr_france[$year][$mo][$key])) {
                     $totalCases_cofr_france[$year][$mo][$key] = [];
                }
                $cntInMonth[$year][$mo][$key] = count($totalCases_cofr_france[$year][$mo][$key]);
            }
        }
        $cofrList = Person::find()->select('id, nickname')->where(['id' => array_unique($cofr_ids)])->indexBy('id')->asArray()->all();
        return $this->render('reports_index', [
            'indexList1' => $indexList1,
            'indexList2' => $indexList2,
            'year'=>$year,
            'result' => $cntInMonth,
            'links_howfound' => $links_howfound,
            'links_howcontacted' => $links_howcontacted,
            'links_prospect' => $links_prospect,
            'links_day_count' => $links_day_count,
            'links_pax_count' => $links_pax_count,
            'links_req_travel' => $links_req_travel,
            'link_destinations' => $link_destinations,
            'link_france_ids' => $link_france_ids,
            'totalCases_statusInMonth' => $totalCases_statusInMonth,
            'cofrList' => $cofrList
        ]);
    }



    public function actionOver_view($id = 0)
    {
        $theVenue = Venue::findOne($id);
        if (isset($_POST) && $_POST) {
            $over_view_options = [];
            foreach ($_POST as $key => $value) {
                if ($key == 'btn_save') continue;
                if ($key == 'price_range') $value = '|' . $value . '|';
                $over_view_options[$key] = $value;
            }
            $theVenue->scenario = 'venue_u_ovv';
            $theVenue->updated_at = NOW;
            $theVenue->updated_by = USER_ID;
            $theVenue->over_view_options = serialize($over_view_options);
            $theVenue->save();
        }
        $over_view_options = unserialize($theVenue->over_view_options);
        if (isset($over_view_options['price_range']) && $over_view_options['price_range'] != '') {
            $over_view_options['price_range'] = trim($over_view_options['price_range'], '|');
        }
        return $this->render('over_view', [
            'over_view_options' => $over_view_options
        ]);
    }



    // thông tin thưởng DH
    public function actionDh($year = '', $month = '', $operator = '')
    {
        //init date

        for ($y = 2016; $y <= date('Y') + 1; $y ++) {
            $yearList[$y] = $y;
        }
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }
        if ($month != '' && !in_array($month, $monthList)) {
            $month = date('n');
        }
        $param = [':year' => $year];
        $sql = 'SELECT tu.*, t.code, ts.start_date, ts.pax_count,  p.nickname, tu.user_id
                FROM at_tour_user tu INNER JOIN at_tours t ON tu.tour_id = t.id
                                     INNER JOIN at_tour_stats ts ON t.id = ts.tour_old_id
                                     INNER JOIN persons p ON tu.user_id = p.id
                WHERE role ="operator" AND YEAR(ts.start_date) = :year
                ';
        if ($month != '') {
            $sql .= ' AND MONTH(ts.start_date) = :month';
            $param[':month'] = $month;
        }
        $sql .= ' ORDER BY tu.user_id';

        $tours = Yii::$app->db->createCommand($sql, $param)->queryAll();
        // var_dump($tours);die;
        return $this->render('dh', [
            'year' => $year,
            'month' => $month,
            'operator' => $operator,
            'yearList' => $yearList,
            'monthList' => $monthList,
            'tours' => $tours

        ]);
    }

    //feedback cho cskh
    public function actionFeedback_online($id = 0, $action = 'add', $feedback = 0)
    {
        // test
        $versions['20072017'] = [
            'questions' => [
                    'q1' => [
                        'title' => 'Globalement, comment évaluez-vous les prestations suivantes?',
                        'options' => ['Hôtels', 'Chez l’habitant', 'Repas', 'Bateaux', 'Train', 'Véhicule'],
                        'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant', 'Non Utilisé'],
                        'note_q' => '',
                        'v_op_v' => [
                            [5, 10, 20, 30, 40, 40 ],
                            [5, 10, 20, 30, 40, 40 ],
                            [5, 10, 15, 20, 25, 25 ],
                            [5, 10, 15, 20, 25, 25 ],
                            [5, 10, 15, 20, 25, 25 ],
                            [5, 10, 15, 20, 25, 25 ],
                        ]
                    ],
                    'q2' => [
                        'title' => "Quel est votre niveau de satisfaction en rapport avec les compétences du guide",
                        'options' => ['Niveau de français', 'Connaissances', 'Capacité d’organisation', 'Serviabilité - Disponibilité - Aimabilité', 'Capacité d’assurer le contact du voyageur avec les habitants et la vie locale'],
                        'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                        'note_q' => "Est-ce que vous avez des commentaires particuliers concernant les prestations des guides?",
                        'v_op_v' => [],
                    ],
                    'q3' => [
                        'title' => "Quel est votre niveau de satisfaction en rapport avec les prestations du chauffeur",
                        'options' => ['Professionnalisme dans la conduite', 'Serviabilité', 'Concentration', 'Relationnel (avec le guide et les voyageurs)', 'Propreté du véhicule'],
                        'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                        'note_q' => "Est-ce que vous avez des commentaires particuliers concernant les prestations des chauffeurs?",
                        'v_op_v' => [],
                    ],
                    'q4' => [
                        'title' => 'Comment évaluez-vous les prestations et les services',
                        'options' => ['Rapport Qualité/Prix', 'Qualité de nos services', 'Originalité de nos services', 'Suivi général lors du voyage', 'Niveau de francais de vos conseillères (Conseiller(ère) de vente et Conseiller(ère) client)', 'Résolution des problèmes', 'Personnalisation des services'],
                        'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                        'note_q' => "Est-ce que vous avez des commentaires particuliers concernant nos prestations et services?",
                        'v_op_v' => [
                            [10, 20, 40, 60, 80 ],
                            [10, 20, 40, 60, 80 ],
                            [20, 40, 60, 80, 100 ],
                            [10, 20, 30, 40, 50 ],
                            [10, 20, 40, 60, 80],
                            [15, 30, 45, 60, 75],
                            [10, 20, 30, 40, 50]
                        ],
                    ],
            ],
        ];
        $theTour = Product::find()
        ->select(['id', 'op_code', 'op_name', 'day_from'])
        ->where(['id'=>$id, 'op_status'=>'op'])
        ->asArray()
        ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
        ->where(['ct_id'=>$id, 'status'=>'on'])
        ->asArray()
        ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }
        $ver = '20072017';
        if (isset($_POST['save'])) {
            $content = '<table style="clear:both; margin:0;">
                <tr>
                    <td width="275">Tour code: <strong>'.$theTour['op_code'].'</strong> / ID <strong>'.$theTour['id'].'</strong></td>
                    <td width="">Votre nom et prénom: </strong></td>
                </tr>
            </table>
            <table border="1px solid #cdcdcd"><tbody>';
            foreach ($_POST as $key => $t) {
                if ($key == 'save') {
                    continue;
                }
                $question = $versions['20072017']['questions'][$key];
                $content .= '<tr><td colspan="2" align="center"><strong>Partie '.substr($key, 1).'</strong></td></tr>';
                foreach ($t as $q => $ans) {
                    $ans = (isset($question['options_value'][$ans-1]))?$question['options_value'][$ans-1]: $ans;
                    if ($q == 'note_q') {
                        $q = 'Note';
                    }
                    $content .= '<tr>
                    <td>'.$q.'</td>
                    <td>'.$ans.'</td>
                    </tr>';
                }

            }
            $content .= '</tbody></table>';
            echo $content; die;
            //send mail to Khang Ha
            $args = [
                    ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                    ['to', 'khang.ha@amica-travel.com', 'Nguyên', 'NV'],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
            $this->mgIt(
                'Feedback online',
                '//mg/send_fb',
                [
                    'content'=>$content,
                ],
                $args
            );
            die('email Sended');

        }

        return $this->render('tours_feedback', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'versions' => $versions,
            ]);
    }
    //import excell
	public function actionImportPaxListFromExcel($tour_id = 0, $booking_id = 0)
    {
        // Huan, Duc Anh
        // if (!in_array(USER_ID, [1, 8162])) {
        //     throw new HttpException(403, 'Go away!');
        // }

        $theTour = Product::find()
            ->where(['op_status'=>'op', 'id'=>$tour_id])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Not found.');
        }

        $theBooking = Booking::find()
            ->where(['id'=>$booking_id, 'product_id'=>$theTour['id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404, 'Not found.');
        }

        $results = false;

        if (!empty($_POST['data'])) {
            $data = $_POST['data'];
            $lines = explode(PHP_EOL, $data);
            foreach ($lines as $line) {
                $line = str_replace(chr(9), ']|[', $line);
                $cells = explode(']|[', $line);
                if (isset($cells[10]) && $cells[10] != '') {
                    $results[] = $cells;
                }
            }
        }

        // Ghi csdl
        if (isset($_POST['ok']) && is_array($_POST['ok'])) {
            for ($i = 0; $i < count($_POST['ok']); $i ++) {
                if ($_POST['ok'][$i] == 'OK') {
                    $person = new Person;
                    $person->created_at = NOW;
                    $person->created_by = USER_ID;
                    $person->updated_at = NOW;
                    $person->updated_by = USER_ID;
                    $person->status = 'on';
                    $person->fname = $_POST['fname'][$i];
                    $person->lname = $_POST['lname'][$i];
                    $person->name = $_POST['lname'][$i].' '.$_POST['fname'][$i];
                    $person->gender = $_POST['gender'][$i];
                    $dmy = explode('/', $_POST['dob'][$i]);
                    $person->bday = (int)$dmy[0];
                    $person->bmonth = (int)$dmy[1];
                    $person->byear = (int)$dmy[2];
                    $person->country_code = $_POST['cc'][$i];
                    $person->save(false);

                    Yii::$app->db->createCommand()
                        ->insert('at_search', [
                            'rtype'=>'user',
                            'rid'=>$person->id,
                            'search'=>str_replace('-', '', \fURL::makeFriendly($person->name, '-')),
                            'found'=>trim($person->name),
                            ])
                        ->execute();

                    // Add booking info
                    Yii::$app->db->createCommand()
                        ->insert('at_booking_user', [
                            'created_at'=>NOW,
                            'created_by'=>USER_ID,
                            'updated_at'=>NOW,
                            'updated_by'=>USER_ID,
                            'booking_id'=>$booking_id,
                            'user_id'=>$person->id,
                            ])
                        ->execute();
                    if ($_POST['pp_no'][$i] != '') {
                        // Add passport info if not blank
                        $passport = [
                            $_POST['cc'][$i], //Country
                            $_POST['pp_no'][$i], // Number
                            $_POST['fname'][$i], // Name 1
                            $_POST['lname'][$i], // Name 2
                            $_POST['gender'][$i], // Gender
                            $_POST['dob'][$i], // Date of birth
                            '', // Date of issue
                            $_POST['pp_exp'][$i], // Date of expiry
                            '', // File path
                        ];

                        Yii::$app->db->createCommand()
                            ->insert('metas', [
                                'created_dt'=>NOW,
                                'created_by'=>USER_ID,
                                'updated_dt'=>NOW,
                                'updated_by'=>USER_ID,
                                'rtype'=>'user',
                                'rid'=>$person->id,
                                'name'=>'passport',
                                'value'=>implode(chr(10), $passport),
                                'format'=>'passport',
                                ])
                            ->execute();
                    }
                } // if ok == OK
            }
            Yii::$app->session->setFlash('success', 'Đã ghi vào CSDL.');
            return $this->redirect('/tours/pax/'.$tour_id);
        }

        return $this->render('tool_import-pax-list-from-excel', [
            'theTour'=>$theTour,
            'results'=>$results,
        ]);
    }

	public function actionDoanhThuTour($month = '', $output = '', $usd = 22500, $eur = 24500) {
        if ($month == '') {
            $month = date('Y-m');
        }
        $xrate = [
            'EUR'=>$eur,
            'USD'=>$usd,
        ];
        // Cac tour trong thang
        $sql = 'select op_code, op_name, op_finish, day_from, date_add(day_from, interval day_count-1 day) as day_until, day_count, p.pax, u.name AS seller, IF(company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=company_id LIMIT 1)) AS company from at_bookings b, at_cases k, at_ct p, persons u where k.id=b.case_id AND p.id=b.product_id AND u.id=k.owner_id AND substring(day_from, 1, 7)=:month AND op_status="op" order by day_from, p.id';
        $theTours = Product::find()
            ->select(['id', 'op_code', 'op_name', 'op_finish', 'day_in'=>'day_from', new \yii\db\Expression('date_add(day_from, interval day_count-1 day) as day_out'), 'days'=>'day_count', 'pax'])
            ->with([
                'tour',
                'tourStats'=>function($q) {
                    return $q->select(['tour_id', 'countries']);
                },
                'bookings',
                'bookings.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'bookings.case',
                'bookings.case.stats',

                'bookings.case.company',
                'bookings.case.owner',
                'bookings.invoices'=>function($q) {
                    return $q->orderBy('currency');
                },
                'bookings.payments',
                'bookings.invoices.payments',
                ])
            ->where(['op_status'=>'op'])
            ->andWhere('day_count>=1')
            ->andHaving('substring(day_out, 1, 7)=:month', [':month'=>$month])
            ->orderBy('day_out')
            ->asArray()
            ->all();

        // Check
        if ($output == 'check') {
            if (USER_ID != 1) die('Dang update. Xin doi.');
            foreach ($theTours as $tour) {
                echo '<br><strong>', $tour['op_code'], '</strong>';
                foreach ($tour['bookings'] as $booking) {
                    foreach ($booking['invoices'] as $invoice) {
                        echo '<br> ----- ', $invoice['ref'], ' : ', $invoice['amount'], ' ', $invoice['currency'], ' x ', $invoice['gw_xrate'];
                        foreach ($booking['payments'] as $payment) {
                            echo '<br> ----- ----- ', $payment['ref'], ' : ', $payment['amount'], ' x ', $payment['xrate'];
                            if ($payment['invoice_id'] == 0) {
                                echo ' ----- <span style="color:red">NO-ID</span>';
                            } elseif ($payment['invoice_id'] == $invoice['id']) {
                                echo ' ----- <span style="color:green">ID</span>';
                            } else {
                                echo ' ----- <span style="color:red">ID</span>';
                            }
                            if ($invoice['ref'] == '') {
                                echo ' ----- <span style="color:red">NO-REF</span>';
                            } elseif ($invoice['ref'] != $payment['ref']) {
                                echo ' ----- <span style="color:red">REF</span>';
                            } else {
                                echo ' ----- <span style="color:green">REF</span>';
                                //if ($payment['invoice_id'] == 0 && isset($_GET['update']) && $_GET['update'] == 'yes') {
                                if (isset($_GET['update']) && $_GET['update'] == 'yes') {
                                    $sql = 'UPDATE at_payments SET invoice_id=:i WHERE id=:p LIMIT 1';
                                    Yii::$app->db->createCommand($sql, [':i'=>$invoice['id'], ':p'=>$payment['id']])->execute();
                                }
                            }
                            echo ' (i ', $invoice['id'], ') (p ', $payment['id'], ') (pi ', $payment['invoice_id'], ') <a target="_blank" href="/ketoan/update-payment?payment=', $payment['id'], '&invoice=', $invoice['id'],'">update</a>';
                        }
                    }
                }
            }
            exit;
        }
        // Download
        if ($output == 'download') {
        	$spreadsheet = new Spreadsheet();

            $filename = 'doanh_thu_tour_ket_thuc_'.$month.'_'.date('Ymd-His').'.xlsx';

            // $arr = ['CODE', 'KHOI HANH', 'KET THUC', 'NGAY', 'PAX', 'HOA DON', 'LOAI TIEN', 'DA THU', 'VND', 'BAN HANG', 'QUOC GIA', '+', '-', 'NHO THU', 'KX', 'TX'];
            $spreadsheet->getActiveSheet()
			    ->setCellValue('A1', 'CODE')
			    ->setCellValue('B1', 'KHOI HANH')
			    ->setCellValue('C1', 'KET THUC')
			    ->setCellValue('D1', 'NGAY')
			    ->setCellValue('E1', 'PAX')
			    ->setCellValue('F1', 'HOA DON')
			    ->setCellValue('G1', 'LOAI TIEN')
			    ->setCellValue('H1', 'DA THU')
			    ->setCellValue('I1', 'VND')
			    ->setCellValue('J1', 'BAN HANG')
			    ->setCellValue('K1', 'QUOC GIA')
			    ->setCellValue('L1', '+')
			    ->setCellValue('M1', '-')
			    ->setCellValue('N1', 'NHO THU')
			    ->setCellValue('O1', 'KENH')
			    ->setCellValue('P1', 'NGUON');

            $output = [];
            foreach ($theTours as $tour) {
                if (!isset($output[$tour['op_code']])) {
                    $output[$tour['op_code']]['countries'] = strtoupper($tour['tourStats']['countries']);
                    $output[$tour['op_code']]['day_in'] = $tour['day_in'];
                    $output[$tour['op_code']]['day_out'] = $tour['day_out'];
                    $output[$tour['op_code']]['days'] = $tour['days'];
                    $output[$tour['op_code']]['pax'] = $tour['pax'];
                    $output[$tour['op_code']]['sellers'] = [];
                    $output[$tour['op_code']]['invoice_count'] = 0;
                    $output[$tour['op_code']]['invoices']['+'] = [
                        'nhothu'=>'',
                        'total'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                        'paid'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                    ];
                    $output[$tour['op_code']]['invoices']['-'] = [
                        'nhothu'=>'',
                        'total'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                        'paid'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                    ];
                    $output[$tour['op_code']]['kx'] = [];
                    $output[$tour['op_code']]['tx'] = [];
                }
                foreach ($tour['bookings'] as $booking) {
                    $output[$tour['op_code']]['sellers'][] = $booking['case']['owner']['name'];
                    foreach ($booking['invoices'] as $invoice) {
                        $output[$tour['op_code']]['invoice_count'] ++;
                        if ($invoice['stype'] == 'invoice') {
                            $output[$tour['op_code']]['invoices']['+']['total'][$invoice['currency']] += $invoice['amount'];
                            $output[$tour['op_code']]['invoices']['+']['nhothu'] = $invoice['nho_thu'];
                            foreach ($invoice['payments'] as $payment) {
                                $output[$tour['op_code']]['invoices']['+']['paid']['VND'] += $payment['xrate'] * $payment['amount'];
                            }
                        } else {
                            $output[$tour['op_code']]['invoices']['-']['total'][$invoice['currency']] += $invoice['amount'];
                            $output[$tour['op_code']]['invoices']['+']['nhothu'] = $invoice['nho_thu'];
                            foreach ($invoice['payments'] as $payment) {
                                $output[$tour['op_code']]['invoices']['-']['paid']['VND'] += $payment['xrate'] * $payment['amount'];
                            }
                        }
                    }
                    // Mong yeu cau
                    $case = $booking['case'];
                    // Loai Khach
                    if ($case['how_found'] == 'new' || strpos($case['how_found'], 'new/nref') !== false) {
                    	$output[$tour['op_code']]['tx'][] = 'New';
					}
					if (strpos($case['how_found'], 'new/ref') !== false) {
						$output[$tour['op_code']]['tx'][] = 'Referred';
					}
					if (strpos($case['how_found'], 'returning') !== false) {
						$output[$tour['op_code']]['tx'][] = 'Returning';
					}

					// Kenh tiep can
					if ($case['stats']['kx'] != '') {
						$output[$tour['op_code']]['kx'][] = $case['stats']['kx'];
					} else {
						$kx = 'k8';
						// K1
						if ($case['how_contacted'] == 'web/adwords/google') {
							$kx = 'k1';
						}

						// K2
						if ($case['how_contacted'] == 'web/adwords/bing') {
							$kx = 'k2';
						}

						// K3
						if (strpos($case['how_contacted'], 'web/search') !== false) {
							$kx = 'k3';
						}

						// K4
						if (strpos($case['how_contacted'], 'web/link') !== false || strpos($case['how_contacted'], 'web/adonline') !== false || $case['how_contacted'] == 'web') {
							$kx = 'k4';
						}
						// K5
						if ($case['how_contacted'] == 'web/direct') {
							$kx = 'k5';
						}
						// K6
						if ($case['how_contacted'] == 'web/email') {
							$kx = 'k6';
						}
						// K5
						if (strpos($case['how_contacted'], 'nweb') !== false) {
							$kx = 'k7';
						}
						$output[$tour['op_code']]['kx'][] = $kx;
					}
                }
            }
            // \fCore::expose($output);
            // exit;
            $dataArray = [];
            foreach ($output as $code=>$item) {
                if ($item['invoice_count'] == 0) {
                    $dataArray[] = [
                        $code,
                        $item['day_in'],
                        $item['day_out'],
                        $item['days'],
                        $item['pax'],
                        '',
                        '',
                        '',
                        '',
                        implode(', ', $item['sellers']),
                        $item['countries'],
                        '',
                        '',
                        '',
                        implode(', ', $item['kx']),
                        implode(', ', $item['tx']),
                    ];
                } else {
                    foreach ($item['invoices'] as $pmkey=>$pm) {
                        foreach ($pm['total'] as $curr=>$num) {
                            if ($num != 0) {
                                $paid = 0;
                                foreach ($pm['paid'] as $pcurr=>$pnum) {
                                    if ($pnum != 0 && $pcurr == 'VND') {
                                        $paid += $pnum;
                                    }
                                }
                                $dataArray[] = [
                                    $code,
                                    $item['day_in'],
                                    $item['day_out'],
                                    $item['days'],
                                    $item['pax'],
                                    $num,
                                    $curr,
                                    $paid,
                                    'VND',
                                    implode(', ', $item['sellers']),
                                    $item['countries'],
                                    $pmkey == '+' ? 'invoice' : '',
                                    $pmkey == '-' ? 'refund' : '',
                                    $pm['nhothu'],
                                    implode(', ', $item['kx']),
                        			implode(', ', $item['tx']),
                                ];
                            }
                        }
                    }
                }
            }
            //add array to cells
          	$spreadsheet->getActiveSheet()->fromArray($dataArray, null, 'A2');

          	$spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);

          	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename);

          	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
			exit;
        }
        // exit;
        return $this->render('ketoan_doanh-thu-tour', [
            'theTours'=>$theTours,
            'month'=>$month,
            'usd'=>$usd,
            'eur'=>$eur,
        ]);
    }

    //report
	public function actionReport_market($dt = '')
	{
		$query = Kase::find()
			->with([
				'owner' => function($q){
					return $q->select(['id', 'name']);
				}
			])
			->where('YEAR(created_at) >= 2015');
		if ($dt != '') {//var_dump($dt);die;
			$query->innerJoinWith('stats');
			$year = date('Y', strtotime($dt));
			$month = date('m', strtotime($dt));
			$query->andWhere(['YEAR(tour_end_date)' => $year, 'MONTH(tour_end_date)' => $month]);
		}
		$cases = $query->asArray()->all();
		$data_users = [];
		$arr_user_name = [];
		foreach ($cases as $case) {
			$arr_user_name[$case['owner']['id']] = $case['owner']['name'];

			if (!isset($data_users[$case['owner_id']])) {
				$data_users[$case['owner_id']] = [];
			}
			$y_open = date('Y', strtotime($case['created_at']));
			$kx = 'k8';
			// K1
			if ($case['how_contacted'] == 'web/adwords/google') {
				$kx = 'k1';
			}

			// K2
			if ($case['how_contacted'] == 'web/adwords/bing') {
				$kx = 'k2';
			}

			// K3
			if (strpos($case['how_contacted'], 'web/search') !== false) {
				$kx = 'k3';
			}

			// K4
			if (strpos($case['how_contacted'], 'web/link') !== false || strpos($case['how_contacted'], 'web/adonline') !== false || $case['how_contacted'] == 'web') {
				$kx = 'k4';
			}
			// K5
			if ($case['how_contacted'] == 'web/direct') {
				$kx = 'k5';
			}
			// K6
			if ($case['how_contacted'] == 'web/email') {
				$kx = 'k6';
			}
			// K5
			if (strpos($case['how_contacted'], 'nweb') !== false) {
				$kx = 'k7';
			}


			if (!isset($data_users[$case['owner_id']][$kx])) {
				$data_users[$case['owner_id']][$kx] = [];
			}
			// new case
			if ($case['how_found'] == 'new' || $case['how_found'] == 'new/nref' || strpos($case['how_found'], 'new/nref') !== false) {
				if (!isset($data_users[$case['owner_id']][$kx]['new'])) {
					$data_users[$case['owner_id']][$kx]['new'] = [];
				}
				if (!isset($data_users[$case['owner_id']][$kx]['new'][$y_open])) {
					$data_users[$case['owner_id']][$kx]['new'][$y_open] = 0;
				}
				$data_users[$case['owner_id']][$kx]['new'][$y_open]++;
			}
			// referral case
			if ($case['how_found'] == 'new/ref' || strpos($case['how_found'], 'new/ref') !== false) {
				if (!isset($data_users[$case['owner_id']][$kx]['ref'])) {
					$data_users[$case['owner_id']][$kx]['ref'] = [];
				}
				if (!isset($data_users[$case['owner_id']][$kx]['ref'][$y_open])) {
					$data_users[$case['owner_id']][$kx]['ref'][$y_open] = 0;
				}
				$data_users[$case['owner_id']][$kx]['ref'][$y_open]++;
			}
			// returning case
			if ($case['how_found'] == 'returning' || strpos($case['how_found'], 'returning') !== false) {
				if (!isset($data_users[$case['owner_id']][$kx]['returning'])) {
					$data_users[$case['owner_id']][$kx]['returning'] = [];
				}
				if (!isset($data_users[$case['owner_id']][$kx]['returning'][$y_open])) {
					$data_users[$case['owner_id']][$kx]['returning'][$y_open] = 0;
				}
				$data_users[$case['owner_id']][$kx]['returning'][$y_open]++;
			}

		}




		// export to excel

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
		$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');

		$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('G1:I1');
		$objPHPExcel->getActiveSheet()->mergeCells('J1:K1');
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Ban Hang')
		            ->setCellValue('B1', 'Kenh')
		            ->setCellValue('C1', 'New')
		            ->setCellValue('G1', 'Referral')
		            ->setCellValue('J1', 'Old')
		            ->setCellValue('C2', 2015)
	                ->setCellValue('D2', 2016)
	                ->setCellValue('E2', 2017)
					->setCellValue('F2', 2018)
	                ->setCellValue('G2', 2016)
	                ->setCellValue('H2', 2017)
	                ->setCellValue('I2', 2018)
	                ->setCellValue('J2', 2016)
	                ->setCellValue('K2', 2017);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
		$k = 3;
		foreach ($data_users as $user_id => $d_user) {
			$rowspan = count($d_user);
			$limit_mergeRow = ($k + $rowspan) - 1;
			$range_mer = 'A'.$k.':'.'A'. $limit_mergeRow;
			$objPHPExcel->getActiveSheet()->mergeCells($range_mer);
			$ki = 1;
			ksort($d_user);
			foreach ($d_user as $kx => $d_kx) {
				if ($ki == 1) {
					$objPHPExcel->setActiveSheetIndex(0)
			                ->setCellValue('A'.$k, $user_id);
				}
				$objPHPExcel->setActiveSheetIndex(0)
			                ->setCellValue('B'.$k, $kx)
			                ->setCellValue('C'.$k, isset($d_kx['new'][2015])? $d_kx['new'][2015]: 0)
			                ->setCellValue('D'.$k, isset($d_kx['new'][2016])? $d_kx['new'][2016]: 0)
			                ->setCellValue('E'.$k, isset($d_kx['new'][2017])? $d_kx['new'][2017]: 0)
							->setCellValue('F'.$k, isset($d_kx['new'][2018])? $d_kx['new'][2018]: 0)
			                ->setCellValue('G'.$k, isset($d_kx['ref'][2016])? $d_kx['ref'][2016]: 0)
			                ->setCellValue('H'.$k, isset($d_kx['ref'][2017])? $d_kx['ref'][2017]: 0)
			                ->setCellValue('I'.$k, isset($d_kx['ref'][2018])? $d_kx['ref'][2018]: 0)
			                ->setCellValue('J'.$k, isset($d_kx['old'][2016])? $d_kx['old'][2016]: 0)
			                ->setCellValue('K'.$k, isset($d_kx['old'][2017])? $d_kx['old'][2017]: 0);


				$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':K'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':K'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$k++;
				$ki ++;
			}
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->setTitle('Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('MyExcel.xlsx');
		// end export


		return $this->render('report_market', [
			'data_users' => $data_users,
			'arr_user_name' => $arr_user_name
		]);
	}




	public function actionU_profile($page = 1){
        if (Yii::$app->request->isAjax) {
        	$persons = Person::find()->where('is_client = "yes"');
        	$query = clone $persons;
            $resultCount = 50;
            $offset = ($page - 1) * $resultCount;
            $persons = $persons->offset($offset)->limit($resultCount)->asArray()->all();
            $count = count($query->all());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $ids = [];
            foreach ($persons as $person) {
				$profile = Yii::$app->db->createCommand('SELECT * FROM at_profiles_customer WHERE user_id=:user_id', [':user_id' => $person['id']])->queryOne();
				$user_bookings = Yii::$app->db->createCommand('
						SELECT *
						FROM at_booking_user INNER JOIN at_bookings ON  at_booking_user.booking_id = at_bookings.id
											 INNER JOIN at_ct ON  at_bookings.product_id = at_ct.id
											 INNER JOIN at_tour_stats ON  at_ct.id = at_tour_stats.tour_id
						WHERE user_id =:user_id', ['user_id' => $person['id']])->queryAll();
				$user_cases = Yii::$app->db->createCommand('SELECT * FROM at_case_user WHERE user_id =:user_id', ['user_id' => $person['id']])->queryAll();
				$referral_cases = Yii::$app->db->createCommand('SELECT * FROM at_referrals INNER JOIN at_cases ON at_referrals.case_id = at_cases.id WHERE  user_id =:user_id', ['user_id' => $person['id']])->queryAll();


				//get mails and check

				$metas_email = Yii::$app->db->createCommand('SELECT * FROM metas WHERE rid=:user_id AND rtype = "user" AND name="email"', [':user_id' => $person['id']])->queryAll();
				// var_dump($metas_email);die;
				$arr_contact = [];
				foreach ($metas_email as $meta) {//var_dump($meta);die;
					$inquiries = Yii::$app->db->createCommand('SELECT created_at, updated_at, email FROM at_inquiries WHERE  email =:email', [':email' => $meta['value']])->queryAll();
					if ($inquiries) {
						foreach ($inquiries as $inquiry) {
							$arr_contact['created_at'][] = $inquiry['created_at'];
							$arr_contact['updated_at'][] = $inquiry['updated_at'];
						}
					}
					// var_dump($inquiries);die;
					$emails = Yii::$app->db->createCommand('SELECT * FROM at_mails WHERE  at_mails.`from` LIKE :email', [':email' => '%'.$meta['value'].'%'])->queryAll();
					if ($emails) {
						foreach ($emails as $email) {
							$arr_contact['created_at'][] = $email['created_at'];
							$arr_contact['updated_at'][] = $email['updated_at'];
						}
					}
				}

				if (count($arr_contact) > 0) {
					$last_contact = $first_contact = $arr_contact['created_at'][0];
					foreach ($arr_contact['created_at'] as $dt) {
						if (strtotime($first_contact) > strtotime($dt)) {
							$first_contact = $dt;
						}
						if (strtotime($last_contact) < strtotime($dt)) {
							$last_contact = $dt;
						}
					}
				}

				//
				$won_referral_count = 0;
				if ($referral_cases) {
					foreach ($referral_cases as $referral_case) {
						if ($referral_case['deal_status'] == 'won') {
							$won_referral_count ++;
						}
					}
				}
				$tour_dates = [];
				$visited_countries = '';
				if ($user_bookings) {
					foreach ($user_bookings as $user_booking) {
						$tour_dates[] = date('Y-m', strtotime($user_booking['day_from']));
						if ($visited_countries == '') {
							$visited_countries = $user_booking['countries'];
						} else {
							$visited_countries .= ','. $user_booking['countries'];
						}
					}
				}
				if ($visited_countries != '') {
					$arr_visited_countries = array_unique(explode(',', $visited_countries));
					$visited_countries = implode(',', $arr_visited_countries);
				}
				if (!$profile) {
					//create new profile
					Yii::$app->db->createCommand('
									INSERT INTO at_profiles_customer (
										created_dt, created_by, updated_dt, updated_by, user_id, case_count, booking_count, referral_count, won_referral_count, first_contact, last_contact, tour_dates, visited_countries, note
									)
									values(:created_dt, :created_by, :updated_dt, :updated_by, :user_id, :case_count, :booking_count, :referral_count, :won_referral_count, :first_contact, :last_contact, :tour_dates, :visited_countries, "")',
									[
										':created_dt' => NOW,
										':created_by' => USER_ID,
										':updated_dt' => NOW,
										':updated_by' => USER_ID,
										':user_id' => $person['id'],
										':case_count' => count($user_cases),
										':booking_count' => count($user_bookings),
										':referral_count' => count($referral_cases),
										':won_referral_count' => $won_referral_count,
										':first_contact' => isset($first_contact)? $first_contact: '',
										':last_contact' => isset($last_contact)? $last_contact: '',
										':tour_dates' => implode(';', $tour_dates),
										':visited_countries' => $visited_countries
									])->execute();

				} else {
					//update
					Yii::$app->db->createCommand('
										UPDATE at_profiles_customer
										SET
											updated_dt =:updated_dt,
											updated_by =:updated_by,
											case_count =:case_count,
											booking_count =:booking_count,
											referral_count =:referral_count,
											won_referral_count =:won_referral_count,
											first_contact =:first_contact,
											last_contact =:last_contact,
											tour_dates = :tour_dates,
											visited_countries =:visited_countries
					 					WHERE id =:id',
										[
											':id' => $profile['id'],
											':updated_dt' => NOW,
											':updated_by' => USER_ID,
											':case_count' => count($user_cases),
											':booking_count' => count($user_bookings),
											':referral_count' => count($referral_cases),
											':won_referral_count' => $won_referral_count,
											':first_contact' => isset($first_contact)? $first_contact: '',
											':last_contact' => isset($last_contact)? $last_contact: '',
											':tour_dates' => implode(';', $tour_dates),
											':visited_countries' => $visited_countries
										])->execute();
					$ids[] = $profile['id'];
				}

			}

            if (!$morePages) {
            	return json_encode(['page' => $page, 'status' => 'done!']);
            }
			$page = $page + 1;
            echo json_encode(['page' => $page, 'status' => 'more', 'ids_updated' =>implode(',', $ids)]);
        } else {
        	return $this->render('u_profile');
        }
    }
	public function actionU_profile_data()
	{
		foreach ($persons as $person) {
			$profile = Yii::$app->db->createCommand('SELECT * FROM at_profiles_customer WHERE user_id=:user_id', [':user_id' => $person['id']])->queryOne();
			$user_bookings = Yii::$app->db->createCommand('
					SELECT *
					FROM at_booking_user INNER JOIN at_bookings ON  at_booking_user.booking_id = at_bookings.id
										 INNER JOIN at_ct ON  at_bookings.product_id = at_ct.id
										 INNER JOIN at_tour_stats ON  at_ct.id = at_tour_stats.tour_id
					WHERE user_id =:user_id', ['user_id' => $person['id']])->queryAll();
			$user_cases = Yii::$app->db->createCommand('SELECT * FROM at_case_user WHERE user_id =:user_id', ['user_id' => $person['id']])->queryAll();
			$referral_cases = Yii::$app->db->createCommand('SELECT * FROM at_referrals INNER JOIN at_cases ON at_referrals.case_id = at_cases.id WHERE  user_id =:user_id', ['user_id' => $person['id']])->queryAll();
			$metas_email = Yii::$app->db->createCommand('SELECT * FROM metas WHERE rid=:user_id AND rtype = "user" AND name="email"', [':user_id' => $person['id']])->queryAll();
			// var_dump($metas_email);die;
			foreach ($metas_email as $email) {
				$inquiries = Yii::$app->db->createCommand('SELECT * FROM at_inquiries WHERE  email =:email', [':email' => $email])->queryAll();
				$emails = Yii::$app->db->createCommand('SELECT * FROM at_mails WHERE  email LIKE :email', [':email' => '%'.$email.'%'])->queryAll();
				var_dump($emails);die;
			}
			$won_referral_count = 0;
			if ($referral_cases) {
				foreach ($referral_cases as $referral_case) {
					if ($referral_case['deal_status'] == 'won') {
						$won_referral_count ++;
					}
				}
			}
			$tour_dates = [];
			$visited_countries = '';
			if ($user_bookings) {
				foreach ($user_bookings as $user_booking) {
					$tour_dates[] = date('Y-m', strtotime($user_booking['day_from']));
					if ($visited_countries == '') {
						$visited_countries = $user_booking['countries'];
					} else {
						$visited_countries .= ','. $user_booking['countries'];
					}
				}
			}
			if ($visited_countries != '') {
				$arr_visited_countries = array_unique(explode(',', $visited_countries));
				$visited_countries = implode(',', $arr_visited_countries);
			}
			if (!$profile) {
				//create new profile
				Yii::$app->db->createCommand('
									INSERT INTO at_profiles_customer (
										created_dt, created_by, updated_dt, updated_by, user_id, case_count, booking_count, referral_count, won_referral_count, first_contact, last_contact, tour_dates, visited_countries, note
									)
									values(:created_dt, :created_by, :updated_dt, :updated_by, :user_id, :case_count, :booking_count, :referral_count, :won_referral_count, :first_contact, :last_contact, :tour_dates, :visited_countries, "")',
									[
										':created_dt' => NOW,
										':created_by' => USER_ID,
										':updated_dt' => NOW,
										':updated_by' => USER_ID,
										':user_id' => $person['id'],
										':case_count' => count($user_cases),
										':booking_count' => count($user_bookings),
										':referral_count' => count($referral_cases),
										':won_referral_count' => $won_referral_count,
										':first_contact' => '',
										':last_contact' => '',
										':tour_dates' => implode(';', $tour_dates),
										':visited_countries' => $visited_countries
									])->execute();
			} else {
				//update
				Yii::$app->db->createCommand('
									UPDATE at_profiles_customer
									SET
										case_count =:case_count,
										booking_count =:booking_count,
										referral_count =:referral_count,
										won_referral_count =:won_referral_count,
										-- first_contact =:first_contact,
										-- last_contact =:last_contact,
										tour_dates = :tour_dates,
										visited_countries =:visited_countries
				 					WHERE id =:id',
									[
										':id' => $profile['id'],
										':case_count' => count($user_cases),
										':booking_count' => count($user_bookings),
										':referral_count' => count($referral_cases),
										':won_referral_count' => $won_referral_count,
										// ':first_contact' => '',
										// ':last_contact' => '',
										':tour_dates' => implode(';', $tour_dates),
										':visited_countries' => $visited_countries
									])->execute();
			}

		}die('finish');
	}
	public function actionImport()
	{
		// var_dump(Yii::$app->request);die();
		$arr = [];
		if (isset($_FILES['import'])) {
			//$file = UploadedFile::getInstance($_POST['import']);
			// $file_name = $_FILES['import']['name'];
			$tmp_name = $_FILES["import"]["tmp_name"];


			$objPHPExcel = \PHPExcel_IOFactory::load($tmp_name);
			$arr = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);


			// print_r($arr);die();
			var_dump($arr); die();
			$booking = Booking::find()->where(['id' => 38873])->with([
				'invoices',
				'payments'
				])->one();
			// var_dump($booking['invoices']);
			// var_dump($booking['payments']);
			// var_dump($arr);die();
			foreach ($arr as $k => $row) {
				if ($k == 1) continue;
				$thePayment = new Payment();
				$thePayment->scenario = 'payments_c';
				// if ($row['A'] != '') {
				// 	$tour = AtTours::find()->where(['code' => $row['A']])->with([
				// 		'ct'
				// 	])->asArray()->one();
				// 	if ($tour == null) continue;
				// 	$theBooking = Booking::find()->where(['product_id' => $tour['ct']['id']])->with([
				// 		'invoices',
				// 		'payments'
				// 	])->asArray()->one();
				// 	if ($theBooking == null) continue;

				// 	$thePayment->booking_id = $theBooking['id'];
		  //           $thePayment->created_at = date('Y-m-d H:i:s', strtotime('now'));//NOW;
		  //           $thePayment->created_by = 1;//USER_ID;
		  //           $thePayment->updated_at = '0000-00-00 00:00:00';//NOW;
		  //           $thePayment->updated_by = 1;//USER_ID;
		  //           $thePayment->status = 'on';

		  //           $thePayment->ref = $row['A'].' - '.$row['B'];
		  //           $thePayment->payment_dt = date('Y-m-d H:i:s', strtotime($row['C']));
		  //           $thePayment->payer = $row['D'];
		  //           $thePayment->payee = $row['E'];
		  //           $thePayment->method = $row['F'];
		  //           $thePayment->amount = $row['G'];
		  //           $thePayment->currency = strtoupper($row['H']);
		  //           if ($row['H'] == 'VND') {
		  //           	$thePayment->xrate = 1;
		  //           }
		  //           else {
		  //           	$thePayment->xrate = $row['I'];
		  //           }
		  //           $thePayment->note = $row['J'];
		  //           if ($row['B'] != '') {
				// 		$invois = explode('+', $row['B']);
				// 		if (count($invois) > 1) {
				// 			// foreach ($invois as $index => $invoi) {
				// 			// 	foreach ($theBooking['invoices'] as $key => $value) {
				// 			// 		if (intval($invoi) == $key+1) {
				// 			// 			$thePayment->invoice_id = $value['id'];
				// 			// 		}
				// 			// 	}
				// 			// }
				// 			$thePayment->invoice_id = 1;
				// 		} else {
				// 			foreach ($theBooking['invoices'] as $key => $value) {
				// 				$arr_ref = explode('-', $value['ref']);
				// 				if (count($arr_ref) == 2) {
				// 					if (intval($invois[0]) == intval($arr_ref[1])) {
				// 						$thePayment->invoice_id = $value['id'];
				// 					}
				// 				}
				// 			}
				// 		}
				// 	}
				// 	else {
				// 		$thePayment->invoice_id = 0;
				// 	}

				// 	if ($thePayment->validate()) {
				// 		$thePayment->save();
				// 	}
				// 	else
				// 	{
				// 		var_dump($thePayment->errors);die();
				// 	}
				// }
			}
		}
		if (true) {
			$wizard = new PHPExcel_Helper_HTML;
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'stt')
			            ->setCellValue('B1', 'Total number of events: ')
			            ->setCellValue('E1', 'Total number of events: ');
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
			foreach ($arr as $k => $row)
			{
				if ($k == 1) continue;
			    	//add html
	                $html = '<font color="red">
						<h1 align="center">My very first example of rich text<br />generated from html markup</h1>
						<p>
						<font size="14" COLOR="rgb(0,255,128)">
						<b>This block</b> contains an <i>italicized</i> word;
						while this block uses an <u>underline</u>.
						</font>
						</p>
						<p align="right"><font size="9" color="red">
						I want to eat <ins><del>healthy food</del> <strong>pizza</strong></ins>.
						</font>
					';
					$richText = $wizard->toRichTextObject($html);
					$objPHPExcel->setActiveSheetIndex(0)
			                ->setCellValue('A'.$k, $row['A'])
			                ->setCellValue('B'.$k, $row['B'])
			                ->setCellValue('C'.$k, $row['C'])
			                ->setCellValue('D'.$k, $row['D'])
			                ->setCellValue('E'.$k, $row['E'])
							->setCellValue('F'.$k, $richText);
			}

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setTitle('Report');
			$objPHPExcel->setActiveSheetIndex(0);

			//add img
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setPath('img/demo-3.jpg');
			$objDrawing->setCoordinates('A5');
			//setOffsetX works properly
			$objDrawing->setOffsetX(5);
			$objDrawing->setOffsetY(5);
			//set width, height
			$objDrawing->setWidth(100);
			$objDrawing->setHeight(100);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('MyExcel.xlsx');
		}
		return $this->render('import', [
			'arr' => $arr
		]);
	}
	public function actionExport()
	{
		$query = Kase::find()
				->with([
					'cperson' => function($q){
						return $q->select(['id', 'fname', 'lname', 'gender', 'byear',
							'country_code', 'email'
						])->with('refCases');
					},
					'stats' => function($q){
						return $q->select(['case_id', 'req_countries']);
					},
				])
				->where('MONTH(created_at) IN (7,8,9) AND YEAR(created_at) = 2017 AND deal_status = "lost"')
				->asArray()->all();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'First name')
		            ->setCellValue('B1', 'Last name')
		            ->setCellValue('C1', 'Gender')
		            ->setCellValue('D1', 'Date of birth')
		            ->setCellValue('E1', 'National')
		            ->setCellValue('F1', 'Email')
		            ->setCellValue('G1', 'Destinations')
		            ->setCellValue('H1', 'Reason')
		            ->setCellValue('I1', 'Status');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$persons = [];
		$k = 2;
		foreach ($query as $case) {
			if (count($case['cperson']) == 0) {
				continue;
			}
			foreach ($case['cperson'] as $person) {
				$person = $case['cperson'][0];
				$objPHPExcel->setActiveSheetIndex(0)
			                ->setCellValue('A'.$k, $person['fname'])
			                ->setCellValue('B'.$k, $person['lname'])
			                ->setCellValue('C'.$k, $person['gender'])
			                ->setCellValue('D'.$k, $person['byear'])
			                ->setCellValue('E'.$k, $person['country_code'])
							->setCellValue('F'.$k, $person['email'])
			                ->setCellValue('G'.$k, str_replace("|", ",", $case['stats']['req_countries']))
			                ->setCellValue('H'.$k, $case['why_closed'])
			                ->setCellValue('I'.$k, 'new client');
			    if (count($person['refCases']) > 0) {
			    	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$k, 'old');
			    }
				$k++;
			}
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(false);

		// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);

		$objPHPExcel->getActiveSheet()->setTitle('Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('MyExcel.xlsx');
		die('okkkkkkk');
	}

	public function actionSale_b2c($view = 'ketthuc', $year = 0, $year2 = 0, $unit_price = 'EUR', $sopax = '', $songay = '')
    {
        if ($year == 0) {
            $year = date('Y');
        }
        if ($year2 == $year) {
            $year2 = 0;
        }

        $result = [];
        // $result[$yyyy][$mm][$index]
        // 'Số tour', 'Số khách', 'Số ngày',
        // 'Số khách BQ /tour', 'Số ngày BQ /tour',
        // 'Doanh thu', 'Giá vốn', 'Lợi nhuận',
        // 'Doanh thu BQ /tour', 'Giá vốn BQ /tour', 'Lợi nhuận BQ /tour',
        // 'Doanh thu BQ /khách', 'Giá vốn BQ /khách', 'Lợi nhuận BQ /khách',
        // 'Doanh thu BQ /khách/ngày', 'Giá vốn BQ /khách/ngày', 'Lợi nhuận BQ /khách/ngày',

        $query = Product::find()
            ->select(['id', 'at_ct.day_count', 'start_date'=>'day_from', 'at_ct.price', 'at_ct.price_unit', 'end_date'=>new \yii\db\Expression('IF(at_ct.day_count=0, day_from, DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->where(['and', ['op_status'=>'op'], 'op_finish!="canceled"'])
            ->andWhere('SUBSTRING(op_code,1,1)="F"')
            ->with([
                'bookings'=>function($q){
                    return $q->select(['id', 'product_id', 'pax']);
                },
                'bookings.invoices'=>function($q){
                    return $q->select(['id', 'booking_id', 'amount', 'currency', 'due_dt']);
                },
                'bookings.invoices.payments'=>function($q){
                    return $q->select(['invoice_id', 'amount', 'currency', 'xrate', 'payment_dt']);
                },
                'bookings.report'=>function($q){
                    return $q->select(['booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
                },
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id']);
                },
                'tour.cpt'=>function($q){
                    return $q->select(['tour_id', 'qty', 'price', 'plusminus', 'unitc']);
                },
                'tourStats'
                ])
            ->asArray();


        if ($view == 'khoihanh') {
            $query->andHaving('YEAR(start_date)=:year', [':year'=>$year]);
        } else {
            $query->andHaving('YEAR(end_date)=:year', [':year'=>$year]);
        }

        for ($m = 0; $m <= 12; $m ++) {
            for ($i = 0; $i <= 20; $i ++) {
                // Con so du tinh, hoac thuc te neu khong co du tinh
                $result[$year][$m][$i] = 0;
                // Con so thuc te
                $result[$year][$m][$i.'tt'] = 0;
            }
            // Con so tim kiem
            $result[$year][$m]['tk'] = 0;
            // Ti le % con so tim kiem so voi thuc te
            $result[$year][$m]['pc'] = 0;
            // Doanh thu nguyen te
            $hoadonNguyente[$year][$m] = [];
            $thuNguyente[$year][$m] = [];
        }

        $arr_xrate = [
            2016 => [
				1 => ['USD'=>22376,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				2 => ['USD'=>22296,'EUR'=>24524,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				3 => ['USD'=>22263,'EUR'=>24683,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				4 => ['USD'=>22258,'EUR'=>25150,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				5 => ['USD'=>22281,'EUR'=>25075,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				6 => ['USD'=>22305,'EUR'=>24944,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				7 => ['USD'=>22263,'EUR'=>24533,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				8 => ['USD'=>22261,'EUR'=>24832,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				9 => ['USD'=>22267,'EUR'=>24869,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				10 => ['USD'=>22276,'EUR'=>24490,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				11 => ['USD'=>22421,'EUR'=>24159,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				12 => ['USD'=>22676,'EUR'=>23834,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
			],
			2017 => [
				1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
				8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
			],
        ];

        // Cac tham so tim kiem
        $sopaxMin = 0;
        $sopaxMax = 0;
        $search = false;
        if ($sopax != '') {
            $sopaxArr = explode('-', $sopax);
            $sopaxMin = (int)trim($sopaxArr[0]);
            $sopaxMax = (int)trim($sopaxArr[1] ?? '0');
        }

        $songayMin = 0;
        $songayMax = 0;
        if ($songay != '') {
            $songayArr = explode('-', $songay);
            $songayMin = (int)trim($songayArr[0]);
            $songayMax = (int)trim($songayArr[1] ?? '0');
        }

        $doanhthuMin = 0;
        $doanhthuMax = 0;
        if (isset($_GET['doanhthu']) && $_GET['doanhthu'] != '') {
        	$doanhthuArr = explode('-', $_GET['doanhthu']);
            $doanhthuMin = (int)trim($doanhthuArr[0]);
            $doanhthuMax = (int)trim($doanhthuArr[1] ?? '0');
        }

        $loinhuanMin = 0;
        $loinhuanMax = 0;
        if (isset($_GET['loinhuan']) && $_GET['loinhuan'] != '') {
        	$loinhuanArr = explode('-', $_GET['loinhuan']);
            $loinhuanMin = (int)trim($loinhuanArr[0]);
            $loinhuanMax = (int)trim($loinhuanArr[1] ?? '0');
        }

        $theTours = $query
            ->all();
        $search = false;
       	if ($songay != ''
       		|| $sopax != ''
       		|| (isset($_GET['diemden']) && $_GET['diemden'] != '')
       		|| (isset($_GET['doanhthu']) && $_GET['doanhthu'] != '')
       		|| (isset($_GET['loinhuan']) && $_GET['loinhuan'] != '')
       	) {
        	$result_search = $result;
            $search = true;
        }
        foreach ($theTours as $tour) {
        	$xrate = [];

            $songayOk = false;
            $sopaxOk = false;
            $diemdenOk = false;

            $doanhthuOk = false;
            $loinhuanOk = false;



            // echo '<h3>', $tour['end_date'], '</h3>';
            $month = (int)substr($tour['end_date'], 5, 2);
            // So tour
            $result[$year][$month][0] ++;
            // So ngay
            $result[$year][$month][2] += $tour['day_count'];

            $dttt = 0;
            $giavontt = 0;
            foreach ($tour['bookings'] as $booking) {
                // So khach
                $result[$year][$month][1] += $booking['pax'];

                // Doanh thu
                foreach ($booking['invoices'] as $invoice) {
                    if (!isset($hoadonNguyente[$year][$month][$invoice['currency']])) {
                        $hoadonNguyente[$year][$month][$invoice['currency']] = 0;
                    }
                    $hoadonNguyente[$year][$month][$invoice['currency']] += $invoice['amount'];
                    // echo '<br>HDON THANG ', $month, ' += ', number_format($invoice['amount']), ' ', $invoice['currency'];

                    foreach ($invoice['payments'] as $payment) {
                        if (!isset($thuNguyente[$year][$month][$payment['currency']])) {
                            $thuNguyente[$year][$month][$payment['currency']] = 0;
                        }
                        $thuNguyente[$year][$month][$payment['currency']] += $payment['amount'];
                        // echo '<br>--------------- THU THANG ', $month, ' += ', number_format($payment['amount']), ' ', $payment['currency'];

                        $xrate = $this->getXrates($arr_xrate, $payment['payment_dt'], $unit_price);
                        // var_dump($xrate);die;
                        // Doanh thu - thuc te ///getXrates
                        $doanhthu = $payment['amount'];
	                    if ($payment['currency'] != $unit_price) {
	                        $doanhthu *= $xrate[$payment['currency']];
	                    }
                    	$result[$year][$month]['5tt'] += $doanhthu;
                    	$dttt += $doanhthu;
                    }
                    $xrate = $this->getXrates($arr_xrate, $invoice['due_dt'], $unit_price);
                    // Doanh thu - du tinh
                    $doanhthu = $invoice['amount'];
                    if ($invoice['currency'] != $unit_price) {
                        $doanhthu *= $xrate[$invoice['currency']];
                    }
                	$result[$year][$month][5] += $doanhthu;
                }

            	// Gia von - du tinh
            	$xrate = $this->getXrates($arr_xrate, '', $unit_price);
                $giavon = $booking['report']['cost'];
                if (strtoupper($booking['report']['cost_unit']) != $unit_price && isset($xrate[$booking['report']['cost_unit']])) {//var_dump($xrate[$booking['report']['cost_unit']]);die;
                    $giavon *= $xrate[$booking['report']['cost_unit']];
                }
                $result[$year][$month][6] += $giavon;
            }


            // kiem tra tim kiem theo doanh thu
            if (isset($_GET['doanhthu']) && $_GET['doanhthu'] != '') {
            	if ($doanhthuMax > 0 && $doanhthuMin <= $dttt && $dttt <= $doanhthuMax) {
                	$doanhthuOk = true;
            	} else {
            		if ($doanhthuMin == $doanhthu) {
            			$doanhthuOk = true;
            		}
            	}
            } else { $doanhthuOk = true; }




            if (!empty($tour['tour']['cpt'])) {

                foreach ($tour['tour']['cpt'] as $cpt) {
                    $giavon = $cpt['qty'] * $cpt['price'];
                    if ($cpt['plusminus'] == 'minus') {
                        $giavon = -$giavon;
                    }
                    $giavon *= $xrate[$cpt['unitc']];
                    $result[$year][$month]['6tt'] += $giavon;
                    $giavontt = $giavon;
                }
            }
            // kiem tra tim kiem theo doanh thu
            if (isset($_GET['doanhthu']) && $_GET['doanhthu'] != '') {
            	if ($doanhthuMax > 0 && $doanhthuMin <= $dttt && $dttt <= $doanhthuMax) {
                	$doanhthuOk = true;
            	} else {
            		if ($doanhthuMin == $doanhthu) {
            			$doanhthuOk = true;
            		}
            	}
            } else { $doanhthuOk = true; }

            // kiem tra tim kiem theo loi nhuan
            $loinhuan = $dttt - $giavontt;
            if (isset($_GET['loinhuan']) && $_GET['loinhuan'] != '') {
            	if ($loinhuanMax > 0 && $loinhuanMin <= $loinhuan && $loinhuan <= $loinhuanMax) {
                	$loinhuanOk = true;
            	} else {
            		if ((int)$loinhuanMin == (int)$loinhuan) {
            			$loinhuanOk = true;
            		}
            	}
            } else { $loinhuanOk = true; }

            // So khach BQ /tour
            $result[$year][$month][3] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month][1] / $result[$year][$month][0];
            // So ngay BQ /tour
            $result[$year][$month][4] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month][2] / $result[$year][$month][0];

            // Loi nhuan
            $result[$year][$month][7] = $result[$year][$month][5] - $result[$year][$month][6];
            $result[$year][$month]['7tt'] = $result[$year][$month]['5tt'] - $result[$year][$month]['6tt'];

            // Ti le lai LN/DT %
            $result[$year][$month][17] = $result[$year][$month][5] == 0 ? 0 : 100 * ($result[$year][$month][7] / $result[$year][$month][5]);
            $result[$year][$month]['17tt'] = $result[$year][$month]['5tt'] == 0 ? 0 : 100 * ($result[$year][$month]['7tt'] / $result[$year][$month]['5tt']);

            // Ti le markup DT/GV - 1 %
            $result[$year][$month][18] = $result[$year][$month][6] == 0 ? 0 : 100 * ($result[$year][$month][5] / $result[$year][$month][6] - 1);
            $result[$year][$month]['18tt'] = $result[$year][$month]['6tt'] == 0 ? 0 : 100 * ($result[$year][$month]['5tt'] / $result[$year][$month]['6tt'] - 1);

            // Doanh thu BQ /tour
            $result[$year][$month][8] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month][5] / $result[$year][$month][0];
            $result[$year][$month]['8tt'] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month]['5tt'] / $result[$year][$month][0];
            // Gia von BQ /tour
            $result[$year][$month][9] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month][6] / $result[$year][$month][0];
            $result[$year][$month]['9tt'] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month]['6tt'] / $result[$year][$month][0];
            // Loi nhuan BQ /tour
            $result[$year][$month][10] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month][7] / $result[$year][$month][0];
            $result[$year][$month]['10tt'] = $result[$year][$month][0] == 0 ? 0 : $result[$year][$month]['7tt'] / $result[$year][$month][0];

            // Doanh thu BQ /khach
            $result[$year][$month][11] = $result[$year][$month][1] == 0 ? 0 : $result[$year][$month][5] / $result[$year][$month][1];
            $result[$year][$month]['11tt'] = $result[$year][$month][1] == 0 ? 0 : $result[$year][$month]['5tt'] / $result[$year][$month][1];
            // Gia von BQ /khach
            $result[$year][$month][12] = $result[$year][$month][1] == 0 ? 0 : $result[$year][$month][6] / $result[$year][$month][1];
            $result[$year][$month]['12tt'] = $result[$year][$month][1] == 0 ? 0 : $result[$year][$month]['6tt'] / $result[$year][$month][1];
            // Loi nhuan BQ /khach
            $result[$year][$month][13] = $result[$year][$month][1] == 0 ? 0 : $result[$year][$month][7] / $result[$year][$month][1];
            $result[$year][$month]['13tt'] = $result[$year][$month][1] == 0 ? 0 : $result[$year][$month]['7tt'] / $result[$year][$month][1];

            // Doanh thu BQ /khach/ngay
            $result[$year][$month][14] = $result[$year][$month][4] == 0 ? 0 : $result[$year][$month][11] / $result[$year][$month][4];
            $result[$year][$month]['14tt'] = $result[$year][$month][4] == 0 ? 0 : $result[$year][$month]['11tt'] / $result[$year][$month][4];
            // Gia von BQ /khach/ngay
            $result[$year][$month][15] = $result[$year][$month][4] == 0 ? 0 : $result[$year][$month][12] / $result[$year][$month][4];
            $result[$year][$month]['15tt'] = $result[$year][$month][4] == 0 ? 0 : $result[$year][$month]['12tt'] / $result[$year][$month][4];
            // Loi nhuan BQ /khach/ngay
            $result[$year][$month][16] = $result[$year][$month][4] == 0 ? 0 : $result[$year][$month][13] / $result[$year][$month][4];
            $result[$year][$month]['16tt'] = $result[$year][$month][4] == 0 ? 0 : $result[$year][$month]['13tt'] / $result[$year][$month][4];





            //////////////////////////////
            // Tim kiem theo tieu chi khac
            //////////////////////////////
          	// kiem tra tim kiem theo so khach
            if ($sopax != '') {
            	if ($sopaxMax > 0 && $sopaxMin <= $tour['tourStats']['pax_count'] && $tour['tourStats']['pax_count'] <= $sopaxMax) {
                	$sopaxOk = true;
            	} else {
            		if ($sopaxMin == $tour['tourStats']['pax_count']) {
            			$sopaxOk = true;
            		}
            	}
            } else {
            	$sopaxOk = true;
            }


            // kiem tra tim kiem theo so ngay
            if ($songay != '') {
            	if ($songayMax > 0 && $songayMin <= $tour['tourStats']['day_count'] && $tour['tourStats']['day_count'] <= $songayMax) {
                	$songayOk = true;
            	} else {
            		if ($songayMin == $tour['tourStats']['day_count']) {
            			$songayOk = true;
            		}
            	}
            } else { $songayOk = true; }


            // kiem tra tim kiem theo diem den
            if (isset($_GET['diemden']) && $_GET['diemden'] != '') {
            	if (isset($_GET['dkdiemden']) && $_GET['dkdiemden'] == 'any') {
	            	foreach ($_GET['diemden'] as $d) {
	            		if (in_array(strtolower($d), explode(',', $tour['tourStats']['countries']))) {
	            			$diemdenOk = true;
	            		}
	            	}
            	}

            	if (isset($_GET['dkdiemden']) && $_GET['dkdiemden'] == 'all') {
	            	foreach ($_GET['diemden'] as $d) {
	            		if (in_array(strtolower($d), explode(',', $tour['tourStats']['countries']))) {
	            			$diemdenOk = true;
	            		} else {
	            			$diemdenOk = false;
	            			break;
	            		}
	            	}
            	}

            	if (isset($_GET['dkdiemden']) && $_GET['dkdiemden'] == 'only' && count(explode(',', $tour['tourStats']['countries'])) == count($_GET['diemden'])) {
	            	foreach ($_GET['diemden'] as $d) {
	            		if (in_array(strtolower($d), explode(',', $tour['tourStats']['countries']))) {
	            			$diemdenOk = true;
	            		} else {
	            			$diemdenOk = false;
	            			break;
	            		}
	            	}
            	}

            	if (isset($_GET['dkdiemden']) && $_GET['dkdiemden'] == 'not') {
	            	foreach ($_GET['diemden'] as $d) {
	            		if (!in_array(strtolower($d), explode(',', $tour['tourStats']['countries']))) {
	            			$diemdenOk = true;
	            		} else {
	            			$diemdenOk = false;
	            			break;
	            		}
	            	}
            	}
            } else { $diemdenOk = true; }



            // kiem tra neu co tim kiem
            if ($songay != ''
            	|| $sopax != ''
            	|| (isset($_GET['diemden']) && $_GET['diemden'] != '')
            	|| (isset($_GET['doanhthu']) && $_GET['doanhthu'] != '')
            	|| (isset($_GET['loinhuan']) && $_GET['loinhuan'] != '')
            ) {
	            if ($sopaxOk && $songayOk & $diemdenOk && $doanhthuOk && $loinhuanOk) {
	                $result[$year][$month]['tk'] ++;
	                $result[$year][$month]['pc'] = $result[$year][$month][0] == 0 ? 0 : 100 * ($result[$year][$month]['tk'] / $result[$year][$month][0]);

	                // lay du lieu khi phu hop voi dieu kien

	                // echo '<h3>', $tour['end_date'], '</h3>';
		            $month = (int)substr($tour['end_date'], 5, 2);
		            // So tour
		            $result_search[$year][$month][0] ++;
		            // So ngay
		            $result_search[$year][$month][2] += $tour['day_count'];

		            foreach ($tour['bookings'] as $booking) {
		                // So khach
		                $result_search[$year][$month][1] += $booking['pax'];
		                // Doanh thu - du tinh
		                $doanhthu = $booking['report']['price'];
		                if ($booking['report']['price_unit'] == 'USD') {
		                    $doanhthu *= $xrate['EUR'];
		                } elseif ($booking['report']['price_unit'] == 'VND') {
		                    $doanhthu *= $xrate['VND'];
		                }
		                $result_search[$year][$month][5] += $doanhthu;

		                // Doanh thu - thuc te
		                foreach ($booking['invoices'] as $invoice) {
		                    if (!isset($hoadonNguyente[$year][$month][$invoice['currency']])) {
		                        $hoadonNguyente[$year][$month][$invoice['currency']] = 0;
		                    }
		                    $hoadonNguyente[$year][$month][$invoice['currency']] += $invoice['amount'];
		                    // echo '<br>HDON THANG ', $month, ' += ', number_format($invoice['amount']), ' ', $invoice['currency'];

		                    foreach ($invoice['payments'] as $payment) {
		                        if (!isset($thuNguyente[$year][$month][$payment['currency']])) {
		                            $thuNguyente[$year][$month][$payment['currency']] = 0;
		                        }
		                        $thuNguyente[$year][$month][$payment['currency']] += $payment['amount'];
		                        // echo '<br>--------------- THU THANG ', $month, ' += ', number_format($payment['amount']), ' ', $payment['currency'];
		                    }

		                    $doanhthu = $invoice['amount'];
		                    if ($invoice['currency'] == 'USD') {
		                        $doanhthu *= $xrate['EUR'];
		                    } elseif ($invoice['currency'] == 'VND') {
		                        $doanhthu *= $xrate['VND'];
		                    }
		                    $result_search[$year][$month]['5tt'] += $doanhthu;
		                }

		                // Gia von - du tinh
		                $giavon = $booking['report']['cost'];
		                if ($booking['report']['cost_unit'] == 'USD') {
		                    $giavon *= $xrate['EUR'];
		                } elseif ($booking['report']['cost_unit'] == 'VND') {
		                    $giavon *= $xrate['VND'];
		                }
		                $result_search[$year][$month][6] += $giavon;
		            }

		            if (!empty($tour['tour']['cpt'])) {

		                foreach ($tour['tour']['cpt'] as $cpt) {
		                    $giavon = $cpt['qty'] * $cpt['price'];
		                    if ($cpt['plusminus'] == 'minus') {
		                        $giavon = -$giavon;
		                    }
		                    $giavon *= $xrate[$cpt['unitc']];
		                    $result_search[$year][$month]['6tt'] += $giavon;
		                }
		            }

		            // So khach BQ /tour
		            $result_search[$year][$month][3] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month][1] / $result_search[$year][$month][0];
		            // So ngay BQ /tour
		            $result_search[$year][$month][4] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month][2] / $result_search[$year][$month][0];

		            // Loi nhuan
		            $result_search[$year][$month][7] = $result_search[$year][$month][5] - $result_search[$year][$month][6];
		            $result_search[$year][$month]['7tt'] = $result_search[$year][$month]['5tt'] - $result_search[$year][$month]['6tt'];

		            // Ti le lai LN/DT %
		            $result_search[$year][$month][17] = $result_search[$year][$month][5] == 0 ? 0 : 100 * ($result_search[$year][$month][7] / $result_search[$year][$month][5]);
		            $result_search[$year][$month]['17tt'] = $result_search[$year][$month]['5tt'] == 0 ? 0 : 100 * ($result_search[$year][$month]['7tt'] / $result_search[$year][$month]['5tt']);

		            // Ti le markup DT/GV - 1 %
		            $result_search[$year][$month][18] = $result_search[$year][$month][6] == 0 ? 0 : 100 * ($result_search[$year][$month][5] / $result_search[$year][$month][6] - 1);
		            $result_search[$year][$month]['18tt'] = $result_search[$year][$month]['6tt'] == 0 ? 0 : 100 * ($result_search[$year][$month]['5tt'] / $result_search[$year][$month]['6tt'] - 1);

		            // Doanh thu BQ /tour
		            $result_search[$year][$month][8] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month][5] / $result_search[$year][$month][0];
		            $result_search[$year][$month]['8tt'] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month]['5tt'] / $result_search[$year][$month][0];
		            // Gia von BQ /tour
		            $result_search[$year][$month][9] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month][6] / $result_search[$year][$month][0];
		            $result_search[$year][$month]['9tt'] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month]['6tt'] / $result_search[$year][$month][0];
		            // Loi nhuan BQ /tour
		            $result_search[$year][$month][10] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month][7] / $result_search[$year][$month][0];
		            $result_search[$year][$month]['10tt'] = $result_search[$year][$month][0] == 0 ? 0 : $result_search[$year][$month]['7tt'] / $result_search[$year][$month][0];

		            // Doanh thu BQ /khach
		            $result_search[$year][$month][11] = $result_search[$year][$month][1] == 0 ? 0 : $result_search[$year][$month][5] / $result_search[$year][$month][1];
		            $result_search[$year][$month]['11tt'] = $result_search[$year][$month][1] == 0 ? 0 : $result_search[$year][$month]['5tt'] / $result_search[$year][$month][1];
		            // Gia von BQ /khach
		            $result_search[$year][$month][12] = $result_search[$year][$month][1] == 0 ? 0 : $result_search[$year][$month][6] / $result_search[$year][$month][1];
		            $result_search[$year][$month]['12tt'] = $result_search[$year][$month][1] == 0 ? 0 : $result_search[$year][$month]['6tt'] / $result_search[$year][$month][1];
		            // Loi nhuan BQ /khach
		            $result_search[$year][$month][13] = $result_search[$year][$month][1] == 0 ? 0 : $result_search[$year][$month][7] / $result_search[$year][$month][1];
		            $result_search[$year][$month]['13tt'] = $result_search[$year][$month][1] == 0 ? 0 : $result_search[$year][$month]['7tt'] / $result_search[$year][$month][1];

		            // Doanh thu BQ /khach/ngay
		            $result_search[$year][$month][14] = $result_search[$year][$month][4] == 0 ? 0 : $result_search[$year][$month][11] / $result_search[$year][$month][4];
		            $result_search[$year][$month]['14tt'] = $result_search[$year][$month][4] == 0 ? 0 : $result_search[$year][$month]['11tt'] / $result_search[$year][$month][4];
		            // Gia von BQ /khach/ngay
		            $result_search[$year][$month][15] = $result_search[$year][$month][4] == 0 ? 0 : $result_search[$year][$month][12] / $result_search[$year][$month][4];
		            $result_search[$year][$month]['15tt'] = $result_search[$year][$month][4] == 0 ? 0 : $result_search[$year][$month]['12tt'] / $result_search[$year][$month][4];
		            // Loi nhuan BQ /khach/ngay
		            $result_search[$year][$month][16] = $result_search[$year][$month][4] == 0 ? 0 : $result_search[$year][$month][13] / $result_search[$year][$month][4];
		            $result_search[$year][$month]['16tt'] = $result_search[$year][$month][4] == 0 ? 0 : $result_search[$year][$month]['13tt'] / $result_search[$year][$month][4];
		            // $result[$year][$month] = $result_search[$year][$month];
	            }
            }
            ///////////////////
            // end search  ///
            //////////////////
        }
        // Year total
        for ($i = 0; $i <= 20; $i ++) {
            for ($m = 1; $m <= 12; $m ++) {
                $result[$year][0][$i] += $result[$year][$m][$i];
                if (isset($result[$year][$m][$i.'tt'])) {
                    $result[$year][0][$i.'tt'] += $result[$year][$m][$i.'tt'];
                }
            }
        }

        if ($year2 != 0 && $year2 != $year) {
            $query2 = Product::find()
                ->select(['id', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
                ->where(['and', ['op_status'=>'op'], 'op_finish!="canceled"'])
                ->andWhere('SUBSTRING(op_code,1,1)="F"')
                ->with([
                    'bookings'=>function($q){
                        return $q->select(['id', 'product_id', 'pax']);
                    },
                    'bookings.report'=>function($q){
                        return $q->select(['booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
                    },
                    'bookings.invoices'=>function($q){
                        return $q->select(['booking_id', 'amount', 'currency']);
                    },
                    'tour.cpt'=>function($q){
                        return $q->select(['tour_id', 'qty', 'price', 'plusminus', 'unitc']);
                    },
                    ])
                ->asArray();
            if ($view == 'khoihanh') {
                $query2->andHaving('YEAR(start_date)=:year', [':year'=>$year2]);
            } else {
                $query2->andHaving('YEAR(end_date)=:year', [':year'=>$year2]);
            }

            $theTours2 = $query2
                ->all();

            for ($m = 0; $m <= 12; $m ++) {
                for ($i = 0; $i <= 20; $i ++) {
                    $result[$year2][$m][$i] = 0;
                    $result[$year2][$m][$i.'tt'] = 0;
                }
            }

            foreach ($theTours2 as $tour) {
                $month = (int)substr($tour['end_date'], 5, 2);
                // So tour
                $result[$year2][$month][0] ++;
                // So ngay
                $result[$year2][$month][2] += $tour['day_count'];

                foreach ($tour['bookings'] as $booking) {
                    // So khach
                    $result[$year2][$month][1] += $booking['pax'];
                    // Doanh thu - du tinh
                    $doanhthu = $booking['report']['price'];
                    if ($booking['report']['price_unit'] == 'EUR') {
                        $doanhthu *= $xrate['EUR'];
                    } elseif ($booking['report']['price_unit'] == 'VND') {
                        $doanhthu *= $xrate['VND'];
                    }
                    $result[$year2][$month][5] += $doanhthu;

                    // Doanh thu - thuc te
                    foreach ($booking['invoices'] as $invoice) {
                        $doanhthu = $invoice['amount'];
                        if ($invoice['currency'] == 'USD') {
                            $doanhthu *= $xrate['EUR'];
                        } elseif ($invoice['currency'] == 'VND') {
                            $doanhthu *= $xrate['VND'];
                        }
                        $result[$year2][$month]['5tt'] += $doanhthu;
                    }

                    // Gia von - du tinh
                    $giavon = $doanhthu = $booking['report']['cost'];
                    if ($booking['report']['cost_unit'] == 'EUR') {
                        $giavon *= $xrate['EUR'];
                    } elseif ($booking['report']['cost_unit'] == 'VND') {
                        $giavon *= $xrate['VND'];
                    }
                    $result[$year2][$month][6] += $giavon;
                }

                // Gia von - thuc te
                if (!empty($tour['tour']['cpt'])) {

                    foreach ($tour['tour']['cpt'] as $cpt) {
                        $giavon = $cpt['qty'] * $cpt['price'];
                        if ($cpt['plusminus'] == 'minus') {
                            $giavon = -$giavon;
                        }
                        $giavon *= $xrate[$cpt['unitc']];
                        $result[$year2][$month]['6tt'] += $giavon;
                    }

                }

                // So khach BQ /tour
                $result[$year2][$month][3] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month][1] / $result[$year2][$month][0];
                // So ngay BQ /tour
                $result[$year2][$month][4] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month][2] / $result[$year2][$month][0];

                // Loi nhuan
                $result[$year2][$month][7] = $result[$year2][$month][5] - $result[$year2][$month][6];
                $result[$year2][$month]['7tt'] = $result[$year2][$month]['5tt'] - $result[$year2][$month]['6tt'];

                // Ti le lai LN/DT %
                $result[$year2][$month][17] = $result[$year2][$month][5] == 0 ? 0 : 100 * ($result[$year2][$month][7] / $result[$year2][$month][5]);
                $result[$year2][$month]['17tt'] = $result[$year2][$month]['5tt'] == 0 ? 0 : 100 * ($result[$year2][$month]['7tt'] / $result[$year2][$month]['5tt']);

                // Ti le markup DT/GV - 1 %
                $result[$year2][$month][18] = $result[$year2][$month][6] == 0 ? 0 : 100 * ($result[$year2][$month][5] / $result[$year2][$month][6] - 1);
                $result[$year2][$month]['18tt'] = $result[$year2][$month]['6tt'] == 0 ? 0 : 100 * ($result[$year2][$month]['5tt'] / $result[$year2][$month]['6tt'] - 1);

                // Doanh thu BQ /tour
                $result[$year2][$month][8] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month][5] / $result[$year2][$month][0];
                $result[$year2][$month]['8tt'] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month]['5tt'] / $result[$year2][$month][0];
                // Gia von BQ /tour
                $result[$year2][$month][9] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month][6] / $result[$year2][$month][0];
                $result[$year2][$month]['9tt'] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month]['6tt'] / $result[$year2][$month][0];
                // Loi nhuan BQ /tour
                $result[$year2][$month][10] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month][7] / $result[$year2][$month][0];
                $result[$year2][$month]['10tt'] = $result[$year2][$month][0] == 0 ? 0 : $result[$year2][$month]['7tt'] / $result[$year2][$month][0];

                // Doanh thu BQ /khach
                $result[$year2][$month][11] = $result[$year2][$month][1] == 0 ? 0 : $result[$year2][$month][5] / $result[$year2][$month][1];
                $result[$year2][$month]['11tt'] = $result[$year2][$month][1] == 0 ? 0 : $result[$year2][$month]['5tt'] / $result[$year2][$month][1];
                // Gia von BQ /khach
                $result[$year2][$month][12] = $result[$year2][$month][1] == 0 ? 0 : $result[$year2][$month][6] / $result[$year2][$month][1];
                $result[$year2][$month]['12tt'] = $result[$year2][$month][1] == 0 ? 0 : $result[$year2][$month]['6tt'] / $result[$year2][$month][1];
                // Loi nhuan BQ /khach
                $result[$year2][$month][13] = $result[$year2][$month][1] == 0 ? 0 : $result[$year2][$month][7] / $result[$year2][$month][1];
                $result[$year2][$month]['13tt'] = $result[$year2][$month][1] == 0 ? 0 : $result[$year2][$month]['7tt'] / $result[$year2][$month][1];

                // Doanh thu BQ /khach/ngay
                $result[$year2][$month][14] = $result[$year2][$month][4] == 0 ? 0 : $result[$year2][$month][11] / $result[$year2][$month][4];
                $result[$year2][$month]['14tt'] = $result[$year2][$month][4] == 0 ? 0 : $result[$year2][$month]['11tt'] / $result[$year2][$month][4];
                // Gia von BQ /khach/ngay
                $result[$year2][$month][15] = $result[$year2][$month][4] == 0 ? 0 : $result[$year2][$month][12] / $result[$year2][$month][4];
                $result[$year2][$month]['15tt'] = $result[$year2][$month][4] == 0 ? 0 : $result[$year2][$month]['12tt'] / $result[$year2][$month][4];
                // Loi nhuan BQ /khach/ngay
                $result[$year2][$month][16] = $result[$year2][$month][4] == 0 ? 0 : $result[$year2][$month][13] / $result[$year2][$month][4];
                $result[$year2][$month]['16tt'] = $result[$year2][$month][4] == 0 ? 0 : $result[$year2][$month]['13tt'] / $result[$year2][$month][4];

            }

            // Year total
            for ($i = 0; $i <= 20; $i ++) {
                for ($m = 1; $m <= 12; $m ++) {
                    $result[$year2][0][$i] += $result[$year2][$m][$i];
                    if (isset($result[$year2][$m][$i.'tt'])) {
                        $result[$year2][0][$i.'tt'] += $result[$year2][$m][$i.'tt'];
                    }
                }
            }
        }

            // \fCore::expose($hoadonNguyente);
            // \fCore::expose($thuNguyente);
            // exit;

        return $this->render('report_sale-b2c', [
            'result'=>$result,
            'view'=>$view,
            'year'=>$year,
            'year2'=>$year2,
            'xrate'=>$xrate,
            'sopax'=>$sopax,
            'songay'=>$songay,
            'hoadonNguyente'=>$hoadonNguyente,
            'thuNguyente'=>$thuNguyente,
            'search' => $search,
            'result_search' => ($search) ? $result_search : null
        ]);
    }
    public function getXrates($arr = [], $date = '', $unit_price)
    {
    	if (count($arr) == 0) {
    		return [];
    	}
    	if ($date != '') {
	    	$month = (int)date('m', strtotime($date));
	        $year = (int)date('Y', strtotime($date));    	}
    	if ($date != '' && isset($arr[$year][$month])) {
    		$arr_y_m = $arr[$year][$month];
    	} else {
    		$cur_xrate = end($arr);
    		$arr_y_m = end($cur_xrate);
    	}
    	$xrate = [];
    	foreach ($arr_y_m as $unit => $rate) {
    		if ($unit == $unit_price) {
    			$xrate[$unit_price] = 1;
    		} else {
    			$xrate[$unit] = $arr_y_m[$unit]/$arr_y_m[$unit_price];
    		}
    	}
    	return $xrate;
    }


    public function actionExport_customer_vip()
    {
    	$user_bookings_counts = Yii::$app->db->createCommand('
						SELECT at_booking_user.user_id, COUNT(*) AS cnt
						FROM at_booking_user INNER JOIN at_bookings ON  at_booking_user.booking_id = at_bookings.id
											INNER JOIN at_ct ON  at_bookings.product_id = at_ct.id
											INNER JOIN at_tour_stats ON  at_ct.id = at_tour_stats.tour_id
						WHERE YEAR(at_ct.day_from) >= 2014
						GROUP BY at_booking_user.user_id
						HAVING cnt >=3
						')->queryAll();
    	$arr_users = [];
    	foreach ($user_bookings_counts as $user) {
    		$arr_users[$user['user_id']] = $user['user_id'];
    	}

    	$referral_cases = Yii::$app->db->createCommand('
			SELECT at_referrals.user_id, COUNT(*) AS cnt
			FROM at_referrals INNER JOIN at_cases ON at_referrals.case_id = at_cases.id
			WHERE YEAR(at_referrals.created_at) >= 2014 AND at_referrals.user_id > 0 AND deal_status = "won"
			GROUP BY at_referrals.user_id
			HAVING cnt >= 2 AND cnt <= 4
    		')->queryAll();
    	foreach ($referral_cases as $referral) {
    		$arr_users[$referral['user_id']] = $referral['user_id'];
    	}

    	$persons = Person::find()
    		->with([
    			'metas' => function($q) {
    				return $q->andWhere('name = "address" OR name= "tel" OR name="mobile"');
    			}
    		])
    		->where(['id' => $arr_users])->asArray()->all();
    	// var_dump(count($arr_users));die;

    	//export to excel
    	$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Name')
		            ->setCellValue('B1', 'Address')
		            ->setCellValue('C1', 'Phone');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$k = 2;

		foreach ($persons as $person) {
			$address = [];
			$tel = [];
			foreach ($person['metas'] as $meta) {
				if ($meta['name'] == 'address') {
					$address[] = $meta['value'];
				} else {
					$tel[] = $meta['value'];
				}
			}
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$k, $person['name'])
		                ->setCellValue('B'.$k, implode(' | ', $address))
		                ->setCellValue('C'.$k, implode(' | ', $tel));
			$k++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setTitle('Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('MyExcel.xlsx');
		die('okkkkkkk');
    }
    public function actionExport_customer_ambass()
    {
    	$arr_users = [];
    	$referral_cases = Yii::$app->db->createCommand('
			SELECT at_referrals.user_id, COUNT(*) AS cnt
			FROM at_referrals INNER JOIN at_cases ON at_referrals.case_id = at_cases.id
			WHERE YEAR(at_referrals.created_at) >= 2014 AND at_referrals.user_id > 0 AND deal_status = "won"
			GROUP BY at_referrals.user_id
			HAVING cnt >=5
    		')->queryAll();
    	foreach ($referral_cases as $referral) {
    		$arr_users[$referral['user_id']] = $referral['user_id'];
    	}

    	$persons = Person::find()
    		->with([
    			'metas' => function($q) {
    				return $q->andWhere('name = "address" OR name= "tel" OR name="mobile"');
    			}
    		])
    		->where(['id' => $arr_users])->asArray()->all();
    	// var_dump(count($arr_users));die;

    	//export to excel
    	$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Name')
		            ->setCellValue('B1', 'Address')
		            ->setCellValue('C1', 'Phone');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$k = 2;

		foreach ($persons as $person) {
			$address = [];
			$tel = [];
			foreach ($person['metas'] as $meta) {
				if ($meta['name'] == 'address') {
					$address[] = $meta['value'];
				} else {
					$tel[] = $meta['value'];
				}
			}
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$k, $person['name'])
		                ->setCellValue('B'.$k, implode(' | ', $address))
		                ->setCellValue('C'.$k, implode(' | ', $tel));
			$k++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setTitle('Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('MyExcel.xlsx');
		die('okkkkkkk');
    }
    public function actionExport_customers()
    {
    	$user_bookings_counts = Yii::$app->db->createCommand('
						SELECT at_booking_user.user_id, COUNT(*) AS cnt
						FROM at_booking_user INNER JOIN at_bookings ON  at_booking_user.booking_id = at_bookings.id
											INNER JOIN at_ct ON  at_bookings.product_id = at_ct.id
											INNER JOIN at_tour_stats ON  at_ct.id = at_tour_stats.tour_id
						WHERE YEAR(at_ct.day_from) >= 2014
						GROUP BY at_booking_user.user_id
						')->queryAll();
    	$arr_users = [];
    	foreach ($user_bookings_counts as $user) {
    		$arr_users[$user['user_id']] = $user['user_id'];
    	}

    	$persons = Person::find()
    		->with([
    			'metas' => function($q) {
    				return $q->andWhere('name= "email"');
    			}
    		])
    		->where(['id' => $arr_users])->asArray()->all();
    	// var_dump($user_bookings_counts);die;

    	//export to excel
    	$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Name')
		            ->setCellValue('B1', 'Email');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$k = 2;

		foreach ($persons as $person) {
			$emails = [];
			foreach ($person['metas'] as $meta) {
				if ($meta['name'] == 'email') {
					$emails[] = $meta['value'];
				}
			}
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$k, $person['name'])
		                ->setCellValue('B'.$k, implode(' | ', $emails));
			$k++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setTitle('Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('MyExcel.xlsx');
		die('okkkkkkk');
    }

    public function actionTranslate($id = 0, $lang = '')
    {
    	if (Yii::$app->request->isAjax) {
    		$day_translate = Yii::$app->db->createCommand('
				SELECT * FROM translate WHERE day_id=:day_id AND lang=:lang ',
				[
					':day_id' => $_POST['day_id'],
					':lang' => $_POST['lang']
				])->queryOne();
    		if (!$day_translate) {
    			$query = Yii::$app->db->createCommand('
				INSERT INTO translate (day_id, lang, title, content)
					VALUES(:day_id, :lang, :title, :content)',
				[
					':day_id' => $_POST['day_id'],
					':lang' => $_POST['lang'],
					':title' => $_POST['title'],
					':content' => $_POST['content']
				])->execute();
				if (!$query) {
					return json_encode(['err' => 'error!']);
				}
				return json_encode(['success' => 'success!']);
    		} else {
    			$query = Yii::$app->db->createCommand('
					UPDATE translate SET title=:title, content=:content
					WHERE day_id=:day_id AND lang=:lang',
				[
					':day_id' => $_POST['day_id'],
					':lang' => $_POST['lang'],
					':title' => $_POST['title'],
					':content' => $_POST['content']
				])->execute();
				if (!$query) {
					return json_encode(['err' => 'error!']);
				}
				return json_encode(['success' => 'success!']);
    		}
    	}
    	if ($lang == '') {
    		$lang = Yii::$app->language;
    	}
    	$theTour = Product::find()
    		->with([
    			'days'
    		])
    		->where(['id' => $id])->asArray()->one();
    	if (!$theTour) {
    		throw new HttpException(403, "The tour not found");
    	}
        if ($theTour['day_ids']) {
            $dayIdList = explode(',', $theTour['day_ids']);
            $start_date = $theTour['day_from'];
            $cnt = 0;
            foreach ($dayIdList as $day_id) {
                foreach ($theTour['days'] as $day) {
                    if ($day['id'] == $day_id) {
                        $cnt ++;
                        $arr_day[$day_id] =  date('D d/m/Y', strtotime('+'.($cnt - 1).' days', strtotime($start_date)));
                    }
                }
            }
        }
    	$days_translates = Translate::find()->where(['day_id' => $dayIdList, 'lang' => $lang])->asArray()->all();
    	// var_dump($days_translates);die;

        return $this->render('translate', [
        	'days_of_tour' => $arr_day,
        	'theTour' => $theTour,
        	'days_translates' => $days_translates,
        	'lang' => $lang
        ]);
    }

    public function actionZ_form($id = 0, $tour_id = 0)
    {
        $arr_day = [];
    	$theTour = Product::find()
    		->with([
    			'days'
    		])
    		->where(['id' => $tour_id])->asArray()->one();
    	if (!$theTour) {
    		throw new HttpException(403, "The tour not found");
    	}
    	$compair_dt = [];
    	//get list days
        if ($theTour['day_ids']) {
            $dayIdList = explode(',', $theTour['day_ids']);
            $start_date = $theTour['day_from'];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $day_id) {
                foreach ($theTour['days'] as $day) {
                    if ($day['id'] == $day_id) {
                        $cnt ++;

                        $arr_day[date('Y-m-d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt .': '. date('D d/m/Y', strtotime('+'.($cnt - 1).' days', strtotime($start_date))) .' - '.$day['name'];

                        $compair_dt[date('Y-m-d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] = $cnt;
                    }
                }
            }
        }
        if ($id != 0) {
			$item = Yii::$app->db->createCommand('
						SELECT * FROM op_zones WHERE id=:id',
						[
							':id' => $id
						])->queryOne();
			if (!$item) {
				throw new HttpException(403, "Item not found");
			}

			$content_str = str_replace(["Bắc VN", "Trung VN", "Nam VN", "Lào", "Cambodia", "Myanmar", "Thái lan"],
				["zbac_vn", "ztrung_vn", "znam_vn", "zlao", "zcambodia", "zmyanmar", "zthailan"],
				str_replace(['[', ']'], ['',''], $item['content_z']));
			$content_arr = explode(';', $content_str);
			$form_content = [];
			foreach ($content_arr as $zone) {
				$arr_zone = explode(':', $zone);
				if (count($arr_zone) == 1) continue;
				$arr_dt_zone = explode(',', $arr_zone[1]);
				if (count($arr_dt_zone) == 1) {
					$form_content[$arr_zone[0]] = $compair_dt[trim($arr_dt_zone[0])];
				} else {
					$arr_dt = [];
					foreach ($arr_dt_zone as $dt) {
						$arr_dt[] = $compair_dt[trim($dt)];
					}
					$form_content[$arr_zone[0]] = implode(',', $arr_dt);
				}
			}
    	}

       	if (isset($_POST['submit'])) {
       		if(isset($_POST['data'])) {
       			$data = ArrayHelper::toArray(json_decode($_POST['data']));
       			$content = [];
       			$str_content = "[".implode(';', $content)."]";

       			if ($id == 0) {
       				Yii::$app->db->createCommand('
									INSERT INTO op_zones (
										created_dt, created_by, updated_dt, updated_by, tour_id, content_z
									)
									values(:created_dt, :created_by, :updated_dt, :updated_by, :tour_id, :content_z)',
									[
										':created_dt' => NOW,
										':created_by' => USER_ID,
										':updated_dt' => NOW,
										':updated_by' => USER_ID,
										':tour_id' => $theTour['id'],
										':content_z' => $str_content
									])->execute();
       			} else {
       				Yii::$app->db->createCommand('
									UPDATE op_zones SET
										updated_dt=:updated_dt,
										updated_by=:updated_by,
										content_z=:content_z
									WHERE id=:id',
									[
										':id' => $id,
										':updated_dt' => NOW,
										':updated_by' => USER_ID,
										':content_z' => $str_content
									])->execute();
       			}
       		}
       	}
    	return $this->render('dh_tour', [
    		'days_in_tour' => $arr_day,
    		'form_content' => isset($form_content)? $form_content: null,
    		'compair_dt' => $compair_dt
    	]);
    }


    public function actionOp_form($id = 0, $tour_id = 0)
    {
        $arr_days = [];
    	$theTour = Product::find()
    		->with([
    			'days'
    		])
    		->where(['id' => $tour_id])->asArray()->one();
    	if (!$theTour) {
    		throw new HttpException(403, "The tour not found");
    	}
    	$compair_dt = [];
    	//get list days
        if ($theTour['day_ids']) {
            $dayIdList = explode(',', $theTour['day_ids']);
            $start_date = $theTour['day_from'];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $day_id) {
                foreach ($theTour['days'] as $day) {
                    if ($day['id'] == $day_id) {
                        $cnt ++;

                        $arr_days[date('Y-m-d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt .': '. date('D d/m/Y', strtotime('+'.($cnt - 1).' days', strtotime($start_date))) .' - '.$day['name'];

                        $compair_dt[date('Y-m-d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] = $cnt;
                    }
                }
            }
        }
        if ($id != 0) {
			$item = Yii::$app->db->createCommand('
						SELECT * FROM op_zones WHERE id=:id',
						[
							':id' => $id
						])->queryOne();
			if (!$item) {
				throw new HttpException(403, "Item not found");
			}

			$content_str = str_replace(['[', ']'], ['',''], $item['content_op']);
			$content_arr = explode(';', $content_str);
			$form_content = [];
			foreach ($content_arr as $op) {
				$arr_op = explode(':', $op);
				if (count($arr_op) == 1) continue;
				$arr_dt_op = explode(',', $arr_op[1]);
				if (count($arr_dt_op) == 1) {
					$form_content[$arr_op[0]] = $compair_dt[trim($arr_dt_op[0])];
				} else {
					$arr_dt = [];
					foreach ($arr_dt_op as $dt) {
						$arr_dt[] = $compair_dt[trim($dt)];
					}
					$form_content[$arr_op[0]] = implode(',', $arr_dt);
				}
			}
    	}
        // Danh sach dieu hanh
        $operators = [1 => "Quynh Giang", 2 => "Nguyễn Đức Anh", 3 => "Nguyễn Thị Khuê"];

       	if (isset($_POST['submit'])) {
       		if(isset($_POST['data'])) {
       			$data = ArrayHelper::toArray(json_decode($_POST['data']));
       			$content = [];
       			if (count($data) > 0) {
       				foreach ($data as $user_id => $arr_day) {
       					if (!is_array($arr_day)) {
       						$arr_day = explode(',', $arr_day);
       					}
	       				$arrr_dt = [];
	       				foreach ($arr_day as $num) {
	       					$arrr_dt[] = array_search($num, $compair_dt);
	       				}
	       				$content[] = $user_id. ':'.implode(',', $arrr_dt);
	       			}
       			}
       			if (count($content) == 0) {
       				var_dump($_POST);die;
       			}
       			$str_content = "[".implode(';', $content)."]";
       			if ($id == 0) {
       				Yii::$app->db->createCommand('
									INSERT INTO op_zones (
										created_dt, created_by, updated_dt, updated_by, tour_id, content_op
									)
									values(:created_dt, :created_by, :updated_dt, :updated_by, :tour_id, :content_op)',
									[
										':created_dt' => NOW,
										':created_by' => USER_ID,
										':updated_dt' => NOW,
										':updated_by' => USER_ID,
										':tour_id' => $theTour['id'],
										':content_op' => $str_content
									])->execute();
       			} else {
       				Yii::$app->db->createCommand('
									UPDATE op_zones SET
										updated_dt=:updated_dt,
										updated_by=:updated_by,
										content_op=:content_op
									WHERE id=:id',
									[
										':id' => $id,
										':updated_dt' => NOW,
										':updated_by' => USER_ID,
										':content_op' => $str_content
									])->execute();
       			}
       		}
       	}
    	return $this->render('dh_form', [
    		'days_in_tour' => $arr_days,
    		'form_content' => isset($form_content)? $form_content: null,
    		'compair_dt' => $compair_dt,
    		'operators' => $operators
    	]);
    }
    public function actionReport_hotel($stype_zone = 0, $venue_id = 0, $tour_code = '', $date_range = '', $stype_count = 'all')
    {
    	$query = Cpt::find()
    			->with('tour')
    			->where("YEAR(created_at) >= 2016");

    	if ($stype_zone > 0) {
    		$theVenues = Venue::find()->select('id')->where(['destination_id' => $stype_zone])->indexBy('id')->asArray()->all();
    		$venue_ids = array_keys($theVenues);
    		$query->andWhere(['venue_id' => $venue_ids]);
    	}
    	if ($venue_id > 0) {
    		$query->andWhere(['venue_id' => $venue_id]);
    	}
    	if ($date_range != '') {
    		$f_dt = '';
    		$l_dt = '';
    		if (strlen($date_range) == 4) {
    			$f_dt = date('Y-m-d', strtotime($date_range .'/1/1'));
    			$l_dt = date('Y-m-d', strtotime($date_range .'/12/31'));
    		} else {
    			$arr_dt = explode('-', $date_range);
    			if (count($arr_dt) == 2) {
	    			$f_dt = date('Y-m-d', strtotime($arr_dt[0]));
	    			$l_dt = date('Y-m-d', strtotime($arr_dt[1]));
    			}
    		}
    		$query->andWhere('DATE(dvtour_day) BETWEEN :f_dt AND :l_dt', [':f_dt' => $f_dt, ':l_dt' => $l_dt]);
    	}
    	if ($stype_count != 'all') {
    		$query->andWhere('stype =:stype_count', [':stype_count' => $stype_count]);
    	}
    	$arr_data = [];

    	$list = $query->asArray()->all();
    	foreach ($list as $cpt) {
    		$code = $cpt['tour']['code'];
    		if ($tour_code != '') {
    			if (strtolower($tour_code) != strtolower($code)) {
    				continue;
    			}
    		}

    		// init
    		if (!isset($arr_data[$code])) {
    			$arr_data[$code] = [];
    		}
    		if (!isset($arr_data[$code]['room'])) {
    			$arr_data[$code]['room'] = 0;
    		}
    		if (!isset($arr_data[$code]['pax'])) {
    			$arr_data[$code]['pax'] = 0;
    		}
    		if (!isset($arr_data[$code]['currency'])) {
    			$arr_data[$code]['currency'] = [];
    		}
    		if (!isset($arr_data[$code]['currency'][$cpt['unitc']])) {
    			$arr_data[$code]['currency'][$cpt['unitc']] = 0;
    		}//end init



    		// $arr_data[$code]['room'] += $cpt['so_phong'];
    		if (strpos($cpt['unit'], 'phòng') !== false) {
    			$arr_data[$code]['room'] += $cpt['qty'];
    		}
    		// $arr_data[$code]['pax'] += $cpt['so_pax'];
    		if (strpos($cpt['unit'], 'pax') !== false || strpos($cpt['unit'], 'khach')) {
    			$arr_data[$code]['pax'] += $cpt['qty'];
    		}
    		$arr_data[$code]['currency'][$cpt['unitc']] += $cpt['price'];
    	}

	    $venues = Venue::find()
    			->select(['venues.id', 'name', 'name_vi','destination_id'])
    			->innerJoinWith('destination')
    			->where('stype = "hotel" OR stype = "home"')
    			->indexBy('id')->asArray()->all();
    	$data_venue = [];
    	$data_zones = [];
    	foreach ($venues as $venue) {
    		$data_venue[$venue['id']] = $venue['name'];
    		$data_zones[$venue['destination_id']] = $venue['destination']['name_vi'];
    	}
    	return $this->render('report_hotel',[
    		'data_venue' => $data_venue,
    		'data_zones' => $data_zones,
    		'arr_data' => $arr_data,
    		'stype_zone' => $stype_zone,
    		'stype_count' => $stype_count,
    		'date_range' => $date_range,
    		'tour_code' => $tour_code,
    		'venue_id' => $venue_id

    	]);
    }

















































}
?>