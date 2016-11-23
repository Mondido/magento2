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

namespace Mondido\Mondido\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Checkout predispatch observer
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class CheckoutPredispatchObserver implements ObserverInterface
{
    protected $transaction;

    /**
     * Constructor
     *
     * @param \Mondido\Mondido\Model\Api\Transaction      $transaction    Transaction API model
     * @param \Magento\Framework\Message\ManagerInterface $messageManager Message manager
     *
     * @return void
     */
    public function __construct(
        \Mondido\Mondido\Model\Api\Transaction $transaction,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->transaction = $transaction;
        $this->messageManager = $messageManager;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer Observer object
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getControllerAction()->getOnepage()->getQuote();

        $customer = $quote->getCustomer();

        if ($customer->getId() && $customer->getPrimaryShippingAddress()) {
            $address = $customer->getPrimaryShippingAddress();
            $quote->setShippingAddress($address);
            $quote->setBillingAddress($address);
        }

        $shippingAddress = $quote->getShippingAddress('shipping');

        if (!$shippingAddress->getCountryId()) {
            $shippingAddress->setCountryId('SE')->save();
        }

        if (!$shippingAddress->getShippingMethod()) {
            $shippingAddress->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod('flatrate_flatrate');
            $quote->collectTotals()->save();
        }

        if ($quote->getId()) {
            if (!$quote->getMondidoTransaction()) {
                $response = $this->transaction->create($quote);
            } else {
                $response = $this->transaction->update($quote);
            }

            if ($response) {
                $data = json_decode($response);

                if (property_exists($data, 'id')) {
                    $quote->setMondidoTransaction($response);
                    $quote->save();
                } else {
                    $message = sprintf(
                        __("Mondido returned error code %d: %s (%s)"),
                        $data->code,
                        $data->description,
                        $data->name
                    );

                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $request = $objectManager->get('Magento\Framework\App\Request\Http');
                    $urlInterface = $objectManager->get('Magento\Framework\UrlInterface');
                    $url = $urlInterface->getUrl('checkout/cart');

                    $this->messageManager->addError(__($message));

                    $observer->getControllerAction()
                         ->getResponse()
                         ->setRedirect($url);
                }
            }
        }
    }
}
