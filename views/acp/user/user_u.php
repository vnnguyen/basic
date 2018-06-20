<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_user_inc.php');

$timezoneList = [];
foreach (timezone_identifiers_list() as $i)
    $timezoneList[$i] = str_replace(['/', '_'], [' / ', ' '], $i);

$genderList = ['male'=>'Male', 'female'=>'Female'];

$userStatusList = ['on'=>'Active', 'off'=>'Inactive'];

$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'fname'); ?></div>
            <div class="col-md-6"><?= $form->field($theUser, 'lname'); ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'gender')->dropdownList($genderList); ?></div>
            <div class="col-md-2"><?= $form->field($theUser, 'bday', ['inputOptions'=>['type'=>'number', 'min'=>1, 'max'=>31, 'class'=>'form-control']]) ?></div>
            <div class="col-md-2"><?= $form->field($theUser, 'bmonth', ['inputOptions'=>['type'=>'number', 'min'=>1, 'max'=>12, 'class'=>'form-control']]) ?></div>
            <div class="col-md-2"><?= $form->field($theUser, 'byear', ['inputOptions'=>['type'=>'number', 'min'=>1, 'max'=>2099, 'class'=>'form-control']]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'name'); ?></div>
            <div class="col-md-6"><?= $form->field($theUser, 'nickname'); ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'country_code')->dropdownList(ArrayHelper::map($allCountries, 'code', 'name_en')) ?></div>
            <div class="col-md-6"><?= $form->field($theUser, 'status')->dropdownList($userStatusList) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'language')->dropdownList(['en'=>'English', 'vi'=>'Tiếng Việt']); ?></div>
            <div class="col-md-6"><?= $form->field($theUser, 'timezone')->dropdownList($timezoneList); ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'email'); ?></div>
            <div class="col-md-6"><?= $form->field($theUser, 'phone'); ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theUser, 'raw_password') ?></div>
            <div class="col-md-6"><?= $form->field($theUser, 'raw_password_again') ?></div>
        </div>

        <div class="row">
            <div class="col-md-12"><?= $form->field($theUser, 'info')->textArea(['rows'=>5]); ?></div>
        </div>
        <div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary']); ?></div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <? if ($theUser->isNewRecord) { ?>
    Please save this user to upload files and avatar
    <? } else { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $form->field($theUser, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click image/field to upload/change avatar'); ?>
            <p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theUser->image == '' ? 'https://placehold.it/300x300&text=NO+IMAGE' : $theUser->image ?>" alt="Image"></p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            Upload other files
            <a id="upload-other-files" href="#">Click here to upload</a>
        </div>
    </div>
    <? } ?>
</div>
<? ActiveForm::end();

\app\assets\CkfinderAsset::register($this);
        $text = <<<TXT
<script src="{DIR}assets/ckfinder_2.5.1/ckfinder.js"></script>
<script type="text/javascript">
var ckfinderUpdate = '';

function BrowseServer()
{
    var finder = new CKFinder();
    finder.basePath = '{DIR}assets/ckfinder_2.5.1/';
    finder.selectActionFunction = SetFileField;
    finder.popup();
}

function SetFileField( fileUrl )
{
    $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
    $('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(fileUrl);
}

</script>
TXT;
// echo str_replace(['{DIR}'], [DIR], $text);
$jsText = <<<TXT
$('a#upload-other-files').on('click', function(){
    BrowseServer();
    return false;
});
$('.ckfinder').dblclick(function(){
    ckfinderUpdate = $(this).data('ckfinder-update')
    BrowseServer();
});
$('input.ckfinder').change(function(){
    fileUrl = $(this).val();
    if (fileUrl == '') {
        fileUrl = 'https://placehold.it/300x300&text=NO+IMAGE'
    }
    ckfinderUpdate = $(this).data('ckfinder-update')
    $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
});
TXT;
if (!$theUser->isNewRecord) {
    $this->registerJs(\app\assets\CkfinderAsset::ckfinderJs('user'.$theUser['id']));
}
//$theUser->insertCKFinder();

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$js = <<<'TXT'
$('.selectpicker').selectpicker({

});
TXT;

$this->registerJs($js);