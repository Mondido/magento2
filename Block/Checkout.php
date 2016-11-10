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

namespace Mondido\Mondido\Block;

/**
 * Checkout block
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Checkout extends \Magento\Checkout\Block\Onepage
{
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context          Context
     * @param \Magento\Framework\Data\Form\FormKey             $formKey          Form key
     * @param \Magento\Checkout\Model\CompositeConfigProvider  $configProvider   Config provider
     * @param array                                            $layoutProcessors Array with layout processors
     * @param array                                            $data             Array with data
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\CompositeConfigProvider $configProvider,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $formKey, $configProvider, $layoutProcessors, $data);
    }
}
