<?php

function cmp($a,$b)
{
  /*
   print "--a--\n";
   print ($a->{'Sourced on'}."\n");
   print "--b--\n";
   print ($b->{'Sourced on'}."\n");
  */
   return (strtotime(str_replace('/','-',$a->{'Sourced on'})) > strtotime(str_replace('/','-',$b->{'Sourced on'})));
}

class hostProfileStory
{
  protected $subject;
  protected $file;
  protected $times_reported;
  protected $score;
  protected $latest_entry;
  protected $latest_entry_CSP;
  protected $latest_entry_PAR;
  protected $latest_entry_ENV;

  //protected $host_story;
  function __construct($hostname)
  {
    // read in json
    $this->score = 0;
    $json_string = file_get_contents("output/hostProfiles/{$hostname}.profile.json");
    $host = json_decode($json_string);
    //print("before\n");
    //print_r($host);
    // sort by 'Sourced on'
    usort($host,"cmp");
    //print("after\n");
    //print_r($host);
    // order elements by 'Sourced on'
    $this->subject = $host;
    //print("count =".count($this->subject)."\n");
    $this->times_reported = count($this->subject);
    $this->file = fopen("output/hostProfiles/{$hostname}.story","w") or die();
    fwrite($this->file,$hostname."\n");
    fwrite($this->file,$this->subject[0]->{"DNS Name"}."\n");
    fwrite($this->file,$this->subject[0]->{"IP Address"}."\n");
    fwrite($this->file,$this->times_reported."\n");
    $latest_entry_seconds = max(array_map( function($element) {return strtotime(str_replace('-','/',$element->{"Sourced on"}));}, $this->subject));
    $this->latest_entry = date('m/d/Y',$latest_entry_seconds);
    fwrite($this->file,$this->latest_entry."\n");
    $this->story("CSP");
    $this->story("PAR");
    $this->story("ENV");
    //fwrite($this->file,$this->score,"\n");
    fclose($this->file);
  }

  protected function story($component)
  {
   $readings = 0; 
   $chances = 0;
   $sp_date="";
   $latest_reading = "1-1-2000";
   foreach($this->subject as $story_point)
   {
      if (!empty($story_point->{"Seen by {$component}"})) 
      {
        $sp_date = str_replace('-','/',$story_point->{"Seen by {$component}"}); 
        $readings++;
        if (strtotime($sp_date) > strtotime($latest_reading)) { $latest_reading = $sp_date; }
      }
      $chances++;
    }
    if ($readings) 
    { 
      //print "$component Passed\n"; 
      fwrite($this->file,"1\n"); 
      if ($latest_reading !== "1-1-2000" )
      {
         fwrite($this->file,$latest_reading."\n");
      }
      else
      {
         fwrite($this->file,"\n");
      }
      $this->score++;
    } 
    else 
    { 
      //print "$component Failed\n";
      fwrite($this->file,"0\n");
      fwrite($this->file,"\n");
    }
  }
}

//$host_test = new hostProfileStory('ceapp1855');
