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

    /**
     * Constructor
     *
     * @param \Magento\Framework\HTTP\Adapter\Curl $adapter HTTP adapter
     * @param \Mondido\Mondido\Model\Config        $config  Config object
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\HTTP\Adapter\Curl $adapter,
        \Mondido\Mondido\Model\Config $config
    ) {
        $this->_adapter = $adapter;
        $this->_config = $config;
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
            'customer_ref' => $quote->getCustomerId(),
            'amount' => $quote->getBaseGrandTotal(),
            'currency' => $quote->getBaseCurrencyCode(),
            'test' => $this->_config->isTest(),
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

        $data = [
            "merchant_id" => $this->_config->getMerchantId(),
            "amount" => $quote->getBaseGrandTotal(),
            "vat_amount" => "0",
            "payment_ref" => $quote->getId(),
            "test" => $this->_config->isTest(),
            "metadata" => [],
            "currency" => "sek",
            "customer_ref" => $quote->getCustomerId(),
            "hash" => $this->_createHash($quote),
            "process" => "false",
            "success_url" => "",
            "error_url" => "",
            "authorize" => $quote->getPaymentAction(),
            "Items" => []
        ];

        return $this->call($method, $this->resource, null, $data);
    }

    /**
     * Capture transaction
     *
     * @return string
     */
    public function capture()
    {
        $method = 'PUT';

        return $this->call($method, $this->resource, [$id, 'capture']);
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
