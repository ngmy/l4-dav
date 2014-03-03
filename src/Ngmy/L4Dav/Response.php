<?php namespace Ngmy\L4Dav;
/**
 * Part of the L4Dav package.
 *
 * Licensed under MIT License.
 *
 * @package    L4Dav
 * @version    0.2.0
 * @author     Ngmy <y.nagamiya@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT License
 * @copyright  (c) 2014, Ngmy <y.nagamiya@gmail.com>
 * @link       https://github.com/ngmy/l4-dav
 */

/**
 * A wrapper class for the cURL response representation class.
 *
 * @package L4Dav
 */
class Response extends \anlutro\cURL\Response {

	/**
	 * Get the response body.
	 *
	 * @access public
	 * @return string Returns the response body.
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Get the status code.
	 *
	 * @access public
	 * @return integer Returns the status code.
	 */
	public function getStatus()
	{
		return $this->statusCode;
	}

	/**
	 * Get the status message.
	 *
	 * @access public
	 * @return string Returns the status message.
	 */
	public function getMessage()
	{
		return $this->statusText;
	}

}

