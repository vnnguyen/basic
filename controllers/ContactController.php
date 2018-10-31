<?php
namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\data\Pagination;
use yii\base\Model;
use common\models\Mail;
use common\models\Booking;
use common\models\Kase;
use common\models\Contact;
use common\models\Member;
use common\models\Search;
use common\models\Country;
use common\models\Meta;
use common\models\Meta2;
use common\models\File;
use common\models\Note;
use common\models\Product;
use common\models\Tour;
use common\models\ProfileMember;
use common\models\TourguideProfile;
use common\models\UsersUuForm;

class ContactController extends MyController
{
    /**
     * Handle ajax requests
     */
    public function actionAjax($action = '', $query = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($action == 'relto' || $action == 'search') {
            $search = trim($query);
            if ($search == '') {
                return [];
            }
            $people = Contact::find()
                ->select(['id', 'name', 'email', 'bday', 'bmonth', 'byear', 'country_code', 'gender'])
                ->where(['or', is_numeric($search) ? ['id'=>$search] : 0, ['like', 'name', $search], ['like', 'fname', $search], ['like', 'lname', $search], ['like', 'email', $search]])
                ->orderBy('lname, fname')
                ->limit(20)
                ->asArray()
                ->all();
            $result = [];
            if ($action == 'search' && strlen($search) > 3) {
                $result[] = [
                    'value'=>'Add new person: '.$search,
                    'name'=>'+'.$search,
                    'id'=>0,
                ];
            }
            foreach ($people as $person) {
                $result[] = [
                    'value'=>'#'.$person['id'].' '.$person['name'].' '.substr($person['gender'], 0, 1).' '.implode('/', [$person['bday'], $person['bmonth'], $person['byear']]).' '.strtoupper($person['country_code']).' '.$person['email'],
                    'name'=>'#'.$person['id'].' '.$person['name'],
                    'id'=>$person['id'],
                ];
            }
            return ['suggestions'=>$result];
        }

        throw new HttpException(401);

    }

    public function actionIndex($fname = '', $lname = '', $country = '', $gender = '', $email = '', $tel = '')
    {
        $query = Contact::find();

        if ($fname != '') {
            $query->andWhere(['like', 'fname', $fname]);
        }
        if ($lname != '') {
            $query->andWhere(['like', 'lname', $lname]);
        }
        if ($email != '') {
            $query->andWhere(['like', 'email', $email]);
        }
        if (in_array($gender, ['male', 'female'])) {
            $query->andWhere(['gender'=>$gender]);
        }
        if (strlen($country) == 2) {
            $query->andWhere(['country_code'=>$country]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $theContacts = $query
            ->with([
                'metas'=>function($q){
                    return $q->select(['id', 'rid', 'name', 'value', 'format']);
                },
                'cases'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'bookings'=>function($q){
                    return $q->select(['id', 'product_id']);
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'op_code']);
                },
            ])
            ->orderBy('updated_at DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $countryList = Country::find()
            ->select(['code', 'name'=>'name_'.Yii::$app->language])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('contact_index', [
            'pagination'=>$pagination,
            'theContacts'=>$theContacts,
            'fname'=>$fname,
            'lname'=>$lname,
            'gender'=>$gender,
            'email'=>$email,
            'country'=>$country,
            'countryList'=>$countryList,
        ]);
    }

    /**
     * Members of Amica
     */
    public function actionMembers($view = '', $status = 'on', $department = '', $location = '', $name = '')
    {

        if (USER_ID == 1) {
            // // Convert member metas to contact metas
            // $members = Member::find()
            //     ->with(['metas'])
            //     ->asArray()
            //     ->all();
            // foreach ($members as $member) {
            //     foreach ($member['metas'] as $meta) {
            //         if (!in_array($name, ['ext', 'Ext'])) {
            //             Yii::$app->db->createCommand()->update('metas', ['rtype'=>'user', 'rid'=>$member['contact_id']], ['id'=>$meta['id']])->execute();
            //         } else {
            //             Yii::$app->db->createCommand()->update('members', ['ext'=>$meta['value']], ['id'=>$meta['id']])->execute();
            //         }
            //     }
            // }

            // die('OK');
            // exit;
        }

        if (!in_array($view, ['normal', 'list'])) {
            $view = 'normal';
        }

        $query = Contact::find()
            ->select('c.id, c.fname, c.lname, c.name, c.bday, c.bmonth, c.byear, c.gender, c.country_code, c.image, m.is_on_leave, m.is_remote, m.is_intern, m.ext, m.contact_id, m.position, m.department, m.location')
            ->from('members m, contacts c')
            ->where(['m.status'=>$status])
            ->andWhere('m.contact_id=c.id')
            ->andWhere('contact_id!=33776')
            ->andWhere('contact_id!=49847');

        if ($department != '') {
            $query->andWhere(['like', 'm.department', $department]);
        }

        if ($location != '') {
            $query->andWhere(['like', 'm.location', $location]);
        }

        if ($name != '') {
            $query->andWhere(['or', ['c.id'=>$name], ['like', 'c.name', $name]]);
        }

        $theMembers = $query
            ->with([
                'metas'=>function($q){
                    return $q->select(['rid', 'name', 'value'])->indexBy('name');
                },
            ])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        return $this->render('contact_members', [
            'theMembers'=>$theMembers,
            'view'=>$view,
            'status'=>$status,
            'department'=>$department,
            'location'=>$location,
            'name'=>$name,
        ]);
    }

    /**
     * View indexed and unindexed tourguides
     *
     */
    public function actionTourguides($birthmonth = '', $name = '', $tel = '', $orderby = 'updated', $language = '', $region = '', $tourtype = '', $gender = '')
    {
        // Unindexed list
        if (isset($_GET['noindex'])) {
            $sql = 'SELECT tg.id, p.id AS tid, guide_name, p.op_code, SUBSTRING(use_from_dt, 1, 10) AS usedt FROM at_tour_guides tg, at_ct p WHERE p.id=tg.tour_id AND guide_user_id=0 ORDER BY guide_name, use_from_dt';
            $theTourguides = Yii::$app->db->createCommand($sql)->queryAll();

            if (isset($_GET['noindex'])) {

            }

            return $this->render('contact_tourguides-noindex', [
                'theTourguides'=>$theTourguides,
            ]);
        }

        // Duc Anh, Thuy Linh, Bich Ngoc, Ngo Hang, Kim Ngoc, Khang Ha, Tuyen, Thu Hien
        // 171016 Added Hoang Lan
        // 171225 Added Tuan Kiet
        // 171225 Added Thuy Le, Rotha
        // 180328 Added Jonathan
        // 180801 Added Thuy Duong
        if (!in_array(MY_ID, [51183, 29212, 8162, 37675, 1,2,3,4,8,11,118,4432,25457,27726,29296,33415,
            46046, 42901, 1906,
            26052
        ])) {
            throw new HttpException(403);
        }

        $query = Contact::find()
            ->innerJoin('{{%profiles_tourguide}} tgp', 'tgp.user_id=contacts.id');

        if (strlen(trim($name)) >= 2) {
            $query->andWhere(['like', 'contacts.name', trim($name)]);
        }

        if (strlen($language) >= 2) {
            $query->andWhere(['like', 'languages', $language]);
        }

        if (strlen(trim($region)) >= 2) {
            $query->andWhere(['like', 'regions', trim($region)]);
        }

        if (strlen(trim($tourtype)) >= 2) {
            $query->andWhere(['like', 'tour_types', trim($tourtype)]);
        }

        if (in_array($gender, ['male', 'female', 'other'])) {
            $query->andWhere(['gender'=>$gender]);
        }

        if (strlen(trim($tel)) >= 2) {
            $query->andWhere('LOCATE(:phone, phone)!=0', [':phone'=>trim($tel)]);
        }

        if ($birthmonth != '' && $birthmonth != 'unknown') {
            $query->andWhere(['bmonth'=>$birthmonth]);
        } elseif ($birthmonth == 'unknown') {
            $query->andWhere(['bmonth'=>0]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>30,
        ]);

        if ($orderby == 'pts') {
            $query->orderBy('ratings DESC, lname, fname');
        } elseif ($orderby == 'age') {
            $query->orderBy('byear, bmonth, bday, lname, fname');
        } elseif ($orderby == 'since') {
            $query->orderBy('guide_since, lname, fname');
        } elseif ($orderby == 'name') {
            $query->orderBy('lname, fname');
        } else {
            $query->orderBy('updated_at DESC');
        }

        $theTourguides = $query
            ->select('tgp.guide_since, tgp.ratings, tgp.tour_types, tgp.regions, tgp.languages, contacts.id, contacts.updated_at, contacts.status, fname, lname, gender, email, phone, image, bday, bmonth, byear, note, country_code')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();


        return $this->render('contact_tourguides', [
            'pagination'=>$pagination,
            'theTourguides'=>$theTourguides,
            'orderby'=>$orderby,
            'name'=>$name,
            'language'=>$language,
            'tel'=>$tel,
            'gender'=>$gender,
            'region'=>$region,
            'tourtype'=>$tourtype,
            'birthmonth'=>$birthmonth,
        ]);
    }

    /**
     * List of drivers
     */
    public function actionDrivers($name = '', $language = '')
    {
        // Unindexed list
        if (isset($_GET['noindex'])) {
            $sql = 'SELECT tg.id, p.id AS tid, driver_name, p.op_code, SUBSTRING(use_from_dt, 1, 10) AS usedt FROM at_tour_drivers tg, at_ct p WHERE p.id=tg.tour_id AND driver_user_id=0 AND driver_name!="" ORDER BY driver_name, use_from_dt';
            $theContacts = Yii::$app->db->createCommand($sql)->queryAll();

            if (isset($_GET['noindex'])) {

            }

            return $this->render('contact_guides-drivers-noindex', [
                'theContacts'=>$theContacts,
            ]);
        }


        // 171225 Added Tuan Kiet
        // 180227 Added Thuy Le, Rotha
        // 180801 Added Thuy Duong
        if (!in_array(MY_ID, [51183, 1,2,3,4,118,4432,27729,29212,37675,46046, 42901, 1906])) {
            throw new HttpException(403);
        }

        $getOrderby = Yii::$app->request->get('orderby', 'name');
        $getPhone = Yii::$app->request->get('phone', '');
        $getLanguage = Yii::$app->request->get('language', '');
        $getRegion = Yii::$app->request->get('region', '');
        $getTourtype = Yii::$app->request->get('tourtype', '');
        $getGender = Yii::$app->request->get('gender', 'all');

        $query = Contact::find()
            ->innerJoin('{{%profiles_driver}} tgp', 'tgp.user_id=contacts.id');

        if (strlen(trim($name)) >= 2) {
            $query->andWhere(['like', 'contacts.name', trim($name)]);
        }

        if (strlen($language) >= 2) {
            $query->andWhere(['like', 'languages', $language]);
        }

        if (strlen($getRegion) >= 2) {
            $query->andWhere(['like', 'regions', $getRegion]);
        }

        if (strlen($getTourtype) >= 2) {
            $query->andWhere(['like', 'tour_types', $getTourtype]);
        }

        if (in_array($getGender, ['male', 'female'])) {
            $query->andWhere(['gender'=>$getGender]);
        }

        if (strlen($getPhone) > 2) {
            $query->andWhere(['like', 'phone', $getPhone]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        if ($getOrderby == 'pts') {
            $query->orderBy('points DESC, lname, fname');
        } elseif ($getOrderby == 'age') {
            $query->orderBy('byear, lname, fname');
        } elseif ($getOrderby == 'since') {
            $query->orderBy('guide_since, lname, fname');
        } else {
            $query->orderBy('lname, fname');
        }

        $theDrivers = $query
            ->select('tgp.since, tgp.points, tgp.tour_types, tgp.vehicle_types, tgp.regions, tgp.languages, contacts.id, contacts.status, fname, lname, gender, email, phone, image, byear, contacts.info')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('contact_drivers', [
            'pagination'=>$pagination,
            'theDrivers'=>$theDrivers,
            'getOrderby'=>$getOrderby,
            'name'=>$name,
            'language'=>$language,
            'getPhone'=>$getPhone,
            'getGender'=>$getGender,
            'getRegion'=>$getRegion,
            'getTourtype'=>$getTourtype,
        ]);
    }

    /**
     * Duplicates of contacts
     */
    public function actionDuplicates($name = '')
    {
        $sql = 'SELECT id, name, COUNT(*) AS cnt FROM contacts GROUP BY name HAVING cnt>=2 ORDER BY cnt DESC';
        $theContacts = Contact::findBySql($sql)->asArray()->all();
        if (strlen(trim($name)) > 2) {
            $sql = 'SELECT id, fname, lname, name, gender, country_code, bday, bmonth, byear, email, phone FROM contacts WHERE name=:name';
            $dupContacts = Contact::findBySql($sql, [':name'=>$name])->asArray()->all();
        }
        return $this->render('contact_duplicates', [
            'name'=>$name,
            'theContacts'=>$theContacts,
            'dupContacts'=>$dupContacts ?? [],
        ]);
    }


    // List old tags
    public function actionTags($tag = 0)
    {
        $sql = 'SELECT id, name FROM at_terms WHERE taxonomy_id=2 ORDER BY name';
        $theTags = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT u.id, u.fname, u.lname, u.email, u.country_code, u.gender, u.byear FROM contacts u, at_terms t, at_term_rel r WHERE t.taxonomy_id=2 AND r.term_id=t.id AND rtype="user" AND u.id=r.rid AND t.id=:id ORDER BY lname, fname LIMIT 5000';
        $theUsers = Yii::$app->db->createCommand($sql, [':id'=>$tag])->queryAll();

        $userIdList = [];
        $theTours = [];
        if (!empty($theUsers)) {
            foreach ($theUsers as $user) {
                $userIdList[] = $user['id'];
            }
            $sql = 'SELECT p.id, p.op_name, p.op_code, bu.user_id FROM at_ct p, at_bookings b, at_booking_user bu WHERE bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN ('.implode(', ', $userIdList).') AND op_status="op" ORDER BY p.day_from';
            $theTours = Yii::$app->db->createCommand($sql)->queryAll();
        }


        return $this->render('users_tags', [
            'theTags'=>$theTags,
            'theUsers'=>$theUsers,
            'theTours'=>$theTours,
            'tagId'=>$tag,
        ]);
    }

    // Test client input form
    public function actionC(
        $profile = '',
        $new_name = '', // Add person with name
        $new_email = '', // Add person with email
        $booking_id = 0, // Add to booking after save
        $case_id = 0, // Add to case after save
        $xxx = ''
        )
    {
        $theBooking = false;
        $theCase = false;

        $theContact = new Contact;
        $theForm = new \app\models\ContactEditForm;

        if ($booking_id != 0) {
            $theBooking = Booking::find()
                ->where(['id'=>$booking_id])
                ->with([
                    'product'=>function($q){
                        return $q->select(['id', 'op_code', 'op_name', 'op_status', 'op_finish']);
                    },
                ])
                ->asArray()
                ->one();
            if (!$theBooking || ($theBooking['product'] && $theBooking['product']['op_status'] != 'op')) {
                throw new HttpException(404, Yii::t('x', 'Data not found.'));
            }
        }

        if ($case_id != 0) {
            $theCase = Kase::find()
                ->where(['id'=>$case_id])
                ->asArray()
                ->one();
            if (!$theCase) {
                throw new HttpException(404, 'Case not found.');
            }

            if (!in_array(USER_ID, [1, 4432, $theCase['created_by'], $theCase['updated_by'], $theCase['owner_id']])) {
                throw new HttpException(403, 'Access denied.');
            }
        }

        $data = [
            'tel'=>[],
            'email'=>[],
            'url'=>[],
            'addr'=>[],
            'passport'=>[],
            'connection'=>[],
            'other'=>[],
        ];

        if ($new_name != '') {
            $theContact->name = $new_name;
            $theForm->name = $new_name;
            $pos = strrpos($new_name, ' ');
            if ($pos > 0) {
                $theForm->fname = trim(substr($new_name, $pos));
                $theForm->lname = trim(substr($new_name, 0, $pos));
            }
        }
        if ($new_email != '') {
            $data['email'][] = [
                'name'=>'email',
                'value'=>trim(strtolower($new_email)),
                'note'=>'',
                'format'=>'email',
            ];
        }

        // Convert data or post to data
        if (Yii::$app->request->isPost && isset($_POST['name'])) {
            $cntPpt = 0;
            $cntAddr = 0;
            $cntTel = 0;
            $cntRel = 0;
            foreach ($_POST['name'] as $i=>$name) {
                if (in_array($name, ['tel', 'fax', 'mobile'])) {
                    $data['tel'][] = [
                        'name'=>$name,
                        'value'=>$_POST['full'][$cntTel] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'tel',
                        'full'=>$_POST['full'][$cntTel] ?? '',
                    ];
                    $cntTel ++;
                } elseif (in_array($name, ['email'])) {
                    $data['email'][] = [
                        'name'=>$name,
                        'value'=>$_POST['value'][$i] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'email',
                    ];
                } elseif (in_array($name, ['facebook', 'twitter', 'google-plus', 'youtube', 'linkedin', 'tripadvisor', 'website', 'url', 'link'])) {
                    $data['url'][] = [
                        'name'=>$name,
                        'value'=>$_POST['value'][$i] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'url',
                    ];
                } elseif (in_array($name, ['address'])) {
                    $addr_line_1 = $_POST['addr_line_1'][$cntAddr] ?? '';
                    $addr_line_2 = $_POST['addr_line_2'][$cntAddr] ?? '';
                    $addr_city = $_POST['addr_city'][$cntAddr] ?? '';
                    $addr_state = $_POST['addr_state'][$cntAddr] ?? '';
                    $addr_postal = $_POST['addr_postal'][$cntAddr] ?? '';
                    $addr_country = $_POST['addr_country'][$cntAddr] ?? '';

                    $value = implode("\n", [
                        $addr_line_1,
                        $addr_line_2,
                        $addr_city,
                        $addr_state,
                        $addr_postal,
                        $addr_country
                    ]);

                    $data['addr'][] = [
                        'name'=>$name,
                        'value'=>$value,
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'address',
                        'addr_line_1'=>$addr_line_1,
                        'addr_line_2'=>$addr_line_2,
                        'addr_city'=>$addr_city,
                        'addr_state'=>$addr_state,
                        'addr_postal'=>$addr_postal,
                        'addr_country'=>$addr_country,
                    ];
                    $cntAddr ++;
                } elseif (in_array($name, ['passport'])) {
                    $pp_country_code = $_POST['pp_country_code'][$cntPpt] ?? '';
                    $pp_number = $_POST['pp_number'][$cntPpt] ?? '';
                    $pp_name1 = $_POST['pp_name1'][$cntPpt] ?? '';
                    $pp_name2 = $_POST['pp_name2'][$cntPpt] ?? '';
                    $pp_gender = $_POST['pp_gender'][$cntPpt] ?? '';
                    $pp_bdate = $_POST['pp_bdate'][$cntPpt] ?? '';
                    $pp_idate = $_POST['pp_idate'][$cntPpt] ?? '';
                    $pp_edate = $_POST['pp_edate'][$cntPpt] ?? '';
                    $pp_file = $_POST['pp_file'][$cntPpt] ?? '';

                    $value = implode("\n", [
                        $pp_country_code,
                        $pp_number,
                        $pp_name1,
                        $pp_name2,
                        $pp_gender,
                        $pp_bdate,
                        $pp_idate,
                        $pp_edate,
                        $pp_file,
                    ]);

                    $data['passport'][] = [
                        'name'=>$name,
                        'value'=>$value,
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'passport',
                        'pp_country_code'=>$pp_country_code,
                        'pp_number'=>$pp_number,
                        'pp_name1'=>$pp_name1,
                        'pp_name2'=>$pp_name2,
                        'pp_gender'=>$pp_gender,
                        'pp_bdate'=>$pp_bdate,
                        'pp_idate'=>$pp_idate,
                        'pp_edate'=>$pp_edate,
                        'pp_file'=>'',
                    ];
                    $cntPpt ++;
                } elseif (in_array($name, ['connection'])) {
                    $data['connection'][] = [
                        'name'=>'connection',
                        'rel'=>$_POST['rel'][$cntRel] ?? '',
                        'relto'=>$_POST['relto'][$cntRel] ?? '',
                    ];
                    $cntRel ++;
                }
            }
        }

        // Tim xem khach o HS nao -> phu trach HS
//        $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        $kaseOwnerIdList = [];//Yii::$app->db->createCommand($sql, [':user_id'=>$id])->queryColumn();
        // TODO Tim xem khach o tour nao -> phu trach tour
        // $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        // $tourOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$id])->queryColumn();
        // \fCore::expose($tourOwnerIdList);
        // exit;
        // Huan, Fleur & CSKH only
        // $allowList = array_merge($kaseOwnerIdList, [1, 695, 118, 4432, 30554, 34355, 1351, 18598, 26435, 29123,29296,30554, 33415, 34595, 39748, 8162, 34596, 35071, 35887, 40218, 43178, 43179, 43180]);
        // if (!in_array(USER_ID, $allowList)) {
        //     throw new HttpException(403, 'Access denied.');
        // }

        $attribList = [
            'fname', 'lname', 'name',
            'gender', 'bday', 'bmonth', 'byear',
            'country_code', 'language',
            'info',
        ];

        $metaList = [
            'marital', 'pob', 'pob_country',
            'profession', 'job_title', 'employer',

            'traveler_profile', 'traveler_profile_assoc_names',
            'travel_preferences', 'diet', 'allergies', 'diet_note', 'health_condition', 'health_note',
            'transportation', 'transportation_note', 'future_travel_wishlist',
            'likes', 'dislikes',

            'rel_with_amica', 'customer_ranking', 'ambassaddor_potentiality',
            'newsletter_optin',
        ];

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (USER_ID == 1) {
                // \fCore::expose($_POST);
                // \fCore::expose($data);
                // exit;
            }
            // Search
            $emailList = '';
            $emailOne = '';
            foreach ($data['email'] as $cnt=>$item) {
                $emailList .= ' '.$item['value'];
                if ($emailOne == '') {
                    $emailOne = $item['value'];
                }
            }
            $telList = '';
            $telOne = '';
            foreach ($data['tel'] as $cnt=>$item) {
                $telList .= ' '.$item['value'];
                if ($telOne == '') {
                    $telOne = $item['value'];
                }
            }

            $theContact->created_at = NOW;
            $theContact->created_by = USER_ID;
            $theContact->updated_at = NOW;
            $theContact->updated_by = USER_ID;
            $theContact->status = 'on';
            $theContact->email = $emailOne;
            $theContact->phone = $telOne;

            foreach ($attribList as $name) {
                if (in_array($name, ['bday', 'bmonth', 'byear'])) {
                    $theContact->$name = (int)($theForm->$name ?? '');
                } else {
                    $theContact->$name = $theForm->$name ?? '';
                }
            }

            $theContact->save(false);

            Yii::$app->db->createCommand()->insert('at_search', [
                'rtype'=>'user',
                'rid'=>$theContact->id,
                'search'=>\fURL::makeFriendly(trim($theContact->name.' '.$theContact->fname.' '.$theContact->lname.' '.$emailList.' '.$telList), ' '),
                'found'=>trim($theContact->name.' '.$emailOne.' '.$telOne),
            ])->execute();

            // Avatar
            if (isset($_POST['slim']) && is_array($_POST['slim'])) {
                $slim = json_decode($_POST['slim'][0], true);
                // Move file
                if (isset($slim['path']) && $slim['path'] != '') {
                    $oldAvatar = $slim['path'];
                    $newAvatar = str_replace('/assets/slim_1.1.1/server/tmp/', '/upload/amica-user-avatars/', $slim['path']);
                    rename(Yii::getAlias('@webroot').$oldAvatar, Yii::getAlias('@webroot').$newAvatar);
                    $theContact->image = Yii::getAlias('@web').$newAvatar;
                    $theContact->save(false);
                }
            }

            // Connections
            if (!empty($data['connection'])) {
                foreach ($data['connection'] as $item) {
                    if ($item['rel'] != '' && $item['relto'] != '') {
                        $xp = explode(' ', $item['relto']);
                        $sql = 'INSERT INTO pax_relations (person_id, has_relation, to_person_id) VALUES (:p1, :r, :p2)';
                        Yii::$app->db->createCommand($sql, [
                            ':p1'=>trim($xp[0], '#'),
                            ':p2'=>$theContact['id'],
                            ':r'=>$item['rel'],
                        ])->execute();
                    }
                }
            }

            foreach ($metaList as $name) {
                $item = [
                    'name'=>$name,
                    'value'=>$theForm->$name ?? '',
                    'note'=>'',
                    'format'=>'',
                ];
                if (is_array($item['value'])) {
                    $item['value'] = implode('|', $item['value']);
                }
                if ($item['value'] != '') {
                    $data['other'][$name] =  $item;
                }
            }

            foreach ($data as $type=>$group) {
                if ($type != 'connection') {
                    foreach ($group as $item) {
                        Yii::$app->db->createCommand()->insert('metas', [
                            'created_dt'=>NOW,
                            'created_by'=>USER_ID,
                            'updated_dt'=>NOW,
                            'updated_by'=>USER_ID,
                            'rtype'=>'user',
                            'rid'=>$theContact->id,
                            'name'=>$item['name'],
                            'value'=>$item['value'],
                            'note'=>$item['note'],
                            'format'=>$item['format'],
                        ])->execute();
                    }
                }
            }

            if ($case_id != 0) {
                // Insert person to case
                $sql = 'INSERT INTO at_case_user (case_id, user_id, role) VALUES (:ki, :pi, "contact")';
                Yii::$app->db->createCommand($sql, [':ki'=>$case_id, ':pi'=>$theContact->id])->execute();
                return $this->redirect('@web/cases/r/'.$case_id);
            }

            if ($booking_id != 0) {
                $sql = 'INSERT INTO at_booking_user (created_at, created_by, updated_at, updated_by, booking_id, user_id) VALUES (:now, :me, :now, :me, :bi, :ui)';
                Yii::$app->db->createCommand($sql, [
                    ':now'=>NOW,
                    ':me'=>USER_ID,
                    ':bi'=>$booking_id,
                    ':ui'=>$theContact->id,
                ])->execute();
                return $this->redirect('@web/tours/pax/'.$theBooking['product']['id'].'?action=add&booking_id='.$booking_id);
            }

            return $this->redirect('/contacts/'.$theContact['id']);
        }

        return $this->render('contact_u', [
            'profile'=>$profile,
            'theContact'=>$theContact,
            'theForm'=>$theForm,
            'theBooking'=>$theBooking,
            'theCase'=>$theCase,
            'data'=>$data,
        ]);
    }


    public function actionR($id = 0)
    {
        if (USER_ID == 1 && isset($_POST['link_guide_text'], $_POST['link_guide_id']) && strlen(trim($_POST['link_guide_text'])) > 5) {
            $sql = 'UPDATE at_tour_guides SET guide_user_id=:id WHERE guide_user_id=0 AND LOCATE(:text, REPLACE(guide_name, " ", ""))!=0';
            Yii::$app->db->createCommand($sql, [':id'=>$_POST['link_guide_id'], ':text'=>str_replace([' ', '.'], ['', ''], $_POST['link_guide_text'])])->execute();
            $sql = 'UPDATE at_tour_guides SET guide_user_id=:id WHERE guide_user_id=0 AND LOCATE(:text, REPLACE(guide_name, ".", ""))!=0';
            Yii::$app->db->createCommand($sql, [':id'=>$_POST['link_guide_id'], ':text'=>str_replace([' ', '.'], ['', ''], $_POST['link_guide_text'])])->execute();
            return $this->redirect('/contacts/'.$id.'?listtours=guide');
        }

        if (USER_ID == 1 && isset($_POST['link_driver_text'], $_POST['link_driver_id']) && strlen(trim($_POST['link_driver_text'])) > 5) {
            $sql = 'UPDATE at_tour_drivers SET driver_user_id=:id WHERE driver_user_id=0 AND LOCATE(:text, REPLACE(driver_name, " ", ""))!=0';
            Yii::$app->db->createCommand($sql, [':id'=>$_POST['link_driver_id'], ':text'=>str_replace([' ', '.'], ['', ''], $_POST['link_driver_text'])])->execute();
            $sql = 'UPDATE at_tour_drivers SET driver_user_id=:id WHERE driver_user_id=0 AND LOCATE(:text, REPLACE(driver_name, ".", ""))!=0';
            Yii::$app->db->createCommand($sql, [':id'=>$_POST['link_driver_id'], ':text'=>str_replace([' ', '.'], ['', ''], $_POST['link_driver_text'])])->execute();
            return $this->redirect('/contacts/'.$id.'?listtours=driver');
        }

        $theContact = Contact::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($q) {
                    $q->select(['rid', 'name', 'value', 'note', 'format'])
                        ->orderBy('name, id');
                },
                'country'=>function($q){
                    return $q->select(['code', 'name_en']);
                },
                'roles',
                'cases'=>function($q){
                    return $q->select(['id', 'name', 'owner_id']);
                },
                'cases.stats',
                'cases.owner'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'bookings',
                'bookings.case'=>function($q){
                    return $q->select(['id', 'name', 'owner_id']);
                },
                'bookings.case.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'op_code', 'op_name', 'op_finish', 'day_count']);
                },
                'bookings.product.tourStats',
                'bookings.product.incidents'=>function($q) {
                    return $q->select(['id', 'tour_id']);
                },
                'bookings.product.complaints'=>function($q) {
                    return $q->select(['id', 'tour_id']);
                },
                'bookings.product.servicesPlus'=>function($q) {
                    return $q->select(['id', 'tour_id']);
                },
                'bookings.product.tour'=>function($q) {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'refCases'=>function($q){
                    return $q->select(['*']);
                },
                'refCases.stats',
                'refCases.bookings'=>function($q){
                    return $q->andWhere(['status'=>'won']);
                },
                'refCases.bookings.product'=>function($q){
                    return $q->select(['id', 'op_name', 'op_code', 'op_finish', 'day_count', 'day_from']);
                },
                'refCases.bookings.product.tourStats',
                'refCases.bookings.invoices'=>function($q){
                    return $q->select(['id', 'booking_id', 'amount', 'currency', 'stype']);
                },
                'refCases.owner'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'member',
                'profileTourguide',
                'profileDriver',
                'createdBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theContact) {
            throw new HttpException(404, 'Contact not found');
        }

        $theProducts = [];
        if ($theContact['profileTourguide']) {
            $theProducts = Yii::$app->db->createCommand('SELECT tg.pax_ratings, t.id, t.code AS op_code, t.name AS op_name FROM at_tours t, at_tour_guide tg, contacts u WHERE tg.user_id=:id AND tg.pax_ratings!="" AND u.id=tg.user_id AND tg.tour_id=t.id GROUP BY tg.tour_id ORDER BY SUBSTRING(t.code, 2, 6) DESC', [':id'=>$theContact['id']])->queryAll();
        }

        $userMemberProfile = Member::find()
            ->where(['contact_id'=>$id])
            ->asArray()
            ->one();

        $userFiles = File::find()
            ->where(['rtype'=>'user', 'rid'=>$id])
            ->asArray()
            ->all();

        $userNotes = [];
        // Note::find()
        //     ->where(['or', ['rtype'=>'user', 'rid'=>$id], ['from_id'=>$id]])
        //     ->with('updatedBy')
        //     ->orderBy('co DESC')
        //     ->limit(10)
        //     ->asArray()
        //     ->all();

        // Users who viewed this
        $viewedBy = [];/*Yii::$app->db
            ->createCommand('SELECT u.nickname AS name, u.id FROM users u, hits h WHERE h.user_id=u.id AND h.uri=:uri GROUP BY u.id ORDER BY u.lname, u.fname', [':uri'=>'/users/r/'.$id])
            ->queryAll();*/

        $userMails = [];
        $mailList = [];
        foreach ($theContact['metas'] as $meta) {
            // if ($meta['name'] == 'email') {
            //     // $mailList[] = $meta['value'];
            //     $userMails = Mail::find()
            //         ->select(['id', 'subject', 'sent_dt', 'case_id'])
            //         ->where('LOCATE(:email, `from`)!=0 OR LOCATE(:email, `to`)!=0', [':email'=>$meta['value']])
            //         ->asArray()
            //         ->orderBy('sent_dt DESC')
            //         ->limit(50)
            //         ->all();
            //     break;
            // }
        }
        // if ($theContact['email'] != '') {
        //     $sql = 'select id, subject, sent_dt, case_id from at_mails where locate(:email, `from`)!=0 or locate(:email, `to`)!=0 order by sent_dt desc limit 20';
        //     $userMails = Yii::$app->db->createCommand($sql, [':email'=>$theContact['email']])->queryAll();
        // }

        return $this->render('contact_r', [
            'theContact'=>$theContact,
            'userFiles'=>$userFiles,
            'userNotes'=>$userNotes,
            'userMails'=>$userMails,
            'userMemberProfile'=>$userMemberProfile,
            'viewedBy'=>$viewedBy,
            'theProducts'=>$theProducts,
        ]);
    }

    /**
     * Delete a contact
     */
    public function actionD($id = 0)
    {
        $theUser = Contact::findOne($id);

        if (!$theUser) {
            throw new HttpException(404, 'User not found.');
        }

        if (Yii::$app->user->id != 1) {
            throw new HttpException(403, 'Access denied.');
        }

        $getAction = Yii::$app->request->get('action', 'none');
        if ($getAction == 'name') {
            $names = explode(' ', $theUser['fname']);
            $getOption = Yii::$app->request->get('option', 12);
            if ($getOption == 12) {
                $fname = trim($names[0]);
                $lname = trim($names[1]);
            } else {
                $fname = trim($names[1]);
                $lname = trim($names[0]);
            }

            Yii::$app->db->createCommand()
                ->update('contacts', [
                    'fname'=>$fname,
                    'lname'=>$lname,
                    ], [
                    'id'=>$id
                    ])
                ->execute();
            die('OK USER NAME');
        }

        if (Yii::$app->request->get('action') == 'delete') {
            Yii::$app->db->createCommand()
                ->update('contacts', [
                    'fname'=>'A-BLANK-NAME',
                    'lname'=>'',
                    'name'=>'',
                    'about'=>'',
                    'email'=>'',
                    'phone'=>'',
                    'bday'=>0,
                    'bmonth'=>0,
                    'byear'=>0,
                    'is_client'=>'no',
                    ], [
                    'id'=>$id
                    ])
                ->execute();

            Yii::$app->db->createCommand()
                ->delete('metas', ['rtype'=>'user', 'rid'=>$id])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('at_passports', ['user_id'=>$id])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('at_search', ['rtype'=>'user', 'rid'=>$id])
                ->execute();

            die('OK USER DEL');
            return $this->redirect('@web/users/r/'.$id);
        }
    }
    /**
     * question loop
     */
    public function html_question($question = [], $params = '', $cnt = 1) {
        $class = ($cnt > 1)? 'card non_last_select': 'parent_class';
        $html = '<div class="col-md-12 '.$class.'" data-count="'. $cnt .'">';
        $cnt ++;
        foreach ($question as $key => $op) {
            if (is_array($op)) {
                $html .= '<div class="group"><label class=""><input type="radio" name="" value=""> ' . $key .' </label>';
                $html .= $this->html_question($op, '', $cnt). '</div>';
            } else {
                $read_only = '';
                if (strpos($key, '_readonly') !== false) {
                    $key = str_replace('_readonly', '', $key);
                    $read_only = ' disable ';
                }
                if (strpos($key, '_checkbox') !== false) {
                    $key = str_replace('_checkbox', '', $key);
                    $html .= '<label><input class="last_select '. $read_only .'" name="" type="checkbox"  value="'.$op.'" > '. $key .'</label>';
                } else {
                    $checked = '';
                    if ($params >= 4) {
                        $params = '> 4';
                    }
                    if ($key === $params) {
                        $checked = 'checked';
                    }
                    $html .= '<label><input class="last_select" name="" type="radio" '.$checked.' value="'.$op.'"> '. $key .'</label>';
                }
            }
        }
        $html .= '</div>';
        return $html;
    }


    /**
     * Edit contact info
     */
    public function actionU($id = 0,
        $profile = '',
        $booking_id = 0, // Return to booking after save
        $case_id = 0, // Return to case after save
        $test = ''
        )
    {
        $theBooking = false;
        $theCase = false;

        $tourOpList = [];

        $theContact = Contact::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($q){
                    return $q->select(['id', 'rid', 'name', 'value', 'note', 'format']);
                },
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->one();
        if (!$theContact) {
            throw new HttpException(404, 'Contact not found.');
        }


        if ($booking_id != 0) {
            $theBooking = Booking::find()
                ->where(['id'=>$booking_id])
                ->with([
                    'product'=>function($q){
                        return $q->select(['id', 'op_code', 'op_name', 'op_status']);
                    },
                    'product.tour'=>function($q){
                        return $q->select(['id']);
                    },
                ])
                ->asArray()
                ->one();
            if (!$theBooking || ($theBooking['product'] && $theBooking['product']['op_status'] != 'op')) {
                throw new HttpException(404, Yii::t('x', 'Data not found.'));
            }


            // Kiem tra user = dieu hanh tour
            $sql = 'SELECT user_id FROM at_tour_user WHERE role="operator" AND tour_id=:tour_id';
            $tourOpList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theBooking['product']['tour']['id']])->queryColumn();
        }

        // Kiem tra duoc phep edit:
        // - QHKH, ban hang cua HS, dieu hanh cua tour

        $reverseRelList = [
            'spouse'=>'spouse',
            'parent'=>'child',
            'child'=>'parent',
            'sibling'=>'sibling',
            'grandparent'=>'grandchild',
            'grandchild'=>'grandparent',
            'aunt_uncle'=>'nephew_niece',
            'nephew_niece'=>'aunt_uncle',
            'cousin'=>'cousin',
            'relative'=>'relative',
            'friend'=>'friend',
            'acquaintance'=>'acquaintance',
            'colleague'=>'colleague',
            'partner'=>'partner',
            'in-law'=>'in-law',
        ];
        // Connections
        $connections = [];

        $sql = 'SELECT r.has_relation, p.id, p.name FROM pax_relations r, contacts p WHERE p.id=r.to_person_id AND person_id=:id';
        $conn1 = Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->queryAll();
        foreach ($conn1 as $conn) {
            $connections[] = [
                'rel'=>$reverseRelList[$conn['has_relation']] ?? '',
                'relto'=>'#'.$conn['id'].' '.$conn['name'],
            ];
        }

        $sql = 'SELECT r.has_relation, p.id, p.name FROM pax_relations r, contacts p WHERE p.id=r.person_id AND to_person_id=:id';
        $conn2 = Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->queryAll();
        foreach ($conn2 as $conn) {
            $connections[] = [
                'rel'=>$conn['has_relation'],
                'relto'=>'#'.$conn['id'].' '.$conn['name'],
            ];
        }

        $theForm = new \app\models\ContactEditForm;

        $data = [
            'tel'=>[],
            'email'=>[],
            'url'=>[],
            'addr'=>[],
            'passport'=>[],
            'connection'=>[],
            'other'=>[],
        ];

        if (!Yii::$app->request->isPost) {
            // Load from DB
            foreach ($theContact['metas'] as $meta) {
                if (in_array($meta['format'], ['tel'])) {
                    $data['tel'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                        'full'=>$meta['value'],
                    ];
                } elseif (in_array($meta['format'], ['email'])) {
                    $data['email'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                    ];
                } elseif (in_array($meta['format'], ['url'])) {
                    $data['url'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                    ];
                } elseif (in_array($meta['format'], ['address'])) {
                    $addr = explode("\n", $meta['value']);
                    $data['addr'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                        'addr_line_1'=>$addr[0] ?? '',
                        'addr_line_2'=>$addr[1] ?? '',
                        'addr_city'=>$addr[2] ?? '',
                        'addr_state'=>$addr[3] ?? '',
                        'addr_postal'=>$addr[4] ?? '',
                        'addr_country'=>$addr[5] ?? '',
                    ];
                } elseif (in_array($meta['name'], ['passport'])) {
                    $passport = explode(chr(10), $meta['value']);

                    $data['passport'][] = [
                        'format'=>'passport',
                        'name'=>'passport',
                        'value'=>'',
                        'note'=>$meta['note'],
                        'pp_country_code'=>$passport[0] ?? '',
                        'pp_number'=>$passport[1] ?? '',
                        'pp_name1'=>$passport[2] ?? '',
                        'pp_name2'=>$passport[3] ?? '',
                        'pp_gender'=>$passport[4] ?? '',
                        'pp_bdate'=>$passport[5] ?? '',
                        'pp_idate'=>$passport[6] ?? '',
                        'pp_edate'=>$passport[7] ?? '',
                        'pp_file'=>$passport[8] ?? '',
                    ];
                }
            }
            foreach ($connections as $conn) {
                $data['connection'][] = [
                    'name'=>'connection',
                    'rel'=>$conn['rel'],
                    'relto'=>$conn['relto'],
                ];
            }
        } else {
            // Load from POST
            if (isset($_POST['name'])) {
                $cntPpt = 0;
                $cntRel = 0;
                $cntAddr = 0;
                $cntTel = 0;
                foreach ($_POST['name'] as $i=>$name) {
                    if (in_array($name, ['tel', 'fax', 'mobile'])) {
                        $data['tel'][] = [
                            'name'=>$name,
                            'value'=>$_POST['full'][$cntTel] ?? '',
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'tel',
                            'full'=>$_POST['full'][$cntTel] ?? '',
                        ];
                        $cntTel ++;
                    } elseif (in_array($name, ['email'])) {
                        $data['email'][] = [
                            'name'=>$name,
                            'value'=>$_POST['value'][$i] ?? '',
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'email',
                        ];
                    } elseif (in_array($name, ['facebook', 'twitter', 'google-plus', 'youtube', 'linkedin', 'tripadvisor', 'website', 'url', 'link'])) {
                        $data['url'][] = [
                            'name'=>$name,
                            'value'=>$_POST['value'][$i] ?? '',
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'url',
                        ];
                    } elseif (in_array($name, ['address'])) {
                        $addr_line_1 = $_POST['addr_line_1'][$cntAddr] ?? '';
                        $addr_line_2 = $_POST['addr_line_2'][$cntAddr] ?? '';
                        $addr_city = $_POST['addr_city'][$cntAddr] ?? '';
                        $addr_state = $_POST['addr_state'][$cntAddr] ?? '';
                        $addr_postal = $_POST['addr_postal'][$cntAddr] ?? '';
                        $addr_country = $_POST['addr_country'][$cntAddr] ?? '';

                        $value = implode("\n", [
                            $addr_line_1,
                            $addr_line_2,
                            $addr_city,
                            $addr_state,
                            $addr_postal,
                            $addr_country
                        ]);

                        $data['addr'][] = [
                            'name'=>$name,
                            'value'=>$value,
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'address',
                            'addr_line_1'=>$addr_line_1,
                            'addr_line_2'=>$addr_line_2,
                            'addr_city'=>$addr_city,
                            'addr_state'=>$addr_state,
                            'addr_postal'=>$addr_postal,
                            'addr_country'=>$addr_country,
                        ];
                        $cntAddr ++;
                    } elseif (in_array($name, ['passport'])) {
                        $pp_country_code = $_POST['pp_country_code'][$cntPpt] ?? '';
                        $pp_number = $_POST['pp_number'][$cntPpt] ?? '';
                        $pp_name1 = $_POST['pp_name1'][$cntPpt] ?? '';
                        $pp_name2 = $_POST['pp_name2'][$cntPpt] ?? '';
                        $pp_gender = $_POST['pp_gender'][$cntPpt] ?? '';
                        $pp_bdate = $_POST['pp_bdate'][$cntPpt] ?? '';
                        $pp_idate = $_POST['pp_idate'][$cntPpt] ?? '';
                        $pp_edate = $_POST['pp_edate'][$cntPpt] ?? '';
                        $pp_file = $_POST['pp_file'][$cntPpt] ?? '';

                        $value = implode("\n", [
                            $pp_country_code,
                            $pp_number,
                            $pp_name1,
                            $pp_name2,
                            $pp_gender,
                            $pp_bdate,
                            $pp_idate,
                            $pp_edate,
                            $pp_file,
                        ]);

                        $data['passport'][] = [
                            'name'=>$name,
                            'value'=>$value,
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'passport',
                            'pp_country_code'=>$pp_country_code,
                            'pp_number'=>$pp_number,
                            'pp_name1'=>$pp_name1,
                            'pp_name2'=>$pp_name2,
                            'pp_gender'=>$pp_gender,
                            'pp_bdate'=>$pp_bdate,
                            'pp_idate'=>$pp_idate,
                            'pp_edate'=>$pp_edate,
                            'pp_file'=>'',
                        ];
                        $cntPpt ++;
                    } elseif (in_array($name, ['connection'])) {
                        $data['connection'][] = [
                            'name'=>'connection',
                            'rel'=>$_POST['rel'][$cntRel] ?? '',
                            'relto'=>$_POST['relto'][$cntRel] ?? '',
                        ];
                        $cntRel ++;
                    }
                }
            }
        }

        // Tim xem khach o HS nao -> phu trach HS
        $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        $kaseOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$theContact->id])->queryColumn();
        // TODO Tim xem khach o tour nao -> phu trach tour
        // $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        // $tourOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$id])->queryColumn();
        // \fCore::expose($tourOwnerIdList);
        // exit;
        // Huan, Fleur & CSKH only;
        // Removed Fleur, added Viet Anh
        // Added Thu Hong
        // Added Ai Diep
        // Added Ngoc Anh
        // Added Minh Hien
        // Added Tii
        // Added Hue Huong, Quynh Trang
        // Added Thanh Ha
        $allowList = array_merge($tourOpList, $kaseOwnerIdList, [1, 695, 118, 4432, 30554, 34355, 1351, 18598, 45324, 29123,29296,30554, 33415, 34595, 39748, 8162, 34596, 35071, 35887, 40218, 43178, 43179, 43180, 45166, 47034, 12952, 48418, 49435, 35088, 49949, 51532]);
        // if (!in_array(USER_ID, $allowList)) {
        //     throw new HttpException(403, 'Access denied.');
        // }

        $attribList = [
            'fname', 'lname', 'name',
            'gender', 'bday', 'bmonth', 'byear',
            'country_code', 'language',
            'info',
        ];
        $metaList = [
            'marital', 'pob', 'pob_country',
            'profession', 'job_title', 'employer',

            'traveler_profile', 'traveler_profile_assoc_names',
            'travel_preferences', 'diet', 'allergies', 'diet_note', 'health_condition', 'health_note',
            'transportation', 'transportation_note', 'future_travel_wishlist',
            'likes', 'dislikes',

            'rel_with_amica', 'customer_ranking', 'ambassaddor_potentiality',
            'newsletter_optin',
        ];

        foreach ($attribList as $name) {
            if (in_array($name, ['bday', 'bmonth', 'byear']) && $theContact->$name == 0) {
                $theForm->$name = '';
            } else {
                $theForm->$name = $theContact->$name ?? '';
            }
        }

        foreach ($metaList as $name) {
            foreach ($theContact['metas'] as $meta) {
                if ($meta['name'] == $name) {
                    if (strpos($meta['value'], '|') !== false) {
                        $theForm->$name = explode('|', $meta['value']);
                    } else {
                        $theForm->$name = $meta['value'];
                    }
                }
            }
        }

        // Tourguide profile
        $theProfile = false;
        if (USER_ID == 1 && $profile == 'tourguide') {
            $theProfile = TourguideProfile::find()
                ->where(['user_id'=>$theContact['id']])
                ->one();
            if (!$theProfile) {
                $theProfile = new TourguideProfile;
            }
            $theProfile->scenario = 'tourguide/u';
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            if (USER_ID == 1) {
                // \fCore::expose($_POST);
                // \fCore::expose($data);
                // exit;
            }
            if (isset($_POST['slim']) && is_array($_POST['slim'])) {
                $slim = json_decode($_POST['slim'][0], true);
                // Move file
                if (isset($slim['path']) && $slim['path'] != '') {
                    $oldAvatar = $slim['path'];
                    $newAvatar = str_replace('/assets/slim_1.1.1/server/tmp/', '/upload/amica-user-avatars/', $slim['path']);
                    rename(Yii::getAlias('@webroot').$oldAvatar, Yii::getAlias('@webroot').$newAvatar);
                    $theContact->image = Yii::getAlias('@web').$newAvatar;
                }
            }

            $sql = 'DELETE FROM pax_relations WHERE person_id=:id OR to_person_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->execute();
            if (!empty($data['connection'])) {
                foreach ($data['connection'] as $item) {
                    if ($item['rel'] != '' && $item['relto'] != '') {
                        $xp = explode(' ', $item['relto']);
                        $sql = 'INSERT INTO pax_relations (person_id, has_relation, to_person_id) VALUES (:p1, :r, :p2)';
                        Yii::$app->db->createCommand($sql, [
                            ':p1'=>trim($xp[0], '#'),
                            ':p2'=>$theContact['id'],
                            ':r'=>$item['rel'],
                        ])->execute();
                    }
                }
            }

            // Search
            $emailList = '';
            $emailOne = '';
            foreach ($data['email'] as $cnt=>$item) {
                $emailList .= ' '.$item['value'];
                if ($emailOne == '') {
                    $emailOne = $item['value'];
                }
            }
            $telList = '';
            $telOne = '';
            foreach ($data['tel'] as $cnt=>$item) {
                $telList .= ' '.$item['value'];
                if ($telOne == '') {
                    $telOne = $item['value'];
                }
            }

            $theContact->email = $emailOne;
            $theContact->phone = $telOne;

            foreach ($attribList as $name) {
                if (in_array($name, ['bday', 'bmonth', 'byear'])) {
                    $theContact->$name = (int)($theForm->$name ?? '');
                } else {
                    $theContact->$name = $theForm->$name ?? '';
                }
            }

            $theContact->updated_at = NOW;
            $theContact->updated_by = USER_ID;
            $theContact->save(false);

            foreach ($metaList as $name) {
                $item = [
                    'name'=>$name,
                    'value'=>$theForm->$name ?? '',
                    'note'=>'',
                    'format'=>'',
                ];
                if (is_array($item['value'])) {
                    $item['value'] = implode('|', $item['value']);
                }
                if ($item['value'] != '') {
                    $data['other'][$name] =  $item;
                }
            }

            // TODO save meta
            $cnt = 0;
            $max = count($theContact['metas']);

            foreach ($data as $type=>$group) {
                if ($type != 'connection') {
                    foreach ($group as $item) {
                        if (isset($theContact['metas'][$cnt])) {
                            Yii::$app->db->createCommand()->update('metas', [
                                'name'=>$item['name'],
                                'value'=>$item['value'],
                                'note'=>$item['note'],
                                'format'=>$item['format'],
                            ], [
                                'id'=>$theContact['metas'][$cnt]['id'],
                            ])->execute();
                        } else {
                            Yii::$app->db->createCommand()->insert('metas', [
                                'created_dt'=>NOW,
                                'created_by'=>USER_ID,
                                'updated_dt'=>NOW,
                                'updated_by'=>USER_ID,
                                'rtype'=>'user',
                                'rid'=>$theContact->id,
                                'name'=>$item['name'],
                                'value'=>$item['value'],
                                'note'=>$item['note'],
                                'format'=>$item['format'],
                            ])->execute();
                        }
                        $cnt ++;
                    }
                } // if not group connection
            }
            if ($cnt <= $max) {
                for ($i = $cnt; $i < $max; $i ++) {
                    // Delete item
                    Yii::$app->db->createCommand()->delete('metas', [
                        'id'=>$theContact['metas'][$i]['id'],
                    ])->execute();
                }
            }

            $searchText = \fURL::makeFriendly(trim($theContact->name.' '.$theContact->fname.' '.$theContact->lname.' '.$emailList.' '.$telList), ' ');
            $foundText = trim($theContact->name.' '.$emailOne.' '.$telOne);
            $sql = 'SELECT * FROM at_search WHERE rtype="user" AND rid=:id LIMIT 1';
            $search = Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->queryOne();
            if (!$search) {
                Yii::$app->db->createCommand()->insert('at_search', [
                    'rtype'=>'user',
                    'rid'=>$theContact['id'],
                    'search'=>$searchText,
                    'found'=>$foundText,
                ])->execute();
            } else {
                Yii::$app->db->createCommand()->update('at_search', [
                    'search'=>$searchText,
                    'found'=>$foundText,
                ], ['id'=>$search['id']])->execute();
            }

            if ($case_id != 0) {
                return $this->redirect('/cases/r/'.$case_id);
            }

            if ($booking_id != 0) {
                return $this->redirect('/tours/pax/'.$theBooking['product']['id'].'?action=add&booking_id='.$booking_id);
            }

            return $this->redirect('/contacts/'.$theContact['id']);
        }

        return $this->render('contact_u', [
            'profile'=>$profile,
            'theContact'=>$theContact,
            'theProfile'=>$theProfile,
            'theForm'=>$theForm,
            'theBooking'=>$theBooking,
            'theCase'=>$theCase,
            'data'=>$data,
        ]);
    }

    /**
     * TEST Edit contact info
     */
    public function actionU2($id = 0,
        $profile = '',
        $booking_id = 0, // Return to booking after save
        $case_id = 0, // Return to case after save
        $test = ''
        )
    {
        $theBooking = false;
        $theCase = false;

        $tourOpList = [];

        $theContact = Contact::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($q){
                    return $q->select(['id', 'rid', 'name', 'value', 'note', 'format']);
                },
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->one();
        if (!$theContact) {
            throw new HttpException(404, 'Contact not found.');
        }

        if ($booking_id != 0) {
            $theBooking = Booking::find()
                ->where(['id'=>$booking_id])
                ->with([
                    'product'=>function($q){
                        return $q->select(['id', 'op_code', 'op_name', 'op_status']);
                    },
                    'product.tour'=>function($q){
                        return $q->select(['id']);
                    },
                ])
                ->asArray()
                ->one();
            if (!$theBooking || ($theBooking['product'] && $theBooking['product']['op_status'] != 'op')) {
                throw new HttpException(404, Yii::t('x', 'Data not found.'));
            }

            // Kiem tra user = dieu hanh tour
            $sql = 'SELECT user_id FROM at_tour_user WHERE role="operator" AND tour_id=:tour_id';
            $tourOpList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theBooking['product']['tour']['id']])->queryColumn();
        }

        // Kiem tra duoc phep edit:
        // - QHKH, ban hang cua HS, dieu hanh cua tour

        $reverseRelList = [
            'spouse'=>'spouse',
            'parent'=>'child',
            'child'=>'parent',
            'sibling'=>'sibling',
            'grandparent'=>'grandchild',
            'grandchild'=>'grandparent',
            'aunt_uncle'=>'nephew_niece',
            'nephew_niece'=>'aunt_uncle',
            'cousin'=>'cousin',
            'relative'=>'relative',
            'friend'=>'friend',
            'acquaintance'=>'acquaintance',
            'colleague'=>'colleague',
            'partner'=>'partner',
            'in-law'=>'in-law',
        ];
        // Connections
        $connections = [];

        $sql = 'SELECT r.has_relation, p.id, p.name FROM pax_relations r, contacts p WHERE p.id=r.to_person_id AND person_id=:id';
        $conn1 = Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->queryAll();
        foreach ($conn1 as $conn) {
            $connections[] = [
                'rel'=>$reverseRelList[$conn['has_relation']] ?? '',
                'relto'=>'#'.$conn['id'].' '.$conn['name'],
            ];
        }

        $sql = 'SELECT r.has_relation, p.id, p.name FROM pax_relations r, contacts p WHERE p.id=r.person_id AND to_person_id=:id';
        $conn2 = Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->queryAll();
        foreach ($conn2 as $conn) {
            $connections[] = [
                'rel'=>$conn['has_relation'],
                'relto'=>'#'.$conn['id'].' '.$conn['name'],
            ];
        }

        $theForm = new \app\models\ContactEditForm;

        $data = [
            'tel'=>[],
            'email'=>[],
            'url'=>[],
            'addr'=>[],
            'passport'=>[],
            'connection'=>[],
            'other'=>[],
        ];

        if (!Yii::$app->request->isPost) {
            // Load from DB
            foreach ($theContact['metas'] as $meta) {
                if (in_array($meta['format'], ['tel'])) {
                    $data['tel'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                        'full'=>$meta['value'],
                    ];
                } elseif (in_array($meta['format'], ['email'])) {
                    $data['email'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                    ];
                } elseif (in_array($meta['format'], ['url'])) {
                    $data['url'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                    ];
                } elseif (in_array($meta['format'], ['address'])) {
                    $addr = explode("\n", $meta['value']);
                    $data['addr'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                        'addr_line_1'=>$addr[0] ?? '',
                        'addr_line_2'=>$addr[1] ?? '',
                        'addr_city'=>$addr[2] ?? '',
                        'addr_state'=>$addr[3] ?? '',
                        'addr_postal'=>$addr[4] ?? '',
                        'addr_country'=>$addr[5] ?? '',
                    ];
                } elseif (in_array($meta['name'], ['passport'])) {
                    $passport = explode(chr(10), $meta['value']);

                    $data['passport'][] = [
                        'format'=>'passport',
                        'name'=>'passport',
                        'value'=>'',
                        'note'=>$meta['note'],
                        'pp_country_code'=>$passport[0] ?? '',
                        'pp_number'=>$passport[1] ?? '',
                        'pp_name1'=>$passport[2] ?? '',
                        'pp_name2'=>$passport[3] ?? '',
                        'pp_gender'=>$passport[4] ?? '',
                        'pp_bdate'=>$passport[5] ?? '',
                        'pp_idate'=>$passport[6] ?? '',
                        'pp_edate'=>$passport[7] ?? '',
                        'pp_file'=>$passport[8] ?? '',
                    ];
                }
            }
            foreach ($connections as $conn) {
                $data['connection'][] = [
                    'name'=>'connection',
                    'rel'=>$conn['rel'],
                    'relto'=>$conn['relto'],
                ];
            }
        } else {
            // Load from POST
            if (isset($_POST['name'])) {
                $cntPpt = 0;
                $cntRel = 0;
                $cntAddr = 0;
                $cntTel = 0;
                foreach ($_POST['name'] as $i=>$name) {
                    if (in_array($name, ['tel', 'fax', 'mobile'])) {
                        $data['tel'][] = [
                            'name'=>$name,
                            'value'=>$_POST['full'][$cntTel] ?? '',
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'tel',
                            'full'=>$_POST['full'][$cntTel] ?? '',
                        ];
                        $cntTel ++;
                    } elseif (in_array($name, ['email'])) {
                        $data['email'][] = [
                            'name'=>$name,
                            'value'=>$_POST['value'][$i] ?? '',
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'email',
                        ];
                    } elseif (in_array($name, ['facebook', 'twitter', 'google-plus', 'youtube', 'linkedin', 'tripadvisor', 'website', 'url', 'link'])) {
                        $data['url'][] = [
                            'name'=>$name,
                            'value'=>$_POST['value'][$i] ?? '',
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'url',
                        ];
                    } elseif (in_array($name, ['address'])) {
                        $addr_line_1 = $_POST['addr_line_1'][$cntAddr] ?? '';
                        $addr_line_2 = $_POST['addr_line_2'][$cntAddr] ?? '';
                        $addr_city = $_POST['addr_city'][$cntAddr] ?? '';
                        $addr_state = $_POST['addr_state'][$cntAddr] ?? '';
                        $addr_postal = $_POST['addr_postal'][$cntAddr] ?? '';
                        $addr_country = $_POST['addr_country'][$cntAddr] ?? '';

                        $value = implode("\n", [
                            $addr_line_1,
                            $addr_line_2,
                            $addr_city,
                            $addr_state,
                            $addr_postal,
                            $addr_country
                        ]);

                        $data['addr'][] = [
                            'name'=>$name,
                            'value'=>$value,
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'address',
                            'addr_line_1'=>$addr_line_1,
                            'addr_line_2'=>$addr_line_2,
                            'addr_city'=>$addr_city,
                            'addr_state'=>$addr_state,
                            'addr_postal'=>$addr_postal,
                            'addr_country'=>$addr_country,
                        ];
                        $cntAddr ++;
                    } elseif (in_array($name, ['passport'])) {
                        $pp_country_code = $_POST['pp_country_code'][$cntPpt] ?? '';
                        $pp_number = $_POST['pp_number'][$cntPpt] ?? '';
                        $pp_name1 = $_POST['pp_name1'][$cntPpt] ?? '';
                        $pp_name2 = $_POST['pp_name2'][$cntPpt] ?? '';
                        $pp_gender = $_POST['pp_gender'][$cntPpt] ?? '';
                        $pp_bdate = $_POST['pp_bdate'][$cntPpt] ?? '';
                        $pp_idate = $_POST['pp_idate'][$cntPpt] ?? '';
                        $pp_edate = $_POST['pp_edate'][$cntPpt] ?? '';
                        $pp_file = $_POST['pp_file'][$cntPpt] ?? '';

                        $value = implode("\n", [
                            $pp_country_code,
                            $pp_number,
                            $pp_name1,
                            $pp_name2,
                            $pp_gender,
                            $pp_bdate,
                            $pp_idate,
                            $pp_edate,
                            $pp_file,
                        ]);

                        $data['passport'][] = [
                            'name'=>$name,
                            'value'=>$value,
                            'note'=>$_POST['note'][$i] ?? '',
                            'format'=>'passport',
                            'pp_country_code'=>$pp_country_code,
                            'pp_number'=>$pp_number,
                            'pp_name1'=>$pp_name1,
                            'pp_name2'=>$pp_name2,
                            'pp_gender'=>$pp_gender,
                            'pp_bdate'=>$pp_bdate,
                            'pp_idate'=>$pp_idate,
                            'pp_edate'=>$pp_edate,
                            'pp_file'=>'',
                        ];
                        $cntPpt ++;
                    } elseif (in_array($name, ['connection'])) {
                        $data['connection'][] = [
                            'name'=>'connection',
                            'rel'=>$_POST['rel'][$cntRel] ?? '',
                            'relto'=>$_POST['relto'][$cntRel] ?? '',
                        ];
                        $cntRel ++;
                    }
                }
            }
        }

        // Tim xem khach o HS nao -> phu trach HS
        $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        $kaseOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$theContact->id])->queryColumn();
        // TODO Tim xem khach o tour nao -> phu trach tour
        // $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        // $tourOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$id])->queryColumn();
        // \fCore::expose($tourOwnerIdList);
        // exit;
        // Huan, Fleur & CSKH only;
        // Removed Fleur, added Viet Anh
        // Added Thu Hong
        // Added Ai Diep
        // Added Ngoc Anh
        // Added Minh Hien
        // Added Tii
        // Added Hue Huong, Quynh Trang
        $allowList = array_merge($tourOpList, $kaseOwnerIdList, [1, 695, 118, 4432, 30554, 34355, 1351, 18598, 45324, 29123,29296,30554, 33415, 34595, 39748, 8162, 34596, 35071, 35887, 40218, 43178, 43179, 43180, 45166, 47034, 12952, 48418, 49435, 35088, 49949]);
        if (!in_array(USER_ID, $allowList)) {
            throw new HttpException(403, 'Access denied.');
        }

        $attribList = [
            'fname', 'lname', 'name',
            'gender', 'bday', 'bmonth', 'byear',
            'country_code', 'language',
            'info',
        ];
        $metaList = [
            'marital', 'pob', 'pob_country',
            'profession', 'job_title', 'employer',

            'traveler_profile', 'traveler_profile_assoc_names',
            'travel_preferences', 'diet', 'allergies', 'diet_note', 'health_condition', 'health_note',
            'transportation', 'transportation_note', 'future_travel_wishlist',
            'likes', 'dislikes',

            'rel_with_amica', 'customer_ranking', 'ambassaddor_potentiality',
            'newsletter_optin',
        ];

        foreach ($attribList as $name) {
            if (in_array($name, ['bday', 'bmonth', 'byear']) && $theContact->$name == 0) {
                $theForm->$name = '';
            } else {
                $theForm->$name = $theContact->$name ?? '';
            }
        }

        foreach ($metaList as $name) {
            foreach ($theContact['metas'] as $meta) {
                if ($meta['name'] == $name) {
                    if (strpos($meta['value'], '|') !== false) {
                        $theForm->$name = explode('|', $meta['value']);
                    } else {
                        $theForm->$name = $meta['value'];
                    }
                }
            }
        }

        // Tourguide profile
        $theProfile = false;
        if (USER_ID == 1 && $profile == 'tourguide') {
            $theProfile = TourguideProfile::find()
                ->where(['user_id'=>$theContact['id']])
                ->one();
            if (!$theProfile) {
                $theProfile = new TourguideProfile;
            }
            $theProfile->scenario = 'tourguide/u';
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            if (USER_ID == 1) {
                // \fCore::expose($_POST);
                // \fCore::expose($data);
                // exit;
            }
            if (isset($_POST['slim']) && is_array($_POST['slim'])) {
                $slim = json_decode($_POST['slim'][0], true);
                // Move file
                if (isset($slim['path']) && $slim['path'] != '') {
                    $oldAvatar = $slim['path'];
                    $newAvatar = str_replace('/assets/slim_1.1.1/server/tmp/', '/upload/amica-user-avatars/', $slim['path']);
                    rename(Yii::getAlias('@webroot').$oldAvatar, Yii::getAlias('@webroot').$newAvatar);
                    $theContact->image = Yii::getAlias('@web').$newAvatar;
                }
            }

            $sql = 'DELETE FROM pax_relations WHERE person_id=:id OR to_person_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->execute();
            if (!empty($data['connection'])) {
                foreach ($data['connection'] as $item) {
                    if ($item['rel'] != '' && $item['relto'] != '') {
                        $xp = explode(' ', $item['relto']);
                        $sql = 'INSERT INTO pax_relations (person_id, has_relation, to_person_id) VALUES (:p1, :r, :p2)';
                        Yii::$app->db->createCommand($sql, [
                            ':p1'=>trim($xp[0], '#'),
                            ':p2'=>$theContact['id'],
                            ':r'=>$item['rel'],
                        ])->execute();
                    }
                }
            }

            // Search
            $emailList = '';
            $emailOne = '';
            foreach ($data['email'] as $cnt=>$item) {
                $emailList .= ' '.$item['value'];
                if ($emailOne == '') {
                    $emailOne = $item['value'];
                }
            }
            $telList = '';
            $telOne = '';
            foreach ($data['tel'] as $cnt=>$item) {
                $telList .= ' '.$item['value'];
                if ($telOne == '') {
                    $telOne = $item['value'];
                }
            }

            $theContact->email = $emailOne;
            $theContact->phone = $telOne;

            foreach ($attribList as $name) {
                if (in_array($name, ['bday', 'bmonth', 'byear'])) {
                    $theContact->$name = (int)($theForm->$name ?? '');
                } else {
                    $theContact->$name = $theForm->$name ?? '';
                }
            }

            $theContact->updated_at = NOW;
            $theContact->updated_by = USER_ID;
            $theContact->save(false);

            foreach ($metaList as $name) {
                $item = [
                    'name'=>$name,
                    'value'=>$theForm->$name ?? '',
                    'note'=>'',
                    'format'=>'',
                ];
                if (is_array($item['value'])) {
                    $item['value'] = implode('|', $item['value']);
                }
                if ($item['value'] != '') {
                    $data['other'][$name] =  $item;
                }
            }

            // TODO save meta
            $cnt = 0;
            $max = count($theContact['metas']);

            foreach ($data as $type=>$group) {
                if ($type != 'connection') {
                    foreach ($group as $item) {
                        if (isset($theContact['metas'][$cnt])) {
                            Yii::$app->db->createCommand()->update('metas', [
                                'name'=>$item['name'],
                                'value'=>$item['value'],
                                'note'=>$item['note'],
                                'format'=>$item['format'],
                            ], [
                                'id'=>$theContact['metas'][$cnt]['id'],
                            ])->execute();
                        } else {
                            Yii::$app->db->createCommand()->insert('metas', [
                                'created_dt'=>NOW,
                                'created_by'=>USER_ID,
                                'updated_dt'=>NOW,
                                'updated_by'=>USER_ID,
                                'rtype'=>'user',
                                'rid'=>$theContact->id,
                                'name'=>$item['name'],
                                'value'=>$item['value'],
                                'note'=>$item['note'],
                                'format'=>$item['format'],
                            ])->execute();
                        }
                        $cnt ++;
                    }
                } // if not group connection
            }
            if ($cnt <= $max) {
                for ($i = $cnt; $i < $max; $i ++) {
                    // Delete item
                    Yii::$app->db->createCommand()->delete('metas', [
                        'id'=>$theContact['metas'][$i]['id'],
                    ])->execute();
                }
            }

            $searchText = \fURL::makeFriendly(trim($theContact->name.' '.$theContact->fname.' '.$theContact->lname.' '.$emailList.' '.$telList), ' ');
            $foundText = trim($theContact->name.' '.$emailOne.' '.$telOne);
            $sql = 'SELECT * FROM at_search WHERE rtype="user" AND rid=:id LIMIT 1';
            $search = Yii::$app->db->createCommand($sql, [':id'=>$theContact['id']])->queryOne();
            if (!$search) {
                Yii::$app->db->createCommand()->insert('at_search', [
                    'rtype'=>'user',
                    'rid'=>$theContact['id'],
                    'search'=>$searchText,
                    'found'=>$foundText,
                ])->execute();
            } else {
                Yii::$app->db->createCommand()->update('at_search', [
                    'search'=>$searchText,
                    'found'=>$foundText,
                ], ['id'=>$search['id']])->execute();
            }

            if ($case_id != 0) {
                return $this->redirect('/cases/r/'.$case_id);
            }

            if ($booking_id != 0) {
                return $this->redirect('/tours/pax/'.$theBooking['product']['id'].'?action=add&booking_id='.$booking_id);
            }

            return $this->redirect('/contacts/'.$theContact['id']);
        }

        return $this->render('contact_u2', [
            'profile'=>$profile,
            'theContact'=>$theContact,
            'theProfile'=>$theProfile,
            'theForm'=>$theForm,
            'theBooking'=>$theBooking,
            'theCase'=>$theCase,
            'data'=>$data,
        ]);
    }

    // View all uploaded files, including: user-uploaded and manager-uploaded files
    public function actionUploads($id)
    {
        $theContact = Contact::find()
            ->where(['id'=>$id])
            ->with([
                'profileDriver',
                'member',
                'profileTourguide',
                ])
            ->asArray()
            ->one();

        if (!$theContact) {
            throw new HttpException(404, 'Contact not found');
        }

        $theFiles = [];
        $folder1 = Yii::getAlias('@webroot').'/upload/users/'.substr($theContact['created_at'], 0, 7).'/'.$theContact['id'];
        $folder2 = Yii::getAlias('@webroot').'/upload/user-files/'.$theContact['id'];
        if (file_exists($folder1)) {
            $theFiles = \yii\helpers\FileHelper::findFiles($folder1);
        }
        if (file_exists($folder2)) {
            $theFiles = array_merge($theFiles, \yii\helpers\FileHelper::findFiles($folder2));
        }

        return $this->render('contact_uploads', [
            'theContact'=>$theContact,
            'theFiles'=>$theFiles,
        ]);
    }

}
