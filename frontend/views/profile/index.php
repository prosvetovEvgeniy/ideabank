<?php

use common\components\widgets\ProfileMenuWidget;
use frontend\assets\SubMenuAsset;

SubMenuAsset::register($this);
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= ProfileMenuWidget::widget() ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-9">

    </div>
</div>


