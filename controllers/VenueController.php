<?

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\web\Response;

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
    public function parseDateConditionString($str): array
    {
        $result = [];
        // j-j;j-j/n;j-j/n/Y;
        // j/n-j/n;j/n-j/n/Y;
        // j/n/Y-j/n/Y;
        $str = str_replace(['.', ',', ' '], ['/', ';', ';'], $str);
        $ranges = explode(';', $str);
        foreach ($ranges as $range) {
            if ($range == '') {
                continue;
            }
            if (strpos($range, '-') === false) {
                // Single day
                $minStr = $range;
                $maxStr = $range;
            } else {
                // Range
                $minmax = explode('-', $range);
                $minStr = $minmax[0];
                $maxStr = $minmax[1];
            }

            $maxYmd = explode('/', $maxStr);
            $count = count($maxYmd);
            if ($count == 1) {
                // j
                continue;
            } elseif ($count == 2) {
                // j/n
                continue;
            } else {
                // j/n/Y
                $maxDate = implode('-', [$maxYmd[2], $maxYmd[1], $maxYmd[0]]);
            }

            $minYmd = explode('/', $minStr);
            $count = count($minYmd);
            if ($count == 1) {
                // j
                $minDate = implode('-', [$maxYmd[2], $maxYmd[1], $minYmd[0]]);
            } elseif ($count == 2) {
                // j/n
                $minDate = implode('-', [$maxYmd[2], $minYmd[1], $minYmd[0]]);
            } else {
                // j/n/Y
                $minDate = implode('-', [$minYmd[2], $minYmd[1], $minYmd[0]]);
            }
            $result[] = [$minDate, $maxDate];
        }
        // Sort
        // Return
        return $result;
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

    public function actionIndex($dest = '', $type = '', $class = '', $style = '', $price = '', $faci = '', $recc = '', $name = '', $stra = 'sr', $search = '') {
        $allDestinations = Destination::find()
            ->select('id, name_en')
            ->orderBy('country_code, name_en')
            ->asArray()
            ->all();

        $query = Venue::find()
            ->where('LENGTH(name)>3');

        if ($stra == '') {
            $query->andWhere(['stype'=>'hotel']);
        } elseif ($stra == 's') {
            $query
                ->andWhere(['stype'=>'hotel'])
                ->andWhere('LOCATE("sr_s", new_tags)!=0');
        } elseif ($stra == 'r') {
            $query
                ->andWhere(['stype'=>'hotel'])
                ->andWhere('LOCATE("sr_r", new_tags)!=0');
        } elseif ($stra == 'sr') {
            $query
                ->andWhere(['stype'=>'hotel'])
                ->andWhere('LOCATE("sr_s", new_tags)!=0 OR LOCATE("sr_r", new_tags)!=0');
        } elseif ($stra == 'h' || $stra == 'ha') {
            $query
                ->andWhere(['stype'=>'home']);
            if ($stra == 'ha') {
                $query
                    ->andWhere('LOCATE("c_amica", new_tags)!=0');
            }
        }

        if ($dest != '') {
            if (in_array($dest, ['vn', 'la', 'kh', 'mm'])) {
                $destIdList = Destination::find()
                    ->select(['id'])
                    ->where(['country_code'=>$dest])
                    ->asArray()
                    ->column();
                $query->andWhere(['destination_id'=>$destIdList]);
            } else {
                $query->andWhere(['destination_id'=>$dest]);
            }
        }

        if ($type != '') {
            $query->andWhere('LOCATE(:type, new_tags)!=0', [':type'=>$type]);
        }

        if ($class != '') {
            $query->andWhere('LOCATE(:class, new_tags)!=0', [':class'=>$class]);
        }

        if ($style != '') {
            $query->andWhere('LOCATE(:style, new_tags)!=0', [':style'=>$style]);
        }

        if ($price != '') {
            $minp = 0; $maxp = 0;
            $prices = explode('-', $price);
            $minp = (int)$prices[0];
            if (!isset($prices[1])) {
                // // Chi co 1 so thi lay +- 10 USD
                // $minp = max(0, $minp - 10);
                // $maxp = max(0, $minp + 20);
                $maxp = $minp;
            } else {
                if ((int)$prices[1] >= $minp) {
                    $maxp = (int)$prices[1];
                } else {
                    $maxp = $minp;
                }
            }
            if ($maxp != 0) {
                $query->andWhere('new_pricemin<=:maxp', [':maxp'=>$maxp])
                    ->andWhere('new_pricemax>=:minp', [':minp'=>$minp]);
            }
        }

        if ($faci != '') {
            $query->andWhere('LOCATE(:faci, new_tags)!=0', [':faci'=>$faci]);
        }

        if ($recc != '') {
            $query->andWhere('LOCATE(:recc, new_tags)!=0', [':recc'=>$recc]);
        }

        if ($name != '') {
            $query->andWhere(['or', ['like', 'name', $name], ['like', 'about', $name]]);
        }

        $searchParams = explode(' ', $search);
        if (!empty($searchParams)) {
            foreach ($searchParams as $param) {
                $orParams = explode('|', $param);
                if (count($orParams) > 1) {
                    $query->andWhere(['or', 'LOCATE("'.$orParams[0].'", search)!=0', 'LOCATE("'.$orParams[1].'", search)!=0']);
                } else {
                    $query->andWhere('LOCATE("'.$param.'", search)!=0');
                }
            }
        }
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $theVenues = $query
            ->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'image', 'images', 'new_tags', 'new_pricemin', 'new_pricemax'])
            ->orderBy('stype, name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with([
                'destination',
                'metas',
            ])
            ->asArray()
            ->all();

        return $this->render('venue_index', [
            'pagination'=>$pagination,
            'theVenues'=>$theVenues,
            'allDestinations'=>$allDestinations,
            'dest'=>$dest,
            'type'=>$type,
            'class'=>$class,
            'style'=>$style,
            'price'=>$price,
            'faci'=>$faci,
            'recc'=>$recc,
            'name'=>$name,
            'stra'=>$stra,
            'search'=>$search
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

        $the_venue_temp = VenueTmp::find()
            ->where(['venue_id'=>$id])
            ->asArray()
            ->one();

        $the_room = unserialize($the_venue_temp['rooms']);

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
                        Yii::$app->db->createCommand()->update('venues', ['images'=>$code], ['id'=>$theVenue['id']])->execute();
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

        $table_price = $this->explodeHtml($theVenue['new_pricetable']);


        // HUAN: test new view
        return $this->render('venue_r', [
            'theVenue'=>$theVenue,
            'venueMetas'=>$venueMetas,
            'venueNotes'=>$venueNotes,
            'venueFeedbacks'=>$venueFeedbacks,
            'venueTours'=>$venueTours,
            'venueSupplier'=>$venueSupplier,
            'fbTripadvisor'=>'',//$fbTripadvisor,
            'the_venue_temp'=>$the_venue_temp,
            'the_room'=>$the_room,
            'table_price'=>$table_price,
        ]);
    }

    // public function actionR_old($id = 0)
    // {

    //     // Han che mot so view vi khong dung chuan
    //     $restrictedList = [2202];

    //     if (in_array($id, $restrictedList)) {
    //         //throw new HttpException(403, 'Chức năng tạm bị hạn chế.');
    //     }

    //     $theVenue = Venue::find()
    //         ->where(['id'=>$id])
    //         ->with([
    //             'dvc'=>function($q){
    //                 return $q->orderBy('valid_from_dt');
    //             },
    //             'dvc.dvd',
    //             'dv'=>function($q){
    //                 return $q->where('status!="deleted"')->orderBy('grouping, sorder, name');
    //             },
    //             'dv.cp',
    //             'dvo'=>function($q){
    //                 return $q->orderBy('grouping, name');
    //             },
    //             'dvo.cpo',
    //             'destination',
    //             ])
    //         ->asArray()
    //         ->one();


    //     if (!$theVenue) {
    //         throw new HttpException(404, 'Venue not found');
    //     }

    //     // Update SGL,DBL,TWN rooms
    //     if ($theVenue['stype'] == 'hotel') {
    //         $redir = false;
    //         foreach ($theVenue['dv'] as $dv) {
    //             if (strtoupper(substr($dv['name'], -4)) == ' SGL') {
    //                 $redir = true;
    //                 $roomtype = trim(substr($dv['name'], 0, strlen($dv['name']) - 4));
    //                 $name = '';
    //                 $trail = ' {*SGL';
    //                 $dbl = false;
    //                 $twn = false;
    //                 foreach ($theVenue['dv'] as $dv2) {
    //                     if ($dv2['name'] == $roomtype.' DBL' || $dv2['name'] == $roomtype.' dbl') {
    //                         $dbl = true;
    //                         Yii::$app->db
    //                             ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv2['id']])
    //                             ->execute();
    //                     }
    //                     if ($dv2['name'] == $roomtype.' TWN') {
    //                         $twn = true;
    //                         Yii::$app->db
    //                             ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv2['id']])
    //                             ->execute();
    //                     }
    //                 }
    //                 if ($dbl) {
    //                     $trail .= '|DBL';
    //                 }
    //                 if ($twn) {
    //                     $trail .= '|TWN';
    //                 }
    //                 $trail .= '}';
    //                 if ($dbl || $twn) {
    //                     $name = $roomtype.$trail;
    //                     Yii::$app->db
    //                         ->createCommand('UPDATE dv SET name=:n WHERE id=:id LIMIT 1', [':n'=>$name, 'id'=>$dv['id']])
    //                         ->execute();
    //                 }
    //             }
    //         }
    //         if ($redir) {
    //             //return $this->redirect(DIR.URI);
    //         }
    //     }
    //     /*
    //         if (isset($_POST['html']) && $_POST['html'] != '') {
    //             $html = trim($_POST['html']);
    //             $pos = strpos($html, 'slideshow_photos');
    //             if ($pos !== false) {
    //                 $pos2 = strpos($html, '</script>');
    //                 if (false !== $pos2) {
    //                     $code = substr($html, $pos, $pos2 - $pos);
    //                     $code = str_replace([chr(10), chr(13)], ['', ''], $code);
    //                     $code = str_replace(['slideshow_photos = [', ',', '];'], ['<img src=', '><img src=', '>'], $code);
    //                     $code = str_replace(['= '], ['='], $code);
    //                     Yii::$app->db->createCommand()->update('venues', ['images_booking'=>$code], ['id'=>$theVenue['id']])->execute();
    //                     return $this->redirect(DIR.URI);
    //                 } else {
    //                     die('POS2 NF');
    //                 }
    //             } else {
    //                 die('POS1 NF');
    //             }
    //         }

    //         // Trip Advisor feedback
    //         $fbTripadvisor = $theVenue['fb_tripadvisor'];
    //         if ((Yii::$app->user->id == 111 && isset($_GET['get']) && $_GET['get'] == 'tripadvisor') || $theVenue['fb_tripadvisor'] == '') {
    //             if ($theVenue['link_tripadvisor'] != '') {
    //                 $html = ''; //file_get_contents($theVenue['link_tripadvisor']);
    //                 $pos = strpos($html, '<div id="REVIEWS" class="deckB review_collection">');
    //                 if (false !== $pos) {
    //                     $pos2 = strpos($html, '<div id="HSCS">');
    //                     if (false !== $pos2) {
    //                         $code = substr($html, $pos, $pos2 - $pos - 1);
    //                         $code = str_replace(['/ShowUserReviews', 'Review collected in partnership with this hotel '], ['http://www.tripadvisor.com/ShowUserReviews', ''], $code);
    //                         Yii::$app->db->createCommand()->update('venues', ['fb_tripadvisor'=>$code], ['id'=>$theVenue['id']])->execute();
    //                         $fbTripadvisor = $code;
    //                     } else {
    //                         $fbTripadvisor = 'POS2 NF!';
    //                     }
    //                 } else {
    //                     $fbTripadvisor = 'POS1 NF!';
    //                 }
    //             }
    //         }
    //      */
    //     $venueMetas = Meta::find()
    //         ->where(['rtype'=>'venue', 'rid'=>$id])
    //         ->asArray()->all();

    //     $venueNotes = Note::find()
    //         ->where(['rtype'=>'venue', 'rid'=>$id])
    //         ->with(['updatedBy', 'files'])
    //         ->orderBy('uo DESC')
    //         ->asArray()->all();

    //     $sql = 'SELECT f.*, t.day_from, t.op_code, t.op_name FROM at_tour_feedbacks f, at_ct t WHERE f.tour_id=t.id AND f.rtype="venue" AND f.rid=:id ORDER BY t.day_from DESC';
    //     $venueFeedbacks = Yii::$app->db->createCommand($sql, [':id'=>$theVenue['id']])->queryAll();

    //     $venueTours = Yii::$app->db
    //         ->createCommand('SELECT t.id, t.code, t.name, c.price, c.unitc, c.dvtour_name, c.qty, c.unit, c.dvtour_day, c.dvtour_id FROM at_tours t, cpt c WHERE c.tour_id=t.id AND c.venue_id=:id GROUP BY t.id ORDER BY c.dvtour_day DESC LIMIT 100', [':id'=>$id])
    //         ->queryAll();

    //     $venueSupplier = null;
    //     if ($theVenue['supplier_id'] != 0) {
    //         $venueSupplier = Supplier::find()
    //             ->where(['id'=>$theVenue['supplier_id']])
    //             ->with([
    //                 'venues'=>function($q) {
    //                     return $q->select(['id', 'name', 'image', 'supplier_id']);
    //                 }
    //                 ])
    //             ->asArray()
    //             ->one();
    //     }
    //         $html = '<table>
    //         <tbody>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[1.Room Rates/h]</td>
    //         <td>Room Type</td>
    //         <td>View</td>
    //         <td>Number of room</td>
    //         <td>FIT Rates</td>
    //         <td>GIT Rates</td>
    //         <td>Extra bed</td>
    //         <td>Room size</td>
    //         <td>Currency</td>
    //         <td colspan="2">Maximum Occupants</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[1.Room Rates/h]</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>(from 10 rooms)</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>Adult</td>
    //         <td>Children (max 12 years old)</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>City view</td>
    //         <td>125 DBL+32 TWIN</td>
    //         <td>6,750,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>Garden view</td>
    //         <td>51 DBL+4 TWIN</td>
    //         <td>7,190,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe</td>
    //         <td>Pool/ Garden</td>
    //         <td>9 DBL+1TWIN</td>
    //         <td>10,250,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>11,750,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,100,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 16,950,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,850,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{22/04/2018-30/09/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,750,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>City view</td>
    //         <td>125 DBL+32 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,150,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>Garden view</td>
    //         <td>51 DBL+4 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,590,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe</td>
    //         <td>Pool/ Garden</td>
    //         <td>9 DBL+1TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,650,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,150,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,500,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,350,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,250,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{1/10/2018-18/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 47,150,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>City view</td>
    //         <td>125 DBL+32 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,750,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>Garden view</td>
    //         <td>51 DBL+4 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8,190,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe</td>
    //         <td>Pool/ Garden</td>
    //         <td>9 DBL+1TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,250,000</td>
    //         <td>on request</td>
    //         <td>none</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,750,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 15,100,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,950,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,850,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 47,750,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>City view</td>
    //         <td>125 DBL+32 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>Garden view</td>
    //         <td>51 DBL+4 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;27/02/2019-21/04/2019;1/10/2019-30/11/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe</td>
    //         <td>Pool/ Garden</td>
    //         <td>9 DBL+1TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,570,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,170,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{27/02/2019-21/04/2019;1/10/2019-30/11/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>City view</td>
    //         <td>125 DBL+32 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>Garden view</td>
    //         <td>51 DBL+4 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe</td>
    //         <td>Pool/ Garden</td>
    //         <td>9 DBL+1TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 13,570,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,170,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 16,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{12/02/2019-26/02/2019;22/04/2019-30/09/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 45,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>City view</td>
    //         <td>125 DBL+32 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,370,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Room</td>
    //         <td>Garden view</td>
    //         <td>51 DBL+4 TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7,870,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe</td>
    //         <td>Pool/ Garden</td>
    //         <td>9 DBL+1TWIN</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10,870,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>34</td>
    //         <td>VND</td>
    //         <td>2</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 11,870,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 13,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,570,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,370,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{4/01/2019-11/02/2019;1/12/2019-18/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,370,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Suite</td>
    //         <td>City view</td>
    //         <td>9 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 12,470,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>70</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Lam Son Suite</td>
    //         <td>City view</td>
    //         <td>5 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 14,570,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>78</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Deluxe Suite</td>
    //         <td>Pool/ Garden</td>
    //         <td>2 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 18,170,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>81</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Park Executive Suite</td>
    //         <td>City view</td>
    //         <td>6 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 17,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>110</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[1.Room Rates]</td>
    //         <td>Presidential Suite</td>
    //         <td>City view</td>
    //         <td>&nbsp;1 DBL</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 46,970,000</td>
    //         <td>on request</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>190</td>
    //         <td>VND</td>
    //         <td>3</td>
    //         <td>1</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[2.Promotion/h]</td>
    //         <td>Name</td>
    //         <td>Definition</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>Benefit</td>
    //         <td>Condition</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[2.Promotion/h]</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2019-30/09/2019}</td>
    //         <td>[2.Promotion]</td>
    //         <td>Long-stay</td>
    //         <td>Stay 2 nights</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>reduce 10%</td>
    //         <td>not combinable</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2019-30/09/2019}</td>
    //         <td>[2.Promotion]</td>
    //         <td>Long-stay</td>
    //         <td>Stay 3 nights</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>reduce 15%</td>
    //         <td>not combinable</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2019-30/09/2019}</td>
    //         <td>[2.Promotion]</td>
    //         <td>Long-stay</td>
    //         <td>Stay 4 nights</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>reduce 25%</td>
    //         <td>not combinable</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2019-30/09/2019}</td>
    //         <td>[2.Promotion]</td>
    //         <td>Early Bird</td>
    //         <td colspan="3">book and prepayment in advance of 60 days</td>
    //         <td>reduce 20%</td>
    //         <td>not combinable</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2018-30/09/2018}</td>
    //         <td>[2.Promotion]</td>
    //         <td>Long-stay</td>
    //         <td>4 consecutive nights stays</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>reduce 25%</td>
    //         <td>not combinable</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[3.Child Policy/h]</td>
    //         <td>Room categories</td>
    //         <td>Age</td>
    //         <td>0- 5</td>
    //         <td>0- 5</td>
    //         <td>6-11</td>
    //         <td>6-11</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[3.Child Policy/h]</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>Sharing</td>
    //         <td>Extra</td>
    //         <td>Sharing</td>
    //         <td>Extra</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{04/01/2019-18/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{04/01/2019-18/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{04/01/2019-18/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,290,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{04/01/2019-18/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,290,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;&nbsp; 1,290,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2,080,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2,080,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;&nbsp; 2,080,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2018-19/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2018-19/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2018-19/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/04/2018-19/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 867,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 440,000</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Park Room &amp; Park Deluxe Room</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>none</td>
    //         <td>&nbsp;none&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>1st child</td>
    //         <td>FOC</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,937,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{19/12/2018-31/12/2018}</td>
    //         <td>[3.Child Policy]</td>
    //         <td>Other room categories</td>
    //         <td>2nd child</td>
    //         <td>none</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1,937,000</td>
    //         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 434,000</td>
    //         <td>&nbsp;&nbsp; 1,937,000</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[4.Reservation/h]</td>
    //         <td>Email</td>
    //         <td>Phone</td>
    //         <td>&nbsp;</td>
    //         <td>Fax</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[4.Reservation]</td>
    //         <td>reservation.saiph@hyatt.com</td>
    //         <td colspan="2">84&nbsp; 28 3824 1234</td>
    //         <td>84 28 3823 7569</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[5.Payment/h]</td>
    //         <td>Account name</td>
    //         <td>Bank name</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>Account number</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[5.Payment]</td>
    //         <td>Park Hyatt Saigon</td>
    //         <td colspan="3">Vietcombank - Ho Chi Minh Branch</td>
    //         <td>007 100 0902133</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>&nbsp;</td>
    //         <td>[6.Cancellation Policy/h]</td>
    //         <td>Condition</td>
    //         <td>Penalty</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{15/05/2018-18/12/2018;04/01/2019-18/12/2019}</td>
    //         <td>[6.Cancellation Policy]</td>
    //         <td>within 71 hours</td>
    //         <td>1 night charge</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         <tr>
    //         <td>{01/01/2019-03/01/2019;19/12/2019-31/12/2019}</td>
    //         <td>[6.Cancellation Policy]</td>
    //         <td>within 30 days</td>
    //         <td>100%</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         <td>&nbsp;</td>
    //         </tr>
    //         </tbody>
    //         </table>';
    //     $table_price = '';
    //     $price_data = $this->ExplodeHtml($html);

    //     // HUAN: test new view
    //     return $this->render('venue_r'.(USER_ID == 1 ? 'x' : ''), [
    //         'theVenue'=>$theVenue,
    //         'venueMetas'=>$venueMetas,
    //         'venueNotes'=>$venueNotes,
    //         'venueFeedbacks'=>$venueFeedbacks,
    //         'venueTours'=>$venueTours,
    //         'venueSupplier'=>$venueSupplier,
    //         'fbTripadvisor'=>'',//$fbTripadvisor,
    //         'table_price' => $price_data,
    //     ]);
    // }
    public function actionPriceTable2018($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
        $date = explode('-', $_POST['date'] ?? $_GET['date'] ?? '')[0];
        // - Doc cac hop dong co thoi han bao trum $date
        //   TODO: Chi doc hop dong chinh thuc (khong phai promo)
        // - Doc cac dinh nghia thoi han bao trum $date
        // - Doc cac gia co thoi han nhu tren, hoac (blank) (=all)

        if ($date == '') {
            $Ymd = date('Y-m-d');
        } else {
            if (strpos($date, '/') !== false) {
                $parts = explode('/', $date);
            } elseif (strpos($date, '-') !== false) {
                $parts = explode('-', $date);
            } elseif (strpos($date, '.') !== false) {
                $parts = explode('.', $date);
            } else {
                $parts = explode(' ', $date);
            }
            if (count($parts) >= 2) {
                $Ymd = ($parts[2] ?? date('Y')).'-'.$parts[1].'-'.$parts[0];
            } else {
                $Ymd = date('Y').'-'.date('m').'-'.$parts[0];
            }
        }

        if (!Yii::$app->request->isAjax) {
            echo 'DATE = ', (new \DateTime($Ymd))->format('j/n/Y');
        }

        $priceTable = Venue::find()
            ->where(['id'=>$id])
            ->select(['new_pricetable'])
            ->asArray()
            ->scalar();

        $data = [];
        $table = str_replace(['<table>', '</table>', '<tbody>', '</tbody>', '<tr>'], ['', '', '', '', ''], $priceTable);
        $lines = explode('</tr>', $table);
        foreach ($lines as $line) {
            $cells = explode('</td>', $line);
            $arr = [];
            foreach ($cells as $cell) {
                $arr[] = trim(str_replace(['&nbsp;'], [''], $cell));
                // $arr[] = $cell;
                // TODO read colspan, strip <td> tags
            }

            if ($arr[0] != '<td>') {
                $cell = str_replace(['<td>', '{', '}', ' '], ['', '', '', ''], $arr[0]);
                $ranges = $this->parseDateConditionString($cell);
                foreach ($ranges as $range) {
                    if (strtotime($range[0]) <= strtotime($Ymd) && strtotime($Ymd) <= strtotime($range[1])) {
                        if (!in_array(md5($cell), $data)) {
                            $data[] = md5($cell);
                        }
                        break;
                    }
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $data;
        } else {
            \fCore::expose($data);
        }
    }

    /**
     * Tach HTML bang gia
     */
    private function explodeHtml($html, $date = NOW)
    {
        $arrData = [];
        $html = explode('</tr>', trim(str_replace(['<table>', '<tbody>', '</table>', '</tbody>'], ['', '', '', ''], $html)));
        foreach ($html as $tr) {
            if (trim($tr) == '') continue;
            $getName = preg_match('/\[.+\]/', $tr, $match);
            $tr = preg_replace('/(\<td(.*)\>)\[.+\](\<\/td\>)/', '', trim($tr));
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
                            $result_arr[$t_name][] = str_replace(['{', '}'], ['', ''], $row);
                        } else {
                            // var_dump(strtotime($date));
                            // var_dump($this->convertDate($r_dt[0]));
                            // var_dump($this->convertDate($r_dt[1]));die;
                        }
                    }
                }
            }
        }
        return $result_arr;
    }
    // function ExplodeHtml($html = '', $date = NOW)
    // {
    //     $arrData = [];
    //     $html = explode('</tr>',trim(str_replace(['<table>', '<tbody>', '</table>', '</tbody>', '&nbsp;'], ['', '', '', '', ''], $html)));
    //     foreach ($html as $tr) {
    //         if (trim($tr) == '') continue;
    //         $getName = preg_match('/\[.+\]/', $tr, $match);
    //         // $tr = preg_replace('/(\<td(.*)\>)\[.+\](\<\/td\>)/', '', trim($tr));
    //         if ($getName > 0 ) {
    //             $arr = explode('/', str_replace(['[', ']'], ['', ''], $match[0]));
    //             $arrData[$arr[0]][] = $tr;
    //         }
    //     }
    //     $result_arr = [];
    //     foreach ($arrData as $t_name => $table) {
    //         foreach ($table as $row) {
    //             $getDate = preg_match('/\{.+\}/', $row, $match);
    //             if ($getDate < 1) {
    //                 $result_arr[$t_name][] = $row;
    //             } else {
    //                 $r_date = explode(';', str_replace(['{', '}'], ['', ''], $match[0]));
    //                 foreach ($r_date as $dt) {
    //                     $r_dt = explode('-', $dt);
    //                     if (strtotime($date) >= strtotime($this->convertDate($r_dt[0]))
    //                     && strtotime($date) <= strtotime($this->convertDate($r_dt[1]))) {
    //                             $row = preg_replace('/\<td>(.*\d+)\<\/td>/', '<td class="text-right">$1</td>', $row);
    //                             $row = preg_replace('/\<td(.*)\>(\{.+\})(\<\/td\>)/', '<td $1 class="text-center"><span class="date_hover text-info" data-dt="$2" title="$2"><i class="fa fa-exclamation-circle"></i></span>', $row);//str_replace(['{', '}'], ['', ''], $row);
    //                             $result_arr[$t_name][] = str_replace(['{', '}'], ['', ''], $row);
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $result_arr;
    // }
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

            $html = Venue::find()
                ->select('new_pricetable')
                ->where(['id'=>$venue_id])
                ->asArray()
                ->scalar();

            $price_data = $this->explodeHtml($html, $this->convertDate($date));
            return json_encode($price_data);
        }
    }

    /*
     * Update venue info
     */
    public function actionU($id = 0) {
        if (!in_array(USER_ID, [1, 8, 9198, 28722, 34718, 29739])) {
            throw new HttpException(403, 'Access denied');
        }

        $theVenue = Venue::findOne($id);
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        $theVenue->scenario = 'venue/u';

        if (substr($theVenue['info'], 0, 1) != '<') {
            $theVenue['info'] = \yii\helpers\Markdown::process($theVenue['info']);
        }

        // 2018-05-28
        $venueClassiList = [
            '1_bud'=>Yii::t('xx', 'Budget'),
            '1_sta'=>Yii::t('x', 'Standard'),
            '1_sup'=>Yii::t('x', 'Superior'),
            '1_del'=>Yii::t('x', 'Deluxe'),
            '1_lux'=>Yii::t('x', 'Luxury'),
        ];

        $venueArchiList = [
            '2_01'=>Yii::t('x', 'Small building'),
            '2_02'=>Yii::t('x', 'Big building'),
            '2_03'=>Yii::t('x', 'Colonial style'),
            '2_04'=>Yii::t('x', 'Traditional house'),
            '2_05'=>Yii::t('x', 'Bungalows'),
            '2_06'=>Yii::t('x', 'Atypical'),
        ];

        $venueTypeList = [
            '3_01'=>Yii::t('x', 'Hotel'),
            '3_02'=>Yii::t('x', 'Apartment'),
            '3_03'=>Yii::t('x', 'Villa'),
            '3_04'=>Yii::t('x', 'Guesthouse'),
            '3_05'=>Yii::t('x', 'Farm stay'),
            '3_06'=>Yii::t('x', 'Resort'),
            '3_07'=>Yii::t('x', 'Campsite'),
            '3_08'=>Yii::t('x', 'Hostel'),
            '3_09'=>Yii::t('x', 'Homestay'),
            '3_10'=>Yii::t('x', 'Motel'),
            '3_11'=>Yii::t('x', 'Lodge'),
        ];

        $venueStyleList = [
            '4_01'=>Yii::t('x', 'Charming'),
            '4_02'=>Yii::t('x', 'Boutique'),
            '4_03'=>Yii::t('x', 'Character'),
            '4_04'=>Yii::t('x', 'International'),
        ];

        $venueFaciList = [
            '5_01'=>Yii::t('x', 'Lift'),
            '5_02'=>Yii::t('x', 'Indoor swimming pool'),
            '5_03'=>Yii::t('x', 'Outdoor swimming pool'),
            '5_04'=>Yii::t('x', 'Kid’s pool'),
            '5_05'=>Yii::t('x', 'Garden'),
            '5_06'=>Yii::t('x', 'Private beach'),
            '5_07'=>Yii::t('x', 'Spa'),
            '5_08'=>Yii::t('x', 'Massage sauna'),
            '5_09'=>Yii::t('x', 'Bicycle or motorbike'),
            '5_10'=>Yii::t('x', 'Restaurant to recommend'),
            '5_11'=>Yii::t('x', 'Breakfast international buffet'),
            '5_12'=>Yii::t('x', 'Gym/ Fitness centre'),
            '5_13'=>Yii::t('x', 'Conference room'),
            '5_14'=>Yii::t('x', 'Meeting/ banquet facilities'),
            '5_15'=>Yii::t('x', 'Disabled facilities'),
            '5_16'=>Yii::t('x', 'Eco-responsible approach'),
            '5_17'=>Yii::t('x', 'Room service'),
            '5_18'=>Yii::t('x', 'Free wifi outside'),
            '5_19'=>Yii::t('x', 'Airport shuttle'),
            '5_20'=>Yii::t('x', 'Laundry service'),
            '5_21'=>Yii::t('x', 'Terrace'),
            '5_22'=>Yii::t('x', 'Balcony'),
            '5_23'=>Yii::t('x', 'Pet allowed'),
            '5_24'=>Yii::t('x', 'Non-smoking room'),
            '5_25'=>Yii::t('x', 'Family rooms'),
            '5_26'=>Yii::t('x', 'Baby cot'),
            '5_27'=>Yii::t('x', 'Air conditioning'),
            '5_28'=>Yii::t('x', 'Bath tub'),
            '5_30'=>Yii::t('x', 'Internet computers'),
            '5_31'=>Yii::t('x', 'Coffee and tea facilities'),
            '5_32'=>Yii::t('x', 'Electric kettle'),
            '5_33'=>Yii::t('x', 'Iron'),
            '5_34'=>Yii::t('x', 'Hair dresser'),
            '5_35'=>Yii::t('x', 'Electric fan'),
            '5_36'=>Yii::t('x', 'Refrigerator'),
            '5_37'=>Yii::t('x', 'Massage'),
            '5_38'=>Yii::t('x', 'Sauna'),
            '5_40'=>Yii::t('x', 'French'),
            '5_41'=>Yii::t('x', 'English'),
            '5_42'=>Yii::t('x', 'Telephone'),
            '5_43'=>Yii::t('x', 'TV'),
            '5_44'=>Yii::t('x', 'Airport drop off'),
            '5_45'=>Yii::t('x', 'Airport pick up'),
            '5_46'=>Yii::t('x', 'Children’s playground'),
            '5_47'=>Yii::t('x', 'BBQ facilities'),
            '5_50'=>Yii::t('x', 'Babysitter upon request'),

            //bo sung 28/6
            '5_48'=>Yii::t('x', 'German'),
            '5_51'=>Yii::t('x', 'Restaurant'),
            '5_52'=>Yii::t('x', 'Business Centre'),
            '5_53'=>Yii::t('x', '24h reception'),
            '5_54'=>Yii::t('x', 'Parking'),
            '5_55'=>Yii::t('x', 'Car hire'),
            '5_56'=>Yii::t('x', 'Library'),
            '5_57'=>Yii::t('x', 'Transportation'),
            '5_58'=>Yii::t('x', 'Beauty salon'),
            '5_59'=>Yii::t('x', 'Deck chair'),
            '5_60'=>Yii::t('x', 'Desk'),
            '5_61'=>Yii::t('x', 'Electronic safe'),
            '5_62'=>Yii::t('x', 'Fitness/spa locker rooms'),
            '5_63'=>Yii::t('x', 'Yoga classes'),
            '5_64'=>Yii::t('x', 'kid\'s menu'),
            '5_65'=>Yii::t('x', 'Wheelchair access'),
            '5_66'=>Yii::t('x', 'Mid-height light switches and power outlets'),
            '5_67'=>Yii::t('x', 'Raised toilet'),
            '5_68'=>Yii::t('x', 'Meeting room'),
            '5_69'=>Yii::t('x', 'Free wifi in room'),
            '5_70'=>Yii::t('x', 'Satellite TV'),
            '5_71'=>Yii::t('x', 'TV channels'),
            '5_72'=>Yii::t('x', 'play ground'),

        ];

        asort($venueFaciList);

        $venueReccList = [
            '6_01'=>Yii::t('x', 'Couple'),
            '6_02'=>Yii::t('x', 'Family'),
            '6_03'=>Yii::t('x', 'Group'),
            '6_04'=>Yii::t('x', 'Honeymoon'),
            '6_05'=>Yii::t('x', 'Demanding travelers'),
            '6_06'=>Yii::t('x', 'Old people'),
            '6_07'=>Yii::t('x', 'Young people'),
        ];

        $venueStraRecList = [
            'sr_s'=>Yii::t('x', 'Strategic to Amica'),
            'sr_r'=>Yii::t('x', 'Recommended by Amica'),
        ];

        $venueStarList = [
            's_1s'=>Yii::t('x', '1 star'),
            's_2s'=>Yii::t('x', '2 stars'),
            's_3s'=>Yii::t('x', '3 stars'),
            's_4s'=>Yii::t('x', '4 stars'),
            's_5s'=>Yii::t('x', '5 stars'),
        ];

        $newTags = explode(';|', $theVenue->new_tags);

        foreach ($newTags as $newTag) {
            if (substr($newTag, 0, 6) == 'new_o_') {
                $theVenue->new_o = substr($newTag, 6);
            }
            if (substr($newTag, 0, 6) == 'new_p_') {
                $theVenue->new_p = substr($newTag, 6);
            }
        }

        foreach ($newTags as $newTag) {
            if (substr($newTag, 0, 7) == 'vdista_') {
                $theVenue->vdista = (int)substr($newTag, 7);
            }
            if (substr($newTag, 0, 7) == 'vdistb_') {
                $theVenue->vdistb = (int)substr($newTag, 7);
            }
            if (substr($newTag, 0, 7) == 'vdistc_') {
                $theVenue->vdistc = (int)substr($newTag, 7);
            }
        }

        foreach ($venueStraRecList as $code=>$stra) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vstr = $code;
                break;
            }
        }

        foreach ($venueStarList as $code=>$star) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vstar = $code;
                break;
            }
        }

        foreach ($venueClassiList as $code=>$class) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vclassi = $code;
                break;
            }
        }
        foreach ($venueArchiList as $code=>$archi) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->varchi = $code;
                break;
            }
        }
        foreach ($venueTypeList as $code=>$type) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vtype = $code;
                break;
            }
        }

        $theVenue->vstyle = [];
        foreach ($venueStyleList as $code=>$style) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vstyle[] = $code;
            }
        }
        $theVenue->vfaci = [];
        foreach ($venueFaciList as $code=>$faci) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vfaci[] = $code;
            }
        }
        $theVenue->vreccfor = [];
        foreach ($venueReccList as $code=>$recc) {
            if (strpos($theVenue->new_tags, $code) !== false) {
                $theVenue->vreccfor[] = $code;
            }
        }
        $theVenue->vpricerange = trim($theVenue->new_pricemin.'-'.$theVenue->new_pricemax, '-');
        // var_dump($theVenue->new_tags);die;
        // \fCore::expose($theVenue);
        // exit;

        if ($theVenue->load(Yii::$app->request->post()) && $theVenue->validate()) {
            if (isset($_GET['x'])) {
                \fCore::expose($_POST);
                exit;
            }

            $theVenue->updated_at = NOW;
            $theVenue->updated_by = USER_ID;
            // Neu link google map, tach LAT LNG ra khoi link
            // VD https://www.google.com.vn/maps/place/Kh%C3%A1ch+s%E1%BA%A1n+H%E1%BA%A3i+Long+C%C3%A1t+B%C3%A0/@20.7233141,107.0505399,15z/data=!4m2!3m1!1s0x0:0xe0c05a258bce227f?hl=vi&sa=X&ved=0ahUKEwi-i_b8hKnWAhUD2LwKHY2DCAcQ_BIIgQEwCg
            if (strpos($theVenue['latlng'], 'google') !== false) {
                $pos1 = strpos($theVenue['latlng'], '/@');
                if ($pos1 !== false) {
                    $theVenue['latlng'] = substr($theVenue['latlng'], $pos1 + 2);
                    $pos2 = strpos($theVenue['latlng'], 'z');
                    if ($pos2 !== false) {
                        $theVenue['latlng'] = substr($theVenue['latlng'], 0, $pos2 - 3);
                    }
                }
            }
            // Ghi nhan images
            if (strpos($theVenue['images'], ';|') === false) {
                $img = '';
                $cnt = 0;
                preg_match_all('/<img[^>]*?\s+src\s*=\s*"([^"]+)"[^>]*?>/i', $theVenue['images'], $matches);
                foreach ($matches[1] as $match){
                    if (
                        (strpos($match, 'bstatic') !== false && strpos($match, 'images/hotel/') !== false)
                        || (strpos($match, 'tripadvisor.com') !== false && strpos($match, 'media/photo-s') !== false)
                        ) {
                        $cnt ++;
                        if ($cnt > 1) {
                            $img .= ';|';
                        }
                        echo $match,';|';
                        $img .= $match;
                    }
                }
                $theVenue['images'] = $img;
            }

            // if (empty($theVenue->vclassi)) {
            //     $theVenue->vclassi = ['1_sta'];
            // }
            if (empty($theVenue->vstyle)) {
                $theVenue->vstyle = [];
            }
            if (empty($theVenue->vfaci)) {
                $theVenue->vfaci = [];
            }
            if (empty($theVenue->vreccfor)) {
                $theVenue->vreccfor = [];
            }
            if (isset($_POST['add_fee']) && count($_POST['add_fee']) > 0) {
                $add_fee = $_POST['add_fee'];
                foreach ($theVenue->vfaci as $k_faci => $faci) {
                    if (in_array($faci, $add_fee)) {
                        $theVenue->vfaci[$k_faci] .= '_';
                    }
                }
            }

            $newTags = ['new_o_'.$theVenue->new_o, 'new_p_'.$theVenue->new_p, $theVenue->vstr, $theVenue->vstar, $theVenue->varchi, $theVenue->vtype, $theVenue->vclassi, 'vdistc_'.$theVenue->vdistc, 'vdistb_'.$theVenue->vdistb, 'vdista_'.$theVenue->vdista];

            $newTags = array_merge($newTags, $theVenue->vstyle, $theVenue->vfaci, $theVenue->vreccfor);
            // var_dump($newTags);die;
            $theVenue->new_tags = implode(';|', $newTags);

            // Min - Max price
            $prices = explode('-', $theVenue->vpricerange);
            $theVenue->new_pricemin = (int)$prices[0];
            if (isset($prices[1]) && (int)$prices[1] > $theVenue->new_pricemin) {
                $theVenue->new_pricemax = (int)$prices[1];
            } else {
                $theVenue->new_pricemax = $theVenue->new_pricemin;
            }

            if ($theVenue->save(false)) {
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
