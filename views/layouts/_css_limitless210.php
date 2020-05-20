<?php

$css = <<<'TXT'
    /*
    .navbar-brand {min-width:0!important; padding-top:10px; padding-bottom: 10px; margin-right:0;}
    .navbar-brand img {height:28px!important;}
    .breadcrumb-line {border:0!important;}
    .breadcrumb-line-light {background-color: #fff}
    .page-title {padding-top:0; padding-bottom:24px;}
    */
    .fs15 .content {font-size:15px}
    .fs15 .content .form-control {font-size:15px;}
    .fs15 .content select.form-control {height:40px;}
    .content .form-inline.mb-2 .form-control, .content .form-inline.mb-2 button {margin-right:2px;}
    .sidebar {font-size:14px;}
    
    .has-error .help-block {color:red;}

    /*
    .ims-page-header {background-color:#fff; padding:0; border-bottom:1px solid #ddd;}

    .ims-page-title, .ims-page-breadcrumb {padding:0 1rem;}
    .ims-page-breadcrumb {float:left;}
    .ims-page-actions {padding:.625rem 1rem; float:right;}
    .ims-page-title {clear:both;}
    .fs15 .ims-page-tabs {font-size:15px;}
    */

    .fancybox-inner, .fancybox-outer, .fancybox-stage {
        bottom: 0;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
    }

    @media screen and (max-width:1300) {
        .fs15 .content {font-size:15px}
    .fs15 .content .form-control {font-size:15px;}
    }

.has-success .form-control {border-color:green}
.has-error .form-control {border-color:red}

@media (max-width: 768px) {
    .-content {padding:0}
}

@media (min-width: 99769px) and (max-width: 991599px) {
  /* .sidebar-xs  */.sidebar-main {
    width: 3.5rem!important; }
    /* .sidebar-xs  */.sidebar-main .sidebar-content::-webkit-scrollbar {
      width: 0 !important; }
    /* .sidebar-xs  */.sidebar-main .card:not(.card-sidebar-mobile),
    /* .sidebar-xs  */.sidebar-main .card-title {
      display: none; }
    /* .sidebar-xs  */.sidebar-main .card-header h6 + .header-elements {
      padding-top: 0.22117rem;
      padding-bottom: 0.22117rem; }
    /* .sidebar-xs  */.sidebar-main .card-header h5 + .header-elements {
      padding-top: 0.31733rem;
      padding-bottom: 0.31733rem; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item {
      position: relative;
      margin: 0; }
      /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item > .nav-link {
        -ms-flex-pack: center;
            justify-content: center;
        padding-left: 0;
        padding-right: 0; }
        /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item > .nav-link > i {
          position: static;
          margin-left: 0;
          margin-right: 0;
          display: block;
          padding-bottom: 1px; }
        /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item > .nav-link > span {
          display: none; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
      display: block !important; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub {
      position: absolute;
      top: -0.5rem;
      right: -16.875rem;
      width: 16.875rem;
      display: none;
      z-index: 1000;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
      border-top-right-radius: 0.1875rem;
      border-bottom-right-radius: 0.1875rem; }
      /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub[data-submenu-title]:before {
        content: attr(data-submenu-title);
        display: block;
        padding: 0.75rem 1.25rem;
        padding-bottom: 0;
        margin-top: 0.5rem;
        opacity: 0.5; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-submenu > .nav-link:after {
      content: none; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar .nav-group-sub .nav-link {
      padding-left: 1.25rem; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar .nav-group-sub .nav-group-sub .nav-link {
      padding-left: 2.25rem; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar .nav-group-sub .nav-group-sub .nav-group-sub .nav-link {
      padding-left: 3.5rem; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-header {
      padding: 0;
      text-align: center; }
      /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-header > i {
        display: block;
        top: 0;
        padding: 0.75rem 1.25rem;
        margin-top: 0.12502rem;
        margin-bottom: 0.12502rem; }
      /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-header > div {
        display: none; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item-open > .nav-group-sub {
      display: none !important; }
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item:hover > .nav-link.disabled + .nav-group-sub,
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item:hover > .nav-link.disabled > span, /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item:focus > .nav-link.disabled + .nav-group-sub,
    /* .sidebar-xs  */.sidebar-main .nav-sidebar > .nav-item:focus > .nav-link.disabled > span {
      display: none !important; }
    /* .sidebar-xs  */.sidebar-main .sidebar-user .card-body {
      padding-left: 0;
      padding-right: 0; }
    /* .sidebar-xs  */.sidebar-main .sidebar-user .media {
      -ms-flex-pack: center;
          justify-content: center; }
      /* .sidebar-xs  */.sidebar-main .sidebar-user .media > div:not(:first-child) {
        display: none !important; }
      /* .sidebar-xs  */.sidebar-main .sidebar-user .media > div:first-child {
        margin: 0 !important; }
    /* .sidebar-xs  */.sidebar-main .nav-item-submenu-reversed .nav-group-sub {
      top: auto !important;
      bottom: 0; }
    /* .sidebar-xs  */.sidebar-main.sidebar-dark .nav-sidebar > .nav-item:not(.nav-item-open):hover > .nav-link:not(.active):not(.disabled) {
      color: #fff;
      background-color: rgba(0, 0, 0, 0.15); }
    /* .sidebar-xs  */.sidebar-main.sidebar-dark .nav-sidebar > .nav-item-submenu > .nav-group-sub {
      background-color: #304047;
      border-left: 1px solid rgba(255, 255, 255, 0.1); }
    /* .sidebar-xs  */.sidebar-main.sidebar-light .nav-sidebar > .nav-item:not(.nav-item-open):hover > .nav-link:not(.active):not(.disabled) {
      color: #333;
      background-color: #f5f5f5; }
    /* .sidebar-xs  */.sidebar-main.sidebar-light .nav-sidebar > .nav-item-submenu > .nav-group-sub {
      background-color: #fcfcfc;
      border: 1px solid rgba(0, 0, 0, 0.125); }
  /* .sidebar-xs  */.sidebar-main.sidebar-fixed {
    z-index: 1029; }
    /* .sidebar-xs  */.sidebar-main.sidebar-fixed .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, /* .sidebar-xs  */.sidebar-main.sidebar-fixed .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
      position: fixed;
      left: 3.5rem;
      top: 3.12503rem;
      bottom: 0;
      width: 16.875rem;
      overflow-y: auto;
      border-radius: 0; }
  /* .sidebar-xs  */.navbar-lg:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, /* .sidebar-xs  */.navbar-lg:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
    top: 3.37503rem; }
  /* .sidebar-xs  */.navbar-sm:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, /* .sidebar-xs  */.navbar-sm:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
    top: 2.87503rem; }
}

.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto;}
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

body {overflow-y:scroll;}
@media(min-width: 769px){
    .sidebar-main {width:235px;}
}
.navbar-header {min-width:auto;}
#navbar-mobile .navbar-nav {margin-left:0;}
.-navbar-brand {padding:0 20px;}
.-navbar-brand>img {margin-top:8px; height:30px;}
.cke_chrome {box-shadow:none!important;}

/* table */
.table-narrow tr>th, .table-narrow tr>td {padding:8px!important;}
.table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
.table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}

/* .col1, .col2 { position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px;} */
@media (max-width: 1199px) {
    .col1 {width:100%;}
    .col2 {width:100%;}
}
@media (max-width: 799px) {
    .col11 {width:100%;}
    .col12 {width:100%;}
}
@media (min-width: 800px) and (max-width: 1199px) {
    .col11 {width:50%; float:left;}
    .col12 {width:50%; float:left;}
}
@media (min-width: 1200px) and (max-width: 1399px) {
    .col1 {width: 41.66666667%; left:58.33333333%; float:left;}
    .col2 {width: 58.33333333%; right:41.66666667%; float:left;}
    .col11 {width:100%;}
    .col12 {width:100%;}
}
@media (min-width: 1400px) and (max-width: 1599px) {
    .col1 {width: 33.33333333%; left:66.66666667%; float:left;}
    .col2 {width: 66.66666667%; right:33.33333333%; float:left;}
    .col11 {width:100%;}
    .col12 {width:100%;}
}
@media (min-width: 1600px) {
    .col1 {width:50%; left:50%; float:left;}
    .col2 {width:50%; right:50%; float:left;}
    .col11 {width:50%; float:left;}
    .col12 {width:50%; float:left;}
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

// .content form {width:100%}

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

.dropdown-grid {
  position: absolute;
  top: 100%;
  float: left;
  min-width: 300px;
  margin: 2px 0 0;
  text-align: left;
  list-style: none;
  background-color: #fff;
  -webkit-background-clip: padding-box;
          background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
}

.dropdown-menu,
.dropdown-grid {
  color: #4d5259;
  border-radius: 2px;
  font-size: 13px;
  border-color: #f1f2f3;
  padding: 0;
  -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.06);
          box-shadow: 0 0 4px rgba(0, 0, 0, 0.06);
  z-index: 992;
  /*
  &.open-top-right {
    transform-origin: 97% top 0;
    transform: scale(0,0);
  }

  &.open-top-left {
    transform-origin: 3% top 0;
    transform: scale(0,0);
  }

  &.open-top-center {
    left: 50%;
    transform: scale(0,0) translateX(-50%);
  }


  &.open-bottom-right {
    transform-origin: 97% bottom 0;
    transform: scale(0,0);
  }

  &.open-bottom-left {
    transform-origin: 3% bottom 0;
    transform: scale(0,0);
  }

  &.open-bottom-center {
    left: 50%;
    transform-origin: center bottom 0;
    transform: scale(1,0) translateX(-50%);
  }
  */
}

.dropdown-menu a,
.dropdown-grid a {
  color: #4d5259;
}

.show > {
  /*
  .dropdown-menu,
  .dropdown-grid {
    transform: scale(1,1) !important;
  }

  .open-top-center,
  .open-bottom-center {
    transform: scale(1,1) translateX(-50%) !important;
  }
  */
}

.show > .dropdown-grid {
  display: -webkit-box;
  display: flex;
}

.-dropdown-item {
  color: #60666f;
  font-weight: 300;
  padding: 4px 12px;
  width: auto;
  margin: 4px;
  -webkit-transition: 0.15s linear;
  transition: 0.15s linear;
}

.dropdown-item:hover, .dropdown-item:focus {
  background-color: #f9fafb;
}

.dropdown-item.active, .dropdown-item:active {
  color: #4d5259;
  background-color: #f5f6f7;
}

.dropdown-item.show, .dropdown-item.show:focus, .dropdown-item.show:hover {
  background-color: #f9fafb;
  color: #4d5259;
}

.dropdown-item.disabled {
  opacity: .5;
  cursor: not-allowed;
}

.dropdown-item.disabled:hover {
  background-color: transparent;
}

.dropdown-item.flexbox,
.dropdown-item .flexbox {
  -webkit-box-align: baseline;
          align-items: baseline;
}

.dropdown-item i {
  margin-right: 0.5rem;
}

.dropdown-item .icon {
  vertical-align: baseline;
  margin-right: 6px;
  font-size: 0.875rem;
}

.dropdown-grid {
  display: none;
  flex-wrap: wrap;
  padding: 8px;
}

.dropdown-grid .dropdown-item {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-pack: center;
          justify-content: center;
  flex-basis: 33.333333%;
  overflow: hidden;
  text-align: center;
  padding: 12px;
  margin: 0;
  min-height: 90px;
  border: 1px solid transparent;
}

.dropdown-grid .dropdown-item:hover {
  border-color: #f1f2f3;
}

.dropdown-grid .icon {
  font-size: 24px;
}

.dropdown-grid .title {
  margin: 6px -12px 0;
}

.dropdown-grid .i8-icon {
  margin: 0 12px -4px;
}

.dropdown-grid .i8-icon + .title {
  margin-top: 0;
}

.dropdown-grid.cols-2 {
  min-width: 200px;
}

.dropdown-grid.cols-2 .dropdown-item {
  flex-basis: 50%;
}

.dropdown-grid.cols-4 {
  min-width: 400px;
}

.dropdown-grid.cols-4 .dropdown-item {
  flex-basis: 25%;
}

.dropdown-grid-right {
  right: 0;
  left: auto;
}


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


TXT;

$this->registerCss($css);


