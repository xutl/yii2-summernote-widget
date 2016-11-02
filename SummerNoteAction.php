<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\summernote;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\validators\FileValidator;

/**
 * SummerNoteAction class file.
 */
class SummerNoteAction extends Action
{
    /**
     * @var string file input name.
     */
    public $inputName = 'file';

    /**
     * @var callable success callback with signature: `function($filename, $params)`
     */
    public $onComplete;

    /**
     * Initializes the action and ensures the temp path exists.
     */
    public function init()
    {
        parent::init();
        $this->controller->enableCsrfValidation = false;
    }

    /**
     * @return int the max upload size in MB
     */
    public static function getPHPMaxUploadSize()
    {
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        return min($max_upload, $max_post, $memory_limit);
    }

    /**
     * Runs the action.
     * This method displays the view requested by the user.
     * @throws HttpException if the view is invalid
     */
    public function run($callback = null)
    {
        $uploadedFile = UploadedFile::getInstanceByName($this->inputName);
        $params = Yii::$app->request->getBodyParams();
        $validator = new FileValidator([
            'extensions' => 'gif, jpg, jpeg, png, bmp',
            'checkExtensionByMimeType' => true,
            'mimeTypes' => 'image/*',
            "maxSize" => static::getPHPMaxUploadSize() * 1048576,
        ]);
        if (!$validator->validate($uploadedFile, $error)) {
            $result = [
                'state' => $error,
            ];
        } else {
            if ($this->onComplete && ($url = call_user_func($this->onComplete, $uploadedFile, $params)) != false) {
                $result = [
                    "originalName" => $uploadedFile->name,
                    "name" => basename($url),
                    "url" => $url,
                    "size" => $uploadedFile->size,
                    "type" => '.' . $uploadedFile->extension,
                    "state" => 'SUCCESS'
                ];
            } else {
                $result = [
                    "state" => Yii::t('app', 'File save failed'),
                ];
            }
        }
        if (is_null($callback)) {
            echo Json::encode($result);
        } else {
            echo '<script>' . $callback . '(' . Json::encode($result) . ')</script>';
        }
    }
}