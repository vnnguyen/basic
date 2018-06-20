<?
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class DvcUploadForm extends Model
{

    public $uploadFiles;
    public $uploadDir = '';

    public function rules()
    {
        return [
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf,doc,docx,docm,jpg,jpeg,png,gif,tiff,zip,rar,gz,xls,xlsx,xlsm,txt', 'maxFiles' => 10],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $dir = Yii::getAlias('@webroot').'/upload/dvc/'.$this->uploadDir;
            FileHelper::createDirectory($dir);
            foreach ($this->uploadFiles as $file) {
                $file->saveAs($dir.'/'.$file->baseName.'.'.$file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}