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
use yuncms\attachment\AttachmentTrait;
use yuncms\attachment\components\Uploader;

/**
 * SummerNoteAction class file.
 */
class SummerNoteAction extends Action
{
    use AttachmentTrait;

    /**
     * @var string file input name.
     */
    public $inputName = 'file';

    /**
     * Initializes the action and ensures the temp path exists.
     */
    public function init()
    {
        parent::init();
        $this->controller->enableCsrfValidation = false;
    }

    /**
     * Runs the action.
     * This method displays the view requested by the user.
     * @param null $callback
     */
    public function run($callback = null)
    {
        $fieldName = $this->inputName;
        $config = [
            'maxFiles' => 1,
            'extensions' => $this->getSetting('imageAllowFiles'),
            'checkExtensionByMimeType' => true,
            'mimeTypes' => 'image/*',
            "maxSize" => $this->getMaxUploadByte(),
        ];
        $uploader = new Uploader([
            'fileField' => $fieldName,
            'config' => $config,
        ]);
        $uploader->upFile();
        $res = $uploader->getFileInfo();
        if ($res['state'] == 'SUCCESS') {
            $result = [
                "originalName" => $res['original'],
                "name" => $res['title'],
                "url" => $res['url'],
                "size" => $res['size'],
                "type" => $res['type'],
                "state" => 'SUCCESS'
            ];
        } else {
            $result = [
                "state" => Yii::t('app', 'File save failed'),
            ];
        }

        if (is_null($callback)) {
            echo Json::encode($result);
        } else {
            echo '<script>' . $callback . '(' . Json::encode($result) . ')</script>';
        }
    }
}