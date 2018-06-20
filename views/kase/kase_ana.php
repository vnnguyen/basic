<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_kase_inc.php');

Yii::$app->params['page_title'] = 'Exit survey: '.$theCase['name'];
Yii::$app->params['page_breadcrumbs'][] = ['View', 'cases/r/'.$theCase['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Exit survey'];

$anaQuestions['na_160730'] = [
    '0. Time',
    '1. Comment avez-vous connu Amica Travel ?',
    '2. Pour ce voyage avez-vous également contacté d\'autres agences de voyage ?',
    '3. Si oui, pouvez-vous préciser leurs noms ?',
    '4. Finalement, pour quelle formule de voyage avez-vous opté ?',
    '5. Pour quelles raisons vous n\'avez pas choisi Amica Travel ?',
    '6. Quels sont vos commentaires sur les différents échanges entre vous et l\'équipe d\'Amica Travel ?',
    '7. Avez-vous certaines remarques ou suggestions qui nous permettraient d\'améliorer notre service ?',
    '8. Votre nom et/ou votre adresse de mail (pas obligatoire)',
];

$anaQuestions['a_160730'] = [
    '0. Time',
    '1. Comment avez-vous connu Amica Travel ?',
    '2. Pour ce voyage avez-vous également contacté d\'autres agences de voyage ?',
    '3. Si oui, pouvez-vous préciser leurs noms ?',
    '4. Pourquoi avez-vous choisi une agence locale, plutôt qu\'une de votre pays ?',
    '5. Qu\'est-ce qui vous a convaincu de choisir finalement Amica Travel ?',
    '6. Quels sont vos commentaires sur les différents échanges entre vous et l\'équipe d\'Amica Travel ?',
    '7. Avez-vous certaines remarques ou suggestions qui nous permettraient d\'améliorer notre service ?',
    '8. Votre nom et/ou votre adresse de mail (pas obligatoire)'
];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Exit survey result</h6>
        </div>
        <div class="panel-body">
            <form method="post" action="<?= DIR.URI ?>">
                <p>Loại form điều tra<br>
                    <select class="form-control" name="ana">
                        <option value="a_160730">Acheteur</option>
                        <option value="na_160730" <?= $ana == 'na_160730' ? 'selected="selected"' : '' ?>>Non-acheteur</option>
                    </select>
                </p>
                <p>Nội dung trả lời<br>
                    <textarea class="form-control" rows="10" name="str" id="str"><?= $str ?></textarea>
                </p>
                <p>Gửi email thông báo cho (nếu có nhiều email thì tách ra bằng dấu phẩy)<br>
                    <input type="text" class="form-control" name="email" id="email" value="<?= $email ?>">
                </p>

            <?php if (isset($_POST['ana']) && $_POST['ana'] !='' && isset($_POST['str']) && $_POST['str'] !='') {
                $parts = explode('[#QA]', $_POST['str']);
                ?>
            <div class="alert alert-info">
                <strong>Xem và xác nhận câu trả lời khớp với câu hỏi:</strong>
                <?php for ($i = 0; $i < count($anaQuestions[$_POST['ana']]); $i ++) { ?>
                <div class="text-warning text-semibold"><?= $anaQuestions[$_POST['ana']][$i] ?></div>
                <div><?= isset($parts[$i]) ? nl2br(Html::encode(trim($parts[$i]))) : '' ?></div>
                <br>
                <?php } ?>
            </div>

                <p><label><input type="checkbox" name="ok" value="ok"> OK, ghi thông tin như trên vào hồ sơ và gửi email thông báo cho những người liên quan</label></p>
            <?php } ?>
                <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>
            </form>
        </div>
    </div>
</div>
<div class="col-md-4">
    <p><strong>Chỉ dẫn</strong></p>
    <ul>
        <li>Bước 1: Copy paste nội dung trả lời questionaire vào ô, chọn loại questionaire, sau đó nhấn Submit. Chú ý: ở bước này mỗi câu trả lời sẽ được tự động chèn chuỗi ký tự "[#QA]" ở đầu</li> 
        <li>Bước 2: Xem phần "Preview" để xem các câu trả lời đã khớp với câu hỏi chưa. Nếu chưa thì điều chỉnh bằng cách thêm hay xoá các chuỗi ký tự "[#QA]" ở đầu mỗi câu trả lời và Submit lại.</li>
        <li>Bước 3: Nếu đã đúng, chọn "OK" và click "Submit" lần nữa để ghi vào CSDL</li>
    </ul>
</div>
<?
$js = <<<'TXT'
$('#str').on('change', function(){
    var text = $(this).val();
    text = text.replace(/\t/g, "[#QA]");
    $(this).val(text);
});
TXT;

$this->registerJs($js);