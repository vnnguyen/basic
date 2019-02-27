<?php

$css = <<<'CSS'
.center-v {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-pack: center;
          justify-content: center;
  height: 100%;
}

.center-h {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: center;
          justify-content: center;
}

.center-vh {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: center;
          justify-content: center;
  -webkit-box-align: center;
          align-items: center;
  height: 100%;
}

.app-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.8);
  cursor: pointer;
}

.app-backdrop.backdrop-sidebar {
  z-index: 996;
}

.app-backdrop.backdrop-sidebar::after {
  content: "\e646";
  font-family: themify;
  font-size: 24px;
  color: #b5b9bf;
  opacity: .7;
  position: absolute;
  top: 20px;
  right: 30px;
}

.app-backdrop.backdrop-topbar-menu {
  z-index: 992;
}

.app-backdrop.backdrop-quickview {
  background-color: transparent;
  z-index: 998;
}


/** QUICK VIEW **/
.quickview {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  background-color: #fff;
  width: 300px;
  max-width: calc( 100% - 50px);
  position: fixed;
  top: 0;
  bottom: 0;
  right: -300px;
  z-index: 999;
  -webkit-transform: translateZ(0);
          transform: translateZ(0);
  -webkit-transition: .3s ease;
  transition: .3s ease;
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-perspective: 1000;
          perspective: 1000;
  will-change: transform;
}

.quickview.quickview-sm {
  width: 220px;
  right: -220px;
}

.quickview.quickview-lg {
  width: 420px;
  right: -420px;
}

.quickview.quickview-xl {
  width: 640px;
  right: -640px;
}

.quickview.quickview-xxl {
  width: 860px;
  right: -860px;
}

@media (max-width: 767px) {
  .quickview[data-fullscreen-on-mobile] {
    max-width: 100%;
    width: 100%;
    right: -100%;
  }
}

.quickview.reveal {
  right: 0;
  -webkit-box-shadow: 5px 0px 13px 3px rgba(0, 0, 0, 0.1);
          box-shadow: 5px 0px 13px 3px rgba(0, 0, 0, 0.1);
}

.quickview.backdrop-light + .backdrop-quickview {
  background-color: rgba(255, 255, 255, 0.8);
}

.quickview.backdrop-dark + .backdrop-quickview {
  background-color: rgba(0, 0, 0, 0.5);
}

.quickview > .nav-tabs {
  margin-bottom: 0;
  height: 64px;
}

.quickview-body {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
}

.quickview-block {
  padding: 20px;
  width: 100%;
}

.quickview-header {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  padding: 0 20px;
  min-height: 50px !important;
  border-bottom: 1px solid #ebebeb;
}

.quickview-header .close {
  margin-left: 16px;
  font-size: 16px;
}

.quickview-header.nav-tabs {
  padding: 0;
}

.quickview-header .nav-link {
  height: 64px;
  line-height: 58px;
  padding-top: 6px;
  padding-bottom: 0;
}

.quickview-header-lg {
  height: 80px;
}

.quickview-header-lg .nav-link {
  height: 64px;
  line-height: 74px;
}

.quickview-title {
  margin-bottom: 0;
  letter-spacing: .5px;
}

.quickview-footer {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  padding: 0 16px;
  min-height: 64px;
  background-color: #fcfdfe;
  border-top: 1px solid #f1f2f3;
}

.quickview-footer .row {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
}

.quickview-footer > *:not(.row) {
  margin: 0 4px;
}

.quickview-footer a:not(.btn) {
  display: inline-block;
  padding: 0.75rem 0.5rem;
  color: #8b95a5;
}

.quickview-footer a:not(.btn):hover {
  color: #313944;
}



CSS;

$this->registerCss($css);