<?
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
include('_css_limitless.php');
include('_js_limitless.php');
include('../config/functions.php');

MainAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

if (strpos(Yii::$app->params['page_layout'], 'sidebar2') !== false) {
	Yii::$app->params['page_body_class'] .= ' sidebar-dual-visible';
}

if (strpos(Yii::$app->params['page_layout'], 'sidebar4') !== false) {
	Yii::$app->params['page_body_class'] .= ' sidebar-opposite-visible';
}

if (strpos(Yii::$app->params['page_layout'], 'sidebar3') !== false) {
	if (strpos(Yii::$app->params['page_layout'], 'sidebar3-right') !== false) {
		Yii::$app->params['page_body_class'] .= ' has-detached-right';
	} else {
		Yii::$app->params['page_body_class'] .= ' has-detached-left';
	}
}

// OLD IMS
Yii::$app->params['page_breadcrumbs'] = Yii::$app->params['page_breadcrumbs'] ?? $this->params['breadcrumb'] ?? null;
Yii::$app->params['page_actions'] = Yii::$app->params['page_actions'] ?? $this->params['actions'] ?? null;

$this->beginPage();

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= Yii::$app->params['page_meta_title'] ?> - Amica Travel IMS</title>
	<?= Html::csrfMetaTags() ?>
	<?= $this->head() ?>
</head>

<body class="<?= Yii::$app->params['body_class'] ?? '' ?>">
	<? if (strpos(Yii::$app->params['page_layout'], '-n') === false) { ?>
	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="/"><img src="/assets/img/logo-amica-for-ims.png" alt="Logo"></a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-options"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-options-vertical"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-menu"></i></a></li>
<?
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
				</li><?
} // if sections ?>
				<li>
					<form id="qs" action="/search" class="navbar-form navbar-left">
						<div class="form-group has-feedback">
							<input type="search" class="form-control" name="q" id="q" autocomplete="off" placeholder="<?= Yii::t('nav', 'Search') ?>">
							<div id="qx" class="form-control-feedback" style="pointer-events:auto; cursor:pointer;">
								<i id="qi" class="icon-magnifier text-size-base"></i>
							</div>
						</div>
						<div id="suggest" class="search-suggest"></div>
					</form>
				</li>
			</ul>

			<?= $this->blocks['nx'] ?? '' ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-link position-left"></i>
						<span class="hidden-sm"><?= Yii::t('nav', 'Links') ?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" id="dropdown-menu-links">
					<? foreach (Yii::$app->params['top_nav']['links'] as $item) {
						echo renderMenuItem($item);
						} ?>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-question position-left"></i>
						<span class="hidden-sm"><?= Yii::t('nav', 'Help') ?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" id="dropdown-menu-help">
					<? foreach (Yii::$app->params['top_nav']['help'] as $item) {
						echo renderMenuItem($item);
						} ?>
					</ul>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown"><?
	foreach (Yii::$app->params['top_nav']['lang'] as $item) {
		if ($item['code'] == Yii::$app->language) { ?>
						<?= Html::img('@web/assets/img/flags/16x11/'.$item['flag'].'.png', ['alt'=>$item['code'], 'class'=>'position-left']) ?>
						<span class="hidden-sm"><?= $item['name'] ?></span>
						<span class="caret"></span><?
			break;
		}
	} ?>
					</a>

					<ul class="dropdown-menu">
						<li><a class="english" href="/select/lang/en"><img src="/assets/img/flags/16x11/us.png" alt=""> English</a></li>
						<li><a class="french" href="/select/lang/fr"><img src="/assets/img/flags/16x11/fr.png" alt=""> Français</a></li>
						<li><a class="vietnamese" href="/select/lang/vi"><img src="/assets/img/flags/16x11/vn.png" alt=""> Tiếng Việt</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img class="img-circle" style="width:28px; height:28px; margin:-5px 7px -5px 0;" src="/timthumb.php?w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" alt="Avatar">
						<span class="hidden-sm"><?= Yii::$app->user->identity->name ?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<?
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
									<span class="media-heading text-semibold"><?= Yii::$app->user->identity->name ?></span>
									<div class="text-size-mini text-muted">
										<?= Yii::$app->user->identity->profileMember->location ?>
									</div>
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="/me"><i class="icon-settings"></i></a>
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
							<?
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

			<!-- Main content -->
			<div class="content-wrapper">
				<!--[if lt IE 8]><div class="alert alert-danger"><i class="fa fa-warning"></i> You are using an <strong>outdated</strong> browser. Please <a rel="external" class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div><![endif]-->
				<?
				foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
					if (Yii::$app->session->hasFlash($key)) { ?>
				<div class="alert alert-<?= $key ?>"><?= $message ?></div>
				<?
					}
				}
				?>
				<?= $this->blocks['h'] ?? '' ?>
				<? if (strpos(Yii::$app->params['page_layout'], '-h') === false) { ?>
				<!-- Page header -->
				<div class="page-header<?= strpos(Yii::$app->params['page_layout'], 'header_no_margin') !== false ? ' no-margin' : '' ?>">
					<div class="page-header-content">
						<?= $this->blocks['t'] ?? '' ?>
						<? if (strpos(Yii::$app->params['page_layout'], '-t') === false) { ?>
						<div class="page-title">
							<h1>
								<i class="fa fa-<?= Yii::$app->params['page_icon'] ?? 'arrow-circle-o-right' ?> position-left"></i>
								<span class="text-semibold"><?= Yii::$app->params['page_title'] ?></span>
								<? if (isset(Yii::$app->params['page_small_title'])) { ?> <span class="text-light"><?= Yii::$app->params['page_small_title'] ?></span><? } ?>
							</h1>
						</div>
						<?= $this->blocks['tx'] ?? '' ?>
						<? // ACTIONS
						if (isset(Yii::$app->params['page_actions']) && is_array(Yii::$app->params['page_actions'])) { ?>
						<div class="heading-elements">
<div class="btn-toolbar page-actions hidden-print"><?
	foreach (Yii::$app->params['page_actions'] as $iBtnGroup) { ?>
					<div class="btn-group heading-btn btn-group-sm"><?
		foreach ($iBtnGroup as $iBtn) {
			if (!isset($iBtn['hidden']) || !$iBtn['hidden']) {
				if (isset($iBtn['submenu']) && is_array($iBtn['submenu'])) { ?>
						<a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu pull-right"><?
					foreach ($iBtn['submenu'] as $i2Btn) {
						if ($i2Btn == ['-']) { ?>
							<li class="divider"></li><?
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
							<li><?= Html::a($i2BtnIcon.$i2BtnLabel, $i2BtnLink, ['class'=>$i2BtnClass, 'title'=>$i2BtnTitle]) ?></li><?
							}
						} // if divider
					} // foreach i2Btn ?>
						</ul><?
				} else {
					$iBtnIcon = isset($iBtn['icon']) ? '<i class="fa fa-fw fa-'.$iBtn['icon'].'"></i> ' : '';
					$iBtnLabel = isset($iBtn['label']) ? $iBtn['label'] : '';
					$iBtnTitle = isset($iBtn['title']) ? $iBtn['title'] : '';
					$iBtnClass = 'btn btn-default btn-sm ';
					$iBtnClass .= isset($iBtn['class']) ? $iBtn['class'] : '';
					if (isset($iBtn['active']) && $iBtn['active']) {
						$iBtnClass .= ' active';
					}
					$iBtnLink = isset($iBtn['link']) ? $iBtn['link'] : '#';
					if (substr($iBtnLink, 0, 1) != '#' && substr($iBtnLink, 0, 5) != '@web/' && strpos($iBtnLink, '//') === false) {
						$iBtnLink = '@web/'.$iBtnLink;
					}

					echo Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]);
				}// if submenu
			} // if not hidden iBtn
		} // foreach button ?>
					</div><?
	} // foreach button group ?>
				</div><!-- page_actions -->
						</div>
						<? } // if page_actions ?>
						
						<? } // -t ?>
					</div>
					<? if (strpos(Yii::$app->params['page_layout'], '-b') === false) { ?>
					<div class="breadcrumb-line">
						<? if (isset(Yii::$app->params['page_breadcrumbs']) && is_array(Yii::$app->params['page_breadcrumbs'])) { ?>
						<ul class="breadcrumb">
							<li><a href="/"><?= Yii::t('nav', 'Home') ?></a></li>
							<? foreach (Yii::$app->params['page_breadcrumbs'] as $item) { ?>
								<? if (!isset($item[1]) || true === $item[1]) { ?>
							<li class="active"><?= $item[0] ?></li>
								<? } else { ?>
									<? if (substr($item[1], 0, 1) != '#' && substr($item[1], 0, 1) != '@' && strpos($item[1], '//') === false) { ?>
										<? $item[1] = '@web/'.$item[1]; ?>
									<? } ?>
							<li<?= isset($item[2]) && $item[2] === true ? ' class="active"' : '' ?>><?= Html::a($item[0], $item[1]) ?></li>
								<? } ?>
							<? } ?>
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
					<div class="row">
						<?= $content ?>
					</div>

					<?= $this->blocks['f'] ?? '' ?>
					<? if (strpos(Yii::$app->params['page_layout'], '-f') === false) { ?>
					<!-- Footer -->
					<div class="footer text-muted hidden-print pt-20">
						<?= Yii::$app->name ?> version <?= Yii::$app->version ?> - &copy; 2007-2016 <a href="https://www.amica-travel.com?ref=ims_ft">Amica Travel</a>
						<?= $this->blocks['fx'] ?? '' ?>
					</div>
					<!-- /footer -->
					<? } // -f ?>
				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->


			<!-- Opposite sidebar -->

			<!-- /opposite sidebar -->

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
</body>
</html>
<? $this->endPage();
