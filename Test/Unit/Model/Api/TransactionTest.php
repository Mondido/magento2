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

        $storeManagerMock = $this->getMockBuilder(
            'Magento\Store\Model\StoreManagerInterface'
        );

        $storeManagerMock
            ->disableOriginalConstructor()
            ->getMock();

        $storeMock = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();

        $storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($storeMock);

        $storeMock->expects($this->any())
            ->method('getUrl')
            ->willReturn('https://kodbruket.se/');

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
            [
                'adapter' => $curlMock,
                'config' => $configModelMock,
                'storeManager' => $storeManagerMock
            ]
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
            ['getItemsCount', 'getItemsQty', '__wakeup', 'getAllVisibleItems'],
            [],
            '',
            false
        );

        $quoteItemMock = $this->getMockBuilder('Magento\Quote\Model\Quote\Item')
            ->disableOriginalConstructor()
            ->getMock();

        $quoteModelMock->expects($this->once())
            ->method('getAllVisibleItems')
            ->willReturn([$quoteItemMock]);

        $quoteItemMock->expects($this->once())
            ->method('getSku')
            ->willReturn('sku');

        $quoteItemMock->expects($this->once())
            ->method('getName')
            ->willReturn('name');

        $quoteItemMock->expects($this->once())
            ->method('getQty')
            ->willReturn(1);

        $this->object->create($quoteModelMock);
    }
}
