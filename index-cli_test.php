<?php
$strategy = $argv[1];
$strategy_arr = explode("_",$strategy);
$coin = $strategy_arr[0];
$coincap = $strategy_arr[1];
$hours = $strategy_arr[2];
$buyifabove = $strategy_arr[3];
$targetprofit = $strategy_arr[4];
$coinbet = $strategy_arr[5];
$targetcoinration =  $strategy_arr[6];
$lowvolume = $strategy_arr[7];
$exludecoins = array("MEOW","MCRN");

include 'core_test.php';
 ?>