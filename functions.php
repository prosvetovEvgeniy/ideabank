<?php

function debug($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    exit();
}

function dump($data)
{
    var_dump($data);

    exit();
}