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

use Mondido\Mondido\Api\TransactionManagementInterface;
use Mondido\Mondido\Model\Api\Transaction;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Transaction management model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class TransactionManagement implements TransactionManagementInterface
{
    protected $transaction;
    protected $cartRepository;

    /**
     * Constructor
     *
     * @param Mondido\Mondido\Model\Api\Transaction     $transaction    Mondido transaction API model
     * @param Magento\Quote\Api\CartRepositoryInterface $cartRepository Cart repository
     *
     * @return void
     */
    public function __construct(Transaction $transaction, CartRepositoryInterface $cartRepository)
    {
        $this->transaction = $transaction;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Update
     *
     * Takes the quote and updates the transaction at Mondido and returns the JSON response.
     *
     * @param id $cartId Quote identifier
     *
     * @return string
     */
    public function update($cartId)
    {
        $quote = $this->cartRepository->get($cartId);

        return $this->transaction->update($quote);
    }
}
