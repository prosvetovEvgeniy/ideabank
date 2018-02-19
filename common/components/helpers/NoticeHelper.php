<?php

namespace common\components\helpers;

use common\models\entities\UserEntity;
use common\models\repositories\user\UserRepository;

/**
 * Class NoticeHelper
 * @package common\components\helpers
 *
 * @property array $matches
 */
class NoticeHelper
{
    private const PATTERN = '/#\w{2,' . UserEntity::USERNAME_MAX_LENGTH . '}/';

    private $matches;

    public function __construct(string $text)
    {
        preg_match_all(self::PATTERN, $text, $matches);

        /**
         * получаем массив полных уникальных вхождений шаблона
         */
        $this->matches = array_values(array_unique($matches[0]));
    }

    /**
     * @return UserEntity[]
     */
    public function getNoticedUsers()
    {
        $userNames = [];

        foreach ($this->matches as $match) {
            $userNames[] = explode('#', $match)[1];
        }

        return UserRepository::instance()->findAll(['in', 'username', $userNames]);
    }
}