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

namespace Mondido\Mondido\Test\Unit\Controller\Payment;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * Payment action test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mondido\Mondido\Controller\Payment\Index
     */
    protected $object;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->contextMock = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultJsonFactoryMock = $this->getMockBuilder('Magento\Framework\Controller\Result\JsonFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->loggerMock = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->getMockForAbstractClass();

        $this->quoteRepositoryMock = $this->getMockBuilder('Magento\Quote\Api\CartRepositoryInterface')
            ->getMock();

        $this->quoteManagementMock = $this->getMockBuilder('Magento\Quote\Api\CartManagementInterface')
            ->getMock();

        $this->isoHelperMock = $this->getMockBuilder('Mondido\Mondido\Helper\Iso')
            ->disableOriginalConstructor()
            ->getMock();

        $this->transactionMock = $this->getMockBuilder('Mondido\Mondido\Model\Api\Transaction')
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperMock = $this->getMockBuilder('Mondido\Mondido\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderMock = $this->getMockBuilder('Magento\Sales\Model\Order')
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);
        $this->object = $this->objectManager->getObject(
            'Mondido\Mondido\Controller\Payment\Index',
            [
                'context' => $this->contextMock,
                'resultJsonFactory' => $this->resultJsonFactoryMock,
                'logger' => $this->loggerMock,
                'quoteRepository' => $this->quoteRepositoryMock,
                'quoteManagement' => $this->quoteManagementMock,
                'isoHelper' => $this->isoHelperMock,
                'transaction' => $this->transactionMock,
                'helper' => $this->helperMock,
                'order' => $this->orderMock
            ]
        );
    }

    /**
     * Test execute
     *
     * @return void
     */
    public function testExecute()
    {
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->contextMock = null;
        $this->resultJsonFactoryMock = null;
        $this->loggerMock = null;
        $this->quoteRepositoryMock = null;
        $this->quoteManagementMock = null;
        $this->isoHelperMock = null;
        $this->transactionMock = null;
        $this->helperMock = null;
        $this->orderMock = null;
        $this->objectManager = null;
        $this->object = null;
    }
}
