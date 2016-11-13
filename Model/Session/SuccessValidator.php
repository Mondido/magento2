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

namespace Mondido\Mondido\Model\Session;

/**
 * Success validator
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class SuccessValidator
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * Constructor
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession Session
     *
     * @return void
     */
    public function __construct(\Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Is valid
     *
     * @return bool
     */
    public function isValid()
    {
        if (!$this->checkoutSession->getLastSuccessQuoteId()) {
            return false;
        }

        if (!$this->checkoutSession->getLastQuoteId() || !$this->checkoutSession->getLastRealOrderId()) {
            return false;
        }

        return true;
    }
}
