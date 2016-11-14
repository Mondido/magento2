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

use Magento\Framework\DataObject;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment;
use Magento\Quote\Api\Data\PaymentMethodInterface;

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
    protected $_canCapturePartial = true;

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
     * Constructor
     *
     * @param \Magento\Framework\Model\Context                        $context
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory       $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory            $customAttributeFactory Attribute value factory
     * @param \Magento\Payment\Helper\Data                            $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface      $scopeConfig
     * @param Logger                                                  $logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection
     * @param \Mondido\Mondido\Model\Api\Transaction                  $transaction
     * @param array                                                   $data
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Mondido\Mondido\Model\Api\Transaction $transaction,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
        $this->_paymentData = $paymentData;
        $this->_scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->transaction = $transaction;
        $this->initializeData($data);
    }

    /**
     * Authorize
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment Payment
     * @param float                                       $amount  Amount
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
        $order = $payment->getOrder();
        $this->transaction->capture($order, $amount);

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
