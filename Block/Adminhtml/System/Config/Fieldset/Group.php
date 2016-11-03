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

namespace Mondido\Mondido\Block\Adminhtml\System\Config\Fieldset;

/**
 * Fieldset group block
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Group extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Context      $context     Context
     * @param \Magento\Backend\Model\Auth\Session $authSession Auth session
     * @param \Magento\Framework\View\Helper\Js   $jsHelper    Javascript helper
     * @param array                               $data        Data
     *
     * @return void
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
    }

    /**
     * Is collapse state
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Element
     *
     * @return bool
     */
    protected function _isCollapseState($element)
    {
        $extra = $this->_authSession->getUser()->getExtra();
        if (isset($extra['configState'][$element->getId()])) {
            return $extra['configState'][$element->getId()];
        }

        $groupConfig = $element->getGroup();
        if (!empty($groupConfig['expanded'])) {
            return true;
        }

        return false;
    }
}
