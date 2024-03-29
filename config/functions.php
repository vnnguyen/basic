<?php
use yii\helpers\Html;

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return rtrim(rtrim($str, '0'), '.');
    }
}

if (!function_exists('renderMenuItem')) {
    /*
     * renders li or a tag of a menu item
     * returns the HTML tag
     * item: id, title, class, active, label, (a)target,a title, a id, link, html
     */
    function renderMenuItem($item, $tag = 'li')
    {
        if (isset($item['hidden']) && $item['hidden']) {
            return '';
        }

        // html = render raw html
        if (isset($item['html'])) {
            return $item['html'];
        }

        if ($item == ['-'] && $tag == 'li') {
            return '<li class="divider menu-divider"></li>';
        }

        $html = '';

        $aLabel = isset($item['label']) ? $item['label'] : '';
        if (isset($item['icon'])) {
            $aLabel = '<i style="font-size:16px;" class="slicon-'.Html::encode($item['icon']).'"></i> '.Html::encode($aLabel);
        }
        $aLink = isset($item['link']) ? $item['link'] : '';
        if (substr($aLink, 0, 1) != '@' && substr($aLink, 0, 1) != '#' && strpos($aLink, '//') === false) {
            $aLink = '@web/'.$aLink;
        }
        $aArgs = [];
        foreach (['target', 'a target', 'a class', 'a id', 'a title', 'a rel'] as $arg) {
            if (isset($item[$arg])) {
                $aArgs[str_replace('a ', '', $arg)] = Html::encode($item[$arg]);
            }
        }
        $html = Html::a($aLabel, $aLink, $aArgs);

        if ($tag == 'li') {
            $liAttr = '';
            if (isset($item['id'])) {
                $liAttr .= 'id="'.Html::encode($item['id']).'" ';
            }
            if (isset($item['title'])) {
                $liAttr .= 'title="'.Html::encode($item['title']).'" ';
            }
            // Active = class="active"
            if (isset($item['active']) && $item['active']) {
                $item['class'] = isset($item['class']) ? $item['class'].' active' : 'active';
            }
            if (isset($item['class'])) {
                $liAttr .= 'class="'.Html::encode($item['class']).'" ';
            }

            $html = '<li '.$liAttr.'>'.$html.'</li>';
        }

        return $html;
    }
}

if (!function_exists('renderBs4MenuItem')) {
    /*
     * renders li or a tag of a menu item
     * returns the HTML tag
     * item: id, title, class, active, label, (a)target,a title, a id, link, html
     */
    function renderBs4MenuItem($item)
    {
        if (isset($item['hidden']) && $item['hidden']) {
            return '';
        }

        // html = render raw html
        if (isset($item['html'])) {
            return $item['html'];
        }

        if ($item == ['-']) {
            return '<div class="dropdown-divider"></div>';
        }

        $html = '';

        $aLabel = isset($item['label']) ? $item['label'] : '';
        if (isset($item['icon'])) {
            $aLabel = '<i style="font-size:16px;" class="slicon-'.Html::encode($item['icon']).'"></i> '.Html::encode($aLabel);
        }
        $aLink = isset($item['link']) ? $item['link'] : '';
        if (substr($aLink, 0, 1) != '@' && substr($aLink, 0, 1) != '#' && strpos($aLink, '//') === false) {
            $aLink = '@web/'.$aLink;
        }
        $aArgs = [
            'a class'=>'dropdown-item',
        ];
        foreach (['target', 'a target', 'a class', 'a id', 'a title', 'a rel'] as $arg) {
            if (isset($item[$arg])) {
                $aArgs[str_replace('a ', '', $arg)] = Html::encode($item[$arg]);
            }
        }
        $html = Html::a($aLabel, $aLink, $aArgs);

        return $html;
    }
}


if (!function_exists('renderTopnavDropdown')) {
    function renderTopnavDropdown($menu)
    {
        $html = '';
        $html = '<li class="dropdown" id="{{id}}">
    <a href="{{link}}" class="dropdown-toggle" data-toggle="dropdown">
        {{icon}}
        <span class="{{class}}">{{label}}</span>
        {{caret}}
    </a>
    {{dropdown}}
    <ul class="dropdown-menu" id="dropdown-menu-help">
        foreach (Yii :: $ app -> params [ top_nav ][ help ] as $ item) {
            echo renderMenuItem($ item);
        }
    </ul><span class="caret"></span>
</li>';
        $icon = '';
        if (isset($menu['icon'])) {
            $icon = '<i style="font-size:16px;" class="slicon-'.Html::encode($menu['icon']).'"></i>';
        }
        if (isset($menu['icon'])) {
            $icon = '<i style="font-size:16px;" class="slicon-'.Html::encode($menu['icon']).'"></i>';
        }
        return str_replace(['$icon'], [$icon], $html);
    }
}

if (!function_exists('renderPageActionsButtons')) {
    function renderPageActionsButtons()
    {
        $html = '';
        if (!empty(Yii::$app->params['page_actions'])) {
            $html =  '<div class="btn-toolbar page-actions hidden-print">';
            foreach (Yii::$app->params['page_actions'] as $iBtnGroup) {
                $html .= '<div class="btn-group btn-group-sm">';
                foreach ($iBtnGroup as $iBtn) {
                    if (!isset($iBtn['hidden']) || !$iBtn['hidden']) {
                        $iBtnIcon = isset($iBtn['icon']) ? '<i style="font-size:16px;" class="slicon-'.$iBtn['icon'].'"></i> ' : '';
                        $iBtnLabel = isset($iBtn['label']) ? $iBtn['label'] : '';
                        $iBtnTitle = isset($iBtn['title']) ? $iBtn['title'] : '';
                        $iBtnClass = 'btn btn-default btn-sm ';
                        $iBtnClass .= isset($iBtn['class']) ? $iBtn['class'] : '';
                        if (isset($iBtn['active']) && $iBtn['active']) {
                            $iBtnClass .= ' active';
                        }
                        $iBtnLink = isset($iBtn['link']) ? $iBtn['link'] : '#';
                        $iBtnLink = str_replace('@web/', '', $iBtnLink);
                        if (substr($iBtnLink, 0, 1) != '@' && substr($iBtnLink, 0, 1) != '#' && strpos($iBtnLink, '//') === false) {
                            $iBtnLink = '@web/'.$iBtnLink;
                        }

                        $html .= Html::a($iBtnIcon.$iBtnLabel, $iBtnLink, ['class'=>$iBtnClass, 'title'=>$iBtnTitle]);
                        if (isset($iBtn['submenu']) && is_array($iBtn['submenu'])) {
                            $html .= '<a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a><ul class="dropdown-menu pull-right">';
                            foreach ($iBtn['submenu'] as $i2Btn) {
                                if ($i2Btn == ['-']) {
                                    $html .= '<li class="divider"></li>';
                                } else {
                                    if (!isset($i2Btn['hidden']) || !$i2Btn['hidden']) {
                                        $i2BtnIcon = isset($i2Btn['icon']) ? '<i style="font-size:16px;" class="slicon-fw fa-'.$i2Btn['icon'].'"></i> ' : '';
                                        $i2BtnLabel = isset($i2Btn['label']) ? $i2Btn['label'] : '';
                                        $i2BtnTitle = isset($i2Btn['title']) ? $i2Btn['title'] : '';
                                        $i2BtnClass = isset($i2Btn['class']) ? $i2Btn['class'] : '';
                                        if (isset($i2Btn['active']) && $i2Btn['active']) {
                                            $i2BtnClass .= ' active';
                                        }
                                        $i2BtnLink = isset($i2Btn['link']) ? $i2Btn['link'] : '#';
                                        $i2BtnLink = str_replace('@web/', '', $i2BtnLink);
                                        if (strpos($i2BtnLink, '//') === false && $i2BtnLink != '#') {
                                            $i2BtnLink = '@web/'.$i2BtnLink;
                                        }
                                        $html .= '<li>'.Html::a($i2BtnIcon.$i2BtnLabel, $i2BtnLink, ['class'=>$i2BtnClass, 'title'=>$i2BtnTitle]).'</li>';
                                    }
                                } // if divider
                            } // foreach i2Btn
                            $html .= '</ul>';
                        } // if submenu
                    } // if not hidden iBtn
                } // foreach button
                $html .= '</div>';
            } // foreach button group
            $html .= '</div><!-- page_actions -->';
        } // if page actions
        return $html;
    }
}

if (!function_exists('renderMainNavItem')) {
    function renderMainNavItem($item, $depth = 1, $tag = 'li')
    {
        if (isset($item['hidden']) && $item['hidden']) {
            return '';
        }

        // html = render raw html
        if (isset($item['html'])) {
            return $item['html'];
        }

        if ($item == ['-'] && $tag == 'li') {
            return '<li class="divider"></li>';
        }

        if (isset($item['heading']) && $tag == 'li') {
            return '<li class="navigation-header"><span>'.Html::encode($item['heading']).'</span><i class="icon-menu" title="" data-original-title="'.Html::encode($item['heading']).'"></i></li>';
        }

        $html = '';

        if (isset($item['submenu']) && is_array($item['submenu'])) {
            if ($depth == 1) {
                $html .= '<li><a href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i style="font-size:16px" class="slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span>'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul>';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';

            } else {
                $html .= '<li><a href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i style="font-size:16px;" class="slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span>'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul>';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';
            }
        } else {
            $aLabel = isset($item['label']) ? Yii::t('nav', $item['label']) : '';
            if ($depth == 1) {
                $aLabel = '<span>'.Html::encode($aLabel).'</span>';
            } else {
                $aLabel = Html::encode($aLabel);
            }

            if (isset($item['icon'])) {
                $aLabel = '<i style="font-size:16px;" class="slicon-'.Html::encode($item['icon']).'"></i> '.$aLabel;
            }
            $aLink = isset($item['link']) ? $item['link'] : '';
            if (substr($aLink, 0, 1) != '@' && substr($aLink, 0, 1) != '#' && strpos($aLink, '//') === false) {
                $aLink = '@web/'.$aLink;
            }
            $aArgs = [];
            foreach (['target', 'a target', 'a class', 'a id', 'a title', 'a rel'] as $arg) {
                if (isset($item[$arg])) {
                    $aArgs[str_replace('a ', '', $arg)] = Html::encode($item[$arg]);
                }
            }
            $html = Html::a($aLabel, $aLink, $aArgs);

            if ($tag == 'li') {
                $liAttr = '';
                if (isset($item['id'])) {
                    $liAttr .= 'id="'.Html::encode($item['id']).'" ';
                }
                if (isset($item['title'])) {
                    $liAttr .= 'title="'.Html::encode($item['title']).'" ';
                }
                // Active = class="active"
                if (isset($item['active']) && $item['active']) {
                    $item['class'] = isset($item['class']) ? $item['class'].' active' : 'active';
                }
                if (isset($item['class'])) {
                    $liAttr .= 'class="'.Html::encode($item['class']).'" ';
                }

                $html = '<li '.$liAttr.'>'.$html.'</li>';
            }

        }
        return $html;
    }
}


// Limitless 2
if (!function_exists('renderLimitlessMainNavItem')) {
    function renderLimitlessMainNavItem($item, $depth = 1, $tag = 'li')
    {
        if (isset($item['hidden']) && $item['hidden']) {
            return '';
        }

        // html = render raw html
        if (isset($item['html'])) {
            return $item['html'];
        }

        if ($item == ['-'] && $tag == 'li') {
            return '<li class="nav-item-divider"></li>';
        }

        if (isset($item['heading']) && $tag == 'li') {
            return '<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">'.Html::encode($item['heading']).'</div></li>';
        }

        $html = '';

        if (isset($item['submenu']) && is_array($item['submenu'])) {
            if ($depth == 1) {
                $html .= '<li class="nav-item nav-item-submenu '.(isset($item['active']) && $item['active'] ? 'nav-item-expanded nav-item-open' : '').'"><a class="nav-link" href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i class="icon slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span class="title">'.Yii::t('nav', $item['label']).'</span><span class="arrow"></span>';
                $html .='</a><ul class="nav nav-group-sub" data-submenu-title="'.Yii::t('nav', $item['label']).'">';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderLimitlessMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';

            } else {
                $html .= '<li class="nav-item '.(isset($item['active']) && $item['active'] ? 'active' : '').'"><a class="nav-link" href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i class="icon slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span class="title">'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul class="menu-submenu">';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderLimitlessMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';
            }
        } else {
            $aLabel = isset($item['label']) ? Yii::t('nav', $item['label']) : '';
            if ($depth == 1) {
                $aLabel = '<span class="title">'.Html::encode($aLabel).'</span>';
            } else {
                $aLabel = '<span class="title">'.Html::encode($aLabel).'</span>';
                // $aLabel = Html::encode($aLabel);
            }

            if (isset($item['icon'])) {
                $aLabel = '<i class="icon slicon-'.Html::encode($item['icon']).'"></i> '.$aLabel;
            }

            if ($depth != 1) {
                $aLabel = '<span class="dot"></span>'.$aLabel;
            }

            $aLink = isset($item['link']) ? $item['link'] : '';
            if (substr($aLink, 0, 1) != '@' && substr($aLink, 0, 1) != '#' && strpos($aLink, '//') === false) {
                $aLink = '@web/'.$aLink;
            }
            $item['a class'] = 'nav-link';
            if (isset($item['active']) && $item['active']) {
                $item['a class'] .= ' active';
            }
            $aArgs = [];
            foreach (['target', 'a target', 'a class', 'a id', 'a title', 'a rel'] as $arg) {
                if (isset($item[$arg])) {
                    $aArgs[str_replace('a ', '', $arg)] = Html::encode($item[$arg]);
                }
            }
            $html = Html::a($aLabel, $aLink, $aArgs);

            if ($tag == 'li') {
                $item['class'] = isset($item['class']) ? $item['class'].' nav-item' : 'nav-item';
                if (isset($item['id'])) {
                    $liAttr .= 'id="'.Html::encode($item['id']).'" ';
                }
                if (isset($item['title'])) {
                    $liAttr .= 'title="'.Html::encode($item['title']).'" ';
                }
                if (isset($item['class'])) {
                    $liAttr = 'class="'.Html::encode($item['class']).'" ';
                }

                $html = '<li '.$liAttr.'>'.$html.'</li>';
            }

        }
        return $html;
    }
}

// Limitless 2
if (!function_exists('renderLimitless210MainNavItem')) {
    function renderLimitless210MainNavItem($item, $depth = 1, $tag = 'li')
    {
        if (isset($item['hidden']) && $item['hidden']) {
            return '';
        }

        // html = render raw html
        if (isset($item['html'])) {
            return $item['html'];
        }

        if ($item == ['-'] && $tag == 'li') {
            return '<li class="nav-item-divider"></li>';
        }

        if (isset($item['heading']) && $tag == 'li') {
            return '<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">'.Html::encode($item['heading']).'</div></li>';
        }

        $html = '';

        if (isset($item['submenu']) && is_array($item['submenu'])) {
            if ($depth == 1) {
                $html .= '<li class="nav-item nav-item-submenu '.(isset($item['active']) && $item['active'] ? 'nav-item-expanded nav-item-open' : '').'"><a class="nav-link" href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i class="icon slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span class="title">'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul class="nav nav-group-sub" data-submenu-title="'.Yii::t('nav', $item['label']).'">';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderLimitlessMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';

            } else {
                $html .= '<li class="nav-item '.(isset($item['active']) && $item['active'] ? 'active' : '').'"><a class="nav-link" href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i class="icon slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span class="title">'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul class="menu-submenu">';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderLimitlessMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';
            }
        } else {
            $aLabel = isset($item['label']) ? Yii::t('nav', $item['label']) : '';
            if ($depth == 1) {
                $aLabel = '<span class="title">'.Html::encode($aLabel).'</span>';
            } else {
                $aLabel = '<span class="title">'.Html::encode($aLabel).'</span>';
                // $aLabel = Html::encode($aLabel);
            }

            if (isset($item['icon'])) {
                $aLabel = '<i class="icon slicon-'.Html::encode($item['icon']).'"></i> '.$aLabel;
            }

            if ($depth != 1) {
                $aLabel = '<span class="dot"></span>'.$aLabel;
            }

            $aLink = isset($item['link']) ? $item['link'] : '';
            if (substr($aLink, 0, 1) != '@' && substr($aLink, 0, 1) != '#' && strpos($aLink, '//') === false) {
                $aLink = '@web/'.$aLink;
            }
            $item['a class'] = 'nav-link';
            if (isset($item['active']) && $item['active']) {
                $item['a class'] .= ' active';
            }
            $aArgs = [];
            foreach (['target', 'a target', 'a class', 'a id', 'a title', 'a rel'] as $arg) {
                if (isset($item[$arg])) {
                    $aArgs[str_replace('a ', '', $arg)] = Html::encode($item[$arg]);
                }
            }
            $html = Html::a($aLabel, $aLink, $aArgs);

            if ($tag == 'li') {
                $item['class'] = isset($item['class']) ? $item['class'].' nav-item' : 'nav-item';
                if (isset($item['id'])) {
                    $liAttr .= 'id="'.Html::encode($item['id']).'" ';
                }
                if (isset($item['title'])) {
                    $liAttr .= 'title="'.Html::encode($item['title']).'" ';
                }
                if (isset($item['class'])) {
                    $liAttr = 'class="'.Html::encode($item['class']).'" ';
                }

                $html = '<li '.$liAttr.'>'.$html.'</li>';
            }

        }
        return $html;
    }
}


// SmartAdmin 4
if (!function_exists('renderSmartAdminMainNavItem')) {
    function renderSmartAdminMainNavItem($item, $depth = 1, $tag = 'li')
    {
        if (isset($item['hidden']) && $item['hidden']) {
            return '';
        }

        // html = render raw html
        if (isset($item['html'])) {
            return $item['html'];
        }

        if ($item == ['-'] && $tag == 'li') {
            return '<li class="nav-item-divider"></li>';
        }

        if (isset($item['heading']) && $tag == 'li') {
            return '<li class="nav-title">'.Html::encode($item['heading']).'</li>';
        }

        $html = '';

        if (isset($item['submenu']) && is_array($item['submenu'])) {
            if ($depth == 1) {
                $html .= '<li class="nav-item nav-item-submenu '.(isset($item['active']) && $item['active'] ? 'nav-item-expanded nav-item-open' : '').'"><a class="nav-link" href="javascript:;">';
                if (isset($item['icon'])) {
                    $html .= '<i class="fal fa-'.$item['icon'].'"></i> ';
                }
                $html .= '<span class="nav-link-text">'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul>';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderLimitlessMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';

            } else {
                $html .= '<li><a class="nav-link" href="javascript:;" data-filter-tags="'. $item['label'] .'">';
                if (isset($item['icon'])) {
                    $html .= '<i class="icon slicon-'.$item['icon'].'"></i> ';
                }
                $html .= '<span class="nav-link-text">'.Yii::t('nav', $item['label']).'</span>';
                $html .='</a><ul>';
                foreach ($item['submenu'] as $submenuItem) {
                    $html .= renderLimitlessMainNavItem($submenuItem, $depth + 1);
                }
                $html .= '</ul></li>';
            }
        } else {
            $aLabel = isset($item['label']) ? Yii::t('nav', $item['label']) : '';
            if ($depth == 1) {
                $aLabel = '<span class="nav-link-text">'.Html::encode($aLabel).'</span>';
            } else {
                $aLabel = '<span class="nav-link-text">'.Html::encode($aLabel).'</span>';
            }

            if (isset($item['icon'])) {
                $aLabel = '<i class="fal fa-'.Html::encode($item['icon']).'"></i> '.$aLabel;
            }

            $aLink = isset($item['link']) ? $item['link'] : '';
            if (substr($aLink, 0, 1) != '@' && substr($aLink, 0, 1) != '#' && strpos($aLink, '//') === false) {
                $aLink = '@web/'.$aLink;
            }
            $item['a class'] = 'nav-link';
            if (isset($item['active']) && $item['active']) {
                $item['a class'] .= ' active';
            }
            $aArgs = [
                'data-filter-tags'=>$item['label'],
            ];
            foreach (['target', 'a target', 'a class', 'a id', 'a title', 'a rel'] as $arg) {
                if (isset($item[$arg])) {
                    $aArgs[str_replace('a ', '', $arg)] = Html::encode($item[$arg]);
                }
            }
            $html = Html::a($aLabel, $aLink, $aArgs);

            if ($tag == 'li') {
                $item['class'] = isset($item['class']) ? $item['class'].' nav-item' : 'nav-item';
                if (isset($item['id'])) {
                    $liAttr .= 'id="'.Html::encode($item['id']).'" ';
                }
                if (isset($item['title'])) {
                    $liAttr .= 'title="'.Html::encode($item['title']).'" ';
                }
                if (isset($item['class'])) {
                    $liAttr = 'class="'.Html::encode($item['class']).'" ';
                }

                $html = '<li '.$liAttr.'>'.$html.'</li>';
            }

        }
        return $html;
    }
}