<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class ImageUpload extends Model
{
    public $image;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,png']
        ];
    }

    /**
     * @param $file
     * @param $currentImage
     * @return string|void
     */
    public function uploadFile($file, $currentImage)
    {
       $this->image = $file;

       if($this->validate())
       {
           $this->deleteCurrentImage($currentImage);

           return $this->saveImage();
       }
    }

    /**
     * @return string
     */
    private function getFolder(){
        return Yii::getAlias('@web') . 'uploads/';
    }

    /**
     * @return string
     */
    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage)
    {
        if ($this->fileExist($currentImage)) {
            unlink($this->getFolder() . $currentImage);
        }
    }

    public function fileExist($currentImage)
    {
       if(!empty($currentImage) && $currentImage != null)
       {
            return file_exists($this->getFolder() . $currentImage);
       }
    } // пофиксили если вставляем картинку и ничего нет в базе то записывает картинку в базе

    /**
     * @return string
     */
    public function saveImage()
    {
        $filename = $this->generateFilename();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }

    public function getImage()
    {
//            if($this->image)
//            {
//                return '/uploads/' . $this->image;
//            }
//            return '/no-image.png';  то же самое что и строка ниже

        return ($this->image) ? '/uploads/' . $this->image : '/no-image.png';
    }

    /**
     * @return mixed
     */
    public function beforeDelete()
    {
        $this->deleteImage();

        return parent::beforeDelete();
    }
}