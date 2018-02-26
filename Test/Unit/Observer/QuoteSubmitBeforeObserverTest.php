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

use Mondido\Mondido\Test\Unit\MondidoObjectManager as ObjectManager;

/**
 * Observer test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class QuoteSubmitBeforeObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Set up
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->object = $objectManager->getObject('\Mondido\Mondido\Observer\QuoteSubmitBeforeObserver');
    }

    /**
     * Test execute()
     *
     * @return void
     */
    public function testExecute()
    {
    }
}
