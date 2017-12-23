IELKO MICROTRADER

A strategy bot that plays on cryptopia all pairs for a coin in one go.

Strategy has been tested with dodge and does make profit, but slowly.

Installation :
1. Clone this repo on your php server (locally or remotely).
2. Rename config_sample.php to config.php and add your cryptopia api and secret
3. Edit coin and other vars in strategy1.php
4. Set a cron to run strategy1.php every 5 minutes, or run it yourself from the browser and check the output!
Note** Make sure you have the folder from the https://github.com/upggr/cryptopia-api-php locally

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

