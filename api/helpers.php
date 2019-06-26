<?php

function toCamelCase($texto)
{
    $texto = str_replace('_', ' ', $texto);
    $texto = ucwords($texto);
    $texto = str_replace(' ', '', $texto);

    return $texto;
}

function toUnderscore($texto)
{
    $texto = preg_replace('/(?<!^)[A-Z]/', '_$0', $texto);
    $texto = strtolower($texto);

    return $texto;
}