<?php
namespace Chapagain\AutoCurrency\Test\Unit\Plugin;

use PHPUnit\Framework\TestCase;
use Chapagain\AutoCurrency\Plugin\Store;
use Chapagain\AutoCurrency\Helper\Data;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\Config\ScopeConfigInterface;

class StoreTest extends TestCase
{
	const DEFAULT_CURRENCY_CODE = 'USD';

	/**
     * @var Data
     */
	private $data;

	/**
     * @var Store
     */
	private $store;

	/**
     * @var \Chapagain\AutoCurrency\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
	private $dataMock;
	
	/**
     * Set up test class
     */
    public function setUp()
    {
		parent::setUp();
		
		$this->dataMock = $this->getMockBuilder(Data::class)
			->disableOriginalConstructor()
			// ->setMethods(null) # all methods of the class run actual code
			// ->setMethods([]) # all methods of the class returns null
			->setMethods(['getIpAddress']) # all methods of the class run actual code except methods in the array which returns null
			->getMock();
		
		$currencyMock = $this->getMockBuilder(Currency::class)
			->disableOriginalConstructor()
			->getMock();

		$scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
			->disableOriginalConstructor()
			->getMock();

		$this->data = new Data($scopeConfigMock);
		$this->store = new Store($this->dataMock, $currencyMock);
	}

	/**
	 * Test if the model class exists
	 */
	public function testInit()
	{
		$this->assertInstanceOf(Store::class, $this->store);
	}
		
	/**
     * @test
	 * @dataProvider providerTestGetCurrencyCodeIp2Country
     */
	public function TestGetCurrencyCodeIp2Country($ip, $expectedResult) 
	{
		$this->dataMock->expects($this->any())
            ->method('getIpAddress')
			->willReturn($ip);

		$defaultCurrencyCode = self::DEFAULT_CURRENCY_CODE;
		$code = $this->store->getCurrencyCodeIp2Country($defaultCurrencyCode);
		$this->assertSame($expectedResult, $code);	
	}

	public function providerTestGetCurrencyCodeIp2Country()
	{
        return [
			['128.199.105.86', 'GBP'],
			['104.131.166.160', 'USD'],
			['146.185.155.141', 'EUR'],
			['127.0.0.1', self::DEFAULT_CURRENCY_CODE]
		];
	}
}