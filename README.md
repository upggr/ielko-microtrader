IELKO MICROTRADER

A strategy bot that plays on cryptopia all pairs for a coin in one go.

The idea is that based on some parameters, the bot will buy all coins that meet those parameters aiming at X% profit on the base currency. It doesn't matter if the coins are going up or down or what the price is, if a coin is rising, will buy, make profit and go. On the next round same coin might be down again, but the bot will only buy it when is rising again and repeat.

Requirements :
1. A php server. This could be a webserver ot just on windows a portable php enviroment or even php for windows. http://windows.php.net/ All it needs, is a way to run "php index-cli.php strategy1" from the command line in any platform or index.php?strategy=strategy1 from a web-browser.
2. This repo. You can just do a "git pull https://github.com/upggr/ielko-microtrader.git" on any folder in any platform, if you have git installed. Or just download this repo in a folder. I suggest you use git, so you can then do a "git update" as this project is constantly updated with new features and fixes!

Installation :
1. Clone this repo on your php server or php enviroment (locally or remotely).
2. Rename config_sample.php to config.php and replace "XXX" with your api credentials that you can find in your cryptopia account

Suggestions :
1. This bot will perform best if you set it to run every 10-20 minutes. so create a cron or other sort of automation to call it ever X minutes
2. This project is constantly updated, so better have another cron to pull the new code in so you get all updates.
3. Create your own strategy files based on the template, but please leave strategy1.php-strategy10.php intact, as I will keep updating them as I am working on this.
4. I suggest you use the command line. If you dont, make sure your php enabled webserver can run php scripts that can last more than 5 minutes as checking all coins is a lengthy process.

Running :
1. 3. Use provided strategy_creator.html to create a strategy, or use 000000DOGE_0000000020_0000000024_0000000010_0000000.05_0000000020_00000000.5_0000000020
1. If you want to run from the console use "php index-cli.php 000000DOGE_0000000020_0000000024_0000000010_0000000.05_0000000020_00000000.5_0000000020" but if you call from the browser just visit http://localhost.or.any.other.address/index.php?strategy=000000DOGE_0000000020_0000000024_0000000010_0000000.05_0000000020_00000000.5_0000000020


Strategy1 :
On the sample strategy provided,
We are targeting all DOGE pairs in cryptopia that have a price < 0.5 DOGE , have traded more than 20 times in the past 48 hours, had movement of more than 10% in the past 48 hours, were selling mostly in the past 48 hours. We are buying in increments of 20 DOGE and whenever we buy we also create a sell order at a price 5% more than the buy price.

Note that running this strategy as per the variable $coincap even if you have 1.000.000 DOGE, in the end you will be left with 20 DOGE and all the others will be spread to the other coins until the sell orders are fullfilled.

You need to adjust the variables.

I have no responsibility on how you use this script.

Don't just run it, read what it does first.


Variables/Strategy
---
![Variables/Strategy](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/vars.png)


Debug output
---
![Debug output](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/web.png)


Orders as seen on cryptopia!
---
![Orders as seen on cryptopia](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/cryptopia.png)


Never loose a gainer again
---
![Never loose a gainer again](https://github.com/upggr/ielko-microtrader/blob/master/screenshots/gainers.png)


Youtube video of a full cycle!
---
[![Full cycle - strategy2](https://img.youtube.com/vi/-79Iq_Bf5FQ/0.jpg)](https://youtu.be/-79Iq_Bf5FQ)


If you like this and you made money, tip me ! :

DOGE : DLSy6fzUTbxqfFW8LSnU9HtfrQpa2MwtMb   (all funds are going back to the experiment pool to improve and create new strategies).
