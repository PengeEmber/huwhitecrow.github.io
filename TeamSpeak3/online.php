<?php

// load framework library
require_once("libraries/TeamSpeak3/TeamSpeak3.php");

try
{
  // connect to server, authenticate and grab info
  $ts3 = TeamSpeak3::factory("serverquery://serveradmin:jArxegDF@80.211.210.179:10011/?server_port=9987");
  
  // show server as online
  echo "Szerver Állapota: ELÉRHETŐ<br />\n";
  echo "Szerver IP: " . $ts3->getAdapterHost() . ":" . $ts3->virtualserver_port . "<br />\n";
  echo "Szerver neve: " . $ts3->virtualserver_name . "<br />\n";
  echo "Újrainditás óta eltelt idő: " . TeamSpeak3_Helper_Convert::seconds($ts3->virtualserver_uptime) . "<br />\n";
  echo "Szerver verzió: " . TeamSpeak3_Helper_Convert::version($ts3->virtualserver_version) . "<br />\n";
  echo "Jelenleg online: " . $ts3->virtualserver_clientsonline . " / " . $ts3->virtualserver_maxclients . "<br />\n";
}
catch(Exception $e)
{
  // grab errors and show server as offline
  echo "Szerver Állapota: NEM ELÉRHETŐ<br />\n";
}