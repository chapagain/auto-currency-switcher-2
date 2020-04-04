# Auto Currency Switcher 2 - Magento 2 Extension

Automatically switches shop's currency to visitor's local currency - "Magento 2" Extension 

Auto Currency extension tracks visitor's IP address and automatically changes the store currency to the visitor's location currency. Visitor can switch to his/her desired currency at any time.

This extension uses `Webnet77's Ip2Country` IP Address databases for IP Address lookup. 

## Prerequisite ##

#### Enable Multiple Currency on your Magento 2 Store

1. Login to Magento 2 Admin
2. Go to `STORES -> Configuration -> GENERAL -> Currency Setup -> Currency Options`
3. In `Allowed Currencies` box, select the currencies that you want to enable on your site/store
4. Now, go to `STORES -> Currency Rates`
5. Import currency rates by clicking the Import button, Or add the rates manually
6. Then, click the `Save Currency Rates` button

## Installation ##

#### Manual Installation

1. The module's files should be placed in folder: `app/code/Chapagain/AutoCurrency`
2. Open terminal/command-prompt
3. Go to your Magento website’s root directory with the following command:
    - `cd /path/to/your/magento/root/directory`
4. Enable the module and clear static content with the following command:
    - `php bin/magento module:enable Chapagain_AutoCurrency –clear-static-content`
5. Do setup upgrade with the following command:
    - `php bin/magento setup:upgrade`

#### Composer Installation
1. Go to your Magento website’s root directory with the following command:
    - `cd /path/to/your/magento/root/directory`
2. Run the following command:
    - `composer require chapagain/magento2-autocurrency`
3. Enable the module and clear static content with the following command:
    - `php bin/magento module:enable Chapagain_AutoCurrency –clear-static-content`
4. Do setup upgrade with the following command:
    - `php bin/magento setup:upgrade`
    
#### Configuration Settings

1. Login to your Magento site's admin
2. Go to `STORES → Settings → Configuration` page
3. On left sidebar, click on `CHAPAGAIN EXTENSIONS → Auto Currency` menu
4. From there, you can Enable/Disable the module. The module is enabled by default.

## Updating GeoIP Databases ##

The GeoIP database should be updated from time to time in order to make this extension work accurately. 

1. Download [IPV4 CSV](http://software77.net/geo-ip/) file
2. Extract the file. This will extract `IPtoCountry.csv` file.
3. Use [Ip2Country lookup classes for PHP](https://github.com/mgefvert/Ip2Country) to create binary-optimized version of the csv file.
4. Upload the binary file (`.dat` file) to your `[Module Folder]/geoip/ip2country/` folder.
    
## Links

- [BLOG: Auto Currency Switcher 2: Magento 2 Extension [FREE]](http://blog.chapagain.com.np/auto-currency-switcher-2-magento-2-extension-free/)

- [GitHub Repository: Magento 1.x - Auto Currency Switcher](https://github.com/chapagain/auto-currency-switcher)

- [BLOG: Magento 1.x Extension: Auto Currency Switcher](http://blog.chapagain.com.np/magento-extension-auto-currency-switcher-free/)
