<?php
namespace Chapagain\AutoCurrency\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Chapagain\AutoCurrency\Helper\Data;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Chapagain\AutoCurrency\Helper\Ip2Country;

class DataTest extends TestCase
{
	/**
     * @var Data
     */
	private $data;

	/**
     * @var ScopeConfigInterface|MockObject
     */
	private $scopeConfigMock;

	/**
     * @var Ip2Country|MockObject
     */
	private $ip2CountryMock;
	
	/**
     * Set up test class
     */
    public function setUp()
    {
		parent::setUp();
		
		$this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
			->disableOriginalConstructor()
			->getMock();

		$this->ip2CountryMock = $this->getMockBuilder(Ip2Country::class)
			->disableOriginalConstructor()
			->getMock();

        $this->data = new Data($this->scopeConfigMock, $this->ip2CountryMock);
	}

	/**
	 * @test
	 * Test if the helper class exists
	 */
	public function testInit()
	{
		$this->assertInstanceOf(Data::class, $this->data);
	}

	/**
	 * @test
	 * Test if the module is enabled
	 */
	public function testIsEnabled()
	{
		$this->scopeConfigMock->expects($this->once())
			->method('getValue')
            ->with(Data::XML_PATH_AUTOCURRENCY_ENABLED, ScopeInterface::SCOPE_STORE)
			->will($this->returnValue(true));
		
        $this->assertTrue((bool)$this->data->isEnabled());
	}

	/**
     * @test
	 * @dataProvider providerTestCheckValidIp
     */
	public function TestCheckValidIp($ip, $expectedResult) 
	{
		$this->assertSame($expectedResult, $this->data->checkValidIp($ip));	
	}

	/**
     * @test
	 * @dataProvider providerTestCheckIpv6
     */
	public function TestCheckIpv6($ip, $expectedResult) 
	{
		$this->assertSame($expectedResult, $this->data->checkIpv6($ip));	
	}

	public function providerTestCheckValidIp()
	{
        return [
			['128.199.105.86', true],
			['104.131.166.160', true],
			['2002:4559:1FE2::4559:1FE2', true],
			['FE80:0000:0000:0000:0202:B3FF:FE1E:8329', true]
		];
	}
	
	public function providerTestCheckIpv6()
	{
        return [
			['128.199.105.86', false],
			['104.131.166.160', false],
			['2002:4559:1FE2::4559:1FE2', true],
			['FE80:0000:0000:0000:0202:B3FF:FE1E:8329', true]
		];
    }
}