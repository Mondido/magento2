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

namespace Mondido\Mondido\Model;

use Mondido\Mondido\Api\GuestTransactionManagementInterface;
use Mondido\Mondido\Model\Api\Transaction;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Transaction management model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class GuestTransactionManagement implements GuestTransactionManagementInterface
{
    protected $transaction;
    protected $quoteIdMaskFactory;

    /**
     * Constructor
     *
     * @param Mondido\Mondido\Model\Api\Transaction  $transaction        Mondido transaction API model
     * @param Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory Quote ID mask factory
     *
     * @return void
     */
    public function __construct(Transaction $transaction, QuoteIdMaskFactory $quoteIdMaskFactory)
    {
        $this->transaction = $transaction;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * Update
     *
     * Takes the quote and updates the transaction at Mondido and returns the JSON response.
     *
     * @param string $cartId Masked quote identifier
     *
     * @return string
     */
    public function update($cartId)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->transaction->update($quoteIdMask->getQuoteId());
    }
}
