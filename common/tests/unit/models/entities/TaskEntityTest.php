<?php
namespace common\tests\models\entities;


use common\models\entities\CommentEntity;
use common\models\entities\ProjectEntity;
use common\models\entities\TaskEntity;
use common\models\entities\TaskLikeEntity;
use common\models\entities\UserEntity;
use common\models\repositories\TaskRepository;

class TaskEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var TaskEntity */
    protected $task;

    /** @var array */
    protected $data = [
        'id'              => 1,
        'title'           => 'title',
        'content'         => 'content',
        'authorId'        => 1,
        'projectId'       => 1,
        'status'          => 0,
        'visibilityArea'  => 1,
        'parentId'        => null,
        'plannedEndAt'    => 1511431761,
        'endAt'           => 1511431761,
        'createdAt'       => 1511431761,
        'updatedAt'       => 1511431761,
        'deleted'         => false
    ];

    /** @var array */
    protected $dataForSetters = [
        'title'           => 'new title',
        'content'         => 'new content',
        'authorId'        => 2,
        'projectId'       => 2,
        'status'          => 3,
        'visibilityArea'  => 2,
        'parentId'        => 1,
        'plannedEndAt'    => 1511431761,
        'endAt'           => 1511431761,
    ];

    protected function _before()
    {
        $this->task = new TaskEntity(
            $this->data['title'],
            $this->data['content'],
            $this->data['authorId'],
            $this->data['projectId'],
            $this->data['status'],
            $this->data['visibilityArea'],
            $this->data['parentId'],
            $this->data['plannedEndAt'],
            $this->data['endAt'],
            $this->data['id'],
            $this->data['createdAt'],
            $this->data['updatedAt'],
            $this->data['deleted']
        );
    }

    protected function _after()
    {
    }

    // #################### TESTS OF GETTERS ######################

    public function testGetId()
    {
        $this->assertEquals($this->task->getId(), $this->data['id']);
    }

    public function testGetTitle()
    {
        $this->assertEquals($this->task->getTitle(), $this->data['title']);
    }

    public function testGetAuthorId()
    {
        $this->assertEquals($this->task->getAuthorId(), $this->data['authorId']);
    }

    public function testGetProjectId()
    {
        $this->assertEquals($this->task->getProjectId(), $this->data['projectId']);
    }

    public function testGetStatus()
    {
        $this->assertEquals($this->task->getStatus(), $this->data['status']);
    }

    public function testGetVisibilityArea()
    {
        $this->assertEquals($this->task->getVisibilityArea(), $this->data['visibilityArea']);
    }

    public function testGetParentId()
    {
        $this->assertEquals($this->task->getParentId(), $this->data['parentId']);
    }

    public function testGetPlannedEndAt()
    {
        $this->assertEquals($this->task->getPlannedEndAt(), $this->data['plannedEndAt']);
    }

    public function testGetEndAt()
    {
        $this->assertEquals($this->task->getEndAt(), $this->data['endAt']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->task->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->task->getUpdatedAt(), $this->data['updatedAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->task->getDeleted(), $this->data['deleted']);
    }

    // #################### TESTS OF SETTERS ######################

    public function testSetTitle()
    {
        $this->task->setTitle($this->dataForSetters['title']);
        
        $this->assertEquals($this->task->getTitle(), $this->dataForSetters['title']);
    }

    public function testSetContent()
    {
        $this->task->setContent($this->dataForSetters['content']);

        $this->assertEquals($this->task->getContent(), $this->dataForSetters['content']);
    }

    public function testSetAuthorId()
    {
        $this->task->setAuthorId($this->dataForSetters['authorId']);

        $this->assertEquals($this->task->getAuthorId(), $this->dataForSetters['authorId']);
    }

    public function testSetProjectId()
    {
        $this->task->setProjectId($this->dataForSetters['projectId']);

        $this->assertEquals($this->task->getProjectId(), $this->dataForSetters['projectId']);
    }

    public function testSetStatus()
    {
        $this->task->setStatus($this->dataForSetters['status']);

        $this->assertEquals($this->task->getStatus(), $this->dataForSetters['status']);
    }

    public function testSetVisibilityArea()
    {
        $this->task->setVisibilityArea($this->dataForSetters['visibilityArea']);

        $this->assertEquals($this->task->getVisibilityArea(), $this->dataForSetters['visibilityArea']);
    }

    public function testSetParentId()
    {
        $this->task->setParentId($this->dataForSetters['parentId']);

        $this->assertEquals($this->task->getParentId(), $this->dataForSetters['parentId']);
    }

    public function testSetPlannedEndAt()
    {
        $this->task->setPlannedEndAt($this->dataForSetters['plannedEndAt']);

        $this->assertEquals($this->task->getPlannedEndAt(), $this->dataForSetters['plannedEndAt']);
    }

    public function testSetEndAt()
    {
        $this->task->setEndAt($this->dataForSetters['endAt']);

        $this->assertEquals($this->task->getEndAt(), $this->dataForSetters['endAt']);
    }
    
    
    // #################### TESTS OF RELATIONS ######################


    public function testGetProjectCheckOnClassName()
    {
        $task = TaskRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($task->getProject()), ProjectEntity::class);
    }

    public function testGetAuthorCheckOnClassName()
    {
        $task = TaskRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($task->getAuthor()), UserEntity::class);
    }

    public function testGetTaskLikesCheckOnArray()
    {
        $task = TaskRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($task->getTaskLikes()), true);
    }

    public function testGetTaskLikesCheckOnClassName()
    {
        $task = TaskRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($task->getTaskLikes()[0]), TaskLikeEntity::class);
    }

    public function testGetCommentsCheckOnArray()
    {
        $task = TaskRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($task->getComments()), true);
    }

    public function testGetCommentsCheckOnClassName()
    {
        $task = TaskRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($task->getComments()[0]), CommentEntity::class);
    }


    // #################### TESTS OF LOGIC ######################


}