<?
require_once 'lib/parsecsv-for-php/parsecsv.lib.php';
require_once 'lib/utils.inc.php';

class SCDextract
{
  protected $hosts;
  protected $filename;
  protected $host_list;
  
  public function __construct($filename)
  {
	print("Importing SCD Extract {$filename}\n");
	$this->hosts = new parseCSV($filename);
        print(count($this->hosts->data)." assets in SCD.\n");
        $this->filename = $filename;
        $this->host_list = array_column($this->hosts->data,"ASSET NAME");
  }
  protected function resetCSV()
  {
	$this->hosts->conditions = "";
 	$this->hosts->parse($this->filename);
  }
  public function findAsset($asset)
  {
	$this->hosts->conditions = "ASSET NAME is {$asset}";
        print "{$this->hosts->conditions}\n";
        $this->hosts->parse($this->filename);
  }
  
  public function getSCDHosts()
  {
	return $this->host_list;
  }
  public function testClass()
  {
	#print_r($this->hosts->data);
        $this->findAsset('026083F');
        print_r($this->hosts->data);
  }
}
/*
$scd = new SCDextract('output/SCDextract/SCD1-9-2015.csv');
$scd->testClass();
*/
