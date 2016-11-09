<?php

namespace Mondido\Mondido\Block;

class Checkout extends \Magento\Checkout\Block\Onepage
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\CompositeConfigProvider $configProvider,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $formKey, $configProvider, $layoutProcessors, $data);
    }
}
