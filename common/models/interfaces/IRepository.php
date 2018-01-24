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
     * @return IEntity | null
     */
    public function findOne(array $condition);

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null);

    /**
     * @param array $condition
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition);
}