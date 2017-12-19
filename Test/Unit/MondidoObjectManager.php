<?php

namespace Mondido\Mondido\Test\Unit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class MondidoObjectManager
 *
 * @package  Mondido\Mondido\Test\Unit
 * @license  MIT License https://opensource.org/licenses/MIT
 * @author   Robert Lord, Codepeak AB <robert@codepeak.se>
 * @link     https://codepeak.se
 */
class MondidoObjectManager extends ObjectManager
{
    /**
     * MondidoObjectManager constructor.
     *
     * @param \PHPUnit\Framework\TestCase $testObject
     */
    public function __construct($testObject)
    {
        $this->_testObject = $testObject;
    }

    /**
     * Get mock without constructor call
     *
     * @param string $className
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