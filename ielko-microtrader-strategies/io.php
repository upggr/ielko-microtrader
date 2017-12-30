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

      break;
}
}
else {
  echo 'no t defined';
}

}

?>
