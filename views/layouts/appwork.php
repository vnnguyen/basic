<?php
use yii\helpers\Html;
use app\assets\AppworkAsset;

include('_nav.php');
// include('_css_limitless.php');
// include('_css_theadmin.php');
include('_js.php');
// include('_js_theadmin.php');
include('../config/functions.php');

AppworkAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

Yii::$app->params['body_class'] = Yii::$app->params['body_class'] ?? '';

// OLD IMS
Yii::$app->params['page_breadcrumbs'] = Yii::$app->params['page_breadcrumbs'] ?? $this->params['breadcrumb'] ?? null;
Yii::$app->params['page_actions'] = Yii::$app->params['page_actions'] ?? $this->params['actions'] ?? null;

if (isset(Yii::$app->params['page_js'])) {
    $this->registerJs(Yii::$app->params['page_js']);
}
if (isset(Yii::$app->params['page_css'])) {
    $this->registerCss(Yii::$app->params['page_css']);
}

$this->beginPage();

?><!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="default-style layout-fixed layout-collapsed">
<head>
    <title><?= Yii::$app->params['page_meta_title'] ?> +ims</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <?php $this->head(); ?>
    <?php if (in_array(USER_ID, [111])) { ?>
    <script>
    window.themeSettings = new ThemeSettings({
        cssPath: '/themes/appwork_1.2.0/assets/vendor/css/rtl/',
        themesPath: '/themes/appwork_1.2.0/assets/vendor/css/rtl/'
    });
    </script>
    <?php } ?>
    <style type="text/css">
    body {overflow-y:scroll;}
    .has-error input, .has-error select, .has-error textarea {border-color:red;}
    .has-error .help-block {color:red;}
    .has-success input, .has-success select, .has-success textarea {border-color:green;}
    .has-success .help-block {color:green;}
    .help-block {font-size:.9rem}
    .select2-container {z-index:9999!important;}
    .select2-results__option.select2-results__option--highlighted a {color:#fff!important;}

    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto;}
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    /** RIBBON **/
.ribbon {
  position: absolute;
  top: -3px;
  left: -3px;
  width: 150px;
  height: 150px;
  text-align: center;
  background-color: transparent;
}

.ribbon-inner {
  position: absolute;
  top: 16px;
  left: 0;
  display: inline-block;
  max-width: 100%;
  height: 30px;
  padding-right: 20px;
  padding-left: 20px;
  overflow: hidden;
  line-height: 30px;
  color: #fff;
  text-overflow: ellipsis;
  white-space: nowrap;
  background-color: #526069;
}
.ribbon-inner .icon {
  font-size: 16px;
}

.ribbon-lg .ribbon-inner {
  height: 38px;
  font-size: 1.286rem;
  line-height: 38px;
}

.ribbon-sm .ribbon-inner {
  height: 26px;
  font-size: .858rem;
  line-height: 26px;
}

.ribbon-xs .ribbon-inner {
  height: 22px;
  font-size: .858rem;
  line-height: 22px;
}

.ribbon-vertical .ribbon-inner {
  top: 0;
  left: 16px;
  width: 30px;
  height: 60px;
  padding: 15px 0;
}

.ribbon-vertical.ribbon-xs .ribbon-inner {
  width: 22px;
  height: 50px;
}

.ribbon-vertical.ribbon-sm .ribbon-inner {
  width: 26px;
  height: 55px;
}

.ribbon-vertical.ribbon-lg .ribbon-inner {
  width: 38px;
  height: 70px;
}

.ribbon-reverse {
  right: -3px;
  left: auto;
}
.ribbon-reverse .ribbon-inner {
  right: 0;
  left: auto;
}
.ribbon-reverse.ribbon-vertical .ribbon-inner {
  right: 16px;
}

.ribbon-bookmark .ribbon-inner {
  padding-right: 42px;
  background-color: transparent;
  background-image: -webkit-linear-gradient(right, transparent 22px, #526069 0);
  background-image: -o-linear-gradient(right, transparent 22px, #526069 0);
  background-image: linear-gradient(to left, transparent 22px, #526069 0);
  -webkit-box-shadow: none;
  box-shadow: none;
}
.ribbon-bookmark .ribbon-inner:before {
  position: absolute;
  top: 0;
  right: 0;
  display: block;
  width: 0;
  height: 0;
  content: "";
  border: 15px solid #526069;
  border-right: 10px solid transparent;
}

.ribbon-bookmark.ribbon-vertical .ribbon-inner {
  height: 82px;
  padding-right: 0;
  padding-bottom: 37px;
  background-image: -webkit-linear-gradient(bottom, transparent 22px, #526069 0);
  background-image: -o-linear-gradient(bottom, transparent 22px, #526069 0);
  background-image: linear-gradient(to top, transparent 22px, #526069 0);
}
.ribbon-bookmark.ribbon-vertical .ribbon-inner:before {
  top: auto;
  bottom: 0;
  left: 0;
  margin-top: -15px;
  border-right: 15px solid #526069;
  border-bottom: 10px solid transparent;
}

.ribbon-bookmark.ribbon-vertical.ribbon-xs .ribbon-inner:before {
  margin-top: -11px;
}

.ribbon-bookmark.ribbon-vertical.ribbon-sm .ribbon-inner:before {
  margin-top: -13px;
}

.ribbon-bookmark.ribbon-vertical.ribbon-lg .ribbon-inner:before {
  margin-top: -19px;
}

.ribbon-bookmark.ribbon-reverse .ribbon-inner {
  padding-right: 20px;
  padding-left: 42px;
  background-image: -webkit-linear-gradient(left, transparent 22px, #526069 0);
  background-image: -o-linear-gradient(left, transparent 22px, #526069 0);
  background-image: linear-gradient(to right, transparent 22px, #526069 0);
}
.ribbon-bookmark.ribbon-reverse .ribbon-inner:before {
  left: 0;
  border-right: 15px solid #526069;
  border-left: 10px solid transparent;
}

.ribbon-bookmark.ribbon-reverse.ribbon-vertical .ribbon-inner {
  padding-right: 0;
  padding-left: 0;
}
.ribbon-bookmark.ribbon-reverse.ribbon-vertical .ribbon-inner:before {
  right: auto;
  left: 0;
  border-right-color: #526069;
  border-bottom-color: transparent;
  border-left: 15px solid #526069;
}

.ribbon-bookmark.ribbon-xs .ribbon-inner:before {
  border-width: 11px;
}

.ribbon-bookmark.ribbon-sm .ribbon-inner:before {
  border-width: 13px;
}

.ribbon-bookmark.ribbon-lg .ribbon-inner:before {
  border-width: 19px;
}

.ribbon-badge {
  top: -2px;
  left: -2px;
  overflow: hidden;
}
.ribbon-badge .ribbon-inner {
  left: -40px;
  width: 100%;
  -webkit-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
}
.ribbon-badge.ribbon-reverse {
  right: -2px;
  left: auto;
}
.ribbon-badge.ribbon-reverse .ribbon-inner {
  right: -40px;
  left: auto;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
}
.ribbon-badge.ribbon-bottom {
  top: auto;
  bottom: -2px;
}
.ribbon-badge.ribbon-bottom .ribbon-inner {
  top: auto;
  bottom: 16px;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
}
.ribbon-badge.ribbon-bottom.ribbon-reverse .ribbon-inner {
  -webkit-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

.ribbon-corner {
  top: 0;
  left: 0;
  overflow: hidden;
}
.ribbon-corner:before {
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 0;
  content: "";
  border: 30px solid transparent;
  border-top-color: #526069;
  border-left-color: #526069;
}
.ribbon-corner .ribbon-inner {
  top: 0;
  left: 0;
  width: 40px;
  height: 35px;
  padding: 0;
  line-height: 35px;
  background-color: transparent;
}
.ribbon-corner.ribbon-reverse {
  right: 0;
  left: auto;
}
.ribbon-corner.ribbon-reverse:before {
  right: 0;
  left: auto;
  border-right-color: #526069;
  border-left-color: transparent;
}
.ribbon-corner.ribbon-reverse .ribbon-inner {
  right: 0;
  left: auto;
}
.ribbon-corner.ribbon-bottom {
  top: auto;
  bottom: 0;
}
.ribbon-corner.ribbon-bottom:before {
  top: auto;
  bottom: 0;
  border-top-color: transparent;
  border-bottom-color: #526069;
}
.ribbon-corner.ribbon-bottom .ribbon-inner {
  top: auto;
  bottom: 0;
}
.ribbon-corner.ribbon-xs:before {
  border-width: 22px;
}
.ribbon-corner.ribbon-xs .ribbon-inner {
  width: 28px;
  height: 26px;
  line-height: 26px;
}
.ribbon-corner.ribbon-xs .ribbon-inner > .icon {
  font-size: .858rem;
}
.ribbon-corner.ribbon-sm:before {
  border-width: 26px;
}
.ribbon-corner.ribbon-sm .ribbon-inner {
  width: 34px;
  height: 32px;
  line-height: 32px;
}
.ribbon-corner.ribbon-sm .ribbon-inner > .icon {
  font-size: .858rem;
}
.ribbon-corner.ribbon-lg:before {
  border-width: 36px;
}
.ribbon-corner.ribbon-lg .ribbon-inner {
  width: 46px;
  height: 44px;
  line-height: 44px;
}
.ribbon-corner.ribbon-lg .ribbon-inner > .icon {
  font-size: 1.286rem;
}

.ribbon-clip {
  left: -14px;
}
.ribbon-clip:before {
  position: absolute;
  top: 46px;
  left: 0;
  width: 0;
  height: 0;
  content: "";
  border: 7px solid transparent;
  border-top-color: #37474f;
  border-right-color: #37474f;
}
.ribbon-clip .ribbon-inner {
  padding-left: 23px;
  border-radius: 0 5px 5px 0;
}
.ribbon-clip.ribbon-reverse {
  right: -14px;
  left: auto;
}
.ribbon-clip.ribbon-reverse:before {
  right: 0;
  left: auto;
  border-right-color: transparent;
  border-left-color: #37474f;
}
.ribbon-clip.ribbon-reverse .ribbon-inner {
  padding-right: 23px;
  padding-left: 15px;
  border-radius: 5px 0 0 5px;
}
.ribbon-clip.ribbon-bottom {
  top: auto;
  bottom: -3px;
}
.ribbon-clip.ribbon-bottom:before {
  top: auto;
  bottom: 46px;
  border-top-color: transparent;
  border-bottom-color: #37474f;
}
.ribbon-clip.ribbon-bottom .ribbon-inner {
  top: auto;
  bottom: 16px;
}
.ribbon-clip.ribbon-xs:before {
  top: 38px;
}
.ribbon-clip.ribbon-xs.ribbon-bottom:before {
  top: auto;
  bottom: 38px;
}
.ribbon-clip.ribbon-sm:before {
  top: 42px;
}
.ribbon-clip.ribbon-sm.ribbon-bottom:before {
  top: auto;
  bottom: 42px;
}
.ribbon-clip.ribbon-lg:before {
  top: 54px;
}
.ribbon-clip.ribbon-lg.ribbon-bottom:before {
  top: auto;
  bottom: 54px;
}

.ribbon-primary .ribbon-inner {
  background-color: #3e8ef7;
}

.ribbon-primary.ribbon-bookmark .ribbon-inner {
  background-color: transparent;
  background-image: -webkit-linear-gradient(right, transparent 22px, #3e8ef7 0);
  background-image: -o-linear-gradient(right, transparent 22px, #3e8ef7 0);
  background-image: linear-gradient(to left, transparent 22px, #3e8ef7 0);
}
.ribbon-primary.ribbon-bookmark .ribbon-inner:before {
  border-color: #3e8ef7;
  border-right-color: transparent;
}

.ribbon-primary.ribbon-bookmark.ribbon-reverse .ribbon-inner {
  background-image: -webkit-linear-gradient(left, transparent 22px, #3e8ef7 0);
  background-image: -o-linear-gradient(left, transparent 22px, #3e8ef7 0);
  background-image: linear-gradient(to right, transparent 22px, #3e8ef7 0);
}
.ribbon-primary.ribbon-bookmark.ribbon-reverse .ribbon-inner:before {
  border-right-color: #3e8ef7;
  border-left-color: transparent;
}

.ribbon-primary.ribbon-bookmark.ribbon-vertical .ribbon-inner {
  background-image: -webkit-linear-gradient(bottom, transparent 22px, #3e8ef7 0);
  background-image: -o-linear-gradient(bottom, transparent 22px, #3e8ef7 0);
  background-image: linear-gradient(to top, transparent 22px, #3e8ef7 0);
}
.ribbon-primary.ribbon-bookmark.ribbon-vertical .ribbon-inner:before {
  border-right-color: #3e8ef7;
  border-bottom-color: transparent;
}

.ribbon-primary.ribbon-bookmark.ribbon-vertical.ribbon-reverse .ribbon-inner:before {
  border-right-color: #3e8ef7;
  border-bottom-color: transparent;
  border-left-color: #3e8ef7;
}

.ribbon-primary.ribbon-corner:before {
  border-top-color: #3e8ef7;
  border-left-color: #3e8ef7;
}

.ribbon-primary.ribbon-corner .ribbon-inner {
  background-color: transparent;
}

.ribbon-primary.ribbon-corner.ribbon-reverse:before {
  border-right-color: #3e8ef7;
  border-left-color: transparent;
}

.ribbon-primary.ribbon-corner.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #3e8ef7;
}

.ribbon-primary.ribbon-clip:before {
  border-top-color: #247cf0;
  border-right-color: #247cf0;
}

.ribbon-primary.ribbon-clip.ribbon-reverse:before {
  border-right-color: transparent;
  border-left-color: #247cf0;
}

.ribbon-primary.ribbon-clip.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #247cf0;
}

.ribbon-success .ribbon-inner {
  background-color: #11c26d;
}

.ribbon-success.ribbon-bookmark .ribbon-inner {
  background-color: transparent;
  background-image: -webkit-linear-gradient(right, transparent 22px, #11c26d 0);
  background-image: -o-linear-gradient(right, transparent 22px, #11c26d 0);
  background-image: linear-gradient(to left, transparent 22px, #11c26d 0);
}
.ribbon-success.ribbon-bookmark .ribbon-inner:before {
  border-color: #11c26d;
  border-right-color: transparent;
}

.ribbon-success.ribbon-bookmark.ribbon-reverse .ribbon-inner {
  background-image: -webkit-linear-gradient(left, transparent 22px, #11c26d 0);
  background-image: -o-linear-gradient(left, transparent 22px, #11c26d 0);
  background-image: linear-gradient(to right, transparent 22px, #11c26d 0);
}
.ribbon-success.ribbon-bookmark.ribbon-reverse .ribbon-inner:before {
  border-right-color: #11c26d;
  border-left-color: transparent;
}

.ribbon-success.ribbon-bookmark.ribbon-vertical .ribbon-inner {
  background-image: -webkit-linear-gradient(bottom, transparent 22px, #11c26d 0);
  background-image: -o-linear-gradient(bottom, transparent 22px, #11c26d 0);
  background-image: linear-gradient(to top, transparent 22px, #11c26d 0);
}
.ribbon-success.ribbon-bookmark.ribbon-vertical .ribbon-inner:before {
  border-right-color: #11c26d;
  border-bottom-color: transparent;
}

.ribbon-success.ribbon-bookmark.ribbon-vertical.ribbon-reverse .ribbon-inner:before {
  border-right-color: #11c26d;
  border-bottom-color: transparent;
  border-left-color: #11c26d;
}

.ribbon-success.ribbon-corner:before {
  border-top-color: #11c26d;
  border-left-color: #11c26d;
}

.ribbon-success.ribbon-corner .ribbon-inner {
  background-color: transparent;
}

.ribbon-success.ribbon-corner.ribbon-reverse:before {
  border-right-color: #11c26d;
  border-left-color: transparent;
}

.ribbon-success.ribbon-corner.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #11c26d;
}

.ribbon-success.ribbon-clip:before {
  border-top-color: #05a85c;
  border-right-color: #05a85c;
}

.ribbon-success.ribbon-clip.ribbon-reverse:before {
  border-right-color: transparent;
  border-left-color: #05a85c;
}

.ribbon-success.ribbon-clip.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #05a85c;
}

.ribbon-info .ribbon-inner {
  background-color: #0bb2d4;
}

.ribbon-info.ribbon-bookmark .ribbon-inner {
  background-color: transparent;
  background-image: -webkit-linear-gradient(right, transparent 22px, #0bb2d4 0);
  background-image: -o-linear-gradient(right, transparent 22px, #0bb2d4 0);
  background-image: linear-gradient(to left, transparent 22px, #0bb2d4 0);
}
.ribbon-info.ribbon-bookmark .ribbon-inner:before {
  border-color: #0bb2d4;
  border-right-color: transparent;
}

.ribbon-info.ribbon-bookmark.ribbon-reverse .ribbon-inner {
  background-image: -webkit-linear-gradient(left, transparent 22px, #0bb2d4 0);
  background-image: -o-linear-gradient(left, transparent 22px, #0bb2d4 0);
  background-image: linear-gradient(to right, transparent 22px, #0bb2d4 0);
}
.ribbon-info.ribbon-bookmark.ribbon-reverse .ribbon-inner:before {
  border-right-color: #0bb2d4;
  border-left-color: transparent;
}

.ribbon-info.ribbon-bookmark.ribbon-vertical .ribbon-inner {
  background-image: -webkit-linear-gradient(bottom, transparent 22px, #0bb2d4 0);
  background-image: -o-linear-gradient(bottom, transparent 22px, #0bb2d4 0);
  background-image: linear-gradient(to top, transparent 22px, #0bb2d4 0);
}
.ribbon-info.ribbon-bookmark.ribbon-vertical .ribbon-inner:before {
  border-right-color: #0bb2d4;
  border-bottom-color: transparent;
}

.ribbon-info.ribbon-bookmark.ribbon-vertical.ribbon-reverse .ribbon-inner:before {
  border-right-color: #0bb2d4;
  border-bottom-color: transparent;
  border-left-color: #0bb2d4;
}

.ribbon-info.ribbon-corner:before {
  border-top-color: #0bb2d4;
  border-left-color: #0bb2d4;
}

.ribbon-info.ribbon-corner .ribbon-inner {
  background-color: transparent;
}

.ribbon-info.ribbon-corner.ribbon-reverse:before {
  border-right-color: #0bb2d4;
  border-left-color: transparent;
}

.ribbon-info.ribbon-corner.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #0bb2d4;
}

.ribbon-info.ribbon-clip:before {
  border-top-color: #0099b8;
  border-right-color: #0099b8;
}

.ribbon-info.ribbon-clip.ribbon-reverse:before {
  border-right-color: transparent;
  border-left-color: #0099b8;
}

.ribbon-info.ribbon-clip.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #0099b8;
}

.ribbon-warning .ribbon-inner {
  background-color: #eb6709;
}

.ribbon-warning.ribbon-bookmark .ribbon-inner {
  background-color: transparent;
  background-image: -webkit-linear-gradient(right, transparent 22px, #eb6709 0);
  background-image: -o-linear-gradient(right, transparent 22px, #eb6709 0);
  background-image: linear-gradient(to left, transparent 22px, #eb6709 0);
}
.ribbon-warning.ribbon-bookmark .ribbon-inner:before {
  border-color: #eb6709;
  border-right-color: transparent;
}

.ribbon-warning.ribbon-bookmark.ribbon-reverse .ribbon-inner {
  background-image: -webkit-linear-gradient(left, transparent 22px, #eb6709 0);
  background-image: -o-linear-gradient(left, transparent 22px, #eb6709 0);
  background-image: linear-gradient(to right, transparent 22px, #eb6709 0);
}
.ribbon-warning.ribbon-bookmark.ribbon-reverse .ribbon-inner:before {
  border-right-color: #eb6709;
  border-left-color: transparent;
}

.ribbon-warning.ribbon-bookmark.ribbon-vertical .ribbon-inner {
  background-image: -webkit-linear-gradient(bottom, transparent 22px, #eb6709 0);
  background-image: -o-linear-gradient(bottom, transparent 22px, #eb6709 0);
  background-image: linear-gradient(to top, transparent 22px, #eb6709 0);
}
.ribbon-warning.ribbon-bookmark.ribbon-vertical .ribbon-inner:before {
  border-right-color: #eb6709;
  border-bottom-color: transparent;
}

.ribbon-warning.ribbon-bookmark.ribbon-vertical.ribbon-reverse .ribbon-inner:before {
  border-right-color: #eb6709;
  border-bottom-color: transparent;
  border-left-color: #eb6709;
}

.ribbon-warning.ribbon-corner:before {
  border-top-color: #eb6709;
  border-left-color: #eb6709;
}

.ribbon-warning.ribbon-corner .ribbon-inner {
  background-color: transparent;
}

.ribbon-warning.ribbon-corner.ribbon-reverse:before {
  border-right-color: #eb6709;
  border-left-color: transparent;
}

.ribbon-warning.ribbon-corner.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #eb6709;
}

.ribbon-warning.ribbon-clip:before {
  border-top-color: #de4e00;
  border-right-color: #de4e00;
}

.ribbon-warning.ribbon-clip.ribbon-reverse:before {
  border-right-color: transparent;
  border-left-color: #de4e00;
}

.ribbon-warning.ribbon-clip.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #de4e00;
}

.ribbon-danger .ribbon-inner {
  background-color: #ff4c52;
}

.ribbon-danger.ribbon-bookmark .ribbon-inner {
  background-color: transparent;
  background-image: -webkit-linear-gradient(right, transparent 22px, #ff4c52 0);
  background-image: -o-linear-gradient(right, transparent 22px, #ff4c52 0);
  background-image: linear-gradient(to left, transparent 22px, #ff4c52 0);
}
.ribbon-danger.ribbon-bookmark .ribbon-inner:before {
  border-color: #ff4c52;
  border-right-color: transparent;
}

.ribbon-danger.ribbon-bookmark.ribbon-reverse .ribbon-inner {
  background-image: -webkit-linear-gradient(left, transparent 22px, #ff4c52 0);
  background-image: -o-linear-gradient(left, transparent 22px, #ff4c52 0);
  background-image: linear-gradient(to right, transparent 22px, #ff4c52 0);
}
.ribbon-danger.ribbon-bookmark.ribbon-reverse .ribbon-inner:before {
  border-right-color: #ff4c52;
  border-left-color: transparent;
}

.ribbon-danger.ribbon-bookmark.ribbon-vertical .ribbon-inner {
  background-image: -webkit-linear-gradient(bottom, transparent 22px, #ff4c52 0);
  background-image: -o-linear-gradient(bottom, transparent 22px, #ff4c52 0);
  background-image: linear-gradient(to top, transparent 22px, #ff4c52 0);
}
.ribbon-danger.ribbon-bookmark.ribbon-vertical .ribbon-inner:before {
  border-right-color: #ff4c52;
  border-bottom-color: transparent;
}

.ribbon-danger.ribbon-bookmark.ribbon-vertical.ribbon-reverse .ribbon-inner:before {
  border-right-color: #ff4c52;
  border-bottom-color: transparent;
  border-left-color: #ff4c52;
}

.ribbon-danger.ribbon-corner:before {
  border-top-color: #ff4c52;
  border-left-color: #ff4c52;
}

.ribbon-danger.ribbon-corner .ribbon-inner {
  background-color: transparent;
}

.ribbon-danger.ribbon-corner.ribbon-reverse:before {
  border-right-color: #ff4c52;
  border-left-color: transparent;
}

.ribbon-danger.ribbon-corner.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #ff4c52;
}

.ribbon-danger.ribbon-clip:before {
  border-top-color: #f2353c;
  border-right-color: #f2353c;
}

.ribbon-danger.ribbon-clip.ribbon-reverse:before {
  border-right-color: transparent;
  border-left-color: #f2353c;
}

.ribbon-danger.ribbon-clip.ribbon-bottom:before {
  border-top-color: transparent;
  border-bottom-color: #f2353c;
}

    </style>
</head>
<body>
    <?php $this->beginBody(); ?>
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <!-- Layout sidenav -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-dark">

            <!-- Brand demo (see assets/css/demo/demo.css) -->
            <div class="app-brand demo">
                <span class="app-brand-logo demo bg-primary">
                    <!-- <svg viewBox="0 0 148 80" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><linearGradient id="a" x1="46.49" x2="62.46" y1="53.39" y2="48.2" gradientUnits="userSpaceOnUse"><stop stop-opacity=".25" offset="0"></stop><stop stop-opacity=".1" offset=".3"></stop><stop stop-opacity="0" offset=".9"></stop></linearGradient><linearGradient id="e" x1="76.9" x2="92.64" y1="26.38" y2="31.49" xlink:href="#a"></linearGradient><linearGradient id="d" x1="107.12" x2="122.74" y1="53.41" y2="48.33" xlink:href="#a"></linearGradient></defs><path style="fill: #fff;" transform="translate(-.1)" d="M121.36,0,104.42,45.08,88.71,3.28A5.09,5.09,0,0,0,83.93,0H64.27A5.09,5.09,0,0,0,59.5,3.28L43.79,45.08,26.85,0H.1L29.43,76.74A5.09,5.09,0,0,0,34.19,80H53.39a5.09,5.09,0,0,0,4.77-3.26L74.1,35l16,41.74A5.09,5.09,0,0,0,94.82,80h18.95a5.09,5.09,0,0,0,4.76-3.24L148.1,0Z"></path><path transform="translate(-.1)" d="M52.19,22.73l-8.4,22.35L56.51,78.94a5,5,0,0,0,1.64-2.19l7.34-19.2Z" fill="url(#a)"></path><path transform="translate(-.1)" d="M95.73,22l-7-18.69a5,5,0,0,0-1.64-2.21L74.1,35l8.33,21.79Z" fill="url(#e)"></path><path transform="translate(-.1)" d="M112.73,23l-8.31,22.12,12.66,33.7a5,5,0,0,0,1.45-2l7.3-18.93Z" fill="url(#d)"></path></svg> -->
                    <img src="/assets/img/logo_140x140_w.png" style="width:30px; height:30px" alt="Logo">
                </span>
                <a href="/" class="app-brand-text demo sidenav-text font-weight-normal ml-2">ims/</a>
                <a href="/" class="app-brand-text demo sidenav-text font-weight-bold"><?= SEG1 == 'b2b' ? 'b2b' : 'workspace' ?></a>
                <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                    <i class="ion ion-md-menu align-middle"></i>
                </a>
            </div>

            <div class="sidenav-divider mt-0"></div>

            <!-- Links -->
            <ul class="sidenav-inner py-1">
                <?php
                if (isset(Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']])) {
                    Yii::$app->params['side_nav']['main'] = Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']];
                    foreach (Yii::$app->params['side_nav']['main'] as $item) {
                        echo renderAppworkMainNavItem($item);
                    }
                } ?>
            </ul>
            <!--
            <div class="sidenav-divider mb-0"></div>
            <div class="sidenav-block my-1">
                <div class="small">
                  Milestone
                  <div class="float-right">55%</div>
                </div>
                <div class="progress mt-1 mb-3" style="height: 4px;">
                  <div class="progress-bar bg-success" style="width: 55%;"></div>
                </div>

                <div class="small">
                  Release
                  <div class="float-right">80%</div>
                </div>
                <div class="progress mt-1" style="height: 4px;">
                  <div class="progress-bar bg-danger" style="width: 80%;"></div>
                </div>
            </div>
            -->
        </div>
        <!-- / Layout sidenav -->

        <!-- Layout container -->
        <div class="layout-container">
            <!-- Layout navbar -->
            <nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-white container-p-x" id="layout-navbar">
                <!-- Brand demo (see assets/css/demo/demo.css) -->
                <a href="/" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
                    <span class="app-brand-logo demo bg-primary">
                        <!-- <svg viewBox="0 0 148 80" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><linearGradient id="a" x1="46.49" x2="62.46" y1="53.39" y2="48.2" gradientUnits="userSpaceOnUse"><stop stop-opacity=".25" offset="0"></stop><stop stop-opacity=".1" offset=".3"></stop><stop stop-opacity="0" offset=".9"></stop></linearGradient><linearGradient id="e" x1="76.9" x2="92.64" y1="26.38" y2="31.49" xlink:href="#a"></linearGradient><linearGradient id="d" x1="107.12" x2="122.74" y1="53.41" y2="48.33" xlink:href="#a"></linearGradient></defs><path style="fill: #fff;" transform="translate(-.1)" d="M121.36,0,104.42,45.08,88.71,3.28A5.09,5.09,0,0,0,83.93,0H64.27A5.09,5.09,0,0,0,59.5,3.28L43.79,45.08,26.85,0H.1L29.43,76.74A5.09,5.09,0,0,0,34.19,80H53.39a5.09,5.09,0,0,0,4.77-3.26L74.1,35l16,41.74A5.09,5.09,0,0,0,94.82,80h18.95a5.09,5.09,0,0,0,4.76-3.24L148.1,0Z"></path><path transform="translate(-.1)" d="M52.19,22.73l-8.4,22.35L56.51,78.94a5,5,0,0,0,1.64-2.19l7.34-19.2Z" fill="url(#a)"></path><path transform="translate(-.1)" d="M95.73,22l-7-18.69a5,5,0,0,0-1.64-2.21L74.1,35l8.33,21.79Z" fill="url(#e)"></path><path transform="translate(-.1)" d="M112.73,23l-8.31,22.12,12.66,33.7a5,5,0,0,0,1.45-2l7.3-18.93Z" fill="url(#d)"></path></svg> -->
                        <img src="/assets/img/logo_140x140_w.png" style="width:30px; height:30px" alt="Logo">
                    </span>
                    <span class="app-brand-text demo font-weight-normal ml-2">ims</span>
                    <span class="app-brand-text demo font-weight-bold">/<?= SEG1 == 'b2b' ? 'b2b' : 'workspace' ?></span>
                </a>

                <!-- Sidenav toggle (see assets/css/demo/demo.css) -->
                <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
                    <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)">
                        <i class="ion ion-md-menu text-large align-middle"></i>
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse collapse" id="layout-navbar-collapse">
                    <!-- Divider -->
                    <hr class="d-lg-none w-100 my-2">
                    <div class="navbar-nav align-items-lg-center">
                        
                        <!-- Search -->
                        <label class="nav-item navbar-text navbar-search-box p-0 active">
                            <i class="slicon-magnifier align-middle"></i>
                            <span class="-navbar-search-input pl-2" style="min-width:350px">
                                <!-- <input type="text" class="form-control navbar-text mx-2" placeholder="<?= Yii::t('x', 'Search') ?>..." style="width:200px"> -->
                                <select id="livesearch" style="width:100%;">
                                    <option value="" selected="selected"><?= Yii::t('x', 'Search') ?>...</option>
                                </select>
                            </select>
                            </span>
                        </label>
                    </div>

                    <div class="navbar-nav align-items-lg-center ml-auto">
                        <div class="demo-navbar-quicklinks nav-item dropdown mr-lg-3">
                            <a href="#" class="nav-link dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <i class="slicon-list"></i>
                                <span class="d-lg-none align-middle">&nbsp; Quick links</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="bg-primary text-center text-white font-weight-bold p-3">
                                    Quick links
                                </div>
                                <div class="list-group list-group-flush">
                                    <a class="dropdown-item" href="/venues"><i class="slicon-home"></i> Hotels</a>
                                    <a class="dropdown-item" href="/venues?stra=h"><i class="slicon-home"></i> Homestays</a>
                                    <a class="dropdown-item" href="/venues/homestay-calendar"><i class="slicon-home"></i> --- Homestay calendar</a>
                                    <a class="dropdown-item" href="/ref/halongcruises"><i class="slicon-anchor"></i> Cruises</a>
                                    <a class="dropdown-item" href="/ref/ssspots"><i class="fa fa-truck"></i> Sightseeing</a>
                                    <a class="dropdown-item" href="/venues?type=restaurant&amp;destination_id=1"><i class="fa fa-coffee"></i> Restaurants</a>
                                    <a class="dropdown-item" href="/ref/tables"><i class="fa fa-table"></i> Other tables</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/tours"><i class="fa fa-car"></i> Tours starting this month</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/members"><i class="fa fa-font"></i> Amica members</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-none demo-navbar-notifications nav-item dropdown mr-lg-3">
                            <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown">
                                <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
                                <span class="badge badge-primary badge-dot indicator"></span>
                                <span class="d-lg-none align-middle">&nbsp; Notifications</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="bg-primary text-center text-white font-weight-bold p-3">
                                    4 New Notifications
                                </div>
                                <div class="list-group list-group-flush">
                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                                      <div class="ui-icon ui-icon-sm ion ion-md-home bg-secondary border-0 text-white"></div>
                                      <div class="media-body line-height-condenced ml-3">
                                        <div class="text-dark">Login from 192.168.1.1</div>
                                        <div class="text-light small mt-1">
                                          Aliquam ex eros, imperdiet vulputate hendrerit et.
                                        </div>
                                        <div class="text-light small mt-1">12h ago</div>
                                      </div>
                                    </a>

                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                                      <div class="ui-icon ui-icon-sm ion ion-md-person-add bg-info border-0 text-white"></div>
                                      <div class="media-body line-height-condenced ml-3">
                                        <div class="text-dark">You have <strong>4</strong> new followers</div>
                                        <div class="text-light small mt-1">
                                          Phasellus nunc nisl, posuere cursus pretium nec, dictum vehicula tellus.
                                        </div>
                                      </div>
                                    </a>

                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                                      <div class="ui-icon ui-icon-sm ion ion-md-power bg-danger border-0 text-white"></div>
                                      <div class="media-body line-height-condenced ml-3">
                                        <div class="text-dark">Server restarted</div>
                                        <div class="text-light small mt-1">
                                          19h ago
                                        </div>
                                      </div>
                                    </a>

                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                                      <div class="ui-icon ui-icon-sm ion ion-md-warning bg-warning border-0 text-dark"></div>
                                      <div class="media-body line-height-condenced ml-3">
                                        <div class="text-dark">99% server load</div>
                                        <div class="text-light small mt-1">
                                          Etiam nec fringilla magna. Donec mi metus.
                                        </div>
                                        <div class="text-light small mt-1">
                                          20h ago
                                        </div>
                                      </div>
                                    </a>
                                </div>
                                <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all notifications</a>
                            </div>
                        </div>

                        <div class="d-none demo-navbar-messages nav-item dropdown mr-lg-3">
                            <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown">
                                <i class="ion ion-ios-mail navbar-icon align-middle"></i>
                                <span class="badge badge-primary badge-dot indicator"></span>
                                <span class="d-lg-none align-middle">&nbsp; Messages</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="bg-primary text-center text-white font-weight-bold p-3">
                                    4 New Messages
                                </div>
                      <div class="list-group list-group-flush">
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                          <img src="/assets/img/placeholder.jpg" class="d-block ui-w-40 rounded-circle" alt>
                          <div class="media-body ml-3">
                            <div class="text-dark line-height-condenced">Sit meis deleniti eu, pri vidit meliore docendi ut.</div>
                            <div class="text-light small mt-1">
                              Mae Gibson &nbsp;·&nbsp; 58m ago
                            </div>
                          </div>
                        </a>

                        <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                          <img src="/assets/img/placeholder.jpg" class="d-block ui-w-40 rounded-circle" alt>
                          <div class="media-body ml-3">
                            <div class="text-dark line-height-condenced">Mea et legere fuisset, ius amet purto luptatum te.</div>
                            <div class="text-light small mt-1">
                              Kenneth Frazier &nbsp;·&nbsp; 1h ago
                            </div>
                          </div>
                        </a>

                        <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                          <img src="/assets/img/placeholder.jpg" class="d-block ui-w-40 rounded-circle" alt>
                          <div class="media-body ml-3">
                            <div class="text-dark line-height-condenced">Sit meis deleniti eu, pri vidit meliore docendi ut.</div>
                            <div class="text-light small mt-1">
                              Nelle Maxwell &nbsp;·&nbsp; 2h ago
                            </div>
                          </div>
                        </a>

                        <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center">
                          <img src="/assets/img/placeholder.jpg" class="d-block ui-w-40 rounded-circle" alt>
                          <div class="media-body ml-3">
                            <div class="text-dark line-height-condenced">Lorem ipsum dolor sit amet, vis erat denique in, dicunt prodesset te vix.</div>
                            <div class="text-light small mt-1">
                              Belle Ross &nbsp;·&nbsp; 5h ago
                            </div>
                          </div>
                        </a>
                      </div>

                      <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all messages</a>
                    </div>
                  </div>

                    <!-- Apps -->
                    <div class="demo-navbar-apps nav-item dropdown mr-lg-3">
                        <a href="#" class="nav-link dropdown-toggle hide-arrow" data-toggle="dropdown">
                            <i class="slicon-grid"></i>
                            <span class="d-lg-none align-middle">&nbsp; All apps</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right p-0 text-center" style="min-width:300px;">
                            <div class="bg-primary text-center text-white font-weight-bold p-3">
                                Select an app
                            </div>
                            <div class="row">
                                <div class="col p-3">
                                    <a href="/products" class="text-primary">
                                        <i class="d-block fa fa-2x fa-briefcase text-muted"></i>
                                        <?= Yii::t('x', 'B2C programs') ?>
                                    </a>
                                </div>
                                <div class="col p-3">
                                    <a href="/cases" class="text-primary">
                                        <i class="d-block fa fa-2x fa-briefcase text-muted"></i>
                                        <?= Yii::t('x', 'B2C cases') ?>
                                    </a>
                                </div>
                                <div class="col p-3">
                                    <a href="/customers" class="text-primary">
                                        <i class="d-block fa fa-2x fa-briefcase text-muted"></i>
                                        <?= Yii::t('x', 'B2C customers') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col p-3">
                                    <a href="/b2b/programs" class="text-primary">
                                        <i class="d-block fa fa-2x fa-briefcase text-muted"></i>
                                        <?= Yii::t('x', 'B2B programs') ?>
                                    </a>
                                </div>
                                <div class="col p-3">
                                    <a href="/b2b/cases" class="text-primary">
                                        <i class="d-block fa fa-2x fa-briefcase text-muted"></i>
                                        <?= Yii::t('x', 'B2B cases') ?>
                                    </a>
                                </div>
                                <div class="col p-3">
                                    <a href="/b2b/clients" class="text-primary">
                                        <i class="d-block fa fa-2x fa-briefcase text-muted"></i>
                                        <?= Yii::t('x', 'B2B customers') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col p-3">
                                    <a href="/contacts/members" class="text-primary">
                                        <i class="d-block fa fa-2x fa-group text-muted"></i>
                                        <?= Yii::t('x', 'Members') ?>
                                    </a>
                                </div>
                                <div class="col p-3">
                                    <a href="/tours" class="text-primary">
                                        <i class="d-block fa fa-2x fa-flag text-muted"></i>
                                        <?= Yii::t('x', 'Tours') ?>
                                    </a>
                                </div>
                                <div class="col p-3">
                                    <a href="/blog" class="text-primary">
                                        <i class="d-block fa fa-2x fa-bullhorn text-muted"></i>
                                        <?= Yii::t('x', 'News') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>

                    <div class="demo-navbar-user nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                                <img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" alt="Me" class="d-block ui-w-30 rounded-circle">
                                <span class="px-1 mr-lg-2 ml-2 ml-lg-0"><?= Yii::$app->user->identity->name ?></span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="/me/profile" class="dropdown-item"><i class="slicon-user"></i> <?= Yii::t('nav', 'My profile') ?></a>
                            <a href="/me/my-settings/password" class="dropdown-item"><i class="slicon-key"></i> <?= Yii::t('nav', 'Change password') ?></a>
                            <a href="/me/my-settings/preferences" class="dropdown-item"><i class="slicon-settings"></i> <?= Yii::t('nav', 'Preferences') ?></a>
                            <div class="dropdown-divider"></div>
                            <a href="/tasks" class="dropdown-item"><i class="slicon-check"></i> <?= Yii::t('nav', 'My tasks') ?></a>
                            <a href="/mails" class="dropdown-item"><i class="slicon-envelope"></i> <?= Yii::t('nav', 'My emails') ?></a>
                            <a href="/notes" class="dropdown-item"><i class="slicon-notebook"></i> <?= Yii::t('nav', 'My notes') ?></a>
                            <a href="/me/my-reports" class="dropdown-item"><i class="slicon-pie-chart"></i> <?= Yii::t('nav', 'My reports') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item english" href="/select/lang/en"><i class="flag-icon flag-icon-us"></i> <span>English</span></a>
                            <a class="dropdown-item french" href="/select/lang/fr"><i class="flag-icon flag-icon-fr"></i> <span>Français</span></a>
                            <a class="dropdown-item vietnamese" href="/select/lang/vi"><i class="flag-icon flag-icon-vn"></i> <span>Tiếng Việt</span></a>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" class="dropdown-item"><i class="slicon-power"></i> <?= Yii::t('x', 'Log out') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- / Layout navbar -->

                <!-- Layout content -->
                <div class="layout-content">
                    <!--[if lt IE 11]><div class="hidden-print alert alert-warning mb-0" style="border-width:0; border-bottom-width:1px; border-radius:0"><i class="fa fa-warning"></i> You are using an <strong>outdated</strong> browser. Please <a target="_blank" class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div><![endif]-->
                    <?php
                    foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
                        if (Yii::$app->session->hasFlash($key)) { ?>
                    <div class="hidden-print alert alert-<?= $key ?> mb-0" style="border-width:0; border-bottom-width:1px; border-radius:0"><?= $message ?></div>
                    <?php
                        }
                    }
                    ?>
                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <?php if (strpos(Yii::$app->params['page_layout'], '-h') === false) { ?>
                        <div class="my-page-header d-lg-flex d-md-flex justify-content-between align-items-center">
                            <div>
                                <?php if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
                                    <?php if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                                <div class="my-page-breadcrumb small breadcrumb ml-0 mb-0">
                                    <a class="breadcrumb-item py-0" href="/"><?= Yii::t('nav', 'Home') ?></a><?php
                                    foreach (Yii::$app->params['page_breadcrumbs'] as $item) {
                                        if (!empty($item)) {
                                            if (!isset($item[1]) || true === $item[1]) { ?>
                                            <span class="breadcrumb-item py-0 active"><?= $item[0] ?></span><?php
                                        } else {
                                            if (substr($item[1], 0, 1) != '#' && substr($item[1], 0, 1) != '@' && strpos($item[1], '//') === false) {
                                                $item[1] = '@web/'.$item[1];
                                            } ?>
                                    <a class="breadcrumb-item py-0<?= isset($item[2]) && $item[2] === true ? 'active' : '' ?>" href="<?= str_replace('@web', '', $item[1]) ?>"><?= $item[0] ?></a><?php
                                            }
                                        }
                                    } ?>
                                </div>
                                    <?php } // isset b ?>
                                <?php } // -b ?>

                                <?php if (strpos(Yii::$app->params['page_layout'], '-t') === false) { ?>
                                <div class="ims-page-header">
                                    <h4 class="font-weight-bold pt-2 pb-<?= empty($this->blocks['page_tabs']) ? '4' : '2' ?> mb-0">
                                        <?php if (!empty(Yii::$app->params['page_icon'])) { ?><i class="mr-2 <?= strpos(Yii::$app->params['page_icon'], 'slicon') === false ? 'fa fa-' : '' ?><?= Yii::$app->params['page_icon'] ?>"></i><?php } ?>
                                        <?= Yii::$app->params['page_title'] ?>
                                        <?php if (!empty(Yii::$app->params['page_small_title'])) { ?><span class="font-weight-normal"><?= Yii::$app->params['page_small_title'] ?></span><?php } ?>
                                    </h4>
                                    <?php if (!empty(Yii::$app->params['page_sub_title'])) { ?><div class="d-block text-muted"><?= Yii::$app->params['page_sub_title'] ?></div><?php } ?>
                                </div>
                                <?php } ?>
                            </div>

<?php
if (strpos(Yii::$app->params['page_layout'], '-a') === false) { ?>
    <div><?php
    if (isset(Yii::$app->params['page_actions']) && is_array(Yii::$app->params['page_actions'])) { ?>
                    <div class="my-page-actions mb-3 mb-md-0 mb-lg-0">
                        <div class="btn-group btn-group-sm"><?php
                                foreach (Yii::$app->params['page_actions'] as $iii=>$iBtnGroup) {
                                    foreach ($iBtnGroup as $iBtn) {
                                        if (!isset($iBtn['hidden']) || !$iBtn['hidden']) {
                                            if (isset($iBtn['submenu']) && is_array($iBtn['submenu'])) { ?>
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" href="#"></a>
                                <div class="dropdown-menu dropdown-menu-right"><?php
                                                foreach ($iBtn['submenu'] as $i2Btn) {
                                                    if ($i2Btn == ['-']) { ?>
                                    <div class="dropdown-divider"></div><?php
                                                    } else {
                                                        if (!isset($i2Btn['hidden']) || !$i2Btn['hidden']) {
                                                            $i2BtnIcon = '';
                                                            if (isset($i2Btn['icon'])) {
                                                                if (substr($i2Btn['icon'], 0, 6) == 'slicon') {
                                                                    $i2BtnIcon = '<i class="'.$i2Btn['icon'].'"></i> ';
                                                                } else {
                                                                    $i2BtnIcon = '<i class="fa fa-fw fa-'.$i2Btn['icon'].'"></i> ';
                                                                }
                                                            }
                                                            $i2BtnLabel = $i2Btn['label'] ?? '';
                                                            $i2BtnTitle = $i2Btn['title'] ?? '';
                                                            $i2BtnClass = 'dropdown-item '.($i2Btn['class'] ?? '');
                                                            if (isset($i2Btn['active']) && $i2Btn['active']) {
                                                                $i2BtnClass .= ' active';
                                                            }
                                                            $i2BtnLink = isset($i2Btn['link']) ? $i2Btn['link'] : '#';
                                                            if (substr($i2BtnLink, 0, 1) != '#' && substr($i2BtnLink, 0, 5) != '@web/' && strpos($i2BtnLink, '//') === false) {
                                                                $i2BtnLink = '@web/'.$i2BtnLink;
                                                            }
                                                            echo Html::a($i2BtnIcon.$i2BtnLabel, $i2BtnLink, ['class'=>$i2BtnClass, 'title'=>$i2BtnTitle]);
                                                        }
                                                    } // if divider
                                                } // foreach i2Btn ?>
                                </div>
                            </div>
                        </div><?php
                                                // Neu chua het thi tiep tuc <div>
                                                if ($iii < count(Yii::$app->params['page_actions'])) { ?>
                        <div class="btn-group btn-group-sm"><?php
                                                } 
                                            } else {
                                                $iBtnIcon = '';
                                                if (isset($iBtn['icon'])) {
                                                    if (substr($iBtn['icon'], 0, 6) == 'slicon') {
                                                        $iBtnIcon = '<i class="'.$iBtn['icon'].'"></i> ';
                                                    } else {
                                                        $iBtnIcon = '<i class="fa fa-fw fa-'.$iBtn['icon'].'"></i> ';
                                                    }
                                                }

                                                $iBtnLabel = isset($iBtn['label']) ? $iBtn['label'] : '';
                                                $iBtnTitle = isset($iBtn['title']) ? $iBtn['title'] : '';
                                                $iBtnClass = 'btn btn-outline-secondary ';
                                                $iBtnClass .= isset($iBtn['class']) ? $iBtn['class'] : '';
                                                if (isset($iBtn['active']) && $iBtn['active']) {
                                                    $iBtnClass .= ' active';
                                                }
                                                $iBtnLink = isset($iBtn['link']) ? $iBtn['link'] : '#';
                                                if (substr($iBtnLink, 0, 1) != '#' && substr($iBtnLink, 0, 5) != '@web/' && strpos($iBtnLink, '//') === false) {
                                                    $iBtnLink = '@web/'.$iBtnLink;
                                                }

                                                echo Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]);
                                            }// if submenu
                                        } // if not hidden iBtn
                                    } // foreach button
                                } // foreach button group ?>
                        </div>
                    </div><?php
                    }
                } // -a ?>
            </div>

                        </div><!-- /.my-page-header -->
                        <?php } ?>
                        <?php if (!empty($this->blocks['page_tabs'])) { ?>
                        <div class="ims-page-tabs">
                            <?= $this->blocks['page_tabs'] ?>
                        </div>
                        <?php } else { ?>
                            <hr class="border-light container-m--x mt-0 mb-4">
                        <?php } ?>
                        <div class="<?= strpos(Yii::$app->params['page_layout'], '-r') === false ? 'row' : '' ?>">
                            <?= $content ?>
                        </div>
                    </div>
                    <!-- / Content -->
                    <!-- Layout footer -->
                    <nav class="layout-footer footer bg-footer-theme">
                        <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
                            <div class="pt-3">
                                <span class="footer-text font-weight-bolder">ims</span> &copy; Amica Travel
                            </div>
                            <div>
                                <a href="/help/about" class="footer-link pt-3">About Us</a>
                                <a href="/help" class="footer-link pt-3 ml-4">Help</a>
                                <a href="/help/bug" class="footer-link pt-3 ml-4">Contact</a>
                            </div>
                        </div>
                    </nav>
                    <!-- / Layout footer -->
                </div>
                <!-- Layout content -->
            </div>
            <!-- / Layout container -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage();
