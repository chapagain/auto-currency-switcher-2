# Auto Currency Switcher 2 - Magento 2 Extension

Automatically switches shop's currency to visitor's local currency - "Magento 2" Extension 

Auto Currency extension tracks visitor's IP address and automatically changes the store currency to the visitor's location currency. Visitor can switch to his/her desired currency at any time.

This extension uses `Webnet77's Ip2Country` IP Address databases for IP Address lookup. 

## Installation ##

Just install the module in your multi-currency enabled Magento shop and the module will work on the fly. 

No extra configuration settings is to be made. 

You also have the option to `Enable or Disable` the module from configuration setting. 

`STORES -> Configuration -> CHAPAGAIN EXTENSIONS -> Auto Currency`

The module is `Enabled` by default.

## Auto Currency Switcher Extension for Magento 1.x ##

[Auto Currency Switcher 1 - Github Source](https://github.com/chapagain/auto-currency-switcher)

[Magento 1.x Extension: Auto Currency Switcher BLOG](http://blog.chapagain.com.np/magento-extension-auto-currency-switcher-free/)

## Updating GeoIP Databases ##

The GeoIP database should be updated from time to time in order to make this extension work accurately. 

1) Download [IPV4 CSV](http://software77.net/geo-ip/) file

2) Extract the file. This will extract `IPtoCountry.csv` file.

3) Use [Ip2Country lookup classes for PHP](https://github.com/mgefvert/Ip2Country) to create binary-optimized version of the csv file.

4) Upload the binary file (`.dat` file) to your `[Magento Folder]/var/geoip/ip2country/` folder.
