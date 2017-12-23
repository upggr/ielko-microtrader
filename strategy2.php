<?php
include 'bower_components/cryptopia-api-php/cryptopiaAPI.php';
include 'config.php';
try {
   $ct = New Cryptopia($API_SECRET, $API_KEY);
   $coin = "DOGE"; //what will be the base coin to play to
   $coincap = 20; //how many coins to keep from $coin (not to play them)
   $hours = 48; //how many hours of market data to analyze
   $buyifabove = 10; // when a coin increased $buyifabove % in the last $hours hours will play (buy)
   $targetprofit = 0.15; //target proft to make (sell order)
   $coinbet = 20; //increments of $coin to play
   $targetcoinration = 0.5; //only play on coins that have last price less than $targetcoinration value than the $coin
   $lowvolume = 20; //only trade the coin of thre has been more than $lowvolume transactions in the past $hours hours

   $mycoinbalance = $ct->getCurrencyBalance( $coin );
   if ($mycoinbalance > $coincap) {
     echo "Balance of ".$mycoinbalance. " is higher than ".$coincap.", therefore I will start. <br />";
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
echo 'found '.sizeof($coinpool).' tradable coins <br />';
  $coinsinorder = $ct->activeOrders();
  foreach ($coinsinorder as $key => $value) {
    if (($key_s = array_search(str_replace($coin,"",$value['symbol']), $coinpool)) !== false) {
    unset($coinpool[$key_s]);
}
  }
echo 'Reduced coin pool to '.sizeof($coinpool).' tradable coins (exluded coins on order)<br />';

for ($x = 0; $x <= sizeof($coinpool); $x++) {
//for ($x = 0; $x <= 10; $x++) {
  $mycoinbalance = $ct->getCurrencyBalance( $coin );
  if ($mycoinbalance > $coincap) {
   echo "Balance of ".$mycoinbalance." ".$coin. " is higher than ".$coincap.", therefore I will keep trading. <br />";
   $api_url_constr = "https://www.cryptopia.co.nz/api/GetMarketHistory/".$coinpool[$x]."_".$coin."/".$hours;
   echo $api_url_constr.'<br />';
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
  //   echo sizeof($data['Data']).'<br />';
  //   echo '<pre>';print_r($data['Data']);echo '<pre>';
     for ($y = 0; $y <= sizeof($data['Data']); $y++) {
       if ($data['Data'][$y]['Type'] == 'Buy') {
         $buycounter = $buycounter + 1;
       }
       else if ($data['Data'][$y]['Type'] == 'Sell') {
         $sellcounter = $sellcounter + 1;
       }


  //     echo $data['Data'][$y]['Type'].'<br />';
     }
     if ($buycounter > $sellcounter)  {
  //     echo 'looks like more people are buying '.$coinpool[$x].' in the past '.$hours.' hours..<br />';
       $tradeflag = 'buy';
     }
     else if ($buycounter < $sellcounter)  {
  //     echo 'looks like more people are selling '.$coinpool[$x].' in the past '.$hours.' hours..<br />';
       $tradeflag = 'sell';
     }
     else {
    //   echo 'unable to find the sentiment (buy/sell) for the past '.$hours.' hours..<br />';
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
echo 'most of the people are in "'.$tradeflag.'" mode<br />';
echo 'price is '.$direction_flag.'<br />';
echo $coinpool[$x].' had a min price of '.$minprice.' and a max price of '.$maxprice.'<br />';
echo $coinpool[$x].' started at '.$minprice_d.' and finished at '.$maxprice_d.'<br />';
echo $coinpool[$x].' flunctuated '.round($flunc).'% in the past '.$hours.' hours<br />' ;
echo $coinpool[$x].' changed '.round($difference).'% in the past '.$hours.' hours <br />' ;

if ($direction_flag == 'rising' && ($difference > $buyifabove) && ($tradeflag > 'buy')) {
      echo 'will play with '.$coinpool[$x].'<br />';

      $api_url_constr2 = "https://www.cryptopia.co.nz/api/GetMarketOrders/".$coinpool[$x]."_".$coin."/10";
      echo $api_url_constr2.'<br />';
      $result2 = file_get_contents($api_url_constr2);
      $data2=json_decode($result2,true);
      if ($data2['Success'] == '1') {
  //   echo '<pre>';print_r($data2['Data']['Sell']);echo '<pre>';
     echo 'I need to buy '.$coinbet.' worth of  '.$coin.'<br />';
     if ($data2['Data']['Sell'][0]['Volume'] > $coinbet)
     {
       $pricetobuy = $data2['Data']['Sell'][0]['Price'];
       $pricetosell = $pricetobuy+($pricetobuy*$targetprofit);
       $targetcoins = $coinbet/$pricetobuy;
       echo 'will buy '.$coinbet.' '.$coin.' worth of '.$coinpool[$x].' at '.$pricetobuy.' (TradePairId = '.$data2['Data']['Sell'][0]['TradePairId'].') ('.$targetcoins.' '.$coinpool[$x].')<br />';
       $ct->buy($coinpool[$x].$coin, $targetcoins, ($pricetobuy));
       sleep(2);
       $ct->sell($coinpool[$x].$coin, $targetcoins-($targetcoins*0.03), ($pricetosell));
       echo 'executed<br /><br />';
     }
     else {
       echo 'the first sell order is less than the minimum threshold setting ('.$data2['Data']['Sell'][0]['Volume'].' vs '.$coinbet.').
       Will just buy whatever they sell on the next order<br />';
        $pricetobuy = $data2['Data']['Sell'][1]['Price'];
        $pricetosell = $pricetobuy+($pricetobuy*$targetprofit);
        $targetcoins = $data2['Data']['Sell'][0]['Volume']/$pricetobuy;
        echo 'will buy '.$data2['Data']['Sell'][0]['Volume'].' '.$coin.' worth of '.$coinpool[$x].' at '.$pricetobuy.' (TradePairId = '.$data2['Data']['Sell'][0]['TradePairId'].') ('.$targetcoins.' '.$coinpool[$x].')<br />';
  //      $ct->buy($coinpool[$x].$coin, $targetcoins, ($pricetobuy));
        sleep(2);
  //      $ct->sell($coinpool[$x].$coin, $targetcoins-($targetcoins*0.03), ($pricetosell));
        echo 'executed<br /><br />';
     }
   }
}
else {
  echo 'will not play with '.$coinpool[$x].'<br /><br />';
}
   }
   else {
     echo 'Problem getting market data or trade volume less than the thresholds ('.$transno.' < '.$lowvolume.'), will not trade this coin...<br /><br />';
   }


//    echo $coinpool[$x].'<br />';
  }
  else {
    echo "Balance of ".$mycoinbalance." ".$coin. " is lower than ".$coincap.", therefore I will stop trading now. <br />";
  }
sleep(1);
}


 foreach ($coinpool as $key => $value) {
//echo $value.'<br />';
 }
//     echo '<pre>';print_r($ct->getPrices());echo '</pre>';


   }




  // echo '<pre>';print_r($ct->getPrices());  echo '</pre>';


// echo '<pre>';print_r($ct->marketOrderbook("PAKDOGE")); echo '</pre>';


 } catch(Exception $e) {
    echo '' . $e->getMessage() . PHP_EOL;
 }

 ?>