<?

namespace app\controllers;

use common\models\Company;
use common\models\Cplink;
use common\models\Cpt;
use common\models\Lichxe;
use common\models\Mail;
use common\models\Message;
use common\models\Meta2;
use common\models\Note;
use common\models\Pax;
use common\models\Person;
use common\models\PrintFeedbackForm;
use common\models\PrintWelcomeBannerForm;
use common\models\Product;
use common\models\Sysnote;
use common\models\Tour;
use common\models\TourAcceptForm;
use common\models\TourAssignCsForm;
use common\models\TourDriverForm;
use common\models\TourGuideForm;
use common\models\TourInCtForm;
use common\models\TourInHdForm;
use common\models\TourInLxForm;
use common\models\Tournote;
use common\models\TourRatingsForm;
use common\models\TourSettingsForm;
use common\models\TourStats;
use common\models\User;
use common\models\Venue;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\HttpException;
use \kartik\mpdf\Pdf;

class TourController extends MyController
{
    // Settings
    public function actionStats($id = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id, 'op_status' => 'op'])
            ->with([
                'tourStats',
                'bookings',
            ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $theTour['id']])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!$theTour['tourStats']) {
            $theStats                = new TourStats;
            $theStats['tour_id']     = $theTour['id'];
            $theStats['tour_old_id'] = $theTourOld['id'];
        } else {
            $theStats = TourStats::find()
                ->where(['tour_id' => $theTour['id']])
                ->one();
        }

        $theTourPaxCount = 0;
        foreach ($theTour['bookings'] as $booking) {
            $theTourPaxCount += (int) $booking['pax'];
        }

        $theStats['start_date'] = $theTour['day_from'];
        $theStats['end_date']   = date('Y-m-d', strtotime('+ ' . ($theTour['day_count'] - 1) . ' days', strtotime($theTour['day_from'])));
        $theStats['day_count']  = $theTour['day_count'];
        $theStats['pax_count']  = $theTourPaxCount;
        $theStats['tour_code']  = $theTour['op_code'];
        $theStats['tour_name']  = $theTour['op_name'];

        if ($theStats->load(Yii::$app->request->post()) && $theStats->validate()) {
            $theStats->save(false);
            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tour_stats', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            'theStats'   => $theStats,
        ]);
    }

    // Settings
    public function actionSettings($id = 0)
    {
        $theTourOld = Tour::find()
            ->where(['id' => $id])
            ->andWhere('status!="draft"')
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($theTourOld['status'] == 'draft') {
            return $this->redirect('@web/tours/accept/' . $theTourOld['id']);
        }

        $theTour = Product::find()
            ->where(['id' => $theTourOld['ct_id']])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theForm            = new TourSettingsForm;
        $theForm['op_code'] = $theTour['op_code'];
        $theForm['op_name'] = $theTour['op_name'];
        $theForm['owner']   = $theTourOld['owner'];

        $oldOperatorIdList = [];
        foreach ($oldOperatorList as $operator) {
            $oldOperatorIdList[] = $operator['user_id'];
        }

        $theForm['operators'] = $oldOperatorIdList;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tour_settings', [
            'theTour' => $theTour,
            'theForm' => $theForm,
        ]);
    }

    // Test make tour summary form
    public function actionSummary($id = 0)
    {
        $theTour = Product::find()
            ->where(['op_status' => 'op', 'id' => $id])
            ->with([
                'days',
                'metas'  => function ($q) {
                    return $q->andWhere(['name' => ['summary_1', 'summary_2']]);
                },
                'bookings',
                'bookings.pax',
                'bookings.case.company',
                'guides' => function ($q) {
                    return $q->orderBy('use_from_dt');
                },
                'guides.guide',
            ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }

        $theForm = new \app\models\SiTourSummaryForm;

        $theForm->tour_company = \fUTF8::upper($theTour['bookings'][0]['case']['company']['name']);
        $theForm->tour_code    = $theTour['op_code'];
        $theForm->tour_name    = $theTour['op_name'];

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            Yii::$app->language = $theTour['language'];
            $this->saveTourSummary($id);
            return $this->actionSummaryPdf($id);
        }

        return $this->render('tour_summary', [
            'theForm' => $theForm,
            'theTour' => $theTour,
        ]);
    }

    private function saveTourSummary($id)
    {
        $lines = [];
        $line  = '';
        for ($i = 1; $i < count($_POST['d_date']); $i++) {
            $line    = implode('];[', [$_POST['d_date'][$i], $_POST['d_name'][$i], $_POST['d_guides'][$i], $_POST['d_meals'][$i]]);
            $lines[] = $line;
        }
        if (!empty($lines)) {
            $value       = implode(']|[', $lines);
            $meta        = new Meta2;
            $meta->rtype = 'product';
            $meta->rid   = $id;
            $meta->name  = 'summary_2';
            $meta->value = $value;
            return $meta->save(false);
        }
        return false;

    }

    // 160705 Output pdf Nhung's request
    public function actionSummaryPdf($id = 0)
    {
        $content = $this->renderPartial('tour_summary_pdf');

        $pdf = new Pdf([
            'mode'         => Pdf::MODE_BLANK,

            'marginLeft'   => 0,
            'marginRight'  => 0,
            'marginTop'    => 32,
            'marginBottom' => 22,

            'marginHeader' => 0,
            'marginFooter' => 0,

            'format'       => Pdf::FORMAT_A4,
            'orientation'  => Pdf::ORIENT_PORTRAIT,
            //'destination' => Pdf::DEST_DOWNLOAD,
            'filename'     => 'TRAVEL DIARY - ' . $_POST['SiTourSummaryForm']['tour_code'] . ' - SECRET INDOCHINA - ' . date('Ymd_His') . '.pdf',
            'content'      => $content,
            'cssFile'      => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline'    => '
        #content {padding-left:50px; padding-right:50px; font-family:bellmt;}
            h1, h2, h3, h4, h5, h6, th {color:#e43a92!important; font-family:bellmt;}
            h1 {font-size:20px!important; text-align:center;}
            h3 {font-size:17px!important; font-weight:bold;}
            table {border-collapse:collapse;}
            table td, table th {font-family:bellmt; font-size:15px; text-align:center; border-color:#ddd;}
            a {color:#222!important; border-bottom:1px dotted #444;}
            thead tr th {background-color:#e9e9e9;}
            tr.bg1 td {background-color:#f9f9f9;}
            @page {
                header: myheader;
                footer:  myfooter;
            }
            ',
            'options'      => [
                'title' => 'TRAVEL DIARY - ' . $_POST['SiTourSummaryForm']['tour_code'] . ' - SECRET INDOCHINA',
            ],
            'methods'      => [
            ],
        ]);

        return $pdf->render();
    }

    public function actionAccept($id = 0)
    {
        $theTourOld = Tour::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTour = Product::find()
            ->where(['id' => $theTourOld['ct_id']])
            ->with([
                'bookings',
                'bookings.case'       => function ($q) {
                    return $q->select(['id', 'name', 'owner_id']);
                },
                'bookings.case.owner' => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'email']);
                },
            ])
            ->one();

        if (Yii::$app->request->isAjax && isset($_POST['action'], $_POST['email']) && $_POST['action'] == 'also') {
            $this->mgIt(
                'ims | There\'s a new tour for you "' . $theTourOld['code'] . ' - ' . $theTourOld['name'] . '"',
                '//mg/tour_assign',
                [
                    'theTour'    => $theTour,
                    'theTourOld' => $theTourOld,
                ],
                [
                    ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                    ['to', $_POST['email'], '', ''],
                    ['bcc', 'bich.ngoc@amica-travel.com', 'Ngọc', 'HB.'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ]
            );
            return 'OK';
        }

        if ($theTourOld['status'] != 'draft') {
            return $this->redirect('/tours/u/' . $theTourOld['id']);
        }
        /*
        $ym = date('ym', strtotime($theTour['day_from']));
        $sql = 'SELECT MAX(SUBSTRING(code, 6, 3)) AS maxx FROM at_tours WHERE SUBSTRING(code, 2, 4)=:ym';
        $maxx = Yii::$app->db->createCommand($sql, [':ym'=>$ym])->queryScalar();
         */
        $theForm               = new TourAcceptForm;
        $theForm['op_code']    = $theTourOld['code'];
        $theForm['op_name']    = $theTourOld['name'];
        $theForm['client_ref'] = $theTour['client_ref'];
        $theForm['owner']      = 118;

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array(USER_ID, [1, 118, 8162])) {
            throw new HttpException(403, 'Access denied.');
        }

        $sql          = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from persons u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND ur.role_id=5 ORDER BY u.lname';
        $operatorList = Yii::$app->db->createCommand($sql)->queryAll();

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $theTour['op_status']  = 'op';
            $theTour['op_name']    = $theForm['op_name'];
            $theTour['op_code']    = $theForm['op_code'];
            $theTour['client_ref'] = $theForm['client_ref'];
            $theTour->save(false);

            Yii::$app->db->createCommand()->update(
                'at_tours', [
                    'uo'     => NOW,
                    'ub'     => USER_ID,
                    'status' => 'on',
                    'owner'  => $theForm['owner'],
                    'name'   => $theTour['op_name'],
                    'code'   => $theTour['op_code'],
                ], ['id' => $theTourOld['id']])->execute();

            Yii::$app->db->createCommand()->insert('at_search', [
                'rtype'  => 'tour',
                'rid'    => $theTourOld['id'],
                'search' => str_replace(['-'], [''], \fURL::makeFriendly($theTour['op_code'] . ' ' . $theTour['op_name'], '-')),
                'found'  => $theTour['op_code'] . ' ' . $theTour['op_name'],
            ])->execute();

            // Email new op
            $operatorNameList = [];
            foreach ($theForm['operators'] as $userId) {
                foreach ($operatorList as $operator) {
                    if ($operator['user_id'] == $userId) {
                        Yii::$app->db->createCommand()->insert('at_tour_user', [
                            'tour_id' => $theTourOld['id'],
                            'user_id' => $userId,
                            'role'    => 'operator',
                        ])->execute();

                        $userEmail = $operator['email'];
                        $userFname = $operator['fname'];
                        $userLname = $operator['lname'];

                        $operatorNameList[] = $userFname . ' ' . $userLname;
                        $this->mgIt(
                            'ims | Tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'] . '" has been assigned to you',
                            '//mg/tour_assign',
                            [
                                'theTour'    => $theTour,
                                'theTourOld' => $theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', $userEmail, $userLname, $userFname],
                                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                            ]
                        );
                    }
                }
            }

            // Notify seller
            foreach ($theTour['bookings'] as $booking) {
                $userEmail = $booking['case']['owner']['email'];
                $userFname = $booking['case']['owner']['fname'];
                $userLname = $booking['case']['owner']['lname'];
                $this->mgIt(
                    'Your new tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'] . '" has been assigned to ' . implode(', ', $operatorNameList),
                    '//mg/tour_assign',
                    [
                        'theTour'    => $theTour,
                        'theTourOld' => $theTourOld,
                    ],
                    [
                        ['from', Yii::$app->user->identity->email, Yii::$app->user->identity->nickname, 'via IMS'],
                        ['to', $userEmail, $userLname, $userFname],
                        ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                    ]
                );
            }

            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tours_accept', [
            'theTour'      => $theTour,
            'theTourOld'   => $theTourOld,
            'theForm'      => $theForm,
            'operatorList' => $operatorList,
        ]);
    }
    public function actionTh_tour($view = 'normal', $month = '', $fg = '', $status = '')
    {
        $orderby = 'enddate';
        $kq      = Yii::$app->request->get('qhkh_kq');
        $kt      = Yii::$app->request->get('qhkh_kt');
        $point   = Yii::$app->request->get('qhkh_diem');
        if ($month == 'next30days') {
            $dateRange = [date('Y-m-d'), date('Y-m-d', strtotime('+30 days'))];
        } elseif ($month == 'last30days') {
            $dateRange = [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')];
        } elseif (strlen($month) == 10) {
            $dateRange = [$month, date('Y-m-d', strtotime('+6 days ' . $month))];
        } elseif (strlen($month) == 7) {
            $dateRange = [$month . '-01', date('Y-m-t', strtotime($month . '-01'))];
        } else {
            $month     = date('Y-m');
            $dateRange = [date('Y-m-01'), date('Y-m-t')];
        }

        $query = Product::find()
            ->where(['op_status' => 'op'])
            ->select(['*', 'ed' => new \yii\db\Expression('(SELECT DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->andHaving('ed BETWEEN :date1 AND :date2', [':date1' => $dateRange[0], ':date2' => $dateRange[1]]);
        if ($kq != '' || $kt != '' || $point != '') {
            $query->joinWith('tourStats');
            if ($kq != '') {
                $query->andWhere(['qhkh_ketthuc' => $kq]);
            }
            if ($kt != '') {
                $query->andWhere(['LIKE', 'qhkh_khaithac', $kt]);
            }
            if ($point != '') {
                $query->andWhere(['qhkh_diem' => $point]);
            }
        }
        $theTours = $query
            ->orderBy('ed')
            ->with([
                'tour'               => function ($q) {
                    return $q->select(['id', 'ct_id', 'code', 'name', 'status', 'owner']);
                },
                'updatedBy'          => function ($q) {
                    return $q->select(['id', 'name' => 'nickname', 'image']);
                },
                'tourStats',
                'days'               => function ($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings',
                'bookings.case'      => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings.createdBy' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname', 'image'])->orderBy('lname, fname');
                },
                'pax'                => function ($q) {
                    return $q->select(['tour_id', 'is_repeating', 'name', 'pp_country_code', 'pp_birthdate', 'pp_gender']);
                },
            ])
            ->asArray()
            ->all();
        $sql       = 'SELECT SUBSTRING(day_from,1,7) AS ym, COUNT(*) AS total FROM at_ct WHERE op_status="op" GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();
        if (isset($_POST['tour_id'])) {
            $theTourStat = TourStats::findOne($_POST['tour_id']);
            if (!$theTourStat) {
                throw new HttpException(403, "Tour Stats not found");
            }
            if (isset($_POST['kq'])) {
                $theTourStat->qhkh_ketthuc = $_POST['kq'];
            }

            if (isset($_POST['kt'])) {
                $theTourStat->qhkh_khaithac = implode(',', $_POST['kt']);
            }

            if (isset($_POST['point'])) {
                $theTourStat->qhkh_diem = $_POST['point'];
            }

            if (!$theTourStat->save(false)) {
                throw new HttpException(403, "Tour Stats not saved");
            } else {
                return $this->redirect(Url::current());
            }
        }

        return $this->render('th_tour', [
            'theTours'  => $theTours,
            'month'     => $month,
            'fg'        => $fg,
            'status'    => $status,
            'orderby'   => $orderby,
            'monthList' => $monthList,
        ]);
    }

    public function actionIndex($view = 'normal', $orderby = 'startdate', $month = '', $fg = '', $status = '', $seller = 0, $operator = 0, $cservice = 0, $name = '', $dayname = '', $owner = '')
    {
        if ($month == 'next30days') {
            $dateRange = [date('Y-m-d'), date('Y-m-d', strtotime('+30 days'))];
        } elseif ($month == 'last30days') {
            $dateRange = [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')];
        } elseif (strlen($month) == 10) {
            $dateRange = [$month, date('Y-m-d', strtotime('+6 days ' . $month))];
        } elseif (strlen($month) == 7) {
            $dateRange = [$month . '-01', date('Y-m-t', strtotime($month . '-01'))];
        } else {
            $month     = date('Y-m');
            $dateRange = [date('Y-m-01'), date('Y-m-t')];
        }

        $query = Product::find()
            ->where(['op_status' => 'op']);
        if ($orderby == 'enddate') {
            $query->select(['*', 'ed' => new \yii\db\Expression('(SELECT DATE_ADD(day_from, INTERVAL day_count-1 DAY))')]);
        }

        if ($orderby == 'enddate') {
            $query->andHaving('ed BETWEEN :date1 AND :date2', [':date1' => $dateRange[0], ':date2' => $dateRange[1]]);
        } elseif ($orderby == 'startdate') {
            $query->andWhere('day_from BETWEEN :date1 AND :date2', [':date1' => $dateRange[0], ':date2' => $dateRange[1]]);
        } else {
            // Created
            $sql        = 'SELECT ct_id FROM at_tours WHERE created_dt BETWEEN :date1 AND :date2 ORDER BY created_dt DESC';
            $tourIdList = Yii::$app->db->createCommand($sql, [':date1' => $dateRange[0], ':date2' => $dateRange[1]])->queryColumn();
            $query->select(['*', new \yii\db\Expression('(SELECT id FROM at_tours WHERE at_tours.ct_id=at_ct.id LIMIT 1) AS tour_old_id')]);
            $query->andWhere(['id' => $tourIdList]);
        }

        if (strlen($name) > 2) {
            $query->andWhere(['like', 'op_name', $name]);
        }

        $theTours = $query
            ->orderBy($orderby == 'enddate' ? 'ed' : ($orderby == 'startdate' ? 'day_from' : 'tour_old_id DESC'))
            ->with([
                'tour'               => function ($q) {
                    return $q->select(['id', 'ct_id', 'code', 'name', 'status', 'owner']);
                },
                'updatedBy'          => function ($q) {
                    return $q->select(['id', 'name' => 'nickname', 'image']);
                },
                'tourStats',
                'days'               => function ($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings',
                'bookings.case'      => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings.createdBy' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname', 'image'])->orderBy('lname, fname');
                },
                'tour.operators'     => function ($q) {
                    return $q->select(['id', 'name' => 'nickname', 'image'])->orderBy('lname, fname');
                },
                'pax'                => function ($q) {
                    return $q->select(['tour_id', 'is_repeating', 'name', 'pp_country_code', 'pp_birthdate', 'pp_gender']);
                },
            ])
            ->asArray()
            ->all();

        $sql       = 'SELECT SUBSTRING(day_from,1,7) AS ym, COUNT(*) AS total FROM at_ct WHERE op_status="op" GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();

        $tourIdList = [];
        $sellerList = [];
        foreach ($theTours as $tour) {
            $tourIdList[] = (int) $tour['tour']['id'];
            foreach ($tour['bookings'] as $booking) {
                $sellerList[$booking['createdBy']['id']] = $booking['createdBy']['name'];
            }
        }

        $operatorList = [];
        $cserviceList = [];
        $tourPeople   = [];

        if (!empty($tourIdList)) {
            $sql          = 'SELECT u.id, u.nickname AS name, tu.tour_id FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id IN (' . implode(', ', $tourIdList) . ')';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();

            $sql          = 'SELECT u.id, u.nickname AS name, tu.tour_id FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="cservice" AND tu.tour_id IN (' . implode(', ', $tourIdList) . ')';
            $cserviceList = Yii::$app->db->createCommand($sql)->queryAll();

            $sql        = 'SELECT u.id, u.nickname AS name, tu.tour_id, tu.role FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role IN ("operator", "cservice") AND tu.tour_id IN (' . implode(', ', $tourIdList) . ')';
            $tourPeople = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $staffList = [];
        foreach ($tourIdList as $tourId) {
            $staffList[$tourId] = [
                'se' => [],
                'op' => [],
                'cs' => [],
            ];
            foreach ($tourPeople as $person) {
                if ($person['tour_id'] == $tourId && $person['role'] == 'operator') {
                    $staffList[$tourId]['op'][] = $person['id'];
                }
                if ($person['tour_id'] == $tourId && $person['role'] == 'cservice') {
                    $staffList[$tourId]['cs'][] = $person['id'];
                }
            }
        }

        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }
        $tourOldIdList = [0];
        foreach ($theTours as $tour) {
            $tourOldIdList[] = (int) $tour['tour']['id'];
        }
        $sql           = 'select tour_id, points, IF (guide_user_id=0, guide_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u where u.id=guide_user_id limit 1)) AS namephone from at_tour_guides where parent_id=0 AND tour_id IN (' . implode(',', $tourIdList) . ')';
        $tourGuides    = Yii::$app->db->createCommand($sql)->queryAll();
        $sql           = 'select tour_id, points, IF (driver_user_id=0, driver_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u where u.id=driver_user_id limit 1)) AS namephone from at_tour_drivers where parent_id=0 AND tour_id IN (' . implode(',', $tourIdList) . ')';
        $tourDrivers   = Yii::$app->db->createCommand($sql)->queryAll();
        $sql           = 'select tu.tour_id, u.id, u.nickname AS name, u.image FROM persons u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="operator" AND tu.tour_id IN (' . implode(',', $tourOldIdList) . ')';
        $tourOperators = Yii::$app->db->createCommand($sql)->queryAll();
        $sql           = 'select tu.tour_id, u.id, u.nickname AS name, u.image FROM persons u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="cservice" AND tu.tour_id IN (' . implode(',', $tourOldIdList) . ')';
        $tourCCStaff   = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('tour_index', [
            'theTours'      => $theTours,
            'tourGuides'    => $tourGuides,
            'tourDrivers'   => $tourDrivers,
            'tourOperators' => $tourOperators,
            'tourCCStaff'   => $tourCCStaff,
            'month'         => $month,
            'fg'            => $fg,
            'status'        => $status,
            'seller'        => $seller,
            'operator'      => $operator,
            'cservice'      => $cservice,
            'name'          => $name,
            'view'          => $view,
            'orderby'       => $orderby,
            'dayname'       => $dayname,
            'monthList'     => $monthList,
            'sellerList'    => $sellerList,
            'operatorList'  => $operatorList,
            'cserviceList'  => $cserviceList,
            'staffList'     => $staffList,
            'owner'         => $owner,
        ]);
    }

    public function actionR($id = 0)
    {
        $productId = Yii::$app->db->createCommand('SELECT ct_id FROM at_tours WHERE id=:id LIMIT 1', [':id' => $id])->queryScalar();
        $theTour   = Product::find()
            ->where(['id' => $productId])
            ->with([
                'pax'                  => function ($q) {
                    return $q->orderBy('booking_id, name');
                },
                'tournotes',
                'tourStats',
                'tournotes.updatedBy'  => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.createdBy',
                'bookings.case',
                'bookings.case.owner'  => function ($q) {
                    return $q->select(['id', 'nickname']);
                },
                'bookings.invoices',
                'bookings.payments',
                'days',
                'tour',
                'tour.operators',
                'tour.cskh',
                'tour.guides',
                'tour.tasks'           => function ($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tour.tasks.assignees' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
                'tour.tasks.createdBy' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }

        // Amica all people
        $thePeople = User::find()
            ->select(['id', 'name', 'fname', 'lname', 'nickname', 'email'])
            ->where(['status' => 'on', 'is_member' => 'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        // Tour guides
        // $sql = 'SELECT u.id, u.fname, u.lname, u.phone AS uphone, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:tour_id ORDER BY day LIMIT 100';
        // $tourGuidesOld = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql        = 'select g.*, IF(guide_user_id=0, "", (SELECT gender from persons u WHERE u.id=g.guide_user_id LIMIT 1)) AS gender FROM at_tour_guides g where tour_id=:tour_id limit 100';
        $tourGuides = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Tour operators
        $sql           = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:tour_id';
        $tourOperators = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql         = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="cservice" AND tu.tour_id=:tour_id';
        $tourCSStaff = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['tour']['id']])->queryAll();

        // Tour feedbacks
        $sql           = 'SELECT * FROM at_tour_feedbacks WHERE tour_id=:tour_id ORDER BY id DESC LIMIT 100';
        $tourFeedbacks = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Pax list
        $bookingIdList = [];
        $tourPax       = [];
        foreach ($theTour['bookings'] as $booking) {
            $bookingIdList[] = $booking['id'];
        }
        if (!empty($bookingIdList)) {
            $sql     = 'SELECT u.id, u.fname, u.lname, u.name, u.byear, u.bmonth, u.bday, u.gender, u.country_code, bu.booking_id FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.status!="canceled" AND bu.booking_id IN (' . implode(',', $bookingIdList) . ')';
            $tourPax = Yii::$app->db->createCommand($sql)->queryAll();
            // Tour reg info
            //$sql = 'SELECT booking_id, reg_confirmed_dt FROM at_client_page_links WHERE reg_confirmed_dt!=0 AND booking_id IN ('.implode(',', $bookingIdList).')';
            $tourRegInfo = []; //Yii::$app->db->createCommand($sql)->queryAll();
        }

        // List of case id
        $caseIdList = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']) {
                $caseIdList[] = $booking['case']['id'];
            }
        }

        // Tour referrals
        $tourRefs = [];
        if (!empty($caseIdList)) {
            $sql      = 'SELECT r.*, u.name FROM at_referrals r, persons u WHERE u.id=r.user_id AND r.case_id IN (' . implode(',', $caseIdList) . ') LIMIT 100';
            $tourRefs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Post a note
        if (isset($_POST['body'])) {
            $utag  = false;
            $itag  = false;
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $body  = $_POST['body'];

            if (strpos($title, '#urgent') !== false) {
                $utag  = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag  = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);

            // $thePeople = $theTour['tour']['operators'];

            // Name mentions
            $toList      = [];
            $toEmailList = [];
            $toIdList    = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@[' . $person['nickname'] . ']';
                    // 160107 Quick fix cho Ha beo khong email duoc Quynh Giang
                    $mentionEmail = strstr(str_replace('.', '', $person['email']), '@', true);
                    if (strpos($_POST['to'], $mention) !== false || strpos($_POST['to'], $mentionEmail) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[]         = $person['email'];
                        $toIdList[]            = $person['id'];
                    }
                }
                foreach ($thePeople as $person) {
                    //TODO: foreach ($theCase['people'] as $person) {
                    $fromEmail = 'from:' . $person['email'];
                    $toEmail   = 'to:' . $person['email'];
                    if (strpos($_POST['to'], $fromEmail) !== false) {
                        $noteFromId   = $person['id'];
                        $noteToId     = USER_ID;
                        $noteViaEmail = true;
                    } elseif (strpos($_POST['to'], $toEmail) !== false) {
                        $noteFromId   = USER_ID;
                        $noteToId     = $person['id'];
                        $noteViaEmail = true;
                    }
                }
            }
            /* OLD
            foreach ($thePeople as $person) {
            $mention = '@['.$person['name'].']';
            if (strpos($body, $mention) !== false) {
            $body = str_replace($mention, '@'.Html::a($person['name'], 'https://my.amicatravel.com/users/r/'.$person['id']), $body);
            $_POST['body'] = str_replace($mention, '@[user-'.$person['id'].']', $_POST['body']);
            $toEmailList[] = $person['email'];
            $toIdList[] = $person['id'];
            }
            }
             */
            $toEmailList = array_unique($toEmailList);

            /*          \fCore::expose($title);
            \fCore::expose($body);
            \fCore::expose($toEmailList);
            exit;
             */
            // Save note

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

            $theNote           = new Note;
            $theNote->scenario = 'notes_c';

            $theNote->co       = NOW;
            $theNote->cb       = USER_ID;
            $theNote->uo       = NOW;
            $theNote->ub       = USER_ID;
            $theNote->status   = 'on';
            $theNote->via      = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                $theNote->priority = 'C1';
            }
            if ($utag) {
                $theNote->priority = 'A3';
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to    = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title   = $title;
            $theNote->body    = $_POST['body'];
            $theNote->rtype   = 'tour';
            $theNote->rid     = $theTour['tour']['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }

            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl  = 'https://my.amicatravel.com/tours/r/' . $theTour['tour']['id'];
            $relName = $theTour['tour']['code'] . ' - ' . $theTour['tour']['name'];

            // Upload files
            $fileList = '';
            if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) && count($_POST['fileid']) == count($_POST['filename'])) {
                foreach ($_POST['fileid'] as $i => $fileId) {
                    $newFileName = $_POST['filename'][$i];
                    $rawFileExt  = strrchr($newFileName, '.');
                    $rawFileName = $fileId . $rawFileExt;
                    $rawFilePath = Yii::getAlias('@webroot') . '/assets/plupload_2.1.9/' . $rawFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid  = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize  = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0] . '×' . $imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'           => ICT,
                                'cb'           => USER_ID,
                                'uo'           => ICT,
                                'ub'           => USER_ID,
                                'name'         => $newFileName,
                                'ext'          => $rawFileExt,
                                'size'         => $fileSize,
                                'img_size'     => $fileImgSize,
                                'uid'          => $fileUid,
                                'filegroup_id' => 1,
                                'rtype'        => 'tour',
                                'rid'          => $theTour['tour']['id'],
                                'n_id'         => $theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot') . '/upload/user-files/' . substr(ICT, 0, 7) . '/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-' . USER_ID . '-' . $newFileId . '-' . $fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir . $newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/' . $newFileId . '">' . $newFileName . '</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                            Yii::$app->db->createCommand()
                                ->delete('at_files', [
                                    'id' => $newFileId,
                                ])
                                ->execute();
                        }
                    }
                }
            }

            if ($fileList != '') {
                $body = $fileList . '<br>' . $body;
            }

            // Send email

            if (!empty($toEmailList)) {
                // Tour staff names, to include at the end of email subject
                $trail = $theTour['tour']['code'] . ' - ' . $theTour['tour']['name'];
                if (count($theTour['bookings']) > 1) {
                    $trail .= ' (combined)';
                }

                $tourPaxCount = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourPaxCount[] = $booking['pax'];
                }
                $trail .= ' - ' . implode('+', $tourPaxCount) . 'p ';
                $trail .= $theTour['day_count'] . 'd ';
                $trail .= date('j/n', strtotime($theTour['day_from'])) . ' - ';

                $tourStaff = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourStaff[] = $booking['createdBy']['nickname'];
                }
                foreach ($theTour['tour']['operators'] as $user) {
                    $tourStaff[] = $user['nickname'];
                }

                $trail .= implode(', ', $tourStaff);

                $subject = $theTour['tour']['code'] . ' - ' . $title;
                $subject = str_replace($theTour['tour']['code'] . ' - ' . $theTour['tour']['code'] . ' - ', $theTour['tour']['code'] . ' - ', $subject);
                if ($itag) {
                    $subject = '#important ' . $subject;
                }
                if ($utag) {
                    $subject = '#urgent ' . $subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Tour: ' . $trail;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-' . $theNote->id . '-' . USER_ID . '@amicatravel.com'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id => $user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                $this->mgIt(
                    $subject,
                    '//mg/note_added',
                    [
                        'toList'  => $toList,
                        'theNote' => $theNote,
                        'relUrl'  => $relUrl,
                        'body'    => $body,
                    ],
                    $args
                );
            }
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id' => $caseIdList])
            ->andWhere(['or', 'LOCATE("[cs]", subject)!=0', ['like', 'tags', 'op']])
            ->asArray()
            ->all();

        $theNotes = Note::find()
            ->where(['rtype' => 'tour', 'rid' => $theTour['tour']['id']])
            ->with([
                'files',
                'from' => function ($q) {
                    return $q->select(['id', 'nickname', 'image']);
                },
                'to'   => function ($q) {
                    return $q->select(['id', 'nickname']);
                },
            ])
            ->asArray()
            ->orderBy('co DESC')
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype' => 'tour', 'rid' => $theTour['tour']['id']])
            ->with([
                'user' => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'name']);
                },
            ])
            ->asArray()
            ->all();

        // Old pax, older tours if any
        $olderTours    = [];
        $tourPaxIdList = [];
        foreach ($tourPax as $user) {
            $tourPaxIdList[] = $user['id'];
        }
        if (!empty($tourPaxIdList)) {
            $sql        = 'SELECT t.id, t.code, t.name, t.status FROM at_tours t, at_ct p, at_booking_user bu, at_bookings b WHERE t.ct_id=p.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN (' . implode(',', $tourPaxIdList) . ') AND p.day_from<:start_date GROUP BY t.id LIMIT 10';
            $olderTours = Yii::$app->db->createCommand($sql, [':start_date' => $theTour['day_from']])->queryAll();
        }

        // Tour hang
        $companyIdList = [];
        $tourAgents    = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $companyIdList[] = $booking['case']['company_id'];
            }
        }
        if (!empty($companyIdList)) {
            $sql        = 'SELECT id, name, image FROM at_companies WHERE id IN (' . implode(', ', $companyIdList) . ') LIMIT 1';
            $tourAgents = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Drivers and vehicles
        $sql         = 'select * from at_tour_drivers where tour_id=:tour_id limit 100';
        $tourDrivers = Yii::$app->db->createCommand($sql, [':tour_id' => $productId])->queryAll();

        // TEST HUAN
        $theMessage           = false;
        $theMessage           = new Message();
        $theMessage->scenario = 'message/c';

        // Render view
        return $this->render('tour_r', [
            'theTour'       => $theTour,
            'thePeople'     => $thePeople,
            'inboxMails'    => $inboxMails,
            'theNotes'      => $theNotes,
            'theSysnotes'   => $theSysnotes,
            'tourPax'       => $tourPax,
            'tourRegInfo'   => $tourRegInfo,
            'tourRefs'      => $tourRefs,
            'olderTours'    => $olderTours,
            'tourAgents'    => $tourAgents,
            'tourDrivers'   => $tourDrivers,
            'tourGuides'    => $tourGuides,
            'tourOperators' => $tourOperators,
            'tourCSStaff'   => $tourCSStaff,
            'tourFeedbacks' => $tourFeedbacks,
            'theMessage'    => $theMessage,
        ]);
    }

    // Test
    public function actionX($id = 0)
    {
        $productId = Yii::$app->db->createCommand('SELECT ct_id FROM at_tours WHERE id=:id LIMIT 1', [':id' => $id])->queryScalar();
        $theTour   = Product::find()
            ->where(['id' => $productId])
            ->with([
                'tournotes',
                'tourStats',
                'tournotes.updatedBy'  => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.createdBy',
                'bookings.case',
                'bookings.case.owner'  => function ($q) {
                    return $q->select(['id', 'nickname']);
                },
                'bookings.invoices',
                'bookings.payments',
                'days',
                'tour',
                'tour.operators',
                'tour.guides',
                'tour.tasks'           => function ($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tour.tasks.assignees' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
                'tour.tasks.createdBy' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }

        // Amica all people
        $thePeople = Person::find()
            ->select(['id', 'name', 'fname', 'lname', 'nickname', 'email'])
            ->where(['status' => 'on', 'is_member' => 'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        // Tour guides
        // $sql = 'SELECT u.id, u.fname, u.lname, u.phone AS uphone, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:tour_id ORDER BY day LIMIT 100';
        // $tourGuidesOld = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql        = 'select * from at_tour_guides where tour_id=:tour_id limit 100';
        $tourGuides = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Tour operators
        $sql           = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:tour_id';
        $tourOperators = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql         = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="cservice" AND tu.tour_id=:tour_id';
        $tourCSStaff = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['tour']['id']])->queryAll();

        // Tour feedbacks
        $sql           = 'SELECT * FROM at_tour_feedbacks WHERE tour_id=:tour_id ORDER BY id DESC LIMIT 100';
        $tourFeedbacks = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Pax list
        $bookingIdList = [];
        $tourPax       = [];
        foreach ($theTour['bookings'] as $booking) {
            $bookingIdList[] = $booking['id'];
        }
        if (!empty($bookingIdList)) {
            $sql     = 'SELECT u.id, u.fname, u.lname, u.name, u.byear, u.bmonth, u.bday, u.gender, u.country_code, bu.booking_id FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.status!="canceled" AND bu.booking_id IN (' . implode(',', $bookingIdList) . ')';
            $tourPax = Yii::$app->db->createCommand($sql)->queryAll();
            // Tour reg info
            //$sql = 'SELECT booking_id, reg_confirmed_dt FROM at_client_page_links WHERE reg_confirmed_dt!=0 AND booking_id IN ('.implode(',', $bookingIdList).')';
            $tourRegInfo = []; //Yii::$app->db->createCommand($sql)->queryAll();
        }

        // List of case id
        $caseIdList = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']) {
                $caseIdList[] = $booking['case']['id'];
            }
        }

        // Tour referrals
        $tourRefs = [];
        if (!empty($caseIdList)) {
            $sql      = 'SELECT r.*, u.name FROM at_referrals r, persons u WHERE u.id=r.user_id AND r.case_id IN (' . implode(',', $caseIdList) . ') LIMIT 100';
            $tourRefs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Post a note
        if (isset($_POST['body'])) {
            $utag  = false;
            $itag  = false;
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $body  = $_POST['body'];

            if (strpos($title, '#urgent') !== false) {
                $utag  = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag  = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);

            // $thePeople = $theTour['tour']['operators'];

            // Name mentions
            $toList      = [];
            $toEmailList = [];
            $toIdList    = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@[' . $person['nickname'] . ']';
                    // 160107 Quick fix cho Ha beo khong email duoc Quynh Giang
                    $mentionEmail = strstr(str_replace('.', '', $person['email']), '@', true);
                    if (strpos($_POST['to'], $mention) !== false || strpos($_POST['to'], $mentionEmail) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[]         = $person['email'];
                        $toIdList[]            = $person['id'];
                    }
                }
                foreach ($thePeople as $person) {
                    //TODO: foreach ($theCase['people'] as $person) {
                    $fromEmail = 'from:' . $person['email'];
                    $toEmail   = 'to:' . $person['email'];
                    if (strpos($_POST['to'], $fromEmail) !== false) {
                        $noteFromId   = $person['id'];
                        $noteToId     = USER_ID;
                        $noteViaEmail = true;
                    } elseif (strpos($_POST['to'], $toEmail) !== false) {
                        $noteFromId   = USER_ID;
                        $noteToId     = $person['id'];
                        $noteViaEmail = true;
                    }
                }
            }
            /* OLD
            foreach ($thePeople as $person) {
            $mention = '@['.$person['name'].']';
            if (strpos($body, $mention) !== false) {
            $body = str_replace($mention, '@'.Html::a($person['name'], 'https://my.amicatravel.com/users/r/'.$person['id']), $body);
            $_POST['body'] = str_replace($mention, '@[user-'.$person['id'].']', $_POST['body']);
            $toEmailList[] = $person['email'];
            $toIdList[] = $person['id'];
            }
            }
             */
            $toEmailList = array_unique($toEmailList);

            /*          \fCore::expose($title);
            \fCore::expose($body);
            \fCore::expose($toEmailList);
            exit;
             */
            // Save note

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

            $theNote           = new Note;
            $theNote->scenario = 'notes_c';

            $theNote->co       = NOW;
            $theNote->cb       = USER_ID;
            $theNote->uo       = NOW;
            $theNote->ub       = USER_ID;
            $theNote->status   = 'on';
            $theNote->via      = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                $theNote->priority = 'C1';
            }
            if ($utag) {
                $theNote->priority = 'A3';
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to    = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title   = $title;
            $theNote->body    = $_POST['body'];
            $theNote->rtype   = 'tour';
            $theNote->rid     = $theTour['tour']['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }

            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl  = 'https://my.amicatravel.com/tours/r/' . $theTour['tour']['id'];
            $relName = $theTour['tour']['code'] . ' - ' . $theTour['tour']['name'];

            // Upload files
            $fileList = '';
            if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) && count($_POST['fileid']) == count($_POST['filename'])) {
                foreach ($_POST['fileid'] as $i => $fileId) {
                    $newFileName = $_POST['filename'][$i];
                    $rawFileExt  = strrchr($newFileName, '.');
                    $rawFileName = $fileId . $rawFileExt;
                    $rawFilePath = Yii::getAlias('@webroot') . '/assets/plupload_2.1.9/' . $rawFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid  = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize  = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0] . '×' . $imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'           => ICT,
                                'cb'           => USER_ID,
                                'uo'           => ICT,
                                'ub'           => USER_ID,
                                'name'         => $newFileName,
                                'ext'          => $rawFileExt,
                                'size'         => $fileSize,
                                'img_size'     => $fileImgSize,
                                'uid'          => $fileUid,
                                'filegroup_id' => 1,
                                'rtype'        => 'tour',
                                'rid'          => $theTour['tour']['id'],
                                'n_id'         => $theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot') . '/upload/user-files/' . substr(ICT, 0, 7) . '/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-' . USER_ID . '-' . $newFileId . '-' . $fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir . $newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/' . $newFileId . '">' . $newFileName . '</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                            Yii::$app->db->createCommand()
                                ->delete('at_files', [
                                    'id' => $newFileId,
                                ])
                                ->execute();
                        }
                    }
                }
            }

            if ($fileList != '') {
                $body = $fileList . '<br>' . $body;
            }

            // Send email

            if (!empty($toEmailList)) {
                // Tour staff names, to include at the end of email subject
                $trail = $theTour['tour']['code'] . ' - ' . $theTour['tour']['name'];
                if (count($theTour['bookings']) > 1) {
                    $trail .= ' (combined)';
                }

                $tourPaxCount = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourPaxCount[] = $booking['pax'];
                }
                $trail .= ' - ' . implode('+', $tourPaxCount) . 'p ';
                $trail .= $theTour['day_count'] . 'd ';
                $trail .= date('j/n', strtotime($theTour['day_from'])) . ' - ';

                $tourStaff = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourStaff[] = $booking['createdBy']['nickname'];
                }
                foreach ($theTour['tour']['operators'] as $user) {
                    $tourStaff[] = $user['nickname'];
                }

                $trail .= implode(', ', $tourStaff);

                $subject = $theTour['tour']['code'] . ' - ' . $title;
                $subject = str_replace($theTour['tour']['code'] . ' - ' . $theTour['tour']['code'] . ' - ', $theTour['tour']['code'] . ' - ', $subject);
                if ($itag) {
                    $subject = '#important ' . $subject;
                }
                if ($utag) {
                    $subject = '#urgent ' . $subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Tour: ' . $trail;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-' . $theNote->id . '-' . USER_ID . '@amicatravel.com'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id => $user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                $this->mgIt(
                    $subject,
                    '//mg/note_added',
                    [
                        'toList'  => $toList,
                        'theNote' => $theNote,
                        'relUrl'  => $relUrl,
                        'body'    => $body,
                    ],
                    $args
                );
            }
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id' => $caseIdList])
            ->andWhere(['or', 'LOCATE("[cs]", subject)!=0', ['like', 'tags', 'op']])
            ->asArray()
            ->all();

        $theNotes = Note::find()
            ->where(['rtype' => 'tour', 'rid' => $theTour['tour']['id']])
            ->with([
                'files',
                'from' => function ($q) {
                    return $q->select(['id', 'nickname', 'image']);
                },
                'to'   => function ($q) {
                    return $q->select(['id', 'nickname']);
                },
            ])
            ->asArray()
            ->orderBy('co DESC')
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype' => 'tour', 'rid' => $theTour['tour']['id']])
            ->with([
                'user' => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'name']);
                },
            ])
            ->asArray()
            ->all();

        // Old pax, older tours if any
        $olderTours    = [];
        $tourPaxIdList = [];
        foreach ($tourPax as $user) {
            $tourPaxIdList[] = $user['id'];
        }
        if (!empty($tourPaxIdList)) {
            $sql        = 'SELECT t.id, t.code, t.name, t.status FROM at_tours t, at_ct p, at_booking_user bu, at_bookings b WHERE t.ct_id=p.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN (' . implode(',', $tourPaxIdList) . ') AND p.day_from<:start_date GROUP BY t.id LIMIT 10';
            $olderTours = Yii::$app->db->createCommand($sql, [':start_date' => $theTour['day_from']])->queryAll();
        }

        // Tour hang
        $companyIdList = [];
        $tourAgents    = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $companyIdList[] = $booking['case']['company_id'];
            }
        }
        if (!empty($companyIdList)) {
            $sql        = 'SELECT id, name, image FROM at_companies WHERE id IN (' . implode(', ', $companyIdList) . ') LIMIT 1';
            $tourAgents = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Drivers and vehicles
        $sql         = 'select * from at_tour_drivers where tour_id=:tour_id limit 100';
        $tourDrivers = Yii::$app->db->createCommand($sql, [':tour_id' => $productId])->queryAll();

        // Render view
        return $this->render('tour_x', [
            'theTour'       => $theTour,
            'thePeople'     => $thePeople,
            'inboxMails'    => $inboxMails,
            'theNotes'      => $theNotes,
            'theSysnotes'   => $theSysnotes,
            'tourPax'       => $tourPax,
            'tourRegInfo'   => $tourRegInfo,
            'tourRefs'      => $tourRefs,
            'olderTours'    => $olderTours,
            'tourAgents'    => $tourAgents,
            'tourDrivers'   => $tourDrivers,
            'tourGuides'    => $tourGuides,
            'tourOperators' => $tourOperators,
            'tourCSStaff'   => $tourCSStaff,
            'tourFeedbacks' => $tourFeedbacks,
        ]);
    }

    public function actionTongHopRoiNuoc($month = '', $venue = 1347)
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym, COUNT(*) AS total FROM cpt WHERE plusminus = "plus" AND LOCATE(:rn, dvtour_name)!=0 GROUP BY ym ORDER BY ym DESC', ['rn' => 'ối nước'])
            ->queryAll();
        $theCptx = Cpt::find()
            ->with([
                'tour'                => function ($q) {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'tour.product'        => function ($q) {
                    return $q->select(['id']);
                },
                'tour.product.guides' => function ($q) {
                    return $q->select(['tour_id', 'guide_name', 'use_from_dt', 'use_until_dt']);
                },
                'tour.operators'      => function ($q) {
                    return $q->select(['id', 'name', 'phone']);
                },
            ])
            ->andWhere('LOCATE(:rn, dvtour_name)!=0', [':rn' => 'ối nước'])
            ->andWhere('SUBSTRING(dvtour_day,1,7)=:ym', [':ym' => $month])
        // ->andWhere('venue_id=:venue', [':venue'=>$venue])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();
        // Operators
        $tourIdList = [0];
        foreach ($theCptx as $cpt) {
            $tourIdList[] = $cpt['tour']['id'];
        }
        $sql           = 'SELECT u.id, u.nickname AS name, u.phone, tu.tour_id FROM users u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id IN (' . implode(',', $tourIdList) . ')';
        $tourOperators = Yii::$app->db->createCommand($sql)->queryAll();

        $sql       = 'SELECT v.id, v.name FROM venues v, cpt c WHERE c.venue_id=v.id AND LOCATE(:rn, dvtour_name)!=0';
        $venueList = Yii::$app->db->createCommand($sql, [':rn' => 'ối nước'])->queryAll();

        return $this->render('tours_tong-hop-roi-nuoc', [
            'theCptx'       => $theCptx,
            'month'         => $month,
            'monthList'     => $monthList,
            'tourOperators' => $tourOperators,
            'venue'         => $venue,
            'venueList'     => $venueList,
        ]);
    }

    public function actionTongHopNuocUong($month = '', $name = '')
    {
        $getMonth = $month;

        if (strlen($getMonth) != 7) {
            $getMonth = date('Y-m');
        }

        $monthList = Yii::$app->db
        //->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym, COUNT(*) AS total FROM cpt WHERE plusminus = "plus" AND LOCATE(:rn, dvtour_name)!=0 AND price=3190 GROUP BY ym ORDER BY ym DESC', ['rn'=>'ước uống'])
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym, COUNT(*) AS total FROM cpt WHERE plusminus = "plus" AND price=3190 GROUP BY ym ORDER BY ym DESC', ['rn' => 'ước uống'])
            ->queryAll();
        $theCptx = Cpt::find()
            ->with([
                'tour'                => function ($q) {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'tour.product'        => function ($q) {
                    return $q->select(['id']);
                },
                'tour.product.guides' => function ($q) {
                    return $q->select(['tour_id', 'guide_name', 'use_from_dt', 'use_until_dt']);
                },
                'tour.operators'      => function ($q) {
                    return $q->select(['id', 'name', 'phone']);
                },
            ])
        //->where('LOCATE(:rn, dvtour_name)!=0', [':rn'=>'ước uống'])
            ->andWhere('price=3190', [':ym' => $getMonth])
            ->andWhere('SUBSTRING(dvtour_day,1,7)=:ym', [':ym' => $getMonth])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        return $this->render('tours_tong-hop-nuoc-uong', [
            'theCptx'   => $theCptx,
            'getMonth'  => $getMonth,
            'name'      => $name,
            'monthList' => $monthList,
        ]);
    }

    public function actionU($id = 0, $for = '')
    {
        $theTourOld = Tour::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($theTourOld['status'] == 'draft') {
            return $this->redirect('@web/tours/accept/' . $theTourOld['id']);
        }

        $theTour = Product::find()
            ->where(['id' => $theTourOld['ct_id']])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array($for, ['', 'dhsg', 'ducanh'])) {
            $for = '';
        }

        if (!in_array(USER_ID, [1, 118, 25457, 8162])) {
            throw new HttpException(403, 'Access denied.');
        }

        if (USER_ID == 25457 && $for != 'dhsg') {
            throw new HttpException(403, 'Access denied.');
        }

        if (USER_ID == 8162 && $for != 'ducanh') {
            // throw new HttpException(403, 'Access denied.');
        }

        if ($for == 'dhsg') {
            $sql          = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from users u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND u.id IN (25457, 27726, 37675) ORDER BY u.lname';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();
        } elseif ($for == 'ducanh') {
            $sql          = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from users u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND u.id IN (8162, 34596) ORDER BY u.lname';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $sql          = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from users u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND ur.role_id=5 AND u.status="on" ORDER BY u.lname';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $sql             = 'select user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
        $oldOperatorList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

        $theForm            = new TourAcceptForm;
        $theForm['op_code'] = $theTour['op_code'];
        $theForm['op_name'] = $theTour['op_name'];
        $theForm['owner']   = $theTourOld['owner'];

        $oldOperatorIdList = [];
        foreach ($oldOperatorList as $operator) {
            $oldOperatorIdList[] = $operator['user_id'];
        }

        $theForm['operators'] = $oldOperatorIdList;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            if ($for == '') {
                $theTour['op_name']    = $theForm['op_name'];
                $theTour['op_code']    = $theForm['op_code'];
                $theTour['client_ref'] = $theForm['client_ref'];
                $theTour->save(false);
            }
            Yii::$app->db->createCommand()->update(
                'at_tours', [
                    'uo'    => NOW,
                    'ub'    => USER_ID,
                    'name'  => $theTour['op_name'],
                    'code'  => $theTour['op_code'],
                    'owner' => $theForm['owner'],
                ], ['id' => $theTourOld['id']])->execute();

            Yii::$app->db->createCommand()->update('at_search', [
                'search' => str_replace(['-'], [''], \fURL::makeFriendly($theTour['op_code'] . ' ' . $theTour['op_name'], '-')),
                'found'  => $theTour['op_code'] . ' ' . $theTour['op_name'],
            ], [
                'rtype' => 'tour',
                'rid'   => $theTourOld['id'],
            ])->execute();

            // Delete removed ops
            foreach ($oldOperatorIdList as $id) {
                if ($for == 'dhsg' && !in_array($id, $theForm['operators']) && in_array($id, [25457, 27726, 37675])) {
                    Yii::$app->db->createCommand()
                        ->delete('at_tour_user', ['tour_id' => $theTourOld['id'], 'user_id' => $id, 'role' => 'operator'])
                        ->execute();
                }
                if ($for == 'ducanh' && !in_array($id, $theForm['operators']) && in_array($id, [8162, 34596])) {
                    Yii::$app->db->createCommand()
                        ->delete('at_tour_user', ['tour_id' => $theTourOld['id'], 'user_id' => $id, 'role' => 'operator'])
                        ->execute();
                }
                if ($for == '' && !in_array($id, $theForm['operators'])) {
                    Yii::$app->db->createCommand()
                        ->delete('at_tour_user', ['tour_id' => $theTourOld['id'], 'user_id' => $id, 'role' => 'operator'])
                        ->execute();
                }
            }

            // Save and email new ops
            foreach ($theForm['operators'] as $userId) {
                if (!in_array($userId, $oldOperatorIdList)) {
                    // Save tour op
                    Yii::$app->db->createCommand()->insert('at_tour_user', [
                        'tour_id' => $theTourOld['id'],
                        'user_id' => $userId,
                        'role'    => 'operator',
                    ])->execute();

                    // Email
                    if ($for == 'dhsg') {
                        $this->mgIt(
                            'ims | Kim Ngoc has just assigned an operator to tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'],
                            '//mg/tour_assign',
                            [
                                'theTour'    => $theTour,
                                'theTourOld' => $theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', 'bich.ngoc@amica-travel.com', 'Ngoc', 'HB.'],
                            ]
                        );
                        $newOperator = Person::find(['id', 'fname', 'lname', 'email'])->where(['id' => $userId])->asArray()->one();
                        $this->mgIt(
                            'ims | Tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'] . '" has been assigned to you',
                            '//mg/tour_assign',
                            [
                                'theTour'    => $theTour,
                                'theTourOld' => $theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', $newOperator['email'], $newOperator['lname'], $newOperator['fname']],
                            ]
                        );
                    } elseif ($for == 'ducanh') {
                        $this->mgIt(
                            'ims | Đức Anh has just assigned an operator to tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'],
                            '//mg/tour_assign',
                            [
                                'theTour'    => $theTour,
                                'theTourOld' => $theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', 'bich.ngoc@amica-travel.com', 'Ngoc', 'HB.'],
                            ]
                        );
                        $newOperator = Person::find(['id', 'fname', 'lname', 'email'])->where(['id' => $userId])->asArray()->one();
                        $this->mgIt(
                            'ims | Tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'] . '" has been assigned to you',
                            '//mg/tour_assign',
                            [
                                'theTour'    => $theTour,
                                'theTourOld' => $theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', $newOperator['email'], $newOperator['lname'], $newOperator['fname']],
                            ]
                        );
                    } else {
                        $newOperator = Person::find(['id', 'fname', 'lname', 'email'])->where(['id' => $userId])->asArray()->one();
                        $this->mgIt(
                            'ims | Tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'] . '" has been assigned to you',
                            '//mg/tour_assign',
                            [
                                'theTour'    => $theTour,
                                'theTourOld' => $theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', $newOperator['email'], $newOperator['lname'], $newOperator['fname']],
                            ]
                        );
                    }

                }
            }

            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tours_accept', [
            'theTour'      => $theTour,
            'theTourOld'   => $theTourOld,
            'theForm'      => $theForm,
            'operatorList' => $operatorList,
            'for'          => $for,
        ]);
    }

    // Assign customer care personnel
    public function actionCskh($id = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id, 'op_status' => 'op', 'op_finish' => ''])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array(USER_ID, [1, 27388, 29296])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $sql     = 'select u.id, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from persons u WHERE id IN (1351, 12952, 29123, 29296, 30554, 39063, 35071) AND u.status="on" AND u.is_member="yes" ORDER BY lname, fname';
        $cssList = Yii::$app->db->createCommand($sql)->queryAll();

        $theForm = new TourAssignCsForm;

        $sql        = 'select user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="cservice"';
        $oldCssList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

        $oldCssIdList = [];
        foreach ($oldCssList as $cs) {
            $oldCssIdList[] = $cs['user_id'];
        }
        $theForm['css'] = $oldCssIdList;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            // Remove old
            foreach ($oldCssIdList as $oldId) {
                if (!in_array($oldId, $theForm['css'])) {
                    Yii::$app->db->createCommand()->delete('at_tour_user', ['tour_id' => $theTourOld['id'], 'user_id' => $oldId, 'role' => 'cservice'])->execute();
                }
            }
            // Email new
            foreach ($theForm['css'] as $newId) {
                if (!in_array($newId, $oldCssIdList)) {
                    // Save tour op
                    Yii::$app->db->createCommand()->insert('at_tour_user', [
                        'tour_id' => $theTourOld['id'],
                        'user_id' => $newId,
                        'role'    => 'cservice',
                    ])->execute();
                    // Email
                    foreach ($cssList as $user) {
                        if ($user['id'] == $newId && $newId != USER_ID) {
                            $this->mgIt(
                                'ims | Tour "' . $theTour['op_code'] . ' - ' . $theTour['op_name'] . '" has been assigned to you',
                                '//mg/tour_assign',
                                [
                                    'theTour'    => $theTour,
                                    'theTourOld' => $theTourOld,
                                ],
                                [
                                    ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                    ['to', $user['email'], $user['lname'], $user['fname']],
                                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                                ]
                            );
                        }
                    }
                }
            }
            // Redir
            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tours_cskh', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            'theForm'    => $theForm,
            'cssList'    => $cssList,
        ]);
    }

    // Cancel a tour; must cancel bookings first
    public function actionCxl($id = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array(USER_ID, [1, 118, 8162])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // TODO check if all bookings have been canceled

        $sql             = 'select user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
        $oldOperatorList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

        $oldOperatorIdList = [];
        foreach ($oldOperatorList as $operator) {
            $oldOperatorIdList[] = $operator['user_id'];
        }

        if (Yii::$app->request->post('confirm') == 'cancel') {
            // Cancel tour
            Yii::$app->db->createCommand()->update('at_tours', ['status' => 'deleted'], ['id' => $theTourOld['id']])->execute();
            Yii::$app->db->createCommand()->update('at_ct', ['op_finish' => 'canceled', 'op_finish_dt' => NOW], ['id' => $theTour['id']])->execute();
            // Email people
            // Set message
            Yii::$app->session->setFlash('success', 'Tour operation has been canceled: ' . $theTour['op_code']);
            // Redir
            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tours_cxl', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            //'operatorList'=>$operatorList,
        ]);
    }

    public function actionAjax2($search = 'hansgn')
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        if (isset($_POST['action'], $_POST['dvtour_id'], $_POST['tour_id'], $_POST['formdata'])) {
            $theTourOld = Tour::find()
                ->where(['id' => $_POST['tour_id']])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found');
            }
            $theTourOld = Tour::find()
                ->where(['id' => $_POST['tour_id']])
                ->asArray()
                ->one();

            if ($_POST['dvtour_id'] != 0) {
                $theCpt = Cpt::find()
                    ->where(['dvtour_id' => $_POST['dvtour_id']])
                    ->asArray()
                    ->one();
                if (!$theCpt) {
                    throw new HttpException(404, 'Tour cost not found');
                }

                $checkStatus = [
                    'c1' => strpos($dv['c1'], 'on') !== false,
                    'c2' => strpos($dv['c2'], 'on') !== false,
                    'c3' => strpos($dv['c3'], 'on') !== false,
                    'c4' => strpos($dv['c4'], 'on') !== false,
                    'c5' => strpos($dv['c5'], 'on') !== false,
                    'c6' => strpos($dv['c6'], 'on') !== false,
                    'c7' => strpos($dv['c7'], 'on') !== false,
                    'c8' => strpos($dv['c8'], 'on') !== false,
                    'c9' => strpos($dv['c9'], 'on') !== false,
                ];

            }

            foreach ($_POST['formdata'] as $fd) {
                $_POST[$fd['name']] = $fd['value'];
            }

            // Action create
            if ($_POST['action'] == 'create') {
                if (!in_array(USER_ID, $tourOperatorIds) && USER_ID != 1) {
                    die(json_encode(array('NOK', '2 - Access denied for tour : [' . $_POST['tour_id'] . ']')));
                }
                $fv             = new hxFormValidation();
                $_POST['qty']   = str_replace(',', '', $_POST['qty']);
                $_POST['price'] = str_replace(',', '', $_POST['price']);
                $fv->setRules('dvtour_name', 'Tên dịch vụ', 'trim|required|max_length[64]');
                $fv->setRules('oppr', 'Đối tác / Cung cấp', 'trim|max_length[64]');
                $fv->setRules('venue_id', 'Chọn địa điểm (venue)', 'trim|required|is_natural');
                $fv->setRules('qty', 'Số lượng', 'trim|required|is_numeric');
                $fv->setRules('unit', 'Đơn vị', 'trim|required|max_length[64]');
                $fv->setRules('price', 'Đơn giá', 'trim|required|is_numeric');
                $fv->setRules('unitc', 'Đơn vị tiền tệ', 'trim|required|exact_length[3]');
                $fv->setRules('vat', 'VAT (%)', 'trim|required|is_natural');
                $fv->setRules('prebooking', 'Cần book trước hay không', 'trim|required');
                $fv->setRules('payer', 'Người trả', 'trim|required|max_length[64]');
                $fv->setRules('due', 'Hạn thanh toán', 'trim|exact_length[10]');
                $fv->setRules('status', 'Tình trạng đặt trước', 'trim|required|exact_length[1]');
                if ($fv->run()) {
                    $q = Yii::$app->db->createCommand('INSERT INTO cpt (created_at, created_by, updated_at, updated_by, tour_id, dvtour_day, dvtour_name, oppr,
                        adminby, via_company_id, by_company_id, venue_id, start, number, qty, unit, price, unitc, vat, prebooking, payer, status, due, plusminus)
                        VALUES (%s, %i, %s, %i, %i, %s, %s, %s, %s, %i, %i, %i, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
                        NOW, USER_ID, NOW, USER_ID, $_POST['tour_id'], $_POST['dvtour_day'], $_POST['dvtour_name'], $_POST['oppr'],
                        $_POST['adminby'], $_POST['via_company_id'], $_POST['by_company_id'], $_POST['venue_id'], $_POST['start'], $_POST['number'],
                        $_POST['qty'], $_POST['unit'], $_POST['price'], $_POST['unitc'], $_POST['vat'], $_POST['prebooking'], $_POST['payer'], $_POST['status'], $_POST['due'], $_POST['plusminus']
                    );
                    $newDvId = $q->getAutoIncrementedValue();

                    // Save note if any
                    if ($_POST['mm'] != '') {
                        Yii::$app->db->createCommand('INSERT INTO at_comments (created_at, created_by, updated_at, updated_by, status, rtype, rid, pid, body) VALUES (%s, %i, %s, %i, %s, %s, %i, %i, %s)',
                            NOW, USER_ID, NOW, USER_ID, 'on', 'cpt', $newDvId, $_POST['tour_id'], $_POST['mm']
                        );
                    }

                    die(json_encode(array('OK-CREATE', '', $newDvId, $_POST['dvtour_day'])));
                } else {
                    die(json_encode(array('NOK', strip_tags($fv->getErrorMessage()))));
                }
            }
        }
    }

    public function actionCalendar($date = '', $view = '')
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

        $sql      = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND DATE_ADD(day_from, INTERVAL day_count DAY)>:this ORDER BY day_from, id LIMIT 1000';
        $theTours = Product::findBySql($sql, [':this' => $thisWeek, ':next' => $nextWeek])
            ->with([
                'tournotes',
                'tournotes.updatedBy' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'days'                => function ($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings.createdBy'  => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
                'tour'                => function ($q) {
                    return $q->select(['id', 'ct_id']);
                },
                'tour.cpt'            => function ($q) {
                    return $q->select(['dvtour_id', 'tour_id', 'dvtour_name', 'dvtour_day', 'venue_id', 'qty', 'unit'])
                        ->where('venue_id!=0')
                        ->andWhere(['or', 'dvtour_name="Khách sạn"', 'dvtour_name="Hotel"', 'dvtour_name="Tàu ngủ đêm"', 'dvtour_name="Tàu Hạ Long"', 'dvtour_name="nhà dân"', 'dvtour_name="Accommodation"']);
                },
                'tour.cpt.venue'      => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'tour.operators'      => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
                'tour.cskh'           => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
            ])
            ->asArray()->all();

        // Drivers and vehicles
        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }

        // Khach sinh nhat trong khoang nay
        $dayList = [];
        for ($i = 0; $i < 7; $i++) {
            $dayList[] = date('j/n', strtotime('+' . $i . ' days', strtotime($thisWeek)));
        }
        $sql              = 'SELECT u.id AS user_id, u.bday, u.bmonth, u.byear, u.name, p.id AS product_id FROM persons u, at_booking_user bu, at_bookings b, at_ct p WHERE u.id=bu.user_id AND b.id=bu.booking_id AND p.id=b.product_id AND CONCAT(u.bday, "/", u.bmonth) IN ("' . implode('","', $dayList) . '") AND p.id IN (' . implode(',', $tourIdList) . ')';
        $paxWithBirthdays = Yii::$app->db->createCommand($sql, [':day1' => date('j', strtotime($thisWeek)), ':day2' => date('j', strtotime($nextWeek)), ':month1' => date('n', strtotime($thisWeek)), ':month2' => date('n', strtotime($nextWeek))])->queryAll();
        if (isset($_GET['x'])) {
            \fCore::expose($paxWithBirthdays);
            exit;
        }

        $sql        = 'select *, IF(guide_user_id=0, guide_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id IN (' . implode(',', $tourIdList) . ') order by use_from_dt limit 1000';
        $tourGuides = Yii::$app->db->createCommand($sql)->queryAll();

        $sql         = 'select *, IF(driver_user_id=0, driver_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id IN (' . implode(',', $tourIdList) . ') order by use_from_dt limit 1000';
        $tourDrivers = Yii::$app->db->createCommand($sql)->queryAll();

        if ($view == 'v') {
            return $this->render('tours_calendar_v', [
                'theTours'         => $theTours,
                'theGuides'        => [],
                'prevWeek'         => $prevWeek,
                'thisWeek'         => $thisWeek,
                'nextWeek'         => $nextWeek,
                'tourDrivers'      => $tourDrivers,
                'tourGuides'       => $tourGuides,
                'paxWithBirthdays' => $paxWithBirthdays,
            ]);
        }

        return $this->render('tours_calendar', [
            'theTours'         => $theTours,
            'prevWeek'         => $prevWeek,
            'thisWeek'         => $thisWeek,
            'nextWeek'         => $nextWeek,
            'tourDrivers'      => $tourDrivers,
            'tourGuides'       => $tourGuides,
            'paxWithBirthdays' => $paxWithBirthdays,
        ]);
    }

    // Month calendar
    public function actionCalendarMonth($date = '', $view = '')
    {
        if (strlen($date) != 10) {
            $date = date('Y-m-d');
        }

        $thisMonth = $date;
        $prevMonth = date('Y-m-d', strtotime('-30 days', strtotime($thisMonth)));
        $nextMonth = date('Y-m-d', strtotime('+30 days', strtotime($thisMonth)));

        $sql      = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND DATE_ADD(day_from, INTERVAL day_count DAY)>:this ORDER BY day_from, id LIMIT 1000';
        $theTours = Product::findBySql($sql, [':this' => $thisMonth, ':next' => $nextMonth])
            ->with([
                'tournotes',
                'tournotes.updatedBy' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'days'                => function ($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings.createdBy'  => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'tour'                => function ($q) {
                    return $q->select(['id', 'ct_id']);
                },
                'tour.cpt'            => function ($q) {
                    return $q->select(['dvtour_id', 'tour_id', 'dvtour_name', 'dvtour_day', 'venue_id', 'qty', 'unit'])
                        ->where('venue_id!=0')
                        ->andWhere(['or', 'dvtour_name="Khách sạn"', 'dvtour_name="Hotel"', 'dvtour_name="Tàu ngủ đêm"', 'dvtour_name="nhà dân"', 'dvtour_name="Accommodation"']);
                },
                'tour.cpt.venue'      => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'tour.operators'      => function ($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()->all();

        // Drivers and vehicles
        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }

        $sql        = 'select *, IF(guide_user_id=0, guide_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id IN (' . implode(',', $tourIdList) . ') order by use_from_dt limit 1000';
        $tourGuides = Yii::$app->db->createCommand($sql)->queryAll();

        $sql         = 'select *, IF(driver_user_id=0, driver_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id IN (' . implode(',', $tourIdList) . ') order by use_from_dt limit 1000';
        $tourDrivers = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('tours_calendar-month', [
            'theTours'    => $theTours,
            'prevMonth'   => $prevMonth,
            'thisMonth'   => $thisMonth,
            'nextMonth'   => $nextMonth,
            'tourDrivers' => $tourDrivers,
            'tourGuides'  => $tourGuides,
        ]);
    }

    // Vehicles and drivers settings
    // id = product id
    // action = add|addtime|edit|delete = add more svc time
    // edit = edit
    // delete = delete
    public function actionDrivers($id = 0, $action = 'add', $item_id = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id, 'op_status' => 'op'])
            ->with(['days'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour itinerary not found.');
        }

        if (!in_array(USER_ID, [1])) {
            // throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Drivers and vehicles
        $sql         = 'select *, driver_name, driver_user_id, IF(driver_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id=:tour_id order by use_from_dt limit 100';
        $tourDrivers = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Driver list
        $sql        = 'select u.id, CONCAT(u.name, " - ", REPLACE(u.phone, " ", "")) AS namephone from persons u, at_profiles_driver p where u.id=p.user_id order by u.lname, u.fname limit 3000';
        $theDrivers = Yii::$app->db->createCommand($sql)->queryAll();

        $theDriver = false;

        // Check action
        if (
            !in_array($action, ['add', 'addtime', 'edit', 'delete'])
            || (in_array($action, ['addtime', 'edit', 'delete']) && $item_id == 0)
        ) {
            return $this->redirect(DIR . URI);
        }

        // action add
        if ($action == 'add') {
            $theForm                = new TourDriverForm;
            $theForm->bookingStatus = 'confirmed';
            $theForm->useTimezone   = 'Asia/Ho_Chi_Minh';
            $theForm->useFromDt     = $theTour['day_from'] . ' 08:00';
            $theForm->useUntilDt    = date('Y-m-d', strtotime('+ ' . ($theTour['day_count'] - 1) . ' days', strtotime($theTour['day_from']))) . ' 22:00';

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $driverUserId = 0;
                foreach ($theDrivers as $driver) {
                    if ($theForm['driverName'] == trim($driver['namephone'])) {
                        $driverUserId = $driver['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->insert('at_tour_drivers', [
                    'created_dt'     => NOW,
                    'created_by'     => USER_ID,
                    'updated_dt'     => NOW,
                    'updated_by'     => USER_ID,
                    'tour_id'        => $theTour['id'],
                    'vehicle_type'   => $theForm['vehicleType'],
                    'vehicle_number' => $theForm['vehicleNumber'],
                    'driver_company' => $theForm['driverCompany'],
                    'driver_name'    => $theForm['driverName'],
                    'driver_user_id' => $driverUserId,
                    'use_from_dt'    => $theForm['useFromDt'],
                    'use_until_dt'   => $theForm['useUntilDt'],
                    'use_timezone'   => $theForm['useTimezone'],
                    'booking_status' => $theForm['bookingStatus'],
                    'points'         => $theForm['points'],
                    'note'           => $theForm['note'],
                ])->execute();

                return $this->redirect(DIR . URI);
            }
        }

        // action add time
        if ($action == 'addtime' && $item_id != 0) {
            foreach ($tourDrivers as $driver) {
                if ($driver['id'] == $item_id) {
                    $theDriver = $driver;
                }
            }

            if (!$theDriver) {
                throw new HttpException(404, 'Driver info not found');
            }

            if (!in_array(USER_ID, [1, 118, 29296, 33415, $theDriver['created_by'], $theDriver['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourDriverForm;

            $theForm->useTimezone   = $theDriver['use_timezone'];
            $theForm->vehicleType   = $theDriver['vehicle_type'];
            $theForm->vehicleNumber = $theDriver['vehicle_number'];
            $theForm->driverCompany = $theDriver['driver_company'];
            $theForm->driverName    = $theDriver['driver_name'];
            $theForm->useFromDt     = $theDriver['use_from_dt'];
            $theForm->useUntilDt    = $theDriver['use_until_dt'];
            $theForm->bookingStatus = $theDriver['booking_status'];
            $theForm->points        = $theDriver['points'];
            $theForm->note          = $theDriver['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                Yii::$app->db->createCommand()->insert('at_tour_drivers', [
                    'created_dt'     => NOW,
                    'created_by'     => USER_ID,
                    'updated_dt'     => NOW,
                    'updated_by'     => USER_ID,
                    'parent_id'      => $item_id,
                    'tour_id'        => $theTour['id'],
                    'vehicle_type'   => $theForm['vehicleType'],
                    'vehicle_number' => $theForm['vehicleNumber'],
                    'driver_company' => $theForm['driverCompany'],
                    'driver_name'    => $theForm['driverName'],
                    'driver_user_id' => $theDriver['driver_user_id'],
                    'use_from_dt'    => $theForm['useFromDt'],
                    'use_until_dt'   => $theForm['useUntilDt'],
                    'use_timezone'   => $theForm['useTimezone'],
                    'booking_status' => $theForm['bookingStatus'],
                    //'points'=>$theForm['points'],
                    'note'           => $theForm['note'],
                ])->execute();

                return $this->redirect(DIR . URI);
            }
        }

        // action edit
        if ($action == 'edit' && $item_id != 0) {
            foreach ($tourDrivers as $driver) {
                if ($driver['id'] == $item_id) {
                    $theDriver = $driver;
                }
            }

            if (!$theDriver) {
                throw new HttpException(404, 'Driver info not found');
            }

            $allowEditList = [1, 118, 29296, 33415, $theDriver['created_by'], $theDriver['updated_by']];

            // Tour ops
            $sql       = 'SELECT user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
            $tourOpIds = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

            foreach ($tourOpIds as $opId) {
                $allowEditList[] = $opId['user_id'];
            }

            $allowEditList = array_unique($allowEditList);

            if (!in_array(USER_ID, $allowEditList)) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourDriverForm;

            $theForm->useTimezone   = $theDriver['use_timezone'];
            $theForm->vehicleType   = $theDriver['vehicle_type'];
            $theForm->vehicleNumber = $theDriver['vehicle_number'];
            $theForm->driverCompany = $theDriver['driver_company'];
            $theForm->driverName    = $theDriver['driver_name'];
            $theForm->useFromDt     = $theDriver['use_from_dt'];
            $theForm->useUntilDt    = $theDriver['use_until_dt'];
            $theForm->bookingStatus = $theDriver['booking_status'];
            $theForm->points        = $theDriver['points'];
            $theForm->note          = $theDriver['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $driverUserId = 0;
                foreach ($theDrivers as $driver) {
                    if ($theForm['driverName'] == trim($driver['namephone'])) {
                        $driverUserId = $driver['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->update('at_tour_drivers', [
                    'updated_dt'     => NOW,
                    'updated_by'     => USER_ID,
                    'vehicle_type'   => $theForm['vehicleType'],
                    'vehicle_number' => $theForm['vehicleNumber'],
                    'driver_company' => $theForm['driverCompany'],
                    'driver_name'    => $theDriver['driver_user_id'] != 0 ? $theDriver['driver_name'] : $theForm['driverName'],
                    'driver_user_id' => $theDriver['driver_user_id'] != 0 ? $theDriver['driver_user_id'] : $driverUserId,
                    'use_from_dt'    => $theForm['useFromDt'],
                    'use_until_dt'   => $theForm['useUntilDt'],
                    'use_timezone'   => $theForm['useTimezone'],
                    'booking_status' => $theForm['bookingStatus'],
                    'points'         => $theForm['points'],
                    'note'           => $theForm['note'],
                ], ['id' => $item_id])->execute();
                Yii::$app->session->setFlash('success', 'Driver info has been updated: ' . $theDriver['driver_company'] . ' / ' . $theDriver['driver_name']);
                return $this->redirect(DIR . URI);
            }
        }

        // action delete
        if ($action == 'delete' && $item_id != 0) {
            $theForm = false;
            foreach ($tourDrivers as $driver) {
                if ($driver['id'] == $item_id) {
                    $theDriver = $driver;
                }
            }

            if (!$theDriver) {
                throw new HttpException(404, 'Driver info not found');
            }

            if (!in_array(USER_ID, [1, 118, $theDriver['created_by'], $theDriver['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }

            //if (Yii::$app->request->post('confirm') == 'delete') {
            Yii::$app->db->createCommand()->delete('at_tour_drivers', ['parent_id' => $item_id])->execute();
            Yii::$app->db->createCommand()->delete('at_tour_drivers', ['id' => $item_id])->execute();
            Yii::$app->session->setFlash('success', 'Driver info has been deleted: ' . $theDriver['driver_company'] . ' / ' . $theDriver['driver_name']);
            return $this->redirect(DIR . URI);
            //}
        }

        return $this->render('tours_drivers', [
            'theTour'     => $theTour,
            'theTourOld'  => $theTourOld,
            'theForm'     => $theForm,
            'tourDrivers' => $tourDrivers,
            'theDriver'   => $theDriver,
            'theDrivers'  => $theDrivers,
            'action'      => $action,
            'item_id'     => $item_id,
        ]);
    }

    // Pax list
    public function actionPax($id, $action = 'list', $pax = 0, $booking = 0, $contact = 0)
    {
        $theTour = Product::find()
            ->with([
                'pax' => function ($q) {
                    return $q->orderBy('booking_id, name');
                },
                'bookings',
                'bookings.case',
                'bookings.case.people',
                'bookings.people',
                'bookings.people.country',
            ])
            ->where(['id' => $id])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $bookingIdList = [];
        foreach ($theTour['bookings'] as $_booking) {
            $bookingIdList[] = $_booking['id'];
        }

        // If only 1 booking
        if (count($bookingIdList) == 1) {
            $booking = $bookingIdList[0];
        }

        // Added Thu Tran, Phuong Anh
        $allowList = [1, 8162, 34595, 39748, 1351, 29296, 12952, 27388, 29123, 30554, 35071, 33415, 39063, 40217];

        if ($action == 'link' && $contact != 0) {
            // Link new pax to existing contact
            foreach ($theTour['bookings'] as $tbooking) {
                foreach ($tbooking['case']['people'] as $bkcontact) {
                    if ($bkcontact['id'] == $contact) {
                        $thePax                  = new Pax;
                        $thePax->account_id      = 1;
                        $thePax->created_by      = USER_ID;
                        $thePax->updated_by      = USER_ID;
                        $thePax->created_dt      = NOW;
                        $thePax->updated_dt      = NOW;
                        $thePax->tour_id         = $theTour['id'];
                        $thePax->booking_id      = $booking;
                        $thePax->contact_id      = $bkcontact['id'];
                        $thePax->name            = $bkcontact['name'];
                        $thePax->pp_country_code = $bkcontact['country_code'];
                        $data                    = [
                            'pp_country_code' => $bkcontact['country_code'],
                            'gender'          => $bkcontact['gender'],
                            'tel'             => $bkcontact['phone'],
                            'email'           => $bkcontact['email'],
                            'name'            => $bkcontact['name'],
                        ];
                        $thePax->data = serialize($data);
                        $thePax->save(false);
                        break;
                    }
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax has been linked to contact.'));
            return $this->redirect('');
        }

        if ($action == 'edit' && $pax != 0) {
            $theForm = new \app\models\BookingPaxForm;
            $thePax  = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $theForm->setAttributes(unserialize($thePax->data));
        } elseif ($action == 'view' && $pax != 0) {
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $theForm = false;
        } elseif ($action == 'cancel' && $pax != 0) {
            // TODO cancel pax booking
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $thePax->status = 'canceled';
            $thePax->save(false);
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax has been canceled.'));
            return $this->redirect('/tours/pax/' . $theTour['id']);

        } elseif ($action == 'uncancel' && $pax != 0) {
            // TODO cancel pax booking
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $thePax->status = '';
            $thePax->save(false);
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax has been un-canceled.'));
            return $this->redirect('/tours/pax/' . $theTour['id']);
        } elseif ($action == 'delete' && $pax != 0) {
            // TODO delete pax info
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, [$thePax['created_by'], $thePax['updated_by']])) {
                throw new HttpException(404, 'Access denied.');
            }
            $thePax->delete();
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax info has been deleted.'));
            return $this->redirect('/tours/pax/' . $theTour['id']);
        } else {
            // List all pax and add new pax
            $theForm            = new \app\models\BookingPaxForm;
            $thePax             = new Pax;
            $thePax->account_id = 1;
            $thePax->created_by = USER_ID;
            $thePax->updated_by = USER_ID;
            $thePax->created_dt = NOW;
            $thePax->updated_dt = NOW;
            $thePax->tour_id    = $theTour['id'];
            $thePax->booking_id = $booking;
            $thePax->contact_id = 0;
        }

        if ($theForm && $theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if ($action == 'list' && !in_array($booking, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if ($action == 'list' && !in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }

            $thePax->is_repeating    = $theForm->is_repeating;
            $thePax->name            = $theForm->name;
            $thePax->pp_name         = $theForm->pp_name;
            $thePax->pp_name2        = $theForm->pp_name2;
            $thePax->pp_gender       = $theForm->pp_gender;
            $thePax->pp_country_code = $theForm->pp_country_code == '' ? null : $theForm->pp_country_code;
            $thePax->pp_number       = $theForm->pp_number;
            $thePax->pp_idate        = $theForm->pp_iyear . '-' . $theForm->pp_imonth . '-' . $theForm->pp_iday;
            $thePax->pp_edate        = $theForm->pp_eyear . '-' . $theForm->pp_emonth . '-' . $theForm->pp_eday;
            $thePax->pp_birthdate    = $theForm->pp_byear . '-' . $theForm->pp_bmonth . '-' . $theForm->pp_bday;
            $thePax->data            = serialize($theForm->getAttributes());
            $thePax->save(false);
            Yii::$app->session->setFlash('success', Yii::t('c', 'Pax info has been saved.'));
            return $this->redirect('/tours/pax/' . $theTour['id']);
        }

        // $inboxMails = Mail::find()
        //     ->select(['id', 'files'])
        //     ->where(['case_id'=>$caseIdList])
        //     ->asArray()
        //     ->all();

        // $theNotes = Note::find()
        //     ->select(['id'])
        //     ->where(['rtype'=>'tour', 'rid'=>$theTour['tour']['id']])
        //     ->with([
        //         'files',
        //     ->asArray()
        //     ->all();

        $countryList = \common\models\Country::find()
            ->select(['name_en', 'name_vi', 'code'])
            ->asArray()
            ->all();

        $genderList = [
            'male'   => 'Male',
            'female' => 'Female',
        ];

        return $this->render('tour_pax', [
            'theTour'     => $theTour,
            'theTourOld'  => $theTourOld,
            'theForm'     => $theForm,
            'thePax'      => $thePax,
            'countryList' => $countryList,
            'genderList'  => $genderList,
        ]);
    }

    // Copy chi phi tour
    public function actionCopyCosts($s = '', $d = '')
    {
        $sourceTour = Tour::find()
            ->where(['code' => $s])
            ->with(['cpt', 'cpt.venue'])
            ->asArray()
            ->one();
        $destTour = Tour::find()
            ->where(['code' => $d])
            ->with(['cpt', 'cpt.venue'])
            ->asArray()
            ->one();
        if (!$sourceTour && !$destTour) {
            return $this->render('tours_copy-costs');
        }
        $sourceProduct = Product::find()
            ->where(['op_code' => $s])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$sourceProduct) {
            throw new HttpException(404, 'Source tour not found.');
        }
        $destProduct = Product::find()
            ->where(['op_code' => $d])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$destProduct) {
            throw new HttpException(404, 'Destination tour not found.');
        }

        if (isset($_GET['sd']) && isset($_GET['dd']) && strlen($_GET['sd']) == 10 && strlen($_GET['dd']) == 10) {
            $dc        = (int) $_GET['dc'] == 0 ? 1 : (int) $_GET['dc'];
            $startDate = date('Y-m-d', strtotime($_GET['sd']));
            $dayList   = [$startDate];
            for ($i = 1; $i < $dc; $i++) {
                $dayList[] = date('Y-m-d', strtotime('+ ' . ($i) . ' days', strtotime($startDate)));
            }

            if (USER_ID == 1) {
                \fCore::expose($dayList);
                exit;
            }

            $sourceCpt = Cpt::find()
                ->where(['tour_id' => $sourceTour['id'], 'dvtour_day' => $dayList])
                ->asArray()
                ->all();
            if (!empty($sourceCpt)) {
                foreach ($sourceCpt as $cpt) {
                    Yii::$app->db
                        ->createCommand()
                        ->insert('cpt', [
                            'created_at'     => NOW,
                            'created_by'     => USER_ID,
                            'updated_at'     => NOW,
                            'updated_by'     => USER_ID,
                            'tour_id'        => $destTour['id'],
                            'dvtour_day'     => date('Y-m-d', strtotime($_GET['dd'])),
                            'dvtour_name'    => $cpt['dvtour_name'],
                            'oppr'           => $cpt['oppr'],
                            'venue_id'       => $cpt['venue_id'],
                            'by_company_id'  => $cpt['by_company_id'],
                            'via_company_id' => $cpt['via_company_id'],
                            'qty'            => $cpt['qty'],
                            'unit'           => $cpt['unit'],
                            'price'          => $cpt['price'],
                            'unitc'          => $cpt['unitc'],
                            'booker'         => $cpt['booker'],
                            'payer'          => $cpt['payer'],
                            'adminby'        => $cpt['adminby'],
                            'status'         => 'n',
                            'plusminus'      => $cpt['plusminus'],
                        ])->execute();
                    echo $cpt['dvtour_name'];
                }
            }
            return $this->redirect('@web/tours/copy-costs?s=' . $s . '&d=' . $d);
        }

        return $this->render('tours_copy-costs', [
            'sourceProduct' => $sourceProduct,
            'destProduct'   => $destProduct,
            'sourceTour'    => $sourceTour,
            'destTour'      => $destTour,
        ]);
    }

    // In ct cho dieu hanh
    public function actionInCt($id = 0)
    {
        $theTour = Product::find()
            ->with([
                'pax',
                'days',
                'updatedBy',
                'bookings',
                'bookings.people',
                'bookings.people.country',
                'bookings.case',
                'bookings.case.company',
            ])
            ->where(['id' => $id, 'op_status' => 'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theForm           = new TourInCtForm;
        $theForm->language = $theTour['language'];
        if (isset($_GET['language']) && in_array($_GET['language'], ['en', 'fr', 'vi'])) {
            $theForm->language = $_GET['language'];
        }
        $theForm->days     = '1-' . $theTour['day_count'];
        $theForm->sections = ['summary', 'itinerary', 'price'];
        $logoList          = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $logoList[] = [
                    'id'      => $booking['case']['company_id'],
                    'company' => $booking['case']['company']['name'],
                    'logo'    => $booking['case']['company']['image'],
                ];
            }
        }
        $logoList[] = [
            'id'      => 'amica',
            'company' => 'Amica Travel',
            'logo'    => Yii::$app->params['print_logo'],
        ]; // Agent goes first

        // If has 2 meaning agent + amica
        if (count($logoList) > 1) {
            $logoList[] = [
                'id'      => 'secret',
                'company' => 'Secret Indochina',
                'logo'    => Yii::$app->params['print_logo_si'],
            ]; // Agent goes first
        }

        $logo = $logoList[0]['logo'];

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (empty($theForm->sections)) {
                $theForm->sections = ['itinerary'];
            }

            if ($theForm->logo != '') {
                foreach ($theTour['bookings'] as $booking) {
                    if ($booking['case']['company_id'] == $theForm->logo) {
                        $logo = $booking['case']['company']['image'];
                    }
                }
            }

            // return $this->render('tours_in-ct_ok', [
            //     'theForm'=>$theForm,
            //     'logo'=>$logo,
            //     'theTour'=>$theTour,
            //     'theTourOld'=>$theTourOld,
            // ]);
        }

        return $this->render('tours_in-ct', [
            'theForm'    => $theForm,
            'logo'       => $logo,
            'logoList'   => $logoList,
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
        ]);
    }

    // In lich xe cho dieu hanh
    public function actionInLx($id = 0, $action = 'add', $lichxe = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id, 'op_status' => 'op'])
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
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->with([
                'operators' => function ($q) {
                    return $q->select(['id', 'name' => new \yii\db\Expression('CONCAT(fname, " ", lname, " - ", phone)')]);
                },
            ])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($lichxe != 0) {
            $theLichxe = Lichxe::find()
                ->where(['id' => $lichxe, 'tour_id' => $theTour['id']])
                ->one();
            if (!$theLichxe) {
                throw new HttpException(404, 'Lich xe not found');
            }
        }

        $theLichxes = Lichxe::find()
            ->where(['tour_id' => $theTour['id']])
            ->with([
                'updatedBy' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
            ])
            ->orderBy('updated_by DESC')
            ->asArray()
            ->all();

        $theLichxeContent = false;

        if ($action == 'add') {
            $theForm       = new TourInLxForm;
            $theForm->days = '1-' . $theTour['day_count'];
            $totalTourPax  = 0;
            foreach ($theTour['bookings'] as $booking) {
                $totalTourPax += $booking['pax'];
            }
            $theForm->pax          = $totalTourPax;
            $theForm->dieuhanh     = USER_ID;
            $theLichxe             = new Lichxe;
            $theLichxe->created_dt = NOW;
            $theLichxe->created_by = USER_ID;
            $theLichxe->updated_dt = NOW;
            $theLichxe->updated_by = USER_ID;
            $theLichxe->tour_id    = $theTour['id'];
        } elseif ($action == 'edit') {
            $theForm = new TourInLxForm;

            if ($theLichxe->cpkhac != '') {
                $cpkhac              = explode(';|', $theLichxe->cpkhac);
                $theForm->cpkhac_ten = $cpkhac[0];
                $theForm->cpkhac_dvi = $cpkhac[1];
                $theForm->cpkhac_sl  = $cpkhac[2];
                $theForm->cpkhac_gia = $cpkhac[3];
            }

            foreach (['name', 'days', 'vp', 'pax', 'vp', 'dieuhanh', 'huongdan', 'loaixe', 'chuxe', 'laixe', 'giakm', 'giadb', 'giatb', 'note'] as $item) {
                $theForm->$item = $theLichxe->$item;
            }

            $theLichxeContent = [];
            $lines            = explode('|||', $theLichxe->content);
            foreach ($lines as $line) {
                $theLichxeContent[] = explode(';;;', $line);
            }

        } elseif ($action == 'print') {
            $totalTourPax = 0;
            foreach ($theTour['bookings'] as $booking) {
                $totalTourPax += $booking['pax'];
            }
            $theTour['pax'] = $totalTourPax;

            $docTitle = 'Lịch xe';
            $docNum   = 'AT-LX-' . $theTour['op_code'];

            $html = $this->renderPartial('tour_in-lx_ok_pdf', [
                'theTour'          => $theTour,
                'theTourOld'       => $theTourOld,
                'theLichxe'        => $theLichxe,
                'theLichxeContent' => $theLichxeContent,
                'action'           => $action,
                'docTitle'         => $docTitle,
                'docNum'           => $docNum,
            ]);
            $mpdf = new Mpdf;
            $mpdf->SetTitle($theTour['op_code'] . ' - ' . $docTitle);
            $mpdf->SetAuthor('Amica Travel'); // TODO my company's name
            $mpdf->SetSubject($docTitle . ' v.170114');
            // LOAD a stylesheet
            // $stylesheet = file_get_contents(Yii::getAlias('@app').'/mpdfstyleColliers.css');
            // $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);

            $fileName = $theTour['op_code'] . ' - ' . $docTitle . '.pdf';
            $mpdf->Output($fileName, 'I');

            exit;

            return $this->render('tour_in-lx_ok', [
                'theTour'          => $theTour,
                'theTourOld'       => $theTourOld,
                'theLichxe'        => $theLichxe,
                'theLichxeContent' => $theLichxeContent,
                'action'           => $action,
            ]);
        } else {
            throw new HttpException(401, 'Invalid action');
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate() & !empty($_POST['noidung'])) {
            if ($action == 'add') {
                // Add cpt and link to lichxe
                $theCpt              = new Cpt;
                $theCpt->created_at  = NOW;
                $theCpt->created_by  = USER_ID;
                $theCpt->updated_at  = NOW;
                $theCpt->updated_by  = USER_ID;
                $theCpt->tour_id     = $theTourOld['id'];
                $theCpt->dvtour_name = 'Chi phí xe MB (auto)';
                $theCpt->dvtour_day  = $theTour['day_from'];
                $theCpt->oppr        = $theForm->chuxe;
                $theCpt->payer       = 'Amica Hà Nội';
                $theCpt->qty         = 1;
                $theCpt->unit        = 'xe';
                $theCpt->price       = 0;
                $theCpt->unitc       = 'VND';
                $theCpt->save(false);
                $theLichxe->cpt_id = $theCpt['dvtour_id'];
            } else {
                // Edit
                $theLichxe->updated_dt = NOW;
                $theLichxe->updated_by = USER_ID;
            }

            if ((int) $theForm->cpkhac_sl != 0 && (int) $theForm->cpkhac_gia != 0) {
                $theLichxe->cpkhac = implode(';|', [$theForm->cpkhac_ten, $theForm->cpkhac_dvi, $theForm->cpkhac_sl, $theForm->cpkhac_gia]);
            }

            foreach (['name', 'days', 'pax', 'vp', 'dieuhanh', 'huongdan', 'loaixe', 'chuxe', 'laixe', 'giakm', 'giadb', 'giatb', 'note'] as $item) {
                $theLichxe->$item = $theForm->$item;
            }
            $lines = [];
            if (isset($_POST['tt']) && !empty($_POST['tt'])) {
                for ($i = 0; $i < count($_POST['tt']); $i++) {
                    $lines[] = implode(';;;', [$_POST['tt'][$i], $_POST['ngay'][$i], $_POST['noidung'][$i], $_POST['sl'][$i], $_POST['dvi'][$i], $_POST['gia'][$i]]);
                }
            }

            $theLichxe->content = implode('|||', $lines);
            $theLichxe->save(false);
            return $this->redirect('?action=print&lichxe=' . $theLichxe['id']);
        }

        return $this->render('tour_in-lx', [
            'theForm'          => $theForm,
            'theTour'          => $theTour,
            'theTourOld'       => $theTourOld,
            'theLichxes'       => $theLichxes,
            'theLichxe'        => $theLichxe ?  ? false,
            'theLichxeContent' => $theLichxeContent,
            'lichxe'           => $lichxe,
            'action'           => $action,
        ]);
    }

    // copy lich xe
    public function actionCopy_lx($tour_code = '', $lx_id = 0)
    {
        if (Yii::$app->request->isAjax) {
            if ($tour_code == '') {
                return json_encode(['err' => 'tour code is not empty']);
            }
            $theTour = Product::find()->where(['op_code' => $tour_code])->asArray()->one();
            if (!$theTour) {
                return json_encode(['err' => 'tour not found']);
            }
            $copy_lx = Lichxe::findOne($lx_id);
            if (!$copy_lx) {
                return json_encode(['err' => 'Lich xe not found']);
            }
            $sql       = "SELECT * FROM at_tour_user WHERE role = 'operator' AND tour_id =:tour_id";
            $operators = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();
            if (!$operators) {
                return json_encode(['err' => 'Operators empty']);
            }
            $operator_status = false;
            $name_op         = '';
            foreach ($operators as $operator) {
                if ($operator['user_id'] == USER_ID) {
                    $operator_status = true;
                    $name_op         = Yii::$app->user->identity->name . ' - ' . Yii::$app->user->identity->phone;
                }
            }
            if (!$operator_status) {
                return json_encode(['err' => 'Copy denied']);
            }
            $theLichxe = new Lichxe;

            $theLichxe->created_dt = NOW;
            $theLichxe->created_by = USER_ID;
            $theLichxe->updated_dt = NOW;
            $theLichxe->updated_by = USER_ID;

            $theLichxe->tour_id  = $theTour['id'];
            $theLichxe->cpt_id   = 0;
            $theLichxe->dieuhanh = $name_op;

            $theLichxe->name = $copy_lx['name'];
            $theLichxe->days = $copy_lx['days'];
            $theLichxe->vp   = $copy_lx['vp'];
            $theLichxe->pax  = $copy_lx['pax'];

            $theLichxe->loaixe  = $copy_lx['loaixe'];
            $theLichxe->chuxe   = $copy_lx['chuxe'];
            $theLichxe->laixe   = $copy_lx['laixe'];
            $theLichxe->giakm   = $copy_lx['giakm'];
            $theLichxe->giadb   = $copy_lx['giadb'];
            $theLichxe->giatb   = $copy_lx['giatb'];
            $theLichxe->note    = $copy_lx['note'];
            $theLichxe->content = $copy_lx['content'];
            if ($theLichxe->save(false)) {
                return $this->redirect('/tours/in-lx/' . $theTour['id']);
            } else {
                return json_encode(['err' => 'Copy error']);
            }
        }
    }

    // In bien don khach
    public function actionInBn($id = 0)
    {
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->with([
                'bookings'              => function ($q) {
                    return $q->select(['id', 'case_id', 'product_id']);
                },
                'bookings.people'       => function ($q) {
                    return $q->select(['id', 'fname', 'lname', 'gender']);
                },
                'bookings.case'         => function ($q) {
                    return $q->select(['id', 'name', 'company_id']);
                },
                'bookings.case.company' => function ($q) {
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->where(['id' => $id, 'op_status' => 'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $names    = '';
        $paxCount = 0;
        foreach ($theTour['bookings'] as $booking) {
            foreach ($booking['people'] as $pax) {
                $paxCount++;
                $names .= $pax['lname'] . ' ' . $pax['fname'] . ' ' . ($pax['gender'] == 'male' ? 'Mr' : 'Ms') . "\n";
            }
        }

        $theForm = new PrintWelcomeBannerForm;

        $theForm->template = 'new';
        $theForm->language = 'fr';
        $theForm->pax      = $paxCount . ' pax';
        $theForm->names    = $names;
        $theForm->logo     = 'amica';
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $theForm->template = 'old';
                $theForm->logo     = 'other';
            }
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (in_array($theForm->output, ['pdf-download', 'pdf-view'])) {
                $content = $this->renderPartial('tours_in-bn_ok', [
                    'theTour' => $theTour,
                    'theForm' => $theForm,
                ]);
                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    'mode'         => Pdf::MODE_UTF8,

                    'marginLeft'   => 8,
                    'marginRight'  => 8,
                    'marginTop'    => 8,
                    'marginBottom' => 8,

                    // A4 paper format
                    'format'       => Pdf::FORMAT_A4,
                    // portrait orientation
                    'orientation'  => Pdf::ORIENT_LANDSCAPE,
                    // stream to browser inline
                    'destination'  => $theForm->output == 'pdf-download' ? Pdf::DEST_DOWNLOAD : Pdf::DEST_BROWSER,
                    'filename'     => 'WELCOME-' . $theTour['op_code'] . '.pdf',
                    // your html content input
                    'content'      => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting
                    //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    // 'cssFile'=>'@app/views/invoice/bootstrap.min.css',
                    // any css to be embedded if required
                    'cssInline'    => '* {padding:0; border:0; margin:0;} table {width:100%;}',
                    // set mPDF properties on the fly
                    'options'      => [
                        'title'                    => 'WELCOME BANNER -' . $theTour['op_code'],
                        'allow_charset_conversion' => false,
                        'autoScriptToLang'         => true,
                        'autoLangToFont'           => true,
                        'autoVietnamese'           => true,
                    ],
                    // call mPDF methods on the fly
                    'methods'      => [
                        //'SetHeader'=>['INVOICE'],
                        //'SetFooter'=>['{PAGENO}'],
                    ],
                ]);
                // return the pdf output as per the destination setting
                return $pdf->render();
            }
            return $this->renderPartial('tours_in-bn_ok', [
                'theTour' => $theTour,
                'theForm' => $theForm,
            ]);
        }

        return $this->render('tours_in-bn', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            'theForm'    => $theForm,
        ]);
    }

    // Tour and tourguide points
    public function actionRatings($id = 0)
    {
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->where(['id' => $id, 'op_status' => 'op', 'op_finish' => ''])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // CSKH only
        if (!in_array(USER_ID, [1, 1351, 7756, 9881, 30554, 33415, 29296, 39063])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theForm                = new TourRatingsForm;
        $theForm['tour_points'] = $theTourOld['pax_ratings'];

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            Yii::$app->db->createCommand()->update('at_tours', ['pax_ratings' => $theForm['tour_points']], ['id' => $theTourOld['id']])->execute();
            return $this->redirect('@web/tours/r/' . $theTourOld['id']);
        }

        return $this->render('tour_ratings', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            'theForm'    => $theForm,
        ]);
    }

    public function actionUploadfile() //ajax

    {
        if (Yii::$app->request->isAjax) {
            $data = array();
            if (isset($_FILES)) {
                $error     = false;
                $files     = array();
                $uploaddir = '../uploads/';
                foreach ($_FILES as $file) {
                    if (file_exists($uploaddir . basename($file['name']))) {
                        $files[] = $uploaddir . $file['name'];
                    } else {
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777);
                        }
                        if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
                            $files[] = $uploaddir . $file['name'];
                        } else {
                            $error = true;
                        }
                    }
                }
                $data = ($error) ? ['error' => 'There was an error uploading your files'] : ['files' => $files];
            } else {
                $data = ['error' => 'files is empty!'];
            }
            echo json_encode($data);
        }
    }
    public function reArrayFiles(&$file_post)
    {

        $file_ary   = [];
        $file_count = count($file_post['name']);
        $file_keys  = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    // Feedback on anything
    // action = add | update
    public function actionFeedback($id = 0)
    {
        $versions['20072018'] = [
            'questions' => [
                'q1'  => [
                    'title'         => 'Par quel intermédiaire, avez-vous connu Amica Travel ?',
                    'options'       => ['Bouche à oreille', 'Ancien voyageur Amica', 'Médias (guides de voyage, presse, etc.)', 'Événement Amica Travel ', 'Internet', 'Autre'],
                    'options_value' => [''],
                    'note_q'        => '',
                ],

                'q2'  => [
                    'title'         => "",
                    'options'       => [
                        'Votre ressenti général de votre voyage',
                        "Notre disponibilité (tél, courriel, etc.)",
                        "Compétences de votre conseiller(e) (compréhension, adaptation du programme)",
                        "Informations délivrées pour le voyage (devis, programme, informations pratiques, etc.) :",
                        "Rapport Qualité/Prix",
                        "Suivi et résolution des problèmes pendant le voyage",
                        "Personnalisation et originalité de nos prestations",
                        "Qualité des prestations::Itinéraire",
                        "Qualité des prestations::Hébergements",
                        "Qualité des prestations::Contact avec l’habitant",
                        "Qualité des prestations::Véhicule",
                        "Qualité des prestations::Bateau",
                        "Qualité des prestations::Autre moyen de transports",
                        "Ce séjour était-il adapté à votre degré d'immersion que vous avez souhaité (participation aux acitivités, séjours et contacts chez l'habitant, etc.)",
                    ],

                    'options_value' => ['Très insatisfait', 'Peu satisfait', 'Moyen', 'Satisfait', 'Très satisfait'],
                    'note_q'        => '',
                ],
                'q3'  => [
                    'title'         => "guide",
                    'options'       => [
                        "Niveau de français",
                        "Connaissances",
                        "Capacité d’organisation",
                        "Serviabilité - Disponibilité - Aimabilité",
                        "Capacité d’assurer le contact du voyageur avec les habitants et la vie locale",
                    ],
                    'options_value' => ['Très insatisfait', 'Peu satisfait', 'Moyen', 'Satisfait', 'Très satisfait'],
                    'note_q'        => '',
                ],
                'q4'  => [
                    'title'         => "chauffeur",
                    'options'       => [
                        "Qualité de la conduite",
                        "Serviabilité",
                        "Concentration",
                        "Relationnel (avec le guide et les voyageurs)",
                        "Propreté du véhicule",
                    ],
                    'options_value' => ['Très insatisfait', 'Peu satisfait', 'Moyen', 'Satisfait', 'Très satisfait'],
                    'note_q'        => '',
                ],
                'q5'  => [
                    'title'         => "Vos autres remarques et suggestions sont la bienvenue, dans un souci constant d'amélioration de nos services ?",
                    'options'       => [
                        "autre",
                    ],
                    'options_value' => [''],
                    'note_q'        => '',
                ],
                'q6'  => [
                    'title'         => "Durant votre voyage, avez-vous constaté des conditions de visites défavorables ?",
                    'options'       => [
                        "De sur-fréquentation touristique",
                        "D'utilisation des plastiques jetables",
                        "Sur la qualité des infrastructures chez l'habitant ",
                        "Sur le travail des mineurs",
                        "Sur les conditions de travail de votre guide et chauffeur",
                    ],
                    'options_value' => ['Oui', 'Non'],
                    'note_q'        => '',
                ],
                'q7'  => [
                    'title'         => "Quelle solution/action proposez-vous pour notre Fondation Amica ?",
                    'options'       => ['autre'],
                    'options_value' => [''],
                    'note_q'        => '',
                ],
                'q8'  => [
                    'title'         => "Choisiriez-vous Amica Travel pour un prochain voyage ?",
                    'options'       => [
                        "Choisiriez-vous Amica Travel pour un prochain voyage?",
                    ],
                    'options_value' => ['Oui', 'Non', 'Je ne sais pas'],
                    'note_q'        => '',
                ],
                'q9'  => [
                    'title'         => "Si oui, quels sont vos prochains voyages envisagés en Asie du Sud-Est ?",
                    'options'       => [
                        "Vietnam",
                        "Laos",
                        "Cambodge",
                        "Birmanie",
                        "autre",
                    ],
                    'options_value' => [''],
                    'note_q'        => '',
                ],
                'q10' => [
                    'title'         => "Recommanderiez-vous Amica Travel à vos amis ?",
                    'options'       => [
                        "Recommanderiez-vous Amica Travel à vos amis ?",
                    ],
                    'options_value' => ['Oui', 'Non'],
                    'note_q'        => '',
                ],
                'q11' => [
                    'title'         => 'Pouvez-vous nous citer des valeurs que vous associez à Amica Travel ?',
                    'options'       => ['Empathie', 'Compréhension', 'Passion', 'Partage', 'Créativité', 'Responsable', 'Autre'],
                    'options_value' => [''],
                    'note_q'        => '',
                ],

            ],
        ];
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->with([
                'bookings',
                'guides'         => function ($q) {
                    $q->orderBy('use_from_dt');
                },
                'guides.guide'   => function ($q) {
                    return $q->select(['id', 'gender', 'lname', 'image']);
                },
                'guides'         => function ($q) {
                    $q->orderBy('use_from_dt');
                },
                'drivers.driver' => function ($q) {
                    return $q->select(['id', 'gender', 'lname', 'image']);
                },
                'pax',
            ])
            ->where(['id' => $id, 'op_status' => 'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }
        // $theTour['guides'] = null;
        // $theTour['drivers'] = null;

        $booking = $theTour['bookings'][0];
        $cpLink  = Cplink::find()->where(['booking_id' => $booking['id']])->one();
        if (!$cpLink) {
            throw new HttpException(404, 'cpLink not found.');
        }
        if ($cpLink->fb_accessed_dt == '0000-00-00 00:00:00') {
            $cpLink->fb_accessed_dt = NOW;
            $cpLink->save(false);
        }
        $info    = '';
        $contact = '';
        $data    = '';

        if (isset($_POST['info'])) {
            $data_post              = $_POST;
            $questions_point['q2']  = $data_post['q2'];
            $questions_point['q3']  = isset($data_post['q3']) ? $data_post['q3'] : null;
            $questions_point['q4']  = isset($data_post['q4']) ? $data_post['q4'] : null;
            $questions_point['q8']  = $data_post['q8'];
            $questions_point['q10'] = $data_post['q10'];
            //files upload
            if (!empty($_FILES['inputUpload'])) {
                $files      = $this->reArrayFiles($_FILES['inputUpload']);
                $errors     = [];
                $link_files = [];

                $fileExtensions = ['jpeg', 'jpg', 'png'];
                $uploadDir      = "uploads/";

                foreach ($files as $file) {
                    $ext_arr = explode(".", $file['name']);
                    $extr    = end($ext_arr);
                    if (!in_array($extr, $fileExtensions)) {
                        $errors[] = [$file['name'] => 'this file # type of image'];
                        continue;
                    }
                    if ($file['size'] > 1000000) {
                        $errors[] = [$file['name'] => 'this file to large 1000000'];
                        continue;
                    }
                    $uniqer    = substr(md5(uniqid(rand(), 1)), 0, 5);
                    $file_name = $uniqer . '_' . $file['name'];

                    $uploadPath = $uploadDir . $booking['id'] . '/' . $cpLink->customer_id . '/';
                    FileHelper::createDirectory($uploadPath);
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath . $file_name)) {
                        $errors[] = [$file['name'] => 'this file upload was error'];
                    } else {
                        $link_files[] = $uploadPath . $file_name;
                    }
                }
            }
            $poins = [
                'q2'  => [
                    'Votre ressenti général de votre voyage'                                                                                                               => 30,
                    "Notre disponibilité (tél, courriel, etc.)"                                                                                                            => 5,
                    "Compétences de votre conseiller(e) (compréhension, adaptation du programme)"                                                                          => 5,
                    "Informations délivrées pour le voyage (devis, programme, informations pratiques, etc.) :"                                                             => 3,
                    "Rapport Qualité/Prix"                                                                                                                                 => 5,
                    "Suivi et résolution des problèmes pendant le voyage"                                                                                                  => 6,
                    "Personnalisation et originalité de nos prestations"                                                                                                   => 5,
                    "Qualité des prestations::Itinéraire"                                                                                                                  => 2.5,
                    "Qualité des prestations::Hébergements"                                                                                                                => 2.5,
                    "Qualité des prestations::Contact avec l’habitant"                                                                                                     => 2.5,
                    "Qualité des prestations::Véhicule"                                                                                                                    => 2.5,
                    "Qualité des prestations::Bateau"                                                                                                                      => 1,
                    "Ce séjour était-il adapté à votre degré d'immersion que vous avez souhaité (participation aux acitivités, séjours et contacts chez l'habitant, etc.)" => 5,
                ],
                'q3'  => [
                    "Niveau de français"                                                            => 2,
                    "Connaissances"                                                                 => 2,
                    "Capacité d’organisation"                                                       => 2,
                    "Serviabilité - Disponibilité - Aimabilité"                                     => 2,
                    "Capacité d’assurer le contact du voyageur avec les habitants et la vie locale" => 2,

                ],
                'q4'  => [
                    "Qualité de la conduite"                       => 1,
                    "Serviabilité"                                 => 1,
                    "Concentration"                                => 1,
                    "Relationnel (avec le guide et les voyageurs)" => 1,
                    "Propreté du véhicule"                         => 1,

                ],
                'q8'  => 5,
                'q10' => 5,
            ];
            $table        = [];
            $cpLink_point = [];
            $arr_point    = [];
            foreach ($questions_point as $k_q => $questions) {
                if ($k_q == 'q2') {
                    $arr_ = [];
                    foreach ($questions as $q => $column_id) {
                        if ($column_id == '') {
                            $column_id = 5;
                        }
                        if (!isset($poins[$k_q][$q])) {
                            continue;
                        }
                        $arr_[] = ($column_id - 1) * $poins[$k_q][$q];
                    }
                    $table['q2'] = array_sum($arr_);
                }
                if ($k_q == 'q3' || $k_q == 'q4') {
                    if ($k_q == 'q3') {
                        $arr_points = [
                            'Niveau de français'                                                            => [0, 10, 30, 40, 50],
                            'Connaissances'                                                                 => [0, 15, 45, 60, 75],
                            'Capacité d’organisation'                                                       => [0, 15, 45, 60, 75],
                            'Serviabilité - Disponibilité - Aimabilité'                                     => [0, 20, 60, 80, 100],
                            'Capacité d’assurer le contact du voyageur avec les habitants et la vie locale' => [0, 20, 60, 80, 100],
                        ];
                    }
                    if ($k_q == 'q4') {
                        $arr_points = [
                            'Qualité de la conduite'                       => [0, 10, 30, 40, 50],
                            'Serviabilité'                                 => [0, 10, 30, 40, 50],
                            'Concentration'                                => [0, 10, 30, 40, 50],
                            'Relationnel (avec le guide et les voyageurs)' => [0, 10, 30, 40, 50],
                            'Propreté du véhicule'                         => [0, 10, 30, 40, 50],
                        ];
                    }
                    if (!$questions) {
                        $table[$k_q] = 20;
                        continue;
                    }

                    $arr_ = [];

                    $cpLink_ids = array_keys(current($questions));
                    $keys_q     = array_keys($questions);

                    foreach ($cpLink_ids as $id_l) {
                        foreach ($keys_q as $key_q) {
                            if ($questions[$key_q][$id_l] == '') {
                                $questions[$key_q][$id_l] = 5;
                            }
                            if (!isset($poins[$k_q][$key_q])) {
                                continue;
                            }
                            $arr_[$id_l][$key_q]      = ($questions[$key_q][$id_l] - 1) * $poins[$k_q][$key_q];
                            $arr_point[$id_l][$key_q] = $arr_points[$key_q][$questions[$key_q][$id_l] - 1];
                        }
                    }
                    $total[$k_q] = 0;
                    foreach ($arr_ as $cpLink_id => $ar_v) {
                        $cpLink_point[$k_q][$cpLink_id] = array_sum($ar_v);
                        $total[$k_q] += $cpLink_point[$k_q][$cpLink_id];
                    }
                    $table[$k_q] = ceil($total[$k_q] / count($arr_));
                }
                if (in_array($k_q, ['q8', 'q10'])) {
                    $column_id = 2;
                    if (isset($questions['Oui'])) {
                        $column_id = 4;
                    }
                    if (isset($questions['Non'])) {
                        $column_id = 0;
                    }
                    $table[$k_q] = $column_id * $poins[$k_q];
                }
            }
            if (isset($cpLink_point['q3'])) {
                foreach ($cpLink_point['q3'] as $user_id => $point) {
                    Yii::$app->db->createCommand()
                        ->update('at_tour_guides', [
                            // 'updated_by'=>USER_ID,
                            'points' => isset($arr_point[$user_id]) ? array_sum($arr_point[$user_id]) : 0,
                        ], ['tour_id' => $theTour['id'], 'guide_user_id' => $user_id])
                        ->execute();
                }
            }
            if (isset($cpLink_point['q4'])) {
                foreach ($cpLink_point['q4'] as $user_id => $point) {
                    Yii::$app->db->createCommand()
                        ->update('at_tour_drivers', [
                            // 'updated_by'=>USER_ID,
                            'points' => isset($arr_point[$user_id]) ? array_sum($arr_point[$user_id]) : 0,
                        ], ['tour_id' => $theTour['id'], 'driver_user_id' => $user_id])
                        ->execute();
                }
            }
            $data_post['link_files'] = isset($link_files) ? implode(', ', $link_files) : '';
            $cpLink->fb_data         = serialize($data_post);
            $cpLink->fb_submitted_dt = NOW;
            $cpLink->fb_point        = array_sum($table) / 100;
            $cpLink->save(false);
            // var_dump($table);die;
            return $this->redirect('view_feedback?id=' . $theTour['id'] . '&fb_id=' . $cpLink->id);
            // return $this->redirect('/newfb/merci');
        }

        return $this->renderPartial('tours_feedback', [
            // 'theFb'=>$theFb,
            'theTour'   => $theTour,
            'versions'  => $versions,
            'info'      => $info,
            'contact'   => $contact,
            'content_q' => $data,
        ]);
    }
    public function actionView_feedback($id = 0, $fb_id = 0)
    {
        $versions['20072018'] = [
            'questions' => [
                'q1'  => [
                    'title'         => 'Par quel intermédiaire, avez-vous connu Amica Travel ?',
                    'options'       => ['Bouche à oreille', 'Ancien voyageur Amica', 'Médias (guides de voyage, presse, etc.)', 'Événement Amica Travel ', 'Internet', 'Autre'],
                    'options_value' => [''],
                    'note_q'        => '',
                ],

                'q2'  => [
                    'title'         => "",
                    'options'       => [
                        'Votre ressenti général de votre voyage',
                        "Notre disponibilité (tél, courriel, etc.)",
                        "Compétences de votre conseiller(e) (compréhension, adaptation du programme)",
                        "Informations délivrées pour le voyage (devis, programme, informations pratiques, etc.) :",
                        "Rapport Qualité/Prix",
                        "Suivi et résolution des problèmes pendant le voyage",
                        "Personnalisation et originalité de nos prestations",
                        "Qualité des prestations::Itinéraire",
                        "Qualité des prestations::Hébergements",
                        "Qualité des prestations::Contact avec l’habitant",
                        "Qualité des prestations::Véhicule",
                        "Qualité des prestations::Bateau",
                        "Qualité des prestations::Autre moyen de transports",
                        "Ce séjour était-il adapté à votre degré d'immersion que vous avez souhaité (participation aux acitivités, séjours et contacts chez l'habitant, etc.)",
                    ],

                    'options_value' => ['Très insatisfait', 'Peu satisfait', 'Moyen', 'Satisfait', 'Très satisfait'],
                    'note_q'        => '',
                ],
                'q3'  => [
                    'title'         => "guide",
                    'options'       => [
                        "Niveau de français",
                        "Connaissances",
                        "Capacité d’organisation",
                        "Serviabilité - Disponibilité - Aimabilité",
                        "Capacité d’assurer le contact du voyageur avec les habitants et la vie locale",
                    ],
                    'options_value' => ['Très insatisfait', 'Peu satisfait', 'Moyen', 'Satisfait', 'Très satisfait'],
                    'note_q'        => '',
                ],
                'q4'  => [
                    'title'         => "chauffeur",
                    'options'       => [
                        "Qualité de la conduite",
                        "Serviabilité",
                        "Concentration",
                        "Relationnel (avec le guide et les voyageurs)",
                        "Propreté du véhicule",
                    ],
                    'options_value' => ['Très insatisfait', 'Peu satisfait', 'Moyen', 'Satisfait', 'Très satisfait'],
                    'note_q'        => '',
                ],
                'q5'  => [
                    'title'         => "Vos autres remarques et suggestions sont la bienvenue, dans un souci constant d'amélioration de nos services ?",
                    'options'       => [
                        "autre",
                    ],
                    'options_value' => [''],
                    'note_q'        => '',
                ],
                'q6'  => [
                    'title'         => "Durant votre voyage, avez-vous constaté des conditions de visites défavorables ?",
                    'options'       => [
                        "De sur-fréquentation touristique",
                        "D'utilisation des plastiques jetables",
                        "Sur la qualité des infrastructures chez l'habitant ",
                        "Sur le travail des mineurs",
                        "Sur les conditions de travail de votre guide et chauffeur",
                    ],
                    'options_value' => ['Oui', 'Non'],
                    'note_q'        => '',
                ],
                'q7'  => [
                    'title'         => "Quelle solution/action proposez-vous pour notre Fondation Amica ?",
                    'options'       => ['autre'],
                    'options_value' => [''],
                    'note_q'        => '',
                ],
                'q8'  => [
                    'title'         => "Choisiriez-vous Amica Travel pour un prochain voyage ?",
                    'options'       => [
                        "Choisiriez-vous Amica Travel pour un prochain voyage?",
                    ],
                    'options_value' => ['Oui', 'Non', 'Je ne sais pas'],
                    'note_q'        => '',
                ],
                'q9'  => [
                    'title'         => "Si oui, quels sont vos prochains voyages envisagés en Asie du Sud-Est ?",
                    'options'       => [
                        "Vietnam",
                        "Laos",
                        "Cambodge",
                        "Birmanie",
                        "autre",
                    ],
                    'options_value' => [''],
                    'note_q'        => '',
                ],
                'q10' => [
                    'title'         => "Recommanderiez-vous Amica Travel à vos amis ?",
                    'options'       => [
                        "Recommanderiez-vous Amica Travel à vos amis ?",
                    ],
                    'options_value' => ['Oui', 'Non'],
                    'note_q'        => '',
                ],
                'q11' => [
                    'title'         => 'Pouvez-vous nous citer des valeurs que vous associez à Amica Travel ?',
                    'options'       => ['Empathie', 'Compréhension', 'Passion', 'Partage', 'Créativité', 'Responsable', 'Autre'],
                    'options_value' => [''],
                    'note_q'        => '',
                ],

            ],
        ];
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->with([
                'guides'         => function ($q) {
                    $q->orderBy('use_from_dt');
                },
                'guides.guide'   => function ($q) {
                    return $q->select(['id', 'gender', 'lname', 'image']);
                },
                'guides'         => function ($q) {
                    $q->orderBy('use_from_dt');
                },
                'drivers.driver' => function ($q) {
                    return $q->select(['id', 'gender', 'lname', 'image']);
                },
                'pax',
            ])
            ->where(['id' => $id, 'op_status' => 'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }
        $cpLink = Cplink::find()->where(['id' => $fb_id])->one();
        if (!$cpLink) {
            throw new HttpException(404, 'cpLink not found.');
        }

        $content = unserialize($cpLink->fb_data);
        // var_dump($content);die;
        return $this->render('view_fb', [
            'content_q' => $content,
            'theTour'   => $theTour,
            'versions'  => $versions,
        ]);
    }
    public function actionFb_total($orderby = 'startdate', $month = '')
    {
        $versions['20072017'] = [

            'questions' => [
                'q1' => [
                    'title'         => 'Globalement, comment évaluez-vous les prestations suivantes',
                    'options'       => ['Hôtels', 'Chez l’habitant', 'Repas', 'Bateaux', 'Train', 'Véhicule'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant', 'Non Utilisé'],
                    'note_q'        => '',
                    'v_op_v'        => [
                        [5, 10, 20, 30, 40, 40],
                        [5, 10, 20, 30, 40, 40],
                        [5, 10, 15, 20, 25, 25],
                        [5, 10, 15, 20, 25, 25],
                        [5, 10, 15, 20, 25, 25],
                        [5, 10, 15, 20, 25, 25],
                    ],
                ],
                'q2' => [
                    'title'         => "Quel est votre niveau de satisfaction en rapport avec les compétences du guide",
                    'options'       => ['Niveau de français', 'Connaissances', 'Capacité d’organisation', 'Serviabilité - Disponibilité - Aimabilité', 'Capacité d’assurer le contact du voyageur avec les habitants et la vie locale'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                    'note_q'        => "* Si vous n'avex pas d'activités chez l'habitant durant votre séjour, veuillez ne pas répondre à cette question* <br>
        Est-ce que vous des commentaires particuliers concernant les prestations du guide",
                    'v_op_v'        => [
                        [10, 20, 30, 40, 50],
                        [10, 20, 40, 60, 80],
                        [20, 40, 60, 80, 100],
                        [20, 40, 60, 80, 100],
                        [10, 20, 30, 45, 60],
                    ],
                ],
                'q3' => [
                    'title'         => "Quel est votre niveau de satisfaction en rapport avec les prestations du chauffeur",
                    'options'       => ['Professionnalisme dans la conduite', 'Serviabilité', 'Concentration', 'Relationnel (avec le guide et les voyageurs)', 'Propreté du véhicule'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                    'note_q'        => "Est-ce que vous avez des commentaires particuliers concernant les prestations du chauffeur",
                    'v_op_v'        => [
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                    ],
                ],
                'q4' => [
                    'title'         => 'Comment évaluez-vous les prestations et les services',
                    'options'       => ['Rapport Qualité/Prix', 'Qualité de nos services', 'Originalité de nos services', 'Suivi général lors du voyage', 'Niveau de francais de vos conseillères (Conseiller(ère) de vente et Conseiller(ère) client)', 'Résolution des problèmes', 'Personnalisation des services'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                    'note_q'        => "Est-ce que vous avez des commentaires particuliers concernant nos prestations et services",
                    'v_op_v'        => [
                        [10, 20, 40, 60, 80],
                        [10, 20, 40, 60, 80],
                        [20, 40, 60, 80, 100],
                        [10, 20, 30, 40, 50],
                        [10, 20, 40, 60, 80],
                        [15, 30, 45, 60, 75],
                        [10, 20, 30, 40, 50],
                    ],
                ],
            ],
        ];
        $version = $versions['20072017'];
        $query   = Product::find();
        $query->select(['at_ct.id', 'op_code', 'op_name', 'day_from', 'ed' => new \yii\db\Expression('(SELECT DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->innerJoinWith('fb');

        $theTours = $query->asArray()->all();
        if (!$theTours) {
            throw new HttpException(404, 'Tours not found');
        }
        $result = [];
        foreach ($theTours as $tour) {
            $y       = date('Y', strtotime($tour['ed']));
            $m       = intVal(date('m', strtotime($tour['ed'])));
            $db_data = unserialize($tour['fb']['content']);
            foreach ($db_data['questions'] as $q => $q_content) {
                if ($q !== 'q2' && $q !== 'q3') {
                    $options = $q_content['diem'];
                    foreach ($options as $op => $op_score) {
                        $result[$y][$m][$q][$op][] = $op_score;
                    }
                } else {
                    $tables = [];
                    foreach ($q_content as $t => $t_content) {
                        if (!isset($t_content['diem'])) {
                            continue;
                        }

                        $options = $t_content['diem'];
                        foreach ($options as $op => $op_score) {
                            $tables[$op] = $op_score;
                        }
                    }
                    if (empty($tables)) {
                        var_dump($total_table);die;
                    }
                    $result[$y][$m][$q][] = $tables;
                }
            }
        }
        $y_min = min(array_keys($result));
        $y_max = min(array_keys($result));
        // var_dump($result[2017][7]['q1']);die;
        $arr_count = [];
        for ($yr = $y_min; $yr <= $y_max; $yr++) {
            for ($mo = 1; $mo <= 12; $mo++) {
                if (isset($result[$yr][$mo])) {
                    foreach ($result[$yr][$mo] as $q => $q_content) {
                        if ($q != 'q2' && $q != 'q3') {
                            foreach ($q_content as $op => $op_v) {
                                $arr_count[$yr][$mo][$q][$op] = array_sum($op_v) / count($op_v);
                            }
                        } else {
                            $total_table = [];
                            foreach ($q_content as $table) {
                                foreach ($table as $op => $v) {
                                    $total_table[$op][] = $v;
                                }
                            }
                            foreach ($total_table as $k => $arr_v) {
                                // if (count($arr_v) > 1 && $mo == 7) {
                                //     var_dump($q_content);
                                //     var_dump(array_sum($arr_v) / count($arr_v));die;
                                // }
                                $arr_count[$yr][$mo][$q][$k] = array_sum($arr_v) / count($arr_v);
                            }
                        }
                    }
                }
            }
        }
        // var_dump($arr_count[2017][7]['q1']);die;
        return $this->render('fb_total', [
            'result'  => $arr_count,
            'minYear' => $y_min,
            'maxYear' => $y_max,
            'version' => $version,
        ]);
    }
    public function actionFb_index($orderby = 'startdate', $month = '')
    {

        if ($month == 'next30days') {
            $dateRange = [date('Y-m-d'), date('Y-m-d', strtotime('+30 days'))];
        } elseif ($month == 'last30days') {
            $dateRange = [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')];
        } elseif (strlen($month) == 10) {
            $dateRange = [$month, date('Y-m-d', strtotime('+6 days ' . $month))];
        } elseif (strlen($month) == 7) {
            $dateRange = [$month . '-01', date('Y-m-t', strtotime($month . '-01'))];
        } else {
            $month     = date('Y-m');
            $dateRange = [date('Y-m-01'), date('Y-m-t')];
        }
        $query = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from', 'ed' => new \yii\db\Expression('(SELECT DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->with(['fb'])
            ->where(['op_status' => 'op']);
        if ($orderby == 'enddate') {
            $query->andHaving('ed BETWEEN :date1 AND :date2', [':date1' => $dateRange[0], ':date2' => $dateRange[1]]);
        } else {
            $query->andWhere('day_from BETWEEN :date1 AND :date2', [':date1' => $dateRange[0], ':date2' => $dateRange[1]]);
        }
        $theTours = $query->asArray()->all();

        if (!$theTours) {
            throw new HttpException(404, 'Tour not found.');
        }
        $sql       = 'SELECT SUBSTRING(day_from,1,7) AS ym, COUNT(*) AS total FROM at_ct WHERE op_status="op" GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();
        // foreach ($theTours as $tour) {
        //     if (!empty($tour['fb']) || $tour['fb']) {
        //         var_dump($tour);die;
        //     }
        // }

        return $this->render('fb_list', [
            'theTours'  => $theTours,
            'orderby'   => $orderby,
            'month'     => $month,
            'monthList' => $monthList,
        ]);
    }

    // In form feedback
    public function actionInFb($id = 0, $tcg = 'no')
    {
        $versions['20072017'] = [

            'questions' => [
                'q1' => [
                    'title'         => 'Globalement, comment évaluez-vous les prestations suivantes',
                    'options'       => ['Hôtels', 'Chez l’habitant', 'Repas', 'Bateaux', 'Train', 'Véhicule'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant', 'Non Utilisé'],
                    'note_q'        => '',
                    'v_op_v'        => [
                        [5, 10, 20, 30, 40, 40],
                        [5, 10, 20, 30, 40, 40],
                        [5, 10, 15, 20, 25, 25],
                        [5, 10, 15, 20, 25, 25],
                        [5, 10, 15, 20, 25, 25],
                        [5, 10, 15, 20, 25, 25],
                    ],
                ],
                'q2' => [
                    'title'         => "Quel est votre niveau de satisfaction en rapport avec les compétences du guide",
                    'options'       => ['Niveau de français', 'Connaissances', 'Capacité d’organisation', 'Serviabilité - Disponibilité - Aimabilité', 'Capacité d’assurer le contact du voyageur avec les habitants et la vie locale'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                    'note_q'        => "* Si vous n'avex pas d'activités chez l'habitant durant votre séjour, veuillez ne pas répondre à cette question* <br>
        Est-ce que vous des commentaires particuliers concernant les prestations du guide",
                    'v_op_v'        => [
                        [10, 20, 30, 40, 50],
                        [10, 20, 40, 60, 80],
                        [20, 40, 60, 80, 100],
                        [20, 40, 60, 80, 100],
                        [10, 20, 30, 45, 60],
                    ],
                ],
                'q3' => [
                    'title'         => "Quel est votre niveau de satisfaction en rapport avec les prestations du chauffeur",
                    'options'       => ['Professionnalisme dans la conduite', 'Serviabilité', 'Concentration', 'Relationnel (avec le guide et les voyageurs)', 'Propreté du véhicule'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                    'note_q'        => "Est-ce que vous avez des commentaires particuliers concernant les prestations du chauffeur",
                    'v_op_v'        => [
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                        [5, 10, 15, 20, 30],
                    ],
                ],
                'q4' => [
                    'title'         => 'Comment évaluez-vous les prestations et les services',
                    'options'       => ['Rapport Qualité/Prix', 'Qualité de nos services', 'Originalité de nos services', 'Suivi général lors du voyage', 'Niveau de francais de vos conseillères (Conseiller(ère) de vente et Conseiller(ère) client)', 'Résolution des problèmes', 'Personnalisation des services'],
                    'options_value' => ['Très insatisfaisant', 'Insatisfaisant', 'Acceptable', 'Satisfaisant', 'Très satisfaisant'],
                    'note_q'        => "Est-ce que vous avez des commentaires particuliers concernant nos prestations et services",
                    'v_op_v'        => [
                        [10, 20, 40, 60, 80],
                        [10, 20, 40, 60, 80],
                        [20, 40, 60, 80, 100],
                        [10, 20, 30, 40, 50],
                        [10, 20, 40, 60, 80],
                        [15, 30, 45, 60, 75],
                        [10, 20, 30, 40, 50],
                    ],
                ],
            ],
        ];
        Yii::$app->params['fb_versions'] = $versions;
        $theTour                         = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from', 'day_ids'])
            ->where(['id' => $id, 'op_status' => 'op', 'op_finish' => ''])
            ->with([
                'days'                  => function ($q) {
                    return $q->select(['id', 'name', 'rid']);
                },
                'bookings'              => function ($q) {
                    return $q->select(['id', 'product_id', 'status', 'case_id', 'product_id']);
                },
                'bookings.case'         => function ($q) {
                    return $q->select(['id', 'name', 'company_id', 'is_b2b']);
                },
                'bookings.case.company' => function ($q) {
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theCompany = false;
        if (isset($theTour['bookings'][0]['case']['company'])) {
            $theCompany          = $theTour['bookings'][0]['case']['company'];
            $theCompany['image'] = $theCompany['image'];
        }

        $theForm              = new PrintFeedbackForm;
        $theForm->language    = 'fr';
        $theForm->logoName    = 'us';
        $theForm->guideNames  = 1;
        $theForm->driverNames = 1;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $printLogo = Yii::$app->params['print_logo'];
            $printName = 'Amica Travel';
            $questions = $versions['20072017']['questions'];

            if ($tcg == 'yes') {
                $printLogo           = DIR . 'assets/img/logo_tcg_263x102.jpg';
                $printName           = 'Tam Coc Garden';
                $theForm['logoName'] = 'them';
            }

            if ($theCompany && in_array($theForm['logoName'], ['them', 'none'])) {
                $printLogo = $theCompany['image'];
                $printName = $theCompany['name'];
            }

            if ($theForm['logoName'] == 'voyages-villegia') {
                $theCompany['name']  = 'Voyages Villegia';
                $theCompany['image'] = Yii::getAlias('@www') . '/upload/companies/2015-05/294/voyagevillegia_horiz.png';

                $printName = 'Voyages Villegia';
                $printLogo = Yii::getAlias('@www') . '/upload/companies/2015-05/294/voyagevillegia_horiz.png';
            }

            if ($theForm['logoName'] == 'si') {
                $theCompany['name']  = 'Secret Indochina';
                $theCompany['image'] = Yii::getAlias('@www') . '/assets/img/logo_si_160922_1248x664.jpg';

                $printName = 'Secret Indochina';
                $printLogo = Yii::getAlias('@www') . '/assets/img/logo_si_160922_1248x664.jpg';
            }

            $docTitle = 'Feedback tour';
            $docNum   = 'AT-LPCNVHD-' . $theTour['op_code'];
            $html     = $this->renderPartial('tours_in-fb_pdf', [
                'theTour'    => $theTour,
                'docTitle'   => $docTitle,
                'docNum'     => $docNum,
                'theTourOld' => $theTourOld,
                'theForm'    => $theForm,
                'printLogo'  => $printLogo,
                'printName'  => $printName,
                'theCompany' => $theCompany,
                'questions'  => $questions,
            ]);
            $mpdf = new \mPDF();
            $mpdf->SetTitle($docTitle . ' - ' . $theTour['op_code']);
            $mpdf->SetAuthor('Amica Travel');
            $mpdf->SetSubject($docTitle . ' v.170624');
            if (1 == USER_ID) {
                // $mpdf->SetWatermarkText("SAMPLE");
                // $mpdf->showWatermarkText = true;
                // $mpdf->watermark_font = 'DejaVuSansCondensed';
                // $mpdf->watermarkTextAlpha = 0.1;
            }
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);

            $fileName = $docTitle . ' - ' . $theTour['op_code'] . '.pdf';
            $mpdf->Output($fileName, 'I');

            exit;
        }

        return $this->render('tours_in-fb', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            'theForm'    => $theForm,
            'theCompany' => $theCompany,
        ]);
    }

    // In bang chi phi tour
    public function actionInCf($id = 0)
    {
        $theTour = Tour::findOne($id);
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }
        $theProduct = Product::find()
            ->where(['id' => $theTour['ct_id']])
            ->with([
                'days',
                'bookings',
                'bookings.createdBy',
                'updatedBy',
            ])
            ->asArray()
            ->one();
        if (!$theProduct) {
            throw new HttpException(404, 'Tour not found');
        }

        $sql       = 'SELECT tu.*, CONCAT(u.fname, " ", u.lname) AS name, u.phone FROM persons u, at_tour_user tu WHERE tu.role IN ("operator", "cservice") AND tu.user_id=u.id AND tu.tour_id=:id ORDER BY u.lname';
        $thePeople = Yii::$app->db->createCommand($sql, [':id' => $id])->queryAll();

        $theCptx = Cpt::find()
            ->where(['tour_id' => $id])
            ->with([
                'company' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'   => function ($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()
            ->all();

        return $this->render('tours_in-cp', [
            'theTour'    => $theTour,
            'theProduct' => $theProduct,
            'thePeople'  => $thePeople,
            'theCptx'    => $theCptx,
        ]);
    }

    // In bang chi phi cho tour guide
    public function actionInHf($id = 0)
    {
        $theTour = Product::find()
            ->with([
                'days',
                'updatedBy',
                'bookings',
                'bookings.createdBy',
            ])
            ->where(['id' => $id, 'op_status' => 'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Tour guide list
        $sql           = 'select guide_user_id, guide_name from at_tour_guides where tour_id=:tour_id AND parent_id=0 limit 100';
        $tourguideList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Tour driver list
        $sql        = 'select driver_user_id, driver_name from at_tour_drivers where tour_id=:tour_id AND parent_id=0 limit 100';
        $driverList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Payer list
        $sql       = 'select payer from cpt where tour_id=:tour_id group by payer order by payer limit 100';
        $payerList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

        $theForm           = new TourInHdForm;
        $theForm->days     = '1-' . $theTour['day_count'];
        $theForm->payer    = 'Hướng dẫn MB 1';
        $theForm->language = Yii::$app->language;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (empty($theForm->options)) {
                $theForm->options = [];
            }
            $sql = 'SELECT *,
            IF (via_company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=via_company_id LIMIT 1)) AS via_company_name,
            IF (by_company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=by_company_id LIMIT 1)) AS by_company_name,
            IF (venue_id=0, "", (SELECT name FROM venues v WHERE v.id=venue_id LIMIT 1)) AS venue_name,
            1
            FROM cpt WHERE (latest=dvtour_id OR latest=0) AND tour_id=:tour_id ORDER BY dvtour_day, dvtour_name, updated_at';
            $theCptx = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

            // Get exchange rates
            $xRates = [
                'USD' => 22295,
                'VND' => 1,
            ];
            $sql      = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="' . $theTour['day_from'] . '" ORDER BY rate_dt DESC LIMIT 1';
            $theXRate = Yii::$app->db->createCommand($sql)->queryScalar();
            if ($theXRate) {
                $xRates['USD'] = $theXRate;
            }

            return $this->render('tours_in-hd_ok', [
                'theTour'       => $theTour,
                'theTourOld'    => $theTourOld,
                'theForm'       => $theForm,
                'tourguideList' => $tourguideList,
                'driverList'    => $driverList,
                'payerList'     => $payerList,
                'theCptx'       => $theCptx,
                'xRates'        => $xRates,
            ]);
        }

        return $this->render('tours_in-hd', [
            'theTour'       => $theTour,
            'theTourOld'    => $theTourOld,
            'theForm'       => $theForm,
            'tourguideList' => $tourguideList,
            'driverList'    => $driverList,
            'payerList'     => $payerList,
        ]);
    }

    // In bang chi phi cho tour guide
    public function actionInHd($id = 0)
    {
        $theTour = Product::find()
            ->with([
                'days',
                'updatedBy',
                'bookings',
                'bookings.createdBy',
            ])
            ->where(['id' => $id, 'op_status' => 'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Tour guide list
        $sql           = 'select guide_user_id, guide_name from at_tour_guides where tour_id=:tour_id AND parent_id=0 limit 100';
        $tourguideList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Tour driver list
        $sql        = 'select driver_user_id, driver_name from at_tour_drivers where tour_id=:tour_id AND parent_id=0 limit 100';
        $driverList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Payer list
        $sql       = 'select payer from cpt where tour_id=:tour_id group by payer order by payer limit 100';
        $payerList = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

        $theForm           = new TourInHdForm;
        $theForm->days     = '1-' . $theTour['day_count'];
        $theForm->payer    = 'Hướng dẫn MB 1';
        $theForm->language = Yii::$app->language;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (empty($theForm->options)) {
                $theForm->options = [];
            }
            $sql = 'SELECT *,
            IF (via_company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=via_company_id LIMIT 1)) AS via_company_name,
            IF (by_company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=by_company_id LIMIT 1)) AS by_company_name,
            IF (venue_id=0, "", (SELECT name FROM venues v WHERE v.id=venue_id LIMIT 1)) AS venue_name,
            1
            FROM cpt WHERE (latest=dvtour_id OR latest=0) AND tour_id=:tour_id ORDER BY dvtour_day, dvtour_name, updated_at';
            $theCptx = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

            // Get exchange rates
            $xRates = [
                'USD' => 22295,
                'VND' => 1,
            ];
            $sql      = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="' . $theTour['day_from'] . '" ORDER BY rate_dt DESC LIMIT 1';
            $theXRate = Yii::$app->db->createCommand($sql)->queryScalar();
            if ($theXRate) {
                $xRates['USD'] = $theXRate;
            }

            $html = $this->renderPartial('tours_in-hd_ok_pdf', [
                'theTour'       => $theTour,
                'theTourOld'    => $theTourOld,
                'theForm'       => $theForm,
                'tourguideList' => $tourguideList,
                'driverList'    => $driverList,
                'payerList'     => $payerList,
                'theCptx'       => $theCptx,
                'xRates'        => $xRates,
            ]);
            $mpdf = new \mPDF();
            $mpdf->SetTitle('Chi phí tour - ' . $theTour['op_code']);
            $mpdf->SetAuthor('Amica Travel'); // TODO my company's name
            $mpdf->SetSubject('Chi phí tour v.170120');
            // LOAD a stylesheet
            // $stylesheet = file_get_contents(Yii::getAlias('@app').'/mpdfstyleColliers.css');
            // $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
            if (1 == USER_ID) {
                $mpdf->SetWatermarkText("SAMPLE");
                $mpdf->showWatermarkText = true;
            }
            $mpdf->watermark_font     = 'DejaVuSansCondensed';
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');
            // $mpdf->SetWatermarkImage (Yii::getAlias('@app').'/cvf-footer.png', 0.8, [30, 30], [180, 270]);
            // $mpdf->showWatermarkImage = true;
            $mpdf->WriteHTML($html);

            $fileName = 'Chi phí tour - ' . $theTour['op_code'] . '.pdf';
            // $mpdf->Output(Yii::getAlias('@app').'/storage/'.ACCOUNT_ID.'/val-pdf/'.substr($theVal->created_dt, 0, 7).'/'.$theVal->id.'/'.$fileName, 'F');
            $mpdf->Output($fileName, 'I');

            exit;

            return $this->render('tours_in-hd_ok', [
                'theTour'       => $theTour,
                'theTourOld'    => $theTourOld,
                'theForm'       => $theForm,
                'tourguideList' => $tourguideList,
                'driverList'    => $driverList,
                'payerList'     => $payerList,
                'theCptx'       => $theCptx,
                'xRates'        => $xRates,
            ]);
        }

        return $this->render('tours_in-hd', [
            'theTour'       => $theTour,
            'theTourOld'    => $theTourOld,
            'theForm'       => $theForm,
            'tourguideList' => $tourguideList,
            'driverList'    => $driverList,
            'payerList'     => $payerList,
        ]);
    }

    public function actionNhadan()
    {
        $venueList = [
            2284 => 'Bao Tuan homestay, Hanoi',
            1563 => 'Boping, Kompong Thom, ghép',
            1197 => 'Cầu, Tùng Bá, không ghép',
            1672 => 'Chiến, Bảo Lạc, không ghép',
            2193 => 'Chớ, Mù Căng Chải, ghép max 2 đoàn/10p',
            942  => 'Cư, Nộn Khê, không ghép',
            2168 => 'Đảo Cò, Hải Dương, ghép',
            459  => 'Hải, Bảo Lạc, không ghép',
            1779 => 'Hùng, Ba Bể, không ghép',
            616  => 'Ích, Nộn Khê, không ghép',
            2063 => 'Kính, Hà Giang, ghép max 12p',
            1191 => 'Liễu, Lũng Lai, không ghép',
            1604 => 'Loma, Phong Sali, Laos, 12',
            1605 => 'Lungton, Phong Sali, Laos, 6+4+4',
            1577 => 'Ngoan, Nậm Ngùa, không ghép',
            807  => 'Nguyên, Huế, ghép',
            1603 => 'Opa, Phong Sali, Laos, 7+4+4',
            455  => 'Pà Chi, Bắc Hà, ghép',
            2074 => 'Pheng, Phong Sali, Laos, max 10, không ghép',
            1192 => 'Phin, Vai Thai / Sạc Xậy, không ghép',
            1852 => 'Phong, Bắc Hà, ghép, max 13',
            1583 => 'Phương, Bảo Lạc, không ghép',
            259  => 'Phượng, Nghĩa Lộ, không ghép',
            1369 => 'Quỳnh, Hà Giang, ghép',
            310  => 'Sa, Bắc Hà, ghép',
            1126 => 'San, Siem Reap, ghép',
            751  => 'Sáng, Bắc Hà, ghép',
            1198 => 'Sỹ, Séo Lủng, không ghép',
            1023 => 'Tam Coc Garden, Ninh Binh, ghép',
            752  => 'Tập, Ba Bể, ghép',
            581  => 'Thành, Hồng Phong, không ghép',
            2082 => 'Thiện, Hà Nội, không ghép, không ngủ',
            1054 => 'Thuyết Nhung, Mai Châu, ? ghép',
            452  => 'Tư, Mù Căng Chải, ghép',
            1193 => 'Tưng, Nậm Ngùa, không ghép',
            2301 => 'Viet Coconut Village, Ben Tre',
            1400 => 'Việt, Bến Tre, ghép',
        ];
        $yearList = [2018 => 2018, 2017 => 2017, 2016 => 2016, 2015 => 2015, 2014 => 2014, 2013 => 2013];

        // Add or remove avils
        if (isset($_POST['action'], $_POST['venue_id'], $_POST['day'], $_POST['note']) && $_POST['action'] == 'add-avail' && trim($_POST['note']) != '') {
            if (!array_key_exists($_POST['venue_id'], $venueList)) {
                throw new HttpException(404, 'Venue not found');
            }
            Yii::$app->db->createCommand()
                ->insert('at_avails', [
                    'created_at' => NOW,
                    'created_by' => USER_ID,
                    'stype'      => 'wait',
                    'rtype'      => 'venue',
                    'rid'        => $_POST['venue_id'],
                    'from_dt'    => $_POST['day'],
                    'note'       => (isset($_POST['pax']) && trim($_POST['pax']) != '' ? '(' . trim($_POST['pax']) . ') ' : '') . trim($_POST['note']),
                ])
                ->execute();
            return $this->redirect('@web/tours/nhadan?venue=' . $_POST['venue_id'] . '&year=' . substr($_POST['day'], 0, 4));
        }

        if (isset($_GET['action'], $_GET['id'], $_GET['venue_id'], $_GET['year']) && $_GET['action'] == 'remove-avail') {
            if (USER_ID == 8162) {
                // 161207 Duc Anh xoa Hoang Lan
                Yii::$app->db->createCommand()
                    ->delete('at_avails', [
                        'id'         => $_GET['id'],
                        'created_by' => [USER_ID, 29212],
                    ])
                    ->execute();
            } else {
                Yii::$app->db->createCommand()
                    ->delete('at_avails', [
                        'id'         => $_GET['id'],
                        'created_by' => USER_ID,
                    ])
                    ->execute();
            }
            return $this->redirect('@web/tours/nhadan?venue=' . $_GET['venue_id'] . '&year=' . $_GET['year']);
        }

        $getVenue = Yii::$app->request->get('venue', 616);
        $getYear  = Yii::$app->request->get('year', date('Y'));
        $getView  = Yii::$app->request->get('view', 'year');
        if ($getView != 'year') {
            $getView = 'month';
        }

        if (!array_key_exists($getVenue, $venueList)) {
            throw new HttpException(404, 'Venue not found');
        }
        if (!array_key_exists($getYear, $yearList)) {
            throw new HttpException(404, 'Invalid year');
        }

        $theVenue = Venue::find()
            ->where(['id' => $getVenue])
            ->asArray()
            ->one();
        $theCptx = Cpt::find()
            ->where(['venue_id' => $theVenue['id']])
            ->andWhere('YEAR(dvtour_day)=:year', [':year' => $getYear])
            ->with([
                'tour'      => function ($q) {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'updatedBy' => function ($q) {
                    return $q->select(['id', 'name' => 'nickname']);
                },
            ])
            ->orderBy($getView == 'year' ? 'tour_id, dvtour_day' : 'dvtour_day')
            ->asArray()
            ->limit(2500)
            ->all();
        $theWaits = Yii::$app->db->createCommand('SELECT a.*, u.nickname AS username FROM at_avails a, users u WHERE u.id=a.created_by AND  a.stype="wait" AND a.rtype="venue" AND a.rid=:id AND YEAR(a.from_dt)=:year', [':id' => $getVenue, ':year' => $getYear])
            ->queryAll();

        $ctIdList = [];
        foreach ($theCptx as $cpt) {
            $ctIdList[] = $cpt['tour']['ct_id'];
        }
        $guideList = [];
        if (!empty($ctIdList)) {
            $sql       = 'select tour_id, guide_name, use_from_dt, use_until_dt from at_tour_guides WHERE tour_id IN (' . implode(',', $ctIdList) . ')';
            $guideList = Yii::$app->db->createCommand($sql)->queryAll();
        }
        return $this->render('tours_nhadan', [
            'theVenue'  => $theVenue,
            'theCptx'   => $theCptx,
            'theWaits'  => $theWaits,
            'getVenue'  => $getVenue,
            'getYear'   => $getYear,
            'getView'   => $getView,
            'venueList' => $venueList,
            'yearList'  => $yearList,
            'guideList' => $guideList,
        ]);
    }

    public function actionServices($id = 0, $filter = '', $code = '')
    {
        // Go to another code
        if ($code != '') {
            $theTour = Tour::find()
                ->select(['ct_id'])
                ->andWhere(['like', 'code', $code])
                ->orderBy('day_from DESC')
                ->asArray()
                ->one();
            if ($theTour) {
                return $this->redirect('/tours/services/' . $theTour['ct_id']);
            }
        }

        $theTourOld = Tour::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found');
        }

        // Find tour
        $theTour = Product::find()
            ->where(['id' => $theTourOld['ct_id']])
            ->andWhere(['op_status' => 'op'])
            ->with([
                'days',
            ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour program not found');
        }

        $theCptx = Cpt::find()
            ->where(['tour_id' => $theTourOld['id']])
            ->andWhere(['or', 'latest=0', 'latest=dvtour_id'])
            ->with([
                'company'            => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'cp',
                'cp.venue'           => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'cp.company'         => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'viaCompany'         => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'              => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'comments',
                'comments.updatedBy' => function ($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        $sStatus = [
            'n' => 'Chưa đặt',
            'x' => 'Bị huỷ',
            'k' => 'OK',
        ];

        // Get exchange rates
        $sql3          = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="' . $theTour['day_from'] . '" ORDER BY rate_dt DESC LIMIT 1';
        $xRates['USD'] = Yii::$app->db->createCommand($sql3)->queryOne();

        $allVenues = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $allCompanies = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // Tour operators
        $sql5          = 'SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id=:id ORDER BY u.lname LIMIT 100';
        $tourOperators = Yii::$app->db->createCommand($sql5, [':id' => $theTourOld['id']])->queryAll();

        $tourOperatorIds = [];
        foreach ($tourOperators as $to) {
            $tourOperatorIds[] = $to['user_id'];
        }

        // Guides in this tour
        $sql6       = 'SELECT u.id, u.fname, u.lname, u.about AS uabout, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:id ORDER BY day LIMIT 100';
        $tourGuides = Yii::$app->db->createCommand($sql6, [':id' => $theTour['id']])->queryAll();

        return $this->render('tour_costs', [
            'theTour'         => $theTour,
            'theTourOld'      => $theTourOld,
            'theCptx'         => $theCptx,
            'sStatus'         => $sStatus,
            'xRates'          => $xRates,
            'allCompanies'    => $allCompanies,
            'allVenues'       => $allVenues,
            'tourOperators'   => $tourOperators,
            'tourOperatorIds' => $tourOperatorIds,
            'tourGuides'      => $tourGuides,
            'filter'          => $filter,
        ]);
    }

    // public function actionCosts($id = 0, $filter = '', $code = '')
    public function actionCosts($id = '', $code = '')
    {
        // Go to another code
        if ($code != '') {
            $theTour = Tour::find()
                ->select(['id'])
                ->andWhere(['like', 'code', $code])
                ->orderBy('id DESC')
                ->asArray()
                ->one();
            if ($theTour) {
                return $this->redirect('/tours/costs/' . $theTour['id']);
            }
        }
        $filter = '';

        $theTourOld = Tour::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found');
        }

        // Find tour
        $theTour = Product::find()
            ->where(['id' => $theTourOld['ct_id']])
            ->andWhere(['op_status' => 'op'])
            ->with([
                'days',
            ])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour program not found');
        }

        $theCptx = Cpt::find()
            ->where(['tour_id' => $theTourOld['id']])
            ->andWhere(['or', 'latest=0', 'latest=dvtour_id'])
            ->with([
                'company'            => function ($q) {
                    return $q->select(['id', 'name']);
                },
                // 'cp'=>function($q) {
                //     return $q->select(['id', 'name', 'unit', 'venue_id', 'by_company_id']);
                // },
                // 'cp.venue'=>function($q) {
                //     return $q->select(['id', 'name']);
                // },
                // 'cp.company'=>function($q) {
                //     return $q->select(['id', 'name']);
                // },
                'viaCompany'         => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'              => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'comments',
                'comments.updatedBy' => function ($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        $sStatus = [
            'n' => 'Chưa đặt',
            'x' => 'Bị huỷ',
            'k' => 'OK',
        ];

        // Get exchange rates
        $sql3          = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="' . $theTour['day_from'] . '" ORDER BY rate_dt DESC LIMIT 1';
        $xRates['USD'] = Yii::$app->db->createCommand($sql3)->queryOne();

        $allVenues = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $allCompanies = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // Tour operators
        $sql5          = 'SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id=:id ORDER BY u.lname LIMIT 100';
        $tourOperators = Yii::$app->db->createCommand($sql5, [':id' => $theTourOld['id']])->queryAll();

        $tourOperatorIds = [];
        foreach ($tourOperators as $to) {
            $tourOperatorIds[] = $to['user_id'];
        }

        // Guides in this tour
        $sql6       = 'SELECT u.id, u.fname, u.lname, u.about AS uabout, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:id ORDER BY day LIMIT 100';
        $tourGuides = Yii::$app->db->createCommand($sql6, [':id' => $theTour['id']])->queryAll();

        return $this->render('tour_costs', [
            'theTour'         => $theTour,
            'theTourOld'      => $theTourOld,
            'theCptx'         => $theCptx,
            'sStatus'         => $sStatus,
            'xRates'          => $xRates,
            'allCompanies'    => $allCompanies,
            'allVenues'       => $allVenues,
            'tourOperators'   => $tourOperators,
            'tourOperatorIds' => $tourOperatorIds,
            'tourGuides'      => $tourGuides,
            'filter'          => $filter,
        ]);
    }
    //tra lại dòng qua ajax
    public function actionLoad_tr($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            // Read and return a line for tour cost input form
            $theId  = Yii::$app->request->get('id', 0);
            $theCpt = Yii::$app->db->createCommand('SELECT *,
                (SELECT COUNT(*) FROM at_comments WHERE rtype="cpt" AND rid=dvtour_id) AS comment_count,
                IF (venue_id=0, "-", (select name from venues v where v.id=venue_id limit 1)) AS venue_name,
                IF (by_company_id=0, "-", (select name from at_companies c where c.id=by_company_id limit 1)) AS by_company_name,
                IF (via_company_id=0, "-", (select name from at_companies c where c.id=via_company_id limit 1)) AS via_company_name,
                (select name from dvo d where d.id=cp_id limit 1) AS dv_name,
                0 AS versions
                FROM cpt WHERE dvtour_id=:dvtour_id LIMIT 1', [':dvtour_id' => $theId])->queryOne();
            if ($theCpt == null) {
                die('Data not found');
            }

            $theTour = Yii::$app->db->createCommand('SELECT ct.day_from, ct.pax, t.* FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id AND t.id=:tour_id LIMIT 1', [':tour_id' => $theCpt['tour_id']])->queryOne();

            if ($theTour == null) {
                die('Data not found');
            }
            return $this->renderAjax('row_updated', [
                'theTour' => $theTour,
                'theCpt'  => $theCpt,
            ]);
        }
    }
    public function actionTel()
    {
        return $this->render('tel', []);
    }
    public function actionUpload()
    {
        if (Yii::$app->request->isAjax) {
            // var_dump($_FILES["images"]["error"]);die();
            foreach ($_FILES["images"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $name     = $_FILES["images"]["name"][$key];
                    $filePath = Yii::getAlias('@webroot') . "/uploads/";
                    move_uploaded_file($_FILES["images"]["tmp_name"][$key], $filePath . $_FILES['images']['name'][$key]);
                }
            }
            echo "<h2>Successfully Uploaded Images TO $filePath</h2>";
        } else {
            return $this->render('upload', []);
        }

    }
    public function actionUpload1()
    {
        if (Yii::$app->request->isAjax) {
            // var_dump($_FILES["images"]["error"]);die();
            foreach ($_FILES["images"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $name     = $_FILES["images"]["name"][$key];
                    $filePath = Yii::getAlias('@webroot') . "/uploads/";
                    move_uploaded_file($_FILES["images"]["tmp_name"][$key], $filePath . $_FILES['images']['name'][$key]);
                }
            }
            echo "<h2>Successfully Uploaded Images TO $filePath</h2>";
        } else {
            return $this->render('upload1', []);
        }

    }
    public function actionAjax()
    {
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['action']) && isset($_POST['dvtour_id']) && isset($_POST['tour_id']) && isset($_POST['formdata'])) {
                // Kiem tra tour
                $t = Yii::$app->db->createCommand('select * from at_tours where id=:tour_id limit 1', [':tour_id' => $_POST['tour_id']])->queryOne();
                if ($t == null) {
                    die(json_encode(array('NOK', 'Tour not found: [' . $_POST['tour_id'] . ']')));
                }

                // Danh sach dieu hanh
                $q = Yii::$app->db->createCommand('SELECT tu.*, u.name
                    FROM persons u, at_tour_user tu
                    WHERE tu.user_id=u.id
                    AND tu.tour_id=:tour_id
                    AND tu.role="operator"
                    ORDER BY u.lname
                    LIMIT 100',
                    [':tour_id' => $t['id']])->queryAll();

                $tourOperators   = $q;
                $tourOperatorIds = array();
                foreach ($tourOperators as $to) {
                    $tourOperatorIds[] = $to['user_id'];
                }

                $tourOperatorIds[] = 34718;

                // Kiem tra quyen truy cap
                // TBA: ke toan van co quyen truy cap, chi can check o action
                // if (myID != $t['op']) die(json_encode(array('NOK', '1 - Access denied for tour : ['.$_POST['tour_id'].']')));
                // Kiem tra dvtour
                if ($_POST['dvtour_id'] != 0) {
                    $q = Yii::$app->db->createCommand('select * from cpt where dvtour_id=:dvtour_id limit 1', [':dvtour_id' => $_POST['dvtour_id']])->queryOne();
                    if ($q == null) {
                        die(json_encode(array('NOK', 'DVtour not found: [' . $_POST['dvtour_id'] . ']')));
                    }

                    $dv          = $q;
                    $checkStatus = [
                        'c3' => strpos($dv['c3'], 'on') !== false,
                    ];
                }

                foreach ($_POST['formdata'] as $fd) {
                    $_POST[$fd['name']] = $fd['value'];
                }

                // Action create
                if ($_POST['action'] == 'create') {
                    if (!in_array(USER_ID, $tourOperatorIds) && myID != 1) {
                        die(json_encode(array('NOK', '2 - Access denied for tour : [' . $_POST['tour_id'] . ']')));
                    }

                    $_POST['qty']   = str_replace(',', '', $_POST['qty']);
                    $_POST['price'] = str_replace(',', '', $_POST['price']);
                    $q              = Yii::$app->db->createCommand('INSERT INTO cpt (created_at, created_by, updated_at, updated_by, tour_id, dvtour_day, dvtour_name, oppr,
                        adminby, qty, unit, price, unitc, prebooking, payer, status, due, plusminus, venue_id, by_company_id, via_company_id)
                        VALUES (:created_at, :created_by, :updated_at, :updated_by, :tour_id, :dvtour_day, :dvtour_name, :oppr,
                        :adminby, :qty, :unit, :price, :unitc, :prebooking, :payer, :status, :due, :plusminus, :venue_id, :by_company_id, :via_company_id)',
                        [
                            ':created_at'     => NOW,
                            ':created_by'     => USER_ID,
                            ':updated_at'     => NOW,
                            ':updated_by'     => USER_ID,
                            ':tour_id'        => $t['id'],
                            ':adminby'        => $_POST['adminby'],
                            ':dvtour_day'     => $_POST['dvtour_day'],
                            ':dvtour_name'    => $_POST['dvtour_name'],
                            ':oppr'           => $_POST['oppr'],
                            ':qty'            => $_POST['qty'],
                            ':unit'           => $_POST['unit'],
                            ':price'          => $_POST['price'],
                            ':unitc'          => $_POST['unitc'],
                            ':prebooking'     => $_POST['prebooking'],
                            ':payer'          => $_POST['payer'],
                            ':status'         => $_POST['status'],
                            ':due'            => $_POST['due'],
                            ':plusminus'      => $_POST['plusminus'],
                            ':venue_id'       => $_POST['venue_id'],
                            ':by_company_id'  => $_POST['by_company_id'],
                            ':via_company_id' => $_POST['via_company_id'],
                        ]);
                    if ($q->execute()) {
                        $newDvId = Yii::$app->db->getLastInsertID();
                        // Save note if any
                        if ($_POST['comment'] != '') {
                            Yii::$app->db->createCommand('INSERT INTO at_comments (created_at, created_by, updated_at, updated_by, status, rtype, rid, pid, body) VALUES (:created_at, :created_by, :updated_at, :updated_by, :status, :rtype, :rid, :pid, :body)',
                                [
                                    ':created_at' => NOW,
                                    ':created_by' => USER_ID,
                                    ':updated_at' => NOW,
                                    ':updated_by' => USER_ID,
                                    ':status'     => 'on',
                                    ':rtype'      => 'cpt',
                                    ':rid'        => isset($_POST['dvtour_id']) && $_POST['dvtour_id'] != 0 ? $_POST['dvtour_id'] : $newDvId,
                                    ':pid'        => $_POST['tour_id'],
                                    ':body'       => $_POST['comment'],
                                ])->execute();
                        }

                        die(json_encode(array('OK-CREATE', '', $newDvId, $_POST['dvtour_day'])));
                    } else {
                        die(json_encode(array('NOK', strip_tags($q->errors))));
                    }
                }

                // Action copy
                if ($_POST['action'] == 'copy') {
                    // if (!in_array(USER_ID, $tourOperatorIds))
                    //     die(json_encode(array('NOK', 'Action COPY is denied for tour : ['.$_POST['tour_id'].']')));
                    $_POST['qty']   = str_replace(',', '', $_POST['qty']);
                    $_POST['price'] = str_replace(',', '', $_POST['price']);
                    $q              = Yii::$app->db->createCommand('INSERT INTO cpt (created_at, created_by, updated_at, updated_by, tour_id, dvtour_day, dvtour_name, oppr,
                        adminby, qty, unit, price, unitc, prebooking, payer, status, due, plusminus,venue_id)
                        VALUES (:created_at, :created_by, :updated_at, :updated_by, :tour_id, :dvtour_day, :dvtour_name, :oppr, :adminby,  :qty, :unit, :price, :unitc, :prebooking, :payer, :status, :due, :plusminus, :venue_id)',
                        [
                            ':created_at'  => NOW,
                            ':created_by'  => USER_ID,
                            ':updated_at'  => NOW,
                            ':updated_by'  => USER_ID,
                            ':tour_id'     => $t['id'],
                            ':adminby'     => $_POST['adminby'],
                            ':dvtour_day'  => $_POST['dvtour_day'],
                            ':dvtour_name' => $_POST['dvtour_name'],
                            ':oppr'        => $_POST['oppr'],
                            ':qty'         => $_POST['qty'],
                            ':unit'        => $_POST['unit'],
                            ':price'       => $_POST['price'],
                            ':unitc'       => $_POST['unitc'],
                            ':prebooking'  => $_POST['prebooking'],
                            ':payer'       => $_POST['payer'],
                            ':status'      => $_POST['status'],
                            ':due'         => $_POST['due'],
                            ':plusminus'   => $_POST['plusminus'],
                            ':venue_id'    => $dv['venue_id'],
                        ]);
                    if ($q->execute()) {
                        $newDvId = Yii::$app->db->getLastInsertID();

                        // Save note if any
                        if ($_POST['comment'] != '') {
                            Yii::$app->db->createCommand('INSERT INTO at_comments (created_at, created_by, updated_at, updated_by, status, rtype, rid, pid, body) VALUES (:created_at, :created_by, :updated_at, :updated_by, :status, :rtype, :rid, :pid, :body)',
                                [
                                    ':created_at' => NOW,
                                    ':created_by' => USER_ID,
                                    ':updated_at' => NOW,
                                    ':updated_by' => USER_ID,
                                    ':status'     => 'on',
                                    ':rtype'      => 'cpt',
                                    ':rid'        => isset($_POST['dvtour_id']) && $_POST['dvtour_id'] != 0 ? $_POST['dvtour_id'] : $newDvId,
                                    ':pid'        => $_POST['tour_id'],
                                    ':body'       => $_POST['comment'],
                                ])->execute();
                        }

                        die(json_encode(array('OK-COPY', '', $newDvId, $_POST['dvtour_day'])));
                    } else {
                        die(json_encode(array('NOK', strip_tags($fv->getErrorMessage()))));
                    }
                }
                // Action update
                if ($_POST['action'] == 'update-prepare') {
                    //if (!in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', '3 - Access denied for tour : ['.$_POST['tour_id'].']')));
                    // 121108 Requires ub
                    // if (myID != $dv['updated_by']) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));
                    // Van con Mai Thuy ghost
                    // if (!in_array(USER_ID, $tourOperatorIds) && USER_ID != 1)
                    //     die(json_encode(array('NOK', '3 - Access denied for tour : ['.$_POST['tour_id'].']')));
                    die(
                        json_encode(
                            array(
                                'OK-UPDATE-PREPARE',
                                'dvtour_day'     => $dv['dvtour_day'],
                                '',
                                'dvtour_name'    => $dv['dvtour_name'],
                                'oppr'           => $dv['oppr'],
                                'qty'            => $dv['qty'],
                                'unit'           => $dv['unit'],
                                'price'          => number_format($dv['price'], intval($dv['price']) == $dv['price'] ? 0 : 2),
                                'unitc'          => $dv['unitc'],
                                0,
                                'vat'            => $dv['vat'],
                                'prebooking'     => $dv['prebooking'],
                                'payer'          => $dv['payer'],
                                'status'         => $dv['status'],
                                'due'            => $dv['due'] != '0000-00-00' ? $dv['due'] : '',
                                'venue_id'       => $dv['venue_id'],
                                'adminby'        => $dv['adminby'],
                                'start'          => '00:00',
                                'crfund'         => $dv['crfund'],
                                'via_company_id' => $dv['via_company_id'],
                                'by_company_id'  => $dv['by_company_id'],
                                'plusminus'      => $dv['plusminus'],
                            )
                        )
                    );
                }
                // Action update
                if ($_POST['action'] == 'update') {
                    // Kiem tra xem da thanh toan chua
                    $mttx = Yii::$app->db->createCommand('SELECT id FROM at_mtt WHERE status!="deleted" AND cpt_id=:dvtour_id LIMIT 2', [':dvtour_id' => $dv['dvtour_id']])->queryAll();
                    // if ($mttx == null) {
                    //     die(json_encode(array('NOK', '3 - Payment already made for this service : ['.$dv['dvtour_id'].']')));
                    // }

                    //die(json_encode(array('NOK', 'Tạm thời ngưng edit dv tour ; liên hệ Mr Huân')));
                    $myApprovers = explode('][', $dv['approved_by']);
                    if ($dv['approved_by'] != '' && count($myApprovers) >= 3) {
                        $mustSaveAs = true;
                        $saveAsId   = $_POST['dvtour_id'];
                    }
                    // Phai la nguoi dieu hanh
                    // if (myID != $dv['updated_by'] || !in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));

                    // Back to updater 121108
                    // if (myID != $dv['updated_by']) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));
                    // Since 120926 Ms Mai Thuy retires
                    if (!in_array(USER_ID, $tourOperatorIds)) {
                        die(json_encode(array('NOK', '4 - Access denied for tour : [' . $_POST['tour_id'] . ']')));
                    }

                    // Dich vu phai chua duoc duyet [TRA][KTT][TGD]
                    // foreach ($checkStatus as $status) {
                    //     if ($status) {
                    //         die(json_encode(['NOK', 'Không thể sửa mục đã được kế toán đánh dấu '.$status]));
                    //     }
                    // }

                    if ($dv['xacnhan_by'] != 0) {
                        die(json_encode(array('NOK', 'Không thể sửa mục đã được kế toán trưởng đánh dấu [KTT]')));
                    }

                    if ($dv['duyet_by'] != 0) {
                        die(json_encode(array('NOK', 'Không thể sửa mục đã được TGĐ duyệt')));
                    }

                    // var_dump($_POST);die();
                    $_POST['qty']   = str_replace(',', '', $_POST['qty']);
                    $_POST['price'] = str_replace(',', '', $_POST['price']);
                    $q              = Yii::$app->db->createCommand('UPDATE cpt SET updated_at=:updated_at, updated_by=:updated_by, approved=0, approved_by="", dvtour_day=:dvtour_day, dvtour_name=:dvtour_name, oppr=:oppr, adminby=:adminby, qty=:qty, unit=:unit, price=:price, unitc=:unitc, prebooking=:prebooking, payer=:payer, status=:status, due=:due, plusminus=:plusminus, venue_id=:venue_id, by_company_id=:by_company_id, via_company_id=:via_company_id WHERE dvtour_id=:dvtour_id LIMIT 1',
                        [
                            ':updated_at'     => NOW,
                            ':updated_by'     => USER_ID,
                            ':dvtour_day'     => $_POST['dvtour_day'],
                            ':dvtour_name'    => $_POST['dvtour_name'],
                            ':oppr'           => $_POST['oppr'],
                            ':adminby'        => $_POST['adminby'],
                            ':qty'            => $_POST['qty'],
                            ':unit'           => $_POST['unit'],
                            ':price'          => $_POST['price'],
                            ':unitc'          => $_POST['unitc'],
                            ':prebooking'     => $_POST['prebooking'],
                            ':payer'          => $_POST['payer'],
                            ':status'         => $_POST['status'],
                            ':due'            => $_POST['due'],
                            ':plusminus'      => $_POST['plusminus'],
                            ':dvtour_id'      => $_POST['dvtour_id'],
                            ':venue_id'       => $_POST['venue_id'],
                            ':by_company_id'  => $_POST['by_company_id'],
                            ':via_company_id' => $_POST['via_company_id'],
                        ]);
                    if ($q->execute()) {
                        // Save note if any
                        if ($_POST['comment'] != '') {
                            $q = Yii::$app->db->createCommand('INSERT INTO at_comments (created_at, created_by, updated_at, updated_by, status, rtype, rid, pid, body) VALUES (:created_at, :created_by, :updated_at, :updated_by, :status, :rtype, :rid, :pid, :body)',
                                [
                                    ':created_at' => NOW,
                                    ':created_by' => USER_ID,
                                    ':updated_at' => NOW,
                                    ':updated_by' => USER_ID,
                                    ':status'     => 'on',
                                    ':rtype'      => 'cpt',
                                    ':rid'        => isset($_POST['dvtour_id']) && $_POST['dvtour_id'] != 0 ? $_POST['dvtour_id'] : $newDvId,
                                    ':pid'        => $_POST['tour_id'],
                                    ':body'       => $_POST['comment'],
                                ])->execute();

                            /*
                        Yii::$app->db->createCommand('INSERT INTO at_mm (uo, ub, rel_type, rel_id, pid, mm) VALUES (%s, %i, %s, %i, %i, %s)',
                        NOW, myID, 'service', $_POST['dvtour_id'], $_POST['tour_id'], $_POST['mm']
                        );*/
                        }
                        // Bao cho nhung nguoi lien quan, neu co
                        /*
                        if ($dv['approved_by'] != '') {
                        foreach ($myApprovers as $myApprover) {
                        $myApprover = trim(trim($myApprover, '['), ':');
                        // if ($myApprover != myID) Yii::$app->db->createCommand('INSERT INTO chat (`from`, `to`, `message`, `sent`) VALUES (?,?,?,NOW())', array(myID, $myApprover, 'Dịch vụ <a href="'.SITE_HOME.'tours-dvtour/'.$_POST['tour_id'].'#day'.$_POST['dvtour_day'].'">'.$t['tour_code'].' : '.$_POST['dvtour_day'].' : '.$_POST['dvtour_name'].'</a> đã thay đổi. Đề nghị duyệt lại.'));
                        }
                        }
                         */
                        die(json_encode(array('OK-UPDATE', '', $_POST['dvtour_id'], $_POST['dvtour_day'])));
                    } else {
                        die(json_encode(array('NOK', $q->errors)));
                    }
                    die(json_encode(array('NOK', 'Welcome to 2012')));
                }

                // Action delete
                if ($_POST['action'] == 'delete') {
                    if (USER_ID != $dv['updated_by']) {
                        die(json_encode(array('NOK', 'Access denied for tour : [' . $_POST['tour_id'] . ']')));
                    }

                    // Dich vu phai chua duoc duyet [TRA][KTT][TGD]
                    foreach ($checkStatus as $status) {
                        if ($status) {
                            die(json_encode(['NOK', 'Không thể sửa mục đã được kế toán đánh dấu ' . $status]));
                        }
                    }
                    if ($dv['xacnhan_by'] != 0) {
                        die(json_encode(array('NOK', 'Không thể sửa mục đã được kế toán trưởng đánh dấu [KTT]')));
                    }

                    // Delete all comments
                    Yii::$app->db->createCommand('DELETE FROM at_comments WHERE rtype="cpt" AND rid=:dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();
                    // Delete dvt
                    Yii::$app->db->createCommand('DELETE FROM cpt WHERE dvtour_id=:dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();
                    $newTotalCost = 0;
                    if ($dv['plusminus'] == 'plus') {
                        if (isset($xRates[$dv['unitc']])) {
                            $newTotalCost = (int) $_POST['total'] - $dv['qty'] * $dv['price'] * $xRates[$dv['unitc']] * (1 + $dv['vat'] / 100);
                        }
                    } else {
                        if (isset($xRates[$dv['unitc']])) {
                            $newTotalCost = (int) $_POST['total'] + $dv['qty'] * $dv['price'] * $xRates[$dv['unitc']] * (1 + $dv['vat'] / 100);
                        }
                    }
                    die(json_encode(array('OK-DELETE', '', number_format($newTotalCost, 2))));
                }

                // Action mark [ok]
                if ($_POST['action'] == 'ok') {
                    // Chi dieu hanh co quyen
                    if (USER_ID != $dv['updated_by'] || !in_array(USER_ID, $tourOperatorIds)) {
                        die(json_encode(array('NOK', 'Chỉ dành cho người điều hành tour')));
                    }

                    // Chi co the sua neu Mr Manh chua duyet
                    if ($dv['duyet_by'] != 0) {
                        die(json_encode(array('NOK', 'Không thể sửa mục đã được TGĐ duyệt')));
                    }

                    // Đã được duyệt thì bỏ
                    if ($dv['status'] == 'k') {
                        // Check it
                        $q = Yii::$app->db->createCommand('UPDATE cpt SET status="n" WHERE dvtour_id=:dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();
                        if ($q == 1) {
                            die(json_encode(array('OK-OK', '')));
                        }

                    } else {
                        // Disapprove it
                        $q = Yii::$app->db->createCommand('UPDATE cpt SET status="k" WHERE dvtour_id=:dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();
                        if ($q == 1) {
                            die(json_encode(array('OK-OK', 'xacnhan')));
                        }

                    }
                    die(json_encode(array('NOK', 'Không thực hiện được thao tác.')));
                }

                // Phuong, Hien, Hanh, Lan, Binh, Huyen, Ngoc, Mong
                // 28431,  11,   17,   16,  20787,29739, 30085, 32206

                // 150930 => Tu Phuong
                if ($_POST['action'] == 'ktt') {
                    // Binh, Hanh, Hien
                    if (!in_array(USER_ID, [1, 28431])) {
                        die(json_encode(array('NOK', 'ONLY Tu Phuong allowed')));
                    }
                    // Chi co the sua neu Mr Manh chua duyet
                    // 150930 bo GD if ($dv['duyet_by'] != 0) die(json_encode(array('NOK', 'Không thể sửa mục đã được TGĐ duyệt')));
                    if ($dv['xacnhan_by'] == 0) {
                        // Check it
                        $q = Yii::$app->db->createCommand('UPDATE cpt SET xacnhan_date=NOW(), xacnhan_by=:USER_ID WHERE dvtour_id=:dvtour_id LIMIT 1', [':USER_ID' => USER_ID, ':dvtour_id' => $_POST['dvtour_id']])->execute();
                        if ($q == 1) {
                            die(json_encode(array('OK-OK', 'xacnhan')));
                        }

                    } else {
                        // Disapprove it
                        $q = Yii::$app->db->createCommand('UPDATE cpt SET xacnhan_by = 0 WHERE dvtour_id=:dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();
                        if ($q == 1) {
                            die(json_encode(array('OK-OK', '')));
                        }

                    }
                    die(json_encode(array('NOK', 'Không thực hiện được thao tác.')));
                }

                // NEW CHECKS
                if (in_array($_POST['action'], ['c3'])) {
                    // Phuong, Hien, Hanh, Lan, Binh, Huyen, Ngoc, Mong
                    // 28431,  11,   17,   16,  20787,29739, 30085, 32206
                    $allowUsers = [
                        'c3' => [1, 28431, 17, 20787, 11, 29739, 25457, 32206],
                    ];

                    if (!in_array(USER_ID, [1])) {
                        // die(json_encode(['NOK', 'Tạm thời dừng để update']));
                    }

                    // Xem co duoc quyen check khong
                    $cx = $_POST['action'];
                    if (!in_array(USER_ID, $allowUsers[$cx])) {
                        die(json_encode(['NOK', 'Access denied']));
                    }

                    // Chi KTT duoc quyen check lai muc da check
                    // Neu toi ko phai KTT va toi check muc da dc nguoi khac check
                    if (!in_array(USER_ID, [28431]) && $checkStatus[$cx] && strpos($dv[$cx], ',' . USER_ID . ',') === false) {
                        die(json_encode(['NOK', 'Không thể sửa mục đã được người khác check']));
                    }

                    // Checking order

                    // C3 chi neu C1 hoac C2
                    // if ($cx == 'c3' && (!$checkStatus['c1'] || !$checkStatus['c2'])) {
                    //     // die(json_encode(['NOK', 'Chưa check C1 hoặc C2']));
                    // }
                    // C3 chi neu C1 hoac C2
                    // if ($cx == 'c3' && (!$checkStatus['c1'] || !$checkStatus['c2'])) {
                    //     die(json_encode(['NOK', 'Chưa check C1 hoặc C2']));
                    // }

                    if (strpos($dv[$_POST['action']], 'on') === false) {
                        $value  = 'on,' . USER_ID . ',' . NOW;
                        $status = 'on';
                    } else {
                        $value  = 'off,' . USER_ID . ',' . NOW;
                        $status = 'off';
                    }

                    $q = Yii::$app->db->createCommand('UPDATE cpt SET ' . $cx . '="' . $value . '" WHERE dvtour_id=:dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();

                    if ($q == 1) {
                        die(json_encode(['OK-OK', $status]));
                    }

                    if (isset($dv[$_POST['action'] . '_status'])) {
                        /*
                    if ($dv[$_POST['action'].'_status'] == 'off') {
                    // Make ON
                    $q = Yii::$app->db->createCommand('UPDATE cpt SET '.$cx.'_status="on", '.$cx.'_dt=NOW(), '.$cx.'_by=%i WHERE dvtour_id=%i LIMIT 1', myID, $_POST['dvtour_id']);
                    if ($q->countAffectedRows() == 1) die(json_encode(['OK-OK', 'on']));
                    } else {
                    // Make OFF
                    $q = Yii::$app->db->createCommand('UPDATE cpt SET '.$cx.'_status="off", '.$cx.'_dt=NOW(), '.$cx.'_by=%i WHERE dvtour_id=%i LIMIT 1', myID, $_POST['dvtour_id']);
                    if ($q->countAffectedRows() == 1) die(json_encode(['OK-OK', 'off']));
                    }*/
                    }
                    die(json_encode(['NOK', 'Unknown error']));
                }

                // Action mark [vat]

                // Action mark [tgd]
                // Duyet boss, myID = 2
                if ($_POST['action'] == 'gd') {
                    // Chi Mr Manh va Mr Tuan (since 111031) co the duyet
                    if (USER_ID != 2 && USER_ID != 4065) {
                        die(json_encode(['NOK', 'Access denied. Only Mr Tuan, Mr Manh.']));
                    }
                    // Đã được duyệt thì bỏ
                    if ($dv['duyet_by'] == 0) {
                        // Check it
                        Yii::$app->db->createCommand('UPDATE cpt SET duyet_date=NOW(), duyet_by=:uid WHERE dvtour_id=:dvtour_id LIMIT 1', [':uid' => USER_ID, ':dvtour_id' => $_POST['dvtour_id']])->execute();
                        die(json_encode(['OK-OK', 'xacnhan']));
                    } else {
                        // Disapprove it
                        Yii::$app->db->createCommand('UPDATE cpt SET duyet_by=0 WHERE dvtour_id=dvtour_id LIMIT 1', [':dvtour_id' => $_POST['dvtour_id']])->execute();
                        die(json_encode(['OK-OK', '']));
                    }
                }

                // XAC NHAN CUA KE TOAN VAT G_ID = 7
                if ($_POST['action'] == 'vat') {
                    // Chi ke toan co quyen
                    if (!$appUser->hasRoles('ketoan')) {
                        die(json_encode(['NOK', 'Access denied. Only accountants.']));
                    }
                    // Chi co the sua neu Mr Manh chua duyet
                    //if ($s['duyet_by'] != 0) exit('nok');
                    // Thay đổi trạng thái VAT
                    $vatStatus = '';
                    if ($s['vat_ok'] == '') {
                        $vatStatus = 'needed';
                    }

                    if ($s['vat_ok'] == 'needed') {
                        $vatStatus = 'ok';
                    }

                    if ($s['vat_ok'] == 'ok') {
                        $vatStatus = '';
                    }

                    Yii::$app->db->createCommand('UPDATE cpt SET vat_ok=:vatStatus, vat_by=:uid WHERE dvtour_id=:dvtour_id LIMIT 1', [':vatStatus' => $vatStatus, ':uid' => USER_ID, ':dvtour_id' => $_POST['dvtour_id']]);
                    // Reload
                    die(json_encode(['OK-OK', '']));
                }
            }
        }
    }
    public function actionTongcp($month = 0, $payer = '', $orderby = 'tourcode')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        // Danh sách tour
        $sql = 'SELECT ct.pax, ct.day_count, ct.day_from, t.code, t.name, t.status, t.ct_id, t.id, t.se
            FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id AND SUBSTRING(day_from, 1, 7)=:ym ORDER BY ' . ($orderby == 'tourcode' ? 'SUBSTRING(code,2,7)' : 'day_from, SUBSTRING(code,2,7)') . ' LIMIT 1000';
        $theTours = Yii::$app->db->createCommand($sql, [':ym' => $month])->queryAll();

        $tourIdList = [];
        $result     = [];
        $usdRates   = [];
        foreach ($theTours as $tour) {
            $tourIdList[]        = $tour['id'];
            $result[$tour['id']] = 0;

            // USD-VND rates
            $sql = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="' . $tour['day_from'] . '" ORDER BY rate_dt DESC LIMIT 1';
            //$usdRates[$tour['id']] = Yii::$app->db->createCommand($sql)->queryScalar();
            //if ($usdRates[$tour['id']] == 0) {
            $usdRates[$tour['id']] = 21000;
            //}
        }

        $xRates['EUR'] = 24300;
        $xRates['USD'] = 21300;
        $xRates['VND'] = 1;

        // Cac chi phi cua tour
        if ($payer == 'bunthol') {
            $sql = 'SELECT * FROM cpt WHERE tour_id IN (' . implode(',', $tourIdList) . ') AND (latest=0 OR latest=dvtour_id) AND payer="Bunthol" ORDER BY tour_id';
        } elseif ($payer == 'itravellaos') {
            $sql = 'SELECT * FROM cpt WHERE tour_id IN (' . implode(',', $tourIdList) . ') AND (latest=0 OR latest=dvtour_id) AND payer="iTravelLaos" ORDER BY tour_id';
        } else {
            $sql = 'SELECT * FROM cpt WHERE tour_id IN (' . implode(',', $tourIdList) . ') AND (latest=0 OR latest=dvtour_id) ORDER BY tour_id';
        }
        $theCptx = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($theCptx as $cp) {
            if ($cp['latest'] == 0) {
                if (in_array($payer, ['bunthol', 'itravellaos'])) {
                    $sub = $cp['qty'] * $cp['price'] * (1 + $cp['vat'] / 100);
                } else {
                    if ($cp['unitc'] == 'USD') {
                        $sub = $cp['qty'] * $cp['price'] * $usdRates[$cp['tour_id']] * (1 + $cp['vat'] / 100);
                    } else {
                        $sub = $cp['qty'] * $cp['price'] * $xRates[$cp['unitc']] * (1 + $cp['vat'] / 100);
                    }
                }

                if ($cp['plusminus'] == 'minus') {
                    $sub = -$sub;
                }
                $result[$cp['tour_id']] += $sub;
            }
        }

        return $this->render('tours_tongchiphi', [
            'month'    => $month,
            'result'   => $result,
            'theTours' => $theTours,
            'theCptx'  => $theCptx,
        ]);
    }

    public function actionCmd($id)
    {
        $theTour = Product::find()
            ->where(['op_status' => 'op', 'id' => $id])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found ' . $id);
        }
        return $this->render('tours_cmd', [
            'theTour' => $theTour,
        ]);
    }

    // Test tour by day
    public function actionTest($id = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id])
            ->with([
                'days',
                'tournotes',
                'tournotes.updatedBy' => function ($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()
            ->one();

        /*$sql = 'select n.id, n.title, n.from_id, n.body from at_messages n, at_relations r where r.otype="note" and r.oid=n.id and r.rtype="case" and r.rid=:rid order by co desc';
        $theNotes = \common\models\Note::findBySql($sql, [
        ':rid'=>22319,
        ])
        ->with([
        'from'=>function($q) {
        return $q->select(['id', 'name']);
        },
        'to'=>function($q) {
        return $q->select(['id', 'name']);
        },
        ])
        ->asArray()
        ->all();

        \fCore::expose($theNotes);
        exit;*/

        if (!$theTour) {
            throw new HttpException(404, 'Tour itinerary not found.');
        }

        if (!in_array(USER_ID, [1])) {
            // throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Drivers and vehicles
        $sql            = 'select *, IF(driver_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id=:tour_id order by use_from_dt limit 100';
        $theTourDrivers = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Guides
        $sql           = 'select *, IF(guide_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id=:tour_id order by use_from_dt limit 100';
        $theTourguides = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Notes
        $sql          = 'select *, IF(driver_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id=:tour_id order by use_from_dt limit 100';
        $theTourNotes = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        $theForm = new \app\models\TourTestForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            echo 'DONE';
            exit;
        }

        return $this->render('tours_test', [
            'theTour'        => $theTour,
            'theTourOld'     => $theTourOld,
            'theTourDrivers' => $theTourDrivers,
            'theTourguides'  => $theTourguides,
            'theForm'        => $theForm,
        ]);
    }

    // Assign tour guides
    public function actionGuides($id = 0, $action = 'add', $item_id = 0)
    {
        $theTour = Product::find()
            ->where(['id' => $id])
            ->with(['days'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour itinerary not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id' => $id, 'status' => 'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Guides for this tour
        $sql        = 'select *, guide_name, guide_user_id, IF(guide_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id=:tour_id order by use_from_dt limit 100';
        $tourGuides = Yii::$app->db->createCommand($sql, [':tour_id' => $theTour['id']])->queryAll();

        // Tour guide list
        $sql       = 'select u.id, CONCAT(u.name, " - ", REPLACE(u.phone, " ", "")) AS namephone from persons u, at_profiles_tourguide p where u.id=p.user_id order by u.lname, u.fname limit 3000';
        $theGuides = Yii::$app->db->createCommand($sql)->queryAll();

        $theGuide = false;

        // Check action
        if (
            !in_array($action, ['add', 'addtime', 'edit', 'delete'])
            || (in_array($action, ['addtime', 'edit', 'delete']) && $item_id == 0)
        ) {
            return $this->redirect(DIR . URI);
        }

        // action add
        if ($action == 'add') {
            $theForm                = new TourGuideForm;
            $theForm->bookingStatus = 'confirmed';
            $theForm->useTimezone   = 'Asia/Ho_Chi_Minh';
            $theForm->useFromDt     = $theTour['day_from'] . ' 08:00';
            $theForm->useUntilDt    = date('Y-m-d', strtotime('+ ' . ($theTour['day_count'] - 1) . ' days', strtotime($theTour['day_from']))) . ' 22:00';

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $guideUserId = 0;
                foreach ($theGuides as $guide) {
                    if ($theForm['guideName'] == trim($guide['namephone'])) {
                        $guideUserId = $guide['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->insert('at_tour_guides', [
                    'created_dt'     => NOW,
                    'created_by'     => USER_ID,
                    'updated_dt'     => NOW,
                    'updated_by'     => USER_ID,
                    'tour_id'        => $theTour['id'],
                    'guide_company'  => $theForm['guideCompany'],
                    'guide_name'     => $theForm['guideName'],
                    'guide_user_id'  => $guideUserId,
                    'use_from_dt'    => $theForm['useFromDt'],
                    'use_until_dt'   => $theForm['useUntilDt'],
                    'use_timezone'   => $theForm['useTimezone'],
                    'booking_status' => $theForm['bookingStatus'],
                    'points'         => $theForm['points'],
                    'note'           => $theForm['note'],
                ])->execute();

                return $this->redirect(DIR . URI);
            }
        }

        // action add time
        if ($action == 'addtime' && $item_id != 0) {
            foreach ($tourGuides as $guide) {
                if ($guide['id'] == $item_id) {
                    $theGuide = $guide;
                }
            }

            if (!$theGuide) {
                throw new HttpException(404, 'Guide info not found');
            }

            if (!in_array(USER_ID, [1, 118, 29296, 33415, $theGuide['created_by'], $theGuide['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourGuideForm;

            $theForm->useTimezone   = $theGuide['use_timezone'];
            $theForm->guideCompany  = $theGuide['guide_company'];
            $theForm->guideName     = $theGuide['guide_name'];
            $theForm->useFromDt     = $theGuide['use_from_dt'];
            $theForm->useUntilDt    = $theGuide['use_until_dt'];
            $theForm->bookingStatus = $theGuide['booking_status'];
            $theForm->points        = $theGuide['points'];
            $theForm->note          = $theGuide['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                Yii::$app->db->createCommand()->insert('at_tour_guides', [
                    'created_dt'     => NOW,
                    'created_by'     => USER_ID,
                    'updated_dt'     => NOW,
                    'updated_by'     => USER_ID,
                    'parent_id'      => $item_id,
                    'tour_id'        => $theTour['id'],
                    'guide_company'  => $theForm['guideCompany'],
                    'guide_name'     => $theForm['guideName'],
                    'guide_user_id'  => $theGuide['guide_user_id'],
                    'use_from_dt'    => $theForm['useFromDt'],
                    'use_until_dt'   => $theForm['useUntilDt'],
                    'use_timezone'   => $theForm['useTimezone'],
                    'booking_status' => $theForm['bookingStatus'],
                    //'points'=>$theForm['points'],
                    'note'           => $theForm['note'],
                ])->execute();

                return $this->redirect(DIR . URI);
            }
        }

        // action edit
        if ($action == 'edit' && $item_id != 0) {
            foreach ($tourGuides as $guide) {
                if ($guide['id'] == $item_id) {
                    $theGuide = $guide;
                }
            }

            if (!$theGuide) {
                throw new HttpException(404, 'Guide info not found');
            }

            $allowEditList = [1, 118, 29296, 33415, $theGuide['created_by'], $theGuide['updated_by']];

            // Tour ops
            $sql       = 'SELECT user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
            $tourOpIds = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

            foreach ($tourOpIds as $opId) {
                $allowEditList[] = $opId['user_id'];
            }

            $allowEditList = array_unique($allowEditList);

            if (!in_array(USER_ID, $allowEditList)) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourGuideForm;

            $theForm->useTimezone   = $theGuide['use_timezone'];
            $theForm->guideCompany  = $theGuide['guide_company'];
            $theForm->guideName     = $theGuide['guide_name'];
            $theForm->useFromDt     = $theGuide['use_from_dt'];
            $theForm->useUntilDt    = $theGuide['use_until_dt'];
            $theForm->bookingStatus = $theGuide['booking_status'];
            $theForm->points        = $theGuide['points'];
            $theForm->note          = $theGuide['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $guideUserId = 0;
                foreach ($theGuides as $guide) {
                    if ($theForm['guideName'] == trim($guide['namephone'])) {
                        $guideUserId = $guide['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->update('at_tour_guides', [
                    'updated_dt'     => NOW,
                    'updated_by'     => USER_ID,
                    'guide_company'  => $theForm['guideCompany'],
                    'guide_name'     => $theGuide['guide_user_id'] != 0 ? $theGuide['guide_name'] : $theForm['guideName'],
                    'guide_user_id'  => $theGuide['guide_user_id'] != 0 ? $theGuide['guide_user_id'] : $guideUserId,
                    'use_from_dt'    => $theForm['useFromDt'],
                    'use_until_dt'   => $theForm['useUntilDt'],
                    'use_timezone'   => $theForm['useTimezone'],
                    'booking_status' => $theForm['bookingStatus'],
                    'points'         => $theForm['points'],
                    'note'           => $theForm['note'],
                ], ['id' => $item_id])->execute();
                Yii::$app->session->setFlash('success', 'Guide info has been updated: ' . $theGuide['guide_name']);
                return $this->redirect(DIR . URI);
            }
        }

        // action delete
        if ($action == 'delete' && $item_id != 0) {
            $theForm = false;
            foreach ($tourGuides as $guide) {
                if ($guide['id'] == $item_id) {
                    $theGuide = $guide;
                }
            }

            if (!$theGuide) {
                throw new HttpException(404, 'Guide info not found');
            }

            $allowEditList = [1, 118, 29296, $theGuide['created_by'], $theGuide['updated_by']];

            // Tour ops
            $sql       = 'SELECT user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
            $tourOpIds = Yii::$app->db->createCommand($sql, [':tour_id' => $theTourOld['id']])->queryAll();

            foreach ($tourOpIds as $opId) {
                $allowEditList[] = $opId['user_id'];
            }

            $allowEditList = array_unique($allowEditList);

            if (!in_array(USER_ID, $allowEditList)) {
                throw new HttpException(403, 'Access denied');
            }

            //if (Yii::$app->request->post('confirm') == 'delete') {
            Yii::$app->db->createCommand()->delete('at_tour_guides', ['parent_id' => $item_id])->execute();
            Yii::$app->db->createCommand()->delete('at_tour_guides', ['id' => $item_id])->execute();
            Yii::$app->session->setFlash('success', 'Guide info has been deleted: ' . $theGuide['guide_name']);
            return $this->redirect(DIR . URI);
            //}
        }

        return $this->render('tours_guides', [
            'theTour'    => $theTour,
            'theTourOld' => $theTourOld,
            'theForm'    => $theForm,
            'tourGuides' => $tourGuides,
            'theGuide'   => $theGuide,
            'theGuides'  => $theGuides,
            'action'     => $action,
            'item_id'    => $item_id,
        ]);
    }

    public function actionGuides2($id = 0)
    {
        if ($id != 333) {
            die('REQUIRES 333');
        }
        $sql = 'select * from at_tour_guide order by tour_id, user_id, day';
        $tgx = Yii::$app->db->createCommand($sql)->queryAll();
        $ntg = [
            'tour_id'  => 0,
            'guide_id' => 0,
            'points'   => 0,
            'from'     => 0,
            'until'    => 0,
        ];
        Yii::$app->db->createCommand('truncate table at_tour_guides')->execute();
        foreach ($tgx as $tg) {
            if ($tg['ct_id'] == $ntg['tour_id'] && $tg['user_id'] == $ntg['guide_id']) {
                if ((int) $tg['pax_ratings'] != 0) {
                    $ntg['points'] = (int) $tg['pax_ratings'];
                }
                if (date('Y-m-d', strtotime('+1 days', strtotime($ntg['until']))) != $tg['day']) {
                    echo '<br>tour id=', $tg['tour_id'];
                }
                $ntg['until'] = $tg['day'] . ' 22:00';
            } else {
                if ($ntg['tour_id'] != 0) {
                    Yii::$app->db->createCommand()->insert('at_tour_guides', [
                        'created_dt'     => $ntg['uo'],
                        'created_by'     => $ntg['ub'],
                        'updated_dt'     => $ntg['uo'],
                        'updated_by'     => $ntg['ub'],
                        'points'         => $ntg['points'],
                        'tour_id'        => $ntg['tour_id'],
                        'guide_user_id'  => $ntg['guide_id'],
                        'use_from_dt'    => $ntg['from'],
                        'use_until_dt'   => $ntg['until'],
                        'use_timezone'   => 'Asia/Ho_Chi_Minh',
                        'booking_status' => 'confirmed',
                    ])->execute();
                    echo '<br>ITEM ' . $tg['id'] . ' SAVED.';
                }
                $ntg = [
                    'uo'       => $tg['uo'],
                    'ub'       => $tg['ub'],
                    'tour_id'  => $tg['ct_id'],
                    'guide_id' => $tg['user_id'],
                    'points'   => (int) $tg['pax_ratings'],
                    'from'     => $tg['day'] . ' 08:00',
                    'until'    => $tg['day'] . ' 22:00',
                ];
            }
        }
        echo '<br>DONE!';
    }

    // Create tour notes for calendar
    public function actionCtn($id = 0)
    {
        $theTour = Product::find()
            ->where(['op_status' => 'op', 'id' => $id])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }
        $theNote = Tournote::find()
            ->where(['product_id' => $id, 'created_by' => USER_ID])
            ->one();
        if (!$theNote) {
            $theNote             = new Tournote;
            $theNote->product_id = $theTour['id'];
            $theNote->created_at = NOW;
            $theNote->created_by = USER_ID;
            $theNote->updated_at = NOW;
            $theNote->updated_by = USER_ID;
        }

        if ($theNote->load(Yii::$app->request->post()) && $theNote->validate()) {
            $theNote->save();
            return $this->redirect('/products/op/' . $theTour['id']);
        }

        return $this->render('tours_ctn', [
            'theTour' => $theTour,
            'theNote' => $theNote,
        ]);
    }
}
