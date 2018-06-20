<?php
namespace app\controllers\actions;

use Yii;
use yii\web\Response;

class PaxWhoDidAction extends \yii\base\Action {
    /**
     * Pax who have or have not visited certain countries
     * @return Array
     */
    public function run($visit = '', $notvisit = '', $year = '') {
        // Tours which went to Laos
        $sql = 'SELECT * FROM at_tour_stats WHERE LOCATE("la", countries)!=0';
        $toursWhich['went to Laos'] = Yii::$app->db->createCommand($sql)->queryAll();
        // \fCore::expose($toursWhich['went to Laos']); exit;
        $tourIdList = [];
        foreach ($toursWhich['went to Laos'] as $tour) {
            $tourIdList[] = $tour['tour_id'];
        }
        // Bookings of tours which did not go to Laos
        $bookings = \common\models\Booking::find()
            ->select(['id', 'product_id'])
            ->with([
                'product'=>function($q) {
                    return $q->select(['id', 'op_code', 'op_name'])->where(['not', ['op_finish'=>'canceled']]);
                },
                'pax'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'gender', 'country_code', 'email', 'byear'])->where('email!=""');
                }
                ])
            ->where(['not', ['product_id'=>$tourIdList]])
            ->asArray()
            ->all();

        return $this->render('pax_who_did', [
            'bookings'=>$bookings,
        ]);
    }
}