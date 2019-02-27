<?php
namespace app\controllers;

use Yii;
use yii\web\HttpException;
use common\models\Kase;
use common\models\Contact;
use common\models\Product;
use common\models\Tour;
use common\models\Booking;
use common\models\User;
use common\models\Task;
use common\models\Meta;
use common\models\ServicePlus;
use yii\data\Pagination;

class QhkhController extends MyController
{
    /**
     * Index page for QHKH
     */
    public function actionIndex()
    {
        return $this->render('qhkh_index');
    }

    /**
     * Birthdates of customers
     */
    public function actionCustomersBirthdays($day = 0, $month = 0, $year = 0, $name = '')
    {
        if ($month == 0) {
            $month = date('n');
        }

        $query = Contact::find()
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
            ->select(['contacts.id', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear'])
            ->with(['metas', 'bookings', 'bookings.product'])
            ->orderBy('bday, byear')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('qhkh_customers-birthdays', [
            'pagination'=>$pagination,
            'theUsers'=>$theUsers,
            'year'=>$year,
            'month'=>$month,
            'day'=>$day,
            'name'=>$name,
        ]);
    }

    /**
     * CR-related tasks
     */
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

        return $this->render('qhkh_tasks', [
            'theTours'=>$theTours,
            'month'=>$month,
            'monthList'=>$monthList,
        ]);
    }

    /**
     * Customers visits to offices
     */
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
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM contacts u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND SUBSTRING(tk.description,1,5)!="AC SG" AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'saigon') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM contacts u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC SG ", tk.description)=1 OR tk.description="AC SG") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'luangprabang') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM contacts u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC LP ", tk.description)=1 OR tk.description="AC LP") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } else {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM contacts u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
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

        return $this->render('qhkh_tasks-ac', [
            'theTasks'=>$theTasks,
            'thisWeek'=>$thisWeek,
            'prevWeek'=>$prevWeek,
            'nextWeek'=>$nextWeek,
            'at'=>$at,
        ]);
    }

    /**
     * Club Amba - Ampo
     */
    public function actionClubAmba()
    {
        $paxIdList = Meta::find()
            ->select(['rid'])
            ->where(['rtype'=>'user', 'name'=>'ambassaddor_potentiality', 'value'=>[5, 7]])
            ->asArray()
            ->column();
        $thePax = Contact::find()
            ->select(['id', 'name', 'gender', 'country_code', 'email', 'phone', 'bday', 'bmonth', 'byear'])
            ->with([
                'metas'=>function($q){
                    return $q->select('rid, format, value')->andWhere(['format'=>['email', 'tel']]);
                },
            ])
            ->where(['id'=>$paxIdList])
            ->asArray()
            ->all();
        return $this->render('qhkh_club-amba', [
            'thePax'=>$thePax,
        ]);
    }

    /**
     * Service Plus QHKH
     */
    public function actionServicePlus($action = 'list', $view = '', $year = '', $month = '', $tour = '', $success = '', $qhkh = '', $id = 0)
    {
        if (!in_array($action, ['list', 'view', 'add', 'edit', 'delete', 'ok', 'nok'])) {
            $action = 'list';
        }

        if ($action == 'list' || $action == 'view') {
            $viewList = [
                'tourstart'=>Yii::t('x', 'Tour start date'),
                'tourend'=>Yii::t('x', 'Tour end date'),
                'service'=>Yii::t('x', 'Service date'),
                'updated'=>Yii::t('x', 'Input date'),
                'tour'=>Yii::t('x', 'Tour code/name'),
            ];

            if (!array_key_exists($view, $viewList)) {
                $view = 'tourend';
            }

            for ($y = 2017; $y <= 1 + date('Y'); $y ++) {
                $yearList[$y] = $y;
            }

            if (!in_array($year, $yearList)) {
                $year = date('Y');
            }

            for ($m = 1; $m <= 12; $m ++) {
                $monthList[$m] = $m;
            }

            if (!in_array($month, $monthList)) {
                $month = '';
            }

            $successList = [
                'yes'=>Yii::t('x', 'Yes'),
                'no'=>Yii::t('x', 'No'),
                'empty'=>Yii::t('x', 'Not determined'),
            ];

            if (!array_key_exists($success, $successList)) {
                $success = '';
            }

            $query = ServicePlus::find()
                ->select(['services_plus.*', 'at_ct.day_from', 'at_ct.day_count', 'end_date'=>new \yii\db\Expression('IF(day_count=0,day_from,DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
                ->innerJoinWith('tour');

            if ($year != '') {
                if ($view == 'tourstart') {
                    if ($month == '') {
                        $query->andWhere('YEAR(day_from)=:year', [':year'=>$year]);
                    } else {
                        $query->andWhere('YEAR(day_from)=:year AND MONTH(day_from)=:month', [':year'=>$year, ':month'=>$month]);
                    }
                    $query->orderBy('day_from DESC');
                } elseif ($view == 'tourend') {
                    if ($month == '') {
                        $query->andHaving('YEAR(end_date)=:year', [':year'=>$year]);
                    } else {
                        $query->andHaving('YEAR(end_date)=:year AND MONTH(end_date)=:month', [':year'=>$year, ':month'=>$month]);
                    }
                    $query->orderBy('end_date DESC');
                } elseif ($view == 'service') {
                    if ($month == '') {
                        $query->andWhere('YEAR(svc_date)=:year', [':year'=>$year]);
                    } else {
                        $query->andWhere('YEAR(svc_date)=:year AND MONTH(svc_date)=:month', [':year'=>$year, ':month'=>$month]);
                    }
                    $query->orderBy('svc_date DESC');
                } elseif ($view == 'updated') {
                    if ($month == '') {
                        $query->andWhere('YEAR(updated_dt)=:year', [':year'=>$year]);
                    } else {
                        $query->andWhere('YEAR(updated_dt)=:year AND MONTH(updated_dt)=:month', [':year'=>$year, ':month'=>$month]);
                    }
                    $query->orderBy('updated_dt DESC');
                } else {
                    // Tour code
                    $tourIdList = Product::find()
                        ->where(['or', ['id'=>$tour], ['like', 'op_code', $tour], ['like', 'op_name', $tour]])
                        ->andWhere(['op_status'=>'op'])
                        ->asArray()
                        ->column();
                    $query->andWhere(['tour_id'=>$tourIdList])->orderBy('day_from DESC');
                }

                if ($success == 'empty') {
                    $query->andWhere('svc_success=""');
                } elseif ($success != '') {
                    $query->andWhere(['svc_success'=>$success]);
                }
            }

            if ((int)$qhkh != 0) {
                $query->andWhere(['services_plus.updated_by'=>(int)$qhkh]);
            }

            $countQuery = clone $query;
            $pagination = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize'=>25,
            ]);

            $theServices = $query
                ->with([
                    'tour'=>function($q){
                        return $q->select(['id', 'op_name', 'op_code', 'day_count', 'day_from']);
                    },
                    'updatedBy'=>function($q){
                        return $q->select(['id', 'name'=>'nickname']);
                    },
                    ])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $qhkhList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM users u, services_plus s WHERE s.updated_by=u.id GROUP BY s.updated_by ORDER BY u.fname, u.lname')->queryAll();
            $qhkhList = \yii\helpers\ArrayHelper::map($qhkhList, 'id', 'name');

            return $this->render('qhkh_service-plus', [
                'pagination' => $pagination,
                'theServices' => $theServices,
                'view' => $view,
                'year' => $year,
                'month' => $month,
                'tour'=>$tour,
                'success' => $success,
                'qhkh' => $qhkh,
                'viewList' => $viewList,
                'yearList' => $yearList,
                'monthList' => $monthList,
                'successList' => $successList,
                'qhkhList' => $qhkhList,
            ]);
        } elseif ($action == 'add') {
            Yii::$app->params['page_title'] = 'Thêm service plus';
            $theService = new ServicePlus;
            if ($theService->load(Yii::$app->request->post())) {
                $theTour = Product::find()->where('UPPER(op_code)=:code', [':code' => strtoupper($theService->code)])->one();
                if (!$theTour) {
                    throw new HttpException(404, "The tour not found");
                }
                $theService->tour_id = $theTour->id;
                $theService->created_dt = NOW;
                $theService->created_by = USER_ID;
                $theService->updated_dt = NOW;
                $theService->updated_by = USER_ID;

                if (!$theService->save(false)) {
                    throw new HttpException(401, "The service is not saved");
                }
                $this->redirect('/qhkh/service-plus?action=view&id='.$theService['id']);
            }
            return $this->render('qhkh_service-plus_u', [
                'theService' => $theService
            ]);
        } elseif ($action == 'edit') {
            Yii::$app->params['page_title'] = 'Sửa Service Plus:';
            $theService = ServicePlus::find()
                ->where(['id'=>$id])
                ->with(['tour'])
                ->one();
            if (!$theService) {
                throw new HttpException(404, 'Service Plus not found.');
            }
            if (!$theService['tour']) {
                throw new HttpException(404, 'Tour not found.');
            }

            if (!in_array(USER_ID, [1, 29123, $theService['created_by'], $theService['updated_by']])) {
                throw new HttpException(403, 'Access denied.');
            }

            $theService->code = $theService['tour']['op_code'];

            $oldSuccess = $theService->svc_success;

            if ($theService->load(Yii::$app->request->post())) {
                $theTour = Product::find()
                    ->where('UPPER(op_code)=:code', [':code' => strtoupper($theService->code)])
                    ->one();
                if (!$theTour) {
                    throw new HttpException(404, "The tour not found");
                }

                $theService->tour_id = $theTour->id;
                $theService->updated_dt = NOW;
                $theService->updated_by = USER_ID;

                if (in_array($theService->svc_success, ['yes', 'no', '']) && $theService->svc_success != $oldSuccess) {
                    $theService->svc_success_updated_dt = NOW;
                    $theService->svc_success_updated_by = USER_ID;
                }

                if (!$theService->save(false)) {
                    throw new HttpException(401, "The service is not saved");
                }
                $this->redirect('/qhkh/service-plus?action=view&id='.$theService['id']);
            }
            return $this->render('qhkh_service-plus_u', [
                'theService' => $theService
            ]);

        } elseif ($action == 'delete' && $id != 0) {
            $theService = ServicePlus::findOne($id);
            if (!$theService) {
                throw new HttpException(404, 'Service not found.');
            }
            if (!in_array(USER_ID, [1, 29123, $theService['created_by'], $theService['updated_by']])) {
                throw new HttpException(403, 'Access denied.');
            }
            if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
                $theService->delete();
                return $this->redirect('?action=list');
            }
            return $this->render('qhkh_service-plus_d', [
                'theService' => $theService
            ]);

        } elseif (($action == 'ok' || $action == 'nok') && $id != 0) {
            $theService = ServicePlus::findOne($id);
            if (!$theService) {
                throw new HttpException(404, 'Service not found.');
            }
            if (!in_array(USER_ID, [1, 29123, $theService['created_by'], $theService['updated_by']])) {
                throw new HttpException(403, 'Access denied.');
            }

            $theService->svc_success = $action == 'ok' ? 'yes' : 'no';
            $theService->svc_success_updated_dt = NOW;
            $theService->svc_success_updated_by = USER_ID;
            $theService->save(false);
            return $this->redirect('?action=list');
        }

    }

    /**
     * Index page for QHKH
     */
    public function actionQuyTrinhThuMau()
    {
        return $this->render('qhkh_quy-trinh-thu-mau');
    }

    /**
     * Chot tour (tour_stats)
     */
    public function actionChotTour($tour_id = 0, $month = 0, $year = 0, $fg = 'f', $staff = '', $diem = 0, $ketthuc = '', $khaithac = 0)
    {
        // Edit single
        if ($tour_id != 0) {
            $theTour = Product::find()
                ->select(['id', 'op_name', 'op_code', 'op_finish', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(at_ct.day_count=0, at_ct.day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
                ->andWhere(['op_status'=>'op', 'id'=>$tour_id])
                ->with([
                    'tourStats',
                    'tour',
                    'tour.cskh'=>function($q){
                        return $q->select(['id', 'name'=>'nickname']);
                    },
                    ])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }
            $theTourOld = Tour::find()
                ->where(['ct_id'=>$theTour['id']])
                ->asArray()
                ->one();
            if (!$theTourOld) {
                throw new HttpException(404, 'Tour not found.');
            }

            // 181005 Thu, NgAnh, Diep, Huong
            $qhkhIdList = [29123, 1, 47034, 12952, 49949];
            foreach ($theTour['tour']['cskh'] as $cskh) {
                $qhkhIdList[] = $cskh['id'];
            }
            if (!in_array(USER_ID, $qhkhIdList)) {
                throw new HttpException(403, 'Access denied.');
            }

            $theForm = new \app\models\ChotTourForm;
            $theForm->qhkh_ketthuc = $theTour['tourStats']['qhkh_ketthuc'] ?? '';
            $theForm->qhkh_diem = $theTour['tourStats']['qhkh_diem'] ?? 0;
            $theForm->khach_diem = $theTourOld['pax_ratings'] ?? $theTour['tourStats']['khach_diem'] ?? 0;
            $theForm->qhkh_da_khaithac = explode('|', $theTour['tourStats']['qhkh_khaithac'] ?? '');
            $theForm->qhkh_dexuat_khaithac = explode('|', $theTour['tourStats']['qhkh_khaithac'] ?? '');
            $theForm->mkt_da_khaithac = explode('|', $theTour['tourStats']['mkt_khaithac'] ?? '');
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

                $theForm->qhkh_khaithac = [];
                if (!empty($theForm->qhkh_da_khaithac)) {
                    $theForm->qhkh_khaithac = $theForm->qhkh_da_khaithac;
                }
                if (!empty($theForm->qhkh_dexuat_khaithac)) {
                    $theForm->qhkh_khaithac = array_merge($theForm->qhkh_khaithac, $theForm->qhkh_dexuat_khaithac);
                }

                if (isset($_GET['xh'])) {
                    // \fCore::expose($_POST);
                    // \fCore::expose($theForm);
                    // exit;
                }

                if (USER_ID == 18598) {
                    // Cao Nhung only mkt
                    $sql = 'UPDATE at_tour_stats SET mkt_khaithac=:v4 WHERE tour_id=:id';
                    Yii::$app->db->createCommand($sql, [
                        ':v4'=>!empty($theForm->mkt_da_khaithac) ? implode('|', $theForm->mkt_da_khaithac) : '',
                        ':id'=>$theTour['id'],
                        ])->execute();

                } else {
                    $sql = 'UPDATE at_tour_stats SET qhkh_ketthuc=:v1, qhkh_khaithac=:v2, qhkh_diem=:v3 , khach_diem=:v4 WHERE tour_id=:id';
                    Yii::$app->db->createCommand($sql, [
                        ':v1'=>$theForm->qhkh_ketthuc,
                        ':v2'=>!empty($theForm->qhkh_khaithac) ? implode('|', $theForm->qhkh_khaithac) : '',
                        ':v3'=>$theForm->qhkh_diem,
                        ':v4'=>$theForm->khach_diem,
                        ':id'=>$theTour['id'],
                        ])->execute();
                    $sql = 'UPDATE at_tours SET pax_ratings=:v4 WHERE id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':v4'=>$theForm->khach_diem,
                        ':id'=>$theTourOld['id'],
                        ])->execute();

                }
                Yii::$app->session->setFlash('success', 'Đã chốt tour: '.$theTour['op_code']);
                $ymd = explode('-', $theTour['end_date']);
                return $this->redirect('?year='.$ymd[0].'&month='.(int)$ymd[1]);
            }

            return $this->render('qhkh_chot-tour_id', [
                'theTour'=>$theTour,
                'theForm'=>$theForm,
            ]);
        }

        // View all tours in month
        if ($month == 0) {
            $month = date('n');
        }
        if ($year == 0) {
            $year = date('Y');
        }

        $query = Product::find()
            ->select(['at_ct.id', 'op_name', 'op_code', 'op_finish', 'at_ct.day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(at_ct.day_count=0, at_ct.day_from, DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->andWhere(['op_status'=>'op'])
            ->andHaving('YEAR(end_date)=:y AND MONTH(end_date)=:m', [':y'=>$year, ':m'=>$month])
            ->innerJoinWith('tourStats');
        if ($fg == 'f') {
            $query->andWhere('SUBSTRING(op_code, 1, 1)="F"');
        }
        if ($ketthuc != '') {
            $query->andWhere(['qhkh_ketthuc'=>$ketthuc]);
        }
        if ($khaithac != 0) {
            $query->andWhere('LOCATE(:k, qhkh_khaithac)!=0', [':k'=>$khaithac]);
        }
        if ($diem != 0) {
            $query->andWhere(['qhkh_diem'=>$diem]);
        }

        $theTours = $query
            ->with([
                'tour',
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tourStats',
                'bookings',
                'bookings.case',
                'bookings.case.people'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'bookings.case.people.metas'=>function($q){
                    return $q->select(['id', 'rid', 'value'])->andWhere(['format'=>'email']);
                },
                ])
            ->orderBy('end_date')
            ->asArray()
            ->all();

        if ((int)$staff != 0) {
            $filteredTours = [];
            foreach ($theTours as $tour) {
                foreach ($tour['tour']['cskh'] as $qhkh) {
                    if ($qhkh['id'] == (int)$staff) {
                        $filteredTours[] = $tour;
                        break;
                    }
                }
            }
            $theTours = $filteredTours;
        }

        $sql = 'SELECT user_id FROM at_tour_user WHERE role="cservice" GROUP BY user_id';
        $staffIdList = Yii::$app->db->createCommand($sql)->queryColumn();
        $staffList = User::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on', 'id'=>$staffIdList])
            ->asArray()
            ->all();
        $staffList = \yii\helpers\ArrayHelper::map($staffList, 'id', 'name');

        return $this->render('qhkh_chot-tour', [
            'theTours'=>$theTours,
            'year'=>$year,
            'month'=>$month,
            'fg'=>$fg,
            'staff'=>$staff,
            'ketthuc'=>$ketthuc,
            'khaithac'=>$khaithac,
            'diem'=>$diem,
            'staffList'=>$staffList,
            ]);
    }

    /**
     * Quy QHKH
     */
    public function actionQuyQhkh($action = '', $month = '', $year = '')
    {
        // View month
        if ($action == 'view-month') {
            if (strlen($month) != 7) {
                $month = date('Y-m');
            }
            $theTours = Product::find()
                // ->select()
                ->with(['bookings'])
                ->where(['op_status'=>'op'])
                ->andWhere('SUBSTRING(day_from,1,7)=:month', [':month'=>$month])
                ->andWhere('SUBSTRING(op_code,1,1)!="G"')
                ->orderBy('day_from')
                ->asArray()
                ->all();
            // foreach ($theTours as $tour) {
            //     foreach ($tour['bookings'] as $booking) {
            //         if (substr($tour['op_code'], 0, 1) == 'G') {
            //     $sql2 = 'UPDATE at_bookings SET quy_qhkh=0, quy_qhkh_updated_dt=0, quy_qhkh_updated_by=0 WHERE id=:id LIMIT 1';
            //     Yii::$app->db->createCommand($sql2, [
            //         ':id'=>$booking['id'],
            //         ])->execute();
            //         }
            //     }
            // }
            return $this->render('qhkh_quy-qhkh_view-month', [
                'theTours'=>$theTours,
                ]);

        }
        // Huan update
        if ($action == 'huan-update') {
            $sql = 'select b.id, t.op_code, t.op_name, b.pax, t.day_count, t.day_from, op_finish from at_bookings b, at_ct t where SUBSTRING(t.op_code,1,1)!="G" AND b.product_id=t.id and year(day_from)=:year and month(day_from)=:month and t.op_status="op"';
            $bookings = Yii::$app->db->createCommand($sql, [':month'=>$month, ':year'=>$year])->queryAll();
            foreach ($bookings as $booking) {
                if ($booking['day_count'] >= 5) {
                    $quy = 10 * $booking['pax'];
                } else {
                    $quy = 5 * $booking['pax'];
                }
                echo '<br>', $booking['id'], ' : ', $booking['op_code'],' (', $booking['pax'], 'p ', $booking['day_count'], 'd) = ', $quy;
                if ($booking['day_count'] < 5) {
                    echo ' -- SMALL --';
                }
                $sql2 = 'UPDATE at_bookings SET quy_qhkh=:q, quy_qhkh_updated_dt=:now, quy_qhkh_updated_by=:me WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql2, [
                    ':q'=>$quy,
                    ':now'=>NOW,
                    ':me'=>USER_ID,
                    ':id'=>$booking['id'],
                    ])->execute();
            }
            exit;
        }

        $thuQuyQhkh = Yii::$app->db->createCommand('select SUM(quy_qhkh) AS tong, SUBSTRING(p.day_from, 1, 7) AS thang from at_bookings b, at_ct p where b.product_id=p.id AND quy_qhkh>0 GROUP BY thang ORDER BY thang DESC')->queryAll();
        $chiQuyQhkh = Yii::$app->db->createCommand('select SUM(quy_qhkh) AS tong, SUBSTRING(quy_qhkh_updated_dt, 1, 10) AS thang from at_bookings where quy_qhkh>0 GROUP BY SUBSTRING(quy_qhkh_updated_dt,1,7) ORDER BY SUBSTRING(quy_qhkh_updated_dt,1,7) DESC')->queryAll();
        return $this->render('qhkh_quy-qhkh', [
            'thuQuyQhkh'=>$thuQuyQhkh,
            'chiQuyQhkh'=>$chiQuyQhkh,
        ]);
    }


}
