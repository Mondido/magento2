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

namespace Mondido\Mondido\Model\Api;

use Magento\Framework\UrlInterface;

/**
 * Mondido transaction API model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Transaction extends Mondido
{
    public $resource = 'transactions';
    protected $_config = 'config';
    protected $_storeManager;
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Framework\HTTP\Adapter\Curl       $adapter      HTTP adapter
     * @param \Mondido\Mondido\Model\Config              $config       Config object
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager Store manager
     * @param \Magento\Framework\UrlInterface            $urlBuilder   URL builder
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\HTTP\Adapter\Curl $adapter,
        \Mondido\Mondido\Model\Config $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder
    ) {
        $this->_adapter = $adapter;
        $this->_config = $config;
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Create hash
     *
     * @param Magento\Quote\Model\Quote $quote Quote object
     *
     * @return string
     */
    protected function _createHash(\Magento\Quote\Model\Quote $quote)
    {
        $hashRecipe = [
            'merchant_id' => $this->_config->getMerchantId(),
            'payment_ref' => $quote->getId(),
            'customer_ref' => $quote->getCustomerId() ? $quote->getCustomerId() : '',
            'amount' => number_format($quote->getBaseGrandTotal(), 2),
            'currency' => strtolower($quote->getBaseCurrencyCode()),
            'test' => $this->_config->isTest() ? 'test' : '',
            'secret' => $this->_config->getSecret()
        ];

        $hash = md5(implode($hashRecipe));

        return $hash;
    }

    /**
     * Create transaction
     *
     * @param Magento\Quote\Model\Quote $quote A quote object
     *
     * @return string
     */
    public function create(\Magento\Quote\Model\Quote $quote)
    {
        $method = 'POST';

        $webhook = [
            'url' => $this->_storeManager->getStore()->getUrl('mondido/payment'),
            'trigger' => 'payment',
            'http_method' => 'post',
            'data_format' => 'form_data'
        ];

        $quoteItems = $quote->getAllVisibleItems();

        $transactionItems = [];

        foreach ($quoteItems as $item) {
            $transactionItems[] = [
                'artno' => $item->getSku(),
                'description' => $item->getName(),
                'qty' => $item->getQty(),
                'amount' => $item->getBaseRowTotal(),
                'vat' => $item->getBaseTaxAmount(),
                'discount' => $item->getBaseDiscountAmount()
            ];
        }

        $shippingAddress = $quote->getShippingAddress('shipping');

        $transactionItems[] = [
            'artno' => $shippingAddress->getShippingMethod(),
            'description' => $shippingAddress->getShippingDescription(),
            'qty' => 1,
            'amount' => $shippingAddress->getBaseShippingAmount(),
            'vat' => $shippingAddress->getBaseShippingTaxAmount(),
            'discount' => $shippingAddress->getBaseDiscountAmount()
        ];

        $countryCodes = ['SE' => 'SWE'];

        $paymentDetails = [
            'email' => $shippingAddress->getEmail(),
            'phone' => $shippingAddress->getTelephone(),
            'first_name' => $shippingAddress->getFirstname(),
            'last_name' => $shippingAddress->getLastname(),
            'zip' => $shippingAddress->getPostcode(),
            'address_1' => $shippingAddress->getStreetLine(0),
            'address_2' => $shippingAddress->getStreetLine(1),
            'city' => $shippingAddress->getCity(),
            'country_code' => $countryCodes[$shippingAddress->getCountryId()]
        ];

        $metaData = [
            'user' => $paymentDetails,
            'products' => $transactionItems
        ];

        $data = [
            "merchant_id" => $this->_config->getMerchantId(),
            "amount" => number_format($quote->getBaseGrandTotal(), 2),
            "vat_amount" => number_format($shippingAddress->getBaseTaxAmount(), 2),
            "payment_ref" => $quote->getId(),
            'test' => $this->_config->isTest() ? 'true' : 'false',
            "metadata" => $metaData,
            'currency' => strtolower($quote->getBaseCurrencyCode()),
            "customer_ref" => $quote->getCustomerId() ? $quote->getCustomerId() : '',
            "hash" => $this->_createHash($quote),
            "process" => "false",
            "success_url" => $this->urlBuilder->getUrl('mondido/checkout/redirect'),
            "error_url" => $this->urlBuilder->getUrl('mondido/checkout/error'),
            "authorize" => $this->_config->getPaymentAction() == 'authorize' ? 'true' : 'false',
            "items" => json_encode($transactionItems),
            "webhook" => json_encode($webhook),
            "payment_details" => $paymentDetails
        ];

        return $this->call($method, $this->resource, null, $data);
    }

    /**
     * Update transaction
     *
     * @param Magento\Quote\Model\Quote $quote A quote object
     *
     * @return string|boolean
     */
    public function update(\Magento\Quote\Model\Quote $quote)
    {
        $transaction = json_decode($quote->getMondidoTransaction());

        if (property_exists($transaction, 'id')) {
            $id = $transaction->id;
        } else {
            return false;
        }

        $method = 'PUT';

        $quoteItems = $quote->getAllVisibleItems();
        $transactionItems = [];

        foreach ($quoteItems as $item) {
            $transactionItems[] = [
                'artno' => $item->getSku(),
                'description' => $item->getName(),
                'qty' => $item->getQty(),
                'amount' => $item->getBaseRowTotal(),
                'vat' => $item->getBaseTaxAmount(),
                'discount' => $item->getBaseDiscountAmount()
            ];
        }

        $data = [
            "amount" => number_format($quote->getBaseGrandTotal(), 2),
            "vat_amount" => number_format(0, 2),
            "metadata" => [],
            "currency" => strtolower($quote->getBaseCurrencyCode()),
            "customer_ref" => $quote->getCustomerId() ? $quote->getCustomerId() : '',
            "hash" => $this->_createHash($quote),
            "items" => json_encode($transactionItems),
            "process" => "false",
            "card_expiry" => "1217",
            "card_cvv" => "200",
            "card_number" => "41111111111111",
            "card_holder" => "John Doe",
            "card_type" => "VISA"
        ];

        return $this->call($method, $this->resource, (string) $id, $data);
    }

    /**
     * Capture transaction
     *
     * @param \Magento\Sales\Model\Order $order  Order
     * @param float                      $amount Amount to capture
     *
     * @return string
     */
    public function capture(\Magento\Sales\Model\Order $order, $amount)
    {
        $method = 'PUT';

        $transaction = json_decode($order->getMondidoTransaction());

        if (property_exists($transaction, 'id')) {
            $id = $transaction->id;
        } else {
            return false;
        }

        $data = ['amount' => number_format($amount, 2)];

        return $this->call($method, $this->resource, [$id, 'capture'], $data);
    }

    /**
     * Capture transaction
     *
     * @param \Magento\Sales\Model\Order $order  Order
     * @param float                      $amount Amount to capture
     *
     * @return string
     */
    public function refund(\Magento\Sales\Model\Order $order, $amount)
    {
        $method = 'POST';

        $transaction = json_decode($order->getMondidoTransaction());

        if (property_exists($transaction, 'id')) {
            $id = $transaction->id;
        } else {
            return false;
        }

        $data = ['amount' => number_format($amount, 2), 'reason' => 'Refund from Magento', 'transaction_id' => $id];

        return  $this->call($method, 'refunds', null, $data);
    }


    /**
     * Show transaction
     *
     * @param int $id Transaction ID
     *
     * @return string
     */
    public function show($id)
    {
        $method = 'GET';

        return $this->call($method, $this->resource, $id);
    }
}
