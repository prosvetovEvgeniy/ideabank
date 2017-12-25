<?php

namespace frontend\assets;


use yii\web\AssetBundle;

class TaskLikeAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/src';

    public $css = [

    ];

    public $js = [
        'js/comment/TaskLike.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        //$this->publishOptions = ['forceCopy' => true];
    }
}