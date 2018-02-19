<?php

namespace common\components\widgets;

use yii\base\Widget;

/**
 * Class ProjectSearchWidget
 * @package common\components\widgets
 *
 * @property string $routeToSearchAction
 * @property string $searchValue;
 */
class ProjectSearchWidget extends Widget
{
    //путь к экшену для поиска
    protected $routeToSearchAction = '/project/search';

    //название проекта, который ищем
    public $searchValue;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('project-search-form', [
            'routeToSearchAction' => $this->routeToSearchAction,
            'searchValue'         => $this->searchValue
        ]);
    }
}