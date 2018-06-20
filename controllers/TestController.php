<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;

use common\models\Company;
use common\models\Country;
use common\models\Destination;
use common\models\Venue;
use common\models\User;
use common\models\Search;
use common\models\Ct;
use common\models\Cpt;
use common\models\Kase;
use common\models\Inquiry;
use common\models\Note;
use common\models\Sysnote;
use common\models\Tour;
use common\models\Product;
use common\models\Booking;
use common\models\Task;
use Mailgun\Mailgun;

use app\models\Dv;
use app\models\Cp;

use yii\data\GridView;
use yii\data\ActiveDataProvider;

class TestController extends MyController
{
    // Ecobus
    public function actionEcobus($from = '')
    {
        return $this->render('test_ecobus');
    }

    // 160608 Thy tim khach 45+ lost 
    public function actionCase45($case = 0, $rename = '')
    {
        $sql = '';
        //Yii::$app->db->createCommand($sql)->queryAll();
        $cases = Kase::find()
            ->select(['id', 'name'])
            ->where('YEAR(created_at) IN (2011, 2012, 2013, 2014)')
            ->andWhere(['is_b2b'=>'no', 'deal_status'=>'lost', 'status'=>'closed'])
            ->with([
                'inquiries'
            ])
            ->asArray()
            ->all();
        echo count($cases), ' found<hr><table cellpadding=0 cellspacing=0 border=1>';
        foreach ($cases as $case) {
            echo '<tr><td>', $case['id'], '</td><td>', \yii\helpers\Html::a($case['name'], '/cases/r/'.$case['id'], ['target'=>'_blank']), '</td><td>';
            foreach ($case['inquiries'] as $inquiry) {
                $data = @unserialize($inquiry['data']);
                if (isset($data['agesOfTravelers12'])) {
                    $age = $data['agesOfTravelers12'];
                    echo $inquiry['name'], '</td><td>', $inquiry['email'], '</td><td>', $age;
                } else {
                    echo $inquiry['name'], '</td><td>', $inquiry['email'], '</td><td>', '';
                }
                break;
            }
            if (empty($case['inquiries'])) {
                echo '</td><td></td><td>';
            }
            echo '</td></tr>';
        }
    }

    public function actionCaseDupNames($case = 0, $rename = '')
    {
        // Tim cac hs ten blank
        $sql = 'select id, (SELECT found FROM at_search s WHERE s.rtype="case" AND s.rid=at_cases.id LIMIT 1) AS found FROM at_cases WHERE name="" ORDER BY id DESC LIMIT 100';
        $cases = Yii::$app->db->createCommand($sql)->queryAll();
        echo '<html><head><META http-equiv="refresh" content="5;URL=https://my.amicatravel.com/test/case-dup-names"><style>body {font:15px Helvetica;}</style></head><body>';

        foreach ($cases as $case) {
            echo '<br>', $case['id'], ' - ', $case['found'];
            $sql = 'UPDATE at_cases SET name=:name WHERE id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':name'=>$case['found'], ':id'=>$case['id']])->execute();
        }


        exit;
        $sql = 'select rid, found from at_search where rtype="case" AND substring(found,-3)="(2)" LIMIT 10';
        $ff = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($ff as $f) {
            //echo '<br>', $f['found'];
            $sql = 'UPDATE at_cases SET name="'.$f['found'].'" WHERE id='.$f['rid'].' LIMIT 1;';
            echo '<br>', $sql;
            //Yii::$app->db->createCommand($sql, [':name'=>$f['found'], ':id'=>$f['rid']])->execute();
        }

        exit;
        if ((int)$case != 0 && $rename != '' && USER_ID == 1) {
            $sql = 'UPDATE at_cases SET name=:name WHERE id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':name'=>$rename, ':id'=>$case])->execute();
            $sql = 'UPDATE at_search SET found=:name WHERE rtype="case" AND rid=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':name'=>$rename, ':id'=>$case])->execute();
            return $this->redirect(DIR.URI);
        }

        $sql = 'select name, count(*) as x from at_cases group by name having x>1 order by x;';
        $dupcases = Yii::$app->db->createCommand($sql)->queryAll();
        echo count($dupcases);
        foreach ($dupcases as $dupcase) {
            $cases = \common\models\Kase::find()
                ->select(['id', 'name', 'created_at', 'how_found'])
                ->where(['name'=>$dupcase['name']])
                ->with(['people'])
                ->orderBy('created_at')
                ->asArray()
                ->all();
            echo '<hr>';
            foreach ($cases as $case) {
                echo '<br>', \yii\helpers\Html::a($case['id'], '/cases/u/'.$case['id']), ' / ', $case['name'], ' / ', $case['how_found'], ' : ', date('j.n.Y', strtotime($case['created_at']));
                $name1 = $case['name'].' (2)';
                echo \yii\helpers\Html::a('<br>=== Rename: '.$name1, '?case='.$case['id'].'&rename='.urlencode($name1));
                $name2 = $case['name'].' ('.substr($case['created_at'], 0, 4).')';
                echo \yii\helpers\Html::a('<br>=== Rename: '.$name2, '?case='.$case['id'].'&rename='.urlencode($name2));
                $name3 = $case['name'].' ('.date('n-Y', strtotime($case['created_at'])).')';
                echo \yii\helpers\Html::a('<br>=== Rename: '.$name3, '?case='.$case['id'].'&rename='.urlencode($name3));
                echo '<br>';
                foreach ($case['people'] as $user) {
                    echo ' |=== ', $user['name'], ' ', $user['email'], ' ', $user['country_code'], ' ', $user['byear'];
                }

            }
        }
    }

    // 160106 Test DVT
    public function actionDv($type = '', $dest = '', $name = '', $provider = '', $time = '', $cond = '')
    {
        $query = Dv::find();

        if ($type != '') {
            $query->andWhere(['stype'=>(int)$type]);
        }
        if ($dest != '') {
            $query->andWhere(['dest_id'=>(int)$dest]);
        }
        if ($provider != '') {
            $query->andWhere(['or', ['venue_id'=>(int)$provider], ['provider_id'=>(int)$provider]]);
        }
        if ($time != '') {
            //$query->andWhere(['or', ['venue_id'=>(int)$vendor], ['provider_id'=>(int)$vendor]]);
        }
        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }

        $theDvx = $query
            ->with([
                'venue',
                'provider',
                'destination'
                ])
            ->asArray()
            ->limit(100)
            ->offset(0)
            ->all();

        $destList = Destination::find()
            ->select(['id', 'name_vi'])
            ->asArray()
            ->all();

        $venueList = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('test_dv', [
            'theDvx'=>$theDvx,
            'type'=>$type,
            'dest'=>$dest,
            'name'=>$name,
            'provider'=>$provider,
            'time'=>$time,
            'cond'=>$cond,
            'destList'=>$destList,
            'venueList'=>$venueList,
        ]);
    }

    // Add dv
    public function actionDvc()
    {
        $theDv = new Dv();
        $theDv->scenario = 'dv/c';
        if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
            $theDv->save(false);
            Yii::$app->session->setFlash('success', 'Đã thêm dịch vụ: '.$theDv['name']);
            return $this->redirect('/test/dv');
        }

        $destList = Destination::find()
            ->select(['id', 'name_vi'])
            ->asArray()
            ->all();

        $venueList = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('test_dvc', [
            'theDv'=>$theDv,
            'destList'=>$destList,
            'venueList'=>$venueList,
            'companyList'=>$companyList,
        ]);
    }

    // Edit dvt
    public function actionDvu($id = 0)
    {
        $theDv = Dv::findOne($id);
        $theDv->scenario = 'dv/u';
        if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
            $theDv->save(false);
            Yii::$app->session->setFlash('success', 'Đã update dịch vụ: '.$theDv['name']);
            return $this->redirect('/test/dv');
        }

        $destList = Destination::find()
            ->select(['id', 'name_vi'])
            ->asArray()
            ->all();

        $venueList = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('test_dvc', [
            'theDv'=>$theDv,
            'destList'=>$destList,
            'venueList'=>$venueList,
            'companyList'=>$companyList,
        ]);
    }

    // Read dvt
    public function actionDvr($id = 0)
    {
        $theDv = Dv::find()
            ->where(['id'=>$id])
            ->with([
                'destination',
                'venue',
                'provider',
                ])
            ->asArray()
            ->one();

        return $this->render('test_dvr', [
            'theDv'=>$theDv,
        ]);
    }

    // 151229 Tuyet Chinh muon thong ke su dung tau be va nha dan
    public function actionChinh($dest = 0, $type = 'hotel', $star = 0, $zero = 'yes', $sort = 'desc', $orderby = 'bookings')
    {
        $andDest = '';
        $andType = '';
        $andStar = '';
        $andZero = '';
        if ($dest != 0) {
            $andDest = ' AND venue_dest='.(int)$dest;
        }
        if (in_array($type, ['hotel', 'home', 'cruise'])) {
            $andType = ' AND venue_type="'.$type.'"';
        }
        if ((int)$star > 1) {
            $andStar = ' AND venue_stars='.(int)$star;
        }
        if ($zero == 'no') {
            $andZero = ' AND bookings_2015!=0';
        }
        $orderByText = 'bookings_2015 ';
        if ($orderby == 'stars') {
            $orderByText = 'venue_stars ';
        }
        if ($orderby == 'name') {
            $orderByText = 'venue_name ';
        }
        $sortText = 'DESC ';
        if ($sort == 'asc') {
            $sortText = 'ASC ';
        }

        $sql = 'SELECT d.id, d.name_vi FROM at_destinations d, venues v WHERE v.destination_id=d.id ORDER BY name_vi';
        $destList = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT d.name_vi, c.* FROM at_chinh1 c, at_destinations d WHERE d.id=c.venue_dest AND c.venue_type IN ("hotel", "home", "cruise", "restaurant") AND venue_status IN ("on", "draft") '.$andDest.$andType.$andStar.$andZero.' ORDER BY '.$orderByText.$sortText.', c.venue_type, c.venue_dest, c.venue_stars';
        $results = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('test_chinh', [
            'results'=>$results,
            'dest'=>$dest,
            'type'=>$type,
            'star'=>$star,
            'zero'=>$zero,
            'orderby'=>$orderby,
            'sort'=>$sort,
            'destList'=>$destList,
        ]);
    }

    // 151229 Tuyet Chinh muon thong ke su dung tau be va nha dan
    public function actionChinh1x()
    {
        /*
        151231 UPDATE NIGHT COUNT 2015

        $sql = 'select venue_id from cpt where year(dvtour_day)=2015 and venue_id!=0 AND (dvtour_name="Khách sạn" OR dvtour_name="Hotel" OR dvtour_name="Tàu ngủ đêm" OR dvtour_name="Tàu Hạ Long" OR dvtour_name="nhà dân" OR dvtour_name="Accommodation") group by venue_id, dvtour_day order by venue_id';
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $data = [];
        foreach ($results as $r) {
            if (!isset($data[$r['venue_id']])) {
                $data[$r['venue_id']] = [
                    'nights'=>1,
                ];
            } else {
                $data[$r['venue_id']]['nights'] ++;
            }
        }
        foreach ($data as $id=>$item) {
            $sql = 'UPDATE at_chinh1 SET nights_2015=:n WHERE venue_id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [
                ':n'=>$item['nights'],
                ':id'=>$id,
                ])->execute();
            echo '<br>Doing id #', $id, ' - ', $item['nights'];
        }
        echo '<br>DONE.';
        */
        /*
        151231 UPDATE BOOKINGS, PAX COUNT 2015
        $data = [];
        $sql = 'select venue_id, tour_id from cpt where year(dvtour_day)=2015 and venue_id!=0 group by venue_id, tour_id order by venue_id';
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($results as $r) {
            $sql = 'SELECT b.pax FROM at_bookings b, at_ct p, at_tours t WHERE p.op_status="op" AND p.op_finish!="canceled" AND t.ct_id=p.id AND b.product_id=p.id AND t.id=:id';
            $bookings = Yii::$app->db->createCommand($sql, [':id'=>$r['tour_id']])->queryAll();
            $paxCnt = 0;
            $bookingCnt = 0;
            foreach ($bookings as $booking) {
                $bookingCnt ++;
                $paxCnt += $booking['pax'];
            }

            if (!isset($data[$r['venue_id']])) {
                $data[$r['venue_id']] = [
                    'venue_id'=>$r['venue_id'],
                    'bookings'=>$bookingCnt,
                    'pax'=>$paxCnt,
                ];
            } else {
                $data[$r['venue_id']]['bookings'] += $bookingCnt;
                $data[$r['venue_id']]['pax'] += $paxCnt;
            }
        }

        foreach ($data as $id=>$item) {
            $sql = 'UPDATE at_chinh1 SET bookings_2015=:b, pax_2015=:p WHERE venue_id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [
                ':b'=>$item['bookings'],
                ':p'=>$item['pax'],
                ':id'=>$id,
                ])->execute();
            echo '<br>Doing id #', $id, ' - ', $item['bookings'], ' - ', $item['pax'];
        }
        echo '<br>DONE.';
        */
    }

    public function actionChinh1()
    {
        $sql = 'select v.id, v.name, t.code, cp.tour_id from cpt cp, venues v, at_tours t where v.stype="home" AND t.id=cp.tour_id AND cp.venue_id=v.id AND YEAR(cp.dvtour_day)=2015 AND t.status!="deleted" GROUP BY cp.venue_id, cp.tour_id ORDER BY v.name';
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $venues = [];
        $tourIdList = [];
        foreach ($results as $result) {
            $tourIdList[] = $result['tour_id'];
        }
        $tourIdList = array_unique($tourIdList);
        $pax = [];
        foreach ($tourIdList as $id) {
            $sql = 'SELECT b.pax FROM at_bookings b, at_ct p, at_tours t WHERE t.ct_id=p.id AND p.id=b.product_id AND t.id=:id LIMIT 1';
            $cnt = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryScalar();
            $pax[$id] = $cnt;
        }

        foreach ($results as $result) {
            if (isset($venues[$result['id']])) {
                $venues[$result['id']]['tours'] ++;
                $venues[$result['id']]['pax'] += $pax[$result['tour_id']];
            } else {
                $venues[$result['id']] = [
                    'name'=>$result['name'],
                    'tours'=>1,
                    'pax'=>$pax[$result['tour_id']],
                ];
            }
        }
        echo '<h3>THONG KE SU DUNG NHA DAN 2015 (SELECT ALL + COPY + PASTE VAO EXCEL)</h3>
        <style>.r {text-align:right} body {font-size:15px; font-family:sans-serif;} td, th {padding:5px; border:1px solid #ccc;}</style>
        <table>
            <tr><th>Nha dan</th><th>So tour</th><th>So pax</th></tr>';
        foreach ($venues as $id=>$venue) {
            echo '
            <tr><td>', $venue['name'], '</td><td class="r">', $venue['tours'], '</td><td class="r">', $venue['pax'], '</td></tr>
            ';
        }
        echo '</table>';
    }

    public function actionExcel()
    {
        $allModels = User::find(['is_member'=>'yes'])->limit(21)->all();
        \moonland\phpexcel\Excel::widget([
        'models' => $allModels,
        'mode' => 'export', //default value as 'export'
        'columns' => ['fname','lname','email'], //without header working, because the header will be get label from attribute label. 
        //'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'], 
        ]);

        \moonland\phpexcel\Excel::export([
        'models' => $allModels, 
        'columns' => ['fname','lname','email'], //without header working, because the header will be get label from attribute label. 
        //'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
        ]);
    }

    // Thêm email rules cho các case hiện đang mở
    public function actionEmailRules()
    {
        $openCases = Kase::find()
            ->select(['id', 'name', 'owner_id', 'created_at'])
            ->with([
                'owner'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'people'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'people.metas'=>function($q) {
                    return $q->select(['v', 'rid'])->where(['k'=>'email', 'rtype'=>'user']);
                },
            ])
            ->where(['status'=>'open', 'is_b2b'=>'no'])
            ->andWhere('YEAR(created_at)="2014"')
            ->orderBy('created_at DESC')
            ->asArray()
            ->all();
        return $this->render('test_email-rules', [
            'openCases' => $openCases,
        ]);
    }

    // ct/c
    public function actionCtC()
    {
        $theCt = new Ct;
        $theCt->scenario = 'create';
        if ($theCt->load(Yii::$app->request->post()) && $theCt->validate()) {
            $theCt->uo = NOW;
            $theCt->ub = Yii::$app->user->id;
            $theCt->save();
            return $this->redirect('@web/ct/r/'.$theCt->id);
        }
        return $this->render('ct_c', [
            'theCt'=>$theCt,
        ]);
    }

    // CT Clone
    public function actionRbac()
    {
        // $auth = Yii::$app->authManager;
        // $p = $auth->createPermission('do_this');
        // $p->description = 'Do this';
        // $auth->add($p);

        // $r = $auth->createRole('this');
        // $auth->add($r);
        // $auth->addChild($r, $p);

        // $auth->assign($r, USER_ID);

        Yii::$app->params['page_title'] = 'I cannot do this';
        if (Yii::$app->user->can('do_this')) {
            Yii::$app->params['page_title'] = 'I CAN DO THIS';
        }

        return $this->render('test_rbac', [
        ]);
    }

    // Tour cal
    public function actionTourCal()
    {
        return $this->render('tour-cal');
    }

    // Identity cookie
    public function actionIden()
    {
        if (isset($_COOKIE['_identity'])) {
            $value = $_COOKIE['_identity'];
            $value = explode(':"', $value);
            $value = trim($value[1], '";');
            $data = json_decode($value, true);
            \fCore::expose($data);
            if (count($data) === 3 && isset($data[0], $data[1], $data[2])) {
                list ($id, $authKey, $duration) = $data;

                $ip = Yii::$app->getRequest()->getUserIP();
                echo "User '$id' with AuthKey '$authKey' logged in from $ip via cookie. Duration: $duration", __METHOD__;
            }
        } else {
            echo 'Cookie not set';
        }
    }

    // Community
    public function actionComm()
    {
        $this->layout = 'comm';
        return $this->render('test_comm');
    }

    // General test
    public function actionIndex($pass = '') {
        if ($pass != '') {
            echo Yii::$app->security->generatePasswordHash($pass);
            exit;
        }
        return $this->render('test_index', [
            'pass'=>$pass,
        ]);
    }

    // 150909 Van Nga yeu cau danh sach khach tour de nop cong an
    public function actionVannga() {
        $startDateFrom = '2015-06-01';
        $startDateUntil = '2015-09-01';
        // cac tour khoi hanh trong khoang thoi gian noi tren
        $sql = 'select t.id, t.op_name, t.op_code, day_from, day_count, (select id from at_tours where at_tours.ct_id=t.id limit 1) AS tid from at_ct t where op_status="op" and t.day_from>=:from and t.day_from<:until and op_finish!="canceled" order by day_from';
        $theTours = Yii::$app->db->createCommand($sql, [':from'=>$startDateFrom, ':until'=>$startDateUntil])->queryAll();
        $tourIdList = [];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }
        $sql = 'select u.id, u.fname, u.lname, u.bday, u.bmonth, u.byear, u.gender, b.product_id AS tour_id, (select name_en from at_countries c where code=country_code limit 1) as country from persons u, at_booking_user bu, at_bookings b where b.id=bu.booking_id AND bu.user_id=u.id AND b.product_id IN ('.implode(',', $tourIdList).')';
        $theCustomers = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('test_vannga', [
            'theTours'=>$theTours,
            'theCustomers'=>$theCustomers,
        ]);
    }   

    // Add missing user search 131207-1114
    public function actionFixusersearch()
    {
        USER_ID == 1 || die('MR HUAN ONLY');

        $query = User::find();
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
            ]);
        $models = $query
            ->select(['id', 'fname', 'lname', 'email', 'phone'])
            ->with(['search'=>function($query) {
                return $query->select(['rid', 'search', 'found']);
            }])
            ->orderBy('persons.id')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        $insertData = [];
        foreach ($models as $li) {
            if (!isset($li['search'])) {
                $insertData[] = [
                    'user',
                    $li['id'],
                    strtolower(trim($li['fname'].$li['lname'].' '.$li['email'].' '.$li['phone'])),
                    trim($li['fname'].' '.$li['lname'].' '.$li['email'].' '.$li['phone']),
                ];
            }
        }
        if (!empty($insertData)) {
            Yii::$app->db->createCommand()->batchInsert('at_search', ['rtype', 'rid', 'search', 'found'], $insertData)->execute();
        }

        return $this->render('fixusersearch', [
            'pages'=>$pages,
            'models'=>$models,
            ]
        );
    }

    // Check hotel addresses / locations
    public function actionHotels()
    {
        $query = Venue::find()->where(['stype'=>'hotel']);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
            ]);
        $models = $query
            ->with(['destination', 'metas', 'company', 'ncc'])
            ->orderBy('destination_id, name')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('test_hotels', [
                'pages'=>$pages,
                'models'=>$models,
            ]
        );
    }

    // Send 2014 email for Thu
    public function actionSendMar8()
    {
        die('SENT');
            $this->mgIt(
            'Chúc mừng Quốc tế Phụ nữ 8/3 - Mời giao lưu ăn trưa',
            'cm8-3-2016',
            [
                'theCase'=>0,
            ],
            [
                ['from', 'AnhEm@amicatravel.com', 'Anh em', 'Amica Travel'],
                ['bcc', 'duc.manh@amicatravel.com'],
                ['bcc', 'hieu@amicatravel.com'],
                ['bcc', 'thu.huong@amicatravel.com'],
                ['bcc', 'hn.huan@gmail.com'],
            ]
        );
    }

    public function actionTenks()
    {
        // Viet tat ten ks
        $venues = Venue::find()
            ->select(['id', 'name'])
            ->where(['stype'=>'hotel', 'abbr'=>''])
            ->limit(500)
            ->all();
        $cnt = 0;
        foreach ($venues as $v) {
            echo ++$cnt;
            $name = explode(' ', $v['name']);
            $abbr = '';
            foreach ($name as $n) {
                if ($n != 'Hotel' && $n != 'hotel' && $n != '&') {
                    $abbr .= substr($n,0,1);
                }
            }
            $abbr = strtolower($abbr);
            echo ' ', $v['name'], ' = ', $abbr, '<br>';
        }
    }

    public function actionClone()
    {
        // Clone ct tour
        die('DONE');
        $ct1 = Ct::find()->one();
        echo $ct1->id;
        $ct = new Ct;
        $ct->setAttributes($ct1->getAttributes(null, ['id']), false);

        $ct->scenario = 'test_clone';
        $ct->offer_count = 0;
        $ct->uo = NOW;
        $ct->ub = 1;
        \fCore::expose($ct);
        exit;

        $ct->save();
        echo ' == '.$ct->id.' '.$ct->title;
    }

    // Test cases/c
    public function actionCasesc()
    {
        $theCase = new Kase;
        $theCase->scenario = 'cases_c';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            $theCase->validate();
        }

        return $this->render('cases_u', [
            'theCase'=>$theCase,
        ]);
    }

    public function actionCc($id = 0)
    {
        $theCase = Kase::findOne($id);

        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }

        if (Yii::$app->user->id > 4 && Yii::$app->user->id != 'DoanHa' && $theCase['owner_id'] != Yii::$app->user->id) {
            throw new HttpException(403, 'Access denied.');
            
        }

        if ($theCase['status_2'] == 'closed') {
            throw new HttpException(403, 'Case is already CLOSED.');
        }

        $theCase->scenario = 'cases_close';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            //$theCase->save();
            $theNote = new Sysnote;
            $theNote->created_at = NOW;
            $theNote->user_id = Yii::$app->user->id;
            $theNote->uri = URI;
            $theNote->action = 'kase/close';
            $theNote->ip = Yii::$app->request->getUserIP();
            $theNote->info = $theCase['closed_note'];
            $theNote->save();

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        // Close a case
        return $this->render('//kase/cases_close', [
            'theCase'=>$theCase
        ]);
    }

    public function actionCr($id = 0)
    {
        $theCase = Kase::findOne($id);

        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }

        $caseSysnotes = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            //->orderBy('created_at DESC')
            ->asArray()
            ->all();

        $caseNotes = Note::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->asArray()
            ->all();

        $caseInquiries = Inquiry::find()
            ->where(['case_id'=>$id])
            ->asArray()
            ->all();

        $timeline = [];
        foreach ($caseNotes as $li) {
            $timeline[$li['co']] = ['rtype'=>'note', 'rid'=>$li['id']];
        }
        foreach ($caseSysnotes as $li) {
            $timeline[$li['created_at']] = ['rtype'=>'sysnote', 'rid'=>$li['id']];
        }
        foreach ($caseInquiries as $li) {
            $timeline[$li['created_at']] = ['rtype'=>'inquiry', 'rid'=>$li['id']];
        }
        krsort($timeline);

        \fCore::expose($timeline);
        \fCore::expose($caseInquiries);
        \fCore::expose($caseSysnotes);
        \fCore::expose($caseNotes);
        exit;

        return $this->render('//kase/cases_r', [
            'theCase'=>$theCase,
            'timeline'=>$timeline,
        ]);
    }

    public function actionPjax()
    {
        if (isset($_GET['a'])) {
            $this->layout = false;
            if ($_GET['a'] == 'b') {
                echo 'A = Trao đổi với VnExpress trước buổi họp báo sáng 26/4, ông Nguyễn Sự, Bí thư Hội An (Quảng Nam), chia sẻ việc quản lý chặt du khách ra vào phố cổ phải mua vé không phải là "tận thu" mà để chống thất thu. Trong những ngày qua bản thân ông nhận được nhiều tin nhắn của những người dân tỏ thái độ khó chịu trước những dư luận không hay về thành phố này và chính bản thân ông cảm thấy có lỗi.';
            } else {
                echo 'Sự việc vừa qua cho thấy mọi người rất quan tâm đến Hội An. Tôi và lãnh đạo thành phố không phẫn nộ mà phải cảm ơn dư luận, cộng đồng mạng và báo chí. Đây chính là vấn đề để người quản lý, người lãnh đạo, người tổ chức thực hiện nhìn lại mình, nhận ra cái vô lý thì mình phải bỏ, cái bất hợp lý thì phải điều chỉnh.';
            }
            exit;
        }
        /*
        $dataProvider = new ActiveDataProvider([
            'query' => Venue::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        */
        return $this->render('test_pjax', [
            //'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNew()
    {
        $theCases = Kase::find()
            ->orderBy('created_at DESC')
            ->offset(30)
            ->limit(20)
            ->asArray()
            ->all();
        // Giao dien moi
        return $this->render('test_new', [
            'theCases'=>$theCases
        ]);
    }

    // bookings
    public function actionPax($id = 0)
    {
        $theTour = Tour::find()
            ->where(['id'=>$id])
            ->one();

        $theProduct = Product::find()
            ->with(['bookings'])
            ->where(['id'=>$theTour['ct_id']])
            ->one();

        return $this->render('//tours/tours_pax', [
            'theTour'=>$theTour,
            'theProduct'=>$theProduct,
        ]);
    }

    // List of FR customers
    public function actionFrCustomers($id = 0)
    {
        $thePeople = User::find()
            ->select(['persons.id', 'name', 'email'])
            ->joinWith([
                'bookings.product'=>function($q) {
                    return $q->select(['at_ct.id', 'title', 'op_code', 'op_name'])->where(['at_ct.language'=>'fr']);
                },
            ])
            ->where('persons.id>:id', [':id'=>$id])
            ->andWhere('persons.email!=""')
            ->orderBy('persons.id')
            ->limit(500)
            ->asArray()
            ->all();
        foreach ($thePeople as $person) {
            echo strtolower($person['email']), '<br>';
        }

        //\fCore::expose($thePeople);
    }


    // Agoda hotel info
    public function actionAgoda()
    {
        return $this->render('test_agoda_hotels');
    }

    // Parse email datetime
    public function actionEmailTime()
    {
        $str = 'Wed, 24 Sep 2014 14:14:36 +0700';
        $date = \DateTime::createFromFormat('D, d M Y H:i:s O', $str);
        echo $str, '<hr>';
        echo $date->format('d-m-Y H:i');
        echo $date->getTimestamp();
    }

    // Common layout for sites
    public function actionLayout()
    {
        // $this->layout = 'common-140901';
        $theTours = Tour::find()
            ->select(['id', 'code', 'name'])
            ->orderBy('id DESC')
            ->limit(40)
            ->asArray()
            ->all();
        return $this->render('test_layout', [
            'theTours'=>$theTours,
        ]);
    }

    // Loai bo user trung ten
    public function actionDupUsers($name = '', $dump = 0, $keep= 0)
    {
        if ($keep != 0) {
            $keepUser = User::findOne($keep);
            if (!$keepUser) {
                die('KEEP NF');
            }
        }
        if ($dump != 0) {
            $dumpUser = User::findOne($dump);
            if (!$dumpUser) {
                die('DUMP NF');
            }
        }
        if ($dump != 0) {
            if ($keep != 0 && $keep != $dump) {
                $sql = 'UPDATE at_meta SET rid=:keep WHERE rtype="user" AND rid=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE at_booking_user SET user_id=:keep WHERE user_id=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE at_case_user SET user_id=:keep WHERE user_id=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE at_messages SET from_id=:keep WHERE from_id=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE at_messages SET m_to=:keep WHERE m_to=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE at_cases SET ref=:keep WHERE ref=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE at_referrals SET user_id=:keep WHERE user_id=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump, ':keep'=>$keep])->execute();
                $sql = 'UPDATE persons SET fname=:fname, lname=:lname, name=:name, gender=:gender, country_code=:country_code, email=:email, phone=:phone, bday=:bday, bmonth=:bmonth, byear=:byear WHERE id=:keep';
                Yii::$app->db->createCommand($sql, [
                    ':keep'=>$keep,
                    ':fname'=>$dumpUser['fname'],
                    ':lname'=>$dumpUser['lname'],
                    ':name'=>$dumpUser['name'],
                    ':gender'=>$dumpUser['gender'],
                    ':country_code'=>$dumpUser['country_code'],
                    ':email'=>strtolower($dumpUser['email'] == '' ? $keepUser['email'] : $dumpUser['email']),
                    ':phone'=>$dumpUser['phone'],
                    ':bday'=>$dumpUser['bday'],
                    ':bmonth'=>$dumpUser['bmonth'],
                    ':byear'=>$dumpUser['byear'],
                ])->execute();
            } else {
                $sql = 'DELETE FROM at_meta WHERE rtype="user" AND rid=:dump';
                Yii::$app->db->createCommand($sql, [':dump'=>$dump])->execute();
            }
            $sql = 'UPDATE persons SET fname="A-BLANK-NAME", lname="-", name="-", bday=0, bmonth=0, byear=0, email="", phone="" WHERE id=:dump';
            Yii::$app->db->createCommand($sql, [':dump'=>$dump])->execute();
        }

        $sql = 'select id, name, count(*) as cnt from persons group by name having cnt>1 order by id desc';
        $allUsers = Yii::$app->db->createCommand($sql)->queryAll();

        if ($name != '') {
            $sql = 'select * from persons where name=:name';
            $dupUsers = User::find()
                ->where(['name'=>$name])
                ->with(['cases', 'bookings'])
                ->asArray()->all();
            foreach ($dupUsers as $user) {
                echo '<br>', $user['id'], ' - <a href="/users/r/', $user['id'], '">', $user['name'], '</a> - ', $user['email'], ' - <a href="/test/users?keep=', $user['id'], '">keep</a>';
                foreach ($user['cases'] as $case) {
                    echo '<br>K <a href="/cases/r/', $case['id'], '">', $case['name'], '</a> ', $case['created_at'];
                }
                foreach ($user['bookings'] as $booking) {
                    echo '<br>B <a href="/bookings/r/', $booking['id'], '">', $booking['id'], '</a> ', $booking['created_at'];
                }
            }
            echo '<hr>';
        }

        $cnt = 0;
        foreach ($allUsers as $user) {
            $cnt ++;
            echo '<br>', $cnt, ' <a href="/test/dup-users?name=', urlencode($user['name']), '">', $user['name'], '</a> / ', $user['cnt'];
        }
    }


    // 20-10-2014
    public function actionRemoveDupMails() {
        echo '<h2>REMOVE DUP MAILS</h2>';
        $sql = 'select id, message_id, count(*) as t, from_email, to_email, case_id from at_mails group by message_id having t>1';
        $mails = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($mails as $mail) {
            echo '<br>', $mail['id'], \yii\helpers\Html::encode($mail['message_id']);
            $sql = 'delete from at_mails where message_id=:mid and id!=:id';
            Yii::$app->db->createCommand($sql, [':mid'=>$mail['message_id'], ':id'=>$mail['id']])->execute();
        }
    }

    // Test: tasks
    public function actionTasks($who = 1)
    {
        $theTasks = Task::find()
            ->joinWith(['assignees'])
            ->onCondition(['at_task_user.user_id'=>$who])
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'assignees'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->where(['at_tasks.status'=>'on'])
            ->orderBy('due_dt DESC, is_priority')
            ->asArray()
            ->limit(1000)
            ->all();
        return $this->render('//task/tasks', [
            'theTasks'=>$theTasks,
        ]);
    }

    public function actionTasksc()
    {
        
    }
}
/*
MERGE CASE
UPDATE at_messages SET rid=19339 WHERE rtype="case" AND rid=20349;
UPDATE at_files SET rid=19339 WHERE rtype="case" AND rid=20349;
UPDATE at_tasks SET rid=19339 WHERE rtype="case" AND rid=20349;
UPDATE at_inquiries SET case_id=19339 WHERE case_id=20349;
UPDATE at_mails SET case_id=19339 WHERE case_id=20349;
UPDATE at_case_user SET case_id=19339 WHERE case_id=20349;
UPDATE at_bookings SET case_id=19339 WHERE case_id=20349;
UPDATE at_email_mapping SET case_id=19339 WHERE case_id=20349;
*/