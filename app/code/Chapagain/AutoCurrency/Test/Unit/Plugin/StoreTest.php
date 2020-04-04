<?php
namespace Chapagain\AutoCurrency\Test\Unit\Plugin;

use PHPUnit\Framework\TestCase;
use Chapagain\AutoCurrency\Plugin\Store;
use Chapagain\AutoCurrency\Helper\Data;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Chapagain\AutoCurrency\Helper\Ip2Country;
use Magento\Framework\Component\ComponentRegistrar;

class StoreTest extends TestCase
{
	const DEFAULT_CURRENCY_CODE = 'USD';

	/**
     * @var Store
     */
	private $store;

	/**
     * @var \Chapagain\AutoCurrency\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
	private $dataMock;

	/**
     * @var Ip2Country
     */
	private $ip2Country;
	
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
			->setMethods(['getIpAddress', 'loadIp2Country']) # all methods of the class run actual code except methods in the array which returns null
			->getMock();
		
		$currencyMock = $this->getMockBuilder(Currency::class)
			->disableOriginalConstructor()
			->getMock();

		$scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
			->disableOriginalConstructor()
			->getMock();

		$componentRegistrar = $this->getMockBuilder(ComponentRegistrar::class)
			->disableOriginalConstructor()
			->setMethods(null) # all methods of the class run actual code
			->getMock();

		$this->store = new Store($this->dataMock, $currencyMock);
		$this->ip2Country = new Ip2Country($componentRegistrar);
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

		$this->ip2Country->preload();
		$this->dataMock->expects($this->any())
            ->method('loadIp2Country')
			->willReturn($this->ip2Country);

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