<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class SurveyUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'minFiles' => 5],
        ];
    }

//    public function upload()
//    {
//        if ($this->validate()) {
//            $path = Yii::getAlias('@backend') . '/uploads/' . $this->imageFiles->baseName . '.' . $this->imageFiles->extension;
//            $this->imageFiles->saveAs($path);
////            @chmod($path, 0775);
//            return $path;
//        } else {
//            return false;
//        }
//    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $path = Yii::getAlias('@frontend') . '/web/uploads/' . $this->survey_id . '_' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                @chmod($path, 0755);
            }
            return true;
        } else {
            return false;
        }
    }


    public function attributeLabels()
    {
        return [
            'files' => 'Загрузить фото',
        ];
    }

}
