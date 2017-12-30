<?php
include('config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: *');
startio($_GET);

function startio()
{
  if (isset($_GET['type'])) {
        switch ($_GET['type']) {
          case 'test':
          echo 'test';
          break

          case 'submit_data':
        $apikey =  $_GET['apikey'];
        $strategy =  $_GET['strategy'];
        $real_amount =  $_GET['real_amount'];
        $good_amount =  $_GET['good_amount'];
          $sql = "INSERT INTO `stats` (`apikey`,`strategy`,`real_amount`,`good_amount`) VALUES ('$apikey','$strategy','$real_amount','$good_amount')";
$sql = preg_replace("/(?<=[A-Za-z0-9])(')(?=[A-Za-z0-9])/", "\'", $sql);
db_query($sql) or die(db_error());
          break;
}
}
else {
  echo "no T defined \n";
}

}

?>
