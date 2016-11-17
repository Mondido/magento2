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
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context            $context           Context object
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory Result factory
     * @param \Psr\Log\LoggerInterface                         $logger            Logger interface
     * @param \Magento\Quote\Api\CartRepositoryInterface       $quoteRepository   Cart repository interface
     * @param \Magento\Quote\Api\CartManagementInterface       $quoteManagement   Cart management interface
     *
     * @return void
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger,
        CartRepositoryInterface $quoteRepository,
        CartManagementInterface $quoteManagement
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->quoteRepository = $quoteRepository;
        $this->quoteManagement = $quoteManagement;
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

        if (array_key_exists('status', $data) && in_array($data['status'], ['approved', 'authorized'])) {
            $quoteId = $data['payment_ref'];
            $quote = $this->quoteRepository->get($quoteId);

            try {
                $shippingAddress = $quote->getShippingAddress('shipping');
                $shippingAddress->setFirstname('John');
                $shippingAddress->setLastname('Doe');
                $shippingAddress->setStreet(['Street 1', 'Street 2']);
                $shippingAddress->setCity('City');
                $shippingAddress->setPostcode('123 45');
                $shippingAddress->setTelephone('12345');
                $shippingAddress->setEmail('john.doe@example.com');
                $shippingAddress->save();

                $billingAddress = $quote->getBillingAddress('billing');
                $billingAddress->setFirstname('John');
                $billingAddress->setLastname('Doe');
                $billingAddress->setStreet(['Street 1', 'Street 2']);
                $billingAddress->setCity('City');
                $billingAddress->setPostcode('123 45');
                $billingAddress->setTelephone('12345');
                $billingAddress->setEmail('john.doe@example.com');
                $billingAddress->setCountryId('SE');
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

            if ($order) {
                $result['order_ref'] = $order->getIncrementId();
                $this->logger->debug('Order created for quote ID ' . $quoteId);

                // Authorize transaction
                try {
                    // Fetch object manager
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                    // Fetch transaction builder interface
                    $builderInterface = $objectManager
                        ->get('Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface');

                    // Prepare the transaction
                    $payment = $order->getPayment();
                    $payment->setLastTransId($data['id']);
                    $payment->setTransactionId($data['id']);
                    $payment->setAdditionalInformation(
                        [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $data]
                    );
                    $formatedPrice = $order->getBaseCurrency()->formatTxt(
                        $order->getGrandTotal()
                    );

                    $message = __('The authorized amount is %1.', $formatedPrice);

                    //get the object of builder class
                    $transaction = $builderInterface
                        ->setPayment($payment)
                        ->setOrder($order)
                        ->setTransactionId($data['id'])
                        ->setAdditionalInformation(
                            [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $data]
                        )
                        ->setFailSafe(true)
                        //build method creates the transaction and returns the object
                        ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH);

                    $payment->addTransactionCommentsToOrder(
                        $transaction,
                        $message
                    );
                    $payment->setParentTransactionId(null);
                    $payment->save();
                    $order->save();
                } catch (Exception $e) {
                    $this->logger->debug('Unable to save auth transaction: ' . $e->getMessage());
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
