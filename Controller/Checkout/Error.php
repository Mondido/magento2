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
 * Error action
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Error extends \Mondido\Mondido\Controller\Checkout\Index
{
    /**
     * Execute
     *
     * @return @void
     */
    public function execute()
    {
        $message = $this->getRequest()->getParam('error_name');
        $this->messageManager->addError(__($message));

        $url = $this->_url->getUrl('checkout/cart');
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
