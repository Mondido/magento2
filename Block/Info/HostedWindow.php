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

namespace Mondido\Mondido\Block\Info;

use Magento\Payment\Block\Info;

/**
 * Hosted Window info block
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class HostedWindow extends Info
{
    /**
     * @var string
     */
    protected $_template = 'Mondido_Mondido::info/hostedwindow.phtml';

    /**
     * Prepare specific information
     *
     * @param null|\Magento\Framework\DataObject|array $transport Information
     *
     * @return \Magento\Framework\DataObject
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }

        $info = $this->getInfo();
        $referenceID = $info->getAdditionalInformation('method_title');
        $transport = new \Magento\Framework\DataObject([(string)__('Method title') => $referenceID]);
        $transport->setData('ID', $info->getAdditionalInformation('id'));
        $transport->setData('URL', $info->getAdditionalInformation('href'));
        $transport->setData('Status', $info->getAdditionalInformation('status'));

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport;
    }
}
