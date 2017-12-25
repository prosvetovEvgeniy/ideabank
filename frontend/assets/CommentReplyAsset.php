<?php

namespace frontend\assets;


use yii\web\AssetBundle;

class CommentReplyAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/src';

    public $css = [

    ];

    public $js = [
        'js/comment/CommentReply.js'
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