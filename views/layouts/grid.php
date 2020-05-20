<?php
use yii\helpers\Html;
use app\assets\GridAsset as MainAsset;

include('_nav.php');
// include('_css_limitless.php');
// include('_css_theadmin.php');
// include('_js.php');
// include('_js_theadmin.php');
include('../config/functions.php');

MainAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

Yii::$app->params['body_class'] = Yii::$app->params['body_class'] ?? '';

// OLD IMS
Yii::$app->params['page_breadcrumbs'] = Yii::$app->params['page_breadcrumbs'] ?? $this->params['breadcrumb'] ?? null;
Yii::$app->params['page_actions'] = Yii::$app->params['page_actions'] ?? $this->params['actions'] ?? null;

if (isset(Yii::$app->params['page_js'])) {
    $this->registerJs(Yii::$app->params['page_js']);
}
if (isset(Yii::$app->params['page_css'])) {
    $this->registerCss(Yii::$app->params['page_css']);
}

$this->beginPage();

?><!doctype html>
<html lang="vi">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Yii::$app->params['page_meta_title'] ?> | amica.ims</title>
    <?= Html::csrfMetaTags() ?>
    <!-- <link rel="stylesheet" href="https://my.amicatravel.com/assets/simple-line-icons_2.4.0/css/simple-line-icons.css"> -->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://my.amicatravel.com/themes/limitless_2.1.0/layout_2/LTR/default/full/assets/css/colors.min.css">
 -->    
    <?php $this->head(); ?>
    <style>
*,
*::before,
*::after {
  box-sizing: border-box;
}

body {
  height: 100%;
  overflow-x: hidden;
  font-family: Roboto, sans-serif;
}

.my-g {display:grid}
#my-wrapper {display:grid;
    height: 100vh;
    grid-template-rows: 60px 1fr;
    grid-template-columns: 280px 1fr;
    grid-template-areas: "header header" "nav main";
}

#my-header {grid-template-columns:minmax(min-content, max-content) 30px 30px 30px 1fr 30px minmax(min-content, max-content) 30px;
    grid-area: header;
    border-bottom:1px solid #485e9029;
    align-items: center;
    /*position:sticky;*/
    width:100%;
    grid-column-gap:6px;

}
    #my-header > a, #my-header > div > a {display:inline-block; text-decoration:none; color:#eee;}
    #my-header > a:hover, #my-header > div > a:hover {color:#fff}
        #my-header-brand {height:50px; display:grid; grid-template-columns:50px 1fr; grid-column-gap:6px; -background-color:#3f51b5; align-items:center;}
            #my-header-brand-logo {height:32px; margin-left:4px; }
            #my-header-brand-name {font-size:24px;}
    #my-header .show .dropdown-menu {z-index:999999; margin-top:10px!important;}
#my-nav {grid-area: nav;
    position:relative;
    overflow-x:scroll;
    border-right:1px solid #485e9029;
    /*background-color:#e8eaf6;*/
    /*color:#333333d9;*/
}
    #my-nav .nav {display:block;}
    #my-nav li.nav-item {display:block; margin-left:20px;}
    .nav-header {height:50px; background-color:#000; color:#fff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.35);}
#my-page-breadcrumb {padding:20px 20px 10px 20px; font-size:14px;}
    #my-page-breadcrumb a {color:#777;}
#my-page-title {font-size:24px; line-height:28px; padding:10px 20px;}
    #my-page-main-title {font-weight:500;}
    #my-page-small-title {font-weight:200;}
    #my-page-sub-title {}
#my-page-body {padding:20px;}


#my-page {grid-area: main;
    overflow-y:scroll;
}
footer {grid-area: footer;
    height:50px;
    border-top:1px solid #ccc;
    padding:0 20px;
    display:grid;
    grid-template-columns:auto auto;
    align-items: center;
    justify-content: space-between;
}
@media (min-width: 1025px) and (max-width: 1280px) {
    #my-wrapper {
        grid-template-columns: 50px 1fr;
    }
    #my-nav ul {display:none;}
    #my-header {left:50px;}
}
@media (min-width: 768px) and (max-width: 1024px) {
    #my-wrapper {
        grid-template-columns: 0 1fr;
    }
    #my-header {left:0px;}
    #my-nav {
        z-index:99999;
        transform: translate3d(-280px, 0px, 0px);
        position: absolute;
        width: 280px;
        background: #263249;
        color: #eee;
        left: 0;
        height: 100%;
        transition: all .3s;
    }
    .navopen #my-nav {
        transform: translate3d(0px, 0px, 0px);
        width:280px;
    }
}
@media (max-width: 767px) {
    #my-wrapper {
        grid-template-columns: 0 1fr;
    }
    #my-header {left:0px;}
}
.post-header {font-size:17px;}
.post-avatar {width:64px; height:64px;}
    </style>
</head>
<body>
    <?php $this->beginBody(); ?>
    <article id="my-wrapper">
        <header id="my-header" class="my-g bg-indigo">
            <div id="my-header-brand">
                <img id="my-header-brand-logo" alt="Logo" src="https://my.amicatravel.com/assets/img/logo_165x128_c.png">
                <span id="my-header-brand-name">
                    <strong class="text-blue">ims</strong>
                    <span class="text-blue mr-2">/workspace</span>
                </span>
            </div>
            <a href="#" class="action-toggle-nav"><i class="fa fa-bars" style="font-size:24px"></i>
            <a href="#"><i class="fa fa-th-large" style="font-size:24px"></i></a>
            <a href="/search"><i class="fa fa-search" style="font-size:24px;"></i></a>
            <div class="alpha-indigo" style="display:inline-block; line-height:38px; margin:6px 0; padding:0 6px; height:38px; -background-color:#eee;">Search...</div>
            <div style="display: inline-block;">
                <a href="#" data-toggle="dropdown"><i class="fa fa-list" style="font-size:24px;"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="/hotels"><i class="slicon-home"></i> Hotels</a>
                    <a class="dropdown-item" href="/homestays"><i class="slicon-home"></i> Homestays</a>
                    <a class="dropdown-item" href="/venues/homestay-calendar"><i class="slicon-home"></i> --- Homestay calendar</a>
                    <a class="dropdown-item" href="/ref/halongcruises"><i class="slicon-anchor"></i> Cruises</a>
                    <a class="dropdown-item" href="/ref/ssspots"><i class="fa fa-truck"></i> Sightseeing</a>
                    <a class="dropdown-item" href="/venues?type=restaurant&amp;destination_id=1"><i class="fa fa-coffee"></i> Restaurants</a>
                    <a class="dropdown-item" href="/ref/tables"><i class="fa fa-table"></i> Other price tables</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/tours?orderby=startdate"><i class="fa fa-car"></i> Tours starting this month</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/members"><i class="fa fa-font"></i> Amica members</a>
                </div>
            </div>
            <div style="display:inline-block;">
                <a href="#" data-toggle="dropdown">
                    <i class="fa fa-user" style="font-size:24px;"></i>
                    <span><?= Yii::$app->user->identity->name ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/me/profile" class="dropdown-item"><i class="slicon-user"></i> Profile của tôi</a>
                    <a href="/me/my-settings/password" class="dropdown-item"><i class="slicon-key"></i> Đổi mật khẩu</a>
                    <a href="/me/my-settings/preferences" class="dropdown-item"><i class="slicon-settings"></i> Tuỳ chọn</a>
                    <a href="/me/work-calendar" class="dropdown-item"><i class="slicon-calendar"></i> Work calendar</a>
                    <div class="dropdown-divider"></div>
                    <a href="/tasks" class="dropdown-item"><i class="slicon-check"></i> Nhiệm vụ của tôi</a>
                    <a href="/mails" class="dropdown-item"><i class="slicon-envelope"></i> Email của tôi</a>
                    <a href="/posts" class="dropdown-item"><i class="slicon-notebook"></i> Note của tôi</a>
                    <a href="/me/my-reports" class="dropdown-item"><i class="slicon-pie-chart"></i> Báo cáo của tôi</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item english" href="/select/lang/en"><i class="flag-icon flag-icon-us"></i> <span>English</span></a>
                    <a class="dropdown-item french" href="/select/lang/fr"><i class="flag-icon flag-icon-fr"></i> <span>Français</span></a>
                    <a class="dropdown-item vietnamese" href="/select/lang/vi"><i class="flag-icon flag-icon-vn"></i> <span>Tiếng Việt</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item"><i class="slicon-power"></i> Đăng xuất</a>
                </div>
            </div>
            <a href="#"><i class="fa fa-ellipsis-v" style="font-size:24px;"></i></a>
        </header>
        <nav id="my-nav" class="alpha-indigo">
            <div id="nav-main">
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
        </nav>
        <main id="my-page">
            <?php if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
            <div id="my-page-breadcrumb" class="d-print-none">
                <?php if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
                <a class="breadcrumb-item py-0" href="/"><i class="fa fa-home"></i></a><?php
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
            <?php if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
            <div id="my-page-title">
                <span id="my-page-main-title"><?= Yii::$app->params['page_title'] ?></span>
                <?php if (Yii::$app->params['page_small_title']) { ?>
                <span id="my-page-small-title"><?= Yii::$app->params['page_small_title'] ?></span>
                <?php } ?>
            </div>
            <?php } //-t ?>

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

            <?php if (!empty($this->blocks['page_tabs'])) { ?>
            <div class="my-page-header-2 bg-light">
                <?= $this->blocks['page_tabs'] ?? '' ?>
            </div>
            <?php } else { ?>
            <hr>
            <?php } ?>

            <div id="my-page-body">
                <div class="row">
                <?php
                echo $content;
                ?>
                </div>
            </div>
            <footer id="my-footer">
                <div>IMS 2019.2 &copy; 2007 Amica Travel.</div>
                <div>
                    <a href="#">About</a>
                    &middot;
                    <a href="#">Help</a>
                    &middot;
                    <a href="#">Contact</a>
                </div>
            </footer>
        </main>
        <nav id="my-right-nav" style="display:none;">
            This is the right nav
        </nav>
    </article>
    <?php $this->endBody(); ?>
    <script>
$(function(){
$('.action-toggle-nav').on('click', function(e){
    e.preventDefault()
    $('body').toggleClass('navopen')
})

const ps = new PerfectScrollbar('#my-nav', {
  wheelSpeed: 2,
  wheelPropagation: true,
  minScrollbarLength: 20
});

})
    </script>
</body>
</html>
<?php $this->endPage();

