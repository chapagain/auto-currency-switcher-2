<?php

namespace Chapagain\AutoCurrency\Plugin;

use Chapagain\AutoCurrency\Helper\Data;
use Magento\Directory\Model\Currency;
use Magento\Store\Model\StoreManagerInterface;

class FrontControllerInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        Data $helper,
        Currency $currency,
        StoreManagerInterface $storeManager
    )
    {
        $this->helper = $helper;
        $this->currency = $currency;
        $this->storeManager = $storeManager;
    }

    /**
     * Update current store currency code
     *
     * @param \Magento\Framework\App\FrontController $subject
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return void
     */
    public function beforeDispatch(
        \Magento\Framework\App\FrontController $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {
        if ($this->helper->isEnabled()) {
            $currentCurrency = $this->storeManager->getStore()->getCurrentCurrencyCode();
            $this->storeManager->getStore()->setCurrentCurrencyCode($this->getCurrencyCodeByIp($currentCurrency));
        }
    }

    /**
     * Get Currency code by IP Address
     *
     * @param string $result Currency Code
     * @return string $currencyCode
     */
    public function getCurrencyCodeByIp($result = '')
    {
        $currencyCode = $this->getCurrencyCodeIp2Country($result);
        // if currencyCode is not present in allowedCurrencies
        // then return the default currency code
        $allowedCurrencies = $this->currency->getConfigAllowCurrencies();
        //$allowedCurrencies = $this->currencyFactory->getConfigAllowCurrencies();
        if (!in_array($currencyCode, $allowedCurrencies)) {
            return $result;
        }
        return $currencyCode;
    }

    /**
     * Get Currency code by IP Address
     * Using Ip2Country Database
     *
     * @param string $result Currency Code
     * @return string $currencyCode
     */
    public function getCurrencyCodeIp2Country($result = '')
    {
        // load Ip2Country database
        $ipc = $this->helper->loadIp2Country();
        // get IP Address
        $ipAddress = $this->helper->getIpAddress();
        // additional valid ip check
        // because Ip2Country generates error for invalid IP address
        if (!$this->helper->checkValidIp($ipAddress)) {
            return null;
        }
        $countryCode = $ipc->lookup($ipAddress);
        // return default currency code when country code is ZZ
        // i.e. if browsed in localhost / personal computer
        if ($countryCode == 'ZZ') {
            $currencyCode = $result;
        } else {
            $currencyCode = $this->helper->getCurrencyByCountry($countryCode);
        }
        return $currencyCode;
    }
}
