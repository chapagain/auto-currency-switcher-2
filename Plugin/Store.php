<?php
namespace Chapagain\AutoCurrency\Plugin;

use Chapagain\AutoCurrency\Helper\Data;
use Magento\Directory\Model\Currency;

class Store
{
	/**
	 * @var Data
	 */
	protected $helper;

	/**
	 * @var Currency
	 */
	protected $currency;

	public function __construct(
		Data $helper,
		Currency $currency
	) {
		$this->helper = $helper;
		$this->currency = $currency;
	}

	/**
     * Update default store currency code
     *
	 * @param object \Magento\Store\Model\Store 
	 * @param string $result Currency Code
     * @return string
     */
    public function afterGetDefaultCurrencyCode(\Magento\Store\Model\Store $subject, $result)
    {
		if ($this->helper->isEnabled()) {
			$currencyCode = $this->getCurrencyCodeByIp($result);
			return !empty($currencyCode) ? $currencyCode : $result;
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
		if(!in_array($currencyCode, $allowedCurrencies)) {
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
		$countryCode = $this->helper->useCloudflareCountry() ? $_SERVER['HTTP_CF_IPCOUNTRY'] : $ipc->lookup($ipAddress);
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