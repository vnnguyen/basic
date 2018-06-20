<?php

namespace app\controllers;

use Yii;
use app\models\Tour;
use app\models\AtNgaymau;
use app\models\Daystour;
use app\models\Lx;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;
use yii\helpers\Url;



use app\models\AtTours;
use app\models\AtCt;
use app\models\User;
use yii\web\HttpException;
use app\models\TourInLxForm;

/**
 * TourController implements the CRUD actions for Tour model.
 */
class TourController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
                    // 'delete' => ['POST'],
        ],
        ],
        ];
    }

    /**
     * Lists all Tour models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Tour();
        $listData = $query = Tour::find();
            //pagination
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $dataProvider = $query->offset($pages->offset)
        ->limit($pages->limit)->orderBy('id DESC')
        ->all();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'model' => $model
            ]);
    }

    /**
     * Displays a single Tour model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            ]);
    }

    public function actionList_lich_xe()
    {
        $lxs = Lx::find()->all();
        return $this->render('list_lx', [
            'lxs' => $lxs
        ]);
    }

    public function actionCreate_lx()
    {
        $lx = new Lx();
        $lx->created_on = date('Y-m-d H:i:s', strtotime('now'));
        $lx->created_by = 1;
        $lx->cpt_id = 0;
        $lx->name = '';
        $lx->content =serialize([]);
        if ($lx->save()) {
            return $this->redirect(['in_lx', 'id' => 51456]);
        }
    }
    // In lich xe cho dieu hanh

    public function actionIn_lx($id = 25)
    {
        $lx_old = Lx::find()->where(['cpt_id' => $id])->asArray()->one();

        $theTour = AtCt::find()
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->with([
                'days',
                'updatedBy',
                'guides',
                // 'tour.cskh',
                // 'tour.operators',
            ])
            ->asArray()
            ->one();
        // var_dump($theTour);die();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = AtTours::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->with([
                'operators'=>function($q){
                    return $q->select(['id', 'name'=>new \yii\db\Expression('CONCAT(fname, " ", lname, " - ", phone)')]);
                },
            ])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theForm = new TourInLxForm;
        $theForm->days = '1-'.$theTour['day_count'];
        $theForm->giakm = 4500;
        $theForm->giadb = 1150000;
        $theForm->giatb = 1100000;
        $theForm->loaixe = '7 chỗ';
        $theForm->dieuhanh = 1;
        if ($lx_old != null) {
            $post = unserialize($lx_old['content']);
            // var_dump($post);die();
                if ($post) {
                    $theForm->giakm = $post['TourInLxForm']['giakm'];
                    $theForm->giadb = $post['TourInLxForm']['giadb'];
                    $theForm->giatb = $post['TourInLxForm']['giatb'];
                    $theForm->loaixe = $post['TourInLxForm']['loaixe'];
                    $theForm->giakm = $post['TourInLxForm']['giakm'];
                    $theForm->dieuhanh = $post['TourInLxForm']['dieuhanh'];
                    $theForm->chuxe = $post['TourInLxForm']['chuxe'];
                    $theForm->laixe = $post['TourInLxForm']['laixe'];
                    $theForm->huongdan = $post['TourInLxForm']['huongdan'];
                    $theForm->note = $post['TourInLxForm']['note'];
                }
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $theLx = Lx::find()->where(['cpt_id' => $id])->one();
            if ($theLx != null) {
                $theLx->updated_on = date('Y-m-d H:i:s', strtotime('now'));
                $theLx->updated_by = 1;
                $theLx->cpt_id = $id;
                $theLx->name = $theForm['vp'];
                $theLx->content = serialize($_POST);
            } else {
                $theLx = new Lx();
                $theLx->created_on = date('Y-m-d H:i:s', strtotime('now'));
                $theLx->created_by = 1;
                $theLx->updated_on = date('Y-m-d H:i:s', strtotime('now'));
                $theLx->updated_by = 1;
                $theLx->cpt_id = $id;
                $theLx->name = $theForm['vp'];
                $theLx->content = serialize($_POST);
            }
            if ($theLx->save()) {
                return $this->render('tours_in-lx_ok', [
                    'theForm'=>$theForm,
                    'theTour'=>$theTour,
                    'theTourOld'=>$theTourOld,
                ]);
            } else {
                var_dump($theLx->errors);die();
            }
        }

        return $this->render('tours_in-lx', [
            'theForm'=>$theForm,
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'lx_old' => $lx_old,
        ]);
    }
    public function actionList_lx($query)
    {

        if (Yii::$app->request->isAjax) {
            $arr_lx = [
                ["Ăn tối", "at", "20"],
                ["Ăn tối + rối nước", "atrn", "40"],
                ["Đón ga", "dg", "30"],
                ["Tiễn ga", "tg", "30"],
                ["Đón ga (trong ngày ko sử dụng xe chỉ có tiễn hoặc đón)", "dg", "50"],
                ["Tiễn ga (trong ngày ko sử dụng xe chỉ có tiễn hoặc đón)", "tg", "50"],
                ["Đón sân bay - đưa qua văn phòng", "dsbam", "130"],
                ["Đón sân bay - massa", "dsbmassa", "150"],
                ["Đón sân bay - Hạ Long - Tuần Châu", "dsbhl", "300"],
                ["Hạ Long - Tuần Châu - Tiễn sân bay", "hlsb", "280"],
                ["Đón sân bay", "dsb", "100"],
                ["Tiễn sân bay", "tsb", "100"],
                ["Hà Nội - Tiễn sân bay ", "hntsb", "100"],
                ["Đón sân bay + đi thêm 1 điểm thăm quan ", "dsb", "170"],
                ["Đưa đi học nấu ăn (trong tour)", "na", "                "],
                ["Hà Nội City 1 ngày", "hnvis", "150"],
                ["Hà Nội City 1 ngày ngoài tour (khi tour có 1 ngày duy nhất)", "hanvis", "200"],
                ["Hà Nội City 1/2 + ăn trưa", "hanvis 1/2", "120"],
                ["Hà Nội City 1/2", "hanvis 1/2", "100"],
                ["Massage", "massa", "50"],
                ["Rối nước (chỉ từ 17h trở ra mới tính,)", "rn", "20"],
                ["Hà Nội - Hà Thái - Canh Hoạch - Phú Vinh - Hà Nội (full day)", "hnhtpv", "250"],
                ["Hà Nội - Hạ Thái - Phú Vinh - Hà Nội", "hnhtpv", "200"],
                ["Hà Nội - Hạ Thái - Tam Cốc", "hnhttc", "260"],
                ["Hà Nội - Hạ Thái - Nhị Khê (full day)", "hnnkh", "220"],
                ["Hà Nội - Hạ Thái + city nửa ngày ", "hnht 1/2", "220"],
                ["Hà Nội - Nhị Khê - Hà Thái - Hà Nội (half day)", "hnnkh 1/2", "150"],
                ["Hà Nội - Bát Tràng - Bút Tháp - Đông Hồ - Hanoi", "hnvis", "250"],
                ["Hà Nội - Bát tràng /Vạn Phúc 1/2 ngày trong tour", "hnvp", "100"],
                ["Hà Nội - Bát tràng /Vạn Phúc cả ngày", "hnbt", "150"],
                ["Hà Nội - Bút tháp - ĐHồ - Đồng Kỵ - Hanoi", "hnvis", "250"],
                ["Hà Nội - Chùa thầy - Chùa Tây Phương + Chùa Trăm Gian - Hà Nội", "hnct", "250"],
                ["Hà Nội - Chùa thầy - Chùa Tây Phương 1 ngày", "hntp", "200"],
                ["Hà Nội - Đường Lâm - Chùa Thầy - Hanoi", "hndl", "250"],
                ["Hà Nội - Đường Lâm - Chùa Thầy - Tây Phương - Hà Nội", "hndl", "280"],
                ["Hà Nội - Đường Lâm - Hanoi", "hndl", "200"],
                ["Hà Nội - Làng Nôm - Chùa Nôm ( xã Đại Đồng, Văn Lâm, Hưng Yên)  - City 1/2", "hnhy", "250"],
                ["Hà Nội - Chùa Hương - Nộn Khê hoặc Hồng Phong", "hnhp", "300"],
                ["Hà Nội - Chùa Hương - Tam Cốc ( Ngủ Tam Cốc) or Thung Nham ", "hntn", "250"],
                ["Hà Nội - Chùa Hương - Vân Long (Ngủ Vân Long) or Emeralda Resort", "hnchvl", "250"],
                ["Hà Nội - Chùa Hương 1 ngày", "hnch", "200"],
                ["Hà Nội - Đường Lâm - Mai Châu", "hndl", "250"],
                ["Hà Nội - Ferme du colvert ", "hnf", "150"],
                ["Hà Nội - Hòa Bình (Bản mường, bản dao) - Hà Nội", "hnhb", "300"],
                ["Hà Nội - Lương Sơn", "hnls", "150"],
                ["Hà Nội - Mai Châu", "hnmc", "200"],
                ["Hà Nội - Mai Châu - Hà Nội 1 ngày", "hnmc", "380"],
                ["Hà Nội - Mai Châu ( Mai Hịch)", "hnmh", "250"],
                ["Hà Nội - Mai Châu - Bản Bước - Pù Luông", "hnpl", "300"],
                ["Pù Luông 1 ngày", "plvis", "150"],
                ["Pù Luông - Vân Long - Tam Cốc", "pltc", "300"],
                ["Pù Luông - Tam Cốc ", "pltc", "250"],
                ["Mai Hịch -Tam Cốc", "mhtc", "250"],
                ["Hà Nội - Mai Châu (Thăm Pà Cò, Xăm Khòe )", "hnmc", "250"],
                ["Hà Nội - Vịt Cổ xanh - Mai Châu", "hnmc", "250"],
                ["Bến Bính - Ninh Bình (Thung Nham / Tam Cốc..)", "bbnb", "210"],
                ["Hạ Long - Bút Tháp - Phù Lãng - Tiễn sân bay", "hltsb", "380"],
                ["Hạ Long - Bút Tháp (hoặc Phù Lãng) - Hà Nội", "hlhn", "260"],
                ["Hạ Long - Côn sơn - Hà Nội", "hlhn", "260"],
                ["Hạ Long ( Tuần Châu) - Tiễn Sân Bay - Hà Nội ", "hltsb", "290"],
                ["Hạ Long ( Hòn Gai) - Tiễn Sân Bay - Hà Nội ", "hltsb", "315"],
                ["Hạ Long lưu xe", "hlld", "100"],
                ["Hạ Long (Tuần Châu) - Hà Nội", "hlhn", "210"],
                ["Hạ Long (Hòn Gai) - Hà Nội", "hlhn", "235"],
                ["Hà Nội - Hạ Long (Tuần Châu)", "hnhl", "210"],
                ["Hà Nội - Ha Long (Hòn Gai)", "hnhl", "235"],
                ["Sang bến Hòn Gai", "hg", "25"],
                ["Hạ Long - Bút Tháp (hoặc Phù Lãng) - Tiễn sân bay", "hltsb", "340"],
                ["Hạ Long - Đông Triều - Tiễn sân bay", "hltsb", "310"],
                ["Hà Nội - Hạ Long ( Bãi Cháy or Tuần Châu )- xe ko tải sang Hải Phòng", "hnhlhp", "300"],
                ["Hà Nội - Hạ Long (bến Tuần Châu)", "hnhl", "210"],
                ["Hà Nội - Hạ Long (bến bãi cháy)", "hnhl", "210"],
                ["Hà Nội - Vân Long - Hoa Lư - Tam Cốc ", "hntc", "260"],
                ["Hà Nội - Hạ Long (bến  Hòn Gai )", "hnhl", "235"],
                ["Hà Nội - Hạ Long 1 ngày Bãi cháy/ Tuần châu", "hnhl", "380"],
                ["Cộng thêm km đi cao tốc ", "ct", "10"],
                ["Hà Nội - Hạ Long 2 ngày Bãi cháy / Tuần châu", "hnhl", "400"],
                ["Hà Nội - Hải Phòng (Bến Bính)", "hnhp", "150"],
                ["Hà Nội - Hải Phòng - Thăm Chùa Dư Hằng", "hnhp", "200"],
                ["Hà Nội - Hòn Gai - xe ko tải sang Hải Phòng", "hnhp", "325"],
                ["Hà Nội - Yên Đức - Hạ Long", "hnhl", "250"],
                ["Hòn Gai - City Hải Phòng - tiễn sân bay Cát Bi - xe chạy không về Hà Nội", "hghpvis", "350"],
                ["Hòn Gai - City trung tâm Hải Phòng - lưu đêm", "hghpld", "225"],
                ["Hòn Gai - Khách sạn trung tâm TP Hải Phòng xe nằm lưu đêm", "hghpld", "175"],
                ["Lưu đêm ở các tỉnh khác", "ld", "130"],
                ["Lưu đêm ở Hạ Long", "ldhl", "100"],
                ["Sang Hòn Gai 2 lượt", "hg", "50"],
                ["Tuần Châu/ Bãi Cháy/ Hòn Gai - Tiễn sb Cát Bi HP - Xe chạy không về Hà Nội", "hlcbhn", "300"],
                ["Xe chạy không từ Hạ Long (Bãi Cháy hoặc Tuần Châu ) - Bến Bính (Đi thêm Hòn Gai + 25km)", "hlbbk", "100"],
                ["Xe chạy không từ Hạ Long (Bãi Cháy hoặc Tuần Châu ) - Bến Bính (nếu đi thêm Hòn Gai + 25km) ( trường hợp không kết hợp với chương trình nào)", "hlbbct", "150"],
                ["Xe không tải từ Hạ Long sang đón khách Bến Bính - Hà Nội", "hlbbhn", "250"],
                ["Xe không tải từ Hà Nội xuống Bến Bính đón khách về Hà Nội", "hnbb", "270"],
                ["Hà Nội - Bái Đính - Tam Cốc / Thung Nham", "hntn", "200"],
                ["Hà Nội - Bái Đính - Tràng An - Hà Nội 1 ngày", "hnbdhn", "300"],
                ["Hà Nội - Bái Đính - Vân Long", "hnvl", "200"],
                ["Hà Nội - Hoa Lư- Vân Long - Tam Cốc", "hntc", "260"],
                ["Hà Nội  -Cúc Phương", "hncp", "200"],
                ["Hà Nội - Cúc Phương - Hà Nội (Cửa Rừng)", "hncp", "350"],
                ["Hà Nội - Động Thiên Hà - Tam cốc - Nộn Khê", "hnnk", "250"],
                ["Hà Nội - Hang Múa - Thái Vi - Tam Cốc /Thung Nham", "hntn", "250"],
                ["Hà Nội - Hoa Lư - Kênh Gà - Tam Cốc / Thung Nham", "hntc", "250"],
                ["Hà Nội - Hoa Lư - Kênh Gà - Tràng An - Tam Cốc", "hntc", "300"],
                ["Hà Nội - Hoa Lư - Phát Diệm - Hà Nội 1 ngày", "hnvis", "400"],
                ["Hà Nội - Hoa Lư - Tam Cốc - Hà Nội 1 ngày", "hnvis", "300"],
                ["Hà Nội - Hoa Lư - Tam Cốc / Thung Nham / Vân Long", "hnvl", "200"],
                ["Hà Nội - Hoa Lư - Tràng An - Tam Cốc", "hntc", "250"],
                ["Hà Nội - Hoa Lư - Vân Long", "hnvl", "200"],
                ["Hà Nội - Kênh Gà - Hoa Lư - Thung Nham", "hntn", "250"],
                ["Hà Nội - Kênh Gà - Hoa Lư - Vân Long", "hnvl", "250"],
                ["Hà Nội - Kênh Gà - Tam Cốc - Hà Nội 1 ngày", "hnvis", "300"],
                ["Hà Nội - Kênh Gà - Tam Cốc - Nộn Khê / Hồng Phong", "hnhp", "250"],
                ["Hà Nội - Kênh Gà - Tam Cốc / Thung Nham", "hntn", "200"],
                ["Hà Nội - Kênh Gà - Vân Long - Hà Nội 1 ngày", "hnvis", "300"],
                ["Hà Nội - Kênh Gà - Vân Long - Hồng Phong", "hnhp", "250"],
                ["Hà Nội - Kênh Gà - Vân Long - Tam Cốc / Thung Nham", "hntn", "250"],
                ["Hà Nội - Kênh gà - Vân Long / Emeralda Resort", "hnvl", "200"],
                ["Hà Nội - Ninh Bình (ko tải) - Đón khách ở Ga Ninh Bình", "hnnb", "150"],
                ["Hà Nội - Nộn Khê /Hồng Phong (hoặc ngược lại)", "hnnk", "200"],
                [" Nộn Khê /Hồng Phong - Hà Nội ", "nkhn", "200"],
                ["Hà Nội - Phát Diệm - Hà Nội", "hnvis", "350"],
                ["Hà Nội - Phát Diệm - Nộn Khê - Hà Nội", "hnvis", "350"],
                ["Hà Nội - Phát Diệm - Nộn Khê - Tam Cốc / Thung Nham", "hntn", "250"],
                ["Hà Nội - Phát Diệm - Nộn Khê / Hồng Phong", "hnnk", "200"],
                ["Hà Nội - Phát Diệm - Tam Cốc / Thung Nham", "hntn", "200"],
                ["Hà Nội - Tam Cốc - Phát Diệm", "hnpd", "200"],
                ["Hà Nội - Tam Cốc - Phát Diệm - Hà Nội 1 ngày", "hnvis", "400"],
                ["Hà Nội - Tam Cốc - Phát Diệm - Nộn Khê", "hnnk", "250"],
                ["Hà Nội - Tam Cốc - Vân Long", "hnvl", "200"],
                ["Hà Nội - Tam Cốc (Thêm 1 điểm ở Ninh Bình + thêm 50 km, trừ Cúc Phường)", "hntc", "150"],
                ["Tam cốc lưu xe", "tcld", "130"],
                ["Hà Nội - Tam Cốc", "hntc", "210"],
                ["Hà Nội - Tràng An - Tam Cốc / Thung Nham", "hntn", "200"],
                ["Hà Nội - Vân Long - Hoa Lư - Tam Cốc / Thung Nham", "hntn", "250"],
                ["Hà Nội - Vân Long - Phát Diệm - Nộn Khê/ Hồng Phong", "hnhp", "250"],
                ["Hà Nội - Vân Long - Tam Cốc - Nộn Khê / Hồng Phong", "hnhp", "250"],
                ["Hà Nội - Vân Long - Tam Cốc / Thung Nham", "hntn", "200"],
                ["Hà Nội - Vân Long - Tam Cốc -Nộn Khê /Hồng Phong - Phát Diệm", "hnpd", "250"],
                ["Nộn Khê/Hồng phong - Tam Cốc / Emeral Ninh Binh ", "nktc", "150"],
                ["Bắc Hà (không thăm quan) Lào Cai - Xe không tải về Hà nội", "bhhn", "350"],
                ["Hà Nội - Bắc Hà (có thăm Bắc Hà)", "hnbh", "500"],
                ["Bắc Hà (có thăm ) - Hà Nội", "bhhn", "500"],
                ["Hà Nội - Bắc Hà (không thăm)", "hnbh", "450"],
                ["Bắc Hà - Hà Nội (không thăm)", "bhhn", "450"],
                ["Hà Nội - Điện Biên", "hndb", "500"],
                ["Điện Biên - Hà Nội ", "dbhn", "500"],
                ["Hà Nội - Điện Biên (chạy không tải)", "hndb", "400"],
                ["Điện Biên - Hà Nội  (chạy không tải)", "dbhn", "400"],
                ["Hà Nội - Hải Dương - Xem rối nước ở Bồ Dương - Hà Nội", "hnhd", "250"],
                ["Hà Nội - Lào Cai (đường mới  cao tốc)", "hnlc", "350"],
                ["Lào cai - Hà Nội (đường cao tốc)", "lchn", "350"],
                ["Hà Nội - Lào Cai  (Xe không tải )", "hnlc", "300"],
                ["Lào cai - Hà Nội (không tải)", "lchn", "300"],
                ["Hà Nội - Quảng Xương Thanh Hóa - ksan 257 Trường Thi ( TP Thanh Hóa)", "hnth", "300"],
                ["Hà Nội - Sapa ( cao tốc mới)", "hnsp", "400"],
                ["Sapa - Hà Nội (không thăm)", "hnsp", "400"],
                ["Hà Nội - Sapa -  Thăm xung quanh nửa ngày Sapa", "hnsp", "450"],
                ["Sapa (thăm ) - Hà Nội ", "sphn", "450"],
                ["Sapa (không thăm) -Hà Nội ", "sphn", "400"],
                ["Hà Nội - Tam Đảo - Hà Nội 1 ngày", "hntd", "400"],
                ["Hà Nội - Tam Đảo 2 ngày (Do từ chân dốc lên đến đỉnh dốc đường khó đi, dốc nên xe rất ăn xăng, dầu + phòng nội bộ lái xe quá đắt)", "hntd", "500"],
                ["Thành Phố Thanh Hóa - Nộn Khê Ninh Bình", "thnk", "200"],
                ["Hà Nội - Đảo Cò - Hải Dương ( Đi theo đường cũ qua Văn Giang)", "hnhd", "150"],
                ["Hà Nội - Đảo Cò - Hải Dương - Hà Nội", "hndc", "230"],
                ["Hạ Long ( Tuần Châu) Đảo Cò ngủ ở đảo cò", "hlhd", "200"],
                ["Hạ Long  ( Tuần Châu ) - Đảo Cò - Hà Nội", "hldc", "250"],
                ["Hạ Long  ( Hòn gai ) - Đảo Cò - Hà Nội", "hldc", "275"],
                ["Hà Nội - Sơn Tây - Moon garden - Hà Nội (1 ngày)", "hnks", "200"],
                ["Hà Nội - Sơn Tây - Moon garden (ngủ lại Moon Garden)", "hnks", "150"],
                ["Moon garden - Hạ Long ( Tuần Châu) ", "kshl", "280"],
                ["Mai Châu - Bản Nhót - Pù Luông", "mcpl", "150"],
                ["Mai Châu - Hoa Lư - Tam Cốc", "mctc", "300"],
                ["Mai Châu - Kênh Gà - Nộn Khê", "mcnk", "300"],
                ["Mai Châu - Kênh Gà - Vân Long", "mcvl", "300"],
                ["Mai Châu - Kênh Gà - Vân Long - Hồng Phong", "mchp", "350"],
                ["Mai Châu - Tam Cốc or Thung Nham / Vân Long / Nộn Khê / Hồng Phong", "mctc", "250"],
                ["Mai Châu - Thổ Hà (gần Kênh Gà) - Tam Cốc / Thung Nham / Vân Long", "mctc", "300"],
                ["Mai Hịch - Bản Bước - Hà Nội", "mhhn", "250"],
                ["Mai Hịch - Cúc Phương - Tam Cốc", "mhtc", "300"],
                ["Mai Hịch - Kênh Gà - Tam Cốc", "mhtc", "300"],
                ["Mai Hịch - Tam Cốc", "mhtc", "250"],
                ["Mai Hịch - Pù Luông - Mai Hịch ", "mhvis", "150"],
                ["Mai Hịch - Thung Nắng - Thung Nham", "mhtn", "300"],
                ["Ninh Bình - Hoa Lư - Kênh Gà - Hà Nội", "nbhn", "350"],
                ["Ninh Bình - Phát Diệm", "nbpd", "150"],
                ["Ninh Bình - Thành phố Vinh", "nbv", "250"],
                ["Tam Cốc - Bái Đính - Tràng An - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Bích Động - Mai Châu - Mai  Hịch", "tcmh", "300"],
                ["Tam Cốc - Cúc Phương - Hạ Long", "tccphl", "350"],
                ["Tam Cốc - Hoa Lư - Hạ Long (Bãi Cháy)", "tchl", "300"],
                ["Tam Cốc - Hoa Lư - Vân Long - Hà Nội", "tchn", "250"],
                ["Tam Cốc - Hoa Lư / Thái Vi / Hang Múa - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Hồng Phong/ Nộn Khê- Phát Diệm - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Kênh Gà - Vân Long - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Nộn Khê ", "tcnk", "150"],
                ["Nộn Khê - Tam Cốc", "nktc", "150"],
                ["Tam Cốc - Phát Diệm - H. Phong/N. Khê - Tam Cốc", "tcvis", "180"],
                ["Tam Cốc - Phát Diệm - Hà Nội", "tchn", "200"],
                ["Tam Cốc - Phát Diệm - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Vân Long ", "tcvl", "150"],
                ["Vân Long - Tam Cốc", "vltc", "150"],
                ["Tam Cốc - Vân Long - Nộn Khê", "tcnk", "150"],
                ["Tam Cốc / Thung Nham / Vân Long - Hà Nội", "tchn", "150"],
                ["Tam Cốc -Hạ Long - Tuần Châu", "tchl", "260"],
                ["Tam Cốc -Hạ Long -  Hòn Gai", "tchl", "285"],
                ["Nộn Khê/ Hồng Phong - Hạ Long (Tuần Châu)", "nkhl", "260"],
                ["Nộn Khê/ Hồng Phong - Hạ Long (Hòn Gai)", "nkhl", "285"],
                ["Hạ Long (Tuần Châu ) - Nộn Khê/Hồng Phong", "hlhp", "285"],
                ["Hạ Long (Hòn gai) - Nộn Khê/Hồng Phong", "hlhp", "285"],
                ["Tam Cốc Garden - Cúc Phương - Tam Cốc Garden", "tcvis", "200"],
                ["Tam Cốc Garden - Cúc Phương (ngủ Cúc Phương)", "tccp", "150"],
                ["Thung Nham - Yên Đức - Hòn Gai", "tnhg", "325"],
                ["Vào Giữa rừng (Cộng thêm)", "cp", "50"],
                ["Vân Long - Nộn Khê - Vân Long", "vlvis", "150"],
                ["Vân Long - Nộn Khê ", "vlnk", "150"],
                ["Nộn Khê - Vân Long", "nkvl", "150"],
                ["Vân Long - Tam Cốc - Nộn Khê", "vlnk", "150"],
                ["Vân Long - Tràng An - Bái Đính - Hoa Lư- Vân Long", "vlvis", "200"],
                ["Nộn Khê - Hải Hậu", "nkhh", "150"],
                ["Nộn Khê - Phát Diệm - Nộn Khê", "nkvis", "150"],
                ["Nộn Khê - Phát Diệm - Vân Long", "nkvis", "150"],
                ["Nộn Khê - Tam Cốc - Nộn Khê", "nkvis", "150"],
                ["Nộn Khê /Yên Mô - Tam Cốc /Vân Long", "nkvl", "150"],
                ["Nộn Khê tự do (ko dùng xe đi bất cứ đâu)", "nkld", "130"],
                ["Nộn Khê/ Tam Cốc/ - Ninh Bình - Phủ Lý - Đồng Văn - Đảo Cò ", "nkdc", "150"],
                ["Nộn Khê/ Tam Cốc - Ninh Bình - Phủ Lý - Đồng Văn - Rẽ qua Ninh Giang - Đảo Cò  - Hà Nội ", "nkdc", "300"],
                ["Hải Hậu - Hạ Long - Bãi Cháy/Tuần Châu", "hhhl", "200"],
                ["Hải Hậu - Hạ Long (Hòn gai)", "hhhl", "225"],
                ["Hải Hậu - Hà Nội", "hhhn", "200"],
                ["Hà Nội - Lạng Sơn - thăm Tam Thanh", "hnls", "250"],
                ["TP Lạng Sơn - Bến Bính", "lsbb", "250"],
                ["TP Lạng Sơn - Hạ Long ( Hòn Gai hoặc Tuần Châu)", "lshl", "250"],
                ["Ba Bể - Hải Dương", "bbhd", "400"],
                ["Hải Dương - Hạ Long (Tuần Châu)", "hdhl", "210"],
            ];
            $suggestions =[];
            foreach ($arr_lx as $v) {
                if (strpos(strtolower($v[0]), strtolower($query)) !== false || strpos(strtolower($v[1]), strtolower($query)) !== false) {
                    if ($v[1] != '') {
                        $v[1] = '| '.$v[1];
                    }
                    if ($v[2] == '') {
                        $v[2] = 0;
                    }
                    $obj = ['value' => trim($v[0]).' '.trim($v[1]), 'data' => trim($v[2])];
                    $suggestions[] = $obj;
                }
            }
            $result = [
                'suggestions' => $suggestions
            ];
                echo json_encode($result);
        }
    }



    // In form feedback
    public function actionIn_fb($id = 0, $tcg = 'no')
    {
        $theTour = AtCt::find()
            ->select(['id', 'op_code', 'op_name', 'day_from', 'day_ids'])
            ->where(['id'=>$id, 'op_status'=>'op', 'op_finish'=>''])
            ->with([
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'rid']);
                },
                'bookings'=>function($q) {
                    return $q->select(['id', 'product_id', 'status', 'case_id', 'product_id']);
                },
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'name', 'company_id','is_b2b']);
                },
                // 'bookings.case.company'=>function($q) {
                //     return $q->select(['id', 'name', 'image']);
                // },
            ])
            ->asArray()
            ->one();
        var_dump($theTour['bookings'][0]['case']['is_b2b']);die();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = AtTours::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theCompany = false;
        // if (isset($theTour['bookings'][0]['case']['company'])) {
        //     $theCompany = $theTour['bookings'][0]['case']['company'];
        //     $theCompany['image'] = $theCompany['image'];
        // }

        // $theForm = new PrintFeedbackForm;
        // $theForm->language = 'fr';
        // $theForm->logoName = 'us';
        // $theForm->guideNames = 'yes';
        // $theForm->driverNames = 'yes';
        // if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
        //     $printLogo = Yii::$app->params['print_logo'];
        //     $printName = 'Amica Travel';

        //     if ($tcg == 'yes') {
        //         $printLogo = DIR.'assets/img/logo_tcg_263x102.jpg';
        //         $printName = 'Tam Coc Garden';
        //         $theForm['logoName'] = 'them';
        //     }

        //     if ($theCompany && in_array($theForm['logoName'], ['them', 'none'])) {
        //         $printLogo = $theCompany['image'];
        //         $printName = $theCompany['name'];
        //     }

        //     if ($theForm['logoName'] == 'voyages-villegia') {
        //         $theCompany['name'] = 'Voyages Villegia';
        //         $theCompany['image'] = Yii::getAlias('@www').'/upload/companies/2015-05/294/voyagevillegia_horiz.png';

        //         $printName = 'Voyages Villegia';
        //         $printLogo = Yii::getAlias('@www').'/upload/companies/2015-05/294/voyagevillegia_horiz.png';
        //     }

        //     return $this->renderPartial('tours_in-fb_ok_'.$theForm->language, [
        //         'theTour'=>$theTour,
        //         'theTourOld'=>$theTourOld,
        //         'theForm'=>$theForm,
        //         'printLogo'=>$printLogo,
        //         'printName'=>$printName,
        //         'theCompany'=>$theCompany,
        //     ]);
        // }
        return $this->renderPartial('tours_in-fb_ok_en', [
                'theTour'=>$theTour,
                'theTourOld'=>$theTourOld,
                // 'theForm'=>$theForm,
                // 'printLogo'=>$printLogo,
                'printName'=>'abc',
                // 'theCompany'=>$theCompany,
            ]);
        // return $this->render('tours_in-fb', [
        //     'theTour'=>$theTour,
        //     'theTourOld'=>$theTourOld,
        //     'theForm'=>$theForm,
        //     'theCompany'=>$theCompany,
        // ]);
    }
    /**
     * Creates a new Tour model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tour();

        if ($model->load(Yii::$app->request->post())) {
            $model->start_date = date('Y-m-d',strtotime($model->start_date));
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            ]);
    }

    /**
     * Updates an existing Tour model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->start_date = date('Y-m-d', strtotime($model->start_date));
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            ]);
    }

    /**
     * Deletes an existing Tour model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {   $day_tours = AtNgaymau::find()->where('tour_id=:id', [':id' => $id])->all();
    if ($day_tours != null) {
        AtNgaymau::deleteAll(['tour_id' => $id]);
    }
    $this->findModel($id)->delete();
    return $this->redirect(['index']);
}

    /**
     * Finds the Tour model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tour the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tour::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * update name of Tour model.
     *
     *
     */
    public function actionUpdate_ajax(){
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['title']) && $_POST['title'] != '') {
                $tour = Tour::findOne($_POST['id']);
                if ($tour != null) {
                    $tour->title = $_POST['title'];
                    if ($tour->save()) {
                        return '1';
                    }
                }
                return 'error';
            }
        }
    }
    /**
     * copy a Tour
     *
     *
     */
    public function actionDuplicate($id)
    {
        $returnUrl = Yii::$app->request->referrer;
        $data = $this->findModel($id);
        $model = new Tour();
        $model->title = $data->title;
        $model->excerpt = $data->excerpt;
        $model->start_date = $data->start_date;
        if ($model->load(Yii::$app->request->post())) {
            $model->start_date = date('Y-m-d', strtotime($model->start_date));
            if ($model->save()) {
                $daystour = AtNgaymau::find()->where('tour_id=:id',[':id' => $id])->all();
                if ($daystour != null) {
                    $ids = explode(",", $data->days_id);
                    $arr_ids = [];
                    foreach ($ids as $id) {
                        foreach ($daystour as $value) {
                            if ($value->id == $id) {
                                $day = new AtNgaymau();
                                $day->attributes = $value->attributes;
                                $day->tour_id = $model->id;
                                $day->save();
                                $arr_ids[] = $day->id;
                            }
                        }
                    }
                    if (count($arr_ids) > 0) {
                        $str_ids = implode(",", $arr_ids);
                        $model->days_id = $str_ids;
                        $model->save();
                    }

                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
            ]);
    }
    public function actionUpdate_position()
    {
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['days_id']) && isset($_POST['id']) && $_POST['id'] != '') {
                $tour = Tour::findOne($_POST['id']);
                if($tour != null) {
                    $tour->days_id = $_POST['days_id'];
                    if ($tour->save()) {
                        return 1;
                    }
                }
            }
            return 'empty';
        }
    }

    public function actionReport($id)
    {

        // get your HTML raw content without any layouts or scripts
        $model = Tour::find()->where('id='.$id)->one();
        if ($model == null) {
            throw new \yii\web\HttpException(400, 'null model', 405);
        }
        if ($model->days_id != null) {
            $days_tour = AtNgaymau::find()->where('tour_id=:id', [':id' => $id])->all();
            $content = $this->renderPartial('content_pdf',['model' => $model, 'days_tour' =>$days_tour]);
        }
        // $content = "
        // <b style='color:red'>bold</b>
        
        // <a href='http://latcoding.com'>Latcoding.com</a>
        // ";

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content, 
            'marginTop' => '110px',
            'marginLeft' => 0,
            'marginRight' => 0,
            'marginHeader' => '0',
            'marginBottom' => '10px',
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '
                *{font-family: Candara}
            @page {
                header: html_myHTMLHeader1;
                footer:  html_MyCustomFooter;
            }
            @page :first {
                footer:  html_MyCustomFooter1;
                background-image:url("'.Url::to("@web/images/img/img-first-bg.png", true).'");
                background-position: 0;
                background-image-resize:6;
            }
            .main-content { page-break-before: right;}
            .main-content h1{ font-size: 20px; color: #BC489A; font-weight: bold;}
            .pdf-list-days th{font-size:10px; color: #BC489A; font-weight: bold}
            .pdf-list-days .td-format{ font-size:10px; padding-right: 20px; line-height: 30px}
            .pdf-title{ font-size: 11px; color: #BC489A; display: block; padding-left: 20px}
            .pdf-date-tour{ font-weight: bold;}
            .pdf-list-days-detail {}
            .pdf-content-tour{ font-size: 10px; text-align: justify;}
            .pdf-list-days-detail td{padding: 8px 0;}
            .pdf-list-days-detail td .note-pdf{ font-size: 9px!important;}
            ',
            // set mPDF properties on the fly
            'options' => ['title' => 'Name tour','defaultfooterline' => 0],
             // call mPDF methods on the fly
            'methods' => [
            // 'SetHTMLHeader'=>["<img src='".Url::to('@web/images/img/img-header.png', true)."'/>"],
            // 'SetFooter'=>['<img src="" alt="logo">{PAGENO}'],
            ]
            ]);

        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        // return the pdf output as per the destination setting
        // var_dump($pdf->getApi()); die();
        return $pdf->render();
    }


    public function actionReport1($id)
    {

        // get your HTML raw content without any layouts or scripts
        $model = Tour::find()->where('id='.$id)->one();
        if ($model == null) {
            throw new \yii\web\HttpException(400, 'null model', 405);
        }
        if ($model->days_id != null) {
            $days_tour = AtNgaymau::find()->where('tour_id=:id', [':id' => $id])->all();
            $content = $this->renderPartial('content_pdf1',['model' => $model, 'days_tour' =>$days_tour]);
        }
        // $content = "
        // <b style='color:red'>bold</b>
        
        // <a href='http://latcoding.com'>Latcoding.com</a>
        // ";

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content, 
            'marginTop' => '110px',
            'marginLeft' => 0,
            'marginRight' => 0,
            'marginHeader' => '0',
            'marginBottom' => '80px',
            'marginFooter' => '0',
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '
                *{font-family: Candara}
            @page {
                header: html_myHTMLHeader1;
                footer:  html_MyCustomFooter;
            }
            .main-content h1{ font-size: 16px; color: #ed3691; font-weight: bold; text-align: center; line-height: 30px}
            .pdf-list {padding: 0 20px;}
            .pdf-list .program table { margin-top: 15px; border: 1px solid #bfbfbf}
            .pdf-list .program table tr td, .pdf-list .program table tr th{ border: 1px solid #bfbfbf}
            .pdf-list h3{ font-size: 12px; color: #ed3691; font-weight: bold; border-bottom: 1px solid #ed3691}
            .pdf-list .program table th {color: #ed3691; font-weight: bold; }
            .pdf-list .program table , .pdf-list .program table th{text-align:center; font-size: 11px}
            .participant table tr th{ background-color: #d9d9d9;}
            .participant table tr td {border: 1px solid #bfbfbf}
            .heber table tr th{ background-color: #c8c8c8;}
            ',
            // set mPDF properties on the fly
            'options' => ['title' => 'Name tour','defaultfooterline' => 0],
             // call mPDF methods on the fly
            'methods' => [
            // 'SetHTMLHeader'=>["<img src='".Url::to('@web/images/img/img-header.png', true)."'/>"],
            // 'SetFooter'=>['<img src="" alt="logo">{PAGENO}'],
            ]
            ]);

        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        // return the pdf output as per the destination setting
        // var_dump($pdf->getApi()); die();
        return $pdf->render();
    }
    

}
