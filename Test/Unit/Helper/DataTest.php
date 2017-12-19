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

namespace Mondido\Mondido\Test\Unit\Helper;

use Mondido\Mondido\Test\Unit\MondidoObjectManager as ObjectManager;

/**
 * Data helper test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class DataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Mondido\Mondido\Helper\Data
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
        $objectManager = new ObjectManager($this);
        $this->object = $objectManager->getObject('Mondido\Mondido\Helper\Data');
    }

    /**
     * Temp test
     *
     * @return void
     */
    public function testTmp()
    {
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->object = null;
    }
}
