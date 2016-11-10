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
     * @param \Mondido\Mondido\Model\Api\Transaction $transaction Transaction API model
     *
     * @return void
     */
    public function __construct(\Mondido\Mondido\Model\Api\Transaction $transaction)
    {
        $this->transaction = $transaction;
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

        $shippingAddress = $quote->getShippingAddress('shipping')->setCountryId('SE')->save();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate');
        $quote->collectTotals()->save();

        if ($quote->getId()) {
            if (!$quote->getMondidoTransaction()) {
                $response = $this->transaction->create($quote);
            } else {
                $response = $this->transaction->update($quote);
            }

            $data = json_decode($response);

            if (isset($data->id)) {
                $quote->setMondidoTransaction($response);
                $quote->save();
            } else {
                // Log $data->description;
            }
        }
    }
}
