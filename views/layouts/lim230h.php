<?php
use yii\helpers\Html;
use app\assets\Limitless230hAsset as MainAsset;

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

if (strpos(Yii::$app->params['page_layout'], '.s') !==  false) {
    Yii::$app->params['page_class'] .= ' sidebar-xs';
}

$themeLogo = 'logo_165x128_c.png';
$themeColor = 'indigo';
if (in_array(USER_ID, [24820])) {
    $themeColor = 'danger';
    $themeLogo = 'logo_140x140_w.png';
}
if (in_array(USER_ID, [11])) {
    $themeColor = 'brown';
}

if (!empty($this->blocks['page_sidebar_1'])) {
    Yii::$app->params['page_class'] .= ' sidebar-xs';
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
    <style type="text/css">
@media (min-width: 768px) {
    .sidebar-xs .sidebar-main .nav-item-submenu-reversed .nav-group-sub {
        top: 0!important;
        bottom: 0;
    }
}

.col42 {order:2; width:33%; max-width:33%; flex:0 0 33%; padding-right: .625rem; padding-left: .625rem;}
.col81 {order:1; width:67%; max-width:67%; flex:0 0 67%; padding-right: .625rem; padding-left: .625rem;}

@media screen and (max-width: 1399px) {
    .col42 {order:1; width:100%; max-width:100%; flex:none;}
    .col81 {order:2; width:100%; max-width:100%; flex:none;}
}

.fal.fa-file {color:#999;}
.fal.fa-file-word {color:#295492;}
.fal.fa-file-excel {color:#1c6d42;}
.fal.fa-file-pdf {color:#FC6249;}

body {overflow:hidden;}
.content-wrapper {height:calc(100vh - 50px); display:grid; grid-template-rows:auto <?= empty($this->blocks['page-fixed-top']) ? '' : ' auto'?> 1fr<?= empty($this->blocks['page-fixed-bottom']) ? '' : ' auto' ?>;}
.page-content {font-size:15px;}
#my-page-tabs {background-color:#fff;}
#my-page-content {overflow-x:hidden; overflow-y: auto;}
.h1, h1 {font-size:25px;}
.h2, h2 {font-size:23px;}
.h3, h3 {font-size:21px;}
.h4, h4 {font-size:19px;}
.h5, h5 {font-size:17px;}
.h6, h6 {font-size:16px;}
.dropdown-menu, .sidebar .nav-item, .btn, .form-control {font-size:14px;}
.navbar-nav-link {font-size:14px;}
.navbar-nav-link .fal, .navbar-toggler .fal {font-size:16px;}
.select2-selection--multiple .select2-search--inline .select2-search__field {font-size:14px!important;}
.navbar-brand {padding:0;}
/* main search select2 */
select#livesearch[multiple] {height:36px;}
.select2-selection--multiple .select2-search--inline:first-child .select2-search__field {padding-left:.2rem!important;}
.select2-selection--multiple .select2-search--inline:first-child {padding-left:8px!important;}
/* table */
.table-narrow tr>th, .table-narrow tr>td {padding:8px!important;}
.table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
.table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}
/** header **/
.my-page-header {display:grid; grid-template-areas:"a b" "c b"; grid-template-columns:1fr auto; background-color:#fff; padding-bottom:20px; border-bottom:1px solid #ddd;}
    .my-page-breadcrumb {grid-area:a; padding:0 20px; margin-top:20px;}
        .my-page-breadcrumb .fal {font-size:14px;}
    .my-page-title {grid-area:c; padding:0 20px; margin-top:20px;}
    .my-page-actions {grid-area:b; padding:0 20px; margin-top:20px;}
        .my-page-actions .nav-link {padding:3px 6px; font-size:14px;}
        .my-page-actions .nav-pills {background-color:#fff;}

.my-page-breadcrumb + .my-page-title {margin-top:10px;}
@media screen and (max-width:767px) {
    .my-page-header {display:block}
}

/** old **/
.badge.status {text-transform:uppercase; color:#fff}
.badge.b2b {background-color:#c60;}
.badge.b2c {background-color:#999;}
.badge.priority {background-color:#660;}
.badge.vespa {background-color:purple;}
.badge.status.open {background-color:#369;}
.badge.status.closed {background-color:#333;}
.badge.status.onhold {background-color:#666;}
.badge.status.pending {background-color:#666;}
.badge.status.lost {background-color:#c66;}
.badge.status.won {background-color:#393;}

.con { position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px;}

@media (max-width: 1199px) {
    .con-1 {width:100%;}
    .con-2 {width:100%;}
}
@media (max-width: 799px) {
    .con-1-1 {width:100%;}
    .con-1-2 {width:100%;}
}
@media (min-width: 800px) and (max-width: 1199px) {
    .con-1-1 {width:50%; float:left;}
    .con-1-2 {width:50%; float:left;}
}
@media (min-width: 1200px) and (max-width: 1399px) {
    .con-1 {width: 41.66666667%; left:58.33333333%; float:left;}
    .con-2 {width: 58.33333333%; right:41.66666667%; float:left;}
    .con-1-1 {width:100%;}
    .con-1-2 {width:100%;}
}
@media (min-width: 1400px) and (max-width: 1599px) {
    .con-1 {width: 33.33333333%; left:66.66666667%; float:left;}
    .con-2 {width: 66.66666667%; right:33.33333333%; float:left;}
    .con-1-1 {width:100%;}
    .con-1-2 {width:100%;}
}
@media (min-width: 1600px) {
    .con-1 {width:50%; left:50%; float:left;}
    .con-2 {width:50%; right:50%; float:left;}
    .con-1-1 {width:50%; float:left;}
    .con-1-2 {width:50%; float:left;}
}

.post-body {max-width: 800px;}

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

    <?php if (!empty($this->blocks['page_sidebar_1'])) { ?>
    /** sidebar **/
    @media screen and (max-width:767px) {
        .sidebar-mobile-secondary .sidebar-secondary {width:90%;}
        .sidebar-mobile-secondary .sidebar-secondary.sidebar-fullscreen {width:100%;}
    }
    @media screen and (min-width:768px) {
    body:not(.sidebar-xs) #my-sidebar {position:fixed; left:0;}
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
    <?php } ?>

    .has-error .help-block {color:red;}
    .has-error input, .has-error textarea, .has-error select {border-color:red;}
    .has-success input, .has-success textarea, .has-success select {border-color:green;}
    #my-page-sidebar {font-size:.9rem}
    .ims-navbar {background-color:#3f51b5;}
    .form-control, .dropdown-menu {font-size:.9rem!important}
    <?php if (in_array(USER_ID, [1, 24820])) { ?>
    .navbar-dark.bg-danger, .sidebar-mobile-toggler.bg-danger {background-color:#d01d15!important;}
    <?php } ?>

@media print {
    body {overflow:auto!important;}
    .content-wrapper {height:auto!important;}
    .my-page-header {border:none!important;}
}

    </style>
</head>
<body class="<?= Yii::$app->params['page_class'] ?> navbar-top sidebar-right-visible">
    <?php $this->beginBody(); ?>
    <!-- Main navbar -->
    <div class="navbar navbar-expand-md navbar-light alpha-<?= $themeColor ?> fixed-top d-print-none">
    
        <!-- Header with logos -->
        <div class="navbar-header navbar-dark bg-<?= $themeColor ?> d-none d-md-flex align-items-md-center">
            <div class="navbar-brand navbar-brand-md" style="font-size:21px; font-weight:300; padding-top:inherit; padding-bottom:inherit;">
                <a href="/" class="d-inline-block">
                    <img src="/assets/img/<?= $themeLogo ?>" alt="Logo" style="display:inline-block; height:24px">
                </a>
                <span class="text-white"><strong>ims</strong> / workspace</span>
            </div>
    
            <div class="navbar-brand navbar-brand-xs py-0">
                <a href="/" class="d-inline-block">
                    <img src="/assets/img/<?= $themeLogo ?>" alt="Logo" style="height:24px">
                </a>
            </div>
        </div>
        <!-- /header with logos -->
    
    
        <!-- Mobile controls -->
        <div class="d-flex flex-1 d-md-none align-items-center">
            <div class="navbar-brand mr-auto" style="font-size:23px; font-weight:300;">
                <a href="/" class="d-inline-block text-<?= $themeColor ?>">
                    <img src="/assets/img/<?= $themeLogo ?>" alt="Logo" style="display:inline-block; height:24px">
                </a>
                <span class="text-<?= $themeColor ?>"><strong>ims</strong> / workspace</span>
            </div>
            <!--
            <button class="navbar-toggler" type="button">
                <i class="fal fa-search"></i>
            </button>
            -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="fal fa-align-justify" style="font-size:16px"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="fal fa-align-left"></i>
            </button>
        </div>
        <!-- /mobile controls -->
        
        
        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav mr-md-2">
                <?php if (empty($this->blocks['page_sidebar_1'])) { ?>
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="fal fa-align-left"></i>
                    </a>
                </li>
                <?php } ?>
                <!--
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-secondary-toggle d-none d-md-block" data-popup="tooltip-demo" title="Hide secondary" data-placement="bottom" data-container="body" data-trigger="hover">
                        <i class="fal fa-align-left"></i>
                    </a>
                </li>
                -->
                <li class="nav-item dropdown -d-none">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fal fa-search"></i>
                        <span class="-d-md-none ml-2" id="search-for"><?= Yii::t('x', 'Search anything') ?></span>
                    </a>
                    <div class="dropdown-menu" id="action-select-search-in">
                        <a class="dropdown-item" data-search="tour" href="#"><?= Yii::t('x', 'Tours') ?></a>
                        <a class="dropdown-item" data-search="contact" href="#"><?= Yii::t('x', 'Contacts') ?></a>
                        <a class="dropdown-item" data-search="file" href="#"><?= Yii::t('x', 'Sales files') ?></a>
                        <!-- a class="dropdown-item" data-search="attachment" href="#a"><?= Yii::t('x', 'Attachments') ?></a -->
                        <a class="dropdown-item" data-search="place" href="#"><?= Yii::t('x', 'Places/Vendors') ?></a>
                        <!-- a class="dropdown-item" data-search="vendor" href="#"><?= Yii::t('x', 'Vendors') ?></a -->
                        <div role="separator" class="dropdown-divider"></div>
                        <a class="dropdown-item" data-search="" href="#"><?= Yii::t('x', 'Search anything') ?></a>
                        <div role="separator" class="dropdown-divider"></div>
                        <h6 class="dropdown-header text-uppercase"><?= Yii::t('x', 'Advanced search') ?></h6>
                        <a class="dropdown-item" href="/search"><?= Yii::t('x', 'Posts') ?></a>
                        <a class="dropdown-item" href="/search/attachments"><?= Yii::t('x', 'Attachments') ?></a>
                    </div>
                </li>
            </ul>

            <select id="livesearch" multiple="multiple" class="form-control"></select>

            <ul class="navbar-nav ml-md-2">
                <!-- 
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link">
                        <i class="fal fa-bell"></i>
                        <span class="d-md-none ml-2"><?= Yii::t('x', 'Notifications') ?></span>
                        <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0"><?= random_int(1, 20) ?></span>
                    </a>
                </li>
                -->

                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown"  title="<?= Yii::t('x', 'Quick links') ?>">
                        <i class="fal fa-cube"></i>
                        <span class="ml-2"><?= Yii::t('x', 'Quick links') ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                        <div class="dropdown-content-body p-2">
                            <div class="px-3 py-1 text-center"><?= Yii::t('x', 'Quick links') ?></div>
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <a href="/products" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-list text-green fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C programs') ?></div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="/files" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-shopping-bag text-green fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C Sales') ?></div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="/customers" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-users text-green fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C customers') ?></div>
                                    </a>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <a href="/b2b/programs" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-list-ol text-blue fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B programs') ?></div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="/b2b/cases" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-briefcase text-blue fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B Sales') ?></div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="/b2b/clients" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-users text-blue fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B customers') ?></div>
                                    </a>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <a href="/tours" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-flag text-pink fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Tour operation') ?></div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="/blog" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-bullhorn text-info fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Company news') ?></div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="/contacts/members" class="d-block text-default text-center rounded p-1">
                                        <i class="fal fa-font text-violet fa-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Company members') ?></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-user">
                    <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                        <img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle mr-2" height="34" alt="">
                        <span><?= Yii::$app->user->identity->name ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="/me/profile" class="dropdown-item"><i class="slicon-user"></i> <?= Yii::t('nav', 'My profile') ?></a>
                        <a href="/me/my-settings/password" class="dropdown-item"><i class="slicon-key"></i> <?= Yii::t('nav', 'Change password') ?></a>
                        <a href="/me/my-settings/preferences" class="dropdown-item"><i class="slicon-settings"></i> <?= Yii::t('nav', 'Preferences') ?></a>
                        <a href="/me/work-calendar" class="dropdown-item"><i class="slicon-calendar"></i> <?= Yii::t('nav', 'Work calendar') ?></a>
                        <div class="dropdown-divider"></div>
                        <a href="/tasks" class="dropdown-item"><i class="slicon-check"></i> <?= Yii::t('nav', 'My tasks') ?></a>
                        <a href="/mails" class="dropdown-item"><i class="slicon-envelope"></i> <?= Yii::t('nav', 'My emails') ?></a>
                        <a href="/posts" class="dropdown-item"><i class="slicon-notebook"></i> <?= Yii::t('nav', 'My notes') ?></a>
                        <a href="/me/my-reports" class="dropdown-item"><i class="slicon-pie-chart"></i> <?= Yii::t('nav', 'My reports') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item english" href="/select/lang/en"><i class="flag-icon flag-icon-us"></i> <span>English</span></a>
                        <a class="dropdown-item french" href="/select/lang/fr"><i class="flag-icon flag-icon-fr"></i> <span>Français</span></a>
                        <a class="dropdown-item vietnamese" href="/select/lang/vi"><i class="flag-icon flag-icon-vn"></i> <span>Tiếng Việt</span></a>
                        <div class="dropdown-divider"></div>
                        <a href="/logout" class="dropdown-item"><i class="slicon-power"></i> <?= Yii::t('x', 'Log out') ?></a>
                    </div>
                </li>
                <!--
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-right-toggle d-none d-md-block">
                        <i class="fal fa-align-right"></i>
                    </a>
                </li>
                -->
            </ul>
        </div>
        <!-- /navbar content -->
        
    </div>
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content">
        <!-- Main sidebar -->
        <div class="sidebar sidebar-light alpha-<?= $themeColor ?> sidebar-main sidebar-expand-md sidebar-fixed d-print-none" id="my-sidebar">
            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center bg-<?= $themeColor ?>">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                IMS 
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <!-- /sidebar mobile toggler -->
            <!-- Sidebar content -->
            <div class="sidebar-content">
                <!-- User menu -->
                <div class="sidebar-user">
                    <div class="card-body">
                        <div class="media">
                            <div class="mr-3">
                                <a href="/me/profile"><img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" width="38" height="38" class="rounded-circle" alt=""></a>
                            </div>

                            <div class="media-body">
                                <div class="media-title font-weight-semibold"><?= Yii::$app->user->identity->contact->name ?></div>
                                <div class="font-size-xs opacity-50">
                                    <i class="fal fa-map-marker font-size-sm"></i> &nbsp;<?= Yii::$app->user->identity->contact->memberProfile->location ?>
                                </div>
                            </div>
                            <div class="ml-3 align-self-center">
                                <a href="/me/profile" class="text-<?= $themeColor ?>"><i class="fal fa-cog"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /user menu -->
                <!-- Main navigation -->
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
                <!-- /main navigation -->
            </div>
            <!-- /sidebar content -->
        </div>
        <!-- /main sidebar -->

        <?php if (!empty($this->blocks['page_sidebar_1'])) { ?>
        <!-- Secondary sidebar -->
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md sidebar-fixed" id="my-sidebar-1">
            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-secondary-toggle">
                    <i class="fal fa-arrow-left"></i>
                </a>
                <span class="font-weight-semibold"><?= Yii::$app->params['page_sidebar_1_title'] ?? '' ?></span>
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <!-- /sidebar mobile toggler -->
            <!-- Sidebar content -->
            <div class="sidebar-content">
                <?= $this->blocks['page_sidebar_1'] ?>
            </div>
            <!-- /sidebar content -->
        </div>
        <!-- /secondary sidebar -->
        <?php } ?>

        <!-- Main content -->
        <div class="content-wrapper">
            <div id="my-page-alerts">
                <!--[if lt IE 11]><div class="mb-0 border-0 d-print-none alert alert-warning"><i class="fal fa-warning"></i> You are using an <strong>outdated</strong> browser. Please <a rel="external" class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div><![endif]-->
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
            </div><!-- #my-page-alerts -->
            <?php if (!empty($this->blocks['page-fixed-top'])) { ?><div id="my-page-fixed-top"><?= $this->blocks['page-fixed-top'] ?></div><!-- #my-page-fixed-top --><?php } ?>
            <div id="my-page-content">

                <?php if (strpos(Yii::$app->params['page_layout'], '-h') ===  false) { ?>
                <!-- Page header -->
                <div class="my-page-header">
                    <?php if (strpos(Yii::$app->params['page_layout'], '-b') === false &&  isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                    <div class="my-page-breadcrumb d-print-none">
                        <div class="breadcrumb breadcrumb-caret">
                            <a class="breadcrumb-item py-0" href="/"><i class="fal fa-home"></i></a><?php
                        foreach (Yii::$app->params['page_breadcrumbs'] as $item) {
                            if (!empty($item)) {
                                if (!isset($item[1]) || true === $item[1]) { ?>
                            <span class="breadcrumb-item py-0 active"><?= $item[0] ?></span><?php
                            } else {
                                if (substr($item[1], 0, 1) != '#' && substr($item[1], 0, 1) != '@' && strpos($item[1], '//') === false) {
                                    $item[1] = '@web/'.$item[1];
                                } ?>
                            <a class="breadcrumb-item py-0 <?= isset($item[2]) && $item[2] === true ? 'active' : '' ?>" href="<?= str_replace('@web', '', $item[1]) ?>"><?= $item[0] ?></a><?php
                                }
                            }
                        } ?>
                        </div>
                    </div>
                    <?php } //-b ?>

                    <?php if (strpos(Yii::$app->params['page_layout'], '-t') ===  false) { ?>
                    <div class="my-page-title">
                        <h2 class="mb-0">
                            <?php if (!empty(Yii::$app->params['page_icon'])) { ?><i class="fal fa-<?= Yii::$app->params['page_icon'] ?> mr-1"></i><?php } ?>
                            <span class="font-weight-semibold"><?= Yii::$app->params['page_title'] ?></span>
                            <?= Yii::$app->params['page_small_title'] ?? '' ?>
                            <?php if (!empty(Yii::$app->params['page_sub_title'])) { ?><small class="d-block"><?= Yii::$app->params['page_sub_title'] ?></small><?php } ?>
                        </h2>
                    </div>
                    <?php } // -t ?>

                    <?php if (strpos(Yii::$app->params['page_layout'], '-a') === false) { ?>
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
                <!-- /page header -->
                <?php } // -h ?>

                <?php if (!empty($this->blocks['page_tabs'])) { ?>
                <div id="my-page-tabs">
                    <?= $this->blocks['page_tabs'] ?>
                </div>
                <?php } ?>

                <div id="my-page-body" class="<?= strpos(Yii::$app->params['page_layout'], '-r') === false ? 'row' : '' ?> <?= strpos(Yii::$app->params['page_layout'], '-p') === false ? 'p-3' : '' ?>">
                <?= $content ?>
                </div>

            </div><!-- #my-page-content -->
            <?php if (!empty($this->blocks['page-fixed-bottom'])) { ?><div id="my-page-fixed-bottom"><?= $this->blocks['page-fixed-bottom'] ?></div><!-- #my-page-fixed-bottom --><?php } ?>
            
        </div>
        <!-- /main content -->

        <?php if (!empty($this->blocks['page_sidebar_2'])) { ?>
        <div class="sidebar sidebar-light sidebar-right sidebar-expand-md sidebar-fixed" id="my-sidebar-1">
            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
                <span class="font-weight-semibold">Right sidebar</span>
                <a href="#" class="sidebar-mobile-right-toggle">
                    <i class="icon-arrow-right8"></i>
                </a>
            </div>
            <!-- /sidebar mobile toggler -->
            <!-- Sidebar content -->
            <div class="sidebar-content">
                <?= $this->blocks['page_sidebar_2'] ?>
            </div>
            <!-- /sidebar content -->
        </div>
        <?php } ?>

    </div>
    <!-- /page content -->
    <?php $this->endBody(); ?>
    <script>
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
            var ps1 = new PerfectScrollbar('#my-sidebar .sidebar-content', {
                wheelSpeed: 2,
                wheelPropagation: true
            });
                        // Initialize
            // var ps2 = new PerfectScrollbar('#my-sidebar2 .sidebar-content', {
            //     wheelSpeed: 2,
            //     wheelPropagation: true
            // });
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
</body>
</html>
<?php $this->endPage();

