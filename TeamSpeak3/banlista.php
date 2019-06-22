
<?php
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
$ts3 = TeamSpeak3::factory("serverquery://serveradmin:jArxegDF@80.211.210.179:10011/?server_port=9987");
$banlist = $ts3 -> banlist();


  echo '<table class="table table-striped table-bordered table-hover" border="1">';  
echo '
<tr>
<th>Ban ID</th>
<th>Ban IP</th>
<th>Ban Név</th>
<th>Ban időtartama</th>
<th>Kitiltotta</th>
<th>Ban indok</th>
</tr>';

  foreach ($banlist as $row)
  {

  if(empty($row['reason']))
  $reason = "Indok nincs megadva";
  else
  $reason = $row['reason'];

  if(empty($row['name']))
  $name = "Nincs név megadva";
  else
  $name = $row['name'];

  if(empty($row['ip']))
  $ip = "Nincs IP megadva";
  else
  $ip = $row['ip'];

  if($row['duration'] == 0)
  $expires = "Örök kitiltás";
  else
  $expires = date('d-m-Y H:i:s', $row['created'] + $row['duration']);

  echo '<td>' . $row['banid'] . '</td>';
  echo '<td>' . $ip . '</td>';
  echo '<td>' . $name . '</td>';
  echo '<td>' . $expires . '</td>';
  echo '<td>' . $row['invokername'] . '</td>';
  echo '<td>' . $reason . '</td>';
  echo '</tr>';
  }


?>