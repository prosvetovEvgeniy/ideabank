<?php

function debug($data = "TEST", $timeStart = null, $timeEnd = null)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    if($timeStart === null || $timeEnd === null)
    {
        exit();
    }

    echo '<pre>';
    echo 'Выполнено за: ';
    echo ($timeEnd - $timeStart);
    echo ' секунд';
    echo '</pre>';

    exit();
}

function dump($data = "TEST", $timeStart = null, $timeEnd = null)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';

    if($timeStart === null || $timeEnd === null)
    {
        exit();
    }

    echo '<pre>';
    echo 'Выполнено за: ';
    echo ($timeEnd - $timeStart);
    echo ' секунд';
    echo '</pre>';

    exit();
}