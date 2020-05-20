<?php

$css = <<<'TXT'
@charset "UTF-8";
body {
  font-family: Roboto, sans-serif;
  -color: #4d5259;
  -font-weight: 300;
  -font-size: 13px;
  line-height: 24px;
}

button, input, optgroup, select, textarea {
  font-family: Roboto, sans-serif;
  font-weight: 300;
}

canvas:focus {
  outline: none;
}

::-moz-selection {
  -background: #4ed2c5;
  -color: #fff;
}

::selection {
  -background: #4ed2c5;
  -color: #fff;
}

::-moz-selection {
  -background: #4ed2c5;
  -color: #fff;
}

a {
  -color: #33cabb;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

a:hover, a:focus {
  -color: #4d5259;
  text-decoration: none;
  outline: none;
}

a.media {
  -color: #4d5259;
}

a[class*="text-"]:hover, a[class*="text-"]:focus {
  text-decoration: none;
}

a[class*="hover-"] {
  -color: #4d5259;
}

b,
strong {
  font-weight: 500;
}

small,
time,
.small {
  font-family: Roboto, sans-serif;
  font-weight: 400;
  -font-size: 11px;
  color: #8b95a5;
}

small a,
time a,
.small a {
  color: #8b95a5;
  border-bottom: 1px dotted #8b95a5;
}

small a:hover,
time a:hover,
.small a:hover {
  color: #33cabb;
  border-bottom-color: transparent;
}

.lead {
  font-size: 16px;
  line-height: 32px;
}

.semibold {
  font-weight: 400;
}

.dropcap:first-child:first-letter {
  float: left;
  color: #903;
  font-size: 75px;
  line-height: 60px;
  padding-top: 4px;
  padding-right: 8px;
  padding-left: 3px;
}

h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6 {
  font-family: Roboto, sans-serif;
  -font-weight: 300;
  color: #313944;
  line-height: 1.5;
  letter-spacing: .5px;
}

h1 b,
h1 strong, h2 b,
h2 strong, h3 b,
h3 strong, h4 b,
h4 strong, h5 b,
h5 strong, h6 b,
h6 strong,
.h1 b,
.h1 strong, .h2 b,
.h2 strong, .h3 b,
.h3 strong, .h4 b,
.h4 strong, .h5 b,
.h5 strong, .h6 b,
.h6 strong {
  -font-weight: 400;
}

h1 small, h2 small, h3 small, h4 small, h5 small, h6 small,
.h1 small, .h2 small, .h3 small, .h4 small, .h5 small, .h6 small {
  font-size: 65%;
}

h1 a,
h1 a[class*="hover-"], h2 a,
h2 a[class*="hover-"], h3 a,
h3 a[class*="hover-"], h4 a,
h4 a[class*="hover-"], h5 a,
h5 a[class*="hover-"], h6 a,
h6 a[class*="hover-"],
.h1 a,
.h1 a[class*="hover-"], .h2 a,
.h2 a[class*="hover-"], .h3 a,
.h3 a[class*="hover-"], .h4 a,
.h4 a[class*="hover-"], .h5 a,
.h5 a[class*="hover-"], .h6 a,
.h6 a[class*="hover-"] {
  color: #313944;
}

h1, .h1 {
  font-size: 33px;
}

h2, .h2 {
  font-size: 28px;
}

h3, .h3 {
  font-size: 23px;
}

h4, .h4 {
  font-size: 19px;
}

h5, .h5 {
  font-size: 16px;
  font-weight: 400;
}

h6, .h6 {
  font-size: 14px;
  font-weight: 400;
}

.font-alt {
  font-family: Roboto, sans-serif;
}

.sidetitle {
  font-style: italic;
  margin-left: 0.5rem;
}

.sidetitle::before {
  content: '\2014 \00A0';
}

.subtitle {
  display: block;
  margin-top: 8px;
}

.list-iconic {
  list-style: none;
  padding-left: 1.5rem;
}

.list-iconic .icon {
  padding-right: 0.5rem;
  font-size: 0.75em;
}

.blockquote {
  font-style: italic;
  margin-left: 2rem;
  margin-right: 2rem;
  margin-top: 1rem;
  color: #4d5259;
}

.blockquote p {
  font-size: 1.125rem;
  line-height: 1.875rem;
}

.blockquote a {
  color: #4d5259;
}

.blockquote footer {
  color: #8b95a5;
  font-size: 0.875rem;
}

.blockquote footer::before {
  content: '\2014 \00A0';
}

.blockquote-inverse,
.blockquote-inverse footer {
  color: #fff;
}

dt {
  font-weight: 400;
}

time {
  font-size: 12px;
  color: #8b95a5;
}

.dl-inline dt,
.dl-inline dd {
  display: inline-block;
}

.dl-inline dd {
  margin-bottom: 0;
}

@media (max-width: 991px) {
  html {
    font-size: 16px;
  }
  h1, .h1 {
    font-size: 31px;
  }
  h2, .h2 {
    font-size: 26px;
  }
  h3, .h3 {
    font-size: 22px;
  }
  h4, .h4 {
    font-size: 18px;
  }
  h5, .h5 {
    font-size: 15px;
  }
  h6, .h6 {
    font-size: 13px;
  }
  .blockquote {
    margin-left: 2rem;
    margin-right: 2rem;
  }
}

@media (max-width: 767px) {
  html {
    font-size: 15px;
  }
  h1, .h1 {
    font-size: 24px;
  }
  h2, .h2 {
    font-size: 22px;
  }
  h3, .h3 {
    font-size: 20px;
  }
  h4, .h4 {
    font-size: 16px;
  }
  .lead {
    font-size: 15px;
  }
  .blockquote {
    margin-left: 0;
    margin-right: 0;
  }
}

hr {
  border-top-color: rgba(77, 82, 89, 0.07);
  margin: 2rem auto;
}

.divider-dash {
  opacity: .7;
  margin: 0 4px;
  vertical-align: middle;
  color: #8b95a5;
}

.divider-dash::before {
  content: '\2014 \00A0';
}

.divider-dot {
  display: inline-block;
  width: 3px;
  height: 3px;
  border-radius: 50%;
  margin: 0 4px;
  vertical-align: middle;
  opacity: .5;
  background-color: #8b95a5;
}

.divider-line {
  display: -webkit-inline-box;
  display: inline-flex;
  height: 20px;
  width: 1px;
  margin: 0 4px;
  background-color: rgba(77, 82, 89, 0.07);
}

.divider {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-flex: 0;
          flex: 0 1 0%;
  color: #8b95a5;
  font-size: 11px;
  letter-spacing: .5px;
  margin: 2rem auto;
  width: 100%;
}

.divider::before, .divider::after {
  content: '';
  -webkit-box-flex: 1;
          flex-grow: 1;
  border-top: 1px solid #ebebeb;
}

.divider::before {
  margin-right: 16px;
}

.divider::after {
  margin-left: 16px;
}

.divider a {
  color: #8b95a5;
}

.divider-vertical {
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  margin-top: 0;
  margin-bottom: 0;
  padding: 0 2rem;
}

.divider-vertical::before, .divider-vertical::after {
  border-top: none;
  border-right: 1px solid #ebebeb;
  margin: 0;
}

.divider-vertical::before {
  margin-bottom: 16px;
}

.divider-vertical::after {
  margin-top: 16px;
}

.hr-sm,
.divider-sm {
  margin: 1rem auto;
}

.hr-lg,
.divider-lg {
  margin: 3rem auto;
}

.divider-vertical.divider-sm {
  padding: 0 1rem;
}

.divider-vertical.divider-lg {
  padding: 0 3rem;
}

.article a {
  color: #48b0f7;
}

.article .lead {
  font-size: 17px;
  color: #616a78;
  opacity: .8;
}

.article p {
  font-size: 15px;
  line-height: 28px;
  letter-spacing: 0.1px;
}

.article b, .article strong {
  font-weight: 400;
}

.article blockquote {
  margin: 40px 0;
}

.article blockquote p {
  font-size: 18px;
}

.container-article {
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
}

@media (max-width: 767px) {
  .article .lead {
    font-size: 15px;
  }
  .article p {
    font-size: 13px;
    line-height: 24px;
  }
  .article blockquote p {
    font-size: 15px;
  }
}

pre {
  background-color: #f9fafb;
  border-left: 5px solid #ebebeb;
  padding: 12px;
  border-radius: 3px;
  color: #616a78;
}

code {
  white-space: nowrap;
  color: #ba5e63;
}

.code-bold {
  color: #bd4147;
  font-weight: 600;
  letter-spacing: .5px;
}

kbd {
  background-color: #465161;
}

.pre-scrollable {
  max-height: 360px;
}

.clipboard-copy {
  position: absolute;
  top: 6px;
  right: 13px;
  line-height: 20px;
  opacity: 0;
  z-index: 9;
  -webkit-transition: opacity .5s;
  transition: opacity .5s;
}

.clipboard-copy:hover {
  text-decoration: none;
}

pre:hover .clipboard-copy {
  opacity: 1;
  -webkit-transition: opacity .5s, -webkit-transform 0s;
  transition: opacity .5s, -webkit-transform 0s;
  transition: transform 0s, opacity .5s;
  transition: transform 0s, opacity .5s, -webkit-transform 0s;
}

:not(pre) > code[class*="language-"],
pre[class*="language-"] {
  position: relative;
  background-color: #f9fafb;
  padding-bottom: 12px;
  margin-top: 0;
  margin-bottom: 25px;
  word-wrap: normal;
  border: 1px solid #ebebeb;
}

.line-numbers .line-numbers-rows {
  border-right-color: #ebebeb;
}

div.prism-show-language > div.prism-show-language-label {
  border-radius: 0;
  background-color: transparent;
  color: #8b95a5;
  font-family: Roboto, sans-serif;
  font-size: 11px;
  opacity: 0.5;
  letter-spacing: 1px;
  right: 8px;
  top: 4px;
}

.prism-show-language {
  display: none;
}

.line-highlight {
  background: rgba(255, 255, 0, 0.1);
}

.token.badge {
  font-size: 100%;
  padding: 0;
}

.code-preview {
  border: 1px solid #ebebeb;
  border-bottom: none;
  padding: 20px;
  background-color: #fff;
}

.code-preview > *:last-child {
  margin-bottom: 0;
}

.code-title {
  background-color: #f9fafb;
  border: 1px solid #ebebeb;
  border-bottom: none;
  padding: 15px 20px;
  margin-bottom: 0;
}

.code-title > *:last-child {
  margin-bottom: 0;
}

.code {
  margin-bottom: 30px;
}

.code.code-fold pre {
  display: none;
}

.code pre {
  margin-bottom: 0;
}

.code.show-language .prism-show-language {
  display: block;
}

.code-card .code-title {
  font-family: Roboto, sans-serif;
}

.code-card .code-preview {
  padding: 0;
}

.code-toggler {
  border-top: 1px solid #ebebeb;
  margin-top: -1px;
  text-align: right;
}

.code-toggler .btn {
  border-radius: 0;
  background-color: #f9fafb;
  border-top: none;
  color: #8b95a5;
}

.code-toggler .btn i {
  vertical-align: middle;
}

img {
  max-width: 100%;
}

.img-thumbnail {
  padding: 0.25rem;
  border-color: #f3f3f3;
  border-radius: 3px;
}

.img-shadow {
  -webkit-box-shadow: 0 0 25px 5px rgba(0, 0, 0, 0.2);
          box-shadow: 0 0 25px 5px rgba(0, 0, 0, 0.2);
}

.img-outside-right {
  overflow: hidden;
}

.img-outside-right img {
  width: 100%;
  -webkit-transform: translateX(15%);
          transform: translateX(15%);
}

.avatar {
  position: relative;
  display: inline-block;
  width: 36px;
  height: 36px;
  line-height: 36px;
  text-align: center;
  border-radius: 100%;
  background-color: #f5f6f7;
  color: #8b95a5;
  text-transform: uppercase;
}

.avatar img {
  width: 100%;
  height: 100%;
  border-radius: 100%;
  vertical-align: top;
}

.avatar-bordered {
  border: 4px solid rgba(255, 255, 255, 0.25);
  -webkit-background-clip: padding-box;
  /* for Safari */
  background-clip: padding-box;
  /* for IE9+, Firefox 4+, Opera, Chrome */
}

.avatar-square {
  border-radius: 0;
}

.avatar-sm {
  width: 29px;
  height: 29px;
  line-height: 29px;
  font-size: 0.75rem;
}

.avatar-lg {
  width: 48px;
  height: 48px;
  line-height: 48px;
  font-size: 1.125rem;
}

.avatar-xl {
  width: 64px;
  height: 64px;
  line-height: 64px;
  font-size: 1.25rem;
}

.avatar-xxl {
  width: 96px;
  height: 96px;
  line-height: 96px;
  font-size: 1.375rem;
}

.avatar-xxxl {
  width: 128px;
  height: 128px;
  line-height: 128px;
  font-size: 1.5rem;
}

.avatar-pill {
  width: auto;
  border-radius: 18px;
  color: #616a78;
  text-transform: none;
  letter-spacing: 0;
  background-color: #f9fafb;
  font-size: 0.875rem;
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-align: center;
          align-items: center;
}

.avatar-pill:hover, .avatar-pill:focus {
  color: #616a78;
  background-color: #f5f6f7;
}

.avatar-pill img {
  width: 36px;
}

.avatar-pill span {
  padding-right: 18px;
  padding-left: 8px;
}

.avatar-pill .close {
  padding-left: 0;
  padding-right: 8px;
  font-size: 19px;
  line-height: inherit;
}

.avatar-pill.avatar-sm {
  border-radius: 14.5px;
  font-size: 0.8125rem;
}

.avatar-pill.avatar-sm img {
  width: 29px;
}

.avatar-pill.avatar-sm span {
  padding-right: 14.5px;
}

.avatar-pill.avatar-sm .close {
  padding-right: 6px;
  font-size: 18px;
}

.avatar-pill.avatar-lg {
  border-radius: 24px;
  font-size: 0.9375rem;
}

.avatar-pill.avatar-lg img {
  width: 48px;
}

.avatar-pill.avatar-lg span {
  padding-right: 24px;
}

.avatar-pill.avatar-lg .close {
  padding-right: 10px;
  font-size: 20px;
}

.avatar-pill.avatar-xl {
  border-radius: 32px;
  font-size: 1rem;
}

.avatar-pill.avatar-xl img {
  width: 64px;
}

.avatar-pill.avatar-xl span {
  padding-right: 32px;
  padding-left: 12px;
}

.avatar-pill.avatar-xl .close {
  padding-right: 12px;
  font-size: 22px;
}

.avatar-pill.avatar-xxl {
  border-radius: 48px;
  font-size: 1.125rem;
}

.avatar-pill.avatar-xxl img {
  width: 96px;
}

.avatar-pill.avatar-xxl span {
  padding-right: 48px;
  padding-left: 12px;
}

.avatar-pill.avatar-xxl .close {
  padding-right: 16px;
  font-size: 24px;
}

.avatar-pill.avatar-xxxl {
  border-radius: 64px;
  font-size: 1.25rem;
}

.avatar-pill.avatar-xxxl img {
  width: 128px;
}

.avatar-pill.avatar-xxxl span {
  padding-right: 64px;
  padding-left: 12px;
}

.avatar-pill.avatar-xxxl .close {
  padding-right: 20px;
  font-size: 24px;
}

.avatar[class*='status-']::after {
  content: '';
  position: absolute;
  right: 0px;
  bottom: 0;
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 100%;
  border: 2px solid #fff;
  background-color: #33cabb;
}

.avatar[class*='status-'].avatar-sm::after {
  right: -2px;
  width: 9px;
  height: 9px;
}

.avatar[class*='status-'].avatar-lg::after {
  right: 4px;
}

.avatar[class*='status-'].avatar-xl::after {
  right: 5px;
  width: 11px;
  height: 11px;
}

.avatar[class*='status-'].avatar-xxl::after {
  right: 15px;
  width: 12px;
  height: 12px;
}

.avatar[class*='status-'].avatar-xxxl::after {
  right: 25px;
  width: 16px;
  height: 16px;
}

.avatar.status-success::after {
  background-color: #15c377;
}

.avatar.status-info::after {
  background-color: #48b0f7;
}

.avatar.status-warning::after {
  background-color: #faa64b;
}

.avatar.status-danger::after {
  background-color: #f96868;
}

.avatar.status-dark::after {
  background-color: #465161;
}

.avatar-list {
  display: -webkit-inline-box;
  display: inline-flex;
}

.avatar-list:not(.avatar-list-overlap) {
  margin: -2px;
}

.avatar-list:not(.avatar-list-overlap) > * {
  margin: 2px;
}

@media (max-width: 767px) {
  .avatar-list:not(.avatar-list-overlap) {
    margin: -1px;
  }
  .avatar-list:not(.avatar-list-overlap) > * {
    margin: 1px;
  }
}

.avatar-list-overlap .avatar {
  border: 2px solid #fff;
  -webkit-box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
          box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
  -webkit-transition: .15s linear;
  transition: .15s linear;
}

.avatar-list-overlap .avatar + .avatar {
  margin-left: -16px;
}

.avatar-list-overlap .avatar + .avatar-sm {
  margin-left: -12px;
}

.avatar-list-overlap .avatar + .avatar-lg {
  margin-left: -20px;
}

.avatar-list-overlap .avatar + .avatar-xl {
  margin-left: -26px;
}

.avatar-list-overlap .avatar + .avatar-xxl {
  margin-left: -36px;
}

.avatar-list-overlap .avatar + .avatar-xxxl {
  margin-left: -48px;
}

.avatar-list-overlap .overlap-exclude,
.avatar-list-overlap .avatar:hover + .avatar {
  margin-left: 0;
}

.avatar-add {
  font-family: themify;
  background-color: transparent;
  border: 1px dashed #a8afbb;
  color: #8b95a5;
  font-size: 0.875rem;
}

.avatar-add::before {
  content: "\e61a";
}

.avatar-add:hover {
  background-color: #33cabb;
  border-color: #33cabb;
  color: #fff;
}

.avatar-more span {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  color: rgba(255, 255, 255, 0.8);
  border-radius: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
}

.avatar-more:hover span {
  color: #fff;
  background-color: rgba(0, 0, 0, 0.65);
}

[data-provide~="more-avatar"] ~ .avatar {
  display: none;
}

[class*="img-hov-"] {
  overflow: hidden;
}

[class*="img-hov-"] img {
  -webkit-transition: .45s;
  transition: .45s;
  -webkit-backface-visibility: hidden;
}

.img-hov-fadein {
  -webkit-transition: .45s;
  transition: .45s;
}

.img-hov-fadein:hover {
  opacity: .7;
}

.img-hov-fadeout {
  opacity: .7;
  -webkit-transition: .45s;
  transition: .45s;
}

.img-hov-fadeout:hover {
  opacity: 1;
}

.img-hov-zoomin img:hover {
  -webkit-transform: scale(1.045);
          transform: scale(1.045);
}

.img-hov-zoomout img {
  -webkit-transform: scale(1.045);
          transform: scale(1.045);
}

.img-hov-zoomout img:hover {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.img-hov-stretchin img {
  -webkit-transform-origin: left top 0;
          transform-origin: left top 0;
}

.img-hov-stretchin img:hover {
  -webkit-transform: scale(1.045);
          transform: scale(1.045);
}

.img-hov-stretchout img {
  -webkit-transform-origin: left top 0;
          transform-origin: left top 0;
  -webkit-transform: scale(1.045);
          transform: scale(1.045);
}

.img-hov-stretchout img:hover {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.img-hov-slideleft img {
  -webkit-transform-origin: left center 0;
          transform-origin: left center 0;
  -webkit-transform: scale(1.045) translateX(0);
          transform: scale(1.045) translateX(0);
}

.img-hov-slideleft img:hover {
  -webkit-transform: scale(1.045) translateX(-4.5%);
          transform: scale(1.045) translateX(-4.5%);
}

.img-hov-slideright img {
  -webkit-transform-origin: right center 0;
          transform-origin: right center 0;
  -webkit-transform: scale(1.045) translateX(0);
          transform: scale(1.045) translateX(0);
}

.img-hov-slideright img:hover {
  -webkit-transform: scale(1.045) translateX(4.5%);
          transform: scale(1.045) translateX(4.5%);
}

.img-hov-slideup img {
  -webkit-transform-origin: center top 0;
          transform-origin: center top 0;
  -webkit-transform: scale(1.045) translateY(0);
          transform: scale(1.045) translateY(0);
}

.img-hov-slideup img:hover {
  -webkit-transform: scale(1.045) translateY(-4.5%);
          transform: scale(1.045) translateY(-4.5%);
}

.img-hov-slidedown img {
  -webkit-transform-origin: center bottom 0;
          transform-origin: center bottom 0;
  -webkit-transform: scale(1.045) translateY(0);
          transform: scale(1.045) translateY(0);
}

.img-hov-slidedown img:hover {
  -webkit-transform: scale(1.045) translateY(4.5%);
          transform: scale(1.045) translateY(4.5%);
}

.img-hov-rotateleft img {
  -webkit-transform: rotateZ(0) scale(1);
          transform: rotateZ(0) scale(1);
}

.img-hov-rotateleft img:hover {
  -webkit-transform: rotateZ(-5deg) scale(1.1);
          transform: rotateZ(-5deg) scale(1.1);
}

.img-hov-rotateright img {
  -webkit-transform: rotateZ(0) scale(1);
          transform: rotateZ(0) scale(1);
}

.img-hov-rotateright img:hover {
  -webkit-transform: rotateZ(5deg) scale(1.1);
          transform: rotateZ(5deg) scale(1.1);
}

.teaser {
  background: #465161;
  position: relative;
  overflow: hidden;
  text-align: center;
  cursor: pointer;
}

.teaser img {
  position: relative;
  display: block;
  min-height: 100%;
  max-width: 100%;
  width: 100%;
  opacity: 0.7;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.teaser figcaption {
  padding: 2em;
  color: #fff;
  text-transform: uppercase;
  font-size: 1.25em;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.teaser figcaption::before, .teaser figcaption::after {
  pointer-events: none;
}

.teaser figcaption,
.teaser figcaption > a:not(.btn) {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.teaser figcaption > a {
  text-indent: 200%;
  white-space: nowrap;
  font-size: 0;
  opacity: 0;
}

.teaser h2,
.teaser h3,
.teaser h4 {
  color: #fff;
  font-family: Roboto, sans-serif;
  font-weight: 300;
  margin: 0;
}

.teaser h2 span,
.teaser h3 span,
.teaser h4 span {
  font-weight: 500;
}

.teaser p {
  letter-spacing: 1px;
  font-size: 68.5%;
  margin: 0;
}

.teaser-honey img {
  opacity: 0.9;
  -webkit-transition: opacity 0.35s;
  transition: opacity 0.35s;
}

.teaser-honey figcaption::before {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 10px;
  background: #33cabb;
  content: '';
  -webkit-transform: translate3d(0, 10px, 0);
          transform: translate3d(0, 10px, 0);
}

.teaser-honey h3 {
  position: absolute;
  bottom: 0;
  left: 0;
  padding: 1em 1.5em;
  width: 100%;
  text-align: left;
  -webkit-transform: translate3d(0, -30px, 0);
          transform: translate3d(0, -30px, 0);
}

.teaser-honey h3 i {
  font-style: normal;
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, -30px, 0);
          transform: translate3d(0, -30px, 0);
}

.teaser-honey figcaption::before,
.teaser-honey h3 {
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
}

.teaser-honey:hover img {
  opacity: 0.5;
}

.teaser-honey:hover figcaption::before,
.teaser-honey:hover h3,
.teaser-honey:hover h3 i {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-zoe figcaption {
  top: auto;
  bottom: 0;
  padding: 1em;
  height: 3.75em;
  background: #fff;
  color: #3c4a50;
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 100%, 0);
          transform: translate3d(0, 100%, 0);
}

.teaser-zoe h3 {
  color: #313944;
  float: left;
}

.teaser-zoe .icon-links a {
  float: right;
  color: #4d5259;
  font-size: 1.4em;
}

.teaser-zoe:hover .icon-links a:hover,
.teaser-zoe:hover .icon-links a:focus {
  color: #313944;
}

.teaser-zoe p.description {
  position: absolute;
  bottom: 8em;
  padding: 2em;
  color: #fff;
  text-transform: none;
  font-size: 90%;
  opacity: 0;
  -webkit-transition: opacity 0.35s;
  transition: opacity 0.35s;
  -webkit-backface-visibility: hidden;
}

.teaser-zoe h3,
.teaser-zoe .icon-links a {
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 200%, 0);
          transform: translate3d(0, 200%, 0);
}

.teaser-zoe .icon-links a span::before {
  padding: 8px 10px;
}

.teaser-zoe h3 {
  display: inline-block;
}

.teaser-zoe:hover p.description {
  opacity: 1;
}

.teaser-zoe:hover figcaption,
.teaser-zoe:hover h3,
.teaser-zoe:hover .icon-links a {
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-zoe:hover h3 {
  -webkit-transition-delay: 0.05s;
          transition-delay: 0.05s;
}

.teaser-zoe:hover .icon-links a:nth-child(3) {
  -webkit-transition-delay: 0.1s;
          transition-delay: 0.1s;
}

.teaser-zoe:hover .icon-links a:nth-child(2) {
  -webkit-transition-delay: 0.15s;
          transition-delay: 0.15s;
}

.teaser-zoe:hover .icon-links a:first-child {
  -webkit-transition-delay: 0.2s;
          transition-delay: 0.2s;
}

.teaser-marley figcaption {
  text-align: right;
}

.teaser-marley h3,
.teaser-marley p {
  position: absolute;
  right: 30px;
  left: 30px;
  padding: 10px 0;
}

.teaser-marley p {
  bottom: 30px;
  line-height: 1.5;
  -webkit-transform: translate3d(0, 100%, 0);
          transform: translate3d(0, 100%, 0);
}

.teaser-marley h3 {
  top: 30px;
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 20px, 0);
          transform: translate3d(0, 20px, 0);
}

.teaser-marley:hover h3 {
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-marley h3::after {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  height: 4px;
  background: #fff;
  content: '';
  -webkit-transform: translate3d(0, 40px, 0);
          transform: translate3d(0, 40px, 0);
}

.teaser-marley h3::after,
.teaser-marley p {
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
}

.teaser-marley:hover h3::after,
.teaser-marley:hover p {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-bubba img {
  opacity: 0.7;
  -webkit-transition: opacity 0.35s;
  transition: opacity 0.35s;
}

.teaser-bubba:hover img {
  opacity: 0.4;
}

.teaser-bubba figcaption::before,
.teaser-bubba figcaption::after {
  position: absolute;
  top: 30px;
  right: 30px;
  bottom: 30px;
  left: 30px;
  content: '';
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
}

.teaser-bubba figcaption::before {
  border-top: 1px solid #fff;
  border-bottom: 1px solid #fff;
  -webkit-transform: scale(0, 1);
          transform: scale(0, 1);
}

.teaser-bubba figcaption::after {
  border-right: 1px solid #fff;
  border-left: 1px solid #fff;
  -webkit-transform: scale(1, 0);
          transform: scale(1, 0);
}

.teaser-bubba h3 {
  padding-top: 27%;
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, -20px, 0);
          transform: translate3d(0, -20px, 0);
}

.teaser-bubba p {
  padding: 20px 2.5em;
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 20px, 0);
          transform: translate3d(0, 20px, 0);
}

.teaser-bubba:hover figcaption::before,
.teaser-bubba:hover figcaption::after {
  opacity: 1;
  -webkit-transform: scale(1);
          transform: scale(1);
}

.teaser-bubba:hover h3,
.teaser-bubba:hover p {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-milo img {
  max-width: none;
  width: 100%;
  opacity: 1;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(-20px, 0, 0) scale(1.12);
          transform: translate3d(-20px, 0, 0) scale(1.12);
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
}

.teaser-milo h3 {
  position: absolute;
  right: 0;
  bottom: 0;
  padding: 1em 1.2em;
}

.teaser-milo p {
  padding: 0 10px 0 0;
  width: 50%;
  border-right: 1px solid #fff;
  text-align: right;
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(-40px, 0, 0);
          transform: translate3d(-40px, 0, 0);
}

.teaser-milo:hover img {
  opacity: 0.5;
  -webkit-transform: translate3d(0, 0, 0) scale(1);
          transform: translate3d(0, 0, 0) scale(1);
}

.teaser-milo:hover p {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-hera h3 {
  font-size: 158.75%;
}

.teaser-hera h3,
.teaser-hera p {
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(-50%, -50%, 0);
          transform: translate3d(-50%, -50%, 0);
  -webkit-transform-origin: 50%;
          transform-origin: 50%;
}

.teaser-hera figcaption::before {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 200px;
  height: 200px;
  border: 2px solid #fff;
  content: '';
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(-50%, -50%, 0) rotate3d(0, 0, 1, -45deg) scale3d(0, 0, 1);
          transform: translate3d(-50%, -50%, 0) rotate3d(0, 0, 1, -45deg) scale3d(0, 0, 1);
  -webkit-transform-origin: 50%;
          transform-origin: 50%;
}

.teaser-hera p {
  width: 60px;
  text-transform: none;
  font-size: 121%;
  line-height: 2;
}

.teaser-hera p a {
  color: #fff;
}

.teaser-hera p a:hover,
.teaser-hera p a:focus {
  opacity: 0.6;
}

.teaser-hera p a i {
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
}

.teaser-hera p a:first-child i {
  -webkit-transform: translate3d(-60px, -60px, 0);
          transform: translate3d(-60px, -60px, 0);
}

.teaser-hera p a:nth-child(2) i {
  -webkit-transform: translate3d(60px, -60px, 0);
          transform: translate3d(60px, -60px, 0);
}

.teaser-hera p a:nth-child(3) i {
  -webkit-transform: translate3d(-60px, 60px, 0);
          transform: translate3d(-60px, 60px, 0);
}

.teaser-hera p a:nth-child(4) i {
  -webkit-transform: translate3d(60px, 60px, 0);
          transform: translate3d(60px, 60px, 0);
}

.teaser-hera:hover figcaption::before {
  opacity: 1;
  -webkit-transform: translate3d(-50%, -50%, 0) rotate3d(0, 0, 1, -45deg) scale3d(1, 1, 1);
          transform: translate3d(-50%, -50%, 0) rotate3d(0, 0, 1, -45deg) scale3d(1, 1, 1);
}

.teaser-hera:hover h3 {
  opacity: 0;
  -webkit-transform: translate3d(-50%, -50%, 0) scale3d(0.8, 0.8, 1);
          transform: translate3d(-50%, -50%, 0) scale3d(0.8, 0.8, 1);
}

.teaser-hera:hover p i:empty {
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
  opacity: 1;
}

.teaser-winston {
  text-align: left;
}

.teaser-winston img {
  -webkit-transition: opacity 0.45s;
  transition: opacity 0.45s;
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
}

.teaser-winston figcaption::before {
  position: absolute;
  bottom: -100%;
  left: 0;
  width: 200%;
  height: 100%;
  background-color: #fff;
  content: '';
  -webkit-transition: opacity 0.45s, -webkit-transform 0.45s;
  transition: opacity 0.45s, -webkit-transform 0.45s;
  transition: opacity 0.45s, transform 0.45s;
  transition: opacity 0.45s, transform 0.45s, -webkit-transform 0.45s;
  -webkit-transform-origin: 0 100%;
          transform-origin: 0 100%;
}

.teaser-winston h3 {
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 20px, 0);
          transform: translate3d(0, 20px, 0);
}

.teaser-winston p {
  position: absolute;
  right: 0;
  bottom: 0;
  padding: 0 1.5em 7% 0;
}

.teaser-winston a {
  margin: 0 10px;
  color: #313944;
  font-size: 170%;
}

.teaser-winston a:hover,
.teaser-winston a:focus {
  color: #33cabb;
}

.teaser-winston p a i {
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 50px, 0);
          transform: translate3d(0, 50px, 0);
}

.teaser-winston:hover img {
  opacity: 0.6;
}

.teaser-winston:hover h3 {
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-winston:hover figcaption::before {
  opacity: 0.7;
  -webkit-transform: rotate3d(0, 0, 1, -15deg);
          transform: rotate3d(0, 0, 1, -15deg);
}

.teaser-winston:hover p i {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-winston:hover p a:nth-child(3) i {
  -webkit-transition-delay: 0.05s;
          transition-delay: 0.05s;
}

.teaser-winston:hover p a:nth-child(2) i {
  -webkit-transition-delay: 0.1s;
          transition-delay: 0.1s;
}

.teaser-winston:hover p a:first-child i {
  -webkit-transition-delay: 0.15s;
          transition-delay: 0.15s;
}

.teaser-terry figcaption {
  padding: 1em;
}

.teaser-terry figcaption::before,
.teaser-terry figcaption::after {
  position: absolute;
  width: 200%;
  height: 200%;
  border-style: solid;
  border-color: #101010;
  content: '';
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
}

.teaser-terry figcaption::before {
  right: 0;
  bottom: 0;
  border-width: 0 70px 60px 0;
  -webkit-transform: translate3d(70px, 60px, 0);
          transform: translate3d(70px, 60px, 0);
}

.teaser-terry figcaption::after {
  top: 0;
  left: 0;
  border-width: 15px 0 0 15px;
  -webkit-transform: translate3d(-15px, -15px, 0);
          transform: translate3d(-15px, -15px, 0);
}

.teaser-terry img,
.teaser-terry p a {
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
}

.teaser-terry img {
  opacity: 0.85;
}

.teaser-terry h3 {
  position: absolute;
  bottom: 0;
  left: 0;
  padding: 0.4em 10px;
  width: 50%;
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(100%, 0, 0);
          transform: translate3d(100%, 0, 0);
}

@media screen and (max-width: 920px) {
  .teaser-terry h3 {
    padding: 0.75em 10px;
    font-size: 120%;
  }
}

.teaser-terry p {
  float: right;
  clear: both;
  text-align: left;
  text-transform: none;
  font-size: 111%;
}

.teaser-terry p a {
  display: block;
  margin-bottom: 1em;
  color: #fff;
  opacity: 0;
  -webkit-transform: translate3d(90px, 0, 0);
          transform: translate3d(90px, 0, 0);
}

.teaser-terry p a:hover,
.teaser-terry p a:focus {
  color: #f3cf3f;
}

.teaser-terry:hover figcaption::before,
.teaser-terry:hover figcaption::after {
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-terry:hover img {
  opacity: 0.6;
}

.teaser-terry:hover h3,
.teaser-terry:hover p a {
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-terry:hover p a {
  opacity: 1;
}

.teaser-terry:hover p a:first-child {
  -webkit-transition-delay: 0.025s;
          transition-delay: 0.025s;
}

.teaser-terry:hover p a:nth-child(2) {
  -webkit-transition-delay: 0.05s;
          transition-delay: 0.05s;
}

.teaser-terry:hover p a:nth-child(3) {
  -webkit-transition-delay: 0.075s;
          transition-delay: 0.075s;
}

.teaser-terry:hover p a:nth-child(4) {
  -webkit-transition-delay: 0.1s;
          transition-delay: 0.1s;
}

.teaser-steve {
  z-index: auto;
  overflow: visible;
}

.teaser-steve:before,
.teaser-steve h2:before {
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
  width: 100%;
  height: 100%;
  background: #000;
  content: '';
  -webkit-transition: opacity 0.35s;
  transition: opacity 0.35s;
}

.teaser-steve:before {
  -webkit-box-shadow: 0 3px 30px rgba(0, 0, 0, 0.8);
          box-shadow: 0 3px 30px rgba(0, 0, 0, 0.8);
  opacity: 0;
}

.teaser-steve figcaption {
  z-index: 1;
}

.teaser-steve img {
  opacity: 1;
  -webkit-transition: -webkit-transform 0.35s;
  transition: -webkit-transform 0.35s;
  transition: transform 0.35s;
  transition: transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: perspective(1000px) translate3d(0, 0, 0);
          transform: perspective(1000px) translate3d(0, 0, 0);
}

.teaser-steve h2,
.teaser-steve p {
  background: #fff;
  color: #2d434e;
}

.teaser-steve h2 {
  position: relative;
  margin-top: 2em;
  padding: 0.25em;
}

.teaser-steve h2:before {
  -webkit-box-shadow: 0 1px 10px rgba(0, 0, 0, 0.5);
          box-shadow: 0 1px 10px rgba(0, 0, 0, 0.5);
}

.teaser-steve p {
  margin-top: 1em;
  padding: 0.5em;
  font-weight: 800;
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: scale3d(0.9, 0.9, 1);
          transform: scale3d(0.9, 0.9, 1);
}

.teaser-steve:hover:before {
  opacity: 1;
}

.teaser-steve:hover img {
  -webkit-transform: perspective(1000px) translate3d(0, 0, 21px);
          transform: perspective(1000px) translate3d(0, 0, 21px);
}

.teaser-steve:hover h2:before {
  opacity: 0;
}

.teaser-steve:hover p {
  opacity: 1;
  -webkit-transform: scale3d(1, 1, 1);
          transform: scale3d(1, 1, 1);
}

.teaser-kira {
  text-align: left;
}

.teaser-kira img {
  -webkit-transition: opacity 0.35s;
  transition: opacity 0.35s;
}

.teaser-kira figcaption {
  z-index: 1;
}

.teaser-kira h2 {
  color: #fff;
}

.teaser-kira p {
  padding: 28px 10px;
  font-weight: 500;
  font-size: 100%;
  opacity: 0;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, -10px, 0);
          transform: translate3d(0, -10px, 0);
}

.teaser-kira p a {
  margin: 0 0.5em;
  color: #313944;
  vertical-align: middle;
}

.teaser-kira p a:hover, .teaser-kira p a:focus {
  opacity: 0.6;
}

.teaser-kira figcaption::before {
  position: absolute;
  top: 8px;
  right: 2em;
  left: 2em;
  z-index: -1;
  height: 3.1em;
  background: #fff;
  content: '';
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(0, 4em, 0) scale3d(1, 0.023, 1);
          transform: translate3d(0, 4em, 0) scale3d(1, 0.023, 1);
  -webkit-transform-origin: 50% 0;
          transform-origin: 50% 0;
}

.teaser-kira:hover img {
  opacity: 0.5;
}

.teaser-kira:hover p {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-kira:hover figcaption::before {
  opacity: 0.7;
  -webkit-transform: translate3d(0, 5em, 0) scale3d(1, 1, 1);
          transform: translate3d(0, 5em, 0) scale3d(1, 1, 1);
}

.teaser-julia img {
  max-width: none;
  -webkit-transition: opacity 1s, -webkit-transform 1s;
  transition: opacity 1s, -webkit-transform 1s;
  transition: opacity 1s, transform 1s;
  transition: opacity 1s, transform 1s, -webkit-transform 1s;
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
}

.teaser-julia figcaption {
  text-align: left;
}

.teaser-julia h2 {
  position: relative;
  padding: 0.5em 0;
}

.teaser-julia p {
  display: table;
  margin: 0 0 0.25em;
  padding: 0.4em 1em;
  background: rgba(255, 255, 255, 0.9);
  color: #4d5259;
  text-transform: none;
  font-weight: 500;
  font-size: 75%;
  -webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, -webkit-transform 0.35s;
  transition: opacity 0.35s, transform 0.35s;
  transition: opacity 0.35s, transform 0.35s, -webkit-transform 0.35s;
  -webkit-transform: translate3d(-360px, 0, 0);
          transform: translate3d(-360px, 0, 0);
}

.teaser-julia p:first-child {
  -webkit-transition-delay: 0.15s;
          transition-delay: 0.15s;
}

.teaser-julia p:nth-of-type(2) {
  -webkit-transition-delay: 0.1s;
          transition-delay: 0.1s;
}

.teaser-julia p:nth-of-type(3) {
  -webkit-transition-delay: 0.05s;
          transition-delay: 0.05s;
}

.teaser-julia:hover p:first-child {
  -webkit-transition-delay: 0s;
          transition-delay: 0s;
}

.teaser-julia:hover p:nth-of-type(2) {
  -webkit-transition-delay: 0.05s;
          transition-delay: 0.05s;
}

.teaser-julia:hover p:nth-of-type(3) {
  -webkit-transition-delay: 0.1s;
          transition-delay: 0.1s;
}

.teaser-julia:hover img {
  opacity: 0.4;
  -webkit-transform: scale3d(1.1, 1.1, 1);
          transform: scale3d(1.1, 1.1, 1);
}

.teaser-julia:hover p {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

.teaser-simple {
  cursor: default;
}

.teaser-simple img {
  opacity: 1;
  -webkit-transition: opacity 0.5s;
  transition: opacity 0.5s;
}

.teaser-simple figcaption {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center;
  text-transform: none;
  -webkit-transform: scale(0);
          transform: scale(0);
  -webkit-transition: .35s;
  transition: .35s;
}

.teaser-simple figcaption > * {
  margin-left: 4px;
  margin-right: 4px;
}

.teaser-simple figcaption > *:first-child {
  margin-left: 0;
}

.teaser-simple figcaption > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .teaser-simple figcaption > * {
    margin-left: 2px;
    margin-right: 2px;
  }
  .teaser-simple figcaption > *:first-child {
    margin-left: 0;
  }
  .teaser-simple figcaption > *:last-child {
    margin-right: 0;
  }
}

.teaser-simple figcaption a {
  position: static;
  z-index: auto;
  text-indent: 0;
  white-space: nowrap;
  font-size: inherit;
  opacity: 1;
}

.teaser-simple figcaption a:not(.btn) {
  width: auto;
  height: auto;
  font-size: 13px;
}

.teaser-simple:hover img {
  opacity: 0.5;
}

.teaser-simple:hover figcaption {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.media {
  padding: 16px 12px;
  -webkit-transition: background-color .2s linear;
  transition: background-color .2s linear;
}

.media > * {
  margin: 0 8px;
}

.media a:not(.btn):not(.avatar) {
  color: #4d5259;
}

.media.flex-column > * {
  margin: 0;
}

.media.flex-column > div {
  width: 100%;
}

.media.active {
  background-color: #f9fafb;
}

.media.bordered {
  border: 1px solid #ebebeb;
}

.media.items-center {
  -webkit-box-align: center;
          align-items: center;
}

.media[data-provide~="checkable"], .media[data-provide~="selectable"] {
  cursor: pointer;
}

.media .media {
  margin-top: 1.25rem;
}

.media .lead {
  line-height: 1.875rem;
}

.media .title {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
}

.media .avatar {
  flex-shrink: 0;
}

.media .align-center {
  -ms-grid-row-align: center;
      align-self: center;
}

.media .media-hover-show {
  opacity: 0;
  -webkit-transition: .3s;
  transition: .3s;
}

.media .dropdown .dropdown-toggle {
  opacity: .7;
}

.media .dropdown:hover .dropdown-toggle, .media .dropdown.open .dropdown-toggle {
  opacity: 1;
}

.media:hover .media-hover-show {
  opacity: 1;
}

.media .custom-control {
  margin-right: 0;
}

.media .nav {
  flex-wrap: nowrap;
  margin-left: -8px;
  margin-right: -8px;
}

.media .nav-link {
  line-height: 24px;
  font-size: 90%;
  padding: 0 8px;
}

.media-reverse {
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
}

.media-center {
  -webkit-box-align: center;
          align-items: center;
}

.media-block-actions {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  margin-top: 1rem;
  -webkit-transition: .5s;
  transition: .5s;
}

.media:hover .media-block-actions {
  opacity: 1;
}

.media-collapsible {
  flex-wrap: wrap;
}

.media-collapsible .collapse {
  -webkit-box-flex: 1;
          flex-grow: 1;
  flex-shrink: 0;
  min-width: 100%;
}

.collapse-arrow::before {
  content: "\e64b";
  font-family: themify;
  display: inline-block;
  -webkit-transition: .4s;
  transition: .4s;
}

.collapse-arrow:not(.collapsed)::before {
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}

.media-body {
  min-width: 0;
}

.media-body > * {
  margin-bottom: 0;
}

.media-body .media > *:first-child {
  margin-left: 0;
}

.media-body .media > *:last-child {
  margin-right: 0;
}

.media-left {
  padding-right: 0;
}

.media-right-out {
  padding-left: 1.25rem;
}

.media-right-out a {
  color: #616a78;
  font-size: 1.125rem;
  opacity: 0.8;
}

.media-right-out a:hover {
  color: #33cabb;
}

.media-right-out a + a {
  margin-left: 0.5rem;
}

.media-action {
  opacity: 0;
  color: #8b95a5;
}

.media:hover .media-action {
  opacity: 1;
}

.media-action-visible .media-action {
  opacity: 1;
}

.media.media-xs {
  padding: 10px 6px;
}

.media.media-sm {
  padding: 12px 8px;
}

.media.media-lg {
  padding-top: 20px 16px;
}

.media.media-xl {
  padding-top: 24px 20px;
}

.media.media-xxl {
  padding-top: 32px 24px;
}

.media-inverse,
.media-inverse h1, .media-inverse h2, .media-inverse h3, .media-inverse h4, .media-inverse h5, .media-inverse h6 {
  color: #fff;
}

.media-inverse .small,
.media-inverse small,
.media-inverse time {
  color: rgba(255, 255, 255, 0.7);
}

.media-inverse .nav-link {
  color: rgba(255, 255, 255, 0.7);
}

.media-inverse .nav-link:hover {
  color: white;
}

.media-new {
  background-color: #f3f9ff;
}

.media-list-hover .media-new:hover {
  background-color: #ecf5fe !important;
}

.media-single,
.media-center-v {
  -webkit-box-align: center;
          align-items: center;
}

.media-vertical {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
}

.media-chat {
  padding-right: 64px;
  margin-bottom: 0;
}

.media-chat.media-chat-reverse {
  padding-right: 12px;
  padding-left: 64px;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
}

.media-chat .media-body {
  -webkit-box-flex: initial;
          flex: initial;
  display: table;
}

.media-chat .media-body p {
  position: relative;
  padding: 6px 8px;
  margin: 4px 0;
  background-color: #f5f6f7;
  border-radius: 3px;
}

.media-chat .media-body p.inverse {
  color: #fff;
}

.media-chat .media-body p.meta {
  background-color: transparent !important;
  padding: 0;
  opacity: .8;
}

.media-chat .media-body p.meta time {
  font-weight: 300;
}

.media-chat.media-chat-reverse .media-body p {
  float: right;
  clear: right;
  background-color: #48b0f7;
  color: #fff;
}

.media-meta-day {
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  margin-bottom: 0;
  color: #8b95a5;
  opacity: .8;
  font-weight: 400;
}

.media-meta-day::before, .media-meta-day::after {
  content: '';
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  border-top: 1px solid #ebebeb;
}

.media-meta-day::before {
  margin-right: 16px;
}

.media-meta-day::after {
  margin-left: 16px;
}

@media (max-width: 767px) {
  .media-doc {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
  }
  .media-doc > * {
    margin-left: 0;
    margin-right: 0;
  }
  .media-doc .media-body {
    margin: 30px 0;
  }
}

.media-list > .media:not(.media-list-header):not(.media-list-footer),
.media-list .media-list-body > .media {
  margin-bottom: 0;
}

.media-listul {
  padding-left: 0;
}

.media-list .media .sortable-dot {
  margin: 0;
  margin-left: -5px;
  opacity: 0;
  border-radius: 2px;
  -webkit-transition: .5s;
  transition: .5s;
}

.media-list .media:hover .sortable-dot {
  opacity: .6;
}

.media-list[data-provide~="selectable"] .media:not(.media-list-header):not(.media-list-footer) {
  cursor: pointer;
}

.media-list-hover > .media:not(.media-list-header):not(.media-list-footer):hover,
.media-list-hover .media-list-body > .media:hover {
  background-color: #f9fafb;
}

.media-list-divided > .media:not(.media-list-header):not(.media-list-footer),
.media-list-divided .media-list-body > .media {
  border-bottom: 1px solid rgba(77, 82, 89, 0.07);
}

.media-list-divided > .media:not(.media-list-header):not(.media-list-footer):last-child,
.media-list-divided .media-list-body > .media:last-child {
  border-bottom: none;
}

.media-list-bordered {
  border: 1px solid #f1f2f3;
}

.media-list-striped .media:not(.media-list-header):not(.media-list-footer):nth-child(even) {
  background-color: #f9fafb;
}

.media-list-xs .media {
  padding: 10px 16px;
}

.media-list-sm .media {
  padding: 12px 16px;
}

.media-list-lg .media {
  padding: 20px 16px;
}

.media-list-xl .media {
  padding: 24px 20px;
}

.media-list-xxl .media {
  padding: 32px 24px;
}

.media-list-header {
  border-bottom: 1px solid #ebebeb;
  background-color: #fcfdfe;
}

.media-list-footer {
  border-top: 1px solid #ebebeb;
  background-color: #fcfdfe;
}

.media-grid {
  display: -webkit-box;
  display: flex;
  flex-wrap: wrap;
  margin-right: -15px;
}

.media-grid::after {
  content: '';
  -webkit-box-flex: 1;
          flex: auto;
}

.media-grid.row {
  margin-left: -15px;
  margin-right: -15px;
}

.media-grid.row .media {
  margin-bottom: 15px;
}

.media-grid > .media {
  margin-right: 15px;
  margin-bottom: 15px;
  width: 200px;
}

.media-grid-bordered .media {
  border: 1px solid #ebebeb;
}

.media-grid-hover .media:hover {
  background-color: #f9fafb;
}

.table th {
  border-top: 0;
  font-weight: 400;
}

.table tbody th {
  border-top: 1px solid #eceeef;
}

.table thead th {
  border-bottom: 1px solid #ebebeb;
}

.table tfoot th {
  border-top: 1px solid #ebebeb;
  border-bottom: 0;
}

.thead-default th {
  background-color: #fcfdfe;
}

.table-hover tbody tr {
  -webkit-transition: background-color 0.2s linear;
  transition: background-color 0.2s linear;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #fcfdfe;
}

.table-hover tbody tr:hover {
  background-color: #f9fafb;
}

.table-sm th,
.table-sm td {
  padding: .5rem;
}

.table-lg th,
.table-lg td {
  padding: 1rem;
}

.table-separated {
  border-collapse: separate;
  border-spacing: 0 8px;
}

.table-separated.table-striped tbody tr:nth-of-type(odd),
.table-separated.table-hover tbody tr:hover {
  background-color: #f9fafb;
}

.table-separated tbody tr {
  background-color: #fcfdfe;
  -webkit-transition: .5s;
  transition: .5s;
}

.table-separated tbody tr > *:first-child {
  border-top-left-radius: 3px;
  border-bottom-left-radius: 3px;
}

.table-separated tbody tr > *:last-child {
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}

.table-separated tbody tr th,
.table-separated tbody tr td {
  border-top: none;
}

.table-separated thead th {
  border-bottom: none;
}

.table-active,
.table-active > th,
.table-active > td {
  background-color: #f5f6f7;
}

.table tr[class*="bl-"] > *:first-child {
  border-left: inherit;
}

.table tr[class*="br-"] > *:last-child {
  border-right: inherit;
}

.table tr[class*="bt-"] > * {
  border-top: inherit;
}

.table tr[class*="bb-"] > * {
  border-bottom: inherit;
}

.table tr[class*="bx-"] > *:first-child {
  border-left: inherit;
}

.table tr[class*="bx-"] > *:last-child {
  border-right: inherit;
}

.table tr[class*="by-"] > * {
  border-top: inherit;
  border-bottom: inherit;
}

.table tr[class*="b-"] > * {
  border-top: inherit;
  border-bottom: inherit;
}

.table tr[class*="b-"] > *:first-child {
  border-left: inherit;
}

.table tr[class*="b-"] > *:last-child {
  border-right: inherit;
}

.table .media {
  padding: 0;
}

.table-actions .table-action {
  padding: 0 4px;
  font-size: 1rem;
  color: #8b95a5;
}

.table-actions .table-action:first-child {
  padding-left: 0;
}

.table-actions .table-action:last-child {
  padding-rightt: 0;
}

.table-actions .dropdown {
  display: inline-block;
}

.table tr.active {
  background-color: #f9fafb;
  -webkit-transition: background-color .3s;
  transition: background-color .3s;
}

.table-bordered {
  border: 1px solid #ebebeb !important;
}

.alert {
  border: none;
  border-radius: 3px;
}

.alert hr {
  margin-top: 15px;
  margin-bottom: 15px;
}

.alert-link {
  font-weight: 400;
}

.alert-primary {
  color: #1c7068;
  background-color: #d5f5f3;
}

.alert-secondary {
  background-color: #e4e7ea;
}

.alert-info {
  color: #004085;
  background-color: #cce5ff;
}

.alert-light {
  background-color: #f7fafc;
}

.callout {
  padding: 15px 20px;
  margin-bottom: 20px;
  border-left: 3px solid transparent;
}

.callout-success {
  border-left-color: #15c377;
  background-color: #fcfdfe;
}

.callout-success h3,
.callout-success h4,
.callout-success h5,
.callout-success h6,
.callout-success a {
  color: #15c377;
}

.callout-success h3,
.callout-success h4,
.callout-success h5,
.callout-success h6 {
  text-transform: uppercase;
}

.callout-success > *:last-child {
  margin-bottom: 0;
}

.callout-info {
  border-left-color: #48b0f7;
  background-color: #fcfdfe;
}

.callout-info h3,
.callout-info h4,
.callout-info h5,
.callout-info h6,
.callout-info a {
  color: #48b0f7;
}

.callout-info h3,
.callout-info h4,
.callout-info h5,
.callout-info h6 {
  text-transform: uppercase;
}

.callout-info > *:last-child {
  margin-bottom: 0;
}

.callout-warning {
  border-left-color: #faa64b;
  background-color: #fcfdfe;
}

.callout-warning h3,
.callout-warning h4,
.callout-warning h5,
.callout-warning h6,
.callout-warning a {
  color: #faa64b;
}

.callout-warning h3,
.callout-warning h4,
.callout-warning h5,
.callout-warning h6 {
  text-transform: uppercase;
}

.callout-warning > *:last-child {
  margin-bottom: 0;
}

.callout-danger {
  border-left-color: #f96868;
  background-color: #fcfdfe;
}

.callout-danger h3,
.callout-danger h4,
.callout-danger h5,
.callout-danger h6,
.callout-danger a {
  color: #f96868;
}

.callout-danger h3,
.callout-danger h4,
.callout-danger h5,
.callout-danger h6 {
  text-transform: uppercase;
}

.callout-danger > *:last-child {
  margin-bottom: 0;
}

@media (max-width: 767px) {
  .callout {
    padding: 10px;
  }
}

.tooltip-inner {
  background-color: #323232;
  border-radius: 2px;
  font-size: 12px;
}

.tooltip.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #323232;
}

.tooltip.bs-tooltip-top .arrow::before {
  border-top-color: #323232;
}

.tooltip.bs-tooltip-right .arrow::before {
  border-right-color: #323232;
}

.tooltip.bs-tooltip-left .arrow::before {
  border-left-color: #323232;
}

.tooltip-primary.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #33cabb;
}

.tooltip-primary.bs-tooltip-top .arrow::before {
  border-top-color: #33cabb;
}

.tooltip-primary.bs-tooltip-right .arrow::before {
  border-right-color: #33cabb;
}

.tooltip-primary.bs-tooltip-left .arrow::before {
  border-left-color: #33cabb;
}

.tooltip-primary .tooltip-inner {
  color: #fff;
  background-color: #33cabb;
}

.tooltip-secondary.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #e4e7ea;
}

.tooltip-secondary.bs-tooltip-top .arrow::before {
  border-top-color: #e4e7ea;
}

.tooltip-secondary.bs-tooltip-right .arrow::before {
  border-right-color: #e4e7ea;
}

.tooltip-secondary.bs-tooltip-left .arrow::before {
  border-left-color: #e4e7ea;
}

.tooltip-secondary .tooltip-inner {
  color: #fff;
  background-color: #e4e7ea;
}

.tooltip-success.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #15c377;
}

.tooltip-success.bs-tooltip-top .arrow::before {
  border-top-color: #15c377;
}

.tooltip-success.bs-tooltip-right .arrow::before {
  border-right-color: #15c377;
}

.tooltip-success.bs-tooltip-left .arrow::before {
  border-left-color: #15c377;
}

.tooltip-success .tooltip-inner {
  color: #fff;
  background-color: #15c377;
}

.tooltip-info.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #48b0f7;
}

.tooltip-info.bs-tooltip-top .arrow::before {
  border-top-color: #48b0f7;
}

.tooltip-info.bs-tooltip-right .arrow::before {
  border-right-color: #48b0f7;
}

.tooltip-info.bs-tooltip-left .arrow::before {
  border-left-color: #48b0f7;
}

.tooltip-info .tooltip-inner {
  color: #fff;
  background-color: #48b0f7;
}

.tooltip-warning.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #faa64b;
}

.tooltip-warning.bs-tooltip-top .arrow::before {
  border-top-color: #faa64b;
}

.tooltip-warning.bs-tooltip-right .arrow::before {
  border-right-color: #faa64b;
}

.tooltip-warning.bs-tooltip-left .arrow::before {
  border-left-color: #faa64b;
}

.tooltip-warning .tooltip-inner {
  color: #fff;
  background-color: #faa64b;
}

.tooltip-danger.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #f96868;
}

.tooltip-danger.bs-tooltip-top .arrow::before {
  border-top-color: #f96868;
}

.tooltip-danger.bs-tooltip-right .arrow::before {
  border-right-color: #f96868;
}

.tooltip-danger.bs-tooltip-left .arrow::before {
  border-left-color: #f96868;
}

.tooltip-danger .tooltip-inner {
  color: #fff;
  background-color: #f96868;
}

.tooltip-pink.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #f96197;
}

.tooltip-pink.bs-tooltip-top .arrow::before {
  border-top-color: #f96197;
}

.tooltip-pink.bs-tooltip-right .arrow::before {
  border-right-color: #f96197;
}

.tooltip-pink.bs-tooltip-left .arrow::before {
  border-left-color: #f96197;
}

.tooltip-pink .tooltip-inner {
  color: #fff;
  background-color: #f96197;
}

.tooltip-purple.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #926dde;
}

.tooltip-purple.bs-tooltip-top .arrow::before {
  border-top-color: #926dde;
}

.tooltip-purple.bs-tooltip-right .arrow::before {
  border-right-color: #926dde;
}

.tooltip-purple.bs-tooltip-left .arrow::before {
  border-left-color: #926dde;
}

.tooltip-purple .tooltip-inner {
  color: #fff;
  background-color: #926dde;
}

.tooltip-brown.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #8d6658;
}

.tooltip-brown.bs-tooltip-top .arrow::before {
  border-top-color: #8d6658;
}

.tooltip-brown.bs-tooltip-right .arrow::before {
  border-right-color: #8d6658;
}

.tooltip-brown.bs-tooltip-left .arrow::before {
  border-left-color: #8d6658;
}

.tooltip-brown .tooltip-inner {
  color: #fff;
  background-color: #8d6658;
}

.tooltip-cyan.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #57c7d4;
}

.tooltip-cyan.bs-tooltip-top .arrow::before {
  border-top-color: #57c7d4;
}

.tooltip-cyan.bs-tooltip-right .arrow::before {
  border-right-color: #57c7d4;
}

.tooltip-cyan.bs-tooltip-left .arrow::before {
  border-left-color: #57c7d4;
}

.tooltip-cyan .tooltip-inner {
  color: #fff;
  background-color: #57c7d4;
}

.tooltip-yellow.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #fcc525;
}

.tooltip-yellow.bs-tooltip-top .arrow::before {
  border-top-color: #fcc525;
}

.tooltip-yellow.bs-tooltip-right .arrow::before {
  border-right-color: #fcc525;
}

.tooltip-yellow.bs-tooltip-left .arrow::before {
  border-left-color: #fcc525;
}

.tooltip-yellow .tooltip-inner {
  color: #fff;
  background-color: #fcc525;
}

.tooltip-gray.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #868e96;
}

.tooltip-gray.bs-tooltip-top .arrow::before {
  border-top-color: #868e96;
}

.tooltip-gray.bs-tooltip-right .arrow::before {
  border-right-color: #868e96;
}

.tooltip-gray.bs-tooltip-left .arrow::before {
  border-left-color: #868e96;
}

.tooltip-gray .tooltip-inner {
  color: #fff;
  background-color: #868e96;
}

.tooltip-dark.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #465161;
}

.tooltip-dark.bs-tooltip-top .arrow::before {
  border-top-color: #465161;
}

.tooltip-dark.bs-tooltip-right .arrow::before {
  border-right-color: #465161;
}

.tooltip-dark.bs-tooltip-left .arrow::before {
  border-left-color: #465161;
}

.tooltip-dark .tooltip-inner {
  color: #fff;
  background-color: #465161;
}

.tooltip-secondary .tooltip-inner {
  color: #4d5259;
}

.tooltip-light.bs-tooltip-bottom .arrow::before,
.tooltip-white.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #fff;
}

.tooltip-light.bs-tooltip-top .arrow::before,
.tooltip-white.bs-tooltip-top .arrow::before {
  border-top-color: #fff;
}

.tooltip-light.bs-tooltip-right .arrow::before,
.tooltip-white.bs-tooltip-right .arrow::before {
  border-right-color: #fff;
}

.tooltip-light.bs-tooltip-left .arrow::before,
.tooltip-white.bs-tooltip-left .arrow::before {
  border-left-color: #fff;
}

.tooltip-light .tooltip-inner,
.tooltip-white .tooltip-inner {
  color: #4d5259;
  background-color: #fff;
}

.popover {
  font-family: Roboto, sans-serif;
  border-color: #ebebeb;
  border-radius: 2px;
}

.popover.bs-popover-bottom .arrow::before {
  border-bottom-color: #ebebeb;
}

.popover.bs-popover-bottom .arrow::after {
  border-bottom-color: #fcfdfe;
}

.popover.bs-popover-top .arrow::before {
  border-top-color: #ebebeb;
}

.popover.bs-popover-left .arrow::before {
  border-left-color: #ebebeb;
}

.popover.bs-popover-right .arrow::before {
  border-right-color: #ebebeb;
}

.popover-header {
  background-color: #fcfdfe;
  padding-top: 12px;
  padding-bottom: 12px;
  font-family: Roboto, sans-serif;
  font-size: 14px;
  font-weight: 400;
  color: #616a78;
  border-bottom-color: #f1f2f3;
}

.popover-body {
  font-weight: 300;
}

.toast {
  padding: 14px 24px;
  line-height: 20px;
  color: #fff;
  background: #323232;
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  position: fixed;
  bottom: 0;
  left: 50%;
  -webkit-transform: translateX(-50%) translateY(100%);
          transform: translateX(-50%) translateY(100%);
  -webkit-transition: .3s;
  transition: .3s;
  -webkit-box-shadow: 0px 3px 2px rgba(0, 0, 0, 0.2);
          box-shadow: 0px 3px 2px rgba(0, 0, 0, 0.2);
  z-index: 1051;
  min-width: 288px;
  max-width: 80%;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
}

.toast.reveal {
  -webkit-transform: translateX(-50%) translateY(0);
          transform: translateX(-50%) translateY(0);
}

.toast .text {
  flex-basis: 1;
  font-weight: 400;
  white-space: nowrap;
  margin-right: 48px;
}

.toast .action {
  text-transform: uppercase;
  font-weight: 400;
  font-size: 0.875rem;
}

.toast .action a:hover {
  text-decoration: none;
}

.accordion .card {
  background-color: #fff;
  border: 1px solid #f1f2f3;
  margin-bottom: 16px;
}

.accordion .card:last-child {
  margin-bottom: 0;
}

.accordion .card-title {
  background-color: #fcfdfe;
}

.accordion .card-title a {
  display: block;
  letter-spacing: 1px;
  padding-left: 33px;
}

.accordion .card-title a::before {
  content: "\e648";
  display: inline-block;
  font-family: themify;
  font-size: 12px;
  margin-right: 20px;
  margin-left: -33px;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.accordion .card-title a.collapsed::before {
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}

.accordion-connected .card {
  margin-bottom: 0;
  border: none;
  border-bottom: 1px solid #f1f2f3;
}

.accordion-connected .card:last-child {
  border-bottom: 0;
}

.accordion-connected .card-title {
  border-bottom: 0;
  background-color: #fff;
}

.nav-tabs {
  border-bottom-color: #ebebeb;
  margin-bottom: 1rem;
}

.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-item.show .nav-link:focus,
.nav-tabs .nav-item.show .nav-link:hover {
  color: #4d5259;
}

.nav-tabs .nav-link {
  position: relative;
  border: none;
  font-size: 13px;
  text-align: center;
  color: #8b95a5;
  border-bottom: 2px solid transparent;
  padding: 10px 16px;
  border-radius: 0;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}

@media (max-width: 767px) {
  .nav-tabs .nav-link {
    padding: 8px 12px;
  }
}

.nav-tabs .nav-link:hover, .nav-tabs .nav-link.active, .nav-tabs .nav-link.active:focus, .nav-tabs .nav-link.active:hover {
  color: #4d5259;
  border-color: #33cabb;
}

.nav-tabs .nav-link .icon {
  margin-right: 4px;
}

.nav-tabs .nav-link .close {
  width: 12px;
  height: 12px;
  margin-top: 6px;
  font-size: 20px;
  opacity: 0;
  -webkit-transform: translateX(10px);
          transform: translateX(10px);
}

.nav-tabs .nav-link .close span {
  display: inline-block;
  width: inherit;
  height: inherit;
}

.nav-tabs .nav-link:hover .close {
  opacity: .25;
}

.nav-tabs.nav-tabs-primary .nav-link:hover, .nav-tabs.nav-tabs-primary .nav-link.active, .nav-tabs.nav-tabs-primary .nav-link.active:focus, .nav-tabs.nav-tabs-primary .nav-link.active:hover {
  border-color: #33cabb;
}

.nav-tabs.nav-tabs-secondary .nav-link:hover, .nav-tabs.nav-tabs-secondary .nav-link.active, .nav-tabs.nav-tabs-secondary .nav-link.active:focus, .nav-tabs.nav-tabs-secondary .nav-link.active:hover {
  border-color: #e4e7ea;
}

.nav-tabs.nav-tabs-success .nav-link:hover, .nav-tabs.nav-tabs-success .nav-link.active, .nav-tabs.nav-tabs-success .nav-link.active:focus, .nav-tabs.nav-tabs-success .nav-link.active:hover {
  border-color: #15c377;
}

.nav-tabs.nav-tabs-info .nav-link:hover, .nav-tabs.nav-tabs-info .nav-link.active, .nav-tabs.nav-tabs-info .nav-link.active:focus, .nav-tabs.nav-tabs-info .nav-link.active:hover {
  border-color: #48b0f7;
}

.nav-tabs.nav-tabs-warning .nav-link:hover, .nav-tabs.nav-tabs-warning .nav-link.active, .nav-tabs.nav-tabs-warning .nav-link.active:focus, .nav-tabs.nav-tabs-warning .nav-link.active:hover {
  border-color: #faa64b;
}

.nav-tabs.nav-tabs-danger .nav-link:hover, .nav-tabs.nav-tabs-danger .nav-link.active, .nav-tabs.nav-tabs-danger .nav-link.active:focus, .nav-tabs.nav-tabs-danger .nav-link.active:hover {
  border-color: #f96868;
}

.nav-tabs.nav-tabs-pink .nav-link:hover, .nav-tabs.nav-tabs-pink .nav-link.active, .nav-tabs.nav-tabs-pink .nav-link.active:focus, .nav-tabs.nav-tabs-pink .nav-link.active:hover {
  border-color: #f96197;
}

.nav-tabs.nav-tabs-purple .nav-link:hover, .nav-tabs.nav-tabs-purple .nav-link.active, .nav-tabs.nav-tabs-purple .nav-link.active:focus, .nav-tabs.nav-tabs-purple .nav-link.active:hover {
  border-color: #926dde;
}

.nav-tabs.nav-tabs-brown .nav-link:hover, .nav-tabs.nav-tabs-brown .nav-link.active, .nav-tabs.nav-tabs-brown .nav-link.active:focus, .nav-tabs.nav-tabs-brown .nav-link.active:hover {
  border-color: #8d6658;
}

.nav-tabs.nav-tabs-cyan .nav-link:hover, .nav-tabs.nav-tabs-cyan .nav-link.active, .nav-tabs.nav-tabs-cyan .nav-link.active:focus, .nav-tabs.nav-tabs-cyan .nav-link.active:hover {
  border-color: #57c7d4;
}

.nav-tabs.nav-tabs-yellow .nav-link:hover, .nav-tabs.nav-tabs-yellow .nav-link.active, .nav-tabs.nav-tabs-yellow .nav-link.active:focus, .nav-tabs.nav-tabs-yellow .nav-link.active:hover {
  border-color: #fcc525;
}

.nav-tabs.nav-tabs-gray .nav-link:hover, .nav-tabs.nav-tabs-gray .nav-link.active, .nav-tabs.nav-tabs-gray .nav-link.active:focus, .nav-tabs.nav-tabs-gray .nav-link.active:hover {
  border-color: #868e96;
}

.nav-tabs.nav-tabs-dark .nav-link:hover, .nav-tabs.nav-tabs-dark .nav-link.active, .nav-tabs.nav-tabs-dark .nav-link.active:focus, .nav-tabs.nav-tabs-dark .nav-link.active:hover {
  border-color: #465161;
}

.nav-tabs-light-mode {
  border-bottom: none;
  background-color: #f9fafb;
}

.nav-tabs-light-mode .nav-link {
  border-bottom: none;
}

.nav-tabs-inverse-mode {
  border-bottom: none;
  background-color: transparent;
  margin-bottom: 0;
}

.nav-tabs-inverse-mode .nav-link {
  border-bottom: none;
}

.nav-tabs-inverse-mode .nav-link.active, .nav-tabs-inverse-mode .nav-link.active:focus, .nav-tabs-inverse-mode .nav-link.active:hover {
  background-color: #f9fafb;
}

.nav-tabs-inverse-mode + .tab-content {
  background-color: #f9fafb;
  padding: 20px 16px;
}

.nav-tabs-left,
.nav-tabs-right {
  display: -webkit-box;
  display: flex;
}

.nav-tabs-left .nav-tabs,
.nav-tabs-right .nav-tabs {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  border-bottom: 0;
}

.nav-tabs-left .nav-tabs .nav-item + .nav-item,
.nav-tabs-right .nav-tabs .nav-item + .nav-item {
  margin-left: 0;
}

.nav-tabs-left .nav-tabs .nav-link,
.nav-tabs-right .nav-tabs .nav-link {
  border-bottom: none;
  text-align: left;
}

.nav-tabs-left .tab-content,
.nav-tabs-right .tab-content {
  overflow: hidden;
}

.nav-tabs-left .nav-tabs {
  border-right: 1px solid #ebebeb;
}

.nav-tabs-left .nav-tabs .nav-link {
  border-right: 2px solid transparent;
}

.nav-tabs-left .tab-content {
  padding-left: 20px;
}

.nav-tabs-right .nav-tabs {
  -webkit-box-ordinal-group: 3;
          order: 2;
  border-left: 1px solid #ebebeb;
}

.nav-tabs-right .nav-tabs .nav-link {
  border-left: 2px solid transparent;
}

.nav-tabs-right .tab-content {
  -webkit-box-ordinal-group: 2;
          order: 1;
  padding-right: 20px;
}

.nav-process {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: baseline;
          align-items: baseline;
  margin-bottom: 1rem;
}

.nav-process .nav-title {
  font-weight: 400;
}

.nav-process .nav-item {
  -webkit-box-align: center;
          align-items: center;
}

.nav-process .nav-link {
  padding: 0;
}

.nav-process-circle .nav-item {
  position: relative;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  color: #8b95a5;
  padding: 0 12px;
}

.nav-process-circle .nav-item:first-child .nav-link::before {
  display: none;
}

.nav-process-circle .nav-item.complete .nav-link, .nav-process-circle .nav-item.complete .nav-link::before, .nav-process-circle .nav-item.processing .nav-link, .nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #dcfcfa;
}

.nav-process-circle .nav-item.complete .nav-link::after, .nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #33cabb;
  width: 29px;
  height: 29px;
  -webkit-transform: translateX(0);
          transform: translateX(0);
  color: #fff;
}

.nav-process-circle .nav-item.complete .nav-link::after {
  width: 29px;
  height: 29px;
  -webkit-transform: translateX(0);
          transform: translateX(0);
  color: #fff;
}

.nav-process-circle .nav-item.processing {
  color: #4d5259;
}

.nav-process-circle .nav-item.processing .nav-link::after {
  width: 13px;
  height: 13px;
  margin-top: 8px;
  -webkit-transform: translateX(8px);
          transform: translateX(8px);
  color: transparent;
}

.nav-process-circle .nav-link {
  display: -webkit-inline-box;
  display: inline-flex;
  margin: 10px 0;
  width: 29px;
  height: 29px;
  max-height: 29px;
  border-radius: 50%;
  background-color: #f7fafc;
  -webkit-transition: .5s;
  transition: .5s;
  z-index: 1;
}

.nav-process-circle .nav-link::before {
  content: '';
  position: absolute;
  left: calc(-50% + 14.5px);
  right: calc(50% + 14.5px);
  height: 10px;
  margin-top: 9.5px;
  background-color: #f7fafc;
  cursor: default;
  -webkit-transition: .5s;
  transition: .5s;
}

.nav-process-circle .nav-link::after {
  content: "\e64c";
  font-family: themify;
  width: 0;
  height: 0;
  text-align: center;
  font-size: 15px;
  position: absolute;
  border-radius: 50%;
  background-color: transparent;
  color: transparent;
  -webkit-transform: translate(14.5px, 14.5px);
          transform: translate(14.5px, 14.5px);
  -webkit-transition: .5s;
  transition: .5s;
  z-index: 1;
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center;
}

.nav-process-iconic .nav-item {
  position: relative;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  text-align: center;
  color: #8b95a5;
  padding: 0 12px;
}

.nav-process-iconic .nav-item i {
  color: #8b95a5;
}

.nav-process-iconic .nav-item:first-child .nav-link::before {
  display: none;
}

.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #33cabb;
}

.nav-process-iconic .nav-item.complete i {
  color: #fff;
}

.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #33cabb;
}

.nav-process-iconic .nav-item.processing {
  color: #4d5259;
}

.nav-process-iconic .nav-item.processing i {
  color: #33cabb;
}

.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #33cabb;
  background-color: #fff;
}

.nav-process-iconic .nav-link {
  font-size: 22px;
  -webkit-box-pack: center !important;
          justify-content: center !important;
  margin: 10px 0;
  width: 48px;
  height: 48px;
  line-height: 44px;
  max-height: 48px;
  border-radius: 50%;
  border: 2px solid transparent;
  background-color: #f7fafc;
  color: inherit;
}

.nav-process-iconic .nav-link::before {
  content: '';
  position: absolute;
  left: calc(-50% + 24px);
  right: calc(50% + 24px);
  height: 3px;
  margin-top: 22.5px;
  background-color: #f7fafc;
  cursor: default;
}

.nav-process-box {
  margin-bottom: 0;
}

.nav-process-box .nav-item {
  display: -webkit-box;
  display: flex;
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  border-right: 1px solid #f1f2f3;
}

.nav-process-box .nav-item::after {
  content: "\e649";
  font-family: themify;
  color: #f1f2f3;
  background-color: #fff;
  margin-right: -10px;
  line-height: 1;
}

.nav-process-box .nav-item:last-child {
  border-right: none;
}

.nav-process-box .nav-item:last-child::after {
  display: none;
}

.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #fff;
  background-color: #33cabb;
}

.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number i {
  color: #fff;
}

.nav-process-box .nav-item.processing .nav-link-number {
  background-color: #465161;
  color: #fff;
}

.nav-process-box .nav-item.processing .nav-title {
  color: #465161;
}

.nav-process-box .nav-link {
  padding: 12px 20px;
  width: 100%;
}

.nav-process-box .nav-link-number {
  display: inline-block;
  max-width: 29px;
  width: 29px;
  height: 29px;
  line-height: 29px;
  font-size: 15px;
  font-weight: 400;
  border-radius: 50%;
  background-color: #f7fafc;
  color: #8b95a5;
  text-align: center;
}

.nav-process-box .nav-link-number i {
  font-size: 12px;
}

.nav-process-block .nav-item {
  display: -webkit-box;
  display: flex;
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  color: #8b95a5;
  margin-right: 4px;
}

.nav-process-block .nav-item:last-child {
  margin-right: 0;
}

.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #33cabb;
  color: #fff;
}

.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #33cabb;
  font-weight: 400;
}

.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #33cabb;
  color: #fff;
}

.nav-process-block .nav-link {
  display: -webkit-box;
  display: flex;
  width: 100%;
  background-color: #f7fafc;
  padding: 20px;
}

.nav-process-block .nav-link-number {
  max-width: 48px;
  width: 48px;
  height: 48px;
  line-height: 48px;
  font-size: 28px;
  font-weight: 300;
  border-radius: 50%;
  background-color: #fff;
  color: #8b95a5;
  text-align: center;
  flex-shrink: 0;
}

.nav-process-block .nav-link-body {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  flex-shrink: 1;
  -webkit-box-flex: 1;
          flex-grow: 1;
  white-space: normal;
  padding-left: 12px;
  line-height: 1.25rem;
  font-weight: 300;
  white-space: nowrap;
}

.nav-process-block .nav-title {
  margin-bottom: 10px;
  font-size: 1rem;
}

@media (max-width: 767px) {
  .nav-process-block .nav-link {
    padding: 10px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
    -webkit-box-align: center;
            align-items: center;
  }
  .nav-process-block .nav-link-number {
    -webkit-transform: scale(0.6);
            transform: scale(0.6);
  }
  .nav-process-block .nav-link-body {
    text-align: center;
    margin-left: 0 !important;
    padding-left: 0;
    margin-top: 10px;
  }
}

.nav-process-sm.nav-process-circle .nav-item.complete .nav-link::after, .nav-process-sm.nav-process-circle .nav-item.processing .nav-link::after {
  width: 24px;
  height: 24px;
  line-height: 24px;
}

.nav-process-sm.nav-process-circle .nav-item.complete .nav-link::after {
  width: 24px;
  height: 24px;
}

.nav-process-sm.nav-process-circle .nav-item.processing .nav-link::after {
  width: 8px;
  height: 8px;
  margin-top: 8px;
  -webkit-transform: translateX(8px);
          transform: translateX(8px);
}

.nav-process-sm.nav-process-circle .nav-link {
  margin: 10px 0;
  width: 24px;
  height: 24px;
  max-height: 24px;
}

.nav-process-sm.nav-process-circle .nav-link::before {
  left: calc(-50% + 12px);
  right: calc(50% + 12px);
  height: 10px;
  margin-top: 7px;
}

.nav-process-sm.nav-process-circle .nav-link::after {
  font-size: 12px;
}

.nav-process-sm.nav-process-iconic .nav-link {
  font-size: 18px;
  width: 36px;
  height: 36px;
  line-height: 32px;
  max-height: 36px;
}

.nav-process-sm.nav-process-iconic .nav-link::before {
  left: calc(-50% + 18px);
  right: calc(50% + 18px);
  margin-top: 16.5px;
}

.nav-process-sm.nav-process-box .nav-link {
  padding-top: 8px;
  padding-bottom: 8px;
}

.nav-process-sm.nav-process-box .nav-link-number {
  max-width: 24px;
  width: 24px;
  height: 24px;
  line-height: 24px;
  font-size: 12px;
}

.nav-process-sm.nav-process-box .nav-link-number i {
  font-size: 10px;
}

.nav-process-sm.nav-process-box .nav-title {
  font-size: 12px;
}

.nav-process-sm.nav-process-block .nav-link {
  padding: 16px;
}

.nav-process-sm.nav-process-block .nav-link-number {
  max-width: 36px;
  width: 36px;
  height: 36px;
  line-height: 36px;
  font-size: 24px;
}

.nav-process-sm.nav-process-block .nav-link-body {
  line-height: 1rem;
}

.nav-process-sm.nav-process-block .nav-title {
  font-size: .9375rem;
}

.nav-process-lg.nav-process-circle .nav-item.complete .nav-link::after, .nav-process-lg.nav-process-circle .nav-item.processing .nav-link::after {
  width: 36px;
  height: 36px;
  line-height: 36px;
}

.nav-process-lg.nav-process-circle .nav-item.complete .nav-link::after {
  width: 36px;
  height: 36px;
}

.nav-process-lg.nav-process-circle .nav-item.processing .nav-link::after {
  width: 14px;
  height: 14px;
  margin-top: 11px;
  -webkit-transform: translateX(11px);
          transform: translateX(11px);
}

.nav-process-lg.nav-process-circle .nav-link {
  margin: 10px 0;
  width: 36px;
  height: 36px;
  max-height: 36px;
}

.nav-process-lg.nav-process-circle .nav-link::before {
  left: calc(-50% + 18px);
  right: calc(50% + 18px);
  height: 10px;
  margin-top: 13px;
}

.nav-process-lg.nav-process-circle .nav-link::after {
  font-size: 18px;
}

.nav-process-lg.nav-process-iconic .nav-link {
  font-size: 28px;
  width: 64px;
  height: 64px;
  line-height: 60px;
  max-height: 64px;
}

.nav-process-lg.nav-process-iconic .nav-link::before {
  left: calc(-50% + 32px);
  right: calc(50% + 32px);
  margin-top: 30.5px;
}

.nav-process-lg.nav-process-box .nav-link {
  padding-top: 16px;
  padding-bottom: 16px;
}

.nav-process-lg.nav-process-box .nav-link-number {
  max-width: 36px;
  width: 36px;
  height: 36px;
  line-height: 36px;
  font-size: 16px;
}

.nav-process-lg.nav-process-box .nav-link-number i {
  font-size: 12px;
}

.nav-process-lg.nav-process-box .nav-title {
  font-size: 14px;
}

.nav-process-lg.nav-process-block .nav-link {
  padding: 24px;
}

.nav-process-lg.nav-process-block .nav-link-number {
  max-width: 64px;
  width: 64px;
  height: 64px;
  line-height: 64px;
  font-size: 32px;
}

.nav-process-lg.nav-process-block .nav-link-body {
  line-height: 1.5rem;
}

.nav-process-lg.nav-process-block .nav-title {
  font-size: 1.125rem;
}

.nav-process-secondary.nav-process-circle .nav-item.complete .nav-link, .nav-process-secondary.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-secondary.nav-process-circle .nav-item.processing .nav-link,
.nav-process-secondary.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #f7fafc;
}

.nav-process-secondary.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-secondary.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #e4e7ea;
}

.nav-process-secondary.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-secondary.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #e4e7ea;
}

.nav-process-secondary.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #e4e7ea;
}

.nav-process-secondary.nav-process-iconic .nav-item.processing i {
  color: #e4e7ea;
}

.nav-process-secondary.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #e4e7ea;
}

.nav-process-secondary.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #e4e7ea;
}

.nav-process-secondary.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #e4e7ea;
}

.nav-process-secondary.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #e4e7ea;
}

.nav-process-secondary.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #e4e7ea;
}

.nav-process-gray.nav-process-circle .nav-item.complete .nav-link, .nav-process-gray.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-gray.nav-process-circle .nav-item.processing .nav-link,
.nav-process-gray.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #f2f2f2;
}

.nav-process-gray.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-gray.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #868e96;
}

.nav-process-gray.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-gray.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #868e96;
}

.nav-process-gray.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #868e96;
}

.nav-process-gray.nav-process-iconic .nav-item.processing i {
  color: #868e96;
}

.nav-process-gray.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #868e96;
}

.nav-process-gray.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #868e96;
}

.nav-process-gray.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #868e96;
}

.nav-process-gray.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #868e96;
}

.nav-process-gray.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #868e96;
}

.nav-process-dark.nav-process-circle .nav-item.complete .nav-link, .nav-process-dark.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-dark.nav-process-circle .nav-item.processing .nav-link,
.nav-process-dark.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #c8c8c8;
}

.nav-process-dark.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-dark.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #465161;
}

.nav-process-dark.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-dark.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #465161;
}

.nav-process-dark.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #465161;
}

.nav-process-dark.nav-process-iconic .nav-item.processing i {
  color: #465161;
}

.nav-process-dark.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #465161;
}

.nav-process-dark.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #465161;
}

.nav-process-dark.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #465161;
}

.nav-process-dark.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #465161;
}

.nav-process-dark.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #465161;
}

.nav-process-success.nav-process-circle .nav-item.complete .nav-link, .nav-process-success.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-success.nav-process-circle .nav-item.processing .nav-link,
.nav-process-success.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #e3fcf2;
}

.nav-process-success.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-success.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #15c377;
}

.nav-process-success.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-success.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #15c377;
}

.nav-process-success.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #15c377;
}

.nav-process-success.nav-process-iconic .nav-item.processing i {
  color: #15c377;
}

.nav-process-success.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #15c377;
}

.nav-process-success.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #15c377;
}

.nav-process-success.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #15c377;
}

.nav-process-success.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #15c377;
}

.nav-process-success.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #15c377;
}

.nav-process-info.nav-process-circle .nav-item.complete .nav-link, .nav-process-info.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-info.nav-process-circle .nav-item.processing .nav-link,
.nav-process-info.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #e3f3fc;
}

.nav-process-info.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-info.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #48b0f7;
}

.nav-process-info.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-info.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #48b0f7;
}

.nav-process-info.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #48b0f7;
}

.nav-process-info.nav-process-iconic .nav-item.processing i {
  color: #48b0f7;
}

.nav-process-info.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #48b0f7;
}

.nav-process-info.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #48b0f7;
}

.nav-process-info.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #48b0f7;
}

.nav-process-info.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #48b0f7;
}

.nav-process-info.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #48b0f7;
}

.nav-process-warning.nav-process-circle .nav-item.complete .nav-link, .nav-process-warning.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-warning.nav-process-circle .nav-item.processing .nav-link,
.nav-process-warning.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #fcf0e3;
}

.nav-process-warning.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-warning.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #faa64b;
}

.nav-process-warning.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-warning.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #faa64b;
}

.nav-process-warning.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #faa64b;
}

.nav-process-warning.nav-process-iconic .nav-item.processing i {
  color: #faa64b;
}

.nav-process-warning.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #faa64b;
}

.nav-process-warning.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #faa64b;
}

.nav-process-warning.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #faa64b;
}

.nav-process-warning.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #faa64b;
}

.nav-process-warning.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #faa64b;
}

.nav-process-danger.nav-process-circle .nav-item.complete .nav-link, .nav-process-danger.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-danger.nav-process-circle .nav-item.processing .nav-link,
.nav-process-danger.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #fce3e3;
}

.nav-process-danger.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-danger.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #f96868;
}

.nav-process-danger.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-danger.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #f96868;
}

.nav-process-danger.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #f96868;
}

.nav-process-danger.nav-process-iconic .nav-item.processing i {
  color: #f96868;
}

.nav-process-danger.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #f96868;
}

.nav-process-danger.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #f96868;
}

.nav-process-danger.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #f96868;
}

.nav-process-danger.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #f96868;
}

.nav-process-danger.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #f96868;
}

.nav-process-pink.nav-process-circle .nav-item.complete .nav-link, .nav-process-pink.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-pink.nav-process-circle .nav-item.processing .nav-link,
.nav-process-pink.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #fce3ec;
}

.nav-process-pink.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-pink.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #f96197;
}

.nav-process-pink.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-pink.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #f96197;
}

.nav-process-pink.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #f96197;
}

.nav-process-pink.nav-process-iconic .nav-item.processing i {
  color: #f96197;
}

.nav-process-pink.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #f96197;
}

.nav-process-pink.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #f96197;
}

.nav-process-pink.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #f96197;
}

.nav-process-pink.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #f96197;
}

.nav-process-pink.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #f96197;
}

.nav-process-purple.nav-process-circle .nav-item.complete .nav-link, .nav-process-purple.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-purple.nav-process-circle .nav-item.processing .nav-link,
.nav-process-purple.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #ece3fc;
}

.nav-process-purple.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-purple.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #926dde;
}

.nav-process-purple.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-purple.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #926dde;
}

.nav-process-purple.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #926dde;
}

.nav-process-purple.nav-process-iconic .nav-item.processing i {
  color: #926dde;
}

.nav-process-purple.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #926dde;
}

.nav-process-purple.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #926dde;
}

.nav-process-purple.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #926dde;
}

.nav-process-purple.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #926dde;
}

.nav-process-purple.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #926dde;
}

.nav-process-brown.nav-process-circle .nav-item.complete .nav-link, .nav-process-brown.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-brown.nav-process-circle .nav-item.processing .nav-link,
.nav-process-brown.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #eddcd5;
}

.nav-process-brown.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-brown.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #8d6658;
}

.nav-process-brown.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-brown.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #8d6658;
}

.nav-process-brown.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #8d6658;
}

.nav-process-brown.nav-process-iconic .nav-item.processing i {
  color: #8d6658;
}

.nav-process-brown.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #8d6658;
}

.nav-process-brown.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #8d6658;
}

.nav-process-brown.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #8d6658;
}

.nav-process-brown.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #8d6658;
}

.nav-process-brown.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #8d6658;
}

.nav-process-cyan.nav-process-circle .nav-item.complete .nav-link, .nav-process-cyan.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-cyan.nav-process-circle .nav-item.processing .nav-link,
.nav-process-cyan.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #e3fafc;
}

.nav-process-cyan.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-cyan.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #57c7d4;
}

.nav-process-cyan.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-cyan.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #57c7d4;
}

.nav-process-cyan.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #57c7d4;
}

.nav-process-cyan.nav-process-iconic .nav-item.processing i {
  color: #57c7d4;
}

.nav-process-cyan.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #57c7d4;
}

.nav-process-cyan.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #57c7d4;
}

.nav-process-cyan.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #57c7d4;
}

.nav-process-cyan.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #57c7d4;
}

.nav-process-cyan.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #57c7d4;
}

.nav-process-yellow.nav-process-circle .nav-item.complete .nav-link, .nav-process-yellow.nav-process-circle .nav-item.complete .nav-link::before,
.nav-process-yellow.nav-process-circle .nav-item.processing .nav-link,
.nav-process-yellow.nav-process-circle .nav-item.processing .nav-link::before {
  background-color: #fcf8e3;
}

.nav-process-yellow.nav-process-circle .nav-item.complete .nav-link::after,
.nav-process-yellow.nav-process-circle .nav-item.processing .nav-link::after {
  background-color: #fcc525;
}

.nav-process-yellow.nav-process-iconic .nav-item.complete .nav-link::before, .nav-process-yellow.nav-process-iconic .nav-item.processing .nav-link::before {
  background-color: #fcc525;
}

.nav-process-yellow.nav-process-iconic .nav-item.complete .nav-link {
  background-color: #fcc525;
}

.nav-process-yellow.nav-process-iconic .nav-item.processing i {
  color: #fcc525;
}

.nav-process-yellow.nav-process-iconic .nav-item.processing .nav-link {
  border-color: #fcc525;
}

.nav-process-yellow.nav-process-box .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  background-color: #fcc525;
}

.nav-process-yellow.nav-process-block .nav-item.complete:not(.processing) .nav-link {
  background-color: #fcc525;
}

.nav-process-yellow.nav-process-block .nav-item.complete:not(.processing) .nav-link .nav-link-number {
  color: #fcc525;
}

.nav-process-yellow.nav-process-block .nav-item.processing .nav-link-number {
  background-color: #fcc525;
}

.nav-process-secondary.nav-process-circle .nav-item.complete .nav-link::after {
  color: #8b95a5;
}

.nav-process-secondary.nav-process-iconic .nav-item .nav-link {
  color: #e4e7ea;
}

.nav-process-secondary.nav-process-iconic .nav-item.complete .nav-link {
  color: #8b95a5;
}

.nav-process-secondary.nav-process-block .nav-item.complete .nav-link {
  color: #8b95a5;
}

.nav-process-secondary.nav-process-block .nav-item.processing .nav-link-number {
  color: #8b95a5;
}

.progress {
  height: auto;
  border-radius: 2px;
  margin-bottom: 8px;
  background-color: #f5f6f7;
}

.progress-bar {
  height: 5px;
  background-color: #33cabb;
}

progress {
  width: 100%;
}

.progress-xs {
  height: 3px;
}

.progress-sm {
  height: 4px;
}

.progress-lg {
  height: 6px;
}

.progress-xl {
  height: 7px;
}

.progress[value] {
  color: #f9fafb;
}

.progress[value]::-webkit-progress-bar {
  background-color: #f9fafb;
  border-radius: 0;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.progress[value]::-webkit-progress-value {
  background-color: #33cabb;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.progress[value="100"]::-webkit-progress-value {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.progress-primary[value]::-webkit-progress-value {
  background-color: #33cabb;
}

.progress-primary[value]::-moz-progress-bar {
  background-color: #33cabb;
}

.progress-secondary[value]::-webkit-progress-value {
  background-color: #e4e7ea;
}

.progress-secondary[value]::-moz-progress-bar {
  background-color: #e4e7ea;
}

.progress-success[value]::-webkit-progress-value {
  background-color: #15c377;
}

.progress-success[value]::-moz-progress-bar {
  background-color: #15c377;
}

.progress-info[value]::-webkit-progress-value {
  background-color: #48b0f7;
}

.progress-info[value]::-moz-progress-bar {
  background-color: #48b0f7;
}

.progress-warning[value]::-webkit-progress-value {
  background-color: #faa64b;
}

.progress-warning[value]::-moz-progress-bar {
  background-color: #faa64b;
}

.progress-danger[value]::-webkit-progress-value {
  background-color: #f96868;
}

.progress-danger[value]::-moz-progress-bar {
  background-color: #f96868;
}

.progress-pink[value]::-webkit-progress-value {
  background-color: #f96197;
}

.progress-pink[value]::-moz-progress-bar {
  background-color: #f96197;
}

.progress-purple[value]::-webkit-progress-value {
  background-color: #926dde;
}

.progress-purple[value]::-moz-progress-bar {
  background-color: #926dde;
}

.progress-brown[value]::-webkit-progress-value {
  background-color: #8d6658;
}

.progress-brown[value]::-moz-progress-bar {
  background-color: #8d6658;
}

.progress-cyan[value]::-webkit-progress-value {
  background-color: #57c7d4;
}

.progress-cyan[value]::-moz-progress-bar {
  background-color: #57c7d4;
}

.progress-yellow[value]::-webkit-progress-value {
  background-color: #fcc525;
}

.progress-yellow[value]::-moz-progress-bar {
  background-color: #fcc525;
}

.progress-gray[value]::-webkit-progress-value {
  background-color: #868e96;
}

.progress-gray[value]::-moz-progress-bar {
  background-color: #868e96;
}

.progress-dark[value]::-webkit-progress-value {
  background-color: #465161;
}

.progress-dark[value]::-moz-progress-bar {
  background-color: #465161;
}

.spinner-linear {
  position: relative;
  height: 2px;
  display: block;
  width: 100%;
  background-color: #f5f6f7;
  border-radius: 2px;
  -webkit-background-clip: padding-box;
          background-clip: padding-box;
  overflow: hidden;
}

.spinner-linear .line {
  background-color: #33cabb;
}

.spinner-linear .line::before, .spinner-linear .line::after {
  content: '';
  position: absolute;
  background-color: inherit;
  top: 0;
  left: 0;
  bottom: 0;
  will-change: left, right;
}

.spinner-linear .line::before {
  -webkit-animation: spinner-linear 2.1s cubic-bezier(0.65, 0.815, 0.735, 0.395) infinite;
          animation: spinner-linear 2.1s cubic-bezier(0.65, 0.815, 0.735, 0.395) infinite;
}

.spinner-linear .line::after {
  -webkit-animation: spinner-linear-short 2.1s cubic-bezier(0.165, 0.84, 0.44, 1) infinite;
          animation: spinner-linear-short 2.1s cubic-bezier(0.165, 0.84, 0.44, 1) infinite;
  -webkit-animation-delay: 1.15s;
          animation-delay: 1.15s;
}

@-webkit-keyframes spinner-linear {
  0% {
    left: -35%;
    right: 100%;
  }
  60% {
    left: 100%;
    right: -90%;
  }
  100% {
    left: 100%;
    right: -90%;
  }
}

@keyframes spinner-linear {
  0% {
    left: -35%;
    right: 100%;
  }
  60% {
    left: 100%;
    right: -90%;
  }
  100% {
    left: 100%;
    right: -90%;
  }
}

@-webkit-keyframes spinner-linear-short {
  0% {
    left: -200%;
    right: 100%;
  }
  60% {
    left: 107%;
    right: -8%;
  }
  100% {
    left: 107%;
    right: -8%;
  }
}

@keyframes spinner-linear-short {
  0% {
    left: -200%;
    right: 100%;
  }
  60% {
    left: 107%;
    right: -8%;
  }
  100% {
    left: 107%;
    right: -8%;
  }
}

.spinner-dots {
  width: 70px;
  text-align: center;
}

.spinner-dots span {
  width: 12px;
  height: 12px;
  background-color: #33cabb;
  border-radius: 100%;
  display: inline-block;
  -webkit-animation: spinner-dots 1.4s infinite ease-in-out both;
          animation: spinner-dots 1.4s infinite ease-in-out both;
}

.spinner-dots .dot1 {
  -webkit-animation-delay: -0.32s;
          animation-delay: -0.32s;
}

.spinner-dots .dot2 {
  -webkit-animation-delay: -0.16s;
          animation-delay: -0.16s;
}

@-webkit-keyframes spinner-dots {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
    transform: scale(0);
  }
  40% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}

@keyframes spinner-dots {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
    transform: scale(0);
  }
  40% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}

.spinner-ball {
  width: 50px;
  height: 50px;
  -webkit-animation: spinner-ball infinite linear 1s;
          animation: spinner-ball infinite linear 1s;
  border-radius: 100%;
  background-color: #33cabb;
}

@-webkit-keyframes spinner-ball {
  0% {
    -webkit-transform: scale(0.1);
            transform: scale(0.1);
    opacity: 1;
  }
  100% {
    -webkit-transform: scale(1);
            transform: scale(1);
    opacity: 0;
  }
}

@keyframes spinner-ball {
  0% {
    -webkit-transform: scale(0.1);
            transform: scale(0.1);
    opacity: 1;
  }
  100% {
    -webkit-transform: scale(1);
            transform: scale(1);
    opacity: 0;
  }
}

.spinner-circle {
  width: 25px;
  height: 25px;
  -webkit-animation: spinner-circle infinite .75s linear;
          animation: spinner-circle infinite .75s linear;
  border: 1px solid #33cabb;
  border-top-color: transparent;
  border-radius: 100%;
}

.spinner-circle-shadow {
  position: relative;
  width: 25px;
  height: 25px;
  -webkit-animation: spinner-circle infinite .75s linear;
          animation: spinner-circle infinite .75s linear;
  border: 1px solid rgba(51, 202, 187, 0.3);
  border-left-color: #33cabb;
  border-radius: 100%;
}

@-webkit-keyframes spinner-circle {
  0% {
    -webkit-transform: rotate(0);
            transform: rotate(0);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

@keyframes spinner-circle {
  0% {
    -webkit-transform: rotate(0);
            transform: rotate(0);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

.spinner-circle-material-svg {
  -webkit-animation: spinner-svg-spinner 1.5s linear infinite;
          animation: spinner-svg-spinner 1.5s linear infinite;
  height: 54px;
  width: 54px;
}

.spinner-circle-material-svg .circle {
  -webkit-animation: spinner-svg-progress 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite, spinner-svg-colors 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
          animation: spinner-svg-progress 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite, spinner-svg-colors 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
  fill: none;
  stroke: #db3236;
  stroke-linecap: round;
  stroke-width: 2;
}

@-webkit-keyframes spinner-svg-spinner {
  0% {
    -webkit-transform: rotate(-110deg);
            transform: rotate(-110deg);
  }
  20% {
    -webkit-transform: rotate(-70deg);
            transform: rotate(-70deg);
  }
  60% {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  100% {
    -webkit-transform: rotate(250deg);
            transform: rotate(250deg);
  }
}

@keyframes spinner-svg-spinner {
  0% {
    -webkit-transform: rotate(-110deg);
            transform: rotate(-110deg);
  }
  20% {
    -webkit-transform: rotate(-70deg);
            transform: rotate(-70deg);
  }
  60% {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  100% {
    -webkit-transform: rotate(250deg);
            transform: rotate(250deg);
  }
}

@-webkit-keyframes spinner-svg-progress {
  0% {
    stroke-dasharray: 1, 150;
    stroke-dashoffset: 0;
  }
  20% {
    stroke-dasharray: 1, 150;
    stroke-dash-offset: 0;
  }
  60% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -35;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -124;
  }
}

@keyframes spinner-svg-progress {
  0% {
    stroke-dasharray: 1, 150;
    stroke-dashoffset: 0;
  }
  20% {
    stroke-dasharray: 1, 150;
    stroke-dash-offset: 0;
  }
  60% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -35;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -124;
  }
}

@-webkit-keyframes spinner-svg-colors {
  0% {
    stroke: #db3236;
  }
  23% {
    stroke: #db3236;
  }
  27% {
    stroke: #4885ed;
  }
  48% {
    stroke: #4885ed;
  }
  52% {
    stroke: #3cba54;
  }
  73% {
    stroke: #3cba54;
  }
  77% {
    stroke: #f4c20d;
  }
  98% {
    stroke: #f4c20d;
  }
}

@keyframes spinner-svg-colors {
  0% {
    stroke: #db3236;
  }
  23% {
    stroke: #db3236;
  }
  27% {
    stroke: #4885ed;
  }
  48% {
    stroke: #4885ed;
  }
  52% {
    stroke: #3cba54;
  }
  73% {
    stroke: #3cba54;
  }
  77% {
    stroke: #f4c20d;
  }
  98% {
    stroke: #f4c20d;
  }
}

.spinner-circle-material {
  height: 50px;
  min-height: 50px;
  width: 50px;
  border-radius: 100px;
  border: 2px transparent solid;
  border-top: 2px #3F51B5 solid;
  -webkit-animation: spinner-material 4s infinite;
          animation: spinner-material 4s infinite;
}

@-webkit-keyframes spinner-material {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
    border-top-color: #3F51B5;
  }
  25% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
    border-top-color: #F44336;
  }
  50% {
    -webkit-transform: rotate(720deg);
            transform: rotate(720deg);
    border-top-color: #FFC107;
  }
  75% {
    -webkit-transform: rotate(1080deg);
            transform: rotate(1080deg);
    border-top-color: #4CAF50;
  }
  100% {
    -webkit-transform: rotate(1440deg);
            transform: rotate(1440deg);
    border-top-color: #3F51B5;
  }
}

@keyframes spinner-material {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
    border-top-color: #3F51B5;
  }
  25% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
    border-top-color: #F44336;
  }
  50% {
    -webkit-transform: rotate(720deg);
            transform: rotate(720deg);
    border-top-color: #FFC107;
  }
  75% {
    -webkit-transform: rotate(1080deg);
            transform: rotate(1080deg);
    border-top-color: #4CAF50;
  }
  100% {
    -webkit-transform: rotate(1440deg);
            transform: rotate(1440deg);
    border-top-color: #3F51B5;
  }
}

.spinner-primary.spinner-linear .line,
.spinner-primary.spinner-dots span, .spinner-primary.spinner-ball {
  background-color: #33cabb;
}

.spinner-primary.spinner-circle {
  border-color: #33cabb;
  border-top-color: transparent;
}

.spinner-primary.spinner-circle-shadow {
  border-color: rgba(51, 202, 187, 0.3);
  border-left-color: #33cabb;
}

.spinner-secondary.spinner-linear .line,
.spinner-secondary.spinner-dots span, .spinner-secondary.spinner-ball {
  background-color: #e4e7ea;
}

.spinner-secondary.spinner-circle {
  border-color: #e4e7ea;
  border-top-color: transparent;
}

.spinner-secondary.spinner-circle-shadow {
  border-color: rgba(228, 231, 234, 0.3);
  border-left-color: #e4e7ea;
}

.spinner-success.spinner-linear .line,
.spinner-success.spinner-dots span, .spinner-success.spinner-ball {
  background-color: #15c377;
}

.spinner-success.spinner-circle {
  border-color: #15c377;
  border-top-color: transparent;
}

.spinner-success.spinner-circle-shadow {
  border-color: rgba(21, 195, 119, 0.3);
  border-left-color: #15c377;
}

.spinner-info.spinner-linear .line,
.spinner-info.spinner-dots span, .spinner-info.spinner-ball {
  background-color: #48b0f7;
}

.spinner-info.spinner-circle {
  border-color: #48b0f7;
  border-top-color: transparent;
}

.spinner-info.spinner-circle-shadow {
  border-color: rgba(72, 176, 247, 0.3);
  border-left-color: #48b0f7;
}

.spinner-warning.spinner-linear .line,
.spinner-warning.spinner-dots span, .spinner-warning.spinner-ball {
  background-color: #faa64b;
}

.spinner-warning.spinner-circle {
  border-color: #faa64b;
  border-top-color: transparent;
}

.spinner-warning.spinner-circle-shadow {
  border-color: rgba(250, 166, 75, 0.3);
  border-left-color: #faa64b;
}

.spinner-danger.spinner-linear .line,
.spinner-danger.spinner-dots span, .spinner-danger.spinner-ball {
  background-color: #f96868;
}

.spinner-danger.spinner-circle {
  border-color: #f96868;
  border-top-color: transparent;
}

.spinner-danger.spinner-circle-shadow {
  border-color: rgba(249, 104, 104, 0.3);
  border-left-color: #f96868;
}

.spinner-pink.spinner-linear .line,
.spinner-pink.spinner-dots span, .spinner-pink.spinner-ball {
  background-color: #f96197;
}

.spinner-pink.spinner-circle {
  border-color: #f96197;
  border-top-color: transparent;
}

.spinner-pink.spinner-circle-shadow {
  border-color: rgba(249, 97, 151, 0.3);
  border-left-color: #f96197;
}

.spinner-purple.spinner-linear .line,
.spinner-purple.spinner-dots span, .spinner-purple.spinner-ball {
  background-color: #926dde;
}

.spinner-purple.spinner-circle {
  border-color: #926dde;
  border-top-color: transparent;
}

.spinner-purple.spinner-circle-shadow {
  border-color: rgba(146, 109, 222, 0.3);
  border-left-color: #926dde;
}

.spinner-brown.spinner-linear .line,
.spinner-brown.spinner-dots span, .spinner-brown.spinner-ball {
  background-color: #8d6658;
}

.spinner-brown.spinner-circle {
  border-color: #8d6658;
  border-top-color: transparent;
}

.spinner-brown.spinner-circle-shadow {
  border-color: rgba(141, 102, 88, 0.3);
  border-left-color: #8d6658;
}

.spinner-cyan.spinner-linear .line,
.spinner-cyan.spinner-dots span, .spinner-cyan.spinner-ball {
  background-color: #57c7d4;
}

.spinner-cyan.spinner-circle {
  border-color: #57c7d4;
  border-top-color: transparent;
}

.spinner-cyan.spinner-circle-shadow {
  border-color: rgba(87, 199, 212, 0.3);
  border-left-color: #57c7d4;
}

.spinner-yellow.spinner-linear .line,
.spinner-yellow.spinner-dots span, .spinner-yellow.spinner-ball {
  background-color: #fcc525;
}

.spinner-yellow.spinner-circle {
  border-color: #fcc525;
  border-top-color: transparent;
}

.spinner-yellow.spinner-circle-shadow {
  border-color: rgba(252, 197, 37, 0.3);
  border-left-color: #fcc525;
}

.spinner-gray.spinner-linear .line,
.spinner-gray.spinner-dots span, .spinner-gray.spinner-ball {
  background-color: #868e96;
}

.spinner-gray.spinner-circle {
  border-color: #868e96;
  border-top-color: transparent;
}

.spinner-gray.spinner-circle-shadow {
  border-color: rgba(134, 142, 150, 0.3);
  border-left-color: #868e96;
}

.spinner-dark.spinner-linear .line,
.spinner-dark.spinner-dots span, .spinner-dark.spinner-ball {
  background-color: #465161;
}

.spinner-dark.spinner-circle {
  border-color: #465161;
  border-top-color: transparent;
}

.spinner-dark.spinner-circle-shadow {
  border-color: rgba(70, 81, 97, 0.3);
  border-left-color: #465161;
}

.dock-list {
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
  -webkit-box-align: end;
          align-items: flex-end;
  padding-right: 22px;
  height: 0;
  position: fixed;
  right: 0;
  bottom: 0;
  z-index: 994;
  max-width: calc(100% - 30px);
}

.dock-list.maximize {
  z-index: 999;
}

@media (max-width: 767px) {
  .dock-list {
    padding-right: 7px;
    max-width: calc(100% - 23px);
  }
}

.modal-open .dock-list {
  z-index: 1050;
}

.modal-open .dock-list .dock {
  border-color: transparent;
  -webkit-transition: border-color 0s;
  transition: border-color 0s;
}

.modal-open .dock-list .modal.in {
  background-color: rgba(0, 0, 0, 0.3);
}

.dock {
  border: 1px solid #f1f2f3;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
  -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
          box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
  margin: 0 8px;
  -webkit-transition: .3s;
  transition: .3s;
  display: none;
  max-width: 100%;
}

.dock:hover {
  -webkit-box-shadow: 0 1px 30px rgba(0, 0, 0, 0.06);
          box-shadow: 0 1px 30px rgba(0, 0, 0, 0.06);
}

.dock.reveal {
  display: block;
}

.dock.shake:not(.minimize),
.dock.shake.minimize .dock-header {
  -webkit-animation: h-shake 0.82s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
          animation: h-shake 0.82s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-perspective: 1000px;
          perspective: 1000px;
}

.dock.blink .dock-header {
  background-color: #e3f3fc;
}

.dock.minimize {
  -webkit-transform: translateY(calc(100% - 44px));
          transform: translateY(calc(100% - 44px));
}

.dock.minimize .dock-header {
  cursor: pointer;
}

.dock.minimize .dock-body {
  width: 0;
  min-width: 0;
}

.dock.minimize .dock-actions {
  margin-left: 20px;
}

.dock.minimize .dock-actions > * {
  display: none;
}

.dock.minimize .dock-actions [data-dock="close"] {
  display: inline-block;
  opacity: 0;
}

.dock.minimize:hover .dock-actions [data-dock="close"] {
  opacity: 1;
}

.dock.maximize {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin-left: 0;
  margin-right: 0;
  z-index: 999;
}

.dock.maximize .dock-body {
  width: 100%;
  height: 100%;
}

.dock-header {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  padding: 6px 12px;
  height: 44px;
  background-color: #f9fafb;
  border-bottom: 1px solid #f1f2f3;
  -webkit-transition: .3s;
  transition: .3s;
}

.dock-title {
  display: -webkit-box;
  display: flex;
  font-weight: 300;
  font-size: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-left: 8px;
  margin: -8px;
}

.dock-title > * {
  margin: 8px;
}

@media (max-width: 767px) {
  .dock-title {
    margin: -4px;
  }
  .dock-title > * {
    margin: 4px;
  }
}

.dock-actions {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
  position: relative;
  z-index: 9;
  color: #8b95a5;
  margin: -8px;
}

.dock-actions > * {
  margin: 8px;
}

@media (max-width: 767px) {
  .dock-actions {
    margin: -4px;
  }
  .dock-actions > * {
    margin: 4px;
  }
}

.dock-actions > *:not(.dropdown),
.dock-actions > .dropdown .dropdown-toggle {
  min-width: 20px;
  height: 44px;
  text-align: center;
  opacity: .7;
  cursor: pointer;
  -webkit-transition: .2s;
  transition: .2s;
}

.dock-actions > *:not(.dropdown):hover,
.dock-actions > .dropdown .dropdown-toggle:hover {
  opacity: 1;
}

.dock-actions > *:not(.dropdown),
.dock-actions .dropdown-toggle {
  line-height: 44px;
}

.dock-actions [data-dock="close"]::before,
.dock-actions [data-dock="maximize"]::before,
.dock-actions [data-dock="minimize"]::before {
  font-family: themify;
}

.dock-actions [data-dock="close"]::before {
  content: "\e646";
}

.dock-actions [data-dock="maximize"]::before {
  content: "\e6be";
  display: inline-block;
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg);
}

.dock-actions [data-dock="minimize"]::before {
  content: "\e622";
  vertical-align: sub;
}

.dock-header-inverse .dock-info {
  color: #fff;
}

.dock-header-inverse .dock-actions {
  color: #fff;
}

.dock-body {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  background-color: #fff;
  min-height: 300px;
  min-width: 240px;
  max-width: 100%;
  width: 400px;
  height: 400px;
}

.dock-block {
  padding: 20px;
}

.dock-xs .dock-body {
  width: 260px;
  height: 300px;
}

.dock-sm .dock-body {
  width: 340px;
  height: 340px;
}

.dock-lg .dock-body {
  width: 480px;
  height: 480px;
}

.dock-xl .dock-body {
  width: 560px;
  height: 560px;
}

.dock-footer {
  padding: 12px;
  background-color: #fff;
}

@-webkit-keyframes h-shake {
  10%, 90% {
    -webkit-transform: translate3d(-1px, 0, 0);
            transform: translate3d(-1px, 0, 0);
  }
  20%, 80% {
    -webkit-transform: translate3d(2px, 0, 0);
            transform: translate3d(2px, 0, 0);
  }
  30%, 50%, 70% {
    -webkit-transform: translate3d(-4px, 0, 0);
            transform: translate3d(-4px, 0, 0);
  }
  40%, 60% {
    -webkit-transform: translate3d(4px, 0, 0);
            transform: translate3d(4px, 0, 0);
  }
}

@keyframes h-shake {
  10%, 90% {
    -webkit-transform: translate3d(-1px, 0, 0);
            transform: translate3d(-1px, 0, 0);
  }
  20%, 80% {
    -webkit-transform: translate3d(2px, 0, 0);
            transform: translate3d(2px, 0, 0);
  }
  30%, 50%, 70% {
    -webkit-transform: translate3d(-4px, 0, 0);
            transform: translate3d(-4px, 0, 0);
  }
  40%, 60% {
    -webkit-transform: translate3d(4px, 0, 0);
            transform: translate3d(4px, 0, 0);
  }
}

@-webkit-keyframes dock-show {
  0% {
    display: none;
    opacity: 0;
  }
  1% {
    display: block;
    opacity: 0;
    -webkit-transform-origin: right bottom 0;
            transform-origin: right bottom 0;
    -webkit-transform: scale(0, 0);
            transform: scale(0, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
}

@keyframes dock-show {
  0% {
    display: none;
    opacity: 0;
  }
  1% {
    display: block;
    opacity: 0;
    -webkit-transform-origin: right bottom 0;
            transform-origin: right bottom 0;
    -webkit-transform: scale(0, 0);
            transform: scale(0, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
}

@-webkit-keyframes dock-w {
  from {
  }
  to {
    max-width: 100%;
    max-height: 100%;
    height: auto;
    margin: 0 8px;
  }
}

@keyframes dock-w {
  from {
  }
  to {
    max-width: 100%;
    max-height: 100%;
    height: auto;
    margin: 0 8px;
  }
}

.modal-content {
  border-radius: 3px;
  border: none;
}

.modal-header {
  border-bottom-color: #f1f2f3;
}

.modal-header.no-border {
  margin-bottom: 1rem;
}

.modal-title {
  font-family: Roboto, sans-serif;
  font-weight: 400;
  letter-spacing: .5px;
}

.modal-footer {
  border: none;
  padding-top: 0.75rem;
  padding-bottom: 0.75rem;
}

.modal[data-backdrop="false"]:not(.modal-fill) .modal-content {
  border: 1px solid #f1f2f3;
  -webkit-box-shadow: 0 1px 10px rgba(0, 0, 0, 0.06);
          box-shadow: 0 1px 10px rgba(0, 0, 0, 0.06);
}

.modal-top {
  opacity: 1;
}

.modal-top.show .modal-dialog {
  -webkit-transform: translateY(0) !important;
          transform: translateY(0) !important;
}

.modal-top .modal-dialog {
  margin-top: 0;
  -webkit-transform: translateY(-100%) !important;
          transform: translateY(-100%) !important;
}

.modal-top .modal-content {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.modal-center {
  -webkit-transform: scale(0);
          transform: scale(0);
  -webkit-transition: .4s;
  transition: .4s;
}

.modal-center.show {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.modal-center .modal-dialog {
  margin: 0;
  width: 100%;
  position: absolute;
  bottom: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, 50%) !important;
          transform: translate(-50%, 50%) !important;
}

.modal-bottom {
  opacity: 1;
  overflow-y: hidden !important;
}

.modal-bottom.show .modal-dialog {
  -webkit-transform: translate(-50%, 0) !important;
          transform: translate(-50%, 0) !important;
}

.modal-bottom .modal-dialog {
  margin: 0;
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 100%;
  -webkit-transform: translate(-50%, 100%) !important;
          transform: translate(-50%, 100%) !important;
  -webkit-transition: .4s;
  transition: .4s;
}

.modal-bottom .modal-content {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.modal-left {
  opacity: 1;
}

.modal-left.show .modal-dialog {
  -webkit-transform: translate(0, 0) !important;
          transform: translate(0, 0) !important;
}

.modal-left .modal-dialog {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 360px;
  max-width: 100%;
  margin: 0;
  -webkit-transform: translate(-100%, 0) !important;
          transform: translate(-100%, 0) !important;
  -webkit-transition: .5s;
  transition: .5s;
}

.modal-left .modal-content {
  height: 100%;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  border-radius: 0;
}

.modal-left .modal-body {
  -webkit-box-flex: 1;
          flex-grow: 1;
}

.modal-right {
  opacity: 1;
}

.modal-right.show .modal-dialog {
  -webkit-transform: translate(0, 0) !important;
          transform: translate(0, 0) !important;
}

.modal-right .modal-dialog {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 360px;
  max-width: 100%;
  margin: 0;
  -webkit-transform: translate(100%, 0) !important;
          transform: translate(100%, 0) !important;
  -webkit-transition: .5s;
  transition: .5s;
}

.modal-right .modal-content {
  height: 100%;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  border-radius: 0;
}

.modal-right .modal-body {
  -webkit-box-flex: 1;
          flex-grow: 1;
}

.modal-fill {
  background: rgba(255, 255, 255, 0.97);
  -webkit-transform: scale(0, 0);
          transform: scale(0, 0);
  -webkit-transition: .4s;
  transition: .4s;
}

.modal-fill.show {
  display: -webkit-box !important;
  display: flex !important;
  -webkit-box-pack: center;
          justify-content: center;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-flow: column nowrap;
  -webkit-transform: scale(1, 1);
          transform: scale(1, 1);
  -webkit-transition: .4s;
  transition: .4s;
}

.modal-fill .modal-dialog {
  display: -webkit-box;
  display: flex;
  max-width: 100%;
  width: 100%;
  height: 100%;
  margin: 0;
  -webkit-box-pack: center;
          justify-content: center;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-flow: column nowrap;
  align-content: center;
  -webkit-box-align: center;
          align-items: center;
}

.modal-fill .modal-content {
  background: transparent;
  width: 100%;
  max-width: 600px;
}

.modal-fill .modal-header {
  border-bottom: none;
}

.modal-fill .modal-header .close {
  position: fixed;
  top: 0;
  right: 0;
  padding: 20px;
  font-size: 2.5rem;
  font-weight: 300;
}

@media (min-width: 576px) {
  .modal-sm {
    width: 360px;
    max-width: 360px;
  }
}

.btn {
  font-size: 14px;
  padding: 5px 16px;
  line-height: inherit;
  color: #8b95a5;
  letter-spacing: 1px;
  border-radius: 2px;
  background-color: #fff;
  border-color: #ebebeb;
  outline: none !important;
  -webkit-transition: 0.15s linear;
  transition: 0.15s linear;
}

.btn:hover {
  cursor: pointer;
}

.btn:focus, .btn.focus, .btn:active, .btn.active {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-bold {
  font-family: Roboto, sans-serif;
  text-transform: uppercase;
  font-weight: 500;
  font-size: 12px;
}

.btn-group-xs > .btn,
.btn-xs {
  font-size: 11px;
  padding: 2px 8px;
  line-height: 18px;
}

.btn-group-xs > .btn.btn-bold,
.btn-xs.btn-bold {
  font-size: 9px;
}

.btn-group-sm > .btn,
.btn-sm {
  font-size: 12px;
  padding: 4px 12px;
  line-height: 20px;
}

.btn-group-sm > .btn.btn-bold,
.btn-sm.btn-bold {
  font-size: 11px;
}

.btn-group-lg > .btn,
.btn-lg {
  font-size: 15px;
  padding: 7px 20px;
  line-height: 32px;
}

.btn-group-lg > .btn.btn-bold,
.btn-lg.btn-bold {
  font-size: 14px;
}

.btn-w-xs {
  width: 85px;
}

.btn-w-sm {
  width: 100px;
}

.btn-w-md {
  width: 120px;
}

.btn-w-lg {
  width: 145px;
}

.btn-w-xl {
  width: 180px;
}

.btn-round {
  border-radius: 10rem;
}

.btn-primary {
  background-color: #33cabb;
  border-color: #33cabb;
  color: #fff;
}

.btn-primary:hover {
  background-color: #52d3c7;
  border-color: #52d3c7;
  color: #fff;
}

.btn-primary:focus, .btn-primary.focus {
  color: #fff;
}

.btn-primary.disabled, .btn-primary:disabled {
  background-color: #33cabb;
  border-color: #33cabb;
  opacity: 0.5;
}

.btn-primary:not([disabled]):not(.disabled).active, .btn-primary:not([disabled]):not(.disabled):active,
.show > .btn-primary.dropdown-toggle {
  background-color: #2ba99d;
  border-color: #2ba99d;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-success {
  background-color: #15c377;
  border-color: #15c377;
  color: #fff;
}

.btn-success:hover {
  background-color: #16d17f;
  border-color: #16d17f;
  color: #fff;
}

.btn-success:focus, .btn-success.focus {
  color: #fff;
}

.btn-success.disabled, .btn-success:disabled {
  background-color: #15c377;
  border-color: #15c377;
  opacity: 0.5;
}

.btn-success:not([disabled]):not(.disabled).active, .btn-success:not([disabled]):not(.disabled):active,
.show > .btn-success.dropdown-toggle {
  background-color: #14b56f;
  border-color: #14b56f;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-info {
  background-color: #48b0f7;
  border-color: #48b0f7;
  color: #fff;
}

.btn-info:hover {
  background-color: #65bdf8;
  border-color: #65bdf8;
  color: #fff;
}

.btn-info:focus, .btn-info.focus {
  color: #fff;
}

.btn-info.disabled, .btn-info:disabled {
  background-color: #48b0f7;
  border-color: #48b0f7;
  opacity: 0.5;
}

.btn-info:not([disabled]):not(.disabled).active, .btn-info:not([disabled]):not(.disabled):active,
.show > .btn-info.dropdown-toggle {
  background-color: #2ba3f6;
  border-color: #2ba3f6;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-warning {
  background-color: #faa64b;
  border-color: #faa64b;
  color: #fff;
}

.btn-warning:hover {
  background-color: #fbb264;
  border-color: #fbb264;
  color: #fff;
}

.btn-warning:focus, .btn-warning.focus {
  color: #fff;
}

.btn-warning.disabled, .btn-warning:disabled {
  background-color: #faa64b;
  border-color: #faa64b;
  opacity: 0.5;
}

.btn-warning:not([disabled]):not(.disabled).active, .btn-warning:not([disabled]):not(.disabled):active,
.show > .btn-warning.dropdown-toggle {
  background-color: #f99a32;
  border-color: #f99a32;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-danger {
  background-color: #f96868;
  border-color: #f96868;
  color: #fff;
}

.btn-danger:hover {
  background-color: #fa8181;
  border-color: #fa8181;
  color: #fff;
}

.btn-danger:focus, .btn-danger.focus {
  color: #fff;
}

.btn-danger.disabled, .btn-danger:disabled {
  background-color: #f96868;
  border-color: #f96868;
  opacity: 0.5;
}

.btn-danger:not([disabled]):not(.disabled).active, .btn-danger:not([disabled]):not(.disabled):active,
.show > .btn-danger.dropdown-toggle {
  background-color: #f84f4f;
  border-color: #f84f4f;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-secondary {
  color: #4d5259 !important;
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #fff;
}

.btn-secondary:hover {
  background-color: #edeff1;
  border-color: #edeff1;
  color: #fff;
}

.btn-secondary:focus, .btn-secondary.focus {
  color: #fff;
}

.btn-secondary.disabled, .btn-secondary:disabled {
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  opacity: 0.5;
}

.btn-secondary:not([disabled]):not(.disabled).active, .btn-secondary:not([disabled]):not(.disabled):active,
.show > .btn-secondary.dropdown-toggle {
  background-color: #dbdfe3;
  border-color: #dbdfe3;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-link {
  color: #48b0f7;
  font-weight: 500;
}

.btn-link:hover, .btn-link:focus {
  text-decoration: none;
  color: #e4e7ea;
}

.btn-light {
  background-color: #fcfdfe;
  border-color: #ebebeb;
  color: #8b95a5;
}

.btn-light:hover, .btn-light:focus {
  background-color: #f9fafb;
  color: #4d5259;
}

.btn-light:active, .btn-light.active,
.show > .btn-light.dropdown-toggle {
  background-color: #f9fafb;
  color: #4d5259;
}

.btn-purple {
  background-color: #926dde;
  border-color: #926dde;
  color: #fff;
}

.btn-purple:hover {
  background-color: #a282e3;
  border-color: #a282e3;
  color: #fff;
}

.btn-purple:focus, .btn-purple.focus {
  color: #fff;
}

.btn-purple.disabled, .btn-purple:disabled {
  background-color: #926dde;
  border-color: #926dde;
  opacity: 0.5;
}

.btn-purple:not([disabled]):not(.disabled).active, .btn-purple:not([disabled]):not(.disabled):active,
.show > .btn-purple.dropdown-toggle {
  background-color: #8258d9;
  border-color: #8258d9;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pink {
  background-color: #f96197;
  border-color: #f96197;
  color: #fff;
}

.btn-pink:hover {
  background-color: #fa75a4;
  border-color: #fa75a4;
  color: #fff;
}

.btn-pink:focus, .btn-pink.focus {
  color: #fff;
}

.btn-pink.disabled, .btn-pink:disabled {
  background-color: #f96197;
  border-color: #f96197;
  opacity: 0.5;
}

.btn-pink:not([disabled]):not(.disabled).active, .btn-pink:not([disabled]):not(.disabled):active,
.show > .btn-pink.dropdown-toggle {
  background-color: #f84d8a;
  border-color: #f84d8a;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-cyan {
  background-color: #57c7d4;
  border-color: #57c7d4;
  color: #fff;
}

.btn-cyan:hover {
  background-color: #77d2dc;
  border-color: #77d2dc;
  color: #fff;
}

.btn-cyan:focus, .btn-cyan.focus {
  color: #fff;
}

.btn-cyan.disabled, .btn-cyan:disabled {
  background-color: #57c7d4;
  border-color: #57c7d4;
  opacity: 0.5;
}

.btn-cyan:not([disabled]):not(.disabled).active, .btn-cyan:not([disabled]):not(.disabled):active,
.show > .btn-cyan.dropdown-toggle {
  background-color: #37bccc;
  border-color: #37bccc;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-yellow {
  background-color: #fcc525;
  border-color: #fcc525;
  color: #fff;
}

.btn-yellow:hover {
  background-color: #fdd04d;
  border-color: #fdd04d;
  color: #fff;
}

.btn-yellow:focus, .btn-yellow.focus {
  color: #fff;
}

.btn-yellow.disabled, .btn-yellow:disabled {
  background-color: #fcc525;
  border-color: #fcc525;
  opacity: 0.5;
}

.btn-yellow:not([disabled]):not(.disabled).active, .btn-yellow:not([disabled]):not(.disabled):active,
.show > .btn-yellow.dropdown-toggle {
  background-color: #f5b703;
  border-color: #f5b703;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-brown {
  background-color: #8d6658;
  border-color: #8d6658;
  color: #fff;
}

.btn-brown:hover {
  background-color: #9d7162;
  border-color: #9d7162;
  color: #fff;
}

.btn-brown:focus, .btn-brown.focus {
  color: #fff;
}

.btn-brown.disabled, .btn-brown:disabled {
  background-color: #8d6658;
  border-color: #8d6658;
  opacity: 0.5;
}

.btn-brown:not([disabled]):not(.disabled).active, .btn-brown:not([disabled]):not(.disabled):active,
.show > .btn-brown.dropdown-toggle {
  background-color: #7d5b4e;
  border-color: #7d5b4e;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-dark {
  background-color: #465161;
  border-color: #465161;
  color: #fff;
}

.btn-dark:hover {
  background-color: #515d70;
  border-color: #515d70;
  color: #fff;
}

.btn-dark:focus, .btn-dark.focus {
  color: #fff;
}

.btn-dark.disabled, .btn-dark:disabled {
  background-color: #465161;
  border-color: #465161;
  opacity: 0.5;
}

.btn-dark:not([disabled]):not(.disabled).active, .btn-dark:not([disabled]):not(.disabled):active,
.show > .btn-dark.dropdown-toggle {
  background-color: #3b4552;
  border-color: #3b4552;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-facebook {
  background-color: #3b5998;
  border-color: #3b5998;
  color: #fff;
}

.btn-facebook:hover {
  background-color: #466ab5;
  border-color: #466ab5;
  color: #fff;
}

.btn-facebook:focus, .btn-facebook.focus {
  color: #fff;
}

.btn-facebook.disabled, .btn-facebook:disabled {
  background-color: #3b5998;
  border-color: #3b5998;
  opacity: 0.5;
}

.btn-facebook:not([disabled]):not(.disabled).active, .btn-facebook:not([disabled]):not(.disabled):active,
.show > .btn-facebook.dropdown-toggle {
  background-color: #30487b;
  border-color: #30487b;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-google {
  background-color: #dd4b39;
  border-color: #dd4b39;
  color: #fff;
}

.btn-google:hover {
  background-color: #e36b5c;
  border-color: #e36b5c;
  color: #fff;
}

.btn-google:focus, .btn-google.focus {
  color: #fff;
}

.btn-google.disabled, .btn-google:disabled {
  background-color: #dd4b39;
  border-color: #dd4b39;
  opacity: 0.5;
}

.btn-google:not([disabled]):not(.disabled).active, .btn-google:not([disabled]):not(.disabled):active,
.show > .btn-google.dropdown-toggle {
  background-color: #ca3523;
  border-color: #ca3523;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-twitter {
  background-color: #00aced;
  border-color: #00aced;
  color: #fff;
}

.btn-twitter:hover {
  background-color: #17bfff;
  border-color: #17bfff;
  color: #fff;
}

.btn-twitter:focus, .btn-twitter.focus {
  color: #fff;
}

.btn-twitter.disabled, .btn-twitter:disabled {
  background-color: #00aced;
  border-color: #00aced;
  opacity: 0.5;
}

.btn-twitter:not([disabled]):not(.disabled).active, .btn-twitter:not([disabled]):not(.disabled):active,
.show > .btn-twitter.dropdown-toggle {
  background-color: #008ec4;
  border-color: #008ec4;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-linkedin {
  background-color: #007bb6;
  border-color: #007bb6;
  color: #fff;
}

.btn-linkedin:hover {
  background-color: #0097df;
  border-color: #0097df;
  color: #fff;
}

.btn-linkedin:focus, .btn-linkedin.focus {
  color: #fff;
}

.btn-linkedin.disabled, .btn-linkedin:disabled {
  background-color: #007bb6;
  border-color: #007bb6;
  opacity: 0.5;
}

.btn-linkedin:not([disabled]):not(.disabled).active, .btn-linkedin:not([disabled]):not(.disabled):active,
.show > .btn-linkedin.dropdown-toggle {
  background-color: #005f8d;
  border-color: #005f8d;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pinterest {
  background-color: #cb2027;
  border-color: #cb2027;
  color: #fff;
}

.btn-pinterest:hover {
  background-color: #df353c;
  border-color: #df353c;
  color: #fff;
}

.btn-pinterest:focus, .btn-pinterest.focus {
  color: #fff;
}

.btn-pinterest.disabled, .btn-pinterest:disabled {
  background-color: #cb2027;
  border-color: #cb2027;
  opacity: 0.5;
}

.btn-pinterest:not([disabled]):not(.disabled).active, .btn-pinterest:not([disabled]):not(.disabled):active,
.show > .btn-pinterest.dropdown-toggle {
  background-color: #a81a20;
  border-color: #a81a20;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-git {
  background-color: #666666;
  border-color: #666666;
  color: #fff;
}

.btn-git:hover {
  background-color: #7a7a7a;
  border-color: #7a7a7a;
  color: #fff;
}

.btn-git:focus, .btn-git.focus {
  color: #fff;
}

.btn-git.disabled, .btn-git:disabled {
  background-color: #666666;
  border-color: #666666;
  opacity: 0.5;
}

.btn-git:not([disabled]):not(.disabled).active, .btn-git:not([disabled]):not(.disabled):active,
.show > .btn-git.dropdown-toggle {
  background-color: #525252;
  border-color: #525252;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-tumblr {
  background-color: #32506d;
  border-color: #32506d;
  color: #fff;
}

.btn-tumblr:hover {
  background-color: #3f6589;
  border-color: #3f6589;
  color: #fff;
}

.btn-tumblr:focus, .btn-tumblr.focus {
  color: #fff;
}

.btn-tumblr.disabled, .btn-tumblr:disabled {
  background-color: #32506d;
  border-color: #32506d;
  opacity: 0.5;
}

.btn-tumblr:not([disabled]):not(.disabled).active, .btn-tumblr:not([disabled]):not(.disabled):active,
.show > .btn-tumblr.dropdown-toggle {
  background-color: #253b51;
  border-color: #253b51;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-vimeo {
  background-color: #aad450;
  border-color: #aad450;
  color: #fff;
}

.btn-vimeo:hover {
  background-color: #badc71;
  border-color: #badc71;
  color: #fff;
}

.btn-vimeo:focus, .btn-vimeo.focus {
  color: #fff;
}

.btn-vimeo.disabled, .btn-vimeo:disabled {
  background-color: #aad450;
  border-color: #aad450;
  opacity: 0.5;
}

.btn-vimeo:not([disabled]):not(.disabled).active, .btn-vimeo:not([disabled]):not(.disabled):active,
.show > .btn-vimeo.dropdown-toggle {
  background-color: #99ca32;
  border-color: #99ca32;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-youtube {
  background-color: #bb0000;
  border-color: #bb0000;
  color: #fff;
}

.btn-youtube:hover {
  background-color: #e40000;
  border-color: #e40000;
  color: #fff;
}

.btn-youtube:focus, .btn-youtube.focus {
  color: #fff;
}

.btn-youtube.disabled, .btn-youtube:disabled {
  background-color: #bb0000;
  border-color: #bb0000;
  opacity: 0.5;
}

.btn-youtube:not([disabled]):not(.disabled).active, .btn-youtube:not([disabled]):not(.disabled):active,
.show > .btn-youtube.dropdown-toggle {
  background-color: #920000;
  border-color: #920000;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-flickr {
  background-color: #ff0084;
  border-color: #ff0084;
  color: #fff;
}

.btn-flickr:hover {
  background-color: #ff2998;
  border-color: #ff2998;
  color: #fff;
}

.btn-flickr:focus, .btn-flickr.focus {
  color: #fff;
}

.btn-flickr.disabled, .btn-flickr:disabled {
  background-color: #ff0084;
  border-color: #ff0084;
  opacity: 0.5;
}

.btn-flickr:not([disabled]):not(.disabled).active, .btn-flickr:not([disabled]):not(.disabled):active,
.show > .btn-flickr.dropdown-toggle {
  background-color: #d6006f;
  border-color: #d6006f;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-reddit {
  background-color: #ff4500;
  border-color: #ff4500;
  color: #fff;
}

.btn-reddit:hover {
  background-color: #ff6329;
  border-color: #ff6329;
  color: #fff;
}

.btn-reddit:focus, .btn-reddit.focus {
  color: #fff;
}

.btn-reddit.disabled, .btn-reddit:disabled {
  background-color: #ff4500;
  border-color: #ff4500;
  opacity: 0.5;
}

.btn-reddit:not([disabled]):not(.disabled).active, .btn-reddit:not([disabled]):not(.disabled):active,
.show > .btn-reddit.dropdown-toggle {
  background-color: #d63a00;
  border-color: #d63a00;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-dribbble {
  background-color: #ea4c89;
  border-color: #ea4c89;
  color: #fff;
}

.btn-dribbble:hover {
  background-color: #ee71a1;
  border-color: #ee71a1;
  color: #fff;
}

.btn-dribbble:focus, .btn-dribbble.focus {
  color: #fff;
}

.btn-dribbble.disabled, .btn-dribbble:disabled {
  background-color: #ea4c89;
  border-color: #ea4c89;
  opacity: 0.5;
}

.btn-dribbble:not([disabled]):not(.disabled).active, .btn-dribbble:not([disabled]):not(.disabled):active,
.show > .btn-dribbble.dropdown-toggle {
  background-color: #e62771;
  border-color: #e62771;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-skype {
  background-color: #00aff0;
  border-color: #00aff0;
  color: #fff;
}

.btn-skype:hover {
  background-color: #1ac1ff;
  border-color: #1ac1ff;
  color: #fff;
}

.btn-skype:focus, .btn-skype.focus {
  color: #fff;
}

.btn-skype.disabled, .btn-skype:disabled {
  background-color: #00aff0;
  border-color: #00aff0;
  opacity: 0.5;
}

.btn-skype:not([disabled]):not(.disabled).active, .btn-skype:not([disabled]):not(.disabled):active,
.show > .btn-skype.dropdown-toggle {
  background-color: #0091c7;
  border-color: #0091c7;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-instagram {
  background-color: #517fa4;
  border-color: #517fa4;
  color: #fff;
}

.btn-instagram:hover {
  background-color: #6993b5;
  border-color: #6993b5;
  color: #fff;
}

.btn-instagram:focus, .btn-instagram.focus {
  color: #fff;
}

.btn-instagram.disabled, .btn-instagram:disabled {
  background-color: #517fa4;
  border-color: #517fa4;
  opacity: 0.5;
}

.btn-instagram:not([disabled]):not(.disabled).active, .btn-instagram:not([disabled]):not(.disabled):active,
.show > .btn-instagram.dropdown-toggle {
  background-color: #446a89;
  border-color: #446a89;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-lastfm {
  background-color: #c3000d;
  border-color: #c3000d;
  color: #fff;
}

.btn-lastfm:hover {
  background-color: #ec0010;
  border-color: #ec0010;
  color: #fff;
}

.btn-lastfm:focus, .btn-lastfm.focus {
  color: #fff;
}

.btn-lastfm.disabled, .btn-lastfm:disabled {
  background-color: #c3000d;
  border-color: #c3000d;
  opacity: 0.5;
}

.btn-lastfm:not([disabled]):not(.disabled).active, .btn-lastfm:not([disabled]):not(.disabled):active,
.show > .btn-lastfm.dropdown-toggle {
  background-color: #9a000a;
  border-color: #9a000a;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-behance {
  background-color: #1769ff;
  border-color: #1769ff;
  color: #fff;
}

.btn-behance:hover {
  background-color: #4083ff;
  border-color: #4083ff;
  color: #fff;
}

.btn-behance:focus, .btn-behance.focus {
  color: #fff;
}

.btn-behance.disabled, .btn-behance:disabled {
  background-color: #1769ff;
  border-color: #1769ff;
  opacity: 0.5;
}

.btn-behance:not([disabled]):not(.disabled).active, .btn-behance:not([disabled]):not(.disabled):active,
.show > .btn-behance.dropdown-toggle {
  background-color: #0054ed;
  border-color: #0054ed;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-rss {
  background-color: #f26522;
  border-color: #f26522;
  color: #fff;
}

.btn-rss:hover {
  background-color: #f48049;
  border-color: #f48049;
  color: #fff;
}

.btn-rss:focus, .btn-rss.focus {
  color: #fff;
}

.btn-rss.disabled, .btn-rss:disabled {
  background-color: #f26522;
  border-color: #f26522;
  opacity: 0.5;
}

.btn-rss:not([disabled]):not(.disabled).active, .btn-rss:not([disabled]):not(.disabled):active,
.show > .btn-rss.dropdown-toggle {
  background-color: #de500d;
  border-color: #de500d;
  color: #fff;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-primary-outline {
  color: #33cabb;
  background-color: transparent;
  border-color: #33cabb;
}

.btn-primary-outline:hover {
  color: #fff;
  background-color: #33cabb;
  border-color: #33cabb;
}

.btn-primary-outline:not([disabled]):not(.disabled).active, .btn-primary-outline:not([disabled]):not(.disabled):active,
.show > .btn-primary-outline.dropdown-toggle {
  background-color: #2ba99d;
  border-color: #2ba99d;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-success-outline {
  color: #15c377;
  background-color: transparent;
  border-color: #15c377;
}

.btn-success-outline:hover {
  color: #fff;
  background-color: #15c377;
  border-color: #15c377;
}

.btn-success-outline:not([disabled]):not(.disabled).active, .btn-success-outline:not([disabled]):not(.disabled):active,
.show > .btn-success-outline.dropdown-toggle {
  background-color: #12a766;
  border-color: #12a766;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-info-outline {
  color: #48b0f7;
  background-color: transparent;
  border-color: #48b0f7;
}

.btn-info-outline:hover {
  color: #fff;
  background-color: #48b0f7;
  border-color: #48b0f7;
}

.btn-info-outline:not([disabled]):not(.disabled).active, .btn-info-outline:not([disabled]):not(.disabled):active,
.show > .btn-info-outline.dropdown-toggle {
  background-color: #2ba3f6;
  border-color: #2ba3f6;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-warning-outline {
  color: #faa64b;
  background-color: transparent;
  border-color: #faa64b;
}

.btn-warning-outline:hover {
  color: #fff;
  background-color: #faa64b;
  border-color: #faa64b;
}

.btn-warning-outline:not([disabled]):not(.disabled).active, .btn-warning-outline:not([disabled]):not(.disabled):active,
.show > .btn-warning-outline.dropdown-toggle {
  background-color: #f99a32;
  border-color: #f99a32;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-danger-outline {
  color: #f96868;
  background-color: transparent;
  border-color: #f96868;
}

.btn-danger-outline:hover {
  color: #fff;
  background-color: #f96868;
  border-color: #f96868;
}

.btn-danger-outline:not([disabled]):not(.disabled).active, .btn-danger-outline:not([disabled]):not(.disabled):active,
.show > .btn-danger-outline.dropdown-toggle {
  background-color: #f84f4f;
  border-color: #f84f4f;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-secondary-outline {
  color: #e4e7ea;
  background-color: transparent;
  border-color: #e4e7ea;
}

.btn-secondary-outline:hover {
  color: #fff;
  background-color: #e4e7ea;
  border-color: #e4e7ea;
}

.btn-secondary-outline:not([disabled]):not(.disabled).active, .btn-secondary-outline:not([disabled]):not(.disabled):active,
.show > .btn-secondary-outline.dropdown-toggle {
  background-color: #dbdfe3;
  border-color: #dbdfe3;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-primary {
  color: #33cabb;
  background-color: transparent;
  border-color: #33cabb;
}

.btn-outline.btn-primary:hover {
  color: #fff;
  background-color: #33cabb;
  border-color: #33cabb;
}

.btn-outline.btn-primary:not([disabled]):not(.disabled).active, .btn-outline.btn-primary:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-primary.dropdown-toggle {
  background-color: #2ba99d;
  border-color: #2ba99d;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-success {
  color: #15c377;
  background-color: transparent;
  border-color: #15c377;
}

.btn-outline.btn-success:hover {
  color: #fff;
  background-color: #15c377;
  border-color: #15c377;
}

.btn-outline.btn-success:not([disabled]):not(.disabled).active, .btn-outline.btn-success:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-success.dropdown-toggle {
  background-color: #12a766;
  border-color: #12a766;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-info {
  color: #48b0f7;
  background-color: transparent;
  border-color: #48b0f7;
}

.btn-outline.btn-info:hover {
  color: #fff;
  background-color: #48b0f7;
  border-color: #48b0f7;
}

.btn-outline.btn-info:not([disabled]):not(.disabled).active, .btn-outline.btn-info:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-info.dropdown-toggle {
  background-color: #2ba3f6;
  border-color: #2ba3f6;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-warning {
  color: #faa64b;
  background-color: transparent;
  border-color: #faa64b;
}

.btn-outline.btn-warning:hover {
  color: #fff;
  background-color: #faa64b;
  border-color: #faa64b;
}

.btn-outline.btn-warning:not([disabled]):not(.disabled).active, .btn-outline.btn-warning:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-warning.dropdown-toggle {
  background-color: #f99a32;
  border-color: #f99a32;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-danger {
  color: #f96868;
  background-color: transparent;
  border-color: #f96868;
}

.btn-outline.btn-danger:hover {
  color: #fff;
  background-color: #f96868;
  border-color: #f96868;
}

.btn-outline.btn-danger:not([disabled]):not(.disabled).active, .btn-outline.btn-danger:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-danger.dropdown-toggle {
  background-color: #f84f4f;
  border-color: #f84f4f;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-secondary {
  color: #e4e7ea;
  background-color: transparent;
  border-color: #e4e7ea;
}

.btn-outline.btn-secondary:hover {
  color: #fff;
  background-color: #e4e7ea;
  border-color: #e4e7ea;
}

.btn-outline.btn-secondary:not([disabled]):not(.disabled).active, .btn-outline.btn-secondary:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-secondary.dropdown-toggle {
  background-color: #dbdfe3;
  border-color: #dbdfe3;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-light {
  color: #fff;
  background-color: transparent;
  border-color: #fff;
}

.btn-outline.btn-light:hover {
  color: #fff;
  background-color: #fff;
  border-color: #fff;
}

.btn-outline.btn-light:not([disabled]):not(.disabled).active, .btn-outline.btn-light:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-light.dropdown-toggle {
  background-color: #f2f2f2;
  border-color: #f2f2f2;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-purple {
  color: #926dde;
  background-color: transparent;
  border-color: #926dde;
}

.btn-outline.btn-purple:hover {
  color: #fff;
  background-color: #926dde;
  border-color: #926dde;
}

.btn-outline.btn-purple:not([disabled]):not(.disabled).active, .btn-outline.btn-purple:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-purple.dropdown-toggle {
  background-color: #8258d9;
  border-color: #8258d9;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-pink {
  color: #f96197;
  background-color: transparent;
  border-color: #f96197;
}

.btn-outline.btn-pink:hover {
  color: #fff;
  background-color: #f96197;
  border-color: #f96197;
}

.btn-outline.btn-pink:not([disabled]):not(.disabled).active, .btn-outline.btn-pink:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-pink.dropdown-toggle {
  background-color: #f84d8a;
  border-color: #f84d8a;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-cyan {
  color: #57c7d4;
  background-color: transparent;
  border-color: #57c7d4;
}

.btn-outline.btn-cyan:hover {
  color: #fff;
  background-color: #57c7d4;
  border-color: #57c7d4;
}

.btn-outline.btn-cyan:not([disabled]):not(.disabled).active, .btn-outline.btn-cyan:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-cyan.dropdown-toggle {
  background-color: #37bccc;
  border-color: #37bccc;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-yellow {
  color: #fcc525;
  background-color: transparent;
  border-color: #fcc525;
}

.btn-outline.btn-yellow:hover {
  color: #fff;
  background-color: #fcc525;
  border-color: #fcc525;
}

.btn-outline.btn-yellow:not([disabled]):not(.disabled).active, .btn-outline.btn-yellow:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-yellow.dropdown-toggle {
  background-color: #f5b703;
  border-color: #f5b703;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-brown {
  color: #8d6658;
  background-color: transparent;
  border-color: #8d6658;
}

.btn-outline.btn-brown:hover {
  color: #fff;
  background-color: #8d6658;
  border-color: #8d6658;
}

.btn-outline.btn-brown:not([disabled]):not(.disabled).active, .btn-outline.btn-brown:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-brown.dropdown-toggle {
  background-color: #7d5b4e;
  border-color: #7d5b4e;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-dark {
  color: #465161;
  background-color: transparent;
  border-color: #465161;
}

.btn-outline.btn-dark:hover {
  color: #fff;
  background-color: #465161;
  border-color: #465161;
}

.btn-outline.btn-dark:not([disabled]):not(.disabled).active, .btn-outline.btn-dark:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-dark.dropdown-toggle {
  background-color: #3b4552;
  border-color: #3b4552;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-light:hover {
  color: #4d5259;
}

.btn-outline.btn-facebook {
  color: #3b5998;
  background-color: transparent;
  border-color: #3b5998;
}

.btn-outline.btn-facebook:hover {
  color: #fff;
  background-color: #3b5998;
  border-color: #3b5998;
}

.btn-outline.btn-facebook:not([disabled]):not(.disabled).active, .btn-outline.btn-facebook:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-facebook.dropdown-toggle {
  background-color: #30487b;
  border-color: #30487b;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-google {
  color: #dd4b39;
  background-color: transparent;
  border-color: #dd4b39;
}

.btn-outline.btn-google:hover {
  color: #fff;
  background-color: #dd4b39;
  border-color: #dd4b39;
}

.btn-outline.btn-google:not([disabled]):not(.disabled).active, .btn-outline.btn-google:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-google.dropdown-toggle {
  background-color: #ca3523;
  border-color: #ca3523;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-twitter {
  color: #00aced;
  background-color: transparent;
  border-color: #00aced;
}

.btn-outline.btn-twitter:hover {
  color: #fff;
  background-color: #00aced;
  border-color: #00aced;
}

.btn-outline.btn-twitter:not([disabled]):not(.disabled).active, .btn-outline.btn-twitter:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-twitter.dropdown-toggle {
  background-color: #008ec4;
  border-color: #008ec4;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-linkedin {
  color: #007bb6;
  background-color: transparent;
  border-color: #007bb6;
}

.btn-outline.btn-linkedin:hover {
  color: #fff;
  background-color: #007bb6;
  border-color: #007bb6;
}

.btn-outline.btn-linkedin:not([disabled]):not(.disabled).active, .btn-outline.btn-linkedin:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-linkedin.dropdown-toggle {
  background-color: #005f8d;
  border-color: #005f8d;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-pinterest {
  color: #cb2027;
  background-color: transparent;
  border-color: #cb2027;
}

.btn-outline.btn-pinterest:hover {
  color: #fff;
  background-color: #cb2027;
  border-color: #cb2027;
}

.btn-outline.btn-pinterest:not([disabled]):not(.disabled).active, .btn-outline.btn-pinterest:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-pinterest.dropdown-toggle {
  background-color: #a81a20;
  border-color: #a81a20;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-git {
  color: #666666;
  background-color: transparent;
  border-color: #666666;
}

.btn-outline.btn-git:hover {
  color: #fff;
  background-color: #666666;
  border-color: #666666;
}

.btn-outline.btn-git:not([disabled]):not(.disabled).active, .btn-outline.btn-git:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-git.dropdown-toggle {
  background-color: #525252;
  border-color: #525252;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-tumblr {
  color: #32506d;
  background-color: transparent;
  border-color: #32506d;
}

.btn-outline.btn-tumblr:hover {
  color: #fff;
  background-color: #32506d;
  border-color: #32506d;
}

.btn-outline.btn-tumblr:not([disabled]):not(.disabled).active, .btn-outline.btn-tumblr:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-tumblr.dropdown-toggle {
  background-color: #253b51;
  border-color: #253b51;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-vimeo {
  color: #aad450;
  background-color: transparent;
  border-color: #aad450;
}

.btn-outline.btn-vimeo:hover {
  color: #fff;
  background-color: #aad450;
  border-color: #aad450;
}

.btn-outline.btn-vimeo:not([disabled]):not(.disabled).active, .btn-outline.btn-vimeo:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-vimeo.dropdown-toggle {
  background-color: #99ca32;
  border-color: #99ca32;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-youtube {
  color: #bb0000;
  background-color: transparent;
  border-color: #bb0000;
}

.btn-outline.btn-youtube:hover {
  color: #fff;
  background-color: #bb0000;
  border-color: #bb0000;
}

.btn-outline.btn-youtube:not([disabled]):not(.disabled).active, .btn-outline.btn-youtube:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-youtube.dropdown-toggle {
  background-color: #920000;
  border-color: #920000;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-flickr {
  color: #ff0084;
  background-color: transparent;
  border-color: #ff0084;
}

.btn-outline.btn-flickr:hover {
  color: #fff;
  background-color: #ff0084;
  border-color: #ff0084;
}

.btn-outline.btn-flickr:not([disabled]):not(.disabled).active, .btn-outline.btn-flickr:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-flickr.dropdown-toggle {
  background-color: #d6006f;
  border-color: #d6006f;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-reddit {
  color: #ff4500;
  background-color: transparent;
  border-color: #ff4500;
}

.btn-outline.btn-reddit:hover {
  color: #fff;
  background-color: #ff4500;
  border-color: #ff4500;
}

.btn-outline.btn-reddit:not([disabled]):not(.disabled).active, .btn-outline.btn-reddit:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-reddit.dropdown-toggle {
  background-color: #d63a00;
  border-color: #d63a00;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-dribbble {
  color: #ea4c89;
  background-color: transparent;
  border-color: #ea4c89;
}

.btn-outline.btn-dribbble:hover {
  color: #fff;
  background-color: #ea4c89;
  border-color: #ea4c89;
}

.btn-outline.btn-dribbble:not([disabled]):not(.disabled).active, .btn-outline.btn-dribbble:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-dribbble.dropdown-toggle {
  background-color: #e62771;
  border-color: #e62771;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-skype {
  color: #00aff0;
  background-color: transparent;
  border-color: #00aff0;
}

.btn-outline.btn-skype:hover {
  color: #fff;
  background-color: #00aff0;
  border-color: #00aff0;
}

.btn-outline.btn-skype:not([disabled]):not(.disabled).active, .btn-outline.btn-skype:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-skype.dropdown-toggle {
  background-color: #0091c7;
  border-color: #0091c7;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-instagram {
  color: #517fa4;
  background-color: transparent;
  border-color: #517fa4;
}

.btn-outline.btn-instagram:hover {
  color: #fff;
  background-color: #517fa4;
  border-color: #517fa4;
}

.btn-outline.btn-instagram:not([disabled]):not(.disabled).active, .btn-outline.btn-instagram:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-instagram.dropdown-toggle {
  background-color: #446a89;
  border-color: #446a89;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-lastfm {
  color: #c3000d;
  background-color: transparent;
  border-color: #c3000d;
}

.btn-outline.btn-lastfm:hover {
  color: #fff;
  background-color: #c3000d;
  border-color: #c3000d;
}

.btn-outline.btn-lastfm:not([disabled]):not(.disabled).active, .btn-outline.btn-lastfm:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-lastfm.dropdown-toggle {
  background-color: #9a000a;
  border-color: #9a000a;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-behance {
  color: #1769ff;
  background-color: transparent;
  border-color: #1769ff;
}

.btn-outline.btn-behance:hover {
  color: #fff;
  background-color: #1769ff;
  border-color: #1769ff;
}

.btn-outline.btn-behance:not([disabled]):not(.disabled).active, .btn-outline.btn-behance:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-behance.dropdown-toggle {
  background-color: #0054ed;
  border-color: #0054ed;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-outline.btn-rss {
  color: #f26522;
  background-color: transparent;
  border-color: #f26522;
}

.btn-outline.btn-rss:hover {
  color: #fff;
  background-color: #f26522;
  border-color: #f26522;
}

.btn-outline.btn-rss:not([disabled]):not(.disabled).active, .btn-outline.btn-rss:not([disabled]):not(.disabled):active,
.show > .btn-outline.btn-rss.dropdown-toggle {
  background-color: #de500d;
  border-color: #de500d;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-label {
  position: relative;
  padding-left: 52px;
  overflow: hidden;
}

.btn-label label {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 36px;
  line-height: inherit;
  padding-top: 5px;
  padding-bottom: 5px;
  background-color: rgba(0, 0, 0, 0.1);
  cursor: pointer;
  margin-bottom: 0;
}

.btn-label.btn-xs {
  padding-left: 32px;
}

.btn-label.btn-xs label {
  width: 24px;
}

.btn-label.btn-sm {
  padding-left: 41px;
}

.btn-label.btn-sm label {
  width: 29px;
}

.btn-label.btn-lg {
  padding-left: 64px;
}

.btn-label.btn-lg label {
  width: 48px;
}

.btn-float {
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center;
  width: 56px;
  height: 56px;
  padding: 0;
  margin: 0;
  font-size: 24px;
  border-radius: 100%;
  -webkit-box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
          box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.btn-float.btn-sm {
  width: 40px;
  height: 40px;
  font-size: 16px;
}

.btn-float:active {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-sq,
.btn-square {
  padding: 5px;
  width: 36px;
  height: 36px;
}

.btn-sq.btn-lg,
.btn-square.btn-lg {
  width: 48px;
  height: 48px;
}

.btn-sq.btn-sm,
.btn-square.btn-sm {
  width: 29px;
  height: 29px;
}

.btn-sq.btn-xs,
.btn-square.btn-xs {
  width: 24px;
  height: 24px;
}

.btn-pure {
  background-color: transparent !important;
  border-color: transparent !important;
}

.btn-pure.btn-primary {
  color: #33cabb;
}

.btn-pure.btn-primary:hover, .btn-pure.btn-primary:not([disabled]):not(.disabled).active, .btn-pure.btn-primary:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-primary.dropdown-toggle {
  color: #29a195;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-secondary {
  color: #e4e7ea;
}

.btn-pure.btn-secondary:hover, .btn-pure.btn-secondary:not([disabled]):not(.disabled).active, .btn-pure.btn-secondary:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-secondary.dropdown-toggle {
  color: #c7ced4;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-success {
  color: #15c377;
}

.btn-pure.btn-success:hover, .btn-pure.btn-success:not([disabled]):not(.disabled).active, .btn-pure.btn-success:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-success.dropdown-toggle {
  color: #10955b;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-info {
  color: #48b0f7;
}

.btn-pure.btn-info:hover, .btn-pure.btn-info:not([disabled]):not(.disabled).active, .btn-pure.btn-info:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-info.dropdown-toggle {
  color: #179bf5;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-warning {
  color: #faa64b;
}

.btn-pure.btn-warning:hover, .btn-pure.btn-warning:not([disabled]):not(.disabled).active, .btn-pure.btn-warning:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-warning.dropdown-toggle {
  color: #f98d19;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-danger {
  color: #f96868;
}

.btn-pure.btn-danger:hover, .btn-pure.btn-danger:not([disabled]):not(.disabled).active, .btn-pure.btn-danger:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-danger.dropdown-toggle {
  color: #f73737;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-pink {
  color: #f96197;
}

.btn-pure.btn-pink:hover, .btn-pure.btn-pink:not([disabled]):not(.disabled).active, .btn-pure.btn-pink:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-pink.dropdown-toggle {
  color: #f73077;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-purple {
  color: #926dde;
}

.btn-pure.btn-purple:hover, .btn-pure.btn-purple:not([disabled]):not(.disabled).active, .btn-pure.btn-purple:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-purple.dropdown-toggle {
  color: #7343d5;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-brown {
  color: #8d6658;
}

.btn-pure.btn-brown:hover, .btn-pure.btn-brown:not([disabled]):not(.disabled).active, .btn-pure.btn-brown:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-brown.dropdown-toggle {
  color: #6e4f44;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-cyan {
  color: #57c7d4;
}

.btn-pure.btn-cyan:hover, .btn-pure.btn-cyan:not([disabled]):not(.disabled).active, .btn-pure.btn-cyan:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-cyan.dropdown-toggle {
  color: #33b6c5;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-yellow {
  color: #fcc525;
}

.btn-pure.btn-yellow:hover, .btn-pure.btn-yellow:not([disabled]):not(.disabled).active, .btn-pure.btn-yellow:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-yellow.dropdown-toggle {
  color: #ebb003;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-gray {
  color: #868e96;
}

.btn-pure.btn-gray:hover, .btn-pure.btn-gray:not([disabled]):not(.disabled).active, .btn-pure.btn-gray:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-gray.dropdown-toggle {
  color: #6c757d;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-dark {
  color: #465161;
}

.btn-pure.btn-dark:hover, .btn-pure.btn-dark:not([disabled]):not(.disabled).active, .btn-pure.btn-dark:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-dark.dropdown-toggle {
  color: #313843;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-facebook {
  color: #3b5998;
}

.btn-pure.btn-facebook:hover, .btn-pure.btn-facebook:not([disabled]):not(.disabled).active, .btn-pure.btn-facebook:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-facebook.dropdown-toggle {
  color: #2d4373;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-google {
  color: #dd4b39;
}

.btn-pure.btn-google:hover, .btn-pure.btn-google:not([disabled]):not(.disabled).active, .btn-pure.btn-google:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-google.dropdown-toggle {
  color: #c23321;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-twitter {
  color: #00aced;
}

.btn-pure.btn-twitter:hover, .btn-pure.btn-twitter:not([disabled]):not(.disabled).active, .btn-pure.btn-twitter:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-twitter.dropdown-toggle {
  color: #0087ba;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-linkedin {
  color: #007bb6;
}

.btn-pure.btn-linkedin:hover, .btn-pure.btn-linkedin:not([disabled]):not(.disabled).active, .btn-pure.btn-linkedin:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-linkedin.dropdown-toggle {
  color: #005983;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-pinterest {
  color: #cb2027;
}

.btn-pure.btn-pinterest:hover, .btn-pure.btn-pinterest:not([disabled]):not(.disabled).active, .btn-pure.btn-pinterest:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-pinterest.dropdown-toggle {
  color: #9f191f;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-git {
  color: #666666;
}

.btn-pure.btn-git:hover, .btn-pure.btn-git:not([disabled]):not(.disabled).active, .btn-pure.btn-git:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-git.dropdown-toggle {
  color: #4d4c4c;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-tumblr {
  color: #32506d;
}

.btn-pure.btn-tumblr:hover, .btn-pure.btn-tumblr:not([disabled]):not(.disabled).active, .btn-pure.btn-tumblr:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-tumblr.dropdown-toggle {
  color: #22364a;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-vimeo {
  color: #aad450;
}

.btn-pure.btn-vimeo:hover, .btn-pure.btn-vimeo:not([disabled]):not(.disabled).active, .btn-pure.btn-vimeo:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-vimeo.dropdown-toggle {
  color: #93c130;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-youtube {
  color: #bb0000;
}

.btn-pure.btn-youtube:hover, .btn-pure.btn-youtube:not([disabled]):not(.disabled).active, .btn-pure.btn-youtube:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-youtube.dropdown-toggle {
  color: #880000;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-flickr {
  color: #ff0084;
}

.btn-pure.btn-flickr:hover, .btn-pure.btn-flickr:not([disabled]):not(.disabled).active, .btn-pure.btn-flickr:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-flickr.dropdown-toggle {
  color: #cc006a;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-reddit {
  color: #ff4500;
}

.btn-pure.btn-reddit:hover, .btn-pure.btn-reddit:not([disabled]):not(.disabled).active, .btn-pure.btn-reddit:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-reddit.dropdown-toggle {
  color: #cc3700;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-dribbble {
  color: #ea4c89;
}

.btn-pure.btn-dribbble:hover, .btn-pure.btn-dribbble:not([disabled]):not(.disabled).active, .btn-pure.btn-dribbble:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-dribbble.dropdown-toggle {
  color: #e51e6b;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-skype {
  color: #00aff0;
}

.btn-pure.btn-skype:hover, .btn-pure.btn-skype:not([disabled]):not(.disabled).active, .btn-pure.btn-skype:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-skype.dropdown-toggle {
  color: #008abd;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-instagram {
  color: #517fa4;
}

.btn-pure.btn-instagram:hover, .btn-pure.btn-instagram:not([disabled]):not(.disabled).active, .btn-pure.btn-instagram:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-instagram.dropdown-toggle {
  color: #406582;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-lastfm {
  color: #c3000d;
}

.btn-pure.btn-lastfm:hover, .btn-pure.btn-lastfm:not([disabled]):not(.disabled).active, .btn-pure.btn-lastfm:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-lastfm.dropdown-toggle {
  color: #90000a;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-behance {
  color: #1769ff;
}

.btn-pure.btn-behance:hover, .btn-pure.btn-behance:not([disabled]):not(.disabled).active, .btn-pure.btn-behance:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-behance.dropdown-toggle {
  color: #0050e3;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-pure.btn-rss {
  color: #f26522;
}

.btn-pure.btn-rss:hover, .btn-pure.btn-rss:not([disabled]):not(.disabled).active, .btn-pure.btn-rss:not([disabled]):not(.disabled):active,
.show > .btn-pure.btn-rss.dropdown-toggle {
  color: #d54d0d;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.btn-flat {
  position: relative;
  font-size: 13px;
  font-family: Roboto, sans-serif;
  text-transform: uppercase;
  background-color: #fcfdfe;
  border: none;
  letter-spacing: 1px;
  border-radius: 0;
}

.btn-flat:hover {
  background-color: #f9fafb;
}

.btn-flat svg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.btn-flat circle {
  fill: rgba(255, 255, 255, 0.3);
}

.btn-flat.btn-primary {
  color: #33cabb;
}

.btn-flat.btn-primary circle {
  fill: rgba(51, 202, 187, 0.1);
}

.btn-flat.btn-secondary {
  color: #e4e7ea;
}

.btn-flat.btn-secondary circle {
  fill: rgba(228, 231, 234, 0.1);
}

.btn-flat.btn-success {
  color: #15c377;
}

.btn-flat.btn-success circle {
  fill: rgba(21, 195, 119, 0.1);
}

.btn-flat.btn-info {
  color: #48b0f7;
}

.btn-flat.btn-info circle {
  fill: rgba(72, 176, 247, 0.1);
}

.btn-flat.btn-warning {
  color: #faa64b;
}

.btn-flat.btn-warning circle {
  fill: rgba(250, 166, 75, 0.1);
}

.btn-flat.btn-danger {
  color: #f96868;
}

.btn-flat.btn-danger circle {
  fill: rgba(249, 104, 104, 0.1);
}

.btn-flat.btn-pink {
  color: #f96197;
}

.btn-flat.btn-pink circle {
  fill: rgba(249, 97, 151, 0.1);
}

.btn-flat.btn-purple {
  color: #926dde;
}

.btn-flat.btn-purple circle {
  fill: rgba(146, 109, 222, 0.1);
}

.btn-flat.btn-brown {
  color: #8d6658;
}

.btn-flat.btn-brown circle {
  fill: rgba(141, 102, 88, 0.1);
}

.btn-flat.btn-cyan {
  color: #57c7d4;
}

.btn-flat.btn-cyan circle {
  fill: rgba(87, 199, 212, 0.1);
}

.btn-flat.btn-yellow {
  color: #fcc525;
}

.btn-flat.btn-yellow circle {
  fill: rgba(252, 197, 37, 0.1);
}

.btn-flat.btn-gray {
  color: #868e96;
}

.btn-flat.btn-gray circle {
  fill: rgba(134, 142, 150, 0.1);
}

.btn-flat.btn-dark {
  color: #465161;
}

.btn-flat.btn-dark circle {
  fill: rgba(70, 81, 97, 0.1);
}

.btn-flat.btn-secondary {
  color: #616a78;
}

.btn-flat.btn-facebook {
  color: #3b5998;
}

.btn-flat.btn-facebook circle {
  fill: rgba(59, 89, 152, 0.1);
}

.btn-flat.btn-google {
  color: #dd4b39;
}

.btn-flat.btn-google circle {
  fill: rgba(221, 75, 57, 0.1);
}

.btn-flat.btn-twitter {
  color: #00aced;
}

.btn-flat.btn-twitter circle {
  fill: rgba(0, 172, 237, 0.1);
}

.btn-flat.btn-linkedin {
  color: #007bb6;
}

.btn-flat.btn-linkedin circle {
  fill: rgba(0, 123, 182, 0.1);
}

.btn-flat.btn-pinterest {
  color: #cb2027;
}

.btn-flat.btn-pinterest circle {
  fill: rgba(203, 32, 39, 0.1);
}

.btn-flat.btn-git {
  color: #666666;
}

.btn-flat.btn-git circle {
  fill: rgba(102, 102, 102, 0.1);
}

.btn-flat.btn-tumblr {
  color: #32506d;
}

.btn-flat.btn-tumblr circle {
  fill: rgba(50, 80, 109, 0.1);
}

.btn-flat.btn-vimeo {
  color: #aad450;
}

.btn-flat.btn-vimeo circle {
  fill: rgba(170, 212, 80, 0.1);
}

.btn-flat.btn-youtube {
  color: #bb0000;
}

.btn-flat.btn-youtube circle {
  fill: rgba(187, 0, 0, 0.1);
}

.btn-flat.btn-flickr {
  color: #ff0084;
}

.btn-flat.btn-flickr circle {
  fill: rgba(255, 0, 132, 0.1);
}

.btn-flat.btn-reddit {
  color: #ff4500;
}

.btn-flat.btn-reddit circle {
  fill: rgba(255, 69, 0, 0.1);
}

.btn-flat.btn-dribbble {
  color: #ea4c89;
}

.btn-flat.btn-dribbble circle {
  fill: rgba(234, 76, 137, 0.1);
}

.btn-flat.btn-skype {
  color: #00aff0;
}

.btn-flat.btn-skype circle {
  fill: rgba(0, 175, 240, 0.1);
}

.btn-flat.btn-instagram {
  color: #517fa4;
}

.btn-flat.btn-instagram circle {
  fill: rgba(81, 127, 164, 0.1);
}

.btn-flat.btn-lastfm {
  color: #c3000d;
}

.btn-flat.btn-lastfm circle {
  fill: rgba(195, 0, 13, 0.1);
}

.btn-flat.btn-behance {
  color: #1769ff;
}

.btn-flat.btn-behance circle {
  fill: rgba(23, 105, 255, 0.1);
}

.btn-flat.btn-rss {
  color: #f26522;
}

.btn-flat.btn-rss circle {
  fill: rgba(242, 101, 34, 0.1);
}

.btn-multiline {
  padding-top: 15px;
  padding-bottom: 5px;
}

.btn-group,
.btn-group-vertical {
  vertical-align: initial;
}

.btn-group .btn i {
  vertical-align: middle;
}

.btn-group-vertical > .btn:first-child:not(:last-child) {
  border-top-right-radius: 2px;
}

.btn-group-vertical > .btn:last-child:not(:first-child) {
  border-bottom-left-radius: 2px;
}

.btn-spacer .btn + .btn {
  margin-left: 8px;
}

.btn-group-justified {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
}

.btn-group-justified .btn {
  width: 100%;
}

.btn-group-round .btn:first-child {
  border-top-left-radius: 10rem;
  border-bottom-left-radius: 10rem;
}

.btn-group-round .btn:last-child {
  border-top-right-radius: 10rem;
  border-bottom-right-radius: 10rem;
}

.fab {
  position: relative;
  display: inline-block;
  z-index: 9;
}

.fab > .btn {
  position: relative;
  z-index: 1;
}

.fab-fixed {
  position: fixed;
  right: 40px;
  bottom: 40px;
}

.fab-buttons {
  position: absolute;
  bottom: 64px;
  right: 8px;
  list-style: none;
  margin: 0;
  padding: 0;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: reverse;
          flex-direction: column-reverse;
}

.fab-buttons li {
  padding: 4px 0;
  text-align: right;
  opacity: 0;
  -webkit-transition: .4s;
  transition: .4s;
}

.fab-buttons li:nth-child(1) {
  -webkit-transform: translateY(103%);
          transform: translateY(103%);
}

.fab-buttons li:nth-child(2) {
  -webkit-transform: translateY(206%);
          transform: translateY(206%);
}

.fab-buttons li:nth-child(3) {
  -webkit-transform: translateY(309%);
          transform: translateY(309%);
}

.fab-buttons li:nth-child(4) {
  -webkit-transform: translateY(412%);
          transform: translateY(412%);
}

.fab-buttons li:nth-child(5) {
  -webkit-transform: translateY(515%);
          transform: translateY(515%);
}

.fab-buttons li:nth-child(6) {
  -webkit-transform: translateY(618%);
          transform: translateY(618%);
}

.fab-buttons li:nth-child(7) {
  -webkit-transform: translateY(721%);
          transform: translateY(721%);
}

.fab-buttons li:nth-child(8) {
  -webkit-transform: translateY(824%);
          transform: translateY(824%);
}

.fab-buttons li:nth-child(9) {
  -webkit-transform: translateY(927%);
          transform: translateY(927%);
}

.fab-icon-default,
.fab-icon-active {
  display: inline-block;
  position: absolute;
  left: 50%;
  top: 50%;
  opacity: 1;
  -webkit-transform: translate(-50%, -50%) scale(1);
          transform: translate(-50%, -50%) scale(1);
  -webkit-transition: .5s;
  transition: .5s;
}

.fab-icon-active {
  opacity: 0;
  -webkit-transform: translate(-50%, -50%) scale(0);
          transform: translate(-50%, -50%) scale(0);
}

.fab > .btn.active .fab-icon-default {
  opacity: 0;
  -webkit-transform: translate(-50%, -50%) scale(0);
          transform: translate(-50%, -50%) scale(0);
}

.fab > .btn.active .fab-icon-active {
  opacity: 1;
  -webkit-transform: translate(-50%, -50%) scale(1);
          transform: translate(-50%, -50%) scale(1);
}

.fab > .btn.active + .fab-buttons li {
  opacity: 1;
  -webkit-transform: translate(0);
          transform: translate(0);
}

.fab-dir-bottom .fab-buttons {
  top: 64px;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
}

.fab-dir-bottom .fab-buttons li:nth-child(1) {
  -webkit-transform: translateY(-103%);
          transform: translateY(-103%);
}

.fab-dir-bottom .fab-buttons li:nth-child(2) {
  -webkit-transform: translateY(-206%);
          transform: translateY(-206%);
}

.fab-dir-bottom .fab-buttons li:nth-child(3) {
  -webkit-transform: translateY(-309%);
          transform: translateY(-309%);
}

.fab-dir-bottom .fab-buttons li:nth-child(4) {
  -webkit-transform: translateY(-412%);
          transform: translateY(-412%);
}

.fab-dir-bottom .fab-buttons li:nth-child(5) {
  -webkit-transform: translateY(-515%);
          transform: translateY(-515%);
}

.fab-dir-bottom .fab-buttons li:nth-child(6) {
  -webkit-transform: translateY(-618%);
          transform: translateY(-618%);
}

.fab-dir-bottom .fab-buttons li:nth-child(7) {
  -webkit-transform: translateY(-721%);
          transform: translateY(-721%);
}

.fab-dir-bottom .fab-buttons li:nth-child(8) {
  -webkit-transform: translateY(-824%);
          transform: translateY(-824%);
}

.fab-dir-bottom .fab-buttons li:nth-child(9) {
  -webkit-transform: translateY(-927%);
          transform: translateY(-927%);
}

.fab-dir-left .fab-buttons {
  top: 0;
  right: 64px;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
}

.fab-dir-left .fab-buttons li {
  padding: 8px 4px;
}

.fab-dir-left .fab-buttons li:nth-child(1) {
  -webkit-transform: translateX(103%);
          transform: translateX(103%);
}

.fab-dir-left .fab-buttons li:nth-child(2) {
  -webkit-transform: translateX(206%);
          transform: translateX(206%);
}

.fab-dir-left .fab-buttons li:nth-child(3) {
  -webkit-transform: translateX(309%);
          transform: translateX(309%);
}

.fab-dir-left .fab-buttons li:nth-child(4) {
  -webkit-transform: translateX(412%);
          transform: translateX(412%);
}

.fab-dir-left .fab-buttons li:nth-child(5) {
  -webkit-transform: translateX(515%);
          transform: translateX(515%);
}

.fab-dir-left .fab-buttons li:nth-child(6) {
  -webkit-transform: translateX(618%);
          transform: translateX(618%);
}

.fab-dir-left .fab-buttons li:nth-child(7) {
  -webkit-transform: translateX(721%);
          transform: translateX(721%);
}

.fab-dir-left .fab-buttons li:nth-child(8) {
  -webkit-transform: translateX(824%);
          transform: translateX(824%);
}

.fab-dir-left .fab-buttons li:nth-child(9) {
  -webkit-transform: translateX(927%);
          transform: translateX(927%);
}

.fab-dir-right .fab-buttons {
  top: 0;
  left: 64px;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
          flex-direction: row;
}

.fab-dir-right .fab-buttons li {
  padding: 8px 4px;
}

.fab-dir-right .fab-buttons li:nth-child(1) {
  -webkit-transform: translateX(-103%);
          transform: translateX(-103%);
}

.fab-dir-right .fab-buttons li:nth-child(2) {
  -webkit-transform: translateX(-206%);
          transform: translateX(-206%);
}

.fab-dir-right .fab-buttons li:nth-child(3) {
  -webkit-transform: translateX(-309%);
          transform: translateX(-309%);
}

.fab-dir-right .fab-buttons li:nth-child(4) {
  -webkit-transform: translateX(-412%);
          transform: translateX(-412%);
}

.fab-dir-right .fab-buttons li:nth-child(5) {
  -webkit-transform: translateX(-515%);
          transform: translateX(-515%);
}

.fab-dir-right .fab-buttons li:nth-child(6) {
  -webkit-transform: translateX(-618%);
          transform: translateX(-618%);
}

.fab-dir-right .fab-buttons li:nth-child(7) {
  -webkit-transform: translateX(-721%);
          transform: translateX(-721%);
}

.fab-dir-right .fab-buttons li:nth-child(8) {
  -webkit-transform: translateX(-824%);
          transform: translateX(-824%);
}

.fab-dir-right .fab-buttons li:nth-child(9) {
  -webkit-transform: translateX(-927%);
          transform: translateX(-927%);
}

.dropdown .flex-row,
.dropup .flex-row {
  -webkit-box-align: center;
          align-items: center;
}

.dropdown-backdrop {
  cursor: default;
}

.dropdown-toggle {
  cursor: pointer;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.dropdown-toggle::after {
  margin-left: .5em;
  vertical-align: .12em;
}

.dropup .dropdown-toggle::after {
  vertical-align: .2em;
}

.dropdown-toggle.no-caret::after {
  display: none;
}

.dropdown-toggle .icon {
  vertical-align: baseline;
  margin-right: 6px;
  font-size: 0.75rem;
}

.open > .btn-primary.dropdown-toggle {
  background-color: #33cabb;
  border-color: #33cabb;
}

.open > .btn-primary.dropdown-toggle.focus, .open > .btn-primary.dropdown-toggle:focus, .open > .btn-primary.dropdown-toggle:hover {
  background-color: #2ba99d;
  border-color: #2ba99d;
}

.open > .btn-secondary.dropdown-toggle {
  background-color: #e4e7ea;
  border-color: #e4e7ea;
}

.open > .btn-secondary.dropdown-toggle.focus, .open > .btn-secondary.dropdown-toggle:focus, .open > .btn-secondary.dropdown-toggle:hover {
  background-color: #dbdfe3;
  border-color: #dbdfe3;
}

.open > .btn-success.dropdown-toggle {
  background-color: #15c377;
  border-color: #15c377;
}

.open > .btn-success.dropdown-toggle.focus, .open > .btn-success.dropdown-toggle:focus, .open > .btn-success.dropdown-toggle:hover {
  background-color: #12a766;
  border-color: #12a766;
}

.open > .btn-info.dropdown-toggle {
  background-color: #48b0f7;
  border-color: #48b0f7;
}

.open > .btn-info.dropdown-toggle.focus, .open > .btn-info.dropdown-toggle:focus, .open > .btn-info.dropdown-toggle:hover {
  background-color: #2ba3f6;
  border-color: #2ba3f6;
}

.open > .btn-warning.dropdown-toggle {
  background-color: #faa64b;
  border-color: #faa64b;
}

.open > .btn-warning.dropdown-toggle.focus, .open > .btn-warning.dropdown-toggle:focus, .open > .btn-warning.dropdown-toggle:hover {
  background-color: #f99a32;
  border-color: #f99a32;
}

.open > .btn-danger.dropdown-toggle {
  background-color: #f96868;
  border-color: #f96868;
}

.open > .btn-danger.dropdown-toggle.focus, .open > .btn-danger.dropdown-toggle:focus, .open > .btn-danger.dropdown-toggle:hover {
  background-color: #f84f4f;
  border-color: #f84f4f;
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
  -font-size: 13px;
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

.dropdown-item {
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

.dropdown-header {
  text-transform: uppercase;
  color: #8b95a5;
  font-size: 12px;
  margin-bottom: 0;
  padding: 12px;
  letter-spacing: .25px;
  opacity: 0.8;
}

.dropdown-footer {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  background-color: #f9fafb;
  border-top: 1px solid #ebebeb;
  padding: 0 0.75rem;
}

.dropdown-footer a {
  display: inline-block;
  padding: 10px 8px;
  color: #8b95a5;
}

.dropdown-footer a:hover {
  color: #4d5259;
}

.topbar .dropdown-menu,
.topbar .dropdown-grid {
  margin-top: 7px !important;
  overflow: visible !important;
}

.topbar .dropdown-menu::before, .topbar .dropdown-menu::after,
.topbar .dropdown-grid::before,
.topbar .dropdown-grid::after {
  content: '';
  position: absolute;
  top: -11px;
  left: 17px;
  width: 0;
  height: 0;
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  border-bottom: 9px solid rgba(0, 0, 0, 0.045);
}

.topbar .dropdown-menu::after,
.topbar .dropdown-grid::after {
  top: -9px;
  border-bottom-color: #fdfeff;
}

.topbar .dropdown-menu.dropdown-menu-right::before, .topbar .dropdown-menu.dropdown-menu-right::after,
.topbar .dropdown-grid.dropdown-menu-right::before,
.topbar .dropdown-grid.dropdown-menu-right::after {
  left: auto;
  right: 17px;
}

.dropdown-divider {
  background-color: #f1f2f3;
  margin: 4px 0;
}

.dropdown-sm .dropdown-item {
  padding-top: 2px;
  padding-bottom: 2px;
}

.dropdown-sm .dropdown-grid {
  min-width: 270px;
}

.dropdown-sm .dropdown-grid .dropdown-item {
  min-height: 80px;
  font-size: .8125rem;
}

.dropdown-sm .dropdown-grid .icon {
  font-size: 20px;
}

.dropdown-sm .dropdown-grid.cols-2 {
  min-width: 180px;
}

.dropdown-sm .dropdown-grid.cols-4 {
  min-width: 360px;
}

.dropdown-lg .dropdown-item {
  padding-top: 7px;
  padding-bottom: 7px;
}

.dropdown-lg .dropdown-grid {
  min-width: 330px;
}

.dropdown-lg .dropdown-grid .dropdown-item {
  min-height: 100px;
}

.dropdown-lg .dropdown-grid .icon {
  font-size: 28px;
}

.dropdown-lg .dropdown-grid.cols-2 {
  min-width: 220px;
}

.dropdown-lg .dropdown-grid.cols-4 {
  min-width: 440px;
}

.breadcrumb {
  background-color: transparent;
  padding: 0;
}

.breadcrumb a {
  color: #8b95a5;
}

.breadcrumb a:hover {
  color: #33cabb;
  text-decoration: none;
}

.breadcrumb .breadcrumb-item i {
  opacity: .9;
  margin-right: 2px;
}

.breadcrumb-item + .breadcrumb-item::before {
  color: #8b95a5;
}

.breadcrumb-arrow .breadcrumb-item + .breadcrumb-item::before {
  content: "\e649";
  font-family: themify;
  font-size: 0.5rem;
  vertical-align: middle;
}

.carousel-item-next,
.carousel-item-prev,
.carousel-item.active {
  display: block;
}

.carousel-caption {
  padding-bottom: 0;
}

.carousel-caption h1, .carousel-caption h2, .carousel-caption h3, .carousel-caption h4, .carousel-caption h5, .carousel-caption h6 {
  color: #fff;
}

.carousel-control-next-icon,
.carousel-control-prev-icon {
  font-family: themify;
  font-size: 28px;
  background: none;
}

.carousel-control-next-icon::before {
  content: "\e649";
}

.carousel-control-prev-icon::before {
  content: "\e64a";
}

.carousel-indicators {
  bottom: 16px;
  margin-bottom: 0;
}

.carousel-indicators li {
  border: none;
  background-color: #fff;
  opacity: .4;
  margin-left: 2px;
  margin-right: 2px;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.carousel-indicators .active {
  opacity: .8;
}

.carousel-indicators-outside {
  position: static;
  margin: 0 auto;
  padding-top: 16px;
}

.carousel-indicators-outside li {
  background-color: #dcddde;
}

.carousel-indicators-outside .active {
  background-color: #c2c8cf;
}

.carousel-indicators-primary .active {
  background-color: #33cabb;
}

.carousel-indicators-secondary .active {
  background-color: #e4e7ea;
}

.carousel-indicators-success .active {
  background-color: #15c377;
}

.carousel-indicators-info .active {
  background-color: #48b0f7;
}

.carousel-indicators-warning .active {
  background-color: #faa64b;
}

.carousel-indicators-danger .active {
  background-color: #f96868;
}

.carousel-indicators-pink .active {
  background-color: #f96197;
}

.carousel-indicators-purple .active {
  background-color: #926dde;
}

.carousel-indicators-brown .active {
  background-color: #8d6658;
}

.carousel-indicators-cyan .active {
  background-color: #57c7d4;
}

.carousel-indicators-yellow .active {
  background-color: #fcc525;
}

.carousel-indicators-gray .active {
  background-color: #868e96;
}

.carousel-indicators-dark .active {
  background-color: #465161;
}

.close {
  font-weight: 300;
  outline: none !important;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.badge {
  border-radius: 3px;
  font-weight: 400;
  line-height: 1.3;
  font-size: 85%;
}

.badge:empty {
  display: inline-block;
  vertical-align: inherit;
}

.badge-pill {
  border-radius: 10rem;
}

.badge-primary {
  background-color: #33cabb;
}

.badge-primary[href]:focus, .badge-primary[href]:hover {
  background-color: #2eb6a8;
}

.badge-secondary {
  background-color: #e4e7ea;
}

.badge-secondary[href]:focus, .badge-secondary[href]:hover {
  background-color: #d6dadf;
}

.badge-success {
  background-color: #15c377;
}

.badge-success[href]:focus, .badge-success[href]:hover {
  background-color: #13ac69;
}

.badge-info {
  background-color: #48b0f7;
}

.badge-info[href]:focus, .badge-info[href]:hover {
  background-color: #30a5f6;
}

.badge-warning {
  background-color: #faa64b;
}

.badge-warning[href]:focus, .badge-warning[href]:hover {
  background-color: #f99a32;
}

.badge-danger {
  background-color: #f96868;
}

.badge-danger[href]:focus, .badge-danger[href]:hover {
  background-color: #f84f4f;
}

.badge-pink {
  background-color: #f96197;
}

.badge-pink[href]:focus, .badge-pink[href]:hover {
  background-color: #f84887;
}

.badge-purple {
  background-color: #926dde;
}

.badge-purple[href]:focus, .badge-purple[href]:hover {
  background-color: #8258d9;
}

.badge-brown {
  background-color: #8d6658;
}

.badge-brown[href]:focus, .badge-brown[href]:hover {
  background-color: #7d5b4e;
}

.badge-cyan {
  background-color: #57c7d4;
}

.badge-cyan[href]:focus, .badge-cyan[href]:hover {
  background-color: #43c0cf;
}

.badge-yellow {
  background-color: #fcc525;
}

.badge-yellow[href]:focus, .badge-yellow[href]:hover {
  background-color: #fcbe0c;
}

.badge-gray {
  background-color: #868e96;
}

.badge-gray[href]:focus, .badge-gray[href]:hover {
  background-color: #78818a;
}

.badge-dark {
  background-color: #465161;
}

.badge-dark[href]:focus, .badge-dark[href]:hover {
  background-color: #3b4552;
}

.badge-warning {
  color: #fff;
}

.badge-default {
  color: #616a78;
  background-color: #f5f6f7;
}

.badge-default[href]:focus, .badge-default[href]:hover {
  color: #616a78;
  background-color: #edeef0;
}

.badge-secondary {
  color: #616a78;
  background-color: #e4e7ea;
}

.badge-secondary[href]:focus, .badge-secondary[href]:hover {
  color: #616a78;
  background-color: #dbdfe3;
}

.badge-sm {
  line-height: 1.2;
  padding-top: 1px;
  padding-bottom: 2px;
  font-size: 75%;
}

.badge-lg {
  line-height: 1.5;
  padding: 5px 7px;
  font-size: 95%;
}

.badge-xl {
  line-height: 1.7;
  padding: 7px 9px;
  font-size: 100%;
}

.badge-dot {
  width: 8px;
  height: 8px;
  padding: 0;
  border-radius: 100%;
  vertical-align: middle;
}

.badge-dot.badge-sm {
  width: 6px;
  height: 6px;
}

.badge-dot.badge-lg {
  width: 10px;
  height: 10px;
}

.badge-dot.badge-xl {
  width: 12px;
  height: 12px;
}

.badge-ring {
  position: relative;
  width: 10px;
  height: 10px;
  padding: 0;
  border-radius: 100%;
  vertical-align: middle;
}

.badge-ring::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 2px;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #fff;
  -webkit-transform: scale(1);
          transform: scale(1);
  -webkit-transition: .3s;
  transition: .3s;
}

.badge-ring.badge-sm {
  width: 8px;
  height: 8px;
}

.badge-ring.badge-sm::after {
  width: 4px;
  height: 4px;
}

.badge-ring.badge-lg {
  width: 12px;
  height: 12px;
}

.badge-ring.badge-lg::after {
  width: 8px;
  height: 8px;
}

.badge-ring.badge-xl {
  width: 14px;
  height: 14px;
}

.badge-ring.badge-xl::after {
  width: 10px;
  height: 10px;
}

.badge-ring.fill::after {
  -webkit-transform: scale(0);
          transform: scale(0);
}

.badge-bold {
  font-family: Roboto, sans-serif;
  text-transform: uppercase;
  font-weight: 500;
  letter-spacing: 1px;
}

.list-group .badge-pill {
  margin-top: 3px;
}

.list-group.bordered {
  border: 1px solid #ebebeb;
}

.list-group-item {
  font-weight: 0.875rem;
  border: 0;
  border-bottom: 1px solid #f3f3f3;
  margin-bottom: 0;
  border-radius: 0 !important;
}

.list-group-item.active, .list-group-item.active:hover, .list-group-item.active:focus {
  background-color: #33cabb;
  border-color: #33cabb;
}

.list-group-item.active .list-group-item-text, .list-group-item.active:hover .list-group-item-text, .list-group-item.active:focus .list-group-item-text {
  color: #fff;
}

.list-group-item:last-child {
  border-bottom: 0;
}

.list-group-item .media {
  margin-bottom: 0;
}

.list-group-item-heading {
  font-weight: 400;
}

a.list-group-item:focus,
a.list-group-item:hover,
button.list-group-item:focus,
button.list-group-item:hover {
  background-color: #f9fafb;
}

.nav-item i {
  width: 1.28571429em;
  text-align: center;
}

.nav-link {
  font-weight: 400;
  color: #8b95a5;
  line-height: 33px;
  padding: 0px 12px;
  white-space: nowrap;
}

.nav-link:hover, .nav-link:focus, .nav-link.active {
  color: #4d5259;
}

.nav-link.disabled {
  color: #8b95a5;
  opacity: .7;
}

.nav-primary .nav-link:not(.disabled):hover, .nav-primary .nav-link:not(.disabled):focus {
  color: #33cabb;
}

.nav:not(.nav-tabs) .nav-link > * {
  margin: 0 4px;
}

.nav:not(.nav-tabs) .nav-link > *:first-child {
  margin-left: 0;
}

.nav:not(.nav-tabs) .nav-link > *:last-child {
  margin-right: 0;
}

.nav:not(.nav-tabs) .nav-link > *:not(.badge) {
  -webkit-box-flex: 1;
          flex-grow: 1;
}

.nav-action {
  color: #8b95a5;
  font-size: 14px;
  opacity: 0;
  margin: 0 4px;
}

.nav-action:hover {
  color: #4d5259;
}

.nav-item:hover .nav-action {
  opacity: 1;
}

.nav-actions-visible .nav-action {
  opacity: 1;
}

.nav-pills::after {
  display: none;
}

.nav-pills .nav-link {
  border-radius: 2px;
}

.nav-pills .nav-item.show .nav-link,
.nav-pills .nav-link.active {
  color: #4d5259;
  background-color: #f9fafb;
}

.nav.flex-column .nav-item {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  height: 29px;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.nav.flex-column .nav-item + .nav-item {
  margin: 4px 0;
}

.nav.flex-column .nav-item:first-child {
  margin-bottom: 4px;
}

.nav.flex-column .nav-item:last-child {
  margin-bottom: 0;
}

.nav.flex-column .nav-item.active .nav-link {
  color: #4d5259;
}

.nav.flex-column .nav-item.disabled {
  background-color: transparent;
  opacity: .7;
  cursor: not-allowed;
}

.nav.flex-column .nav-item.disabled .nav-link {
  color: #8b95a5;
}

.nav.flex-column .nav-item.disabled > * {
  cursor: not-allowed;
}

.nav.flex-column .nav-link {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  padding: 0;
}

.nav.flex-column.nav-pills .nav-item {
  padding: 0;
  height: 36px;
  border-radius: 2px;
}

.nav.flex-column.nav-pills .nav-item.active, .nav.flex-column.nav-pills .nav-item:hover {
  background-color: #f9fafb;
}

.nav.flex-column.nav-pills .nav-item .nav-link {
  padding-left: 18px;
  padding-right: 18px;
}

.nav-dot-separated .nav-item,
.nav-dot-separated > .nav-link {
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-align: center;
          align-items: center;
  margin-left: 0 !important;
}

.nav-dot-separated .nav-item::after,
.nav-dot-separated > .nav-link::after {
  content: '•';
  vertical-align: middle;
  color: #616a78;
  opacity: 0.4;
  cursor: default;
}

.nav-dot-separated .nav-item:last-child::after,
.nav-dot-separated > .nav-link:last-child::after {
  display: none;
}

.nav-dot-separated .nav-item:hover::after,
.nav-dot-separated > .nav-link:hover::after {
  color: #616a78;
}

.nav-dot-separated > .nav-link {
  padding-right: 0;
}

.nav-dot-separated > .nav-link::after {
  padding-left: inherit;
}

.nav-dotted .nav-link {
  line-height: normal;
  padding: 0 4px;
  margin-left: 8px;
  margin-right: 8px;
  border-bottom: 1px dotted #959daa;
}

.nav-sm .nav-item {
  height: 29px !important;
}

.nav-sm .nav-link {
  line-height: 29px;
}

.nav-lg .nav-item {
  height: 48px !important;
  font-size: 14px;
}

.nav-lg .nav-link {
  line-height: 48px;
  font-weight: 300;
}

.nav.gap-0 .nav-link {
  padding: 0;
}

.nav.gap-1 .nav-link {
  padding: 0 4px;
}

.nav.gap-2 .nav-link {
  padding: 0 8px;
}

.nav.gap-3 .nav-link {
  padding: 0 12px;
}

.nav.gap-4 .nav-link {
  padding: 0 16px;
}

.nav.gap-5 .nav-link {
  padding: 0 20px;
}

.nav.no-gutters > .nav-link:first-child,
.nav.no-gutters .nav-item:first-child .nav-link {
  padding-left: 0;
}

.nav.no-gutters > .nav-link:last-child,
.nav.no-gutters .nav-item:last-child .nav-link {
  padding-right: 0;
}

.page-link {
  color: #8b95a5;
  font-weight: 400;
  border-color: #ebebeb;
  padding: 0 8px;
  margin: 0 3px;
  min-width: 31px;
  line-height: 29px;
  text-align: center;
  border-radius: 2px !important;
}

.page-link:hover, .page-link:focus {
  background-color: #f9fafb;
  color: #4d5259;
}

.page-link span {
  font-size: 75%;
}

.page-item.disabled .page-link, .page-item.disabled .page-link:focus, .page-item.disabled .page-link:hover {
  opacity: 0.6;
}

.page-item.active .page-link, .page-item.active .page-link:focus, .page-item.active .page-link:hover {
  background-color: #33cabb;
  border-color: #33cabb;
  font-weight: 500;
}

.pagination.no-border .page-link {
  border: none;
}

.pagination.no-border .page-link:hover, .pagination.no-border .page-link:focus {
  border-color: #f9fafb;
}

.pagination.no-gutters {
  margin-left: 1px;
}

.pagination.no-gutters .page-link {
  margin: 0;
  margin-left: -1px;
  border-radius: 0 !important;
}

.pagination-circle .page-link {
  border-radius: 50% !important;
}

.pagination-sm .page-link {
  padding: 0;
  min-width: 26px;
  line-height: 24px;
}

.pagination-lg .page-link {
  padding: 0;
  min-width: 38px;
  line-height: 36px;
}

.pagination-primary .page-item.active .page-link, .pagination-primary .page-item.active .page-link:focus, .pagination-primary .page-item.active .page-link:hover {
  background-color: #33cabb;
  border-color: #33cabb;
}

.pagination-secondary .page-item.active .page-link, .pagination-secondary .page-item.active .page-link:focus, .pagination-secondary .page-item.active .page-link:hover {
  background-color: #e4e7ea;
  border-color: #e4e7ea;
}

.pagination-success .page-item.active .page-link, .pagination-success .page-item.active .page-link:focus, .pagination-success .page-item.active .page-link:hover {
  background-color: #15c377;
  border-color: #15c377;
}

.pagination-info .page-item.active .page-link, .pagination-info .page-item.active .page-link:focus, .pagination-info .page-item.active .page-link:hover {
  background-color: #48b0f7;
  border-color: #48b0f7;
}

.pagination-warning .page-item.active .page-link, .pagination-warning .page-item.active .page-link:focus, .pagination-warning .page-item.active .page-link:hover {
  background-color: #faa64b;
  border-color: #faa64b;
}

.pagination-danger .page-item.active .page-link, .pagination-danger .page-item.active .page-link:focus, .pagination-danger .page-item.active .page-link:hover {
  background-color: #f96868;
  border-color: #f96868;
}

.pagination-pink .page-item.active .page-link, .pagination-pink .page-item.active .page-link:focus, .pagination-pink .page-item.active .page-link:hover {
  background-color: #f96197;
  border-color: #f96197;
}

.pagination-purple .page-item.active .page-link, .pagination-purple .page-item.active .page-link:focus, .pagination-purple .page-item.active .page-link:hover {
  background-color: #926dde;
  border-color: #926dde;
}

.pagination-brown .page-item.active .page-link, .pagination-brown .page-item.active .page-link:focus, .pagination-brown .page-item.active .page-link:hover {
  background-color: #8d6658;
  border-color: #8d6658;
}

.pagination-cyan .page-item.active .page-link, .pagination-cyan .page-item.active .page-link:focus, .pagination-cyan .page-item.active .page-link:hover {
  background-color: #57c7d4;
  border-color: #57c7d4;
}

.pagination-yellow .page-item.active .page-link, .pagination-yellow .page-item.active .page-link:focus, .pagination-yellow .page-item.active .page-link:hover {
  background-color: #fcc525;
  border-color: #fcc525;
}

.pagination-gray .page-item.active .page-link, .pagination-gray .page-item.active .page-link:focus, .pagination-gray .page-item.active .page-link:hover {
  background-color: #868e96;
  border-color: #868e96;
}

.pagination-dark .page-item.active .page-link, .pagination-dark .page-item.active .page-link:focus, .pagination-dark .page-item.active .page-link:hover {
  background-color: #465161;
  border-color: #465161;
}

.pagination-secondary .page-item.active .page-link, .pagination-secondary .page-item.active .page-link:focus, .pagination-secondary .page-item.active .page-link:hover {
  color: #8b95a5;
}

.timeline {
  position: relative;
  list-style: none;
  margin: 0 auto 30px;
  padding-left: 0;
  width: 90%;
  z-index: 1;
}

.timeline-block {
  display: -webkit-box;
  display: flex;
}

.timeline-detail {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  padding-bottom: 30px;
}

@media (max-width: 767px) {
  .timeline-detail {
    display: none;
  }
}

.timeline-point {
  position: relative;
  flex-shrink: 0;
  -webkit-box-flex: 0;
          flex-grow: 0;
  -webkit-box-ordinal-group: 3;
          order: 2;
  width: 96px;
  padding-bottom: 30px;
  text-align: center;
}

.timeline-point::before {
  content: '';
  position: absolute;
  top: 9px;
  left: 50%;
  bottom: -9px;
  width: 1px;
  margin-left: -1px;
  z-index: -1;
  background-color: #ebebeb;
}

.timeline .timeline-block:first-child .timeline-point::after {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  display: inline-block;
  width: 4px;
  height: 4px;
  margin-left: -2px;
  border-radius: 50%;
}

.timeline .timeline-block:last-child .timeline-point::after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: 0;
  display: inline-block;
  width: 6px;
  height: 6px;
  margin-left: -3px;
  border-radius: 50%;
}

.timeline-point-primary::before {
  background-color: #33cabb;
}

.timeline-point-secondary::before {
  background-color: #e4e7ea;
}

.timeline-point-success::before {
  background-color: #15c377;
}

.timeline-point-info::before {
  background-color: #48b0f7;
}

.timeline-point-warning::before {
  background-color: #faa64b;
}

.timeline-point-danger::before {
  background-color: #f96868;
}

.timeline-point-pink::before {
  background-color: #f96197;
}

.timeline-point-purple::before {
  background-color: #926dde;
}

.timeline-point-brown::before {
  background-color: #8d6658;
}

.timeline-point-cyan::before {
  background-color: #57c7d4;
}

.timeline-point-yellow::before {
  background-color: #fcc525;
}

.timeline-point-gray::before {
  background-color: #868e96;
}

.timeline-point-dark::before {
  background-color: #465161;
}

.timeline-content {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
  margin-bottom: 60px;
}

.timeline-content .card {
  margin-bottom: 0;
}

.timeline-period {
  position: relative;
  padding: 30px 0;
  text-align: center;
  font-size: 20px;
  font-weight: 300;
  color: #8b95a5;
}

.timeline-period::after {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  display: inline-block;
  width: 6px;
  height: 6px;
  margin-left: -3px;
  background-color: #ebebeb;
  border-radius: 50%;
}

.timeline-period time {
  font-size: inherit;
  font-weight: inherit;
}

.timeline .timeline-period:first-child::after {
  display: none;
}

@media (max-width: 991px) {
  .timeline .timeline-block .timeline-detail {
    -webkit-box-ordinal-group: 2;
            order: 1;
    text-align: right;
  }
  .timeline .timeline-block .timeline-content {
    -webkit-box-ordinal-group: 4;
            order: 3;
  }
}

@media (min-width: 992px) {
  .timeline .timeline-block:nth-child(even) .timeline-detail {
    -webkit-box-ordinal-group: 2;
            order: 1;
    text-align: right;
  }
  .timeline .timeline-block:nth-child(even) .timeline-content {
    -webkit-box-ordinal-group: 4;
            order: 3;
  }
  .timeline .timeline-block:nth-child(odd) .timeline-detail {
    -webkit-box-ordinal-group: 4;
            order: 3;
    text-align: left;
  }
  .timeline .timeline-block:nth-child(odd) .timeline-content {
    -webkit-box-ordinal-group: 2;
            order: 1;
  }
}

.timeline.timeline-content-left .timeline-block .timeline-detail {
  -webkit-box-ordinal-group: 4;
          order: 3;
  text-align: left;
}

.timeline.timeline-content-left .timeline-block .timeline-content {
  -webkit-box-ordinal-group: 2;
          order: 1;
}

.timeline.timeline-content-right .timeline-block .timeline-detail {
  -webkit-box-ordinal-group: 2;
          order: 1;
  text-align: right;
}

.timeline.timeline-content-right .timeline-block .timeline-content {
  -webkit-box-ordinal-group: 4;
          order: 3;
}

.timeline-activity .timeline-content {
  margin-bottom: 10px;
}

.timeline-point-xs .timeline-point {
  width: 32px;
}

.timeline-point-sm .timeline-point {
  width: 64px;
}

.timeline-point-lg .timeline-point {
  width: 128px;
}

.timeline-point-xl .timeline-point {
  width: 256px;
}

[data-provide~="fullscreen"] .fullscreen-active {
  display: none;
}

[data-provide~="fullscreen"].is-fullscreen .fullscreen-default {
  display: none;
}

[data-provide~="fullscreen"].is-fullscreen .fullscreen-active {
  display: inline-block;
}

.pace {
  -webkit-pointer-events: none;
  pointer-events: none;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  display: none;
}

.pace .pace-progress {
  background: #33cabb;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 2px;
  z-index: 2001;
}

.pace-inactive {
  display: none;
}

body[data-provide~="pace"] .pace {
  display: block;
}

body[data-provide~="pace"] .pace-inactive {
  display: none;
}

.pace-primary .pace-progress {
  background: #33cabb;
}

.pace-secondary .pace-progress {
  background: #e4e7ea;
}

.pace-success .pace-progress {
  background: #15c377;
}

.pace-info .pace-progress {
  background: #48b0f7;
}

.pace-warning .pace-progress {
  background: #faa64b;
}

.pace-danger .pace-progress {
  background: #f96868;
}

.pace-pink .pace-progress {
  background: #f96197;
}

.pace-purple .pace-progress {
  background: #926dde;
}

.pace-brown .pace-progress {
  background: #8d6658;
}

.pace-cyan .pace-progress {
  background: #57c7d4;
}

.pace-yellow .pace-progress {
  background: #fcc525;
}

.pace-gray .pace-progress {
  background: #868e96;
}

.pace-dark .pace-progress {
  background: #465161;
}

.mapael {
  /* Reset Zoom button first */
  /* Then Zoom In button */
  /* Then Zoom Out button */
}

.mapael .map {
  position: relative;
}

.mapael .zoomButton {
  background-color: #fff;
  border: 1px solid #f1f2f3;
  color: #4d5259;
  width: 20px;
  height: 20px;
  line-height: 20px;
  text-align: center;
  border-radius: 3px;
  cursor: pointer;
  position: absolute;
  top: 0;
  font-weight: bold;
  left: 10px;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.mapael .zoomButton:hover {
  background-color: #f9fafb;
}

.mapael .zoomReset {
  top: 10px;
}

.mapael .zoomIn {
  top: 34px;
}

.mapael .zoomOut {
  top: 58px;
}

.mapael .mapTooltip {
  position: absolute;
  background-color: #474c4b;
  opacity: 0.70;
  border-radius: 3px;
  padding: 4px 8px;
  max-width: 200px;
  display: none;
  color: #fff;
  z-index: 1000;
}

svg.emojione {
  width: 18px;
  height: 18px;
  margin: 0 2px;
  vertical-align: text-bottom;
}

.fc button {
  font-weight: 400;
  height: 36px;
  line-height: 36px;
  padding: 0 12px;
  text-transform: capitalize;
}

.fc-state-default {
  border: 1px solid #ebebeb;
  background-color: #fcfdfe;
  background-image: none;
  color: #4d5259;
  text-shadow: none;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.fc-state-default:hover {
  background-color: #f5f6f7;
}

.fc-state-default.fc-corner-right {
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}

.fc-state-default.fc-corner-left {
  border-top-left-radius: 3px;
  border-bottom-left-radius: 3px;
}

.fc-state-active {
  background-color: #f5f6f7;
}

.fc-state-disabled:hover {
  background-color: #fcfdfe;
}

.fc-unthemed .fc-content,
.fc-unthemed .fc-divider,
.fc-unthemed .fc-list-heading td,
.fc-unthemed .fc-list-view,
.fc-unthemed .fc-popover,
.fc-unthemed .fc-row,
.fc-unthemed tbody,
.fc-unthemed td,
.fc-unthemed th,
.fc-unthemed thead {
  border-color: #ebebeb;
}

.fc th {
  font-weight: 400;
  border: none;
  padding: 12px 0;
}

.fc-event {
  font-size: 12px;
  border: none;
  color: #fff;
  border-radius: 2px;
  padding: 2px 6px;
  opacity: .9;
  -webkit-transition: none;
  transition: none;
}

.fc-event:hover {
  opacity: 1;
}

.fc-event:not(.badge),
.fc-event-dot:not(.badge) {
  background-color: #33cabb;
}

.fc-content {
  color: #fff;
}

.fc-day-grid-event .fc-time {
  font-weight: 500;
  padding-right: 4px;
}

.fc-day-grid-event {
  margin-top: 2px;
  margin-bottom: 2px;
}

.fc-unthemed .fc-divider,
.fc-unthemed .fc-list-heading td,
.fc-unthemed .fc-popover .fc-header {
  background-color: #f5f6f7;
}

.fc-popover .fc-header {
  padding: 4px 8px;
}

.fc-unthemed .fc-popover .fc-header .fc-close {
  color: #8b95a5;
  opacity: .7;
  margin-top: 6px;
  -webkit-transition: .3s;
  transition: .3s;
}

.fc-unthemed .fc-popover .fc-header .fc-close:hover {
  opacity: 1;
}

.fc-toolbar h2 {
  font-family: Roboto, sans-serif;
  font-size: 18px;
  color: #4d5259;
  padding-top: 8px;
}

.fc-head-container {
  border: none !important;
}

.pswp__caption__center {
  text-align: center;
}

.ps-container {
  position: relative;
}

.ps-container > .ps-scrollbar-x-rail {
  height: 3px;
}

.ps-container > .ps-scrollbar-x-rail > .ps-scrollbar-x {
  background-color: rgba(0, 0, 0, 0.25);
  border-radius: 0;
  height: 3px;
  bottom: 0;
}

.ps-container > .ps-scrollbar-x-rail:hover,
.ps-container > .ps-scrollbar-x-rail:hover > .ps-scrollbar-x, .ps-container > .ps-scrollbar-x-rail:active,
.ps-container > .ps-scrollbar-x-rail:active > .ps-scrollbar-x {
  height: 7px;
}

.ps-container > .ps-scrollbar-y-rail {
  width: 3px;
  right: 2px !important;
}

.ps-container > .ps-scrollbar-y-rail > .ps-scrollbar-y {
  background-color: rgba(0, 0, 0, 0.25);
  border-radius: 0;
  width: 3px;
  right: 0;
}

.ps-container > .ps-scrollbar-y-rail:hover,
.ps-container > .ps-scrollbar-y-rail:hover > .ps-scrollbar-y, .ps-container > .ps-scrollbar-y-rail:active,
.ps-container > .ps-scrollbar-y-rail:active > .ps-scrollbar-y {
  width: 7px;
}

.ps-container.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail,
.ps-container.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail {
  background-color: rgba(0, 0, 0, 0.1);
}

.ps-container:hover.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail > .ps-scrollbar-x,
.ps-container.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail > .ps-scrollbar-x {
  background-color: rgba(0, 0, 0, 0.4);
  height: 7px;
}

.ps-container:hover.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail > .ps-scrollbar-y,
.ps-container.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail > .ps-scrollbar-y {
  background-color: rgba(0, 0, 0, 0.4);
  width: 7px;
}

.ps-container:hover > .ps-scrollbar-x-rail:hover,
.ps-container:hover > .ps-scrollbar-y-rail:hover,
.ps-container.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail,
.ps-container.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail,
.ps-container:hover.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail,
.ps-container:hover.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail {
  background-color: rgba(0, 0, 0, 0.1);
}

.ps-container:hover > .ps-scrollbar-x-rail:hover > .ps-scrollbar-x,
.ps-container:hover > .ps-scrollbar-y-rail:hover > .ps-scrollbar-y,
.ps-container.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail > .ps-scrollbar-x,
.ps-container.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail > .ps-scrollbar-y,
.ps-container:hover.ps-in-scrolling.ps-x > .ps-scrollbar-x-rail > .ps-scrollbar-x,
.ps-container:hover.ps-in-scrolling.ps-y > .ps-scrollbar-y-rail > .ps-scrollbar-y {
  background-color: rgba(0, 0, 0, 0.3);
}

.swal2-modal .swal2-title {
  font-family: Roboto, sans-serif;
  font-size: 22px;
  font-weight: 400;
  letter-spacing: .5px;
  color: #313944;
}

.swal2-modal .swal2-content {
  color: #4d5259;
  font-size: 1rem;
}

.swal2-modal .swal2-confirm,
.swal2-modal .swal2-cancel {
  min-width: 80px;
}

.swal2-modal button + button {
  margin-left: 12px;
}

.shepherd-element.shepherd-theme-arrows-plain-buttons.shepherd-has-title .shepherd-content header {
  background-color: #f5f6f7;
}

.shepherd-element.shepherd-theme-arrows-plain-buttons.shepherd-has-title .shepherd-content header h3 {
  font-size: 16px;
}

.shepherd-element.shepherd-theme-arrows-plain-buttons.shepherd-element-attached-top.shepherd-element-attached-center.shepherd-has-title .shepherd-content:before,
.shepherd-element.shepherd-theme-arrows-plain-buttons.shepherd-element-attached-top.shepherd-element-attached-left.shepherd-target-attached-bottom.shepherd-has-title .shepherd-content:before,
.shepherd-element.shepherd-theme-arrows-plain-buttons.shepherd-element-attached-top.shepherd-element-attached-right.shepherd-target-attached-bottom.shepherd-has-title .shepherd-content:before {
  border-bottom-color: #f5f6f7;
}

.shepherd-element.shepherd-theme-arrows-plain-buttons .shepherd-content {
  font-size: 14px;
  line-height: 24px;
  width: 400px;
  max-width: calc(100% - 32px);
  -webkit-filter: none;
          filter: none;
  border: 1px solid #f1f2f3;
  border-radius: 3px;
  -webkit-box-shadow: 0 0 40px rgba(0, 0, 0, 0.06);
          box-shadow: 0 0 40px rgba(0, 0, 0, 0.06);
}

.shepherd-element.shepherd-theme-arrows-plain-buttons .shepherd-content::before {
  border-width: 12px;
}

.shepherd-element.shepherd-theme-arrows-plain-buttons .shepherd-content footer .shepherd-buttons li .shepherd-button {
  color: #fff;
}

.shepherd-element.shepherd-theme-arrows-plain-buttons .shepherd-content .shepherd-text p {
  line-height: inherit;
}

.backdrop-tour {
  background-color: rgba(0, 0, 0, 0.3) !important;
  z-index: 999;
}

.shepherd-active .shepherd-step,
.shepherd-active .shepherd-target.shepherd-enabled {
  z-index: 1000;
}

.shepherd-active .shepherd-target .shepherd-target {
  z-index: 1001;
}

[data-provide~="sortable"] [draggable="true"] {
  cursor: move;
}

.sortable-placeholder {
  background-color: #f9fafb;
  border: 2px dashed #ebebeb;
}

.sortable-dot {
  display: inline-block;
  width: 5px;
  min-height: 20px;
  background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAG0lEQVQYV2NkYGD4z8DAwMgABXAGNgGwSgwVAFbmAgXQdISfAAAAAElFTkSuQmCC);
  cursor: move;
  cursor: -webkit-zoom-grabbing;
  opacity: .6;
}

.lity-close {
  font-family: Roboto, sans-serif;
  font-weight: 300;
  opacity: .6;
  -webkit-transition: .5s;
  transition: .5s;
}

.lity-close:hover, .lity-close:focus, .lity-close:active {
  font-family: Roboto, sans-serif;
  font-weight: 300;
  opacity: 1;
}

.bootstrap-table {
  margin-bottom: 1rem;
}

.bootstrap-table .table {
  padding: 0 !important;
  border-bottom: 0;
}

.bootstrap-table .table td,
.bootstrap-table .table th {
  padding: .75rem !important;
}

.bootstrap-table .table > thead > tr > th {
  border-bottom-color: #ebebeb;
}

.bootstrap-table .table-separated {
  border-collapse: separate !important;
}

.bootstrap-table .table-separated > thead > tr > th {
  border-bottom: 0;
}

.bootstrap-table .table-sm th,
.bootstrap-table .table-sm td {
  padding: .5rem !important;
}

.bootstrap-table .table-lg th,
.bootstrap-table .table-lg td {
  padding: 1rem !important;
}

.fixed-table-container {
  border: none;
  border-radius: 0;
}

.fixed-table-container thead th,
.fixed-table-container tbody td {
  border-left: none;
}

.fixed-table-container thead th {
  position: relative;
}

.fixed-table-container thead th .th-inner {
  background-image: none !important;
  padding: 0;
}

.fixed-table-container thead th .th-inner::before, .fixed-table-container thead th .th-inner::after {
  content: '';
  position: absolute;
  right: 12px;
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
}

.fixed-table-container thead th .both::before {
  border-bottom: 8px solid #ebebeb;
  bottom: 55%;
}

.fixed-table-container thead th .both::after {
  border-top: 8px solid #ebebeb;
  top: 55%;
}

.fixed-table-container thead th .desc::before {
  display: none;
}

.fixed-table-container thead th .desc::after {
  border-top-color: #8b95a5;
}

.fixed-table-container thead th .asc::before {
  border-bottom-color: #8b95a5;
}

.fixed-table-container thead th .asc::after {
  display: none;
}

.fixed-table-body .table > thead > tr > th {
  border-bottom: none;
}

.fixed-table-body .card-view .title {
  font-weight: 500;
}

.fixed-table-header .table th {
  padding: 0 !important;
}

.fixed-table-header .table .th-inner {
  padding: .75rem;
}

table.dataTable {
  width: 100% !important;
}

.dataTables_wrapper {
  padding-left: 0;
  padding-right: 0;
}

.dataTables_wrapper tfoot .form-control {
  width: auto;
}

.jsgrid-cell {
  padding: .625rem;
}

.jsgrid-header-cell {
  font-weight: 400;
  padding: .75rem;
}

.jsgrid-grid-header,
.jsgrid-header-row > .jsgrid-header-cell {
  background-color: #f9fafb;
}

.jsgrid-edit-row > .jsgrid-cell,
.jsgrid-filter-row > .jsgrid-cell,
.jsgrid-grid-body,
.jsgrid-grid-header,
.jsgrid-header-row > .jsgrid-header-cell,
.jsgrid-insert-row > .jsgrid-cell {
  border-color: #f1f2f3;
}

.jsgrid-alt-row > .jsgrid-cell {
  background: #fcfdfe;
}

.jsgrid-selected-row > .jsgrid-cell {
  background: #f3f9ff;
  border-color: #f1f2f3;
}

.jsgrid-grid-body tr:last-child .jsgrid-cell {
  border-bottom: 0;
}

.jsgrid input,
.jsgrid select,
.jsgrid textarea {
  border: 1px solid #ebebeb;
  border-radius: 2px;
  color: #8b95a5;
  padding: 5px 8px;
  font-size: 14px;
  line-height: 20px;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.jsgrid input:focus,
.jsgrid select:focus,
.jsgrid textarea:focus {
  border-color: #83e0d7;
  color: #4d5259;
  outline: none;
}

.jsgrid select {
  height: 32px;
}

.jsgrid-pager {
  text-align: center;
}

.jsgrid-pager a {
  color: #48b0f7;
}

.jsgrid-pager-current-page {
  font-weight: 500;
}

.swiper-container {
  width: 100%;
  height: 100%;
}

.swiper-container .container,
.swiper-container [class^="col-"] {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}

.swiper-slide .card-shadowed {
  margin-left: 10px;
  margin-right: 10px;
}

.swiper-button-next,
.swiper-button-prev {
  background-image: none;
  color: #8b95a5;
  -webkit-transition: .4s;
  transition: .4s;
}

.swiper-button-next::before,
.swiper-button-prev::before {
  font-family: themify;
  font-size: 1.75rem;
  opacity: .7;
  -webkit-transition: .4s;
  transition: .4s;
}

.swiper-button-next:hover::before,
.swiper-button-prev:hover::before {
  opacity: 1;
}

.swiper-button-next::before {
  content: "\e649";
}

.swiper-button-prev::before {
  content: "\e64a";
}

.swiper-button-next {
  right: 0;
}

.swiper-button-prev {
  left: 0;
}

.swiper-button-hidden .swiper-button-next {
  right: -44px;
}

.swiper-button-hidden .swiper-button-prev {
  left: -44px;
}

.swiper-button-hidden:hover .swiper-button-next {
  right: 24px;
}

.swiper-button-hidden:hover .swiper-button-prev {
  left: 24px;
}

.swiper-button-box .swiper-button-next,
.swiper-button-box .swiper-button-prev {
  width: 44px;
  height: 64px;
  margin-top: -32px;
  line-height: 64px;
  text-align: center;
  color: #fff;
  background-color: rgba(0, 0, 0, 0.2);
  opacity: .6;
  -webkit-transition: .4s;
  transition: .4s;
}

.swiper-button-box .swiper-button-next::before,
.swiper-button-box .swiper-button-prev::before {
  font-family: themify;
  font-size: 1.75rem;
  opacity: 1;
}

.swiper-button-box .swiper-button-next:hover,
.swiper-button-box .swiper-button-prev:hover {
  opacity: 1;
}

@media (max-width: 991px) {
  .swiper-button-box .swiper-button-next,
  .swiper-button-box .swiper-button-prev {
    width: 24px;
    height: 30px;
    margin-top: -15px;
    line-height: 30px;
  }
  .swiper-button-box .swiper-button-next::before,
  .swiper-button-box .swiper-button-prev::before {
    font-size: 1rem;
  }
}

.swiper-button-box .swiper-button-next {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}

.swiper-button-box .swiper-button-prev {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}

.swiper-button-box.swiper-button-hidden:hover .swiper-button-next {
  right: 0;
}

.swiper-button-box.swiper-button-hidden:hover .swiper-button-prev {
  left: 0;
}

.swiper-button-circular .swiper-button-next,
.swiper-button-circular .swiper-button-prev {
  width: 40px;
  height: 40px;
  line-height: 38px;
  text-align: center;
  border: 1px solid #fff;
  border-radius: 50%;
  background-color: #fff;
  color: #616a78;
  -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.swiper-button-circular .swiper-button-next:hover,
.swiper-button-circular .swiper-button-prev:hover {
  color: #4d5259;
  -webkit-box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
          box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
}

.swiper-button-circular .swiper-button-next::before,
.swiper-button-circular .swiper-button-prev::before {
  font-size: 14px;
}

.swiper-button-circular .swiper-button-prev {
  left: 5%;
}

.swiper-button-circular .swiper-button-next {
  right: 5%;
}

.swiper-button-circular .swiper-button-next::before {
  content: "\e649";
}

.swiper-button-circular .swiper-button-prev::before {
  content: "\e64a";
}

.swiper-pagination-outline .swiper-pagination-bullet:not(.swiper-pagination-bullet-active) {
  border: 1px solid rgba(0, 0, 0, 0.5);
  background: transparent;
}

.swiper-pagination-bullet {
  position: relative;
  background: #ccc;
  opacity: 1;
  width: 6px;
  height: 6px;
  -webkit-transition: .3s;
  transition: .3s;
}

.swiper-pagination-bullet:hover {
  background: #aaa;
}

.swiper-pagination-bullet::before, .swiper-pagination-bullet::after {
  position: absolute;
  left: -7px;
  display: inline-block;
  width: 20px;
  height: 13px;
  content: "";
  cursor: pointer;
}

.swiper-pagination-bullet::before {
  top: -10px;
}

.swiper-pagination-bullet::after {
  bottom: -10px;
}

.swiper-pagination-bullet-active {
  background: #777;
}

.swiper-container-horizontal > .swiper-pagination-bullets .swiper-pagination-bullet {
  margin: 0 7px;
}

.swiper-pagination-outside .swiper-wrapper {
  padding-bottom: 30px;
}

.swiper-pagination-outside.swiper-container-horizontal > .swiper-pagination-bullets {
  bottom: 0;
}

.swiper-container[data-centered-slides="true"] .swiper-slide {
  opacity: .1;
  -webkit-transition: opacity 1s;
  transition: opacity 1s;
}

.swiper-container[data-centered-slides="true"] .swiper-slide-active,
.swiper-container[data-centered-slides="true"] .swiper-slide-duplicate-active {
  opacity: 1;
}

@media (max-width: 767px) {
  .swiper-button-next,
  .swiper-button-prev {
    display: none;
  }
}

.peity {
  vertical-align: middle;
}

.jqstooltip {
  background-color: #323232 !important;
  font-size: 13px !important;
  padding: 4px 6px !important;
  color: #fff !important;
  overflow: hidden !important;
  text-align: center !important;
  border: none !important;
  max-width: 400px !important;
  max-height: 400px !important;
  -webkit-box-sizing: content-box;
          box-sizing: content-box;
}

.jqsfield {
  font-size: 13px !important;
  color: #fff !important;
  /*set the text color here */
}

[data-provide="easypie"] {
  display: -webkit-inline-box;
  display: inline-flex;
  text-align: center;
  position: relative;
}

.easypie-data {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 10px;
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center;
}

.morris-hover {
  z-index: 900;
}

.morris-hover.morris-default-style {
  border-radius: 2px;
  padding: 8px 12px;
  background: rgba(255, 255, 255, 0.95);
  font-family: inherit;
  font-weight: 400;
  border: 1px solid #f1f2f3;
  -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.morris-hover.morris-default-style .morris-hover-point {
  text-align: left;
}

.bootstrap-select.btn-group .dropdown-toggle {
  letter-spacing: 0;
  font-weight: 300;
  padding: 5px 12px;
  padding-right: 25px;
}

.bootstrap-select.btn-group .dropdown-toggle::after {
  opacity: .7;
}

.bootstrap-select.btn-group .dropdown-toggle:focus {
  outline: none !important;
}

.bootstrap-select.btn-group .dropdown-toggle .filter-option i {
  margin-right: 8px;
}

.bootstrap-select.btn-group.standalone {
  vertical-align: text-bottom;
}

.bootstrap-select.btn-group.standalone .dropdown-toggle {
  border: 0;
  padding-top: 0;
  padding-bottom: 0;
}

.bootstrap-select.btn-group.standalone .dropdown-toggle .filter-option {
  width: auto;
}

.bootstrap-select.btn-group .dropdown-item.divider {
  height: 1px;
  background-color: #f1f2f3;
  margin: 4px 0;
  padding: 0;
}

.bootstrap-select.btn-group .dropdown-item.dropdown-header {
  text-transform: uppercase;
  color: #8b95a5;
  font-size: 12px;
  margin-bottom: 0;
  padding: 8px;
  padding-bottom: 0;
  letter-spacing: .25px;
  opacity: 0.8;
}

.bootstrap-select.btn-group .dropdown-item.dropdown-header:hover {
  background-color: transparent;
}

.bootstrap-select.btn-group .dropdown-item.selected {
  color: #4d5259;
  background-color: #f5f6f7;
}

.bootstrap-select.btn-group .dropdown-item.disabled:hover,
.bootstrap-select.btn-group .dropdown-item.disabled .dropdown-item-inner:hover {
  background-color: transparent;
  cursor: not-allowed;
}

.bootstrap-select.btn-group .dropdown-menu a.dropdown-item span.dropdown-item-inner.opt {
  padding-left: 8px;
}

.bootstrap-select.btn-group .popover-title {
  color: #8b95a5;
  padding: 6px 16px;
  background-color: #fcfdfe;
}

.bootstrap-select.btn-group .no-results {
  margin: 0;
  padding: 6px 20px;
}

.dropdown-item-inner {
  outline: none;
}

.bs-searchbox .form-control {
  line-height: 20px;
  -font-size: 13px;
  padding: 4px 8px 3px;
}

.bootstrap-select > .dropdown-toggle.bs-placeholder, .bootstrap-select > .dropdown-toggle.bs-placeholder:active, .bootstrap-select > .dropdown-toggle.bs-placeholder:focus, .bootstrap-select > .dropdown-toggle.bs-placeholder:hover {
  color: #c9ccce;
}

.input-group .bootstrap-select.form-control:not([class*="col-"]) {
  width: auto;
}

.datepicker.dropdown-menu {
  -webkit-transform: scale(1, 1);
          transform: scale(1, 1);
  -webkit-transition: 0s;
  transition: 0s;
  padding: 6px;
}

.datepicker-dropdown:before {
  border-bottom-color: #ebebeb;
}

.datepicker-dropdown.datepicker-orient-top:before {
  border-top-color: #ebebeb;
}

.datepicker > div.datepicker-days {
  display: block;
}

.datepicker table tr td,
.datepicker table tr th,
.datepicker table tr td span {
  border-radius: 0;
  font-size: 12px;
  color: #4d5259;
}

.datepicker table tr th {
  font-weight: 500;
}

.datepicker table tr td.old, .datepicker table tr td.new {
  color: #bcc0c5;
}

.datepicker table tr td.range {
  background-color: #f5f6f7;
  border-color: #f5f6f7;
}

.datepicker table tr td.range-start, .datepicker table tr td.range-end {
  background-color: #33cabb !important;
  border-color: #33cabb !important;
  text-shadow: none;
  border-radius: 100%;
}

.datepicker table tr td.range-start {
  border-top-right-radius: 0 !important;
  border-bottom-right-radius: 0 !important;
}

.datepicker table tr td.range-end {
  border-top-left-radius: 0 !important;
  border-bottom-left-radius: 0 !important;
}

.datepicker table tr td.range-start.range-end {
  border-radius: 100% !important;
}

.datepicker table tr .dow {
  padding-top: 0.5rem;
  padding-bottom: 1rem;
  font-size: 11px;
}

.datepicker .datepicker-switch {
  font-weight: 300;
  font-size: 13px;
}

.datepicker .datepicker-switch,
.datepicker .prev,
.datepicker .next {
  line-height: 36px;
}

.datepicker .prev,
.datepicker .next {
  font-size: 0;
}

.datepicker .prev::before,
.datepicker .next::before {
  font-family: themify;
  color: #8b95a5;
  font-size: 12px;
  vertical-align: top;
}

.datepicker .prev::before {
  content: "\e629";
}

.datepicker .next::before {
  content: "\e628";
}

.datepicker table tr td.day:hover,
.datepicker table tr td.focused,
.datepicker tfoot tr th:hover,
.datepicker .datepicker-switch:hover,
.datepicker .next:hover,
.datepicker .prev:hover {
  background-color: #f9fafb;
}

.datepicker table tr td.active.active,
.datepicker table tr td.active.highlighted.active,
.datepicker table tr td.active.highlighted:active,
.datepicker table tr td.active:active {
  background-color: #33cabb;
  border-color: #33cabb;
  text-shadow: none;
  border-radius: 100%;
}

.datepicker table tr td span.active.active,
.datepicker table tr td span.active.disabled.active,
.datepicker table tr td span.active.disabled:active,
.datepicker table tr td span.active.disabled:hover.active,
.datepicker table tr td span.active.disabled:hover:active,
.datepicker table tr td span.active:active,
.datepicker table tr td span.active:hover.active,
.datepicker table tr td span.active:hover:active {
  background-color: #33cabb;
  border-color: #33cabb;
}

.datepicker table tr td.active.active.focus,
.datepicker table tr td.active.active:focus,
.datepicker table tr td.active.active:hover,
.datepicker table tr td.active.highlighted.active.focus,
.datepicker table tr td.active.highlighted.active:focus,
.datepicker table tr td.active.highlighted.active:hover,
.datepicker table tr td.active.highlighted:active.focus,
.datepicker table tr td.active.highlighted:active:focus,
.datepicker table tr td.active.highlighted:active:hover,
.datepicker table tr td.active:active.focus,
.datepicker table tr td.active:active:focus,
.datepicker table tr td.active:active:hover {
  background-color: #31c2b3;
  border-color: #31c2b3;
}

.datepicker table tr td.today {
  color: #4d5259;
  background-color: #f5f6f7;
  border-color: #f5f6f7;
}

.clockpicker.dropdown-menu {
  -webkit-transform: scale(1, 1);
          transform: scale(1, 1);
  padding: 6px;
  -webkit-transition: initial;
  transition: initial;
}

.clockpicker-button {
  color: #4d5259;
  padding: 3px;
  height: auto;
  font-size: .875rem;
}

.clockpicker-popover .popover-content {
  background-color: #f9fafb;
}

.clockpicker-plate {
  border-color: #ebebeb;
}

.bootstrap-timepicker-widget.dropdown-menu {
  -webkit-transform: scale(1, 1);
          transform: scale(1, 1);
  -webkit-transition: 0s;
  transition: 0s;
  padding: 6px;
}

.bootstrap-timepicker-widget.dropdown-menu:before {
  border-bottom-color: #ebebeb;
}

.bootstrap-timepicker-widget.timepicker-orient-bottom:before {
  border-top-color: #ebebeb;
}

.bootstrap-timepicker-widget table td input {
  border-radius: 2px;
  border: 1px solid #ebebeb;
  font-size: 0.875rem;
  height: 29px;
  width: 29px;
  line-height: 29px;
}

.bootstrap-timepicker-widget table td input:focus {
  outline: none;
}

.bootstrap-timepicker-widget table td a {
  color: #8b95a5;
  height: 29px;
  width: 29px;
  line-height: 29px;
  padding: 0;
}

.bootstrap-timepicker-widget table td a:hover {
  background-color: #fcfdfe;
  border-color: #ebebeb;
  border-radius: 2px;
}

.bootstrap-timepicker-widget .glyphicon {
  font-family: themify;
}

.bootstrap-timepicker-widget .glyphicon-chevron-up::before {
  content: "\e648";
}

.bootstrap-timepicker-widget .glyphicon-chevron-down::before {
  content: "\e64b";
}

.rating {
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
  list-style: none;
  margin: 0;
  padding: 0;
}

.rating input {
  display: none;
}

.rating label {
  color: #e3e4e5;
  cursor: pointer;
}

.rating label::before {
  margin-right: 5px;
  font-size: 20px;
  display: inline-block;
}

.rating .active,
.rating :checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
  color: #33cabb;
}

.rating-static {
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
          flex-direction: row;
}

.rating-static label {
  cursor: default;
}

.rating-static label:not(.active) {
  color: #e3e4e5 !important;
}

.rating-xs label::before {
  margin-right: 1px;
  font-size: 13px;
}

.rating-sm label::before {
  margin-right: 2px;
  font-size: 16px;
}

.rating-lg label::before {
  font-size: 24px;
}

.rating-remove {
  margin-left: 10px;
  margin-right: 10px;
  cursor: pointer;
  line-height: 20px;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

[data-has-rate="true"]:hover .rating-remove {
  opacity: .6;
  visibility: visible;
}

[data-has-rate="true"]:hover .rating-remove:hover {
  opacity: 1;
}

.rating-primary .active,
.rating-primary :checked ~ label,
.rating-primary label:hover,
.rating-primary label:hover ~ label {
  color: #33cabb;
}

.rating-secondary .active,
.rating-secondary :checked ~ label,
.rating-secondary label:hover,
.rating-secondary label:hover ~ label {
  color: #e4e7ea;
}

.rating-success .active,
.rating-success :checked ~ label,
.rating-success label:hover,
.rating-success label:hover ~ label {
  color: #15c377;
}

.rating-info .active,
.rating-info :checked ~ label,
.rating-info label:hover,
.rating-info label:hover ~ label {
  color: #48b0f7;
}

.rating-warning .active,
.rating-warning :checked ~ label,
.rating-warning label:hover,
.rating-warning label:hover ~ label {
  color: #faa64b;
}

.rating-danger .active,
.rating-danger :checked ~ label,
.rating-danger label:hover,
.rating-danger label:hover ~ label {
  color: #f96868;
}

.rating-pink .active,
.rating-pink :checked ~ label,
.rating-pink label:hover,
.rating-pink label:hover ~ label {
  color: #f96197;
}

.rating-purple .active,
.rating-purple :checked ~ label,
.rating-purple label:hover,
.rating-purple label:hover ~ label {
  color: #926dde;
}

.rating-brown .active,
.rating-brown :checked ~ label,
.rating-brown label:hover,
.rating-brown label:hover ~ label {
  color: #8d6658;
}

.rating-cyan .active,
.rating-cyan :checked ~ label,
.rating-cyan label:hover,
.rating-cyan label:hover ~ label {
  color: #57c7d4;
}

.rating-yellow .active,
.rating-yellow :checked ~ label,
.rating-yellow label:hover,
.rating-yellow label:hover ~ label {
  color: #fcc525;
}

.rating-gray .active,
.rating-gray :checked ~ label,
.rating-gray label:hover,
.rating-gray label:hover ~ label {
  color: #868e96;
}

.rating-dark .active,
.rating-dark :checked ~ label,
.rating-dark label:hover,
.rating-dark label:hover ~ label {
  color: #465161;
}

.toggler {
  color: #e3e4e5;
  cursor: pointer;
  font-size: 20px;
  margin-bottom: 0;
  line-height: 1;
}

.toggler i {
  -webkit-transition: color .2s linear;
  transition: color .2s linear;
}

.toggler input {
  display: none;
}

.toggler input:checked + i {
  color: #fcc525;
}

.toggler-primary input:checked + i {
  color: #33cabb;
}

.toggler-secondary input:checked + i {
  color: #e4e7ea;
}

.toggler-success input:checked + i {
  color: #15c377;
}

.toggler-info input:checked + i {
  color: #48b0f7;
}

.toggler-warning input:checked + i {
  color: #faa64b;
}

.toggler-danger input:checked + i {
  color: #f96868;
}

.toggler-pink input:checked + i {
  color: #f96197;
}

.toggler-purple input:checked + i {
  color: #926dde;
}

.toggler-brown input:checked + i {
  color: #8d6658;
}

.toggler-cyan input:checked + i {
  color: #57c7d4;
}

.toggler-yellow input:checked + i {
  color: #fcc525;
}

.toggler-gray input:checked + i {
  color: #868e96;
}

.toggler-dark input:checked + i {
  color: #465161;
}

.minicolors-panel {
  border-color: #ebebeb;
  -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.09);
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.09);
}

.minicolors-theme-bootstrap .minicolors-input-swatch {
  border-radius: 50%;
  width: 20px;
  height: 20px;
  border: none;
  top: 50%;
  left: 12px;
  -webkit-transform: translateY(-50%);
          transform: translateY(-50%);
}

.minicolors-theme-bootstrap .minicolors-swatches .minicolors-swatch {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border-color: #ebebeb;
}

.minicolors-swatches .minicolors-swatch {
  margin-right: 6px;
}

.minicolors input[type=hidden] + .minicolors-swatch {
  width: 20px;
  -webkit-transform: translateY(0);
          transform: translateY(0);
}

.minicolors-theme-bootstrap .minicolors-input {
  padding-left: 40px !important;
}

.form-type-line .minicolors-theme-bootstrap .minicolors-input-swatch,
.form-type-material .minicolors-theme-bootstrap .minicolors-input-swatch {
  left: 0;
}

.form-type-line .minicolors-theme-bootstrap .minicolors-input,
.form-type-material .minicolors-theme-bootstrap .minicolors-input {
  padding-left: 28px !important;
}

.color-selector {
  display: inline-block;
}

.color-selector label {
  position: relative;
  display: inline-block;
  width: 29px;
  height: 29px;
  border-radius: 50%;
  cursor: pointer;
  margin-right: 4px;
  margin-bottom: 4px;
}

.color-selector label.inverse {
  border: 1px solid #e3e4e5;
}

.color-selector label.inverse span::after {
  color: #4d5259;
}

.color-selector span {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 50%;
}

.color-selector span::after {
  content: "\e64c";
  font-family: themify;
  font-size: 1rem;
  font-weight: bold;
  display: block;
  color: #fff;
  width: 100%;
  text-align: center;
  position: absolute;
  top: 50%;
  -webkit-transform: translateY(-50%) scale(0, 0);
          transform: translateY(-50%) scale(0, 0);
  -webkit-transition: .2s;
  transition: .2s;
}

.color-selector input {
  opacity: 0;
}

.color-selector input:checked ~ span::after {
  -webkit-transform: translateY(-50%) scale(1, 1);
          transform: translateY(-50%) scale(1, 1);
}

.color-selector-sm label {
  width: 24px;
  height: 24px;
}

.color-selector-sm span::after {
  font-size: .875rem;
}

.color-selector-lg label {
  width: 36px;
  height: 36px;
}

.color-selector-lg span::after {
  font-size: 1.125rem;
}

[data-provide~="knob"] {
  outline: none !important;
}

.noUi-target {
  margin: 1rem 0;
}

.noUi-target.noUi-vertical {
  display: inline-block;
  margin: 0 1rem;
}

.noUi-target.noUi-connect,
.noUi-target .noUi-connect {
  background-color: #33cabb;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.noUi-background {
  background: #f5f6f7;
}

.noUi-handle {
  background-color: #fff;
  border-radius: 50%;
  cursor: pointer;
  -webkit-transform: scale(1);
          transform: scale(1);
  -webkit-box-shadow: none;
          box-shadow: none;
  outline: none;
  border: 1px solid #ebebeb;
  -webkit-transition: 0.2s ease-in-out;
  transition: 0.2s ease-in-out;
}

.noUi-handle::before, .noUi-handle::after {
  display: none;
}

.noUi-handle:hover {
  -webkit-transform: scale(1.1);
          transform: scale(1.1);
}

.noUi-handle:hover .noUi-tooltip {
  display: block;
}

.noUi-handle:active {
  -webkit-transform: scale(1.3);
          transform: scale(1.3);
}

.noUi-horizontal {
  height: 4px;
  border-radius: 4px;
  background: #f5f6f7;
  border: none;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.noUi-horizontal .noUi-handle {
  width: 20px;
  height: 20px;
  left: -10px;
  top: -8px;
}

.noUi-vertical {
  width: 4px;
  height: 180px;
  border-radius: 4px;
  background: #f5f6f7;
  border: none;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.noUi-vertical .noUi-handle {
  width: 20px;
  height: 20px;
  left: -8px;
  top: -10px;
}

.noUi-tooltip {
  background-color: #323232;
  font-size: 0.75rem;
  border: none;
  color: #fff;
  padding: 0 6px;
  height: 24px;
  line-height: 25px;
  display: none;
}

.noUi-horizontal .noUi-tooltip {
  -webkit-transform: translateX(-50%);
          transform: translateX(-50%);
}

.noUi-horizontal .noUi-handle-upper .noUi-tooltip {
  bottom: auto;
  top: -32px;
}

.slider-primary.noUi-connect,
.slider-primary .noUi-connect {
  background-color: #33cabb;
}

.slider-secondary.noUi-connect,
.slider-secondary .noUi-connect {
  background-color: #e4e7ea;
}

.slider-success.noUi-connect,
.slider-success .noUi-connect {
  background-color: #15c377;
}

.slider-info.noUi-connect,
.slider-info .noUi-connect {
  background-color: #48b0f7;
}

.slider-warning.noUi-connect,
.slider-warning .noUi-connect {
  background-color: #faa64b;
}

.slider-danger.noUi-connect,
.slider-danger .noUi-connect {
  background-color: #f96868;
}

.slider-pink.noUi-connect,
.slider-pink .noUi-connect {
  background-color: #f96197;
}

.slider-purple.noUi-connect,
.slider-purple .noUi-connect {
  background-color: #926dde;
}

.slider-brown.noUi-connect,
.slider-brown .noUi-connect {
  background-color: #8d6658;
}

.slider-cyan.noUi-connect,
.slider-cyan .noUi-connect {
  background-color: #57c7d4;
}

.slider-yellow.noUi-connect,
.slider-yellow .noUi-connect {
  background-color: #fcc525;
}

.slider-gray.noUi-connect,
.slider-gray .noUi-connect {
  background-color: #868e96;
}

.slider-dark.noUi-connect,
.slider-dark .noUi-connect {
  background-color: #465161;
}

.bootstrap-tagsinput {
  display: block;
  border: 1px solid #ebebeb;
  -webkit-box-shadow: none;
          box-shadow: none;
  padding: 12px 12px 0;
  min-height: 52px;
}

.bootstrap-tagsinput input {
  font-size: 0.875rem;
  font-weight: 400;
  color: #4d5259;
}

.bootstrap-tagsinput .badge {
  height: 24px;
  line-height: 24px;
  border-radius: 12px;
  padding: 0 0.75rem;
  font-size: 0.875rem;
  margin-right: 0.75rem;
  margin-bottom: 0.75rem;
}

.bootstrap-tagsinput .badge.badge-default, .bootstrap-tagsinput .badge.badge-secondary {
  color: #616a78;
}

.bootstrap-tagsinput .badge [data-role=remove] {
  font-weight: 300;
  opacity: 0.7;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.bootstrap-tagsinput .badge [data-role=remove]:hover {
  -webkit-box-shadow: none;
          box-shadow: none;
  opacity: 1;
}

.bootstrap-tagsinput .badge:not([class*="badge-"]) {
  color: #616a78;
  background-color: #f5f6f7;
}

.bootstrap-tagsinput .twitter-typeahead {
  display: inline-block !important;
}

.bootstrap-tagsinput .twitter-typeahead .tt-menu {
  margin-top: 20px;
  min-width: 180px;
}

.twitter-typeahead {
  display: block !important;
}

.tt-menu {
  background-color: #fff;
  right: 0;
  margin-top: 2px;
  border-radius: 2px;
  border: 1px solid #f1f2f3;
  -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
          box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
}

.tt-suggestion {
  display: block;
  margin: 4px;
  padding: 6px 12px;
  cursor: pointer;
  -webkit-transition: 0.15s linear;
  transition: 0.15s linear;
}

.tt-suggestion.tt-cursor, .tt-suggestion:hover, .tt-suggestion:focus {
  background-color: #f9fafb;
}

.typeahead-scrollable .tt-menu {
  max-height: 220px;
  overflow-y: auto;
}

.bootstrap-maxlength {
  border-radius: 0;
  margin: 1px 0;
}

.pwstrength {
  position: relative;
}

.pwstrength .progress {
  height: 3px;
}

.pwstrength [data-vertical] + .progress {
  position: absolute;
  height: 3px;
  margin-bottom: 0;
  -webkit-transform: rotate(-90deg);
          transform: rotate(-90deg);
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.form-control {
  border-color: #ebebeb;
  border-radius: 2px;
  -color: #8b95a5;
  padding: 5px 12px;
  -font-size: 14px;
  line-height: inherit;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.form-control:disabled, .form-control[readonly] {
  color: #8b95a5;
}

.form-control[readonly] {
  background-color: #fff;
}

.form-control:focus {
  -border-color: #83e0d7;
  -color: #4d5259;
  -webkit-box-shadow: 0 0 0 0.1rem rgba(51, 202, 187, 0.15);
          box-shadow: 0 0 0 0.1rem rgba(51, 202, 187, 0.15);
}

.form-control:focus[readonly] {
  -border-color: #ebebeb;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.form-control::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  -color: #c9ccce;
}

.form-control::-moz-placeholder {
  /* Firefox 19+ */
  -color: #c9ccce;
}

.form-control:-ms-input-placeholder {
  /* IE 10+ */
  -color: #c9ccce;
}

.form-control:-moz-placeholder {
  /* Firefox 18- */
  -color: #c9ccce;
}

.form-control option {
  font-weight: 300;
}

label {
  font-weight: 400;
  -font-size: 12px;
  letter-spacing: .5px;
}

label.require::after, label.required::after {
  content: '*';
  color: #f96868;
  font-weight: 500;
  margin-left: 8px;
}

select.form-control:not([size]):not([multiple]) {
  height: 36px;
}

.form-group small {
  color: #868e96;
  font-weight: 300;
  font-size: 85%;
  line-height: 1.4;
}

.form-group small.form-text {
  color: #8b95a5;
  font-size: 90%;
  padding-left: 0;
  line-height: 1.4;
}

.form-group label + small {
  padding-left: 10px;
}

.has-form-text:hover .form-text,
.has-form-text .form-control:focus + .form-text {
  opacity: 1;
  -webkit-transition: .5s;
  transition: .5s;
}

.has-form-text .form-text {
  opacity: 0;
  -webkit-transition: .5s;
  transition: .5s;
}

.checkbox label,
.radio label {
  font-weight: 300;
  letter-spacing: 0;
}

.checkbox input[type=checkbox],
.checkbox-inline input[type=checkbox],
.radio input[type=radio],
.radio-inline input[type=radio] {
  margin-top: 6px;
  vertical-align: top;
}

.form-control-plaintext {
  padding-top: 6px;
  padding-bottom: 6px;
  line-height: 24px;
}

.auto-expand {
  overflow: hidden;
  resize: none;
  -webkit-transition: .5s;
  transition: .5s;
}

.form-inline .form-group {
  margin-right: 1rem;
}

.custom-file {
  width: 100%;
}

.custom-file-control {
  border-radius: 2px;
  border-color: #ebebeb;
}

.custom-file-control::before {
  border-color: #ebebeb;
  background-color: #ebebeb;
  color: #8b95a5;
}

.custom-file-control::after {
  color: #4d5259;
  font-weight: 300;
}

.custom-file-control:lang(en)::after {
  content: attr(data-input-value);
}

.custom-select {
  border-color: #ebebeb;
}

.bootstrap-select.btn-group.show .dropdown-toggle {
  border-color: #83e0d7;
  color: #4d5259;
}

.input-group-text {
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-align: center;
          align-items: center;
  background-color: #f9fafb;
  border-color: #ebebeb;
  color: #8b95a5;
  font-weight: 300;
  border-radius: 2px;
  padding: 5px 12px;
  font-size: 14px;
  height: 100%;
}

.input-group-text .custom-control {
  margin-right: 0;
}

.input-group-btn .btn-group,
.input-group-btn .btn {
  height: 100%;
}

.input-group-input + .input-group-input {
  margin-left: 16px;
}

.form-control-lg,
.input-group-lg .form-control,
.input-group-lg > .input-group-addon,
.input-group-lg > .input-group-btn > .btn {
  line-height: 32px;
  font-size: 16px;
  padding: 7px 16px;
}

.form-control-sm,
.input-group-sm .form-control,
.input-group-sm > .input-group-addon,
.input-group-sm > .input-group-btn > .btn {
  line-height: 20px;
  font-size: 13px;
  padding: 4px 8px 3px;
}

.input-group-sm > .input-group-btn > select.btn:not([size]):not([multiple]),
.input-group-sm > select.form-control:not([size]):not([multiple]),
.input-group-sm > select.input-group-addon:not([size]):not([multiple]),
select.form-control-sm:not([size]):not([multiple]) {
  height: 29px;
}

.input-group-lg > .input-group-btn > select.btn:not([size]):not([multiple]),
.input-group-lg > select.form-control:not([size]):not([multiple]),
.input-group-lg > select.input-group-addon:not([size]):not([multiple]),
select.form-control-lg:not([size]):not([multiple]) {
  height: 48px;
}

.has-success .form-control {
  border-color: #15c377;
}

.has-success .form-control:focus {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.has-success .checkbox,
.has-success .checkbox-inline,
.has-success .custom-control,
.has-success .form-control-feedback,
.has-success .form-control-label,
.has-success .radio,
.has-success .radio-inline,
.has-success.checkbox label,
.has-success.checkbox-inline label,
.has-success.radio label,
.has-success.radio-inline label {
  color: #15c377;
}

.has-warning .form-control {
  border-color: #faa64b;
}

.has-warning .form-control:focus {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.has-warning .checkbox,
.has-warning .checkbox-inline,
.has-warning .custom-control,
.has-warning .form-control-feedback,
.has-warning .form-control-label,
.has-warning .radio,
.has-warning .radio-inline,
.has-warning.checkbox label,
.has-warning.checkbox-inline label,
.has-warning.radio label,
.has-warning.radio-inline label {
  color: #faa64b;
}

.has-danger .form-control {
  border-color: #f96868;
}

.has-danger .form-control:focus {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.has-danger .checkbox,
.has-danger .checkbox-inline,
.has-danger .custom-control,
.has-danger .form-control-feedback,
.has-danger .form-control-label,
.has-danger .radio,
.has-danger .radio-inline,
.has-danger.checkbox label,
.has-danger.checkbox-inline label,
.has-danger.radio label,
.has-danger.radio-inline label {
  color: #f96868;
}

.form-group .form-control-feedback {
  display: none;
}

.form-group .form-control-feedback ul {
  margin-bottom: .5rem;
}

.form-group.has-success .form-control-feedback, .form-group.has-warning .form-control-feedback, .form-group.has-danger .form-control-feedback {
  display: block;
}

.invalid-feedback {
  color: #f96868;
  font-size: 13px;
}

.custom-select.is-invalid,
.form-control.is-invalid,
.was-validated .custom-select:invalid,
.was-validated .form-control:invalid {
  border-color: #f96868 !important;
}

.custom-select.is-invalid:focus,
.form-control.is-invalid:focus,
.was-validated .custom-select:invalid:focus,
.was-validated .form-control:invalid:focus {
  -webkit-box-shadow: 0 0 0 0.1rem rgba(249, 104, 104, 0.15) !important;
          box-shadow: 0 0 0 0.1rem rgba(249, 104, 104, 0.15) !important;
}

.custom-select.is-valid,
.form-control.is-valid,
.was-validated .custom-select:valid,
.was-validated .form-control:valid {
  border-color: #15c377 !important;
}

.custom-select.is-valid:focus,
.form-control.is-valid:focus,
.was-validated .custom-select:valid:focus,
.was-validated .form-control:valid:focus {
  -webkit-box-shadow: 0 0 0 0.1rem rgba(21, 195, 119, 0.15) !important;
          box-shadow: 0 0 0 0.1rem rgba(21, 195, 119, 0.15) !important;
}

.form-type-roundinput.form-control,
.form-type-round input.form-control, .form-type-roundselect:not([multiple]).form-control,
.form-type-round select:not([multiple]).form-control {
  border-radius: 10rem;
  padding-left: 20px;
  padding-right: 20px;
}

.form-type-roundinput.form-control-sm, .form-type-roundselect:not([multiple]).form-control-sm,
.form-type-round.input-group-sm input.form-control,
.form-type-round input.form-control-sm,
.form-type-round select:not([multiple]).form-control-sm,
.form-type-round .input-group-sm input.form-control {
  padding-left: 16px;
  padding-right: 16px;
}

.form-type-roundinput.form-control-lg, .form-type-roundselect:not([multiple]).form-control-lg,
.form-type-round.input-group-lg input.form-control,
.form-type-round input.form-control-lg,
.form-type-round select:not([multiple]).form-control-lg,
.form-type-round .input-group-lg input.form-control {
  padding-left: 24px;
  padding-right: 24px;
}

.form-type-round.input-group,
.form-type-round .input-group {
  border-radius: 10rem;
}

.form-type-round.input-group .input-group-prepend > .input-group-text:first-child,
.form-type-round.input-group .input-group-prepend > .btn:first-child,
.form-type-round .input-group .input-group-prepend > .input-group-text:first-child,
.form-type-round .input-group .input-group-prepend > .btn:first-child {
  border-top-left-radius: 10rem;
  border-bottom-left-radius: 10rem;
}

.form-type-round.input-group .input-group-append > .input-group-text:last-child,
.form-type-round.input-group .input-group-append > .btn:last-of-type,
.form-type-round .input-group .input-group-append > .input-group-text:last-child,
.form-type-round .input-group .input-group-append > .btn:last-of-type {
  border-top-right-radius: 10rem;
  border-bottom-right-radius: 10rem;
}

.form-type-round .custom-file-control {
  border: 0;
  border-radius: 0;
  border-bottom: 1px solid #ebebeb;
  padding-left: 0;
  padding-right: 0;
}

.form-type-round .custom-file-control::before {
  border-radius: 0;
}

.form-type-round .bootstrap-select.btn-group .dropdown-toggle {
  border-radius: 10rem;
}

.form-type-fill.form-control,
.form-type-fill .form-control {
  background-color: #f7f9fa;
  border-color: #f1f2f3;
}

.form-type-fill.form-control:focus,
.form-type-fill .form-control:focus {
  background-color: #f3f5f7;
  -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.04);
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.04);
  border-color: #f1f2f3;
}

.form-type-fill .input-group-text {
  border-color: #f1f2f3;
  background-color: #f7f9fa;
  border-right: 1px solid #f1f2f3;
}

.form-type-fill .input-group .form-control + .input-group-text {
  border-left: 1px solid #f1f2f3;
  border-right: 0;
}

.form-type-fill .input-group-btn:not(:first-child) > .btn,
.form-type-fill .input-group-btn:not(:first-child) > .btn-group {
  z-index: initial;
}

.form-type-fill .bootstrap-select.btn-group .dropdown-toggle {
  background-color: #f7f9fa;
  border-color: #f1f2f3;
}

.form-type-fill .bootstrap-select.btn-group.show .dropdown-toggle {
  background-color: #f3f5f7;
  -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.04);
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.04);
  border-color: #f1f2f3;
}

.form-type-line.form-control,
.form-type-line .form-control {
  border-color: transparent;
  padding-left: 0;
  padding-right: 0;
  -webkit-background-size: 0 1px, 100% 1px;
          background-size: 0 1px, 100% 1px;
  background-repeat: no-repeat;
  background-position: center bottom, center calc(100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#33cabb), to(#33cabb)), -webkit-gradient(linear, left top, left bottom, from(#ebebeb), to(#ebebeb));
  background-image: -webkit-linear-gradient(#33cabb, #33cabb), -webkit-linear-gradient(#ebebeb, #ebebeb);
  background-image: linear-gradient(#33cabb, #33cabb), linear-gradient(#ebebeb, #ebebeb);
  -webkit-transition: background 0.3s;
  transition: background 0.3s;
}

.form-type-line.form-control:focus, .form-type-line.form-control.focus,
.form-type-line .form-control:focus,
.form-type-line .form-control.focus {
  -webkit-background-size: 100% 1px, 100% 1px;
          background-size: 100% 1px, 100% 1px;
  border-color: transparent;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.form-type-line.form-control:disabled, .form-type-line.form-control[readonly],
.form-type-line .form-control:disabled,
.form-type-line .form-control[readonly] {
  background-color: #fff;
  opacity: 0.7;
}

.form-type-line.form-group label,
.form-type-line .form-group label {
  margin-bottom: 0;
}

.form-type-line.input-group .btn,
.form-type-line .input-group .btn {
  border-radius: 0;
}

.form-type-line.input-group .input-group-text,
.form-type-line .input-group .input-group-text {
  background-color: #fff;
  border: none;
  color: #8b95a5;
}

.form-type-line.input-group .input-group-prepend ~ .form-control,
.form-type-line.input-group .form-control ~ .input-group-append,
.form-type-line .input-group .input-group-prepend ~ .form-control,
.form-type-line .input-group .form-control ~ .input-group-append {
  margin-left: 1rem;
}

.form-type-line .custom-file-control {
  border: 0;
  border-radius: 0;
  border-bottom: 1px solid #ebebeb;
  padding-left: 0;
  padding-right: 0;
}

.form-type-line .custom-file-control::before {
  border-radius: 0;
}

.form-type-line .bootstrap-select.btn-group .dropdown-toggle {
  border: none;
  background-color: transparent;
  padding-left: 0;
  -webkit-background-size: 0 1px, 100% 1px;
          background-size: 0 1px, 100% 1px;
  background-repeat: no-repeat;
  background-position: center bottom, center calc(100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#33cabb), to(#33cabb)), -webkit-gradient(linear, left top, left bottom, from(#ebebeb), to(#ebebeb));
  background-image: -webkit-linear-gradient(#33cabb, #33cabb), -webkit-linear-gradient(#ebebeb, #ebebeb);
  background-image: linear-gradient(#33cabb, #33cabb), linear-gradient(#ebebeb, #ebebeb);
  -webkit-transition: background 0.3s;
  transition: background 0.3s;
}

.form-type-line .bootstrap-select.btn-group .dropdown-toggle:focus {
  background-color: #fff;
}

.form-type-line .bootstrap-select.btn-group.show .dropdown-toggle {
  -webkit-background-size: 100% 1px, 100% 1px;
          background-size: 100% 1px, 100% 1px;
  border-color: transparent;
}

.form-type-line .has-success .form-control,
.form-type-line.has-success .form-control {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377)), -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377));
  background-image: -webkit-linear-gradient(#15c377, #15c377), -webkit-linear-gradient(#15c377, #15c377);
  background-image: linear-gradient(#15c377, #15c377), linear-gradient(#15c377, #15c377);
}

.form-type-line .has-warning .form-control,
.form-type-line.has-warning .form-control {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#faa64b), to(#faa64b)), -webkit-gradient(linear, left top, left bottom, from(#faa64b), to(#faa64b));
  background-image: -webkit-linear-gradient(#faa64b, #faa64b), -webkit-linear-gradient(#faa64b, #faa64b);
  background-image: linear-gradient(#faa64b, #faa64b), linear-gradient(#faa64b, #faa64b);
}

.form-type-line .has-danger .form-control,
.form-type-line.has-danger .form-control {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868)), -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868));
  background-image: -webkit-linear-gradient(#f96868, #f96868), -webkit-linear-gradient(#f96868, #f96868);
  background-image: linear-gradient(#f96868, #f96868), linear-gradient(#f96868, #f96868);
}

.form-type-line .custom-select.is-invalid,
.form-type-line .form-control.is-invalid,
.form-type-line .was-validated .custom-select:invalid,
.form-type-line .was-validated .form-control:invalid {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868)), -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868));
  background-image: -webkit-linear-gradient(#f96868, #f96868), -webkit-linear-gradient(#f96868, #f96868);
  background-image: linear-gradient(#f96868, #f96868), linear-gradient(#f96868, #f96868);
  border: none;
}

.form-type-line .custom-select.is-invalid:focus,
.form-type-line .form-control.is-invalid:focus,
.form-type-line .was-validated .custom-select:invalid:focus,
.form-type-line .was-validated .form-control:invalid:focus {
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
}

.form-type-line .custom-select.is-valid,
.form-type-line .form-control.is-valid,
.form-type-line .was-validated .custom-select:valid,
.form-type-line .was-validated .form-control:valid {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377)), -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377));
  background-image: -webkit-linear-gradient(#15c377, #15c377), -webkit-linear-gradient(#15c377, #15c377);
  background-image: linear-gradient(#15c377, #15c377), linear-gradient(#15c377, #15c377);
  border: none;
}

.form-type-line .custom-select.is-valid:focus,
.form-type-line .form-control.is-valid:focus,
.form-type-line .was-validated .custom-select:valid:focus,
.form-type-line .was-validated .form-control:valid:focus {
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
}

.form-type-material.form-control,
.form-type-material .form-control {
  border-color: transparent;
  padding-left: 0;
  padding-right: 0;
  -webkit-background-size: 0 2px, 100% 1px;
          background-size: 0 2px, 100% 1px;
  background-repeat: no-repeat;
  background-position: center bottom, center calc(100% - 1px);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#33cabb), to(#33cabb)), -webkit-gradient(linear, left top, left bottom, from(#ebebeb), to(#ebebeb));
  background-image: -webkit-linear-gradient(#33cabb, #33cabb), -webkit-linear-gradient(#ebebeb, #ebebeb);
  background-image: linear-gradient(#33cabb, #33cabb), linear-gradient(#ebebeb, #ebebeb);
  -webkit-transition: background 0.3s;
  transition: background 0.3s;
}

.form-type-material.form-control:focus, .form-type-material.form-control.focus,
.form-type-material .form-control:focus,
.form-type-material .form-control.focus {
  -webkit-background-size: 100% 2px, 100% 1px;
          background-size: 100% 2px, 100% 1px;
  border-color: transparent;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.form-type-material.form-control:disabled, .form-type-material.form-control[readonly],
.form-type-material .form-control:disabled,
.form-type-material .form-control[readonly] {
  background-color: #fff;
  opacity: 0.7;
}

.form-type-material.form-control.form-control-sm ~ label,
.form-type-material .form-control.form-control-sm ~ label {
  font-size: 80%;
}

.form-type-material.form-control.form-control-lg ~ label,
.form-type-material .form-control.form-control-lg ~ label {
  font-size: 120%;
}

.form-type-material.form-group,
.form-type-material .form-group {
  position: relative;
  padding-top: 10px;
  margin-bottom: 20px;
}

.form-type-material.form-group label:not(.custom-control-label):not(.switch),
.form-type-material .form-group label:not(.custom-control-label):not(.switch) {
  position: absolute;
  left: 0;
  top: 13px;
  font-weight: 300;
  padding: 0;
  pointer-events: none;
  white-space: nowrap;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

.form-type-material.form-group .form-control-lg ~ label,
.form-type-material .form-group .form-control-lg ~ label {
  top: 20px;
}

.form-type-material.input-group,
.form-type-material .input-group {
  position: relative;
  padding-top: 10px;
}

.form-type-material.input-group label:not(.custom-control-label),
.form-type-material .input-group label:not(.custom-control-label) {
  position: absolute;
  left: 0;
  top: 8px !important;
  font-weight: 300;
  padding: 0;
  pointer-events: none;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

.form-type-material.input-group .input-group-text,
.form-type-material .input-group .input-group-text {
  background-color: #fff;
  border: none;
  color: #8b95a5;
}

.form-type-material.input-group .input-group-prepend .btn:last-of-type,
.form-type-material .input-group .input-group-prepend .btn:last-of-type {
  margin-right: 1rem;
}

.form-type-material.input-group .input-group-append .btn:first-of-type,
.form-type-material .input-group .input-group-append .btn:first-of-type {
  margin-left: 1rem;
}

.form-type-material.input-group.input-group-lg label,
.form-type-material .input-group.input-group-lg label {
  font-size: 120%;
  top: 14px !important;
}

.form-type-material.input-group.input-group-sm label,
.form-type-material .input-group.input-group-sm label {
  font-size: 80%;
  top: 3px !important;
}

.form-type-material .custom-file-control {
  border: 0;
  border-radius: 0;
  border-bottom: 1px solid #ebebeb;
  padding-left: 0;
  padding-right: 0;
}

.form-type-material .custom-file-control::before {
  border-radius: 0;
}

.form-type-material .input-group-input {
  position: relative;
  -webkit-box-flex: 1;
          flex-grow: 1;
}

.form-type-material .input-group-input .form-control {
  width: 100%;
}

.form-type-material .input-group-input label {
  top: 6px !important;
  z-index: 3;
}

.form-type-material .input-group-input.do-float label,
.form-type-material .input-group-input .label-floated {
  top: -13px !important;
}

.form-type-material .bootstrap-select.btn-group .dropdown-toggle {
  background-color: transparent;
  border: none;
  padding-left: 0;
  -webkit-background-size: 0 2px, 100% 1px;
          background-size: 0 2px, 100% 1px;
  background-repeat: no-repeat;
  background-position: center bottom, center calc(100% - 1px);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#33cabb), to(#33cabb)), -webkit-gradient(linear, left top, left bottom, from(#ebebeb), to(#ebebeb));
  background-image: -webkit-linear-gradient(#33cabb, #33cabb), -webkit-linear-gradient(#ebebeb, #ebebeb);
  background-image: linear-gradient(#33cabb, #33cabb), linear-gradient(#ebebeb, #ebebeb);
  -webkit-transition: background 0.3s;
  transition: background 0.3s;
}

.form-type-material .bootstrap-select.btn-group .dropdown-toggle:focus {
  background-color: #fff;
}

.form-type-material .bootstrap-select.btn-group.show .dropdown-toggle {
  -webkit-background-size: 100% 2px, 100% 1px;
          background-size: 100% 2px, 100% 1px;
  border-color: transparent;
}

.form-type-material .bootstrap-select.btn-group.input-group-btn .dropdown-toggle {
  margin-top: 0;
}

.form-type-material .bootstrap-select.btn-group + label {
  z-index: 3;
}

.do-float label,
.label-floated {
  top: -8px !important;
  font-size: 10px !important;
  font-weight: 400 !important;
  opacity: .5;
}

.form-type-material .has-success .form-control,
.form-type-material.has-success .form-control {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377)), -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377));
  background-image: -webkit-linear-gradient(#15c377, #15c377), -webkit-linear-gradient(#15c377, #15c377);
  background-image: linear-gradient(#15c377, #15c377), linear-gradient(#15c377, #15c377);
}

.form-type-material .has-success.do-float label,
.form-type-material .has-success .do-float label,
.form-type-material.has-success.do-float label,
.form-type-material.has-success .do-float label {
  color: #15c377;
}

.form-type-material .has-warning .form-control,
.form-type-material.has-warning .form-control {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#faa64b), to(#faa64b)), -webkit-gradient(linear, left top, left bottom, from(#faa64b), to(#faa64b));
  background-image: -webkit-linear-gradient(#faa64b, #faa64b), -webkit-linear-gradient(#faa64b, #faa64b);
  background-image: linear-gradient(#faa64b, #faa64b), linear-gradient(#faa64b, #faa64b);
}

.form-type-material .has-warning.do-float label,
.form-type-material .has-warning .do-float label,
.form-type-material.has-warning.do-float label,
.form-type-material.has-warning .do-float label {
  color: #faa64b;
}

.form-type-material .has-danger .form-control,
.form-type-material.has-danger .form-control {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868)), -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868));
  background-image: -webkit-linear-gradient(#f96868, #f96868), -webkit-linear-gradient(#f96868, #f96868);
  background-image: linear-gradient(#f96868, #f96868), linear-gradient(#f96868, #f96868);
}

.form-type-material .has-danger.do-float label,
.form-type-material .has-danger .do-float label,
.form-type-material.has-danger.do-float label,
.form-type-material.has-danger .do-float label {
  color: #f96868;
}

.form-type-material .custom-select.is-invalid,
.form-type-material .form-control.is-invalid,
.form-type-material .was-validated .custom-select:invalid,
.form-type-material .was-validated .form-control:invalid {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868)), -webkit-gradient(linear, left top, left bottom, from(#f96868), to(#f96868));
  background-image: -webkit-linear-gradient(#f96868, #f96868), -webkit-linear-gradient(#f96868, #f96868);
  background-image: linear-gradient(#f96868, #f96868), linear-gradient(#f96868, #f96868);
  border: none;
}

.form-type-material .custom-select.is-invalid:focus,
.form-type-material .form-control.is-invalid:focus,
.form-type-material .was-validated .custom-select:invalid:focus,
.form-type-material .was-validated .form-control:invalid:focus {
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
}

.form-type-material .custom-select.is-valid,
.form-type-material .form-control.is-valid,
.form-type-material .was-validated .custom-select:valid,
.form-type-material .was-validated .form-control:valid {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377)), -webkit-gradient(linear, left top, left bottom, from(#15c377), to(#15c377));
  background-image: -webkit-linear-gradient(#15c377, #15c377), -webkit-linear-gradient(#15c377, #15c377);
  background-image: linear-gradient(#15c377, #15c377), linear-gradient(#15c377, #15c377);
  border: none;
}

.form-type-material .custom-select.is-valid:focus,
.form-type-material .form-control.is-valid:focus,
.form-type-material .was-validated .custom-select:valid:focus,
.form-type-material .was-validated .form-control:valid:focus {
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
}

.form-type-combine .form-group,
.form-type-combine.form-group,
.form-type-combine .input-group,
.form-type-combine.input-group {
  position: relative;
  background-color: #fff;
  position: relative;
  border: 1px solid #ebebeb;
  border-radius: 2px;
  padding: 4px 12px 8px;
  cursor: text;
  outline: none !important;
  -webkit-transition: .5s;
  transition: .5s;
}

.form-type-combine .form-group:focus, .form-type-combine .form-group.focused,
.form-type-combine.form-group:focus,
.form-type-combine.form-group.focused,
.form-type-combine .input-group:focus,
.form-type-combine .input-group.focused,
.form-type-combine.input-group:focus,
.form-type-combine.input-group.focused {
  background-color: #fcfdfe;
}

.form-type-combine .form-group:focus label,
.form-type-combine .form-group:focus small,
.form-type-combine .form-group:focus .form-text, .form-type-combine .form-group.focused label,
.form-type-combine .form-group.focused small,
.form-type-combine .form-group.focused .form-text,
.form-type-combine.form-group:focus label,
.form-type-combine.form-group:focus small,
.form-type-combine.form-group:focus .form-text,
.form-type-combine.form-group.focused label,
.form-type-combine.form-group.focused small,
.form-type-combine.form-group.focused .form-text,
.form-type-combine .input-group:focus label,
.form-type-combine .input-group:focus small,
.form-type-combine .input-group:focus .form-text,
.form-type-combine .input-group.focused label,
.form-type-combine .input-group.focused small,
.form-type-combine .input-group.focused .form-text,
.form-type-combine.input-group:focus label,
.form-type-combine.input-group:focus small,
.form-type-combine.input-group:focus .form-text,
.form-type-combine.input-group.focused label,
.form-type-combine.input-group.focused small,
.form-type-combine.input-group.focused .form-text {
  opacity: .4;
}

.form-type-combine .form-group.disabled,
.form-type-combine.form-group.disabled,
.form-type-combine .input-group.disabled,
.form-type-combine.input-group.disabled {
  background-color: #f9fafb;
  opacity: .5;
  cursor: not-allowed;
}

.form-type-combine .form-group label,
.form-type-combine .form-group small,
.form-type-combine .form-group .form-text,
.form-type-combine.form-group label,
.form-type-combine.form-group small,
.form-type-combine.form-group .form-text,
.form-type-combine .input-group label,
.form-type-combine .input-group small,
.form-type-combine .input-group .form-text,
.form-type-combine.input-group label,
.form-type-combine.input-group small,
.form-type-combine.input-group .form-text {
  -webkit-transition: .5s;
  transition: .5s;
}

.form-type-combine .form-group .form-control,
.form-type-combine .form-group .form-control-plaintext,
.form-type-combine.form-group .form-control,
.form-type-combine.form-group .form-control-plaintext,
.form-type-combine .input-group .form-control,
.form-type-combine .input-group .form-control-plaintext,
.form-type-combine.input-group .form-control,
.form-type-combine.input-group .form-control-plaintext {
  background-color: transparent;
  border: none;
  padding: 0;
  width: 100%;
  height: 25px;
  line-height: 25px;
  min-height: auto;
}

.form-type-combine .form-group .form-control:focus,
.form-type-combine .form-group .form-control.focused,
.form-type-combine.form-group .form-control:focus,
.form-type-combine.form-group .form-control.focused,
.form-type-combine .input-group .form-control:focus,
.form-type-combine .input-group .form-control.focused,
.form-type-combine.input-group .form-control:focus,
.form-type-combine.input-group .form-control.focused {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.form-type-combine .form-group textarea,
.form-type-combine .form-group select[multiple],
.form-type-combine.form-group textarea,
.form-type-combine.form-group select[multiple],
.form-type-combine .input-group textarea,
.form-type-combine .input-group select[multiple],
.form-type-combine.input-group textarea,
.form-type-combine.input-group select[multiple] {
  height: auto !important;
}

.form-type-combine .form-group label,
.form-type-combine.form-group label,
.form-type-combine .input-group label,
.form-type-combine.input-group label {
  font-family: Roboto, sans-serif;
  font-weight: 500;
  font-size: 10px;
  line-height: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 0;
  cursor: inherit;
}

.form-type-combine .form-group.require::after,
.form-type-combine.form-group.require::after,
.form-type-combine .input-group.require::after,
.form-type-combine.input-group.require::after {
  content: '*';
  color: #f96868;
  font-size: 0.875rem;
  font-weight: 500;
  position: absolute;
  top: 0;
  right: 6px;
}

.form-type-combine .form-group.form-group-sm .form-control,
.form-type-combine .form-group.form-group-sm .form-control-plaintext,
.form-type-combine.form-group.form-group-sm .form-control,
.form-type-combine.form-group.form-group-sm .form-control-plaintext,
.form-type-combine .input-group.form-group-sm .form-control,
.form-type-combine .input-group.form-group-sm .form-control-plaintext,
.form-type-combine.input-group.form-group-sm .form-control,
.form-type-combine.input-group.form-group-sm .form-control-plaintext {
  height: 20px;
  line-height: 20px;
  font-size: 13px;
}

.form-type-combine .form-group.form-group-sm label,
.form-type-combine.form-group.form-group-sm label,
.form-type-combine .input-group.form-group-sm label,
.form-type-combine.input-group.form-group-sm label {
  font-size: 10px;
}

.form-type-combine .form-group.form-group-sm .input-group-text,
.form-type-combine.form-group.form-group-sm .input-group-text,
.form-type-combine .input-group.form-group-sm .input-group-text,
.form-type-combine.input-group.form-group-sm .input-group-text {
  line-height: 58px;
  font-size: 13px;
}

.form-type-combine .form-group.form-group-lg .form-control,
.form-type-combine .form-group.form-group-lg .form-control-plaintext,
.form-type-combine.form-group.form-group-lg .form-control,
.form-type-combine.form-group.form-group-lg .form-control-plaintext,
.form-type-combine .input-group.form-group-lg .form-control,
.form-type-combine .input-group.form-group-lg .form-control-plaintext,
.form-type-combine.input-group.form-group-lg .form-control,
.form-type-combine.input-group.form-group-lg .form-control-plaintext {
  height: 32px;
  line-height: 32px;
  font-size: 15px;
}

.form-type-combine .form-group.form-group-lg label,
.form-type-combine.form-group.form-group-lg label,
.form-type-combine .input-group.form-group-lg label,
.form-type-combine.input-group.form-group-lg label {
  font-size: 12px;
}

.form-type-combine .form-group.form-group-lg .input-group-text,
.form-type-combine.form-group.form-group-lg .input-group-text,
.form-type-combine .input-group.form-group-lg .input-group-text,
.form-type-combine.input-group.form-group-lg .input-group-text {
  line-height: 70px;
  font-size: 15px;
}

.form-type-combine .form-group .bootstrap-select.btn-group .dropdown-toggle,
.form-type-combine.form-group .bootstrap-select.btn-group .dropdown-toggle,
.form-type-combine .input-group .bootstrap-select.btn-group .dropdown-toggle,
.form-type-combine.input-group .bootstrap-select.btn-group .dropdown-toggle {
  background-color: transparent;
  border: none;
  padding: 0 18px 0 0;
  height: 25px;
  line-height: 25px;
  min-height: auto;
}

.form-type-combine .form-group .bootstrap-select.btn-group.show .dropdown-toggle,
.form-type-combine.form-group .bootstrap-select.btn-group.show .dropdown-toggle,
.form-type-combine .input-group .bootstrap-select.btn-group.show .dropdown-toggle,
.form-type-combine.input-group .bootstrap-select.btn-group.show .dropdown-toggle {
  -webkit-background-size: 100% 2px, 100% 1px;
          background-size: 100% 2px, 100% 1px;
  border-color: transparent;
}

.form-type-combine .input-group,
.form-type-combine.input-group {
  padding: 0;
  display: -webkit-box;
  display: flex;
  overflow: visible;
  margin-bottom: 1rem;
}

.form-type-combine .input-group .input-group-text,
.form-type-combine.input-group .input-group-text {
  flex-shrink: 0;
  -webkit-box-pack: center;
          justify-content: center;
  border: none;
  min-width: 63px;
  line-height: 63px;
  padding-top: 0;
  padding-bottom: 0;
  cursor: default;
}

.form-type-combine .input-group .input-group-text .custom-control,
.form-type-combine.input-group .input-group-text .custom-control {
  display: -webkit-inline-box;
  display: inline-flex;
}

.form-type-combine .input-group .input-group-prepend .btn,
.form-type-combine .input-group .input-group-append .btn,
.form-type-combine.input-group .input-group-prepend .btn,
.form-type-combine.input-group .input-group-append .btn {
  height: 100%;
  border: 0;
  margin: 0;
}

.form-type-combine .input-group .input-group-input,
.form-type-combine.input-group .input-group-input {
  padding: 4px 12px 8px;
  -webkit-box-flex: 1;
          flex-grow: 1;
  -webkit-transition: .5s;
  transition: .5s;
}

.form-type-combine .input-group .input-group-input:focus, .form-type-combine .input-group .input-group-input.focused,
.form-type-combine.input-group .input-group-input:focus,
.form-type-combine.input-group .input-group-input.focused {
  background-color: #fcfdfe;
}

.form-type-combine .input-group .input-group-input:focus label,
.form-type-combine .input-group .input-group-input:focus small,
.form-type-combine .input-group .input-group-input:focus .form-text, .form-type-combine .input-group .input-group-input.focused label,
.form-type-combine .input-group .input-group-input.focused small,
.form-type-combine .input-group .input-group-input.focused .form-text,
.form-type-combine.input-group .input-group-input:focus label,
.form-type-combine.input-group .input-group-input:focus small,
.form-type-combine.input-group .input-group-input:focus .form-text,
.form-type-combine.input-group .input-group-input.focused label,
.form-type-combine.input-group .input-group-input.focused small,
.form-type-combine.input-group .input-group-input.focused .form-text {
  opacity: .4;
}

.form-type-combine .input-group .input-group-input .form-control:focus,
.form-type-combine.input-group .input-group-input .form-control:focus {
  background-color: transparent;
}

.form-type-combine .input-group .input-group-text + .input-group-input,
.form-type-combine .input-group .input-group-btn + .input-group-input,
.form-type-combine .input-group .input-group-input + .input-group-text,
.form-type-combine .input-group .input-group-input + .input-group-btn .btn,
.form-type-combine .input-group .input-group-btn .btn + .btn,
.form-type-combine.input-group .input-group-text + .input-group-input,
.form-type-combine.input-group .input-group-btn + .input-group-input,
.form-type-combine.input-group .input-group-input + .input-group-text,
.form-type-combine.input-group .input-group-input + .input-group-btn .btn,
.form-type-combine.input-group .input-group-btn .btn + .btn {
  border-left: 1px solid #ebebeb;
}

.form-type-combine .input-group .input-group-append,
.form-type-combine.input-group .input-group-append {
  border-left: 1px solid #ebebeb;
}

.form-type-combine .input-group .input-group-prepend,
.form-type-combine.input-group .input-group-prepend {
  border-right: 1px solid #ebebeb;
}

.form-type-combine .input-group .form-control:focus,
.form-type-combine.input-group .form-control:focus {
  border-color: #ebebeb;
  background-color: #fcfdfe;
  -webkit-transition: .5s;
  transition: .5s;
}

.form-type-combine.has-success,
.form-type-combine .has-success {
  border-color: #15c377;
}

.form-type-combine.has-success + .form-control-feedback,
.form-type-combine .has-success + .form-control-feedback {
  color: #15c377;
}

.form-type-combine.has-warning,
.form-type-combine .has-warning {
  border-color: #faa64b;
}

.form-type-combine.has-warning + .form-control-feedback,
.form-type-combine .has-warning + .form-control-feedback {
  color: #faa64b;
}

.form-type-combine.has-danger,
.form-type-combine .has-danger {
  border-color: #f96868;
}

.form-type-combine.has-danger + .form-control-feedback,
.form-type-combine .has-danger + .form-control-feedback {
  color: #f96868;
}

.form-type-combine .form-group + .form-control-feedback {
  margin-top: -.5rem;
  line-height: 20px;
}

.form-type-combine .custom-select.is-invalid,
.form-type-combine .form-control.is-invalid,
.form-type-combine .was-validated .custom-select:invalid,
.form-type-combine .was-validated .form-control:invalid {
  border-color: #f96868 !important;
}

.form-type-combine .custom-select.is-invalid:focus,
.form-type-combine .form-control.is-invalid:focus,
.form-type-combine .was-validated .custom-select:invalid:focus,
.form-type-combine .was-validated .form-control:invalid:focus {
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
}

.form-type-combine .custom-select.is-valid,
.form-type-combine .form-control.is-valid,
.form-type-combine .was-validated .custom-select:valid,
.form-type-combine .was-validated .form-control:valid {
  border-color: #15c377 !important;
}

.form-type-combine .custom-select.is-valid:focus,
.form-type-combine .form-control.is-valid:focus,
.form-type-combine .was-validated .custom-select:valid:focus,
.form-type-combine .was-validated .form-control:valid:focus {
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
}

.form-groups-attached {
  margin-bottom: 1rem;
}

.form-groups-attached .form-group {
  margin-bottom: 0;
  border-radius: 0;
}

.form-groups-attached > div:not(:last-child),
.form-groups-attached > div:not(:last-child) .form-group {
  border-bottom-color: transparent;
}

.form-groups-attached .row {
  margin-left: 0;
  margin-right: 0;
}

.form-groups-attached .row > .form-group:not(:last-child) {
  border-right-color: transparent;
}

.file-group {
  position: relative;
  overflow: hidden;
}

.file-group input[type="file"] {
  position: absolute;
  opacity: 0;
  z-index: -1;
  width: 20px;
}

.file-group-inline {
  display: inline-block;
}

.form-control.file-value {
  cursor: text;
}

.dropify-wrapper {
  border: 1px solid #ebebeb;
  padding: 12px;
}

.dropify-wrapper .dropify-clear,
.dropify-wrapper.touch-fallback .dropify-clear {
  bottom: 6px;
  border: none;
  color: #f96868;
  font-weight: 400;
  font-size: 11px;
  letter-spacing: 1px;
  padding: 6px 12px;
  cursor: pointer;
}

.dropify-wrapper .dropify-clear:hover,
.dropify-wrapper.touch-fallback .dropify-clear:hover {
  background-color: #f9fafb;
}

.dropify-wrapper.touch-fallback .dropify-preview .dropify-infos .dropify-infos-inner {
  padding-top: 10px;
  padding-bottom: 0;
}

.dropify-wrapper .dropify-preview .dropify-infos .dropify-infos-inner p,
.dropify-wrapper.touch-fallback .dropify-preview .dropify-infos .dropify-infos-inner p {
  color: #8b95a5;
  font-weight: 300;
}

.dropify-wrapper.touch-fallback .dropify-preview .dropify-infos .dropify-infos-inner p.dropify-infos-message {
  font-size: 12px;
  opacity: 0.6;
  display: none;
}

.dropify-wrapper.touch-fallback .dropify-preview .dropify-infos .dropify-infos-inner p.dropify-filename {
  font-weight: 400;
  font-size: 13px;
  color: #616a78;
}

.dropzone {
  padding: 10px;
  border: 2px dashed #ebebeb;
  background-color: #fcfdfe;
  -webkit-transition: .5s;
  transition: .5s;
}

.dropzone.dz-drag-hover {
  border: 2px dashed #48b0f7;
}

.dropzone .dz-preview {
  margin: 10px;
}

.dropzone .dz-preview .dz-image {
  border-radius: 4px !important;
}

.dz-message {
  color: #a5b3c7;
  font-size: 1rem;
}

.dz-message span::before {
  font-family: FontAwesome;
  content: "\f0ee";
  font-size: 2rem;
  display: block;
  padding-bottom: 16px;
  opacity: .5;
}

.form-check-input {
  outline: none !important;
}

.custom-control {
  min-width: 18px;
}

.custom-controls-stacked::after {
  display: block;
  content: "";
  clear: both;
}

.custom-controls-stacked .custom-control {
  display: block;
  margin-bottom: 6px;
}

.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before,
.custom-checkbox .custom-control-input:active:not(:disabled) ~ .custom-control-label::before,
.custom-radio .custom-control-input:checked ~ .custom-control-label::before,
.custom-radio .custom-control-input:active:not(:disabled) ~ .custom-control-label::before {
  background-color: #fcfdfe;
}

.custom-checkbox .custom-control-input:focus ~ .custom-control-label::before,
.custom-radio .custom-control-input:focus ~ .custom-control-label::before {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after,
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  -webkit-transform: scale(1);
          transform: scale(1);
  background-image: none;
}

.custom-checkbox .custom-control-input:disabled ~ .custom-control-label::before, .custom-checkbox .custom-control-input:disabled ~ .custom-control-label::after,
.custom-radio .custom-control-input:disabled ~ .custom-control-label::before,
.custom-radio .custom-control-input:disabled ~ .custom-control-label::after {
  opacity: .5;
}

.custom-checkbox .custom-control-input:disabled ~ .custom-control-label::before,
.custom-radio .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #fcfdfe !important;
}

.custom-checkbox .custom-control-input:checked ~ .custom-control-label.strike-on-check,
.custom-radio .custom-control-input:checked ~ .custom-control-label.strike-on-check {
  text-decoration: line-through;
  opacity: .6;
}

.custom-control-label {
  color: #4d5259;
  font-weight: 300;
}

.custom-control-label::before {
  top: .125rem;
  width: 18px;
  height: 18px;
  background-color: #fcfdfe;
  border: 1px solid #ebebeb;
  pointer-events: initial;
}

.custom-control-label::after {
  content: '';
  display: inline-block;
  -webkit-transform: scale(0);
          transform: scale(0);
  -webkit-transition: -webkit-transform .3s;
  transition: -webkit-transform .3s;
  transition: transform .3s;
  transition: transform .3s, -webkit-transform .3s;
}

.custom-radio .custom-control-label::after {
  border-radius: 50%;
  vertical-align: top;
  margin-top: 3px;
  margin-left: 5px;
  display: inline-block;
  width: 8px;
  height: 8px;
  background-color: #33cabb;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0;
}

.custom-checkbox .custom-control-label::after {
  content: "\e64c";
  top: 0;
  left: 2px;
  font-family: themify;
  font-size: 11px;
  font-weight: bold;
  text-align: center;
  color: #33cabb;
}

.custom-checkbox .custom-control-label:empty::after {
  top: 4px;
}

.custom-control.no-border .custom-control-label::before {
  border-color: transparent;
  background-color: transparent !important;
}

.custom-control.no-border.custom-checkbox .custom-control-indicator::after {
  font-size: 14px;
}

.custom-control.no-border.custom-radio .custom-control-indicator::after {
  margin-top: 3px;
  display: inline-block;
  width: 9px;
  height: 9px;
}

.custom-control-sm {
  min-width: 14px;
  padding-left: 1.25rem;
}

.custom-control-sm .custom-control-label::before {
  top: 4px;
  width: 14px;
  height: 14px;
  line-height: 12px;
}

.custom-control-sm.custom-radio .custom-control-label::after {
  margin-top: 5px;
  margin-left: 4px;
  width: 6px;
  height: 6px;
}

.custom-control-sm.custom-radio.no-border .custom-control-label::after {
  margin-top: 5px;
  margin-left: 4px;
  width: 7px;
  height: 7px;
}

.custom-control-sm.custom-checkbox .custom-control-label::after {
  font-size: 9px;
  left: 0px;
}

.custom-control-sm.custom-checkbox.no-border .custom-control-label::after {
  font-size: 11px;
}

.custom-control-lg {
  min-width: 22px;
  padding-left: 2rem;
}

.custom-control-lg .custom-control-label::before {
  width: 22px;
  height: 22px;
  line-height: 22px;
}

.custom-control-lg.custom-radio .custom-control-label::after {
  margin-top: 4px;
  margin-left: 6px;
  width: 10px;
  height: 10px;
}

.custom-control-lg.custom-radio.no-border .custom-control-label::after {
  margin-top: 4px;
  margin-left: 6px;
  width: 10px;
  height: 10px;
}

.custom-control-lg.custom-checkbox .custom-control-label::after {
  font-size: 14px;
  left: 4px;
}

.custom-control-lg.custom-checkbox.no-border .custom-control-label::after {
  font-size: 16px;
}

.custom-control-light .custom-control-label::before {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: transparent;
}

.custom-control-primary.custom-checkbox .custom-control-label::after {
  color: #33cabb;
}

.custom-control-primary.custom-radio .custom-control-label::after {
  background-color: #33cabb;
}

.custom-control-secondary.custom-checkbox .custom-control-label::after {
  color: #e4e7ea;
}

.custom-control-secondary.custom-radio .custom-control-label::after {
  background-color: #e4e7ea;
}

.custom-control-success.custom-checkbox .custom-control-label::after {
  color: #15c377;
}

.custom-control-success.custom-radio .custom-control-label::after {
  background-color: #15c377;
}

.custom-control-info.custom-checkbox .custom-control-label::after {
  color: #48b0f7;
}

.custom-control-info.custom-radio .custom-control-label::after {
  background-color: #48b0f7;
}

.custom-control-warning.custom-checkbox .custom-control-label::after {
  color: #faa64b;
}

.custom-control-warning.custom-radio .custom-control-label::after {
  background-color: #faa64b;
}

.custom-control-danger.custom-checkbox .custom-control-label::after {
  color: #f96868;
}

.custom-control-danger.custom-radio .custom-control-label::after {
  background-color: #f96868;
}

.custom-control-pink.custom-checkbox .custom-control-label::after {
  color: #f96197;
}

.custom-control-pink.custom-radio .custom-control-label::after {
  background-color: #f96197;
}

.custom-control-purple.custom-checkbox .custom-control-label::after {
  color: #926dde;
}

.custom-control-purple.custom-radio .custom-control-label::after {
  background-color: #926dde;
}

.custom-control-brown.custom-checkbox .custom-control-label::after {
  color: #8d6658;
}

.custom-control-brown.custom-radio .custom-control-label::after {
  background-color: #8d6658;
}

.custom-control-cyan.custom-checkbox .custom-control-label::after {
  color: #57c7d4;
}

.custom-control-cyan.custom-radio .custom-control-label::after {
  background-color: #57c7d4;
}

.custom-control-yellow.custom-checkbox .custom-control-label::after {
  color: #fcc525;
}

.custom-control-yellow.custom-radio .custom-control-label::after {
  background-color: #fcc525;
}

.custom-control-gray.custom-checkbox .custom-control-label::after {
  color: #868e96;
}

.custom-control-gray.custom-radio .custom-control-label::after {
  background-color: #868e96;
}

.custom-control-dark.custom-checkbox .custom-control-label::after {
  color: #465161;
}

.custom-control-dark.custom-radio .custom-control-label::after {
  background-color: #465161;
}

.custom-control-light.custom-checkbox .custom-control-label::after {
  color: #fff;
}

.custom-control-light.custom-radio .custom-control-label::after {
  background-color: #fff;
}

.custom-control-secondary .custom-control-label::after {
  color: #4d5259;
}

.custom-control-light .custom-control-label::after {
  color: #33cabb;
}

.custom-control-light.custom-radio .custom-control-label::after {
  background-color: #33cabb;
}

.custom-control-input.is-invalid ~ .custom-control-indicator,
.was-validated .custom-control-input:invalid ~ .custom-control-indicator,
.custom-control-input.is-valid ~ .custom-control-indicator,
.was-validated .custom-control-input:valid ~ .custom-control-indicator {
  background-color: #fcfdfe;
}

.custom-control-input.is-invalid ~ .custom-control-description {
  color: #f96868;
}

.custom-control-input.is-invalid ~ .invalid-feedback {
  display: -webkit-box;
  display: flex;
  width: 100%;
  padding-left: 1rem;
  font-size: 12px;
  margin-top: 0;
  font-style: italic;
}

.custom-control-input.is-invalid ~ .invalid-feedback::before {
  content: '\2014 \00A0';
}

.custom-control-input.is-valid ~ .custom-control-description {
  color: #15c377;
}

.switch {
  font-weight: 300;
  letter-spacing: 0;
  margin-bottom: 0;
  line-height: 29px;
  cursor: pointer;
  white-space: nowrap;
}

.switch input {
  display: none;
}

.switch input:checked ~ .switch-indicator {
  background: #dcfcfa;
}

.switch input:checked ~ .switch-indicator::after {
  background: #33cabb;
  left: calc(100% - 20px);
  -webkit-box-shadow: 0px 0px 3px #fcfdfe;
          box-shadow: 0px 0px 3px #fcfdfe;
}

.switch input:disabled ~ span {
  cursor: not-allowed;
  opacity: .5;
}

.switch.disabled {
  opacity: .5;
  cursor: not-allowed;
}

.switch-indicator {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 10px;
  background: #e3e4e5;
  border-radius: 20px;
  vertical-align: middle;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

.switch-indicator::after {
  content: '';
  display: block;
  position: absolute;
  left: 0px;
  top: 0px;
  width: 20px;
  height: 20px;
  -webkit-transition: 0.3s;
  transition: 0.3s;
  cursor: inherit;
  background: #fff;
  border-radius: 50%;
  top: -5px;
  -webkit-box-shadow: 0px 0px 3px #bbb;
          box-shadow: 0px 0px 3px #bbb;
}

.switch-description {
  padding-left: 6px;
}

.switch-border input:checked + .switch-indicator {
  border-color: #dcfcfa;
}

.switch-border input:checked + .switch-indicator::after {
  left: calc(100% - 14px);
}

.switch-border .switch-indicator {
  border: 2px solid #e3e4e5;
  background: transparent !important;
  height: 20px;
}

.switch-border .switch-indicator::after {
  background: #d6d7d9;
  border-radius: 50%;
  width: 12px;
  height: 12px;
  top: 2px;
  left: 2px;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.switch-sm .switch-indicator {
  height: 4px;
  border-radius: 0;
}

.switch-sm .switch-indicator::after {
  top: -8px;
}

.switch-lg input:checked + .switch-indicator::after {
  left: calc(100% - 18px);
}

.switch-lg .switch-indicator {
  height: 20px;
}

.switch-lg .switch-indicator::after {
  background: #fff;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  top: 2px;
  left: 2px;
}

.switch-stacked .switch {
  display: inline;
}

.switch-stacked .switch::after {
  display: block;
  margin-bottom: .25rem;
  content: "";
}

.switch-secondary input:checked + .switch-indicator {
  background: #f7fafc;
  border-color: #f7fafc;
}

.switch-secondary input:checked + .switch-indicator::after {
  background: #e4e7ea;
}

.switch-success input:checked + .switch-indicator {
  background: #e3fcf2;
  border-color: #e3fcf2;
}

.switch-success input:checked + .switch-indicator::after {
  background: #15c377;
}

.switch-info input:checked + .switch-indicator {
  background: #e3f3fc;
  border-color: #e3f3fc;
}

.switch-info input:checked + .switch-indicator::after {
  background: #48b0f7;
}

.switch-warning input:checked + .switch-indicator {
  background: #fcf0e3;
  border-color: #fcf0e3;
}

.switch-warning input:checked + .switch-indicator::after {
  background: #faa64b;
}

.switch-danger input:checked + .switch-indicator {
  background: #fce3e3;
  border-color: #fce3e3;
}

.switch-danger input:checked + .switch-indicator::after {
  background: #f96868;
}

.switch-gray input:checked + .switch-indicator {
  background: #f2f2f2;
  border-color: #f2f2f2;
}

.switch-gray input:checked + .switch-indicator::after {
  background: #868e96;
}

.switch-dark input:checked + .switch-indicator {
  background: #c8c8c8;
  border-color: #c8c8c8;
}

.switch-dark input:checked + .switch-indicator::after {
  background: #465161;
}

.switch-pink input:checked + .switch-indicator {
  background: #fce3ec;
  border-color: #fce3ec;
}

.switch-pink input:checked + .switch-indicator::after {
  background: #f96197;
}

.switch-purple input:checked + .switch-indicator {
  background: #ece3fc;
  border-color: #ece3fc;
}

.switch-purple input:checked + .switch-indicator::after {
  background: #926dde;
}

.switch-brown input:checked + .switch-indicator {
  background: #eddcd5;
  border-color: #eddcd5;
}

.switch-brown input:checked + .switch-indicator::after {
  background: #8d6658;
}

.switch-cyan input:checked + .switch-indicator {
  background: #e3fafc;
  border-color: #e3fafc;
}

.switch-cyan input:checked + .switch-indicator::after {
  background: #57c7d4;
}

.switchery > small {
  padding-left: 0;
}

.note-editor.note-frame,
.note-popover.note-frame {
  border-color: #f1f2f3;
}

.note-editor .dropdown-menu,
.note-popover .dropdown-menu {
  white-space: nowrap;
  width: auto;
}

.note-editor .dropdown-menu li a,
.note-popover .dropdown-menu li a {
  display: block;
  margin: 4px;
  padding: 4px 12px;
}

.note-editor .dropdown-menu li a:hover,
.note-popover .dropdown-menu li a:hover {
  background-color: #f9fafb;
}

.note-editor .dropdown-menu li a > *,
.note-popover .dropdown-menu li a > * {
  margin: 0;
}

.note-editor .note-color .dropdown-menu,
.note-popover .note-color .dropdown-menu {
  padding: 5px 0;
}

.note-editor .note-color .btn + .btn::after,
.note-popover .note-color .btn + .btn::after {
  margin-left: 0;
}

.note-toolbar .btn,
.note-popover .btn {
  background-color: #eff1f2;
  color: #8b95a5;
  font-size: .8125rem;
}

.note-toolbar .btn:hover, .note-toolbar .btn.active,
.note-popover .btn:hover,
.note-popover .btn.active {
  background-color: #eaecee;
  color: #4d5259;
}

.note-toolbar .btn.dropdown-toggle,
.note-popover .btn.dropdown-toggle {
  z-index: initial;
}

.note-toolbar .open .dropdown-toggle,
.note-toolbar .show .dropdown-toggle,
.note-popover .open .dropdown-toggle,
.note-popover .show .dropdown-toggle {
  background-color: #eaecee;
}

.panel-heading.note-toolbar,
.note-editor.note-frame .note-statusbar {
  background-color: #f9fafb;
  border-bottom: 1px solid #f4f5f5;
}

.note-icon-caret,
.note-popover.popover {
  display: none;
}

.note-editor.note-frame .note-statusbar .note-resizebar .note-icon-bar {
  border-top-color: #ebebeb;
}

.note-popover .popover-content .note-para .dropdown-menu,
.panel-heading.note-toolbar .note-para .dropdown-menu {
  min-width: 204px;
}

.note-editor.note-frame .note-editing-area .note-codable {
  background-color: #f9fafb;
  color: #4d5259;
}

.note-editor.note-frame .note-editing-area .note-codable:focus {
  outline: none;
}

.note-popover .popover-content .note-color .dropdown-menu .btn-group .note-color-reset,
.panel-heading.note-toolbar .note-color .dropdown-menu .btn-group .note-color-reset {
  border-radius: 2px;
  margin: 5px;
  height: 29px;
  line-height: 27px;
  font-size: 0.75rem;
}

.note-popover .popover-content .note-color .dropdown-menu .btn-group .note-color-reset:hover,
.panel-heading.note-toolbar .note-color .dropdown-menu .btn-group .note-color-reset:hover {
  background-color: #f9fafb;
}

.note-palette-title {
  font-weight: 400;
  line-height: 24px;
}

.note-btn-group.note-fontname {
  vertical-align: top;
}

.note-btn-group.note-fontname .note-btn {
  line-height: 22px;
}

.note-btn-group.note-fontsize {
  vertical-align: top;
}

[data-provide~="summernote"].b-0 + .note-editor {
  border: none;
}

[data-provide~="summernote"].b-0 + .note-editor .note-statusbar {
  display: none;
}

/*
 * Fix for Bootstrap Beta 1. Color picker doesn't open.
 */
.note-popover .popover-content .note-color .dropdown-menu .btn-group,
.panel-heading.note-toolbar .note-color .dropdown-menu .btn-group {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
}

.ql-bubble .ql-out-bottom,
.ql-bubble .ql-out-top {
  visibility: visible;
}

.ql-toolbar.ql-snow {
  font-family: Roboto, sans-serif;
  border-color: #ebebeb;
  border-bottom-color: #f1f2f3;
  background-color: #fcfdfe;
}

.ql-container.ql-snow {
  border-color: #ebebeb;
}

.ql-toolbar.ql-snow .ql-picker-label {
  font-weight: 300;
}

.ql-editor.ql-blank::before {
  color: #c9ccce;
  font-style: normal;
}

.ql-container {
  font-family: Roboto, sans-serif;
  height: auto;
}

.ql-editor {
  line-height: 24px;
}

.quill-no-border .ql-container.ql-snow {
  border: none;
}

.quill-no-border .ql-toolbar.ql-snow {
  border: none;
  border-bottom: 1px solid #f1f2f3;
}

.lookup {
  position: relative;
  display: -webkit-inline-box;
  display: inline-flex;
  -webkit-box-align: center;
          align-items: center;
}

.lookup::before {
  content: "\e610";
  font-family: themify;
  font-size: 16px;
  position: absolute;
  top: 52%;
  left: 0;
  -webkit-transform: translateY(-50%);
          transform: translateY(-50%);
  color: rgba(77, 82, 89, 0.4);
  width: 36px;
  text-align: center;
  cursor: text;
}

.lookup input {
  color: #4d5259;
  border: 1px solid #ebebeb;
  border-radius: 18px;
  height: 36px;
  width: 200px;
  max-width: 100%;
  padding-left: 36px;
  padding-right: 18px;
  font-family: Roboto, sans-serif;
  font-size: 15px;
  font-weight: 300;
  letter-spacing: .5px;
  outline: none !important;
  -webkit-transition: .5s;
  transition: .5s;
}

.lookup input::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  color: rgba(77, 82, 89, 0.7);
}

.lookup input::-moz-placeholder {
  /* Firefox 19+ */
  color: rgba(77, 82, 89, 0.7);
}

.lookup input:-ms-input-placeholder {
  /* IE 10+ */
  color: rgba(77, 82, 89, 0.7);
}

.lookup input:-moz-placeholder {
  /* Firefox 18- */
  color: rgba(77, 82, 89, 0.7);
}

.lookup input:focus {
  background-color: rgba(77, 82, 89, 0.04);
}

.lookup input + input,
.lookup input + .bootstrap-select .dropdown-toggle {
  border-left: none;
}

.lookup .bootstrap-select + .bootstrap-select .dropdown-toggle {
  border-left: none;
}

.lookup .btn {
  height: 36px;
  line-height: 36px;
}

.lookup.no-icon::before {
  display: none;
}

.lookup.no-icon input {
  padding-left: 18px;
}

.lookup-sm::before {
  font-size: 14px;
  width: 29px;
}

.lookup-sm input {
  border-radius: 14.5px;
  height: 29px;
  width: 29px;
  padding-left: 29px;
  padding-right: 14.5px;
  font-size: 14px;
  width: 160px;
}

.lookup-sm .btn {
  height: 29px;
  line-height: 29px;
}

.lookup-lg::before {
  font-size: 20px;
  width: 48px;
}

.lookup-lg input {
  border-radius: 24px;
  height: 48px;
  width: 48px;
  padding-left: 48px;
  padding-right: 24px;
  font-size: 16px;
  width: 250px;
}

.lookup-lg .btn {
  height: 48px;
  line-height: 48px;
}

.lookup-right::before {
  left: auto;
  right: 0;
}

.lookup-right input {
  padding-left: 18px;
  padding-right: 36px;
}

.lookup-right.lookup-sm input {
  padding-left: 14.5px;
  padding-right: 29px;
}

.lookup-right.lookup-lg input {
  padding-left: 24px;
  padding-right: 48px;
}

.lookup-right.no-icon input {
  padding-right: 18px;
}

.lookup-right.no-icon.lookup-sm input {
  padding-right: 14.5px;
}

.lookup-right.no-icon.lookup-lg input {
  padding-right: 24px;
}

.lookup-circle {
  z-index: 0;
}

.lookup-circle::before {
  z-index: -1;
}

.lookup-circle input {
  background-color: rgba(77, 82, 89, 0.04);
  border: none;
  width: 36px;
  padding-right: 0;
}

.lookup-circle input:focus {
  background-color: rgba(77, 82, 89, 0.05);
  width: 170px;
  padding-right: 18px;
}

.lookup-circle.lookup-sm::before {
  font-size: 14px;
  width: 29px;
}

.lookup-circle.lookup-sm input {
  border-radius: 14.5px;
  height: 29px;
  width: 29px;
  padding-left: 29px;
}

.lookup-circle.lookup-sm input:focus {
  width: 150px;
  padding-right: 14.5px;
}

.lookup-circle.lookup-lg::before {
  font-size: 16px;
  width: 48px;
}

.lookup-circle.lookup-lg input {
  border-radius: 24px;
  height: 48px;
  width: 48px;
  padding-left: 48px;
}

.lookup-circle.lookup-lg input:focus {
  width: 200px;
  padding-right: 24px;
}

.lookup-circle.lookup-right::before {
  left: auto;
  right: 0;
}

.lookup-circle.lookup-right input {
  padding-left: 0;
  padding-right: 36px;
}

.lookup-circle.lookup-right input:focus {
  padding-left: 18px;
}

.lookup-circle.lookup-right.lookup-sm input {
  padding-left: 0;
  padding-right: 29px;
}

.lookup-circle.lookup-right.lookup-sm input:focus {
  padding-left: 14.5px;
}

.lookup-circle.lookup-right.lookup-lg input {
  padding-left: 0;
  padding-right: 48px;
}

.lookup-circle.lookup-right.lookup-lg input:focus {
  padding-left: 24px;
}

.lookup-huge {
  display: -webkit-box;
  display: flex;
}

.lookup-huge::before {
  font-size: 24px;
  width: 64px;
}

.lookup-huge input {
  border-radius: 32px;
  height: 64px;
  width: 64px;
  padding-left: 64px;
  padding-right: 32px;
  font-size: 20px;
  font-weight: 100;
  width: 100%;
}

.lookup-huge input::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  color: #c9ccce;
}

.lookup-huge input::-moz-placeholder {
  /* Firefox 19+ */
  color: #c9ccce;
}

.lookup-huge input:-ms-input-placeholder {
  /* IE 10+ */
  color: #c9ccce;
}

.lookup-huge input:-moz-placeholder {
  /* Firefox 18- */
  color: #c9ccce;
}

.lookup-huge .btn,
.lookup-huge .bootstrap-select.btn-group > .dropdown-toggle {
  height: 64px;
  line-height: 100%;
}

.lookup-huge.no-icon input {
  padding-left: 32px;
}

.lookup-fullscreen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.985);
  z-index: 1050;
  padding: 50px 5%;
  display: none;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
}

.lookup-fullscreen.reveal {
  display: -webkit-box;
  display: flex;
}

.lookup-fullscreen > .close {
  position: absolute;
  top: 32px;
  right: 32px;
}

.lookup-fullscreen .lookup-form {
  border-bottom: 1px solid #ebebeb;
  margin-bottom: 2rem;
}

.lookup-fullscreen .lookup-form input {
  border: none;
  font-size: 44px;
  background-color: transparent;
  outline: none !important;
  padding: 25px 0;
  color: #4d5259;
  width: 100%;
}

.lookup-fullscreen .lookup-form input::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  color: #c9ccce;
}

.lookup-fullscreen .lookup-form input::-moz-placeholder {
  /* Firefox 19+ */
  color: #c9ccce;
}

.lookup-fullscreen .lookup-form input:-ms-input-placeholder {
  /* IE 10+ */
  color: #c9ccce;
}

.lookup-fullscreen .lookup-form input:-moz-placeholder {
  /* Firefox 18- */
  color: #c9ccce;
}

.lookup-fullscreen .lookup-results {
  height: 100%;
}

.lookup-fullscreen .lookup-results .ps-scrollbar-x-rail {
  display: none;
}

@media (max-width: 767px) {
  .lookup-fullscreen .lookup-results.scrollable .row {
    margin-left: 0;
    margin-right: 0;
  }
  .lookup-fullscreen .lookup-results.scrollable .row [class*="col-"] {
    padding-left: 0;
    padding-right: 0;
  }
}

.publisher {
  position: relative;
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  padding: 12px 20px;
  background-color: #f9fafb;
}

.publisher > * {
  margin: 0 8px;
}

.publisher > *:first-child {
  margin-left: 0;
}

.publisher > *:last-child {
  margin-right: 0;
}

.publisher-multi {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-align: start;
          align-items: flex-start;
}

.publisher-multi > * {
  margin: 0;
  -webkit-box-flex: 1;
          flex-grow: 1;
  width: 100%;
}

.publisher-multi .publisher-input {
  margin-bottom: 1rem;
}

.publisher-input {
  -webkit-box-flex: 1;
          flex-grow: 1;
  border: none;
  outline: none !important;
  background-color: transparent;
}

.publisher-btn {
  background-color: transparent;
  border: none;
  color: #8b95a5;
  font-size: 16px;
  cursor: pointer;
  overflow: -moz-hidden-unscrollable;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.publisher-btn:hover {
  color: #4d5259;
}

.publisher-avatar {
  position: absolute;
  width: auto;
  left: -18px;
  top: 8px;
}

.publisher-avatar.avatar-sm {
  left: -14.5px;
  top: auto;
}

[data-wizard].disabled {
  opacity: 0;
}

@media (min-width: 992px) {
  .modal-open .topbar {
    padding-right: 37px;
  }
}

body .main-container,
body > main,
body > div > main {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  min-height: 100vh;
  -webkit-transition: margin-left .3s ease-out;
  transition: margin-left .3s ease-out;
}

body .topbar + .main-container,
body .topbar + main {
  padding-top: 64px;
}

body .topbar-secondary + .main-container,
body .topbar-secondary + main {
  padding-top: 80px;
}

.main-content {
  padding: 30px 30px 0;
  -webkit-box-flex: 1;
          flex: 1 0 auto;
}

.main-content.container {
  padding-left: 15px;
  padding-right: 15px;
}

.main-content > .container {
  padding-left: 0;
  padding-right: 0;
}

.container-full {
  margin-left: -30px;
  margin-right: -30px;
}

.site-footer {
  padding: 15px 30px;
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

@media (min-width: 768px) {
  .card-maximize {
    left: 260px;
  }
}

@media (max-width: 991px) {
  .topbar,
  body .main-container,
  body > main,
  body > div > main {
    margin-left: 0;
  }
  .card-maximize {
    left: 0;
  }
  .site-footer,
  .site-footer .container {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
  }
}

@media (max-width: 767px) {
  .topbar {
    padding: 0 0.25rem;
  }
  .site-footer,
  .main-content {
    padding-left: 15px;
    padding-right: 15px;
  }
  .container-full {
    margin-left: -15px;
    margin-right: -15px;
  }
}

@media print {
  body * {
    visibility: hidden;
  }
  .printing-area {
    visibility: visible;
    position: absolute;
    left: 0;
    top: 0;
  }
  .printing-area * {
    visibility: visible;
  }
}

body {
  background-color: #f5f6fa;
}

.sticker-stick {
  position: fixed;
}

.row.no-gap, .row.no-gutters {
  margin-left: 0;
  margin-right: 0;
}

.row.no-gap > .col,
.row.no-gap > [class*="col-"], .row.no-gutters > .col,
.row.no-gutters > [class*="col-"] {
  padding-right: 0;
  padding-left: 0;
}

.row.gap-1 {
  margin-left: -4px;
  margin-right: -4px;
}

.row.gap-1 > .col,
.row.gap-1 > [class*="col-"] {
  padding-left: 4px;
  padding-right: 4px;
}

@media (max-width: 767px) {
  .row.gap-1 {
    margin-left: -1px;
    margin-right: -1px;
  }
  .row.gap-1 > .col,
  .row.gap-1 > [class*="col-"] {
    padding-left: 1px;
    padding-right: 1px;
  }
}

.row.gap-2 {
  margin-left: -8px;
  margin-right: -8px;
}

.row.gap-2 > .col,
.row.gap-2 > [class*="col-"] {
  padding-left: 8px;
  padding-right: 8px;
}

@media (max-width: 767px) {
  .row.gap-2 {
    margin-left: -2px;
    margin-right: -2px;
  }
  .row.gap-2 > .col,
  .row.gap-2 > [class*="col-"] {
    padding-left: 2px;
    padding-right: 2px;
  }
}

.row.gap-3 {
  margin-left: -15px;
  margin-right: -15px;
}

.row.gap-3 > .col,
.row.gap-3 > [class*="col-"] {
  padding-left: 15px;
  padding-right: 15px;
}

@media (max-width: 767px) {
  .row.gap-3 {
    margin-left: -3.75px;
    margin-right: -3.75px;
  }
  .row.gap-3 > .col,
  .row.gap-3 > [class*="col-"] {
    padding-left: 3.75px;
    padding-right: 3.75px;
  }
}

.row.gap-4 {
  margin-left: -24px;
  margin-right: -24px;
}

.row.gap-4 > .col,
.row.gap-4 > [class*="col-"] {
  padding-left: 24px;
  padding-right: 24px;
}

@media (max-width: 767px) {
  .row.gap-4 {
    margin-left: -6px;
    margin-right: -6px;
  }
  .row.gap-4 > .col,
  .row.gap-4 > [class*="col-"] {
    padding-left: 6px;
    padding-right: 6px;
  }
}

.row.gap-5 {
  margin-left: -32px;
  margin-right: -32px;
}

.row.gap-5 > .col,
.row.gap-5 > [class*="col-"] {
  padding-left: 32px;
  padding-right: 32px;
}

@media (max-width: 767px) {
  .row.gap-5 {
    margin-left: -8px;
    margin-right: -8px;
  }
  .row.gap-5 > .col,
  .row.gap-5 > [class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
  }
}

.row.gap-y {
  margin-top: -15px;
  margin-bottom: -15px;
}

.row.gap-y > .col,
.row.gap-y > [class*="col-"] {
  padding-top: 15px;
  padding-bottom: 15px;
}

@media (max-width: 767px) {
  .row.gap-y {
    margin-top: -3.75px;
    margin-bottom: -3.75px;
  }
  .row.gap-y > .col,
  .row.gap-y > [class*="col-"] {
    padding-top: 3.75px;
    padding-bottom: 3.75px;
  }
}

.row.gap-y.gap-1 {
  margin-top: -4px;
  margin-bottom: -4px;
}

.row.gap-y.gap-1 > .col,
.row.gap-y.gap-1 > [class*="col-"] {
  padding-top: 4px;
  padding-bottom: 4px;
}

@media (max-width: 767px) {
  .row.gap-y.gap-1 {
    margin-top: -1px;
    margin-bottom: -1px;
  }
  .row.gap-y.gap-1 > .col,
  .row.gap-y.gap-1 > [class*="col-"] {
    padding-top: 1px;
    padding-bottom: 1px;
  }
}

.row.gap-y.gap-2 {
  margin-top: -8px;
  margin-bottom: -8px;
}

.row.gap-y.gap-2 > .col,
.row.gap-y.gap-2 > [class*="col-"] {
  padding-top: 8px;
  padding-bottom: 8px;
}

@media (max-width: 767px) {
  .row.gap-y.gap-2 {
    margin-top: -2px;
    margin-bottom: -2px;
  }
  .row.gap-y.gap-2 > .col,
  .row.gap-y.gap-2 > [class*="col-"] {
    padding-top: 2px;
    padding-bottom: 2px;
  }
}

.row.gap-y.gap-3 {
  margin-top: -15px;
  margin-bottom: -15px;
}

.row.gap-y.gap-3 > .col,
.row.gap-y.gap-3 > [class*="col-"] {
  padding-top: 15px;
  padding-bottom: 15px;
}

@media (max-width: 767px) {
  .row.gap-y.gap-3 {
    margin-top: -3.75px;
    margin-bottom: -3.75px;
  }
  .row.gap-y.gap-3 > .col,
  .row.gap-y.gap-3 > [class*="col-"] {
    padding-top: 3.75px;
    padding-bottom: 3.75px;
  }
}

.row.gap-y.gap-4 {
  margin-top: -24px;
  margin-bottom: -24px;
}

.row.gap-y.gap-4 > .col,
.row.gap-y.gap-4 > [class*="col-"] {
  padding-top: 24px;
  padding-bottom: 24px;
}

@media (max-width: 767px) {
  .row.gap-y.gap-4 {
    margin-top: -6px;
    margin-bottom: -6px;
  }
  .row.gap-y.gap-4 > .col,
  .row.gap-y.gap-4 > [class*="col-"] {
    padding-top: 6px;
    padding-bottom: 6px;
  }
}

.row.gap-y.gap-5 {
  margin-top: -32px;
  margin-bottom: -32px;
}

.row.gap-y.gap-5 > .col,
.row.gap-y.gap-5 > [class*="col-"] {
  padding-top: 32px;
  padding-bottom: 32px;
}

@media (max-width: 767px) {
  .row.gap-y.gap-5 {
    margin-top: -8px;
    margin-bottom: -8px;
  }
  .row.gap-y.gap-5 > .col,
  .row.gap-y.gap-5 > [class*="col-"] {
    padding-top: 8px;
    padding-bottom: 8px;
  }
}

.flexbox {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
}

.flexbox > * {
  margin-left: 4px;
  margin-right: 4px;
}

.flexbox > *:first-child {
  margin-left: 0;
}

.flexbox > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .flexbox > * {
    margin-left: 2px;
    margin-right: 2px;
  }
  .flexbox > *:first-child {
    margin-left: 0;
  }
  .flexbox > *:last-child {
    margin-right: 0;
  }
}

.flexbox.no-gap > *,
.flexbox.no-gutters > * {
  margin-left: 0;
  margin-right: 0;
}

ul.flexbox {
  list-style: none;
  padding-left: 0;
  margin-bottom: 0;
}

.flexbox-vertical {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-pack: justify;
          justify-content: space-between;
}

.flexbox-vertical.no-gap > *,
.flexbox-vertical.no-gutters > * {
  margin-top: 0;
  margin-bottom: 0;
}

.flex-justified > *,
.flex-grow-all > *,
.flex-cols-wide > *,
.flex-col-wide,
.flex-grow {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
}

.flex-grow-1 {
  -webkit-box-flex: 1;
          flex-grow: 1;
}

.flex-grow-2 {
  -webkit-box-flex: 2;
          flex-grow: 2;
}

.flex-grow-3 {
  -webkit-box-flex: 3;
          flex-grow: 3;
}

.flex-grow-4 {
  -webkit-box-flex: 4;
          flex-grow: 4;
}

.flex-grow-5 {
  -webkit-box-flex: 5;
          flex-grow: 5;
}

.flex-grow-6 {
  -webkit-box-flex: 6;
          flex-grow: 6;
}

.flex-grow-7 {
  -webkit-box-flex: 7;
          flex-grow: 7;
}

.flex-grow-8 {
  -webkit-box-flex: 8;
          flex-grow: 8;
}

.flex-grow-9 {
  -webkit-box-flex: 9;
          flex-grow: 9;
}

.flex-grow-0 {
  -webkit-box-flex: 0;
          flex-grow: 0;
}

.masonry-grid {
  -webkit-column-count: 3;
     -moz-column-count: 3;
          column-count: 3;
  -webkit-column-gap: 30px;
     -moz-column-gap: 30px;
          column-gap: 30px;
}

.masonry-grid.no-gap, .masonry-grid.no-gutters {
  -webkit-column-gap: 0;
     -moz-column-gap: 0;
          column-gap: 0;
}

.masonry-grid.no-gap .masonry-item, .masonry-grid.no-gutters .masonry-item {
  padding-bottom: 0;
}

@media (max-width: 767px) {
  .masonry-grid.no-gap, .masonry-grid.no-gutters {
    -webkit-column-gap: 0;
       -moz-column-gap: 0;
            column-gap: 0;
  }
  .masonry-grid.no-gap .masonry-item, .masonry-grid.no-gutters .masonry-item {
    padding-bottom: 0;
  }
}

.masonry-grid.gap-1 {
  -webkit-column-gap: 8px;
     -moz-column-gap: 8px;
          column-gap: 8px;
}

.masonry-grid.gap-1 .masonry-item {
  padding-bottom: 8px;
}

@media (max-width: 767px) {
  .masonry-grid.gap-1 {
    -webkit-column-gap: 4px;
       -moz-column-gap: 4px;
            column-gap: 4px;
  }
  .masonry-grid.gap-1 .masonry-item {
    padding-bottom: 4px;
  }
}

.masonry-grid.gap-2 {
  -webkit-column-gap: 16px;
     -moz-column-gap: 16px;
          column-gap: 16px;
}

.masonry-grid.gap-2 .masonry-item {
  padding-bottom: 16px;
}

@media (max-width: 767px) {
  .masonry-grid.gap-2 {
    -webkit-column-gap: 8px;
       -moz-column-gap: 8px;
            column-gap: 8px;
  }
  .masonry-grid.gap-2 .masonry-item {
    padding-bottom: 8px;
  }
}

.masonry-grid.gap-3 {
  -webkit-column-gap: 30px;
     -moz-column-gap: 30px;
          column-gap: 30px;
}

.masonry-grid.gap-3 .masonry-item {
  padding-bottom: 30px;
}

@media (max-width: 767px) {
  .masonry-grid.gap-3 {
    -webkit-column-gap: 15px;
       -moz-column-gap: 15px;
            column-gap: 15px;
  }
  .masonry-grid.gap-3 .masonry-item {
    padding-bottom: 15px;
  }
}

.masonry-grid.gap-4 {
  -webkit-column-gap: 48px;
     -moz-column-gap: 48px;
          column-gap: 48px;
}

.masonry-grid.gap-4 .masonry-item {
  padding-bottom: 48px;
}

@media (max-width: 767px) {
  .masonry-grid.gap-4 {
    -webkit-column-gap: 24px;
       -moz-column-gap: 24px;
            column-gap: 24px;
  }
  .masonry-grid.gap-4 .masonry-item {
    padding-bottom: 24px;
  }
}

.masonry-grid.gap-5 {
  -webkit-column-gap: 64px;
     -moz-column-gap: 64px;
          column-gap: 64px;
}

.masonry-grid.gap-5 .masonry-item {
  padding-bottom: 64px;
}

@media (max-width: 767px) {
  .masonry-grid.gap-5 {
    -webkit-column-gap: 32px;
       -moz-column-gap: 32px;
            column-gap: 32px;
  }
  .masonry-grid.gap-5 .masonry-item {
    padding-bottom: 32px;
  }
}

.masonry-cols-2 {
  -webkit-column-count: 2;
     -moz-column-count: 2;
          column-count: 2;
}

.masonry-cols-3 {
  -webkit-column-count: 3;
     -moz-column-count: 3;
          column-count: 3;
}

.masonry-cols-4 {
  -webkit-column-count: 4;
     -moz-column-count: 4;
          column-count: 4;
}

.masonry-cols-5 {
  -webkit-column-count: 5;
     -moz-column-count: 5;
          column-count: 5;
}

.masonry-item {
  display: block;
  -webkit-column-break-inside: avoid;
     page-break-inside: avoid;
          break-inside: avoid;
  padding-bottom: 30px;
}

.layout-chat {
  height: 100vh;
}

.layout-chat .main-content {
  display: -webkit-box;
  display: flex;
  height: 10%;
}

.menu {
  list-style: none;
  padding-left: 0;
  margin-bottom: 30px;
}

.menu-item {
  vertical-align: top;
  -webkit-transition: opacity 0.2s linear;
  transition: opacity 0.2s linear;
}

.menu-link {
  height: 56px;
  padding: 0 12px;
  font-weight: 400;
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.menu-link > * {
  margin-left: 8px;
  margin-right: 8px;
}

.menu-link .icon {
  font-size: 18px;
  font-weight: 500;
  letter-spacing: 1px;
  text-align: center;
  flex-basis: 30px;
  flex-shrink: 0;
}

.menu-link .icon::before {
  letter-spacing: 0;
}

.menu-link .dot {
  position: relative;
  flex-basis: 30px;
  flex-shrink: 0;
}

.menu-link .dot::after {
  content: '';
  position: absolute;
  top: -4px;
  left: 50%;
  margin-left: -4px;
  width: 8px;
  height: 8px;
  border: 1px solid #fff;
  border-radius: 100%;
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.menu-link .title {
  -webkit-box-flex: 1;
          flex-grow: 1;
  display: -webkit-box;
  display: flex;
  flex-shrink: 0;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  line-height: 1;
  font-size: 14px;
}

.menu-link .title small {
  color: inherit;
  font-weight: 300;
  font-size: 11px;
  margin-top: .375rem;
  opacity: 0.4;
}

.menu-link .arrow::before {
  content: "\e649";
  font-family: themify;
  font-size: 8px;
  cursor: pointer;
  display: inline-block;
  -webkit-transition: -webkit-transform 0.3s linear;
  transition: -webkit-transform 0.3s linear;
  transition: transform 0.3s linear;
  transition: transform 0.3s linear, -webkit-transform 0.3s linear;
}

.menu-link .badge {
  margin-top: auto;
  margin-bottom: auto;
}

.menu-submenu {
  background-color: #455160;
  padding-top: 1rem;
  padding-bottom: 1rem;
  padding-left: 0;
  display: none;
  list-style-type: none;
  margin-bottom: 0;
}

.menu-submenu .menu-link {
  height: 32px;
  font-size: .875rem;
  font-weight: 300;
  letter-spacing: 0;
  color: #fff;
  opacity: 0.8;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.menu-submenu .menu-link .icon {
  font-size: 13px;
  font-weight: 400;
}

.menu-submenu .menu-link .title {
  -font-size: 13px;
}

.menu-submenu .menu-link .arrow::before {
  font-size: .5rem;
}

.menu-submenu .menu-item.active > .menu-link,
.menu-submenu .menu-item:hover > .menu-link {
  opacity: 1;
  background-color: transparent;
}

.menu-sub-submenu {
  list-style-type: none;
  margin-bottom: 0;
  padding-left: 40px;
}

.menu-sub-submenu .menu-item {
  padding-left: 12px;
  opacity: 0.7;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.menu-sub-submenu .menu-item:hover, .menu-sub-submenu .menu-item.active {
  opacity: 1;
}

.menu-sub-submenu .menu-link .dot {
  flex-basis: 0;
}

.menu-sub-submenu .menu-link .dot::after {
  left: -12px;
}

.menu-category {
  position: relative;
  font-family: Roboto, sans-serif;
  display: block;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 0.6875rem;
  font-weight: 500;
  line-height: 32px;
  padding: 1rem 20px 0.5rem;
  color: #fff;
  opacity: 0.7;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.menu-category::after {
  content: '';
  width: 100%;
  height: 0;
  border-top: 1px dashed #fff;
  vertical-align: middle;
  margin-left: 1.5rem;
  opacity: 0.3;
  position: absolute;
  top: 50%;
  margin-top: 3px;
}

.menu-divider {
  display: block;
  height: 1px;
  background-color: #fff;
  opacity: 0.08;
  margin: 1rem 0;
}

.menu-xs > .menu-item > .menu-link {
  height: 42px;
}

.menu-sm > .menu-item > .menu-link {
  height: 48px;
}

.menu-lg > .menu-item > .menu-link {
  height: 64px;
}

.menu-xl > .menu-item > .menu-link {
  height: 72px;
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 260px;
  background-color: #3f4a59;
  white-space: nowrap;
  -webkit-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.08);
          box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.08);
  display: -webkit-box;
  display: flex;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  z-index: 997;
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

.sidebar ~ .topbar,
.sidebar ~ main {
  margin-left: 260px;
}

.sidebar-sm {
  width: 220px;
}

.sidebar-sm ~ .topbar,
.sidebar-sm ~ main {
  margin-left: 220px;
}

.sidebar-lg {
  width: 300px;
}

.sidebar-lg ~ .topbar,
.sidebar-lg ~ main {
  margin-left: 300px;
}

.sidebar-header,
.sidebar-footer {
  height: 64px;
}

.sidebar-navigation {
  position: relative;
  overflow: hidden;
  -webkit-box-flex: 1;
          flex: 1 1 0%;
}

.sidebar-profile {
  text-align: center;
  padding: 40px 20px 30px;
}

.sidebar-profile .avatar {
  width: 100px;
  height: 100px;
  -webkit-transition: .5s;
  transition: .5s;
}

.sidebar-profile .profile-info {
  -webkit-transform: scale(1);
          transform: scale(1);
  margin-top: 1rem;
  -webkit-transition: .5s;
  transition: .5s;
}

.sidebar-header {
  background-color: #926dde;
  padding: 0 12px;
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  color: #fff;
}

.sidebar-header > * {
  margin-left: 8px;
  margin-right: 8px;
}

.sidebar-header .logo-icon {
  text-align: center;
  flex-basis: 30px;
  font-size: 1.25rem;
  flex-shrink: 0;
}

.sidebar-header .logo {
  font-size: 1rem;
  font-weight: 500;
  -webkit-box-flex: 1;
          flex-grow: 1;
}

.sidebar-header a {
  color: #fff;
}

.sidebar-toggle-fold {
  margin: 0;
  padding: 12px 8px;
  cursor: pointer;
}

.sidebar-toggle-fold::before {
  content: '';
  display: inline-block;
  width: 10px;
  height: 10px;
  border: 2px solid #fff;
  border-radius: 100%;
  background-color: #fff;
  -webkit-transition: 0.2s linear;
  transition: 0.2s linear;
}

.sidebar-folded .sidebar-toggle-fold::before {
  background-color: transparent;
}

.sidebar-header-inverse {
  color: #4d5259;
  background-color: #fff;
}

.sidebar-header-inverse .sidebar-toggle-fold::before {
  border-color: #4d5259;
}

.sidebar-header-inverse a {
  color: #4d5259;
}

.sidebar-folded .sidebar-header-inverse .sidebar-toggle-fold::before {
  background-color: #4d5259;
}

.sidebar-footer {
  border-top: 1px solid rgba(77, 82, 89, 0.07);
  padding: 0 16px;
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center;
}

.sidebar-footer > * {
  margin: 0 4px;
}

.sidebar-footer > *:first-child {
  margin-left: 0;
}

.sidebar-footer > *:last-child {
  margin-right: 0;
}

.sidebar-spacer-sm {
  height: 32px;
}

.sidebar-spacer {
  height: 64px;
}

.sidebar-spacer-lg {
  height: 96px;
}

.sidebar-open {
  overflow: hidden;
}

.sidebar-open .sidebar {
  left: 0 !important;
  -webkit-box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1) !important;
          box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1) !important;
}

.sidebar-folded .sidebar ~ .topbar,
.sidebar-folded .sidebar ~ main {
  margin-left: 80px;
}

.sidebar-folded .sidebar .hide-on-fold {
  display: none;
}

.sidebar-folded .sidebar .fade-on-fold {
  opacity: 0;
  -webkit-transition: .5s;
  transition: .5s;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right) {
  width: 80px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right) .sidebar-profile .avatar {
  width: 40px;
  height: 40px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right) .sidebar-profile .profile-info {
  -webkit-transform: scale(0.33);
          transform: scale(0.33);
  margin-left: -100px;
  margin-right: -100px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right):hover {
  width: 260px;
  -webkit-box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1);
          box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1);
}

.sidebar-folded .sidebar:not(.sidebar-icons-right):hover .sidebar-profile .avatar {
  width: 100px;
  height: 100px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right):hover .sidebar-profile .profile-info {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.sidebar-folded .sidebar:not(.sidebar-icons-right).sidebar-sm:hover {
  width: 220px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right).sidebar-lg:hover {
  width: 300px;
}

.sidebar-folded .sidebar-icons-right {
  left: -180px;
}

.sidebar-folded .sidebar-icons-right:hover {
  left: 0;
  -webkit-box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1);
          box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1);
}

.sidebar-folded .sidebar-icons-right.sidebar-sm {
  left: -140px;
}

.sidebar-folded .sidebar-icons-right.sidebar-lg {
  left: -220px;
}

.sidebar-folded .card-maximize {
  left: 80px;
}

.sidebar-folded .sidebar .hide-on-fold {
  display: initial;
}

.sidebar-folded .sidebar .fade-on-fold {
  opacity: 1;
}

.sidebar-folded .sidebar .title,
.sidebar-folded .sidebar .logo {
  margin-left: 8px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right) .sidebar-profile .avatar {
  width: 100px;
  height: 100px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right) .sidebar-profile .profile-info {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.sidebar-folded .sidebar.sidebar-icons-right:not(.sidebar-icons-boxed) .logo-icon,
.sidebar-folded .sidebar.sidebar-icons-right:not(.sidebar-icons-boxed) .icon,
.sidebar-folded .sidebar.sidebar-icons-right:not(.sidebar-icons-boxed) .dot {
  margin-left: 8px;
  flex-basis: 30px;
}

.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .logo-icon,
.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .icon {
  margin-left: 8px;
}

.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .menu-submenu .icon,
.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .menu-submenu .dot {
  margin-left: 13px;
}

.sidebar-hidden .sidebar {
  left: -260px;
}

.sidebar-hidden .sidebar.sidebar-lg {
  left: -300px;
}

.sidebar-hidden .sidebar.sidebar-sm {
  left: -220px;
}

.sidebar-hidden .sidebar ~ .topbar,
.sidebar-hidden .sidebar ~ main {
  margin-left: 0;
}

.sidebar .menu-item.open .menu-submenu {
  display: block;
}

.sidebar .menu-link {
  position: relative;
}

.sidebar .menu-link .title small {
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.sidebar .open .arrow::before {
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg);
}

.sidebar-icons-boxed .sidebar-header .logo-icon {
  flex-basis: 40px;
}

.sidebar-icons-boxed .menu-link .icon {
  font-size: 16px;
  background-color: rgba(0, 0, 0, 0.07);
  flex-basis: 40px;
  line-height: 40px;
  border-radius: 2px;
}

.sidebar-icons-boxed .menu-submenu .menu-link .icon {
  font-size: 13px;
  flex-basis: 30px;
  line-height: 30px;
}

.sidebar-icons-boxed .menu .active > .menu-link .icon {
  background-color: rgba(255, 255, 255, 0.15);
}

.sidebar-icons-boxed .menu-sub-submenu {
  padding-left: 50px;
}

.sidebar-icons-boxed .menu-submenu .icon,
.sidebar-icons-boxed .menu-submenu .dot {
  margin-left: 13px;
  margin-right: 13px;
}

.sidebar-icons-right .sidebar-header .logo-icon,
.sidebar-icons-right .sidebar-navigation .menu-link .icon,
.sidebar-icons-right .sidebar-navigation .menu-link .dot {
  -webkit-box-ordinal-group: 2;
          order: 1;
}

.sidebar-icons-right .sidebar-navigation .menu-sub-submenu {
  padding-left: 0;
}

.sidebar-folded .sidebar .title,
.sidebar-folded .sidebar .logo {
  -webkit-transition: .4s;
  transition: .4s;
}

.sidebar-folded .sidebar:not(.sidebar-icons-right) .title,
.sidebar-folded .sidebar:not(.sidebar-icons-right) .logo {
  margin-left: 20px;
}

.sidebar-folded .sidebar:not(.sidebar-icons-boxed):not(.sidebar-icons-right) .sidebar-header,
.sidebar-folded .sidebar:not(.sidebar-icons-boxed):not(.sidebar-icons-right) .menu-link {
  padding-left: 17px;
}

.sidebar-folded .sidebar.sidebar-icons-right:not(.sidebar-icons-boxed) .logo-icon,
.sidebar-folded .sidebar.sidebar-icons-right:not(.sidebar-icons-boxed) .icon,
.sidebar-folded .sidebar.sidebar-icons-right:not(.sidebar-icons-boxed) .dot {
  margin-left: 20px;
  flex-basis: 40px;
}

.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .logo-icon,
.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .icon,
.sidebar-folded .sidebar.sidebar-icons-right.sidebar-icons-boxed .dot {
  margin-left: 25px;
}

.sidebar-folded .sidebar:hover .hide-on-fold {
  display: initial;
}

.sidebar-folded .sidebar:hover .fade-on-fold {
  opacity: 1;
}

.sidebar-folded .sidebar:hover .title,
.sidebar-folded .sidebar:hover .logo {
  margin-left: 8px;
}

.sidebar-folded .sidebar:hover:not(.sidebar-icons-right) .sidebar-profile .avatar {
  width: 100px;
  height: 100px;
}

.sidebar-folded .sidebar:hover:not(.sidebar-icons-right) .sidebar-profile .profile-info {
  -webkit-transform: scale(1);
          transform: scale(1);
}

.sidebar-folded .sidebar:hover.sidebar-icons-right:not(.sidebar-icons-boxed) .logo-icon,
.sidebar-folded .sidebar:hover.sidebar-icons-right:not(.sidebar-icons-boxed) .icon,
.sidebar-folded .sidebar:hover.sidebar-icons-right:not(.sidebar-icons-boxed) .dot {
  margin-left: 8px;
  flex-basis: 30px;
}

.sidebar-folded .sidebar:hover.sidebar-icons-right.sidebar-icons-boxed .logo-icon,
.sidebar-folded .sidebar:hover.sidebar-icons-right.sidebar-icons-boxed .icon {
  margin-left: 8px;
}

.sidebar-folded .sidebar:hover.sidebar-icons-right.sidebar-icons-boxed .menu-submenu .icon,
.sidebar-folded .sidebar:hover.sidebar-icons-right.sidebar-icons-boxed .menu-submenu .dot {
  margin-left: 13px;
}

.sidebar-collapse .hidden-expand-down {
  display: none;
}

.sidebar-collapse ~ .topbar .sidebar-toggler {
  display: inline-block;
}

.sidebar-collapse .sidebar-toggle-fold {
  display: none;
}

.sidebar-collapse.sidebar {
  -webkit-box-shadow: none;
          box-shadow: none;
  left: -260px;
}

.sidebar-collapse.sidebar ~ .topbar,
.sidebar-collapse.sidebar ~ main {
  margin-left: 0;
}

.sidebar-collapse.sidebar-sm {
  left: -220px;
}

.sidebar-collapse.sidebar-lg {
  left: -300px;
}

.sidebar-expand .hidden-expand-up {
  display: none;
}

.sidebar-expand ~ .topbar .sidebar-toggler {
  display: none;
}

.sidebar-expand.sidebar-icon-only {
  width: 70px;
}

.sidebar-expand.sidebar-icon-only ~ .topbar,
.sidebar-expand.sidebar-icon-only ~ main {
  margin-left: 70px;
}

.sidebar-expand.sidebar-icon-only .menu > .menu-item.active > .menu-link,
.sidebar-expand.sidebar-icon-only .menu > .menu-item:hover > .menu-link {
  background-color: transparent !important;
}

.sidebar-expand.sidebar-icon-only .menu.menu-bordery > .menu-item > .menu-link::before {
  width: 3px;
}

.sidebar-expand.sidebar-iconic {
  width: 100px;
  overflow: visible;
}

.sidebar-expand.sidebar-iconic ~ .topbar,
.sidebar-expand.sidebar-iconic ~ main {
  margin-left: 100px;
}

.sidebar-expand.sidebar-iconic .menu-link {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  height: auto;
  padding-top: 12px;
  padding-bottom: 8px;
}

.sidebar-expand.sidebar-iconic .menu-link .title {
  -webkit-box-flex: 0;
          flex-grow: 0;
  line-height: 36px;
  font-weight: 300;
  letter-spacing: 0;
}

.sidebar-expand.sidebar-iconic .menu-link .icon {
  font-size: 20px;
  line-height: 36px;
}

.sidebar-expand.sidebar-iconic .menu-link .badge {
  position: absolute;
  top: 8px;
  right: 4px;
}

.sidebar-expand.sidebar-iconic .sidebar-header {
  -webkit-box-pack: center;
          justify-content: center;
}

.sidebar-expand.sidebar-iconic .sidebar-profile {
  padding: 20px;
}

.sidebar-expand.sidebar-iconic .sidebar-profile .avatar {
  width: 56px;
  height: 56px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm {
  width: 80px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm ~ .topbar,
.sidebar-expand.sidebar-iconic.sidebar-sm ~ main {
  margin-left: 80px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm .menu-link {
  padding-top: 8px;
  padding-bottom: 4px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm .menu-link .title {
  line-height: 29px;
  font-size: 12px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm .menu-link .icon {
  font-size: 16px;
  line-height: 29px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm .menu-link .badge {
  top: 4px;
  right: 2px;
}

.sidebar-expand.sidebar-iconic.sidebar-sm .sidebar-profile .avatar {
  width: 40px;
  height: 40px;
}

.sidebar-expand.sidebar-iconic.sidebar-lg {
  width: 120px;
}

.sidebar-expand.sidebar-iconic.sidebar-lg ~ .topbar,
.sidebar-expand.sidebar-iconic.sidebar-lg ~ main {
  margin-left: 120px;
}

.sidebar-expand.sidebar-iconic.sidebar-lg .menu-link {
  padding-top: 16px;
  padding-bottom: 12px;
}

.sidebar-expand.sidebar-iconic.sidebar-lg .menu-link .icon {
  font-size: 24px;
}

.sidebar-expand.sidebar-iconic.sidebar-lg .menu-link .badge {
  top: 12px;
  right: 8px;
}

.sidebar-expand.sidebar-iconic.sidebar-lg .sidebar-profile .avatar {
  width: 76px;
  height: 76px;
}

@media (max-width: 575px) {
  .sidebar-expand-sm .hidden-expand-down {
    display: none;
  }
  .sidebar-expand-sm ~ .topbar .sidebar-toggler {
    display: inline-block;
  }
  .sidebar-expand-sm .sidebar-toggle-fold {
    display: none;
  }
  .sidebar-expand-sm.sidebar {
    -webkit-box-shadow: none;
            box-shadow: none;
    left: -260px;
  }
  .sidebar-expand-sm.sidebar ~ .topbar,
  .sidebar-expand-sm.sidebar ~ main {
    margin-left: 0;
  }
  .sidebar-expand-sm.sidebar-sm {
    left: -220px;
  }
  .sidebar-expand-sm.sidebar-lg {
    left: -300px;
  }
}

@media (min-width: 576px) {
  .sidebar-expand-sm .hidden-expand-up {
    display: none;
  }
  .sidebar-expand-sm ~ .topbar .sidebar-toggler {
    display: none;
  }
  .sidebar-expand-sm.sidebar-icon-only {
    width: 70px;
  }
  .sidebar-expand-sm.sidebar-icon-only ~ .topbar,
  .sidebar-expand-sm.sidebar-icon-only ~ main {
    margin-left: 70px;
  }
  .sidebar-expand-sm.sidebar-icon-only .menu > .menu-item.active > .menu-link,
  .sidebar-expand-sm.sidebar-icon-only .menu > .menu-item:hover > .menu-link {
    background-color: transparent !important;
  }
  .sidebar-expand-sm.sidebar-icon-only .menu.menu-bordery > .menu-item > .menu-link::before {
    width: 3px;
  }
  .sidebar-expand-sm.sidebar-iconic {
    width: 100px;
    overflow: visible;
  }
  .sidebar-expand-sm.sidebar-iconic ~ .topbar,
  .sidebar-expand-sm.sidebar-iconic ~ main {
    margin-left: 100px;
  }
  .sidebar-expand-sm.sidebar-iconic .menu-link {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
    height: auto;
    padding-top: 12px;
    padding-bottom: 8px;
  }
  .sidebar-expand-sm.sidebar-iconic .menu-link .title {
    -webkit-box-flex: 0;
            flex-grow: 0;
    line-height: 36px;
    font-weight: 300;
    letter-spacing: 0;
  }
  .sidebar-expand-sm.sidebar-iconic .menu-link .icon {
    font-size: 20px;
    line-height: 36px;
  }
  .sidebar-expand-sm.sidebar-iconic .menu-link .badge {
    position: absolute;
    top: 8px;
    right: 4px;
  }
  .sidebar-expand-sm.sidebar-iconic .sidebar-header {
    -webkit-box-pack: center;
            justify-content: center;
  }
  .sidebar-expand-sm.sidebar-iconic .sidebar-profile {
    padding: 20px;
  }
  .sidebar-expand-sm.sidebar-iconic .sidebar-profile .avatar {
    width: 56px;
    height: 56px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm {
    width: 80px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm ~ .topbar,
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm ~ main {
    margin-left: 80px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm .menu-link {
    padding-top: 8px;
    padding-bottom: 4px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm .menu-link .title {
    line-height: 29px;
    font-size: 12px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm .menu-link .icon {
    font-size: 16px;
    line-height: 29px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm .menu-link .badge {
    top: 4px;
    right: 2px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-sm .sidebar-profile .avatar {
    width: 40px;
    height: 40px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg {
    width: 120px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg ~ .topbar,
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg ~ main {
    margin-left: 120px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg .menu-link {
    padding-top: 16px;
    padding-bottom: 12px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg .menu-link .icon {
    font-size: 24px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg .menu-link .badge {
    top: 12px;
    right: 8px;
  }
  .sidebar-expand-sm.sidebar-iconic.sidebar-lg .sidebar-profile .avatar {
    width: 76px;
    height: 76px;
  }
}

@media (max-width: 767px) {
  .sidebar-expand-md .hidden-expand-down {
    display: none;
  }
  .sidebar-expand-md ~ .topbar .sidebar-toggler {
    display: inline-block;
  }
  .sidebar-expand-md .sidebar-toggle-fold {
    display: none;
  }
  .sidebar-expand-md.sidebar {
    -webkit-box-shadow: none;
            box-shadow: none;
    left: -260px;
  }
  .sidebar-expand-md.sidebar ~ .topbar,
  .sidebar-expand-md.sidebar ~ main {
    margin-left: 0;
  }
  .sidebar-expand-md.sidebar-sm {
    left: -220px;
  }
  .sidebar-expand-md.sidebar-lg {
    left: -300px;
  }
}

@media (min-width: 768px) {
  .sidebar-expand-md .hidden-expand-up {
    display: none;
  }
  .sidebar-expand-md ~ .topbar .sidebar-toggler {
    display: none;
  }
  .sidebar-expand-md.sidebar-icon-only {
    width: 70px;
  }
  .sidebar-expand-md.sidebar-icon-only ~ .topbar,
  .sidebar-expand-md.sidebar-icon-only ~ main {
    margin-left: 70px;
  }
  .sidebar-expand-md.sidebar-icon-only .menu > .menu-item.active > .menu-link,
  .sidebar-expand-md.sidebar-icon-only .menu > .menu-item:hover > .menu-link {
    background-color: transparent !important;
  }
  .sidebar-expand-md.sidebar-icon-only .menu.menu-bordery > .menu-item > .menu-link::before {
    width: 3px;
  }
  .sidebar-expand-md.sidebar-iconic {
    width: 100px;
    overflow: visible;
  }
  .sidebar-expand-md.sidebar-iconic ~ .topbar,
  .sidebar-expand-md.sidebar-iconic ~ main {
    margin-left: 100px;
  }
  .sidebar-expand-md.sidebar-iconic .menu-link {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
    height: auto;
    padding-top: 12px;
    padding-bottom: 8px;
  }
  .sidebar-expand-md.sidebar-iconic .menu-link .title {
    -webkit-box-flex: 0;
            flex-grow: 0;
    line-height: 36px;
    font-weight: 300;
    letter-spacing: 0;
  }
  .sidebar-expand-md.sidebar-iconic .menu-link .icon {
    font-size: 20px;
    line-height: 36px;
  }
  .sidebar-expand-md.sidebar-iconic .menu-link .badge {
    position: absolute;
    top: 8px;
    right: 4px;
  }
  .sidebar-expand-md.sidebar-iconic .sidebar-header {
    -webkit-box-pack: center;
            justify-content: center;
  }
  .sidebar-expand-md.sidebar-iconic .sidebar-profile {
    padding: 20px;
  }
  .sidebar-expand-md.sidebar-iconic .sidebar-profile .avatar {
    width: 56px;
    height: 56px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm {
    width: 80px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm ~ .topbar,
  .sidebar-expand-md.sidebar-iconic.sidebar-sm ~ main {
    margin-left: 80px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm .menu-link {
    padding-top: 8px;
    padding-bottom: 4px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm .menu-link .title {
    line-height: 29px;
    font-size: 12px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm .menu-link .icon {
    font-size: 16px;
    line-height: 29px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm .menu-link .badge {
    top: 4px;
    right: 2px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-sm .sidebar-profile .avatar {
    width: 40px;
    height: 40px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-lg {
    width: 120px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-lg ~ .topbar,
  .sidebar-expand-md.sidebar-iconic.sidebar-lg ~ main {
    margin-left: 120px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-lg .menu-link {
    padding-top: 16px;
    padding-bottom: 12px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-lg .menu-link .icon {
    font-size: 24px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-lg .menu-link .badge {
    top: 12px;
    right: 8px;
  }
  .sidebar-expand-md.sidebar-iconic.sidebar-lg .sidebar-profile .avatar {
    width: 76px;
    height: 76px;
  }
}

@media (max-width: 991px) {
  .sidebar-expand-lg .hidden-expand-down {
    display: none;
  }
  .sidebar-expand-lg ~ .topbar .sidebar-toggler {
    display: inline-block;
  }
  .sidebar-expand-lg .sidebar-toggle-fold {
    display: none;
  }
  .sidebar-expand-lg.sidebar {
    -webkit-box-shadow: none;
            box-shadow: none;
    left: -260px;
  }
  .sidebar-expand-lg.sidebar ~ .topbar,
  .sidebar-expand-lg.sidebar ~ main {
    margin-left: 0;
  }
  .sidebar-expand-lg.sidebar-sm {
    left: -220px;
  }
  .sidebar-expand-lg.sidebar-lg {
    left: -300px;
  }
}

@media (min-width: 992px) {
  .sidebar-expand-lg .hidden-expand-up {
    display: none;
  }
  .sidebar-expand-lg ~ .topbar .sidebar-toggler {
    display: none;
  }
  .sidebar-expand-lg.sidebar-icon-only {
    width: 70px;
  }
  .sidebar-expand-lg.sidebar-icon-only ~ .topbar,
  .sidebar-expand-lg.sidebar-icon-only ~ main {
    margin-left: 70px;
  }
  .sidebar-expand-lg.sidebar-icon-only .menu > .menu-item.active > .menu-link,
  .sidebar-expand-lg.sidebar-icon-only .menu > .menu-item:hover > .menu-link {
    background-color: transparent !important;
  }
  .sidebar-expand-lg.sidebar-icon-only .menu.menu-bordery > .menu-item > .menu-link::before {
    width: 3px;
  }
  .sidebar-expand-lg.sidebar-iconic {
    width: 100px;
    overflow: visible;
  }
  .sidebar-expand-lg.sidebar-iconic ~ .topbar,
  .sidebar-expand-lg.sidebar-iconic ~ main {
    margin-left: 100px;
  }
  .sidebar-expand-lg.sidebar-iconic .menu-link {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
    height: auto;
    padding-top: 12px;
    padding-bottom: 8px;
  }
  .sidebar-expand-lg.sidebar-iconic .menu-link .title {
    -webkit-box-flex: 0;
            flex-grow: 0;
    line-height: 36px;
    font-weight: 300;
    letter-spacing: 0;
  }
  .sidebar-expand-lg.sidebar-iconic .menu-link .icon {
    font-size: 20px;
    line-height: 36px;
  }
  .sidebar-expand-lg.sidebar-iconic .menu-link .badge {
    position: absolute;
    top: 8px;
    right: 4px;
  }
  .sidebar-expand-lg.sidebar-iconic .sidebar-header {
    -webkit-box-pack: center;
            justify-content: center;
  }
  .sidebar-expand-lg.sidebar-iconic .sidebar-profile {
    padding: 20px;
  }
  .sidebar-expand-lg.sidebar-iconic .sidebar-profile .avatar {
    width: 56px;
    height: 56px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm {
    width: 80px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm ~ .topbar,
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm ~ main {
    margin-left: 80px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm .menu-link {
    padding-top: 8px;
    padding-bottom: 4px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm .menu-link .title {
    line-height: 29px;
    font-size: 12px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm .menu-link .icon {
    font-size: 16px;
    line-height: 29px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm .menu-link .badge {
    top: 4px;
    right: 2px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-sm .sidebar-profile .avatar {
    width: 40px;
    height: 40px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg {
    width: 120px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg ~ .topbar,
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg ~ main {
    margin-left: 120px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg .menu-link {
    padding-top: 16px;
    padding-bottom: 12px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg .menu-link .icon {
    font-size: 24px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg .menu-link .badge {
    top: 12px;
    right: 8px;
  }
  .sidebar-expand-lg.sidebar-iconic.sidebar-lg .sidebar-profile .avatar {
    width: 76px;
    height: 76px;
  }
}

@media (max-width: 1199px) {
  .sidebar-expand-xl .hidden-expand-down {
    display: none;
  }
  .sidebar-expand-xl ~ .topbar .sidebar-toggler {
    display: inline-block;
  }
  .sidebar-expand-xl .sidebar-toggle-fold {
    display: none;
  }
  .sidebar-expand-xl.sidebar {
    -webkit-box-shadow: none;
            box-shadow: none;
    left: -260px;
  }
  .sidebar-expand-xl.sidebar ~ .topbar,
  .sidebar-expand-xl.sidebar ~ main {
    margin-left: 0;
  }
  .sidebar-expand-xl.sidebar-sm {
    left: -220px;
  }
  .sidebar-expand-xl.sidebar-lg {
    left: -300px;
  }
}

@media (min-width: 1200px) {
  .sidebar-expand-xl .hidden-expand-up {
    display: none;
  }
  .sidebar-expand-xl ~ .topbar .sidebar-toggler {
    display: none;
  }
  .sidebar-expand-xl.sidebar-icon-only {
    width: 70px;
  }
  .sidebar-expand-xl.sidebar-icon-only ~ .topbar,
  .sidebar-expand-xl.sidebar-icon-only ~ main {
    margin-left: 70px;
  }
  .sidebar-expand-xl.sidebar-icon-only .menu > .menu-item.active > .menu-link,
  .sidebar-expand-xl.sidebar-icon-only .menu > .menu-item:hover > .menu-link {
    background-color: transparent !important;
  }
  .sidebar-expand-xl.sidebar-icon-only .menu.menu-bordery > .menu-item > .menu-link::before {
    width: 3px;
  }
  .sidebar-expand-xl.sidebar-iconic {
    width: 100px;
    overflow: visible;
  }
  .sidebar-expand-xl.sidebar-iconic ~ .topbar,
  .sidebar-expand-xl.sidebar-iconic ~ main {
    margin-left: 100px;
  }
  .sidebar-expand-xl.sidebar-iconic .menu-link {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
    height: auto;
    padding-top: 12px;
    padding-bottom: 8px;
  }
  .sidebar-expand-xl.sidebar-iconic .menu-link .title {
    -webkit-box-flex: 0;
            flex-grow: 0;
    line-height: 36px;
    font-weight: 300;
    letter-spacing: 0;
  }
  .sidebar-expand-xl.sidebar-iconic .menu-link .icon {
    font-size: 20px;
    line-height: 36px;
  }
  .sidebar-expand-xl.sidebar-iconic .menu-link .badge {
    position: absolute;
    top: 8px;
    right: 4px;
  }
  .sidebar-expand-xl.sidebar-iconic .sidebar-header {
    -webkit-box-pack: center;
            justify-content: center;
  }
  .sidebar-expand-xl.sidebar-iconic .sidebar-profile {
    padding: 20px;
  }
  .sidebar-expand-xl.sidebar-iconic .sidebar-profile .avatar {
    width: 56px;
    height: 56px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm {
    width: 80px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm ~ .topbar,
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm ~ main {
    margin-left: 80px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm .menu-link {
    padding-top: 8px;
    padding-bottom: 4px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm .menu-link .title {
    line-height: 29px;
    font-size: 12px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm .menu-link .icon {
    font-size: 16px;
    line-height: 29px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm .menu-link .badge {
    top: 4px;
    right: 2px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-sm .sidebar-profile .avatar {
    width: 40px;
    height: 40px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg {
    width: 120px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg ~ .topbar,
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg ~ main {
    margin-left: 120px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg .menu-link {
    padding-top: 16px;
    padding-bottom: 12px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg .menu-link .icon {
    font-size: 24px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg .menu-link .badge {
    top: 12px;
    right: 8px;
  }
  .sidebar-expand-xl.sidebar-iconic.sidebar-lg .sidebar-profile .avatar {
    width: 76px;
    height: 76px;
  }
}

.sidebar .menu-link {
  color: #fff;
}

.sidebar .menu-item:hover > .menu-link {
  color: #fff;
}

.sidebar .menu-item:hover > .menu-link .title small {
  opacity: 0.7;
}

.sidebar .menu-item.active > .menu-link {
  color: #fff;
}

.sidebar .menu-item.active > .menu-link .title small {
  opacity: .8;
}

.sidebar .menu > .menu-item:hover > .menu-link {
  background-color: rgba(0, 0, 0, 0.1);
}

.sidebar-profile {
  color: #fff;
}

.sidebar-profile h3, .sidebar-profile h4, .sidebar-profile h5, .sidebar-profile h6 {
  color: #fff;
}

.sidebar-footer {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-iconic .menu-item + .menu-item {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #33cabb;
}

.sidebar.sidebar-dark {
  background-color: #242a33;
}

.sidebar.sidebar-dark .menu-submenu {
  background-color: #2b323b;
}

.sidebar.sidebar-light {
  background-color: #fff;
}

.sidebar.sidebar-light .sidebar-profile {
  color: #4d5259;
  border-bottom-color: rgba(77, 82, 89, 0.07);
}

.sidebar.sidebar-light .sidebar-profile h3, .sidebar.sidebar-light .sidebar-profile h4, .sidebar.sidebar-light .sidebar-profile h5, .sidebar.sidebar-light .sidebar-profile h6 {
  color: #313944;
}

.sidebar.sidebar-light .sidebar-footer {
  border-top-color: rgba(77, 82, 89, 0.07);
}

.sidebar.sidebar-light.sidebar-icons-boxed .menu .menu-link .icon {
  background-color: rgba(0, 0, 0, 0.035);
}

.sidebar.sidebar-light.sidebar-icons-boxed .menu .active > .menu-link .icon {
  background-color: rgba(255, 255, 255, 0.15);
}

.sidebar.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  color: #fff;
}

.sidebar.sidebar-light.sidebar-iconic .menu-item + .menu-item {
  border-top-color: rgba(77, 82, 89, 0.07);
}

.sidebar.sidebar-light .menu .menu-link {
  color: #7d858f;
  opacity: 1;
}

.sidebar.sidebar-light .menu .dot::after {
  border-color: #595f67;
}

.sidebar.sidebar-light .menu .menu-link .title small {
  opacity: 0.7;
  color: #4d5259;
}

.sidebar.sidebar-light .menu > .menu-item:hover > .menu-link {
  color: #4d5259;
  background-color: #f9fafb;
}

.sidebar.sidebar-light .menu > .menu-item:hover > .menu-link .title small {
  opacity: 1;
}

.sidebar.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  color: #fff;
}

.sidebar.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link .title small,
.sidebar.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link .title small {
  color: #fff;
}

.sidebar.sidebar-light .menu-submenu {
  background-color: #f7f9fa;
}

.sidebar.sidebar-light .menu-submenu .menu-item:hover .menu-link,
.sidebar.sidebar-light .menu-submenu .menu-item.active .menu-link {
  color: #4d5259;
}

.sidebar.sidebar-light .menu-category {
  color: #000;
}

.sidebar.sidebar-light .menu-category::after {
  border-top-color: #4d5259;
}

.sidebar.sidebar-light .menu-divider {
  background-color: #000;
}

.sidebar .menu.menu-bordery > .menu-item > .menu-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 4px;
  -webkit-transition: .5s;
  transition: .5s;
}

.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  color: #fff;
}

.sidebar.sidebar-light .menu > .menu-item.active > .menu-link {
  background-color: #f9fafb;
  color: #4d5259;
}

.sidebar .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #33cabb;
}

.sidebar.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(51, 202, 187, 0.95);
}

.sidebar.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(51, 202, 187, 0.85);
}

.sidebar.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #33cabb;
}

.sidebar.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #33cabb;
}

.sidebar .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #33cabb;
}

.sidebar.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #33cabb !important;
}

.sidebar .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #33cabb;
  border-color: #33cabb;
}

.sidebar.sidebar-color-primary .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #33cabb;
}

.sidebar.sidebar-color-primary.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(51, 202, 187, 0.95);
}

.sidebar.sidebar-color-primary.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(51, 202, 187, 0.85);
}

.sidebar.sidebar-color-primary.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #33cabb;
}

.sidebar.sidebar-color-primary.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-primary.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #33cabb;
}

.sidebar.sidebar-color-primary .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-primary .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #33cabb;
}

.sidebar.sidebar-color-primary.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #33cabb !important;
}

.sidebar.sidebar-color-primary .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-primary .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #33cabb;
  border-color: #33cabb;
}

.sidebar.sidebar-color-secondary .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #e4e7ea;
}

.sidebar.sidebar-color-secondary.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(228, 231, 234, 0.95);
}

.sidebar.sidebar-color-secondary.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(228, 231, 234, 0.85);
}

.sidebar.sidebar-color-secondary.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #e4e7ea;
}

.sidebar.sidebar-color-secondary.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-secondary.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #e4e7ea;
}

.sidebar.sidebar-color-secondary .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-secondary .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #e4e7ea;
}

.sidebar.sidebar-color-secondary.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #e4e7ea !important;
}

.sidebar.sidebar-color-secondary .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-secondary .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #e4e7ea;
  border-color: #e4e7ea;
}

.sidebar.sidebar-color-success .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #15c377;
}

.sidebar.sidebar-color-success.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(21, 195, 119, 0.95);
}

.sidebar.sidebar-color-success.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(21, 195, 119, 0.85);
}

.sidebar.sidebar-color-success.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #15c377;
}

.sidebar.sidebar-color-success.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-success.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #15c377;
}

.sidebar.sidebar-color-success .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-success .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #15c377;
}

.sidebar.sidebar-color-success.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #15c377 !important;
}

.sidebar.sidebar-color-success .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-success .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #15c377;
  border-color: #15c377;
}

.sidebar.sidebar-color-info .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #48b0f7;
}

.sidebar.sidebar-color-info.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(72, 176, 247, 0.95);
}

.sidebar.sidebar-color-info.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(72, 176, 247, 0.85);
}

.sidebar.sidebar-color-info.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #48b0f7;
}

.sidebar.sidebar-color-info.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-info.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #48b0f7;
}

.sidebar.sidebar-color-info .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-info .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #48b0f7;
}

.sidebar.sidebar-color-info.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #48b0f7 !important;
}

.sidebar.sidebar-color-info .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-info .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #48b0f7;
  border-color: #48b0f7;
}

.sidebar.sidebar-color-warning .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #faa64b;
}

.sidebar.sidebar-color-warning.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(250, 166, 75, 0.95);
}

.sidebar.sidebar-color-warning.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(250, 166, 75, 0.85);
}

.sidebar.sidebar-color-warning.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #faa64b;
}

.sidebar.sidebar-color-warning.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-warning.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #faa64b;
}

.sidebar.sidebar-color-warning .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-warning .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #faa64b;
}

.sidebar.sidebar-color-warning.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #faa64b !important;
}

.sidebar.sidebar-color-warning .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-warning .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #faa64b;
  border-color: #faa64b;
}

.sidebar.sidebar-color-danger .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #f96868;
}

.sidebar.sidebar-color-danger.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(249, 104, 104, 0.95);
}

.sidebar.sidebar-color-danger.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(249, 104, 104, 0.85);
}

.sidebar.sidebar-color-danger.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #f96868;
}

.sidebar.sidebar-color-danger.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-danger.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #f96868;
}

.sidebar.sidebar-color-danger .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-danger .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #f96868;
}

.sidebar.sidebar-color-danger.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #f96868 !important;
}

.sidebar.sidebar-color-danger .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-danger .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #f96868;
  border-color: #f96868;
}

.sidebar.sidebar-color-pink .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #f96197;
}

.sidebar.sidebar-color-pink.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(249, 97, 151, 0.95);
}

.sidebar.sidebar-color-pink.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(249, 97, 151, 0.85);
}

.sidebar.sidebar-color-pink.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #f96197;
}

.sidebar.sidebar-color-pink.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-pink.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #f96197;
}

.sidebar.sidebar-color-pink .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-pink .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #f96197;
}

.sidebar.sidebar-color-pink.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #f96197 !important;
}

.sidebar.sidebar-color-pink .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-pink .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #f96197;
  border-color: #f96197;
}

.sidebar.sidebar-color-purple .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #926dde;
}

.sidebar.sidebar-color-purple.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(146, 109, 222, 0.95);
}

.sidebar.sidebar-color-purple.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(146, 109, 222, 0.85);
}

.sidebar.sidebar-color-purple.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #926dde;
}

.sidebar.sidebar-color-purple.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-purple.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #926dde;
}

.sidebar.sidebar-color-purple .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-purple .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #926dde;
}

.sidebar.sidebar-color-purple.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #926dde !important;
}

.sidebar.sidebar-color-purple .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-purple .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #926dde;
  border-color: #926dde;
}

.sidebar.sidebar-color-brown .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #8d6658;
}

.sidebar.sidebar-color-brown.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(141, 102, 88, 0.95);
}

.sidebar.sidebar-color-brown.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(141, 102, 88, 0.85);
}

.sidebar.sidebar-color-brown.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #8d6658;
}

.sidebar.sidebar-color-brown.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-brown.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #8d6658;
}

.sidebar.sidebar-color-brown .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-brown .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #8d6658;
}

.sidebar.sidebar-color-brown.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #8d6658 !important;
}

.sidebar.sidebar-color-brown .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-brown .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #8d6658;
  border-color: #8d6658;
}

.sidebar.sidebar-color-cyan .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #57c7d4;
}

.sidebar.sidebar-color-cyan.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(87, 199, 212, 0.95);
}

.sidebar.sidebar-color-cyan.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(87, 199, 212, 0.85);
}

.sidebar.sidebar-color-cyan.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #57c7d4;
}

.sidebar.sidebar-color-cyan.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-cyan.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #57c7d4;
}

.sidebar.sidebar-color-cyan .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-cyan .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #57c7d4;
}

.sidebar.sidebar-color-cyan.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #57c7d4 !important;
}

.sidebar.sidebar-color-cyan .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-cyan .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #57c7d4;
  border-color: #57c7d4;
}

.sidebar.sidebar-color-yellow .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #fcc525;
}

.sidebar.sidebar-color-yellow.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(252, 197, 37, 0.95);
}

.sidebar.sidebar-color-yellow.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(252, 197, 37, 0.85);
}

.sidebar.sidebar-color-yellow.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #fcc525;
}

.sidebar.sidebar-color-yellow.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-yellow.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #fcc525;
}

.sidebar.sidebar-color-yellow .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-yellow .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #fcc525;
}

.sidebar.sidebar-color-yellow.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #fcc525 !important;
}

.sidebar.sidebar-color-yellow .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-yellow .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #fcc525;
  border-color: #fcc525;
}

.sidebar.sidebar-color-gray .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #868e96;
}

.sidebar.sidebar-color-gray.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(134, 142, 150, 0.95);
}

.sidebar.sidebar-color-gray.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(134, 142, 150, 0.85);
}

.sidebar.sidebar-color-gray.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #868e96;
}

.sidebar.sidebar-color-gray.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-gray.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #868e96;
}

.sidebar.sidebar-color-gray .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-gray .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #868e96;
}

.sidebar.sidebar-color-gray.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #868e96 !important;
}

.sidebar.sidebar-color-gray .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-gray .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #868e96;
  border-color: #868e96;
}

.sidebar.sidebar-color-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: #465161;
}

.sidebar.sidebar-color-dark.sidebar-dark .menu:not(.menu-bordery) > .menu-item.active > .menu-link {
  background-color: rgba(70, 81, 97, 0.95);
}

.sidebar.sidebar-color-dark.sidebar-dark.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: rgba(70, 81, 97, 0.85);
}

.sidebar.sidebar-color-dark.sidebar-light.sidebar-icons-boxed .menu-submenu .active > .menu-link .icon {
  background-color: #465161;
}

.sidebar.sidebar-color-dark.sidebar-light .menu:not(.menu-bordery) > .menu-item.active > .menu-link,
.sidebar.sidebar-color-dark.sidebar-light .menu:not(.menu-bordery) > .menu-item.active:hover > .menu-link {
  background-color: #465161;
}

.sidebar.sidebar-color-dark .menu.menu-bordery > .menu-item.active > .menu-link::before,
.sidebar.sidebar-color-dark .menu.menu-bordery > .menu-item:hover > .menu-link::before {
  background-color: #465161;
}

.sidebar.sidebar-color-dark.sidebar-icons-boxed .menu.menu-bordery .active > .menu-link .icon {
  background-color: #465161 !important;
}

.sidebar.sidebar-color-dark .menu-submenu .menu-item.active > .menu-link .dot::after,
.sidebar.sidebar-color-dark .menu-submenu .menu-item:hover > .menu-link .dot::after {
  background-color: #465161;
  border-color: #465161;
}

.topbar {
  padding: 0 18px;
  height: 64px;
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  z-index: 995;
  -webkit-transition: margin-left 0.3s ease;
  transition: margin-left 0.3s ease;
  background-color: #fff;
  -webkit-box-shadow: 4px 0 5px rgba(0, 0, 0, 0.08);
          box-shadow: 4px 0 5px rgba(0, 0, 0, 0.08);
}

.topbar .container {
  padding-left: 0;
  padding-right: 0;
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
}

.topbar .container .topbar-left {
  margin-left: 0;
}

@media (max-width: 767px) {
  .topbar .container {
    width: 100%;
  }
}

.topbar.topbar-unfix {
  position: absolute;
}

.topbar .form-control:focus,
.topbar .form-control.focused {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.topbar.bg-transparent {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.topbar-btn {
  display: inline-block;
  font-size: 1rem;
  font-weight: 400;
  line-height: 64px;
  padding: 0 12px;
  text-align: center;
  color: rgba(77, 82, 89, 0.7);
  cursor: pointer;
  min-width: 48px;
  white-space: nowrap;
  border: none;
  border-top: 1px solid transparent;
  -webkit-transition: .2s linear;
  transition: .2s linear;
  position: relative;
  z-index: 991;
}

.topbar-btn:hover {
  color: #4d5259;
  border-top-color: #33cabb;
}

.topbar-btn .icon {
  vertical-align: baseline;
  margin-right: 6px;
  font-size: 0.75rem;
}

.topbar-btn .material-icons {
  -webkit-transform: translateY(6px);
          transform: translateY(6px);
  font-size: 26px;
}

.topbar-btn strong {
  font-weight: 500;
}

.topbar-btn img {
  max-height: 64px;
}

.topbar-brand {
  flex-shrink: 0;
  margin-left: 15px;
}

.topbar .dropdown-menu,
.topbar .dropdown-grid {
  border: none;
  border-radius: 0;
  margin-top: -1px;
  top: 100%;
  border: 1px solid rgba(235, 235, 235, 0.4);
  border-top: 0;
  line-height: 1.625rem;
  overflow: hidden;
}

.topbar .dropdown-menu:not(.dropdown-grid) {
  min-width: 180px;
  max-width: 360px;
}

.topbar .dropdown.show .topbar-btn {
  color: #4d5259;
  border-top-color: #33cabb;
}

.topbar .media-list {
  width: 358px !important;
}

.topbar .lookup-circle {
  padding-left: 12px;
  padding-right: 12px;
}

.topbar .lookup-circle::before {
  left: 12px;
}

.topbar .lookup-circle.lookup-right::before {
  left: auto;
  right: 12px;
}

.topbar .topbar-lookup-text {
  display: inline-block;
  padding-left: 12px;
  padding-right: 12px;
  color: rgba(77, 82, 89, 0.4);
  cursor: text;
  -webkit-transition: .7s;
  transition: .7s;
}

.topbar .topbar-lookup-text:hover {
  color: rgba(77, 82, 89, 0.7);
}

.topbar .form-control {
  line-height: 24px;
}

.topbar p {
  margin-bottom: 0;
}

.topbar-divider,
.topbar-divider-full {
  border-left: 1px solid rgba(77, 82, 89, 0.07);
  height: 20px;
  -ms-grid-row-align: center;
      align-self: center;
  margin: 0 12px;
}

.topbar-divider-full {
  height: 64px;
}

.topbar-left {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
}

.topbar-title {
  margin-bottom: 0;
  margin-right: 12px;
  font-size: 1.25rem;
  font-weight: 500;
  -ms-grid-row-align: center;
      align-self: center;
}

.sidebar-toggler,
.topbar-menu-toggler {
  font-size: 21px;
  background: 0 0;
  outline: none !important;
}

.sidebar-toggler i,
.topbar-menu-toggler i {
  font-style: normal;
  display: inline-block;
  -webkit-transform: translateY(-2px);
          transform: translateY(-2px);
}

.topbar-right {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
}

.topbar-btns {
  list-style: none;
  padding-left: 0;
  margin-bottom: 0;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
}

.topbar-btns .dropdown {
  flex-shrink: 0;
}

.topbar-btns .topbar-btn {
  font-size: 1.125rem;
  min-width: 48px;
  color: rgba(77, 82, 89, 0.4);
}

.topbar-btns .topbar-btn.has-new {
  color: #4d5259;
}

.topbar-btns .topbar-btn.has-new i {
  position: relative;
}

.topbar-btns .topbar-btn.has-new i::after {
  content: '';
  position: absolute;
  top: -3px;
  right: -3px;
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 100%;
  border: 2px solid #fff;
  background-color: #f96868;
}

.topbar-btns .topbar-btn .avatar {
  margin-top: -6px;
}

.topbar-btns .media-list-divided + .dropdown-footer {
  border-top: 0;
}

.topbar-search::before {
  display: none;
}

.topbar-search input {
  border: none;
  border-radius: 0;
  padding-left: 12px;
  padding-right: 12px;
  width: 100%;
  z-index: 2;
}

.topbar-search .lookup-placeholder {
  position: absolute;
  top: 20px;
  left: 0;
  padding: 0 12px;
  white-space: nowrap;
  opacity: .6;
  z-index: 1;
  -webkit-transition: .5s;
  transition: .5s;
}

.topbar-search .lookup-placeholder span {
  -webkit-transition: .5s linear;
  transition: .5s linear;
}

.topbar-search .lookup-placeholder i {
  margin-top: 2px;
}

.topbar-search:hover .lookup-placeholder {
  opacity: .8;
}

.topbar-search .form-control {
  height: 64px;
  line-height: 64px;
  background-color: transparent;
  padding-left: 38px;
}

.topbar-search .lookup-placeholder .ti-search {
  display: inline-block;
  vertical-align: middle;
  margin-bottom: 4px;
  font-size: 16px;
  margin-right: 6px;
}

.topbar-search .tt-menu {
  margin-top: 0;
  border-top: 0;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.topbar-search .tt-dropdown-menu {
  max-height: 320px;
  overflow-y: auto;
}

.topbar-inverse .topbar-search {
  color: #fff;
}

@media (max-width: 767px) {
  .topbar-title {
    margin-left: 0;
    font-size: 1.375rem;
    font-weight: 400;
  }
}

.topbar .menu {
  margin-left: 12px;
  margin-bottom: 0;
  white-space: nowrap;
}

.topbar .menu-item {
  position: relative;
  display: inline-block;
}

.topbar .menu-item:hover > .menu-link,
.topbar .menu-item.active > .menu-link {
  color: #4d5259;
  border-top-color: #33cabb;
}

.topbar .menu-link {
  height: 64px;
  color: rgba(77, 82, 89, 0.7);
  border-top: 2px solid transparent;
}

.topbar .menu-link .icon,
.topbar .menu-link .dot {
  flex-basis: 16px;
}

.topbar .menu-link .title small {
  opacity: 1;
}

.topbar .menu-link .arrow::before {
  content: "\e64b";
}

.topbar .menu-submenu {
  position: absolute;
  top: 100%;
  left: 0;
  padding: 0;
  min-width: 200px;
  background-color: #fff;
  -webkit-box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.topbar .menu-submenu .menu-item {
  display: block;
}

.topbar .menu-submenu .menu-item:hover .menu-sub-submenu {
  display: block;
}

.topbar .menu-submenu .menu-item:hover > .menu-link {
  color: #4d5259;
  background-color: #f9fafb;
}

.topbar .menu-submenu .menu-item.active > .menu-link {
  color: #4d5259;
  background-color: #f9fafb;
}

.topbar .menu-submenu .menu-item .icon,
.topbar .menu-submenu .menu-item .dot {
  margin-left: 6px;
}

.topbar .menu-submenu .menu-link {
  height: 32px;
  border-top: none;
  opacity: 1;
}

.topbar .menu-submenu .menu-link .arrow::before {
  content: "\e649";
}

.topbar .menu-submenu .dot::after {
  border-color: #4d5259;
}

.topbar .menu-sub-submenu {
  position: absolute;
  top: -4px;
  left: 100%;
  display: none;
  background-color: #fff;
  min-width: 200px;
  padding-left: 0;
  -webkit-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.06);
          box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.06);
}

.topbar .menu-sub-submenu .menu-item {
  padding-left: 0;
  opacity: 1;
}

.topbar .menu-category,
.topbar .menu-divider {
  display: none;
}

.topbar-expand .hidden-expand-up {
  display: none;
}

.topbar-expand .topbar-menu-toggler {
  display: none;
}

.topbar-expand.topbar .menu-item:hover .menu-submenu {
  display: block;
}

.topbar-expand.topbar .menu-submenu .menu-item {
  margin: 4px;
}

@media (max-width: 575px) {
  .topbar-expand-sm .hidden-expand-down {
    display: none;
  }
  .topbar-expand-sm .topbar-menu-toggler {
    display: inline-block;
  }
  .topbar-expand-sm.topbar .topbar-navigation {
    background-color: #fff;
  }
  .topbar-expand-sm.topbar .menu {
    margin-left: 0;
    width: 260px;
  }
  .topbar-expand-sm.topbar .menu-category {
    display: block;
    color: #4d5259;
  }
  .topbar-expand-sm.topbar .menu-category::after {
    border-top-color: #4d5259;
  }
  .topbar-expand-sm.topbar .menu-divider {
    display: block;
    background-color: #4d5259;
  }
  .topbar-expand-sm.topbar .menu-item {
    display: block;
  }
  .topbar-expand-sm.topbar .menu-item.open .menu-submenu {
    display: block !important;
  }
  .topbar-expand-sm.topbar .menu-item:hover > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-sm.topbar .menu-item.active > .menu-link {
    color: #fff;
    background-color: #33cabb;
  }
  .topbar-expand-sm.topbar .menu-link {
    border-top: none;
    color: #8b95a5;
  }
  .topbar-expand-sm.topbar .menu-link .arrow::before {
    content: "\e649";
  }
  .topbar-expand-sm.topbar .open .arrow::before {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  .topbar-expand-sm.topbar .menu-submenu {
    position: static;
    display: none;
    width: 100%;
    -webkit-box-shadow: none;
            box-shadow: none;
    padding-top: 1rem;
    padding-bottom: 1rem;
    background-color: #f7f9fa;
  }
  .topbar-expand-sm.topbar .menu-submenu .menu-item:hover > .menu-link,
  .topbar-expand-sm.topbar .menu-submenu .menu-item.active > .menu-link {
    color: #4d5259;
    background-color: transparent;
  }
  .topbar-expand-sm.topbar .menu-submenu .arrow::before {
    display: none;
  }
  .topbar-expand-sm.topbar .menu-sub-submenu {
    position: static;
    display: block;
    background-color: transparent;
    padding-left: 20px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .topbar-expand-sm.topbar.topbar-secondary .menu-item.active > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-sm .topbar-navigation {
    position: fixed;
    top: 0;
    left: -260px;
    bottom: 0;
    width: 260px;
    overflow: hidden;
    z-index: 999;
    -webkit-transition: left 0.3s ease;
    transition: left 0.3s ease;
  }
  .topbar-expand-sm.topbar-inverse .menu > .menu-item > .menu-link,
  .topbar-expand-sm.topbar-inverse .menu > .menu-item > .menu-link .icon::before {
    color: #4d5259;
  }
  .topbar-expand-sm.topbar-inverse .menu > .menu-item.active > .menu-link,
  .topbar-expand-sm.topbar-inverse .menu > .menu-item.active > .menu-link .icon::before {
    color: #fff;
  }
}

@media (min-width: 576px) {
  .topbar-expand-sm .hidden-expand-up {
    display: none;
  }
  .topbar-expand-sm .topbar-menu-toggler {
    display: none;
  }
  .topbar-expand-sm.topbar .menu-item:hover .menu-submenu {
    display: block;
  }
  .topbar-expand-sm.topbar .menu-submenu .menu-item {
    margin: 4px;
  }
}

@media (max-width: 767px) {
  .topbar-expand-md .hidden-expand-down {
    display: none;
  }
  .topbar-expand-md .topbar-menu-toggler {
    display: inline-block;
  }
  .topbar-expand-md.topbar .topbar-navigation {
    background-color: #fff;
  }
  .topbar-expand-md.topbar .menu {
    margin-left: 0;
    width: 260px;
  }
  .topbar-expand-md.topbar .menu-category {
    display: block;
    color: #4d5259;
  }
  .topbar-expand-md.topbar .menu-category::after {
    border-top-color: #4d5259;
  }
  .topbar-expand-md.topbar .menu-divider {
    display: block;
    background-color: #4d5259;
  }
  .topbar-expand-md.topbar .menu-item {
    display: block;
  }
  .topbar-expand-md.topbar .menu-item.open .menu-submenu {
    display: block !important;
  }
  .topbar-expand-md.topbar .menu-item:hover > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-md.topbar .menu-item.active > .menu-link {
    color: #fff;
    background-color: #33cabb;
  }
  .topbar-expand-md.topbar .menu-link {
    border-top: none;
    color: #8b95a5;
  }
  .topbar-expand-md.topbar .menu-link .arrow::before {
    content: "\e649";
  }
  .topbar-expand-md.topbar .open .arrow::before {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  .topbar-expand-md.topbar .menu-submenu {
    position: static;
    display: none;
    width: 100%;
    -webkit-box-shadow: none;
            box-shadow: none;
    padding-top: 1rem;
    padding-bottom: 1rem;
    background-color: #f7f9fa;
  }
  .topbar-expand-md.topbar .menu-submenu .menu-item:hover > .menu-link,
  .topbar-expand-md.topbar .menu-submenu .menu-item.active > .menu-link {
    color: #4d5259;
    background-color: transparent;
  }
  .topbar-expand-md.topbar .menu-submenu .arrow::before {
    display: none;
  }
  .topbar-expand-md.topbar .menu-sub-submenu {
    position: static;
    display: block;
    background-color: transparent;
    padding-left: 20px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .topbar-expand-md.topbar.topbar-secondary .menu-item.active > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-md .topbar-navigation {
    position: fixed;
    top: 0;
    left: -260px;
    bottom: 0;
    width: 260px;
    overflow: hidden;
    z-index: 999;
    -webkit-transition: left 0.3s ease;
    transition: left 0.3s ease;
  }
  .topbar-expand-md.topbar-inverse .menu > .menu-item > .menu-link,
  .topbar-expand-md.topbar-inverse .menu > .menu-item > .menu-link .icon::before {
    color: #4d5259;
  }
  .topbar-expand-md.topbar-inverse .menu > .menu-item.active > .menu-link,
  .topbar-expand-md.topbar-inverse .menu > .menu-item.active > .menu-link .icon::before {
    color: #fff;
  }
}

@media (min-width: 768px) {
  .topbar-expand-md .hidden-expand-up {
    display: none;
  }
  .topbar-expand-md .topbar-menu-toggler {
    display: none;
  }
  .topbar-expand-md.topbar .menu-item:hover .menu-submenu {
    display: block;
  }
  .topbar-expand-md.topbar .menu-submenu .menu-item {
    margin: 4px;
  }
}

@media (max-width: 991px) {
  .topbar-expand-lg .hidden-expand-down {
    display: none;
  }
  .topbar-expand-lg .topbar-menu-toggler {
    display: inline-block;
  }
  .topbar-expand-lg.topbar .topbar-navigation {
    background-color: #fff;
  }
  .topbar-expand-lg.topbar .menu {
    margin-left: 0;
    width: 260px;
  }
  .topbar-expand-lg.topbar .menu-category {
    display: block;
    color: #4d5259;
  }
  .topbar-expand-lg.topbar .menu-category::after {
    border-top-color: #4d5259;
  }
  .topbar-expand-lg.topbar .menu-divider {
    display: block;
    background-color: #4d5259;
  }
  .topbar-expand-lg.topbar .menu-item {
    display: block;
  }
  .topbar-expand-lg.topbar .menu-item.open .menu-submenu {
    display: block !important;
  }
  .topbar-expand-lg.topbar .menu-item:hover > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-lg.topbar .menu-item.active > .menu-link {
    color: #fff;
    background-color: #33cabb;
  }
  .topbar-expand-lg.topbar .menu-link {
    border-top: none;
    color: #8b95a5;
  }
  .topbar-expand-lg.topbar .menu-link .arrow::before {
    content: "\e649";
  }
  .topbar-expand-lg.topbar .open .arrow::before {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  .topbar-expand-lg.topbar .menu-submenu {
    position: static;
    display: none;
    width: 100%;
    -webkit-box-shadow: none;
            box-shadow: none;
    padding-top: 1rem;
    padding-bottom: 1rem;
    background-color: #f7f9fa;
  }
  .topbar-expand-lg.topbar .menu-submenu .menu-item:hover > .menu-link,
  .topbar-expand-lg.topbar .menu-submenu .menu-item.active > .menu-link {
    color: #4d5259;
    background-color: transparent;
  }
  .topbar-expand-lg.topbar .menu-submenu .arrow::before {
    display: none;
  }
  .topbar-expand-lg.topbar .menu-sub-submenu {
    position: static;
    display: block;
    background-color: transparent;
    padding-left: 20px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .topbar-expand-lg.topbar.topbar-secondary .menu-item.active > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-lg .topbar-navigation {
    position: fixed;
    top: 0;
    left: -260px;
    bottom: 0;
    width: 260px;
    overflow: hidden;
    z-index: 999;
    -webkit-transition: left 0.3s ease;
    transition: left 0.3s ease;
  }
  .topbar-expand-lg.topbar-inverse .menu > .menu-item > .menu-link,
  .topbar-expand-lg.topbar-inverse .menu > .menu-item > .menu-link .icon::before {
    color: #4d5259;
  }
  .topbar-expand-lg.topbar-inverse .menu > .menu-item.active > .menu-link,
  .topbar-expand-lg.topbar-inverse .menu > .menu-item.active > .menu-link .icon::before {
    color: #fff;
  }
}

@media (min-width: 992px) {
  .topbar-expand-lg .hidden-expand-up {
    display: none;
  }
  .topbar-expand-lg .topbar-menu-toggler {
    display: none;
  }
  .topbar-expand-lg.topbar .menu-item:hover .menu-submenu {
    display: block;
  }
  .topbar-expand-lg.topbar .menu-submenu .menu-item {
    margin: 4px;
  }
}

@media (max-width: 1199px) {
  .topbar-expand-xl .hidden-expand-down {
    display: none;
  }
  .topbar-expand-xl .topbar-menu-toggler {
    display: inline-block;
  }
  .topbar-expand-xl.topbar .topbar-navigation {
    background-color: #fff;
  }
  .topbar-expand-xl.topbar .menu {
    margin-left: 0;
    width: 260px;
  }
  .topbar-expand-xl.topbar .menu-category {
    display: block;
    color: #4d5259;
  }
  .topbar-expand-xl.topbar .menu-category::after {
    border-top-color: #4d5259;
  }
  .topbar-expand-xl.topbar .menu-divider {
    display: block;
    background-color: #4d5259;
  }
  .topbar-expand-xl.topbar .menu-item {
    display: block;
  }
  .topbar-expand-xl.topbar .menu-item.open .menu-submenu {
    display: block !important;
  }
  .topbar-expand-xl.topbar .menu-item:hover > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-xl.topbar .menu-item.active > .menu-link {
    color: #fff;
    background-color: #33cabb;
  }
  .topbar-expand-xl.topbar .menu-link {
    border-top: none;
    color: #8b95a5;
  }
  .topbar-expand-xl.topbar .menu-link .arrow::before {
    content: "\e649";
  }
  .topbar-expand-xl.topbar .open .arrow::before {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  .topbar-expand-xl.topbar .menu-submenu {
    position: static;
    display: none;
    width: 100%;
    -webkit-box-shadow: none;
            box-shadow: none;
    padding-top: 1rem;
    padding-bottom: 1rem;
    background-color: #f7f9fa;
  }
  .topbar-expand-xl.topbar .menu-submenu .menu-item:hover > .menu-link,
  .topbar-expand-xl.topbar .menu-submenu .menu-item.active > .menu-link {
    color: #4d5259;
    background-color: transparent;
  }
  .topbar-expand-xl.topbar .menu-submenu .arrow::before {
    display: none;
  }
  .topbar-expand-xl.topbar .menu-sub-submenu {
    position: static;
    display: block;
    background-color: transparent;
    padding-left: 20px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .topbar-expand-xl.topbar.topbar-secondary .menu-item.active > .menu-link {
    color: #4d5259;
  }
  .topbar-expand-xl .topbar-navigation {
    position: fixed;
    top: 0;
    left: -260px;
    bottom: 0;
    width: 260px;
    overflow: hidden;
    z-index: 999;
    -webkit-transition: left 0.3s ease;
    transition: left 0.3s ease;
  }
  .topbar-expand-xl.topbar-inverse .menu > .menu-item > .menu-link,
  .topbar-expand-xl.topbar-inverse .menu > .menu-item > .menu-link .icon::before {
    color: #4d5259;
  }
  .topbar-expand-xl.topbar-inverse .menu > .menu-item.active > .menu-link,
  .topbar-expand-xl.topbar-inverse .menu > .menu-item.active > .menu-link .icon::before {
    color: #fff;
  }
}

@media (min-width: 1200px) {
  .topbar-expand-xl .hidden-expand-up {
    display: none;
  }
  .topbar-expand-xl .topbar-menu-toggler {
    display: none;
  }
  .topbar-expand-xl.topbar .menu-item:hover .menu-submenu {
    display: block;
  }
  .topbar-expand-xl.topbar .menu-submenu .menu-item {
    margin: 4px;
  }
}

.topbar-menu-open {
  overflow: hidden;
}

.topbar-menu-open .topbar-navigation {
  left: 0;
  -webkit-box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1);
          box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.1);
}

.topbar-secondary {
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  -webkit-box-shadow: none;
          box-shadow: none;
  height: 81px;
}

.topbar-secondary .topbar-btn {
  line-height: 80px;
  border-top: none;
}

.topbar-secondary .topbar-btn.has-new i::after {
  top: -2px;
  right: -2px;
  width: 8px;
  height: 8px;
  border: none;
}

.topbar-secondary .topbar-btn i {
  font-size: 20px;
}

.topbar-secondary .dropdown.show .topbar-btn,
.topbar-secondary .topbar-btn:hover {
  background-color: #f7fafc;
}

.topbar-secondary .dropdown-menu {
  border: none;
  -webkit-box-shadow: none;
          box-shadow: none;
  -webkit-transition: none;
  transition: none;
  margin-top: 0 !important;
}

.topbar-secondary .dropdown-menu::before, .topbar-secondary .dropdown-menu::after {
  display: none;
}

.topbar-secondary .dropdown:hover .topbar-btn {
  background-color: #f7fafc;
}

.topbar-secondary .dropdown-item {
  font-family: Roboto, sans-serif;
  font-size: 13px;
  text-transform: uppercase;
  height: 38px;
  line-height: 38px;
  padding-top: 0;
  padding-bottom: 0;
}

.topbar-secondary .dropdown-item:hover, .topbar-secondary .dropdown-item:focus {
  background-color: transparent;
}

.topbar-secondary .dropdown-divider {
  background-color: rgba(0, 0, 0, 0.05);
}

.topbar-secondary .menu-link {
  font-family: Roboto, sans-serif;
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 1px;
  height: 80px;
  border-top: none;
}

.topbar-secondary .menu-link .title {
  font-weight: 500;
  letter-spacing: 1.5px;
}

.topbar-secondary .menu-submenu .menu-link {
  height: 38px;
}

.topbar-secondary .dropdown-menu,
.topbar-secondary .menu > .menu-item:hover,
.topbar-secondary .menu > .menu-item.active,
.topbar-secondary .menu-submenu,
.topbar-secondary .menu-sub-submenu {
  background-color: #f7fafc;
}

.topbar-secondary .menu-item.active > .menu-link,
.topbar-secondary .menu-item:hover > .menu-link {
  color: #4d5259;
  background-color: transparent !important;
}

.topbar-secondary .menu-submenu,
.topbar-secondary .menu-sub-submenu {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.topbar-secondary .menu-submenu .title,
.topbar-secondary .menu-sub-submenu .title {
  letter-spacing: 1px;
}

.topbar-secondary .menu-sub-submenu {
  border-left: 1px solid rgba(0, 0, 0, 0.03);
}

.topbar-inverse {
  background-color: #3f4a59;
}

.topbar-inverse .topbar-title,
.topbar-inverse .sidebar-toggler,
.topbar-inverse .topbar-menu-toggler {
  color: white;
}

.topbar-inverse .topbar-btn {
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .topbar-btn:hover,
.topbar-inverse .dropdown.show .topbar-btn {
  color: white;
  border-top-color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .topbar-divider {
  border-left-color: rgba(255, 255, 255, 0.08);
}

.topbar-inverse .topbar-btns .topbar-btn.has-new {
  color: white;
}

.topbar-inverse .topbar-btns .topbar-btn.has-new i::after {
  border: none;
  top: -2px;
  right: -2px;
  width: 8px;
  height: 8px;
}

.topbar-inverse .lookup-circle::before {
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .lookup-circle input {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.topbar-inverse .lookup-circle input:focus {
  background-color: rgba(255, 255, 255, 0.15);
}

.topbar-inverse .lookup-circle input::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .lookup-circle input::-moz-placeholder {
  /* Firefox 19+ */
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .lookup-circle input:-ms-input-placeholder {
  /* IE 10+ */
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .lookup-circle input:-moz-placeholder {
  /* Firefox 18- */
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .menu > .menu-item > .menu-link {
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse .menu > .menu-item:hover > .menu-link,
.topbar-inverse .menu > .menu-item.active > .menu-link {
  color: white;
  border-top-color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse.topbar-secondary {
  border-bottom-color: rgba(255, 255, 255, 0.05);
}

.topbar-inverse.topbar-secondary .dropdown-menu,
.topbar-inverse.topbar-secondary .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary .menu > .menu-item.active,
.topbar-inverse.topbar-secondary .menu-submenu,
.topbar-inverse.topbar-secondary .menu-sub-submenu,
.topbar-inverse.topbar-secondary .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary .topbar-btn:hover {
  background-color: #3b4553;
}

.topbar-inverse.topbar-secondary .dropdown-item {
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse.topbar-secondary .dropdown-item:hover, .topbar-inverse.topbar-secondary .dropdown-item:focus {
  color: white;
  background-color: #394350;
}

.topbar-inverse.topbar-secondary .dropdown-divider {
  border-top-color: rgba(255, 255, 255, 0.07);
}

.topbar-inverse.topbar-secondary .menu-item:hover > .menu-link,
.topbar-inverse.topbar-secondary .menu-item.active > .menu-link {
  color: white;
  border-top-color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse.topbar-secondary .menu-link {
  color: rgba(255, 255, 255, 0.7);
}

.topbar-inverse.topbar-secondary.bg-primary .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-primary .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-primary .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-primary .menu-submenu,
.topbar-inverse.topbar-secondary.bg-primary .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-primary .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-primary .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-primary .topbar-btn:hover {
  background-color: #31c2b3;
}

.topbar-inverse.topbar-secondary.bg-primary .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-primary .dropdown-item:focus {
  background-color: #30beb0;
}

.topbar-inverse.topbar-secondary.bg-secondary .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-secondary .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-secondary .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-secondary .menu-submenu,
.topbar-inverse.topbar-secondary.bg-secondary .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-secondary .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-secondary .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-secondary .topbar-btn:hover {
  background-color: #dee2e6;
}

.topbar-inverse.topbar-secondary.bg-secondary .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-secondary .dropdown-item:focus {
  background-color: #dbdfe3;
}

.topbar-inverse.topbar-secondary.bg-success .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-success .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-success .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-success .menu-submenu,
.topbar-inverse.topbar-secondary.bg-success .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-success .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-success .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-success .topbar-btn:hover {
  background-color: #14ba71;
}

.topbar-inverse.topbar-secondary.bg-success .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-success .dropdown-item:focus {
  background-color: #14b56f;
}

.topbar-inverse.topbar-secondary.bg-info .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-info .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-info .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-info .menu-submenu,
.topbar-inverse.topbar-secondary.bg-info .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-info .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-info .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-info .topbar-btn:hover {
  background-color: #3eacf7;
}

.topbar-inverse.topbar-secondary.bg-info .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-info .dropdown-item:focus {
  background-color: #39aaf6;
}

.topbar-inverse.topbar-secondary.bg-warning .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-warning .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-warning .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-warning .menu-submenu,
.topbar-inverse.topbar-secondary.bg-warning .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-warning .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-warning .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-warning .topbar-btn:hover {
  background-color: #faa141;
}

.topbar-inverse.topbar-secondary.bg-warning .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-warning .dropdown-item:focus {
  background-color: #fa9f3c;
}

.topbar-inverse.topbar-secondary.bg-danger .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-danger .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-danger .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-danger .menu-submenu,
.topbar-inverse.topbar-secondary.bg-danger .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-danger .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-danger .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-danger .topbar-btn:hover {
  background-color: #f95e5e;
}

.topbar-inverse.topbar-secondary.bg-danger .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-danger .dropdown-item:focus {
  background-color: #f85959;
}

.topbar-inverse.topbar-secondary.bg-pink .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-pink .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-pink .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-pink .menu-submenu,
.topbar-inverse.topbar-secondary.bg-pink .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-pink .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-pink .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-pink .topbar-btn:hover {
  background-color: #f95791;
}

.topbar-inverse.topbar-secondary.bg-pink .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-pink .dropdown-item:focus {
  background-color: #f8528d;
}

.topbar-inverse.topbar-secondary.bg-purple .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-purple .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-purple .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-purple .menu-submenu,
.topbar-inverse.topbar-secondary.bg-purple .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-purple .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-purple .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-purple .topbar-btn:hover {
  background-color: #8c65dc;
}

.topbar-inverse.topbar-secondary.bg-purple .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-purple .dropdown-item:focus {
  background-color: #8961db;
}

.topbar-inverse.topbar-secondary.bg-brown .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-brown .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-brown .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-brown .menu-submenu,
.topbar-inverse.topbar-secondary.bg-brown .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-brown .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-brown .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-brown .topbar-btn:hover {
  background-color: #876154;
}

.topbar-inverse.topbar-secondary.bg-brown .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-brown .dropdown-item:focus {
  background-color: #845f52;
}

.topbar-inverse.topbar-secondary.bg-cyan .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-cyan .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-cyan .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-cyan .menu-submenu,
.topbar-inverse.topbar-secondary.bg-cyan .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-cyan .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-cyan .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-cyan .topbar-btn:hover {
  background-color: #4fc4d2;
}

.topbar-inverse.topbar-secondary.bg-cyan .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-cyan .dropdown-item:focus {
  background-color: #4bc3d1;
}

.topbar-inverse.topbar-secondary.bg-yellow .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-yellow .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-yellow .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-yellow .menu-submenu,
.topbar-inverse.topbar-secondary.bg-yellow .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-yellow .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-yellow .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-yellow .topbar-btn:hover {
  background-color: #fcc21b;
}

.topbar-inverse.topbar-secondary.bg-yellow .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-yellow .dropdown-item:focus {
  background-color: #fcc116;
}

.topbar-inverse.topbar-secondary.bg-gray .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-gray .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-gray .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-gray .menu-submenu,
.topbar-inverse.topbar-secondary.bg-gray .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-gray .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-gray .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-gray .topbar-btn:hover {
  background-color: #818991;
}

.topbar-inverse.topbar-secondary.bg-gray .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-gray .dropdown-item:focus {
  background-color: #7e868f;
}

.topbar-inverse.topbar-secondary.bg-dark .dropdown-menu,
.topbar-inverse.topbar-secondary.bg-dark .menu > .menu-item:hover,
.topbar-inverse.topbar-secondary.bg-dark .menu > .menu-item.active,
.topbar-inverse.topbar-secondary.bg-dark .menu-submenu,
.topbar-inverse.topbar-secondary.bg-dark .menu-sub-submenu,
.topbar-inverse.topbar-secondary.bg-dark .dropdown.show .topbar-btn,
.topbar-inverse.topbar-secondary.bg-dark .dropdown:hover .topbar-btn,
.topbar-inverse.topbar-secondary.bg-dark .topbar-btn:hover {
  background-color: #424c5b;
}

.topbar-inverse.topbar-secondary.bg-dark .dropdown-item:hover, .topbar-inverse.topbar-secondary.bg-dark .dropdown-item:focus {
  background-color: #404a58;
}

.aside {
  background-color: #fff;
  -webkit-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.04);
          box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.04);
  position: fixed;
  top: 0;
  bottom: 0;
  width: 250px;
  padding-top: 64px;
  z-index: 991;
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

.aside ~ .header,
.aside ~ .main-content,
.aside ~ .site-footer {
  margin-left: 250px;
}

.aside > .nav-tabs {
  margin-bottom: 0;
}

.aside-open .aside {
  -webkit-box-shadow: 1px 1px 15px rgba(0, 0, 0, 0.07) !important;
          box-shadow: 1px 1px 15px rgba(0, 0, 0, 0.07) !important;
}

.aside-sm {
  width: 200px;
}

.aside-sm ~ .header,
.aside-sm ~ .main-content,
.aside-sm ~ .site-footer {
  margin-left: 200px;
}

.aside-lg {
  width: 300px;
}

.aside-lg ~ .header,
.aside-lg ~ .main-content,
.aside-lg ~ .site-footer {
  margin-left: 300px;
}

.aside-open .aside {
  left: 0 !important;
}

.aside-body {
  padding: 20px 0;
  height: 100%;
}

.aside-block {
  padding: 0 20px;
}

.aside-title {
  color: #8b95a5;
  font-size: 13px;
  letter-spacing: .5px;
  text-transform: uppercase;
  margin-bottom: 12px;
  opacity: .65;
}

.aside-toggler {
  display: none;
  background-color: #fff;
  height: 48px;
  width: 24px;
  border-top-right-radius: 48px;
  border-bottom-right-radius: 48px;
  border: 1px solid #ebebeb;
  border-left: none;
  position: absolute;
  top: 50%;
  right: -24px;
  margin-top: -24px;
  font-family: themify;
  padding-left: 2px;
  color: #616a78;
  cursor: pointer;
  -webkit-box-shadow: 4px 1px 9px rgba(0, 0, 0, 0.07);
          box-shadow: 4px 1px 9px rgba(0, 0, 0, 0.07);
}

.aside-toggler:focus {
  outline: none;
}

.aside-toggler::before {
  content: "\e649";
  -webkit-transition: .3s linear;
  transition: .3s linear;
}

.aside-open .aside-toggler::before {
  display: inline-block;
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}

@media (max-width: 767px) {
  .aside-toggler {
    height: 40px;
    width: 30px;
    border-top-right-radius: 40px;
    border-bottom-right-radius: 40px;
    right: -30px;
    margin-top: -20px;
    font-size: 0.875rem;
  }
}

.aside-collapse .aside-toggler {
  display: inline-block;
}

.aside-collapse.aside {
  left: -250px;
  -webkit-box-shadow: none;
          box-shadow: none;
}

.aside-collapse.aside ~ .header,
.aside-collapse.aside ~ .main-content,
.aside-collapse.aside ~ .site-footer {
  margin-left: 0;
}

.aside-collapse.aside-sm {
  left: -200px;
}

.aside-collapse.aside-lg {
  left: -300px;
}

@media (max-width: 575px) {
  .aside-expand-sm .aside-toggler {
    display: inline-block;
  }
  .aside-expand-sm.aside {
    left: -250px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .aside-expand-sm.aside ~ .header,
  .aside-expand-sm.aside ~ .main-content,
  .aside-expand-sm.aside ~ .site-footer {
    margin-left: 0;
  }
  .aside-expand-sm.aside-sm {
    left: -200px;
  }
  .aside-expand-sm.aside-lg {
    left: -300px;
  }
}

@media (max-width: 767px) {
  .aside-expand-md .aside-toggler {
    display: inline-block;
  }
  .aside-expand-md.aside {
    left: -250px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .aside-expand-md.aside ~ .header,
  .aside-expand-md.aside ~ .main-content,
  .aside-expand-md.aside ~ .site-footer {
    margin-left: 0;
  }
  .aside-expand-md.aside-sm {
    left: -200px;
  }
  .aside-expand-md.aside-lg {
    left: -300px;
  }
}

@media (max-width: 991px) {
  .aside-expand-lg .aside-toggler {
    display: inline-block;
  }
  .aside-expand-lg.aside {
    left: -250px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .aside-expand-lg.aside ~ .header,
  .aside-expand-lg.aside ~ .main-content,
  .aside-expand-lg.aside ~ .site-footer {
    margin-left: 0;
  }
  .aside-expand-lg.aside-sm {
    left: -200px;
  }
  .aside-expand-lg.aside-lg {
    left: -300px;
  }
}

@media (max-width: 1199px) {
  .aside-expand-xl .aside-toggler {
    display: inline-block;
  }
  .aside-expand-xl.aside {
    left: -250px;
    -webkit-box-shadow: none;
            box-shadow: none;
  }
  .aside-expand-xl.aside ~ .header,
  .aside-expand-xl.aside ~ .main-content,
  .aside-expand-xl.aside ~ .site-footer {
    margin-left: 0;
  }
  .aside-expand-xl.aside-sm {
    left: -200px;
  }
  .aside-expand-xl.aside-lg {
    left: -300px;
  }
}

.site-footer {
  background-color: #fff;
  -font-size: 12px;
  color: #616a78;
  border-top: 1px solid #f1f2f3;
}

.site-footer p {
  margin-bottom: 0;
  font-weight: 400;
}

.site-footer a {
  color: #616a78;
}

.site-footer a:hover {
  color: #33cabb;
}

.card {
  border: 0;
  border-radius: 0px;
  margin-bottom: 30px;
  -webkit-box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
          box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
  -webkit-transition: .5s;
  transition: .5s;
}

.card > .table-responsive .table,
.card > .table {
  margin-bottom: 0;
}

.card > .table-responsive .table tr td:first-child,
.card > .table-responsive .table tr th:first-child,
.card > .table tr td:first-child,
.card > .table tr th:first-child {
  padding-left: 20px;
}

.card > .table-responsive .table tr td:last-child,
.card > .table-responsive .table tr th:last-child,
.card > .table tr td:last-child,
.card > .table tr th:last-child {
  padding-right: 20px;
}

.card .card-hover-show {
  opacity: 0;
  -webkit-transition: .3s linear;
  transition: .3s linear;
}

.card:hover .card-hover-show {
  opacity: 1;
}

.card > .alert,
.card-content > .alert {
  border-radius: 0;
  margin-bottom: 0;
}

.card > .callout,
.card-content > .callout {
  margin-bottom: 0;
}

.card > .nav-tabs,
.card-content > .nav-tabs {
  margin-bottom: 0;
}

.card-title {
  font-family: Roboto, sans-serif;
  font-weight: 300;
  line-height: 1.5;
  margin-bottom: 0;
  padding: 15px 20px;
  border-bottom: 1px solid rgba(77, 82, 89, 0.07);
}

.card-title a {
  color: #313944;
}

.card-title strong,
.card-title b {
  font-weight: 400;
}

.h6.card-title, h6.card-title {
  font-size: 13.5px;
}

.h5.card-title, h5.card-title {
  font-size: 15px;
}

.h4.card-title, h4.card-title {
  font-size: 17px;
}

.h3.card-title, h3.card-title {
  font-size: 19px;
}

.h2.card-title, h2.card-title {
  font-size: 22px;
}

.h1.card-title, h1.card-title {
  font-size: 26px;
}

.card-title-bold {
  font-family: Roboto, sans-serif;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  font-weight: 500;
}

.card-header {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  padding: 15px 20px;
  background-color: transparent;
  border-bottom: 1px solid rgba(77, 82, 89, 0.07);
}

.card-header::after {
  display: none;
}

.card-header > * {
  margin-left: 8px;
  margin-right: 8px;
}

.card-header > *:first-child {
  margin-left: 0;
}

.card-header > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .card-header > * {
    margin-left: 4px;
    margin-right: 4px;
  }
  .card-header > *:first-child {
    margin-left: 0;
  }
  .card-header > *:last-child {
    margin-right: 0;
  }
}

.card-header.card-header-sm {
  padding-top: 8px;
  padding-bottom: 4px;
}

.card-header.card-header-sm .card-title {
  font-size: 0.9375rem;
}

.card-header .card-title {
  padding: 0;
  border: none;
}

.card-header progress,
.card-header input {
  margin-bottom: 0;
}

.card-header .pagination {
  margin-top: 0;
  margin-bottom: 0;
}

.card-header-actions {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  margin: -4px;
}

.card-header-actions > * {
  margin: 4px;
}

@media (max-width: 767px) {
  .card-header-actions {
    margin: -2px;
  }
  .card-header-actions > * {
    margin: 2px;
  }
}

.card-footer {
  background-color: #fcfdfe;
  border-top: 1px solid rgba(77, 82, 89, 0.07);
  color: #8b95a5;
  padding: 10px 20px;
}

.card-footer > *:last-child {
  margin-bottom: 0;
}

.card-controls {
  list-style-type: none;
  padding-left: 0;
  margin-bottom: 0;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
}

.card-controls li > a {
  font-family: themify;
  font-size: 12px;
  display: inline-block;
  padding: 0 4px;
  margin: 0 4px;
  color: #8b95a5;
  opacity: .8;
  -webkit-transition: 0.3s linear;
  transition: 0.3s linear;
}

.card-controls li > a:hover {
  color: #33cabb;
}

.card-controls li:first-child > a {
  margin-right: 0;
}

.card-controls .dropdown.show > a {
  color: #33cabb;
}

.card-controls [data-toggle="dropdown"],
.card-controls .card-btn-reload {
  font-size: 14px;
}

.card-btn-close::before {
  content: "\e646";
}

.card-btn-slide::before {
  content: "\e648";
}

.card-btn-maximize::before {
  content: "\e6e8";
}

.card-btn-fullscreen::before {
  content: "\e659";
}

.card-btn-prev::before {
  content: "\e64a";
}

.card-btn-next::before {
  content: "\e649";
}

.card-carousel .card-footer .carousel-indicators-outside {
  padding: 6px 0;
}

.card-body > *:last-child {
  margin-bottom: 0;
}

.card-img,
.card-img-top,
.card-img-bottom {
  border-radius: 0;
}

.card-loading {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.8);
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: center;
          justify-content: center;
  -webkit-box-align: center;
          align-items: center;
  opacity: 0;
  z-index: -1;
  -webkit-transition: .5s;
  transition: .5s;
}

.card-loading.reveal {
  opacity: 1;
  z-index: auto;
}

.card-inverse {
  color: #fff !important;
  background-color: #465161;
}

.card-inverse h1, .card-inverse h2, .card-inverse h3, .card-inverse h4, .card-inverse h5, .card-inverse h6,
.card-inverse .card-title,
.card-inverse small,
.card-inverse .card-controls li > a {
  color: #fff !important;
}

.card-inverse .card-title small {
  opacity: 0.8;
}

.card-inverse .card-btn-more::before,
.card-inverse .card-btn-more::after {
  border-color: #fff;
}

.card-inverse .card-header,
.card-inverse .card-footer,
.card-inverse .card-action {
  border-color: rgba(255, 255, 255, 0.15);
}

.card-primary {
  background-color: #33cabb;
}

.card-primary.card-bordered {
  border-color: #33cabb;
}

.card-secondary {
  background-color: #e4e7ea;
}

.card-secondary.card-bordered {
  border-color: #e4e7ea;
}

.card-success {
  background-color: #15c377;
}

.card-success.card-bordered {
  border-color: #15c377;
}

.card-info {
  background-color: #48b0f7;
}

.card-info.card-bordered {
  border-color: #48b0f7;
}

.card-warning {
  background-color: #faa64b;
}

.card-warning.card-bordered {
  border-color: #faa64b;
}

.card-danger {
  background-color: #f96868;
}

.card-danger.card-bordered {
  border-color: #f96868;
}

.card-pink {
  background-color: #f96197;
}

.card-pink.card-bordered {
  border-color: #f96197;
}

.card-purple {
  background-color: #926dde;
}

.card-purple.card-bordered {
  border-color: #926dde;
}

.card-brown {
  background-color: #8d6658;
}

.card-brown.card-bordered {
  border-color: #8d6658;
}

.card-cyan {
  background-color: #57c7d4;
}

.card-cyan.card-bordered {
  border-color: #57c7d4;
}

.card-yellow {
  background-color: #fcc525;
}

.card-yellow.card-bordered {
  border-color: #fcc525;
}

.card-gray {
  background-color: #868e96;
}

.card-gray.card-bordered {
  border-color: #868e96;
}

.card-dark {
  background-color: #465161;
}

.card-dark.card-bordered {
  border-color: #465161;
}

.card-outline-primary {
  background-color: #fff;
  border: 1px solid #33cabb;
}

.card-outline-secondary {
  background-color: #fff;
  border: 1px solid #e4e7ea;
}

.card-outline-success {
  background-color: #fff;
  border: 1px solid #15c377;
}

.card-outline-info {
  background-color: #fff;
  border: 1px solid #48b0f7;
}

.card-outline-warning {
  background-color: #fff;
  border: 1px solid #faa64b;
}

.card-outline-danger {
  background-color: #fff;
  border: 1px solid #f96868;
}

.card-outline-pink {
  background-color: #fff;
  border: 1px solid #f96197;
}

.card-outline-purple {
  background-color: #fff;
  border: 1px solid #926dde;
}

.card-outline-brown {
  background-color: #fff;
  border: 1px solid #8d6658;
}

.card-outline-cyan {
  background-color: #fff;
  border: 1px solid #57c7d4;
}

.card-outline-yellow {
  background-color: #fff;
  border: 1px solid #fcc525;
}

.card-outline-gray {
  background-color: #fff;
  border: 1px solid #868e96;
}

.card-outline-dark {
  background-color: #fff;
  border: 1px solid #465161;
}

.card-bordered {
  border: 1px solid #ebebeb;
}

.card-shadowed {
  -webkit-box-shadow: 0 0 25px rgba(0, 0, 0, 0.07);
          box-shadow: 0 0 25px rgba(0, 0, 0, 0.07);
}

.card-hover-shadow:hover {
  -webkit-box-shadow: 0 0 35px rgba(0, 0, 0, 0.07);
          box-shadow: 0 0 35px rgba(0, 0, 0, 0.07);
}

.card-transparent {
  -webkit-box-shadow: none;
          box-shadow: none;
  background-color: transparent;
}

.card-round {
  border-radius: 4px;
}

.card-maximize {
  position: fixed;
  top: 64px;
  right: 0;
  bottom: 0;
  margin-bottom: 0;
  z-index: 900;
  -webkit-transition: left 0.3s ease;
  transition: left 0.3s ease;
}

.card-maximize .card-btn-maximize {
  color: #33cabb;
}

.card-fullscreen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin-bottom: 0;
  z-index: 998;
}

.card-fullscreen .card-btn-fullscreen {
  color: #33cabb;
}

.card-slided-up .card-content {
  display: none;
}

.card-slided-up .card-btn-slide::before {
  content: "\e64b";
}

.card-columns {
  margin-bottom: 30px;
}

@media (min-width: 0) {
  .card-group .card + .card {
    border-left: 1px solid rgba(77, 82, 89, 0.07);
  }
}

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
  min-height: 64px !important;
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

.header {
  position: relative;
  display: -webkit-box;
  display: flex;
  flex-wrap: wrap;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  -webkit-box-align: center;
          align-items: center;
  background-color: #fff;
  margin-bottom: 30px;
  -webkit-background-size: cover;
          background-size: cover;
  background-position: center center;
  border-bottom: 1px solid #ebebeb;
}

.header > .container {
  margin-bottom: 0;
  padding-left: 30px;
  padding-right: 30px;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  flex-wrap: wrap;
  -webkit-box-align: center;
          align-items: center;
}

.header > .container .header-info,
.header > .container .header-action,
.header > .container .header-bar {
  padding-left: 0;
  padding-right: 0;
}

.header, .header.bg-img {
  z-index: 2;
}

.header-bar {
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
          align-items: center;
  width: 100%;
  padding: 12px 30px;
  background-color: #fff;
  border-bottom: 1px solid #f1f2f3;
}

.header-bar > * {
  margin-bottom: 0;
}

.header-title {
  -font-weight: 100;
  font-family: Roboto, sans-serif;
  color: #4d5259;
}

.header-title strong {
  font-weight: 300;
}

.header-title small {
  display: block;
  font-weight: 300;
  font-size: 1rem;
  line-height: 1.7;
  color: inherit;
  opacity: 0.7;
  padding-top: 16px;
}

.header-info {
  margin: 50px 0;
  padding: 0 30px;
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  flex-wrap: wrap;
  width: 100%;
}

@media (max-width: 767px) {
  .header-info {
    margin-top: 30px;
    margin-bottom: 30px;
  }
}

.header-info .left {
  -webkit-box-flex: 1;
          flex: 1 1 0%;
}

.header-info .breadcrumb-item::before {
  color: rgba(77, 82, 89, 0.7);
}

.header-info .breadcrumb-item.active {
  color: #4d5259;
}

.header-info .breadcrumb-item a {
  color: rgba(77, 82, 89, 0.7);
}

.header-info .breadcrumb-item a:hover {
  color: #4d5259;
}

.header-action {
  position: relative;
  padding: 0 30px;
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: justify;
          justify-content: space-between;
  flex-wrap: wrap;
  -webkit-box-align: center;
          align-items: center;
  width: 100%;
}

.header-action .nav {
  white-space: nowrap;
  flex-wrap: nowrap;
  margin-bottom: 0;
  border-bottom: none;
  /*
    @include media-down(sm) {
      overflow-x: auto;
    }
    */
}

.header-action .nav-link {
  display: inline-block;
  font-family: Roboto, sans-serif;
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 1px;
  text-transform: uppercase;
  padding: 0.75rem 1rem 1rem;
  border-bottom: 3px solid transparent;
  color: rgba(77, 82, 89, 0.7);
  background-color: transparent !important;
}

.header-action .nav-link.active, .header-action .nav-link:hover {
  color: #4d5259;
  border-bottom-color: #33cabb;
}

.header-action .nav-link + .nav-link {
  margin-left: 0;
}

.header-action .nav-link i {
  margin-right: 4px;
}

.header-action .nav-link small {
  color: inherit;
  vertical-align: bottom;
  opacity: 0.7;
}

@media (max-width: 767px) {
  .header-action .nav-link {
    padding: 0.5rem 0.75rem 0.75rem;
  }
}

.header-action .dropdown-menu {
  margin-top: 0;
}

.header-action .dropdown.show .nav-link {
  color: #4d5259;
  border-bottom-color: #33cabb;
}

.header-action .nav-item {
  margin-bottom: 0;
}

.header-action .nav-tabs .nav-link {
  -webkit-transition: .2s linear;
  transition: .2s linear;
}

.header-action .buttons {
  position: absolute;
  right: 30px;
  bottom: 0;
  -webkit-transform: translateY(50%);
          transform: translateY(50%);
}

.header-action .buttons .btn {
  margin-left: 4px;
}

.header-transparent {
  background-color: transparent;
  border-bottom: none;
}

.header-inverse {
  background-color: #3f4a59;
}

.header-inverse .header-info .header-title {
  color: white;
}

.header-inverse .header-info .breadcrumb-item::before {
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .header-info .breadcrumb-item.active {
  color: white;
}

.header-inverse .header-info .breadcrumb-item a {
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .header-info .breadcrumb-item a:hover {
  color: white;
}

.header-inverse .header-action .nav-link {
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .header-action .nav-link.active, .header-inverse .header-action .nav-link:hover {
  color: white;
  border-bottom-color: #33cabb;
}

.header-inverse .header-action .dropdown.show .nav-link {
  color: white;
}

.header-inverse[class*="bg-"] .header-action .nav-link.active, .header-inverse[class*="bg-"] .header-action .nav-link:hover {
  border-bottom-color: rgba(255, 255, 255, 0.7);
}

.header-inverse[class*="bg-"] .header-action .dropdown.show .nav-link {
  border-bottom-color: rgba(255, 255, 255, 0.7);
}

.header-inverse .lookup-circle::before {
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .lookup-circle input {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.header-inverse .lookup-circle input:focus {
  background-color: rgba(255, 255, 255, 0.15);
}

.header-inverse .lookup-circle input::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .lookup-circle input::-moz-placeholder {
  /* Firefox 19+ */
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .lookup-circle input:-ms-input-placeholder {
  /* IE 10+ */
  color: rgba(255, 255, 255, 0.7);
}

.header-inverse .lookup-circle input:-moz-placeholder {
  /* Firefox 18- */
  color: rgba(255, 255, 255, 0.7);
}

@media (max-width: 991px) {
  .page-info {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
            flex-direction: column;
    margin-top: 2rem;
  }
  .page-info > div {
    width: 100%;
    margin: 0.5rem 0;
  }
  .page-info .header-search::before {
    right: 24px;
  }
  .page-action nav a {
    padding-top: 0.5rem;
    padding-bottom: 1.25rem;
  }
}

.preloader {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #fff;
  display: -webkit-box;
  display: flex;
  -webkit-box-pack: center;
          justify-content: center;
  -webkit-box-align: center;
          align-items: center;
  z-index: 2000;
}

.price {
  font-family: Roboto, sans-serif;
  font-weight: bold;
  font-size: 75px;
  line-height: 75px;
  color: #000;
  padding: 10px 0;
}

.price span {
  display: block;
  font-size: 12px;
  font-weight: 400;
  line-height: 25px;
  color: #616a78;
}

.price sup {
  font-size: 28px;
  vertical-align: super;
}

.price-dollar {
  display: inline-block;
  font-size: 16px;
  vertical-align: top;
  margin-right: -10px;
  margin-top: 14px;
}

.price-interval {
  display: inline-block;
  font-size: 12px;
  vertical-align: text-bottom;
  margin-left: -10px;
  margin-bottom: 14px;
  color: #8b95a5;
}

.b-0 {
  border: 0px solid #ebebeb !important;
}

.bt-0 {
  border-top: 0px solid #ebebeb !important;
}

.br-0 {
  border-right: 0px solid #ebebeb !important;
}

.bb-0 {
  border-bottom: 0px solid #ebebeb !important;
}

.bl-0 {
  border-left: 0px solid #ebebeb !important;
}

.bx-0 {
  border-right: 0px solid #ebebeb !important;
  border-left: 0px solid #ebebeb !important;
}

.by-0 {
  border-top: 0px solid #ebebeb !important;
  border-bottom: 0px solid #ebebeb !important;
}

.b-1 {
  border: 1px solid #ebebeb !important;
}

.bt-1 {
  border-top: 1px solid #ebebeb !important;
}

.br-1 {
  border-right: 1px solid #ebebeb !important;
}

.bb-1 {
  border-bottom: 1px solid #ebebeb !important;
}

.bl-1 {
  border-left: 1px solid #ebebeb !important;
}

.bx-1 {
  border-right: 1px solid #ebebeb !important;
  border-left: 1px solid #ebebeb !important;
}

.by-1 {
  border-top: 1px solid #ebebeb !important;
  border-bottom: 1px solid #ebebeb !important;
}

.b-2 {
  border: 2px solid #ebebeb !important;
}

.bt-2 {
  border-top: 2px solid #ebebeb !important;
}

.br-2 {
  border-right: 2px solid #ebebeb !important;
}

.bb-2 {
  border-bottom: 2px solid #ebebeb !important;
}

.bl-2 {
  border-left: 2px solid #ebebeb !important;
}

.bx-2 {
  border-right: 2px solid #ebebeb !important;
  border-left: 2px solid #ebebeb !important;
}

.by-2 {
  border-top: 2px solid #ebebeb !important;
  border-bottom: 2px solid #ebebeb !important;
}

.b-3 {
  border: 3px solid #ebebeb !important;
}

.bt-3 {
  border-top: 3px solid #ebebeb !important;
}

.br-3 {
  border-right: 3px solid #ebebeb !important;
}

.bb-3 {
  border-bottom: 3px solid #ebebeb !important;
}

.bl-3 {
  border-left: 3px solid #ebebeb !important;
}

.bx-3 {
  border-right: 3px solid #ebebeb !important;
  border-left: 3px solid #ebebeb !important;
}

.by-3 {
  border-top: 3px solid #ebebeb !important;
  border-bottom: 3px solid #ebebeb !important;
}

.border {
  border: 1px solid #ebebeb !important;
}

.border-primary {
  border-color: #33cabb !important;
}

.border-secondary {
  border-color: #e4e7ea !important;
}

.border-success {
  border-color: #15c377 !important;
}

.border-info {
  border-color: #48b0f7 !important;
}

.border-warning {
  border-color: #faa64b !important;
}

.border-danger {
  border-color: #f96868 !important;
}

.border-pink {
  border-color: #f96197 !important;
}

.border-purple {
  border-color: #926dde !important;
}

.border-brown {
  border-color: #8d6658 !important;
}

.border-cyan {
  border-color: #57c7d4 !important;
}

.border-yellow {
  border-color: #fcc525 !important;
}

.border-gray {
  border-color: #868e96 !important;
}

.border-dark {
  border-color: #465161 !important;
}

.border-transparent {
  border-color: transparent !important;
}

.border-white {
  border-color: #fff !important;
}

.border-light {
  border-color: #f1f2f3 !important;
}

.border-fade {
  border-color: rgba(77, 82, 89, 0.07) !important;
}

.bg-primary {
  background-color: #33cabb !important;
  color: #fff;
}

.bg-secondary {
  background-color: #e4e7ea !important;
  color: #fff;
}

.bg-success {
  background-color: #15c377 !important;
  color: #fff;
}

.bg-info {
  background-color: #48b0f7 !important;
  color: #fff;
}

.bg-warning {
  background-color: #faa64b !important;
  color: #fff;
}

.bg-danger {
  background-color: #f96868 !important;
  color: #fff;
}

.bg-pink {
  background-color: #f96197 !important;
  color: #fff;
}

.bg-purple {
  background-color: #926dde !important;
  color: #fff;
}

.bg-brown {
  background-color: #8d6658 !important;
  color: #fff;
}

.bg-cyan {
  background-color: #57c7d4 !important;
  color: #fff;
}

.bg-yellow {
  background-color: #fcc525 !important;
  color: #fff;
}

.bg-gray {
  background-color: #868e96 !important;
  color: #fff;
}

.bg-dark {
  background-color: #465161 !important;
  color: #fff;
}

.bg-white {
  background-color: #fff !important;
}

.bg-inverse {
  background-color: #465161 !important;
  color: #fff;
}

.bg-transparent {
  background-color: transparent !important;
}

.bg-secondary {
  color: #4d5259;
}

.bg-lightest {
  background-color: #fcfdfe !important;
}

.bg-lighter {
  background-color: #f9fafb !important;
}

.bg-light {
  background-color: #f5f6f7 !important;
}

.bg-pale-primary {
  background-color: #dcfcfa !important;
}

.bg-pale-secondary {
  background-color: #f7fafc !important;
}

.bg-pale-success {
  background-color: #e3fcf2 !important;
}

.bg-pale-info {
  background-color: #e3f3fc !important;
}

.bg-pale-warning {
  background-color: #fcf0e3 !important;
}

.bg-pale-danger {
  background-color: #fce3e3 !important;
}

.bg-pale-pink {
  background-color: #fce3ec !important;
}

.bg-pale-purple {
  background-color: #ece3fc !important;
}

.bg-pale-brown {
  background-color: #eddcd5 !important;
}

.bg-pale-cyan {
  background-color: #e3fafc !important;
}

.bg-pale-yellow {
  background-color: #fcf8e3 !important;
}

.bg-pale-gray {
  background-color: #f2f2f2 !important;
}

.bg-pale-dark {
  background-color: #c8c8c8 !important;
}

.text-primary {
  color: #33cabb !important;
}

.text-secondary {
  color: #e4e7ea !important;
}

.text-success {
  color: #15c377 !important;
}

.text-info {
  color: #48b0f7 !important;
}

.text-warning {
  color: #faa64b !important;
}

.text-danger {
  color: #f96868 !important;
}

.text-pink {
  color: #f96197 !important;
}

.text-purple {
  color: #926dde !important;
}

.text-brown {
  color: #8d6658 !important;
}

.text-cyan {
  color: #57c7d4 !important;
}

.text-yellow {
  color: #fcc525 !important;
}

.text-gray {
  color: #868e96 !important;
}

.text-dark {
  color: #465161 !important;
}

.text-facebook {
  color: #3b5998 !important;
}

.text-google {
  color: #dd4b39 !important;
}

.text-twitter {
  color: #00aced !important;
}

.text-linkedin {
  color: #007bb6 !important;
}

.text-pinterest {
  color: #cb2027 !important;
}

.text-git {
  color: #666666 !important;
}

.text-tumblr {
  color: #32506d !important;
}

.text-vimeo {
  color: #aad450 !important;
}

.text-youtube {
  color: #bb0000 !important;
}

.text-flickr {
  color: #ff0084 !important;
}

.text-reddit {
  color: #ff4500 !important;
}

.text-dribbble {
  color: #ea4c89 !important;
}

.text-skype {
  color: #00aff0 !important;
}

.text-instagram {
  color: #517fa4 !important;
}

.text-lastfm {
  color: #c3000d !important;
}

.text-behance {
  color: #1769ff !important;
}

.text-rss {
  color: #f26522 !important;
}

.text-default {
  color: #4d5259 !important;
}

.text-muted {
  color: #868e96 !important;
}

.text-light {
  color: #616a78 !important;
}

.text-lighter {
  color: #a5b3c7 !important;
}

.text-fade {
  color: rgba(77, 82, 89, 0.7) !important;
}

.text-fader {
  color: rgba(77, 82, 89, 0.5) !important;
}

.text-fadest {
  color: rgba(77, 82, 89, 0.4) !important;
}

.text-transparent {
  color: transparent !important;
}

a.text-primary:hover, a.text-primary:focus {
  color: #33cabb !important;
}

a.text-secondary:hover, a.text-secondary:focus {
  color: #e4e7ea !important;
}

a.text-info:hover, a.text-info:focus {
  color: #48b0f7 !important;
}

a.text-success:hover, a.text-success:focus {
  color: #15c377 !important;
}

a.text-warning:hover, a.text-warning:focus {
  color: #faa64b !important;
}

a.text-danger:hover, a.text-danger:focus {
  color: #f96868 !important;
}

.hover-primary:hover, .hover-primary:focus {
  color: #33cabb !important;
}

.hover-secondary:hover, .hover-secondary:focus {
  color: #e4e7ea !important;
}

.hover-success:hover, .hover-success:focus {
  color: #15c377 !important;
}

.hover-info:hover, .hover-info:focus {
  color: #48b0f7 !important;
}

.hover-warning:hover, .hover-warning:focus {
  color: #faa64b !important;
}

.hover-danger:hover, .hover-danger:focus {
  color: #f96868 !important;
}

.hover-pink:hover, .hover-pink:focus {
  color: #f96197 !important;
}

.hover-purple:hover, .hover-purple:focus {
  color: #926dde !important;
}

.hover-brown:hover, .hover-brown:focus {
  color: #8d6658 !important;
}

.hover-cyan:hover, .hover-cyan:focus {
  color: #57c7d4 !important;
}

.hover-yellow:hover, .hover-yellow:focus {
  color: #fcc525 !important;
}

.hover-gray:hover, .hover-gray:focus {
  color: #868e96 !important;
}

.hover-dark:hover, .hover-dark:focus {
  color: #465161 !important;
}

.hover-facebook:hover, .hover-facebook:focus {
  color: #3b5998 !important;
}

.hover-google:hover, .hover-google:focus {
  color: #dd4b39 !important;
}

.hover-twitter:hover, .hover-twitter:focus {
  color: #00aced !important;
}

.hover-linkedin:hover, .hover-linkedin:focus {
  color: #007bb6 !important;
}

.hover-pinterest:hover, .hover-pinterest:focus {
  color: #cb2027 !important;
}

.hover-git:hover, .hover-git:focus {
  color: #666666 !important;
}

.hover-tumblr:hover, .hover-tumblr:focus {
  color: #32506d !important;
}

.hover-vimeo:hover, .hover-vimeo:focus {
  color: #aad450 !important;
}

.hover-youtube:hover, .hover-youtube:focus {
  color: #bb0000 !important;
}

.hover-flickr:hover, .hover-flickr:focus {
  color: #ff0084 !important;
}

.hover-reddit:hover, .hover-reddit:focus {
  color: #ff4500 !important;
}

.hover-dribbble:hover, .hover-dribbble:focus {
  color: #ea4c89 !important;
}

.hover-skype:hover, .hover-skype:focus {
  color: #00aff0 !important;
}

.hover-instagram:hover, .hover-instagram:focus {
  color: #517fa4 !important;
}

.hover-lastfm:hover, .hover-lastfm:focus {
  color: #c3000d !important;
}

.hover-behance:hover, .hover-behance:focus {
  color: #1769ff !important;
}

.hover-rss:hover, .hover-rss:focus {
  color: #f26522 !important;
}

.hover-white:hover, .hover-white:focus {
  color: #fff !important;
}

.hover-muted:hover, .hover-muted:focus {
  color: #868e96 !important;
}

.hover-light:hover, .hover-light:focus {
  color: #616a78 !important;
}

.hover-lighter:hover, .hover-lighter:focus {
  color: #a5b3c7 !important;
}

.hover-fade:hover, .hover-fade:focus {
  color: rgba(77, 82, 89, 0.4) !important;
}

.hover-transparent:hover, .hover-transparent:focus {
  color: transparent !important;
}

.active.active-primary {
  background-color: #dcfcfa !important;
}

.active.active-secondary {
  background-color: #f7fafc !important;
}

.active.active-success {
  background-color: #e3fcf2 !important;
}

.active.active-info {
  background-color: #e3f3fc !important;
}

.active.active-warning {
  background-color: #fcf0e3 !important;
}

.active.active-danger {
  background-color: #fce3e3 !important;
}

.active.active-pink {
  background-color: #fce3ec !important;
}

.active.active-purple {
  background-color: #ece3fc !important;
}

.active.active-brown {
  background-color: #eddcd5 !important;
}

.active.active-cyan {
  background-color: #e3fafc !important;
}

.active.active-yellow {
  background-color: #fcf8e3 !important;
}

.active.active-gray {
  background-color: #f2f2f2 !important;
}

.active.active-dark {
  background-color: #c8c8c8 !important;
}

.active.active-white {
  background-color: #fff !important;
}

.active.active-transparent {
  background-color: transparent !important;
}

.bg-img {
  position: relative;
  border-bottom: none;
  background-position: center;
  -webkit-background-size: cover;
          background-size: cover;
  background-repeat: no-repeat;
  z-index: 0;
}

.overlay {
  position: relative;
}

.overlay::before {
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: -1;
  background-color: rgba(0, 0, 0, 0.1);
}

.overlay-dark::before {
  background-color: rgba(0, 0, 0, 0.35);
}

.overlay-darker::before {
  background-color: rgba(0, 0, 0, 0.5);
}

.overlay-darkest::before {
  background-color: rgba(0, 0, 0, 0.7);
}

.overlay-light::before {
  background-color: rgba(255, 255, 255, 0.55);
}

.overlay-lighter::before {
  background-color: rgba(255, 255, 255, 0.7);
}

.overlay-lightest::before {
  background-color: rgba(255, 255, 255, 0.9);
}

.bg-fixed {
  background-attachment: fixed;
}

.bg-repeat {
  background-repeat: repeat;
  -webkit-background-size: auto auto;
          background-size: auto;
}

.bg-video {
  position: absolute;
  top: 50%;
  left: 50%;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  z-index: -100;
  -webkit-transform: translate(-50% -50%);
          transform: translate(-50% -50%);
  overflow: hidden;
}

.bg-img-left {
  background-position: left center;
}

.bg-img-right {
  background-position: right center;
}

[data-overlay],
[data-overlay-light] {
  position: relative;
}

[data-overlay]::before,
[data-overlay-light]::before {
  position: absolute;
  content: '';
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #191919;
  z-index: -1;
  border-radius: inherit;
}

[data-overlay-light]::before {
  background: #fff;
}

[data-overlay-primary]::before {
  background: #33cabb;
}

[data-overlay="1"]::before {
  opacity: 0.1;
}

[data-overlay="2"]::before {
  opacity: 0.2;
}

[data-overlay="3"]::before {
  opacity: 0.3;
}

[data-overlay="4"]::before {
  opacity: 0.4;
}

[data-overlay="5"]::before {
  opacity: 0.5;
}

[data-overlay="6"]::before {
  opacity: 0.6;
}

[data-overlay="7"]::before {
  opacity: 0.7;
}

[data-overlay="8"]::before {
  opacity: 0.8;
}

[data-overlay="9"]::before {
  opacity: 0.9;
}

[data-overlay-light="1"]::before {
  opacity: 0.1;
}

[data-overlay-light="2"]::before {
  opacity: 0.2;
}

[data-overlay-light="3"]::before {
  opacity: 0.3;
}

[data-overlay-light="4"]::before {
  opacity: 0.4;
}

[data-overlay-light="5"]::before {
  opacity: 0.5;
}

[data-overlay-light="6"]::before {
  opacity: 0.6;
}

[data-overlay-light="7"]::before {
  opacity: 0.7;
}

[data-overlay-light="8"]::before {
  opacity: 0.8;
}

[data-overlay-light="9"]::before {
  opacity: 0.9;
}

[data-scrim-top],
[data-scrim-bottom] {
  position: relative;
}

[data-scrim-top]::before,
[data-scrim-bottom]::before {
  position: absolute;
  content: '';
  top: 0;
  left: 0;
  right: 0;
  bottom: 20%;
  background: -webkit-gradient(linear, left top, left bottom, from(#191919), to(transparent));
  background: -webkit-linear-gradient(top, #191919 0%, transparent 100%);
  background: linear-gradient(to bottom, #191919 0%, transparent 100%);
  z-index: -1;
  border-radius: inherit;
}

[data-scrim-bottom]::before {
  top: 20%;
  bottom: 0;
  background: -webkit-gradient(linear, left top, left bottom, from(transparent), to(#191919));
  background: -webkit-linear-gradient(top, transparent 0%, #191919 100%);
  background: linear-gradient(to bottom, transparent 0%, #191919 100%);
}

[data-scrim-top="1"]::before,
[data-scrim-bottom="1"]::before {
  opacity: 0.1;
}

[data-scrim-top="2"]::before,
[data-scrim-bottom="2"]::before {
  opacity: 0.2;
}

[data-scrim-top="3"]::before,
[data-scrim-bottom="3"]::before {
  opacity: 0.3;
}

[data-scrim-top="4"]::before,
[data-scrim-bottom="4"]::before {
  opacity: 0.4;
}

[data-scrim-top="5"]::before,
[data-scrim-bottom="5"]::before {
  opacity: 0.5;
}

[data-scrim-top="6"]::before,
[data-scrim-bottom="6"]::before {
  opacity: 0.6;
}

[data-scrim-top="7"]::before,
[data-scrim-bottom="7"]::before {
  opacity: 0.7;
}

[data-scrim-top="8"]::before,
[data-scrim-bottom="8"]::before {
  opacity: 0.8;
}

[data-scrim-top="9"]::before,
[data-scrim-bottom="9"]::before {
  opacity: 0.9;
}

.bring-front {
  z-index: 1;
}

.transition-3s {
  -webkit-transition: .3s;
  transition: .3s;
}

.transition-5s {
  -webkit-transition: .5s;
  transition: .5s;
}

.overflow-hidden {
  overflow: hidden;
}

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

.rotate-45 {
  display: inline-block;
  -webkit-transform: rotate(45deg);
          transform: rotate(45deg);
}

.rotate-90 {
  display: inline-block;
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg);
}

.rotate-180 {
  display: inline-block;
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}

.opacity-0 {
  opacity: 0 !important;
}

.opacity-5 {
  opacity: 0.05 !important;
}

.opacity-10 {
  opacity: 0.1 !important;
}

.opacity-15 {
  opacity: 0.15 !important;
}

.opacity-20 {
  opacity: 0.2 !important;
}

.opacity-25 {
  opacity: 0.25 !important;
}

.opacity-30 {
  opacity: 0.3 !important;
}

.opacity-35 {
  opacity: 0.35 !important;
}

.opacity-40 {
  opacity: 0.4 !important;
}

.opacity-45 {
  opacity: 0.45 !important;
}

.opacity-50 {
  opacity: 0.5 !important;
}

.opacity-55 {
  opacity: 0.55 !important;
}

.opacity-60 {
  opacity: 0.6 !important;
}

.opacity-65 {
  opacity: 0.65 !important;
}

.opacity-70 {
  opacity: 0.7 !important;
}

.opacity-75 {
  opacity: 0.75 !important;
}

.opacity-80 {
  opacity: 0.8 !important;
}

.opacity-85 {
  opacity: 0.85 !important;
}

.opacity-90 {
  opacity: 0.9 !important;
}

.opacity-95 {
  opacity: 0.95 !important;
}

.opacity-100 {
  opacity: 1 !important;
}

.cursor-default {
  cursor: default !important;
}

.cursor-pointer {
  cursor: pointer;
}

.cursor-text {
  cursor: text;
}

.shadow-1 {
  -webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.06);
          box-shadow: 0 0 5px rgba(0, 0, 0, 0.06);
}

.shadow-2 {
  -webkit-box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
          box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
}

.shadow-3 {
  -webkit-box-shadow: 0 0 25px rgba(0, 0, 0, 0.09);
          box-shadow: 0 0 25px rgba(0, 0, 0, 0.09);
}

.shadow-4 {
  -webkit-box-shadow: 0 0 35px rgba(0, 0, 0, 0.11);
          box-shadow: 0 0 35px rgba(0, 0, 0, 0.11);
}

.shadow-5 {
  -webkit-box-shadow: 0 0 45px rgba(0, 0, 0, 0.13);
          box-shadow: 0 0 45px rgba(0, 0, 0, 0.13);
}

.hover-shadow-1:hover {
  -webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.06);
          box-shadow: 0 0 5px rgba(0, 0, 0, 0.06);
}

.hover-shadow-2:hover {
  -webkit-box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
          box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
}

.hover-shadow-3:hover {
  -webkit-box-shadow: 0 0 25px rgba(0, 0, 0, 0.09);
          box-shadow: 0 0 25px rgba(0, 0, 0, 0.09);
}

.hover-shadow-4:hover {
  -webkit-box-shadow: 0 0 35px rgba(0, 0, 0, 0.11);
          box-shadow: 0 0 35px rgba(0, 0, 0, 0.11);
}

.hover-shadow-5:hover {
  -webkit-box-shadow: 0 0 45px rgba(0, 0, 0, 0.13);
          box-shadow: 0 0 45px rgba(0, 0, 0, 0.13);
}

.shadow-material-1 {
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.shadow-material-2 {
  -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
          box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

.shadow-material-3 {
  -webkit-box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

.shadow-material-4 {
  -webkit-box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
          box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
}

.shadow-material-5 {
  -webkit-box-shadow: 0 19px 38px rgba(0, 0, 0, 0.3), 0 15px 12px rgba(0, 0, 0, 0.22);
          box-shadow: 0 19px 38px rgba(0, 0, 0, 0.3), 0 15px 12px rgba(0, 0, 0, 0.22);
}

.hover-shadow-material-1:hover {
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.hover-shadow-material-2:hover {
  -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
          box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

.hover-shadow-material-3:hover {
  -webkit-box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

.hover-shadow-material-4:hover {
  -webkit-box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
          box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
}

.hover-shadow-material-5:hover {
  -webkit-box-shadow: 0 19px 38px rgba(0, 0, 0, 0.3), 0 15px 12px rgba(0, 0, 0, 0.22);
          box-shadow: 0 19px 38px rgba(0, 0, 0, 0.3), 0 15px 12px rgba(0, 0, 0, 0.22);
}

.shadow-0,
.hover-shadow-0,
.shadow-material-0,
.hover-shadow-material-0 {
  -webkit-box-shadow: none;
          box-shadow: none;
}

.text-hue-rotate {
  color: #f35626;
  background-image: -webkit-linear-gradient(92deg, #f35626, #feab3a);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  -webkit-animation: hue-rotate 60s infinite linear;
}

.bg-hue-rotate {
  -webkit-animation: hue-rotate 30s linear infinite;
}

@-webkit-keyframes hue {
  from {
    -webkit-filter: hue-rotate(0deg);
  }
  to {
    -webkit-filter: hue-rotate(-360deg);
  }
}

.no-border {
  border: none !important;
}

.no-padding {
  padding: 0 !important;
}

.no-margin {
  margin: 0 !important;
}

.no-shadow {
  shadow: none;
}

.no-text-shadow {
  text-shadow: none;
}

.no-scroll {
  overflow: hidden;
}

.no-radius {
  border-radius: 0 !important;
}

.no-shrink {
  flex-shrink: 0;
}

.no-grow {
  -webkit-box-flex: 0;
          flex-grow: 0;
}

.no-letter-spacing {
  letter-spacing: 0;
}

.no-wrap {
  white-space: nowrap;
  flex-wrap: nowrap;
}

.no-underline:hover, .no-underline:focus {
  text-decoration: none;
}

.w-0px {
  width: 0px !important;
}

.h-0px {
  height: 0px !important;
}

.w-10px {
  width: 10px !important;
}

.h-10px {
  height: 10px !important;
}

.w-20px {
  width: 20px !important;
}

.h-20px {
  height: 20px !important;
}

.w-25px {
  width: 25px !important;
}

.h-25px {
  height: 25px !important;
}

.w-30px {
  width: 30px !important;
}

.h-30px {
  height: 30px !important;
}

.w-40px {
  width: 40px !important;
}

.h-40px {
  height: 40px !important;
}

.w-50px {
  width: 50px !important;
}

.h-50px {
  height: 50px !important;
}

.w-60px {
  width: 60px !important;
}

.h-60px {
  height: 60px !important;
}

.w-64px {
  width: 64px !important;
}

.h-64px {
  height: 64px !important;
}

.w-70px {
  width: 70px !important;
}

.h-70px {
  height: 70px !important;
}

.w-75px {
  width: 75px !important;
}

.h-75px {
  height: 75px !important;
}

.w-80px {
  width: 80px !important;
}

.h-80px {
  height: 80px !important;
}

.w-90px {
  width: 90px !important;
}

.h-90px {
  height: 90px !important;
}

.w-100px {
  width: 100px !important;
}

.h-100px {
  height: 100px !important;
}

.w-120px {
  width: 120px !important;
}

.h-120px {
  height: 120px !important;
}

.w-140px {
  width: 140px !important;
}

.h-140px {
  height: 140px !important;
}

.w-150px {
  width: 150px !important;
}

.h-150px {
  height: 150px !important;
}

.w-160px {
  width: 160px !important;
}

.h-160px {
  height: 160px !important;
}

.w-180px {
  width: 180px !important;
}

.h-180px {
  height: 180px !important;
}

.w-200px {
  width: 200px !important;
}

.h-200px {
  height: 200px !important;
}

.w-250px {
  width: 250px !important;
}

.h-250px {
  height: 250px !important;
}

.w-300px {
  width: 300px !important;
}

.h-300px {
  height: 300px !important;
}

.w-350px {
  width: 350px !important;
}

.h-350px {
  height: 350px !important;
}

.w-400px {
  width: 400px !important;
}

.h-400px {
  height: 400px !important;
}

.w-450px {
  width: 450px !important;
}

.h-450px {
  height: 450px !important;
}

.w-500px {
  width: 500px !important;
}

.h-500px {
  height: 500px !important;
}

.w-600px {
  width: 600px !important;
}

.h-600px {
  height: 600px !important;
}

.w-700px {
  width: 700px !important;
}

.h-700px {
  height: 700px !important;
}

.w-800px {
  width: 800px !important;
}

.h-800px {
  height: 800px !important;
}

.w-900px {
  width: 900px !important;
}

.h-900px {
  height: 900px !important;
}

.w-0 {
  width: 0 !important;
}

.h-0 {
  height: 0 !important;
}

.w-full {
  width: 100% !important;
}

.w-half {
  width: 50%  !important;
}

.w-third {
  width: 33.333333% !important;
}

.w-fourth {
  width: 25%  !important;
}

.w-fifth {
  width: 20%  !important;
}

.h-full {
  height: 100% !important;
}

.h-half {
  height: 50%  !important;
}

.h-third {
  height: 33.333333% !important;
}

.h-fourth {
  height: 25%  !important;
}

.h-fifth {
  height: 20%  !important;
}

.w-fullscreen {
  width: 100vw !important;
}

.h-fullscreen {
  height: 100vh !important;
}

.min-w-fullscreen {
  min-width: 100vw !important;
}

.min-h-fullscreen {
  min-height: 100vh !important;
}

.m-10 {
  margin: 10px !important;
}

.mt-10 {
  margin-top: 10px !important;
}

.mr-10 {
  margin-right: 10px !important;
}

.mb-10 {
  margin-bottom: 10px !important;
}

.ml-10 {
  margin-left: 10px !important;
}

.mx-10 {
  margin-right: 10px !important;
  margin-left: 10px !important;
}

.my-10 {
  margin-top: 10px !important;
  margin-bottom: 10px !important;
}

.m-12 {
  margin: 12px !important;
}

.mt-12 {
  margin-top: 12px !important;
}

.mr-12 {
  margin-right: 12px !important;
}

.mb-12 {
  margin-bottom: 12px !important;
}

.ml-12 {
  margin-left: 12px !important;
}

.mx-12 {
  margin-right: 12px !important;
  margin-left: 12px !important;
}

.my-12 {
  margin-top: 12px !important;
  margin-bottom: 12px !important;
}

.m-15 {
  margin: 15px !important;
}

.mt-15 {
  margin-top: 15px !important;
}

.mr-15 {
  margin-right: 15px !important;
}

.mb-15 {
  margin-bottom: 15px !important;
}

.ml-15 {
  margin-left: 15px !important;
}

.mx-15 {
  margin-right: 15px !important;
  margin-left: 15px !important;
}

.my-15 {
  margin-top: 15px !important;
  margin-bottom: 15px !important;
}

.m-16 {
  margin: 16px !important;
}

.mt-16 {
  margin-top: 16px !important;
}

.mr-16 {
  margin-right: 16px !important;
}

.mb-16 {
  margin-bottom: 16px !important;
}

.ml-16 {
  margin-left: 16px !important;
}

.mx-16 {
  margin-right: 16px !important;
  margin-left: 16px !important;
}

.my-16 {
  margin-top: 16px !important;
  margin-bottom: 16px !important;
}

.m-20 {
  margin: 20px !important;
}

.mt-20 {
  margin-top: 20px !important;
}

.mr-20 {
  margin-right: 20px !important;
}

.mb-20 {
  margin-bottom: 20px !important;
}

.ml-20 {
  margin-left: 20px !important;
}

.mx-20 {
  margin-right: 20px !important;
  margin-left: 20px !important;
}

.my-20 {
  margin-top: 20px !important;
  margin-bottom: 20px !important;
}

.m-24 {
  margin: 24px !important;
}

.mt-24 {
  margin-top: 24px !important;
}

.mr-24 {
  margin-right: 24px !important;
}

.mb-24 {
  margin-bottom: 24px !important;
}

.ml-24 {
  margin-left: 24px !important;
}

.mx-24 {
  margin-right: 24px !important;
  margin-left: 24px !important;
}

.my-24 {
  margin-top: 24px !important;
  margin-bottom: 24px !important;
}

.m-25 {
  margin: 25px !important;
}

.mt-25 {
  margin-top: 25px !important;
}

.mr-25 {
  margin-right: 25px !important;
}

.mb-25 {
  margin-bottom: 25px !important;
}

.ml-25 {
  margin-left: 25px !important;
}

.mx-25 {
  margin-right: 25px !important;
  margin-left: 25px !important;
}

.my-25 {
  margin-top: 25px !important;
  margin-bottom: 25px !important;
}

.m-30 {
  margin: 30px !important;
}

.mt-30 {
  margin-top: 30px !important;
}

.mr-30 {
  margin-right: 30px !important;
}

.mb-30 {
  margin-bottom: 30px !important;
}

.ml-30 {
  margin-left: 30px !important;
}

.mx-30 {
  margin-right: 30px !important;
  margin-left: 30px !important;
}

.my-30 {
  margin-top: 30px !important;
  margin-bottom: 30px !important;
}

.m-35 {
  margin: 35px !important;
}

.mt-35 {
  margin-top: 35px !important;
}

.mr-35 {
  margin-right: 35px !important;
}

.mb-35 {
  margin-bottom: 35px !important;
}

.ml-35 {
  margin-left: 35px !important;
}

.mx-35 {
  margin-right: 35px !important;
  margin-left: 35px !important;
}

.my-35 {
  margin-top: 35px !important;
  margin-bottom: 35px !important;
}

.m-40 {
  margin: 40px !important;
}

.mt-40 {
  margin-top: 40px !important;
}

.mr-40 {
  margin-right: 40px !important;
}

.mb-40 {
  margin-bottom: 40px !important;
}

.ml-40 {
  margin-left: 40px !important;
}

.mx-40 {
  margin-right: 40px !important;
  margin-left: 40px !important;
}

.my-40 {
  margin-top: 40px !important;
  margin-bottom: 40px !important;
}

.m-45 {
  margin: 45px !important;
}

.mt-45 {
  margin-top: 45px !important;
}

.mr-45 {
  margin-right: 45px !important;
}

.mb-45 {
  margin-bottom: 45px !important;
}

.ml-45 {
  margin-left: 45px !important;
}

.mx-45 {
  margin-right: 45px !important;
  margin-left: 45px !important;
}

.my-45 {
  margin-top: 45px !important;
  margin-bottom: 45px !important;
}

.m-50 {
  margin: 50px !important;
}

.mt-50 {
  margin-top: 50px !important;
}

.mr-50 {
  margin-right: 50px !important;
}

.mb-50 {
  margin-bottom: 50px !important;
}

.ml-50 {
  margin-left: 50px !important;
}

.mx-50 {
  margin-right: 50px !important;
  margin-left: 50px !important;
}

.my-50 {
  margin-top: 50px !important;
  margin-bottom: 50px !important;
}

.m-60 {
  margin: 60px !important;
}

.mt-60 {
  margin-top: 60px !important;
}

.mr-60 {
  margin-right: 60px !important;
}

.mb-60 {
  margin-bottom: 60px !important;
}

.ml-60 {
  margin-left: 60px !important;
}

.mx-60 {
  margin-right: 60px !important;
  margin-left: 60px !important;
}

.my-60 {
  margin-top: 60px !important;
  margin-bottom: 60px !important;
}

.m-70 {
  margin: 70px !important;
}

.mt-70 {
  margin-top: 70px !important;
}

.mr-70 {
  margin-right: 70px !important;
}

.mb-70 {
  margin-bottom: 70px !important;
}

.ml-70 {
  margin-left: 70px !important;
}

.mx-70 {
  margin-right: 70px !important;
  margin-left: 70px !important;
}

.my-70 {
  margin-top: 70px !important;
  margin-bottom: 70px !important;
}

.m-80 {
  margin: 80px !important;
}

.mt-80 {
  margin-top: 80px !important;
}

.mr-80 {
  margin-right: 80px !important;
}

.mb-80 {
  margin-bottom: 80px !important;
}

.ml-80 {
  margin-left: 80px !important;
}

.mx-80 {
  margin-right: 80px !important;
  margin-left: 80px !important;
}

.my-80 {
  margin-top: 80px !important;
  margin-bottom: 80px !important;
}

.m-90 {
  margin: 90px !important;
}

.mt-90 {
  margin-top: 90px !important;
}

.mr-90 {
  margin-right: 90px !important;
}

.mb-90 {
  margin-bottom: 90px !important;
}

.ml-90 {
  margin-left: 90px !important;
}

.mx-90 {
  margin-right: 90px !important;
  margin-left: 90px !important;
}

.my-90 {
  margin-top: 90px !important;
  margin-bottom: 90px !important;
}

.m-100 {
  margin: 100px !important;
}

.mt-100 {
  margin-top: 100px !important;
}

.mr-100 {
  margin-right: 100px !important;
}

.mb-100 {
  margin-bottom: 100px !important;
}

.ml-100 {
  margin-left: 100px !important;
}

.mx-100 {
  margin-right: 100px !important;
  margin-left: 100px !important;
}

.my-100 {
  margin-top: 100px !important;
  margin-bottom: 100px !important;
}

.m-120 {
  margin: 120px !important;
}

.mt-120 {
  margin-top: 120px !important;
}

.mr-120 {
  margin-right: 120px !important;
}

.mb-120 {
  margin-bottom: 120px !important;
}

.ml-120 {
  margin-left: 120px !important;
}

.mx-120 {
  margin-right: 120px !important;
  margin-left: 120px !important;
}

.my-120 {
  margin-top: 120px !important;
  margin-bottom: 120px !important;
}

.m-140 {
  margin: 140px !important;
}

.mt-140 {
  margin-top: 140px !important;
}

.mr-140 {
  margin-right: 140px !important;
}

.mb-140 {
  margin-bottom: 140px !important;
}

.ml-140 {
  margin-left: 140px !important;
}

.mx-140 {
  margin-right: 140px !important;
  margin-left: 140px !important;
}

.my-140 {
  margin-top: 140px !important;
  margin-bottom: 140px !important;
}

.m-150 {
  margin: 150px !important;
}

.mt-150 {
  margin-top: 150px !important;
}

.mr-150 {
  margin-right: 150px !important;
}

.mb-150 {
  margin-bottom: 150px !important;
}

.ml-150 {
  margin-left: 150px !important;
}

.mx-150 {
  margin-right: 150px !important;
  margin-left: 150px !important;
}

.my-150 {
  margin-top: 150px !important;
  margin-bottom: 150px !important;
}

.m-160 {
  margin: 160px !important;
}

.mt-160 {
  margin-top: 160px !important;
}

.mr-160 {
  margin-right: 160px !important;
}

.mb-160 {
  margin-bottom: 160px !important;
}

.ml-160 {
  margin-left: 160px !important;
}

.mx-160 {
  margin-right: 160px !important;
  margin-left: 160px !important;
}

.my-160 {
  margin-top: 160px !important;
  margin-bottom: 160px !important;
}

.m-180 {
  margin: 180px !important;
}

.mt-180 {
  margin-top: 180px !important;
}

.mr-180 {
  margin-right: 180px !important;
}

.mb-180 {
  margin-bottom: 180px !important;
}

.ml-180 {
  margin-left: 180px !important;
}

.mx-180 {
  margin-right: 180px !important;
  margin-left: 180px !important;
}

.my-180 {
  margin-top: 180px !important;
  margin-bottom: 180px !important;
}

.m-200 {
  margin: 200px !important;
}

.mt-200 {
  margin-top: 200px !important;
}

.mr-200 {
  margin-right: 200px !important;
}

.mb-200 {
  margin-bottom: 200px !important;
}

.ml-200 {
  margin-left: 200px !important;
}

.mx-200 {
  margin-right: 200px !important;
  margin-left: 200px !important;
}

.my-200 {
  margin-top: 200px !important;
  margin-bottom: 200px !important;
}

.m-250 {
  margin: 250px !important;
}

.mt-250 {
  margin-top: 250px !important;
}

.mr-250 {
  margin-right: 250px !important;
}

.mb-250 {
  margin-bottom: 250px !important;
}

.ml-250 {
  margin-left: 250px !important;
}

.mx-250 {
  margin-right: 250px !important;
  margin-left: 250px !important;
}

.my-250 {
  margin-top: 250px !important;
  margin-bottom: 250px !important;
}

.m-300 {
  margin: 300px !important;
}

.mt-300 {
  margin-top: 300px !important;
}

.mr-300 {
  margin-right: 300px !important;
}

.mb-300 {
  margin-bottom: 300px !important;
}

.ml-300 {
  margin-left: 300px !important;
}

.mx-300 {
  margin-right: 300px !important;
  margin-left: 300px !important;
}

.my-300 {
  margin-top: 300px !important;
  margin-bottom: 300px !important;
}

.m-400 {
  margin: 400px !important;
}

.mt-400 {
  margin-top: 400px !important;
}

.mr-400 {
  margin-right: 400px !important;
}

.mb-400 {
  margin-bottom: 400px !important;
}

.ml-400 {
  margin-left: 400px !important;
}

.mx-400 {
  margin-right: 400px !important;
  margin-left: 400px !important;
}

.my-400 {
  margin-top: 400px !important;
  margin-bottom: 400px !important;
}

.m-500 {
  margin: 500px !important;
}

.mt-500 {
  margin-top: 500px !important;
}

.mr-500 {
  margin-right: 500px !important;
}

.mb-500 {
  margin-bottom: 500px !important;
}

.ml-500 {
  margin-left: 500px !important;
}

.mx-500 {
  margin-right: 500px !important;
  margin-left: 500px !important;
}

.my-500 {
  margin-top: 500px !important;
  margin-bottom: 500px !important;
}

.p-10 {
  padding: 10px !important;
}

.pt-10 {
  padding-top: 10px !important;
}

.pr-10 {
  padding-right: 10px !important;
}

.pb-10 {
  padding-bottom: 10px !important;
}

.pl-10 {
  padding-left: 10px !important;
}

.px-10 {
  padding-right: 10px !important;
  padding-left: 10px !important;
}

.py-10 {
  padding-top: 10px !important;
  padding-bottom: 10px !important;
}

.p-12 {
  padding: 12px !important;
}

.pt-12 {
  padding-top: 12px !important;
}

.pr-12 {
  padding-right: 12px !important;
}

.pb-12 {
  padding-bottom: 12px !important;
}

.pl-12 {
  padding-left: 12px !important;
}

.px-12 {
  padding-right: 12px !important;
  padding-left: 12px !important;
}

.py-12 {
  padding-top: 12px !important;
  padding-bottom: 12px !important;
}

.p-15 {
  padding: 15px !important;
}

.pt-15 {
  padding-top: 15px !important;
}

.pr-15 {
  padding-right: 15px !important;
}

.pb-15 {
  padding-bottom: 15px !important;
}

.pl-15 {
  padding-left: 15px !important;
}

.px-15 {
  padding-right: 15px !important;
  padding-left: 15px !important;
}

.py-15 {
  padding-top: 15px !important;
  padding-bottom: 15px !important;
}

.p-16 {
  padding: 16px !important;
}

.pt-16 {
  padding-top: 16px !important;
}

.pr-16 {
  padding-right: 16px !important;
}

.pb-16 {
  padding-bottom: 16px !important;
}

.pl-16 {
  padding-left: 16px !important;
}

.px-16 {
  padding-right: 16px !important;
  padding-left: 16px !important;
}

.py-16 {
  padding-top: 16px !important;
  padding-bottom: 16px !important;
}

.p-20 {
  padding: 20px !important;
}

.pt-20 {
  padding-top: 20px !important;
}

.pr-20 {
  padding-right: 20px !important;
}

.pb-20 {
  padding-bottom: 20px !important;
}

.pl-20 {
  padding-left: 20px !important;
}

.px-20 {
  padding-right: 20px !important;
  padding-left: 20px !important;
}

.py-20 {
  padding-top: 20px !important;
  padding-bottom: 20px !important;
}

.p-24 {
  padding: 24px !important;
}

.pt-24 {
  padding-top: 24px !important;
}

.pr-24 {
  padding-right: 24px !important;
}

.pb-24 {
  padding-bottom: 24px !important;
}

.pl-24 {
  padding-left: 24px !important;
}

.px-24 {
  padding-right: 24px !important;
  padding-left: 24px !important;
}

.py-24 {
  padding-top: 24px !important;
  padding-bottom: 24px !important;
}

.p-25 {
  padding: 25px !important;
}

.pt-25 {
  padding-top: 25px !important;
}

.pr-25 {
  padding-right: 25px !important;
}

.pb-25 {
  padding-bottom: 25px !important;
}

.pl-25 {
  padding-left: 25px !important;
}

.px-25 {
  padding-right: 25px !important;
  padding-left: 25px !important;
}

.py-25 {
  padding-top: 25px !important;
  padding-bottom: 25px !important;
}

.p-30 {
  padding: 30px !important;
}

.pt-30 {
  padding-top: 30px !important;
}

.pr-30 {
  padding-right: 30px !important;
}

.pb-30 {
  padding-bottom: 30px !important;
}

.pl-30 {
  padding-left: 30px !important;
}

.px-30 {
  padding-right: 30px !important;
  padding-left: 30px !important;
}

.py-30 {
  padding-top: 30px !important;
  padding-bottom: 30px !important;
}

.p-35 {
  padding: 35px !important;
}

.pt-35 {
  padding-top: 35px !important;
}

.pr-35 {
  padding-right: 35px !important;
}

.pb-35 {
  padding-bottom: 35px !important;
}

.pl-35 {
  padding-left: 35px !important;
}

.px-35 {
  padding-right: 35px !important;
  padding-left: 35px !important;
}

.py-35 {
  padding-top: 35px !important;
  padding-bottom: 35px !important;
}

.p-40 {
  padding: 40px !important;
}

.pt-40 {
  padding-top: 40px !important;
}

.pr-40 {
  padding-right: 40px !important;
}

.pb-40 {
  padding-bottom: 40px !important;
}

.pl-40 {
  padding-left: 40px !important;
}

.px-40 {
  padding-right: 40px !important;
  padding-left: 40px !important;
}

.py-40 {
  padding-top: 40px !important;
  padding-bottom: 40px !important;
}

.p-45 {
  padding: 45px !important;
}

.pt-45 {
  padding-top: 45px !important;
}

.pr-45 {
  padding-right: 45px !important;
}

.pb-45 {
  padding-bottom: 45px !important;
}

.pl-45 {
  padding-left: 45px !important;
}

.px-45 {
  padding-right: 45px !important;
  padding-left: 45px !important;
}

.py-45 {
  padding-top: 45px !important;
  padding-bottom: 45px !important;
}

.p-50 {
  padding: 50px !important;
}

.pt-50 {
  padding-top: 50px !important;
}

.pr-50 {
  padding-right: 50px !important;
}

.pb-50 {
  padding-bottom: 50px !important;
}

.pl-50 {
  padding-left: 50px !important;
}

.px-50 {
  padding-right: 50px !important;
  padding-left: 50px !important;
}

.py-50 {
  padding-top: 50px !important;
  padding-bottom: 50px !important;
}

.p-60 {
  padding: 60px !important;
}

.pt-60 {
  padding-top: 60px !important;
}

.pr-60 {
  padding-right: 60px !important;
}

.pb-60 {
  padding-bottom: 60px !important;
}

.pl-60 {
  padding-left: 60px !important;
}

.px-60 {
  padding-right: 60px !important;
  padding-left: 60px !important;
}

.py-60 {
  padding-top: 60px !important;
  padding-bottom: 60px !important;
}

.p-70 {
  padding: 70px !important;
}

.pt-70 {
  padding-top: 70px !important;
}

.pr-70 {
  padding-right: 70px !important;
}

.pb-70 {
  padding-bottom: 70px !important;
}

.pl-70 {
  padding-left: 70px !important;
}

.px-70 {
  padding-right: 70px !important;
  padding-left: 70px !important;
}

.py-70 {
  padding-top: 70px !important;
  padding-bottom: 70px !important;
}

.p-80 {
  padding: 80px !important;
}

.pt-80 {
  padding-top: 80px !important;
}

.pr-80 {
  padding-right: 80px !important;
}

.pb-80 {
  padding-bottom: 80px !important;
}

.pl-80 {
  padding-left: 80px !important;
}

.px-80 {
  padding-right: 80px !important;
  padding-left: 80px !important;
}

.py-80 {
  padding-top: 80px !important;
  padding-bottom: 80px !important;
}

.p-90 {
  padding: 90px !important;
}

.pt-90 {
  padding-top: 90px !important;
}

.pr-90 {
  padding-right: 90px !important;
}

.pb-90 {
  padding-bottom: 90px !important;
}

.pl-90 {
  padding-left: 90px !important;
}

.px-90 {
  padding-right: 90px !important;
  padding-left: 90px !important;
}

.py-90 {
  padding-top: 90px !important;
  padding-bottom: 90px !important;
}

.p-100 {
  padding: 100px !important;
}

.pt-100 {
  padding-top: 100px !important;
}

.pr-100 {
  padding-right: 100px !important;
}

.pb-100 {
  padding-bottom: 100px !important;
}

.pl-100 {
  padding-left: 100px !important;
}

.px-100 {
  padding-right: 100px !important;
  padding-left: 100px !important;
}

.py-100 {
  padding-top: 100px !important;
  padding-bottom: 100px !important;
}

.p-120 {
  padding: 120px !important;
}

.pt-120 {
  padding-top: 120px !important;
}

.pr-120 {
  padding-right: 120px !important;
}

.pb-120 {
  padding-bottom: 120px !important;
}

.pl-120 {
  padding-left: 120px !important;
}

.px-120 {
  padding-right: 120px !important;
  padding-left: 120px !important;
}

.py-120 {
  padding-top: 120px !important;
  padding-bottom: 120px !important;
}

.p-140 {
  padding: 140px !important;
}

.pt-140 {
  padding-top: 140px !important;
}

.pr-140 {
  padding-right: 140px !important;
}

.pb-140 {
  padding-bottom: 140px !important;
}

.pl-140 {
  padding-left: 140px !important;
}

.px-140 {
  padding-right: 140px !important;
  padding-left: 140px !important;
}

.py-140 {
  padding-top: 140px !important;
  padding-bottom: 140px !important;
}

.p-150 {
  padding: 150px !important;
}

.pt-150 {
  padding-top: 150px !important;
}

.pr-150 {
  padding-right: 150px !important;
}

.pb-150 {
  padding-bottom: 150px !important;
}

.pl-150 {
  padding-left: 150px !important;
}

.px-150 {
  padding-right: 150px !important;
  padding-left: 150px !important;
}

.py-150 {
  padding-top: 150px !important;
  padding-bottom: 150px !important;
}

.p-160 {
  padding: 160px !important;
}

.pt-160 {
  padding-top: 160px !important;
}

.pr-160 {
  padding-right: 160px !important;
}

.pb-160 {
  padding-bottom: 160px !important;
}

.pl-160 {
  padding-left: 160px !important;
}

.px-160 {
  padding-right: 160px !important;
  padding-left: 160px !important;
}

.py-160 {
  padding-top: 160px !important;
  padding-bottom: 160px !important;
}

.p-180 {
  padding: 180px !important;
}

.pt-180 {
  padding-top: 180px !important;
}

.pr-180 {
  padding-right: 180px !important;
}

.pb-180 {
  padding-bottom: 180px !important;
}

.pl-180 {
  padding-left: 180px !important;
}

.px-180 {
  padding-right: 180px !important;
  padding-left: 180px !important;
}

.py-180 {
  padding-top: 180px !important;
  padding-bottom: 180px !important;
}

.p-200 {
  padding: 200px !important;
}

.pt-200 {
  padding-top: 200px !important;
}

.pr-200 {
  padding-right: 200px !important;
}

.pb-200 {
  padding-bottom: 200px !important;
}

.pl-200 {
  padding-left: 200px !important;
}

.px-200 {
  padding-right: 200px !important;
  padding-left: 200px !important;
}

.py-200 {
  padding-top: 200px !important;
  padding-bottom: 200px !important;
}

.p-250 {
  padding: 250px !important;
}

.pt-250 {
  padding-top: 250px !important;
}

.pr-250 {
  padding-right: 250px !important;
}

.pb-250 {
  padding-bottom: 250px !important;
}

.pl-250 {
  padding-left: 250px !important;
}

.px-250 {
  padding-right: 250px !important;
  padding-left: 250px !important;
}

.py-250 {
  padding-top: 250px !important;
  padding-bottom: 250px !important;
}

.p-300 {
  padding: 300px !important;
}

.pt-300 {
  padding-top: 300px !important;
}

.pr-300 {
  padding-right: 300px !important;
}

.pb-300 {
  padding-bottom: 300px !important;
}

.pl-300 {
  padding-left: 300px !important;
}

.px-300 {
  padding-right: 300px !important;
  padding-left: 300px !important;
}

.py-300 {
  padding-top: 300px !important;
  padding-bottom: 300px !important;
}

.p-400 {
  padding: 400px !important;
}

.pt-400 {
  padding-top: 400px !important;
}

.pr-400 {
  padding-right: 400px !important;
}

.pb-400 {
  padding-bottom: 400px !important;
}

.pl-400 {
  padding-left: 400px !important;
}

.px-400 {
  padding-right: 400px !important;
  padding-left: 400px !important;
}

.py-400 {
  padding-top: 400px !important;
  padding-bottom: 400px !important;
}

.p-500 {
  padding: 500px !important;
}

.pt-500 {
  padding-top: 500px !important;
}

.pr-500 {
  padding-right: 500px !important;
}

.pb-500 {
  padding-bottom: 500px !important;
}

.pl-500 {
  padding-left: 500px !important;
}

.px-500 {
  padding-right: 500px !important;
  padding-left: 500px !important;
}

.py-500 {
  padding-top: 500px !important;
  padding-bottom: 500px !important;
}

.gap-items > *,
.gap-items-3 > * {
  margin-left: 8px;
  margin-right: 8px;
}

.gap-items > *:first-child,
.gap-items-3 > *:first-child {
  margin-left: 0;
}

.gap-items > *:last-child,
.gap-items-3 > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .gap-items > *,
  .gap-items-3 > * {
    margin-left: 4px;
    margin-right: 4px;
  }
  .gap-items > *:first-child,
  .gap-items-3 > *:first-child {
    margin-left: 0;
  }
  .gap-items > *:last-child,
  .gap-items-3 > *:last-child {
    margin-right: 0;
  }
}

.gap-items-1 > * {
  margin-left: 2px;
  margin-right: 2px;
}

.gap-items-1 > *:first-child {
  margin-left: 0;
}

.gap-items-1 > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .gap-items-1 > * {
    margin-left: 1px;
    margin-right: 1px;
  }
  .gap-items-1 > *:first-child {
    margin-left: 0;
  }
  .gap-items-1 > *:last-child {
    margin-right: 0;
  }
}

.gap-items-2 > * {
  margin-left: 4px;
  margin-right: 4px;
}

.gap-items-2 > *:first-child {
  margin-left: 0;
}

.gap-items-2 > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .gap-items-2 > * {
    margin-left: 2px;
    margin-right: 2px;
  }
  .gap-items-2 > *:first-child {
    margin-left: 0;
  }
  .gap-items-2 > *:last-child {
    margin-right: 0;
  }
}

.gap-items-4 > * {
  margin-left: 12px;
  margin-right: 12px;
}

.gap-items-4 > *:first-child {
  margin-left: 0;
}

.gap-items-4 > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .gap-items-4 > * {
    margin-left: 6px;
    margin-right: 6px;
  }
  .gap-items-4 > *:first-child {
    margin-left: 0;
  }
  .gap-items-4 > *:last-child {
    margin-right: 0;
  }
}

.gap-items-5 > * {
  margin-left: 16px;
  margin-right: 16px;
}

.gap-items-5 > *:first-child {
  margin-left: 0;
}

.gap-items-5 > *:last-child {
  margin-right: 0;
}

@media (max-width: 767px) {
  .gap-items-5 > * {
    margin-left: 8px;
    margin-right: 8px;
  }
  .gap-items-5 > *:first-child {
    margin-left: 0;
  }
  .gap-items-5 > *:last-child {
    margin-right: 0;
  }
}

.gap-multiline-items,
.gap-multiline-items-3 {
  margin: -8px;
}

.gap-multiline-items > *,
.gap-multiline-items-3 > * {
  margin: 8px;
}

@media (max-width: 767px) {
  .gap-multiline-items,
  .gap-multiline-items-3 {
    margin: -4px;
  }
  .gap-multiline-items > *,
  .gap-multiline-items-3 > * {
    margin: 4px;
  }
}

.gap-multiline-items-1 {
  margin: -2px;
}

.gap-multiline-items-1 > * {
  margin: 2px;
}

@media (max-width: 767px) {
  .gap-multiline-items-1 {
    margin: -1px;
  }
  .gap-multiline-items-1 > * {
    margin: 1px;
  }
}

.gap-multiline-items-2 {
  margin: -4px;
}

.gap-multiline-items-2 > * {
  margin: 4px;
}

@media (max-width: 767px) {
  .gap-multiline-items-2 {
    margin: -2px;
  }
  .gap-multiline-items-2 > * {
    margin: 2px;
  }
}

.gap-multiline-items-4 {
  margin: -12px;
}

.gap-multiline-items-4 > * {
  margin: 12px;
}

@media (max-width: 767px) {
  .gap-multiline-items-4 {
    margin: -6px;
  }
  .gap-multiline-items-4 > * {
    margin: 6px;
  }
}

.gap-multiline-items-5 {
  margin: -16px;
}

.gap-multiline-items-5 > * {
  margin: 16px;
}

@media (max-width: 767px) {
  .gap-multiline-items-5 {
    margin: -8px;
  }
  .gap-multiline-items-5 > * {
    margin: 8px;
  }
}

.gap-y.gap-items,
.gap-y.gap-items-3 {
  margin: -8px;
}

.gap-y.gap-items > *,
.gap-y.gap-items-3 > * {
  margin: 8px;
}

@media (max-width: 767px) {
  .gap-y.gap-items,
  .gap-y.gap-items-3 {
    margin: -4px;
  }
  .gap-y.gap-items > *,
  .gap-y.gap-items-3 > * {
    margin: 4px;
  }
}

.gap-y.gap-items-1 {
  margin: -2px;
}

.gap-y.gap-items-1 > * {
  margin: 2px;
}

@media (max-width: 767px) {
  .gap-y.gap-items-1 {
    margin: -1px;
  }
  .gap-y.gap-items-1 > * {
    margin: 1px;
  }
}

.gap-y.gap-items-2 {
  margin: -4px;
}

.gap-y.gap-items-2 > * {
  margin: 4px;
}

@media (max-width: 767px) {
  .gap-y.gap-items-2 {
    margin: -2px;
  }
  .gap-y.gap-items-2 > * {
    margin: 2px;
  }
}

.gap-y.gap-items-4 {
  margin: -12px;
}

.gap-y.gap-items-4 > * {
  margin: 12px;
}

@media (max-width: 767px) {
  .gap-y.gap-items-4 {
    margin: -6px;
  }
  .gap-y.gap-items-4 > * {
    margin: 6px;
  }
}

.gap-y.gap-items-5 {
  margin: -16px;
}

.gap-y.gap-items-5 > * {
  margin: 16px;
}

@media (max-width: 767px) {
  .gap-y.gap-items-5 {
    margin: -8px;
  }
  .gap-y.gap-items-5 > * {
    margin: 8px;
  }
}

.font-body,
.font-roboto {
  font-family: Roboto, sans-serif;
}

.font-title,
.font-ubuntu {
  font-family: Roboto, sans-serif;
}

.font-article,
.font-lora {
  font-family: Roboto, sans-serif;
}

.fs-8 {
  font-size: 8px !important;
}

.fs-9 {
  font-size: 9px !important;
}

.fs-10 {
  font-size: 10px !important;
}

.fs-11 {
  font-size: 11px !important;
}

.fs-12 {
  font-size: 12px !important;
}

.fs-13 {
  font-size: 13px !important;
}

.fs-14 {
  font-size: 14px !important;
}

.fs-15 {
  font-size: 15px !important;
}

.fs-16 {
  font-size: 16px !important;
}

.fs-17 {
  font-size: 17px !important;
}

.fs-18 {
  font-size: 18px !important;
}

.fs-19 {
  font-size: 19px !important;
}

.fs-20 {
  font-size: 20px !important;
}

.fs-22 {
  font-size: 22px !important;
}

.fs-24 {
  font-size: 24px !important;
}

.fs-25 {
  font-size: 25px !important;
}

.fs-26 {
  font-size: 26px !important;
}

.fs-28 {
  font-size: 28px !important;
}

.fs-30 {
  font-size: 30px !important;
  line-height: 1.2;
}

.fs-35 {
  font-size: 35px !important;
  line-height: 1.2;
}

.fs-40 {
  font-size: 40px !important;
  line-height: 1.2;
}

.fs-45 {
  font-size: 45px !important;
  line-height: 1.2;
}

.fs-50 {
  font-size: 50px !important;
  line-height: 1.2;
}

.fs-60 {
  font-size: 60px !important;
  line-height: 1.2;
}

.fs-70 {
  font-size: 70px !important;
  line-height: 1.2;
}

.fs-80 {
  font-size: 80px !important;
  line-height: 1.2;
}

.fs-90 {
  font-size: 90px !important;
  line-height: 1.2;
}

.fw-100 {
  font-weight: 100 !important;
}

.fw-200 {
  font-weight: 200 !important;
}

.fw-300 {
  font-weight: 300 !important;
}

.fw-400 {
  font-weight: 400 !important;
}

.fw-500 {
  font-weight: 500 !important;
}

.fw-600 {
  font-weight: 600 !important;
}

.fw-700 {
  font-weight: 700 !important;
}

.fw-800 {
  font-weight: 800 !important;
}

.fw-900 {
  font-weight: 900 !important;
}

.lh-0 {
  line-height: 0   !important;
}

.lh-1 {
  line-height: 1   !important;
}

.lh-11 {
  line-height: 1.1 !important;
}

.lh-12 {
  line-height: 1.2 !important;
}

.lh-13 {
  line-height: 1.3 !important;
}

.lh-14 {
  line-height: 1.4 !important;
}

.lh-15 {
  line-height: 1.5 !important;
}

.lh-16 {
  line-height: 1.6 !important;
}

.lh-17 {
  line-height: 1.7 !important;
}

.lh-18 {
  line-height: 1.8 !important;
}

.lh-19 {
  line-height: 1.9 !important;
}

.lh-2 {
  line-height: 2   !important;
}

.lh-22 {
  line-height: 2.2 !important;
}

.lh-24 {
  line-height: 2.4 !important;
}

.lh-25 {
  line-height: 2.5 !important;
}

.lh-26 {
  line-height: 2.6 !important;
}

.lh-28 {
  line-height: 2.8 !important;
}

.lh-3 {
  line-height: 3   !important;
}

.lh-35 {
  line-height: 3.5 !important;
}

.lh-4 {
  line-height: 4   !important;
}

.lh-45 {
  line-height: 4.5 !important;
}

.lh-5 {
  line-height: 5   !important;
}

.letter-spacing-0 {
  letter-spacing: 0px !important;
}

.ls-0 {
  letter-spacing: 0px !important;
}

.letter-spacing-1 {
  letter-spacing: 1px !important;
}

.ls-1 {
  letter-spacing: 1px !important;
}

.letter-spacing-2 {
  letter-spacing: 2px !important;
}

.ls-2 {
  letter-spacing: 2px !important;
}

.letter-spacing-3 {
  letter-spacing: 3px !important;
}

.ls-3 {
  letter-spacing: 3px !important;
}

.letter-spacing-4 {
  letter-spacing: 4px !important;
}

.ls-4 {
  letter-spacing: 4px !important;
}

.letter-spacing-5 {
  letter-spacing: 5px !important;
}

.ls-5 {
  letter-spacing: 5px !important;
}

.text-truncate {
  width: auto;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.align-sub {
  vertical-align: sub;
}
TXT;

$this->registerCss($css);


