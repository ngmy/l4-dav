<?php namespace Ngmy\L4Dav\Service\Http;
/**
 * Part of the L4Dav package.
 *
 * Licensed under MIT License.
 *
 * @package    L4Dav
 * @version    0.5.0
 * @author     Ngmy <y.nagamiya@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT License
 * @copyright  (c) 2014, Ngmy <y.nagamiya@gmail.com>
 * @link       https://github.com/ngmy/l4-dav
 */

/**
 * A request interface.
 *
 * @package L4Dav
 */
interface RequestInterface {

	/**
	 * Set the HTTP method.
	 *
	 * @param string $method The HTTP method.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\RequestInterface Returns self for chainability.
	 */
	public function method($method);

	/**
	 * Set the request url.
	 *
	 * @param string $url The request url.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\RequestInterface Returns self for chainability.
	 */
	public function url($url);

	/**
	 * Set the HTTP headers.
	 *
	 * @param array $headers The HTTP headers.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\RequestInterface Returns self for chainability.
	 */
	public function headers(array $headers);

	/**
	 * Set the cURL options.
	 *
	 * @param array $options The cURL options.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\RequestInterface Returns self for chainability.
	 */
	public function options(array $options);

	/**
	 * Send the request.
	 *
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\ResponseInteface Returns a Response class object.
	 */
	public function send();

}
