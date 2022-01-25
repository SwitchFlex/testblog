<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class ImageUpload extends Model
{
    public $image;

    public function uploadFile(UploadedFile $file)
    {
       $this->image = $file;

       $filename = strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension); // чтобы не повторялись имена картинок. Это генератор названия картинки

       $file->saveAs(Yii::getAlias('@web') . 'uploads/' . $file->name);

       return $filename;
    }

}