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

namespace Mondido\Mondido\Helper;

/**
 * Data helper
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	/**
	 * Format number
	 *
	 * Wrapper for number_format()
	 *
	 * @param float  $number             The number being formatted
	 * @param int    $decimals           Sets the number of decimal points
	 * @param string $decimalPoint       Sets the separator for the decimal point
	 * @param string $thousandsSeparator Sets the thousands separator
	 *
	 * @return float
	 */
	public function formatNumber($number, $decimals = 2, $point = '.', $thousandsSeparator = '')
	{
		return number_format($number, $decimals, $point, $thousandsSeparator);
	}
}
