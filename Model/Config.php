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

use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Config model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Config
{
    protected $_scopeConfig;
    protected $configPathPattern = 'payment/mondido/%s';

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig Config
     *
     * @return void
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get merchant ID
     *
     * @return string
     */
    public function getMerchantId()
    {
        $configPath = sprintf($this->configPathPattern, 'merchant_id');
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->getValue($configPath, $scope);
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        $configPath = sprintf($this->configPathPattern, 'password');
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->getValue($configPath, $scope);
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        $configPath = sprintf($this->configPathPattern, 'secret');
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->getValue($configPath, $scope);
    }

    /**
     * Get payment action
     *
     * @return string
     */
    public function getPaymentAction()
    {
        $configPath = sprintf($this->configPathPattern, 'payment_action');
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->getValue($configPath, $scope);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        $configPath = sprintf($this->configPathPattern, 'active');
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->isSetFlag($configPath, $scope);
    }

    /**
     * Is test
     *
     * @return bool
     */
    public function isTest()
    {
        $configPath = sprintf($this->configPathPattern, 'test');
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->isSetFlag($configPath, $scope);
    }

    /**
     * Get allowed countries
     *
     * @return string
     */
    public function getAllowedCountries()
    {
        $configPath = 'general/country/allow';
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE;

        return $this->_scopeConfig->getValue($configPath, $scope);
    }

    /**
     * Get default country
     *
     * @return string
     */
    public function getDefaultCountry()
    {
        $configPath = 'general/country/default';
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->_scopeConfig->getValue($configPath, $scope);
    }
}
