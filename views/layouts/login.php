<?
use yii\helpers\Html;

app\assets\Bootstrap3Asset::register($this);

$this->registerMetaTag(['name'=>'viewport', 'content'=>'width=device-width, initial-scale=1.0']);
$this->registerMetaTag(['http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge']);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs("$.backstretch([
    '/assets/img/bg01_160921.jpg?x',
    '/assets/img/bg02_160921.jpg?x',
    '/assets/img/bg03_160921.jpg?x',
    ], {duration:6000, fade:800});");

$css = <<<TXT
#wrap {max-width:350px; margin:80px auto 0; opacity:0.9; background-color:#fff;}
    #wrap2 {padding:24px;}
        #hd {text-align:center; font-size:16px; font-weight:bold; color:#C4519D;}
        h3 {margin:0 0 16px; text-align:center;}
        input[type], select {border-radius:0; -moz-border-radius:0; -webkit-border-radius:0;}
        label {font-weight:normal;}
        #ft {text-align:center; color:#747474;}
TXT;
$this->registerCss($css);

$this->beginPage();

?><!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
    <head>
        <meta charset="utf-8" />
        <title><?= Html::encode($this->title) ?></title>
        <? $this->head(); ?>
    </head>
    <body>
        <? $this->beginBody(); ?>
        <div id="wrap">
            <div id="wrap2">
                <div id="hd"><img style="height:60px;" alt="<?= Yii::$app->params['brand_name'] ?>" src="<?= Yii::$app->params['print_logo'] ?>"></div>
                <hr>
                <h3><?= $this->title ?></h3>
                <?= $content ?>
                <hr>
                <div id="ft">For help, call (04) 6273 4455</div>
            </div>
        </div>
        <? $this->endBody(); ?>
    </body>
</html>
<? $this->endPage();