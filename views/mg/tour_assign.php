<div style="width:600px;">
    <p>--- DON'T REPLY TO THIS EMAIL | ĐỪNG TRẢ LỜI EMAIL NÀY ---</p>
    <p>Tour code and name: <?= $theTourOld['code'] ?> - <?= $theTourOld['name'] ?></p>
    <p>Start date: <?= date('D j/n/Y', strtotime($theTour['day_from'])) ?></p>
    <p>Number of pax: <?= $theTour['pax'] ?></p>
    <p>Link to details: https://my.amicatravel.com/tours/r/<?= $theTourOld['id'] ?></p>
    <p>&mdash;<br />Sent by <?= Yii::$app->user->identity->nickname ?></p>
</div>
