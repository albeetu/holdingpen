<?php

require_once 'buildSCDExtract.php';
require_once 'hostProfileStory.php';
require_once 'lib/utils.inc.php';

class orrNonCompliantAssets
{
  
  protected $unique_assets;
  protected $assets_not_in_SCD;
  protected $scd;
  protected $failed_CSP;
  protected $failed_PAR;
  protected $failed_ENV;
  protected $failed_all;
  protected $noncompliantCSV;
  protected $day_range; #
  protected $report_date;
  
  public function __construct($orrNonCompliantFile, $SCDList = null)
  {

    # pass in a procesed orrNonCompliant host list.
    $this->scd = $SCDList;
    print("--Importing RAW ORR NonCompliant Extract :: $orrNonCompliantFile --\n");
    $this->noncompliantCSV = new parseCSV($orrNonCompliantFile);
    $this->unique_assets = array_unique(array_column($this->noncompliantCSV->data,"Host Name"));
    $this->num_unique_assets = count($this->unique_assets);
    $this->report_date = $this->noncompliantCSV->data[0]["Sourced on"];
    $this->orr_filename = $orrNonCompliantFile;
    $this->failed_CSP = $this->failed_set("CSP");
    $this->failed_PAR = $this->failed_set("PAR");
    $this->failed_ENV = $this->failed_set("ENV");
    $this->failed_ALL = $this->failed_set("ALL");
  }
  
 

  public function dashboard()
  {
    print("--ORR non compliant assets dashboard for {$this->report_date} --\n");
    ## count the number of records
    print(count($this->noncompliantCSV->data)." records in non-compliant ORR list.\n");
    ## count the number of unique hosts
    print($this->num_unique_assets." unique hosts in non-compliant ORR list.\n");
    ## scrubed count against SCD
    print(count($this->assets_not_in_SCD)." hosts recommended to be added to SCD.\n");
    print($this->gotNumFailed("CSP")." hosts failed CSP\n");
    print($this->gotNumFailed("PAR")." hosts failed PAR\n");
    print($this->gotNumFailed("ENV")." hosts failed ENV\n");
    print($this->gotNumFailed("ALL")." hosts failed all components\n");
  }
  public function csvRow()
  {
	return "{$this->report_date},{$this->num_unique_assets},{$this->gotNumFailed("CSP")},{$this->gotNumFailed("PAR")},{$this->gotNumFailed("ENV")},{$this->gotNumFailed("ALL")}\n";
  }
  public function getUniqueAssets()
  {
  }
  public function getAssetCount()
  {
    return $this->num_unique_assets;
  }

  public function getReportDate()
  {
     return $this->report_date;
  }

  public function gotNumFailed($value)
  {
    switch($value)
    {
      case "CSP":
         return count($this->failed_CSP);
      break;
      case "PAR":
         return count($this->failed_PAR);
      break;
      case "ENV":
         return count($this->failed_ENV);
      break;
      case "ALL":
         return count($this->failed_ALL);
      break;
      default:
    }
  }
 
  protected function failed_set($component)
  {
//    $this->noncompliantCSV->conditions = '';
//    $this->noncompliantCSV->parse($this->noncompliantCSV);
    $list = clone $this->noncompliantCSV;
    
    switch($component)
    {
      case "CSP":
        $list->conditions = 'Seen by CSP is ""';
        $list->parse($this->orr_filename);
        return $list->data;
      break;
      case "PAR":
        $list->conditions = 'Seen by PAR is ""';
        $list->parse($this->orr_filename);
        return $list->data;
      break;
      case "ENV":
        $list->conditions = 'Seen by ENV is ""';
        $list->parse($this->orr_filename);
        return $list->data;
      break;
      case "ALL":
        $list->conditions = 'Seen by CSP is "" AND Seen by PAR is "" AND Seen by ENV is ""';
        $list->parse($this->orr_filename);
        return $list->data;
      break;
      default:
    }

  }
 
  protected function unique_list_SCDscrubbed()
  {

  } 

}
/*
>>>>>>> dev-dashboard
print ("------Building compliant ORR Asset report ------\n");
$orrCompliantList = new orrNonCompliantAssets("output/orrExtracts/orrExtract11-24-2014-noncompliant.csv");
$orrCompliantList->dashboard();
$orrCompliantList = new orrNonCompliantAssets("output/orrExtracts/orrExtract12-3-2014-noncompliant.csv");
$orrCompliantList->dashboard();
$orrCompliantList = new orrNonCompliantAssets("output/orrExtracts/orrExtract12-19-2014-noncompliant.csv");
$orrCompliantList->dashboard();
$orrCompliantList = new orrNonCompliantAssets("output/orrExtracts/orrExtract1-9-2015-noncompliant.csv");
$orrCompliantList->dashboard();
$orrCompliantList = new orrNonCompliantAssets("output/orrExtracts/orrExtract1-13-2015-noncompliant.csv");
$orrCompliantList->dashboard();
$orrCompliantList = new orrNonCompliantAssets("output/orrExtracts/orrExtract1-19-2015-noncompliant.csv");
$orrCompliantList->dashboard();
<<<<<<< HEAD
=======
*/
