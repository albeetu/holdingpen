<?php

require_once 'config.inc.php';
require_once 'parsecsv-for-php/parsecsv.lib.php';

class CISOContacts
{
  protected $ciso_list;
  protected $filename;
#public functions
  public function __construct($filename)
  {
	$this->filename = $filename;
	$this->ciso_list = new parseCSV($filename);
  }

  public function get_CISOmember($business_unit)
  {
	#condition
        #Business Unit is $business_unit

	$this->ciso_list->conditions = "Business Unit is {$business_unit}";
        $this->ciso_list->parse($this->filename);
	print_r($this->ciso_list->data);
  }

  public function testClass()
  {     
	print "--Testing CISOContacts class\n";
	print "--Pulling contacts from {$this->filename}\n";
	//print_r($this->ciso_list->data);
        print "--CISO for Australia\n";
        $this->get_CISOmember("Australia");
        print "--CISO for Switzerland\n";
        $this->get_CISOmember("Switzerland");
        print "--CISO for not a BU\n";
        $this->get_CISOmember("not a BU");
        print "--Test completed\n";
  }
}
print "-- Producing CISO contact list \n";
$testCISO = new CISOContacts($config['CISOcontacts_file']);
$testCISO->testClass();
