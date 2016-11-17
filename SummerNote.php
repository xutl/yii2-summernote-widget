<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\summernote;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class SummerNote
 * @package xutl\summernote
 */
class SummerNote extends InputWidget
{

    public $language;
    public $uploadUrl;
    public $clientOptions = [];

    /**
     * {@inheritDoc}
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        if (!isset ($this->options ['id'])) {
            $this->options ['id'] = $this->getId();
        }

        $this->clientOptions = array_merge([
            'height' => 180,
           'toolbar'=>[
               ['style',['style']],
               ['font', ['bold', 'underline', 'clear']],
               ['fontname', ['fontname']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['table', ['table']],
               ['insert', ['gxcode','link', 'picture', 'video']],
               ['view', ['fullscreen', 'codeview', 'help']]
            ],
            'placeholder' => 'write here...',
//            'codemirror' => [
//                'mode' => 'text/html',
//                'htmlMode' => true,
//                'lineNumbers' => true,
//                'theme' => 'monokai'
//            ],

        ], $this->clientOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $language = $this->language ? $this->language : Yii::$app->language;
        if ($this->hasModel()) {
            echo Html::activeTextArea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textArea($this->name, $this->value, $this->options);
        }
        $view = $this->getView();
        SummerNoteAsset::register($view);
        $assetBundle = SummerNoteLanguageAsset::register($view);
        $assetBundle->language = $language;
        $this->clientOptions['lang'] = $language;
        if (!empty($this->uploadUrl)) {
            $this->clientOptions['callbacks']['onImageUpload'] = new JsExpression("function(files) {for (var i = files.length - 1; i >= 0; i--) {var data = new FormData();data.append(\"file\", files[i]);jQuery.ajax({cache: false,contentType: false,processData: false,data: data,type: \"POST\",dataType : \"json\",url: \"" . Url::to($this->uploadUrl) . "\",success: function(res) {jQuery(\"#{$this->options['id']}\").summernote('insertImage',  res.url, res.originalName);}});}}");
        }
        $options = empty ($this->clientOptions) ? '' : Json::htmlEncode($this->clientOptions);
        $this->view->registerJs("jQuery(\"#{$this->options['id']}\").summernote({$options});");
    }
}