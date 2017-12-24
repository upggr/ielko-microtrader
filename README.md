IELKO MICROTRADER

A strategy bot that plays on cryptopia all pairs for a coin in one go.

Strategy has been tested with dodge and does make profit, but slowly.

Installation :
1. Clone this repo on your php server (locally or remotely).
2. Rename config_sample.php to config.php and add your cryptopia api and secret
3. Create a new strategy file, for example strategy_thebest.php, based on the strategy_sample.php file
4. Set a cron to run http://yourserver.com/index.php?strategy=strategy_thebest every 10 minutes, or run it yourself from the browser and check the output!

Note* If you want to use the command line on your php server, do it like this : php -q /var/www/placefothefile/index.php strategy_thebest

Note** Make sure you include the folder from the https://github.com/upggr/cryptopia-api-php locally in the bower components folder

Note*** here is a sample cron job that will run the script on your server every 10 minutes :

*/10 * * * * php -q /home/user/webapps/yoursite.com/ielko-microtrader/index.php strategy_thebest

Note**** Make sure you always pull the newest code from this repo as I keep improving. It is best if you create your own strategy files and do not use strategy1.php to strategy10.php files as I am using those and will keep updating it for my test.


Strategy1 :
On the sample strategy provided,
we are targeting all DOGE pairs in cryptopia that have a price < 0.5 DOGE , have traded more than 20 times in the past 48 hours, had movement of more than 10% in the past 48 hours, were selling mostly in the past 48 hours. We are buying in increments of 20 DOGE and whenever we buy we also create a sell order at a price 5% more than the buy price.
Note that running this strategy as per the variable $coincap even if you have 1.000.000 DOGE, in the end you will be left with 20 DOGE and all the others will be spread to the other coins. You need to adjust the variables. I have no responsibility on how you use this script. Don't just run it, read what it does first.

Variables/Strategy
---
![Variables/Strategy](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/vars.png)


Debug output
---
![Debug output](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/web.png)


Orders as seen on cryptopia!
---
![Orders as seen on cryptopia](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/cryptopia.png)


If you like this and you made money, tip me ! :

DOGE : DLSy6fzUTbxqfFW8LSnU9HtfrQpa2MwtMb   (all funs are going back to the experiment pool to improve and create new strategies).
