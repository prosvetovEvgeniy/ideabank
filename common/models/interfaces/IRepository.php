<?php

namespace common\models\interfaces;


interface IRepository
{
    /**
     * @return IRepository
     */
    public static function instance(): IRepository;

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @param array $with
     * @return IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    );

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int;
}