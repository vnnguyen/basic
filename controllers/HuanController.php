<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Customer;
use common\models\Booking;
use common\models\Kase;
use common\models\Day;
use common\models\Dv;
use common\models\Country;
use common\models\Person;
use common\models\Ct;
use common\models\Tour;
use common\models\TourStats;
use common\models\Product;

use kartik\mpdf\Pdf;

class HuanController extends MyController
{
    // Close Ly's open cases
    public function actionLysOpenCases()
    {

        $cases = Kase::find()
            ->select(['id', 'name'])
            ->where(['deal_status'=>'won', 'status'=>'open', 'owner_id'=>5046])
            ->orderBy('id DESC')
            ->with([
                'bookings'=>function($q){
                    return $q->where(['status'=>'won']);
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'op_name', 'op_code', 'day_from', 'day_count']);
                },
                ])
            ->asArray()
            ->all();
        foreach ($cases as $case) {
            echo '<br>ID=', $case['id'], ' NAME=', $case['name'];
            foreach ($case['bookings'] as $booking) {
                echo ' CODE=', $booking['product']['op_code'];
                echo ' DAYS=', $booking['product']['day_count'];
                echo ' FROM=', $booking['product']['day_from'];
                $dayUntil = strtotime('+ '.$booking['product']['day_count'].' days '.$booking['product']['day_from']);
                echo ' UNTIL=', date('Y-m-d', $dayUntil);
                if ($dayUntil < strtotime(NOW)) {
                    echo ' <span style="color:red">PAST</span>';
                    // Close case
                    $theCase = Kase::findOne($case['id']);
                    if (!$theCase) {
                        die('NO CASE');
                    }
                    $theCase->why_closed = 'won';
                    $theCase->updated_at = NOW;
                    $theCase->updated_by = 5046;
                    $theCase->status = 'closed';
                    $theCase->status_date = NOW;
                    $theCase->closed = NOW;
                    $theCase->save(false);
                    $this->mgIt(
                        'ims | Case "'.$theCase['name'].'" was closed by Ly for reason: '.$theCase['why_closed'],
                        '//mg/cases_close',
                        [
                            'theCase'=>$theCase,
                        ],
                        [
                            ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                            // ['to', 'ngo.hang@amica-travel.com', 'Hằng', 'Ngô'],
                        ]
                    );
                    Yii::$app->db->createCommand()
                        ->insert('at_sysnotes', [
                            'created_at'=>NOW,
                            'user_id'=>5046,
                            'action'=>'kase/close',
                            'rtype'=>'case',
                            'rid'=>$theCase['id'],
                            'uri'=>'cases/close/'.$theCase['id'],
                            'ip'=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP(),
                            'info'=>'Auto closed : won',
                        ])
                        ->execute();
                    Yii::$app->db->createCommand()
                        ->delete('at_email_mapping', ['case_id'=>$theCase['id']])
                        ->execute();
                    // exit;
                }
            }
        }
    }

    // Test dependent dropdown dv
    public function actionTestDv()
    {
        $theForm = new \app\models\HuanTestDvForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            \fCore::expose($theForm);
        }
        return $this->render('huan_test-dv', [
            'theForm'=>$theForm,
            ]);
    }

    // Returns json
    public function actionAjax($action = '', $return = '')
    {
        if ($action == 'ncc-dv') {
            if (isset($_POST['depdrop_parents'][0])) {
                $ncc_id = $_POST['depdrop_parents'][0];
                $theDvx = Dv::find()
                    ->where(['venue_id'=>$ncc_id])
                    ->orderBy('name')
                    ->asArray()
                    ->all();
                $selected = 0;
                //$output = '[';
                $output = [];
                foreach ($theDvx as $dv) {
                    if ($selected == 0) {
                        $selected = $dv['id'];
                    }
                    $output[] = ['id'=>$dv['id'], 'name'=>$dv['name']];
                }
                echo json_encode(['output'=>$output, 'selected'=>$selected]);
                return;
            }
        }
    }

    // Test standard PDF template
    public function actionPdf()
    {
        $this->layout = 'pdf_print';
        $html = $this->render('huan_denghi');
        $mpdf = new \mPDF();
        $mpdf->SetTitle('Mẫu xuất PDF - 170114');
        $mpdf->SetAuthor('Amica Travel'); // TODO my company's name
        $mpdf->SetSubject('Mẫu xuất PDF - 170114');
        // LOAD a stylesheet
        // $stylesheet = file_get_contents(Yii::getAlias('@app').'/mpdfstyleColliers.css');
        // $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
        if (1 == USER_ID) {
            $mpdf->SetWatermarkText("SAMPLE");
            $mpdf->showWatermarkText = true;
        }
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
        // $mpdf->SetWatermarkImage (Yii::getAlias('@app').'/cvf-footer.png', 0.8, [30, 30], [180, 270]);
        // $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);

        $fileName = 'SAMPLE.pdf';
        // $mpdf->Output(Yii::getAlias('@app').'/storage/'.ACCOUNT_ID.'/val-pdf/'.substr($theVal->created_dt, 0, 7).'/'.$theVal->id.'/'.$fileName, 'F');
        $mpdf->Output();
    }

    // Test standard PDF template
    public function actionPdf2()
    {
        $html = $this->renderPartial('huan_pdf-template', [
            ]);
        $mpdf = new \mPDF();
        $mpdf->SetTitle('Mẫu xuất PDF - 170114');
        $mpdf->SetAuthor('Amica Travel'); // TODO my company's name
        $mpdf->SetSubject('Mẫu xuất PDF - 170114');
        // LOAD a stylesheet
        // $stylesheet = file_get_contents(Yii::getAlias('@app').'/mpdfstyleColliers.css');
        // $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
        if (1 == USER_ID) {
            $mpdf->SetWatermarkText("SAMPLE");
            $mpdf->showWatermarkText = true;
        }
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
        // $mpdf->SetWatermarkImage (Yii::getAlias('@app').'/cvf-footer.png', 0.8, [30, 30], [180, 270]);
        // $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);

        $fileName = 'SAMPLE.pdf';
        // $mpdf->Output(Yii::getAlias('@app').'/storage/'.ACCOUNT_ID.'/val-pdf/'.substr($theVal->created_dt, 0, 7).'/'.$theVal->id.'/'.$fileName, 'F');
        $mpdf->Output();
    }


	// Split tout notes fields
    public function actionTourNotes()
    {
    	$tnx = TourNote::find()
    		->asArray()
    		->all();
    	foreach ($tnx as $tn) {
    		$lines = explode(PHP_EOL, $tn['body']);
    		
    	}
        return $this->render('huan_tour-notes');
    }

    // Test multiple meta fields
    public function actionDynamicFormFields()
    {
        return $this->render('huan_dynamic-form-fields');
    }

    // Anything to test for Huan
    public function actionAdwordsGt($year = 2016)
    {
        // Khach duoc gioi thieu nam $year boi khach Adwords
        $theReferrals = \common\models\Referral::find()
            ->from('at_referrals r')
            ->select(['k.id', 'k.name', 'k.created_at', 'r.user_id', 'r.case_id'])
            ->innerJoinWith('case k')
            ->where('YEAR(k.created_at)=:year', [':year'=>$year])
            ->with([
                'case'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'case.bookings'=>function($q){
                    return $q->select(['id', 'status', 'product_id', 'case_id']);
                },
                'case.bookings.product'=>function($q){
                    return $q->select(['id', 'op_code']);
                },
                'user'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'user.cases'=>function($q){
                    return $q->select(['id', 'name', 'created_at'])->where(['web_referral'=>'ad/adwords']);
                },
                'user.cases.bookings'=>function($q){
                    return $q->select(['id', 'status', 'product_id', 'case_id']);
                },
                'user.cases.bookings.product'=>function($q){
                    return $q->select(['id', 'op_code']);
                },
                ])
            ->asArray()
            ->all();

        $cnt = 0;
        echo '<h4>DANH SACH HO SO DUOC GIOI THIEU NAM '.$year.' BOI KHACH ADWORDS</h4>';
        echo '<table border="1" cellpadding="4" cellspacing="4"><thead><tr><th>#</th><th>NAM</th><th>HS NGUOI GT</th><th>TEN NGUOI GT</th><th>TOUR NGUOI GT</th><th>HS DUOC GT</th><th>TOUR DUOC GT</th></tr></thead><tbody>';
        foreach ($theReferrals as $ref) {
            if (!empty($ref['user']['cases'])) {
                foreach ($ref['user']['cases'] as $case) {
                    echo '<tr>';
                    echo '<td>', ++$cnt, '</td>';
                    echo '<td>', substr($case['created_at'], 0, 4), '</td>';
                    echo '<td>', $case['name'], '</td>';
                    echo '<td>', $ref['user']['name'], '</td>';
                    echo '<td>';
                    foreach ($case['bookings'] as $booking) {
                        if ($booking['status'] == 'won') {
                            echo '<div>', $booking['product']['op_code'], '</div>';
                        }
                    }
                    echo '</td>';
                    echo '<td>';
                        echo '<div>', $ref['name'].'</div>';
                    echo '</td>';
                    echo '<td>';
                        foreach ($ref['case']['bookings'] as $booking) {
                            if ($booking['status'] == 'won') {
                                echo '<div>', $booking['product']['op_code'], '</div>';
                            }
                        }
                    echo '</td>';
                    echo '</tr>';

                }
            }
        }
        echo '</tbody></table>';

        // Khach Adwords gioi thieu khach khac
        $theCases = \common\models\Kase::find()
            ->select(['id', 'name', 'created_at'])
            ->with([
                'people',
                'people.refCases'=>function($q) use ($year){
                    return $q->where('YEAR(created_at)>=:year', [':year'=>$year]);
                },
                'people.refCases.bookings',
                'people.refCases.bookings.product',
                'bookings',
                'bookings.product',
                ])
            ->where(['web_referral'=>'ad/adwords'])
            ->andWhere('YEAR(created_at)=:year', [':year'=>$year])
            ->asArray()
            ->all();

        $cnt = 0;
        echo '<h4>DANH SACH HO SO DUOC KHACH ADWORDS NAM '.$year.' GIOI THIEU</h4>';
        echo '<table border="1" cellpadding="4" cellspacing="4"><thead><tr><th>#</th><th>NAM</th><th>HS NGUOI GT</th><th>TEN NGUOI GT</th><th>TOUR NGUOI GT</th><th>HS DUOC GT</th><th>TOUR DUOC GT</th></tr></thead><tbody>';
        foreach ($theCases as $case) {
            foreach ($case['people'] as $person) {
                if (!empty($person['refCases'])) {
                    echo '<tr>';
                    echo '<td>', ++$cnt, '</td>';
                    echo '<td>', substr($case['created_at'], 0, 4), '</td>';
                    echo '<td>', $case['name'], '</td>';
                    echo '<td>', $person['name'], '</td>';
                    echo '<td>';
                    foreach ($case['bookings'] as $booking) {
                        if ($booking['status'] == 'won') {
                            echo '<div>', $booking['product']['op_code'], '</div>';
                        }
                    }
                    echo '</td>';
                    echo '<td>';
                    foreach ($person['refCases'] as $refCase) {
                        echo '<div>', $refCase['name'].'</div>';
                    }
                    echo '</td>';
                    echo '<td>';
                    foreach ($person['refCases'] as $case) {
                        foreach ($case['bookings'] as $booking) {
                            if ($booking['status'] == 'won') {
                                echo '<div>', $booking['product']['op_code'], '</div>';
                            }
                        }
                    }
                    echo '</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</tbody></table>';
        //\fCore::expose($theCases);
    }

            function evaluate_array($s, $sum){
            $arr = array();
            $arr[0] = 0;
            //Psuedo code provided by http://www.cs.utsa.edu/~wagner/CS3343/ss/ss.html
            foreach ($s as $k => $v) {
                echo "testing: " . $v . "<br/>";
                for( $i = 0; $i <= $sum; $i++){
                    if( isset($arr[$i]) ){
                        if( $arr[$i] != $v && $i + $v < $sum){
                            if( !isset($arr[$i + $v]) ){
                                $arr[$i + $v] = $v;
                            }
                        }
                    }
                }
            }
            return $arr;
        }
        function solve($set, $sum, $needle){
            $stack = $this->evaluate_array($set, $sum);
            echo "Size: " . sizeof($stack) . "<br/><br/>";
            while ($needle > 0){
                echo $stack[$needle] . "<br/>";
                $needle -= $stack[$needle];
            }
        }
        function brute_force($set, $needle){
            global $gcount;
            $gcount++;
            $sub = array(
                'found' => false
                , 'vals' => array()
                );
            $workset = $set;
            foreach($workset as $k => $v){
                if( $v == $needle){
                    $sub['found'] = true;
                    array_push($sub['vals'], $v);
                    return $sub;
                }else{
                    array_shift($workset);
                    if( $needle - $v < 0){ return $sub; }
                    $r = $this->brute_force($workset, $needle - $v);
                    if( $r['found'] ){
                        array_push($r['vals'], $v);
                        return $r;
                    }
                }
            }
            return $sub;
        }
        function solve_bf($set, $needle){
            global $gcount;
            $sub = $this->brute_force($set, $needle);
            $sum = 0;
            foreach($sub['vals'] as $k => $v){
                echo $v . "<br/>";
                $sum += $v;
            }
            echo "<br/>Sum: " . $sum;
            echo "<br/># Recursions: " . $gcount;
        }
    // Subset sum for MongLTK 161027
    public function actionS3()
    {
       $mtime = microtime(); 
       $mtime = explode(" ",$mtime); 
       $mtime = $mtime[1] + $mtime[0]; 
       $starttime = $mtime; 

        set_time_limit(120);
        $gcount = 0;
        $set = array(18897109, 12828837, 9461105, 6371773, 5965343, 5946800, 5582170, 5564635, 5268860, 4552402, 4335391, 4296250, 4224851, 4192887, 3439809, 3279833, 3095313, 2812896, 2783243, 2710489, 2543482, 2356285, 2226009, 2149127, 2142508, 2134411);
        $needle = 100000000;
        $sum = 0;
        
        $set = array(13, 45, 23, 45, 11, 35, 46, 9, 98, 44, 1, 1, 1);
        $needle = 46;
        foreach ($set as $k => $v){
            $sum += $v;
        }

        //solve($set, $sum, $needle);
        $this->solve_bf($set, $needle);


       $mtime = microtime(); 
       $mtime = explode(" ",$mtime); 
       $mtime = $mtime[1] + $mtime[0]; 
       $endtime = $mtime; 
       $totaltime = ($endtime - $starttime); 
       echo "<br/><br/>This page was created in ".$totaltime." seconds."; 
    }

    // Cac mau quyet toan
    public function actionQt($template = 'tu')
    {
        $theInvoice = \common\models\Invoice::find()
            ->with(['createdBy', 'updatedBy', 'booking', 'booking.product', 'booking.product.tour'])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        if (!in_array($theInvoice['lang'], ['en', 'fr', 'vi'])) {
            $theInvoice['lang'] = 'en';
        }
        Yii::$app->language = $theInvoice['lang'];

        $content = $this->renderPartial('huan_qt_tu', [
            'theInvoice'=>$theInvoice,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 

            'marginLeft'=>16,
            'marginRight'=>16,
            'marginTop'=>16,
            'marginBottom'=>16,

            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            //'destination' => Pdf::DEST_DOWNLOAD,
            'filename'=>'INVOICE-'.$theInvoice['ref'].'.pdf',
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // 'cssFile'=>'@app/views/invoice/bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '
body, td, th {font-family:timesnewroman; font-size:17px;}
h1 {font-size:28px!important; margin:0 0 4px!important;}
table.nb, table.nb tbody, table.nb tr, table.nb td, table.nb th {border:0!important;}
table#pricetable {border:0!important; border-top:1px solid #BF4C9D!important;}
table.table-bordered td.nb {border:0!important;}
table.table-bordered td.br-0 {border-right:0!important;}
table.table-bordered td.bl-0 {border-left:0!important;}
table.table-bordered td.bb-0 {border-bottom:0!important;}
table.table-bordered td.bt-0 {border-top:0!important;}
            ', 
            // set mPDF properties on the fly
            'options' => [
                'title' => 'AMICA TRAVEL - INVOICE #'.$theInvoice['id']
            ],
            // call mPDF methods on the fly
            'methods' => [ 
                //'SetHeader'=>['INVOICE'], 
                //'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    // Diem tour guide
    public function actionNgoc($date = '', $view = 'guide', $region = '')
    {
        if ($date == '') {
            $date = '2016';
        }
        $andRegion = '';
        if ($region != '') {
            $andRegion = 'AND LOCATE("'.$region.'", regions)!=0';
        }
        if ($view == 'guide') {
            $sql = 'select gp.regions, day_from, op_name, op_code, tg.guide_name AS name, tg.points from at_profiles_tourguide gp, at_ct p, at_tour_guides tg where op_status ="op" and tg.tour_id=p.id and gp.user_id=tg.guide_user_id AND tg.points!="" AND SUBSTRING(p.day_from, 1, '.strlen($date).')="'.$date.'" '.$andRegion.' ORDER BY p.day_from';
        } elseif ($view == 'driver') {
            $sql = 'select dp.regions, day_from, op_name, op_code, tg.driver_name AS name, tg.points from at_profiles_driver dp, at_ct p, at_tour_drivers tg where op_status ="op" and tg.tour_id=p.id and dp.user_id=tg.driver_user_id AND tg.points!="" AND SUBSTRING(p.day_from, 1, '.strlen($date).')="'.$date.'" '.$andRegion.' ORDER BY p.day_from';
        } else {
            die('Invalid view');
        }
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $cnt = 0;
        echo '<p>SYNTAX: https://my.amicatravel.com/huan/ngoc?date=<em>2016|2016-06</em>&view=<em>guide|driver</em>&amp;region=<em>Miền Nam</em></p>';
        echo '<style>table {border-collapse:collapse;} td, th {border:1px solid #ccc;}</style>';
        echo '<table>';
        if ($view == 'guide') {
            echo '<tr><th></th><th>TOUR</th><th>START</th><th>GUIDE</th><th>REGION</th><th>010-239</th><th>240-309</th><th>310-374</th><th>375-400</th></tr>';
        } else {
            echo '<tr><th></th><th>TOUR</th><th>START</th><th>DRIVER</th><th>REGION</th><th>010-179</th><th>180-229</th><th>230-250</th></tr>';
        }
        foreach ($results as $tour) {
            $cnt ++;
            echo '<tr>';
            echo '<td>', $cnt, '</td>';
            echo '<td>', $tour['op_code'], ' - ', $tour['op_name'], '</td>';
            echo '<td>', date('j/n', strtotime($tour['day_from'])), '</td>';
            echo '<td>', $tour['name'], '</td>';
            echo '<td>', $tour['regions'], '</td>';
            if ($view == 'guide') {
            echo '<td>', $tour['points'] >= 10 && $tour['points'] <= 239 ? $tour['points'] : '' , '</td>';
            echo '<td>', $tour['points'] >= 240 && $tour['points'] <= 309 ? $tour['points'] : '' , '</td>';
            echo '<td>', $tour['points'] >= 310 && $tour['points'] <= 374 ? $tour['points'] : '' , '</td>';
            echo '<td>', $tour['points'] >= 375 && $tour['points'] <= 400 ? $tour['points'] : '' , '</td>';
            } else {
            echo '<td>', $tour['points'] >= 10 && $tour['points'] <= 179 ? $tour['points'] : '' , '</td>';
            echo '<td>', $tour['points'] >= 180 && $tour['points'] <= 229 ? $tour['points'] : '' , '</td>';
            echo '<td>', $tour['points'] >= 230 && $tour['points'] <= 250 ? $tour['points'] : '' , '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    // 160619 Sample days
    public function actionNm($name = '', $language = 'fr', $tags = '')
    {
        $sql = 'SELECT COUNT(*) FROM at_ngaymau WHERE language=:l';
        $count = Yii::$app->db->createCommand($sql, [':l'=>$language])->queryScalar();
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'=>25,
        ]);
        $sql2 = 'SELECT n.* FROM at_ngaymau n WHERE n.language=:l ORDER BY ngaymau_title LIMIT '.$pagination->offset.', '.$pagination->limit;
        $theDays = Yii::$app->db->createCommand($sql2, [':l'=>$language])->queryAll();

        return $this->render('huan_nm', [
            'pagination'=>$pagination,
            'name'=>$name,
            'language'=>$language,
            'tags'=>$tags,
            'theDays'=>$theDays,
        ]);
    }

    // 160616 Add old tours
    public function actionNewtour()
    {
        $theForm = new \app\models\HuanNewTourForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            //
            $existing = Tour::find()
                ->where(['code'=>$theForm['code']])
                ->one();
            if ($existing) {
                throw new HttpException(403, 'Existing!');                
            }

            $product = new Product;
            $product->status = 'on';
            $product->created_at = NOW;
            $product->created_by = $theForm['bh'];
            $product->updated_at = NOW;
            $product->updated_by = USER_ID;
            $product->title = 'Auto program for '.$theForm['code'];
            $product->day_from = $theForm['start_date'];
            $product->pax = $theForm['pax'];
            $product->day_count = $theForm['days'];
            $product->op_status = 'op';
            $product->op_code = $theForm['code'];
            $product->op_name = $theForm['name'];
            $product->op_finish = 'finish';
            $product->save(false);

            $tour = new Tour;
            $tour->uo = NOW;
            $tour->ub = USER_ID;
            $tour->status = 'on';
            $tour->ct_id = $product['id'];
            $tour->code = $theForm['code'];
            $tour->name = $theForm['name'];
            $tour->save(false);

            echo '<br>PRODUCT ID = ', $product['id'];

            $case = new Kase;
            $case->created_at = NOW;
            $case->created_by = $theForm['bh'];
            $case->updated_at = NOW;
            $case->updated_at = $theForm['bh'];
            $case->status = 'closed';
            $case->status_date = $theForm['start_date'];
            $case->opened = $theForm['start_date'];
            $case->closed = $theForm['start_date'];
            $case->why_closed = 'won';
            $case->ao = $theForm['start_date'];
            $case->language = 'fr';
            $case->is_b2b = 'no';
            $case->owner_id = $theForm['bh'];
            $case->deal_status = 'won';
            $case->name = 'Auto case for '.$theForm['code'];
            $case->save(false);

            $booking = new Booking;
            $booking->created_at = NOW;
            $booking->created_by = $theForm['bh'];
            $booking->updated_at = NOW;
            $booking->updated_at = $theForm['bh'];
            $booking->status = 'won';
            $booking->case_id = $case['id'];
            $booking->product_id = $product['id'];
            $booking->pax = $theForm['pax'];
            $booking->start_date = $theForm['start_date'];
            $booking->save(false);

            $stat = new TourStats;
            $stat->tour_id = $product['id'];
            $stat->tour_old_id = $tour['id'];
            $stat->countries = $theForm['destinations'];
            $stat->start_date = $theForm['start_date'];
            $stat->day_count = $theForm['days'];
            $stat->pax_count = $theForm['pax'];
            $stat->tour_code = $theForm['code'];
            $stat->tour_name = $theForm['name'];
            $stat->save(false);

            $ss = strtolower($theForm['code'].' - '.str_replace(' ', '', $theForm['name']));
            $ff = $theForm['code']. ' - '.$theForm['name'];
            $sql = 'INSERT INTO at_search (rtype, rid, search, found) VALUES ("tour", :id, :s, :f)';
            Yii::$app->db->createCommand($sql, [
                ':id'=>$tour['id'],
                ':s'=>$ss,
                ':f'=>$ff,
            ])->execute();
        }

        $staffList = Person::find()
            ->select(['id', 'CONCAT_WS(" ", lname, fname, email) AS name'])
            ->orderBy('lname, fname')
            ->where(['is_member'=>['yes', 'old']])
            ->asArray()
            ->all();
        return $this->render('huan_newtour', [
            'theForm'=>$theForm,
            'staffList'=>$staffList,
        ]);
    }

    // 160613 Thy wants list of customers who visited only Vietnam in 2015
    public function actionThyVietnamOnly2015()
    {
        $tours = Product::find()
            ->select(['id', 'op_name', 'op_code'])
            ->where(['op_status'=>'op'])
            ->andWhere('op_finish!="canceled"')
            ->andWhere('YEAR(day_from)=2015')
            ->andWhere('SUBSTRING(op_code,1,1)="F"')
            ->with([
                'days'=>function($q) {
                    return $q->select(['rid', 'name']);
                },
                'bookings'=>function($q) {
                    return $q->select(['id', 'product_id']);
                },
                'bookings.people'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'email', 'country_code']);
                },
                ])
            ->asArray()
            ->all();
        $result = [];
        foreach ($tours as $tour) {
            $pax = [];
            foreach ($tour['bookings'] as $booking) {
                $pax = array_merge($pax, $booking['people']);
            }
            $result[$tour['id']] = [
                'code'=>$tour['op_code'],
                'name'=>$tour['op_name'],
                'pax'=>$pax,
                'vn'=>false,
                'la'=>false,
                'kh'=>false,
                'mm'=>false,
                'id'=>false,
                'my'=>false,
            ];
            //echo '<br>', $tour['op_code'];
            foreach ($tour['days'] as $day) {
                $name = strtolower($day['name']);
                $name = str_replace(' ', '', $name);
            //echo '<br>', $name;
                if (strpos($name, 'hanoi') !== false || strpos($name, 'saigon') !== false || strpos($name, 'hochiminh') !== false) {
                    $result[$tour['id']]['vn'] = true;
                }
                if (strpos($name, 'phnompenh') !== false || strpos($name, 'siemreap') !== false) {
                    $result[$tour['id']]['kh'] = true;
                }
                if (strpos($name, 'vientiane') !== false || strpos($name, 'luang') !== false || strpos($name, 'pakse') !== false) {
                    $result[$tour['id']]['la'] = true;
                }
                if (strpos($name, 'yangoon') !== false || strpos($name, 'bagan') !== false || strpos($name, 'mandalay') !== false) {
                    $result[$tour['id']]['la'] = true;
                }
                if (strpos($name, 'yogyakarta') !== false || strpos($name, 'bali') !== false || strpos($name, 'jakarta') !== false) {
                    $result[$tour['id']]['id'] = true;
                }
                if (strpos($name, 'kuala') !== false || strpos($name, 'penang') !== false || strpos($name, 'johor') !== false) {
                    $result[$tour['id']]['my'] = true;
                }
            }
        }
        echo '<hr><h3>TOUR LIST, DEPARTING IN 2015, F TOURS</h3><table border="1"><thead><tr><th>Tour code & name</th><th>Vietnam</th><th>Laos</th><th>Cambodia</th><th>Myanmar</th><th>Indonesia</th><th>Malaysia</th></thead><tbody>';
        foreach ($result as $id=>$tour) {
            echo '<tr><td>', \yii\helpers\Html::a($tour['code'], '/products/op/'.$id, ['target'=>'_blank']), ' - ', $tour['name'], '</td>';
            echo '<td>', $tour['vn'] ? 'YES' : '', '</td>';
            echo '<td>', $tour['la'] ? 'YES' : '', '</td>';
            echo '<td>', $tour['kh'] ? 'YES' : '', '</td>';
            echo '<td>', $tour['mm'] ? 'YES' : '', '</td>';
            echo '<td>', $tour['id'] ? 'YES' : '', '</td>';
            echo '<td>', $tour['my'] ? 'YES' : '', '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '<hr><h3>PAX LIST, VISITING ONLY VIETNAM IN 2015, F TOURS</h3><table border="1"><thead><tr><th>Tour code & name</th><th>Vietnam</th><th>Laos</th><th>Cambodia</th></thead><tbody>';
        foreach ($result as $id=>$tour) {
            if ($tour['vn'] && !$tour['la'] && !$tour['kh']) {
                $cnt = 0;
                foreach ($tour['pax'] as $pax) {
                    $cnt ++;
                    echo '<tr>';
                    if ($cnt == 1) {
                        echo '<td>', \yii\helpers\Html::a($tour['code'], '/products/op/'.$id, ['target'=>'_blank']), ' - ', $tour['name'], '</td>';
                    } else {
                        echo '<td></td>';
                    }
                    echo '<td>', $pax['fname'], '</td>';
                    echo '<td>', $pax['lname'], '</td>';
                    echo '<td>', $pax['email'], '</td>';
                    echo '<td>', strtoupper($pax['country_code']), '</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</tbody></table>';
    }

    // 160615 Thy : Visitors who have visited Laos and Cambodia but who have never visited Vietnam
    public function actionThy02($min = 0, $max = 0)
    {
        // Lam not khach di VN 160620
/*        $sql = 'SELECT tour_id FROM at_tour_stats WHERE countries=""';


        $sql = 'SELECT u.id, u.fname, u.lname, u.gender, LCASE(u.email) AS email, u.byear, u.country_code FROM persons u, at_pax_stats ps WHERE ps.user_id=u.id AND ps.countries="" AND u.is_member="no"';
        $paxList = Yii::$app->db->createCommand($sql)->queryAll();

        echo '<hr><h3>PAX LIST, POSSIBLY ALREADY VISITING VIETNAM (2011-2016), F TOURS</h3><table border="1"><thead><tr><th></th><th>ID</th><th colspan="2">NAME</th><th>BIRTH YEAR</th><th>GENDER</th><th>NATIONALITY</th><th>EMAIL</th></thead><tbody>';
        foreach ($paxList as $pax) {
            echo '<tr>';
            echo '<td>', ++$cnt, '</td>';
            echo '<td>', \yii\helpers\Html::a($pax['id'], '/users/r/'.$pax['id'], ['target'=>'_blank']), '</td>';
            echo '<td>', $pax['fname'], '</td>';
            echo '<td>', $pax['lname'], '</td>';
            echo '<td>', $pax['byear'], '</td>';
            echo '<td>', $pax['gender'], '</td>';
            echo '<td>', strtoupper($pax['country_code']), '</td>';
            echo '<td>', $pax['email'], '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';

        exit;
*/
        $sql = 'SELECT u.id, u.fname, u.lname, u.gender, LCASE(u.email) AS email, u.byear, u.country_code FROM persons u, at_pax_stats ps WHERE ps.user_id=u.id AND ps.countries!="" AND ps.countries!="," AND LOCATE("vn", ps.countries)=0 AND LOCATE(",,", ps.countries)=0 AND u.is_member="no"';
        //$sql = 'SELECT u.id, u.fname, u.lname, u.gender, LCASE(u.email) AS email, u.byear, u.country_code FROM persons u, at_pax_stats ps WHERE ps.user_id=u.id AND LOCATE("vn", ps.countries)=0 AND u.is_member="no"';
        $paxList = Yii::$app->db->createCommand($sql)->queryAll();

        $cnt = 0;
        echo '<hr><h3>PAX LIST, VISITING ONLY LAOS AND/OR CAMBODIA AND NOT VIETNAM (2011-2016), F TOURS</h3><table border="1"><thead><tr><th></th><th>ID</th><th colspan="2">NAME</th><th>BIRTH YEAR</th><th>GENDER</th><th>NATIONALITY</th><th>EMAIL</th></thead><tbody>';
        foreach ($paxList as $pax) {
            echo '<tr>';
            echo '<td>', ++$cnt, '</td>';
            echo '<td>', \yii\helpers\Html::a($pax['id'], '/users/r/'.$pax['id'], ['target'=>'_blank']), '</td>';
            echo '<td>', $pax['fname'], '</td>';
            echo '<td>', $pax['lname'], '</td>';
            echo '<td>', $pax['byear'], '</td>';
            echo '<td>', $pax['gender'], '</td>';
            echo '<td>', strtoupper($pax['country_code']), '</td>';
            echo '<td>', $pax['email'], '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
   
    public function actionUpdatePaxStats($min = 0, $max = 0)
    {
        /*$sql = 'SELECT user_id FROM at_pax_stats WHERE tours="" LIMIT 1000';
        $uids = Yii::$app->db->createCommand($sql)->queryAll();
        $idList = [];
        foreach ($uids as $uid) {
            $idList[] = $uid['user_id'];
        }*/



        $bookings = Booking::find()
            ->where('id<=:max', [':max'=>$max])
            ->andWhere('id>=:min', [':min'=>$min])
            ->andWhere(['status'=>'won'])
            ->with([
                'product'=>function($q){
                    return $q->select(['id']);
                },
                'product.tourStats'=>function($q){
                    return $q->select(['tour_id', 'countries']);
                },
                'people'=>function($q){
                    return $q->select(['id', 'fname', 'lname', 'bday', 'bmonth', 'byear', 'gender', 'email', 'country_code', 'byear']);
                }
                ])
            ->asArray()
            ->all();
        foreach ($bookings as $booking) {
            foreach ($booking['people'] as $pax) {
                $bd = date('Y-m-d', strtotime($pax['byear'].'-'.$pax['bmonth'].'-'.$pax['bday']));
                //echo '<br>', $booking['product_id'], ' : ', $booking['product']['tourStats']['countries'], ' : ', $pax['fname'], ' ', $pax['lname'], ' ', $pax['email'];
                $sql = 'UPDATE at_pax_stats SET gender="'.$pax['gender'].'", nationality="'.$pax['country_code'].'", birthdate="'.$bd.'", tours=CONCAT_WS(",", tours, "'.$booking['product_id'].'"), countries=CONCAT_WS(",", countries, "'.$booking['product']['tourStats']['countries'].'") WHERE user_id='.$pax['id'].';';
                echo '<br>', $sql;
            }
        }
    }

    public function actionUpdateTourStats($action = '', $year = 2016, $month = '')
    {
        if (USER_ID != 1) {
            throw new HttpException(403, 'Access denied.');
        }

        // action: what to do
        if (!in_array($action, ['insert', 'update', 'view', 'fill'])) {
            $action = 'view';
        }

        for ($y = 2011; $y < 2 + date('Y'); $y ++) {
            $yearList[] = $y;
        }
        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        if (!in_array($month, [1,2,3,4,5,6,7,8,9,10,11,12])) {
            $month = '';
        }

        // fill: insert and update empty tours
        if ($action = 'fill') {
            echo '<strong>ACTION FILL STARTED.</strong>';
            $insertSql = 'INSERT INTO at_tour_stats(
                tour_id, tour_old_id, start_date, end_date, day_count, pax_count, tour_code, tour_name, countries
                ) VALUES (
                :tour_id, :tour_old_id, :start_date, :end_date, :day_count, :pax_count, :tour_code, :tour_name, :countries
                )';


            $sql = 'SELECT p.id, o.id AS old_id, p.op_code, p.op_name, p.day_from, p.day_count, p.pax, (SELECT countries FROM at_tour_stats WHERE tour_id=p.id) AS countries FROM at_ct p, at_tours o WHERE o.ct_id=p.id AND YEAR(p.day_from)>=2016 HAVING countries IS NULL';
            $toursToInsert = Yii::$app->db->createCommand($sql, [
                ':year'=>$year,
                ':month'=>$month,
                ])->queryAll();
            $idList = [];
            foreach ($toursToInsert as $tour) {
                $idList[] = $tour['id'];
                echo '<br>', $tour['op_code'];
                Yii::$app->db->createCommand($insertSql, [
                    ':tour_id'=>$tour['id'],
                    ':tour_old_id'=>$tour['old_id'],
                    ':start_date'=>$tour['day_from'],
                    ':end_date'=>date('Y-m-d', strtotime('+'.($tour['day_count'] - 1).' days', strtotime($tour['day_from']))),
                    ':day_count'=>$tour['day_count'],
                    ':pax_count'=>$tour['pax'],
                    ':tour_code'=>$tour['op_code'],
                    ':tour_name'=>$tour['op_name'],
                    ':countries'=>'',
                    ])->execute();
            }
            echo '<br><strong>ACTION FILL COMPLETED.</strong>';
            if (empty($idList)) {
                exit;
            }

            echo '<br><strong>ACTION UPDATE STARTED.</strong>';
            // Countries visited
            $tours = Product::find()
                ->select(['id', 'op_name', 'op_code'])
                ->where(['id'=>$idList])
                ->with([
                    'days'=>function($q) {
                        return $q->select(['rid', 'name']);
                    },
                    'bookings'=>function($q) {
                        return $q->select(['id', 'product_id']);
                    },
                    'bookings.people'=>function($q) {
                        return $q->select(['id', 'fname', 'lname', 'email', 'country_code']);
                    },
                    ])
                ->asArray()
                ->all();
            $result = [];
            foreach ($tours as $tour) {
                $pax = [];
                foreach ($tour['bookings'] as $booking) {
                    $pax = array_merge($pax, $booking['people']);
                }
                $visited = [
                    'vn'=>false,
                    'la'=>false,
                    'kh'=>false,
                    'mm'=>false,
                    'id'=>false,
                    'my'=>false,
                    'th'=>false,
                    'cn'=>false,
                ];
                echo '<br>', $tour['op_code'];
                foreach ($tour['days'] as $day) {
                    $name = \yii\helpers\Inflector::transliterate($day['name']);
                    $name = str_replace(' ', '', strtolower($name));
                    echo '<br> - - - - - ', $name;
                    if (strpos($name, 'hanoi') !== false || strpos($name, 'saigon') !== false || strpos($name, 'hochiminh') !== false || strpos($name, 'along') !== false) {
                        $visited['vn'] = true;
                    }
                    if (strpos($name, 'phnompenh') !== false || strpos($name, 'siemreap') !== false) {
                        $visited['kh'] = true;
                    }
                    if (strpos($name, 'vientiane') !== false || strpos($name, 'luang') !== false || strpos($name, 'pakse') !== false) {
                        $visited['la'] = true;
                    }
                    if (strpos($name, 'yangoon') !== false || strpos($name, 'bagan') !== false || strpos($name, 'mandalay') !== false) {
                        $visited['mm'] = true;
                    }
                    if (strpos($name, 'yogyakarta') !== false || strpos($name, 'ubud') !== false || strpos($name, 'bali') !== false || strpos($name, 'jakarta') !== false) {
                        $visited['id'] = true;
                    }
                    if (strpos($name, 'kuala') !== false || strpos($name, 'penang') !== false || strpos($name, 'johor') !== false) {
                        $visited['my'] = true;
                    }
                    if (strpos($name, 'bangkok') !== false || strpos($name, 'chiang') !== false) {
                        $visited['th'] = true;
                    }
                    if (strpos($name, 'kunming') !== false || strpos($name, 'guang') !== false) {
                        $visited['cn'] = true;
                    }
                } // day
                $listCountries = [];
                if ($visited['vn']) $listCountries[] = 'vn';
                if ($visited['la']) $listCountries[] = 'la';
                if ($visited['kh']) $listCountries[] = 'kh';
                if ($visited['mm']) $listCountries[] = 'mm';
                if ($visited['id']) $listCountries[] = 'id';
                if ($visited['my']) $listCountries[] = 'my';
                if ($visited['th']) $listCountries[] = 'th';
                if ($visited['cn']) $listCountries[] = 'cn';
                $countryList = implode(',', $listCountries);
                // Savd DB
                $sql = 'UPDATE at_tour_stats SET countries=:c WHERE tour_id=:id';
                Yii::$app->db->createCommand($sql, [
                    ':c'=>$countryList,
                    ':id'=>$tour['id'],
                    ])->execute();
                echo '<br>Countries visited: ', $countryList;
            } // tour
            echo '<br><strong>ACTION UPDATE FINISHED.</strong>';

            exit;
        }

        // insert: add tour to stats table
        if ($action == 'insert') {
            $insertSql = 'INSERT INTO at_tour_stats(
                tour_id, tour_old_id, start_date, end_date, day_count, pax_count, tour_code, tour_name, countries
                ) VALUES (
                :tour_id, :tour_old_id, :start_date, :end_date, :day_count, :pax_count, :tour_code, :tour_name, :countries
                )';

            $sql = 'SELECT p.id, o.id AS old_id, p.op_code, p.op_name, p.day_from, p.day_count, p.pax FROM at_ct p, at_tours o WHERE o.ct_id=p.id AND YEAR(p.day_from)=:year AND MONTH(p.day_from)=:month AND p.op_finish!="canceled"';
            $toursToInsert = Yii::$app->db->createCommand($sql, [
                ':year'=>$year,
                ':month'=>$month,
                ])->queryAll();

            $sql2 = 'DELETE FROM at_tour_stats WHERE YEAR(start_date)=:year AND MONTH(start_date)=:month';
            Yii::$app->db->createCommand($sql2, [
                ':year'=>$year,
                ':month'=>$month,
                ])->execute();

            foreach ($toursToInsert as $tour) {
                echo '<br>', $tour['op_code'];
                Yii::$app->db->createCommand($insertSql, [
                    ':tour_id'=>$tour['id'],
                    ':tour_old_id'=>$tour['old_id'],
                    ':start_date'=>$tour['day_from'],
                    ':end_date'=>date('Y-m-d', strtotime('+'.($tour['day_count'] - 1).' days', strtotime($tour['day_from']))),
                    ':day_count'=>$tour['day_count'],
                    ':pax_count'=>$tour['pax'],
                    ':tour_code'=>$tour['op_code'],
                    ':tour_name'=>$tour['op_name'],
                    ':countries'=>'',
                    ])->execute();
            }
            exit;
        }

        // update: update visited countries
        if ($action == 'update') {
            echo '<strong>ACTION UPDATE STARTED.</strong>';
            // Countries visited
            $tours = Product::find()
                ->select(['id', 'op_name', 'op_code'])
                ->where(['op_status'=>'op'])
                ->andWhere('op_finish!="canceled"')
                ->andWhere('YEAR(day_from)=:year', [':year'=>$year])
                ->andWhere('MONTH(day_from)=:month', [':month'=>$month])
                ->with([
                    'days'=>function($q) {
                        return $q->select(['rid', 'name']);
                    },
                    'bookings'=>function($q) {
                        return $q->select(['id', 'product_id']);
                    },
                    'bookings.people'=>function($q) {
                        return $q->select(['id', 'fname', 'lname', 'email', 'country_code']);
                    },
                    ])
                ->asArray()
                ->all();
            $result = [];
            foreach ($tours as $tour) {
                $pax = [];
                foreach ($tour['bookings'] as $booking) {
                    $pax = array_merge($pax, $booking['people']);
                }
                $visited = [
                    'vn'=>false,
                    'la'=>false,
                    'kh'=>false,
                    'mm'=>false,
                    'id'=>false,
                    'my'=>false,
                    'th'=>false,
                    'cn'=>false,
                ];
                echo '<br>', $tour['op_code'];
                foreach ($tour['days'] as $day) {
                    $name = \yii\helpers\Inflector::transliterate($day['name']);
                    $name = str_replace(' ', '', strtolower($name));
                    echo '<br> - - - - - ', $name;
                    if (strpos($name, 'hanoi') !== false || strpos($name, 'saigon') !== false || strpos($name, 'hochiminh') !== false || strpos($name, 'along') !== false) {
                        $visited['vn'] = true;
                    }
                    if (strpos($name, 'phnompenh') !== false || strpos($name, 'siemreap') !== false) {
                        $visited['kh'] = true;
                    }
                    if (strpos($name, 'vientiane') !== false || strpos($name, 'luang') !== false || strpos($name, 'pakse') !== false) {
                        $visited['la'] = true;
                    }
                    if (strpos($name, 'yangoon') !== false || strpos($name, 'bagan') !== false || strpos($name, 'mandalay') !== false) {
                        $visited['mm'] = true;
                    }
                    if (strpos($name, 'yogyakarta') !== false || strpos($name, 'bali') !== false || strpos($name, 'jakarta') !== false) {
                        $visited['id'] = true;
                    }
                    if (strpos($name, 'kuala') !== false || strpos($name, 'penang') !== false || strpos($name, 'johor') !== false) {
                        $visited['my'] = true;
                    }
                    if (strpos($name, 'bangkok') !== false || strpos($name, 'chiang') !== false) {
                        $visited['th'] = true;
                    }
                    if (strpos($name, 'kunming') !== false || strpos($name, 'guang') !== false) {
                        $visited['cn'] = true;
                    }
                } // day
                $listCountries = [];
                if ($visited['vn']) $listCountries[] = 'vn';
                if ($visited['la']) $listCountries[] = 'la';
                if ($visited['kh']) $listCountries[] = 'kh';
                if ($visited['mm']) $listCountries[] = 'mm';
                if ($visited['id']) $listCountries[] = 'id';
                if ($visited['my']) $listCountries[] = 'my';
                if ($visited['th']) $listCountries[] = 'th';
                if ($visited['cn']) $listCountries[] = 'cn';
                $countryList = implode(',', $listCountries);
                // Savd DB
                $sql = 'UPDATE at_tour_stats SET countries=:c WHERE tour_id=:id';
                Yii::$app->db->createCommand($sql, [
                    ':c'=>$countryList,
                    ':id'=>$tour['id'],
                    ])->execute();
                echo '<br>Countries visited: ', $countryList;
            } // tour
            echo '<br><strong>ACTION UPDATE FINISHED.</strong>';
        }

        exit('NO ACTIONS!');
    }
    /*


        /*
        echo '<hr><h3>PAX LIST, VISITING ONLY VIETNAM IN 2015, F TOURS</h3><table border="1"><thead><tr><th>Tour code & name</th><th>Vietnam</th><th>Laos</th><th>Cambodia</th></thead><tbody>';
        foreach ($result as $id=>$tour) {
            if ($tour['vn'] && !$tour['la'] && !$tour['kh']) {
                $cnt = 0;
                foreach ($tour['pax'] as $pax) {
                    $cnt ++;
                    echo '<tr>';
                    if ($cnt == 1) {
                        echo '<td>', \yii\helpers\Html::a($tour['code'], '/products/op/'.$id, ['target'=>'_blank']), ' - ', $tour['name'], '</td>';
                    } else {
                        echo '<td></td>';
                    }
                    echo '<td>', $pax['fname'], '</td>';
                    echo '<td>', $pax['lname'], '</td>';
                    echo '<td>', $pax['email'], '</td>';
                    echo '<td>', strtoupper($pax['country_code']), '</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</tbody></table>';
        */



    public function actionUpdateCustomerProfiles($from = '')
    {
        if ($from == '') {
            $from = date('Y-m-d', strtotime('30 days ago'));
        }
        // Select all booking_user
        $sql = 'SELECT user_id, created_at, booking_id, (SELECT id FROM at_profiles_customer p WHERE p.user_id=bu.user_id LIMIT 1) AS profile_id FROM at_booking_user bu WHERE status!="canceled" AND created_at>:from';
        $results = Yii::$app->db->createCommand($sql, [':from'=>$from])->queryAll();
        foreach ($results as $result) {
            echo '<br>', Yii::$app->formatter->asRelativeTime($result['created_at']), ' - ', $result['booking_id'], ' - ', $result['profile_id'];
            if ($result['profile_id'] == 0) {
                echo ' - NONE';
                $sql2 = 'INSERT INTO at_profiles_customer (created_dt, created_by, updated_dt, updated_by, user_id, case_count, booking_count, referral_count, won_referral_count)
                VALUES (:now, 1, :now, 1, :user_id, :case_count, :booking_count, :referral_count, :won_referral_count)';
                Yii::$app->db->createCommand($sql2, [
                    ':now'=>NOW,
                    ':user_id'=>$result['user_id'],
                    ':case_count'=>1,
                    ':booking_count'=>1,
                    ':referral_count'=>0,
                    ':won_referral_count'=>0,
                    ])->execute();
            }
        }
    }

    public function actionUpdateCustomerWonRefCount()
    {
        $sql = 'select r.user_id, COUNT(*) AS total FROM at_cases k, at_referrals r WHERE k.id=r.case_id AND k.deal_status="won" GROUP BY r.user_id ORDER BY total DESC';
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        //$sql2 = 'select r.user_id, COUNT(*) AS total FROM at_cases k, at_referrals r WHERE k.id=r.case_id GROUP BY r.user_id ORDER BY total DESC';
        //$results2 = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($results as $result) {
            echo '<br>', $result['user_id'], ' - ', $result['total'];
            $sql2 = 'UPDATE at_profiles_customer SET updated_dt=:now, updated_by=1, won_referral_count=:count WHERE user_id=:user_id LIMIT 1';
            Yii::$app->db->createCommand($sql2, [
                ':now'=>NOW,
                ':count'=>$result['total'],
                ':user_id'=>$result['user_id'],
                ])->execute();
        }
    }
}
