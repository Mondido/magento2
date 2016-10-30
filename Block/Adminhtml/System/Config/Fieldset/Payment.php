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
 * Fieldset payment block
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Payment extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Magento\Config\Model\Config Backend config
     */
    protected $_backendConfig;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Context      $context       Context
     * @param \Magento\Backend\Model\Auth\Session $authSession   Auth session
     * @param \Magento\Framework\View\Helper\Js   $jsHelper      Javascript helper
     * @param \Magento\Backend\Block\Config       $backendConfig Backend config
     * @param array                               $data          Data
     *
     * @return void
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Config\Model\Config $backendConfig,
        array $data = []
    ) {
        $this->_backendConfig = $backendConfig;
        parent::__construct($context, $authSession, $jsHelper, $data);
    }

    /**
     * Get frontend class
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Element
     *
     * @return string
     */
    protected function _getFrontendClass($element)
    {
        $enabledString = $this->_isPaymentEnabled($element) ? ' enabled' : '';
        return parent::_getFrontendClass($element) . ' with-button' . $enabledString;
    }

    /**
     * Is payment enabled
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Element
     *
     * @return bool
     */
    protected function _isPaymentEnabled($element)
    {
        $groupConfig = $element->getGroup();
        $activityPaths = isset($groupConfig['activity_path']) ? $groupConfig['activity_path'] : [];

        if (!is_array($activityPaths)) {
            $activityPaths = [$activityPaths];
        }

        $isPaymentEnabled = false;
        foreach ($activityPaths as $activityPath) {
            $isPaymentEnabled = $isPaymentEnabled
                || (bool)(string)$this->_backendConfig->getConfigDataValue($activityPath);
        }

        return $isPaymentEnabled;
    }

    /**
     * Get header title HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Element
     *
     * @return string
     */
    protected function _getHeaderTitleHtml($element)
    {
        $html = '<div class="config-heading" ><div class="heading"><strong>' . $element->getLegend();

        $groupConfig = $element->getGroup();

        $html .= '</strong>';

        if ($element->getComment()) {
            $html .= '<span class="heading-intro">' . $element->getComment() . '</span>';
        }
        $html .= '</div>';

        $disabledAttributeString = $this->_isPaymentEnabled($element) ? '' : ' disabled="disabled"';
        $disabledClassString = $this->_isPaymentEnabled($element) ? '' : ' disabled';
        $htmlId = $element->getHtmlId();
        $html .= '<div class="button-container"><button type="button"' .
            $disabledAttributeString .
            ' class="button action-configure' .
            (empty($groupConfig['paypal_ec_separate']) ? '' : ' paypal-ec-separate') .
            $disabledClassString .
            '" id="' .
            $htmlId .
            '-head" onclick="mondidoToggleSolution.call(this, \'' .
            $htmlId .
            "', '" .
            $this->getUrl(
                'adminhtml/*/state'
            ) . '\'); return false;"><span class="state-closed">' . __(
                'Configure'
            ) . '</span><span class="state-opened">' . __(
                'Close'
            ) . '</span></button>';

        $html .= '</div></div>';

        return $html;
    }

    /**
     * Get header comment HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Element
     *
     * @return string
     */
    protected function _getHeaderCommentHtml($element)
    {
        return '';
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
        return false;
    }
}
