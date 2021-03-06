<?php
/* yap for page design:
page_layout
body_class
n navbar nx
s sidebar sx
c content
    h header
    t title tx
    b breadcrumbs bx
    f footer fx
+x before x
x+ after x
!x = has x
-x = no x
.x = minimal x
*/

use yii\helpers\Html;
use app\assets\MainAsset;

include('_nav.php');
include('_css.php');
include('_js.php');
include('../config/functions.php');

MainAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

Yii::$app->params['body_class'] = Yii::$app->params['body_class'] ?? '';
if (strpos(Yii::$app->params['page_layout'], 'slo') !== false || strpos(Yii::$app->params['page_layout'], 'sro') !== false) {
    Yii::$app->params['body_class'] .= ' sidebar-xs';
}

if (strpos(Yii::$app->params['page_layout'], 'sli') !== false) {
    Yii::$app->params['body_class'] .= ' sidebar-xs has-detached-left';
}
if (strpos(Yii::$app->params['page_layout'], 'sri') !== false) {
    Yii::$app->params['body_class'] .= ' sidebar-xs has-detached-right';
}
if (strpos(Yii::$app->params['page_layout'], 'sro') !== false) {
    Yii::$app->params['body_class'] .= ' sidebar-xs sidebar-opposite-visible';
}


// OLD IMS
Yii::$app->params['page_breadcrumbs'] = Yii::$app->params['page_breadcrumbs'] ?? $this->params['breadcrumb'] ?? null;
Yii::$app->params['page_actions'] = Yii::$app->params['page_actions'] ?? $this->params['actions'] ?? null;

$this->beginPage();

if (in_array(USER_ID, [24820])) {
    $body_class = 'red-theme size-15';
} else {
    $body_class = 'blue-theme size-15';
}

if (isset($_GET['body_class'])) {
    $body_class = $_GET['body_class'];
}

?><!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <title><?= Yii::$app->params['page_meta_title'] ?> - Amica Travel IMS</title>
    <?= Html::csrfMetaTags() ?>
    <?= $this->head() ?>
    <style type="text/css">
    @media (min-width: 769px) {
        body.blue-theme.sidebar-xs .sidebar-main .navigation>li>ul {background-color:#fff}
    }
    </style>
</head>

<body class="<?= $body_class ?> <?= Yii::$app->params['body_class'] ?? '' ?>">
    <? if (strpos(Yii::$app->params['page_layout'], '-n') === false) { ?>
    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="/" ><img src="/assets/img/logo_396x128_c.png" alt="Logo"></a>
            <ul class="nav navbar-nav pull-right visible-xs-block">
                <? if (strpos(Yii::$app->params['page_layout'], 'sli') !== false || strpos(Yii::$app->params['page_layout'], 'sri') !== false) { ?>
                <li><a class="sidebar-mobile-detached-toggle"><i class="slicon-arrow-down"></i></a></li>
                <? } ?>
                <? if (strpos(Yii::$app->params['page_layout'], 'slo') !== false) { ?>
                <li><a class="sidebar-mobile-secondary-toggle"><i class="slicon-arrow-down"></i></a></li>
                <? } ?>
                <? if (strpos(Yii::$app->params['page_layout'], 'sro') !== false) { ?>
                <li><a class="sidebar-mobile-opposite-toggle"><i class="slicon-arrow-down"></i></a></li>
                <? } ?>
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="slicon-options"></i></a></li>
                <li><a class="sidebar-mobile-main-toggle"><i class="slicon-options-vertical"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav">
                <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="slicon-menu"></i></a></li>
<?php
// SECTIONS
if (isset(Yii::$app->params['top_nav']['sections']) && count(Yii::$app->params['top_nav']['sections']) > 1) {
?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="navbar-section"><?= Yii::$app->params['section_name'] ?></span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" id="dropdown-menu-sections">
                    <? renderDropdownMenu(Yii::$app->params['top_nav']['sections']) ?>
                    </ul>
                </li><?php
} // if sections ?>
                <li style="min-width:320px;">
                    <form id="qs" action="/search" class="navbar-form navbar-left" style="width:100%;">
                        <div class="form-group has-feedback" style="width:100%;">
                            <select id="livesearch" style="width:100%;">
                                <option value="" selected="selected"><?= Yii::t('nav', 'Search') ?></option>
                            </select>
                        </div>
                        <div id="suggest" class="search-suggest"></div>
                    </form>
                </li>
            </ul>

            <?= $this->blocks['nx'] ?? '' ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="<?= Yii::t('nav', 'Links') ?>"><i class="slicon-link"></i></a>
                    <ul class="dropdown-menu" id="dropdown-menu-links">
                    <? foreach (Yii::$app->params['top_nav']['links'] as $item) {
                        echo renderMenuItem($item);
                        } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="<?= Yii::t('nav', 'Help') ?>"><i class="slicon-question"></i></a>
                    <ul class="dropdown-menu" id="dropdown-menu-help">
                    <? foreach (Yii::$app->params['top_nav']['help'] as $item) {
                        echo renderMenuItem($item);
                        } ?>
                    </ul>
                </li>
                <li class="dropdown">
<?php
$langName = 'en';
$flagName = 'gb';
foreach (Yii::$app->params['top_nav']['lang'] as $item) {
    if ($item['code'] == Yii::$app->language) {
        $langName = $item['name'];
        $flagName = $item['flag'];
        break;
    }
}
?>
                    <a class="dropdown-toggle" data-toggle="dropdown" title="<?= $langName ?>"><span class="flag-icon flag-icon-<?= $flagName ?>"></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="english" href="/select/lang/en"><span class="flag-icon flag-icon-us"></span> English</a></li>
                        <li><a class="french" href="/select/lang/fr"><span class="flag-icon flag-icon-fr"></span> Français</a></li>
                        <li><a class="vietnamese" href="/select/lang/vi"><span class="flag-icon flag-icon-vn"></span> Tiếng Việt</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" title="<?= Yii::$app->user->identity->nickname ?>"><img class="img-circle" style="display:inline-block; width:28px; height:28px; margin:-5px 0;" src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" alt="U"></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <?php
                        foreach (Yii::$app->params['top_nav']['user'] as $item) {
                            echo renderMenuItem($item);
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->
    <? } // -n ?>

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">
            <? if (strpos(Yii::$app->params['page_layout'], '-s') === false) { ?>
            <!-- Main sidebar -->
            <div class="sidebar sidebar-main">
                <div class="sidebar-content">
                    <!-- User menu -->
                    <div class="sidebar-user">
                        <div class="category-content">
                            <div class="media">
                                <a href="#" class="media-left"><img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" class="img-circle img-sm" alt=""></a>
                                <div class="media-body">
                                    <span class="media-heading text-semibold"><?= Yii::$app->user->identity->nickname ?></span>
                                    <div class="text-size-mini text-muted">
                                        <?//= Yii::$app->user->identity->member->location ?>
                                    </div>
                                </div>

                                <div class="media-right media-middle">
                                    <ul class="icons-list">
                                        <li>
                                            <a href="/me"><i class="slicon-settings"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /user menu -->

                    <!-- Main navigation -->
                    <div class="sidebar-category sidebar-category-visible">
                        <div class="category-content no-padding">
                            <ul class="navigation navigation-main navigation-accordion">

                                <!-- Main -->
                            <?php
                            if (isset(Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']])) {
                                Yii::$app->params['side_nav']['main'] = Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']];
                                foreach (Yii::$app->params['side_nav']['main'] as $item) {
                                    echo renderMainNavItem($item);
                                }
                            }
                            ?>
                                <!-- /main -->

                            </ul>
                        </div>
                    </div>
                    <!-- /main navigation -->
                    <?= $this->blocks['sx'] ?? '' ?>
                </div>
            </div>
            <!-- /main sidebar -->
            <? } // -s ?>

            <? if (strpos(Yii::$app->params['page_layout'], 'slo') !== false) { ?>
            <?= $this->blocks['slo'] ?? '' ?>
            <? } // slo ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <?= $this->blocks['h'] ?? '' ?>
                <? if (strpos(Yii::$app->params['page_layout'], '-h') === false) { ?>
                <!-- Page header -->
                <div class="page-header page-header-default<?= strpos(Yii::$app->params['page_layout'], 'header_no_margin') !== false ? ' no-margin' : '' ?>">
                    <div class="page-header-content">
                        <?= $this->blocks['t'] ?? '' ?>
                        <? if (strpos(Yii::$app->params['page_layout'], '-t') === false) { ?>
                        <div class="page-title">
                            <h1>
                                <? if (isset(Yii::$app->params['page_icon']) && Yii::$app->params['page_icon'] != '') { ?>
                                <i class="<?= strpos(Yii::$app->params['page_icon'], 'slicon') === false ? 'fa fa-' : '' ?><?= Yii::$app->params['page_icon'] ?> position-left"></i>
                                <? } ?>
                                <span class="text-semibold"><?= Yii::$app->params['page_title'] ?></span>
                                <? if (isset(Yii::$app->params['page_small_title'])) { ?> <span class="text-light text-muted"><?= Yii::$app->params['page_small_title'] ?></span><? } ?>
                            </h1>
                        </div>
                        <?= $this->blocks['tx'] ?? '' ?>
                        <? if (isset($this->blocks['he'])) { ?>
                        <div class="heading-elements">
                        <?= $this->blocks['he'] ?? '' ?>
                        </div>
                        <? } // -he ?>

                        <? } // -t ?>
                    </div>
                    <? if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
                    <div class="breadcrumb-line">
                        <? if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                        <ul class="breadcrumb">
                            <li><a href="/"><?= Yii::t('nav', 'Home') ?></a></li>
                            <?php
                            foreach (Yii::$app->params['page_breadcrumbs'] as $item) {
                                if (!empty($item)) {
                                    if (!isset($item[1]) || true === $item[1]) { ?>
                            <li class="active"><?= $item[0] ?></li>
                                <? } else { ?>
                                    <? if (substr($item[1], 0, 1) != '#' && substr($item[1], 0, 1) != '@' && strpos($item[1], '//') === false) { ?>
                                        <? $item[1] = '@web/'.$item[1]; ?>
                                    <? } ?>
                            <li<?= isset($item[2]) && $item[2] === true ? ' class="active"' : '' ?>><?= Html::a($item[0], $item[1]) ?></li>
                                <?php
                                    }
                                }
                            } ?>
                        </ul>
                        <ul class="breadcrumb-elements">
<?php
if (isset(Yii::$app->params['page_actions']) && is_array(Yii::$app->params['page_actions'])) {
    foreach (Yii::$app->params['page_actions'] as $iBtnGroup) { ?>
        <?php
        foreach ($iBtnGroup as $iBtn) {
            if (!isset($iBtn['hidden']) || !$iBtn['hidden']) {
                if (isset($iBtn['submenu']) && is_array($iBtn['submenu'])) { ?>
                    <li style="border-right:1px solid #f3f3f3;">
                        <a style="padding:10px 5px;" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right"><?php
                    foreach ($iBtn['submenu'] as $i2Btn) {
                        if ($i2Btn == ['-']) { ?>
                            <li class="divider"></li><?php
                        } else {
                            if (!isset($i2Btn['hidden']) || !$i2Btn['hidden']) {
                                $i2BtnIcon = isset($i2Btn['icon']) ? '<i class="fa fa-fw fa-'.$i2Btn['icon'].'"></i> ' : '';
                                $i2BtnLabel = isset($i2Btn['label']) ? $i2Btn['label'] : '';
                                $i2BtnTitle = isset($i2Btn['title']) ? $i2Btn['title'] : '';
                                $i2BtnClass = isset($i2Btn['class']) ? $i2Btn['class'] : '';
                                if (isset($i2Btn['active']) && $i2Btn['active']) {
                                    $i2BtnClass .= ' active';
                                }
                                $i2BtnLink = isset($i2Btn['link']) ? $i2Btn['link'] : '#';
                                if (substr($i2BtnLink, 0, 1) != '#' && substr($i2BtnLink, 0, 5) != '@web/' && strpos($i2BtnLink, '//') === false) {
                                    $i2BtnLink = '@web/'.$i2BtnLink;
                                } ?>
                            <li><?= Html::a($i2BtnIcon.$i2BtnLabel, $i2BtnLink, ['class'=>$i2BtnClass, 'title'=>$i2BtnTitle]) ?></li><?php
                            }
                        } // if divider
                    } // foreach i2Btn ?>
                        </ul>
                    </li>
                    <?php
                } else {
                    $iBtnIcon = isset($iBtn['icon']) ? '<i class="fa fa-fw fa-'.$iBtn['icon'].'"></i> ' : '';
                    $iBtnLabel = isset($iBtn['label']) ? $iBtn['label'] : '';
                    $iBtnTitle = isset($iBtn['title']) ? $iBtn['title'] : '';
                    $iBtnClass = ' ';
                    $iBtnClass .= isset($iBtn['class']) ? $iBtn['class'] : '';
                    if (isset($iBtn['active']) && $iBtn['active']) {
                        $iBtnClass .= ' active text-primary';
                    }
                    $iBtnLink = isset($iBtn['link']) ? $iBtn['link'] : '#';
                    if (substr($iBtnLink, 0, 1) != '#' && substr($iBtnLink, 0, 5) != '@web/' && strpos($iBtnLink, '//') === false) {
                        $iBtnLink = '@web/'.$iBtnLink;
                    }

                    //echo Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]);
                    echo '<li>', Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]), '</li>';
                }// if submenu
            } // if not hidden iBtn
        } // foreach button
    } // foreach button group
}
?>
                        </ul>
                        <? } ?>
                        <?= $this->blocks['bx'] ?? '' ?>
                    </div>
                    <? } // -b ?>
                </div>
                <!-- /page header -->
                <? } // -h ?>

                <!-- Content area -->
                <div class="content">
                    <!--[if lt IE 9]><div class="hidden-print alert alert-warning"><i class="fa fa-warning"></i> You are using an <strong>outdated</strong> browser. Please <a rel="external" class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div><![endif]-->
                    <?php
                    foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
                        if (Yii::$app->session->hasFlash($key)) { ?>
                    <div class="hidden-print alert alert-<?= $key ?>"><?= $message ?></div>
                    <?php
                        }
                    }
                    ?>

                    <? if (strpos(Yii::$app->params['page_layout'], 'sli') !== false) { ?>
                    <div class="sidebar-detached">
                        <?= $this->blocks['sli'] ?? '' ?>
                    </div>
                    <div class="container-detached">
                        <div class="content-detached">
                            <div class="row">
                                <?= $content ?>
                            </div>
                        </div>
                    </div>
                    <? } elseif (strpos(Yii::$app->params['page_layout'], 'sri') !== false) { ?>
                    <div class="container-detached">
                        <div class="content-detached">
                            <div class="row">
                                <?= $content ?>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-detached">
                        <?= $this->blocks['sri'] ?? '' ?>
                    </div>
                    <? } else { ?>
                    <div class="row">
                        <?= $content ?>
                    </div>
                    <? } ?>

                    <?= $this->blocks['f'] ?? '' ?>
                    <? if (strpos(Yii::$app->params['page_layout'], '-f') === false) { ?>
                    <div class="footer text-muted hidden-print">
                        <?= Yii::$app->name ?> version <?= Yii::$app->version ?> - &copy; 2007-2017 <a href="https://www.amica-travel.com?ref=ims_ft">Amica Travel</a>
                        <?= $this->blocks['fx'] ?? '' ?>
                    </div>
                    <? } // -f ?>
                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->
            <? if (strpos(Yii::$app->params['page_layout'], 'sro') !== false) { ?>
            <!-- Opposite sidebar -->
            <?= $this->blocks['sro'] ?? '' ?>
            <!-- /opposite sidebar -->
            <? } ?>
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
    <? $this->endBody() ?>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-1454717-46', 'auto');
    ga('send', 'pageview');
    </script>
    <div class="hidden-print" id="goTop" title="<?= Yii::t('app', 'Go to top of page') ?>"></div>
</body>
</html>
<? $this->endPage();
