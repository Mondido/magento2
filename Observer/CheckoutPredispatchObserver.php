<?php
namespace Mondido\Mondido\Observer;

use Magento\Framework\Event\ObserverInterface;

class CheckoutPredispatchObserver implements ObserverInterface
{
    protected $transaction;

    public function __construct(\Mondido\Mondido\Model\Api\Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getControllerAction()->getOnepage()->getQuote();

        if ($quote->getId()) {
            $response = $this->transaction->create($quote);

            $quote->setMondidoTransaction($response);
            $quote->save();
        }
    }
}
