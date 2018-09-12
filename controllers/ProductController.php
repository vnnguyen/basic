<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\data\Pagination;
use common\models\Note;
use common\models\User;
use common\models\Product;
use common\models\Booking;
use common\models\Kase;
use common\models\Day;
use common\models\Nm;
use common\models\Tour;
use common\models\Venue;
use common\models\Invoice;
use common\models\Payment;
/*
INSERT INTO at_products (id, created_at, created_by, updated_at, updated_by, status, stype, op_status, code, name, info, note, start_date)
(SELEECT id, uo, ub, uo, ub, status, offer_type, "nop", "", title, about, summary, day_from FROM at_ct ORDER BY id);
UPDATE at_products SET op_status="op", code = (SELECT code FROM at_tours t WHERE t.ct_id=at_products.id LIMIT 1);
*/
/*
-- Chuyển và tạo tour TCG
TRUNCATE TABLE at_prod_tcgtour;
INSERT INTO at_prod_tcgtour (day_count, pax_count, name, ct_id, tour_from)
(SELECT days, pax, title, id, day_from from at_ct where ub=15955 order by id);
*/

class ProductController extends MyController
{
/*
INSERT INTO at_prod_tour (id, owner, name, day_count, pax_count, prices) 
(SELECT id, ub, title, days, pax, prices FROM at_ct WHERE offer_type="private" order by id);

INSERT INTO at_products (rtype, rid)
(SELECT "tour", id FROM at_prod_tour ORDER BY id);
*/
    public function behaviors() {
        return [
            'AccessControl' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['x'],
                        'allow'=>true,
                        //'roles'=>['?'],
                    ], [
                        'allow'=>true,
                        'roles'=>['@'],
                    ],
                ]
            ]
        ];
    }

    // Auto devis
    public function actionX($id = 0)
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with([
                'days',
                'createdBy'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name', 'email', 'image']);
                }
            ])
            ->asArray()
            ->one();
        if (!$theProduct) {
            die('ERROR');
        }

        $metaData = [];
        $sql = 'SELECT * FROM at_meta WHERE rtype="product" AND rid=:id AND SUBSTRING(k,1,3)="td/" ORDER BY k';
        $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id']])->queryAll();

        foreach ($metas as $meta) {
            $items = explode('|', $meta['v']);
            if (count($items) == 4) {
                $metaData[] = $items;
            }
        }

        $cnt = 0;
        $devisTableData = [];
        if (!empty($metaData)) {
            foreach ($metaData as $line) {
                if (in_array($line[0], ['-', '']) && !empty($devisTableData)) {
                    $devisTableData[$cnt - 1][2] .= "\n".$line[2];
                } else {
                    $devisTableData[$cnt] = [$line[0], $line[1], $line[2], $line[3]];
                    $cnt ++;
                }
            }
        }

        $theProduct['tableau-devis'] = $devisTableData;

        $key = 'hu4n12bb';

        $data = serialize($theProduct);
        echo $data;
    }

    // Tableau devis, 160718
    public function actionTd($id = 0, $action = '')
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found');
        }

        if (USER_ID != 1) {
            //throw new HttpException(403, 'Please wait. This page is being updated');
        }

        if (!in_array(USER_ID, [1, 4432, 26435, $theProduct['created_by'], $theProduct['updated_by']])) {
            // throw new HttpException(403, 'Access denied');
        }

        if ($theProduct['owner'] != 'at' || $theProduct['language'] != 'fr') {
            // throw new HttpException(403, 'Not applicable');
        }

        $metaData = [];
        $sql = 'SELECT * FROM metas WHERE rtype="product" AND rid=:id AND SUBSTRING(name,1,3)="td/" ORDER BY name';
        $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id']])->queryAll();

        foreach ($metas as $meta) {
            $items = explode('|', $meta['v']);
            if (count($items) == 4) {
                $metaData[] = $items;
            }
        }

        if (Yii::$app->request->isPost) {
            if (USER_ID == 1) {
                // \fCore::expose($_POST); exit;
            }
            if (isset($_POST['i1'], $_POST['i2'], $_POST['i3'], $_POST['i4']) && is_array($_POST['i1'])) {
                Yii::$app->db->createCommand('DELETE FROM at_meta WHERE rtype="product" AND rid=:id AND SUBSTRING(k,1,3)="td/"', [':id'=>$theProduct['id']])->execute();
                for ($i = 0; $i < count($_POST['i1']); $i ++) {
                    $k = 'td/'.substr('0'.$i, -2);
                    $v = implode('|', [$_POST['i1'][$i], $_POST['i2'][$i], $_POST['i3'][$i], $_POST['i4'][$i]]);
                    Yii::$app->db->createCommand()->insert('at_meta', [
                        'uo'=>NOW,
                        'ub'=>USER_ID,
                        'rtype'=>'product',
                        'rid'=>$theProduct['id'],
                        'k'=>$k,
                        'v'=>$v,
                    ])->execute();
                }
                return $this->redirect('/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('product_td', [
            'theProduct'=>$theProduct,
            'metaData'=>$metaData,
        ]);
    }
 
    // Price table, 160915
    public function actionPt($id = 0)
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found');
        }

        $theVenues = Venue::find()
            ->select(['id', 'name', 'destination_id', 'search'])
            ->where(['stype'=>['hotel', 'home', 'cruise']])
            ->with([
                'destination',
                'metas'=>function($q) {
                    return $q->select(['v', 'rid', 'rtype'])->where(['k'=>['website', 'pinterest']]);
                }
                ])
            ->orderBy('destination_id, name')
            ->asArray()
            ->all();

        if (USER_ID != 1) {
            //throw new HttpException(403, 'Please wait. This page is being updated');
        }

        if (!in_array(USER_ID, [1, 4432, 26435, $theProduct['created_by'], $theProduct['updated_by']])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($theProduct['owner'] != 'at' || $theProduct['language'] != 'fr') {
            throw new HttpException(403, 'Not applicable');
        }

        if (Yii::$app->request->isPost) {
            // \fCore::expose($_POST); exit;
            if (isset($_POST['prices'])) {
                Yii::$app->db->createCommand('UPDATE at_ct SET prices=:p WHERE id=:id LIMIT 1', [':p'=>$_POST['prices'], ':id'=>$theProduct['id']])->execute();
                return $this->redirect('/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('product_pt', [
            'theProduct'=>$theProduct,
            'theVenues'=>$theVenues,
        ]);
    }

    // Client ref for Nhung, 151202
    public function actionRef($id = 0)
    {
        $theProduct = Product::find()
            ->with([
                'bookings',
                'bookings.case',
                ])
            ->where(['id'=>$id])
            ->one();
        if (!$theProduct) {
            throw new HttpException(404, 'Tour not found');
        }
        // Client tours only
        $companyId = 0;
        foreach ($theProduct['bookings'] as $booking) {
            if ($booking['case']['company_id'] == 0) {
                throw new HttpException(404, 'Tour not from a client');
            } else {
                $companyId = $booking['case']['company_id'];
            }
        }
        // Huan, Nhung
        if (!in_array(USER_ID, [1, 11724, 36654])) {
            throw new HttpException(403, 'Access denied');
        }
        $theProduct->scenario = 'product/ref';
        if ($theProduct->load(Yii::$app->request->post()) && $theProduct->validate()) {
            $theProduct->save(false);
            return $this->redirect('@web/b2b/client/'.$companyId.'?view=tours');
        }
        return $this->render('product_ref', [
            'theProduct'=>$theProduct
        ]);
    }

    // Swap chuong trinh tour final
    public function actionSwap($old = 0, $new = 0)
    {
        exit;
        if ($old == 0 || $new == 0 || $old == $new) {
            throw new HttpException(403, 'Cannot change this');
        }
        if (USER_ID != 1) {
            throw new HttpException(403, 'Access denied');
        }
        $oldProduct = Product::find()->where(['id'=>$old])->one();
        $newProduct = Product::find()->where(['id'=>$new])->one();
        //echo '<hr>NEW';
        //\fCore::expose($newProduct);
        //echo '<hr>OLD';
        //\fCore::expose($oldProduct);

        echo '<hr>OLD';
        \fCore::expose($oldProduct->getAttributes());
        echo '<hr>NEW';
        //\fCore::expose($newProduct->getAttributes());

        $tmpProduct = new Product;

        $tmpProduct->setAttributes($oldProduct->getAttributes(null, ['id']), false);
        $oldProduct->setAttributes($newProduct->getAttributes(null, ['id']), false);
        $newProduct->setAttributes($tmpProduct->getAttributes(null, ['id']), false);

        echo '<hr>OLD';
        \fCore::expose($oldProduct->getAttributes());
        echo '<hr>NEW';
        //\fCore::expose($newProduct->getAttributes());
        $oldProduct->save(false);
        $newProduct->save(false);
        //Yii::$app->db->createCommand()->update('at_days', ['rid'=>$old], ['rid'=>$new])
/* TODO swap days rid
-- OLD 41544
UPDATE at_days SET rid=41712 WHERE rid=41544 AND in IN (656135,656136,656137,656152,656138,656139,656140,656141,656142,656143,656144,656151,656145,656146,656147,656148,656153,656156,656154,656155,656150);
-- NEW 41712
UPDATE at_days SET rid=41544 WHERE rid=41712  AND in IN (659281,659273,659282,659283,659284,659285,659286,659287,659288,659289,659261,659262,659264,659274,659275,659276,659277,659278,659279,659280);
*/
    }

    public function actionIndex($language = '', $customer = '')
    {
        $getLanguage = Yii::$app->request->get('language', 'all');
        $getType = Yii::$app->request->get('type', 'all');
        $getMonth = Yii::$app->request->get('month', 'all');
        $getUb = Yii::$app->request->get('ub', 0);
        $getProposal = Yii::$app->request->get('proposal', ['all', 'yes', 'no']);
        $getDays = Yii::$app->request->get('days', 'all');
        $getName = Yii::$app->request->get('name', '');
        $getOrder = Yii::$app->request->get('order', 'uo');
        $getSort = Yii::$app->request->get('sort', 'desc');

        $query = Product::find();

        if (SEG2 == 'b2b') {
            $query->andWhere(['owner'=>'si']);
            $query->andWhere(['not', ['offer_type'=>'b2b-prod']]);
        } elseif (SEG2 == 'b2b-prod') {
            $query->andWhere(['owner'=>'si', 'offer_type'=>'b2b-prod']);
        } else {
            $query->andWhere(['owner'=>'at']);
        }

        if (in_array($getLanguage, ['en', 'fr', 'it', 'vi'])) {
            $query->andWhere(['language'=>$getLanguage]);
        }
        if (in_array($getType, ['private', 'combined2016', 'vpc', 'tcg', 'agent'])) {
            $query->andWhere(['offer_type'=>$getType]);
        }
        if ($getMonth != 'all') {
            $query->andWhere('SUBSTRING(day_from,1,7)=:mo', [':mo'=>$getMonth]);
        }
        if ($getUb != 0) {
            $query->andWhere(['created_by'=>$getUb]);
        }
        if ($getName != '' && strlen($getName) >= 2) {
            $query->andWhere(['or', ['like', 'title', $getName], ['like', 'about', $getName], ['like', 'tags', $getName]]);
        }
        if ($getProposal == 'yes') {
            $query->andWhere('offer_count>0');
        } elseif ($getProposal == 'no') {
            $query->andWhere('offer_count=0');
        }
        if ($getDays == '10') {
            $query->andWhere('day_count<=10');
        } elseif ($getDays == '20') {
            $query->andWhere('day_count>=11')->andWhere('day_count<=20');
        } elseif ($getDays == '30') {
            $query->andWhere('day_count>=21')->andWhere('day_count<=30');
        } elseif ($getDays == '31') {
            $query->andWhere('day_count>30');
        }
        $startDateList = Yii::$app->db->createCommand('SELECT SUBSTRING(day_from,1,7) AS ym FROM at_ct GROUP BY ym ORDER BY ym DESC')
            ->queryAll();
        $ubList = Yii::$app->db->createCommand('SELECT u.id, lname, email FROM persons u, at_ct ct WHERE ct.updated_by=u.id GROUP BY u.id ORDER BY u.lname')
            ->queryAll();

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            'route'=>'/'.URI,
        ]);

        if (!in_array($getOrder, ['updated_at', 'day_from', 'day_count', 'pax', 'title'])) {
            $getOrder = 'updated_at';
        }

        if (!in_array($getSort, ['asc', 'desc'])) {
            $getSort = 'desc';
        }

        $theProducts = $query
            ->orderBy($getOrder.' '.$getSort)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'ct_id', 'code', 'name']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname', 'image']);
                },
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings',
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'name']);
                }
                ])
            ->asArray()
            ->all();

        if (SEG2 == 'b2b') {
            // $customerList = Company::find()
            //     ->select(['id', 'name'])
            //     ->where(new yii\db\Expression('(SELECT COUNT(*) FROM at_cases WHERE company_id=id LIMIT 1)>0'))
            //     ->orderBy('name')
            //     ->asArray()
            //     ->all()
        }

        return $this->render((SEG2 == 'b2b-prod' ? 'product_b2b-prod' : 'product_index'), [
            'getOrder'=>$getOrder,
            'getSort'=>$getSort,
            'startDateList'=>$startDateList,
            'ubList'=>$ubList,
            'getLanguage'=>$getLanguage,
            'getUb'=>$getUb,
            'getProposal'=>$getProposal,
            'getType'=>$getType,
            'getMonth'=>$getMonth,
            'getDays'=>$getDays,
            'getName'=>$getName,
            'theProducts'=>$theProducts,
            'pagination'=>$pagination,
        ]);
    }

    // Load CT data from remote server (LON)
    public function actionXOld($id = 0)
    {
        $key = 'tuanNA140407';

        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        $data = serialize($theProduct);
        echo Yii::$app->security->encryptByKey($data, $key);
        //echo $data;
    }

    // New B2B Prod
    private function newB2bProd() {
        $theProduct = new Product;
        
        $theProduct->price = 0;
        $theProduct->scenario = 'product/c/prod';
        $theProduct->day_from = date('Y-m-d');
        $theProduct->price_until = date('Y-m-d', strtotime('+1 year'));

        if ($theProduct->load(Yii::$app->request->post()) && $theProduct->validate()) {
            $theProduct->created_at = NOW;
            $theProduct->created_by = USER_ID;
            $theProduct->updated_at = NOW;
            $theProduct->updated_by = USER_ID;
            $theProduct->status = 'on';
            $theProduct->owner = 'si';
            $theProduct->offer_type = 'b2b-prod';
            if ($theProduct->save(false)) {
                Yii::$app->session->setFlash('success', 'Tour itinerary has been added: '.$theProduct->title);
                return $this->redirect('@web/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('product_u_prod', [
            'theProduct'=>$theProduct,
        ]);
    }

    public function actionC($b2b = 'no') {
        if ($b2b == 'prod') {
            return $this->newB2bProd();
        }

        $theProduct = new Product;
        
        $theProduct->price = 0;
        $theProduct->scenario = 'products_c';
        $theProduct->day_from = date('Y-m-d');
        $theProduct->price_until = date('Y-m-d', strtotime('+1 year'));

        if ($theProduct->load(Yii::$app->request->post()) && $theProduct->validate()) {
            $theProduct->created_at = NOW;
            $theProduct->created_by = USER_ID;
            $theProduct->updated_at = NOW;
            $theProduct->updated_by = USER_ID;
            $theProduct->status = 'on';
            if ($b2b == 'yes') {
                $theProduct->owner = 'si';
            } else {
                $theProduct->owner = 'at';
            }
            if ($theProduct->save(false)) {
                Yii::$app->session->setFlash('success', 'Tour itinerary has been added: '.$theProduct->title);
                return $this->redirect('@web/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('product_u', [
            'theProduct'=>$theProduct,
        ]);
    }

    // Test new building a product (add/edit/re-arrange days)
    public function actionH($id = 0, $action = '')
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with([
                'days',
                'bookings',
                'bookings.case',
                'bookings.createdBy',
            ])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        $theDays = Day::find()->where(['rid'=>$id])->asArray()->all();

        // Load extra days
        if ($theProduct['offer_type'] == 'combined2016') {
            $extraDays = Day::find()
                ->where(['rid'=>$id])
                ->andWhere('parent_day_id!=0')
                ->asArray()
                ->all();
        }
        // Check and fix day numbers
        $dayIdList = array_filter(explode(',', trim($theProduct['day_ids'], ',')));
        if ($theProduct['day_count'] != count($dayIdList)) {
            $theProduct->day_count = count($dayIdList);
            Yii::$app->db->createCommand()
                ->update('at_ct', ['day_count'=>count($dayIdList)], ['id'=>$id])
                ->execute();
        }

        $theCases = Yii::$app->db
            ->createCommand('SELECT c.id, c.name FROM at_cases c, at_bookings b WHERE b.case_id=c.id AND b.product_id=:id LIMIT 100', [':id'=>$id])
            ->queryAll();

        $theTour = Tour::find()
            ->where(['ct_id'=>$id])
            ->one();

        $theDay = new Day;
        $theDay->scenario = 'day/c';

        if ($action == 'insert-day') {
            $insertFrom = $_POST['from'];
            $insertId = $_POST['id'];
            $insertAt = $_POST['at'];

            if ($insertFrom == 'blank') {
                // Insert blank day
                $newDay = new Day;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->language = $theProduct['language'];
                $newDay->rid = $theProduct['id'];
                $newDay->step = 1;
                $newDay->name = '( blank )';
                $newDay->body = '<p>( blank )</p>';
                $newDay->image = '';
                $newDay->meals = '---';
                $newDay->transport = '';
                $newDay->guides = '';
                $newDay->note = '';
            } elseif ($insertFrom == 'sd') {
                // Sample day
                $sourceDay = Nm::findOne($insertId);
                if (!$sourceDay) {
                    throw new HttpException(404, 'Sample day not found');
                }
                $newDay = new Day;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->language = $sourceDay->language;
                $newDay->rid = $theProduct['id'];
                $newDay->step = 1;
                $newDay->name = $sourceDay->title;
                $newDay->body = $sourceDay->body;
                $newDay->image = $sourceDay->image;
                $newDay->meals = $sourceDay->meals;
                $newDay->transport = $sourceDay->transport;
                $newDay->guides = $sourceDay->guides;
                $newDay->note = $sourceDay->note;
            } else {
                // Program day, previous day
                $sourceDay = Day::findOne($insertId);
                if (!$sourceDay) {
                    throw new HttpException(404, 'Day not found');
                }
                $newDay = (clone $sourceDay);
                unset($newDay->id);
                $newDay->isNewRecord = true;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->rid = $theProduct['id'];
                $newDay->step = 1;
            }

            if ($newDay->save(false)) {
                // Insert new day id
                if ($insertAt == -1) {
                    // Insert at first pos
                    $dayIdList = array_merge($newDay->id, $dayIdList);
                } else {
                    array_splice($dayIdList, (int)$_POST['at'], 0, $newDay->id);
                }
                $theProduct['day_ids'] = implode(',', $dayIdList);
                // Save product
                $theProduct->save(false);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'id'=>$newDay->id,
                    'meals'=>$newDay->meals,
                    'title'=>$newDay->name,
                    'guides'=>$newDay->guides,
                    'transport'=>$newDay->transport,
                    'body'=>$newDay->body,
                ];
            }
            throw new HttpException(401);
        }

        if ($action == 'json') {
            require_once('/var/www/vendor/textile/php-textile/Parser.php');
            $parser = new \Netcarver\Textile\Parser();

            $searchIn = $_POST['search_in'];
            $searchLang = $_POST['search_lang'];
            $searchName = $_POST['search_name'];
            $searchTags = $_POST['search_tags'];
            $searchB2cb = $_POST['search_b2cb'];
            $searchPage = $_POST['search_page'];
            if ($searchIn == 'sd') {
                $query = \common\models\Nm::find()
                    ->select(['id', 'title', 'meals', 'body', 'guides', 'transport'])
                    ->andWhere(['language'=>$searchLang]);
                if ($searchB2cb == 'b2c') {
                    $query->andWhere(['owner'=>'at']);
                } else {
                    $query->andWhere(['owner'=>'si']);
                }
                if (strlen($searchName) > 2) {
                    $query->andWhere(['like', 'name', $searchName]);
                }
                if (strlen($searchTags) > 2) {
                    $tagArray = explode(',', str_replace([' '], [','], $searchTags));
                    foreach ($tagArray as $tag) {
                        if (trim($tag) != '') {
                            $query->andWhere('LOCATE(:tags, tags) !=0', [':tags'=>$searchTags]);
                        }
                    }
                }

                $countQuery = clone $query;
                $pagination = new Pagination([
                    'page'=>$searchPage,
                    'totalCount' => $countQuery->count(),
                    'pageSize'=>25,
                ]);

                $theDays = $query
                    ->orderBy('title')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->asArray()
                    ->all();

                $prevPage = $pagination->page == 0 ? false : $pagination->page - 1;
                $nextPage = $pagination->page + 1 >= $pagination->pageCount ? false : $pagination->page + 1;

                $result = [
                    'page'=>$pagination->page,
                    'prev_page'=>$prevPage,
                    'next_page'=>$nextPage,
                    'data'=>[],
                ];
                foreach ($theDays as $day) {
                    $result['data'][] = [
                        'is'=>'day',
                        'id'=>$day['id'],
                        'prog_id'=>0,
                        'title'=>$day['title'],
                        'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                        'meals'=>$day['meals'],
                        'meals'=>$day['meals'],
                        'guides'=>$day['guides'],
                        'transport'=>$day['transport'],
                    ];
                }                
            } elseif ($searchIn == 'ap' || $searchIn == 'mp') {
                // All programs
                $query = Product::find()
                    ->select(['id', 'title', 'day_ids', 'day_from', 'updated_at', 'updated_by', 'op_status', 'op_code'])
                    ->andWhere(['language'=>$searchLang]);
                if ($searchIn == 'mp') {
                    $query->andWhere(['updated_by'=>USER_ID]);
                }

                if ($searchB2cb == 'b2c') {
                    $query->andWhere(['offer_type'=>['private', 'combined2016']]);
                } else {
                    $query->andWhere(['offer_type'=>['agent', 'b2b-prod']]);
                }

                if (strlen($searchName) > 2) {
                    $query->andWhere(['like', 'title', $searchName]);
                }

                if (strlen($searchTags) > 2) {
                    $tagArray = explode(',', str_replace([' '], [','], $searchTags));
                    foreach ($tagArray as $tag) {
                        if (trim($tag) != '') {
                            $query->andWhere('LOCATE(:tags, tags) !=0', [':tags'=>$searchTags]);
                        }
                    }
                }
                
                $countQuery = clone $query;
                $pagination = new Pagination([
                    'page'=>$searchPage,
                    'totalCount' => $countQuery->count(),
                    'pageSize'=>25,
                ]);

                $thePrograms = $query
                    ->orderBy('day_from DESC, updated_at DESC')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->with([
                        'days'=>function($q) {
                            return $q->select(['id', 'rid', 'name', 'body', 'meals', 'guides', 'transport']);
                        },
                        'updatedBy'=>function($q) {
                            return $q->select(['id', 'name'=>'nickname']);
                        },
                    ])
                    ->asArray()
                    ->all();

                $prevPage = $pagination->page == 0 ? false : $pagination->page - 1;
                $nextPage = $pagination->page + 1 >= $pagination->pageCount ? false : $pagination->page + 1;

                $result = [
                    'page'=>$pagination->page,
                    'prev_page'=>$prevPage,
                    'next_page'=>$nextPage,
                    'data'=>[],
                ];

                foreach ($thePrograms as $prog) {
                    $result['data'][] = [
                        'is'=>'prog',
                        'id'=>$prog['id'],
                        'title'=>$prog['title'],
                        'start_date'=>date('j/n/Y', strtotime($prog['day_from'])),
                        'day_count'=>count(array_filter(explode(',', $prog['day_ids']))),
                        'op_code'=>$prog['op_status'] == 'op' ? $prog['op_code'] : '',
                        'updated_by_name'=>$prog['updatedBy']['name'],
                        'updated_at_time'=>date('j/n/Y', strtotime($prog['updated_at'])),
                    ];

                    $dayIdList = explode(',', $prog['day_ids']);
                    foreach ($dayIdList as $dayId) {
                        foreach ($prog['days'] as $day) {
                            if ($day['id'] == $dayId) {
                                $result['data'][] = [
                                    'is'=>'day',
                                    'id'=>$day['id'],
                                    'prog_id'=>$prog['id'],
                                    'title'=>$day['name'],
                                    'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                                    'meals'=>$day['meals'],
                                    'guides'=>$day['guides'],
                                    'transport'=>$day['transport'],
                                ];
                            }
                        }
                    }
                }                
            } else {
                exit;
                // All programs
                $theDays = \common\models\Day::find()
                    ->select(['id', 'name', 'meals', 'body'])
                    ->andWhere(['like', 'name', $searchFor])
                    ->orderBy('name')
                    ->limit(30)
                    ->asArray()
                    ->all();
                $result = [];
                foreach ($theDays as $day) {
                    $result[] = [
                        'id'=>$day['id'],
                        'title'=>$day['name'],
                        'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                        'meals'=>$day['meals'],
                    ];
                }
            }
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }

        if ($action == 'edit-day' && Yii::$app->request->isAjax) {
            if (isset($_POST['Day']['id']) && $_POST['Day']['id'] != 0) {
                $theDay = Day::findOne($_POST['Day']['id']);
                $theDay->scenario = 'day/u';
            } else {
                $theDay = new Day;
                $theDay->scenario = 'day/c';
            }
            if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
                // \Yii::$app->response->format = Response::FORMAT_JSON;
                // \fCore::expose($_POST);
                // \fCore::expose($theDay);
                // echo 'ID=', $theDay->id; return false;
                if ($theDay->save(false)) {
                    return 'Success';
                } else {
                    throw new HttpException (401, 'Error saving data');
                }
            }
        }

        if ($action == 'sort-day') {
            if (isset($_POST['ngay']) && in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) {
                $sql = 'UPDATE at_ct SET day_ids = :di WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':di'=>implode(',', $_POST['ngay']), ':id'=>$theProduct['id']])->execute();
                return true;
            } else {
                throw new HttpException(403, 'Access denied.');
            }
        }

        if ($action == 'delete-day' && isset($_POST['day']) && in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) {
            // Delete day
            $sql = 'DELETE FROM at_days WHERE rid=:id AND id=:day LIMIT 1';
            Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id'], ':day'=>$dayIdList[$_POST['day']]])->execute();
            // day = cnt thu tu ngay, khong phai ID
            unset($dayIdList[$_POST['day']]);
            // Save product
            $sql = 'UPDATE at_ct SET day_ids=:di WHERE id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProduct['id']])->execute();
            return true;   
        }

        // Add blank after
        if ($action == 'add-blank-day' && isset($_POST['at']) && in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) {
            $theDay->created_at = NOW;
            $theDay->created_by = USER_ID;
            $theDay->updated_at = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->rid = $theProduct['id'];
            $theDay->meals = '---';
            $theDay->name = '( no title )';
            $theDay->body = '';
            $theDay->note = '';
            $theDay->image = '';
            if ($theDay->save(false)) {
                array_splice($dayIdList, (int)$_POST['at'], 0, $theDay->id);
            
                // Save product
                $sql = 'UPDATE at_ct SET day_ids=:di WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProduct['id']])->execute();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['id'=>$theDay->id];
            }

            throw new HttpException(401, 'Could not add day.');
        }

        // Chua co code
        if ($theTour && $theProduct['op_code'] != $theTour['code']) {
            Yii::$app->db->createCommand()
                ->update('at_ct', [
                    'op_status'=>'op',
                    'op_code'=>$theTour['code'],
                    'op_name'=>$theTour['name'],
                    ], ['id'=>$id])
                ->execute();
        }

        $metaData = [];
        $sql = 'SELECT * FROM at_meta WHERE rtype="product" AND rid=:id AND SUBSTRING(k,1,3)="td/" ORDER BY k';
        $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id']])->queryAll();

        foreach ($metas as $meta) {
            $items = explode('|', $meta['v']);
            if (count($items) == 4) {
                $metaData[] = $items;
            }
        }

        return $this->render('product_h', [
            'theProduct'=>$theProduct,
            'theDay'=>$theDay,
            'theDays'=>$theDays,
            'extraDays'=>$extraDays ?? [],
            'theCases'=>$theCases,
            'theTour'=>$theTour,
            'metaData'=>$metaData,
        ]);
    }


    public function actionR($id = 0, $action = '')
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with([
                'days',
                'bookings',
                'bookings.case',
                'bookings.createdBy',
            ])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        $theDays = Day::find()->where(['rid'=>$id])->asArray()->all();

        // Load extra days
        if ($theProduct['offer_type'] == 'combined2016') {
            $extraDays = Day::find()
                ->where(['rid'=>$id])
                ->andWhere('parent_day_id!=0')
                ->asArray()
                ->all();
        }
        // Check and fix day numbers
        $dayIdList = array_filter(explode(',', trim($theProduct['day_ids'], ',')));
        if ($theProduct['day_count'] != count($dayIdList)) {
            $theProduct->day_count = count($dayIdList);
            Yii::$app->db->createCommand()
                ->update('at_ct', ['day_count'=>count($dayIdList)], ['id'=>$id])
                ->execute();
        }

        $theCases = Yii::$app->db
            ->createCommand('SELECT c.id, c.name FROM at_cases c, at_bookings b WHERE b.case_id=c.id AND b.product_id=:id LIMIT 100', [':id'=>$id])
            ->queryAll();

        $theTour = Tour::find()
            ->where(['ct_id'=>$id])
            ->one();

        $theDay = new Day;
        $theDay->scenario = 'day/c';

        if ($action == 'insert-day') {
            $insertFrom = $_POST['from'];
            $insertId = $_POST['id'];
            $insertAt = $_POST['at'];

            if ($insertFrom == 'blank') {
                // Insert blank day
                $newDay = new Day;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->language = $theProduct['language'];
                $newDay->rid = $theProduct['id'];
                $newDay->step = 1;
                $newDay->name = '( blank )';
                $newDay->body = '<p>( blank )</p>';
                $newDay->image = '';
                $newDay->meals = '---';
                $newDay->transport = '';
                $newDay->guides = '';
                $newDay->note = '';
            } elseif ($insertFrom == 'sd') {
                // Sample day
                $sourceDay = Nm::findOne($insertId);
                if (!$sourceDay) {
                    throw new HttpException(404, 'Sample day not found');
                }
                $newDay = new Day;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->language = $sourceDay->language;
                $newDay->rid = $theProduct['id'];
                $newDay->step = 1;
                $newDay->name = $sourceDay->title;
                $newDay->body = $sourceDay->body;
                $newDay->image = $sourceDay->image;
                $newDay->meals = $sourceDay->meals;
                $newDay->transport = $sourceDay->transport;
                $newDay->guides = $sourceDay->guides;
                $newDay->note = $sourceDay->note;
            } else {
                // Program day, previous day
                $sourceDay = Day::findOne($insertId);
                if (!$sourceDay) {
                    throw new HttpException(404, 'Day not found');
                }
                $newDay = (clone $sourceDay);
                unset($newDay->id);
                $newDay->isNewRecord = true;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->rid = $theProduct['id'];
                $newDay->step = 1;
            }

            // Truong hop thong tin ngay cũ dùng textile
            if (substr($newDay->body, 0, 1) != '<') {
                require_once('/var/www/vendor/textile/php-textile/Parser.php');
                $parser = new \Netcarver\Textile\Parser();
                $newDay->body = $parser->parse($newDay->body);
            }

            if ($newDay->save(false)) {
                // Insert new day id
                if ($insertAt == -1) {
                    // Insert at first pos
                    $dayIdList = array_merge($newDay->id, $dayIdList);
                } else {
                    array_splice($dayIdList, (int)$_POST['at'], 0, $newDay->id);
                }
                $theProduct['day_ids'] = implode(',', $dayIdList);
                // Save product
                $theProduct->save(false);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'id'=>$newDay->id,
                    'meals'=>$newDay->meals,
                    'title'=>$newDay->name,
                    'guides'=>$newDay->guides,
                    'transport'=>$newDay->transport,
                    'body'=>$newDay->body,
                ];
            }
            throw new HttpException(401);
        }

        if ($action == 'json') {
            require_once('/var/www/vendor/textile/php-textile/Parser.php');
            $parser = new \Netcarver\Textile\Parser();

            $searchIn = $_POST['search_in'];
            $searchLang = $_POST['search_lang'];
            $searchName = $_POST['search_name'];
            $searchTags = $_POST['search_tags'];
            $searchB2cb = $_POST['search_b2cb'];
            $searchPage = $_POST['search_page'];
            if ($searchIn == 'sd') {
                $query = \common\models\Nm::find()
                    ->select(['id', 'title', 'meals', 'body', 'guides', 'transport'])
                    ->andWhere(['language'=>$searchLang]);
                if ($searchB2cb == 'b2c') {
                    $query->andWhere(['owner'=>'at']);
                } else {
                    $query->andWhere(['owner'=>'si']);
                }
                if (strlen($searchName) > 2) {
                    $query->andWhere(['like', 'title', $searchName]);
                }
                if (strlen($searchTags) > 2) {
                    $tagArray = explode(',', str_replace([' '], [','], $searchTags));
                    foreach ($tagArray as $tag) {
                        if (trim($tag) != '') {
                            $query->andWhere('LOCATE(:tags, tags) !=0', [':tags'=>$searchTags]);
                        }
                    }
                }

                $countQuery = clone $query;
                $pagination = new Pagination([
                    'page'=>$searchPage,
                    'totalCount' => $countQuery->count(),
                    'pageSize'=>25,
                ]);

                $theDays = $query
                    ->orderBy('title')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->asArray()
                    ->all();

                $prevPage = $pagination->page == 0 ? false : $pagination->page - 1;
                $nextPage = $pagination->page + 1 >= $pagination->pageCount ? false : $pagination->page + 1;

                $result = [
                    'page'=>$pagination->page,
                    'prev_page'=>$prevPage,
                    'next_page'=>$nextPage,
                    'data'=>[],
                ];
                foreach ($theDays as $day) {
                    $result['data'][] = [
                        'is'=>'day',
                        'id'=>$day['id'],
                        'prog_id'=>0,
                        'title'=>$day['title'],
                        'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                        'meals'=>$day['meals'],
                        'meals'=>$day['meals'],
                        'guides'=>$day['guides'],
                        'transport'=>$day['transport'],
                    ];
                }                
            } elseif ($searchIn == 'ap' || $searchIn == 'mp') {
                // All programs
                $query = Product::find()
                    ->select(['id', 'title', 'day_ids', 'day_from', 'updated_at', 'updated_by', 'op_status', 'op_code', 'pax', 'about'])
                    ->andWhere(['language'=>$searchLang]);
                if ($searchIn == 'mp') {
                    $query->andWhere(['updated_by'=>USER_ID]);
                }

                if ($searchB2cb == 'b2c') {
                    $query->andWhere(['offer_type'=>['private', 'combined2016']]);
                } else {
                    $query->andWhere(['offer_type'=>['agent', 'b2b-prod']]);
                }

                if (strlen($searchName) > 2) {
                    $query->andWhere(['or', ['op_code'=>$searchName], ['like', 'title', $searchName], ['like', 'about', $searchName]]);
                }

                if (strlen($searchTags) > 2) {
                    $tagArray = explode(',', str_replace([' '], [','], $searchTags));
                    foreach ($tagArray as $tag) {
                        if (trim($tag) != '') {
                            $query->andWhere('LOCATE(:tags, tags) !=0', [':tags'=>$searchTags]);
                        }
                    }
                }
                
                $countQuery = clone $query;
                $pagination = new Pagination([
                    'page'=>$searchPage,
                    'totalCount' => $countQuery->count(),
                    'pageSize'=>25,
                ]);

                $thePrograms = $query
                    ->orderBy('day_from DESC, updated_at DESC')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->with([
                        'days'=>function($q) {
                            return $q->select(['id', 'rid', 'name', 'body', 'meals', 'guides', 'transport']);
                        },
                        'updatedBy'=>function($q) {
                            return $q->select(['id', 'name'=>'nickname']);
                        },
                    ])
                    ->asArray()
                    ->all();

                $prevPage = $pagination->page == 0 ? false : $pagination->page - 1;
                $nextPage = $pagination->page + 1 >= $pagination->pageCount ? false : $pagination->page + 1;

                $result = [
                    'page'=>$pagination->page,
                    'prev_page'=>$prevPage,
                    'next_page'=>$nextPage,
                    'data'=>[],
                ];

                foreach ($thePrograms as $prog) {
                    $result['data'][] = [
                        'is'=>'prog',
                        'id'=>$prog['id'],
                        'title'=>$prog['title'],
                        'about'=>$prog['about'],
                        'start_date'=>date('j/n/Y', strtotime($prog['day_from'])),
                        'pax_count'=>$prog['pax'],
                        'day_count'=>count(array_filter(explode(',', $prog['day_ids']))),
                        'op_code'=>$prog['op_status'] == 'op' ? $prog['op_code'] : '',
                        'updated_by_name'=>$prog['updatedBy']['name'],
                        'updated_at_time'=>date('j/n/Y', strtotime($prog['updated_at'])),
                    ];

                    $dayIdList = explode(',', $prog['day_ids']);
                    foreach ($dayIdList as $dayId) {
                        foreach ($prog['days'] as $day) {
                            if ($day['id'] == $dayId) {
                                $result['data'][] = [
                                    'is'=>'day',
                                    'id'=>$day['id'],
                                    'prog_id'=>$prog['id'],
                                    'title'=>$day['name'],
                                    'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                                    'meals'=>$day['meals'],
                                    'guides'=>$day['guides'],
                                    'transport'=>$day['transport'],
                                ];
                            }
                        }
                    }
                }                
            } else {
                exit;
                // All programs
                $theDays = \common\models\Day::find()
                    ->select(['id', 'name', 'meals', 'body'])
                    ->andWhere(['like', 'name', $searchFor])
                    ->orderBy('name')
                    ->limit(30)
                    ->asArray()
                    ->all();
                $result = [];
                foreach ($theDays as $day) {
                    $result[] = [
                        'id'=>$day['id'],
                        'title'=>$day['name'],
                        'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                        'meals'=>$day['meals'],
                    ];
                }
            }
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }

        if ($action == 'edit-day' && Yii::$app->request->isAjax) {
            if (isset($_POST['Day']['id']) && $_POST['Day']['id'] != 0) {
                $theDay = Day::findOne($_POST['Day']['id']);
                $theDay->scenario = 'day/u';
            } else {
                $theDay = new Day;
                $theDay->scenario = 'day/c';
            }
            if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
                // \Yii::$app->response->format = Response::FORMAT_JSON;
                // \fCore::expose($_POST);
                // \fCore::expose($theDay);
                // echo 'ID=', $theDay->id; return false;
                if ($theDay->save(false)) {
                    return 'Success';
                } else {
                    throw new HttpException (401, 'Error saving data');
                }
            }
        }

        if ($action == 'sort-day') {
            if (isset($_POST['ngay']) && in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) {
                $sql = 'UPDATE at_ct SET day_ids = :di WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':di'=>implode(',', $_POST['ngay']), ':id'=>$theProduct['id']])->execute();
                return true;
            } else {
                throw new HttpException(403, 'Access denied.');
            }
        }

        if ($action == 'delete-day' && isset($_POST['day']) && in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) {
            // Delete day
            $sql = 'DELETE FROM at_days WHERE rid=:id AND id=:day LIMIT 1';
            Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id'], ':day'=>$dayIdList[$_POST['day']]])->execute();
            // day = cnt thu tu ngay, khong phai ID
            unset($dayIdList[$_POST['day']]);
            // Save product
            $sql = 'UPDATE at_ct SET day_ids=:di WHERE id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProduct['id']])->execute();
            return true;   
        }

        // Add blank after
        if ($action == 'add-blank-day' && isset($_POST['at']) && in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) {
            $theDay->created_at = NOW;
            $theDay->created_by = USER_ID;
            $theDay->updated_at = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->rid = $theProduct['id'];
            $theDay->meals = '---';
            $theDay->name = '( no title )';
            $theDay->body = '';
            $theDay->note = '';
            $theDay->image = '';
            if ($theDay->save(false)) {
                array_splice($dayIdList, (int)$_POST['at'], 0, $theDay->id);
            
                // Save product
                $sql = 'UPDATE at_ct SET day_ids=:di WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProduct['id']])->execute();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['id'=>$theDay->id];
            }

            throw new HttpException(401, 'Could not add day.');
        }

        // Chua co code
        if ($theTour && $theProduct['op_code'] != $theTour['code']) {
            Yii::$app->db->createCommand()
                ->update('at_ct', [
                    'op_status'=>'op',
                    'op_code'=>$theTour['code'],
                    'op_name'=>$theTour['name'],
                    ], ['id'=>$id])
                ->execute();
        }

        $metaData = [];
        $sql = 'SELECT * FROM metas WHERE rtype="product" AND rid=:id AND SUBSTRING(name,1,3)="td/" ORDER BY name';
        $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id']])->queryAll();

        foreach ($metas as $meta) {
            $items = explode('|', $meta['v']);
            if (count($items) == 4) {
                $metaData[] = $items;
            }
        }

        return $this->render($theProduct['offer_type'] == 'b2b-prod' ? 'product_r_b2bprod' : 'product_r', [
            'theProduct'=>$theProduct,
            'theDay'=>$theDay,
            'theDays'=>$theDays,
            'extraDays'=>$extraDays ?? [],
            'theCases'=>$theCases,
            'theTour'=>$theTour,
            'metaData'=>$metaData,
        ]);
    }

    // Mot so thao tac khi lam ct
    public function actionRr($action = '', $ct = 0, $id = 0)
    {
        $theProduct = Product::find()
            ->where(['id'=>$ct])
            ->with(['days'])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found');
        }

        if (!in_array(USER_ID, [1, $theProduct['created_at']])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($action == 'day-add-blank-after') {
            if ($id == 0) {
                die('ID NOT FOUND');
            }
            $newDay = new Day;
            $newDay->created_at = NOW;
            $newDay->created_by = USER_ID;
            $newDay->updated_at = NOW;
            $newDay->updated_by = USER_ID;
            $newDay->rid = $theProduct['id'];
            $newDay->meals = '---';
            if ($newDay->save(false)) {
                $dayIdNewList = [$newDayId];
                foreach ($dayIdList as $id) {
                    $dayIdNewList[] = $id;
                }
                $dayIdNew = implode(',', $dayIdNewList);

                $theProduct->day_ids = $dayIdNew;
                $theProduct->save(false);


                return $this->redirect('@web/products/r/'.$theProduct['id'].'#ngay-'.$newDay->id);
            }
        }
    }

    public function actionOp($id = 0)
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with(['bookings', 'bookings.pax'])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found');
        }

        // Tim tour neu co
        $theTour = Tour::find()
            ->where(['ct_id'=>$id])
            ->one();

        if (!$theTour) {
            return $this->render('products_op_nop', [
                'theProduct'=>$theProduct,
            ]);
        } else {
            return $this->redirect('@web/tours/r/'.$theTour['id']);
        }
    }

    public function actionSb($id = 0)
    {
        // The product
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        // The booking
        $theBooking = Booking::find()
            ->where(['product_id'=>$id])
            ->with([
                'createdBy',
                'updatedBy',
                'product',
                'product.tour',
                'product.days',
                'case',
                'case.owner',
                'invoices'=>function($q) {
                    return $q->orderBy('due_dt');
                },
                'payments',
                'people',
            ])
            ->asArray()
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        return $this->redirect(['booking/r', 'id'=>$theBooking['id']]);

        $bookingOwner = User::find()
            ->where(['id'=>$theBooking['created_by']])
            ->asArray()
            ->one();

        if (isset($theBooking['product']['tour']['id'])) {
            $tourPeople = Yii::$app->db
                ->createCommand('SELECT u.email, u.fname, u.lname FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:id', [':id'=>$theBooking['product']['tour']['id']])
                ->queryAll();
        }

        $theInvoice = new Invoice();

        $thePayment = new Payment;      
        $thePayment->scenario = 'payments_c';

        if ($thePayment->load(Yii::$app->request->post()) && $thePayment->validate()) {

            $thePayment->booking_id = $theBooking['id'];
            $thePayment->created_at = NOW;
            $thePayment->created_by = USER_ID;
            $thePayment->updated_at = NOW;
            $thePayment->updated_by = USER_ID;
            $thePayment->status = 'on';

            if ($thePayment->save(false)) {
                if ($bookingOwner) {
                    $args = [
                        ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                        ['to', $bookingOwner['email'], $bookingOwner['lname'], $bookingOwner['fname']],
                        ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        // ['attachment', 'filePath', 'fileName'],
                    ];
                    if (isset($tourPeople) && !empty($tourPeople)) {
                        foreach ($tourPeople as $user) {
                            $args[] = ['cc', $user['email'], $user['lname'], $user['fname']];
                        }
                    }
                    $this->mgIt(
                        '[ims] Payment received: '.$thePayment['ref'].' / '.$thePayment['method'].' / '.number_format($thePayment['amount'], 0).' '.$thePayment['currency'],
                        '//payment_received',
                        [
                            'thePayment'=>$thePayment,
                            'theBooking'=>$theBooking,
                        ],
                        $args
                    );
                }

                Yii::$app->session->setFlash('success', 'Payment has been added: '.number_format($thePayment['amount'], 2).' '.$thePayment['currency']);
                return $this->redirect('@web/bookings/r/'.$theBooking['id']);
            }
        }

        // Delete pax from booking
        if (isset($_GET['action']) && $_GET['action'] == 'delete-user-booking' && isset($_GET['user_id'])) {
            // Huan, CSKH
            if (in_array(USER_ID, [1, 7756, 9881, 1351])) {
                Yii::$app->db->createCommand()
                    ->delete('at_booking_user', [
                        'booking_id'=>$theBooking['id'],
                        'user_id'=>$_GET['user_id'],
                    ])
                    ->execute();
                return $this->redirect('@web/bookings/r/'.$theBooking['id']);
            }
        }

        // Cancel pax from booking
        if (isset($_GET['action']) && $_GET['action'] == 'cancel-user-booking' && isset($_GET['user_id'])) {
            // Huan, CSKH
            if (in_array(USER_ID, [1, 7756, 9881, 1351])) {
                Yii::$app->db->createCommand()
                    ->update('at_booking_user',
                        [
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'canceled',
                        ], [
                        'booking_id'=>$theBooking['id'],
                        'user_id'=>$_GET['user_id'],
                        ]
                    )
                    ->execute();
                return $this->redirect('@web/bookings/r/'.$theBooking['id']);
            }
        }

        // Add pax
        if (isset($_POST['action']) && $_POST['action'] == 'add-pax' && isset($_POST['name'])) {
            // Yii::$app->session->remove('searchUsers');
            $name = trim($_POST['name']);
            if ((int)$name > 0) {
                $theUsers = User::find()
                    ->where(['id'=>$name])
                    ->all();
            } elseif (false !== strpos($name, '@')) {
                $theUsers = User::findBySql('SELECT u.* FROM persons u, at_meta m WHERE m.rtype="user" AND m.rid=u.id AND m.k="email" AND m.v=:email', [':email'=>$name])
                    ->asArray()
                    ->all();
            } else {
                $theUsers = User::find()
                    ->where(['name'=>$name])
                    ->orWhere('CONCAT(fname, " ", lname)=:name', [':name'=>$name])
                    ->orWhere('CONCAT(lname, " ", fname)=:name', [':name'=>$name])
                    ->asArray()
                    ->all();
            }
            if (!$theUsers) {
                if (strpos($name, ' ') !== false && strlen($name) > 6) {
                    // Add pax if this is a name First Last
                    $newUser = new User;
                    $newUser->created_at = NOW;
                    $newUser->created_by = USER_ID;
                    $newUser->uo = NOW;
                    $newUser->ub = USER_ID;
                    $newUser->status = 'on';
                    $newUser->name = $name;
                    if ($newUser->save(false)) {
                        Yii::$app->db->createCommand()
                            ->insert('at_booking_user', [
                                'created_at'=>NOW,
                                'created_by'=>USER_ID,
                                'updated_at'=>NOW,
                                'updated_by'=>USER_ID,
                                'booking_id'=>$theBooking['id'],
                                'user_id'=>$newUser['id'],
                                ])
                            ->execute();
                        //return $this->redirect('@web/users/u/'.$newUser['id']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'User not found: #'.$name. '. A new pax name must be of format "First Last" and longer than 6 characters.');
                }
            } else {
                if (count($theUsers) == 1) {
                    Yii::$app->db->createCommand()
                        ->insert('at_booking_user', [
                            'created_at'=>NOW,
                            'created_by'=>USER_ID,
                            'updated_at'=>NOW,
                            'updated_by'=>USER_ID,
                            'booking_id'=>$theBooking['id'],
                            'user_id'=>$theUsers[0]['id'],
                            ])
                        ->execute();
                } else {
                    $searchUsers = $theUsers;
    if (!empty($searchUsers)) {
        echo '<div class="alert alert-info"><strong>The following users were found with same name / email</strong>';
        foreach ($searchUsers as $user) {
            echo '<br>ID: <a href="/users/r/', $user['id'], '">', $user['id'], '</a> | Name: ', $user['fname'], ' / ', $user['fname'], ' (', $user['name'], ')';
        }
        echo '</div>';
        exit;
        die('Insert one of user IDs above or add new user by adding a plus sign before name, eg. "+Nguyen Van A"');
    }
                    Yii::$app->session->set('searchUsers', $theUsers);
                }
            }
            return $this->redirect('@web/bookings/r/'.$theBooking['id']);
        }

        $thePeople = Yii::$app->db->createCommand('SELECT u.id, u.fname, u.lname, u.byear, u.email, u.gender, u.country_code, u.name, bu.status FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.booking_id=:id ORDER BY bu.status', [':id'=>$theBooking['id']])
            ->queryAll();

        $methodList = Yii::$app->db->createCommand('SELECT method FROM at_payments GROUP BY method ORDER BY method')
            ->queryAll();

        return $this->render('bookings_r', [
            'theProduct'=>$theProduct,
            'theBooking'=>$theBooking,
            'thePeople'=>$thePeople,
            'theInvoice'=>$theInvoice,
            'thePayment'=>$thePayment,
            'methodList'=>$methodList,
        ]);
    }

    // Update B2b prod
    private function updateB2bProd($id = 0)
    {
        $theProduct = Product::findOne($id);
        if (!in_array(USER_ID, [1, $theProduct['created_by'], $theProduct['updated_by']])) {
            throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        $theProduct->scenario = 'product/u/b2bprod';
        if ($theProduct->load(Yii::$app->request->post()) && $theProduct->validate()) {
            $theProduct->updated_at = NOW;
            $theProduct->updated_by = USER_ID;
            if ($theProduct->save(false)) {
                Yii::$app->session->setFlash('success', 'Product has been updated: '.$theProduct['title']);
                return $this->redirect('@web/products/r/'.$theProduct['id']);
            }
        }

        $theDays = [];

        return $this->render('product_u_b2bprod', [
            'theProduct'=>$theProduct,
            'theDays'=>$theDays,
        ]);
    }

    public function actionU($id = 0)
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with([
                'bookings'=>function($q) {
                    return $q->select(['id', 'product_id', 'case_id']);
                },
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'owner_id']);
                },
                ])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($theProduct['owner'] == 'si') {
            return $this->redirect('/b2b/programs/u/'.$id);
        }

        // 161003 Allows any of case owners to edit CT
        $editableList = [1, 4432, $theProduct['created_by'], $theProduct['updated_by']];
        foreach ($theProduct['bookings'] as $booking) {
            $editableList[] = $booking['case']['owner_id'];
        }

        if (!in_array(USER_ID, $editableList)) {
            // throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        if ($theProduct['offer_count'] > 0) {
            // Since 140412
            // throw new HttpException(403, 'Access denied. Cannot edit proposed itinerary.');
        }

        $theProduct->scenario = 'products_u';

        $days = Day::find()
            ->select(['id', 'name', 'meals'])
            ->where(['rid'=>$theProduct['id']])
            ->all();
        $theDays = [];
        $dayIds = explode(',', $theProduct['day_ids']);
        if (!empty($dayIds)) {
            foreach ($dayIds as $id) {
                foreach ($days as $day) {
                    if ($day['id'] == $id) {
                        $theDays[] = $day;
                    }
                }
            }
        }

        if ($theProduct->load(Yii::$app->request->post()) && $theProduct->validate()) {
            $theProduct->updated_at = NOW;
            $theProduct->updated_by = USER_ID;
            if ($theProduct->save(false)) {
                Yii::$app->session->setFlash('success', 'Product has been updated: '.$theProduct['title']);
                return $this->redirect('@web/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('product_u', [
            'theProduct'=>$theProduct,
            'theDays'=>$theDays,
        ]);

        

/*
        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@web').'/upload/web/'.substr($model->created_at, 0, 7));
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').'/upload/web/'.substr($model->created_at, 0, 7));
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', 'web/'.substr($model->created_at, 0, 7));
        Yii::$app->session->set('ckfinder_resource_name', 'web');
*/
    }

    public function actionUOp($id = 0) {
        $theProduct = Product::findOne($id);
        if (!$theProduct) {
            throw new HttpException(404, 'Product not found');
        }

        $theProduct->scenario = 'products_u-op';

        if ($theProduct->load(Yii::$app->request->post())) {
            if ($theProduct->save()) {
                Yii::$app->session->setFlash('success', 'Product has been updated: '.$theProduct['name']);
                return $this->redirect('@web/products/op/'.$theProduct['id']);
            }
        }

        return $this->render('products_u-op', [
            'theProduct'=>$theProduct,
        ]);
    }

    // Delete a product
    public function actionD($id = 0) {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with(['bookings'=>function($q){
                return $q->select(['id', 'product_id']);
                }
            ])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($theProduct['created_by'] != USER_ID) {
            throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        if (!empty($theProduct['bookings'])) {
            throw new HttpException(403, 'Access denied. There are existing bookings for this product. You have to delete those bookings first.');
        }

        if (!$theProduct['op_status'] == 'op') {
            throw new HttpException(403, 'Access denied. This product is operational. You can only cancel its operation.');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            // Delete days
            Day::deleteAll(['rid'=>$theProduct['id']]);
            // Delete pdf file
            @unlink(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$rId.'.pdf');
            // Delete new upload files
            if (file_exists(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'])) {
                \yii\helpers\FileHelper::removeDirectory(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id']);
            }
            // Delete product
            $theProduct->delete();

            Yii::$app->session->setFlash('success', 'Product has been deleted: '.$theProduct['title']);
            return $this->redirect('@web/products?ub='.USER_ID);
        }

        return $this->render('products_d', [
            'theProduct'=>$theProduct
        ]);
    }

    public function actionDownload($id = 0, $type = '', $file = '')
    {
        // Download PDF file
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($type == 'pdf' && $file != '') {
            $filePath = Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/pdf/'.$file;
            $fileName = $file;
            if (!file_exists($filePath)) {
                throw new HttpException(404, 'File not found.');
            }
            return Yii::$app->response->sendFile($filePath, $fileName);
        }

        if ($type == 'image' && $file != '') {
            $filePath = Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/image/'.$file;
            $fileName = $file;
            if (!file_exists($filePath)) {
                throw new HttpException(404, 'File not found.');
            }
            return Yii::$app->response->sendFile($filePath, $fileName);
        }

        if ($type == 'excel' && $file != '') {
            $filePath = Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/excel/'.$file;
            $fileName = $file;
            if (!file_exists($filePath)) {
                throw new HttpException(404, 'File not found.');
            }
            return Yii::$app->response->sendFile($filePath, $fileName);
        }

        $filePath = Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProduct['id'].'.pdf';
        if (!file_exists($filePath)) {
            throw new HttpException(404, 'Product file (PDF) not found.');
        }

        $fileName = $theProduct['title'].' '.date('Ymd-Hi', strtotime($theProduct['created_at'])).'.pdf';
        return Yii::$app->response->sendFile($filePath, $fileName);
    }

    public function actionCopy($id = 0)
    {
        $theProduct = Product::find()
            ->where(['id'=>$id])
            ->with(['days'])
            ->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        $newProduct = new Product;
        $newProduct->scenario = 'products_copy';

        if ($newProduct->load(Yii::$app->request->post()) && $newProduct->validate()) {
            $newProduct->offer_type = $theProduct->offer_type == 'b2b-prod' ? 'agent' : $theProduct->offer_type;
            $newProduct->about = $theProduct->about;
            $newProduct->day_count = 0;//$theProduct->day_count;
            $newProduct->day_from = $theProduct->day_from;
            $newProduct->pax = $theProduct->pax;
            $newProduct->intro = $theProduct->intro;
            $newProduct->esprit = $theProduct->esprit;
            $newProduct->points = $theProduct->points;
            $newProduct->conditions = $theProduct->conditions;
            $newProduct->others = $theProduct->others;
            $newProduct->tags = $theProduct->tags;
            $newProduct->promo = $theProduct->promo;
            $newProduct->price = $theProduct->price;
            $newProduct->price_unit = $theProduct->price_unit;
            $newProduct->price_for = $theProduct->price_for;
            $newProduct->price_until = $theProduct->price_until;
            $newProduct->prices = $theProduct->prices;
            $newProduct->image = $theProduct->image;
            $newProduct->language = $theProduct->language;
            $newProduct->owner = $theProduct->owner;

            $newProduct->created_at = NOW;
            $newProduct->created_by = USER_ID;
            $newProduct->updated_at = NOW;
            $newProduct->updated_by = USER_ID;
            $newProduct->status = 'on';
            $newProduct->op_status = 'nop';
            $newProduct->op_code = '';
            $newProduct->op_name = '';
            $newProduct->uid = Yii::$app->security->generateRandomString();
            $newProduct->offer_count = 0;
            $newProduct->day_ids = '';

            if ($newProduct->save(false)) {
                // Save days
                $dayIdList = explode(',', $theProduct['day_ids']);
                if (!$dayIdList) {
                    $dayIdList = [];
                }

                $newDayIds = ',';
                $newDayCount = 0;
                foreach ($dayIdList as $id) {
                    foreach ($theProduct['days'] as $day) {
                        if ($day['id'] == $id) {
                            $newDay = new Day;
                            $newDay->scenario = 'products_copy';

                            $newDay->created_at = NOW;
                            $newDay->created_by = USER_ID;
                            $newDay->updated_at = NOW;
                            $newDay->updated_by = USER_ID;
                            $newDay->status = 'on';
                            $newDay->rid = $newProduct['id'];

                            // TODO: day as option

                            $newDay->name = $day['name'];
                            $newDay->step = $day['step'];
                            $newDay->day = $day['day'];
                            $newDay->body = $day['body'];
                            $newDay->image = $day['image'];
                            $newDay->meals = $day['meals'];
                            $newDay->guides = $day['guides'];
                            $newDay->transport = $day['transport'];
                            $newDay->note = $day['note'];

                            if ($newDay->save(false)) {
                                $newDayIds .= ','.$newDay['id'];
                                $newDayCount ++;
                            }
                        }
                    }
                }

                $newProduct->day_ids = trim($newDayIds, ',');
                $newProduct->day_count = $newDayCount;
                $newProduct->save(false);

                $metaData = [];
                $sql = 'SELECT * FROM at_meta WHERE rtype="product" AND rid=:id AND SUBSTRING(k,1,3)="td/" ORDER BY k';
                $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProduct['id']])->queryAll();

                foreach ($metas as $meta) {
                    Yii::$app->db->createCommand()->insert('at_meta', [
                        'uo'=>NOW,
                        'ub'=>USER_ID,
                        'rtype'=>'product',
                        'rid'=>$newProduct['id'],
                        'k'=>$meta['k'],
                        'v'=>$meta['v'],
                    ])->execute();
                }

                return $this->redirect('@web/products/r/'.$newProduct['id']);
            }
        }

        return $this->render('products_copy', [
            'theProduct'=>$theProduct,
            'newProduct'=>$newProduct,
        ]);
    }

    public function actionUpload($id = 0, $action = '', $type = '', $file = '')
    {
        // Upload PDF file
        $theProduct = Product::find()->where(['id'=>$id])->asArray()->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($action == 'delete' && $type == 'oldpdf') {
            if (!in_array(USER_ID, [1, $theProduct['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProduct['id'].'.pdf');
            return $this->redirect(DIR.URI);
        }

        if ($action == 'delete' && $type == 'pdf' && $file != '') {
            if (!in_array(USER_ID, [1, $theProduct['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/pdf/'.$file);
            return $this->redirect(DIR.URI);
        }

        if ($action == 'delete' && $type == 'image' && $file != '') {
            if (!in_array(USER_ID, [1, $theProduct['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/image/'.$file);
            return $this->redirect(DIR.URI);
        }

        if ($action == 'delete' && $type == 'excel' && $file != '') {
            if (!in_array(USER_ID, [1, $theProduct['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'].'/excel/'.$file);
            return $this->redirect(DIR.URI);
        }

        $model = new \app\models\ProductUploadForm;
        $model->productId = $theProduct['id'];
        if (Yii::$app->request->isPost) {
            $model->pdfFiles = UploadedFile::getInstances($model, 'pdfFiles');
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $model->excelFiles = UploadedFile::getInstances($model, 'excelFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->redirect('/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('products_upload', [
            'theProduct'=>$theProduct,
            'model'=>$model,
        ]);
    }

    public function actionPrint($id = 0)
    {
        // Make print version for Word/Pdf
        $theProduct = Product::find()->where(['id'=>$id])->with(['days', 'createdBy'])->asArray()->one();

        if (!$theProduct) {
            throw new HttpException(404, 'Product not found.');
        }

        return $this->renderPartial('products_print', [
            'theProduct'=>$theProduct,
        ]);
    }
}
