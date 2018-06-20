<?php

$css = <<<'TXT'
body {overflow-y:scroll;}
@media(min-width: 769px){
    .sidebar-main {width:235px;}
    /*.nav-tabs>li {font-size:14px;}*/
}

.navbar-header {min-width:auto;}
#navbar-mobile .navbar-nav {margin-left:0;}
.navbar-brand {padding:0 20px;}
.navbar-brand>img {margin-top:8px; height:30px;}
.cke_chrome {box-shadow:none!important;}

/** ?? **/
.font-helvetica-14 .dropdown-menu>li>a {padding:4px 15px;}
.font-helvetica-14 .content {font-size:14px; font-family:Helvetica, Arial, sans-serif;}
.font-helvetica-14 .table-condensed th, .font-helvetica-14 .table-condensed td {padding:8px!important;}
.font-helvetica-14 .nav-tabs>li {font-size:14px;}

.font-roboto-15 body {font-size:15px;}
.font-roboto-15 blockquote {font-size:15px;}
.font-roboto-15 .navbar, .font-roboto-15 .dropdown-menu, .font-roboto-15 .form-control {font-size:14px;}
.font-roboto-15 .content {font-size:15px;}
.font-roboto-15 .sidebar {font-size:14px;}
.font-roboto-15 .navigation li a {color:#869298;}
.font-roboto-15 .navigation li>a:hover, .font-roboto-15 .navigation li.active>a {color:#fff;}
.font-roboto-15 .navbar-inverse .navbar-nav>li>a, .font-roboto-15 .navbar-inverse .navbar-text {color:#a6b2b8;}
.font-roboto-15 .navbar-inverse .navbar-nav>li>a:hover, .font-roboto-15 .navbar-inverse .navbar-text:hover {color:#fff;}
.font-roboto-15 .navbar-inverse .navbar-nav>li.active>a, .font-roboto-15 .navbar-inverse .navbar-text.active {color:#fff;}
.font-roboto-15 .nav-tabs>li {font-size:15px;}

@media(min-width: 769px){
    .font-helvetica-14 .sidebar-main {_width:235px;}
    .font-helvetica-14 .nav-tabs>li {font-size:14px;}
}

#suggest {font-size:15px;}
.search-suggest {background-color:#eee; z-index:9999; overflow:hidden;}
.search-suggest a {display:block; margin-top:1px; padding:5px; white-space:nowrap;}
.search-suggest a:hover, .search-suggest a:focus, #suggest a:active {background-color:#ccc; color:#000; text-decoration:none;}
#suggest.search-suggest {max-height:600px; width:500px; position:absolute; top:50px;}

.mb-1em {margin-bottom:1em;}

form.panel-search {margin-bottom:16px;}
/** ? **/

.col { position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px;}

@media (max-width: 1199px) {
    .col-1 {width:100%;}
    .col-2 {width:100%;}
}
@media (max-width: 799px) {
    .col-1-1 {width:100%;}
    .col-1-2 {width:100%;}
}
@media (min-width: 800px) and (max-width: 1199px) {
    .col-1-1 {width:50%; float:left;}
    .col-1-2 {width:50%; float:left;}
}
@media (min-width: 1200px) and (max-width: 1399px) {
    .col-1 {width: 41.66666667%; left:58.33333333%; float:left;}
    .col-2 {width: 58.33333333%; right:41.66666667%; float:left;}
    .col-1-1 {width:100%;}
    .col-1-2 {width:100%;}
}
@media (min-width: 1400px) and (max-width: 1599px) {
    .col-1 {width: 33.33333333%; left:66.66666667%; float:left;}
    .col-2 {width: 66.66666667%; right:33.33333333%; float:left;}
    .col-1-1 {width:100%;}
    .col-1-2 {width:100%;}
}
@media (min-width: 1600px) {
    .col-1 {width:50%; left:50%; float:left;}
    .col-2 {width:50%; right:50%; float:left;}
    .col-1-1 {width:50%; float:left;}
    .col-1-2 {width:50%; float:left;}
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

.label-status {background-color:#999; color:#fff;}
.label-status-on {background-color:green;}
.label-status-off {background-color:#c00;}
.label-status-draft {background-color:#999;}
.label-status-deleted {background-color:#000; text-decoration:line-through;}

.label.b2b {background-color:#c60;}
.label.b2c {background-color:#999;}
.label.priority {background-color:#660;}
.label.vespa {background-color:purple;}
.label.status.open {background-color:#369;}
.label.status.closed {background-color:#333;}
.label.status.onhold {background-color:#666;}
.label.status.pending {background-color:#666;}
.label.status.lost {background-color:#c66;}
.label.status.won {background-color:#393;}

@media print {
    .sidebar, .breadcrumb-line {display:none;}
    .page-title {padding:0;}
    body {font-size:12px; margin:0; padding:0;}
    h1 {font-size:25px; font-weight:normal;}
    h2 {font-size:20px;}
    h3 {font-size:18px;}
    h4 {font-size:16px;}
    h5 {font-size:15px;}
    h6 {font-size:13px; color:#666;}
    #wrap {padding:0;}
    nav.navbar {display:none;}
    #sb {display:none;}
    #main {margin:0;}
    #ft {display:none;}
    .page-header .btn-toolbar.pull-right {display:none;}
    a[href]:after{content:""}
    .col-lg-8 {width:64%;}
    .col-lg-4 {width:32%;}
    .table-responsive {overflow:auto; overflow-x:auto;}
    a[href]:after{content:""}
    abbr[title]:after{content:""}
}
TXT;

$this->registerCss($css);

if (1 && (!in_array(USER_ID, [1111, 8162, 34596]) || isset($_GET['lotus']))) {
    $css = '
.font-roboto-15 .navigation li a, .navigation li a {color:#cDa9bB;}
.font-roboto-15 .navbar-inverse .navbar-nav>li>a, .navbar-inverse .navbar-nav>li>a, .font-roboto-15 .navbar-inverse .navbar-text, .navbar-inverse .navbar-text {color:#eDc9dB;}

.sidebar-main {background-color:#601149}
.__sidebar .navigation>li {border-top:1px solid rgba(189,73,155,.1);}
.navigation>li.active>a, .navigation>li.active>a:focus, .navigation>li.active>a:hover {background-color:#BD499B}
.navbar-inverse {background-color:#BD499B; border-color:#BD499B;}

#q {background-color: #D36FB5; border-color: #D36FB5; color: #fff;}
#qs:hover #q {background-color:#e37Fc5; cursor:pointer;}
#qs:hover #q:focus, #q:focus {background-color:#fff; color:#444; cursor:auto;}

#qi {color:#fff;}
#qi.icon-close {color:#ccc;}

#q::-webkit-input-placeholder {color:#fff;}
#q:-moz-placeholder {color:#fff;}
#q::-moz-placeholder {color:#fff;}
#q:-ms-input-placeholder {color:#fff;}

#q:focus::-webkit-input-placeholder {color:#ccc;}
#q:focus:-moz-placeholder {color:#ccc;}
#q:focus::-moz-placeholder {color:#ccc;}
#q:focus:-ms-input-placeholder {color:#ccc;}

@media (min-width: 769px) {
    .sidebar-xs .sidebar-main .navigation-main>li>a>span {background-color:#BD499B; border-color:#BD499B}
    .sidebar-xs .sidebar-main .navigation-main>li>ul {background-color:#601149} 
}
    ';
    $this->registerCss($css);
}

// Kim Ngoc no fonts
if (USER_ID == 25457) {
$css = <<<'TXT'
.content {font-size:14px; font-family:Roboto, sans-serif;}
TXT;
//$this->registerCss($css);
}
