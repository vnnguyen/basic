<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\data\Pagination;
use common\models\Booking;
use common\models\Customer;
use common\models\Country;
use common\models\Person;
use common\models\Ct;
use common\models\Tour;
use common\models\Product;
use common\models\Task;

use \PHPExcel;
use \PHPExcel_IOFactory;

class CustomerController extends MyController
{
    public function actionAjax($action = '', $task_id = 0)
    {
        // 161011: Huan, Khang Ha, Bao Tuan, Pham Ha, Minh Minh
        // 161030: +Ngoc Anh
        if ($action == 'load_task_ac') {
            if (!in_array(USER_ID, [1, 1351, 12952, 27388, 29296, 30554])) {
                throw new HttpException(403, 'Denied');
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            $theTask = Task::find()
                ->where(['id'=>$task_id])
                ->asArray()
                ->one();
            if (!$theTask) {
                throw new HttpException(404, 'Task not found.');
            }

            $table = '';
            for ($i = 1; $i <= 4; $i ++) {
                $check = ' #'.$i;
                if (strpos($theTask['description'], $check)) {
                    $theTask['description'] = str_replace($check, '', $theTask['description']);
                    $table = $i;
                }
            }

            $icons = [];
            foreach ([' #t', ' #s', ' #q', ' #c'] as $check) {
                if (strpos($theTask['description'], $check)) {
                    $theTask['description'] = str_replace($check, '', $theTask['description']);
                    $icons[] = substr($check, -1);
                }
            }

            $time_fuzzy = 'e';
            $time = '09:00';
            if ($theTask['fuzzy'] == 'time' || $theTask['fuzzy'] == 'date') {
                $His = date('H:i:s', strtotime($theTask['due_dt']));
                if ($His == '11:59:59') {
                    $time_fuzzy = 'm';
                } elseif ($His == '17:59:59') {
                    $time_fuzzy = 'a';
                } elseif ($His == '23:59:59') {
                    $time_fuzzy = 'e';
                }
            } else {
                $time_fuzzy = 't';
                $time = date('H:i', strtotime($theTask['due_dt']));
            }
            $response = [
                'time_fuzzy'=>$time_fuzzy,
                'time'=>$time,
                'mins'=>$theTask['mins'],
                'table'=>$table,
                'icons'=>$icons,
                'note'=>trim(substr($theTask['description'], 2)),
            ];
            return $response;
        }

        if ($action == 'update_task_ac') {
            if (!in_array(USER_ID, [1, 1351, 12952, 27388, 29296, 30554])) {
                throw new HttpException(403, 'Denied');
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            $theTask = Task::find()
                ->where(['id'=>$task_id])
                ->one();
            if (!$theTask) {
                throw new HttpException(404, 'Task not found.');
            }

            
            if ($_POST['time_fuzzy'] == 'm') {
                $fuzzy = 'time';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).'11:59:59';
                $time = 'Sáng';
            } elseif ($_POST['time_fuzzy'] == 'a') {
                $fuzzy = 'time';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).'17:59:59';
                $time = 'Chiều';
            } elseif ($_POST['time_fuzzy'] == 'e') {
                $fuzzy = 'time';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).'23:59:59';
                $time = 'TBA';
            } else {
                $fuzzy = 'none';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).$_POST['time'].':00';
                $time = $_POST['time'];
            }
            $mins = (int)$_POST['mins'];
            $description = 'AC';
            $description .= ' '.$_POST['note'];
            if (in_array($_POST['table'], [1,2,3,4])) {
                $description .= ' #'.$_POST['table'];
            }
            if (isset($_POST['purpose']) && !empty($_POST['purpose'])) {
                foreach (['t', 's', 'q', 'c'] as $check) {
                    if (in_array($check, $_POST['purpose'])) {
                        $description .= ' #'.$check;
                    }
                }
            }

            $theTask->due_dt = $due_dt;
            $theTask->fuzzy = $fuzzy;
            $theTask->mins = $mins;
            $theTask->description = $description;
            $theTask->save(false);


            $showicons = [
                ' #t'=>'<i title="Thu/trả lại tiền" class="fa fa-fw fa-dollar text-success"></i>',
                ' #s'=>'<i title="Tổ chức sinh nhật" class="fa fa-fw fa-birthday-cake text-warning"></i>',
                ' #q'=>'<i title="Tặng quà sinh nhật" class="fa fa-fw fa-gift text-pink"></i>',
                ' #c'=>'<i title="Tặng quà khách cũ" class="fa fa-fw fa-user text-danger"></i>',
            ];
            $icons = '';
            foreach ([' #t', ' #s', ' #q', ' #c'] as $check) {
                if (strpos($theTask->description, $check)) {
                    $icons .= $showicons[$check];
                }
            }

            // Success
            $response = [
                'time'=>$time,
                'table'=>$_POST['table'],
                'icons'=>$icons,
                'note'=>$_POST['note'],
            ];
            return $response;
        }
    }

    public function actionIndex(
        $name = '',
        $gender = 'all',
        $age = '',
        Array $country = [],
        $email = '',
        $phone = '',
        $address = '',
        $year = '',
        $code = '',
        $output = 'view', // view or download
        $rcount = 0, // Number of referral cases
        $bcount = 0, // Number of bookings

        $passeport = '',

        $lang = '',
        $typeOfWeb = '',
        $like = '',
        $dislike = '',
        $traveler_profile = '',
        $traveler_pref = '',
        $destination = '',
        $nextCountry = '',
        $ambas = '',
        $department = ''
    )
    {
        if (!in_array(USER_ID, [1,2,3,4,11,118,695,4432,1351,4432,7756,26435,29296,30554,14671, 18598, 27388, 35071])) {
            //throw new HttpException(403, 'Access denied');
        }

        // if ($output == 'download' && !in_array(USER_ID, [1,695,4432,14671,18598])) {
        //     throw new HttpException(403, 'Access denied');
        // }
        $query = Person::find()
            ->innerJoinWith([
                'profileCustomer',
                'bookings'=>function($q){
                    $q->select(['id', 'product_id']);
                },
                'bookings.product'=>function($q){
                    $q->select(['id', 'op_code', 'day_from']);
                }
            ]);


        if ($code != '') {
            $theTour = Product::find()
                ->with([
                    'bookings',
                    'bookings.pax',
                ])
                ->where(['op_code' => $code])->asArray()->one();
                if (!$theTour) {
                    throw new HttpException(403, 'The tour not found');
                }
                $arr_person = [];
                foreach ($theTour['bookings'] as $booking) {
                    foreach ($booking['pax'] as $person) {
                        $arr_person[] = $person['id'];
                    }
                }
                if (count($arr_person) > 0) {
                    $query->andWhere(['persons.id' => $arr_person]);
                }
        }
        if ((int)$rcount > 0) {
            $query->andWhere('won_referral_count>=:rcount', [':rcount'=>(int)$rcount]);
        }

        if ((int)$bcount > 0) {
            $query->andWhere('booking_count>=:bcount', [':bcount'=>(int)$bcount]);
        }

        if ($name != '') {
            $query->andWhere(['OR', ['LIKE', 'fname', $name], ['LIKE', 'lname', $name], ['LIKE', 'name', $name]]);
        }
        if ($email != '') {
            $query->andWhere(['like', 'email', $email]);
        }
        if (in_array($gender, ['male', 'female'])) {
            $query->andWhere(['gender'=>$gender]);
        }
        if (count($country) > 0) {
            foreach ($country as $c) {
                $query->andWhere(['LIKE', 'country_code', $c]);
            }
        }
        if ($year != '') {
            if (strlen($year) == 4 && (int)$year > 2006) {
                $query->andWhere('YEAR(day_from)=:year', [':year'=>$year]);
            }
            if (strlen($year) > 4) {
                $arr = [];
                if (strpos($year, '/') !== false) {
                    $arr = explode('/', $year);
                }
                if (strpos($year, '-') !== false) {
                    $arr = explode('-', $year);
                }
                if (count($arr) > 0) {
                    if (strlen($arr[0]) == 4) {
                        $y = $arr[0];
                        $m = $arr[1];
                    } else {
                        $y = $arr[1];
                        $m = $arr[0];
                    }
                    if ((int)$y > 2006) {
                        $query->andWhere('YEAR(day_from)=:y AND MONTH(day_from) =:m', [':y'=>$y, ':m' => $m]);
                    }
                }
            }
        }
        if (strlen($code) >= 4) {
            $query->andWhere(['like', 'op_code', $code]);
        }

        if ($age != '') {
            $thisYear = date('Y');
            $ageFromTo = explode('-', $age);
            if (is_array($ageFromTo) && count($ageFromTo) == 2) {
                $from = (int)$ageFromTo[0];
                $to = (int)$ageFromTo[1];
                $query->andWhere('byear<=:from', [':from'=>$thisYear - $from])->andWhere('byear>=:to', [':to'=>$thisYear - $to]);
            } else {
                if ((int)$age == 0) {
                    $query->andWhere(['byear'=>0]);
                } else {
                    $query->andWhere(['byear'=>$thisYear - $age]);
                }
            }
        }
        if ($lang != '') {
            $query->andWhere(['persons.language' => $lang]);
        }


        if ($destination != '') {
            $query->andWhere(['LIKE', 'visited_countries', $destination]);
        }
        if ($phone != ''
            || $typeOfWeb != ''
            || $passeport != ''
            || strlen($address) > 2
            || $like != '' || $dislike != ''
            || $traveler_profile != ''
            || $traveler_pref != ''
            || $nextCountry != ''
            || $ambas != ''
            || $department != ''
        ) {
            $query->innerJoinWith('metas');
            if ($phone != '') {
                $phone = ltrim(str_replace(' ', '', $phone), '0');
                $query->andWhere('metas.name = "mobile" OR metas.name = "tel"');
                $query->andWhere(['LIKE', 'metas.value', $phone]);
            }
            if ($typeOfWeb != '') {
                $query->andWhere(['metas.name' => $typeOfWeb]);
            }
            if ($passeport != '') {
                $query->andWhere('metas.name = "Passeport"');
                $query->andWhere(['LIKE', 'metas.value', $passeport]);
            }
            if (strlen($address) > 2 ) {
                $query->andWhere(['like', 'metas.name', 'address']);
                $query->andWhere(['like', 'value', $address]);
            }
            if ($like != '') {
                $query->andWhere('metas.name = "likes"');
                $query->andWhere(['LIKE', 'metas.value', $like]);
            }
            if ($dislike != '') {
                $query->andWhere('metas.name = "dislikes"');
                $query->andWhere(['LIKE', 'metas.value', $dislike]);
            }
            if ($traveler_profile != '') {
                $query->andWhere('metas.name = "traveler_profile"');
                $query->andWhere(['LIKE', 'metas.value', $traveler_profile]);
            }
            if ($traveler_pref != '') {
                $query->andWhere('metas.name = "travel_preferences"');
                $query->andWhere(['LIKE', 'metas.value', $traveler_pref]);
            }
            if ($nextCountry != '') {
                $query->andWhere('metas.name = "future_travel_wishlist"');
                $query->andWhere(['LIKE', 'metas.value', $nextCountry]);
            }
            if ($ambas != '') {
                $query->andWhere('metas.name="ambassaddor_potentiality" AND metas.value =:ambas', [':ambas' => $ambas]);
            }
            if ($department != '') {
                $depart = "\n".$department;
                $query->andWhere(['like', 'metas.name', 'address']);
                $query->andWhere(['like', 'value', $depart]);
            }
        }



        $query->groupBy('persons.id');

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);
        $theCustomers = $query
            ->with([
                'roles',
                'bookings',
                'bookings.product',
                'metas'])
            ->orderBy('lname, fname')
            ->offset($pagination->offset)
            ->limit($output == 'download' ? 5000 : $pagination->limit)
            ->asArray()
            ->all();

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        // export to excel
        if ($output == 'download') {
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'First name')
                        ->setCellValue('C1', 'Last name')
                        ->setCellValue('D1', 'Name')
                        ->setCellValue('E1', 'Gender')
                        ->setCellValue('F1', 'Country')
                        ->setCellValue('G1', 'Day of birth')
                        ->setCellValue('H1', 'Email')
                        ->setCellValue('I1', 'Phone')
                        ->setCellValue('K1', 'Phone');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
            $k = 2;
            foreach ($theCustomers as $person) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$k, $person['id'])
                            ->setCellValue('B'.$k, $person['fname'])
                            ->setCellValue('C'.$k, $person['lname'])
                            ->setCellValue('D'.$k, $person['name'])
                            ->setCellValue('E'.$k, $person['gender'])
                            ->setCellValue('F'.$k, $person['country_code'])
                            ->setCellValue('G'.$k, $person['bday'].'-'.$person['bmonth'].'-'.$person['byear'])
                            ->setCellValue('H'.$k, '')
                            ->setCellValue('I'.$k, '')
                            ->setCellValue('K'.$k, '');
                if (count($person['metas']) > 0) {
                    $arr_email = [];
                    $arr_phone = [];
                    foreach ($person['metas'] as $item) {
                        if ($item['name'] == 'email' && $item['value'] != '') {
                            $arr_email[] = $item['value'];
                        }
                        if ($item['name'] == 'mobile' && $item['value'] != '') {
                            $arr_phone[] = $item['value'];
                        }
                    }
                    if (count($arr_email) > 0) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.$k, implode(', ', $arr_email));
                    }
                    if (count($arr_phone) > 0) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('I'.$k, implode(', ', $arr_phone));
                    }
                }
                if (count($person['bookings']) > 0) {
                    $arr_booking = [];
                    foreach ($person['bookings'] as $booking) {
                        if (isset($booking['product']) && $booking['product'] != null) {
                            $arr_booking[] = $booking['product']['op_code'];
                        }
                    }
                    if (count($arr_booking) > 0) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('K'.$k, implode(', ', $arr_booking));
                    }
                }
                $k++;
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->setTitle('Report');
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('MyExcel.xlsx');die('okkkkk');
        }

        return $this->render('customer_index', [
            'pagination'=>$pagination,
            'theCustomers'=>$theCustomers,
            'year'=>$year,
            'code'=>$code,
            'name'=>$name,
            'gender'=>$gender,
            'age'=>$age,
            'email'=>$email,
            'phone'=>$phone,
            'address'=>$address,
            'country'=>$country,
            'countryList'=>$countryList,
            'bcount'=>$bcount,
            'rcount'=>$rcount,
            'passeport' => $passeport,
            'typeOfWeb' => $typeOfWeb,
            'lang' => $lang,
            'like' => $like,
            'dislike' =>$dislike,
            'traveler_profile' => $traveler_profile,
            'traveler_pref' => $traveler_pref,
            'destination' => $destination,
            'nextCountry' => $nextCountry,
            'ambas' => $ambas,
            'department' => $department
        ]);
    }

    public function actionBirthdays($day = 0, $month = 0, $year = 0, $name = '')
    {
        if (!in_array(USER_ID, [1,2,3,4,11,118,695,4432,1351,7756,9881,29296,30554,14671, 18598, 27388])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($month == 0) {
            $month = date('n');
        }

        $query = Person::find()
            ->innerJoinWith(['bookings']);
        if ($year != 0) {
            $query->andWhere(['byear'=>$year]);
        }
        if ($month != 0) {
            $query->andWhere(['bmonth'=>$month]);
        }
        if ($day != 0) {
            $query->andWhere(['bday'=>$day]);
        }
        if (strlen(trim($name)) > 2) {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);

        $theUsers = $query
            ->select(['persons.id', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear'])
            ->with(['metas', 'bookings', 'bookings.product'])
            ->orderBy('bday, byear')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('customers_birthdays', [
            'pagination'=>$pagination,
            'theUsers'=>$theUsers,
            'year'=>$year,
            'month'=>$month,
            'day'=>$day,
            'name'=>$name,
        ]);
    }

    public function actionPrintBirthdays($month = null)
    {
        if (!in_array(USER_ID, [1,2,3,4,11,118,695,4432,1351,7756,9881,29296,30554,14671, 18598])) {
            throw new HttpException(403, 'Access denied');
        }

        $getMonth = date('n');
        if (isset($month) && (int)$month <= 12) {
            $getMonth = $month;
        }

        $theUsers = Person::find()
            ->innerJoinWith(['bookings'])
            ->select(['persons.id', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear'])
            ->where(['bmonth'=>$getMonth])
            ->with(['metas'])
            ->orderBy('bday, byear')
            ->asArray()
            ->all();

        return $this->render('customers_print-birthdays', [
            'theUsers'=>$theUsers,
            'getMonth'=>$getMonth,
        ]);
    }

    public function actionTasks($month = '')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        $theTours = Product::find()
            ->select(['id', 'op_name', 'op_code', 'day_from', 'day_count', 'pax', 'op_finish'])
            ->with([
                'tour',
                'tour.tasks'=>function($q){
                    return $q->select(['description', 'status', 'due_dt', 'rid', 'rtype'])
                        ->where(['description'=>['PC', 'BV', 'AC', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'SV']])
                        ->orWhere('SUBSTRING(description,1,3) IN ("PC ","BV ","AC ","A1 ", "A2 ", "A3 ", "A4 ", "A5 ", "A6 ", "A7 ", "SV")');
                },
                'tour.cskh'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                }
                ])
            ->where(['op_status'=>'op'])
            ->andWhere('SUBSTRING(day_from,1,7)=:ym', [':ym'=>$month])
            ->orderBy('at_ct.day_from, at_ct.day_count')
            ->asArray()
            ->limit(1000)
            ->all();

            // \fCore::expose($theTours); exit;

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(ct.day_from,1,7) AS ym, SUBSTRING(ct.day_from,1,4) AS yr FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        return $this->render('customers_tasks', [
            'theTours'=>$theTours,
            'month'=>$month,
            'monthList'=>$monthList,
        ]);
    }

    public function actionTasksAc($date = '', $at = 'hanoi')
    {
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

        if ($at == 'hanoi') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND SUBSTRING(tk.description,1,5)!="AC SG" AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'saigon') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC SG ", tk.description)=1 OR tk.description="AC SG") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'luangprabang') {
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
        if ($at == 'hanoi') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC ", description)=1 OR description="AC") AND SUBSTRING(description,1,5)!="AC SG" AND SUBSTRING(description,1,5)!="AC LP" AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } elseif ($at == 'saigon') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC SG ", description)=1 OR description="AC SG") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } elseif ($at == 'luangprabang') {
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

        return $this->render('customers_tasks-ac', [
            'theTasks'=>$theTasks,
            'thisWeek'=>$thisWeek,
            'prevWeek'=>$prevWeek,
            'nextWeek'=>$nextWeek,
            'at'=>$at,
        ]);
    }
}
