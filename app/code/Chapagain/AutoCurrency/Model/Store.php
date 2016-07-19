<?php
namespace Chapagain\AutoCurrency\Model;

use Magento\Framework\App\ObjectManager;

class Store extends \Magento\Store\Model\Store
{
	/**
     * Update default store currency code
     *
     * @return string
     */
    public function getDefaultCurrencyCode()
    {
		$helper = ObjectManager::getInstance()->get('Chapagain\AutoCurrency\Helper\Data');
		
		if ($helper->isEnabled()) {
			$result = parent::getDefaultCurrencyCode();			
			return $this->getCurrencyCodeByIp($result);
		} else {
			return parent::getDefaultCurrencyCode();
		}
    }
		
	/**
     * Get Currency code by IP Address
     *
     * @return string
     */
	public function getCurrencyCodeByIp($result = '') 
	{	
		$currencyCode = $this->getCurrencyCodeIp2Country();		
			
		// if currencyCode is not present in allowedCurrencies
		// then return the default currency code
		$currencyModel = ObjectManager::getInstance()->get('Magento\Directory\Model\Currency');
		$allowedCurrencies = $currencyModel->getConfigAllowCurrencies();
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
     * @return string
     */
	public function getCurrencyCodeIp2Country() 
	{
		$helper = ObjectManager::getInstance()->get('Chapagain\AutoCurrency\Helper\Data');
		
		// load Ip2Country database
		$ipc = $helper->loadIp2Country();
		
		// get IP Address
		$ipAddress = $helper->getIpAddress();
				
		// additional valid ip check 
		// because Ip2Country generates error for invalid IP address
		if (!$helper->checkValidIp($ipAddress)) {
			return null;
		}
		
		$countryCode = $ipc->lookup($ipAddress);
		
		// return default currency code when country code is ZZ
		// i.e. if browsed in localhost / personal computer
		if ($countryCode == 'ZZ') {
			$currencyCode = parent::getDefaultCurrencyCode();
		} else {				
			$currencyCode = $helper->getCurrencyByCountry($countryCode);
		}
		
		return $currencyCode;
	}
}

?>
