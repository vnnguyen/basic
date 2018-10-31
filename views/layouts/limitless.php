<?php
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
if (strpos(Yii::$app->params['page_layout'], 'slo') !== false || strpos(Yii::$app->params['page_layout'], 'sro') !== false || strpos(Yii::$app->params['page_layout'], '.s') !== false) {
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

if (isset(Yii::$app->params['js'])) {
    $this->registerJs(Yii::$app->params['js']);
}

$this->beginPage();

?><!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <title><?= Yii::$app->params['page_meta_title'] ?> - Amica Travel IMS</title>
    <?= Html::csrfMetaTags() ?>

    <!-- Global stylesheets -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->
    <link href="/themes/limitless_2/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/themes/limitless_2/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/themes/limitless_2/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="/themes/limitless_2/assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <!-- <link href="/themes/limitless_2/assets/css/components.min.css" rel="stylesheet" type="text/css"> -->
    <link href="https://my.amicatravel.com/assets/l2/layout_1/LTR/default/full/assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="/themes/limitless_2/assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <?= $this->head() ?>
    <style type="text/css">
    .navbar-brand {min-width:0!important; padding-top:8px; padding-bottom: 8px; margin-right:0;}
    .navbar-brand img {height:32px!important;}
    .breadcrumb-line {border:0!important;}
    .breadcrumb-line-light {background-color: #fff}
    .page-title {padding-top:0; padding-bottom:16px;}
    .content {font-size:16px}
    .content .form-control {font-size:16px}
    .content .form-inline.pb-3 .form-control, .content .form-inline.pb-3 button {margin-right:2px;}
    .navbar-collapse {margin: 0;}
    </style>
</head>

<body class="<?= Yii::$app->params['body_class'] ?>">
    <?php $this->beginBody(); ?>
    <!-- Main navbar -->
    <div class="navbar navbar-expand-md navbar-dark px-3 px-sm-0">
        <div class="navbar-brand">
            <a href="/" class="d-inline-block">
                <img src="https://my.amicatravel.com/assets/img/logo_396x128_c.png" alt="">
            </a>
        </div>


        <div class="d-md-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="slicon-options-vertical"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="slicon-options"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="slicon-menu"></i>
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-apps">
                    <a href="#" class="navbar-nav-link" data-toggle="dropdown">
                        <i class="slicon-grid"></i>
                        <span class="-d-none -d-xs-block">Apps</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left -dropdown-grid">
                        <a class="dropdown-item" href="/">
                            <i class="slicon-home -fs-24"></i>
                            <span class="title">Dashboard</span>
                        </a>
                        <a class="dropdown-item" href="/">
                            <i class="slicon-handbag -fs-24"></i>
                            <span class="title">B2C</span>
                        </a>
                        <a class="dropdown-item" href="/b2b">
                            <i class="slicon-briefcase -fs-24"></i>
                            <span class="title">B2B</span>
                        </a>
                        <a class="dropdown-item" href="/customers">
                            <i class="slicon-people -fs-24"></i>
                            <span class="title">Khách hàng</span>
                        </a>
                        <a class="dropdown-item" href="/members">
                            <i class="slicon-organization -fs-24"></i>
                            <span class="title">Company</span>
                        </a>
                        <a class="dropdown-item" href="/tours">
                            <i class="slicon-directions -fs-24"></i>
                            <span class="title">Operation</span>
                        </a>
                        <a class="dropdown-item" href="/products">
                            <i class="slicon-diamond -fs-24"></i>
                            <span class="title">Products</span>
                        </a>
                        <a class="dropdown-item" href="/dv">
                            <i class="slicon-credit-card -fs-24"></i>
                            <span class="title">Purchasing</span>
                        </a>
                        <a class="dropdown-item" href="/cpt">
                            <i class="slicon-calculator -fs-24"></i>
                            <span class="title">Accounting</span>
                        </a>
                    </div>
                </li>
                <li style="min-width:320px;">
                    <form id="qs" action="/search" class="navbar-form navbar-left" style="width:100%;">
                        <div class="form-group has-feedback" style="width:100%; margin:6px 0 0 0;">
                            <select id="livesearch" style="width:100%;">
                                <option value="" selected="selected"><?= Yii::t('nav', 'Search') ?></option>
                            </select>
                        </div>
                        <div id="suggest" class="search-suggest"></div>
                    </form>
                </li>

                <li class="nav-item">
                    <a href="#" class="navbar-nav-link">
                        <i class="slicon-magnifier"></i>
                        <span class="d-none d-xs-block">Search</span>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
<!--                 <li class="nav-item">
                    <a href="#" class="navbar-nav-link">
                        Text link
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link">
                        <i class="icon-bell2"></i>
                        <span class="d-md-none ml-2">Notifications</span>
                        <span class="badge badge-mark border-white ml-auto ml-md-0"></span>
                    </a>
                </li>
 -->
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link">
                        <i class="slicon-question"></i>
                        <span class="d-none d-xs-block">Help</span>
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-user">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle" alt="">
                        <span><?= Yii::$app->user->identity->nickname ?></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="/me/profile" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-user"></i> Thông tin về tôi</a>
                        <a href="/me/my-settings/change-password" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-key"></i> Đổi mật khẩu</a>
                        <a href="/me/my-settings/preferences" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-settings"></i> Các tuỳ chọn</a>
                        <div class="dropdown-divider"></div>
                        <a href="/tasks" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-check"></i> Nhiệm vụ</a>
                        <a href="/mails" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-envelope"></i> Email của tôi</a>
                        <a href="/notes" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-notebook"></i> Ghi chú của tôi</a>
                        <a href="/me/reports" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-pie-chart"></i> Báo cáo của bán hàng</a>
                        <div class="dropdown-divider"></div>
                        <a href="/logout" a="" class="dropdown-item"><i style="font-size:16px;" class="slicon-power"></i> Đăng xuất</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link">
                        <i class="slicon-menu"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->


    <!-- Page content -->
    <div class="page-content">

        <?php if (strpos(Yii::$app->params['page_layout'], '-s') === false) { ?>
        <div class="sidebar sidebar-light sidebar-main sidebar-expand-md">

            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="slicon-arrow-left"></i>
                </a>
                Navigation
                <a href="#" class="sidebar-mobile-expand">
                    <i class="slicon-size-fullscreen"></i>
                    <i class="slicon-size-actual"></i>
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
                                    <i class="icon-pin font-size-sm"></i> Hanoi, Vietnam
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
                // Yii::$app->params['side_nav_name'] = 'travel';
                if (isset(Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']])) {
                    Yii::$app->params['side_nav']['main'] = Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']];
                    foreach (Yii::$app->params['side_nav']['main'] as $item) {
                        echo renderLimitlessMainNavItem($item);
                    }
                }
                ?>
                    </ul>
                </div>
                <!-- /main navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <?php } // -s ?>

        <!-- Main content -->
        <div class="content-wrapper">

            <?php if (strpos(Yii::$app->params['page_layout'], '-h') === false) { ?>
            <div class="page-header page-header-light">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <?php if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
                    <?php if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                    <div class="d-flex">
                        <div class="breadcrumb breadcrumb-caret">
                            <a class="breadcrumb-item" href="/"><?= Yii::t('nav', 'Home') ?><?php
                        foreach (Yii::$app->params['page_breadcrumbs'] as $item) {
                            if (!empty($item)) {
                                if (!isset($item[1]) || true === $item[1]) { ?>
                            <span class="breadcrumb-item active"><?= $item[0] ?></span><?php
                            } else {
                                if (substr($item[1], 0, 1) != '#' && substr($item[1], 0, 1) != '@' && strpos($item[1], '//') === false) {
                                    $item[1] = '@web/'.$item[1];
                                } ?>
                            <a class="breadcrumb-item<?= isset($item[2]) && $item[2] === true ? 'active' : '' ?>" href="<?= str_replace('@web', '', $item[1]) ?>"><?= $item[0] ?></a><?php
                                }
                            }
                        } ?>
                        </div>

                        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                    </div>
                    <?php } // isset b ?>
                <?php } // -b ?>
                    <div class="header-elements d-none">
                        <div class="breadcrumb justify-content-center">

               <?php
if (isset(Yii::$app->params['page_actions']) && is_array(Yii::$app->params['page_actions'])) { ?>
                <ul class="nav nav-pills"><?php
    foreach (Yii::$app->params['page_actions'] as $iBtnGroup) { ?>
        <?php
        foreach ($iBtnGroup as $iBtn) {
            if (!isset($iBtn['hidden']) || !$iBtn['hidden']) {
                if (isset($iBtn['submenu']) && is_array($iBtn['submenu'])) { ?>
                    <li class="nav-item">
                        <span class="dropdown-toggle" data-toggle="dropdown" style="margin-left:4px;"></span>
                        <ul class="dropdown-menu dropdown-menu-right"><?php
                    foreach ($iBtn['submenu'] as $i2Btn) {
                        if ($i2Btn == ['-']) { ?>
                            <li class="divider"></li><?php
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
                                $i2BtnClass = $i2Btn['class'] ?? 'dropdown-item';
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
                    </li>
                    <?php
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
                        $iBtnClass .= ' active text-primary';
                    }
                    $iBtnLink = isset($iBtn['link']) ? $iBtn['link'] : '#';
                    if (substr($iBtnLink, 0, 1) != '#' && substr($iBtnLink, 0, 5) != '@web/' && strpos($iBtnLink, '//') === false) {
                        $iBtnLink = '@web/'.$iBtnLink;
                    }

                    //echo Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]);
                    ?><li class="nav-item"><?= Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle, 'style'=>'padding:0 4px']) ?></li><?php
                }// if submenu
            } // if not hidden iBtn
        } // foreach button
    } // foreach button group ?>
                </ul><?php
} ?>

                        </div>
                    </div>
                </div>

                <?php if (strpos(Yii::$app->params['page_layout'], '-t') === false) { ?>
                <div class="page-header-content header-elements-md-inline">
                    <div class="page-title d-flex">
                        <h2>
                            <!-- <i class="slicon-arrow-left-circle mr-2"></i> -->
                            <span class="font-weight-semibold"><?= Yii::$app->params['page_title'] ?></span>
                            <?= Yii::$app->params['page_small_title'] ?? '' ?>
                        </h2>
                        <!-- <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a> -->
                    </div>
                    <!-- <div class="header-elements d-none">
                    </div> -->
                </div>
                <?php } // -t ?>
            </div>
            <? } // -h ?>


            <!-- Content area -->
            <div class="page-content content">
                <?= $content ?>
            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->
    <?php $this->endBody(); ?>
    <div class="hidden-print" id="goTop" title="<?= Yii::t('app', 'Go to top of page') ?>"></div>
</body>
</html>
<?php $this->endPage();

