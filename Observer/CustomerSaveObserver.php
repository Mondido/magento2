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
     * Execute observer
     *
     * @param Observer $observer Observer object
     *
     * @return Observer
     */
    public function execute(Observer $observer)
    {
        // Fetch object manager
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        // Fetch customer API
        $customerApi = $objectManager->get('Mondido\Mondido\Model\Api\Customer');

        // Fetch customer object
        $customerObject = $observer->getCustomerDataObject();

        // Update or create the data
        $customerApi->handle($customerObject);

        return $observer;
    }
}
