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

namespace Mondido\Mondido\Model\Api;

/**
 * Abstract Mondido API model
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
abstract class Mondido
{
    protected $_adapter;

    /**
     * Call
     *
     * @param string $method   HTTP method
     * @param string $resource API resource
     * @param mixed  $params   URL params
     * @param array  $data     Data to send to the API
     *
     * @return string
     */
    public function call($method, $resource, $params = null, $data = [])
    {
        $url = 'https://api.mondido.com/v1/' . $resource;

        if (is_string($params)) {
            $url .= "/$params";
        } else if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (is_numeric($key)) {
                    $url .= "/$value";
                } else {
                    $url .= "/$key/$value";
                }
            }
        }

        if (sizeof($data)) {
            if ($method != 'POST') {
                $url .= '?' . http_build_query($data);
            } else {
                $this->_adapter->addOption(CURLOPT_POSTFIELDS, http_build_query($data));
            }
        }

        switch ($method) {
            case 'POST':
                $this->_adapter->addOption(CURLOPT_POST, true);
                break;
            case 'GET':
                $this->_adapter->addOption(CURLOPT_POST, false);
                break;
            case 'DELETE':
                $this->_adapter->addOption(CURLOPT_POST, false)
                    ->addOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'PATCH':
                $this->_adapter->addOption(CURLOPT_POST, true)
                    ->addOption(CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;
            case 'PUT':
                $this->_adapter->addOption(CURLOPT_POST, true)
                    ->addOption(CURLOPT_PUT, true);
                break;
        }

        $userPwd = sprintf("%s:%s", $this->_config->getMerchantId(), $this->_config->getPassword());

        $this->_adapter->addOption(CURLOPT_USERPWD, $userPwd)
            ->addOption(CURLOPT_RETURNTRANSFER, 1)
            ->addOption(CURLOPT_CONNECTTIMEOUT, 30)
            ->addOption(CURLOPT_TIMEOUT, 300)
            ->addOption(CURLOPT_FOLLOWLOCATION, 1)
            ->addOption(CURLOPT_URL, $url)
            ->connect($url);

        $response = $this->_adapter->read();

        $this->_adapter->close();

        return $response;
    }
}
