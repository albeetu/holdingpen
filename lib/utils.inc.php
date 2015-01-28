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

function timecheckfile($filename)
{
  if (file_exists($filename))
  {
  }
}

function cmp_reportdate($a,$b)
{
  /*
   print "--a--\n";
   print ($a->{'Sourced on'}."\n");
   print "--b--\n";
   print ($b->{'Sourced on'}."\n");
  */
   return (strtotime(str_replace("-","/",$a->getReportDate())) > strtotime(str_replace("-","/",($b->getReportDate()))));
}

