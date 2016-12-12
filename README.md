# Mondido payment module for Magento 2

[![Build Status](https://travis-ci.org/kodbruket/magento2-mondido.svg?branch=master)](https://travis-ci.org/kodbruket/magento2-mondido)

[Signup for a free account](https://www.mondido.com/en/signup) or [check out the documentation](https://doc.mondido.com) over at [mondido.com](https://www.mondido.com/en).

## Installation

The easiest way to install the extension is to use [Composer](https://getcomposer.org). Run the following commands:

`$ composer require mondido/magento2`

`$ bin/magento module:enable Mondido_Mondido`

`$ bin/magento setup:upgrade`

`$ bin/magento setup:static-content:deploy`

`$ bin/magento setup:di:compile`

## Limitations

### Shipping and billing address

Address information will be collected by Mondido Payments in the embedded iframe and sent to Magento in a webhook. Any information missing when the order is supposed to be created in the webhook needs to be fixed in the iframe first.

### Shipping methods

For now, the only supported shipping method is the flat rate shipping method. Use shopping cart promotions if free shipping is needed.

### Tracking on success page

We can't guarantee that the webhook has fired and that the order has been created when the customer reaches the success page. Instead of tracking data from the order object, try tracking information directly from the quote.

### Partial capturing

Partial capturing will be added in a future version when the Mondido API supports it.

## Support

Please, feel free to [create issues on our GitHub repository](https://github.com/kodbruket/magento2-mondido/issues). Contact hello@mondido.com if you have specific problems for your account. 
