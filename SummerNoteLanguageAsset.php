<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\summernote;

use Yii;
use yii\web\AssetBundle;

/**
 * Class SummerNoteLanguageAsset
 * @package xutl\select2
 */
class SummerNoteLanguageAsset extends AssetBundle
{
    public $sourcePath = '@vendor/xutl/yii2-summernote-widget/assets';

    /**
     * @var boolean whether to automatically generate the needed language js files.
     * If this is true, the language js files will be determined based on the actual usage of [[DatePicker]]
     * and its language settings. If this is false, you should explicitly specify the language js files via [[js]].
     */
    public $autoGenerate = true;

    /**
     * @var string language to register translation file for
     */
    public $language;

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\jui\JuiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        if ($this->autoGenerate) {
            $language = $this->language;
            $fallbackLanguage = substr($this->language, 0, 2);
            if ($fallbackLanguage !== $this->language && !file_exists(Yii::getAlias($this->sourcePath . "/lang/summernote-{$language}.js"))) {
                $language = $fallbackLanguage;
            }
            $this->js[] = "lang/summernote-$language.js";
        }
        parent::registerAssetFiles($view);
    }
}