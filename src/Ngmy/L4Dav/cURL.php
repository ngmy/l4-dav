<?php namespace Ngmy\L4Dav;
/**
 * Part of the L4Dav package.
 *
 * Licensed under MIT License.
 *
 * @package    L4Dav
 * @version    0.3.0
 * @author     Ngmy <y.nagamiya@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT License
 * @copyright  (c) 2014, Ngmy <y.nagamiya@gmail.com>
 * @link       https://github.com/ngmy/l4-dav
 */

/**
 * A wrapper class for the cURL class.
 *
 * @package L4Dav
 */
class cURL extends \anlutro\cURL\cURL {

	/**
	* Allowed methods => allows postdata
	*
	* @var array
	*/
	protected $methods = array(
		'get'      => false,
		'post'     => true,
		'put'      => true,
		'patch'    => true,
		'delete'   => false,
		'options'  => false,
		'mkcol'    => false,
		'copy'     => false,
		'move'     => false,
		'propfind' => false,
	);

	/**
	* The response class to use.
	*
	* @var string
	*/
	protected $responseClass = '\Ngmy\L4Dav\Response';

}
