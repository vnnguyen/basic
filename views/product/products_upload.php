<?

use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_products_inc.php'); 

Yii::$app->params['page_icon'] = 'upload';
Yii::$app->params['page_title'] = 'Attach files to tour program: '.$theProduct['title'];
Yii::$app->params['page_breadcrumbs'][] = ['Products', 'products'];
Yii::$app->params['page_breadcrumbs'][] = ['View', 'products/r/'.SEG3];
Yii::$app->params['page_breadcrumbs'][] = ['Attach files'];

?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">Attachments</h5>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin() ?>
            <?= $form->field($model, 'pdfFiles[]')->fileInput(['multiple' => true, 'accept' => '.pdf,.doc,.docx']) ?>
            <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
            <?= $form->field($model, 'excelFiles[]')->fileInput(['multiple' => true, 'accept' => '.xls,.xlsx,.xlsb,.xlsb']) ?>
            <?= Html::submitButton('Upload', ['class'=>'btn btn-primary']) ?> or <?= Html::a('Cancel and return', '/products/r/'.$theProduct['id']) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div class="alert alert-info">
        <strong>NOTE:</strong> Starting July 29, 2016, you can upload files of several types and attach to the itinerary:
        <br>- Itinerary print (PDF, Word)
        <br>- Map and other images
        <br>- Cost calculation sheets
        <br>- etc.
        <br>You can upload multiple files at once. Existing files with the same name will be overwritten.
    </div>
</div>
<?
$productPdfFiles = [];
$productImageFiles = [];
$productExcelFiles = [];
$productUploadPath = Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'];
if (file_exists($productUploadPath.'/pdf')) {
    $productPdfFiles = FileHelper::findFiles($productUploadPath.'/pdf');
}
if (file_exists($productUploadPath.'/image')) {
    $productImageFiles = FileHelper::findFiles($productUploadPath.'/image');
}
if (file_exists($productUploadPath.'/excel')) {
    $productExcelFiles = FileHelper::findFiles($productUploadPath.'/excel');
}
?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">Existing files</h5>
        </div>
        <div class="panel-body">
            <?
            if (!empty($productImageFiles)) {
                foreach ($productImageFiles as $file) {
                    $fileName = substr(strrchr($file, "/"), 1);
                    echo Html::a(Html::img('/timthumb.php?h=100&src=/upload/products/'.$theProduct['id'].'/image/'.urlencode($fileName), ['style'=>'margin:0 2px 2px 0']), '/products/download/'.$theProduct['id'].'?action=download&type=image&file='.$fileName, ['title'=>$fileName]);
                    echo Html::a('<i class="fa fa-trash-o"></i>', '?action=delete&type=image&file='.urlencode($fileName), ['title'=>'Delete', 'class'=>'text-danger']);                }
                echo '<div style="height:10px;"></div>';
            }
            if (file_exists(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProduct['id'].'.pdf')) {
                $fileName = 'PDF itinerary';
                echo '<div>+ ', Html::a($fileName, '/products/download/'.$theProduct['id'].'?action=download&type=oldpdf&file='.urlencode($fileName)), ' ', Html::a('<i class="fa fa-trash-o"></i>', '?action=delete&type=oldpdf&file='.urlencode($fileName), ['title'=>'Delete', 'class'=>'text-danger']), '</div>';
            }

            if (!empty($productPdfFiles)) {
                foreach ($productPdfFiles as $file) {
                    $fileName = substr(strrchr($file, "/"), 1);
                    echo '<div>+ ', Html::a($fileName, '/products/download/'.$theProduct['id'].'?action=download&type=pdf&file='.urlencode($fileName)), ' ', Html::a('<i class="fa fa-trash-o"></i>', '?action=delete&type=pdf&file='.urlencode($fileName), ['title'=>'Delete', 'class'=>'text-danger']), '</div>';
                }
            }
            if (!empty($productExcelFiles)) {
                foreach ($productExcelFiles as $file) {
                    $fileName = substr(strrchr($file, "/"), 1);
                    echo '<div>+ ', Html::a($fileName, '/products/download/'.$theProduct['id'].'?action=download&type=excel&file='.$fileName), ' ', Html::a('<i class="fa fa-trash-o"></i>', '?action=delete&type=excel&file='.urlencode($fileName), ['title'=>'Delete', 'class'=>'text-danger']), '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>
