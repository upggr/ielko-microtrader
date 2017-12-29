<?php
error_reporting(E_ALL & ~E_NOTICE);
include 'bower_components/cryptopia-api-php/cryptopiaAPI.php';
include 'config.php';

try {
   $ct = New Cryptopia($API_SECRET, $API_KEY);
   $sellorders = array();
   $mycoinbalance = $ct->getCurrencyBalance( $coin );
   if ($mycoinbalance > $coincap) {
     echo "Balance of ".$mycoinbalance. " is higher than ".$coincap.", starting to trade... \n";
     $ct->updatePrices();
     $tradepairs = $ct->getPrices();
     $coinpool = array();
     foreach ($tradepairs as $key => $value) {
       if (strpos($key, $coin) !== false) {
         if ($value['last'] < $targetcoinration) {
         array_push($coinpool,str_replace('/'.$coin,"",$key));
                  }
       }
     }
//echo '<pre>';print_r($coinpool);echo '</pre>';
echo "I have found ".sizeof($coinpool)." tradable coins \n";
  $coinsinorder = $ct->activeOrders();
  foreach ($coinsinorder as $key => $value) {
    if (($key_s = array_search(str_replace($coin,"",$value['symbol']), $coinpool)) !== false) {
    unset($coinpool[$key_s]);
}
  }
echo "I have reduced the tradeable coin pool to ".sizeof($coinpool)." (excluded coins that are still on order)\n";

for ($x = 0; $x <= sizeof($coinpool); $x++) {
  echo "Processing coin ".$x." out of ".sizeof($coinpool)." (".$coinpool[$x].")\n";
     if (in_array($coinpool[$x] , $exludecoins))
    {
echo "Will not trade this coin as it is excluded manually in your strategy settings \n";
}
else
{
  $mycoinbalance = $ct->getCurrencyBalance( $coin );
  if ($mycoinbalance > $coincap) {
//  echo "Balance of ".$mycoinbalance." ".$coin. " is higher than ".$coincap.", therefore I will keep trading. \n";

  $api_url_constr = "https://www.cryptopia.co.nz/api/GetMarketHistory/".$coinpool[$x]."_".$coin."/".$hours;
  //echo $api_url_constr."\n";
  $result = file_get_contents($api_url_constr);
  $data=json_decode($result,true);
  $transno = sizeof($data['Data']);
  if ($data['Success'] == '1' && $transno > $lowvolume) {
   $buycounter = 0;
   $sellcounter = 0;
   $minprice = min(array_column($data['Data'], 'Price'));
   $minprice_d = $data['Data'][$transno-1]['Price'];
   $maxprice_d = $data['Data'][0]['Price'];
   $maxprice = max(array_column($data['Data'], 'Price'));
   for ($y = 0; $y < sizeof($data['Data']); $y++) {
     if ($data['Data'][$y]['Type'] == 'Buy') {
       $buycounter = $buycounter + 1;
     }
     else if ($data['Data'][$y]['Type'] == 'Sell') {
       $sellcounter = $sellcounter + 1;
     }
     else {
       $sellcounter = $sellcounter + 1;
     }

  //     echo $data['Data'][$y]['Type'].'\n';
   }
   if ($buycounter > $sellcounter)  {
  //     echo 'looks like more people are buying '.$coinpool[$x].' in the past '.$hours.' hours..\n';
     $tradeflag = 'buy';
   }
   else if ($buycounter < $sellcounter)  {
  //     echo 'looks like more people are selling '.$coinpool[$x].' in the past '.$hours.' hours..\n';
     $tradeflag = 'sell';
   }
   else {
  //   echo 'unable to find the sentiment (buy/sell) for the past '.$hours.' hours..\n';
     $tradeflag = 'nothing';
   }
  $flunc = (1 - $minprice / $maxprice) * 100;
  $difference = (1 - $minprice_d / $maxprice_d) * 100;
  if ($maxprice_d - $minprice_d > 0) {
  $direction_flag = 'rising';
  }
  else {
  $direction_flag = 'falling';
  }
  echo "---=== ANALYSIS ===---\n";
  echo "most of the people are in ".$tradeflag." mode\n";
  echo "price is ".$direction_flag."\n";
  echo $coinpool[$x]." had a min price of ".$minprice." and a max price of ".$maxprice."\n";
  echo $coinpool[$x]." started at ".$minprice_d." and finished at ".$maxprice_d."\n";
  echo $coinpool[$x]." flunctuated ".round($flunc)."% in the past ".$hours." hours\n" ;
  echo $coinpool[$x]." changed ".round($difference)."% in the past ".$hours." hours \n" ;
    echo "---=== SUMMARY ===---\n";
  echo "Summary for ".$coinpool[$x]." : direction is : ".$direction_flag." and change > buyifabove (".$difference." > ".$buyifabove.") and tradeflag = ".$tradeflag."\n";
  if ($direction_flag == 'rising' && ($difference > $buyifabove) && ($tradeflag == 'buy')) {
      echo "---=== VERDICT ===---\n";
  echo "I have decided to play with ".$coinpool[$x]."\n";
    $api_url_constr2 = "https://www.cryptopia.co.nz/api/GetMarketOrders/".$coinpool[$x]."_".$coin."/10";
  //  echo $api_url_constr2."\n";
    $result2 = file_get_contents($api_url_constr2);
    $data2=json_decode($result2,true);
    if ($data2['Success'] == '1') {
  //   echo '<pre>';print_r($data2['Data']['Sell']);echo '<pre>';
   echo "I need to buy ".$coinbet." worth of  ".$coin."\n";
   if ($data2['Data']['Sell'][0]['Volume'] > $coinbet)
   {
     $pricetobuy = $data2['Data']['Sell'][0]['Price'];
     $pricetosell = $pricetobuy+($pricetobuy*$targetprofit);
     $targetcoins = $coinbet/$pricetobuy;
     echo "will buy ".$coinbet." ".$coin." worth of ".$coinpool[$x]." at ".$pricetobuy." (TradePairId = ".$data2['Data']['Sell'][0]['TradePairId'].") (".$targetcoins." ".$coinpool[$x].")\n";
     $ct->buy($coinpool[$x].$coin, $targetcoins, ($pricetobuy));
     echo "Bought ".$coinpool[$x].$coin." pair (".$targetcoins." ".$coinpool[$x]." ) at ".$pricetobuy." \n\n";

     sleep(2);
     $cbal = $ct->getCurrencyBalance( $coinpool[$x] );
     $ct->sell($coinpool[$x].$coin, $cbal, ($pricetosell));
     echo "Placing sell order for the ".$coinpool[$x].$coin." pair (".$cbal." ".$coinpool[$x]." ) at ".$pricetosell." ".$coin."\n\n";
     $sellorders[$x]['pair'] =  $coinpool[$x].$coin;
     $sellorders[$x]['amount'] =  $cbal;
     $sellorders[$x]['sellprice'] =  $pricetosell;
   }
   else {
     echo "the first sell order is less than the minimum threshold setting (".$data2['Data']['Sell'][0]['Volume']." vs ".$coinbet.").
     Will just buy whatever they sell on the next order\n";
      $pricetobuy = $data2['Data']['Sell'][1]['Price'];
      $pricetosell = $pricetobuy+($pricetobuy*$targetprofit);
      $targetcoins = $data2['Data']['Sell'][0]['Volume']/$pricetobuy;
      echo "will buy ".$data2['Data']['Sell'][0]['Volume']." ".$coin." worth of ".$coinpool[$x]." at ".$pricetobuy." (TradePairId = ".$data2['Data']['Sell'][0]['TradePairId'].") (".$targetcoins." ".$coinpool[$x].")\n";
  //      $ct->buy($coinpool[$x].$coin, $targetcoins, ($pricetobuy));
      sleep(2);
  //      $ct->sell($coinpool[$x].$coin, $targetcoins-($targetcoins*0.03), ($pricetosell));
      echo "Buy order and sell order is executed succesfully\n\n";
   }
  }
  }
  else {
    echo "---=== VERDICT ===---\n";
  echo "I have decided not to play with ".$coinpool[$x]."\n\n";
  }
  }
  else {
   echo "Problem getting market data or trade volume less than the thresholds (".$transno." < ".$lowvolume."), will not trade this coin...\n\n";
  }


  }
  else {
  echo "Balance of ".$mycoinbalance." ".$coin. " is lower than ".$coincap.", therefore I will stop trading now. \n";
  }
  sleep(1);
}

}
 foreach ($coinpool as $key => $value) {
 }
   }
else {
  echo "not enough balance of the coin to play... \n";
  $openordersarr = $ct->activeOrders();
  foreach ($openordersarr as $key => $value) {
    foreach ($value as $key2 => $value2) {
      if ($key2['sell'] == 'Sell') {
      echo $value2."\n";
  //             }
}
  }
}

 } catch(Exception $e) {
    echo '' . $e->getMessage() . PHP_EOL;
 }

 ?>