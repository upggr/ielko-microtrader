<?php
$strategy = $argv[1];
$strategy_arr = explode("_",$strategy);
$coin =  ltrim($strategy_arr[0], '0');
$coincap =  ltrim($strategy_arr[1], '0');
$hours =  ltrim($strategy_arr[2], '0');
$buyifabove =  ltrim($strategy_arr[3], '0');
$targetprofit =  ltrim($strategy_arr[4], '0');
$coinbet =  ltrim($strategy_arr[5], '0');
$targetcoinration =  ltrim($strategy_arr[6], '0');
$lowvolume = ltrim($strategy_arr[7], '0');
$exludecoins = array("MEOW","MCRN");
include 'core_test.php';
 ?>