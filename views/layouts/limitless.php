<?php
use yii\helpers\Html;
use app\assets\Limitless210Asset;

include('_css_limitless.php');
include('_css_theadmin.php');

include('_nav.php');
include('_js.php');
include('../config/functions.php');

$this->beginPage();

Limitless210Asset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

Yii::$app->params['body_class'] = Yii::$app->params['body_class'] ?? '';


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

    .gmail_quote {display:none;}
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

<body class="<?= Yii::$app->params['body_class'] ?? '' ?>">
    <?php $this->beginBody(); ?>
    <!-- Main navbar -->
    <div class="navbar navbar-expand-md navbar-dark bg-indigo d-print-none">
        <div class="navbar-brand d-flex justify-content-start">
            <a href="/" class="-d-inline-block text-nowrap"><img src="/assets/img/logo_165x128_c.png" alt="Logo"></a>
            <a href="/" class="font-weight-bold text-blue">&nbsp;ims</a>
            <a href="#" class="text-white font-weight-light -dropdown-toggle" -data-toggle="dropdown">&nbsp;/workspace</a>
            <!--
            <div class="dropdown-menu -dropdown-menu-right">
                <a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
                <a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
                <a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
            </div>
            -->
        </div>

        <div class="d-md-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="icon-paragraph-justify3"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="icon-paragraph-justify3"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-make-group mr-2"></i>
                    </a>

                    <div class="dropdown-menu -dropdown-menu-right dropdown-content wmin-md-350">
                        <div class="dropdown-content-body p-2">
                            <div class="row no-gutters">
                                <div class="col-12 col-sm-4">
                                    <a href="/cases" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="slicon-handbag icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C Sales') ?></div>
                                    </a>

                                    <a href="/b2b/cases" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="slicon-briefcase text-blue-400 icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B Sales') ?></div>
                                    </a>

                                    <a href="/tours" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="slicon-directions text-blue-400 icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Tour operation') ?></div>
                                    </a>
                                </div>
                                
                                <div class="col-12 col-sm-4">
                                    <a href="/customers" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="icon-dribbble3 text-pink-400 icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C customers') ?></div>
                                    </a>

                                    <a href="/b2b/clients" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="icon-google-drive text-success-400 icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B customers') ?></div>
                                    </a>

                                    <a href="/blog" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="icon-youtube icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Company news') ?></div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <a href="/products" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="icon-twitter icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2C programs') ?></div>
                                    </a>

                                    <a href="/b2b/programs" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="icon-youtube icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'B2B programs') ?></div>
                                    </a>

                                    <a href="/contacts/members" class="d-block text-default text-center ripple-dark rounded p-1">
                                        <i class="icon-link icon-2x"></i>
                                        <div class="  mt-2"><?= Yii::t('x', 'Company members') ?></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li style="min-width:320px; padding-top:6px; padding-bottom:0; margin-bottom:0; height:48px;">
                    <form id="qs" action="/search" class="navbar-form navbar-left" style="width:100%;">
                        <div class="form-group has-feedback" style="width:100%;">
                            <select id="livesearch" style="width:100%;">
                                <option value="" selected="selected"><?= Yii::t('x', 'Search') ?></option>
                            </select>
                        </div>
                        <div id="suggest" class="search-suggest"></div>
                    </form>
                </li>
            </ul>

            <span class="badge bg-success ml-md-3 mr-md-auto">Online</span>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="slicon-link"></i>
                        <span class="-d-md-none ml-2"><?= Yii::t('x', 'Quick links') ?></span>
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="/venues"><i class="slicon-home"></i> <?= Yii::t('x', 'Hotels') ?></a>
                        <a class="dropdown-item" href="/venues?stra=h"><i class="slicon-home"></i> <?= Yii::t('x', 'Homestays') ?></a>
                        <a class="dropdown-item" href="/venues/homestay-calendar"><i class="slicon-home"></i> --- <?= Yii::t('x', 'Homestay calendar') ?></a>
                        <a class="dropdown-item" href="/ref/halongcruises"><i class="slicon-anchor"></i> <?= Yii::t('x', 'Cruises') ?></a>
                        <a class="dropdown-item" href="/ref/ssspots"><i class="fa fa-truck"></i> <?= Yii::t('x', 'Sightseeing') ?></a>
                        <a class="dropdown-item" href="/venues?type=restaurant&amp;destination_id=1"><i class="fa fa-coffee"></i> <?= Yii::t('x', 'Restaurants') ?></a>
                        <a class="dropdown-item" href="/ref/tables"><i class="fa fa-table"></i> <?= Yii::t('x', 'Other price tables') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/tours?orderby=startdate"><i class="fa fa-car"></i> <?= Yii::t('x', 'Tours starting this month') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/members"><i class="fa fa-font"></i> <?= Yii::t('x', 'Amica members') ?></a>
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

                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->


    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-light alpha-indigo sidebar-main sidebar-expand-md">

            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
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
                                <a href="#"><img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" width="38" height="38" class="rounded-circle" alt=""></a>
                            </div>

                            <div class="media-body">
                                <div class="media-title font-weight-semibold"><?= Yii::$app->user->identity->name ?></div>
                                <div class="font-size-xs opacity-50">
                                    <i class="icon-pin font-size-sm"></i> &nbsp;Amica Travel
                                </div>
                            </div>

                            <div class="ml-3 align-self-center">
                                <a href="#" class="text-white"><i class="icon-cog3"></i></a>
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


        <!-- Main content -->
        <div class="content-wrapper">
            <!--[if lt IE 11]><div class="mb-0 border-0 d-print-none alert alert-warning"><i class="fa fa-warning"></i> You are using an <strong>outdated</strong> browser. Please <a rel="external" class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div><![endif]-->

            <!-- Page header -->
            <style type="text/css">
            .my-page-breadcrumb-title {padding:.8rem 1.25rem;}
            .my-page-breadcrumb + .my-page-title {margin-top:.8rem}
            .my-page-main-title {font-weight:400; margin:0;}
            .my-page-small-title {font-weight:300;}
            .my-page-sub-title {font-size:1rem;}
            .my-page-actions {padding:0 1.25rem;}
            .my-page-actions .nav-link {padding:4px;}
            .my-page-actions .nav-pills .nav-link {padding:.1rem .3rem;}
            </style>
            <?php
            foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
                if (Yii::$app->session->hasFlash($key)) { ?>
            <div class="mb-0 border-0 d-print-none alert alert-<?= $key ?>"><?= $message ?></div>
            <?php
                }
            }
            ?>
            <div class="page-header page-header-light">
                <?php if (strpos(Yii::$app->params['page_layout'], '-h') === false) { ?>
                <div class="my-page-header d-lg-flex align-items-center justify-content-between">
                    <div class="my-page-breadcrumb-title">
                        <?php if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
                        <div class="my-page-breadcrumb d-print-none">
                            <?php if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                            <a class="breadcrumb-item py-0" href="/"><i class="fa fa-home"></i> <?= Yii::t('nav', 'Home') ?></a><?php
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
                <?php } // -h ?>
                <?php if (!empty($this->blocks['page_tabs'])) { ?>
                <div class="my-page-header-2 bg-light">
                    <?= $this->blocks['page_tabs'] ?? '' ?>
                </div>
                <?php } else { ?>
                <?php } ?>
            </div>

            <!-- Content area -->
            <div class="content">
                <div class="row">
                    <?= $content ?>
                </div>
            </div>
            <!-- /content area -->

            <!-- Footer -->
            <footer class="d-md-flex justify-content-between px-3 py-2 d-print-none">
                <div class="footer-copy">&copy; 2007-<?= date('Y') ?> <strong>ims</strong> by <a href="https://www.amica-travel.com?from=ims_footer" target="_blank">Amica Travel</a></div>
                <ul class="footer-links list-inline mb-0">
                    <li class="list-inline-item"><a href="#" class="text-muted" target="_blank"><i class="icon-lifebuoy mr-2"></i> Support</a></li>
                    <li class="list-inline-item"><a href="#" class="text-muted" target="_blank"><i class="icon-file-text2 mr-2"></i> Docs</a></li>
                    <li class="list-inline-item"><a href="/help/bug" class="text-muted"><i class="icon-cart2 mr-2"></i> Contact</a></li>
                </ul>
            </div>
            <!-- /footer -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
    <?php $this->endBody(); ?>
    <div class="d-print-none" id="goTop" title="<?= Yii::t('x', 'Go to top of page') ?>"></div>
</body>
</html>
<?php

$this->endPage();