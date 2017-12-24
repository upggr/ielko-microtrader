<?php
   $coin = "DOGE"; //what will be the base coin to play to. Effectively this bounds the screen to the DOGE market in cryptopia and only coins in this market.
   $coincap = 20; //how many coins to keep from $coin (not to play them)
   $hours = 48; //how many hours of market data to analyze
   $buyifabove = 10; // when a coin increased $buyifabove % in the last $hours hours will play (buy)
   $targetprofit = 0.05; //target proft to make (sell order)
   $coinbet = 20; //increments of $coin to play
   $targetcoinration = 0.5; //only play on coins that have last price less than $targetcoinration value than the $coin
   $lowvolume = 20; //only trade the coin if there has been more than $lowvolume transactions in the past $hours hours
   $exludecoins = array("MEOW","MCRN");