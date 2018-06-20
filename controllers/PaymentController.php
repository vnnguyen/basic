<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Payment;
use common\models\User;
use common\models\Booking;
use common\models\Product;
use common\models\Tour;

class PaymentController extends MyController
{

    public function actionIndex()
    {
        $getBookingId = Yii::$app->request->get('booking_id', '');
        $getProductId = Yii::$app->request->get('product_id', '');
        $getMonth = Yii::$app->request->get('month', '');
        $getTour = Yii::$app->request->get('tour', '');
        $getMethod = Yii::$app->request->get('method', '');
        $getNote = Yii::$app->request->get('note', '');
        $getLimit = Yii::$app->request->get('limit', 25);
        $theBookings = [];

        if (!in_array((int)$getLimit, [25, 50, 100, 500])) {
            $getLimit = 25;
        }

        $query = Payment::find();

        if ($getMonth != '') {
            $query->andWhere('SUBSTRING(payment_dt,1,7)=:ym', [':ym'=>$getMonth]);
        }

        if (strlen($getTour) > 2) {
            $theProduct = Product::find()
                ->where(['like', 'op_code', $getTour])
                ->asArray()
                ->one();
        }

        if ((int)$getProductId != 0) {
            $theProduct = Product::find()
                ->where(['id'=>$getProductId])
                ->asArray()
                ->one();
        }

        if (isset($theProduct)) {
            $getTour = $theProduct['op_code'];
            $theBookings = Booking::find()
                ->select(['id'])
                ->where(['product_id'=>$theProduct['id']])
                ->asArray()
                ->all();
            $bookingIds = [];
            foreach ($theBookings as $booking) {
                $bookingIds[] = $booking['id'];
            }
            $query->andWhere(['booking_id'=>$bookingIds]);
        } else {
            $getTour = '';
            // $query->andWhere(['booking_id'=>0]);
        }

        if (strlen($getMethod) > 2) {
            $query->andWhere(['like', 'method', $getMethod]);
        }

        if (strlen($getNote) > 2) {
            $query->andWhere(['like', 'note', $getNote]);
        }


        if ((int)$getBookingId != 0) {
        }

        $monthList = Yii::$app->db->createCommand('SELECT SUBSTRING(payment_dt,1,7) AS ym FROM at_payments GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        $methodList = Yii::$app->db->createCommand('SELECT method FROM at_payments GROUP BY method ORDER BY method')
            ->queryAll();

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>$getLimit,
        ]);

        $thePayments = $query
            ->orderBy('payment_dt DESC')
            ->offset($pagination->offset)
            ->limit($getLimit)
            ->with([
                'invoice',
                'booking',
                'booking.product.tour',
                'booking.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                /*'tour'=>function($query) {
                    return $query->select(['id', 'ct_id', 'code', 'name']);
                },*/
                'createdBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname', 'image']);
                },
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

        return $this->render('payment_index', [
            'monthList'=>$monthList,
            'methodList'=>$methodList,
            'getMonth'=>$getMonth,
            'getTour'=>$getTour,
            'getMethod'=>$getMethod,
            'getNote'=>$getNote,
            'thePayments'=>$thePayments,
            'theBookings'=>$theBookings,
            'getLimit'=>$getLimit,
            'pagination'=>$pagination,
        ]);
    }

    public function actionC($booking_id = 0) {
        $theBooking = Booking::find()
            ->where(['id'=>$booking_id])
            ->with(['product', 'case', 'createdBy'])
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        $bookingOwner = User::find()
            ->where(['id'=>$theBooking['created_by']])
            ->asArray()
            ->one();

        $thePayment = new Payment;      
        $thePayment->scenario = 'payments_c';

        if ($thePayment->load(Yii::$app->request->post()) && $thePayment->validate()) {

            $thePayment->booking_id = $theBooking['id'];
            $thePayment->created_at = NOW;
            $thePayment->created_by = Yii::$app->user->id;
            $thePayment->updated_at = NOW;
            $thePayment->updated_by = Yii::$app->user->id;
            $thePayment->status = 'on';

            if ($thePayment->save(false)) {
                // Email
                if ($bookingOwner) {
                    $this->mgIt(
                        '[ims] Payment received: '.$thePayment['ref'].' / '.$thePayment['method'].' / '.$thePayment['amount'].' '.$thePayment['currency'],
                        '//payment_received',
                        [
                            'thePayment'=>$thePayment,
                            'theBooking'=>$theBooking,
                        ],
                        [
                            ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                            ['to', $bookingOwner['email'], $bookingOwner['fname'], $bookingOwner['lname']],
                            ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                            // ['attachment', 'filePath', 'fileName'],
                        ]
                    );
                }

                Yii::$app->session->setFlash('success', 'Payment has been added: '.number_format($thePayment['amount'], 2).' '.$thePayment['currency']);
                return $this->redirect('@web/payments');
            }
        }

        return $this->render('payments_u', [
            'thePayment'=>$thePayment,
            'theBooking'=>$theBooking,
        ]);
    }

    public function actionR($id = 0)
    {
        $thePayment = Payment::find()
            ->where(['id'=>$id])
            ->with([
                'updatedBy',
                'invoice',
                'booking',
                'booking.product',
                'booking.product.tour'
            ])
            ->asArray()
            ->one();

        if (!$thePayment) {
            throw new HttpException(404, 'Payment not found.');
        }

        return $this->render('payments_r', [
            'thePayment'=>$thePayment,
        ]);
    }

    public function actionU($id = 0) {
        $thePayment = Payment::find()
            ->where(['id'=>$id])
            ->with(['createdBy', 'booking', 'booking.product', 'booking.product.tour'])
            ->one();

        if (!$thePayment) {
            throw new HttpException(404, 'Payment not found.');
        }

        if (!in_array(Yii::$app->user->id, [$thePayment['created_by']])) {
            throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        $thePayment->scenario = 'payments_u';

        if ($thePayment->load(Yii::$app->request->post()) && $thePayment->validate()) {
            $thePayment->updated_at = NOW;
            $thePayment->updated_by = Yii::$app->user->id;
            if ($thePayment->save(false)) {
                Yii::$app->session->setFlash('success', 'Payment has been updated');
                return $this->redirect('@web/bookings/r/'.$thePayment['booking_id']);
            }
        }

        return $this->render('payments_u', [
            'thePayment'=>$thePayment,
        ]);
    }

    public function actionD($id = 0)
    {
        $thePayment = Payment::find()
            ->where(['id'=>$id])
            //->with(['product', 'case'])
            ->one();
        if (!$thePayment) {
            throw new HttpException(404, 'Payment not found');
        }

        // Must be case owner
        if (!in_array(Yii::$app->user->id, [1, $thePayment['created_by']])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->post('confirm') == 'delete') {
            $thePayment->delete();
            return $this->redirect('@web/bookings/r/'.$thePayment['booking_id']);
        }

        return $this->render('payments_d', [
            'thePayment'=>$thePayment,
        ]);
    }
}
