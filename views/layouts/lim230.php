<?php
use yii\helpers\Html;
use app\assets\Limitless230Asset as MainAsset;
use app\notifications\widgets\MyNotifications;

// include('_css_limitless.php');
// include('_css_theadmin.php');
include('_css_fa.php');

include('_nav.php');
include('_js.php');
include('../config/functions.php');

$this->beginPage();

MainAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? strip_tags(Yii::$app->params['page_title']) : Yii::$app->params['page_meta_title'];
Yii::$app->params['page_class'] = Yii::$app->params['page_class'] ?? Yii::$app->params['body_class'] ?? '';
Yii::$app->params['page_notice'] = Yii::$app->params['page_notice'] ?? [];

$themeColor = 'indigo';
if (in_array(USER_ID, [24820])) {
    $themeColor = 'danger';
}
if (in_array(USER_ID, [1])) {
    $themeColor = 'brown';
}

?><!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Yii::$app->params['page_meta_title'] ?> - IMS - Amica Travel</title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head(); ?>
    <style>
.my-page-breadcrumb-title {padding:.8rem 1.25rem;}
.my-page-breadcrumb + .my-page-title {margin-top:.8rem}
.my-page-main-title {font-weight:400; margin:0;}
.my-page-small-title {font-weight:300;}
.my-page-sub-title {font-size:1rem;}
.my-page-actions {padding:0 1.25rem;}
.my-page-actions .nav-link {padding:4px;}
.my-page-actions .nav-pills .nav-link {padding:.1rem .3rem;}

.note-list {list-style:none; padding:0; margin:0;}
    .note-list-item {list-style:none; border-top:1px solid #eee; padding:24px 0;}
    .note-list-item.first {border-top:none; padding-top:0;}
        .note-avatar {width:64px; height:64px; float:left;}
            .note-author-avatar {width:64px; height:64px;}
        .note-content {margin-left:80px;}
            a.note-author-name, .note-author-name {color:#6d4c41;}
            a.note-recipient-name, .note-recipient-name {color:#9C27B0;}
            .note-heading {margin-top:0;}
                .note-title {}
            .note-meta {}
            .note-file-list {margin-left:2em; margin-bottom: 1em;}
                .note-file-list-item {}
            .note-body {}
            .note-actions {}

@media (max-width: 479px) {
    .note-avatar {display:none;}
    .note-content {margin-left:0;}
}

.fb-title {font-family: 'Mali', cursive; color:#4caf50;}
.fb-content {font-family: 'Mali', cursive;}
.uploader_file_preview {float:left; width:60px; height:60px; display:inline-block; margin-right:12px;}
.mb-12 {margin-bottom:12px;}
.-cke_chrome {border-radius:3px; border-color:#ddd!important;}
.-cke_top {margin:0!important; padding:0 8px!important; border-bottom-color:#eee!important; -background-color:#eee!important;}
.-cke_toolgroup {margin:0!important;}
.-cke_button, .cke_button:hover {border:0!important; padding:4px!important;}
.-cke_button:hover {cursor:pointer!important;background-color:#f5f5f5!important;}
.post-attachments {padding-left:24px;}
#div-edit-post .post-attachments {padding:7px 12px 7px 24px; background-color:#f6f6f6; -border:1px solid #ddd; border-top:0;}
.post-attachment {margin-top:4px;}
.text-tournote.text-blue, .text-tournote.text-blue a {color:blue!important;}

    .div_mail .gmail_quote {display:none;}
    .gmail_quote {display:block;}
    .gmail_signature {display:none;}
    .post-avatar {width:64px;}
    .post-speech-bubble {
      position: relative;
      background: #f7f0e8;
      border-radius: .4em;
    }

    .post-speech-bubble:after {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      width: 0;
      height: 0;
      border: 10px solid transparent;
      border-bottom-color: #f7f0e8;
      border-top: 0;
      border-left: 0;
      margin-left: -5px;
      margin-top: -10px;
    }

    .select2-selection--multiple .select2-selection__choice {background-color:#e5e5e5; color:#444;}
    .select2-selection--multiple .select2-selection__choice:focus, .select2-selection--multiple .select2-selection__choice:hover {background-color:#c5c5c5; color:#444;}
    #mydzfiles {padding:8px; text-align:center; cursor:pointer; background-color:#e5f0ff}
    button.action-save-post:focus {background-color:#e1653f}
   /* Mimic table appearance */
    div.table {
      display: table;
    }
    div.table .file-row {
      display: table-row;
    }
    div.table .file-row > div {
        display: table-cell;
        vertical-align: top;
        border-bottom: 2px solid #fff;
        padding:8px;
    }
    div.table .file-row:nth-child(odd) {
        -background: #f9f9f9;
    }

    div.table#previews .file-row {
        background-color:#e3f1ff;
    }
    div.table#uploaded-previews .file-row {
        background-color:#f6f6f6;
    }

    /* The total progress gets shown by event listeners */
    #total-progress {
      opacity: 0;
      transition: opacity 0.3s linear;
    }

    /* Hide the progress bar when finished */
    #previews .file-row.dz-success .progress {
      opacity: 0;
      transition: opacity 0.3s linear;
    }

    /* Hide the delete button initially */
    #previews .file-row .delete {
        display: none;
    }

    /* Hide the start and cancel buttons and show the delete button */

    #previews .file-row.dz-success .start,
    #previews .file-row.dz-success .cancel {
        display: none;
    }
    #previews .file-row.dz-success .delete {
        display: initial;
    }
    div.preview {width:80px;}

    .cke_textarea_inline.cke_editable.cke_editable_inline {padding:16px; border:1px solid #ccc;}

    .popover {font-size:.95rem}
    .popover-header {font-size:1rem;}
    @media screen and (max-width: 768px)  {
        .media.div-post > a, .media.div-mail > a {display:none;}
    }

    @media screen and (min-width: 800px)  {
      .popover.show {min-width:750px;}
    }
/*    a[href^='/mentions/'] {font-weight:500; color:#881273;}
    a[href^='/mentions/']:hover {color:#ab3496; text-decoration:underline;}
*/    a.my-tag {font-weight:500; background-color:#ede; padding:1px 2px;}
    .dropdown-item {font-weight:400;}
    /* autocomplete */
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto;}
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
    /* page */
/*    .page-content {font-size:.95rem;}
    .navbar-brand {padding-top:10px; padding-bottom:10px; font-size:20px; vertical-align:middle;}
    .navbar-brand img {height:28px!important;}
*/    /** Fancybox **/
    .fancybox-inner, .fancybox-outer, .fancybox-stage {
        bottom: 0;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
    }
    /* table */
    .table-narrow tr>th, .table-narrow tr>td {padding:8px!important;}
    .table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
    .table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}


    .has-error .help-block {color:red;}
    .has-error input, .has-error textarea, .has-error select {border-color:red;}
    .has-success input, .has-success textarea, .has-success select {border-color:green;}
    #my-page-sidebar {font-size:.9rem}
    .ims-navbar {background-color:#3f51b5;}
    .form-control, .dropdown-menu {font-size:.9rem!important}

body .page-content {font-size:15px;}
/*
.dropdown-menu {font-size:.95rem;}
.form-control {font-size:.95rem;}
label {margin-bottom:.2rem;}
*/
.navbar-brand img {height: 28px!important;}
.navbar-brand {padding-top: 7px; padding-bottom: 7px; font-size: 20px; vertical-align: middle;}

@media screen and (min-width:768px) {
body:not(.sidebar-xs) #my-sidebar1 {position:fixed; left:0;}
.sidebar-xs .sidebar-secondary .sidebar-content {left:57px;}
.sidebar-xs .sidebar-secondary {left:57px;}
.sidebar-expand-md.sidebar-secondary .sidebar-content {left:57px;}
}

@media screen and (min-width:1000px) and (max-width:1299px) {
.sidebar-secondary {width:360px;}
}

@media screen and (min-width:1300px) and (max-width:1599px) {
.sidebar-secondary {width:420px}
}

@media screen and (min-width:1600px) {
.sidebar-secondary {width:480px}
}
    <?php if (in_array(USER_ID, [24820])) { ?>
    .navbar-dark.bg-danger, .sidebar-dark.bg-danger {background-color:#d01d15!important;}
    <?php } ?>
    </style>
</head>
<body class=" navbar-top <?= Yii::$app->params['page_class'] ?? '' ?>">
    <?php $this->beginBody(); ?>
    <div class="navbar navbar-expand-md navbar-dark bg-<?= $themeColor ?? 'indigo' ?> fixed-top">
        <div class="navbar-header navbar-dark bg-<?= $themeColor ?? 'indigo' ?> d-none d-md-flex align-items-md-center">
            <div class="navbar-brand navbar-brand-md d-md-flex justify-content-start">
                <a href="/"><img src="/assets/img/logo_165x128_c.png" alt=""></a>
                <a href="/" class="font-weight-bold text-blue">&nbsp;ims</a>
                <a href="javascript:;" class="text-white font-weight-light">&nbsp;/workspace</a>
            </div>
            <div class="navbar-brand navbar-brand-xs d-none d-xs-flex justify-content-start">
                <a href="/"><img src="/assets/img/logo_165x128_c.png" alt=""></a>
                <a href="/" class="font-weight-bold text-blue">&nbsp;ims</a>
                <a href="javascript:;" class="text-white font-weight-light">&nbsp;/workspace</a>
            </div>
        </div>

        <div class="d-flex flex-1 d-md-none">
            <div class="navbar-brand mr-auto">
                <a href="#" class="d-inline-block">
                    <img src="/assets/img/logo_165x128_c.png" alt="">
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="icon-paragraph-justify3"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-secondary-toggle" type="button">
                <i class="icon-more"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="icon-paragraph-justify3"></i>
                    </a>
                </li>
                <?php if (!empty($this->blocks['page_sidebar'])) { ?>
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-secondary-toggle d-none d-md-block" data-popup="tooltip-demo" title="" data-placement="bottom" data-container="body" data-trigger="hover" data-original-title="Hide secondary">
                        <i class="fal fa-exchange"></i>
                    </a>
                </li>
                <?php } ?>
                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fal fa-th-large"></i>
                    </a>

                    <div class="dropdown-menu -dropdown-menu-right dropdown-content wmin-md-350">
                        <div class="dropdown-content-body p-2">
                            <div class="row no-gutters">
                                <div class="col-12 col-sm-4">
                                    <a href="/cases" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-shopping-bag text-green fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C Sales') ?></div>
                                    </a>

                                    <a href="/b2b/cases" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-briefcase text-blue fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B Sales') ?></div>
                                    </a>

                                    <a href="/tours" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-flag text-slate fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Tour operation') ?></div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <a href="/customers" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-users text-pink fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C customers') ?></div>
                                    </a>

                                    <a href="/b2b/clients" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-user-secret text-success fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B customers') ?></div>
                                    </a>

                                    <a href="/blog" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-bullhorn text-info fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Company news') ?></div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <a href="/products" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-list text-warning fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C programs') ?></div>
                                    </a>

                                    <a href="/b2b/programs" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-list-ol text-danger fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B programs') ?></div>
                                    </a>

                                    <a href="/contacts/members" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="fal fa-font text-violet fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Company members') ?></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li id="lisearch" style="min-width:320px; padding-top:6px; padding-bottom:0; margin-bottom:0; height:48px;">
                    <form id="qs" action="/search" class="navbar-form navbar-left" style="width:100%;">
                        <div class="form-group has-feedback" style="width:100%;">
                            <select id="livesearch" style="width:100%;">
                                <option value="" selected="selected"><?= Yii::t('x', 'Search') ?></option>
                            </select>
                        </div>
                        <div id="suggest" class="search-suggest"></div>
                    </form>
                </li>
                <!-- <li class="nav-item dropdown"><?//= MyNotifications::widget([
                    // 'options' => ['class' => 'dropdown nav-notifications'],
                    // 'countOptions' => ['class' => 'badge badge-pill bg-warning-400 ml-auto ml-md-0']
                //]);?>

                </li> -->
                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                        <i class="icon-bubbles4"></i>
                        <span class="d-md-none ml-2">Messages</span>
                        <span id="headNotificationNum" class="badge badge-pill bg-warning-400 ml-auto ml-md-0" data-load_ids="" style="display: none">0</span>
                        <div id="sound" class="d-none"></div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                        <!-- <div class="dropdown-content-header">
                            <span class="font-weight-semibold">Messages</span>
                            <a href="#" class="text-default"><i class="icon-compose"></i></a>
                        </div> -->

                        <div class="dropdown-content-body dropdown-scrollable">
                            <ul class="media-list">
                                <li class="media">
                                    <div class="mr-3 position-relative">
                                        <img src="../../../../global_assets/images/demo/users/face10.jpg" class="rounded-circle" alt="" width="36" height="36">
                                    </div>

                                    <div class="media-body">
                                        <div class="media-title">
                                            <a href="#">
                                                <span class="font-weight-semibold">James Alexander</span>
                                                <span class="text-muted float-right font-size-sm">04:58</span>
                                            </a>
                                        </div>

                                        <span class="text-muted">who knows, maybe that would be the best thing for me...</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="dropdown-content-footer justify-content-center p-0">
                            <a href="#" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="" data-original-title="Load more"><i class="icon-menu7 d-block top-0"></i></a>
                        </div>
                    </div>
                </li>
            </ul>
            <span class="ml-md-3 mr-md-auto"></span>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fal fa-link"></i>
                        <span class="-d-md-none ml-2"><?= Yii::t('x', 'Quick links') ?></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="/hotels"><i class="fal fa-building"></i> <?= Yii::t('x', 'Hotels') ?></a>
                        <a class="dropdown-item" href="/homestays"><i class="fal fa-home"></i> <?= Yii::t('x', 'Homestays') ?></a>
                        <a class="dropdown-item" href="/venues/homestay-calendar"><i class="fal fa-calendar"></i> --- <?= Yii::t('x', 'Homestay calendar') ?></a>
                        <a class="dropdown-item" href="/ref/halongcruises"><i class="fal fa-anchor"></i> <?= Yii::t('x', 'Cruises') ?></a>
                        <a class="dropdown-item" href="/ref/ssspots"><i class="fal fa-camera"></i> <?= Yii::t('x', 'Sightseeing') ?></a>
                        <a class="dropdown-item" href="/venues?type=restaurant&amp;destination_id=1"><i class="fal fa-coffee"></i> <?= Yii::t('x', 'Restaurants') ?></a>
                        <a class="dropdown-item" href="/ref/tables"><i class="fal fa-table"></i> <?= Yii::t('x', 'Other price tables') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/tours?orderby=startdate"><i class="fal fa-car"></i> <?= Yii::t('x', 'Tours starting this month') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/members"><i class="fal fa-font"></i> <?= Yii::t('x', 'Amica members') ?></a>
                    </div>

                </li>


                <li class="nav-item dropdown dropdown-user">
                    <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                        <img src="/timthumb.php?w=100&h=100&src=<?= USER_ID == 1 ? '/assets/img/placeholder.jpg' : Yii::$app->user->identity->image ?>" class="rounded-circle mr-2" height="34" alt="">
                        <span><?= USER_ID == 1 ? Yii::t('x', 'Name') : Yii::$app->user->identity->name ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="/me/profile" class="dropdown-item"><i class="fal fa-user"></i> <?= Yii::t('nav', 'My profile') ?></a>
                        <a href="/me/my-settings/password" class="dropdown-item"><i class="fal fa-key"></i> <?= Yii::t('nav', 'Change password') ?></a>
                        <a href="/me/my-settings/preferences" class="dropdown-item"><i class="fal fa-cog"></i> <?= Yii::t('nav', 'Preferences') ?></a>
                        <a href="/me/work-calendar" class="dropdown-item"><i class="fal fa-calendar"></i> <?= Yii::t('nav', 'Work calendar') ?></a>
                        <div class="dropdown-divider"></div>
                        <a href="/tasks" class="dropdown-item"><i class="fal fa-check"></i> <?= Yii::t('nav', 'My tasks') ?></a>
                        <a href="/mails" class="dropdown-item"><i class="fal fa-envelope"></i> <?= Yii::t('nav', 'My emails') ?></a>
                        <a href="/posts" class="dropdown-item"><i class="fal fa-clipboard-list"></i> <?= Yii::t('nav', 'My notes') ?></a>
                        <a href="/me/my-reports" class="dropdown-item"><i class="fal fa-chart-pie"></i> <?= Yii::t('nav', 'My reports') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item english" href="/select/lang/en"><i class="flag-icon flag-icon-us"></i> <span>English</span></a>
                        <a class="dropdown-item french" href="/select/lang/fr"><i class="flag-icon flag-icon-fr"></i> <span>Français</span></a>
                        <a class="dropdown-item vietnamese" href="/select/lang/vi"><i class="flag-icon flag-icon-vn"></i> <span>Tiếng Việt</span></a>
                        <div class="dropdown-divider"></div>
                        <a href="/logout" class="dropdown-item"><i class="fal fa-power-off"></i> <?= Yii::t('x', 'Log out') ?></a>
                    </div>

                </li>

            </ul>
        </div>
    </div>

    <div class="page-content">
        <div id="my-sidebar1" class="sidebar sidebar-light alpha-<?= $themeColor ?? 'indigo' ?> sidebar-main sidebar-expand-md sidebar-fixed">
            <div class="sidebar-mobile-toggler text-center bg-<?= $themeColor ?? 'indigo' ?>">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>

            <div class="sidebar-content">

                <div class="sidebar-user">
                    <div class="card-body">
                        <div class="media">
                            <div class="mr-3">
                                <a href="#"><img src="<?= Yii::$app->user->identity->image ?>" width="38" height="38" class="rounded-circle" alt=""></a>
                            </div>
                            <div class="media-body">
                                <div class="media-title font-weight-semibold"><?= Yii::$app->user->identity->name ?></div>
                                <div class="font-size-xs opacity-50">
                                    <?//= Yii::$app->user->identity->contact->memberProfile->location ?>
                                </div>
                            </div>
                            <div class="ml-3 align-self-center">
                                <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-sidebar-mobile">
                    <ul class="nav nav-sidebar" data-nav-type="accordion">
                        <?php
                        if (isset(Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']])) {
                            Yii::$app->params['side_nav']['main'] = Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']];
                            foreach (Yii::$app->params['side_nav']['main'] as $item) {
                                echo renderLimitless210MainNavItem($item);
                            }
                        } ?>

                    </ul>
                </div>
            </div>
        </div>

        <?php if (!empty($this->blocks['page_sidebar'])) { ?>
        <div id="my-sidebar2" class="sidebar sidebar-light sidebar-secondary sidebar-expand-md sidebar-fixed">
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-secondary-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                <span class="font-weight-semibold">Navigation</span>
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>

            <div class="sidebar-content">
                <?php // for ($i = 1; $i < random_int(150, 250); $i ++) { echo Yii::$app->security->generateRandomString(random_int(1, 20)), ' ';} ?>
                <?= $this->blocks['page_sidebar'] ?? '' ?>
            </div>
        </div>
        <?php } ?>

        <div class="content-wrapper" style="width:100%;">
            <!--[if lt IE 11]><div class="mb-0 border-0 d-print-none alert alert-warning"><i class="fa fa-warning"></i> You are using an <strong>outdated</strong> browser. Please <a rel="external" class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div><![endif]-->
            <!-- Page header -->
            <?php
            foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
                if (Yii::$app->session->hasFlash($key)) { ?>
            <div class="mb-0 border-0 d-print-none alert alert-<?= $key ?>"><?= $message ?></div>
            <?php
                }
            }
            ?>
            <?php if (!empty(Yii::$app->params['page_notice'])) { ?>
                <?php
                $pageNoticeList = Yii::$app->params['page_notice'];
                foreach ($pageNoticeList as $pageNotice) {
                ?>
            <div class="mb-0 border-0 d-print-none alert alert-<?= $pageNotice[0] ?>"><?= $pageNotice[1] ?></div>
                <?php
                }
                ?>
            <?php } ?>
            <div class="page-header page-header-light">
                <?php if (strpos(Yii::$app->params['page_layout'], '-h') === false) { ?>
                <div class="my-page-header d-lg-flex align-items-center justify-content-between">
                    <div class="my-page-breadcrumb-title">
                        <?php if (0 && strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
                        <div class="my-page-breadcrumb d-print-none">
                            <?php if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                            <a class="breadcrumb-item py-0" href="/"><i class="fa fa-home"></i></a><?php
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
                            <?php } // isset b ?>
                        </div>
                        <?php } //-b ?>
                        <?php if (strpos(Yii::$app->params['page_layout'], '-t') === false) { ?>
                        <div class="my-page-title">
                            <h2 class="my-page-main-title">
                                <?php if (!empty(Yii::$app->params['page_icon'])) { ?><i class="<?= strpos(Yii::$app->params['page_icon'], 'slicon') === false ? 'fa fa-fw fa-' : '' ?><?= Yii::$app->params['page_icon'] ?>"></i><?php } else { ?><i class="icon-arrow-left52"></i><?php } ?>
                                <?= Yii::$app->params['page_title'] ?>
                                <?php if (!empty(Yii::$app->params['page_small_title'])) { ?><span class="my-page-small-title"><?= Yii::$app->params['page_small_title'] ?></span><?php } ?>
                            </h2>
                            <?php if (!empty(Yii::$app->params['page_sub_title'])) { ?><div class="my-page-sub-title"><?= Yii::$app->params['page_sub_title'] ?></div><?php } ?>
                        </div>
                        <?php } // -t ?>
                    </div>
                    <?php if (0 && strpos(Yii::$app->params['page_layout'], '-a') === false) { ?>
                    <div class="my-page-actions d-print-none d-flex align-items-center">
                        <?php if (isset(Yii::$app->params['page_actions']) && is_array(Yii::$app->params['page_actions'])) { ?>
                        <ul class="nav nav-pills nav-pills-bordered nav-pills-toolbar mb-0">
                            <?php
                            foreach (Yii::$app->params['page_actions'] as $iii=>$iBtnGroup) {
                                foreach ($iBtnGroup as $iBtn) {
                                    if (isset($iBtn) && (!isset($iBtn['hidden']) || !$iBtn['hidden'])) {
                                        if (isset($iBtn['submenu']) && is_array($iBtn['submenu'])) { ?>
                            <li class="nav-item dropdown">
                                <span class="nav-link dropdown-toggle" data-toggle="dropdown"></span>
                                <ul class="dropdown-menu dropdown-menu-right"><?php
                    foreach ($iBtn['submenu'] as $i2Btn) {
                        if ($i2Btn == ['-']) { ?>
                                    <li class="dropdown-divider"></li><?php
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
                                } ?>
                                    <?= Html::a($i2BtnIcon.$i2BtnLabel, $i2BtnLink, ['class'=>$i2BtnClass, 'title'=>$i2BtnTitle]) ?><?php
                            }
                        } // if divider
                    } // foreach i2Btn ?>
                                </ul>
                            </li><?php
                    // Neu chua het thi tiep tuc <ul>
                    if ($iii < count(Yii::$app->params['page_actions'])) { ?>
                        </ul>
                        <ul class="ml-1 nav nav-pills nav-pills-bordered nav-pills-toolbar mb-0"><?php
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
                    $iBtnClass = 'nav-link ';
                    $iBtnClass .= isset($iBtn['class']) ? $iBtn['class'] : '';
                    if (isset($iBtn['active']) && $iBtn['active']) {
                        $iBtnClass .= ' active';
                    }
                    $iBtnLink = isset($iBtn['link']) ? $iBtn['link'] : '#';
                    if (substr($iBtnLink, 0, 1) != '#' && substr($iBtnLink, 0, 5) != '@web/' && strpos($iBtnLink, '//') === false) {
                        $iBtnLink = '@web/'.$iBtnLink;
                    }

                    //echo Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]);
                    ?>
                            <li class="nav-item"><?= Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle, '-style'=>'padding:0 4px']) ?></li><?php
                }// if submenu
            } // if not hidden iBtn
        } // foreach button
    } // foreach button group ?>
                        </ul><?php
} ?>
                    </div>
                    <?php } // -a ?>
                </div>
                <?php } // -h ?>
                <?php if (!empty($this->blocks['page_tabsx'])) { ?>
                <div class="my-page-header-2 bg-light">
                    <?= $this->blocks['page_tabs'] ?? '' ?>
                </div>
                <?php } else { ?>
                <?php } ?>
            </div>

            <div class="content">
                <div class="<?= strpos(Yii::$app->params['page_layout'], '-r') === false ? 'row' : '' ?>">
                    <?= $content ?>
                </div>
            </div>

            <!--
            <div class="navbar navbar-expand-lg navbar-light">
                <div class="text-center d-lg-none w-100">
                    <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
                        <i class="icon-unfold mr-2"></i>
                        Footer
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-footer">
                    <span class="navbar-text">
                        IMS v.2020 by <a href="https://www.amica-travel.com">Amica Travel</a>
                    </span>
                    <ul class="navbar-nav ml-lg-auto">
                        <li class="nav-item">
                            <a href="#" class="navbar-nav-link">About</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="navbar-nav-link">
                                <i class="icon-lifebuoy"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="navbar-nav-link font-weight-semibold">
                                <span class="text-pink-400">
                                    <i class="icon-cart2 mr-2"></i>
                                    Contact
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            -->
        </div>

    </div>
    <script type="text/javascript">
    // Setup module
    var FixedSidebarCustomScroll = function() {
        // Setup module components
        // Perfect scrollbar
        var _componentPerfectScrollbar = function() {
            if (typeof PerfectScrollbar == 'undefined') {
                console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
                return;
            }
            // Initialize
            var ps1 = new PerfectScrollbar('#my-sidebar1 .sidebar-content', {
                wheelSpeed: 2,
                wheelPropagation: true
            });
            <?php if (!empty($this->blocks['page_sidebar'])) { ?>
            // Initialize
            // var ps2 = new PerfectScrollbar('#my-sidebar2 .sidebar-content', {
            //     wheelSpeed: 2,
            //     wheelPropagation: true
            // });
            <?php } ?>
        };

        // Return objects assigned to module
        return {
            init: function() {
                _componentPerfectScrollbar();
            }
        }
    }();

    // Initialize module
    document.addEventListener('DOMContentLoaded', function() {
        FixedSidebarCustomScroll.init();
    });
    </script>
    <?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
