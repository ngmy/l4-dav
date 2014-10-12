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

use Ngmy\L4Dav\Library\cURL;

/**
 * A cURL request class.
 *
 * @package L4Dav
 */
class CurlRequest implements RequestInterface {

	/**
	 * The HTTP method.
	 *
	 * @var string
	 * @access protected
	 */
	protected $method;

	/**
	 * The request URL.
	 *
	 * @var string
	 * @access protected
	 */
	protected $url;

	/**
	 * The port number.
	 *
	 * @var integer
	 * @access protected
	 */
	protected $port;

	/**
	 * The HTTP headers.
	 *
	 * @var array
	 * @access protected
	 */
	protected $headers = array();

	/**
	 * The cURL class.
	 *
	 * @var string
	 * @access protected
	 */
	protected $curl;

	/**
	 * The cURL options.
	 *
	 * @var array
	 * @access protected
	 */
	protected $options = array();

	/**
	 * Create a new CurlRequest class object.
	 *
	 * @param \Ngmy\L4Dav\Library\cURL $curl The cURL client library.
	 * @access public
	 * @return void
	 */
	public function __construct(cURL $curl)
	{
		$this->curl = $curl;
	}

	/**
	 * Set the HTTP method.
	 *
	 * @param string $method The HTTP method.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\CurlRequest Returns self for chainability.
	 */
	public function method($method)
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * Set the request url.
	 *
	 * @param string $url The request url.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\CurlRequest Returns self for chainability.
	 */
	public function url($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * Set the HTTP headers.
	 *
	 * @param array $headers The HTTP headers.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\CurlRequest Returns self for chainability.
	 */
	public function headers(array $headers)
	{
		$this->headers = $headers;

		return $this;
	}

	/**
	 * Set the cURL options.
	 *
	 * @param array $options The cURL options.
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\CurlRequest Returns self for chainability.
	 */
	public function options(array $options)
	{
		$this->options = $options;

		return $this;
	}

	/**
	 * Send the request by cURL.
	 *
	 * @access public
	 * @return \Ngmy\L4Dav\Service\Http\CurlResponse Returns a CurlResponse class object.
	 */
	public function send()
	{
		$response = $this->curl->newRequest($this->method, $this->url)
			->setHeaders($this->headers)
			->setOptions($this->options)
			->send();

		return $response;
	}

}
