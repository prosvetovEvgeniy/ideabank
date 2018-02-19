<?php

namespace common\components\widgets;

use yii\base\Widget;

class MessageMenuWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('messagemenu');
    }
}