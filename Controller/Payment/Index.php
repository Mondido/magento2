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

namespace Mondido\Mondido\Controller\Payment;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Mondido\Mondido\Helper\Iso;
use Mondido\Mondido\Model\Api\Transaction;
use Mondido\Mondido\Helper\Data;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;

/**
 * Payment action
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Quote\Api\CartManagementInterface
     */
    protected $quoteManagement;

    /**
     * @var \Mondido\Mondido\Helper\Iso
     */
    protected $isoHelper;

    /**
     * @var \Mondido\Mondido\Model\Api\Transaction
     */
    protected $transaction;

    /**
     * @var \Mondido\Mondido\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context               $context           Context object
     * @param \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory Result factory
     * @param \Psr\Log\LoggerInterface                            $logger            Logger interface
     * @param \Magento\Quote\Api\CartRepositoryInterface          $quoteRepository   Cart repository interface
     * @param \Magento\Quote\Api\CartManagementInterface          $quoteManagement   Cart management interface
     * @param \Mondido\Mondido\Helper\Iso                         $isoHelper         ISO helper
     * @param \Mondido\Mondido\Api\Transaction                    $transaction       Transaction API model
     * @param \Mondido\Mondido\Helper\Data                        $helper            Data helper
     * @param \Magento\Sales\Model\Order                          $order             Order model
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender       Order seder
     *
     * @return void
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger,
        CartRepositoryInterface $quoteRepository,
        CartManagementInterface $quoteManagement,
        Iso $isoHelper,
        Transaction $transaction,
        Data $helper,
        Order $order,
        OrderSender $orderSender
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->quoteRepository = $quoteRepository;
        $this->quoteManagement = $quoteManagement;
        $this->isoHelper = $isoHelper;
        $this->transaction = $transaction;
        $this->helper = $helper;
        $this->order = $order;
        $this->orderSender = $orderSender;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $this->logger->debug(var_export($data, true));

        $result = [];
        $resultJson = $this->resultJsonFactory->create();

        if (array_key_exists('status', $data) && in_array($data['status'], ['authorized'])) {
            $quoteId = $data['payment_ref'];
            $orderObject = $this->order->loadByAttribute('quote_id', $quoteId);
            $incrementId = $orderObject->getIncrementId();

            if ($incrementId) {
                // Order is already created
                $order = $orderObject;
                $orderIsAlreadyCreated = true;
            } else {
                // Prepare to create order
                $quote = $this->quoteRepository->get($quoteId);

                if ($quote->getIsActive()) {
                    $order = false;
                    $result['error'] = 'Quote is still active in Magento, please try again in a while.';
                    $resultJson->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR);
                } else if ($data['amount'] !== $this->helper->formatNumber($quote->getBaseGrandTotal())) {
                    $order = false;
                    $result['error'] = 'Wrong amount';
                    $resultJson->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_BAD_REQUEST);
                } else {
                    try {
                        $transactionJson = $this->transaction->show($data['id']);
                        $transaction = json_decode($transactionJson);

                        $shippingAddress = $quote->getShippingAddress('shipping');
                        $shippingAddress->setFirstname($transaction->payment_details->first_name);
                        $shippingAddress->setLastname($transaction->payment_details->last_name);
                        $shippingAddress->setStreet([$transaction->payment_details->address_1, $transaction->payment_details->address_2]);
                        $shippingAddress->setCity($transaction->payment_details->city);
                        $shippingAddress->setPostcode($transaction->payment_details->zip);
                        $shippingAddress->setTelephone($transaction->payment_details->phone ?: '0');
                        $shippingAddress->setEmail($transaction->payment_details->email);
                        $shippingAddress->setCountryId($this->isoHelper->convertFromAlpha3($transaction->payment_details->country_code));
                        $shippingAddress->save();

                        $billingAddress = $quote->getBillingAddress('billing');
                        $billingAddress->setFirstname($transaction->payment_details->first_name);
                        $billingAddress->setLastname($transaction->payment_details->last_name);
                        $billingAddress->setStreet([$transaction->payment_details->address_1, $transaction->payment_details->address_2]);
                        $billingAddress->setCity($transaction->payment_details->city);
                        $billingAddress->setPostcode($transaction->payment_details->zip);
                        $billingAddress->setTelephone($transaction->payment_details->phone ?: '0');
                        $billingAddress->setEmail($transaction->payment_details->email);
                        $billingAddress->setCountryId($this->isoHelper->convertFromAlpha3($transaction->payment_details->country_code));
                        $billingAddress->save();

                        $quote->getPayment()->importData(['method' => 'mondido_hostedwindow']);
                        $quote->getPayment()->setAdditionalInformation('id', $data['id']);
                        $quote->getPayment()->setAdditionalInformation('href', $data['href']);
                        $quote->getPayment()->setAdditionalInformation('status', $data['status']);

                        $quote->collectTotals()->save();
                        $quote->setCheckoutMethod('guest');

                        if ($quote->getCheckoutMethod() === 'guest') {
                            $quote->setCustomerId(null);
                            $quote->setCustomerEmail($quote->getBillingAddress()->getEmail());
                            $quote->setCustomerIsGuest(true);
                            $quote->setCustomerGroupId(\Magento\Customer\Api\Data\GroupInterface::NOT_LOGGED_IN_ID);
                        }

                        $order = $this->quoteManagement->submit($quote);
                    } catch (\Magento\Framework\Exception\LocalizedException $e) {
                        $order = false;
                        $this->logger->debug($e);
                        $result['error'] = $e->getMessage();
                        $resultJson->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_BAD_REQUEST);
                    }
                }
            }

            if ($order) {
                $result['order_ref'] = $order->getIncrementId();

                if (isset($orderIsAlreadyCreated) && $orderIsAlreadyCreated) {
                    $this->logger->debug('Order was already created for quote ID ' . $quoteId);
                } else {
                    $this->logger->debug('Order created for quote ID ' . $quoteId);

                    if ($order->getCanSendNewEmailFlag()) {
                        try {
                            $this->orderSender->send($order);
                            $order->setCanSendNewEmailFlag(false)->save();
                        } catch (\Exception $e) {
                            $this->_logger->critical($e);
                        }
                    }
                }
            } else {
                $this->logger->debug('Order could not be created for quote ID ' . $quoteId);
            }
        }

        $response = json_encode($result);
        $resultJson->setData($response);

        return $resultJson;
    }
}
