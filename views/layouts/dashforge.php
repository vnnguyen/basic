<?php
use yii\helpers\Html;
use app\assets\DashforgeAsset as MainAsset;

// include('_nav.php');
// include('_js.php');
include('../config/functions.php');

$this->beginPage();

MainAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

Yii::$app->params['body_class'] = Yii::$app->params['body_class'] ?? '';

Yii::$app->params['page_notice'] = Yii::$app->params['page_notice'] ?? [];

?><!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Yii::$app->params['page_meta_title'] ?> | amica.ims</title>

    <?= Html::csrfMetaTags() ?>
    <?php $this->head(); ?>

    <?php /*
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="../../../../global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="../../../../global_assets/js/main/jquery.min.js"></script>
    <script src="../../../../global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="../../../../global_assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files --> 
    <script src="assets/js/app.js"></script>
    <!-- /theme JS files -->
    */ ?>
    <style>
.my-page-breadcrumb-title {padding:.8rem 1.25rem;}
.my-page-breadcrumb + .my-page-title {margin-top:.8rem}
.my-page-main-title {font-weight:400; margin:0;}
.my-page-small-title {font-weight:300;}
.my-page-sub-title {font-size:1rem;}
.my-page-actions {padding:0 1.25rem;}
.my-page-actions .nav-link {padding:4px;}
.my-page-actions .nav-pills .nav-link {padding:.1rem .3rem;}

/* table */
.table-narrow tr>th, .table-narrow tr>td {padding:8px!important;}
.table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
.table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}

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
    .page-content {font-size:.95rem;}
    .navbar-brand {padding-top:10px; padding-bottom:10px; font-size:20px; vertical-align:middle;}
    .navbar-brand img {height:28px!important;}
    /** Fancybox **/
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

    /** RIBBON **/
.ribbon {
  position: absolute;
  top: -3px;
  left: -3px;
  width: 150px;
  height: 150px;
  text-align: center;
  background-color: transparent;
  transform:none!important;
  -webkit-transform:none!important;
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

<body class="page-profile <?= Yii::$app->params['body_class'] ?? '' ?>">
    <?php $this->beginBody(); ?>
    <header class="navbar navbar-header navbar-header-fixed">
        <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
        <div class="navbar-brand">
            <img src="https://my.amicatravel.com/assets/img/logo_165x128_c.png" style="height:28px">
            <a href="/" class="df-logo">ims<span>.workspace</span></a>
        </div><!-- navbar-brand -->
        <div id="navbarMenu" class="navbar-menu-wrapper">
            <div class="navbar-menu-header">
                <img src="https://my.amicatravel.com/assets/img/logo_165x128_c.png" style="height:28px">
                <a href="/" class="df-logo">ims<span>.workspace</span></a>
                <a id="mainMenuClose" href=""><i data-feather="x"></i></a>
            </div><!-- navbar-menu-header -->
            <ul class="nav navbar-menu">
          <li class="nav-label pd-l-20 pd-lg-l-25 d-lg-none">Main Navigation</li>
          <li class="nav-item with-sub active">
            <a href="" class="nav-link"><i data-feather="pie-chart"></i> Dashboard</a>
            <ul class="navbar-menu-sub">
              <li class="nav-sub-item"><a href="dashboard-one.html" class="nav-sub-link"><i data-feather="bar-chart-2"></i>Sales Monitoring</a></li>
              <li class="nav-sub-item"><a href="dashboard-two.html" class="nav-sub-link"><i data-feather="bar-chart-2"></i>Website Analytics</a></li>
              <li class="nav-sub-item"><a href="dashboard-three.html" class="nav-sub-link"><i data-feather="bar-chart-2"></i>Cryptocurrency</a></li>
              <li class="nav-sub-item"><a href="dashboard-four.html" class="nav-sub-link"><i data-feather="bar-chart-2"></i>Helpdesk Management</a></li>
            </ul>
          </li>
          <li class="nav-item with-sub">
            <a href="" class="nav-link"><i data-feather="package"></i> Apps</a>
            <ul class="navbar-menu-sub">
              <li class="nav-sub-item"><a href="app-calendar.html" class="nav-sub-link"><i data-feather="calendar"></i>Calendar</a></li>
              <li class="nav-sub-item"><a href="app-chat.html" class="nav-sub-link"><i data-feather="message-square"></i>Chat</a></li>
              <li class="nav-sub-item"><a href="app-contacts.html" class="nav-sub-link"><i data-feather="users"></i>Contacts</a></li>
              <li class="nav-sub-item"><a href="app-file-manager.html" class="nav-sub-link"><i data-feather="file-text"></i>File Manager</a></li>
              <li class="nav-sub-item"><a href="app-mail.html" class="nav-sub-link"><i data-feather="mail"></i>Mail</a></li>
            </ul>
          </li>
          <li class="nav-item with-sub">
            <a href="" class="nav-link"><i data-feather="layers"></i> Pages</a>
            <div class="navbar-menu-sub">
              <div class="d-lg-flex">
                <ul>
                  <li class="nav-label">Authentication</li>
                  <li class="nav-sub-item"><a href="page-signin.html" class="nav-sub-link"><i data-feather="log-in"></i> Sign In</a></li>
                  <li class="nav-sub-item"><a href="page-signup.html" class="nav-sub-link"><i data-feather="user-plus"></i> Sign Up</a></li>
                  <li class="nav-sub-item"><a href="page-verify.html" class="nav-sub-link"><i data-feather="user-check"></i> Verify Account</a></li>
                  <li class="nav-sub-item"><a href="page-forgot.html" class="nav-sub-link"><i data-feather="shield-off"></i> Forgot Password</a></li>
                  <li class="nav-label mg-t-20">User Pages</li>
                  <li class="nav-sub-item"><a href="page-profile-view.html" class="nav-sub-link"><i data-feather="user"></i> View Profile</a></li>
                  <li class="nav-sub-item"><a href="page-connections.html" class="nav-sub-link"><i data-feather="users"></i> Connections</a></li>
                  <li class="nav-sub-item"><a href="page-groups.html" class="nav-sub-link"><i data-feather="users"></i> Groups</a></li>
                  <li class="nav-sub-item"><a href="page-events.html" class="nav-sub-link"><i data-feather="calendar"></i> Events</a></li>
                </ul>
                <ul>
                  <li class="nav-label">Error Pages</li>
                  <li class="nav-sub-item"><a href="page-404.html" class="nav-sub-link"><i data-feather="file"></i> 404 Page Not Found</a></li>
                  <li class="nav-sub-item"><a href="page-500.html" class="nav-sub-link"><i data-feather="file"></i> 500 Internal Server</a></li>
                  <li class="nav-sub-item"><a href="page-503.html" class="nav-sub-link"><i data-feather="file"></i> 503 Service Unavailable</a></li>
                  <li class="nav-sub-item"><a href="page-505.html" class="nav-sub-link"><i data-feather="file"></i> 505 Forbidden</a></li>
                  <li class="nav-label mg-t-20">Other Pages</li>
                  <li class="nav-sub-item"><a href="page-timeline.html" class="nav-sub-link"><i data-feather="file-text"></i> Timeline</a></li>
                  <li class="nav-sub-item"><a href="page-pricing.html" class="nav-sub-link"><i data-feather="file-text"></i> Pricing</a></li>
                  <li class="nav-sub-item"><a href="page-help-center.html" class="nav-sub-link"><i data-feather="file-text"></i> Help Center</a></li>
                  <li class="nav-sub-item"><a href="page-invoice.html" class="nav-sub-link"><i data-feather="file-text"></i> Invoice</a></li>
                </ul>
              </div>
            </div><!-- nav-sub -->
          </li>
          <li class="nav-item"><a href="../../components/" class="nav-link"><i data-feather="box"></i> Components</a></li>
          <li class="nav-item"><a href="../../collections/" class="nav-link"><i data-feather="archive"></i> Collections</a></li>
        </ul>
      </div><!-- navbar-menu-wrapper -->
      <div class="navbar-right">
        <a id="navbarSearch" href="" class="search-link"><i data-feather="search"></i></a>
        <div class="dropdown dropdown-message">
          <a href="" class="dropdown-link new-indicator" data-toggle="dropdown">
            <i data-feather="message-square"></i>
            <span>5</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header">New Messages</div>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/350" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <strong>Socrates Itumay</strong>
                  <p>nam libero tempore cum so...</p>
                  <span>Mar 15 12:32pm</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/500" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <strong>Joyce Chua</strong>
                  <p>on the other hand we denounce...</p>
                  <span>Mar 13 04:16am</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/600" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <strong>Althea Cabardo</strong>
                  <p>is there anyone who loves...</p>
                  <span>Mar 13 02:56am</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/500" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <strong>Adrian Monino</strong>
                  <p>duis aute irure dolor in repre...</p>
                  <span>Mar 12 10:40pm</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <div class="dropdown-footer"><a href="">View all Messages</a></div>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        <div class="dropdown dropdown-notification">
          <a href="" class="dropdown-link new-indicator" data-toggle="dropdown">
            <i data-feather="bell"></i>
            <span>2</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header">Notifications</div>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/350" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <p>Congratulate <strong>Socrates Itumay</strong> for work anniversaries</p>
                  <span>Mar 15 12:32pm</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/500" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <p><strong>Joyce Chua</strong> just created a new blog post</p>
                  <span>Mar 13 04:16am</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/600" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <p><strong>Althea Cabardo</strong> just created a new blog post</p>
                  <span>Mar 13 02:56am</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <a href="" class="dropdown-item">
              <div class="media">
                <div class="avatar avatar-sm avatar-online"><img src="../https://via.placeholder.com/500" class="rounded-circle" alt=""></div>
                <div class="media-body mg-l-15">
                  <p><strong>Adrian Monino</strong> added new comment on your photo</p>
                  <span>Mar 12 10:40pm</span>
                </div><!-- media-body -->
              </div><!-- media -->
            </a>
            <div class="dropdown-footer"><a href="">View all Notifications</a></div>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        <div class="dropdown dropdown-profile">
          <a href="" class="dropdown-link" data-toggle="dropdown" data-display="static">
            <div class="avatar avatar-sm"><img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle" alt=""></div>
          </a><!-- dropdown-link -->
          <div class="dropdown-menu dropdown-menu-right tx-13">
            <div class="avatar avatar-lg mg-b-15"><img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle" alt=""></div>
            <h6 class="tx-semibold mg-b-5"><?= Yii::$app->user->identity->contact->name ?></h6>
            <p class="mg-b-25 tx-12 tx-color-03">@<?= Yii::$app->user->identity->mention ?></p>

            <a href="" class="dropdown-item"><i data-feather="edit-3"></i> Edit Profile</a>
            <a href="page-profile-view.html" class="dropdown-item"><i data-feather="user"></i> View Profile</a>
            <div class="dropdown-divider"></div>
            <a href="page-help-center.html" class="dropdown-item"><i data-feather="help-circle"></i> Help Center</a>
            <a href="" class="dropdown-item"><i data-feather="life-buoy"></i> Forum</a>
            <a href="" class="dropdown-item"><i data-feather="settings"></i>Account Settings</a>
            <a href="" class="dropdown-item"><i data-feather="settings"></i>Privacy Settings</a>
            <a href="page-signin.html" class="dropdown-item"><i data-feather="log-out"></i>Sign Out</a>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
      </div><!-- navbar-right -->
      <div class="navbar-search">
        <div class="navbar-search-header">
          <input type="search" class="form-control" placeholder="Type and hit enter to search...">
          <button class="btn"><i data-feather="search"></i></button>
          <a id="navbarSearchClose" href="" class="link-03 mg-l-5 mg-lg-l-10"><i data-feather="x"></i></a>
        </div><!-- navbar-search-header -->
        <div class="navbar-search-body">
          <label class="tx-10 tx-medium tx-uppercase tx-spacing-1 tx-color-03 mg-b-10 d-flex align-items-center">Recent Searches</label>
          <ul class="list-unstyled">
            <li><a href="dashboard-one.html">modern dashboard</a></li>
            <li><a href="app-calendar.html">calendar app</a></li>
            <li><a href="../../collections/modal.html">modal examples</a></li>
            <li><a href="../../components/el-avatar.html">avatar</a></li>
          </ul>

          <hr class="mg-y-30 bd-0">

          <label class="tx-10 tx-medium tx-uppercase tx-spacing-1 tx-color-03 mg-b-10 d-flex align-items-center">Search Suggestions</label>

          <ul class="list-unstyled">
            <li><a href="dashboard-one.html">cryptocurrency</a></li>
            <li><a href="app-calendar.html">button groups</a></li>
            <li><a href="../../collections/modal.html">form elements</a></li>
            <li><a href="../../components/el-avatar.html">contact app</a></li>
          </ul>
        </div><!-- navbar-search-body -->
      </div><!-- navbar-search -->
</header><!-- navbar -->

<div class="content content-fixed bd-b">
    <div class="container-fluid pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">My Profile</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Timeline</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0"><?= Yii::$app->params['page_title'] ?></h4>
            </div>
            <div class="search-form mg-t-20 mg-sm-t-0">
                <input type="search" class="form-control" placeholder="Search posts">
                <button class="btn" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
        <?= $content ?>
        </div>
    </div>
</div>
<footer class="footer">
    <div>
        <span>© 2019 DashForge v1.0.0. </span>
        <span>Created by <a href="http://themepixels.me">ThemePixels</a></span>
    </div>
    <div>
        <nav class="nav">
            <a href="https://themeforest.net/licenses/standard" class="nav-link">Licenses</a>
            <a href="../../change-log.html" class="nav-link">Change Log</a>
            <a href="https://discordapp.com/invite/RYqkVuw" class="nav-link">Get Help</a>
        </nav>
    </div>
</footer>
   <?php $this->endBody(); ?>
</body>
</html>
<?php

$this->endPage();