<?php

namespace common\components\helpers;
use common\models\interfaces\IRepository;
use Yii;


/**
 * Class FileHelper
 * @package common\components\helpers
 *
 * @property string $fileExtension
 * @property IRepository $repository
 */
class FileHelper
{
    private $fileExtension;
    private $repository;

    /**
     * FileHelper constructor.
     * @param string $fileExtension
     * @param IRepository $repository
     */
    public function __construct(string $fileExtension, IRepository $repository)
    {
        $this->fileExtension = $fileExtension;
        $this->repository = $repository;
    }

    /**
     * @param string $fieldName
     * @param int $hashLength
     * @return mixed|string
     * @throws \yii\base\Exception
     */
    public function getHash(string $fieldName, int $hashLength = 16)
    {
        $hashName = Yii::$app->security->generateRandomString($hashLength) . '.' . $this->fileExtension;
        $file = $this->repository::instance()->findOne([$fieldName => $hashName]);

        return (!$file) ? $hashName : $this->getHash($this->fileExtension) ;
    }
}