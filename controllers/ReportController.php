<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Venue;
use common\models\Loc;
use common\models\Cp;
use common\models\Cpg;
use common\models\Cpt;
use common\models\Destination;
use common\models\Product;
use common\models\Booking;
use common\models\Inquiry;
use common\models\Invoice;
use common\models\Kase;
use common\models\Person;
use common\models\Tour;
use common\models\Country;
use common\models\Payment;
use common\models\Referral;
use common\models\User;
use yii\db\Query;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportController extends MyController
{
    public function actions() {
        return [
            'xpax-who-did' => [
                'class' => 'app\controllers\actions\PaxWhoDidAction',
            ],
            'xexport' => [
                'class' => 'lajax\translatemanager\controllers\actions\ExportAction',
            ],
            'b2c'=>[
                'class'=>'app\controllers\actions\reports\B2c'
            ],
            'b2c-conversion-rate'=>[
                'class'=>'app\controllers\actions\reports\B2cConversionRate',
            ],
        ];
    }


    /**
     * Bao cao QKKH 4: Phan bo QHKH cac tour theo thang
     */
    public function actionQhkh04($year = '')
    {
        $qhkhIdList = [12952, 52998, 47034, 51532, 49949, 53042, 52997, 29123];
        $staffList = User::find()
            ->select(['id', 'name'])
            ->where(['id'=>$qhkhIdList])
            ->asArray()
            ->all();

        for ($y = date('Y') + 2; $y >= 2012; $y --) {
            $yearList[$y] = $y;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        // Find all tours staring this year
        $sql = 'SELECT u.id AS user_id, u.name AS user_name, t.code, MONTH(p.day_from) AS mo, tour_regions FROM at_tours t, at_ct p, at_tour_user tu, users u WHERE tu.user_id=u.id AND tu.tour_id=t.id AND p.id=t.ct_id AND YEAR(p.day_from)=:year AND p.op_status="op" AND p.op_finish!="canceled" AND tu.role="cservice" AND u.status="on"';
        $tours = Yii::$app->db->createCommand($sql, [':year'=>$year])->queryAll();

        $result = [$year=>[]];
        foreach ($tours as $tour) {
            // var_dump($tour);die;
            $month = $tour['mo'];
            $user = $tour['user_id'];
            $pos = strpos($tour['tour_regions'], ':');
            if ($pos !== false) {
                $dest = substr($tour['tour_regions'], $pos + 1, 2);
                // var_dump($tour);die;
                if ($dest == 'vn') {
                    // $dest = Yii::t('x', 'Vietnam');
                } elseif ($dest == 'la') {
                    // $dest = Yii::t('x', 'Laos');
                } elseif ($dest == 'kh') {
                    // $dest = Yii::t('x', 'Cambodia');
                } else {
                    $dest = Yii::t('x', 'Other');
                }
            } else {
                $dest = 'Other';
            }
            if (!isset($result[$year][$month][$dest][$user])) {
                $result[$year][$month][$dest][$user] = 1;
            } else {
                $result[$year][$month][$dest][$user] ++;
            }
        }

        $result = [$year=>[]];

        $tours = Product::find()
            ->select('*')
            ->where(['op_status'=>'op', 'YEAR(day_from)'=>$year, 'owner'=>'at'])
            ->andWhere('op_finish!="canceled"')
            ->with([
                'tourStats',
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id', 'tour_regions']);
                },
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.case',
                'bookings.case.stats'

            ])
            ->asArray()
            ->all();

        // \fCore::expose($tours); exit;

        foreach ($tours as $tour) {
            if (count($tour['bookings']) > 1) {
                die('co nhieu booking');
            }
            $month = (int)substr($tour['day_from'], 5, 2);
            $pos = strpos($tour['tour']['tour_regions'], ':');
            if ($pos !== false) {
                $dest = substr($tour['tour']['tour_regions'], $pos + 1, 2);
                if ($dest == 'vn') {
                    // $dest = Yii::t('x', 'Vietnam');
                } elseif ($dest == 'la') {
                    // $dest = Yii::t('x', 'Laos');
                } elseif ($dest == 'kh') {
                    // $dest = Yii::t('x', 'Cambodia');
                } else {
                    $dest = Yii::t('x', 'Other');
                }
            } else {
                $dest = 'Other';
            }
            $point_day = 0;
            $day_count = $tour['tourStats'] != null ? $tour['tourStats']['day_count'] : $tour['day_count'];
            if ($day_count >= 1 && $day_count <= 8) {
                $point_day = 1;
            }
            if ($day_count >= 9 && $day_count <= 12) {
                $point_day = 2;
            }
            if ($day_count >= 13 && $day_count <= 21) {
                $point_day = 3;
            }
            if ($day_count >= 22) {
                $point_day = 4;
            }
            $point_pax = 0;
            $pax_count = $tour['tourStats'] != null ? $tour['tourStats']['pax_count'] : $tour['pax'];
            if ($pax_count >= 1 && $pax_count <= 4) {
                $point_pax = 1;
            }
            if ($pax_count >= 5 && $pax_count <= 7) {
                $point_pax = 2;
            }
            if ($pax_count >= 8 && $pax_count <= 11) {
                $point_pax = 3;
            }
            if ($pax_count >= 12) {
                $point_pax = 4;
            }
            $point_dest = 0;
            $countries = '';
            if ($tour['tourStats'] != null) {
                $countries = $tour['tourStats']['countries'];
            } else if (strpos($tour['tour']['tour_regions'], ':') !== false) {
                $tour['tour']['tour_regions'] = str_replace(',', '|', $tour['tour']['tour_regions']);
                $ar_t_regions = explode('|', $tour['tour']['tour_regions']);
                $resul = [];
                foreach ($ar_t_regions as $region) {
                    if (trim($region) == '') continue;
                    $arr_g = explode(':', trim($region));
                    if (!isset($arr_g[1]) || $arr_g[1] == '') continue;
                    $resul[] = trim(substr($tour['tour']['tour_regions'], 0, 2));
                }
                $countries = implode(',', array_unique($resul));
            }
            if ( $countries == 'vn' ) {
                $point_dest = 1;
            }
            if ( strpos($countries, 'kh') !== false || strpos($countries, 'th') !== false )
            {
                $point_dest = 2;
            }
            if ( strpos($countries, 'la') !== false || strpos($countries, 'mm') !== false )
            {
                $point_dest = 3;
            }
            $point_age = 0;
            if ($pax_count >= 2 && isset($tour['bookings'][0]) && $tour['bookings'][0]['case']['stats']) {
                if ($tour['bookings'][0]['case']['stats']['group_age_2_11'] + $tour['bookings'][0]['case']['stats']['group_age_2_11'] > 1) {
                    $point_age = 1;
                }
            }
            $point_tour = $point_day + $point_pax + $point_dest + $point_age;
            if (empty($tour['tour']['cskh'])) {
                if (!isset($result[$year][$month][$dest][0])) {
                    $result[$year][$month][$dest][0] = [];
                }
                $result[$year][$month][$dest][0][$tour['id']] = $point_tour;
            } else {
                foreach ($tour['tour']['cskh'] as $cskh) {
                    $user = $cskh['id'];
                    if (!isset($result[$year][$month][$dest][$user])) {
                        $result[$year][$month][$dest][$user] = [];
                    }
                    $result[$year][$month][$dest][$user][$tour['id']] = $point_tour;

                }
            }
            if ($tour['id'] == 71464) {
                var_dump($tour);die;
            }
        }
        // \fCore::expose($result); exit;

        return $this->render('reports_qhkh-04', [
            'result'=>$result,
            'year'=>$year,
            'yearList'=>$yearList,
            'staffList'=>$staffList,
        ]);
    }

    /**
     * demo Conversion rate for B2C
     */
    public function actionMarket(
        $view = 'created',
        $year = '',
        $groupby = '',

        $month = '',

        $name = '',
        $status = '',
        $deal_status = '',
        $priority = '',
        $language = '',
        $owner_id = '',
        $cofr = '',

        $campaign_id = '',
        $company_id = '',

        $how_found = '', $how_contacted = '',
        $device = '', $site = '', $prospect = '',

        $source = '', $contacted = '', $found = '',

        $nationality = '',
        $age = '',
        $paxcount = '',
        array $req_countries = [],
        $req_countries_select = 'any',

        $req_start = '',
        $req_date = 'start',
        $req_year = '',
        $req_month = '',
        $daycount = '',
        $budget = '',
        $budget_currency = 'USD',


        $req_travel_type = '',
        $req_theme = '',
        $req_tour = '',
        $req_extension = '',
        $test = ''
    )
    {
        $indexList = [
            // 'created'=>['label'=>Yii::t('x', 'Created'), 'color'=>'#00bcd4'],
            '0'=>['label'=>Yii::t('x', '0 Star'), 'color'=>'#cdcdce'],
            '1'=>['label'=>Yii::t('x', '1 Stars'), 'color'=>'#2196f3'],
            '2'=>['label'=>Yii::t('x', '2 Stars'), 'color'=>'#4caf50'],
            '3'=>['label'=>Yii::t('x', '3 Stars'), 'color'=>'#f44336'],
            '4'=>['label'=>Yii::t('x', '4 Stars'), 'color'=>'#333'],

        ];

        for ($y = date('Y') + 10; $y >= 2007; $y--) {
            $yearList[$y] = $y;
        }
        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        $groupbyList = [
            'seller'=>Yii::t('x', 'Seller'),
            'source'=>Yii::t('x', 'Source'),
            // 'other'=>Yii::t('x', 'Other'),
        ];

        $sellerList = Yii::$app->db->createCommand('SELECT u.id, CONCAT_WS(" ", u.nickname, u.email) AS name, IF(u.status="on", "Active", "Inactive") AS status FROM users u, at_cases k WHERE k.owner_id=u.id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();

        $caseHowContactedList = [
            'web'=>'Web',
                'web/adwords'=>'Adwords',
                    'web/adwords/google'=>'Google Adwords',
                    'web/adwords/bing'=>'Bing Ads',
                    'web/adwords/other'=>'Other',
                'web/search'=>'Search',
                    'web/search/google'=>'Google search',
                    'web/search/bing'=>'Bing search',
                    'web/search/yahoo'=>'Yahoo! search',
                    'web/search/other'=>'Other',
                'web/link'=>'Referral',
                    'web/link/360'=>'Blog 360',
                    'web/link/facebook'=>'Facebook',
                    'web/link/other'=>'Other',
                'web/adonline'=>'Ad online',
                    'web/adonline/facebook'=>'Facebook',
                    'web/adonline/voyageforum'=>'VoyageForum',
                    'web/adonline/routard'=>'Routard',
                    'web/adonline/sitevietnam'=>'Site-Vietnam',
                    'web/adonline/other'=>'Other',
                'web/email'=>'Mailing',
                'web/direct'=>'Direct access',

            'nweb'=>'Non-web',
                'nweb/phone'=>'Phone',
                'nweb/email'=>'Email',
                    'nweb/email/tripconn'=>'TripConnexion',
                    'nweb/email/other'=>'Other',
                'nweb/walk-in'=>'Walk-in',
                'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

            'agent'=>'Via a tour company', // OLD?
        ];

        $kaseHowFoundList = [
            'returning'=>Yii::t('x', 'Returning customer'),
            'new'=>Yii::t('x', 'New customer'),
            'referred'=>Yii::t('x', 'Referred customer'),
                'referred/customer'=>Yii::t('x', 'Referred by one of Amica\'s customers'),
                'referred/amica'=>Yii::t('x', 'Referred by one of Amica\'s staff'),
                'referred/org'=>Yii::t('x', 'Referred by an organization or one of its members'), // Ca nhan, to chuc
                'referred/expat'=>Yii::t('x', 'Referred by an expat in Vietnam'),
                'referred/other'=>Yii::t('x', 'Referred from other source'),
        ];

        $kaseDeviceList = [
            'desktop' => Yii::t('x', 'Desktop'),
            'tablet' => Yii::t('x', 'Tablet'),
            'mobile' => Yii::t('x', 'Mobile'),
            'none'=>'None/Unknown',
        ];
        $kaseDestinationList = [
            'vn' => Yii::t('x', 'VN'),
            'la' => Yii::t('x', 'LAO'),
            'kh' => Yii::t('x', 'CAM'),
            'Myanmar' => Yii::t('x', 'Myanmar'),
        ];

        $query = Kase::find()
            ->select(['*'])
            ->andWhere(['is_b2b'=>'no'])
            ->innerJoinWith('stats');

        if ($view == 'created') {
            $query->andWhere('YEAR(created_at)=:year', [':year'=>$year]);
        } elseif ($view == 'tourstart') {
            $query->andWhere('YEAR(tour_start_date)=:year', [':year'=>$year]);
        } elseif ($view == 'tourend') {
            $query->andWhere('YEAR(tour_end_date)=:year', [':year'=>$year]);
        } else {
            $query->andWhere('YEAR(closed)=:year', [':year'=>$year]);
        }

        $theCases = $query
            ->asArray()
            ->all();

        // Get list of sellers
        $kaseSellerList = [];
        foreach ($theCases as $case) {
            if (!in_array($case['owner_id'], $kaseSellerList)) {
                $kaseSellerList[] = $case['owner_id'];
            }
        }

        // 'stype_client'=>Yii::t('x', 'Group by type of client'),
        // 'devices'=>Yii::t('x', 'Group by devices'),
        // 'destination'=>Yii::t('x', 'Group by destination'),

        $result = [];
        for ($m = 0; $m <= 12; $m ++) {
            foreach ($indexList as $index=>$item) {
                $result['total'][$year][$m][$index] = 0;
                $result['total'][$year][$m]['total'] = 0;
                $result['filtered'][$year][$m][$index] = 0;
                $result['filtered'][$year][$m]['total'] = 0;
                if ($groupby == 'source') {
                    foreach ($caseHowContactedList as $hck=>$hcn) {
                        $result['grouped-source'][$hck][$year][$m][$index] = 0;
                        $result['grouped-source'][$hck][$year][$m]['total'] = 0;
                    }
                } elseif ($groupby == 'stype_client') {
                    foreach ($kaseHowFoundList as $hfk => $hfn) {
                        $result['grouped-stype_client'][$hfk][$year][$m][$index] = 0;
                        $result['grouped-stype_client'][$hfk][$year][$m]['total'] = 0;
                    }
                } elseif ($groupby == 'devices') {
                    foreach ($kaseDeviceList as $devk => $dev) {
                        $result['grouped-devices'][$devk][$year][$m][$index] = 0;
                        $result['grouped-devices'][$devk][$year][$m]['total'] = 0;
                    }
                } elseif ($groupby == 'destination') {
                    foreach ($kaseDestinationList as $desk => $country) {
                        $result['grouped-destination'][$desk][$year][$m][$index] = 0;
                        $result['grouped-destination'][$desk][$year][$m]['total'] = 0;
                    }
                }
            }
        }
        foreach ($theCases as $case) {
            // Check conditions
            $checkConditions = true;

            // Name
            if ($checkConditions && trim($name) != '') {
                $thisCondition = strpos($case['name'], trim($name)) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Language
            if ($checkConditions && $language != '') {
                $thisCondition = $case['language'] == $language;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Priority
            if ($checkConditions && $priority != '') {
                $thisCondition = $case['is_priority'] == $priority;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Status
            if ($checkConditions && $status != '') {
                $thisCondition = $case['status'] == $status;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Deal status: NO NO

            // Seller
            if ($checkConditions && $owner_id != '') {
                if ($owner_id == 'all') {
                    $thisCondition = $case['owner_id'] !== null;
                } elseif ($owner_id == 'none') {
                    $thisCondition = $case['owner_id'] === null;
                } else {
                    $thisCondition = $case['owner_id'] == (int)$owner_id;
                }
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Consultant in France
            if ($checkConditions && $cofr != '') {
                $thisCondition = $case['cofr'] == $cofr;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Campaign
            if ($checkConditions && $campaign_id != '') {
                $thisCondition = $case['campaign_id'] == $campaign_id;
                // TODO $checkConditions = $checkConditions && $thisCondition;
            }

            // Prospect
            if ($checkConditions && $prospect != '') {
                $thisCondition = $case['prospect'] == (int)$prospect;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Device
            if ($checkConditions && $device != '') {
                $thisCondition = $case['request_device'] == $device;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Form
            if ($checkConditions && $site != '') {
                $thisCondition = $case['pa_from_site'] == $site;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // How contacted
            if ($checkConditions && $how_contacted != '') {
                $thisCondition = strpos($case['how_contacted'], $how_contacted) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // How found
            if ($checkConditions && $how_found != '') {
                $thisCondition = strpos($case['how_found'], $how_found) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Nationality
            if ($checkConditions && $nationality != '  ' && strlen($nationality) == 2) {
                $thisCondition = strpos($case['group_nationalities'], $nationality) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Age group
            $kasePaxAgeGroupList = [
                '0_1'=>'<2',
                '2_11'=>'2-11',
                '12_17'=>'12-17',
                '18_25'=>'18-25',
                '26_34'=>'26-34',
                '35_50'=>'35-50',
                '51_60'=>'51-60',
                '61_70'=>'61-70',
                '71_up'=>'>70',
            ];
            if ($checkConditions && $age != '' && in_array($age, array_keys($kasePaxAgeGroupList))) {
                $thisCondition = $case['group_age_'.$age] != 0;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Tour start or end date
            // TODO

            // Day count
            if ($checkConditions && $daycount != '') {
                $day = explode('-', $daycount);
                $day[0] = (int)$day[0];
                if (!isset($day[1])) {
                    $day[1] = (int)$day[0];
                }
                $thisCondition = $case['day_count'] != '' && $day[0] <= $case['day_count_max'] && $day[1] >= $case['day_count_min'];
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Pax count
            if ($checkConditions && $paxcount != '') {
                $pax = explode('-', $paxcount);
                $pax[0] = (int)$pax[0];
                if (!isset($pax[1])) {
                    $pax[1] = $pax[0];
                }
                $thisCondition = $case['pax_count'] != '' && $pax[0] <= $case['pax_count_max'] && $pax[1] >= $case['pax_count_min'];
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Visiting countries
            if ($checkConditions && isset($req_countries) && is_array($req_countries) && !empty($req_countries)) {
                $thisCondition = true;
                if ($req_countries_select == 'all' || $req_countries_select == 'only') {
                    foreach ($req_countries as $dest) {
                        $thisCondition = $thisCondition && strpos($case['req_countries'], $dest) !== false;
                    }
                    if ($req_countries_select == 'only') {
                        $thisCondition = $thisCondition && strlen($case['req_countries']) == 2 * count($req_countries) + count($req_countries) - 1;
                    }
                } elseif ($req_countries_select == 'any') {
                    foreach ($req_countries as $dest) {
                        $thisCondition = $thisCondition || strpos($case['req_countries'], $dest) !== false;
                    }
                } else {
                    // TODO Exact
                    // asort($req_countries);
                    // $destList = implode('|', $req_countries);
                    // $query->andWhere(['req_countries'=>$destList]);
                }

                $checkConditions = $checkConditions && $thisCondition;
            }

            // Group type
            if ($checkConditions && $req_travel_type != '') {
                $thisCondition = $case['req_travel_type'] == $req_travel_type;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Tour type
            if ($checkConditions && $req_theme != '') {
                $thisCondition = strpos($case['req_themes'], $req_theme) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Requested tours
            if ($checkConditions && $req_tour != '') {
                $thisCondition = strpos($case['req_tour'], $req_tour) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Requested extensions
            if ($checkConditions && $req_extension != '') {
                $thisCondition = strpos($case['req_extension'], $req_extension) !== false;
                $checkConditions = $checkConditions && $thisCondition;
            }

            // Created date already assigned
            if ($view == 'created') {
                $ymd = explode('-', $case['created_at']);
                $case['m'] = (int)$ymd[1];
            } elseif ($view == 'tourstart') {
                $ymd = explode('-', $case['tour_start_date']);
                $case['m'] = (int)$ymd[1];
            } elseif ($view == 'tourend') {
                $ymd = explode('-', $case['tour_end_date']);
                $case['m'] = (int)$ymd[1];
            } else {
                $ymd = explode('-', $case['closed']);
                $case['m'] = (int)$ymd[1];
            }
            // Calculating
            if ($case['stats']['prospect'] != '') {
                $index_star = (int)$case['stats']['prospect'];
            } else {
                $index_star = 0;
            }
            $result['total'][$year][$case['m']][$index_star] ++;
            $result['total'][$year][0][$index_star] ++;
            // var_dump($result['total'][$year]);die;

            $result['total'][$year][$case['m']]['total'] ++;
            $result['total'][$year][0]['total'] ++;
            if ($checkConditions) {
                $result['filtered'][$year][$case['m']][$index_star] ++;
                $result['filtered'][$year][0][$index_star] ++;
                $result['filtered'][$year][$case['m']]['total'] ++;
                $result['filtered'][$year][0]['total'] ++;

                if ($groupby == 'source') {
                    foreach ($caseHowContactedList as $hck=>$hcn) {
                        if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
                            $result['grouped-source'][$hck][$year][$case['m']][$index_star] ++;
                            $result['grouped-source'][$hck][$year][$case['m']]['total'] ++;
                            $result['grouped-source'][$hck][$year][0][$index_star] ++;
                            $result['grouped-source'][$hck][$year][0]['total'] ++;
                        }
                    }
                } elseif ($groupby == 'stype_client') {
                    foreach ($kaseHowFoundList as $hfk=>$hfn) {
                        if (substr($case['how_found'], 0, strlen($hfk)) == $hfk) {
                            $result['grouped-stype_client'][$hfk][$year][$case['m']][$index_star] ++;
                            $result['grouped-stype_client'][$hfk][$year][$case['m']]['total'] ++;
                            $result['grouped-stype_client'][$hfk][$year][0][$index_star] ++;
                            $result['grouped-stype_client'][$hfk][$year][0]['total'] ++;
                        }
                    }
                } elseif ($groupby == 'devices') {
                    foreach ($kaseDeviceList as $devk => $dev) {
                        if ($case['stats']['request_device'] == $devk) {
                            $result['grouped-devices'][$devk][$year][$case['m']][$index_star] ++;
                            $result['grouped-devices'][$devk][$year][$case['m']]['total'] ++;
                            $result['grouped-devices'][$devk][$year][0][$index_star] ++;
                            $result['grouped-devices'][$devk][$year][0]['total'] ++;
                        }
                    }
                } elseif ($groupby == 'destination') {
                    foreach ($kaseDestinationList as $desk=>$des) {
                        if ($case['stats']['req_countries'] == $desk) {
                            $result['grouped-destination'][$desk][$year][$case['m']][$index_star] ++;
                            $result['grouped-destination'][$desk][$year][$case['m']]['total'] ++;
                            $result['grouped-destination'][$desk][$year][0][$index_star] ++;
                            $result['grouped-destination'][$desk][$year][0]['total'] ++;
                        }
                    }
                }

            }
        }

        // \fCore::expose($result);
        // exit;

        // List of months
        $yearList = Yii::$app->db->createCommand('SELECT YEAR(created_at) AS y FROM at_cases GROUP BY y ORDER BY y DESC')->queryAll();
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, users u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM at_campaigns c ORDER BY c.start_dt DESC')->queryAll();
        $companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, at_companies c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();
        // var_dump($theCases);die;
        return $this->render('report_market', [
            'theCases'=>$theCases,

            'view'=>$view,
            'year'=>$year,
            'month'=>$month,

            'name'=>$name,
            'status'=>$status,
            'deal_status'=>$deal_status,
            'priority'=>$priority,
            'owner_id'=>$owner_id,
            'cofr'=>$cofr,
            'language'=>$language,

            'how_found'=>$how_found,
            'how_contacted'=>$how_contacted,

            'prospect'=>$prospect,
            'device'=>$device,
            'site'=>$site,

            'ownerList'=>$ownerList,
            'campaign_id'=>$campaign_id,
            'campaignList'=>$campaignList,
            'company_id'=>$company_id,
            'source'=>$source,

            'nationality'=>$nationality,
            'age'=>$age,
            'paxcount'=>$paxcount,
            'req_countries'=>$req_countries,
            'req_countries_select'=>$req_countries_select,
            'req_date'=>$req_date,
            'req_year'=>$req_year,
            'req_month'=>$req_month,
            'daycount'=>$daycount,
            'budget'=>$budget,
            'budget_currency'=>$budget_currency,

            'req_travel_type'=>$req_travel_type,
            'req_theme'=>$req_theme,
            'req_tour'=>$req_tour,
            'req_extension'=>$req_extension,

            'yearList'=>$yearList,
            'monthList'=>$monthList,
            'result'=>$result,
            'indexList'=>$indexList,
            'groupby'=>$groupby,
        ]);
    }
    /**
     * Conversion rate for B2C
     */
    // public function actionB2cConversionRate(
    //     $date_created = '',
    //     $date_created_custom = '',
    //     $date_assigned = '',
    //     $date_assigned_custom = '',
    //     $date_won = '',
    //     $date_won_custom = '',
    //     $date_closed = '',
    //     $date_closed_custom = '',

    //     $date_start = '',
    //     $date_start_custom = '',
    //     $date_end = '',
    //     $date_end_custom = '',

    //     $name = '',
    //     $status = '',
    //     $deal_status = '',
    //     $priority = '',
    //     $language = '',
    //     $owner_id = '',
    //     $cofr = '',
    //     $pv = '',

    //     $campaign_id = '',
    //     $company_id = '',

    //     $how_found = '', $how_contacted = '',
    //     $device = '', $site = '',
    //     $kx = '', $tx = '',
    //     $prospect = '',

    //     $source = '', $contacted = '', $found = '',

    //     $nationality = '',
    //     $age = '',
    //     $paxcount = '',
    //     array $req_countries = [],
    //     $req_countries_select = 'any',

    //     $req_start = '',
    //     $req_date = 'start',
    //     // $req_year = '',
    //     // $req_month = '',
    //     $daycount = '',
    //     $budget = '',
    //     $budget_currency = 'USD',

    //     $req_travel_type = '',
    //     $req_theme = '',
    //     $req_tour = '',
    //     $req_extension = '',

    //     $year = '',
    //     $month = '',
    //     $view = 'created',
    //     $groupby = '',
    //     $test = '',
    //     $display_table = 'date_case_created'
    // )
    // {
    //     $indexList = [
    //         // 'created'=>['label'=>Yii::t('x', 'Created'), 'color'=>'#00bcd4'],
    //         'pending'=>['label'=>Yii::t('x', 'Pending'), 'color'=>'#2196f3'],
    //         'won'=>['label'=>Yii::t('x', 'Won'), 'color'=>'#4caf50'],
    //         'lost'=>['label'=>Yii::t('x', 'Lost'), 'color'=>'#f44336'],
    //         'total'=>['label'=>Yii::t('x', 'Total'), 'color'=>'none'],
    //     ];

    //     for ($y = date('Y') + 10; $y >= 2007; $y--) {
    //         $yearList[$y] = $y;
    //     }
    //     $minYear = $maxYear = date('Y');
    //     // if (strpos()) {

    //     // }
    //     if (!in_array($year, $yearList)) {
    //         $year = date('Y');
    //     }

    //     $groupbyList = [
    //         'seller'=>Yii::t('x', 'Seller'),
    //         'source'=>Yii::t('x', 'Source'),
    //         // 'other'=>Yii::t('x', 'Other'),
    //     ];

    //     $sellerList = Yii::$app->db->createCommand('SELECT u.id, CONCAT_WS(" ", u.nickname, u.email) AS name, IF(u.status="on", "Active", "Inactive") AS status FROM users u, at_cases k WHERE k.owner_id=u.id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();

    //     $caseHowContactedList = [
    //         'web'=>'Web',
    //             'web/adwords'=>'Adwords',
    //                 'web/adwords/google'=>'Google Adwords',
    //                 'web/adwords/bing'=>'Bing Ads',
    //                 'web/adwords/other'=>'Other',
    //             'web/search'=>'Search',
    //                 'web/search/google'=>'Google search',
    //                 'web/search/bing'=>'Bing search',
    //                 'web/search/yahoo'=>'Yahoo! search',
    //                 'web/search/other'=>'Other',
    //             'web/link'=>'Referral',
    //                 'web/link/360'=>'Blog 360',
    //                 'web/link/facebook'=>'Facebook',
    //                 'web/link/other'=>'Other',
    //             'web/adonline'=>'Ad online',
    //                 'web/adonline/facebook'=>'Facebook',
    //                 'web/adonline/voyageforum'=>'VoyageForum',
    //                 'web/adonline/routard'=>'Routard',
    //                 'web/adonline/sitevietnam'=>'Site-Vietnam',
    //                 'web/adonline/other'=>'Other',
    //             'web/email'=>'Mailing',
    //             'web/direct'=>'Direct access',

    //         'nweb'=>'Non-web',
    //             'nweb/phone'=>'Phone',
    //             'nweb/email'=>'Email',
    //                 'nweb/email/tripconn'=>'TripConnexion',
    //                 'nweb/email/other'=>'Other',
    //             'nweb/walk-in'=>'Walk-in',
    //             'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

    //         'agent'=>'Via a tour company', // OLD?
    //     ];

    //     $kaseHowFoundList = [
    //         'returning'=>Yii::t('x', 'Returning customer'),
    //         'new'=>Yii::t('x', 'New customer'),
    //         'referred'=>Yii::t('x', 'Referred customer'),
    //             'referred/customer'=>Yii::t('x', 'Referred by one of Amica\'s customers'),
    //             'referred/amica'=>Yii::t('x', 'Referred by one of Amica\'s staff'),
    //             'referred/org'=>Yii::t('x', 'Referred by an organization or one of its members'), // Ca nhan, to chuc
    //             'referred/expat'=>Yii::t('x', 'Referred by an expat in Vietnam'),
    //             'referred/other'=>Yii::t('x', 'Referred from other source'),
    //     ];
    //     $sql_clause = 'created_at';
    //     if ($display_table == 'date_case_created') {
    //         $sql_clause = 'created_at';
    //     }
    //     if ($display_table == 'date_case_assigned') {
    //         $sql_clause = 'ao';
    //     }
    //     if ($display_table == 'date_case_won') {
    //         $sql_clause = 'deal_status_date';
    //     }
    //     if ($display_table == 'date_case_closed') {
    //         $sql_clause = 'closed';
    //     }
    //     if ($display_table == 'date_tour_start') {
    //         $sql_clause = 'tour_start_date';
    //     }
    //     if ($display_table == 'date_tour_end') {
    //         $sql_clause = 'tour_end_date';
    //     }
    //     $query = Kase::find()
    //         ->select(['at_cases.id', 'name', 'at_cases.status', 'ref', 'is_priority', 'deal_status', 'deal_status_date', 'opened', 'owner_id', 'at_cases.created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed', 'closed_note', 'tour_start_date', 'tour_end_date', 'created_at_vn'=>new \yii\db\Expression('DATE_ADD(at_cases.created_at, INTERVAL 7 HOUR)')])
    //         ->where(['is_b2b'=>'no'])
    //         ->innerJoinWith('stats');

    //     if (in_array($prospect, [1,2,3,4,5]) || $site != '' || $device != '') {
    //         $cond = [];
    //         if ($prospect != '') {
    //             $cond['prospect'] = $prospect;
    //         }
    //         if ($site != '') {
    //             $cond['pa_from_site'] = $site;
    //         }
    //         if ($device != '') {
    //             $cond['request_device'] = $device;
    //         }
    //         $query->andWhere($cond);
    //     }

    //     // if ($date1from != '' && $date1until != '') {
    //         // if ($view == 'created') {
    //         //     $dateField = 'created_at';
    //         // } elseif ($view == 'assigned') {
    //         //     $dateField = 'ao';
    //         // } else {
    //         //     $dateField = 'closed';
    //         // }
    //         // if ($view == 'created') {
    //         //     $query->andHaving('(created_at_vn>=:d1f AND created_at_vn<=:d1u)', [':d1f'=>$date1from.' 00:00:00', ':d1u'=>$date1until.' 23:59:59']);
    //         // } elseif ($view == 'won') {
    //         //     // TODO khi ho so co nhieu booking WON thi co the bi loi
    //         //     $query->select(['b.status_dt', 'at_cases.id', 'name', 'at_cases.status', 'ref', 'is_priority', 'deal_status', 'opened', 'owner_id', 'at_cases.created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed', 'closed_note', 'created_at_vn'=>new \yii\db\Expression('DATE_ADD(at_cases.created_at, INTERVAL 7 HOUR)')]);
    //         //     $query->andWhere(['deal_status'=>'won']);
    //         //     $query->innerJoinWith('bookings b')->onCondition(['b.status'=>'won']);
    //         //     $query->andWhere('status_dt>=:d1f AND status_dt<=:d1u', [':d1f'=>$date1from.' 00:00:00', ':d1u'=>$date1until.' 23:59:59']);
    //         // } else {
    //         //     $query->andWhere($dateField.'>=:d1f AND '.$dateField.'<=:d1u', [':d1f'=>$date1from, ':d1u'=>$date1until]);
    //         // }
    //     // }

    //     // Dates
    //     $len1 = strlen($date_created);
    //     $len1c = strlen($date_created_custom);
    //     if ($len1 == 4 || $len1 == 7 || $len1 == 10) {
    //         // yyyy OR yyyy-mm OR yyyy-mm-dd
    //         $query->andWhere('SUBSTRING(created_at, 1, '.$len1.')=:date1', [':date1'=>$date_created]);
    //     } elseif ($date_created == 'custom' && $len1c == 24 && strpos($date_created_custom, ' -- ') !== false) {
    //         // yyyy-mm-dd -- yyyy-mm-dd
    //         $date1 = explode(' -- ', $date_created_custom);
    //         $query->andWhere('created_at>=:date1from AND created_at<=:date1until', [':date1from'=>$date1[0].' 00:00:00', ':date1until'=>$date1[1].' 23:59:59']);
    //     }

    //     $len2 = strlen($date_assigned);
    //     $len2c = strlen($date_assigned_custom);
    //     if ($len2 == 4 || $len2 == 7 || $len2 == 10) {
    //         $query->andWhere('SUBSTRING(ao, 1, '.$len2.')=:date2', [':date2'=>$date_assigned]);
    //     } elseif ($date_assigned == 'custom' && $len2c == 24 && strpos($date_assigned_custom, ' -- ') !== false) {
    //         // yyyy-mm-dd -- yyyy-mm-dd
    //         $date2 = explode(' -- ', $date_assigned_custom);
    //         $query->andWhere('ao>=:date2from AND ao<=:date2until', [':date2from'=>$date2[0], ':date2until'=>$date2[1]]);
    //     }

    //     $len3 = strlen($date_won);
    //     $len3c = strlen($date_won_custom);
    //     if ($len3 == 4 || $len3 == 7 || $len3 == 10) {
    //         $query->andWhere(['deal_status'=>'won']);
    //         $query->andWhere('SUBSTRING(deal_status_date, 1, '.$len3.')=:date3', [':date3'=>$date_won]);
    //     } elseif ($date_won == 'custom' && $len3c == 24 && strpos($date_won_custom, ' -- ') !== false) {
    //         // yyyy-mm-dd -- yyyy-mm-dd
    //         $query->andWhere(['deal_status_date'=>'won']);
    //         $date3 = explode(' -- ', $date_won_custom);
    //         $query->andWhere('deal_status_date>=:date3from AND deal_status_date<=:date3until', [':date3from'=>$date3[0], ':date3until'=>$date3[1]]);
    //     }

    //     $len4 = strlen($date_closed);
    //     $len4c = strlen($date_closed_custom);
    //     if ($len4 == 4 || $len4 == 7 || $len4 == 10) {
    //         $query->andWhere('SUBSTRING(closed, 1, '.$len4.')=:date4', [':date4'=>$date_closed]);
    //     } elseif ($date_closed == 'custom' && $len4c == 24 && strpos($date_closed_custom, ' -- ') !== false) {
    //         // yyyy-mm-dd -- yyyy-mm-dd
    //         $date4 = explode(' -- ', $date_closed_custom);
    //         $query->andWhere(['status'=>'closed']);
    //         $query->andWhere('closed>=:date4from AND closed<=:date4until', [':date4from'=>$date4[0], ':date4until'=>$date4[1]]);
    //     }

    //     $len5 = strlen($date_start);
    //     $len5c = strlen($date_start_custom);
    //     if ($len5 == 4 || $len5 == 7) {
    //         $query->andWhere('SUBSTRING(tour_start_date, 1, '.$len5.')=:date5', [':date5'=>$date_start]);
    //     } elseif ($date_start == 'custom' && $len5c == 24 && strpos($date_start_custom, ' -- ') !== false) {
    //         // yyyy-mm-dd -- yyyy-mm-dd
    //         $date5 = explode(' -- ', $date_start_custom);
    //         $query->andWhere('tour_start_date>=:date5from AND tour_start_date<=:date5until', [':date5from'=>$date5[0], ':date5until'=>$date5[1]]);
    //     }

    //     $len6 = strlen($date_end);
    //     if ($len6 == 4 || $len6 == 7) {
    //         $query->andWhere('SUBSTRING(tour_end_date, 1, '.$len6.')=:date6', [':date6'=>$date_end]);
    //     }

    //     // if ($allocated != '') {
    //     //     $query->andWhere('ao>=:d1f AND ao<=:d1u', [':d1f'=>$date1from, ':d1u'=>$date1until]);
    //     // }

    //     if ($name != '') {
    //         $query->andWhere(['like', 'name', $name]);
    //     }
    //     if ($status != '') {
    //         $query->andWhere(['status'=>$status]);
    //     }
    //     if ($deal_status != '') {
    //         $query->andWhere(['deal_status'=>$deal_status]);
    //     }
    //     if ($priority != '') {
    //         $query->andWhere(['priority'=>$priority]);
    //     }
    //     if ($language != '') {
    //         $query->andWhere(['language'=>$language]);
    //     }
    //     if ($owner_id == 'none') {
    //         $query->andWhere('owner_id IS NULL');
    //     } elseif ($owner_id == 'all') {
    //         $query->andWhere('owner_id IS NOT NULL');
    //     } elseif ($owner_id != '') {
    //         if (substr($owner_id, 0, 5) == 'cofr-') {
    //             $query->andWhere(['cofr'=>(int)substr($owner_id, 5)]);
    //         } else {
    //             $query->andWhere(['owner_id'=>(int)$owner_id]);
    //         }
    //     }
    //     if ($cofr != '') {
    //         $query->andWhere(['cofr'=>(int)$cofr]);
    //     }
    //     if ($campaign_id == 'yes') {
    //         $query->andWhere('campaign_id!=0');
    //     } else {
    //         if ($campaign_id != '') {
    //             $query->andWhere(['campaign_id'=>$campaign_id]);
    //         }
    //     }

    //     if ($how_found != '') {
    //         $query->andWhere('LOCATE(:found, how_found)=1', [':found'=>$how_found]);
    //     }
    //     if ($how_contacted == 'unknown') {
    //         $query->andWhere(['how_contacted'=>'']);
    //     } else {
    //         if ($how_contacted != '') {
    //             if ($how_contacted == 'web-direct') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
    //             } elseif ($how_contacted == 'link') {
    //                 $query->andWhere(['web_referral'=>'link']);
    //             } elseif ($how_contacted == 'social') {
    //                 $query->andWhere(['web_referral'=>'social']);
    //             } elseif ($how_contacted == 'web-search') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
    //             } elseif ($how_contacted == 'web-search-amica') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
    //             } elseif ($how_contacted == 'web-adsense') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
    //             } elseif ($how_contacted == 'web-bingad') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
    //             } elseif ($how_contacted == 'web-otherad') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
    //             } elseif ($how_contacted == 'web-adwords') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
    //             } elseif ($how_contacted == 'web-adwords-amica') {
    //                 $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
    //             } elseif ($how_contacted == 'web-trip-connexion') {
    //                 $query->andWhere(['web_referral'=>'ad/trip-connexion']);
    //             } else {
    //                 $query->andWhere('LOCATE(:hc, how_contacted)=1', [':hc'=>$how_contacted]);
    //             }
    //         }
    //     }

    //     if ($paxcount != '') {
    //         $pax = explode('-', $paxcount);
    //         $pax[0] = (int)$pax[0];
    //         if (!isset($pax[1])) {
    //             $pax[1] = $pax[0];
    //         }
    //         $query->andWhere(['!=', 'pax_count', '']);
    //         $query->andWhere('pax_count_min<=:max AND pax_count_min>=:min', [':min'=>$pax[0], ':max'=>$pax[1]]);
    //     }

    //     if ($daycount != '') {
    //         $day = explode('-', $daycount);
    //         $day[0] = (int)$day[0];
    //         if (!isset($day[1])) {
    //             $day[1] = $day[0];
    //         }
    //         $query->andWhere(['!=', 'day_count', '']);
    //         $query->andWhere('day_count_min<=:max AND day_count_min>=:min', [':min'=>$day[0], ':max'=>$day[1]]);
    //     }

    //     if (isset($req_countries) && is_array($req_countries) && !empty($req_countries)) {
    //         if ($req_countries_select == 'all' || $req_countries_select == 'only') {
    //             foreach ($req_countries as $dest) {
    //                 $query->andWhere('LOCATE("'.$dest.'", req_countries)!=0');//, [':dest'=>$dest]);
    //             }
    //             if ($req_countries_select == 'only') {
    //                 $query->andWhere('LENGTH(req_countries)=:len', [':len'=> 2 * count($req_countries) + count($req_countries) - 1]);//, [':dest'=>$dest]);
    //             }
    //         } elseif ($req_countries_select == 'any') {
    //             $orConditions = '(';
    //             foreach ($req_countries as $dest) {
    //                 if ($orConditions != '(') {
    //                     $orConditions .= ' OR ';
    //                 }
    //                 $orConditions .= 'LOCATE("'.$dest.'", req_countries)!=0';
    //             }
    //             $orConditions .= ')';
    //             $query->andWhere($orConditions);
    //         } else {
    //             // Exact
    //             asort($req_countries);
    //             $destList = implode('|', $req_countries);
    //             $query->andWhere(['req_countries'=>$destList]);
    //         }
    //     }

    //     $paxAgeGroupList = [
    //         '0_1'=>'<2',
    //         '2_11'=>'2-11',
    //         '12_17'=>'12-17',
    //         '18_25'=>'18-25',
    //         '26_34'=>'26-34',
    //         '35_50'=>'35-50',
    //         '51_60'=>'51-60',
    //         '61_70'=>'61-70',
    //         '71_up'=>'>70',
    //     ];
    //     if ($age != '' && in_array($age, array_keys($paxAgeGroupList))) {
    //         $query->andWhere('group_age_'.$age.'!=0');
    //     }

    //     if ($nationality != '  ' && strlen($nationality) == 2) {
    //         $query->andWhere('LOCATE(:n, group_nationalities)!=0', [':n'=>$nationality]);
    //     }

    //     if ($req_travel_type != '') {
    //         $query->andWhere(['req_travel_type'=>$req_travel_type]);
    //     }
    //     if ($req_theme != '') {
    //         $query->andWhere('LOCATE(:n, req_themes)!=0', [':n'=>$req_theme]);
    //     }
    //     if ($req_tour != '') {
    //         $query->andWhere('LOCATE(:n, req_tour)!=0', [':n'=>$req_tour]);
    //     }
    //     if ($req_extension != '') {
    //         $query->andWhere('LOCATE(:n, req_extensions)!=0', [':n'=>$req_extension]);
    //     }

    //     if ($kx == 'k0') {
    //         $query->andWhere(['kx'=>'']);
    //     } elseif ($kx == 'k17') {
    //         $query->andWhere('kx!="" AND kx!="k8"');
    //     } elseif ($kx != '') {
    //         $query->andWhere(['kx'=>$kx]);
    //     }

    //     // Visiting countries
    //     // if ($req_countries != '') {
    //     //     $reqCountryList = explode(',', $req_countries);
    //     //     foreach ($reqCountryList as $reqCountry) {
    //     //         $query->andWhere('LOCATE(:c, req_countries)!=0', [':c'=>$reqCountry]);
    //     //     }
    //     // }

    //     // $countQuery = clone $query;
    //     // $pagination = new Pagination([
    //     //     'totalCount' => $countQuery->count(),
    //     //     'pageSize'=>USER_ID == 1 && isset($_GET['update-kx']) ? 100 : 25,
    //     // ]);

    //     $theCases = $query
    //         ->with([
    //             'stats',
    //             'owner'=>function($query) {
    //                 return $query->select(['id', 'nickname', 'image']);
    //             },
    //             'referrer'=>function($query) {
    //                 return $query->select(['id', 'name', 'is_client']);
    //             },
    //             'company'=>function($query) {
    //                 return $query->select(['id', 'name']);
    //             },
    //             ])
    //         ->orderBy(['created_at' => SORT_DESC])
    //         ->limit(5000)
    //         ->asArray()
    //         ->all();

    //     // Get list of sellers
    //     $kaseSellerList = [];
    //     $arr_years = [];
    //     foreach ($theCases as $case) {
    //         if (!isset($case[$sql_clause])) {
    //             die('not exist field ('.$sql_clause.')');
    //         }
    //         $ymd = explode('-', $case[$sql_clause]);
    //         if (empty($ymd)) {
    //             die('date is empty!!!');
    //         }
    //         if (in_array($ymd[0], $arr_years)) {
    //             continue;
    //         }
    //         $arr_years[] = $ymd[0];
    //         if (!in_array($case['owner_id'], $kaseSellerList)) {
    //             $kaseSellerList[] = $case['owner_id'];
    //         }
    //     }
    //     $arr_years = array_unique($arr_years);
    //     $result = [];
    //     if (!empty($arr_years)) {
    //         foreach ($arr_years as $yr) {
    //             for ($m = 0; $m <= 12; $m ++) {
    //                 foreach ($indexList as $index=>$item) {
    //                     $result['total'][$yr][$m][$index] = 0;
    //                     $result['filtered'][$yr][$m][$index] = 0;
    //                     if ($groupby == 'source') {
    //                         foreach ($caseHowContactedList as $hck=>$hcn) {
    //                             $result['grouped-source'][$hck][$yr][$m][$index] = 0;
    //                         }
    //                     } elseif ($groupby == 'seller') {
    //                         foreach ($kaseSellerList as $sid) {
    //                             $result['grouped-seller'][$sid][$yr][$m][$index] = 0;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     foreach ($theCases as $case) {

    //         // Check conditions
    //         $checkConditions = true;

    //         // Name
    //         // if ($checkConditions && trim($name) != '') {
    //         //     $thisCondition = strpos($case['name'], trim($name)) !== false;
    //         //     $checkConditions = $checkConditions && $thisCondition;
    //         // }

    //         $checkConditions = true; // TODO HUAN

    //         // Created date already assigned
    //         if ($view == 'created') {
    //             $ymd = explode('-', $case['created_at']);
    //             $case['m'] = (int)$ymd[1];
    //         } elseif ($view == 'tourstart') {
    //             $ymd = explode('-', $case['tour_start_date']);
    //             $case['m'] = (int)$ymd[1];
    //         } elseif ($view == 'tourend') {
    //             $ymd = explode('-', $case['tour_end_date']);
    //             $case['m'] = (int)$ymd[1];
    //         } else {
    //             $ymd = explode('-', $case['closed']);
    //             $case['m'] = (int)$ymd[1];
    //         }
    //         $ymd = explode('-', $case[$sql_clause]);
    //         if (empty($ymd)) {
    //             var_dump($case);die;
    //         }
    //         $case['yr'] = $ymd[0];
    //         $case['m'] = (int)$ymd[1];

    //         // Calculating
    //         if ($case['deal_status'] == 'won' || $case['deal_status'] == 'lost') {
    //             $result['total'][$case['yr']][$case['m']][$case['deal_status']] ++;
    //             $result['total'][$case['yr']][0][$case['deal_status']] ++;
    //             if ($checkConditions) {
    //                 $result['filtered'][$case['yr']][$case['m']][$case['deal_status']] ++;
    //                 $result['filtered'][$case['yr']][0][$case['deal_status']] ++;
    //                 if ($groupby == 'source') {
    //                     foreach ($caseHowContactedList as $hck=>$hcn) {
    //                         if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
    //                             $result['grouped-source'][$hck][$case['yr']][$case['m']][$case['deal_status']] ++;
    //                             $result['grouped-source'][$hck][$case['yr']][0][$case['deal_status']] ++;
    //                         }
    //                     }
    //                 } elseif ($groupby == 'seller') {
    //                     foreach ($kaseSellerList as $sid) {
    //                         if ($case['owner_id'] == $sid) {
    //                             $result['grouped-seller'][$sid][$case['yr']][$case['m']][$case['deal_status']] ++;
    //                             $result['grouped-seller'][$sid][$case['yr']][0][$case['deal_status']] ++;
    //                         }
    //                     }
    //                 }
    //             }
    //         } else {
    //             $result['total'][$case['yr']][$case['m']]['pending'] ++;
    //             $result['total'][$case['yr']][0]['pending'] ++;
    //             if ($checkConditions) {
    //                 $result['filtered'][$case['yr']][$case['m']]['pending'] ++;
    //                 $result['filtered'][$case['yr']][0]['pending'] ++;
    //                 if ($groupby == 'source') {
    //                     foreach ($caseHowContactedList as $hck=>$hcn) {
    //                         if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
    //                             $result['grouped-source'][$hck][$case['yr']][$case['m']]['pending'] ++;
    //                             $result['grouped-source'][$hck][$case['yr']][0]['pending'] ++;
    //                         }
    //                     }
    //                 } elseif ($groupby == 'seller') {
    //                     foreach ($kaseSellerList as $sid) {
    //                         if ($case['owner_id'] == $sid) {
    //                             $result['grouped-seller'][$sid][$case['yr']][$case['m']][$case['deal_status']] ++;
    //                             $result['grouped-seller'][$sid][$case['yr']][0][$case['deal_status']] ++;
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         $result['total'][$case['yr']][$case['m']]['total'] ++;
    //         $result['total'][$case['yr']][0]['total'] ++;

    //         if ($checkConditions) {
    //             $result['filtered'][$case['yr']][$case['m']]['total'] ++;
    //             $result['filtered'][$case['yr']][0]['total'] ++;

    //             if ($groupby == 'source') {
    //                 foreach ($caseHowContactedList as $hck=>$hcn) {
    //                     if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
    //                         $result['grouped-source'][$hck][$case['yr']][$case['m']]['total'] ++;
    //                         $result['grouped-source'][$hck][$case['yr']][0]['total'] ++;
    //                     }
    //                 }
    //             } elseif ($groupby == 'seller') {
    //                 foreach ($kaseSellerList as $sid) {
    //                     if ($case['owner_id'] == $sid) {
    //                         $result['grouped-seller'][$sid][$case['yr']][$case['m']]['total'] ++;
    //                         $result['grouped-seller'][$sid][$case['yr']][0]['total'] ++;
    //                     }
    //                 }
    //             }

    //         }
    //     }

    //     // \fCore::expose($result);
    //     // exit;

    //     // List of months
    //     $yearList = Yii::$app->db->createCommand('SELECT YEAR(created_at) AS y FROM at_cases GROUP BY y ORDER BY y DESC')->queryAll();
    //     for ($m = 1; $m <= 12; $m ++) {
    //         $monthList[$m] = $m;
    //     }
    //     $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email, u.status FROM at_cases c, users u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
    //     $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM campaigns c ORDER BY c.start_dt DESC')->queryAll();
    //     $companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, at_companies c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();
    //     // var_dump($tour);die;
    //     // var_dump($result['total']);die('ok');
    //     return $this->render('report_b2c-conversion-rate', [
    //         'theCases'=>$theCases,
    //         'view'=>$view,
    //         'years'=>$arr_years,
    //         'month'=>$month,

    //         'name'=>$name,
    //         'status'=>$status,
    //         'deal_status'=>$deal_status,
    //         'priority'=>$priority,
    //         'owner_id'=>$owner_id,
    //         'cofr'=>$cofr,
    //         'pv'=>$pv,
    //         'language'=>$language,

    //         'how_found'=>$how_found,
    //         'how_contacted'=>$how_contacted,

    //         'prospect'=>$prospect,
    //         'device'=>$device,
    //         'site'=>$site,
    //         'kx'=>$kx,
    //         'tx'=>$tx,

    //         'ownerList'=>$ownerList,
    //         'campaign_id'=>$campaign_id,
    //         'campaignList'=>$campaignList,
    //         'company_id'=>$company_id,
    //         'source'=>$source,

    //         'nationality'=>$nationality,
    //         'age'=>$age,
    //         'paxcount'=>$paxcount,
    //         'req_countries'=>$req_countries,
    //         'req_countries_select'=>$req_countries_select,
    //         'req_date'=>$req_date,

    //         'daycount'=>$daycount,
    //         'budget'=>$budget,
    //         'budget_currency'=>$budget_currency,

    //         'req_travel_type'=>$req_travel_type,
    //         'req_theme'=>$req_theme,
    //         'req_tour'=>$req_tour,
    //         'req_extension'=>$req_extension,

    //         'yearList'=>$yearList,
    //         'monthList'=>$monthList,

    //         'date_created'=>$date_created,
    //         'date_created_custom'=>$date_created_custom,
    //         'date_assigned'=>$date_assigned,
    //         'date_assigned_custom'=>$date_assigned_custom,
    //         'date_won'=>$date_won,
    //         'date_won_custom'=>$date_won_custom,
    //         'date_closed'=>$date_closed,
    //         'date_closed_custom'=>$date_closed_custom,

    //         'date_start'=>$date_start,
    //         'date_start_custom'=>$date_start_custom,
    //         'date_end'=>$date_end,
    //         'date_end_custom'=>$date_end_custom,

    //         'yearList'=>$yearList,
    //         'monthList'=>$monthList,
    //         'result'=>$result,
    //         'indexList'=>$indexList,
    //         'groupby'=>$groupby,

    //         'display_table' => $display_table
    //     ]);
    // }

    // Pax who did
    public function actionPaxWhoDid($visit = '', $notvisit = '', $year = 2016) {
        // Tours which went to Laos
        $sql = 'SELECT * FROM at_tour_stats WHERE LOCATE("la", countries)!=0';
        $toursWhich['went to Laos'] = Yii::$app->db->createCommand($sql)->queryAll();
        // \fCore::expose($toursWhich['went to Laos']); exit;
        $tourIdList = [];
        foreach ($toursWhich['went to Laos'] as $tour) {
            $tourIdList[] = $tour['tour_id'];
        }
        // Tours and pax that went to Laos
        $toursLaos = Product::find()
            ->select(['id'])
            ->with([
                'bookings.pax'=>function($q) {
                    return $q->select(['id']);
                }
                ])
            ->where('op_finish!="canceled"')
            ->andWhere(['id'=>$tourIdList])
            ->asArray()
            ->all();
        $paxIdList = [];
        foreach ($toursLaos as $tour) {
            foreach ($tour['bookings'] as $booking) {
                foreach ($booking['pax'] as $pax) {
                    $paxIdList[] = $pax['id'];
                }
            }
        }

        // Bookings of tours which did not go to Laos
        $tours = Product::find()
            ->select(['id', 'op_code'])
            ->with([
                'bookings.pax'=>function($q) use ($paxIdList) {
                    return $q->select(['id', 'fname', 'lname', 'gender', 'country_code', 'email', 'byear'])
                        ->where('email!=""')
                        ->andWhere(['not', ['id'=>$paxIdList]]);
                }
                ])
            ->where('op_finish!="canceled"')
            ->andWhere(['not', ['id'=>$tourIdList]])
            ->andWhere('YEAR(day_from)=:year', [':year'=>$year])
            ->orderBy('day_from')
            ->asArray()
            ->all();

        return $this->render('report_pax-who-did', [
            'tours'=>$tours,
            'year'=>$year,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('reports_index');
    }

    public function actionCase_open($case_type = 'month_open')
    {
        $getProspect = Yii::$app->request->get('prospect', 'all');
        $getDevice = Yii::$app->request->get('device', 'all');
        $getSite = Yii::$app->request->get('site', 'all');
        $getDestinations = Yii::$app->request->get('destination', '');
        $contacted = Yii::$app->request->get('contacted', '');
        $getNumberDay = Yii::$app->request->get('number_day', '');
        $getOwnerId = Yii::$app->request->get('owner_id', 'all');
        $found = Yii::$app->request->get('found', '');
        $getLanguage = Yii::$app->request->get('language', 'all');
        $getCampaignId = Yii::$app->request->get('campaign_id', 'all');
        $getNumberPax = Yii::$app->request->get('number_pax', '');
        $getDestSelect = Yii::$app->request->get('destselect', 'all');

        $query = Kase::find()
                ->where('is_b2b = "no"')
                ->leftJoin('at_case_stats', 'at_cases.id = at_case_stats.case_id');
        if ($case_type == 'month_end') {
            $query
                ->select(['*',
                'YEAR(DATE_ADD(pa_start_date, INTERVAL
                    CEILING(CASE
                    WHEN day_count_min >= 0 AND day_count_max > 0 THEN (day_count_min + day_count_max)/2
                    WHEN day_count > 0 THEN day_count
                    ELSE day_count_min END) DAY)) AS y',
                'MONTH(DATE_ADD(pa_start_date, INTERVAL
                    CEILING(CASE
                    WHEN day_count_min >= 0 AND day_count_max > 0 THEN (day_count_min + day_count_max)/2
                    WHEN day_count > 0 THEN day_count
                    ELSE day_count_min END) DAY)) AS m',
                ])
                ->andWhere('YEAR(pa_start_date) >= 2016 AND YEAR(pa_start_date) <= 2021');
        }
        if ($case_type == 'month_start') {
            $query->select(['*',
                    'SUBSTRING(pa_start_date, 1, 4) AS y',
                    '
                    CASE
                        WHEN LENGTH(CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(pa_start_date,"-",2),"-",-1),SIGNED INTEGER)) = 1
                            OR LENGTH(CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(pa_start_date,"-",2),"-",-1),SIGNED INTEGER)) = 2
                            THEN CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(pa_start_date,"-",2),"-",-1),SIGNED INTEGER)
                    END AS m'
                    ])
            ->andWhere('SUBSTRING(pa_start_date, 1, 4) in ("2016","2017","2018", "2019", "2020")');
        }
        if (in_array($getProspect, [1,2,3,4,5])){
            if ($getProspect != 'all') {
                $query->andWhere(['prospect' => $getProspect]);
            }
        }
        if ($getSite != 'all') {
                $query->andWhere(['pa_from_site' => $getSite]);
            }
        if ($getDevice != 'all') {
            $query->andWhere('request_device=:device',[':device' => $getDevice]);
        }
        if ($getOwnerId != 'all') {
            if (substr($getOwnerId, 0, 5) == 'cofr-') {
                $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
            } else {
                $query->andWhere(['owner_id'=>(int)$getOwnerId]);
            }
        }
        if (isset($getDestinations) && !empty($getDestinations)) {
            if ($getDestSelect == 'any') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->orWhere(['like', 'req_countries', $des]);

                }
            }
            if ($getDestSelect == 'all') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->andWhere(['like', 'req_countries', $des]);

                }
            }
            if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->andWhere(['like', 'req_countries', $des]);

                }
            }
        }
        if ($getLanguage != 'all') $query->andWhere(['language'=>$getLanguage]);
        if ($contacted != '') {
            if ($contacted == 'web-direct') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
            } elseif ($contacted == 'link') {
                $query->andWhere(['web_referral'=>'link']);
            } elseif ($contacted == 'social') {
                $query->andWhere(['web_referral'=>'social']);
            } elseif ($contacted == 'web-search') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
            } elseif ($contacted == 'web-search-amica') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
            } elseif ($contacted == 'web-adsense') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
            } elseif ($contacted == 'web-bingad') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
            } elseif ($contacted == 'web-otherad') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
            } elseif ($contacted == 'web-adwords') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
            } elseif ($contacted == 'web-adwords-amica') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
            } elseif ($contacted == 'web-trip-connexion') {
                $query->andWhere(['web_referral'=>'ad/trip-connexion']);
            } else {
                $query->andWhere(['how_contacted'=>$contacted]);
            }
        }
        if ($getNumberDay != '') {
            $arr_s = explode('-', $getNumberDay);
            if (count($arr_s) == 1) {
                $arr_s[] = $arr_s[0];
            }
            switch (count($arr_s)) {
                case 2:
                    $query->andWhere('
                        CASE
                            WHEN day_count_max > 0
                                THEN day_count_max >= :minDay AND day_count_min <= :maxDay
                            ELSE
                                day_count_min >=:minDay AND day_count_min <=:maxDay
                        END', [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                    break;
            }
        }
        if ($getCampaignId == 'yes') {
            $query->andWhere('campaign_id!=0');
        } else {
            if ($getCampaignId != 'all') $query->andWhere(['campaign_id'=>$getCampaignId]);
        }
        if ($getNumberPax != '') {
                $arr_s = explode('-', $getNumberPax);
                if (count($arr_s) == 1) {
                    $arr_s[] = $arr_s[0];
                }
                switch (count($arr_s)) {
                    case 1:
                        $query->andWhere('
                            CASE
                                WHEN pax_count_max > 0
                                    THEN pax_count_max >= :numPax AND pax_count_min <= :numPax
                                ELSE pax_count_min =:numPax
                            END', [':numPax' => $getNumberPax]);
                        break;
                    case 2:
                        $s_pax = explode('-', $getNumberPax);
                        $query->andWhere('
                            CASE
                                WHEN pax_count_max > 0
                                    THEN
                                        pax_count_max >= :minPax AND pax_count_min <= :maxPax
                                ELSE
                                    pax_count_min >=:minPax AND pax_count_min <=:maxPax
                            END', [':minPax' => $arr_s[0], ':maxPax' => $arr_s[1]]);
                        break;
                }
            }
        if ($found != '') {
            $query->andWhere(['how_found'=>$found]);
        }
        $totalCases = [];
        $oldCasesWon = [];
        $caseFail = [];
        $casePending = [];
        if ($case_type == 'month_open') {
            $cases = $query->asArray()->all();
            if (!$cases) {
                throw new HttpException(403,"Not found any case");
            }
            foreach ($cases as $case) {
                $y = intVal(date('Y',strtotime($case['created_at'])));
                $m = intVal(date('m',strtotime($case['created_at'])));
                $totalCases[$y][$m][] = $case['id'];
                if ($case['deal_status'] == 'won') {
                    $oldCasesWon[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'lost') {
                    $caseFail[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'pending'){
                    $casePending[$y][$m][] = $case['id'];
                }
            }
            $y_min = min(array_keys($totalCases));
            $y_max = max(array_keys($totalCases));
            // var_dump($y_max);die;
            $cntCaseInMonth = [];
            for ($yr = $y_min; $yr <= $y_max + 5; $yr++) {
                for ($mo = 1; $mo <= 12 ; $mo++) {
                    $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                    $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                    $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                    $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                    $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt);
                    $cntCaseInMonth[$yr][$mo]['c_won'] = count($old_c_won);
                    $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                    $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
                }
            }
        }
        if ($case_type == 'month_end') {
            $cases = $query->asArray()->all();
            if (!$cases) {
                throw new HttpException(403,"Not found any case");
            }
            $totalCases = [];
            $oldCasesWon = [];
            $caseFail = [];
            $casePending = [];
            foreach ($cases as $case) {
                if ($case['y'] == null) {
                    continue;
                }
                $y = $case['y'];
                $m = $case['m'];
                $totalCases[$y][$m][] = $case['id'];
                if ($case['deal_status'] == 'won') {
                    $oldCasesWon[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'lost') {
                    $caseFail[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'pending'){
                    $casePending[$y][$m][] = $case['id'];
                }
            }
            $query = Booking::find()
                ->select(['at_bookings.id', 'at_bookings.case_id','product_id',
                    'YEAR(DATE_ADD(day_from, INTERVAL at_ct.day_count DAY)) AS y',
                    'MONTH(DATE_ADD(day_from, INTERVAL at_ct.day_count DAY)) AS m',
                ])
                ->innerJoinWith([
                    'product' => function($q){
                        return $q->select(['id', 'day_from', 'day_count']);
                    }
                ])
                ->innerJoinWith([
                    'case' => function($q){
                        return $q->select(['id', 'is_b2b']);
                    }
                ])
                ->innerJoinWith([
                    'case.stats'
                ])
                ->andWhere(['is_b2b' => 'no', 'at_bookings.status' => 'won']);
            if (in_array($getProspect, [1,2,3,4,5])){
                if ($getProspect != 'all') {
                    $query->andWhere(['prospect' => $getProspect]);
                }
            }
            if ($getSite != 'all') {
                    $query->andWhere(['pa_from_site' => $getSite]);
                }
            if ($getDevice != 'all') {
                $query->andWhere('request_device=:device',[':device' => $getDevice]);
            }
            if ($getOwnerId != 'all') {
                if (substr($getOwnerId, 0, 5) == 'cofr-') {
                    $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
                } else {
                    $query->andWhere(['owner_id'=>(int)$getOwnerId]);
                }
            }
            if (isset($getDestinations) && !empty($getDestinations)) {
                if ($getDestSelect == 'any') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->orWhere(['like', 'req_countries', $des]);

                    }
                }
                if ($getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);

                    }
                }
                if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);

                    }
                }
            }
            if ($getLanguage != 'all') $query->andWhere(['language'=>$getLanguage]);
            if ($contacted != '') {
                if ($contacted == 'web-direct') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
                } elseif ($contacted == 'link') {
                    $query->andWhere(['web_referral'=>'link']);
                } elseif ($contacted == 'social') {
                    $query->andWhere(['web_referral'=>'social']);
                } elseif ($contacted == 'web-search') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
                } elseif ($contacted == 'web-search-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-adsense') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
                } elseif ($contacted == 'web-bingad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
                } elseif ($contacted == 'web-otherad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
                } elseif ($contacted == 'web-adwords') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
                } elseif ($contacted == 'web-adwords-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-trip-connexion') {
                    $query->andWhere(['web_referral'=>'ad/trip-connexion']);
                } else {
                    $query->andWhere(['how_contacted'=>$contacted]);
                }
            }
            if ($getNumberDay != '') {
                $arr_s = explode('-', $getNumberDay);
                if (count($arr_s) == 1) {
                    $arr_s[] = $arr_s[0];
                }
                switch (count($arr_s)) {
                    case 2:
                        $query->andWhere('
                            CASE
                                WHEN day_count_max > 0
                                    THEN day_count_max >= :minDay AND day_count_min <= :maxDay
                                ELSE
                                    day_count_min >=:minDay AND day_count_min <=:maxDay
                            END', [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                        break;
                }
            }
            if ($getCampaignId == 'yes') {
                $query->andWhere('campaign_id!=0');
            } else {
                if ($getCampaignId != 'all') $query->andWhere(['campaign_id'=>$getCampaignId]);
            }
            if ($getNumberPax != '') {
                    $arr_s = explode('-', $getNumberPax);
                    if (count($arr_s) == 1) {
                        $arr_s[] = $arr_s[0];
                    }
                    switch (count($arr_s)) {
                        case 1:
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN pax_count_max >= :numPax AND pax_count_min <= :numPax
                                    ELSE pax_count_min =:numPax
                                END', [':numPax' => $getNumberPax]);
                            break;
                        case 2:
                            $s_pax = explode('-', $getNumberPax);
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN
                                            pax_count_max >= :minPax AND pax_count_min <= :maxPax
                                    ELSE
                                        pax_count_min >=:minPax AND pax_count_min <=:maxPax
                                END', [':minPax' => $arr_s[0], ':maxPax' => $arr_s[1]]);
                            break;
                    }
                }
            if ($found != '') {
                $query->andWhere(['how_found'=>$found]);
            }
            $bookings = $query->asArray()->all();
            $newCasesWon = [];
            $arr_bookings = [];
            if ($bookings) {
                foreach ($bookings as $b) {
                    if (isset($arr_bookings[$b['case_id']])) {
                        $old_b = $arr_bookings[$b['case_id']];
                        $new_date_end = date('Y-m-d',strtotime($b['product']['day_from'].'+'. $b['product']['day_count'].' days'));
                        $old_date_end = date('Y-m-d',strtotime($old_b['product']['day_from'].'+'. $old_b['product']['day_count'].' days'));
                        if (strtotime($new_date_end) > strtotime($old_date_end)) {
                            $arr_bookings[$b['case_id']] = $b;
                        }
                    } else {
                        $arr_bookings[$b['case_id']] = $b;
                    }
                }
                $new_c_ids = [];
                foreach ($arr_bookings as $b) {
                    $newCasesWon[$b['y']][$b['m']][] = $b['case_id'];
                }
            }
            $y_min = min(array_keys($totalCases));
            $y_max = max(array_keys($totalCases));
            $cntCaseInMonth = [];
            for ($yr = $y_min; $yr <= $y_max; $yr++) {
                for ($mo = 1; $mo <= 12 ; $mo++) {
                    $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                    $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                    $c_won = (isset($newCasesWon[$yr]) && isset($newCasesWon[$yr][$mo]))? $newCasesWon[$yr][$mo]: [];
                    $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                    $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                    $not_equal = 0;
                    if (count($old_c_won) != count($c_won)) {
                        $not_equal = count($old_c_won) - count($c_won);
                    }
                    $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt) - $not_equal;
                    $cntCaseInMonth[$yr][$mo]['c_won'] = count($c_won);
                    $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                    $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
                }
            }
        }
        if ($case_type == 'month_start') {
            $cases = $query->createCommand()->queryAll();
            if (!$cases) {
                throw new HttpException(403,"Not found any case");
            }
            $totalCases = [];
            $oldCasesWon = [];
            $caseFail = [];
            $casePending = [];
            foreach ($cases as $case) {
                if ($case['y'] == null) {
                    continue;
                }
                $y = $case['y'];
                $m = $case['m'];
                $totalCases[$y][$m][] = $case['id'];
                if ($case['deal_status'] == 'won') {
                    $oldCasesWon[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'lost') {
                    $caseFail[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'pending'){
                    $casePending[$y][$m][] = $case['id'];
                }
            }

            $query = Booking::find()
                ->select(['at_bookings.id', 'at_bookings.case_id','product_id',
                    'YEAR(day_from) AS y',
                    'MONTH(day_from) AS m',
                ])
                ->innerJoinWith([
                    'product' => function($q){
                        return $q->select(['id', 'day_from', 'day_count']);
                    }
                ])
                ->innerJoinWith([
                    'case' => function($q){
                        return $q->select(['id', 'is_b2b', 'created_at']);
                    }
                ])
                ->innerJoinWith([
                    'case.stats'
                ])
                ->andWhere(['is_b2b' => 'no', 'at_bookings.status' => 'won']);
            if (in_array($getProspect, [1,2,3,4,5])){
                if ($getProspect != 'all') {
                    $query->andWhere(['prospect' => $getProspect]);
                }
            }
            if ($getSite != 'all') {
                    $query->andWhere(['pa_from_site' => $getSite]);
                }
            if ($getDevice != 'all') {
                $query->andWhere('request_device=:device',[':device' => $getDevice]);
            }
            if ($getOwnerId != 'all') {
                if (substr($getOwnerId, 0, 5) == 'cofr-') {
                    $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
                } else {
                    $query->andWhere(['owner_id'=>(int)$getOwnerId]);
                }
            }
            if (isset($getDestinations) && !empty($getDestinations)) {
                if ($getDestSelect == 'any') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->orWhere(['like', 'req_countries', $des]);

                    }
                }
                if ($getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);

                    }
                }
                if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);

                    }
                }
            }
            if ($getLanguage != 'all') $query->andWhere(['language'=>$getLanguage]);
            if ($contacted != '') {
                if ($contacted == 'web-direct') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
                } elseif ($contacted == 'link') {
                    $query->andWhere(['web_referral'=>'link']);
                } elseif ($contacted == 'social') {
                    $query->andWhere(['web_referral'=>'social']);
                } elseif ($contacted == 'web-search') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
                } elseif ($contacted == 'web-search-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-adsense') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
                } elseif ($contacted == 'web-bingad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
                } elseif ($contacted == 'web-otherad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
                } elseif ($contacted == 'web-adwords') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
                } elseif ($contacted == 'web-adwords-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-trip-connexion') {
                    $query->andWhere(['web_referral'=>'ad/trip-connexion']);
                } else {
                    $query->andWhere(['how_contacted'=>$contacted]);
                }
            }
            if ($getNumberDay != '') {
                $arr_s = explode('-', $getNumberDay);
                if (count($arr_s) == 1) {
                    $arr_s[] = $arr_s[0];
                }
                switch (count($arr_s)) {
                    case 2:
                        $query->andWhere('
                            CASE
                                WHEN day_count_max > 0
                                    THEN day_count_max >= :minDay AND day_count_min <= :maxDay
                                ELSE
                                    day_count_min >=:minDay AND day_count_min <=:maxDay
                            END', [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                        break;
                }
            }
            if ($getCampaignId == 'yes') {
                $query->andWhere('campaign_id!=0');
            } else {
                if ($getCampaignId != 'all') $query->andWhere(['campaign_id'=>$getCampaignId]);
            }
            if ($getNumberPax != '') {
                    $arr_s = explode('-', $getNumberPax);
                    if (count($arr_s) == 1) {
                        $arr_s[] = $arr_s[0];
                    }
                    switch (count($arr_s)) {
                        case 1:
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN pax_count_max >= :numPax AND pax_count_min <= :numPax
                                    ELSE pax_count_min =:numPax
                                END', [':numPax' => $getNumberPax]);
                            break;
                        case 2:
                            $s_pax = explode('-', $getNumberPax);
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN
                                            pax_count_max >= :minPax AND pax_count_min <= :maxPax
                                    ELSE
                                        pax_count_min >=:minPax AND pax_count_min <=:maxPax
                                END', [':minPax' => $arr_s[0], ':maxPax' => $arr_s[1]]);
                            break;
                    }
                }
            if ($found != '') {
                $query->andWhere(['how_found'=>$found]);
            }

            $bookings = $query->asArray()->all();
            $newCasesWon = [];
            $arr_bookings = [];
            if ($bookings) {
                foreach ($bookings as $b) {
                    if (isset($arr_bookings[$b['case_id']])) {
                        $old_b = $arr_bookings[$b['case_id']];
                        $new_date_end = date('Y-m-d',strtotime($b['product']['day_from']));
                        $old_date_end = date('Y-m-d',strtotime($old_b['product']['day_from']));
                        if (strtotime($new_date_end) < strtotime($old_date_end)) {
                            $arr_bookings[$b['case_id']] = $b;
                        }
                    } else {
                        $arr_bookings[$b['case_id']] = $b;
                    }
                }
                $new_c_ids = [];
                foreach ($arr_bookings as $b) {
                    $newCasesWon[$b['y']][$b['m']][] = $b['case_id'].' '.$b['case']['created_at'].' => '. $b['product']['day_from'];
                }
            }
            $y_min = min(array_keys($totalCases));
            $y_max = max(array_keys($totalCases));
            var_dump($y_max);die;
            $cntCaseInMonth = [];
            for ($yr = $y_min; $yr <= $y_max; $yr++) {
                for ($mo = 1; $mo <= 12 ; $mo++) {
                    $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                    $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                    $c_won = (isset($newCasesWon[$yr]) && isset($newCasesWon[$yr][$mo]))? $newCasesWon[$yr][$mo]: [];
                    $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                    $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                    $not_equal = 0;
                    if (count($old_c_won) != count($c_won)) {
                        $not_equal = count($old_c_won) - count($c_won);
                    }
                    $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt) - $not_equal;
                    $cntCaseInMonth[$yr][$mo]['c_won'] = count($c_won);
                    $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                    $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
                }
            }
        }
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, persons u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM at_campaigns c ORDER BY c.start_dt DESC')->queryAll();

        $tourCountryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        return $this->render('case_open', [
            'case_type' => $case_type,
            'minYear'=>$y_min,
            'maxYear'=>$y_max,
            'result' => $cntCaseInMonth,
            'getDevice' => $getDevice,
            'getSite' => $getSite,
            'getDestinations' => $getDestinations,
            'contacted' => $contacted,
            'getNumberDay' => $getNumberDay,
            'ownerList' => $ownerList,
            'getOwnerId' => $getOwnerId,
            'getProspect' => $getProspect,
            'found' => $found,
            'getLanguage' => $getLanguage,
            'getNumberPax' => $getNumberPax,
            'getCampaignId' => $getCampaignId,
            'campaignList' => $campaignList,
            'tourCountryList' => $tourCountryList,
        ]);
    }
    public function actionTour_end()
    {
        $getDestinations = Yii::$app->request->get('destination', '');
        $getNumberDay = Yii::$app->request->get('number_day', '');
        $getDestSelect = Yii::$app->request->get('destselect', 'all');
        $getAge = Yii::$app->request->get('age', '');
        $query = Product::find()
            ->select(['*', 'DATE_ADD(day_from, INTERVAL at_ct.day_count - 1 DAY) AS de',
                    'YEAR(DATE_ADD(day_from, INTERVAL at_ct.day_count - 1 DAY)) AS y',
                    'MONTH(DATE_ADD(day_from, INTERVAL at_ct.day_count - 1 DAY)) AS m',
                ])
            ->with([
                'bookings',
                'bookings.report',
                'pax'
            ])
            ->innerJoinWith('tourStats')
            ->andWhere('offer_type = "private" AND op_status = "op" AND op_finish !="canceled" AND SUBSTRING(tour_code, 1, 1) ="f"');
        if (isset($getDestinations) && !empty($getDestinations)) {
            if ($getDestSelect == 'any') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->orWhere(['like', 'countries', $des]);

                }
            }
            if ($getDestSelect == 'all') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->andWhere(['like', 'countries', $des]);

                }
            }
            if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->andWhere(['like', 'countries', $des]);

                }
            }
        }
        if ($getNumberDay != '') {
            $arr_s = explode('-', $getNumberDay);
            switch (count($arr_s)) {
                case 1:
                    $query->andWhere('at_tour_stats.day_count =:minDay',
                                    [':minDay' => $arr_s[0]]);
                    break;
                case 2:
                    $query->andWhere('at_tour_stats.day_count >=:minDay AND at_tour_stats.day_count <=:maxDay',
                                    [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                    break;
            }
        }
        $search_age = false;
        if ($getAge != '') {
           $search_age = true;
        }
        $tours = $query->asArray()->all();
        $newToursWon = [];
        if ($tours) {
            $tourNotBooking = [];
            foreach ($tours as $t) {
                $total_dt = $total_cost = $day_count = 0;
                $t_cost = $t_cost_unit = $t_doanhThu_unit = $t_doanhThu = $arr_pax_count = [];
                if ($t['bookings']) {
                    foreach ($t['bookings'] as $b) {
                        $cost = $price = 0;
                        $price_unit = $cost_unit = 'not';
                        if ($b['report']) {
                            //price
                            $price = ($b['report']['price'] > 0)? $b['report']['price'] : 0;
                            $price_unit = ($b['report']['price_unit'] != '')? $b['report']['price_unit'] : 'not';
                            //cost
                            $cost = ($b['report']['cost'] > 0)? $b['report']['cost'] : 0;
                            $cost_unit = ($b['report']['cost_unit'] != '')? $b['report']['cost_unit'] : 'not';

                            //pax count
                            $arr_pax_count[] = ($b['report']['pax_count'] > 0)? $b['report']['pax_count'] : 0;
                            // if ($price_unit != $cost_unit) {
                            //     var_dump($b['report']);die;
                            // }
                        }
                        if ($price_unit == 'not') {
                            continue;
                        }
                        $t_doanhThu[] = $price;
                        $t_doanhThu_unit[] = $price_unit;
                        $t_cost[] = $cost;
                        $t_cost_unit[] = $cost_unit;
                    }
                    if (empty($t_doanhThu) && empty($t_doanhThu_unit)) {
                        $t_doanhThu[] = 0;
                        $t_doanhThu_unit[] = 'none';
                    }
                    if (empty($t_cost) && empty($t_cost_unit)) {
                        $t_cost[] = 0;
                        $t_cost_unit[] = 'none';
                    }
                    if (empty($arr_pax_count)) {
                        $arr_pax_count[] = 0;
                    }
                    $p_unit = array_unique($t_doanhThu_unit);
                    $c_unit = array_unique($t_cost_unit);
                    if (count($p_unit) == 1 && count($c_unit) == 1) {
                        $total_dt = array_sum($t_doanhThu);
                        $total_cost = array_sum($t_cost);
                    }
                    // else {
                    //     // var_dump($t_doanhThu);
                    //     // var_dump($t_doanhThu_unit);die('ok');
                    //     $total_dt = [];
                    //     for($i = 0; $i < count($t_doanhThu) - 1; $i ++) {
                    //         $total_dt[$t_doanhThu[$i]] = $t_doanhThu_unit[$i];
                    //     }
                    // }
                    // if (is_array($total_dt)) {
                    //     var_dump(['err']);
                    //     var_dump($total_dt);die;
                    // }
                }
                if ($search_age) {
                    $matched = false;
                    foreach ($t['pax'] as $pax) {
                        $arr_s = explode('-', $getAge);
                        switch (count($arr_s)) {
                            case 1:
                                if ((date('Y') - date('Y', strtotime($pax['pp_birthdate']))) == $arr_s[0]) {
                                    $matched = true;
                                }
                                break;
                            case 2:
                                if ((date('Y') - date('Y', strtotime($pax['pp_birthdate']))) >= $arr_s[0] && (date('Y') - date('Y', strtotime($pax['pp_birthdate']))) <= $arr_s[1]) {
                                    $matched = true;
                                }
                                break;
                            default:
                                $matched = false;
                                break;
                        }
                        if ($matched) {
                            break;
                        }
                    }
                    if (!$matched) {
                        continue;
                    }
                }
                if (empty($t_doanhThu_unit)) {
                    $tourNotBooking[$t['y']][$t['m']][] = $t['id'];
                    continue;
                }
                if ($t['day_count'] > 0) {
                    $day_count = $t['day_count'];
                } else { $day_count = 0;}

                $newToursWon[$t['y']][$t['m']][] = [
                                                    'id' => $t['id'].' '.$t['de'],
                                                    'dt_tour' => [$t_doanhThu_unit[0] => $total_dt],
                                                    'cost_tour' => [$t_cost_unit[0] => $total_cost],
                                                    'day_count' => $day_count,
                                                    'pax_count' => array_sum($arr_pax_count),
                                                    ];
            }
        }
        // var_dump($newToursWon[2017][1]);die;
        if (empty($newToursWon)) {
            var_dump($newToursWon);die;
        }
        $y_min = min(array_keys($newToursWon));
        $y_max = max(array_keys($newToursWon));

        $cntCaseInMonth = [];

        for ($yr = $y_min; $yr <= $y_max; $yr++) {
            for ($mo = 1; $mo <= 12 ; $mo++) {
                $total_cnt = (isset($newToursWon[$yr]) && isset($newToursWon[$yr][$mo]))? $newToursWon[$yr][$mo]: [];
                $cntCaseInMonth[$yr][$mo]['t_total'] = count($total_cnt);
                if (isset($tourNotBooking[$yr][$mo])) {
                    $cntCaseInMonth[$yr][$mo]['t_total'] += count($tourNotBooking[$yr][$mo]);
                }
                if (count($total_cnt) > 0) {
                    $arr_doanhthu_thang = $arr_cost_thang = $arr_day_count_thang = $arr_pax_count_thang = [];
                    foreach ($total_cnt as $tour_m) {
                        if (count($tour_m['dt_tour']) == 1) {
                            if (array_key_exists("USD",$tour_m['dt_tour']) || array_key_exists("EUR",$tour_m['dt_tour']) || array_key_exists("VND",$tour_m['dt_tour'])) {
                                $unit = key($tour_m['dt_tour']);
                                if (isset($arr_doanhthu_thang[$unit])) {
                                    $arr_doanhthu_thang[$unit] += $tour_m['dt_tour'][$unit];
                                } else {
                                    $arr_doanhthu_thang[$unit] = $tour_m['dt_tour'][$unit];
                                }
                            }
                        }
                        if (count($tour_m['cost_tour']) == 1) {
                            if (array_key_exists("USD",$tour_m['cost_tour']) || array_key_exists("EUR",$tour_m['cost_tour']) || array_key_exists("VND",$tour_m['cost_tour'])) {
                                $unit = key($tour_m['cost_tour']);
                                if (isset($arr_cost_thang[$unit])) {
                                    $arr_cost_thang[$unit] += $tour_m['cost_tour'][$unit];
                                } else {
                                    $arr_cost_thang[$unit] = $tour_m['cost_tour'][$unit];
                                }
                            }
                        }
                        // if (count($tour_m['dt_tour']) > 1) {
                        //     var_dump($tour_m['dt_tour']);die;
                        // }
                        if ($tour_m['day_count'] != '') {
                            $arr_day_count_thang[] = $tour_m['day_count'];
                        }
                        if ($tour_m['pax_count'] != '' && $tour_m['pax_count'] > 0) {
                            $arr_pax_count_thang[] = $tour_m['pax_count'];
                        }
                    }
                    if (!empty($arr_doanhthu_thang)) {
                        $cntCaseInMonth[$yr][$mo]['t_dt'] = $arr_doanhthu_thang;
                    } else {
                        $cntCaseInMonth[$yr][$mo]['t_dt'] = 0;
                    }
                    if (!empty($arr_cost_thang)) {
                        $cntCaseInMonth[$yr][$mo]['t_cost'] = $arr_cost_thang;
                    } else {
                        $cntCaseInMonth[$yr][$mo]['t_cost'] = 0;
                    }
                    if (!empty($arr_doanhthu_thang)) {
                        $cntCaseInMonth[$yr][$mo]['day_count'] = array_sum($arr_day_count_thang);
                    } else {
                        $cntCaseInMonth[$yr][$mo]['day_count'] = 0;
                    }
                    if (!empty($arr_doanhthu_thang)) {
                        $cntCaseInMonth[$yr][$mo]['pax_count'] = array_sum($arr_pax_count_thang);
                    } else {
                        $cntCaseInMonth[$yr][$mo]['day_count'] = 0;
                    }
                }
            }
        }

        // var_dump($cntCaseInMonth[2017][1]);die;
        $tourCountryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        return $this->render('tour_end', [
            'result' => $cntCaseInMonth,
            'minYear'=>$y_min,
            'maxYear'=>$y_max,
            'getDestinations' => $getDestinations,
            'getNumberDay' => $getNumberDay,
            'tourCountryList' => $tourCountryList,
            'getAge' => $getAge,

        ]);
    }
    public function actionCase_start()
    {
        $getProspect = Yii::$app->request->get('prospect', 'all');
        $getDevice = Yii::$app->request->get('device', 'all');
        $getSite = Yii::$app->request->get('site', 'all');
        $getDestinations = Yii::$app->request->get('destination', '');
        $contacted = Yii::$app->request->get('contacted', '');
        $getNumberDay = Yii::$app->request->get('number_day', '');
        $getOwnerId = Yii::$app->request->get('owner_id', 'all');
        $found = Yii::$app->request->get('found', '');
        $sql = 'SELECT *, SUBSTRING(pa_start_date, 1, 4) AS y ,
                    CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(pa_start_date,"-",2),"-",-1),SIGNED INTEGER) AS m
                FROM `at_cases` INNER JOIN `at_case_stats` ON at_cases.id = at_case_stats.case_id
                WHERE is_b2b = "no" AND SUBSTRING(pa_start_date, 1, 4) = "2017"';

        //2017
        /*SELECT SUBSTRING(pa_start_date, 1, 4) AS y ,
            CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(pa_start_date,'-',2),'-',-1),SIGNED INTEGER) AS m,
               COUNT(*) AS cnt
        FROM `at_cases` LEFT JOIN `at_case_stats` ON at_cases.id = at_case_stats.case_id
        WHERE is_b2b = "no" AND SUBSTRING(pa_start_date, 1, 4) = "2017"
        GROUP BY y, m;*/
        $cases = Kase::findBySql($sql)->createCommand()->queryAll();
        if (!$cases) {
            throw new HttpException(403,"Not found any case");
        }
        $totalCases = [];
        $case_ids = [];
        $oldCasesWon = [];
        $caseFail = [];
        $casePending = [];
        foreach ($cases as $case) {
            if ($case['y'] == null) {
                continue;
            }
            $y = $case['y'];
            $m = $case['m'];
            $totalCases[$y][$m][] = $case['id'];
            if ($case['deal_status'] == 'won') {
                $case_ids[] = $case['id'];
                $oldCasesWon[$y][$m][] = $case['id'];
            }
            if ($case['deal_status'] == 'lost') {
                $caseFail[$y][$m][] = $case['id'];
            }
            if ($case['deal_status'] == 'pending'){
                $casePending[$y][$m][] = $case['id'];
            }
        }

        $query = Booking::find()
            ->select(['at_bookings.id', 'at_bookings.case_id','product_id',
                'YEAR(day_from) AS y',
                'MONTH(day_from) AS m',
            ])
            ->innerJoinWith([
                'product' => function($q){
                    return $q->select(['id', 'day_from', 'day_count']);
                }
            ])
            ->innerJoinWith([
                'case' => function($q){
                    return $q->select(['id', 'is_b2b', 'created_at']);
                }
            ])
            ->innerJoinWith([
                'case.stats'
            ])
            ->andWhere(['is_b2b' => 'no', 'at_bookings.status' => 'won']);

        $bookings = $query->asArray()->all();
         // var_dump($bookings);die;
        $newCasesWon = [];
        $arr_bookings = [];
        if ($bookings) {
            foreach ($bookings as $b) {
                if (isset($arr_bookings[$b['case_id']])) {
                    $old_b = $arr_bookings[$b['case_id']];
                    $new_date_end = date('Y-m-d',strtotime($b['product']['day_from']));
                    $old_date_end = date('Y-m-d',strtotime($old_b['product']['day_from']));
                    if (strtotime($new_date_end) < strtotime($old_date_end)) {
                        $arr_bookings[$b['case_id']] = $b;
                    }
                } else {
                    $arr_bookings[$b['case_id']] = $b;
                }
            }
            $new_c_ids = [];
            foreach ($arr_bookings as $b) {
                $newCasesWon[$b['y']][$b['m']][] = $b['case_id'].' '.$b['case']['created_at'].' => '. $b['product']['day_from'];
            }
        }
        $y_min = min(array_keys($totalCases));
        $y_max = max(array_keys($totalCases));
        $cntCaseInMonth = [];
        for ($yr = $y_min; $yr <= $y_max; $yr++) {
            for ($mo = 1; $mo <= 12 ; $mo++) {
                $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                $c_won = (isset($newCasesWon[$yr]) && isset($newCasesWon[$yr][$mo]))? $newCasesWon[$yr][$mo]: [];
                $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                $not_equal = 0;
                if (count($old_c_won) != count($c_won)) {
                    $not_equal = count($old_c_won) - count($c_won);
                }
                $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt) - $not_equal;
                $cntCaseInMonth[$yr][$mo]['c_won'] = count($c_won);
                $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
            }
        }
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, persons u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        return $this->render('case_end', [
            'result' => $cntCaseInMonth,
            'minYear'=>$y_min,
            'maxYear'=>$y_max,
            'cases' => $cases,
            'getDevice' => $getDevice,
            'getSite' => $getSite,
            'getDestinations' => $getDestinations,
            'contacted' => $contacted,
            'getNumberDay' => $getNumberDay,
            'ownerList' => $ownerList,
            'getOwnerId' => $getOwnerId,
            'getProspect' => $getProspect,
            'found' => $found,

        ]);
    }

    // 170304
    public function actionTourOperators($year = '')
    {
        $theTours = Product::find()
            ->select(['id', 'day_from'])
            ->where(['op_status'=>'op', 'op_finish'=>''])
            ->andWhere($year != '' ? 'YEAR(day_from)=:year' : '', [':year'=>$year])
            ->orderBy('day_from DESC')
            ->with([
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id']);
                },
                'tour.operators'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                ])
            ->asArray()
            ->all();
        $result = [];
        // \fCore::expose($theTours); exit;
        foreach ($theTours as $tour) {
            $y = substr($tour['day_from'], 0, 4);
            $m = (int)substr($tour['day_from'], 5, 2);
            if (!empty($tour['tour']['operators'])) {
                foreach ($tour['tour']['operators'] as $user) {
                    if (!isset($result[$y][$m][$user['name']])) {
                        $result[$y][$m][$user['name']] = 1;
                    } else {
                        $result[$y][$m][$user['name']] ++;
                    }
                }
            }
        }
        // \fCore::expose($result); exit;
        return $this->render('report_tour-operators', [
            'result'=>$result,
            'year'=>$year
            ]);

    }

    // 160613
    public function actionTourPaxCountry($year = '')
    {
        $tours = Product::find()
            ->select(['id', 'op_name', 'op_code', 'year'=>'YEAR(day_from)'])
            ->where(['op_status'=>'op'])
            ->andWhere('op_finish!="canceled"')
            //->andWhere('YEAR(day_from)=:year', [':year'=>$year])
            ->andWhere('SUBSTRING(op_code,1,1)="F"')
            ->with([
                'days'=>function($q) {
                    return $q->select(['rid', 'name']);
                },
                'bookings'=>function($q) {
                    return $q->select(['pax', 'product_id']);
                },
                ])
            ->orderBy('year')
            ->asArray()
            ->all();
        $result = [];
        foreach ($tours as $tour) {
            $y = $tour['year'];
            $pax = 0;
            foreach ($tour['bookings'] as $booking) {
                $pax += $booking['pax'];
            }
            if (!isset($result[$y])) {
                $result[$y] = [
                    'vn'=>[0, 0], // tour, pax
                    'la'=>[0, 0],
                    'kh'=>[0, 0],
                    'mm'=>[0, 0],
                    'th'=>[0, 0],
                    'id'=>[0, 0],
                    'my'=>[0, 0],
                    'cn'=>[0, 0],
                ];
            }
            $visit = [
                'vn'=>false,
                'la'=>false,
                'kh'=>false,
                'mm'=>false,
                'th'=>false,
                'id'=>false,
                'my'=>false,
                'cn'=>false,
            ];
            foreach ($tour['days'] as $day) {
                $name = strtolower($day['name']);
                $name = str_replace(' ', '', $name);
                if (strpos($name, 'hanoi') !== false || strpos($name, 'saigon') !== false || strpos($name, 'hochiminh') !== false) {
                    $visit['vn'] = true;
                }
                if (strpos($name, 'vientiane') !== false || strpos($name, 'luang') !== false || strpos($name, 'pakse') !== false) {
                    $visit['la'] = true;
                }
                if (strpos($name, 'phnompenh') !== false || strpos($name, 'siemreap') !== false) {
                    $visit['kh'] = true;
                }
                if (strpos($name, 'yangoon') !== false || strpos($name, 'bagan') !== false || strpos($name, 'mandalay') !== false) {
                    $visit['mm'] = true;
                }
                if (strpos($name, 'bangkok') !== false || strpos($name, 'changmai') !== false || strpos($name, 'phuket') !== false) {
                    $visit['th'] = true;
                }
                if (strpos($name, 'yogyakarta') !== false || strpos($name, 'bali') !== false || strpos($name, 'jakarta') !== false) {
                    $visit['id'] = true;
                }
                if (strpos($name, 'kuala') !== false || strpos($name, 'penang') !== false || strpos($name, 'johor') !== false) {
                    $visit['my'] = true;
                }
                if (strpos($name, 'guang') !== false || strpos($name, 'beijing') !== false || strpos($name, 'shanghai') !== false) {
                    $visit['cn'] = true;
                }
            }
            foreach ($visit as $cc=>$tf) {
                if ($tf === true) {
                    $result[$y][$cc][0] ++;
                    $result[$y][$cc][1] += $pax;
                }
            }
        }
        // \fCore::expose($result); exit;
        return $this->render('report_tour-pax-country', ['result'=>$result]);
    }
    /**
     * View Sale B2C result by year
     */
    // public function actionB2c(
    //     $view = 'tourend',
    //     $year = 0, // View data of this year
    //     $year2 = 0, // Compare to this year
    //     $currency = 'EUR',
    //     $sopax = '', $songay = '',
    //     $doanhthu = '', $loinhuan = '',
    //     array $diemden = [], $dkdiemden = '',
    //     $test = '',
    //     array $kx_source = [], array $tx_source = []
    //     )
    // {
    //     $indexList = [
    //         0=>['label'=>'Tổng số tour', 'hint'=>'Số tour kết thúc trong tháng'],
    //         1=>['label'=>'Tổng số khách'],
    //         2=>['label'=>'Tổng số ngày'],
    //         3=>['label'=>'Số khách BQ /tour', 'round'=>1, 'avg'=>[1, 0]],
    //         4=>['label'=>'Số ngày BQ /tour', 'round'=>1, 'avg'=>[2, 0]],

    //         5=>['label'=>'Doanh thu', 'sub'=>$currency, 'est'=>true, 'link'=>'', 'hint'=>"Doanh thu dự tính: Lấy tổng tiền các hoá đơn do bán hàng làm khi bán tour; tỉ giá tính tại thời điểm phải thu tiền.\nDoanh thu thực tế: Lấy tổng tiền các lần thanh toán hoá đơn; tỉ giá tính tại thời điểm thu tiền thực tế."],
    //         6=>['label'=>'Giá vốn', 'sub'=>$currency, 'est'=>true, 'link'=>'', 'hint'=>"Giá vốn dự tính: Lấy giá vốn dự tính do bán hàng nhập khi bán tour; tỉ giá tính tại thời điểm nhập.\nGiá vốn thực tế: Lấy tổng tiền chi phí tour --!thực tế-- do điều hành nhập; tỉ giá tính tại thời điểm phải thanh toán."],
    //         7=>['label'=>'Lợi nhuận', 'sub'=>$currency, 'est'=>true, 'link'=>''],

    //         17=>['label'=>'Tỉ lệ lãi', 'sub'=>'%', 'est'=>true, 'round'=>2, 'avg'=>[7, 5], 'pct'=>true, 'hint'=>'100 * (LN / DT)'],
    //         18=>['label'=>'Tỉ lệ markup', 'sub'=>'%', 'est'=>true, 'round'=>2, 'avg'=>[5, 6], 'pct'=>true, 'minus1'=>true, 'hint'=>'100 * (DT / GV - 1)'],

    //         8=>['label'=>'Doanh thu BQ /tour', 'sub'=>$currency, 'est'=>true, 'avg'=>[5, 0]],
    //         9=>['label'=>'Giá vốn BQ /tour', 'sub'=>$currency, 'est'=>true, 'avg'=>[6, 0]],
    //         10=>['label'=>'Lợi nhuận BQ /tour', 'sub'=>$currency, 'est'=>true, 'avg'=>[7, 0]],
    //         11=>['label'=>'Doanh thu BQ /khách', 'sub'=>$currency, 'est'=>true, 'avg'=>[5, 1]],
    //         12=>['label'=>'Giá vốn BQ /khách', 'sub'=>$currency, 'est'=>true, 'avg'=>[6, 1]],
    //         13=>['label'=>'Lợi nhuận BQ /khách', 'sub'=>$currency, 'est'=>true, 'avg'=>[7, 1]],
    //         14=>['label'=>'Doanh thu BQ /khách/ngày', 'sub'=>$currency, 'est'=>true, 'round'=>2, 'avg'=>[11, 4], 'hint'=>'11. Doanh thu BQ /khách / 4. Số ngày BQ /tour'],
    //         15=>['label'=>'Giá vốn BQ /khách/ngày', 'sub'=>$currency, 'est'=>true, 'round'=>2, 'avg'=>[12, 4]],
    //         16=>['label'=>'Lợi nhuận BQ /khách/ngày', 'sub'=>$currency, 'est'=>true, 'round'=>2, 'avg'=>[13, 4]],
    //     ];

    //     $channelList = [
    //         'k1'=>['id'=>'k1', 'name'=>'K1', 'description'=>'Google Adwords'],
    //         'k2'=>['id'=>'k2', 'name'=>'K2', 'description'=>'Bing Ads'],
    //         'k3'=>['id'=>'k3', 'name'=>'K3', 'description'=>'Other web search'],
    //         'k4'=>['id'=>'k4', 'name'=>'K4', 'description'=>'Referral + Ads online + Other web which source could not be determined'],
    //         'k5'=>['id'=>'k5', 'name'=>'K5', 'description'=>'Direct access'],
    //         'k6'=>['id'=>'k6', 'name'=>'K6', 'description'=>'Mailing'],
    //         'k7'=>['id'=>'k7', 'name'=>'K7', 'description'=>'Non-web'],
    //         'k8'=>['id'=>'k8', 'name'=>'K8', 'description'=>'Other special cases'],
    //         'k0'=>['id'=>'k0', 'name'=>'No channel', 'description'=>'No channel data entered'],
    //     ];

    //     $typeList = [
    //         'new'=>['id'=>'new', 'name'=>'New', 'description'=>'New customer'],
    //         'referred'=>['id'=>'referred', 'name'=>'Referred', 'description'=>'Referred customer'],
    //         'returning'=>['id'=>'returning', 'name'=>'Returning', 'description'=>'Returning customer'],
    //         'unknown'=>['id'=>'unknown', 'name'=>'unknown', 'description'=>'Unknown'],
    //     ];

    //     if ($year == 0) {
    //         $year = date('Y');
    //     }
    //     if ($year2 == $year) {
    //         $year2 = 0;
    //     }

    //     $arr_xrate = [
    //         2016 => [
    //             1 => ['USD'=>22376,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             2 => ['USD'=>22296,'EUR'=>24524,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             3 => ['USD'=>22263,'EUR'=>24683,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             4 => ['USD'=>22258,'EUR'=>25150,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             5 => ['USD'=>22281,'EUR'=>25075,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             6 => ['USD'=>22305,'EUR'=>24944,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             7 => ['USD'=>22263,'EUR'=>24533,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             8 => ['USD'=>22261,'EUR'=>24832,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             9 => ['USD'=>22267,'EUR'=>24869,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             10 => ['USD'=>22276,'EUR'=>24490,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             11 => ['USD'=>22421,'EUR'=>24159,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             12 => ['USD'=>22676,'EUR'=>23834,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //         ],
    //         2017 => [
    //             1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             9 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             10 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             11 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             12 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //         ],
    //         2018 => [
    //             1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             9 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             10 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             11 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             12 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //         ],
    //         2019 => [
    //             1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             9 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             10 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             11 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //             12 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
    //         ],
    //     ];

    //     // Rates to VND
    //     $xRate = [
    //         'USD'=>[
    //             '2013-01'=>22376, '2013-02'=>22296, '2013-03'=>22263,
    //             '2013-04'=>22258, '2013-05'=>22281, '2013-06'=>22305,
    //             '2013-07'=>22263, '2013-08'=>22261, '2013-09'=>22267,
    //             '2013-10'=>22276, '2013-11'=>22421, '2013-12'=>22676,

    //             '2014-01'=>22376, '2014-02'=>22296, '2014-03'=>22263,
    //             '2014-04'=>22258, '2014-05'=>22281, '2014-06'=>22305,
    //             '2014-07'=>22263, '2014-08'=>22261, '2014-09'=>22267,
    //             '2014-10'=>22276, '2014-11'=>22421, '2014-12'=>22676,

    //             '2015-01'=>22376, '2015-02'=>22296, '2015-03'=>22263,
    //             '2015-04'=>22258, '2015-05'=>22281, '2015-06'=>22305,
    //             '2015-07'=>22263, '2015-08'=>22261, '2015-09'=>22267,
    //             '2015-10'=>22276, '2015-11'=>22421, '2015-12'=>22676,

    //             '2016-01'=>22376, '2016-02'=>22296, '2016-03'=>22263,
    //             '2016-04'=>22258, '2016-05'=>22281, '2016-06'=>22305,
    //             '2016-07'=>22263, '2016-08'=>22261, '2016-09'=>22267,
    //             '2016-10'=>22276, '2016-11'=>22421, '2016-12'=>22676,

    //             '2017-01'=>22563, '2017-02'=>22677, '2017-03'=>22758,
    //             '2017-04'=>22673, '2017-05'=>22675, '2017-06'=>22675,
    //             '2017-07'=>22698, '2017-08'=>22694, '2017-09'=>22694,
    //             '2017-10'=>22694, '2017-11'=>22694, '2017-12'=>22694,
    //     // DEMO
    //             '2018-01'=>22563, '2018-02'=>22677, '2018-03'=>22758,
    //             '2018-04'=>22673, '2018-05'=>22675, '2018-06'=>22675,
    //             '2018-07'=>22698, '2018-08'=>22694, '2018-09'=>22694,
    //             '2018-10'=>22694, '2018-11'=>22694, '2018-12'=>22694,

    //             '2019-01'=>22563, '2019-02'=>22677, '2019-03'=>22758,
    //             '2019-04'=>22673, '2019-05'=>22675, '2019-06'=>22675,
    //             '2019-07'=>22698, '2019-08'=>22694, '2019-09'=>22694,
    //             '2019-10'=>22694, '2019-11'=>22694, '2019-12'=>22694,

    //             '0000-00'=>22694,
    //         ],
    //         'EUR'=>[
    //             '2013-01'=>24223, '2013-02'=>24524, '2013-03'=>24683,
    //             '2013-04'=>25150, '2013-05'=>25075, '2013-06'=>24944,
    //             '2013-07'=>24533, '2013-08'=>24832, '2013-09'=>24869,
    //             '2013-10'=>24490, '2013-11'=>24159, '2013-12'=>23834,

    //             '2014-01'=>24223, '2014-02'=>24524, '2014-03'=>24683,
    //             '2014-04'=>25150, '2014-05'=>25075, '2014-06'=>24944,
    //             '2014-07'=>24533, '2014-08'=>24832, '2014-09'=>24869,
    //             '2014-10'=>24490, '2014-11'=>24159, '2014-12'=>23834,

    //             '2015-01'=>24223, '2015-02'=>24524, '2015-03'=>24683,
    //             '2015-04'=>25150, '2015-05'=>25075, '2015-06'=>24944,
    //             '2015-07'=>24533, '2015-08'=>24832, '2015-09'=>24869,
    //             '2015-10'=>24490, '2015-11'=>24159, '2015-12'=>23834,

    //             '2016-01'=>24223, '2016-02'=>24524, '2016-03'=>24683,
    //             '2016-04'=>25150, '2016-05'=>25075, '2016-06'=>24944,
    //             '2016-07'=>24533, '2016-08'=>24832, '2016-09'=>24869,
    //             '2016-10'=>24490, '2016-11'=>24159, '2016-12'=>23834,

    //             '2017-01'=>23868, '2017-02'=>24076, '2017-03'=>24226,
    //             '2017-04'=>24223, '2017-05'=>24971, '2017-06'=>25364,
    //             '2017-07'=>26078, '2017-08'=>26747, '2017-09'=>26747,
    //             '2017-10'=>26747, '2017-11'=>26747, '2017-12'=>26747,
    //     // DEMO
    //             '2018-01'=>23868, '2018-02'=>24076, '2018-03'=>24226,
    //             '2018-04'=>24223, '2018-05'=>24971, '2018-06'=>25364,
    //             '2018-07'=>26078, '2018-08'=>26747, '2018-09'=>26747,
    //             '2018-10'=>26747, '2018-11'=>26747, '2018-12'=>26747,

    //             '2019-01'=>23868, '2019-02'=>24076, '2019-03'=>24226,
    //             '2019-04'=>24223, '2019-05'=>24971, '2019-06'=>25364,
    //             '2019-07'=>26078, '2019-08'=>26747, '2019-09'=>26747,
    //             '2019-10'=>26747, '2019-11'=>26747, '2019-12'=>26747,

    //             '0000-00'=>26747,

    //         ],

    //     ];

    //     // $result[$yyyy][$mm][$index]
    //     $result = [];
    //     $detail = [];
    //     // 'Số tour', kx, tx, 'Số khách', 'Số ngày',
    //     // 'Số khách BQ /tour', 'Số ngày BQ /tour',
    //     // 'Doanh thu', 'Giá vốn', 'Lợi nhuận',
    //     // 'Doanh thu BQ /tour', 'Giá vốn BQ /tour', 'Lợi nhuận BQ /tour',
    //     // 'Doanh thu BQ /khách', 'Giá vốn BQ /khách', 'Lợi nhuận BQ /khách',
    //     // 'Doanh thu BQ /khách/ngày', 'Giá vốn BQ /khách/ngày', 'Lợi nhuận BQ /khách/ngày',

    //     $query = Product::find()
    //         ->select(['id', 'op_code', 'op_name', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
    //         ->where(['and', ['op_status'=>'op'], 'op_finish!="canceled"'])
    //         ->andWhere('SUBSTRING(op_code,1,1)="F"')
    //         ->with([
    //             'bookings'=>function($q){
    //                 return $q->select(['id', 'product_id', 'case_id', 'pax', 'created_at']);
    //             },
    //             'bookings.case',
    //             'bookings.case.stats',
    //             'bookings.invoices'=>function($q){
    //                 return $q->select(['id', 'booking_id', 'amount', 'currency', 'due_dt', 'stype']);
    //             },
    //             'bookings.invoices.payments'=>function($q){
    //                 return $q->select(['invoice_id', 'amount', 'currency', 'xrate', 'payment_dt']);
    //             },
    //             'bookings.report'=>function($q){
    //                 return $q->select(['booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
    //             },
    //             'tour'=>function($q){
    //                 return $q->select(['id', 'ct_id']);
    //             },
    //             'tour.cpt'=>function($q){
    //                 return $q->select(['tour_id', 'qty', 'price', 'plusminus', 'unitc', 'dvtour_day', 'due']);
    //             },
    //             ])
    //         ;
    //     if ($view == 'tourstart') {
    //         $query->andHaving('YEAR(start_date)=:year', [':year'=>$year]);
    //     } else {
    //         $query->andHaving('YEAR(end_date)=:year', [':year'=>$year]);
    //     }
    //     if ($view == 'tourstart') {
    //         $query->andHaving('YEAR(start_date)=:year', [':year'=>$year]);
    //     }

    //     $theTours = $query
    //         ->asArray()
    //         ->all();

    //     for ($m = 0; $m <= 12; $m ++) {
    //         for ($i = 0; $i <= 20; $i ++) {
    //             // Con so thuc te
    //             $result[$year][$m][$i]['actual'] = 0;
    //             // Con so du tinh, neu co
    //             $result[$year][$m][$i]['estimated'] = 0;
    //             // Con so so sanh, neu co
    //             $result[$year][$m][$i]['comp'] = 0;
    //         }
    //         // Con so tim kiem
    //         $result[$year][$m]['tk'] = 0;
    //         // Ti le % con so tim kiem so voi thuc te
    //         $result[$year][$m]['pc'] = 0;
    //         // Doanh thu nguyen te
    //         $hoadonNguyente[$year][$m] = [];
    //         $thuNguyente[$year][$m] = [];
    //         foreach ($channelList as $k => $channel) {
    //             foreach ($typeList as $type => $tl) {
    //                 $result[$year][$m][$k][$type] = 0;
    //             }
    //         }
    //     }

    //     $xrate = [
    //         'EUR'=>1,
    //         'LAK'=>0.0001,
    //         'KHR'=>0.00021,
    //         'THB'=>0.026,
    //         'USD'=>0.85,
    //         'VND'=>0.000037,
    //     ];

    //     // Cac tham so tim kiem
    //     $sopaxMin = 0;
    //     $sopaxMax = 0;
    //     if ($sopax != '') {
    //         $sopaxArr = explode('-', $sopax);
    //         $sopaxMin = (int)trim($sopaxArr[0]);
    //         if (count($sopaxArr) == 2) {
    //             $sopaxMax = (int)trim($sopaxArr[1] ?? '0');
    //         }
    //         else {
    //             $sopaxMax = $sopaxMin;
    //         }
    //     }

    //     $songayMin = 0;
    //     $songayMax = 0;
    //     if ($songay != '') {
    //         $songayArr = explode('-', $songay);
    //         $songayMin = (int)trim($songayArr[0]);
    //         $songayMax = (int)trim($songayArr[1] ?? '0');
    //     }
    //     foreach ($theTours as $tour) {
    //         if (count($tour['bookings']) > 1) {
    //             var_dump($tour['bookings']);die;
    //         }
    //         $kx = $tour['bookings'][0]['case']['stats']['kx'];
    //         $tx = $tour['bookings'][0]['case']['how_found'];
    //         if ($kx == '') {
    //             $kx = 'k0';
    //         }
    //         if ($tx == '') {
    //             $tx = 'unknown';
    //         }
    //         // Thong so cua tour nay, neu thoa cac dieu kien tim kiem thi moi cho vao ket qua cuoi cung
    //         foreach ($indexList as $i=>$index) {
    //             $tourStat[$i] = [
    //                 'actual'=>0,
    //                 'estimated'=>0
    //             ];
    //         }

    //         if ($view == 'tourstart') {
    //             $month = (int)substr($tour['start_date'], 5, 2);
    //         } else {
    //             $month = (int)substr($tour['end_date'], 5, 2);
    //         }

    //         // test source

    //         foreach ($typeList as $type => $tp) {
    //             if (strpos($tx, $type) !== false) {
    //                 $tx = $type;
    //             }
    //         }
    //         if ((isset($kx_source) && !empty($kx_source) && !in_array($kx, $kx_source))
    //             || (isset($tx_source) && !empty($tx_source) && !in_array($tx, $tx_source))
    //         ) {
    //             continue;
    //         }
    //         if (!isset($result[$year][$month][$kx][$tx])) {
    //             var_dump($month);
    //             var_dump($kx);
    //             var_dump($ty);
    //             var_dump($result[$year][$month][$kx][$ty]);
    //             die;
    //         }

    //         $result[$year][$month][$kx][$tx] ++;

    //         // So tour
    //         $tourStat[0]['actual'] = 1;
    //         // So ngay
    //         $tourStat[2]['actual'] = $tour['day_count'];

    //         foreach ($tour['bookings'] as $booking) {
    //             // So khach
    //             $tourStat[1]['actual'] += $booking['pax'];

    //             // Doanh thu - thuc te
    //             foreach ($booking['invoices'] as $invoice) {
    //                 if (!isset($hoadonNguyente[$year][$month][$invoice['currency']])) {
    //                     $hoadonNguyente[$year][$month][$invoice['currency']] = 0;
    //                 }
    //                 $hoadonNguyente[$year][$month][$invoice['currency']] += $invoice['stype'] == 'credit' ? -$invoice['amount'] : $invoice['amount'];
    //                 // echo '<br>HDON THANG ', $month, ' += ', number_format($invoice['amount']), ' ', $invoice['currency'];

    //                 $cu = $invoice['currency'];
    //                 $mo = substr($invoice['due_dt'], 0, 7);
    //                 if ($cu == $currency) {
    //                     // Cung loai tien xem ket qua
    //                     $am = $invoice['amount'];
    //                 } else {
    //                     if ($currency == 'VND') {
    //                         $am = $xRate[$cu][$mo] * $invoice['amount'];
    //                     } else {
    //                         $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $invoice['amount'];
    //                     }
    //                 }

    //                 if ($invoice['stype'] == 'credit') {
    //                     $am = -$am;
    //                 }

    //                 $tourStat[5]['estimated'] += $am;

    //                 if ($month == 12 && USER_ID == 1) {
    //                     // echo '<br>', $mo, ': ', $invoice['amount'], ' ', $invoice['currency'], ' (x', $xRate[$cu][$mo] ?? 1, ') = ', number_format($am);
    //                     // echo ' ==> ', number_format($result[$year][$month][5]['estimated']);
    //                 }

    //                 foreach ($invoice['payments'] as $payment) {
    //                     if (!isset($thuNguyente[$year][$month][$payment['currency']])) {
    //                         $thuNguyente[$year][$month][$payment['currency']] = 0;
    //                     }
    //                     $thuNguyente[$year][$month][$payment['currency']] += $invoice['stype'] == 'credit' ? -$payment['amount'] : $payment['amount'];
    //                     // if ($month == 9) {
    //                     //     echo '<br>--------------- THU THANG ', $month, ' += ', number_format($payment['amount']), ' ', $payment['currency'];
    //                     // }

    //                     // TODO: use payment's exchange rate

    //                     $cu = $payment['currency'];
    //                     $mo = substr($payment['payment_dt'], 0, 7);
    //                     if ($cu == $currency) {
    //                         // Cung loai tien xem ket qua
    //                         $am = $payment['amount'];
    //                     } else {
    //                         if ($currency == 'VND') {
    //                             $am = ($payment['xrate'] > 1 ? $payment['xrate'] : $xRate[$cu][$mo]) * $payment['amount'];
    //                         } else {
    //                             $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $payment['amount'];
    //                         }
    //                     }

    //                     if ($invoice['stype'] == 'credit') {
    //                         $am = -$am;
    //                     }

    //                     $tourStat[5]['actual'] += $am;
    //                 }
    //             }

    //             // Gia von - du tinh
    //             if ($booking['report']) {
    //                 $cu = $booking['report']['cost_unit'];
    //                 $mo = substr($booking['created_at'], 0, 7);
    //                 if ($cu == $currency) {
    //                     // Cung loai tien xem ket qua
    //                     $am = $booking['report']['cost'];
    //                 } else {
    //                     if ($currency == 'VND') {
    //                         if (!isset($xRate[$cu][$mo])) {
    //                             echo $cu, '/', $mo;
    //                             exit;
    //                         }
    //                         $am = $xRate[$cu][$mo] * $booking['report']['cost'];
    //                     } else {
    //                         $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $booking['report']['cost'];
    //                     }
    //                 }

    //                 $tourStat[6]['estimated'] += $am;
    //             }
    //         }

    //         if (!empty($tour['tour']['cpt'])) {

    //             foreach ($tour['tour']['cpt'] as $cpt) {
    //                 $cu = $cpt['unitc'];
    //                 $mo = substr($cpt['due'] == '0000-00-00' ? $cpt['dvtour_day'] : $cpt['due'], 0, 7);

    //                 if ($cu == $currency) {
    //                     // Cung loai tien xem ket qua
    //                     $am = $cpt['qty'] * $cpt['price'];
    //                 } else {
    //                     if ($currency == 'VND') {
    //                         $am = ($xRate[$cu][$mo] ?? $xrate[$cu]) * $cpt['qty'] * $cpt['price'];
    //                     } else {
    //                         if (isset($xRate[$currency][$mo])) {
    //                             $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $cpt['qty'] * $cpt['price'];
    //                         } else {
    //                             // Mot so loai tien khong co ti gia ke toan
    //                             $am = $xrate[$cu] / $xrate[$currency] * $cpt['qty'] * $cpt['price'];
    //                         }
    //                     }
    //                 }

    //                 if ($cpt['plusminus'] == 'minus') {
    //                     $am = -$am;
    //                 }

    //                 $tourStat[6]['actual'] += $am;
    //             }
    //         }

    //         // Loi nhuan
    //         $tourStat[7]['actual'] = $tourStat[5]['actual'] - $tourStat[6]['actual'];
    //         $tourStat[7]['estimated'] = $tourStat[5]['estimated'] - $tourStat[6]['estimated'];

    //         // Kiem tra dieu kien tim kiem
    //         $songayOk = false;
    //         $sopaxOk = true;
    //         $sourceOk = true;
    //         $diemdenOk = true;

    //         if ($sopax != '' && ($tourStat[1]['actual'] < $sopaxMin || $tourStat[1]['actual'] > $sopaxMax)) {
    //             $sopaxOk = false;
    //         }
    //         if ($songay == '' || (($songayMin != 0 || $songayMax !=0) && $songayMin <= $tour['day_count'] && $tour['day_count'] <= $songayMax)) {
    //             $songayOk = true;
    //         }


    //         if ((isset($kx_source) && !empty($kx_source) && !in_array($kx, $kx_source))
    //             || (isset($tx_source) && !empty($tx_source) && !in_array($tx, $tx_source))
    //         ) {
    //             $sourceOk = false;
    //         }
    //         if (isset($diemden) && is_array($diemden) && !empty($diemden)) {

    //             $tour_countries = $tour['bookings'][0]['case']['stats']['req_countries'];
    //             if ($dkdiemden == 'all' || $dkdiemden == 'only') {

    //                 foreach ($diemden as $dest) {
    //                     if (strpos($tour_countries, $dest) === false) {
    //                         $diemdenOk = false;
    //                     }
    //                 }
    //                 if ($dkdiemden == 'only') {
    //                     if (strlen($tour_countries) != 2 * count($diemden) + count($diemden) - 1) {
    //                         $diemdenOk = false;
    //                     }
    //                 }
    //             } elseif ($dkdiemden == 'any') {
    //                 $orConditions = '(';
    //                 foreach ($diemden as $dest) {
    //                     if (strpos($tour_countries, $dest) !== false) {
    //                         $diemdenOk = true;
    //                         break;
    //                     } else {
    //                         $diemdenOk = false;
    //                     }
    //                 }
    //             } else {
    //                 // Exact
    //                 asort($diemden);
    //                 $destList = implode('|', $diemden);
    //                if ($tour_countries != $destList) {
    //                 $diemdenOk = false;
    //                }
    //             }
    //         }

    //         $filterOk = $sopaxOk && $songayOk && $sourceOk;

    //         if ($filterOk) {
    //             // Tour nay thoa dieu kien tim kiem, cho vao ket qua chung
    //             foreach ($indexList as $i=>$index) {
    //                 $result[$year][$month][$i]['actual'] += $tourStat[$i]['actual'] ?? 0;
    //                 $result[$year][$month][$i]['estimated'] += $tourStat[$i]['estimated'] ?? 0;
    //             }
    //             // $result[$year][$month]['tk'] ++;
    //             // $result[$year][$month]['pc'] = $result[$year][$month][0] == 0 ? 0 : 100 * ($result[$year][$month]['tk'] / $result[$year][$month][0]);

    //             if (!isset($detail[$month])) {
    //                 $detail[$month] = [];
    //             }
    //             $detail[$month][] = [
    //                 $tour['id'],
    //                 $tour['op_code'],
    //                 $tour['op_name'],
    //                 $tourStat[5]['actual'],
    //                 $tourStat[6]['actual'],
    //                 $tourStat[2]['actual'],
    //                 $tourStat[1]['actual'],
    //                 $kx,
    //                 $tx,
    //             ];


    //         }
    //     }
    //     for ($m = 1; $m <= 12; $m ++) {
    //         // Tinh bang cong thuc tu dong cho cac index
    //         foreach ($indexList as $i=>$index) {
    //             if (isset($index['avg']) && is_array($index['avg'])) {
    //                 // Average
    //                 $result[$year][$m][$i]['actual'] = $result[$year][$m][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['actual'] / $result[$year][$m][$index['avg'][1]]['actual'];
    //                 if (isset($indexList[$i]['est'])) {
    //                     if (isset($indexList[$index['avg'][1]]['est'])) {
    //                         $result[$year][$m][$i]['estimated'] = $result[$year][$m][$index['avg'][1]]['estimated'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['estimated'] / $result[$year][$m][$index['avg'][1]]['estimated'];
    //                     } else {
    //                         $result[$year][$m][$i]['estimated'] = $result[$year][$m][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['estimated'] / $result[$year][$m][$index['avg'][1]]['actual'];
    //                     }
    //                 }
    //                 // For markup
    //                 if (isset($index['minus1'])) {
    //                     $result[$year][$m][$i]['actual'] -= 1;
    //                     $result[$year][$m][$i]['estimated'] -= 1;
    //                 }
    //                 // For percentage
    //                 if (isset($index['pct'])) {
    //                     $result[$year][$m][$i]['actual'] *= 100;
    //                     $result[$year][$m][$i]['estimated'] *= 100;
    //                 }
    //             }
    //         }
    //     }

    //     // Year total
    //     foreach ($indexList as $i=>$index) {
    //         if (isset($index['avg']) && is_array($index['avg'])) {
    //             // Average
    //             $result[$year][0][$i]['actual'] = $result[$year][0][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['actual'] / $result[$year][0][$index['avg'][1]]['actual'];
    //             if (isset($indexList[$i]['est'])) {
    //                 if (isset($indexList[$index['avg'][1]]['est'])) {
    //                     $result[$year][0][$i]['estimated'] = $result[$year][0][$index['avg'][1]]['estimated'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['estimated'] / $result[$year][0][$index['avg'][1]]['estimated'];
    //                 } else {
    //                     $result[$year][0][$i]['estimated'] = $result[$year][0][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['estimated'] / $result[$year][0][$index['avg'][1]]['actual'];
    //                 }
    //             }
    //             // For markup
    //             if (isset($index['minus1'])) {
    //                 $result[$year][0][$i]['actual'] -= 1;
    //                 $result[$year][0][$i]['estimated'] -= 1;
    //             }
    //             // For percentage
    //             if (isset($index['pct'])) {
    //                 $result[$year][0][$i]['actual'] *= 100;
    //                 $result[$year][0][$i]['estimated'] *= 100;
    //             }
    //         } else {
    //             // Total
    //             for ($m = 1; $m <= 12; $m ++) {
    //                 $result[$year][0][$i]['actual'] += $result[$year][$m][$i]['actual'];
    //                 $result[$year][0][$i]['estimated'] += $result[$year][$m][$i]['estimated'];
    //             }
    //         }

    //     }


    //     // Binh quan
    //     // So pax
    //     // $result[$year][0][3]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][1]['actual'] / $result[$year][0][0]['actual'];
    //     // So ngay
    //     // $result[$year][0][4]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
    //     // Ti le lai
    //     // $result[$year][0][17]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
    //     // Ti le markup
    //     // $result[$year][0][18]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];

    //     // Doanh thu BQ/tour
    //     // $result[$year][0][8]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][5]['actual'] / $result[$year][0][0]['actual'];
    //     // Chi phi BQ/pax
    //     // $result[$year][0][9]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][6]['actual'] / $result[$year][0][0]['actual'];
    //     // Loi nhuan BQ/pax
    //     // $result[$year][0][10]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][7]['actual'] / $result[$year][0][0]['actual'];

    //     // Doanh thu BQ/pax
    //     // $result[$year][0][11]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
    //     // Chi phi BQ/pax
    //     // $result[$year][0][12]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
    //     // Loi nhuan BQ/pax
    //     // $result[$year][0][13]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];

    //     // Ti le markup
    //     // $result[$year][0][14]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
    //     // Ti le markup
    //     // $result[$year][0][15]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
    //     // Ti le markup
    //     // $result[$year][0][16]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];

    //     if ($year2 != 0 && $year2 != $year) {
    //         $query2 = Product::find()
    //             ->select(['id', 'op_code', 'op_name', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
    //             ->where(['and', ['op_status'=>'op'], 'op_finish!="canceled"'])
    //             ->andWhere('SUBSTRING(op_code,1,1)="F"')
    //             ->with([
    //                 'bookings'=>function($q){
    //                     return $q->select(['id', 'product_id', 'pax', 'created_at']);
    //                 },
    //                 'bookings.invoices'=>function($q){
    //                     return $q->select(['id', 'booking_id', 'amount', 'currency', 'due_dt', 'stype']);
    //                 },
    //                 'bookings.invoices.payments'=>function($q){
    //                     return $q->select(['invoice_id', 'amount', 'currency', 'xrate', 'payment_dt']);
    //                 },
    //                 'bookings.report'=>function($q){
    //                     return $q->select(['booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
    //                 },
    //                 'tour'=>function($q){
    //                     return $q->select(['id', 'ct_id']);
    //                 },
    //                 'tour.cpt'=>function($q){
    //                     return $q->select(['tour_id', 'qty', 'price', 'plusminus', 'unitc', 'dvtour_day', 'due']);
    //                 },
    //                 ])
    //             ;
    //         if ($view == 'tourstart') {
    //             $query2->andHaving('YEAR(start_date)=:year', [':year'=>$year2]);
    //         } else {
    //             $query2->andHaving('YEAR(end_date)=:year', [':year'=>$year2]);
    //         }

    //         $theTours2 = $query2
    //             ->asArray()
    //             ->all();

    //     foreach ($theTours2 as $tour) {
    //         // Thong so cua tour nay, neu thoa cac dieu kien tim kiem thi moi cho vao ket qua cuoi cung
    //         foreach ($indexList as $i=>$index) {
    //             $tourStat[$i] = [
    //                 'comp'=>0,
    //             ];
    //         }

    //         if ($view == 'tourstart') {
    //             $month = (int)substr($tour['start_date'], 5, 2);
    //         } else {
    //             $month = (int)substr($tour['end_date'], 5, 2);
    //         }

    //         // So tour
    //         $tourStat[0]['comp'] = 1;
    //         // So ngay
    //         $tourStat[2]['comp'] = $tour['day_count'];

    //         foreach ($tour['bookings'] as $booking) {
    //             // So khach
    //             $tourStat[1]['comp'] += $booking['pax'];

    //             // Doanh thu - thuc te
    //             foreach ($booking['invoices'] as $invoice) {
    //                 // if (!isset($hoadonNguyente[$year][$month][$invoice['currency']])) {
    //                 //     $hoadonNguyente[$year][$month][$invoice['currency']] = 0;
    //                 // }
    //                 // $hoadonNguyente[$year][$month][$invoice['currency']] += $invoice['stype'] == 'credit' ? -$invoice['amount'] : $invoice['amount'];

    //                 $cu = $invoice['currency'];
    //                 $mo = substr($invoice['due_dt'], 0, 7);
    //                 if ($cu == $currency) {
    //                     // Cung loai tien xem ket qua
    //                     $am = $invoice['amount'];
    //                 } else {
    //                     if ($currency == 'VND') {
    //                         $am = $xRate[$cu][$mo] * $invoice['amount'];
    //                     } else {
    //                         $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $invoice['amount'];
    //                     }
    //                 }

    //                 if ($invoice['stype'] == 'credit') {
    //                     $am = -$am;
    //                 }

    //                 // $tourStat[5]['estimated'] += $am;

    //                 foreach ($invoice['payments'] as $payment) {
    //                     // if (!isset($thuNguyente[$year][$month][$payment['currency']])) {
    //                     //     $thuNguyente[$year][$month][$payment['currency']] = 0;
    //                     // }
    //                     // $thuNguyente[$year][$month][$payment['currency']] += $invoice['stype'] == 'credit' ? -$payment['amount'] : $payment['amount'];

    //                     $cu = $payment['currency'];
    //                     $mo = substr($payment['payment_dt'], 0, 7);
    //                     if ($cu == $currency) {
    //                         // Cung loai tien xem ket qua
    //                         $am = $payment['amount'];
    //                     } else {
    //                         if ($currency == 'VND') {
    //                             $am = ($payment['xrate'] > 1 ? $payment['xrate'] : $xRate[$cu][$mo]) * $payment['amount'];
    //                         } else {
    //                             $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $payment['amount'];
    //                         }
    //                     }

    //                     if ($invoice['stype'] == 'credit') {
    //                         $am = -$am;
    //                     }

    //                     $tourStat[5]['comp'] += $am;
    //                 }
    //             }

    //             // Gia von - du tinh
    //             if ($booking['report']) {
    //                 $cu = $booking['report']['cost_unit'];
    //                 $mo = substr($booking['created_at'], 0, 7);
    //                 if ($cu == $currency) {
    //                     // Cung loai tien xem ket qua
    //                     $am = $booking['report']['cost'];
    //                 } else {
    //                     if ($currency == 'VND') {
    //                         if (!isset($xRate[$cu][$mo])) {
    //                             echo $cu, '/', $mo;
    //                             exit;
    //                         }
    //                         $am = $xRate[$cu][$mo] * $booking['report']['cost'];
    //                     } else {
    //                         $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $booking['report']['cost'];
    //                     }
    //                 }

    //                 // $tourStat[6]['estimated'] += $am;
    //             }
    //         }

    //         if (!empty($tour['tour']['cpt'])) {

    //             foreach ($tour['tour']['cpt'] as $cpt) {
    //                 $cu = $cpt['unitc'];
    //                 $mo = substr($cpt['due'] == '0000-00-00' ? $cpt['dvtour_day'] : $cpt['due'], 0, 7);

    //                 if ($cu == $currency) {
    //                     // Cung loai tien xem ket qua
    //                     $am = $cpt['qty'] * $cpt['price'];
    //                 } else {
    //                     if ($currency == 'VND') {
    //                         $am = ($xRate[$cu][$mo] ?? $xrate[$cu]) * $cpt['qty'] * $cpt['price'];
    //                     } else {
    //                         if (isset($xRate[$currency][$mo])) {
    //                             $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $cpt['qty'] * $cpt['price'];
    //                         } else {
    //                             // Mot so loai tien khong co ti gia ke toan
    //                             $am = $xate[$cu] / $xrate[$currency] * $cpt['qty'] * $cpt['price'];
    //                         }
    //                     }
    //                 }

    //                 if ($cpt['plusminus'] == 'minus') {
    //                     $am = -$am;
    //                 }

    //                 $tourStat[6]['comp'] += $am;
    //             }
    //         }

    //         // Loi nhuan
    //         $tourStat[7]['comp'] = $tourStat[5]['comp'] - $tourStat[6]['comp'];
    //         // $tourStat[7]['estimated'] = $tourStat[5]['estimated'] - $tourStat[6]['estimated'];

    //         // Kiem tra dieu kien tim kiem
    //         $songayOk = false;
    //         $sopaxOk = true;

    //         if ($sopax != '' && ($tourStat[1]['comp'] < $sopaxMin || $tourStat[1]['comp'] > $sopaxMax)) {
    //             $sopaxOk = false;
    //         }
    //         if ($songay == '' || (($songayMin != 0 || $songayMax !=0) && $songayMin <= $tour['day_count'] && $tour['day_count'] <= $songayMax)) {
    //             $songayOk = true;
    //         }

    //         $filterOk = $sopaxOk && $songayOk;

    //         if ($filterOk) {
    //             // Tour nay thoa dieu kien tim kiem, cho vao ket qua chung
    //             foreach ($indexList as $i=>$index) {
    //                 $result[$year][$month][$i]['comp'] += $tourStat[$i]['comp'] ?? 0;
    //                 // $result[$year][$month][$i]['estimated'] += $tourStat[$i]['estimated'] ?? 0;
    //             }
    //             // $result[$year][$month]['tk'] ++;
    //             // $result[$year][$month]['pc'] = $result[$year][$month][0] == 0 ? 0 : 100 * ($result[$year][$month]['tk'] / $result[$year][$month][0]);

    //             // if (!isset($detail[$month])) {
    //             //     $detail[$month] = [];
    //             // }
    //             // $detail[$month][] = [
    //             //     $tour['id'],
    //             //     $tour['op_code'],
    //             //     $tour['op_name'],
    //             //     $tourStat[5]['actual'],
    //             //     $tourStat[6]['actual'],
    //             //     $tourStat[2]['actual'],
    //             //     $tourStat[1]['actual'],
    //             // ];


    //         }
    //     }

    //     for ($m = 1; $m <= 12; $m ++) {
    //         // Tinh bang cong thuc tu dong cho cac index
    //         foreach ($indexList as $i=>$index) {
    //             if (isset($index['avg']) && is_array($index['avg'])) {
    //                 // Average
    //                 $result[$year][$m][$i]['comp'] = $result[$year][$m][$index['avg'][1]]['comp'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['comp'] / $result[$year][$m][$index['avg'][1]]['comp'];

    //                 // For markup
    //                 if (isset($index['minus1'])) {
    //                     $result[$year][$m][$i]['comp'] -= 1;
    //                 }
    //                 // For percentage
    //                 if (isset($index['pct'])) {
    //                     $result[$year][$m][$i]['comp'] *= 100;
    //                 }
    //             }
    //         }
    //     }

    //     // Year total
    //     foreach ($indexList as $i=>$index) {
    //         if (isset($index['avg']) && is_array($index['avg'])) {
    //             // Average
    //             $result[$year][0][$i]['comp'] = $result[$year][0][$index['avg'][1]]['comp'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['comp'] / $result[$year][0][$index['avg'][1]]['comp'];
    //             // For markup
    //             if (isset($index['minus1'])) {
    //                 $result[$year][0][$i]['comp'] -= 1;
    //             }
    //             // For percentage
    //             if (isset($index['pct'])) {
    //                 $result[$year][0][$i]['comp'] *= 100;
    //             }
    //         } else {
    //             // Total
    //             for ($m = 1; $m <= 12; $m ++) {
    //                 $result[$year][0][$i]['comp'] += $result[$year][$m][$i]['comp'];
    //             }
    //         }
    //     }

    //     } // if year2
    //     if (isset($_GET['export-data'])) {
    //         $spreadsheet = new Spreadsheet();
    //         $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
    //         $spreadsheet->getActiveSheet()->setCellValue('A1', 'Source');
    //         $columnIndex = 2;
    //         for ($m = 1; $m <= 13; $m ++) {
    //             $spreadsheet->getActiveSheet()->mergeCells($this->stringFromColumnIndex($columnIndex).'1:' . $this->stringFromColumnIndex($columnIndex + 2) . '1');
    //             $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex).'1', ($m <= 12) ? $m : 'total');
    //             $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex).'2', 'New');
    //             $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex + 1).'2', 'Returning');
    //             $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex + 2).'2', 'Referred');
    //             $columnIndex += 3;
    //         }
    //         $k = 3;
    //         foreach ($channelList as $kx => $channel) {
    //             $arr_row = [];
    //             $arr_row[] = $kx;
    //             $total_year['new'] = 0;
    //             $total_year['returning'] = 0;
    //             $total_year['referred'] = 0;
    //             $spreadsheet->getActiveSheet()->setCellValue('A'.$k, $kx);
    //             for ($m = 1; $m <= 12; $m ++) {
    //                 $total_year['new'] += $result[$year][$m][$kx]['new'];
    //                 $total_year['returning'] += $result[$year][$m][$kx]['returning'];
    //                 $total_year['referred'] += $result[$year][$m][$kx]['returning'];
    //                 $arr_row[] = $result[$year][$m][$kx]['new'];
    //                 $arr_row[] = $result[$year][$m][$kx]['returning'];
    //                 $arr_row[] = $result[$year][$m][$kx]['referred'];
    //             }

    //             $arr_row[] = $total_year['new'];
    //             $arr_row[] = $total_year['returning'];
    //             $arr_row[] = $total_year['referred'];
    //             $spreadsheet->getActiveSheet()->fromArray($arr_row, null, 'A'.$k);
    //             $k++;
    //         }
    //         // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //         // $writer->save("05featuredemo.xlsx");
    //         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //         header('Content-Disposition: attachment;filename='. rand(1, 100) . 'report.Xlsx');

    //         $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //         $writer->save('php://output');
    //         exit;
    //     }

    //     return $this->render('report_b2c', [
    //         'tourCount'=>count($theTours),
    //         'indexList'=>$indexList,
    //         'xrateTable'=>$arr_xrate,
    //         'result'=>$result,
    //         'detail'=>$detail,
    //         'view'=>$view,
    //         'year'=>$year,
    //         'year2'=>$year2,
    //         'currency'=>$currency,
    //         'xrate'=>$xrate,
    //         'sopax'=>$sopax,
    //         'songay'=>$songay,
    //         'doanhthu'=>$doanhthu,
    //         'loinhuan'=>$loinhuan,
    //         'diemden'=>$diemden,
    //         'dkdiemden'=>$dkdiemden,
    //         'hoadonNguyente'=>$hoadonNguyente,
    //         'thuNguyente'=>$thuNguyente,

    //         'channelList' => $channelList,
    //         'typeList' =>$typeList,
    //         'kx_source' => $kx_source,
    //         'tx_source' => $tx_source
    //     ]);
    // }
    public static function stringFromColumnIndex($columnIndex)
    {
        static $indexCache = [];
        if (!isset($indexCache[$columnIndex])) {
            $indexValue = $columnIndex;
            $base26 = null;
            do {
                $characterValue = ($indexValue % 26) ?: 26;
                $indexValue = ($indexValue - $characterValue) / 26;
                $base26 = chr($characterValue + 64) . ($base26 ?: '');
            } while ($indexValue > 0);
            $indexCache[$columnIndex] = $base26;
        }
        return $indexCache[$columnIndex];
    }

    // $objPHPExcel->getActiveSheet()->mergeCells(cellsToMergeByColsRow(0,2,3))

    /**
     * Đếm số HS theo kênh và loại
     */
    public function actionMkt05($action = 'cost', $view = '', $year = '', $month = '', $date_end = '', $seller = '')
    {
        if (!in_array($action, ['cost', 'view', 'excel', 'update',])) {
            $action == 'view';
            // View list of cases + costs created in 2014-2018 and tour end dates in year-month
            // View costs for cases created in a date range (per Kx-Tx)
            // Export cost details to Excel for Accounting Dept.
            // Update costs for cases created in a date range (per Kx-Tx)
        }

        $channelList = [
            'k1'=>['id'=>'k1', 'name'=>'K1', 'description'=>'Google Adwords'],
            'k2'=>['id'=>'k2', 'name'=>'K2', 'description'=>'Bing Ads'],
            'k3'=>['id'=>'k3', 'name'=>'K3', 'description'=>'Other web search'],
            'k4'=>['id'=>'k4', 'name'=>'K4', 'description'=>'Referral + Ads online + Other web which source could not be determined'],
            'k5'=>['id'=>'k5', 'name'=>'K5', 'description'=>'Direct access'],
            'k6'=>['id'=>'k6', 'name'=>'K6', 'description'=>'Mailing'],
            'k7'=>['id'=>'k7', 'name'=>'K7', 'description'=>'Non-web'],
            'k8'=>['id'=>'k8', 'name'=>'K8', 'description'=>'Other special cases'],
            ''=>['id'=>'k0', 'name'=>'No channel', 'description'=>'No channel data entered'],
        ];

        $typeList = [
            'new'=>['id'=>'new', 'name'=>'New', 'description'=>'New customer'],
            'referred'=>['id'=>'referred', 'name'=>'Referred', 'description'=>'Referred customer'],
            'returning'=>['id'=>'returning', 'name'=>'Returning', 'description'=>'Returning customer'],
        ];
        $noTypeList = [
            ''=>['id'=>'t0', 'name'=>'No type data', 'description'=>'Data not entered'],
        ];


        if ($action == 'cost') {
            if (!in_array($view, ['ok', 'nok', 'all'])) {
                $view = 'ok';
            }
            if ($view == 'ok') {
                $kxCostCond = 'kx_cost IS NOT NULL';
            } elseif ($view == 'nok') {
                $kxCostCond = 'kx_cost IS NULL';
            } else {
                $kxCostCond = '1=1';
            }

            for ($yr = 2014; $yr <= date('Y'); $yr ++) {
                $yearList[$yr] = $yr;
            }

            if ($year == '' && (strlen($date_end) != 4 && strlen($date_end) != 7)) {
                $year = date('Y');
            }

            $dateEndYmList = Yii::$app->db->createCommand('SELECT SUBSTRING(tour_end_date, 1, 7) AS ym FROM at_case_stats WHERE YEAR(tour_end_date)>=2014 GROUP BY ym ORDER BY ym DESC')->queryColumn();
            foreach ($dateEndYmList as $ym) {
                $yr = substr($ym, 0, 4);
                if (!isset($dateEndList[$yr])) {
                    $dateEndList[$yr] = $yr.' - All year';
                }
                $dateEndList[$ym] = $ym;
            }

            // Cac HS tao trong thoi gian
            $query = Kase::find()
                ->innerJoinWith('stats')
                ->select(['id', 'kx', 'tx'=>'SUBSTRING_INDEX(how_found, "/", 1)', 'kx_cost'])
                ->where(['is_b2b'=>'no'])
                ->andWhere($kxCostCond);
            if ($year != '') {
                $query->andWhere(['YEAR(created_at)'=>$year]);
            }
            if (strlen($date_end) == 4 || strlen($date_end) == 7) {
                $query->andWhere('SUBSTRING(tour_end_date,1,'.strlen($date_end).')=:de', [':de'=>$date_end]);
            }
            if ($seller != '') {
                $query->andWhere('owner_id=:se', [':se'=>$seller]);
            }

            $cases = $query
                ->asArray()
                ->all();

            // Danh sach ban hang
            $sql = 'SELECT u.id, IF(u.status="on", "Active", "Retired") AS status, u.nickname AS name FROM users u, at_cases k WHERE YEAR(k.created_at)>=2014 AND k.is_b2b="no" AND k.owner_id=u.id GROUP BY u.id ORDER BY u.lname';
            $allSellerList = Yii::$app->db->createCommand($sql)->queryAll();
            $sellerList = \yii\helpers\ArrayHelper::map($allSellerList, 'id', 'name', 'status');
            // $sellerList = array_merge([

            // ], $sellerList);

            $data = [];
            $data[$year]['allchannels']['alltypes'] = [
                'count'=>0,
                'total'=>0,
                'avg'=>0,
            ];
            foreach ($channelList as $channelId=>$channels) {
                $data[$year][$channelId]['alltypes'] = [
                    'count'=>0,
                    'total'=>0,
                    'avg'=>0,
                ];
                foreach (array_merge($typeList, $noTypeList) as $typeId=>$types) {
                    $data[$year]['allchannels'][$typeId] = [
                        'count'=>0,
                        'total'=>0,
                        'avg'=>0,
                    ];
                    $data[$year][$channelId][$typeId] = [
                        'count'=>0,
                        'total'=>0,
                        'avg'=>0,
                    ];
                }
            }

            // \fCore::expose(count($cases)); //exit;
            foreach ($cases as $case) {
                $data[$year]['allchannels']['alltypes']['count'] ++;
                $data[$year]['allchannels']['alltypes']['total'] += $case['kx_cost'];

                // if (isset($data[$year][$case['kx']][$case['tx']])) {
                    $data[$year][$case['kx']][$case['tx']]['count'] ++;
                    $data[$year][$case['kx']][$case['tx']]['total'] += $case['kx_cost'];

                    $data[$year]['allchannels'][$case['tx']]['count'] ++;
                    $data[$year]['allchannels'][$case['tx']]['total'] += $case['kx_cost'];

                    $data[$year][$case['kx']]['alltypes']['count'] ++;
                    $data[$year][$case['kx']]['alltypes']['total'] += $case['kx_cost'];
                //}
            }

            $vars = [
                'action'=>$action,
                'channelList'=>$channelList,
                'typeList'=>$typeList,
                'view'=>$view,
                'year'=>$year,
                'yearList'=>$yearList,
                'date_end'=>$date_end,
                'dateEndList'=>$dateEndList,
                'seller'=>$seller,
                'sellerList'=>$sellerList,
                'data'=>$data,
            ];
        }

        if ($action == 'excel') {
            if ($year == '') {
                $year = date('Y');
            }
            if ($month == '') {
                $month = date('n');
            }
            // Excel table for accounting dept.
            $cases = Kase::find()
                ->select(['id', 'owner_id', 'kx_cost', 'kx', 'tx'=>'SUBSTRING_INDEX(how_found, "/", 1)'])
                ->innerJoinWith('stats')
                ->where(['is_b2b'=>'no'])
                ->andWhere(['YEAR(tour_end_date)'=>$year, 'MONTH(tour_end_date)'=>$month])
                ->asArray()
                ->all();

            $sellerIdList = \yii\helpers\ArrayHelper::getColumn($cases, 'owner_id');
            $sellerIdList = array_unique($sellerIdList);

            $data = [];
            foreach ($sellerIdList as $sellerId) {
                foreach ($channelList as $channelId=>$channels) {
                    foreach ($typeList as $typeId=>$types) {
                        $data[$sellerId][$channelId][$typeId] = [
                            'num'=>0,
                            'cost'=>0,
                        ];
                    }
                    $data[$sellerId][$channelId]['total'] = 0;
                }
            }
            foreach ($cases as $case) {
                $se = $case['owner_id'];
                $kx = $case['kx'];
                $tx = $case['tx'];
                if (!isset($data[$se][$kx][$tx])) {
                    $data[$se][$kx][$tx] = [
                        'num'=>0,
                        'cost'=>0,
                    ];
                }
                $data[$se][$kx][$tx]['num'] ++;
                $data[$se][$kx][$tx]['cost'] += $case['kx_cost'];

                $data[$se][$kx]['total'] ++;
            }
            $sellerIdList = array_keys($data);
            $sellerList = User::find()
                ->select(['id', 'name'])
                ->where(['id'=>$sellerIdList])
                ->indexBy('id')
                ->asArray()
                ->all();

            // \fCore::expose($data); exit;

            $vars = [
                'channelList'=>$channelList,
                'typeList'=>$typeList,
                'action'=>$action,
                'month'=>$month,
                'year'=>$year,
                'data'=>$data,
                'sellerList'=>$sellerList,
            ];
        }

        if ($action == 'update') {
            $year = date('Y');
            $yearList = [$year];
            $month = date('Y-m');

            if (Yii::$app->request->isPost && isset($_POST['dates'], $_POST['cost'], $_POST['currency']) && $_POST['dates'] != '') {
                if (!in_array(USER_ID, [1, 695])) {
                    throw new HttpException(403, 'Access denied.');
                }

                $dates = explode(' -- ', $_POST['dates']);

                $cases = Kase::find()
                    ->select('id, kx, kx_cost, how_found')
                    ->innerJoinWith('stats')
                    ->where(['is_b2b'=>'no'])
                    ->andWhere('created_at>=:d1 AND created_at<=:d2', [':d1'=>$dates[0], ':d2'=>$dates[1].' 23:59:59'])
                    ->andWhere(isset($_POST['newonly']) && $_POST['newonly'] == 'new' ? 'kx_cost IS NULL' : '1=1')
                    ->orderBy('created_at DESC')
                    ->asArray()
                    ->all();

                foreach (['k0', 'k1', 'k2', 'k3', 'k4', 'k5', 'k6', 'k7', 'k8'] as $kx) {
                    foreach (['new', 'referred', 'returning', 't0'] as $ty) {
                        if (isset($_POST['cost'][$kx][$ty]) && trim($_POST['cost'][$kx][$ty]) != '') {
                            // Only find cases if cost != 0
                            $caseIdList = [];
                            foreach ($cases as $case) {
                                $kOK = false;
                                $tOK = false;
                                if (($kx == 'k0' && $case['kx'] == '') || ($kx == $case['kx'])) {
                                    $kOK = true;
                                }
                                if (($ty == 't0' && $case['how_found'] == '') || substr($case['how_found'], 0, strlen($ty)) == $ty) {
                                    $tOK = true;
                                }
                                if ($tOK && $kOK) {
                                    $caseIdList[] = $case['id'];
                                }
                            }
                            // Update cost for matched cases
                            $cost = (float)trim($_POST['cost'][$kx][$ty]);
                            if (!empty($caseIdList)) {
                                Yii::$app->db->createCommand()
                                    ->update(
                                        'at_case_stats',
                                        ['kx_cost'=>$cost, 'kx_cost_currency'=>$_POST['currency']],
                                        ['case_id'=>$caseIdList]
                                    )
                                    ->execute();
                            }
                            echo '<br><strong style="color:green">[', $kx, ']</strong><strong style="color:red">[', $ty, ']</strong> <strong>', $cost, '</strong><small>', $_POST['currency'], '</small> (', count($caseIdList), ') ', implode(', ', $caseIdList);
                        }
                    }
                }
                echo '<br><br>COST UPDATE DONE. <a style="color:blue" href="?action=view">CLICK HERE TO RETURN TO REPORT</a>.';
                exit;
            }
            //echo '<br><br>COST UPDATE NOT DONE. <a style="color:red" href="?action=update">CLICK HERE TO RETRY</a>.';
            //exit;
            $vars = [
                'action'=>$action,
                'month'=>$month,
                'year'=>$year,
                'yearList'=>$yearList,
                'channelList'=>$channelList,
                'typeList'=>$typeList,
            ];

        }


        return $this->render('report_mkt-05', $vars);
    }

    public function actionMkt04($month = '', $lang = 'fr', $blank_email = 'no')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        $andLang = '';
        $andEmpty = ' AND u.email!=""';
        if (in_array($lang, ['en', 'fr', 'vi'])) {
            $andLang = ' AND k.language="'.$lang.'"';
        }
        if (in_array($blank_email, ['yes'])) {
            $andEmpty = '';
        }
        $sql = 'SELECT u.id, u.fname, u.lname, u.gender, u.country_code, u.email FROM persons u, at_booking_user bu, at_cases k, at_bookings b WHERE bu.user_id=u.id AND k.id=b.case_id AND bu.booking_id=b.id '.$andLang.$andEmpty.' AND SUBSTRING(u.updated_at,1,7)=:ym GROUP BY u.id ORDER BY u.id LIMIT 1000';
        $results = Yii::$app->db->createCommand($sql, [':ym'=>$month])->queryAll();
        echo '<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title>Danh sách khách mới update - Amica Travel IMS</title><style>table {border-collapse:collapse;} th, td {padding:4px; border:1px solid #ccc;}</style></head><body>';
        echo '<p><strong>DANH SACH KHACH DUOC CAP NHAT THANG ', $month, '</strong></p>';
        echo '<p>Thay đổi các phần in đậm để có kết quả như ý: https://my.amicatravel.com/reports/mkt-04?month=<strong>2016-04|2016-03</strong>&lang=<strong>fr|en</strong>&blank_email=<strong>yes|no</strong></p>';
        echo '<table><thead><tr><th>ID</th><th>HO</th><th>TEN</th><th>GIOI</th><th>QTICH</th><th>EMAIL</th></tr></thead><tbody>';
        foreach ($results as $user) {
            echo '<tr>';
            echo '<td>', $user['id'], '</td>';
            echo '<td>', $user['fname'], '</td>';
            echo '<td>', $user['lname'], '</td>';
            echo '<td>', $user['gender'], '</td>';
            echo '<td>', strtoupper($user['country_code']), '</td>';
            echo '<td>', strtolower($user['email']), '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        exit;
        return $this->render('reports_mkt04', [
            'results'=>$results,
        ]);
    }

    // 160317 Danh sach khach gioi thieu tour con credit
    public function actionMkt03()
    {
        $sql = 'SELECT u.id, u.fname, u.lname, u.email, u.country_code, u.gender, (r.points - r.points_minus) AS credit, (SELECT COUNT(*) FROM at_booking_user bu WHERE bu.user_id=u.id) AS bookings FROM at_referrals r, persons u WHERE u.id=r.user_id AND u.is_member="no" HAVING credit!=0 AND bookings!=0';
        $thePersons = Yii::$app->db->createCommand($sql)->queryAll();

        $results =[];
        foreach ($thePersons as $person) {
            if (!isset($results[$person['id']])) {
                $results[$person['id']] = $person;
            } else {
                $results[$person['id']]['credit'] += $person['credit'];
            }
        }

        $filename = 'mkt_khach_co_credit_'.date('Ymd-His').'.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename='.$filename);

        $out = fopen('php://output', 'w');
        fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

        $arr = ['ID', 'TEN', 'HO', 'EMAIL', 'GIOI', 'QG', 'CREDIT'];
        fputcsv($out, $arr);

        foreach ($results as $id=>$person) {
            $arr = [];
            $arr[] = $person['id'];
            $arr[] = $person['lname'];
            $arr[] = $person['fname'];
            $arr[] = $person['email'];
            $arr[] = $person['gender'];
            $arr[] = strtoupper($person['country_code']);
            $arr[] = $person['credit'];
            fputcsv($out, $arr);
        }
        fclose($out);
        exit;
    }

    // 160220 Danh sach khach lien he khong mua tour
    public function actionMkt02($y = 2016, $l ='fr')
    {
        if (!in_array($y, [2016, 2015, 2014, 2013])) {
            $y = 2016;
        }

        if (!in_array($l, ['fr', 'en'])) {
            $l = 'fr';
        }

        $query = Kase::find()
            ->select(['id'])
            ->where(['deal_status'=>'lost', 'is_b2b'=>'no', 'language'=>$l])
            ->andWhere('YEAR(created_at)=:y', [':y'=>$y]);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>500,
        ]);

        $lostDeals = $query
            ->with([
                'people'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'gender', 'email', 'phone', 'country_code']);
                },
                'people.bookings'=>function($q) {
                    return $q->select(['id']);
                },
                'inquiries'/*=>function($q) {
                    return $q->orderBy('id DESC')->limit(1);
                }*/
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $thePeople = [];
        foreach ($lostDeals as $deal) {
            if (empty($deal['people'])) {
                // echo '<hr>EMPTY: '.$deal['id'];
                foreach ($deal['inquiries'] as $inquiry) {
                    try {
                        $data = @unserialize($inquiry['data']);
                        $age = $data['agesOfTravelers12'] ?? 0;
                        $country = $data['country'] ?? '';
                        $phone = $data['phone'] ?? '';
                    } catch(Exception $e) {
                        $age = 0;
                        $phone = '';
                        $country = '';
                    }
                    $thePeople[$inquiry['email']] = [
                        'id'=>0,
                        'fname'=>$inquiry['name'],
                        'lname'=>'',
                        'gender'=>'',
                        'age'=>$age,
                        'email'=>$inquiry['email'],
                        'phone'=>$phone,
                        'country_code'=>$country,
                    ];
                }
            } else {
                $age = 0;
                foreach ($deal['inquiries'] as $inquiry) {
                    try {
                        $data = @unserialize($inquiry['data']);
                        $age = $data['agesOfTravelers12'] ?? 0;
                    } catch(Exception $e) {
                        $age = 0;
                    }
                }
                foreach ($deal['people'] as $person) {
                    // Not returning
                    if (count($person['bookings']) == 0) {
                        $thePeople[$person['email']] = [
                            'id'=>$person['id'],
                            'fname'=>$person['fname'],
                            'lname'=>$person['lname'],
                            'gender'=>$person['gender'],
                            'age'=>$age,
                            'email'=>$person['email'],
                            'phone'=>$person['phone'],
                            'country_code'=>$person['country_code'],
                        ];
                    }
                }
            }
        }

        if (isset($_GET['x'])) {
            echo count($lostDeals), '-', count($thePeople);
            \fCore::expose($lostDeals);
            exit;
        }
        return $this->render('reports_mkt-02', [
            'thePeople'=>$thePeople,
            'pagination'=>$pagination,
            'y'=>$y,
            'l'=>$l,
        ]);
    }

    // 160108 So luong yeu cau theo tour tren web theo nam
    public function actionMkt01($y = 2015)
    {
        if (!in_array((int)$y, [2014, 2015, 2016, 2017])) {
            $y = 2015;
        }
        $inquiries = Inquiry::find()
            ->select('data')
            ->where('YEAR(created_at)=:y', [':y'=>$y])
            //->andWhere()
            ->asArray()
            ->all();
        foreach ($inquiries as $inquiry) {
            $data = @unserialize($inquiry['data']);
            if (is_array($data)) {
                if (isset($data['tourName']) && $data['tourName'] != '') {
                    $url = str_replace(['http://', 'https://'], ['', ''], $data['tourUrl']);
                    if (strpos($url, '?') !== false) {
                        $url = strchr($data['tourUrl'], '?', true);
                    }
                    if (isset($results[$url])) {
                        $results[$url]['cnt'] ++;
                    } else {
                        $results[$url] = [
                            'cnt'=>1,
                            'name'=>$data['tourName'],
                            'url'=>$data['tourUrl'],
                        ];
                    }
                }
            }
        }
        return $this->render('reports_mkt-01', [
            'results'=>$results,
            'y'=>$y,
        ]);
    }


    public function actionTourLength()
    {
        // Năm đi tour
        $sql = 'SELECT MAX(YEAR(day_from)) FROM at_ct WHERE op_status="op"';
        $maxYear = Yii::$app->db->createCommand($sql)->queryScalar();

        // Các phân nhóm
        $defaultGrouping = '1-7,8-14,15-';
        $getGrouping = Yii::$app->request->get('grouping', $defaultGrouping);
        $groups = explode(',', $getGrouping);
        $theGroups = [];
        foreach ($groups as $group) {
            $item = explode('-', $group);
            if (isset($item[0])) {
                $from = (int)$item[0];
            } else {
                $from = 0;
            }
            if (isset($item[1])) {
                $to = (int)$item[1] == 0 ? 9999 : (int)$item[1];
            } else {
                $to = 9999;
            }
            $theGroups[] = [$group, $from, $to];
        }


        $theProducts = Product::find()
            ->select(['id', 'day_count', 'day_from'])
            ->where(['op_status'=>'op'])
            ->asArray()
            ->all();
        return $this->render('reports_tour-length', [
            'minYear'=>2007,
            'maxYear'=>$maxYear,
            'theProducts'=>$theProducts,
            'theGroups'=>$theGroups,
            'getGrouping'=>$getGrouping,
        ]);
    }

    // Customers with more than $from tours
    public function actionPaxTours($from = 2)
    {
        // Khong list 1 tour vi qua nhieu
        if ($from < 2) {
            $from = 2;
        }

        $sql = 'select u.email, u.phone, u.gender, u.name, u.country_code, u.byear, count(*) as cnt, user_id from persons u, at_booking_user bu, at_bookings b where b.id=bu.booking_id AND u.id=bu.user_id AND b.status="won" and u.is_member="no" group by bu.user_id having cnt>=:from ORDER BY u.lname, u.fname desc';
        $thePax = Yii::$app->db->createCommand($sql, [':from'=>$from])->queryAll();

        $paxIdList = [];
        foreach ($thePax as $pax) {
            $paxIdList[] = $pax['user_id'];
        }

        $paxAddrs = [];
        if (!empty($thePax)) {
            $sql = 'SELECT rid, value FROM metas WHERE name="address" AND rtype="user" AND rid IN ('.implode(',', $paxIdList).')';
            $paxAddrs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $theTours = [];
        if (!empty($paxIdList)) {
            $sql = 'SELECT p.id, p.op_code, b.finish, bu.user_id FROM at_ct p, at_booking_user bu, persons u, at_bookings b WHERE bu.user_id=u.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN('.implode(',', $paxIdList).') ORDER BY SUBSTRING(p.op_code,2,4)';
            $theTours = Yii::$app->db->createCommand($sql)->queryAll();
        }

        return $this->render('reports_pax-tours', [
            'theTours'=>$theTours,
            'thePax'=>$thePax,
            'paxAddrs'=>$paxAddrs,
            'from'=>$from,
        ]);
    }

    public function actionKqkdtour($year = '', $seller = 0, $code = '', $orderby = 'date', $eur = 24100, $usd = 21450, $vnd = 1)
    {
        // 160706 Rem Lan, add Hanh
        if (!in_array(MY_ID, [1,2,3,4,17,34717])) {
            throw new HttpException(403, 'Access denied');
        }

        if (strlen($year) != 4) {
            $year = date('Y');
        }
        $yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018];
        $sql2 = 'SELECT u.id, CONCAT_WS(", ", lname, fname, email) AS name FROM persons u, at_bookings b WHERE b.created_by=u.id GROUP BY u.id ORDER BY lname, fname';
        $sellerList = Yii::$app->db->createCommand($sql2)->queryAll();

        $query = Booking::find();

        if (in_array(strtolower($code), ['f', 'g'])) {
            $query->andWhere('LOWER(SUBSTRING(at_ct.op_code, 1,1))=:code', [':code'=>strtolower($code)]);
        }

        if ($seller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$seller]);
        }

        $theBookings = $query
            ->select(['at_bookings.id', 'at_bookings.created_by', 'product_id', 'finish'])
            ->with([
                'invoices'=>function($q) {
                    return $q->select(['id', 'booking_id', 'amount', 'stype', 'currency', 'payment_status'])->where(['status'=>'active']);
                },
                'payments'=>function($q) {
                    return $q->select(['id', 'booking_id', 'amount', 'currency', 'xrate']);
                },
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->joinWith([
                'product'=>function($q) use ($year) {
                    //return $q->select(['id', 'op_name', 'op_code', 'day_from', 'day_until'=>'DATE_ADD(day_from, INTERVAL day_count-1 DAY)']);//->having('YEAR(day_until)=:year', [':year'=>$year]);
                    return $q->select(['id', 'op_name', 'op_code', 'day_until'])->where('YEAR(day_until)=:year', [':year'=>$year]);
                }
            ])
            ->andWhere(['at_bookings.status'=>'won'])
            //->andWhere('YEAR(at_ct.day_from)=:year', [':year'=>$year])
            //->having('YEAR(at_ct.day_until)=:year', [':year'=>$year])
            ->orderBy($orderby == 'date' ? 'at_ct.day_until' : 'at_ct.op_code')
            ->asArray()
            ->limit(5000)
            ->all();

        //\fCore::expose($theBookings);
        //exit;

        $result = [];
        for ($mm = 0; $mm <= 12; $mm ++) {
            $result['due'][$mm] = 0;
            $result['paid'][$mm] = 0;
            $result['bal'][$mm] = 0;
        }

        foreach ($theBookings as $booking) {
            //$mm = (int)substr($booking['product']['day_from'], 5, 2);
            $mm = (int)substr($booking['product']['day_until'], 5, 2);

            $result['due']['bkg'.$booking['id']] = 0;
            $result['paid']['bkg'.$booking['id']] = 0;
            $result['bal']['bkg'.$booking['id']] = 0;

            foreach ($booking['payments'] as $payment) {
                $amountVnd = $payment['amount'] * $payment['xrate'];
                $result['paid'][$mm] += $amountVnd;
                $result['paid'][0] += $amountVnd;
                $result['paid']['bkg'.$booking['id']] += $amountVnd;
            }

            foreach ($booking['invoices'] as $invoice) {
                if ($invoice['stype'] == 'invoice') {
                    $amount = $invoice['amount'];
                } elseif ($invoice['stype'] == 'credit') {
                    $amount = -$invoice['amount'];
                }

                if ($invoice['currency'] == 'USD') {
                    $amountVnd = $usd * $amount;
                } elseif ($invoice['currency'] == 'EUR') {
                    $amountVnd = $eur * $amount;
                } else {
                    $amountVnd = $amount;
                }

                if ($invoice['payment_status'] == 'unpaid') {
                    $result['bal'][$mm] += $amountVnd;
                    $result['bal'][0] += $amountVnd;
                    $result['bal']['bkg'.$booking['id']] += $amountVnd;
                }

                $result['due'][$mm] = $result['paid'][$mm] + $result['bal'][$mm];
                $result['due'][0] = $result['paid'][0] + $result['bal'][0];
                $result['due']['bkg'.$booking['id']] = $result['paid']['bkg'.$booking['id']] + $result['bal']['bkg'.$booking['id']];
            }
        }

        return $this->render('reports_kqkdtour', [
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'yearList'=>$yearList,
            'seller'=>$seller,
            'orderby'=>$orderby,
            'year'=>$year,
            'eur'=>$eur,
            'usd'=>$usd,
            'vnd'=>$vnd,
            'code'=>$code,
            'result'=>$result,
        ]);
    }

    public function actionLichtttour($year = '', $seller = 0, $method = '', $nhothu = '', $eur = 24062, $usd = 21335)
    {
        // 160706 Rem Lan, add Hanh
        if (!in_array(MY_ID, [1,2,3,4,17,4065])) {
            throw new HttpException(403, 'Access denied');
        }
        if (strlen($year) != 4) {
            $year = date('Y');
        }
        $yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018];

        $sql2 = 'SELECT u.id, CONCAT_WS(", ", lname, fname, email) AS name FROM persons u, at_bookings b WHERE b.created_by=u.id GROUP BY u.id ORDER BY lname, fname';
        $sellerList = Yii::$app->db->createCommand($sql2)->queryAll();

        $query = Invoice::find()
            ->andWhere(['status'=>'active'])
            ->andWhere(['payment_status'=>'unpaid'])
            ->andWhere('YEAR(due_dt)=:year', [':year'=>$year]);

        if ($method != '') {
            $query->andWhere(['method'=>$method]);
        }

        if ($nhothu == 'yes') {
            $query->andWhere('nho_thu!=""');
        } elseif ($nhothu == 'no') {
            $query->andWhere('nho_thu=""');
        } elseif ($nhothu != '') {
            $query->andWhere(['nho_thu'=>str_replace('+', ' ', $nhothu)]);
        }

        $theInvoices = $query
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'booking',
                'booking.product',
            ])
            ->orderBy('due_dt')
            ->asArray()
            ->limit(5000)
            ->indexBy('id')
            ->all();

        // Ngày bắt đầu tính
        if (date('D') == 'Mon') {
            $startDate = date('Y-m-d');
        } else {
            $startDate = date('Y-m-d', strtotime('last Monday'));
        }

        $result = [];
        $total = [];

        foreach ($theInvoices as $invoice) {
            if (strtotime($invoice['due_dt']) < strtotime($startDate)) {
                $result['overdue'][] = $invoice['id'];
                if (!isset($total['overdue'][$invoice['currency']])) {
                    $total['overdue'][$invoice['currency']] = 0;
                }
                if ($invoice['stype'] == 'credit') {
                    $invoice['amount'] = -$invoice['amount'];
                }
                $total['overdue'][$invoice['currency']] += $invoice['amount'];
            }
        }

        while (substr($startDate, 0, 4) == $year) {
            $endDate = date('Y-m-d', strtotime('+6 days', strtotime($startDate)));
            foreach ($theInvoices as $id => $invoice) {
                $dueDate = substr($invoice['due_dt'], 0, 10);
                if (strtotime($dueDate) >= strtotime($startDate) && strtotime($dueDate) <= strtotime($endDate)) {
                    $result[$startDate][] = $id;
                    if (!isset($total[$startDate][$invoice['currency']])) {
                        $total[$startDate][$invoice['currency']] = 0;
                    }
                    if ($invoice['stype'] == 'credit') {
                        $invoice['amount'] = -$invoice['amount'];
                    }
                    $total[$startDate][$invoice['currency']] += $invoice['amount'];
                }
            }
            $startDate = date('Y-m-d', strtotime('+7 days', strtotime($startDate)));
        }

        $sql = 'SELECT method FROM invoices GROUP BY method ORDER BY method';
        $methodList = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT nho_thu FROM invoices GROUP BY nho_thu ORDER BY nho_thu';
        $nhothuList = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('reports_lichtttour', [
            'theInvoices'=>$theInvoices,
            'sellerList'=>$sellerList,
            'yearList'=>$yearList,
            'methodList'=>$methodList,
            'nhothuList'=>$nhothuList,
            'seller'=>$seller,
            'year'=>$year,
            'method'=>$method,
            'nhothu'=>$nhothu,
            'result'=>$result,
            'total'=>$total,
            'eur'=>$eur,
            'usd'=>$usd,
        ]);
    }

    // Lost cases
    public function actionLostCases($month = '', $for = 'b2c', $seller = 0, $reason = '', $search = '')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }
        $query = Kase::find()
            ->andWhere(['status'=>'closed', 'deal_status'=>['lost', 'pending']])
            ->andWhere('SUBSTRING(closed,1,7)=:month', [':month'=>$month]);
        if ($seller != 0) {
            $query->andWhere(['owner_id'=>$seller]);
        }
        if ($reason != '') {
            $query->andWhere(['why_closed'=>$reason]);
        }
        if ($for == 'b2b') {
            $query->andWhere(['is_b2b'=>'yes']);
        } elseif ($for == 'b2c') {
            $query->andWhere(['is_b2b'=>'no']);
        }
        if (strlen($search) >= 2) {
            $query->andWhere(['like', 'closed_note', $search]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theCases = $query
            ->select(['id', 'name', 'status', 'ref', 'is_priority', 'deal_status', 'opened', 'closed', 'owner_id', 'created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'why_closed', 'closed_note'])
            ->orderBy('closed DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with([
                'stats',
                'owner'=>function($query) {
                    return $query->select(['id', 'name', 'image']);
                },
                'referrer'=>function($query) {
                    return $query->select(['id', 'name', 'is_client']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->asArray()
            ->all();

        $sql = 'select substring(closed,1,7) as ym from at_cases group by ym having ym!="0000-00" order by ym desc';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();
        $sql2 = 'select u.id, CONCAT(u.lname, ", ", u.email) as name from persons u, at_cases k where k.owner_id=u.id group by u.id order by lname, fname';
        $sellerList = Yii::$app->db->createCommand($sql2)->queryAll();

        return $this->render('reports_lost-cases', [
            'theCases'=>$theCases,
            'pagination'=>$pagination,
            'month'=>$month,
            'seller'=>$seller,
            'reason'=>$reason,
            'search'=>$search,
            'monthList'=>$monthList,
            'sellerList'=>$sellerList,
            'for'=>$for,
        ]);
    }

    public function actionBookings()
    {
        $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
        $getBantour = Yii::$app->request->get('bantour', 0);
        $getSeller = Yii::$app->request->get('seller', 0);
        $getCurrency = Yii::$app->request->get('currency', 0);
        $getB2b = Yii::$app->request->get('b2b', 'b2c');
        $rates = Yii::$app->request->get('rates', 1.14);

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

        return $this->render('reports_bookings', [
            'getKhoihanh'=>$getKhoihanh,
            'getBantour'=>$getBantour,
            'getSeller'=>$getSeller,
            'getCurrency'=>$getCurrency,
            'getB2b'=>$getB2b,
            'rates'=>$rates,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'listKhoiHanh'=>$listKhoiHanh,
            'listBanTour'=>$listBanTour,
        ]);
    }

    // Danh sach khach hang o khach san
    public function actionCustomersHotel($id = 455, $year = 2017, $month = 1)
    {
        // Khach san
        $theVenue = Venue::findOne($id);

        if (!$theVenue) {
            throw new HttpException(404, 'Hotel not found');
        }

        // Cac tour dung khach san tinh theo ngay su dung
        if ($month == 0) {
            $sql = 'select t.ct_id, t.id, t.code from at_tours t, cpt cp where cp.tour_id=t.id and t.status!="deleted" and cp.dvtour_day<:now and cp.venue_id=:id and year(dvtour_day)=:year group by tour_id order by dvtour_day limit 1000';
            $theTours = Tour::findBySql($sql, [
                //':now'=>date('Y-m-d'),
                ':now'=>'2019-01-01',
                ':id'=>$id,
                ':year'=>$year,
            ])
            ->with([
                'product'=>function($q) {
                    return $q->select(['id', 'title', 'day_from']);
                },
                'product.bookings'=>function($q) {
                    return $q->select('*');
                },
                'product.bookings.pax'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name', 'byear', 'country_code', 'gender', 'email', 'phone'])->orderBy('lname, fname');
                },
                //'product.bookings.pax.metas'=>function($q) {
                //  return $q->select(['v'])->where(['k'=>'address']);
                //},
            ])
            ->asArray()
            ->all();
        } else {
            $sql = 'select t.ct_id, t.id, t.code from at_tours t, cpt cp where cp.tour_id=t.id and t.status!="deleted" and cp.dvtour_day<:now and cp.venue_id=:id and year(dvtour_day)=:year and month(dvtour_day)=:month group by tour_id order by dvtour_day limit 1000';
            $theTours = Tour::findBySql($sql, [
                //':now'=>date('Y-m-d'),
                ':now'=>'2019-01-01',
                ':id'=>$id,
                ':year'=>$year,
                ':month'=>$month,
            ])
            ->with([
                'product'=>function($q) {
                    return $q->select(['id', 'title', 'day_from']);
                },
                'product.bookings'=>function($q) {
                    return $q->select('*');
                },
                'product.bookings.pax'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name', 'byear', 'country_code', 'gender', 'email', 'phone'])->orderBy('lname, fname');
                },
                //'product.bookings.pax.metas'=>function($q) {
                //  return $q->select(['v'])->where(['k'=>'address']);
                //},
            ])
            ->asArray()
            ->all();
        }
        $allCountries = \common\models\Country::find()->select(['code', 'name_en'])->indexBy('code')->asArray()->all();

        return $this->render('reports_pax-hotel', [
            'theVenue'=>$theVenue,
            'theTours'=>$theTours,
            'allCountries'=>$allCountries,
            'id'=>$id,
            'year'=>$year,
            'month'=>$month,
        ]);
    }

    // Count of customers
    public function actionCustomersTours()
    {
        $sql = 'SELECT p.id AS tour_id, b.pax, p.day_from FROM at_bookings b, at_ct p WHERE p.id=b.product_id AND b.status="won" AND b.finish!="canceled" ORDER BY p.day_from';
        $theBookings = Yii::$app->db->createCommand($sql)->queryAll();

        $result = [];
        $resultTours = [];
        $countedTourIdList = [];

        foreach ($theBookings as $booking) {
            $year = (int)substr($booking['day_from'], 0, 4);
            $month = (int)substr($booking['day_from'], 5, 2);
            // Count tours
            if (!isset($resultTours[$year][$month])) {
                $resultTours[$year][$month] = 0;
            }
            if (!isset($resultTours[$year][0])) {
                $resultTours[$year][0] = 0;
            }
            if (!in_array($booking['tour_id'], $countedTourIdList)) {
                $resultTours[$year][$month] ++;
                $resultTours[$year][0] ++;
                $countedTourIdList[] = $booking['tour_id'];
            }

            // Count pax
            if (!isset($result[$year][$month])) {
                $result[$year][$month] = 0;
            }
            $result[$year][$month] += $booking['pax'];
            if (!isset($result[$year][0])) {
                $result[$year][0] = 0;
            }
            $result[$year][0] += $booking['pax'];
        }

        ksort($result);
        ksort($resultTours);

        return $this->render('reports_customers-tours', [
            'result'=>$result,
            'resultTours'=>$resultTours,
        ]);
    }

    public function actionCasesProspectByInquirySource($month = '', $prospect = 'all', $site = 'fr', $source = 'all')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }
        $whereMonth = ' AND SUBSTRING(i.created_at,1,7)=:month ';

        $whereSource = ' AND k.id != :source';
        if ($source != 'all') {
            $whereSource = ' AND k.how_found=:source ';
        }

        $whereProspect = ' AND k.id!=:prospect ';
        if ($prospect != 'all') {
            $whereProspect = ' AND s.prospect=:prospect ';
        }

        $whereSite = '';
        if ($site == 'val') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="val"';
        } elseif ($site == 'vac') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="vac"';
        } elseif ($site == 'vpc') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="vpc"';
        } elseif ($site == 'ami') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="ami"';
        } elseif ($site == 'fr') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,2)="fr"';
        } elseif ($site == 'en') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="en"';
        }
        $whereMonth = ' AND SUBSTRING(i.created_at,1,7)=:month ';

        $sql = 'SELECT COUNT(*) FROM at_inquiries i, at_cases k, at_case_stats s WHERE k.how_contacted="web" AND i.case_id=k.id AND s.case_id=k.id'.$whereMonth.$whereSite.$whereProspect.$whereSource.' GROUP BY k.id';
        $count = Yii::$app->db->createCommand($sql, [
            ':month'=>$month,
            ':prospect'=>$prospect,
            ':source'=>$source,
        ])->queryScalar();

        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'=>500,
        ]);

        $sql = 'SELECT k.id, k.name, i.form_name, i.created_at, k.how_found, s.prospect FROM at_inquiries i, at_cases k, at_case_stats s WHERE k.how_contacted="web" AND i.case_id=k.id AND s.case_id=k.id'.$whereMonth.$whereSite.$whereProspect.$whereSource.' GROUP BY k.id ORDER BY i.created_at LIMIT '.$pagination->offset.', '.$pagination->limit;
        $theCases = Yii::$app->db->createCommand($sql, [
            ':month'=>$month,
            ':prospect'=>$prospect,
            ':source'=>$source,
        ])->queryAll();

        $sql = 'SELECT how_found FROM at_cases GROUP BY how_found ORDER BY how_found';
        $sources = Yii::$app->db->createCommand($sql)->queryAll();
        $sourceList = ['all'=>'All sources'];
        foreach ($sources as $xsource) {
            $sourceList[$xsource['how_found']] = $xsource['how_found'];
        }

        $sql = 'SELECT SUBSTRING(i.created_at,1,7) AS ym FROM at_inquiries i GROUP BY ym ORDER BY ym DESC';
        $months = Yii::$app->db->createCommand($sql)->queryAll();
        $monthList = [];
        foreach ($months as $xmonth) {
            $monthList[$xmonth['ym']] = $xmonth['ym'];
        }

        return $this->render('reports_cases-prospect-by-inquiry-source', [
            'theCases'=>$theCases,
            'pagination'=>$pagination,
            'prospect'=>$prospect,
            'month'=>$month,
            'site'=>$site,
            'source'=>$source,
            'sourceList'=>$sourceList,
            'monthList'=>$monthList,
        ]);
    }
}
