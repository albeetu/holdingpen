<?

require_once 'config.inc.php';

class orrMergeExtract
{
  public function __construct($list,$filename,$header)
  {
    print "-----Merging for {$filename}-----\n";
    $merge = fopen($filename,"w");
    fwrite($merge,$header);
    foreach($list as $file)
    {
      print "-----Merge in progress: {$file}-----\n";
      $lines = file($file);
      array_shift($lines);
      print_r($lines);
      fwrite($merge,implode($lines));
    }
    fclose($merge);
  }
}

$test = new orrMergeExtract($config['Compliant'],$config['CompliantListName'],$config['orrExtract']);
$test2 = new orrMergeExtract($config['NonCompliantList'],$config['NonCompliantListName'],$config['orrExtract']);
?>
