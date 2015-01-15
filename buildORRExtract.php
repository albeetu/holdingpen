<?php
require_once 'lib/config.inc.php';
require_once 'parsecsv-for-php/parsecsv.lib.php';

print "-- Building ORR Extract for $argv[1] --\n";
print "Extracting from $argv[1]\n";
$path_parts = pathinfo($argv[1]);
$csv = new parseCSV($argv[1]);
$i=0;
$today = new DateTime();
print "-- Writing extract to {$config['orrExtracts']}orrExtract{$path_parts['filename']}.csv\n";
$orrExtract = fopen("{$config['orrExtracts']}orrExtract{$path_parts['filename']}-noncompliant.csv","w");
$orrExtractCompliant = fopen("{$config['orrExtracts']}orrExtract{$path_parts['filename']}-compliant.csv","w");
$header = "Host Name,DNS Name,IP Address,Region,Seen by CSP,Seen by PAR,Seen by ENV,Compliant,Days out of Compliance,Sourced on\n";
fwrite($orrExtract,$header);
fwrite($orrExtractCompliant,$header);
foreach ($csv->data as $value)
{
   $hosts[$i]["Host Name"] = $value["Host Name"];
   $hosts[$i]["DNS Name"] = $value["DNS Name"];
   $hosts[$i]["IP Address"] = $value["IP Address"];
   $hosts[$i]["Region"] = $value["Region"];
   $hosts[$i]["Seen by CSP"] = (!empty($value["Seen by CSP"]) ? str_replace(".","-",$value["Seen by CSP"]) : '');
   $hosts[$i]["Seen by PAR"] = (!empty($value["Seen by PAR"]) ? str_replace(".","-",$value["Seen by PAR"]) : '');
   $hosts[$i]["Seen by ENV"] = (!empty($value["Seen by ENV"]) ? str_replace(".","-",$value["Seen by ENV"]) : '');
   if (empty($value["Seen by CSP"]) || empty($value["Seen by PAR"]) || empty($value["Seen by ENV"]))
   {
	$csp = (!empty($value["Seen by CSP"]) ? new DateTime(str_replace(".","-",$value["Seen by CSP"])) : new DateTime);
        $par = (!empty($value["Seen by PAR"]) ? new DateTime(str_replace(".","-",$value["Seen by PAR"])) : new DateTime);
        $env = (!empty($value["Seen by ENV"]) ? new DateTime(str_replace(".","-",$value["Seen by ENV"])) : new DateTime);
	$hosts[$i]["Compliant"] = "No";
        $outOfCompliance = max(date_diff($today,$csp)->days,date_diff($today,$par)->days,date_diff($today,$env)->days);
        $hosts[$i]["Days out of Compliance"] = $outOfCompliance;
   }
   else
   {
	$hosts[$i]["Compliant"] = "Yes";
	$hosts[$i]["Days out of Compliance"] = '';
   }
   $hosts[$i]["Sourced on"] = $path_parts['filename'];
   
# print the csv records.
   
   $record = "{$hosts[$i]["Host Name"]},{$hosts[$i]["DNS Name"]},{$hosts[$i]["IP Address"]},{$hosts[$i]["Region"]},{$hosts[$i]["Seen by CSP"]},{$hosts[$i]["Seen by PAR"]},{$hosts[$i]["Seen by ENV"]},{$hosts[$i]["Compliant"]},{$hosts[$i]["Days out of Compliance"]},{$hosts[$i]["Sourced on"]}";
# more filters qualifying to print?
   if ($hosts[$i]["Compliant"] == "No")
   {
     fwrite($orrExtract,$record."\n");
   }
   else
   {
     fwrite($orrExtractCompliant,$record."\n");
   }
   $i++;
}
print "{$i} assets processed\n";
#print_r($hosts);
#print_r(array_unique($old_hosts));
fclose($orrExtract);
fclose($orrExtractCompliant);
print "-- ORR Extaction complete \n";
?>

