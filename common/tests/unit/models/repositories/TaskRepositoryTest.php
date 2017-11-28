<?php

namespace common\tests\models\repositories;


use common\models\entities\TaskEntity;
use common\models\repositories\TaskRepository;

class TaskRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'title'           => 'title',
        'content'         => 'content',
        'authorId'        => 1,
        'projectId'       => 1,
        'status'          => 0,
        'visibilityArea'  => 1,
        'parentId'        => null,
        'plannedEndAt'    => 1511431761,
        'endAt'           => 1511431761,
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
    }

    protected function _after()
    {
    }

    // tests
    public function testInstance()
    {
        $taskRepository = TaskRepository::instance();

        $this->assertEquals($taskRepository, new TaskRepository());
    }

    public function testAdd()
    {
        /** @var TaskEntity $task */
        $task = TaskRepository::instance()->add(
            new TaskEntity(
                $this->data['title'],
                $this->data['content'],
                $this->data['authorId'],
                $this->data['projectId'],
                $this->data['status'],
                $this->data['visibilityArea'],
                $this->data['parentId'],
                $this->data['plannedEndAt'],
                $this->data['endAt']
            )
        );

        $this->tester->seeRecord($this->paths['task'], ['id' => $task->getId()]);
    }

    public function testUpdate()
    {
        /** @var TaskEntity $task */
        $task = TaskRepository::instance()->add(
            new TaskEntity(
                $this->data['title'],
                $this->data['content'],
                $this->data['authorId'],
                $this->data['projectId'],
                $this->data['status'],
                $this->data['visibilityArea'],
                $this->data['parentId'],
                $this->data['plannedEndAt'],
                $this->data['endAt']
            )
        );

        $task->setProjectId($this->dataForSetters['projectId']);
        $task->setContent($this->dataForSetters['content']);
        $task->setEndAt($this->dataForSetters['endAt']);
        $task->setPlannedEndAt($this->dataForSetters['plannedEndAt']);
        $task->setParentId($this->dataForSetters['parentId']);
        $task->setStatus($this->dataForSetters['status']);
        $task->setVisibilityArea($this->dataForSetters['visibilityArea']);
        $task->setTitle($this->dataForSetters['title']);
        $task->setAuthorId($this->dataForSetters['authorId']);

        $this->assertEquals(TaskRepository::instance()->update($task), $task);
    }

    public function testDelete()
    {
        /** @var TaskEntity $task */
        $task = TaskRepository::instance()->add(
            new TaskEntity(
                $this->data['title'],
                $this->data['content'],
                $this->data['authorId'],
                $this->data['projectId'],
                $this->data['status'],
                $this->data['visibilityArea'],
                $this->data['parentId'],
                $this->data['plannedEndAt'],
                $this->data['endAt']
            )
        );

        TaskRepository::instance()->delete($task);

        $this->tester->seeRecord($this->paths['task'], ['id' => $task->getId(), 'deleted' => true]);
    }
}