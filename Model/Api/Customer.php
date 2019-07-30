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
use Psr\Log\LoggerInterface;

/**
 * Mondido transaction API model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Robert Lord <robert@codepeak.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Customer extends Mondido
{
    public $resource = 'customers';
    protected $_config = 'config';
    protected $_storeManager;
    protected $urlBuilder;
    protected $objectManager;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\HTTP\Adapter\Curl       $adapter      HTTP adapter
     * @param \Mondido\Mondido\Model\Config              $config       Config object
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager Store manager
     * @param \Magento\Framework\UrlInterface            $urlBuilder   URL builder
     * @param Psr\Log\LoggerInterface                    $logger       Logger interface
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\HTTP\Adapter\Curl $adapter,
        \Mondido\Mondido\Model\Config $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        LoggerInterface $logger
    ) {
        $this->_adapter = $adapter;
        $this->_config = $config;
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->logger = $logger;
        $this->addressFactory = $addressFactory;

        if (!defined('PHPUNIT_MONDIDO_TESTSUITE')) {
            $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        }
    }

    /**
     * Handle a Magento customer
     *
     * @param \Magento\Customer\Model\Data\Customer $customer Magento customer object
     *
     * @return bool
     */
    public function handle(\Magento\Customer\Model\Data\Customer $customer)
    {
        // @todo Check if Mondido is enabled

        // Make sure we have a customer object
        if ($customer && $customer->getId()) {
            // Try to find Mondido ID
            $mondidoId = $this->getIdByRef($customer->getId());

            // Check if we should update or create the Mondido customer
            if ($mondidoId) {
                return $this->update($mondidoId, $customer);
            } else {
                return $this->create($customer);
            }
        }

        return false;
    }

    /**
     * Create new customer at Mondido
     *
     * @param \Magento\Customer\Model\Data\Customer $customer Magento customer object
     *
     * @return bool
     */
    public function create(\Magento\Customer\Model\Data\Customer $customer)
    {
        // Build metadata
        $metaData = $this->buildMetadata($customer);

        // Send API request
        $jsonResponse = $this->call(
            'POST',
            $this->resource,
            [],
            [
                'ref'      => $customer->getId(),
                'metadata' => json_encode($metaData)
            ]
        );

        // Make sure we've got a response
        if (!$jsonResponse) {
            return false;
        }

        // Parse the data
        $response = json_decode($jsonResponse);
        if (is_array($response)) {
            $response = current($response);
            if ($response && is_object($response) && property_exists($response, 'id')) {
                if ($response->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Update existing customer at Mondido
     *
     * @param integer|string                        $mondidoId Mondido ID
     * @param \Magento\Customer\Model\Data\Customer $customer  Magento customer ID
     *
     * @return bool
     */
    public function update($mondidoId, \Magento\Customer\Model\Data\Customer $customer)
    {
        // Build metadata
        $metaData = $this->buildMetadata($customer);

        // Send API request
        $jsonResponse = $this->call(
            'PUT',
            $this->resource,
            [$mondidoId],
            [
                'metadata' => json_encode($metaData)
            ]
        );

        // Make sure we've got a response
        if (!$jsonResponse) {
            return false;
        }

        // Parse the data
        $response = json_decode($jsonResponse);
        if (is_array($response)) {
            $response = current($response);
            if ($response && is_object($response) && property_exists($response, 'id')) {
                if ($response->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Show customer
     *
     * @param int $id Customer ID
     *
     * @return string
     */
    public function show($id)
    {
        $method = 'GET';

        return $this->call($method, $this->resource, (string) $id);
    }

    /**
     * Fetch Mondido ID using "ref" field (Magento customer ID)
     *
     * @param integer $referenceId Magento customer ID
     *
     * @return bool
     */
    public function getIdByRef($referenceId)
    {
        // Fetch json response from API
        $jsonResponse = $this->call('GET', $this->resource, [], ['filter[ref]' => $referenceId]);

        // Make sure we've got a response
        if (!$jsonResponse) {
            return false;
        }

        // Parse the data
        $response = json_decode($jsonResponse);
        if (is_array($response)) {
            $response = current($response);
            if ($response && is_object($response) && property_exists($response, 'id')) {
                return $response->id;
            }
        }

        return false;
    }

    /**
     * Build meta data for Mondido
     *
     * @param \Magento\Customer\Model\Data\Customer $customer Magento customer object
     *
     * @return array
     */
    public function buildMetadata(\Magento\Customer\Model\Data\Customer $customer)
    {
        // Setup basic data
        $metaData = [
            'firstname' => $customer->getFirstname(),
            'lastname'  => $customer->getLastname(),
            'email'     => $customer->getEmail(),
        ];

        // Fields to use when looking at addresses
        $addressFields = [
            'firstname',
            'middlename',
            'lastname',
            'company',
            'street',
            'postcode',
            'city',
            'country_id',
            'region',
            'telephone',
            'vat_id'
        ];

        // Add billing address
        if ($customer->getDefaultBilling()) {
            $billingAddress = $this->addressFactory->create();
            $billingAddress->load($customer->getDefaultBilling());
            if ($billingAddress && $billingAddress->getId()) {
                foreach ($addressFields as $fieldKey) {
                    $metaData['billing_' . $fieldKey] = $billingAddress->getData($fieldKey);
                }
            }
        }

        // Add shipping address
        if ($customer->getDefaultShipping()) {
            $shippingAddress = $this->addressFactory->create();
            $shippingAddress->load($customer->getDefaultShipping());
            if ($shippingAddress && $shippingAddress->getId()) {
                foreach ($addressFields as $fieldKey) {
                    $metaData['shipping_' . $fieldKey] = $shippingAddress->getData($fieldKey);
                }
            }
        }

        return $metaData;
    }
}
