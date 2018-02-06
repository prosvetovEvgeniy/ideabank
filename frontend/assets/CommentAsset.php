<?php

namespace frontend\assets;


use yii\web\AssetBundle;

class CommentAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/src';

    public $css = [

    ];

    public $js = [
        'js/comment/CommentReply.js',
        'js/comment/CommentLike.js',
        'js/comment/CommentEdit.js'
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