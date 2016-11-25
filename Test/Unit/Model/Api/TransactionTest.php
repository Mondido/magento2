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

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

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
        $this->objectManager = new ObjectManager($this);

        $configModelMock = $this->getMockBuilder('Mondido\Mondido\Model\Config')
            ->disableOriginalConstructor()
            ->getMock();

        $curlMock = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->getMock();

        $storeManagerMock = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')->getMock();

        $urlBuilderMock = $this->getMockBuilder('Magento\Framework\UrlInterface')->getMock();

        $quoteRepositoryMock = $this->getMockBuilder('Magento\Quote\Api\CartRepositoryInterface')->getMock();

        $helperMock = $this->getMockBuilder('Mondido\Mondido\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $isoHelperMock = $this->getMockBuilder('Mondido\Mondido\Helper\Iso')
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

        $this->object = $this->objectManager->getObject(
            '\Mondido\Mondido\Model\Api\Transaction',
            [
                'adapter' => $curlMock,
                'config' => $configModelMock,
                'storeManager' => $storeManagerMock,
                'urlBuilder' => $urlBuilderMock,
                'helper' => $helperMock,
                'quoteRepository' => $quoteRepositoryMock,
                'isoHelper' => $isoHelperMock
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
            ['getItemsCount', 'getItemsQty', '__wakeup', 'getAllVisibleItems', 'getShippingAddress', 'isVirtual'],
            [],
            '',
            false
        );

        $addressMock = $this->getMockBuilder('Magento\Quote\Model\Quote\Address')
            ->disableOriginalConstructor()
            ->getMock();

        $addressMock->expects($this->once())
            ->method('getFirstname')
            ->willReturn('John');

        $quoteItemMock = $this->getMockBuilder('Magento\Quote\Model\Quote\Item')
            ->disableOriginalConstructor()
            ->getMock();

        $quoteModelMock->expects($this->any())
            ->method('getAllVisibleItems')
            ->willReturn([$quoteItemMock]);

        $quoteModelMock->expects($this->any())
            ->method('getShippingAddress')
            ->willReturn($addressMock);

        $quoteItemMock->expects($this->any())
            ->method('getSku')
            ->willReturn('sku');

        $quoteItemMock->expects($this->any())
            ->method('getName')
            ->willReturn('name');

        $quoteItemMock->expects($this->any())
            ->method('getQty')
            ->willReturn(1);

        $this->object->create($quoteModelMock);
    }
}
