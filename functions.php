<?php

function debug($data = "TEST")
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    exit();
}

function dump($data = "TEST")
{
    echo '<pre>';

    var_dump($data);

    echo '</pre>';

    exit();
}