<?

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\helpers\FileHelper;

use common\models\VenuesUuForm;
use common\models\Venue;
use common\models\VenueTmp;
use common\models\Meta;
use common\models\Note;
use common\models\Dvc;
use common\models\Dvd;
use common\models\Dvo;
use common\models\Cpo;
use common\models\Cpt;
use common\models\Tour;
use common\models\Destination;
use common\models\Supplier;

/*
RESET SEARCH
delete from at_search where rtype="venue";
INSERT INTO at_search (rtype, rid, search, found) (SELECT "venue", id, name, CONCAT(name, ", ", (SELECT name_en FROM at_destinations d WHERE d.id=destination_id LIMIT 1)) FROM venues);
UPDATE at_search SET search=REPLACE(search," ", "") WHERE rtype="venue";
*/

class VenueController extends MyController
{
    // Ajax action to list dv
    public function actionU2($id)
    {
        if (!in_array(USER_ID, [1, 8, 9198, 30738, 28722])) {
            throw new HttpException(403, 'Access denied.');
        }
        $theVenue = Venue::find()
            ->where(['stype'=>'hotel', 'id'=>$id])
            ->asArray()
            ->one();
        if (!$theVenue) {
            throw new HttpException(404, 'Hotel not found');
        }

        $theTmp = VenueTmp::find()
            ->where(['venue_id'=>$theVenue['id']])
            ->one();

        if (!$theTmp) {
            $theTmp = new VenueTmp;
            $theTmp->updated_dt = NOW;
            $theTmp->updated_by = USER_ID;
            $theTmp->venue_id = $theVenue['id'];
            $theTmp->save(false);
        }

        if ($theTmp->load(Yii::$app->request->post()) && $theTmp->validate()) {
            if (isset($_POST['room_name']) && is_array($_POST['room_name'])) {
                $rooms = [];
                foreach ($_POST['room_name'] as $i=>$item) {
                    if ($_POST['room_name'][$i] != '' && $_POST['room_count'][$i] != '')
                    $rooms[] = [
                        'name'=>$_POST['room_name'][$i],
                        'count'=>$_POST['room_count'][$i],
                        'features'=>$_POST['room_features'][$i],
                        'dbl'=>$_POST['room_dbl'][$i],
                        'twn'=>$_POST['room_twn'][$i],
                        'tpl'=>$_POST['room_tpl'][$i],
                        'conn'=>$_POST['room_conn'][$i],
                        'eb'=>$_POST['room_eb'][$i],
                        'sell'=>$_POST['room_sell'][$i],
                        'price'=>$_POST['room_price'][$i],
                        'note'=>$_POST['room_note'][$i],
                    ];
                }
                $theTmp->rooms = serialize($rooms);
            }

            if (isset($_POST['inspection_date'], $_POST['inspection_by']) && is_array($_POST['inspection_date'])) {
                $inspections = [];
                foreach ($_POST['inspection_date'] as $i=>$item) {
                    if ($_POST['inspection_date'][$i] != '' && $_POST['inspection_by'][$i] != '')
                    $inspections[] = [
                        'date'=>$_POST['inspection_date'][$i],
                        'by'=>$_POST['inspection_by'][$i],
                    ];
                }
                $theTmp->inspections = serialize($inspections);
            }

            $theTmp->updated_dt = NOW;
            $theTmp->updated_by = USER_ID;
            $theTmp->save(false);
            return $this->redirect('/ref/hotels');
        }

        return $this->render('venue_u2', [
            'theVenue'=>$theVenue,
            'theTmp'=>$theTmp,
            ]);
    }

    public function actionVenue_map()
    {
        //SELECT id, name, latlng FROM venues WHERE stype="hotel" 
        $venues = Venue::find()
            ->select(['id', 'name', 'latlng'])
            ->where('stype="hotel"')->asArray()->all();
        return $this->renderPartial('map', [
            'venues' => $venues
        ]);
    }

    public function actionMap_sidebar()
    {
        if (Yii::$app->request->isAjax) {
            $venues = null;
            if (isset($_POST['markers']) && is_array($_POST['markers']) && count($_POST['markers']) > 0) {
                $venues = Venue::find()->where(['id' => $_POST['markers']])->asArray()->all();
            }
            $html = '';
            if ($venues == null) {
                return '';
            }
            foreach ($venues as $venue) {
                $html .= '<div class="result-item" data-id="'.$venue['id'].'"><a href="#"><h3>'.$venue['name'].'</h3>
                <div class="result-item-detail"><div class="image" style="background-image: url(/img/demo-3.jpg)">
                    <figure>Average price $30</figure></div><div class="description"><h5><i class="fa fa-map-marker"></i>63 Birch Street</h5><div class ="rating-passive"data-rating="4"> <span class="stars"></span> <span class="reviews">6</span> </div><div class="label label-default">Restaurant</div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis, arcu non hendrerit imperdiet, metus odio scelerisque elit, sed lacinia odio est ac felis. Nam ullamcorper hendrerit ullamcorper. Praesent quis arcu quis leo posuere ornare eu in purus. Nulla ornare rutrum condimentum. Praesent eu pulvinar velit. Quisque non finibus purus, eu auctor ipsum.</p></div> </div> </a> <div class="controls-more"> <ul> <li><a href="#" class="add-to-favorites">Add to favorites</a></li> <li><a href="#" class="add-to-watchlist">Add to watchlist</a></li> </ul> </div> </div>';
            }
            return $html;
        }
    }
    public function actionModal_item()
    {
        if (Yii::$app->request->isAjax) {
            $venue = Venue::find()->where(['id' => $_POST['id']])->asArray()->one();
            if (!$venue) {
                return 'The item not found';
            }
            return $this->renderAjax('_modal_item', ['venue' => $venue]);
        }
    }
    public function actionMap_data()
    {
        if (Yii::$app->request->isAjax) {
            $query = Venue::find()
            ->select(['id', 'name AS title', 'SUBSTRING_INDEX(latlng,",",1) AS latitude', 'SUBSTRING_INDEX(latlng,",",-1) AS longitude', 'image AS marker_image'])
            ->where('stype="hotel" AND latlng != ""');
            if (isset($_POST['keyword']))
            {
                if ($_POST['stype_venue'] != '') {
                    $query->andWhere('stype=:stype', [':stype' => $_POST['stype_venue']]);
                } else {
                    $query->andWhere('stype = "Hotel" OR stype = "home"');
                }
                $query->andWhere(['LIKE', 'name', $_POST['keyword']]);
            } else {
                $query->having('(latitude > :south_west_lat AND latitude < :north_east_lat) AND (longitude > :south_west_lng AND longitude < :north_east_lng)', [
                        ':south_west_lat' => $_POST['south_west_lat'],
                        ':north_east_lat' => $_POST['north_east_lat'],
                        ':south_west_lng' => $_POST['south_west_lng'],
                        ':north_east_lng' => $_POST['north_east_lng'],
                    ]);
            }
            $venues= $query->asArray()->all();
            return json_encode($venues);
        }
    }

    // Ajax action to list dv
    public function actionList_dv($venue_id = 0, $date_selected = '')
    {
        if (Yii::$app->request->isAjax) {
            if ($date_selected == '') {
                return json_encode(['err' => 'date null']);
            }
            $select_dt_arr = explode('/', $date_selected);
            $date_selected = $select_dt_arr[2].'/'.$select_dt_arr[1].'/'.$select_dt_arr[0];
            $dvc = Dvc::find()
            ->where(['venue_id'=>$venue_id])
            ->with([
                'dvd',
                'venue',
                'venue.dv'=>function($q){
                    return $q->where('status!="deleted"')->orderBy('grouping, sorder, name');
                },
                'venue.dv.cp',
                ])
            ->andWhere('DATE(valid_from_dt) <= "'.date('Y/m/d', strtotime($date_selected)).'" AND DATE(valid_until_dt) >= "'.date('Y/m/d', strtotime($date_selected)).'"')
            ->asArray()
            ->one();
            if ($dvc != null) {
                $conditions_change = [];
                foreach ($dvc['dvd'] as $dvd) {
                    if ($dvd['stype'] != 'date') { continue;}
                    $arr_dvds = explode(';', $dvd['def']);
                    foreach ($arr_dvds as $dvd_part) {
                        $arr_parts = explode('-', $dvd_part);
                        if (count($arr_parts) != 2) {continue;}
                        $first_arr = explode('/', $arr_parts[0]);
                        $second_arr = explode('/', $arr_parts[1]);
                        if (count($first_arr) != 3 || count($second_arr) != 3) {continue;}
                        $first_arr = $first_arr[2].'/'.$first_arr[1].'/'.$first_arr[0];
                        $second_arr = $second_arr[2].'/'.$second_arr[1].'/'.$second_arr[0];
                        $date_compair = date('Y/m/d', strtotime($date_selected));
                        if ($date_compair >= date('Y/m/d', strtotime($first_arr))
                            && $date_compair <= date('Y/m/d', strtotime($second_arr))) {
                            $dvc['dvd'] = $dvd;
                            foreach ($dvc['venue']['dv'] as $k_dv => $dv) {
                                $valid_cp = [];
                                $dvc['venue']['dv'][$k_dv]['name'] = str_replace(
                                        [
                                            '[', ']', '{', '}', '|',
                                        ], [
                                            '', '', '(<span class="text-light text-pink">', '</span>)', '/',
                                            ], $dv['name']);
                                foreach ($dv['cp'] as $k_cp => $cp) {
                                    if ($cp['period'] == $dvd['code'] && $dvc['id'] == $cp['dvc_id']) {
                                        $valid_cp[] = $cp;
                                    } else {
                                        if (count($dv['cp']) == 1 && $cp['period'] == '') {
                                            $dvc['venue']['dv'][$k_dv]['cp'][$k_cp] = $cp;
                                        }
                                    }
                                }
                                if (!empty($valid_cp)) {
                                    $dvc['venue']['dv'][$k_dv]['cp'] = $valid_cp;
                                }
                            }
                        }
                    }
                }
                echo json_encode(['dvc' => $dvc]);
            } else {
                return json_encode(['err' => 'dvc is null']);
            }
        }
    }

    public function actionIndex() { 
        $getType = Yii::$app->request->get('type', 'all');
        $getStatus = Yii::$app->request->get('status', 'all');
        $getDestinationId = Yii::$app->request->get('destination_id', 0);
        $getName = Yii::$app->request->get('name', '');

        $getClassification = Yii::$app->request->get('classification', '');
        $getArchitecture_style = Yii::$app->request->get('architecture_style', '');
        $getProperty_type = Yii::$app->request->get('property_type', '');
        $getStyle = Yii::$app->request->get('style', '');
        $getFacilities = Yii::$app->request->get('facilities', '');
        $getRecommended = Yii::$app->request->get('recommended', '');
        $getPrice_range = Yii::$app->request->get('price_range', '');



        $allDestinations = Destination::find()->select('id, name_en')->orderBy('country_code, name_en')->asArray()->all();

        $query = Venue::find()->select(['*', 'over_view_options AS op_ov']);

        if ($getType != 'all') {
            $query->andWhere(['stype'=>$getType]);
        }
        if ($getStatus != 'all') {
            $query->andWhere(['status'=>$getStatus]);
        }
        if ($getDestinationId != 0) {
            $query->andWhere(['destination_id'=>$getDestinationId]);
        }
        if ($getName != '') {
            $query->andWhere(['like', 'name', $getName]);
        }

        if (
            $getClassification != ''
            ||$getArchitecture_style != ''
            || $getProperty_type != ''
            || $getStyle != ''
            || $getFacilities != ''
            || $getRecommended != ''
            || $getPrice_range != ''
        ) {
            $query->andWhere('over_view_options != ""');
            //SELECT * FROM `venues` WHERE over_view_options REGEXP '.*"property_type";s:[0-9]+:"1".*'
            if ($getClassification != '') {
                $query->andWhere('over_view_options  REGEXP \'.*"classification";s:[0-9]+:"'.$getClassification.'".*\'');
            }
            if ($getArchitecture_style != '') {
                $query->andWhere('over_view_options  REGEXP \'.*"architecture_style";s:[0-9]+:"'.$getArchitecture_style.'".*\'');
            }
            if ($getProperty_type != '') {
                $query->andWhere('over_view_options  REGEXP \'.*"property_type";s:[0-9]+:"'.$getProperty_type.'".*\'');
            }
            if ($getStyle != '') {
                //SELECT * FROM `venues` WHERE over_view_options REGEXP '.*"facilities";a:[0-9]+:{.*i:[0-9]+;s:[0-9]+:"30";.*}.*'
                $pat = '.*"style";a:[0-9]+:{';
                foreach ($getStyle as $str) {
                    $pat .= '.*i:[0-9]+;s:[0-9]+:"'. $str .'";';
                }
                $pat .= '.*}.*';
                $query->andWhere('over_view_options  REGEXP \''.$pat.'\'');
            }
            if ($getFacilities != '') {
                $pat = '.*"facilities";a:[0-9]+:{';
                foreach ($getFacilities as $str) {
                    $pat .= '.*i:[0-9]+;s:[0-9]+:"'. $str .'";';
                }
                $pat .= '.*}.*';
                $query->andWhere('over_view_options  REGEXP \''.$pat.'\'');
            }
            if ($getRecommended != '') {
                $pat = '.*"recommended";a:[0-9]+:{';
                foreach ($getRecommended as $str) {
                    $pat .= '.*i:[0-9]+;s:[0-9]+:"'. $str .'";';
                }
                $pat .= '.*}.*';
                $query->andWhere('over_view_options  REGEXP \''.$pat.'\'');
            }
            if ($getPrice_range != '') {
                $price_min = 0;
                $price_max = 0;
                $pr_arr = explode('-', $getPrice_range);
                if (count($pr_arr) == 1) {
                    $price_min = $pr_arr[0] - 5;
                    $price_max = $pr_arr[0] + 5;
                }
                if (count($pr_arr) == 2) {
                    $price_min = $pr_arr[0];
                    $price_max = $pr_arr[1];
                }
                $ids = Yii::$app->db
                ->createCommand("SELECT id,
                    SUBSTRING_INDEX(SUBSTRING_INDEX(over_view_options, '-', 1), '|', -1) AS f_num,
                    SUBSTRING_INDEX(SUBSTRING_INDEX(over_view_options, '-', -1), '|', 1) AS t_num
                    FROM `venues` where over_view_options != ''
                    HAVING !(:price_max < f_num) AND !(:price_min > t_num)", [':price_min'=>$price_min, ':price_max' => $price_max])->queryColumn();
                    $query->andWhere(['id' => $ids]);
            }
        }

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $theVenues = $query
            ->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'info'])
            ->orderBy('stype, name')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->with(['destination'])
            ->asArray()
            ->all();
        return $this->render('venue_index', [
            'pages'=>$pages,
            'theVenues'=>$theVenues,
            'allDestinations'=>$allDestinations,
            'getType'=>$getType,
            'getStatus'=>$getStatus,
            'getDestinationId'=>$getDestinationId,
            'getName'=>$getName,


            'getClassification' => $getClassification,
            'getArchitecture_style' => $getArchitecture_style,
            'getProperty_type' => $getProperty_type,
            'getStyle' => $getStyle,
            'getFacilities' => $getFacilities,
            'getRecommended' => $getRecommended,
            'getPrice_range' => $getPrice_range,
        ]);
    }

    public function actionC()
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Chức năng đang tạm bị hạn chế. Liên hệ Mr Huân để biết thêm chi tiết.');
        }
        $theVenue = new Venue;
        $theVenue->scenario = 'venues_c';

        $destinationList = Destination::find()
            ->select(['id', 'name_en', 'country_code'])
            ->asArray()
            ->all();

        if ($theVenue->load(Yii::$app->request->post())) {
            $theVenue['created_at'] = NOW;
            $theVenue['created_by'] = Yii::$app->user->id;
            $theVenue['updated_at'] = NOW;
            $theVenue['updated_by'] = Yii::$app->user->id;
            $theVenue['status'] = 'draft';
            if ($theVenue->save()) {
                Yii::$app->db
                    ->createCommand()->insert('at_search', [
                        'rtype'=>'venue',
                        'rid'=>$theVenue->id,
                        'search'=>str_replace('-', '', \fURL::makeFriendly($theVenue->name, '-')),
                        'found'=>$theVenue->name.', '.$theVenue->destination->name_en,
                    ])
                    ->execute();
                return $this->redirect('@web/venues/u/'.$theVenue['id']);
            }
        }

        return $this->render('venue_c', [
            'theVenue'=>$theVenue,
            'destinationList'=>$destinationList,
        ]);
    }

    public function actionInfo($id = 0)
    {
        return $this->redirect('@web/venues/r/'.$id);
    }

    public function actionR($id = 0)
    {

        // Han che mot so view vi khong dung chuan
        $restrictedList = [2202];

        if (in_array($id, $restrictedList)) {
            //throw new HttpException(403, 'Chức năng tạm bị hạn chế.');
        }

        $theVenue = Venue::find()
            ->where(['id'=>$id])
            ->with([
                'dvc'=>function($q){
                    return $q->orderBy('valid_from_dt');
                },
                'dvc.dvd',
                'dv'=>function($q){
                    return $q->where('status!="deleted"')->orderBy('grouping, sorder, name');
                },
                'dv.cp',
                'dvo'=>function($q){
                    return $q->orderBy('grouping, name');
                },
                'dvo.cpo',
                'destination',
                ])
            ->asArray()
            ->one();


        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        // Update SGL,DBL,TWN rooms
        if ($theVenue['stype'] == 'hotel') {
            $redir = false;
            foreach ($theVenue['dv'] as $dv) {
                if (strtoupper(substr($dv['name'], -4)) == ' SGL') {
                    $redir = true;
                    $roomtype = trim(substr($dv['name'], 0, strlen($dv['name']) - 4));
                    $name = '';
                    $trail = ' {*SGL';
                    $dbl = false;
                    $twn = false;
                    foreach ($theVenue['dv'] as $dv2) {
                        if ($dv2['name'] == $roomtype.' DBL' || $dv2['name'] == $roomtype.' dbl') {
                            $dbl = true;
                            Yii::$app->db
                                ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv2['id']])
                                ->execute();
                        }
                        if ($dv2['name'] == $roomtype.' TWN') {
                            $twn = true;
                            Yii::$app->db
                                ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv2['id']])
                                ->execute();
                        }
                    }
                    if ($dbl) {
                        $trail .= '|DBL';
                    }
                    if ($twn) {
                        $trail .= '|TWN';
                    }
                    $trail .= '}';
                    if ($dbl || $twn) {
                        $name = $roomtype.$trail;
                        Yii::$app->db
                            ->createCommand('UPDATE dv SET name=:n WHERE id=:id LIMIT 1', [':n'=>$name, 'id'=>$dv['id']])
                            ->execute();
                    }
                }
            }
            if ($redir) {
                //return $this->redirect(DIR.URI);
            }
        }
/*
            if (isset($_POST['html']) && $_POST['html'] != '') {
                $html = trim($_POST['html']);
                $pos = strpos($html, 'slideshow_photos');
                if ($pos !== false) {
                    $pos2 = strpos($html, '</script>');
                    if (false !== $pos2) {
                        $code = substr($html, $pos, $pos2 - $pos);
                        $code = str_replace([chr(10), chr(13)], ['', ''], $code);
                        $code = str_replace(['slideshow_photos = [', ',', '];'], ['<img src=', '><img src=', '>'], $code);
                        $code = str_replace(['= '], ['='], $code);
                        Yii::$app->db->createCommand()->update('venues', ['images_booking'=>$code], ['id'=>$theVenue['id']])->execute();
                        return $this->redirect(DIR.URI);
                    } else {
                        die('POS2 NF');
                    }
                } else {
                    die('POS1 NF');
                }
            }

            // Trip Advisor feedback
            $fbTripadvisor = $theVenue['fb_tripadvisor'];
            if ((Yii::$app->user->id == 111 && isset($_GET['get']) && $_GET['get'] == 'tripadvisor') || $theVenue['fb_tripadvisor'] == '') {
                if ($theVenue['link_tripadvisor'] != '') {
                    $html = ''; //file_get_contents($theVenue['link_tripadvisor']);
                    $pos = strpos($html, '<div id="REVIEWS" class="deckB review_collection">');
                    if (false !== $pos) {
                        $pos2 = strpos($html, '<div id="HSCS">');
                        if (false !== $pos2) {
                            $code = substr($html, $pos, $pos2 - $pos - 1);
                            $code = str_replace(['/ShowUserReviews', 'Review collected in partnership with this hotel '], ['http://www.tripadvisor.com/ShowUserReviews', ''], $code);
                            Yii::$app->db->createCommand()->update('venues', ['fb_tripadvisor'=>$code], ['id'=>$theVenue['id']])->execute();
                            $fbTripadvisor = $code;
                        } else {
                            $fbTripadvisor = 'POS2 NF!';
                        }
                    } else {
                        $fbTripadvisor = 'POS1 NF!';
                    }
                }
            }
*/
        $venueMetas = Meta::find()
            ->where(['rtype'=>'venue', 'rid'=>$id])
            ->asArray()->all();

        $venueNotes = Note::find()
            ->where(['rtype'=>'venue', 'rid'=>$id])
            ->with(['updatedBy', 'files'])
            ->orderBy('uo DESC')
            ->asArray()->all();

        $sql = 'SELECT f.*, t.day_from, t.op_code, t.op_name FROM at_tour_feedbacks f, at_ct t WHERE f.tour_id=t.id AND f.rtype="venue" AND f.rid=:id ORDER BY t.day_from DESC';
        $venueFeedbacks = Yii::$app->db->createCommand($sql, [':id'=>$theVenue['id']])->queryAll();

        $venueTours = Yii::$app->db
            ->createCommand('SELECT t.id, t.code, t.name, c.price, c.unitc, c.dvtour_name, c.qty, c.unit, c.dvtour_day, c.dvtour_id FROM at_tours t, cpt c WHERE c.tour_id=t.id AND c.venue_id=:id GROUP BY t.id ORDER BY c.dvtour_day DESC LIMIT 100', [':id'=>$id])
            ->queryAll();

        $venueSupplier = null;
        if ($theVenue['supplier_id'] != 0) {
            $venueSupplier = Supplier::find()
                ->where(['id'=>$theVenue['supplier_id']])
                ->with([
                    'venues'=>function($q) {
                        return $q->select(['id', 'name', 'image', 'supplier_id']);
                    }
                    ])
                ->asArray()
                ->one();
        }
            $html = '<table>
            <tbody>
            <tr>
            <td>&nbsp;</td>
            <td>[1.Room Rates/h]</td>
            <td>Room Type</td>
            <td>View</td>
            <td>Number of room</td>
            <td>FIT Rates</td>
            <td>GIT Rates</td>
            <td>Extra bed</td>
            <td>Room size</td>
            <td>Currency</td>
            <td colspan="2">Maximum Occupants</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[1.Room Rates/h]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>(from 10 rooms)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Adult</td>
            <td>Children (max 12 years old)</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>6,750,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>7,190,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>10,250,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>11,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,100,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 16,950,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,850,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,150,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,590,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,650,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,150,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,500,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,350,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,250,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 47,150,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,750,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8,190,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,250,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 15,100,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,950,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,850,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 47,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,970,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,470,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,170,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6,970,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,470,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 13,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,170,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 16,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 45,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,370,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,870,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,870,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,870,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 13,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,370,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,370,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,470,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,170,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[2.Promotion/h]</td>
            <td>Name</td>
            <td>Definition</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Benefit</td>
            <td>Condition</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[2.Promotion/h]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>Stay 2 nights</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 10%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>Stay 3 nights</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 15%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>Stay 4 nights</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 25%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Early Bird</td>
            <td colspan="3">book and prepayment in advance of 60 days</td>
            <td>reduce 20%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-30/09/2018}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>4 consecutive nights stays</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 25%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[3.Child Policy/h]</td>
            <td>Room categories</td>
            <td>Age</td>
            <td>0- 5</td>
            <td>0- 5</td>
            <td>6-11</td>
            <td>6-11</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[3.Child Policy/h]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Sharing</td>
            <td>Extra</td>
            <td>Sharing</td>
            <td>Extra</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[4.Reservation/h]</td>
            <td>Email</td>
            <td>Phone</td>
            <td>&nbsp;</td>
            <td>Fax</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[4.Reservation]</td>
            <td>reservation.saiph@hyatt.com</td>
            <td colspan="2">84&nbsp; 28 3824 1234</td>
            <td>84 28 3823 7569</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[5.Payment/h]</td>
            <td>Account name</td>
            <td>Bank name</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Account number</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[5.Payment]</td>
            <td>Park Hyatt Saigon</td>
            <td colspan="3">Vietcombank - Ho Chi Minh Branch</td>
            <td>007 100 0902133</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[6.Cancellation Policy/h]</td>
            <td>Condition</td>
            <td>Penalty</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{15/05/2018-18/12/2018;04/01/2019-18/12/2019}</td>
            <td>[6.Cancellation Policy]</td>
            <td>within 71 hours</td>
            <td>1 night charge</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[6.Cancellation Policy]</td>
            <td>within 30 days</td>
            <td>100%</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            </tbody>
            </table>';
        $table_price = '';
        $price_data = $this->ExplodeHtml($html);

        // HUAN: test new view
        return $this->render('venue_r'.(USER_ID == 1 ? 'x' : ''), [
            'theVenue'=>$theVenue,
            'venueMetas'=>$venueMetas,
            'venueNotes'=>$venueNotes,
            'venueFeedbacks'=>$venueFeedbacks,
            'venueTours'=>$venueTours,
            'venueSupplier'=>$venueSupplier,
            'fbTripadvisor'=>'',//$fbTripadvisor,
            'table_price' => $price_data,
        ]);
    }
    function ExplodeHtml($html = '', $date = NOW)
    {
        $arrData = [];
        $html = explode('</tr>',trim(str_replace(['<table>', '<tbody>', '</table>', '</tbody>', '&nbsp;'], ['', '', '', '', ''], $html)));
        foreach ($html as $tr) {
            if (trim($tr) == '') continue;
            $getName = preg_match('/\[.+\]/', $tr, $match);
            // $tr = preg_replace('/(\<td(.*)\>)\[.+\](\<\/td\>)/', '', trim($tr));
            if ($getName > 0 ) {
                $arr = explode('/', str_replace(['[', ']'], ['', ''], $match[0]));
                $arrData[$arr[0]][] = $tr;
            }
        }
        $result_arr = [];
        foreach ($arrData as $t_name => $table) {
            foreach ($table as $row) {
                $getDate = preg_match('/\{.+\}/', $row, $match);
                if ($getDate < 1) {
                    $result_arr[$t_name][] = $row;
                } else {
                    $r_date = explode(';', str_replace(['{', '}'], ['', ''], $match[0]));
                    foreach ($r_date as $dt) {
                        $r_dt = explode('-', $dt);
                        if (strtotime($date) >= strtotime($this->convertDate($r_dt[0]))
                        && strtotime($date) <= strtotime($this->convertDate($r_dt[1]))) {
                                $row = preg_replace('/\<td>(.*\d+)\<\/td>/', '<td class="text-right">$1</td>', $row);
                                $row = preg_replace('/\<td(.*)\>(\{.+\})(\<\/td\>)/', '<td $1 class="text-center"><span class="date_hover text-info" data-dt="$2" title="$2"><i class="fa fa-exclamation-circle"></i></span>', $row);//str_replace(['{', '}'], ['', ''], $row);
                                $result_arr[$t_name][] = str_replace(['{', '}'], ['', ''], $row);
                        }
                    }
                }
            }
        }
        return $result_arr;
    }
    //convert date  from d/m/Y => Y/m/d
    function convertDate($date = '')
    {
        $arr_dt = explode('/', trim($date));
        $dt = '';
        if (count($arr_dt) == 3 && strlen($arr_dt[2]) == 4) {
            $dt = $arr_dt[2] . '-' . $arr_dt[1] . '-' . $arr_dt[0];
        } else {
            var_dump($arr_dt);die;
        }
        return $dt;
    }
    public function actionPrice_table($venue_id = 0, $date = NOW)
    {
        if (Yii::$app->request->isAjax) {
            $html = '<table>
            <tbody>
            <tr>
            <td>&nbsp;</td>
            <td>[1.Room Rates/h]</td>
            <td>Room Type</td>
            <td>View</td>
            <td>Number of room</td>
            <td>FIT Rates</td>
            <td>GIT Rates</td>
            <td>Extra bed</td>
            <td>Room size</td>
            <td>Currency</td>
            <td colspan="2">Maximum Occupants</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[1.Room Rates/h]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>(from 10 rooms)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Adult</td>
            <td>Children (max 12 years old)</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>6,750,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>7,190,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>10,250,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>11,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,100,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 16,950,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,850,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{22/04/2018-30/09/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,150,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,590,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,650,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,150,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,500,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,350,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,250,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{1/10/2018-18/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 47,150,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,750,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8,190,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,250,000</td>
            <td>on request</td>
            <td>none</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 15,100,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,950,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,850,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 47,750,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,970,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,470,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,170,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6,970,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,470,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,470,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 13,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,170,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 16,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 45,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>City view</td>
            <td>125 DBL+32 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,370,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Room</td>
            <td>Garden view</td>
            <td>51 DBL+4 TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,870,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe</td>
            <td>Pool/ Garden</td>
            <td>9 DBL+1TWIN</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,870,000</td>
            <td>on request</td>
            <td>&nbsp;none&nbsp;</td>
            <td>34</td>
            <td>VND</td>
            <td>2</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,870,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 13,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,370,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,370,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Suite</td>
            <td>City view</td>
            <td>9 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,470,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>70</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Lam Son Suite</td>
            <td>City view</td>
            <td>5 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,570,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>78</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Deluxe Suite</td>
            <td>Pool/ Garden</td>
            <td>2 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,170,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>81</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Park Executive Suite</td>
            <td>City view</td>
            <td>6 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>110</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[1.Room Rates]</td>
            <td>Presidential Suite</td>
            <td>City view</td>
            <td>&nbsp;1 DBL</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,970,000</td>
            <td>on request</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>190</td>
            <td>VND</td>
            <td>3</td>
            <td>1</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[2.Promotion/h]</td>
            <td>Name</td>
            <td>Definition</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Benefit</td>
            <td>Condition</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[2.Promotion/h]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>Stay 2 nights</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 10%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>Stay 3 nights</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 15%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>Stay 4 nights</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 25%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2019-30/09/2019}</td>
            <td>[2.Promotion]</td>
            <td>Early Bird</td>
            <td colspan="3">book and prepayment in advance of 60 days</td>
            <td>reduce 20%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-30/09/2018}</td>
            <td>[2.Promotion]</td>
            <td>Long-stay</td>
            <td>4 consecutive nights stays</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>reduce 25%</td>
            <td>not combinable</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[3.Child Policy/h]</td>
            <td>Room categories</td>
            <td>Age</td>
            <td>0- 5</td>
            <td>0- 5</td>
            <td>6-11</td>
            <td>6-11</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[3.Child Policy/h]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Sharing</td>
            <td>Extra</td>
            <td>Sharing</td>
            <td>Extra</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{04/01/2019-18/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 1,290,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;&nbsp; 2,080,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/04/2018-19/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Park Room &amp; Park Deluxe Room</td>
            <td>2nd child</td>
            <td>none</td>
            <td>none</td>
            <td>none</td>
            <td>&nbsp;none&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>1st child</td>
            <td>FOC</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{19/12/2018-31/12/2018}</td>
            <td>[3.Child Policy]</td>
            <td>Other room categories</td>
            <td>2nd child</td>
            <td>none</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
            <td>&nbsp;&nbsp; 1,937,000</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[4.Reservation/h]</td>
            <td>Email</td>
            <td>Phone</td>
            <td>&nbsp;</td>
            <td>Fax</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[4.Reservation]</td>
            <td>reservation.saiph@hyatt.com</td>
            <td colspan="2">84&nbsp; 28 3824 1234</td>
            <td>84 28 3823 7569</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[5.Payment/h]</td>
            <td>Account name</td>
            <td>Bank name</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Account number</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[5.Payment]</td>
            <td>Park Hyatt Saigon</td>
            <td colspan="3">Vietcombank - Ho Chi Minh Branch</td>
            <td>007 100 0902133</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>[6.Cancellation Policy/h]</td>
            <td>Condition</td>
            <td>Penalty</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{15/05/2018-18/12/2018;04/01/2019-18/12/2019}</td>
            <td>[6.Cancellation Policy]</td>
            <td>within 71 hours</td>
            <td>1 night charge</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
            <td>[6.Cancellation Policy]</td>
            <td>within 30 days</td>
            <td>100%</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            </tbody>
            </table>';
            $table_price = '';
            $price_data = $this->ExplodeHtml($html, $this->convertDate($date));
            return json_encode($price_data);
        }
    }

    public function actionU($id = 0) {
        if (!in_array(MY_ID, [1, 8, 9198, 28722])) {
            // throw new HttpException(403, 'Access denied');
        }

        $theVenue = Venue::findOne($id);
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        $theVenue->scenario = 'venues_u';

        if ($theVenue->load(Yii::$app->request->post())) {
            $theVenue->updated_at = NOW;
            $theVenue->updated_by = MY_ID;
            if ($theVenue->save()) {
                Yii::$app->db
                    ->createCommand()->update('at_search', [
                        'search'=>str_replace('-', '', \fURL::makeFriendly($theVenue->name, '-')),
                        'found'=>$theVenue->name.', '.$theVenue->destination->name_en,
                    ], [
                        'rtype'=>'venue',
                        'rid'=>$theVenue->id,
                    ])
                    ->execute();

                return $this->redirect('@web/venues/r/'.$theVenue['id']);
            }
        }

        $destinationList = Destination::find()
            ->select(['id', 'name_en', 'country_code'])
            ->asArray()
            ->all();

        $supplierList = Supplier::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $folderPath = '/upload/venues/'.substr($theVenue['created_at'], 0, 7).'/'.$theVenue['id'];
        FileHelper::createDirectory(Yii::getAlias('@webroot').$folderPath);
        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$folderPath);
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$folderPath);
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', 'venues/'.substr($theVenue['created_at'], 0, 7).'/'.$theVenue['id']);
        Yii::$app->session->set('ckfinder_resource_name', 'files');

        return $this->render('venue_u', [
            'theVenue'=>$theVenue,
            'destinationList'=>$destinationList,
            'supplierList'=>$supplierList,
        ]);
    }

    public function actionUPromo($id = 0) {
        $theVenue = Venue::findOne($id);
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        $theVenue->scenario = 'venues_u-promo';

        if ($theVenue->load(Yii::$app->request->post())) {
            $theVenue->updated_at = NOW;
            $theVenue->updated_by = Yii::$app->user->id;
            if ($theVenue->save()) {
                return $this->redirect('@web/venues/r/'.$theVenue['id']);
            }
        }

        return $this->render('venue_u-promo', [
            'theVenue'=>$theVenue,
        ]);
    }

    public function actionUu($id = 0)
    {
        $theVenue = Venue::findOne($id);
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        if ($theVenue['stype'] != 'hotel') {
            throw new HttpException(404, 'Not a hotel');
        }

        $theVenue->scenario = 'venues_uu';

        $theForm = new VenuesUuForm;

        $data = unserialize($theVenue['hotel_meta']);
        if (!empty($data) && !Yii::$app->request->isPost) {
            foreach ($data as $k=>$v) {
                $theForm[$k] = $v;
            }
        }

        FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/venues/'.substr($theVenue['created_at'], 0, 7).'/'.$theVenue['id']);

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $theVenue->hotel_meta = serialize($theForm->getAttributes(null, ['image']));;
            //die($theVenue->hotel_meta);
            $theVenue->save();
            return $this->redirect('@web/venues/info/'.$id);
        }

        return $this->render('venue_uu', [
            'theVenue'=>$theVenue,
            'theForm'=>$theForm,
        ]);     
    }

    public function actionD($id = 0) {
        $theVenue = Venue::findOne($id);
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        throw new HttpException(403, 'Under development.');
        

        return $this->render('venue_d', [
            'theVenue'=>$theVenue,
        ]);
    }

}
