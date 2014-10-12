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
 * A response interface.
 *
 * @package L4Dav
 */
interface ResponseInterface {

	/**
	 * Get the response body.
	 *
	 * @access public
	 * @return string Returns the response body.
	 */
	public function getBody();

	/**
	 * Get the status code.
	 *
	 * @access public
	 * @return integer Returns the status code.
	 */
	public function getStatus();

	/**
	 * Get the status message.
	 *
	 * @access public
	 * @return string Returns the status message.
	 */
	public function getMessage();

}
