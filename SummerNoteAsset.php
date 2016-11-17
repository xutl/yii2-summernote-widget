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
 * Class UEditorAsset
 * @package xutl\ueditor
 */
class SummerNoteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/xutl/yii2-summernote-widget/assets';

    public $js = [
        'summernote.min.js',
        'plugin/codewrapper/summernote-ext-codewrapper.min.js',
    ];

    public $css = [
        'summernote.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}