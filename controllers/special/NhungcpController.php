<?

namespace app\controllers\special;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\Person;
use common\models\Search;
use common\models\Ct;
use common\models\Cpt;
use common\models\Day;
use common\models\Kase;
use common\models\Inquiry;
use common\models\Message;
use common\models\ProfileTA;
use common\models\Sysnote;
use common\models\Tour;
use common\models\Product;
use common\models\Booking;
use common\models\Task;
use common\models\SampleTourDay;
use common\models\SampleTourProgram;
use Mailgun\Mailgun;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\GridView;
use yii\data\Pagination;
use yii\web\HttpException;

class NhungcpController extends \app\controllers\MyController
{

    public function actionIndex()
    {
        return $this->render('nhungcp_index', [
        ]);
    }

    public function actionNoel2016($action = '', $what = '')
    {
        if ($action == 'download' && $what == 'list1') {
            // $sql = 'SELECT id, fname, lname, gender, email, country_code, FROM at_booking_user bu, at_bookings b, persons u WHERE bu.user_id=u.id AND bu.booking_id=b.id AND b.status="won" AND u.email!="" GROUP BY u.id ORDER BY u.id';
            $sql = 'SELECT bu.user_id FROM at_booking_user bu GROUP BY user_id';
            $paxIds = Yii::$app->db->createCommand($sql)->queryColumn();
            //\fCore::expose($allCustomers); exit;
            $customers = [];
            $groups = array_chunk($paxIds, 1000);
            foreach ($groups as $group) {
                $pax = Person::find()
                    ->select(['id', 'fname', 'lname', 'byear', 'gender', 'email', 'country_code'])
                    ->where(['is_member'=>'no', 'id'=>$group])
                    ->andWhere(['!=', 'email', ''])
                    ->with([
                        'bookings'=>function($q){
                            return $q->select(['id', 'status', 'product_id', 'created_by'])->where(['status'=>'won'])->orderBy('id DESC');
                        },
                        'bookings.product'=>function($q){
                            return $q->select(['id', 'day_from', 'op_finish']);
                        },
                        'bookings.product.tourStats'=>function($q){
                            return $q->select(['tour_id', 'countries']);
                        },
                        'bookings.product.tour'=>function($q){
                            return $q->select(['id', 'ct_id', 'code']);
                        },
                        'bookings.createdBy'=>function($q){
                            return $q->select(['id', 'name'=>'nickname']);
                        },
                        'bookings.product.tour.cskh'=>function($q){
                            return $q->select(['id', 'name'=>'nickname']);
                        },
                        ])
                    ->asArray()
                    ->all();
                $customers = array_merge($customers, $pax);
                // break;
            }

            $filename = 'nhungcp_list1_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

            $arr = ['ID', 'M_MME', 'FIRST NAME', 'SECOND NAME', 'BIRTHYEAR', 'EMAIL', 'COUNTRY', 'LATEST TOUR', 'START DATE', 'COUNTRIES', 'LATEST SELLER', 'LATEST CR'];
            fputcsv($out, $arr);

            foreach ($customers as $pax) {
                $arr = [$pax['id']];
                $arr[] = $pax['gender'] == 'male' ? 'M' : 'MME';
                $arr[] = $pax['fname'];
                $arr[] = $pax['lname'];
                $arr[] = $pax['byear'];
                $arr[] = $pax['email'];
                $arr[] = strtoupper($pax['country_code']);

                $tourCodes = [];
                $tourStartDates = [];
                $tourCountries = [];
                $tourSellers = [];
                $tourCServices = [];
                foreach ($pax['bookings'] as $booking) {
                    if ($booking['status'] == 'won' && !empty($booking['product'])) {
                        $tourCodes[] = $booking['product']['tour']['code'];
                        $tourStartDates[] = $booking['product']['day_from'];
                        $tourCountries[] = strtoupper($booking['product']['tourStats']['countries']); // Ngay khoi hanh
                        $tourSellers[] = $booking['createdBy']['name']; // Ban hang
                        $cskhNames = [];
                        foreach ($booking['product']['tour']['cskh'] as $cskh) {
                            $cskhNames[] = $cskh['name'];
                        }
                        $tourCServices[] = implode(', ', $cskhNames); // CSKH

                    }
                }
                $arr[] = implode('; ', $tourCodes);
                $arr[] = count($tourStartDates) == 1 ? $tourStartDates[0].';' : implode('; ', $tourStartDates);
                $arr[] = implode('; ', $tourCountries);
                $arr[] = implode('; ', $tourSellers);
                $arr[] = implode('; ', $tourCServices); // CSKH
                fputcsv($out, $arr);

            }
            exit;
        }

        // Danh sach nhap moi
        if ($action == 'download' && $what == 'list1a') {
            $sql = 'SELECT tour_id FROM pax';
            $prodIds = Yii::$app->db->createCommand($sql)->queryColumn();
            $sql = 'SELECT p.op_code, p.day_from, x.* FROM pax x, at_ct p WHERE x.tour_id=p.id ORDER BY x.tour_id';
            $customers = Yii::$app->db->createCommand($sql)->queryAll();
            $theProducts = Product::find()
                ->where(['id'=>$prodIds])
                ->with([
                    'bookings'=>function($q){
                        return $q->select(['id', 'status', 'product_id', 'created_by'])->where(['status'=>'won'])->orderBy('id DESC');
                    },
                    'bookings.createdBy'=>function($q){
                        return $q->select(['id', 'name'=>'nickname']);
                    },
                    'tour'=>function($q){
                        return $q->select(['id', 'ct_id', 'code']);
                    },
                    'tourStats'=>function($q){
                        return $q->select(['tour_id', 'countries']);
                    },
                    'tour.cskh'=>function($q){
                        return $q->select(['id', 'name'=>'nickname']);
                    },
                    ])
                    ->asArray()
                    ->all();

            $filename = 'nhungcp_list1a_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

            $arr = ['ID', 'M_MME', 'FIRST NAME', 'SECOND NAME', 'AGE', 'EMAIL', 'COUNTRY', 'LATEST TOUR', 'START DATE', 'COUNTRIES', 'LATEST SELLER', 'LATEST CR'];
            fputcsv($out, $arr);

            foreach ($customers as $pax) {
                $data = unserialize($pax['data']);
                $arr = [''];
                $arr[] = $pax['pp_gender'] == 'male' ? 'M' : 'MME'; // Ngay khoi hanh
                $arr[] = $data['pp_name'];
                $arr[] = $data['pp_name2'];
                $arr[] = substr($data['pp_birthdate'], 0, 4);
                $arr[] = $data['email'];
                $arr[] = strtoupper($pax['pp_country_code']);
                $arr[] = $pax['op_code']; // Ngay khoi hanh
                $arr[] = $pax['day_from']; // Ngay khoi hanh
                foreach ($theProducts as $product) {
                    if ($product['id'] == $pax['tour_id']) {
                        $arr[] = strtoupper($product['tourStats']['countries']); // Ngay khoi hanh
                        $arr[] = $product['bookings'][0]['createdBy']['name']; // Ban hang
                        $cskhNames = [];
                        foreach ($product['tour']['cskh'] as $cskh) {
                            $cskhNames[] = $cskh['name'];
                        }
                        $arr[] = implode(', ', $cskhNames); // CSKH
                    }
                }
                fputcsv($out, $arr);
            }
            exit;

        }

        if ($action == 'download' && $what == 'list4') {
            if (isset($_GET['status']) && $_GET['status'] == 'lost-only') {
                $sql = 'SELECT cu.user_id FROM at_case_user cu, at_cases c WHERE cu.case_id=c.id AND c.deal_status="lost" GROUP BY user_id';
            } else {
                $sql = 'SELECT cu.user_id FROM at_case_user cu, at_cases c WHERE cu.case_id=c.id AND c.deal_status!="won" GROUP BY user_id';
            }
            $paxIds = Yii::$app->db->createCommand($sql)->queryColumn();
            $customers = [];
            $groups = array_chunk($paxIds, 1000);
            foreach ($groups as $group) {
                $pax = Person::find()
                    ->select(['id', 'fname', 'lname', 'gender', 'byear', 'email', 'country_code'])
                    ->where(['is_member'=>'no', 'id'=>$group])
                    ->andWhere(['!=', 'email', ''])
                    // ->with([
                    //     'bookings'=>function($q){
                    //         if (isset($_GET['status']) && $_GET['status'] == 'lost-only') {
                    //             return $q->select(['id'])->where('status!="lost"');
                    //         } else {
                    //             return $q->select(['id'])->where(['status'=>'won']);
                    //         }
                    //     },
                    //     ])
                    ->asArray()
                    ->all();
                $customers = array_merge($customers, $pax);
            }

            $filename = 'nhungcp_list4_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

            $arr = ['ID', 'M_MME', 'FIRST NAME', 'SECOND NAME', 'EMAIL', 'COUNTRY'];
            fputcsv($out, $arr);

            foreach ($customers as $pax) {
                if (empty($pax['bookings'])) {
                    $arr = [$pax['id']];
                    $arr[] = $pax['gender'] == 'male' ? 'M' : 'MME'; // Ngay khoi hanh
                    $arr[] = $pax['fname'];
                    $arr[] = $pax['lname'];
                    $arr[] = $pax['byear'];
                    $arr[] = $pax['email'];
                    $arr[] = strtoupper($pax['country_code']);
                    fputcsv($out, $arr);
                    // echo '<br>', implode(' / ', $arr);
                }
            }
            exit;
        }
        return $this->render('nhungcp_noel2016', [
        ]);
    }
}