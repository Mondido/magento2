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

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mondido\Mondido\Model\Api\Transaction $transaction,
        array $data = []
    ) {
        $this->transaction = $transaction;
        parent::__construct($context, $data);
    }

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
        $transport = new \Magento\Framework\DataObject();

        $transaction = $this->transaction->show($info->getAdditionalInformation('id'));

        $data = json_decode($transaction, true);

        $transport->setData('ID', $data['id']);
        $transport->setData('Reference', $data['payment_ref']);
        $transport->setData('Status', $data['status']);
        $transport->setData('Payment method', $data['transaction_type']);
        $transport->setData('Card type', $data['payment_details']['card_type']);
        $transport->setData('Card number', $data['payment_details']['card_number']);
        $transport->setData('Card holder', $data['payment_details']['card_holder']);
        $transport->setData('SSN', $data['payment_details']['ssn']);
        $transport->setData('Currency', strtoupper($data['currency']));
        $transport->setData('Payment link', $data['href']);
        $transport->setData('Created at', $data['created_at']);
        $transport->setData('Processed at', $data['processed_at']);

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport;
    }
}
