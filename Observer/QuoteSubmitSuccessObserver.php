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
use Magento\Framework\Message\ManagerInterface;
use Mondido\Mondido\Model\Api\Transaction;

/**
 * Quote submit success observer
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class QuoteSubmitSuccessObserver implements ObserverInterface
{
    /**
     * Constructor
     *
     * @param \Mondido\Mondido\Model\Api\Transaction      $transaction    Transaction API model
     * @param \Magento\Framework\Message\ManagerInterface $messageManager Message manager
     *
     * @return void
     */
    public function __construct(
        Transaction $transaction,
        ManagerInterface $messageManager
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
        $quote = $observer->getQuote();
        $order = $observer->getOrder();

        $metadata = [];
        $metadata['magento']['order']['entity_id'] = $order->getId();
        $metadata['magento']['order']['increment_id'] = $order->getIncrementId();
        $metadata['magento']['quote']['reserved_order_id'] = $quote->getReservedOrderId();

        $this->transaction->updateMetadata($quote, $metadata);
    }
}
