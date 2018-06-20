<?
use yii\helpers\Html;
use yii\helpers\Markdown;
?>
<? if (!empty($theVenue['dvo'])) { ?>
<div class="tab-pane" id="t-promo">
<?
$promoInfo = explode('**Promotion**', $theVenue['info_pricing']);
if (isset($promoInfo[1])) {
    echo Markdown::process($promoInfo[1]);
} else {
    echo 'NO PROMOTIONS AVAILABLE';
}
?>
</div>
<? } ?>