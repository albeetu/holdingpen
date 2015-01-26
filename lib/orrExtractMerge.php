<?

require_once 'config.inc.php';

class orrMergeExtract
{
  public function __construct($list,$filename,$header)
  {
    $merge = fopen($filename,"w");
    fwrite($merge,$header);
    foreach($list as $file)
    {
      $lines = file($file);
      array_shift($lines);
      fwrite($merge,implode($lines));
    }
    fclose($merge);
  }
}

$test = new orrMergeExtract($config['Compliant'],$config['CompliantListName'],$config['orrExtract']);
$test2 = new orrMergeExtract($config['NonCompliantList'],$config['NonCompliantListName'],$config['orrExtract']);
