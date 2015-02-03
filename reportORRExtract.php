<?
// This will take a build raw non orr compliant asset list and perform analysis reports.
require_once 'buildSCDExtract.php';
require_once 'hostProfileStory.php';
require_once 'lib/utils.inc.php';

class nonCompliantORRExtractAnalysis
{
  protected $orrCSV;
  protected $orr_filename;
  protected $scd;
  protected $orrCount;
  protected $unique_orr_hosts;
  protected $extract_dates;
  
  public function __construct($orr_filename,$scd_list, $filterString = null, $rebuildProfiles = false)
  {
    print("--Building ORR non compliant device analysis reports --\n");
    $time_start = microtime(true);
    $this->scd = $scd_list;
    //create new csv object, apply filter if need be, parse object
    print("--Importing RAW ORR Extract --\n"); 
    $this->orrCSV = new parseCSV($orr_filename);
    $this->orr_filename = $orr_filename;
    $this->orrCount = count($this->orrCSV->data);
    $this->extract_dates = array_unique(array_column($this->orrCSV->data,"Sourced on"));
    $this->unique_orr_hosts = array_unique(array_column($this->orrCSV->data,"Host Name"));
    $unique_host_count = count($this->unique_orr_hosts);
    print("--Build non compliant ORR Asset for {$unique_host_count} profiles-- \n");
    if ($rebuildProfiles) { $this->hostProfiles(); }
    print("--Profile build complete --\n");
    $total_time = microtime(true) - $time_start;
    print("--Time taken: ".$total_time." seconds\n\n");

    ## Todo - report on (unique hosts minus the hosts in SCD)
    ##      - build devices that have been injected list
  }
  public function dashboard()
  {
    print("--ORR Dashboard Statistics--\n");
    //determine stats of files
    //number of records
    print(count($this->extract_dates)." extract dates\n");
    print("Extract Date List :: \n");
    print_r($this->extract_dates);
    print("Inbox statistics :: \n");
    print($this->orrCount." total records in ORR Extract\n");
    //number of unique hosts
    print(count($this->unique_orr_hosts)." unique non compliant hosts\n");
    print("Outbox statistics :: \n");
    //print_r($this->unique_orr_hosts);
    //print_r($orrAnalysis->nowinSCD());
    print(count($this->nowinSCD())." assets in the ORR extract now in SCD\n");
    # number printed
    print("TODO: Tally injection total\n");
    print("--ORR Dashboard complete--\n\n");
  }

  public function filter($filterString)
  {
    // filter object, reparse
  }

  public function nowinSCD()
  {
    return array_intersect($this->unique_orr_hosts,$this->scd->getSCDHosts());
  }
  public function getHostProfile($host_list)
  {
     foreach ($host_list as $host)
     {
     }
  }
  public function hostProfiles()
  {
    // determine some kind of trend for all unique and non-compliant hosts.
    foreach ($this->unique_orr_hosts as $host)
    {
       $time_start = microtime(true);
       print ("Building profile for {$host}... ");
       $this->orrCSV->conditions = "Host Name is {$host}";
       // Don't care for this architecture...will be slow.
       $this->orrCSV->parse($this->orr_filename);
       file_put_contents("output/hostProfiles/{$host}.profile.json",json_encode($this->orrCSV->data)."\n");
       file_put_contents("output/hostProfiles/{$host}.profile",print_r($this->orrCSV->data,true));
       print (microtime(true) - $time_start." seconds\n");
       //file_put_contents("output/hostProfiles{$host}.profile",print_r(hostAnalysis($this->orrCSV->data,true));
    }
    print(count($this->unique_orr_hosts)." host profiles created\n");
  }
  
  public function reportCard()
  {
    $header = "Host Name,DNS Name,IP Address,Times Reported,Asset Last Reported,CSP,Last Seen CSP,PAR,Last Seen PAR,ENV,Last Seen ENV";
    $reportCard = fopen("reportORRCard.csv","w");
    fwrite($reportCard,$header."\n");
    foreach ($this->unique_orr_hosts as $host)
    {
      $story = new hostProfileStory($host);
      $lines = array_map('trim',file("output/hostProfiles/{$host}.story"));
      // add in calculations
      $lines_csv = implode(",",$lines);
      fwrite($reportCard,$lines_csv."\n");
    }
    fclose($reportCard);
  }
  
  protected function hostAnalysis($host_history)
  {
    // this will build a story on the host.
    // timestamp
  }
}
// Take the fully compiled non compliant report and generates analysis reports.

$nonCompliantOrrAnalysis = new nonCompliantORRExtractAnalysis("orrNonCompliantPresent.csv",new SCDExtract("output/SCDextract/SCD1-29-2015.csv"),null,true);
$nonCompliantOrrAnalysis->dashboard();
print("---Assets now in SCD from current extract---\n");
// print_r($orrAnalysis->nowinSCD());
print(count($nonCompliantOrrAnalysis->nowinSCD())." assets in the ORR extract now in SCD\n");
print("--TODO: Print hosts in ORR extract that are not in SCD (refined non compliant list?) --\n");
print("--TODO: List of hosts that have no components working (CSP/PAR/ENV)\n");
print("--TODO: List of hosts that fell in and out of the ORR extract (not sure what this fully means)\n");
print("--Build orrReportCard -- \n");
$nonCompliantOrrAnalysis->reportCard();

//print("--Host Profiles--");
//$orrAnalysis->hostProfiles();
