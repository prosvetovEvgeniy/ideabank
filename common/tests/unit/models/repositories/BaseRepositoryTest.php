<?php

namespace common\tests\models\repositories;


class BaseRepositoryTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $paths = [
        'company' => 'common\models\activerecords\Company',
        'comment' => 'common\models\activerecords\Comment',
        'commentLike' => 'common\models\activerecords\CommentLike',
        'message' => 'common\models\activerecords\Message',
        'notice' => 'common\models\activerecords\Notice',
        'participant' => 'common\models\activerecords\Participant',
        'project' => 'common\models\activerecords\Project',
        'task' => 'common\models\activerecords\Task',
        'taskLike' => 'common\models\activerecords\TaskLike',
        'users' => 'common\models\activerecords\Users',
    ];
}