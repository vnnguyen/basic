<?
namespace app\models;
use yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ProductUploadForm extends Model
{

    public $pdfFiles;
    public $imageFiles;
    public $excelFiles;
    public $productId = 0;

    public function rules()
    {
        return [
            [['pdfFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf,doc,docx,docm', 'maxFiles' => 4],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxFiles' => 4],
            [['excelFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xls, xlsx, xlbx', 'maxFiles' => 4],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {//'/var/www/my.amicatravel.com/www
            FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/products/'.$this->productId.'/pdf/');
            FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/products/'.$this->productId.'/image/');
            FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/products/'.$this->productId.'/excel/');
            foreach ($this->pdfFiles as $file) {
                $file->saveAs(Yii::getAlias('@webroot').'/upload/products/'.$this->productId.'/pdf/'.$file->baseName . '.' . $file->extension);
            }
            foreach ($this->imageFiles as $file) {
                $file->saveAs(Yii::getAlias('@webroot').'/upload/products/'.$this->productId.'/image/'.$file->baseName . '.' . $file->extension);
            }
            foreach ($this->excelFiles as $file) {
                $file->saveAs(Yii::getAlias('@webroot').'/upload/products/'.$this->productId.'/excel/'.$file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}