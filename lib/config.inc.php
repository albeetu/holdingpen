<?php

#directories
$config['home'] = "/home/albeetu/holdingpen";
$config['holdingpencsvs'] = "{$config['home']}/output/holdingpencsvs/";
$config['orrExtracts'] = "{$config['home']}/output/orrExtracts/";
$config['CISOcontacts'] = "{$config['home']}/output/CISOContacts/";
$config['CISOcontacts_file'] = "{$config['CISOcontacts']}CISOContacts.csv";

#files to be used
$config['SCDExtract'] = "SCDExtract.csv";
$config['CISOExtract'] = "CISOContact.csv";
$config['CompliantListName'] = "orrCompliantPresent.csv";
$config['NonCompliantListName'] = "orrNonCompliantPresent.csv";

#filelist

$config['holdingpencsvslist'] = glob($config['holdingpencsvs']."*.csv");
$config['NonCompliantList'] = glob($config['orrExtracts']."orrExtract*-noncompliant.csv");
$config['Compliant'] = glob($config['orrExtracts']."orrExtract*-compliant.csv");

#csv headers

$config['orrExtract'] = "Host Name,DNS Name,IP Address,Region,Seen by CSP,Seen by PAR,Seen by ENV,Compliant,Days out of Compliance,Sourced on\n";

