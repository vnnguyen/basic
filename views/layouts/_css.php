<?php

$css = <<<'TXT'
body {overflow-y:scroll;}
@media(min-width: 769px){
    .sidebar-main {width:235px;}
}

.navbar-header {min-width:auto;}
#navbar-mobile .navbar-nav {margin-left:0;}
.navbar-brand {padding:0 20px;}
.navbar-brand>img {margin-top:8px; height:30px;}
.cke_chrome {box-shadow:none!important;}

/** ?? **/
body.size-14 .dropdown-menu>li>a {padding:4px 15px;}
body.size-14 .content {font-size:14px; font-family:Helvetica, Arial, sans-serif;}
body.size-14 .table-condensed th, body.size-14 .table-condensed td {padding:8px!important;}
body.size-14 .nav-tabs>li {font-size:14px;}

/* table */
.table-narrow tr>th, .table-narrow tr>td {padding:8px!important;}
.table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
.table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}


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
    html, body { height: auto; }
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


/* FONT FAMILY */
body.font-helvetica {font-family:Helvetica,Arial,sans-serif;}
body.font-roboto {font-family:Roboto,Helvetica,Arial,sans-serif;}

/* FONT SIZE */
body.size-15 {font-size:15px;}
body.size-15 blockquote {font-size:15px;}
body.size-15 .navbar, body.size-15 .dropdown-menu, body.size-15 .form-control {font-size:14px;}
body.size-15 .content {font-size:15px;}
body.size-15 .sidebar {font-size:14px;}
body.size-15 .navigation li a {_color:#869298;}
body.size-15 .navigation li>a:hover, body.size-15 .navigation li.active>a {__color:#fff;}
body.size-15 .navbar-inverse .navbar-nav>li>a, body.size-15 .navbar-inverse .navbar-text {__color:#a6b2b8;}
body.size-15 .navbar-inverse .navbar-nav>li>a:hover, body.size-15 .navbar-inverse .navbar-text:hover {__color:#fff;}
body.size-15 .navbar-inverse .navbar-nav>li.active>a, body.size-15 .navbar-inverse .navbar-text.active {__color:#fff;}
body.size-15 .nav-tabs>li {font-size:15px;}

/* THEME COLOR */
body.lotus .navbar-inverse {background-color:#BD499B; border-color:#BD499B;}
body.lotus .__sidebar-main {background-color:#601149}
body.lotus .__sidebar .navigation>li {border-top:1px solid rgba(189,73,155,.1);}
body.lotus .__navigation>li.active>a, body.lotus .__navigation>li.active>a:focus, body.lotus .__navigation>li.active>a:hover {background-color:#BD499B}

body.l-ame-du-voyage .navbar-inverse {background-color:#e65925; border-color:#e65925;}
body.l-ame-du-voyage .sidebar-main {background-color:#fff; border-right:1px solid #ddd; color:#333;}
body.l-ame-du-voyage .navigation li a, body.l-ame-du-voyage .navigation li a {color:#333;}

body.l-ame-du-voyage .sidebar:not(.sidebar-default) .media .media-annotation, body.l-ame-du-voyage .sidebar:not(.sidebar-default) .media .text-muted {color:#999;}
body.l-ame-du-voyage .sidebar-main .navigation li.disabled>a, body.l-ame-du-voyage .sidebar-main .navigation li.disabled>a:focus, body.l-ame-du-voyage .sidebar-main .navigation li.disabled>a:hover, body.l-ame-du-voyage .sidebar-main .navigation>li ul {background-color:#fff;}
body.l-ame-du-voyage .sidebar-main .navigation li.active>a, body.l-ame-du-voyage .sidebar-main .navigation li.active>a:focus, body.l-ame-du-voyage .sidebar-main .navigation li.active>a:hover {background-color:#f5f5f5; color:#333;}
body.l-ame-du-voyage .sidebar-main .navigation>li.active>a, body.l-ame-du-voyage .sidebar-main .navigation>li.active>a:focus, body.l-ame-du-voyage .sidebar-main .navigation>li.active>a:hover {background-color:#e65925; color:#fff;}

body.red-theme .navbar-inverse {background-color:#e53935; border-color:#e53935;}
body.red-theme .sidebar-main {background-color:#fff; border-right:1px solid #ddd; color:#333;}
body.red-theme .navigation li a, body.red-theme .navigation li a {color:#333;}

body.red-theme .sidebar:not(.sidebar-default) .media .media-annotation, body.red-theme .sidebar:not(.sidebar-default) .media .text-muted {color:#999;}
body.red-theme .sidebar-main .navigation li.disabled>a, body.red-theme .sidebar-main .navigation li.disabled>a:focus, body.red-theme .sidebar-main .navigation li.disabled>a:hover, body.red-theme .sidebar-main .navigation>li ul {background-color:#fff;}
body.red-theme .sidebar-main .navigation li.active>a, body.red-theme .sidebar-main .navigation li.active>a:focus, body.red-theme .sidebar-main .navigation li.active>a:hover {background-color:#f5f5f5; color:#333;}
body.red-theme .sidebar-main .navigation>li.active>a, body.red-theme .sidebar-main .navigation>li.active>a:focus, body.red-theme .sidebar-main .navigation>li.active>a:hover {background-color:#e53935; color:#fff;}


body.green-theme .navbar-inverse {background-color:#00897b; border-color:#00897b;}
body.green-theme .sidebar-main {background-color:#fff; border-right:1px solid #ddd; color:#333;}
body.green-theme .navigation li a, body.green-theme .navigation li a {color:#333;}

body.green-theme .sidebar:not(.sidebar-default) .media .media-annotation, body.green-theme .sidebar:not(.sidebar-default) .media .text-muted {color:#999;}
body.green-theme .sidebar-main .navigation li.disabled>a, body.green-theme .sidebar-main .navigation li.disabled>a:focus, body.green-theme .sidebar-main .navigation li.disabled>a:hover, body.green-theme .sidebar-main .navigation>li ul {background-color:#fff;}
body.green-theme .sidebar-main .navigation li.active>a, body.green-theme .sidebar-main .navigation li.active>a:focus, body.green-theme .sidebar-main .navigation li.active>a:hover {background-color:#f5f5f5; color:#333;}
body.green-theme .sidebar-main .navigation>li.active>a, body.green-theme .sidebar-main .navigation>li.active>a:focus, body.green-theme .sidebar-main .navigation>li.active>a:hover {background-color:#00897b; color:#fff;}


body.blue-theme .navbar-inverse {background-color:#2196f3; border-color:#2196f3;}
body.blue-theme .sidebar-main {background-color:#fff; border-right:1px solid #ddd; color:#333;}
body.blue-theme .navigation li a, body.blue-theme .navigation li a {color:#333;}

body.blue-theme .sidebar:not(.sidebar-default) .media .media-annotation, body.blue-theme .sidebar:not(.sidebar-default) .media .text-muted {color:#999;}
body.blue-theme .sidebar-main .navigation li.disabled>a, body.blue-theme .sidebar-main .navigation li.disabled>a:focus, body.blue-theme .sidebar-main .navigation li.disabled>a:hover, body.blue-theme .sidebar-main .navigation>li ul {background-color:#fff;}
body.blue-theme .sidebar-main .navigation li.active>a, body.blue-theme .sidebar-main .navigation li.active>a:focus, body.blue-theme .sidebar-main .navigation li.active>a:hover {background-color:#f5f5f5; color:#333;}
body.blue-theme .sidebar-main .navigation>li.active>a, body.blue-theme .sidebar-main .navigation>li.active>a:focus, body.blue-theme .sidebar-main .navigation>li.active>a:hover {background-color:#2196f3; color:#fff;}


body.purple-theme .navbar-inverse {background-color:#62417f; border-color:#62417f;}
body.purple-theme .sidebar-main {background-color:#fff; border-right:1px solid #ddd; color:#333;}
body.purple-theme .navigation li a, body.purple-theme .navigation li a {color:#333;}

body.purple-theme .sidebar:not(.sidebar-default) .media .media-annotation, body.purple-theme .sidebar:not(.sidebar-default) .media .text-muted {color:#999;}
body.purple-theme .sidebar-main .navigation li.disabled>a, body.purple-theme .sidebar-main .navigation li.disabled>a:focus, body.purple-theme .sidebar-main .navigation li.disabled>a:hover, body.purple-theme .sidebar-main .navigation>li ul {background-color:#fff;}
body.purple-theme .sidebar-main .navigation li.active>a, body.purple-theme .sidebar-main .navigation li.active>a:focus, body.purple-theme .sidebar-main .navigation li.active>a:hover {background-color:#f5f5f5; color:#333;}
body.purple-theme .sidebar-main .navigation>li.active>a, body.purple-theme .sidebar-main .navigation>li.active>a:focus, body.purple-theme .sidebar-main .navigation>li.active>a:hover {background-color:#62417f; color:#fff;}

/* AIR DP */
.datepicker>div{display:block;}
.datepicker--button {display:inline-block; width:50%; margin:auto; text-align:center; line-height:32px;}
.gd1 {background-color:#fffff0;}
.dp-note {background:blue; width: 4px; height: 4px; border-radius: 50%; left: 50%; bottom: 1px; -webkit-transform: translateX(-50%); transform: translateX(-50%); position:absolute;}
.-selected- .dp-note {bottom: 2px; background: #fff; opacity: .5;}
.-current- {background-color:#f0fff0;}

@media (min-width: 769px) {
    body.lotus.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    body.lotus.sidebar-xs .sidebar-main .navigation>li>a>span {background-color:#BD499B; border-color:#BD499B; color:#fff;}

    body.l-ame-du-voyage.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    body.l-ame-du-voyage.sidebar-xs .sidebar-main .navigation>li>a>span {background-color:#e65925; border-color:#e65925; color:#fff;}

    body.red-theme.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    body.red-theme.sidebar-xs .sidebar-main .navigation>li>a>span {background-color:#e53935; border-color:#e53935; color:#fff;}

    body.green-theme.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    body.green-theme.sidebar-xs .sidebar-main .navigation>li>a>span {background-color:#00897b; border-color:#00897b; color:#fff;}

    body.blue-theme.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    body.blue-theme.sidebar-xs .sidebar-main .navigation>li>a>span {background-color:#2196f3; border-color:#2196f3; color:#fff;}

    body.purple-theme.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    body.purple-theme.sidebar-xs .sidebar-main .navigation>li>a>span {background-color:#62417f; border-color:#62417f; color:#fff;}

    body.size-14 .nav-tabs>li {font-size:14px;}
}
TXT;

$this->registerCss($css);


