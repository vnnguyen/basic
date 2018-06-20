<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_kase_inc.php');

$this->title = '(TESTING) Send Client page link & Registration request to customer';

$this->params['breadcrumb'][] = ['View', 'cases/r/'.$theCase['id']];
$this->params['breadcrumb'][] = ['Send Client page link', 'cases/send-cpl/'.$theCase['id']];

$bookingProductList = [];
foreach ($theCase['bookings'] as $booking) {
    $bookingProductList[$booking['id']] = $booking['product']['title'].' ('.$booking['status'].')';
}
$emailList = [];
foreach ($theCase['people'] as $user) {
    if ($user['email'] != '') {
        $emailList[$user['email']] = $user['name'].' ('.$user['email'].')';
    }
}

Yii::$app->params['body_class'] = 'bg-white';

?>
<div class="col-md-8">
    <? if (!empty($sentLinks)) { ?>
    <h3 class="no-padding-top"><?= Yii::t('cpl', 'Sent links') ?></h3>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sent to</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($sentLinks as $link) { ?>
            <tr>
                <td><?= $link['created_dt'] ?></td>
                <td><?= $link['email'] ?></td>
                <td><?= $link['status'] ?></td>
                <td><?= Html::a(Yii::t('kase', 'Resend link'), '?action=resend&cplink_id='.$link['id']) ?></td>
            </tr>
            <? } ?>
        </tbody>
    </table>
    <h3 class="no-padding-top"><?= Yii::t('kase', 'Send a new link to a different person') ?></h3>
    <? } ?>

    <? $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theForm, 'booking_id')->dropdownList($bookingProductList, ['prompt'=>'- Select -']) ?></div>
            <div class="col-md-6"><?= $form->field($theForm, 'customer_email')->dropdownList($emailList, ['prompt'=>'- Select -']) ?></div>
        </div>
        <?= $form->field($theForm, 'message')->textArea(['rows'=>15]) ?>
        <?= $form->field($theForm, 'attachments')->checkboxList($attachmentList[$theCase['language']], [
            'separator'=>'<br>',
        ]) ?>
        <div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <div class="alert alert-info">
        <strong>NOTE:</strong> Tuỳ thực tế giao dịch với khách, cách dùng từ ngữ trong email gửi đi có thể khác nhau, người bán hàng có quyền sửa nội dung dưới đây trước khi gửi. Trong email có các token (ký hiệu) sau mà khi gửi sẽ được tự động thay bằng nội dung tương ứng:
        <br>{{ $link }} : bắt buộc phải có, sẽ được thay bằng link đến trang khách hàng
        <br>{{ $name }} : không bắt buộc, sẽ được thay bằng tên đầy đủ của người bán hàng
        <br>{{ $email }} : không bắt buộc, sẽ được thay bằng email của người bán hàng
        <br>Email phải thể hiện được các ý sau:
        <br>- Người nhận message này sẽ click vào link để đến trang Khách hàng Amica Travel
        <br>- Họ sẽ điền thông tin cho mình cũng như tất cả thành viên khác của đoàn tour
        <br>- Nếu họ không biết thông tin của ai trong nhóm, cho bán hàng biết để bán hàng gửi link riêng cho người ấy
    </div>
</div>
<?

$js = <<<'TXT'
$('#cplink-attachments').selectpicker();


$('#cplink-message').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:400,
    contentsCss: '/assets/css/style_ckeditor.css'
    //contentCss:'https://my.amicatravel.com/assets/css/ckeditor_160828.css'
});
TXT;

$this->registerJsFile('https://cdn.ckeditor.com/4.5.11/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.5.11/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);

//$this->registerCssFile(DIR.'assets/bootstrap-select_1.6.3/css/bootstrap-select.min.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-select_1.6.3/js/bootstrap-select.min.js', ['depends'=>'app\assets\MainAsset']);

//$this->registerCss($css);
$this->registerJs($js);
