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

$config['NonCompliantList'] = Array(
"output/orrExtracts/orrExtract11-24-2014-noncompliant.csv",
"output/orrExtracts/orrExtract12-3-2014-noncompliant.csv",
"output/orrExtracts/orrExtract12-19-2014-noncompliant.csv",
"output/orrExtracts/orrExtract1-9-2015-noncompliant.csv",
"output/orrExtracts/orrExtract1-13-2015-noncompliant.csv",
"output/orrExtracts/orrExtract1-19-2015-noncompliant.csv",
"output/orrExtracts/orrExtract1-22-2015-noncompliant.csv"
);

$config['Compliant'] = Array(
"output/orrExtracts/orrExtract11-24-2014-compliant.csv",
"output/orrExtracts/orrExtract12-3-2014-compliant.csv",
"output/orrExtracts/orrExtract12-19-2014-compliant.csv",
"output/orrExtracts/orrExtract1-9-2015-compliant.csv",
"output/orrExtracts/orrExtract1-13-2015-compliant.csv",
"output/orrExtracts/orrExtract1-19-2015-compliant.csv",
"output/orrExtracts/orrExtract1-22-2015-compliant.csv"
);


