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

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Redirect action
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Redirect extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
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
