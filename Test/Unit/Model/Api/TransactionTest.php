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

namespace Mondido\Mondido\Test\Unit\Model\Api;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * Mondido transaction test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class TransactionTest extends \PHPUnit_Framework_TestCase
{

    protected $object;
    protected $objectManager;

    /**
     * Set up
     *
     * @return void
     */
    public function setUp()
    {

        $objectManager = new ObjectManager($this);

        $configModelMock = $this->getMockBuilder('Mondido\Mondido\Model\Config')
            ->disableOriginalConstructor()
            ->getMock();

        $curlMock = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->getMock();

        $curlMock->expects($this->any())
            ->method('addOption')
            ->will($this->returnSelf());
        $curlMock->expects($this->any())
            ->method('connect')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('read')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('getInfo')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('close')
            ->willReturn(true);

        $this->object = $objectManager->getObject(
            '\Mondido\Mondido\Model\Api\Transaction',
            ['adapter' => $curlMock, 'config' => $configModelMock]
        );
    }

    /**
     * Test create
     *
     * @return void
     */
    public function testCreate()
    {
        $quoteModelMock = $this->getMock(
            'Magento\Quote\Model\Quote',
            ['getItemsCount', 'getItemsQty', '__wakeup'],
            [],
            '',
            false
        );

        $this->object->create($quoteModelMock);
    }
}
