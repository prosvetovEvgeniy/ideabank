<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class DeleteNoticeAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/src';

    public $css = [

    ];

    public $js = [
        'js/notice/NoticeDelete.js'
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