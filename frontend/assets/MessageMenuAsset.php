<?php

namespace frontend\assets;


use yii\web\AssetBundle;

class MessageMenuAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/src';

    public $css = [

    ];

    public $js = [
        'js/message/MessageMenu.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        $this->publishOptions = ['forceCopy' => true];
    }
}