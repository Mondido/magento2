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

namespace Mondido\Mondido\Test\Unit\Block;

use Mondido\Mondido\Test\Unit\MondidoObjectManager as ObjectManager;

/**
 * CheckoutTest
 *
 * @todo Needs rewrite!
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class CheckoutTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var
     */
    protected $object;

    /**
     * @var \Mondido\Mondido\Test\Unit\MondidoObjectManager
     */
    protected $objectManager;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        /*
        $this->objectManager = new ObjectManager($this);
        $this->object = $this->objectManager->getObject(
            'Mondido\Mondido\Block\Checkout'
        );
        */
    }

    /**
     * Test execute
     *
     * @return void
     */
    public function testExecute()
    {
        #$this->assertEquals(get_class($this->object), 'Mondido\Mondido\Block\Checkout');
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
