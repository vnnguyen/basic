<?
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\helpers\Markdown;
?>
<div class="tab-pane" id="t-media">
    <div class="clearfix">
    <?
    if (is_dir(Yii::getAlias('@webroot').'/upload/venues/'.substr($theVenue['created_at'], 0, 7).'/'.$theVenue['id'])) {
        $files = [];
        $files = FileHelper::findFiles(Yii::getAlias('@webroot').'/upload/venues/'.substr($theVenue['created_at'], 0, 7).'/'.$theVenue['id']);
        foreach ($files as $file) {
        ?>
        <a rel="media" class="fancybox" href="<?= str_replace(Yii::getAlias('@webroot'), Yii::getAlias('@www'), $file) ?>"><img style="float:left; margin:0 15px 15px 0;" src="/timthumb.php?w=100&h=100&zc=1&src=<?= str_replace('/var/www/my.amicatravel.com/www/', '/', $file) ?>"></a>
        <?
        }
    }
    ?>
    </div>
    <br>
    <div class="clearfix">
        <? if ((($theVenue['images_booking'] == '' && $theVenue['link_booking'] != '') || (Yii::$app->request->get('get') == 'booking')) && in_array(Yii::$app->user->id, [1,9198,22324])) { ?>
        <form method="post" action="">
            <p><textarea rows="10" class="form-control" name="html"></textarea></p>
            <p><button type="submit" class="btn btn-primary">Submit</button> (search for "slideshow_photos")</p>
        </form>
        <? } ?>
        <?
        if ($theVenue['images_booking'] != '') {
            $imgs = str_replace(['<img src="', '<img src= "', '">', chr(10), chr(13)], ['', '', '|', '', ''], $theVenue['images_booking']);
            $imgs = explode('|', trim($imgs, '|'));

            foreach ($imgs as $img) {
                echo Html::a(Html::img($img, ['style'=>'float:left; height:100px; margin:0 8px 8px 0;']), $img, ['rel'=>'gallery', 'class'=>'fancybox']);
            }
        }
        ?>
    </div>
</div>