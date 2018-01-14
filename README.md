IELKO MICROTRADER

Discuss strategies etc at : https://t.me/ielkomicrotrader

A strategy bot that plays on cryptopia all pairs for a coin in one go.

The idea is that based on some parameters, the bot will buy all coins that meet those parameters aiming at X% profit on the base currency. It doesn't matter if the coins are going up or down or what the price is, if a coin is rising, will buy, make profit and go. On the next round same coin might be down again, but the bot will only buy it when is rising again and repeat.

Requirements :
1. A php server. This could be a webserver ot just on windows a portable php enviroment or even php for windows. http://windows.php.net/ (PHP 7+)
2. This repo. You can just do a "git pull https://github.com/upggr/ielko-microtrader.git" on any folder in any platform, if you have git installed. Or just download this repo in a folder. I suggest you use git, so you can then do a "git update" as this project is constantly updated with new features and fixes!

Installation :
1. Clone this repo on your php server or php enviroment (locally or remotely).
2. Rename config_sample.php to config.php and replace "XXX" with your api credentials that you can find in your cryptopia account

Suggestions :
1. This bot will perform best if you set it to run every 10-20 minutes. so create a cron or other sort of automation to call it ever X minutes
2. This project is constantly updated, so better have another cron to pull the new code in so you get all updates.
3. I suggest you use the command line. If you dont, make sure your php enabled webserver can run php scripts that can last more than 5 minutes as checking all coins is a lengthy process.

Running :
1. Use provided strategy_creator.html to create a strategy, or use 0000000BTC_0000.00051_0000000010_0000000010_0000000.05_0000.00051_0000000000_0000000001_0000000020_0000000000_00000000.5_0000000001 or go online make your own : https://electronicgr.com/cryptobot/ielko-microtrader-strategies/strategy_creator.html
2. If you want to run from the console use "php index-cli.php 0000000BTC_0000.00051_0000000010_0000000010_0000000.05_0000.00051_0000000000_0000000001_0000000020_0000000000_00000000.5_0000000001"


Note that you can also run php index-cli_test.php 0000000BTC_0000.00051_0000000010_0000000010_0000000.05_0000.00051_0000000000_0000000001_0000000020_0000000000_00000000.5_0000000001  and this will just run the script without executing any orders, just show you what it would have done. great way to test it with no risk.


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

---

Checkout one of the strategies running live on my cryptopia : https://electronicgr.com/cryptobot/ielko-microtrader-strategies/strategies.html


If you like this and you made money, tip me ! :

DOGE : DLSy6fzUTbxqfFW8LSnU9HtfrQpa2MwtMb   (all funds are going back to the experiment pool to improve and create new strategies).


***Note that, everytime you run this, I have temporarily enabled a feature where you send me some anonymous data : strategy ID, estimated profit and real profit. This is for statistical reasons, so I can create another program where strategies are rated automatically and you can pick other people's strategies, etc. Nothing else is collected, just amounts, and strategy.
The code that does that is this one :

get_url($analyzer."io.php?apikey=".base64_encode($API_KEY)."&strategy=".$strategy."&real_amount=".$basecoinbal_real."&good_amount=".$basecoinbal_pred."&type=submit_data");

in the core.php file. I also transmit an encoded version of your api key, just as uniqness, but not the secret. If you dont like this, just clone this repo , remove the line and run it without. I will soon add an option in the strategy url to disable automatically, but as you can see is perfectly safe!
