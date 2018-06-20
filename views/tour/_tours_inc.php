<?
yap('page_icon', 'car');

if (isset($theTour['id'], $theTourOld)) {
    Yii::$app->params['page_actions'] = [
        [
            ['icon'=>'list', 'title'=>'Tours in this month', 'link'=>'tours', 'active'=>SEG2 == ''],
        ],
    ];

    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'tours/r/'.$theTourOld['id'], 'active'=>SEG2 == 'r'],
        ['submenu'=>[
            ['icon'=>'file-text-o', 'label'=>'View/edit itinerary', 'link'=>'products/r/'.$theTour['id']],
            ['-'],
            ['icon'=>'car', 'label'=>'Tour guides and drivers', 'link'=>'tours/gx/'.$theTourOld['id']],
            ['icon'=>'user', 'label'=>'Tour guides', 'link'=>'tours/guides/'.$theTour['id']],
            ['icon'=>'car', 'label'=>'Drivers', 'link'=>'tours/drivers/'.$theTour['id']],
            ['icon'=>'heart', 'label'=>'Assign customer care staff', 'link'=>'tours/cskh/'.$theTour['id']],
            ['icon'=>'flag', 'label'=>'Assign tour operator SGN', 'link'=>'tours/u/'.$theTourOld['id'].'?for=dhsg', 'hidden'=>!in_array(USER_ID, [1,118,25457])],
            ['icon'=>'flag', 'label'=>'Assign tour operator Đức Anh', 'link'=>'tours/u/'.$theTourOld['id'].'?for=ducanh', 'hidden'=>!in_array(USER_ID, [1,118,8162])],
            ['icon'=>'meh-o', 'label'=>'Tour points', 'link'=>'tours/ratings/'.$theTour['id']],
            ['icon'=>'comment-o', 'label'=>'Tour feedback', 'link'=>'tours/feedback/'.$theTour['id']],
            ['-'],
            ['icon'=>'print', 'label'=>'Print itinerary', 'link'=>'tours/in-ct/'.$theTour['id']],
            ['icon'=>'print', 'label'=>'Print tour sheet', 'link'=>'tours/in-cp/'.$theTourOld['id']],
            ['icon'=>'print', 'label'=>'Print for tour guide', 'link'=>'tours/in-hf/'.$theTour['id']],
            ['icon'=>'print', 'label'=>'Print feedback', 'link'=>'tours/in-fb/'.$theTour['id']],
            ['icon'=>'print', 'label'=>'Print welcome banner', 'link'=>'tours/in-bn/'.$theTour['id']],
            ['icon'=>'print', 'label'=>'Print vehicle booking', 'link'=>'tours/in-lx/'.$theTour['id']],
            ['-'],
            ['icon'=>'file-pdf-o', 'label'=>'PDF summary', 'link'=>'tours/summary/'.$theTour['id']],
            ['-'],
            ['icon'=>'edit', 'label'=>'Edit tour info', 'link'=>'tours/u/'.$theTourOld['id']],
            ['icon'=>'times', 'label'=>'Cancel tour', 'link'=>'tours/cxl/'.$theTour['id'], 'visible'=>$theTourOld['status'] != 'deleted'],
            ],
        ],
    ];

    Yii::$app->params['page_actions'][] = [
        ['icon'=>'users', 'title'=>'Pax list', 'link'=>'tours/pax/'.$theTour['id'], 'active'=>SEG2 == 'pax'],
    ];

    Yii::$app->params['page_actions'][] = [
        ['icon'=>'dollar', 'title'=>'Tour costs', 'link'=>'tours/services/'.$theTourOld['id'], 'active'=>SEG2 == 'services'],
        ['submenu'=>[
            ['icon'=>'dollar', 'label'=>'Tour costs (điều hành)', 'link'=>'tours/services/'.$theTourOld['id'], 'active'=>false],
            ['icon'=>'dollar', 'label'=>'Tour costs (bảng CPT)', 'link'=>'cpt?tour='.$theTourOld['code'], 'active'=>false],
            ],
        ],
    ];

}