<?php

/**
 * @var string $routeToSearchAction
 * @var string $searchValue
 */
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <form method="GET" action="<?= $routeToSearchAction ?>" class="center-block">
        <div class="input-group search-project-form">
            <input value="<?= $searchValue ?>" name="projectName" type="text" class="form-control" placeholder="Найти проект">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Найти</button>
            </span>
        </div>
    </form>
</div>
