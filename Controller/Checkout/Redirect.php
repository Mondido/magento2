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

use Magento\Framework\UrlInterface;

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

    /** @var \Magento\Framework\UrlInterface */
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        UrlInterface $urlBuilder
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $url = $this->urlBuilder->getUrl('mondido/checkout/success');
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
