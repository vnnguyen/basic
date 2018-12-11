<?php
namespace app\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Widget;
use yii\data\Pagination;

class LinkPager extends \yii\widgets\LinkPager
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
    }

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $this->options['class'] = 'pagination justify-content-center center-h';
        $this->firstPageLabel = '&lt;&lt;';
        $this->prevPageLabel = '&lt;';
        $this->nextPageLabel = '&gt;';
        $this->lastPageLabel = '&gt;&gt;';

        $options = $this->linkContainerOptions;
        $this->disabledListItemSubTagOptions = ['class' => 'page-link'];

        $options['data-page'] = $page;
        $options['class'] = 'page-item';

        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            $disabledItemOptions = $this->disabledListItemSubTagOptions;
            $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'span');

            return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        $linkOptions['class'] = 'page-link';

        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);

        $options = ['class' => 'page-item '.$class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label, ['class'=>'page-link']), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        $linkOptions['class']='page-link';

        return Html::tag('li', Html::a($label, '#pagination', $linkOptions), $options);
    }
}
