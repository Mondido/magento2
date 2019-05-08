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
 * @author   Robert Lord <robert@codepeak.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class IsoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Mondido\Mondido\Helper\Iso
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
        $this->object = $objectManager->getObject('Mondido\Mondido\Helper\Iso');
    }

    /**
     * Assure we get an array from "getTranslateArray" method
     *
     * @return void
     */
    public function testTranslateArray()
    {
        $resultDataSet = $this->object->getTranslateArray();
        $this->assertTrue(is_array($resultDataSet));
    }

    /**
     * Test the transform function
     *
     * @return void
     */
    public function testTransformTooLongString()
    {
        $this->setExpectedException(\Exception::class);
        $this->object->transform('TOLONGSTRING');
    }

    /**
     * Test the raw data used in translations from alpha 2 to alpha 3
     *
     * @return void
     */
    public function testTranslateArrayDataContent()
    {
        foreach ($this->object->getTranslateArray() as $key => $value) {
            $this->assertTrue(strlen($key) === 2);
            $this->assertTrue(strlen($value) === 3);
        }
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
