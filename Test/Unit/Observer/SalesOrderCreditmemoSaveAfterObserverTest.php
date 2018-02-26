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

namespace Mondido\Mondido\Test\Unit\Observer;

use Mondido\Mondido\Test\Unit\MondidoObjectManager as ObjectManager;

/**
 * SalesOrderCreditmemoSaveAfterObserverTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class SalesOrderCreditmemoSaveAfterObserverTest extends \PHPUnit\Framework\TestCase
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
            'Mondido\Mondido\Observer\SalesOrderCreditmemoSaveAfterObserver'
        );
    }

    /**
     * Test execute
     *
     * @return void
     */
    public function testExecute()
    {
        $this->assertEquals(get_class($this->object), 'Mondido\Mondido\Observer\SalesOrderCreditmemoSaveAfterObserver');
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
