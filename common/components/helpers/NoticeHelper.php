<?php

namespace common\components\helpers;


use common\models\entities\UserEntity;
use common\models\repositories\UserRepository;

/**
 * Class NoticeHelper
 * @package common\components\helpers
 */
class NoticeHelper
{
    private const PATTERN = '/#\w{2,' . UserEntity::USERNAME_MAX_LENGTH . '}/';

    /**
     * @param string $text
     * @return UserEntity[]
     */
    public static function getNoticedUsers(string $text)
    {
        preg_match_all(self::PATTERN, $text, $matches);

        /**
         * получаем массив полных уникальных вхождений шаблона
         */
        $uniqueMatches = array_values(array_unique($matches[0]));

        $userNames = [];

        foreach ($uniqueMatches as $match)
        {
            $userNames[] = explode('#', $match)[1];
        }

        return UserRepository::instance()->findAll(['in', 'username', $userNames]);
    }
}
