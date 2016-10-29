<?php

namespace Mondido\Mondido\Block\Adminhtml\System\Config\Fieldset;

class Script extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{

    protected $_template = 'Mondido_Mondido::system/config/fieldset/script.phtml';

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }
}
