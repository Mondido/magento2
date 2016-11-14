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

namespace Mondido\Mondido\Model;

use Magento\Payment\Model\InfoInterface;

/**
 * Hosted Window model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class HostedWindow extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = 'mondido_hostedwindow';

    /**
     * @var string
     */
    protected $_formBlockType = 'Magento\OfflinePayments\Block\Form\Checkmo';

    /**
     * @var string
     */
    protected $_infoBlockType = 'Mondido\Mondido\Block\Info\HostedWindow';

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapturePartial = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefund = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefundInvoicePartial = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canUseCheckout = true;

    /**
     * Mondido API wrapper for transactions
     *
     * @var \Mondido\Mondido\Model\Api\Transaction
     */
    protected $transaction;

    /**
     * Authorize
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment Payment
     * @param float $amount                                        Amount
     *
     * @return $this
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        return $this;
    }

    /**
     * Order payment
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment Payment
     * @param float $amount                                        Amount
     *
     * @return $this
     */
    public function order(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        return $this;
    }

    /**
     * Capture payment
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment Payment
     * @param float $amount                                        Amount
     *
     * @return $this
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->transaction = $objectManager->get('Mondido\Mondido\Model\Api\Transaction');

        $order = $payment->getOrder();
        $result = $this->transaction->capture($order, $amount);
        $result = json_decode($result);

        if (property_exists($result, 'code') && $result->code != 200) {
            $message = sprintf(
                __("Mondido returned error code %d: %s (%s)"),
                $result->code,
                $result->description,
                $result->name
            );
            throw new \Magento\Framework\Exception\LocalizedException(__($message));
        }

        return true;
    }

    /**
     * Refund specified amount for payment
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment Payment
     * @param float $amount                                        Amount
     *
     * @return $this
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->transaction = $objectManager->get('Mondido\Mondido\Model\Api\Transaction');

        $order = $payment->getOrder();
        $result = $this->transaction->refund($order, $amount);
        $result = json_decode($result);

        if (property_exists($result, 'code') && $result->code != 200) {
            $message = sprintf(
                __("Mondido returned error code %d: %s (%s)"),
                $result->code,
                $result->description,
                $result->name
            );
            throw new \Magento\Framework\Exception\LocalizedException(__($message));
        }

        return $this;
    }

    /**
     * Void payment
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment Payment
     *
     * @return $this
     */
    public function void(\Magento\Payment\Model\InfoInterface $payment)
    {
        return $this;
    }

    /**
     * Attempt to accept a payment that is under review
     *
     * @param InfoInterface $payment
     *
     * @return false
     */
    public function acceptPayment(InfoInterface $payment)
    {
        return false;
    }

    /**
     * Attempt to deny a payment that is under review
     *
     * @param InfoInterface $payment
     *
     * @return false
     */
    public function denyPayment(InfoInterface $payment)
    {
        return false;
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null|\Magento\Store\Model\Store $storeId
     *
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        if ('order_place_redirect_url' === $field) {
            return $this->getOrderPlaceRedirectUrl();
        }
        if (null === $storeId) {
            $storeId = $this->getStore();
        }
        $path = 'payment/mondido/' . $field;
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
}
