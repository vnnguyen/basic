<?php
use app\assets\Limitless230hAsset as MainAsset;

MainAsset::register($this);

if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr', 'vi'])) {
    Yii::$app->language = $_GET['lang'];
}

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs("$.backstretch([
    '/assets/img/bg01_160921.jpg',
    '/assets/img/bg02_160921.jpg',
    '/assets/img/bg03_160921.jpg',
    ], {duration:6000, fade:800});");

if (Yii::$app->request->hostName == 'my.secretindochina.com') {
    Yii::$app->params['print_logo'] = 'https://www.secretindochina.com/assets/img/xlogo_new.png.pagespeed.ic.RyWkDerRIz.webp';
}

$this->beginPage();
?><!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <title><?= $this->title ?></title>
    <?php $this->head(); ?>
    <style type="text/css">
    .has-error .help-block {color:red;}
    .has-error input {border-color:red;}
    .content .card {max-width:380px}
    </style>
</head>

<body class="bg-slate-800">
    <?php $this->beginBody(); ?>
    <div class="page-content">
        <div class="content-wrapper">
            <div class="content d-flex justify-content-center align-items-center">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="mb-1"><img alt="<?= Yii::$app->params['brand_name'] ?>" src="<?= Yii::$app->params['print_logo'] ?>" style="height:48px;"></div>
                            <h5 class="mb-0"><?= $this->title ?></h5>
                            <span class="d-block text-muted"><!-- *** --></span>
                            <?php
                            foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
                                if (Yii::$app->session->hasFlash($key)) { ?>
                                            <div class="alert alert-<?= $key ?>"><?= $message ?></div><?
                                }
                            } ?>
                        </div>
                        <?= $content ?>
                        <span class="form-text text-center text-muted">For help, <a href="#">contact your account manager</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody(); ?>
</body>
</html>
<?php
$this->endPage();