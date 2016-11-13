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

namespace Mondido\Mondido\Controller\Checkout;

/**
 * Redirect action
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Redirect extends \Magento\Checkout\Controller\Onepage
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        // Get session
        $session = $this->getOnepage()->getCheckout();

        // Get quote
        $quote = $this->getOnepage()->getQuote();

        $reservedOrderId = $quote->reserveOrderId()->setIsActive(0)->save();
        $quoteId = $quote->getId();

        $session->setLastQuoteId($quoteId)
            ->setLastSuccessQuoteId($quoteId)
            ->clearHelperData()
            ->setLastRealOrderId($reservedOrderId);

        $url = $this->_url->getUrl('mondido/checkout/success');
        echo '<!doctype html>
<html>
<head>
<script>
    var isInIframe = (window.location != window.parent.location) ? true : false;
    if (isInIframe == true) {
        window.top.location.href = "'.$url.'";
    } else {
        window.location.href = "'.$url.'";
    }
</script>
</head>
<body></body>
</html>';
        die;
    }
}
