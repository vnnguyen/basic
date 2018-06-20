<?php

namespace app\controllers;

use app\models\B2cSellerDailyTasksEditForm;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\Response;

use common\models\Booking;
use common\models\Kbpost;
use common\models\Kblist;
use common\models\Kbbook;
use common\models\Kase;
use common\models\Meta;
use common\models\User2;

use common\models\Company;
use common\models\Cpt;
use common\models\Dvt;
use common\models\Person;
use common\models\Venue;
use common\models\ReservationForm;
use common\models\Diemlx;
use common\models\Invoice;
use common\models\Product;
use common\models\Event;
use common\models\Task;
use common\models\Tour;
use app\models\SukienPhonghopForm;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ToolController extends MyController
{

    // All tools
    public function actionIndex()
    {
        return $this->render('tools');
    }

    /**
     * Mass import pax list (Duc Anh)
     * 171023
     */
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
                $line = str_replace('&nbsp;', '', trim(htmlentities($line)));
                if ($line == '') {
                    continue;                }
                $cells = explode(']|[', $line);
                if (count($cells) > 0) {
                    $results[] = $cells;
                }
            }
        }
        // var_dump($results);exit;

        if (isset($_FILES["import"]) && $_FILES["import"] != '' && $_FILES["import"]["tmp_name"] != '') {
            $results = false;

            $tmp_name = $_FILES["import"]["tmp_name"];
            $spreadsheet = IOFactory::load($tmp_name);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            foreach ($sheetData as $row => $line) {
                foreach ($line as $cell) {
                    $results[$row][] = trim(htmlentities($cell));
                }
            }
        }
        if ($results) {
            Yii::$app->session->set('results', $results);
        }
        $arr_results = false;
        if (isset($_POST['order'])) {
            $results = Yii::$app->session->get('results');
            $remove_first_row = false;
            if (isset($_POST['check_row_1'])) {
                $remove_first_row = true;
            }
            $order_POST = $_POST['order'];
            $arr_results = [];
            foreach ($results as $i => $row) {
                if ($remove_first_row && $i == 1) {
                    continue;
                }
                $arr = [];
                foreach ($row as $k => $v) {
                    $v = str_replace('&nbsp;', '', $v);
                    if ($order_POST[$k] == 'dob' || $order_POST[$k] == 'passport_ep') {
                        if (strpos($v,'-') == false && strpos($v, '/') == false) {
                            continue 2;
                        }
                        $tmp_v = str_replace('/', '-', $v);
                        $arr_dt = explode('-', $tmp_v);
                        if (count($arr_dt) != 3) {
                            die('not ok');
                        }
                        $dt = $arr_dt[2].'-'.$arr_dt[1].'-'.$arr_dt[0];
                        $v = date('Y-m-d', strtotime($dt));
                    }
                    $arr[$order_POST[$k]] = $v;
                }

                $arr_results[$i] = $arr;
            }
            // var_dump($arr_results);die;
        }

        // Ghi csdl
        if (isset($_POST['ok']) && is_array($_POST['ok'])) {
        }

        return $this->render('tool_import-pax-list-from-excel', [
            'theTour'=>$theTour,
            'results'=>$results,
            'arr_results' => $arr_results
        ]);
    }

    /**
     * B2C sellers daily activity note
     */
    public function actionB2cSellerDailyTasks($action = '', $seller = 0, $year = 0, $month = 0, $date = '')
    {
        if ($year == 0) {
            $year = date('Y');
        }
        if ($month == 0) {
            $month = date('n');
        }

        if ($action == 'delete' && $seller != 0 && $date != '') {
            $theForm = new B2cSellerDailyTasksEditForm;
            $meta = Meta::find()
                ->where(['rtype'=>'b2c-seller-daily-tasks', 'rid'=>$seller, 'name'=>date('Y-m-d', strtotime($date))])
                ->one();
            if ($meta) {
                $meta->delete();
                return $this->redirect('?year='.$year.'&month='.$month.'&seller='.$seller);
            }
            throw new HttpException(404, 'Not found.');
        }

        if ($action == 'edit' && $seller != 0 && $date != '') {
            $theForm = new B2cSellerDailyTasksEditForm;
            $meta = Meta::find()
                ->where(['rtype'=>'b2c-seller-daily-tasks', 'rid'=>$seller, 'name'=>date('Y-m-d', strtotime($date))])
                ->one();
            if ($meta) {
                $x = explode(';|', $meta['value']);
                $theForm['c1'] = $x[1] ?? '';
                $theForm['c2'] = $x[2] ?? '';
                $theForm['c3'] = $x[3] ?? '';
            }
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                if (!$meta) {
                    $meta = new Meta;
                    $meta->created_dt = NOW;
                    $meta->created_by = USER_ID;
                    $meta->rtype = 'b2c-seller-daily-tasks';
                    $meta->rid = $seller;
                }
                $meta->updated_dt = NOW;
                $meta->updated_by = USER_ID;
                $meta->name = date('Y-m-d', strtotime($date));
                $meta->value = ';|'.$theForm['c1'].';|'.$theForm['c2'].';|'.$theForm['c3'];
                $meta->save(false);
                return $this->redirect('?year='.$year.'&month='.$month.'&seller='.$seller);

            }
            return $this->render('tool_b2c-seller-daily-tasks__edit', [
                'theForm'=>$theForm,
                'year'=>$year,
                'month'=>$month,
                'date'=>$date,
                'seller'=>$seller,
            ]);
        }


        // result[year][month][day][seller][1|2|3|4]
        $result = [];
        // Ho so duoc giao trong ngay
        $cases = Kase::find()
            ->select(['id', 'name', 'owner_id', 'ao', 'status', 'deal_status'])
            ->where('YEAR(ao)=:y AND MONTH(ao)=:m', [':y'=>$year, ':m'=>$month])
            ->andWhere(['is_b2b'=>'no'])
            ->asArray()
            ->all();

        // Seller list
        $sellerIdList = \yii\helpers\ArrayHelper::getColumn($cases, 'owner_id');
        $sellers = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['id'=>$sellerIdList])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $tasks = Task::find()
            ->select(['at_tasks.id', 'completed_dt', 'description', 'due_dt', 'user_id', 'rtype', 'rid'])
            ->innerJoinWith('assignees')
            ->with([
                'related'=>function($q) {
                    return $q->select(['id', 'name']);
                }
            ])
            ->where(['user_id'=>$sellerIdList]) // for future tasks
            ->andWhere('completed_dt=0 AND YEAR(due_dt)=:y AND MONTH(due_dt)=:m', [':y'=>$year, ':m'=>$month])
            ->orderBy('completed_dt')
            ->asArray()
            ->all();

        $metas = Meta::find()
            ->select(['rid', 'name', 'value'])
            ->where(['rtype'=>'b2c-seller-daily-tasks', 'rid'=>$seller == 0 ? $sellerIdList : $seller])
            ->asArray()
            ->all();

        return $this->render('tool_b2c-seller-daily-tasks', [
            'result'=>$result,
            'year'=>$year,
            'month'=>$month,
            'action'=>$action,
            'seller'=>$seller,

            'cases'=>$cases,
            'tasks'=>$tasks,
            'sellers'=>$sellers,
            'metas'=>$metas,
        ]);
    }

    // 
    public function actionGheTreEm($action = '', $start = '', $end = '')
    {
        if ($action == 'load') {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $return = [];
            $sql = 'SELECT * FROM at_avails WHERE stype="ghetreem" ';
            $ghex = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($ghex as $ghe) {
                $return[] = array(
                    'title'=>$ghe['rtype'].' Ghế '.$ghe['rid'],
                    'start'=>$ghe['from_dt'],
                    'end'=>$ghe['until_dt'],
                    // 'url'=>'https://www.google.com/',
                    // 'color'=>'#f60',
                    'className'=>'ghe'.$ghe['rtype'],
                    'editable'=>false,
                );
            }
            return $return;
        }
        $theForm = new \app\models\DkSdGheTreEmForm;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $theTour = Product::find()
                ->select(['id'])
                ->where(['op_status'=>'op', 'op_code'=>$theForm->tour])
                ->asArray()
                ->one();
            if (!$theTour) {
                // throw new HttpException(404, 'Tour not found.');
            }
            $sql = 'INSERT INTO at_avails (created_at, created_by, stype, rtype, rid, from_dt, until_dt, note) VALUES (:cd, :cb, :st, :rt, :ri, :fd, :ud, :no)';
            $date = explode(' - ', $theForm->tu);
            Yii::$app->db->createCommand($sql, [
                ':cd'=>NOW,
                ':cb'=>USER_ID,
                ':st'=>'ghetreem',
                ':ri'=>$theForm->ghe,
                ':rt'=>$theForm->tour,
                ':fd'=>$date[0],
                ':ud'=>$date[1],
                ':no'=>$theForm->note,
            ])->execute();
            return $this->redirect('?');
        }
        return $this->render('tool_ghe-tre-em', [
            'theForm'=>$theForm,
        ]);
    }

    // Old Imsprint (from Amica_FR)
    public function actionImsprint($id = 0, $code = 0)
    {
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }
        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        //$handle = fopen('http://www.etourhome.com/products/x/' . $id, 'r');
        //$handle = fopen('http://www.w3schools.com/php/func_filesystem_fopen.asp', 'r');
        $data = stream_get_contents($handle);
        //$data = Security::decrypt($rawData, $key);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);
        
        
      //  var_dump($data['days'][0]['body']);exit;
       // echo "<pre>";
       // print_r($data['days'][0]['body']);
       // exit;

        return $this->renderPartial('tool_imsprint', [
            'theProduct' => $theProduct
        ]);
    }

    public function actionImsprintB2b($id = 0, $code = 0)
    {
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }

        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        $data = stream_get_contents($handle);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);


        return $this->renderPartial('//page2016/imsPrintB2b', [
                    'theProduct' => $theProduct
        ]);
    }

    public function actionImsprintB2bEn($id = 0, $code = 0)
    {
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }

        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        $data = stream_get_contents($handle);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);
       // print_r($data['conditions']);exit;
       //var_dump($data);exit;
        return $this->renderPartial('//page2016/imsPrintB2b_en', [
                    'theProduct' => $theProduct
        ]);
    }

    function vn_str_filter($str) {

        $unicode = array(
            'a' => 'á|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|�?|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|�?|õ|�?|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|�?|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => '�?|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => '�?',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => '�?|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|�?|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => '�?|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        return $str;
    }

    // Lich su dung phong hop
    public function actionPhonghop($action = 'list', $date = '', $at = 'hn', $id = 0)
    {
        if ($action == 'add') {
            if (!in_array(USER_ID, [1, 29296, 25457, 30554])) {
                // Khang Ha, K Ngoc, Minh
                return $this->redirect('?action=reg');
            }

            $theForm = new SukienPhonghopForm;
            $theForm->start_date = date('Y-m-d', strtotime('+1 day'));
            $theForm->start_time = '10:00';
            $theForm->status = 'draft';

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                $theEvent = new Event;
                $theEvent->created_dt = NOW;
                $theEvent->created_by = USER_ID;
                $theEvent->updated_dt = NOW;
                $theEvent->updated_by = USER_ID;
                $theEvent->status = $theForm->status;
                $theEvent->stype = 'phonghop';
                $theEvent->name = $theForm->name;
                $theEvent->info = $theForm->info;
                $theEvent->venue = $theForm->venue;
                $theEvent->attendee_count = $theForm->attendee_count;
                $theEvent->from_dt = $theForm->start_date.' '.$theForm->start_time;
                $theEvent->until_dt = date('Y-m-d H:i:s', strtotime(' + '.$theForm->mins.' minutes', strtotime($theEvent->from_dt)));
                $theEvent->mins = $theForm->mins;
                $theEvent->save(false);
                return $this->redirect('?date='.$theForm->start_date);
            }

            return $this->render('tool_phonghop-edit', [
                'theForm'=>$theForm,
                'action'=>$action,
                ]);
        }

        // Somebody registers
        if ($action == 'reg') {
            $theForm = new SukienPhonghopForm;
            $theForm->start_date = date('Y-m-d', strtotime('+1 day'));
            $theForm->start_time = '10:00';
            $theForm->status = 'draft';

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                $theEvent = new Event;
                $theEvent->created_dt = NOW;
                $theEvent->created_by = USER_ID;
                $theEvent->updated_dt = NOW;
                $theEvent->updated_by = USER_ID;
                $theEvent->status = 'draft';
                $theEvent->stype = 'phonghop';
                $theEvent->name = $theForm->name;
                $theEvent->info = $theForm->info;
                $theEvent->venue = $theForm->venue;
                $theEvent->attendee_count = $theForm->attendee_count;
                $theEvent->from_dt = $theForm->start_date.' '.$theForm->start_time;
                $theEvent->until_dt = date('Y-m-d H:i:s', strtotime(' + '.$theForm->mins.' minutes', strtotime($theEvent->from_dt)));
                $theEvent->mins = $theForm->mins;
                $theEvent->save(false);
                // Email KHang Ha
                $args = [
                    ['from', Yii::$app->user->identity->email, Yii::$app->user->identity->nickname.' on IMS'],
                    ['to', 'khang.ha@amica-travel.com', 'Khang Hạ', 'NV.'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];

                $this->mgIt(
                    'Đăng ký sử dụng phòng họp: '.$theForm->name.' @'.date('j/n/Y H:i', strtotime($theEvent->from_dt)),
                    '//mg/tool_phonghop',
                    [
                        'theEvent'=>$theEvent,
                    ],
                    $args
                );

                return $this->redirect('?date='.$theForm->start_date);
            }

            return $this->render('tool_phonghop-edit', [
                'theForm'=>$theForm,
                'action'=>$action,
                ]);
        }

        if ($action == 'edit') {
            $theEvent = Event::find()
                ->where(['id'=>$id, 'stype'=>'phonghop'])
                ->one();
            if (!$theEvent) {
                throw new HttpException(404, 'Not found.');
            }

            if (!in_array(USER_ID, [1, 29296, $theEvent['created_by']])) {
                // Khang Ha, K Ngoc, Minh
                return $this->redirect('?action=list');
            }

            $theForm = new SukienPhonghopForm;
            $theForm->status = $theEvent->status;
            $theForm->name = $theEvent->name;
            $theForm->info = $theEvent->info;
            $theForm->venue = $theEvent->venue;
            $theForm->attendee_count = $theEvent->attendee_count;
            $theForm->start_date = substr($theEvent->from_dt, 0, 10);
            $theForm->start_time = substr($theEvent->from_dt, 11, 5);
            $theForm->mins = $theEvent->mins;
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                $theEvent->updated_dt = NOW;
                $theEvent->updated_by = USER_ID;
                $theEvent->status = $theForm->status;
                $theEvent->name = $theForm->name;
                $theEvent->info = $theForm->info;
                $theEvent->venue = $theForm->venue;
                $theEvent->attendee_count = $theForm->attendee_count;
                $theEvent->from_dt = $theForm->start_date.' '.$theForm->start_time;
                $theEvent->until_dt = date('Y-m-d H:i:s', strtotime(' + '.$theForm->mins.' minutes', strtotime($theEvent->from_dt)));
                $theEvent->mins = $theForm->mins;
                $theEvent->save(false);
                return $this->redirect('?date='.$theForm->start_date);
            }
            return $this->render('tool_phonghop-edit', [
                'theForm'=>$theForm,
                'action'=>$action,
                ]);
        }

        if ($action == 'delete') {
            $theEvent = Event::find()
                ->where(['id'=>$id, 'stype'=>'phonghop'])
                ->one();
            if (!$theEvent) {
                throw new HttpException(404, 'Not found.');
            }
            if (!in_array(USER_ID, [1, $theEvent->created_by, $theEvent->updated_by])) {
                throw new HttpException(403, 'Access denied.');
            }
            $theEvent->delete();
            return $this->redirect('?');
        }

        if ($action == 'list') {
            if (strlen($date) != 10) {
                $date = date('Y-m-d');
            }

        // if date is Sunday then we have to change to last week as code will give next Monday instead
        if (date('w', strtotime($date)) == 0) {
            $date = date('Y-m-d', strtotime('-1 days', strtotime($date)));
        }

        $thisWeek = date('Y-m-d', strtotime('this week', strtotime($date)));
        $prevWeek = date('Y-m-d', strtotime('-7 days', strtotime($thisWeek)));
        $nextWeek = date('Y-m-d', strtotime('+7 days', strtotime($thisWeek)));

        if ($at == 'hn') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND SUBSTRING(tk.description,1,5)!="AC SG" AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'sg') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC SG ", tk.description)=1 OR tk.description="AC SG") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'lp') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC LP ", tk.description)=1 OR tk.description="AC LP") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } else {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        }

        $tourIdList = [];
        foreach ($theTours as $li) {
            $tourIdList[] = $li['id'];
        }

        $ctIdList = [];
        foreach ($theTours as $li) {
            $ctIdList[] = $li['ct_id'];
        }

        $dayIdList = [];

        foreach ($theTours as $li) {
            $dt1 = new \DateTime($li['day_from']);
            $dt2 = new \DateTime($li['task_date']);
            $interval = $dt1->diff($dt2)->format('%a');
            $dayIdArray = explode(',', $li['day_ids']);
            if (isset($dayIdArray[abs($interval)])) {
                $dayIdList[] = $dayIdArray[abs($interval)]; 
            }
        }

        if (empty($dayIdList)) {
            $theDays = [];
        } else {
            $theDays = Yii::$app->db->createCommand('SELECT id, rid, name FROM at_days WHERE id IN ('.implode(',', $dayIdList).')')
                ->queryAll();
        }

        // Tim cac nhiem vu tour trong khoang ngay
        if ($at == 'hn') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC ", description)=1 OR description="AC") AND SUBSTRING(description,1,5)!="AC SG" AND SUBSTRING(description,1,5)!="AC LP" AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } elseif ($at == 'sg') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC SG ", description)=1 OR description="AC SG") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } elseif ($at == 'lp') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC LP ", description)=1 OR description="AC LP") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } else {
            // All locs
            $taskQuery = Task::find()
                ->where('(LOCATE("AC ", description)=1 OR description="AC") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        }

        $theTasks = $taskQuery
            ->andWhere(['rtype'=>'tour'])
            ->with([
                'tour'=>function($q){
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'tour.product'=>function($q){
                    return $q->select(['id', 'day_from', 'pax', 'day_count']);
                },
                'tour.product.pax'=>function($q){
                    return $q->select(['id', 'tour_id', 'name', 'pp_birthdate']);
                },
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'phone']);
                },
                'tour.operators'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'phone']);
                },
                'tour.product.guides',
                'tour.product.bookings'=>function($q){
                    return $q->select(['id', 'product_id', 'created_by', 'pax']);
                },
                'tour.product.bookings.createdBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tour.product.bookings.people'=>function($q){
                    return $q->select(['id', 'name', 'bday', 'bmonth']);
                },
                'tour.product.bookings.people.bookings'=>function($q){
                    return $q->select(['id', 'status'])->where(['status'=>'won']);
                },
                'tour.product.days'=>function($q) use ($dayIdList) {
                    return $q->select(['id', 'name', 'rid'])->where(['id'=>$dayIdList]);
                },
                ])
            ->orderBy('due_dt')
            ->asArray()
            ->all();

        $theEvents = Event::find()
            ->where(['stype'=>'phonghop'])
            ->andWhere('from_dt>=:thisWeek', [':thisWeek'=>$thisWeek])
            ->andWhere('until_dt<:nextWeek', [':nextWeek'=>$nextWeek])
            ->andWhere('SUBSTRING(venue, 1, 2)'.(!in_array($at, ['hn', 'sg', 'lp']) ? '!' : '').'=:at', [':at'=>$at])
            ->with([
                'createdBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                ])
            ->asArray()
            ->all();

        return $this->render('tool_phonghop-list', [
            'theTasks'=>$theTasks,
            'thisWeek'=>$thisWeek,
            'prevWeek'=>$prevWeek,
            'nextWeek'=>$nextWeek,
            'at'=>$at,
            'theEvents'=>$theEvents,
        ]);
        } // action list

        throw new HttpException(401, 'Yêu cầu không hợp lệ.');
    }

    // Map drawer for tour programs
    public function actionMapDrawer($id, $action = '')
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theProduct) {
            throw new HttpException(403, 'Tour program not found.');
        }

        if (Yii::$app->request->isAjax && $action = 'insert') {
            // Save map file (jpg) to map folder
            $file_name = $_POST['file_name'] ?? 'carte-devis-'.$id.'-'.time().'.jpg';
            $data = $_POST['data'] ?? '';
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);

            if (!is_dir(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/map')) {
                \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/map');
            } else {
                $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/map');
                foreach ($files as $file) {
                    @unlink($file);
                }
            }

            file_put_contents(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/map/'.$file_name, $data);
             
            // CROP + RESIZE HERE
            throw new HttpException(500);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status'=>'ok',
            ];
        }
        return $this->render('tool_map-drawer', [
            'theProduct'=>$theProduct,
            ]);
    }

    // Update ktoan code
    public function actionKetoanXuatCptUpdateCode($action = '', $id = 0)
    {
        if (!in_array(MY_ID, [1, 11, 17, 20787, 28431, 32206])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($action == 'add-item') {
                $name = $_POST['name'] ?? '';
                $code = strtoupper($_POST['code'] ?? '');
                $vat = strtoupper($_POST['vat'] ?? '');
                $sql = 'INSERT INTO at_atuan_codes (name, code, vat, venue_id) VALUES (:name, :code, :vat, 0)';
                Yii::$app->db->createCommand($sql, [
                    ':name'=>$name,
                    ':code'=>$code,
                    ':vat'=>$vat
                ])->execute();
                return ['status'=>'ok', 'id'=>Yii::$app->db->getLastInsertID()];
            }

            if ($action == 'edit-item' && $id != 0) {
                $name = $_POST['name'] ?? '';
                $code = strtoupper($_POST['code'] ?? '');
                $vat = strtoupper($_POST['vat'] ?? '');
                $sql = 'UPDATE at_atuan_codes SET name=:name, code=:code, vat=:vat WHERE venue_id=0 AND id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [
                    ':name'=>$name,
                    ':code'=>$code,
                    ':vat'=>$vat,
                    ':id'=>$id
                ])->execute();
                return ['status'=>'ok'];
            }

            if ($action == 'edit-venue' && $id != 0) {
                $name = $_POST['name'] ?? '';
                $code = strtoupper($_POST['code'] ?? '');
                $vat = strtoupper($_POST['vat'] ?? '');
                $sql = 'SELECT id FROM at_atuan_codes WHERE venue_id=:id LIMIT 1';
                $theId = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryScalar();
                if ($theId > 0) {
                    $sql = 'UPDATE at_atuan_codes SET code=:code, vat=:vat WHERE id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':code'=>$code,
                        ':vat'=>$vat,
                        ':id'=>$theId
                    ])->execute();
                } else {
                    $sql = 'INSERT INTO at_atuan_codes (name, code, vat, venue_id) VALUES (:name, :code, :vat, :id)';
                    Yii::$app->db->createCommand($sql, [
                        ':name'=>$name,
                        ':code'=>$code,
                        ':vat'=>$vat,
                        ':id'=>$id
                    ])->execute();
                }
                return ['status'=>'ok'];
            }

            if ($action == 'del-item' && $id != 0) {
                $sql = 'DELETE FROM at_atuan_codes WHERE venue_id=0 AND id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':id'=>$id])->execute();
                return ['status'=>'ok'];
            }

            if ($action == 'del-venue' && $id != 0) {
                $sql = 'DELETE FROM at_atuan_codes WHERE venue_id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':id'=>$id])->execute();
                return ['status'=>'ok'];
            }

            throw new HttpException(401);
        }

        if (isset($_GET['name'], $_GET['action']) && $_GET['action'] == 'delete') {
            $sql = 'DELETE FROM at_atuan_codes WHERE name=:name LIMIT 1';
            Yii::$app->db->createCommand($sql, [
                ':name'=>$_GET['name'],
                ])->execute();
            Yii::$app->session->setFlash('success', 'Đã xoá mục có tên: '.$_GET['name']);
            return $this->redirect('@web/tools/ketoan-xuat-cpt-update-code');
        }

        if (isset($_POST['name'], $_POST['code'], $_POST['cost'], $_POST['vat'])) {
            $postName = trim($_POST['name']);
            $postCode = trim($_POST['code']);
            $postVat = trim($_POST['vat']);
            if (strtolower(strpos($postName, 0, 4)) != 'vat:') {
                $postCode = strtolower($postCode);
            }
            $postCost = trim($_POST['cost']);
            $sql = 'INSERT INTO at_atuan_codes (name, code, cost, vat) VALUES (:name, :code, :cost, :vat) ON DUPLICATE KEY UPDATE code=:code, cost=:cost, vat=:vat';
            Yii::$app->db->createCommand($sql, [
                ':name'=>$postName,
                ':code'=>$postCode,
                ':cost'=>$postCost,
                ':vat'=>$postVat,
                ])->execute();
            return $this->redirect('@web/tools/ketoan-xuat-cpt-update-code');
        }

        $list = [];
        $sql = 'select *, IF(venue_id=0, "", (SELECT name FROM venues v WHERE v.id=venue_id LIMIT 1)) AS venue_name from at_atuan_codes order by code, name';
        $listItems = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($listItems as $item) {
            $list[$item['name']] = $item;
        }

        return $this->render('tools_ketoan-xuat-cpt-update-code', [
            'list'=>$list,
        ]);
    }

    // Ghep ten HDMB=>tour guide
    public function actionTourguidesMatch($action = 'list', $view = 'end_date', $search = '')
    {
        if ($action == 'ajax' && Yii::$app->request->isAjax) {
            $tour_id = $_POST['tour_id'];
            $name = $_POST['name'];
            $value = $_POST['value'];

            if ((int)$value == 0) {
                $ma_ncc = $value;
            } else {
                $sql = 'SELECT ma_ncc FROM at_profiles_tourguide WHERE user_id=:v LIMIT 1';
                $ma_ncc = Yii::$app->db->createCommand($sql, [':v'=>$value])->queryScalar();
            }

            $sql = 'DELETE FROM link_guide_ncc WHERE tour_id=:t AND name=:n';
            Yii::$app->db->createCommand($sql, [
                ':t'=>$tour_id,
                ':n'=>$name,
                ])->execute();
            $sql = 'INSERT INTO link_guide_ncc (tour_id, name, guide_user_id, ma_ncc) VALUES (:t, :n, :g, :m)';
            Yii::$app->db->createCommand($sql, [
                ':t'=>$tour_id,
                ':n'=>$name,
                ':g'=>$value,
                ':m'=>$ma_ncc,
                ])->execute();
            echo 'DONE';
            exit;
        }

        if (strlen($search) != 7) {
            $search = date('Y-m', strtotime('last month'));
        }

        if ($view == 'end_date') {
            $where = 'LOCATE(:month, DATE_ADD(day_from, INTERVAL day_count - 1 DAY))=1';
        } else {
            // Start date
            $where = 'SUBSTRING(day_from, 1, 7)=:month';
        }

        $theTours = Product::find()
            ->select(['id', 'op_code', 'op_name', 'op_finish'])
            ->andWhere(['op_status'=>'op'])
            ->andWhere($where, [':month'=>$search])
            ->with([
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id']);
                },
                'guides'=>function($q){
                    return $q->select(['guide_user_id', 'tour_id', 'guide_name', 'use_from_dt', 'use_until_dt']);
                },
                'guides.profile'=>function($q){
                    return $q->select(['ma_ncc', 'user_id']);
                },
                'tour.cpt'=>function($q) {
                    return $q->select(['dvtour_name', 'dvtour_day', 'tour_id', 'payer'])
                        ->andWhere(['like', 'payer', 'Hướng dẫn MB%', false])
                        ->orderBy('payer');
                }
                ])
            ->orderBy('day_from')
            ->asArray()
            ->all();

        // \fCore::expose($theTours);

        // exit;

        return $this->render('tourguides_match', [
            'theTours'=>$theTours,
            'view'=>$view,
            'search'=>$search,
            ]);
    }

    // Xuat bang chi phi tour 150909
    public function actionKetoanXuatCpt($view = 'use', $search = '', $eur = 23884, $usd = 21775, $output = 'view')
    {
        if (strlen($search) < 4) {
            $search = 'SEARCH...';
        }
        if ($view == 'tour-code') {
            $sql = 'select t.id, t.op_code, t.day_from, t.day_count, o.id AS oid, u.name AS cbname FROM at_ct t, at_tours o, persons u WHERE u.id=t.created_by AND o.ct_id=t.id AND t.op_status="op" AND LOCATE(:search, t.op_code)!=0 ORDER BY t.day_from LIMIT 1000';
            $theTours = Yii::$app->db->createCommand($sql, [':search'=>$search])->queryAll();

            $oldTourIdList = [];
            foreach ($theTours as $tour) {
                $oldTourIdList[] = $tour['oid'];
            }
        } elseif ($view == 'tour-end') {
            $sql = 'SELECT t.id, t.op_code, t.day_from, t.day_count, o.id AS oid, u.name AS cbname FROM at_ct t, at_tours o, persons u WHERE u.id=t.created_by AND o.ct_id=t.id AND t.day_count>0 AND t.op_status="op" AND LOCATE(:search, DATE_ADD(t.day_from, INTERVAL day_count - 1 DAY))!=0 ORDER BY t.day_from LIMIT 1000';
            $theTours = Yii::$app->db->createCommand($sql, [':search'=>$search])->queryAll();

            $oldTourIdList = [];
            foreach ($theTours as $tour) {
                $oldTourIdList[] = $tour['oid'];
            }
        } elseif ($view == 'use') {
            $sql = 'SELECT t.id, t.op_code, t.day_from, t.day_count, o.id AS oid, "" AS cbname FROM at_ct t, at_tours o, cpt cp WHERE cp.tour_id=o.id AND o.ct_id=t.id AND SUBSTRING(cp.dvtour_day,1,7)=:ym GROUP BY t.id';
            $theTours = Yii::$app->db->createCommand($sql, [':ym'=>$search])->queryAll();

            $oldTourIdList = [];
            foreach ($theTours as $tour) {
                $oldTourIdList[] = $tour['oid'];
            }
        } else { // tour-start
            $sql = 'SELECT t.id, t.op_code, t.day_from, t.day_count, o.id AS oid, u.name AS cbname FROM at_ct t, at_tours o, persons u WHERE u.id=t.created_by AND o.ct_id=t.id AND t.op_status="op" AND LOCATE(:search, t.day_from)!=0 ORDER BY t.day_from LIMIT 1000';
            $theTours = Yii::$app->db->createCommand($sql, [':search'=>$search])->queryAll();

            $oldTourIdList = [];
            foreach ($theTours as $tour) {
                $oldTourIdList[] = $tour['oid'];
            }
        }

        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }

        $sql = 'SELECT * FROM link_guide_ncc WHERE tour_id IN ('.implode(',', $tourIdList).')';
        $nccTg = Yii::$app->db->createCommand($sql)->queryAll();

        if ($view == 'use') {
            $query = Cpt::find()
                ->where('SUBSTRING(dvtour_day,1,7)=:ym', [':ym'=>$search]);
        } else {
            $query = Cpt::find()
                ->where(['tour_id'=>$oldTourIdList]);
        }

        if ($output == 'download' || $view == 'tour') {
            $pageSize = 20000;
        } else {
            $pageSize = 100;
        }

        $query->andWhere(['!=', 'crfund', 'yes']);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>$pageSize,
        ]);

        $theCptx = $query
            ->select(['c3', 'dvtour_id', 'dvtour_day', 'payer', 'venue_id', 'via_company_id', 'by_company_id', 'qty', 'unit', 'price', 'plusminus', 'tour_id', 'dvtour_name', 'unitc', 'oppr'])
            ->with([
                'company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->orderBy('venue_id, oppr')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->asArray()
            ->all();

        $list = [];
        $sql = 'select * FROM at_atuan_codes ORDER by code';
        $listItems = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($listItems as $item) {
            $list[$item['name']] = $item;
        }

$xrate['EUR'] = isset($_GET['eur']) && (int)$_GET['eur'] != 0 ? (int)$_GET['eur'] : 24500;
$xrate['USD'] = isset($_GET['usd']) && (int)$_GET['usd'] != 0 ? (int)$_GET['usd'] : 22300;
$xrate['LAK'] = isset($_GET['lak']) && (int)$_GET['lak'] != 0 ? (int)$_GET['lak'] : 2.75;
$xrate['KHR'] = isset($_GET['khr']) && (int)$_GET['khr'] != 0 ? (int)$_GET['khr'] : 19.73;
$xrate['VND'] = 1;

        if (Yii::$app->request->get('output') == 'download') {
            $filename = 'ketoan_xuat_cpt_'.$search.'_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

            // $arr = ['ID', 'TOUR CODE', 'TOUR IN', 'TOUR OUT', 'NGAY DV', 'NOI DUNG DV', 'SL', 'DON VI', 'GIA', 'TIEN', 'THANH', 'TIEN', 'MA NHA CC', 'TKGN', 'MA PHI', 'VENUE', 'COMPANY', 'OPERATOR', 'PROVIDER', 'STATUS TT'];
            $arr = ['TOUR IN', 'TOUR OUT', 'NGAY DV', 'ID', 'SO HOA DON', 'MA NHA CC', 'NOI DUNG DV', 'TIEN', 'TI GIA', 'MA DV', 'NOI DUNG DV', 'DON VI', 'SL', 'DON GIA', 'THANH NTE', 'THANH VND', 'TK NO', 'TK CO', 'CODE', 'KHOAN MUC CP', 'VENUE', 'COMPANY', 'OPERATOR', 'PAYER', 'VAT', 'STATUS TT'];
            fputcsv($out, $arr);

            foreach ($theTours as $tour) {
                foreach ($theCptx as $cpt) {
                    $arr = [];
                    if ($tour['oid'] == $cpt['tour_id']) {
                        $arr[] = $tour['day_from']; // Ngay khoi hanh
                        $arr[] = date('Y-m-d', strtotime('+ '.($tour['day_count'] - 1).' days', strtotime($tour['day_from']))); // Ngay ket thuc
                        $arr[] = $cpt['dvtour_day']; // Ngay dv
                        $arr[] = $cpt['dvtour_id']; // Id
                        $arr[] = ''; // So hoa don, kt

                        // Ma NCC
                        $text = ''; // NCC
                        $name = ''; // Code
                        $vat = ''; // VAT
                        if (in_array($cpt['payer'], ['Amica Hà Nội', 'Amica Luang Prabang', 'BCEL Laos', 'Hướng dẫn Laos 1', 'Hướng dẫn Laos 2', 'Hướng dẫn Laos 3'])) {
                            if ($cpt['venue']) {
                                $text = $cpt['venue']['name'];
                                foreach ($list as $listItem) {
                                    if ($listItem['venue_id'] == $cpt['venue']['id']) {
                                        $name = $listItem['code'];
                                        $vat = $listItem['vat'];
                                        break;
                                    }
                                }
                            } elseif ($cpt['company']) {
                                $text = $cpt['company']['name'];
                            } else {
                                $text = $cpt['oppr'];
                            }
                        } else {
                            $text = $cpt['payer'];
                        }

                        if ($name == '') {
                            if (isset($list[$text])) {
                                $name = $list[$text]['code'];
                                $name = mb_strtoupper($name);
                            } else {
                                $name = $text;
                            }
                        }

                        // Mot so truong hop VAT cua venue khong co code vi do HDV thanh toan
                        if ($vat == '' && $cpt['venue']) {
$vatCodes = [
    "Ba Bể"=>"BH",
    "Bãi tắm titop"=>"BH",
    "Bản Giốc"=>"BH",
    "Bản Khuổi Khon"=>"BH",
    "Bảo tàng Chàm"=>"BH",
    "Bảo tàng Chứng tích chiến tranh"=>"BH",
    "Bảo Tàng Dân Tộc Học"=>"BH",
    "Bảo tàng Điện Biên Phủ"=>"BH",
    "Bảo tàng Fito"=>"BH",
    "Bảo Tàng Hồ Chí Minh"=>"BH",
    "Bảo Tàng Mỹ Thuật Hà Nội"=>"BH",
    "Bảo Tàng Phụ Nữ Việt Nam"=>"BH",
    "Bảo Tàng Văn Hóa Các Dân Tộc Việt Nam"=>"BH",
    "Bảo Tàng Yersin"=>"BH",
    "Cát Cát"=>"BH",
    "Chùa Bái Đính"=>"BH",
    "Chùa Tây Phương"=>"BH",
    "Chùa Thầy"=>"BH",
    "Côn Sơn"=>"BH",
    "Dinh Độc Lập"=>"BH",
    "Dinh Thống Nhất"=>"BH",
    "Dinh vua Mèo - Bắc Hà"=>"BH",
    "Dinh vua mèo - Hà Giang"=>"BH",
    "Đại Nội Huế"=>"BH",
    "Đền Ngọc Sơn"=>"BH",
    "Đền Quán Thánh"=>"BH",
    "Đồi A1"=>"BH",
    "Động Mê Cung"=>"BH",
    "Động Ngườm Ngao"=>"BH",
    "Động Thiên Cung"=>"BH",
    "Ha Giang Resort (Truong Xuan Resort)"=>"VAT",
    "Halong Palace"=>"VAT",
    "Hầm Đờ Cát"=>"BH",
    "Hầm Ông Giáp"=>"BH",
    "Hang Đá (Sa Pa)"=>"BH",
    "Hang Đầu Gỗ"=>"BH",
    "Hang múa"=>"BH",
    "Hang Sửng Sốt"=>"BH",
    "Hang Tiên Ông"=>"BH",
    "Hoa Binh Hotel 1 & 2"=>"VAT",
    "Hoa Cuong Hotel - Mèo Vạc"=>"VAT",
    "Hoa Lư (Đền Đinh + Lê)"=>"BH",
    "Hoa Viet Hotel"=>"VAT",
    "Hoang Ngoc Hotel"=>"VAT",
    "Khu Di Tích Phủ Chủ tịch"=>"BH",
    "La Pán Tẩn"=>"BH",
    "Lăng Bác"=>"BH",
    "Làng cổ Đường Lâm"=>"BH",
    "Lăng Tự Đức"=>"BH",
    "Lao Chải + Tả Van"=>"BH",
    "Lucky Cafe 2 Restaurant"=>"VAT",
    "Má Tra (Lào Cai)"=>"BH",
    "Madam Yen Restaurant (Coyen Restaurant)"=>"VAT",
    "Muong Thanh Lang Son Hotel"=>"VAT",
    "Nghia Lo Hotel"=>"BH",
    "Nhà Cổ Bình Thủy"=>"BH",
    "Núi Hàm Rồng"=>"BH",
    "Núi Sam"=>"BH",
    "Phố cổ Hội An"=>"BH",
    "Sín Chải"=>"BH",
    "Soi Sim"=>"BH",
    "Sủng Là - Hà Giang"=>"BH",
    "Sunny Hotel Cao Bang"=>"VAT",
    "Tả Phìn"=>"BH",
    "Tam Cốc - Bích Động"=>"BH",
    "Tam Thanh"=>"BH",
    "Thác Bạc"=>"BH",
    "Thăm quan Đảo Cát Bà"=>"BH",
    "Thao Nguyen Hotel"=>"BH",
    "Tháp Ponagar"=>"BH",
    "Thung Chim"=>"BH",
    "Trung Tâm Bảo Tồn Rùa"=>"BH",
    "Trung tâm cứu hộ linh trưởng"=>"BH",
    "Vân Long"=>"BH",
    "Văn Miếu - Quốc Tử Giám"=>"BH",
    "Vịnh Lan Hạ"=>"BH",
    "Vườn Bách Thảo"=>"BH",
    "Vườn Quốc Gia Cát Bà"=>"BH",
    "Xưởng Thêu XQ"=>"BH",
    "Yen Nhi Hotel"=>"VAT",
];
                            $vat = $vatCodes[$cpt['venue']['name']] ?? '';
                        }

                        if (strpos($name, 'Hướng dẫn MB') === 0) {
                            foreach ($nccTg as $ncc) {
                                if ($ncc['tour_id'] == $tour['id'] && $ncc['name'] == $name) {
                                    $name = $ncc['ma_ncc'];
                                    break;
                                }
                            }
                        }

                        $arr[] = $name; // Nha cung cap

                        $cpt['xrate'] = $xrate[$cpt['unitc']] ?? 1;

                        $arr[] = $cpt['dvtour_name']; // Noi dung dv
                        $arr[] = $cpt['unitc']; // Loai tien
                        $arr[] = $cpt['xrate']; // Ti gia
                        $arr[] = ''; // Ma dv, kt
                        $arr[] = $cpt['dvtour_name']; // Noi dung dv
                        $arr[] = $cpt['unit']; // Don vi
                        $arr[] = number_format($cpt['qty'], intval($cpt['qty']) == $cpt['qty'] ? 0 : 2); // So luong
                        $arr[] = ($cpt['plusminus'] == 'minus' ? '-' : '').number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2); // Don gia

                        $cpt['total'] = $cpt['price'] * $cpt['qty'];
                        $arr[] = ($cpt['plusminus'] == 'minus' ? '-' : '').number_format($cpt['total'], intval($cpt['total']) == $cpt['total'] ? 0 : 2); // Thanh NTE

                        $cpt['totalVND'] = $cpt['total'] * $cpt['xrate'];
                        $arr[] = ($cpt['plusminus'] == 'minus' ? '-' : '').number_format($cpt['totalVND'], intval($cpt['totalVND']) == $cpt['totalVND'] ? 0 : 2); // Thanh VND

                        $arr[] = ''; // TK no, kt
                        $arr[] = ''; // TK co, kt
                        $arr[] = $tour['op_code']; // Code tour
                        $arr[] = ''; // Khoan muc cp, kt

                        $arr[] = $cpt['venue']['name']; // Ai tt
                        $arr[] = $cpt['company']['name']; // Ai tt
                        $arr[] = $cpt['oppr']; // Ai tt
                        $arr[] = $cpt['payer']; // Ai tt
                        $arr[] = $vat; // Ai tt
                        if (substr($cpt['c3'], 0, 2) == 'on') {
                            $arr[] = 'TT'; // Ai tt
                        }

                        fputcsv($out, $arr);
                    }
                }
            }

            fclose($out);
            exit;
        }

        return $this->render('tools_ketoan-xuat-cpt', [
            'theTours'=>$theTours,
            'theCptx'=>$theCptx,
            'list'=>$list,
            'view'=>$view,
            'search'=>$search,
            'output'=>$output,
            'pagination'=>$pagination,
        ]);
    }

    // Xuat bang chi phi tour
    public function OLD_actionKetoanXuatCpt($view = 'tour', $search = '', $eur = 23884, $usd = 21775, $output = 'view')
    {
        if (strlen($search) < 4) {
            $search = 'SEARCH...';
        }
        if ($view == 'tour') {
            $sql = 'select t.id, t.op_code, t.day_from, o.id AS oid, u.name AS cbname FROM at_ct t, at_tours o, persons u WHERE u.id=t.created_by AND o.ct_id=t.id AND t.op_status="op" AND LOCATE(:search, t.op_code)!=0 ORDER BY t.day_from LIMIT 1000';
            $theTours = Yii::$app->db->createCommand($sql, [':search'=>$search])->queryAll();

            $oldTourIdList = [];
            foreach ($theTours as $tour) {
                $oldTourIdList[] = $tour['oid'];
            }
        } else {
            $sql = 'select t.id, t.op_code, t.day_from, o.id AS oid, u.name AS cbname FROM at_ct t, at_tours o, persons u WHERE u.id=t.created_by AND o.ct_id=t.id AND t.op_status="op" AND LOCATE(:search, t.day_from)!=0 ORDER BY t.day_from LIMIT 1000';
            $theTours = Yii::$app->db->createCommand($sql, [':search'=>$search])->queryAll();

            $oldTourIdList = [];
            foreach ($theTours as $tour) {
                $oldTourIdList[] = $tour['oid'];
            }
        }

        $query = Cpt::find()
            ->where(['tour_id'=>$oldTourIdList]);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>4000,
        ]);

        $theCptx = $query
            ->select(['payer', 'venue_id', 'via_company_id', 'by_company_id', 'qty', 'unit', 'price', 'plusminus', 'tour_id', 'dvtour_name', 'unitc', 'oppr'])
            ->with([
                'company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->orderBy('venue_id, oppr')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->asArray()
            ->all();
        /*
        $theTour = false;
        $theProduct = false;
        $theCptx = [];
        if ($tour != '') {
            $theTour = Tour::find()->where(['code'=>$tour])->asArray()->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found');
            }
            $theProduct = Product::find()
                ->with(['createdBy'])
                ->where(['id'=>$theTour['ct_id']])
                ->asArray()
                ->one();
            if (!$theProduct) {
                throw new HttpException(404, 'Product not found');
            }
            $theCptx = Cpt::find()
                ->where(['tour_id'=>$theTour['id']])
                ->with([
                    'company'=>function($q) {
                        return $q->select(['id', 'name']);
                    },
                    'venue'=>function($q) {
                        return $q->select(['id', 'name']);
                    },
                ])
                ->orderBy('venue_id, oppr')
                ->asArray()
                ->all();
        }
*/
        $list = [];
        $sql = 'select * from at_atuan_codes order by code';
        $listItems = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($listItems as $item) {
            $list[$item['name']] = $item;
        }

        if (Yii::$app->request->get('output') == 'download') {
            $filename = 'ketoan_xuat_cpt_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fputcsv($out, array('this','is some', 'csv "stuff", you know.'));
            fclose($out);
            exit;
        }

        return $this->render('tools_ketoan-xuat-cpt', [
            'theTours'=>$theTours,
            'theCptx'=>$theCptx,
            'list'=>$list,
            'view'=>$view,
            'search'=>$search,
            'output'=>$output,
            'pagination'=>$pagination,
        ]);
    }

    public function actionIplookup()
    {
        return $this->render('tools_iplookup', [
            //'kbbooks'=>$kbbooks,
        ]);
    }

    public function actionLichguide()
    {
        $yearList = [2017=>2017, 2016=>2016, 2015=>2015, 2014=>2014, 2013=>2013, 2012=>2012];

        $getGuide = 1353;
        $getYear = Yii::$app->request->get('year', date('Y'));

        if (!array_key_exists($getYear, $yearList)) {
            throw new HttpException(404, 'Invalid year');
        }

        $theTourguide = Person::find()
            ->where(['id'=>$getGuide])
            ->asArray()
            ->one();

        $theDays = Yii::$app->db
            ->createCommand('SELECT t.id, t.code, t.name, t.status, tg.day FROM at_tours t, at_tour_guide tg WHERE tg.tour_id=t.id AND tg.user_id=:id ORDER BY tg.day DESC LIMIT 1000', [':id'=>$theTourguide['id']])
            ->queryAll();

        return $this->render('tools_lichguide', [
            'theTourguide'=>$theTourguide,
            'theDays'=>$theDays,
            'getGuide'=>$getGuide,
            'getYear'=>$getYear,
            'yearList'=>$yearList,
        ]);
    }

    public function actionFiche()
    {
        $theForm = new ReservationForm;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            //
        }

        return $this->render('tools_fiche', [
            'theForm'=>$theForm,
        ]);
    }

    public function actionDiemlx($action = '', $id = 0)
    {
        $tourSql = 'SELECT id, CONCAT(code, " - ", name) AS name FROM at_tours ORDER BY SUBSTRING(code,2) DESC LIMIT 5000';
        $driverSql = 'SELECT u.id, CONCAT(u.name, " - ", u.phone) AS name FROM persons u, at_profiles_driver p WHERE p.user_id=u.id ORDER BY u.lname, u.fname LIMIT 5000';
        $tourList = Yii::$app->db->createCommand($tourSql)->queryAll();
        $driverList = Yii::$app->db->createCommand($driverSql)->queryAll();
        if ($action == 'c') {
            $theEntry = new Diemlx;
            //$theEntry->scenario = 'diemlx/c';
            if ($theEntry->load(Yii::$app->request->post()) && $theEntry->validate()) {
                $theEntry->created_at = NOW;
                $theEntry->created_by = MY_ID;
                $theEntry->updated_at = NOW;
                $theEntry->updated_by = MY_ID;
                $theEntry->save();
                return $this->redirect('@web/tools/diemlx');
            }
            return $this->render('diemlx_u', [
                'theEntry'=>$theEntry,
                'tourList'=>$tourList,
                'driverList'=>$driverList,
            ]);
        } elseif ($action == 'r') {

        } elseif ($action == 'u') {
            $theEntry = Diemlx::find()
                ->where(['id'=>$id])
                ->with(['tour'])
                ->one();
            //$theEntry->scenario = 'diemlx/c';
            if (!$theEntry) {
                throw new HttpException(404, 'Not found');
            }
            if ($theEntry->load(Yii::$app->request->post()) && $theEntry->validate()) {
                $theEntry->updated_at = NOW;
                $theEntry->updated_by = Yii::$app->user->id;
                $theEntry->save();
                return $this->redirect('@web/tools/diemlx');
            }
            return $this->render('diemlx_u', [
                'theEntry'=>$theEntry,
                'tourList'=>$tourList,
                'driverList'=>$driverList,
            ]);
        } elseif ($action == 'd') {

        } else {
            $getTourId = Yii::$app->request->get('tour_id', 0);
            $getDriverId = Yii::$app->request->get('driver_user_id', 0);
            $query = Diemlx::find();
            if ($getTourId != 0) {
                $query->andWhere(['tour_id'=>$getTourId]);
            }
            if ($getDriverId != 0) {
                $query->andWhere(['driver_user_id'=>$getDriverId]);
            }
            $countQuery = clone $query;
            $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize'=>25,
            ]);
            $theEntries = $query
                ->with(['driver', 'tour', 'updatedBy'])
                ->orderBy('updated_at DESC')
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
            return $this->render('diemlx', [
                'theEntries'=>$theEntries,
                'pages'=>$pages,
                'tourList'=>$tourList,
                'driverList'=>$driverList,
                'getTourId'=>$getTourId,
                'getDriverId'=>$getDriverId,
            ]);
        }
    }

    // Lich tour xe Hoang Phu
    public function actionXehoangphu()
    {
        $getMonth = Yii::$app->request->get('month', date('Y-m'));
        $sql = 'select substring(dvtour_day,1,7) as ym from cpt group by ym order by ym desc';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();
        
        // Cac dich vụ Hoang phu co ngay su dung trong thang
        $sql = 'select d.dvtour_day, d.dvtour_id, d.dvtour_name, d.unit, d.unitc, d.qty, d.price, t.id, t.code, t.status, t.name, p.day_from from cpt d, at_tours t, at_ct p where t.ct_id=p.id and d.tour_id=t.id AND (via_company_id=2 or by_company_id=2 or locate("Hoang Phu", oppr)!=0) AND SUBSTRING(d.dvtour_day,1,7)=:ym ORDER BY d.dvtour_day limit 1000';
        $theCptx = Cpt::findBySql($sql, [':ym'=>$getMonth])->asArray()->all();

        // Cac dich vu Hoang Phu cua tour khoi hanh trong thang
        $sql = 'select d.dvtour_day, d.dvtour_id, d.dvtour_name, d.unit, d.unitc, d.qty, d.price, t.id, t.code, t.status, t.name, p.day_from from cpt d, at_tours t, at_ct p where t.ct_id=p.id and d.tour_id=t.id AND (via_company_id=2 or by_company_id=2 or locate("Hoang Phu", oppr)!=0) AND SUBSTRING(p.day_from,1,7)=:ym ORDER BY p.day_from, d.dvtour_day limit 1000';
        $theCptx2 = Cpt::findBySql($sql, [':ym'=>$getMonth])->asArray()->all();
        return $this->render('tools_xehoangphu', [
            'theCptx'=>$theCptx,
            'theCptx2'=>$theCptx2,
            'getMonth'=>$getMonth,
            'monthList'=>$monthList,
        ]);
    }

    // Tour cua khach san
    public function actionTourKs($ks, $view = '', $type = '', $tour = '', $year = '', $month = '')
    {
        $theVenue = Venue::findOne($ks);
        if (!$theVenue) {
            throw new HttpException(404, 'Khong tim thay khach san');
        }

        if ($year == '') {
            $year = date('Y');
        }

        $andType = '';
        if ($type == 'a' || $type == 'g') {
            $andType = 'AND tmp_type="'.$type.'"';
        } elseif ($type == 'm') {
            $andType = 'AND tmp_type IN ("b", "l", "d")';
        } elseif ($type == 'o') {
            $andType = 'AND tmp_type NOT IN ("a", "b", "l", "d", "g")';
        }

        if ($month == '') {
            $sql = 'select tour_id FROM cpt WHERE venue_id=:ks '.$andType.' AND YEAR(dvtour_day)=:y GROUP BY tour_id ORDER BY dvtour_day';
            $tourIdList = Yii::$app->db->createCommand($sql, [':ks'=>$ks, ':y'=>$year])->queryColumn();
        } else {
            $sql = 'select tour_id FROM cpt WHERE venue_id=:ks '.$andType.' AND YEAR(dvtour_day)=:y AND MONTH(dvtour_day)=:m GROUP BY tour_id ORDER BY dvtour_day';
            $tourIdList = Yii::$app->db->createCommand($sql, [':ks'=>$ks, ':y'=>$year, ':m'=>$month])->queryColumn();
        }

        $theTours = Tour::find()
            ->select(['id', 'code', 'name', 'status', 'ct_id'])
            ->where(['id'=>$tourIdList])
            ->with([
                'cpt'=>function($q) use ($ks, $month) {
                    return $q
                    ->select(['dvtour_id', 'dvtour_name', 'dvtour_day', 'tour_id', 'qty', 'unit', 'price', 'unitc', 'tmp_type', 'tmp_rn'])
                    ->where(['venue_id'=>$ks])
                    ->andWhere('LOCATE(:month, dvtour_day)=1', [':month'=>$month])
                    ->orderBy('dvtour_day');
                },
                'product'=>function($q) {
                    return $q->select(['id', 'day_count', 'day_from']);
                },
                'product.bookings'=>function($q) {
                    return $q->select(['pax', 'product_id']);
                },
                ])
            ->indexBy('id')
            ->asArray()
            ->all();

        return $this->render('tools_tour-ks', [
            'theVenue'=>$theVenue,
            'theTours'=>$theTours,
            'type'=>$type,
            'view'=>$view,
            'year'=>$year,
            'month'=>$month,
        ]);
    }

    // Tour cua khach san
    public function actionTourPaxKs($ks = 321, $tour = '', $month = '')
    {
        $theVenue = Venue::findOne($ks);
        if (!$theVenue) {
            throw new HttpException(404, 'Khong tim thay khach san');
        }

        if ($month == '') {
            $month = date('Y');
        }

        $theCptx = Cpt::find()
            ->select(['tour_id'])
            ->where(['venue_id'=>$ks])
            // ->andWhere(['or', 'dvtour_name="Khách sạn"', 'dvtour_name="Hotel"', 'dvtour_name="Tàu ngủ đêm"', 'dvtour_name="Tàu Hạ Long"', 'dvtour_name="nhà dân"', 'dvtour_name="Accommodation"'])
            ->andWhere('LOCATE(:month, dvtour_day)=1', [':month'=>$month])
            ->with([
                'tour'=>function($query) {
                    return $query->select(['id', 'ct_id']);
                },
                'tour.product'=>function($query) {
                    return $query->select(['id', 'day_count', 'day_from', 'op_finish', 'op_name', 'op_code']);
                },
                'tour.product.bookings'=>function($query) {
                    return $query->select(['id', 'pax AS paxcount', 'product_id']);
                },
                'tour.product.bookings.pax'=>function($query) {
                    return $query->select(['id', 'name', 'email', 'country_code']);
                },
            ])
            ->orderBy('dvtour_day')
            ->groupBy('tour_id')
            ->asArray()
            ->all();

        // \fCore::expose($results); exit;

        return $this->render('tools_tour-pax-ks', [
            'theVenue'=>$theVenue,
            'results'=>$theCptx,
            'month'=>$month,
        ]);
    }


    // Tour cua khach san
    public function actionTourCty($cty = 6)
    {
        $theCompany = Company::findOne($cty);
        if (!$theCompany) {
            throw new HttpException(404, 'Khong tim thay cong ty');
        }

        $sql = 'select t.id, t.code, t.name, t.status, p.day_count, p.pax, p.day_from from at_tours t, at_ct p, cpt cp, at_companies c where p.id=t.ct_id AND (c.id=cp.by_company_id or c.id=cp.via_company_id) and cp.tour_id=t.id and c.id=:id group by t.id order by SUBSTRING(t.code,2,7) DESC';
        $theTours = Yii::$app->db->createCommand($sql, [':id'=>$cty])->queryAll();

        return $this->render('tools_tour-cty', [
            'theCompany'=>$theCompany,
            'theTours'=>$theTours,
        ]);
    }
}
