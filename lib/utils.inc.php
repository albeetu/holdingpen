<?php

function array_column($array, $column)
{
  $ret = array();
  foreach ($array as $row) $ret[] = $row[$column];
  return $ret;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
