<?php
/**
 * Mondido
 *
 * PHP version 5.6
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */

namespace Mondido\Mondido\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * Config model test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $scopeConfigMock;

    /**
     * Set up
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->scopeConfigMock = $this->getMockBuilder(
            'Magento\Framework\App\Config\ScopeConfigInterface'
        )->getMock();

        $this->object = $objectManager->getObject(
            '\Mondido\Mondido\Model\Config',
            ['scopeConfig' => $this->scopeConfigMock]
        );
    }

    /**
     * Test getMerchantId()
     *
     * @return void
     */
    public function testGetMerchantId()
    {
        $value = 872;

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->object->getMerchantId());
    }

    /**
     * Test getPassword()
     *
     * @return void
     */
    public function testGetPassword()
    {
        $value = 'topsecret';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->object->getPassword());
    }

    /**
     * Test getSecret()
     *
     * @return void
     */
    public function testGetSecret()
    {
        $value = 'topsecret';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->object->getSecret());
    }

    /**
     * Test getPaymentAction()
     *
     * @return void
     */
    public function testGetPaymentAction()
    {
        $value = 'authorize';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->object->getPaymentAction());
    }

    /**
     * Test isActive()
     *
     * @return void
     */
    public function testIsActive()
    {
        $value = true;

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn($value);

        $this->assertEquals($value, $this->object->isActive());
    }

    /**
     * Test isTest()
     *
     * @return void
     */
    public function testIsTest()
    {
        $value = true;

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn($value);

        $this->assertEquals($value, $this->object->isTest());
    }
}
