<?php
namespace Chapagain\AutoCurrency\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Chapagain\AutoCurrency\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;

class DataTest extends TestCase
{
	/**
     * @var Data
     */
	private $data;
	
	/**
     * Set up test class
     */
    public function setUp()
    {
		parent::setUp();
		
		$scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
			->disableOriginalConstructor()
			->getMock();

        $this->data = new Data($scopeConfigMock);
	}

	/**
	 * Test if the helper class exists
	 */
	public function testInit()
	{
		$this->assertInstanceOf(Data::class, $this->data);
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