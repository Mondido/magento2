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

use Magento\Framework\Event\Observer;

/**
 * Quote submit before observer
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Robert Lord <robert@codepeak.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class CustomerSaveObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mondido\Mondido\Model\Api\Customer
     */
    protected $customerApi;

    /**
     * CustomerSaveObserver constructor.
     *
     * @param \Mondido\Mondido\Model\Api\Customer $customerApi
     */
    public function __construct(\Mondido\Mondido\Model\Api\Customer $customerApi)
    {
        $this->customerApi = $customerApi;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer Observer object
     *
     * @return Observer
     */
    public function execute(Observer $observer)
    {

        // Fetch customer object
        $customerObject = $observer->getCustomerDataObject();

        // Update or create the data
        // $this->customerApi->handle($customerObject);

        return $observer;
    }
}
