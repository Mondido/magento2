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

namespace Mondido\Mondido\Test\Unit\Model\Adminhtml\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * PaymentActionTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class PaymentActionTest extends \PHPUnit_Framework_TestCase
{
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
        $this->objectManager = new ObjectManager($this);
        $this->object = $this->objectManager->getObject(
            'Mondido\Mondido\Model\Adminhtml\Source\PaymentAction'
        );
    }

    /**
     * Test execute
     *
     * @return void
     */
    public function testExecute()
    {
        $this->assertEquals(get_class($this->object), 'Mondido\Mondido\Model\Adminhtml\Source\PaymentAction');
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
    }
}
