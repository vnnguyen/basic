<?php

$relName = '';
$relLink = '';

if (isset($thePost['id'])) {

    if ($thePost['rtype'] == 'case') {
        $theCase = \common\models\Kase::find()->select(['id', 'name'])->where(['id'=>$thePost['rid']])->asArray()->one();
        if ($theCase) {
            $relName = $theCase['name'];
            $relLink = 'cases/r/'.$theCase['id'];

            Yii::$app->params['page_breadcrumbs'] = [
                [Yii::t('x', 'Cases'), 'cases'],
                [$relName, $relLink],
                [Yii::t('x', 'View message')],
            ];
        }

    } elseif ($thePost['rtype'] == 'company') {
        $theCompany = \common\models\Client::find()->select(['id', 'name'])->where(['id'=>$thePost['rid']])->asArray()->one();
        if ($theCompany) {
            $relName = $theCompany['name'];
            $relLink = 'b2b/clients/r/'.$theCompany['id'];

            Yii::$app->params['page_breadcrumbs'] = [
                [Yii::t('x', 'B2B'), 'b2b'],
                [Yii::t('x', 'Clients'), 'b2b/clients'],
                [$relName, $relLink],
                [Yii::t('x', 'View message')],
            ];
        }

    } elseif ($thePost['rtype'] == 'tour') {
        $theTour = \common\models\Tour::find()->select(['id', 'code', 'name', 'ct_id'])
            ->with([
                'product'=>function($q) {
                    return $q->select(['id', 'day_from']);
                }
            ])
            ->where(['id'=>$thePost['rid']])->asArray()->one();
        if ($theTour) {
            $relName = $theTour['code'].' - '.$theTour['name'];
            $relLink = 'tours/r/'.$theTour['id'];

            Yii::$app->params['page_breadcrumbs'] = [
                [Yii::t('x', 'Tours'), 'tours'],
                [substr($theTour['product']['day_from'], 0, 7), 'tours?orderby=startdate&time='.substr($theTour['product']['day_from'], 0, 7)],
                [$theTour['code'], $relLink],
                [Yii::t('x', 'View message')],
            ];
        }

    } elseif ($thePost['rtype'] == 'venue') {
        $theVenue = \app\models\Venue::find()->select(['id', 'stype', 'name'])->where(['id'=>$thePost['rid']])->asArray()->one();
        if ($theVenue) {
            $relName = $theVenue['name'];
            $relLink = 'venues/'.$theVenue['id'];
            Yii::$app->params['page_breadcrumbs'] = [
                [Yii::t('x', 'Venues'), 'venues'],
                [$relName, $relLink],
                [Yii::t('x', 'View message')],
            ];
        }
    } elseif ($thePost['rtype'] == 'user') {
        $theContact = \app\models\Contact::find()->select(['id', 'name'])->where(['id'=>$thePost['rid']])->asArray()->one();
        if ($theContact) {
            $relName = $theContact['name'];
            $relLink = 'contacts/'.$theContact['id'];
            Yii::$app->params['page_breadcrumbs'] = [
                [Yii::t('x', 'Contacts'), 'contacts'],
                [$relName, $relLink],
                [Yii::t('x', 'View message')],
            ];
        }
    } else {
        Yii::$app->params['page_breadcrumbs'] = [
            [Yii::t('x', 'Messages'), 'posts'],
            [Yii::t('x', 'View message')],
        ];

    }
}

Yii::$app->params['page_actions'] = [];
if (isset($thePost['id']) && in_array(SEG3, ['', 'u', 'd'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'posts/'.$thePost['id'], 'active'=>SEG2 != '' && SEG3 == ''],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'posts/'.$thePost['id'].'/u', 'active'=>SEG3 == 'u'],
    ];
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'posts/'.$thePost['id'].'/d', 'active'=>SEG3 == 'd', 'class'=>'text-danger'],
    ];
}
