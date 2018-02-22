<?php

namespace common\components\exceptions;

use yii\base\ExitException;

class AccessDeniedException extends ExitException
{
    public function __construct(int $status = 0, ?string $message = null, int $code = 0, \Exception $previous = null)
    {
        parent::__construct($status, $message, $code, $previous);
    }
}