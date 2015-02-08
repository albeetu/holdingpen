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

function import_csv_to_sqlite(&$pdo, $csv_path, $options = array())
{
	extract($options);
	
	if (($csv_handle = fopen($csv_path, "r")) === FALSE)
		throw new Exception('Cannot open CSV file');
		
	if(!$delimiter)
		$delimiter = ',';
		
	if(!$table)
		$table = preg_replace("/[^A-Z0-9]/i", '', basename($csv_path));
	
	if(!$fields){
		$fields = array_map(function ($field){
			return strtolower(preg_replace("/[^A-Z0-9]/i", '', $field));
		}, fgetcsv($csv_handle, 0, $delimiter));
	}
	
	$create_fields_str = join(', ', array_map(function ($field){
		return "$field TEXT NULL";
	}, $fields));
	
	$pdo->beginTransaction();
	
	$create_table_sql = "CREATE TABLE IF NOT EXISTS $table ($create_fields_str)";
	$pdo->exec($create_table_sql);

	$insert_fields_str = join(', ', $fields);
	$insert_values_str = join(', ', array_fill(0, count($fields),  '?'));
	$insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($insert_values_str)";
	$insert_sth = $pdo->prepare($insert_sql);
	
	$inserted_rows = 0;
	while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {
		$insert_sth->execute($data);
		$inserted_rows++;
	}
	
	$pdo->commit();
	
	fclose($csv_handle);
	
	return array(
			'table' => $table,
			'fields' => $fields,
			'insert' => $insert_sth,
			'inserted_rows' => $inserted_rows
		);

}
