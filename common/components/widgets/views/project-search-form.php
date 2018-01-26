<?php

/**
 * @var string $routeToSearchAction
 * @var string $searchValue
 */
?>

<form method="GET" action="<?= $routeToSearchAction ?>">
    <div class="input-group search-project-form">
        <input value="<?= $searchValue ?>" name="projectName" type="text" class="form-control" placeholder="Найти проект">
        <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Найти</button>
        </span>
    </div>
</form>