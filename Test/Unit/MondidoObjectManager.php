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

namespace Mondido\Mondido\Test\Unit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class MondidoObjectManager
 *
 * @category Mondido
 * @package  Mondido\Mondido\Test\Unit
 * @author   Robert Lord, Codepeak AB <robert@codepeak.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://codepeak.se
 */
class MondidoObjectManager extends ObjectManager
{
    /**
     * MondidoObjectManager constructor.
     *
     * @param \PHPUnit\Framework\TestCase $testObject Object to test
     */
    public function __construct($testObject)
    {
        $this->_testObject = $testObject;
    }

    /**
     * Get mock without constructor call
     *
     * @param string $className Class name
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getMockWithoutConstructorCall($className)
    {
        $mock = $this->_testObject->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->getMock();

        return $mock;
    }
}
