<?php
error_reporting(E_ALL & ~E_NOTICE);
include 'bower_components/cryptopia-api-php/cryptopiaAPI.php';
include 'config.php';
$analyzer = "https://electronicgr.com/cryptobot/ielko-microtrader-strategies/";
try {
   $ct = New Cryptopia($API_SECRET, $API_KEY);
   $mycoinbalance = $ct->getCurrencyBalance( $coin );
   $coinpool_all = array();
   $coinpool_price_target = array();
   $coinpool_in_order = array();
   $coinpool = array();
   $sellorders = array();
//user supplied parameters
$coin =  ltrim($strategy_arr[0], '0');
$coincap =  ltrim($strategy_arr[1], '0');
$hours =  ltrim($strategy_arr[2], '0');
$buyifabove =  ltrim($strategy_arr[3], '0');
$targetprofit =  ltrim($strategy_arr[4], '0');
$coinbet =  ltrim($strategy_arr[5], '0');
$target_coin_min_price =  ltrim($strategy_arr[6], '0');
$target_coin_max_price =  ltrim($strategy_arr[7], '0');
$lowvolume = ltrim($strategy_arr[8], '0');
$open_order_coins_flag = ltrim($strategy_arr[9], '0');
$exludecoins = array("MEOW","MCRN");
//user supplied parameters

  echo print_seperator("USER VARIABLES");
echo "Target coin market : ".$coin."\n";
echo "Bank to keep : ".$coincap."\n";
echo "Hours to look back : ".$hours."\n";
echo "Buy coin if increase was more than : ".$buyifabove."% \n";
echo "Target profit to make in each round : ".$targetprofit."\n";
echo "Increments of the coin to play : ".$coinbet."\n";
echo "Play on coins that in comparison to the the coin have a ratio of more than : ".$target_coin_min_price."\n";
echo "Play on coins that in comparison to the the coin have a ratio of less than : ".$target_coin_max_price."\n";
echo "Play on coins that their transaction count in the past timeframe is more than : ".$lowvolume."\n";
echo "Play on coins that are on open orders : ".$open_order_coins_flag."\n";
//000000DOGE_0000000010_0000000048_0000000010_0000000.20_0000000030_0000000000_0000000100_0000000010

// fill coinpool with the coins that are on the current coin market
   $ct->updatePrices();
   $tradepairs = $ct->getPrices();
   foreach ($tradepairs as $key => $value) {
     if (strpos($key, $coin) !== false & strpos($key, $coin) !== 0) {
    //   if ($value['last'] < $targetcoinration) {
      $thecoin = str_replace('/'.$coin,"",$key);
       array_push($coinpool_all,$thecoin);
          if ($value['last'] > $target_coin_min_price &  $value['last'] < $target_coin_max_price) {
              array_push($coinpool_price_target,$thecoin);
            }

     }
   }

  echo print_seperator("COIN POOL DECISIONS");
   echo "Found " .sizeof($coinpool_all)." coins that can be traded \n";
   echo "Will trade on ".sizeof($coinpool_price_target)." coins that have a trade price between ".$target_coin_min_price." and ".$target_coin_max_price." compared to ".$coin." \n";


if ($open_order_coins_flag != 1) {
   $coinsinorder = $ct->activeOrders();
   foreach ($coinsinorder as $key => $value) {
     if (($key_s = array_search(str_replace($coin,"",$value['symbol']), $coinpool_price_target)) !== false) {
     unset($coinpool_price_target[$key_s]);
   }
   }
   $coinpool = array_values($coinpool_price_target);
   echo "I have reduced the tradeable coin pool to ".sizeof($coinpool)." (excluded coins that are on open orders)\n";
}
else {
  $coinpool = array_values($coinpool_price_target);
}

print_r($coinpool);
   if ($mycoinbalance > $coincap) {
    echo print_seperator("BALANCE UPDATE");
     echo "Balance of ".$mycoinbalance. "for ".$coin." is higher than user supplied ".$coincap.", starting to trade... \n";


// send statistics to analytics server
     $openordersarr = $ct->activeOrders();
     $ct->updatePrices();
     $marketsnapshot = $ct->getPrices();
     $basecoinbal_pred = $mycoinbalance;
     $basecoinbal_real = $mycoinbalance;
     foreach ($openordersarr as  $value) {
         if ($value['type'] == 'Sell') {
           if (strpos($value['symbol'], $coin) !== false) {
             $thesymbol = str_replace($coin, "", $value['symbol']);
             $thepred_price = $value['price'];
             $theamount = $value['amount'];
             $basecoinbal_pred = $basecoinbal_pred + ($thepred_price*$theamount);
             $basecoinbal_real = $basecoinbal_real + ($theamount*$marketsnapshot[$thesymbol.'/'.$coin]['bid']);
          }
        }
      }
     echo "expecting ".$basecoinbal_pred. " ".$coin." if all goes good.. \n";
     echo "will get  ".$basecoinbal_real. " ".$coin." if I close all orders now.. \n";
     get_url($analyzer."io.php?apikey=".base64_encode($API_KEY)."&strategy=".$strategy."&real_amount=".$basecoinbal_real."&good_amount=".$basecoinbal_pred."&type=submit_data");
// send statistics to analytics server


//echo '<pre>';print_r($coinpool);echo '</pre>';

//sleep(20);

for ($x = 0; $x <= sizeof($coinpool); $x++) {
  echo print_seperator("ANALYSING COIN");
  echo "Processing coin ".$x." out of ".sizeof($coinpool)." (".$coinpool[$x].")\n";

  $mycoinbalance = $ct->getCurrencyBalance( $coin );
  if ($mycoinbalance > $coincap) {
//  echo "Balance of ".$mycoinbalance." ".$coin. " is higher than ".$coincap.", therefore I will keep trading. \n";

  $api_url_constr = "https://www.cryptopia.co.nz/api/GetMarketHistory/".$coinpool[$x]."_".$coin."/".$hours;
  //echo $api_url_constr."\n";
  $result = file_get_contents($api_url_constr);
  $data=json_decode($result,true);
  $transno = sizeof($data['Data']);
  if ($data['Success'] == '1') {
//    if ($transno > $lowvolume){
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

    $result2 = file_get_contents($api_url_constr2);
    $data2=json_decode($result2,true);
    if ($data2['Success'] == '1') {

   echo "I need to buy ".$coinbet." worth of  ".$coin."\n";
   if ($data2['Data']['Sell'][0]['Volume'] > $coinbet)
   {
     $pricetobuy = $data2['Data']['Sell'][0]['Price'];
     $pricetosell = $pricetobuy+($pricetobuy*$targetprofit);
     $targetcoins = $coinbet/$pricetobuy;
     echo "will buy ".$coinbet." ".$coin." worth of ".$coinpool[$x]." at ".$pricetobuy." (TradePairId = ".$data2['Data']['Sell'][0]['TradePairId'].") (".$targetcoins." ".$coinpool[$x].")\n";
  //   $ct->buy($coinpool[$x].$coin, $targetcoins, ($pricetobuy));
     echo "Bought ".$coinpool[$x].$coin." pair (".$targetcoins." ".$coinpool[$x]." ) at ".$pricetobuy." \n\n";

     sleep(2);
     $cbal = $ct->getCurrencyBalance( $coinpool[$x] );
  //   $ct->sell($coinpool[$x].$coin, $cbal, ($pricetosell));
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
//}
//  else {
//   echo "trade volume less than the thresholds (".$transno." < ".$lowvolume."), will not trade this coin...\n";
//  }
  else {
    echo "Problem getting market data (API problems) \n";
  }


  }
  else {
  echo "Balance of ".$mycoinbalance." ".$coin. " is lower than ".$coincap.", therefore I will stop trading now. \n";
  }
  sleep(1);


}
   }
else {
  echo "Balance of ".$mycoinbalance. "for ".$coin." is lower than user supplied ".$coincap.", stopping trading until balance is higher... \n";
}

 } catch(Exception $e) {
    echo '' . $e->getMessage() . PHP_EOL;
 }


function print_seperator($septext) {
    return "---=== ".$septext." ===---\n";
}
function get_url($url)
 {
     $cmd  = "curl --max-time 60 ";
     $cmd .= "'" . $url . "'";
     $cmd .= " > /dev/null 2>&1 &";
     exec($cmd, $output, $exit);
     return $exit == 0;
 }

 ?>