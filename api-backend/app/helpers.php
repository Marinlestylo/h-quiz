<?php

function getAllPossibleValuesFromEnum($table, $field)
{
    $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")[0]->Type;
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}