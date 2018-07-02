<?
if (!function_exists('displayMenuItem')) {
	function displayMenuItem($item, $depth = 1, $li = true)
	{
		if (isset($item['hidden']) && $item['hidden']) {
			return '';
		}

		$html = '';
		$aAttr = [];
		$liAttr = [];
		$itemIcon = isset($item['icon']) ? '<i class="fa fa-fw fa-'.$item['icon'].'"></i>' : '';
		$itemLink = isset($item['link']) ? $item['link'] : 'javascript:;';
		if ($itemLink != 'javascript:;' && $itemLink != '#' && substr($itemLink, 0, 1) != '@' && strpos($itemLink, '//') === false) {
			$itemLink = '@web/'.$itemLink;
		}

		if ($li) {
			$html = '<li ';
			if (isset($item['id'])) {
				$html .= 'id="'.yii\helpers\Html::encode($item['id']).'"';
			}

			$class = '';
			if (isset($item['class'])) {
				$class = yii\helpers\Html::encode($item['class']);
			}
			if (isset($item['active']) && $item['active']) {
				$class .= ' active';
			}
			if (trim($class) != '') {
				$html .= 'class="'.trim($class).'" ';
			}

			if (isset($item['title'])) {
				$html .= 'title="'.yii\helpers\Html::encode($item['title']).'"';
			}
		}

		$itemLabel = isset($item['label']) ? Yii::t('nav', $item['label']) : '';
		if ($depth == 1) {
			$itemLabel = '<span class="title">'.$itemLabel.'</span>';
			if (isset($item['active']) && $item['active']) {
				$itemLabel .= '<span class="selected"></span>';
			}
			if ($li === false) {
				$itemLabel .= '<span class="arrow'.(isset($item['active']) && $item['active'] ? ' open' : '').'"></span>';
			}
		}

		if ($depth == 2) {
			$itemLabel = '<span class="title">'.$itemLabel.'</span>';
			if (isset($item['active']) && $item['active']) {
				$itemLabel .= '<span class="selected"></span>';
			}
			if ($li === false) {
				$itemLabel .= '<span class="arrow'.(isset($item['active']) && $item['active'] ? ' open' : '').'"></span>';
			}
		}

		$itemAttr = [];
		if (isset($item['target'])) {
			$itemAttr['target'] = $item['target'];
		}

		if ($li) {
			$html .= '>';
		}

		$html .= yii\helpers\Html::a($itemIcon.$itemLabel, $itemLink, $itemAttr);

		if ($li) {
			$html .= '</li>';
		}

		return $html;
	}
}

?>
		<div class="page-sidebar-wrapper">
			<div class="page-sidebar navbar-collapse collapse" id="side-nav">
				<ul class="page-sidebar-menu" data-keep-expanded="false" data-slide-speed="200">
					<li class="sidebar-toggler-wrapper">
						<div class="sidebar-toggler" style="margin-bottom:15px;"></div>
					</li>
					<li class="hidden-lg hidden-md sidebar-search-wrapper">
						<form class="sidebar-search" action="<?= DIR ?>search">
							<a href="javascript:;" class="remove"><i class="fa fa-times"></i></a>
							<div class="input-group">
								<input type="text" id="q2" name="q2" class="form-control" placeholder="Search..." autocomplete="off">
								<span class="input-group-btn"><a href="javascript:;" class="btn submit"><i class="fa fa-search"></i></a></span>
							</div>
							<div id="suggest2" class="search-suggest"></div>
						</form>
					</li><?
if (isset(Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']])) {
	$itemCnt = 0;
	foreach (Yii::$app->params['side_nav'][Yii::$app->params['side_nav_name']] as $item) {
		if ($item == ['-']) { ?>
					<li class="divider" style="height:41px;"></li><?
		} elseif (isset($item['heading'])) { ?>
					<li class="heading"><h3 class="uppercase"><?= $item['heading'] ?></h3></li><?
		} else {
			$itemCnt ++;
			if (isset($item['submenu'])) { ?>
					<li class="<?= isset($item['active']) && $item['active'] ? 'active open ' : '' ?><?= $itemCnt == 1 ? 'start' : '' ?>"><?
						echo displayMenuItem($item, 1, false); ?>
						<ul class="sub-menu"><?
				foreach ($item['submenu'] as $item2) {
					if (isset($item2['submenu'])) { ?>
							<li class="<?= isset($item2['active']) && $item2['active'] ? 'active open' : '' ?>"><?
								echo displayMenuItem($item2, 2, false); ?>
								<ul class="sub-menu"><?
									foreach ($item2['submenu'] as $item3) {
										echo displayMenuItem($item3, 3);
									} ?>
								</ul>
							</li><?
					} else {
						echo displayMenuItem($item2, 2);
					}
				} ?>
						</ul>
					</li><?
			} else {
				echo displayMenuItem($item, 1);
			} // isset submenu
		} // divider,heading,normal
	} // foreach current nav sidebar
} // if isset current nav sidebar
?>
				</ul>
			</div>
		</div>
