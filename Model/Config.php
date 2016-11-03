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

use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;

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

        return $this->_scopeConfig->getValue($configPath);
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        $configPath = sprintf($this->configPathPattern, 'password');

        return $this->_scopeConfig->getValue($configPath);
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        $configPath = sprintf($this->configPathPattern, 'secret');

        return $this->_scopeConfig->getValue($configPath);
    }

    /**
     * Get payment action
     *
     * @return string
     */
    public function getPaymentAction()
    {
        $configPath = sprintf($this->configPathPattern, 'payment_action');

        return $this->_scopeConfig->getValue($configPath);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        $configPath = sprintf($this->configPathPattern, 'active');

        return $this->_scopeConfig->isSetFlag($configPath);
    }

    /**
     * Is test
     *
     * @return bool
     */
    public function isTest()
    {
        $configPath = sprintf($this->configPathPattern, 'test');

        return $this->_scopeConfig->isSetFlag($configPath);
    }
}
