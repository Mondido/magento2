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

namespace Mondido\Mondido\Test\Unit\Controller\Refund;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\TestFramework\Request;

/**
 * Refund action test
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
     * @var \Mondido\Mondido\Controller\Refund\Index
     */
    protected $object;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Psr\Logger\LoggerInterface
     */
    protected $logger;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->resultJson = $this->getMockBuilder('Magento\Framework\Controller\Result\Json')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultJsonFactory = $this->getMockBuilder('Magento\Framework\Controller\Result\JsonFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->logger = $this->getMockBuilder('\Psr\Log\LoggerInterface')->getMockForAbstractClass();

        $this->object = $this->objectManager->getObject(
            'Mondido\Mondido\Controller\Refund\Index',
            ['resultJsonFactory' => $this->resultJsonFactory, 'logger' => $this->logger]
        );
    }

    /**
     * Test execute
     *
     * @return void
     */
    public function testExecute()
    {
        /*
        $postData = ['variable' => 'value'];

        $this->logger->expects($this->atLeastOnce())->method('debug');
        $this->object->getRequest()->expects($this->once())->method('getPostValue');
        $this->resultJson->expects($this->once())->method('setData')->with($postData)->willReturnSelf();
        $this->resultJsonFactory->expects($this->atLeastOnce())->method('create')->willReturn($this->resultJson);

        $this->assertSame($this->resultJson, $this->object->execute());
        */
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->object = null;
        $this->objectManager = null;
        $this->resultJson = null;
        $this->logger = null;
    }
}
