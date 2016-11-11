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

namespace Mondido\Mondido\Plugin\Block\Cart;

use Magento\Framework\UrlInterface;
use Mondido\Mondido\Model\Config;

/**
 * Cart sidebar block plugin
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Sidebar
{
    protected $urlBuilder;
    protected $config;

    /**
     * Constructor
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder URL builder
     * @param \Mondido\Mondido\Model\Config   $config     Config model
     */
    public function __construct(UrlInterface $urlBuilder, Config $config)
    {
        $this->urlBuilder = $urlBuilder;
        $this->config = $config;
    }

    /**
     * After getCheckoutUrl()
     *
     * @param \Magento\Checkout\Block\Cart\Sidebar $subject Checkout cart block
     * @param string                               $result  The original result from the method in $subject
     * 
     * @return string
     */
    public function afterGetCheckoutUrl(\Magento\Checkout\Block\Cart\Sidebar $subject, $result)
    {
        if (!$this->config->isActive()) {
            return $result;
        }

        return $this->urlBuilder->getUrl('mondido/checkout');
    }
}
