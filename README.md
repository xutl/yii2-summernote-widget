# yii2-summernote-widget

适用于YII2的一个富文本编辑器

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist xutl/yii2-summernote-widget
```

or add

```json
"xutl/yii2-summernote-widget": "~1.0.0"
```

to the `require` section of your composer.json.

使用
----

```php
<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use xutl\summernote\SummerNoteAction;
class MyVoteController extends Controller
{
    public function actions()
    {
        return [
            'sn-upload' => [
                'class' => SummerNoteAction::className(),
                'onComplete' => function ($filename, $params) {
                    // Do something with file
                    //返回图像的Url地址
                }
            ],
        ];
    }
}
````
widget:

```php
use xutl\summernote\SummerNote;

<?= $form->field($model, 'description')->widget(SummerNote::className(), [
    'uploadUrl'=>'/file/sn-upload'
]);?>
````