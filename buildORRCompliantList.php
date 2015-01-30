<?php

require_once 'lib/config.inc.php';
require_once 'buildSCDExtract.php';
require_once 'hostProfileStory.php';
require_once 'lib/utils.inc.php';

class orrCompliantAssets
{
  
  protected $unique_assets;
  protected $assets_not_in_SCD;
  protected $scd;
  protected $report_date;
  
  public function __construct($orrCompliantFile, $SCDList = null)
  {
    $this->scd = $SCDList;
    print("--Importing RAW ORR Compliant Extract --\n");
    $this->compliantCSV = new parseCSV($orrCompliantFile);
    $this->unique_assets = array_unique(array_column($this->compliantCSV->data,"Host Name"));
    $this->orr_filename = $orrCompliantFile;
    $this->report_date = $this->compliantCSV->data[0]["Sourced on"];
  }

  public function dashboard()
  {
    print("--ORR Compliant Assets Dashboard for {$this->report_date} --\n");
    ## count the number of records
    print(count($this->compliantCSV->data)." records in compliant ORR list.\n");
    ## count the number of unique hosts
    print(count($this->unique_assets). " unique hosts in compliant ORR list.\n");
    ## scrubed count against SCD
    print(count($this->assets_not_in_SCD)." hosts recommended to be added to SCD.\n");
  }
  public function getUniqueAssets()
  {
    return $this->unique_assets;
  }
  public function getReportDate()
  {
    return $this->report_date;
  }
  
  public function getAssetCount()
  {
    return count($this->unique_assets);
  }
  
  protected function unique_list_SCDscrubbed()
  {

  } 

# build list
# build stats
# return unique list of hosts
# Compare what's in SCD
# 

}
