<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Invoice;
use common\models\InvoiceQcForm;
use common\models\User;
use common\models\Booking;
use common\models\Product;
use common\models\Tour;
use \kartik\mpdf\Pdf;
use \Mpdf\Mpdf;

class InvoiceController extends MyController
{

    public function actionIndex($brand = '')
    {
        $getMonth = Yii::$app->request->get('month', 'all');
        $getStatus = Yii::$app->request->get('status', 'all');
        $getPayment = Yii::$app->request->get('payment', 'all');
        $getMethod = Yii::$app->request->get('method', 'all');
        $getCurrency = Yii::$app->request->get('currency', 'all');
        $getGateway = Yii::$app->request->get('gateway', '');
        $getBy = Yii::$app->request->get('by', 'all');
        $getBillTo = Yii::$app->request->get('billto', '');
        $getBookingId = Yii::$app->request->get('booking_id', 0);
        $getProductId = Yii::$app->request->get('product_id', 0);

        $sql = 'SELECT SUBSTRING(due_dt, 1, 7) AS ym FROM invoices GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();

        $query = Invoice::find();

        if (strlen($getMonth) == 7) {
            $query->andWhere('SUBSTRING(due_dt,1,7)=:ym', [':ym'=>$getMonth]);
        }

        if (in_array($brand, ['at', 'si'])) {
            $query->andWhere(['brand'=>$brand]);
        }
        if (in_array($getStatus, ['active', 'draft', 'canceled'])) {
            $query->andWhere(['status'=>$getStatus]);
        }
        if ($getPayment == 'paid' || $getPayment == 'unpaid') {
            $query->andWhere(['payment_status'=>$getPayment]);
        } elseif ($getPayment == 'overdue') {
            $query->andWhere(['payment_status'=>'unpaid'])->andWhere('due_dt < :now', [':now'=>date('Y-m-d')]);
        }

        if (in_array($getMethod, ['cash', 'card', 'transfer'])) {
            $query->andWhere(['method'=>$getMethod]);
        }

        if (in_array($getCurrency, ['EUR', 'USD', 'VND'])) {
            $query->andWhere(['currency'=>$getCurrency]);
        }

        if ($getBy != 'all') {
            $query->andWhere(['nho_thu'=>$getBy]);
        }

        if (strlen($getGateway) > 2) {
            $query->andWhere(['like', 'gw_name', $getGateway]);
        }

        if ($getBillTo != '') {
            $query->andWhere(['like', 'bill_to_name', $getBillTo]);
        }

        if ($getProductId != 0) {
            $query->andWhere(['booking_id'=>$getBookingId]);
        }

        if ($getBookingId != 0) {
            $query->andWhere(['booking_id'=>$getBookingId]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>strlen($getMonth) == 7 ? 1000 : 25,
        ]);

        $theInvoices = $query
            ->orderBy('payment_status DESC, due_dt')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with([
                /*'tour'=>function($query) {
                    return $query->select(['id', 'ct_id', 'code', 'name']);
                },*/
                'createdBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname', 'image']);
                },
                'booking',
                'booking.createdBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname', 'image']);
                },
                'booking.product.tour',
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname', 'image']);
                },/*
                'days'=>function($query) {
                    return $query->select(['id', 'name', 'meals', 'rid']);
                },
                'cases'=>function($query) {
                    return $query->select(['id', 'name']);
                },*/
                ])
            ->asArray()
            ->all();

        return $this->render('invoice_index', [
            'brand'=>$brand,
            'getMonth'=>$getMonth,
            'getStatus'=>$getStatus,
            'getPayment'=>$getPayment,
            'getMethod'=>$getMethod,
            'getCurrency'=>$getCurrency,
            'getBy'=>$getBy,
            'getGateway'=>$getGateway,
            'getBillTo'=>$getBillTo,
            'getBookingId'=>$getBookingId,
            'getProductId'=>$getProductId,
            'theInvoices'=>$theInvoices,
            'pagination'=>$pagination,
            'monthList'=>$monthList,
        ]);
    }

    // Quickly creates several invoices
    public function actionQc($booking_id = 0, $user_id = 0) {
        $theBooking = Booking::find()
            ->where(['id'=>$booking_id])
            ->with(['product', 'case', 'createdBy'])
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        $theForm = new InvoiceQcForm;
        $theForm->opCode = $theBooking['product']['op_code'];
        $theForm->opName = $theBooking['product']['title'];

        $theForm->lang = $theBooking['case']['language'];
        $theForm->currency = $theBooking['currency'];
        $theForm->cost = round($theBooking['price']);
        $theForm->deposit = round(0.1 * $theBooking['price']);

        // For user
        if ((int)$user_id != 0) {
            $theUser = User::find()->where(['id'=>(int)$user_id])->with(['metas'])->asArray()->one();
            if ($theUser) {
                $theForm->paxName = $theUser['fname'].' '.$theUser['lname'];
                foreach ($theUser['metas'] as $meta) {
                    if ($meta['k'] == 'address' && trim($meta['v'] != '')) {
                        $theForm->paxAddr = trim($meta['v']);
                        break;
                    }
                }
                if ($theUser['email'] != '') {
                    $theForm->paxAddr .= PHP_EOL.'Email: '.trim(strtolower($theUser['email']));
                }
            }
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            return $this->redirect('@web/bookings/r/'.$theBooking['id']);
        }

        return $this->render('invoices_qc', [
            'theForm'=>$theForm,
            'theBooking'=>$theBooking,
        ]);
    }
    public function actionC($booking_id = 0, $for = 0) {
        $theBooking = Booking::find()
            ->where(['id'=>$booking_id])
            ->with(['product', 'case', 'createdBy'])
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        $count = Invoice::find()->where(['booking_id'=>$theBooking['id']])->count();

        $theInvoice = new Invoice;
        $theInvoice->scenario = 'invoices_c';
        $theInvoice->ref = $theBooking['product']['op_code'].'-'.str_pad($count + 1, '2', '0', STR_PAD_LEFT);
        $theInvoice->sig_client = 'name';
        $theInvoice->sig_seller = 'name';
        $theInvoice->due_dt = date('Y-m-d H:i', strtotime('+2 weeks'));
        $theInvoice->body = 'Organisation du voyage "'.$theBooking['product']['title'].'"|'.round($theBooking['price']).'|1'.chr(10).'Acompte de 10% du prix total du voyage "'.$theBooking['product']['title'].'"|'.round(0.1 * $theBooking['price']).'|1';
        $theInvoice->body2 = 'Acompte|-'.round(0.1 * $theBooking['price']);
        $theInvoice->body3 = 'Frais bancaire|3%';
        $theInvoice->note_invoice = 'Taux de change: http://www.vietcombank.com.vn/ExchangeRates';

        // For user
        if ((int)$for != 0) {
            $theUser = User::find()
                ->where(['id'=>(int)$for])
                ->with(['metas'])
                ->asArray()
                ->one();
            if ($theUser) {
                $theInvoice->bill_to_name = $theUser['fname'].' '.$theUser['lname'];
                foreach ($theUser['metas'] as $meta) {
                    if ($meta['k'] == 'address' && trim($meta['v'] != '')) {
                        $theInvoice->bill_to_address = trim($meta['v']);
                        break;
                    }
                }
                if ($theUser['email'] != '') {
                    $theInvoice->bill_to_address .= PHP_EOL.'Email: '.trim(strtolower($theUser['email']));
                }
            }
        }

        if ($theInvoice->load(Yii::$app->request->post()) && $theInvoice->validate()) {

            $theInvoice->booking_id = $theBooking['id'];
            $theInvoice->created_at = NOW;
            $theInvoice->created_by = MY_ID;
            $theInvoice->updated_at = NOW;
            $theInvoice->updated_by = MY_ID;
            $theInvoice->status = 'active';

            if (isset($_POST['desc1'], $_POST['qty1'], $_POST['price1']) && is_array($_POST['desc1'])) {
                $lines = [];
                for ($i = 0; $i < count($_POST['desc1']); $i ++) {
                    $lines[] = $_POST['desc1'][$i].'|'.$_POST['price1'][$i].'|'.$_POST['qty1'][$i];
                }
                $theInvoice->body = implode(chr(10), $lines);
            }
            if (isset($_POST['desc2'], $_POST['price2']) && is_array($_POST['desc2'])) {
                $lines = [];
                for ($i = 0; $i < count($_POST['desc2']); $i ++) {
                    $lines[] = $_POST['desc2'][$i].'|'.$_POST['price2'][$i];
                }
                $theInvoice->body2 = implode(chr(10), $lines);
            }
            if (isset($_POST['desc3'], $_POST['price3']) && is_array($_POST['desc3'])) {
                $lines = [];
                for ($i = 0; $i < count($_POST['desc3']); $i ++) {
                    $lines[] = $_POST['desc3'][$i].'|'.$_POST['price3'][$i];
                }
                $theInvoice->body3 = implode(chr(10), $lines);
            }

            $theInvoice->amount = $this->calculateTotal($theInvoice['body'], $theInvoice['body2']);

            if ($theInvoice->save(false)) {
                Yii::$app->session->setFlash('success', 'Invoice has been added: '.$theInvoice['ref']);
                return $this->redirect('@web/bookings/r/'.$booking_id);
            }
        }

        return $this->render('invoices_u', [
            'theInvoice'=>$theInvoice,
            'theBooking'=>$theBooking,
        ]);
    }

    public function actionR($id = 0)
    {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->with(['createdBy', 'updatedBy', 'booking', 'booking.product', 'booking.product.tour'])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        return $this->render('invoice_r', [
            'theInvoice'=>$theInvoice,
        ]);
    }

    // Copy invoice
    public function actionCopy($id = 0)
    {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->with(['createdBy', 'booking', 'booking.product', 'booking.product.tour'])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        $newInvoice = new Invoice();
        $newInvoice->setAttributes($theInvoice->getAttributes(), false);
        $newInvoice->ref = $theInvoice->ref.'-COPY';
        $newInvoice->created_at = NOW;
        $newInvoice->created_by = MY_ID;
        $newInvoice->updated_at = NOW;
        $newInvoice->updated_by = MY_ID;
        $newInvoice->status = 'draft';
        $newInvoice->payment_status = 'unpaid';
        $newInvoice->link = '';
        $newInvoice->id = null;
        $newInvoice->save(false);

        Yii::$app->session->setFlash('success', 'New invoice has been created by copying.');
        return $this->redirect('@web/invoices/u/'.$theInvoice->id);
    }

    // Print invoice
    public function actionP($id = 0)
    {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->with(['createdBy', 'updatedBy', 'booking', 'booking.product', 'booking.case', 'booking.product.tour'])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        if (!in_array($theInvoice['lang'], ['en', 'fr', 'vi'])) {
            $theInvoice['lang'] = 'en';
        }
        Yii::$app->language = $theInvoice['lang'];

        return $this->render('invoice_p', [
            'theInvoice'=>$theInvoice,
        ]);
    }


    // Print invoice as pdf
    public function actionPdf($id = 0, $op_cur_stype1 = '' , $op_cur_xrate1= 0, $op_cur_stype2 = '' , $op_cur_xrate2= 1)
    {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->with(['createdBy', 'updatedBy', 'booking', 'booking.product', 'booking.product.tour'])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        if (!in_array($theInvoice['lang'], ['en', 'fr', 'vi'])) {
            $theInvoice['lang'] = 'en';
        }

        $html = $this->renderPartial('invoice_pdf', [
            'theInvoice'=>$theInvoice,
            'op_cur_stype1'=>$op_cur_stype1,
            'op_cur_xrate1'=>$op_cur_xrate1,
            'op_cur_stype2'=>$op_cur_stype2,
            'op_cur_xrate2'=>$op_cur_xrate2,
        ]);

        $mpdf = new Mpdf();
        $mpdf->SetTitle('Invoice - ' . $theInvoice['ref']);
        $mpdf->SetAuthor('Amica Travel');
        $mpdf->SetSubject('Invoice v.170706');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $fileName = 'Invoice - ' . $theInvoice['ref'] . '.pdf';
        $mpdf->Output($fileName, 'I');
    }

    public function actionU($id = 0) {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        $theBooking = Booking::find()
            ->where(['id'=>$theInvoice['booking_id']])
            ->with(['product', 'case', 'createdBy'])
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        if (!in_array(MY_ID, [1, $theInvoice['created_by'], $theInvoice['updated_by'], $theBooking['case']['owner_id'], $theInvoice['booking']['created_by']])) {
            // throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        // if ($theInvoice['payment_status'] == 'paid') {
        //     throw new HttpException(403, 'Access denied. The invoice has been paid.');  
        // }

        $theInvoice->scenario = 'invoices_u';

        if ($theInvoice->load(Yii::$app->request->post()) && $theInvoice->validate()) {
            $theInvoice->updated_at = NOW;
            $theInvoice->updated_by = MY_ID;

            if (isset($_POST['desc1'], $_POST['qty1'], $_POST['price1']) && is_array($_POST['desc1'])) {
                $lines = [];
                for ($i = 0; $i < count($_POST['desc1']); $i ++) {
                    $lines[] = $_POST['desc1'][$i].'|'.$_POST['price1'][$i].'|'.$_POST['qty1'][$i];
                }
                $theInvoice->body = implode(chr(10), $lines);
            }
            if (isset($_POST['desc2'], $_POST['price2']) && is_array($_POST['desc2'])) {
                $lines = [];
                for ($i = 0; $i < count($_POST['desc2']); $i ++) {
                    $lines[] = $_POST['desc2'][$i].'|'.$_POST['price2'][$i];
                }
                $theInvoice->body2 = implode(chr(10), $lines);
            }
            if (isset($_POST['desc3'], $_POST['price3']) && is_array($_POST['desc3'])) {
                $lines = [];
                for ($i = 0; $i < count($_POST['desc3']); $i ++) {
                    $lines[] = $_POST['desc3'][$i].'|'.$_POST['price3'][$i];
                }
                $theInvoice->body3 = implode(chr(10), $lines);
            }

            $theInvoice->amount = $this->calculateTotal($theInvoice['body'], $theInvoice['body2']);

            if ($theInvoice->save(false)) {
                Yii::$app->session->setFlash('success', 'Invoice has been updated');
                //return $this->redirect('@web/bookings/r/'.$theInvoice['booking']['id']);
                return $this->redirect('@web/bookings/r/'.$theInvoice['booking_id']);
            }
        }

        return $this->render('invoices_u', [
            'theInvoice'=>$theInvoice,
            'theBooking'=>$theBooking,
        ]);
    }

    // Mark as paid
    public function actionMp($id = 0) {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        $theBooking = Booking::find()
            ->where(['id'=>$theInvoice['booking_id']])
            ->with(['product', 'case', 'createdBy'])
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        if (!in_array(MY_ID, [1, 17, 11, 4065])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theInvoice['status'] != 'active') {
            throw new HttpException(403, 'Access denied. Invoice is not active.');
        }

        if ($theInvoice['payment_status'] == 'paid') {
            throw new HttpException(403, 'Access denied. Invoice has been paid.');
        }

        $theInvoice->updated_at = NOW;
        $theInvoice->updated_by = MY_ID;
        $theInvoice->payment_status = 'paid';
        if ($theInvoice->save(false)) {
            Yii::$app->session->setFlash('success', 'Invoice has been updated');
        }
        return $this->redirect('@web/bookings/r/'.$theInvoice['booking_id']);
    }

    // Mark as unpaid
    public function actionMu($id = 0) {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found.');
        }

        $theBooking = Booking::find()
            ->where(['id'=>$theInvoice['booking_id']])
            ->with(['product', 'case', 'createdBy'])
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        if (!in_array(MY_ID, [1, 17, 11, 4065])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theInvoice['status'] != 'active') {
            throw new HttpException(403, 'Access denied. Invoice is not active.');
        }

        if ($theInvoice['payment_status'] == 'unpaid') {
            throw new HttpException(403, 'Access denied. Invoice is unpaid.');
        }

        $theInvoice->updated_at = NOW;
        $theInvoice->updated_by = MY_ID;
        $theInvoice->payment_status = 'unpaid';
        if ($theInvoice->save(false)) {
            Yii::$app->session->setFlash('success', 'Invoice has been updated');
        }
        return $this->redirect('@web/bookings/r/'.$theInvoice['booking_id']);
    }

    public function actionD($id = 0)
    {
        $theInvoice = Invoice::find()
            ->where(['id'=>$id])
            //->with(['product', 'case'])
            ->one();
        if (!$theInvoice) {
            throw new HttpException(404, 'Invoice not found');
        }

        // Must be case owner
        if (!in_array(Yii::$app->user->id, [1, $theInvoice['created_by'], $theInvoice['booking']['created_by']])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->post('confirm') == 'delete') {
            $theInvoice->delete();
            return $this->redirect('@web/bookings/r/'.$theInvoice['booking_id']);
        }

        return $this->render('invoices_d', [
            'theInvoice'=>$theInvoice,
        ]);
    }

    // Calculate total due from body text
    public function calculateTotal($body1, $body2)
    {
        $total = 0;
        $lines = explode(PHP_EOL, $body1);
        foreach ($lines as $line) {
            $line = trim($line);
            $parts = explode('|', $line);
            if (isset($parts[2])) {
                $value = (float)$parts[1] * (float)$parts[2];
                $total += $value;
            }
        }

        $lines = explode(PHP_EOL, $body2);
        foreach ($lines as $line) {
            $line = trim($line);
            $parts = explode('|', $line);
            if (isset($parts[0]) && count($parts) == 2 && trim($parts[0]) != '') {
                if (strpos($parts[1], '%') !== false) {
                    $value = 0.01 * $total * (float)$parts[1];
                } else {
                    $value = (float)$parts[1];
                }
                $total += $value;
            }
        }
        return round($total);
    }
}
