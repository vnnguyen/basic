<div class="mb-1em"><span class="text-uppercase text-warning text-bold"><?= Yii::t('k', 'Auto import email from/to') ?>:</span>
<?
if (!empty($theEmails)) {
    foreach ($theEmails as $email) {
        echo $email;
        if (in_array(MY_ID, [1, 4432, 26435, $theCase['owner_id']])) {
?>
    <a title="Remove <?= $email ?>" href="?action=remove-email&email=<?= $email ?>" class="text-danger"><i class="fa fa-minus-circle"></i></a>
<?
        }
    }
} else {
?>
    no addresses
<?
}
?>
    <? if (in_array(MY_ID, [1, 4432, 26435, $theCase['owner_id']])) { ?>
    <a id="a-add-email" class="text-success" title="Add email for auto import" href="#" onclick="$('#div-add-email').toggle(0); return false;"><i class="fa fa-plus-circle"></i></a>
    <div id="div-add-email" style="display:none;">
        <form method="post" action="" class="form-inline">
            <input class="form-control" name="email" value="" autocomplete="off" placeholder="Email">
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
    <? } ?>
</div>

