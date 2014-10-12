<?php namespace Ngmy\L4Dav;
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

use Ngmy\L4Dav\Service\Http\RequestInterface;

/**
 * A WebDAV client class.
 *
 * @package L4Dav
 */
class L4Dav {

	/**
	 * The schema of the WebDAV server.
	 *
	 * @var string
	 * @access protected
	 */
	protected $schema;

	/**
	 * The hostname of the WebDAV server.
	 *
	 * @var string
	 * @access protected
	 */
	protected $host;

	/**
	 * The port of the WebDAV server.
	 *
	 * @var integer
	 * @access protected
	 */
	protected $port;

	/**
	 * The path of the WebDAV server.
	 *
	 * @var string
	 * @access protected
	 */
	protected $path;

	/**
	 * The URL of the WebDAV server.
	 *
	 * @var string
	 * @access protected
	 */
	protected $url;

	protected $request;

	/**
	 * Create a new L4Dav class object.
	 *
	 * @param Ngmy\L4Dav\Service\Http\RequestInterface $request
	 * @param string  $webDavUrl  The URL of the WebDAV server.
	 * @param integer $webDavPort The port of the WebDAV server.
	 * @access public
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function __construct(RequestInterface $request, $webDavUrl, $webDavPort = 80)
	{
		if (!preg_match('/([a-z]+):\/\/([a-zA-Z0-9\.]+)(:[0-9]+){0,1}(.*)/', $webDavUrl, $m)) {
			throw new \InvalidArgumentException('Invalid URL format ('.$webDavUrl.')');
		}

		$this->schema = $m[1];
		$this->host   = $m[2];
		$this->port   = isset($m[3]) ? (int) ltrim($m[3], ':') : $webDavPort;
		$this->path   = isset($m[4]) ? rtrim($m[4], '/').'/' : '/';

		$this->url = $this->schema.'://'.$this->host.$this->path;

		$this->request = $request;
	}

	/**
	 * Download a file from the WebDAV server.
	 *
	 * @param string $srcPath  The source path of a file.
	 * @param string $destPath The destination path of a file.
	 * @access public
	 * @return \Ngmy\L4Dav\Response Returns a Response class object.
	 */
	public function get($srcPath, $destPath)
	{
		$fh = fopen($destPath, 'w');

		$options = array(
			CURLOPT_PORT           => $this->port,
			CURLOPT_FILE           => $fh,
			CURLOPT_RETURNTRANSFER => true,
		);

		$result = $this->request->method('GET')
			->url($this->url.$srcPath)
			->options($options)
			->send();

		fclose($fh);

		return $result;
	}

	/**
	 * Upload a file to the WebDAV server.
	 *
	 * @param string $srcPath  The source path of a file.
	 * @param string $destPath The destination path of a file.
	 * @access public
	 * @return \Ngmy\L4Dav\Response Returns a Response class object.
	 */
	public function put($srcPath, $destPath)
	{
		$filesize = filesize($srcPath);
		$fh = fopen($srcPath, 'r');

		$options = array(
			CURLOPT_PORT       => $this->port,
			CURLOPT_PUT        => true,
			CURLOPT_INFILE     => $fh,
			CURLOPT_INFILESIZE => $filesize,
		);

		$result = $this->request->method('PUT')
			->url($this->url.$destPath)
			->options($options)
			->send();

		fclose($fh);

		return $result;
	}

	/**
	 * Delete an item on the WebDAV server.
	 *
	 * @param string $path The path of an item.
	 * @access public
	 * @return \Ngmy\L4Dav\Response Returns a Response class object.
	 */
	public function delete($path)
	{
		$options = array(CURLOPT_PORT => $this->port);

		return $this->request->method('DELETE')
			->url($this->url.$path)
			->options($options)
			->send();
	}

	/**
	 * Copy an item on the WebDAV server.
	 *
	 * @param string $srcPath  The source path of an item.
	 * @param string $destPath The destination path of an item.
	 * @access public
	 * @return \Ngmy\L4Dav\Response Returns a Response class object.
	 */
	public function copy($srcPath, $destPath)
	{
		$options = array(CURLOPT_PORT => $this->port);
		$headers = array('Destination' => $this->url.$destPath);

		return $this->request->method('COPY')
			->url($this->url.$srcPath)
			->headers($headers)
			->options($options)
			->send();
	}

	/**
	 * Rename an item on the WebDAV server.
	 *
	 * @param string $srcPath  The source path of an item.
	 * @param string $destPath The destination path of an item.
	 * @access public
	 * @return \Ngmy\L4Dav\Response Returns a Response class object.
	 */
	public function move($srcPath, $destPath)
	{
		$options = array(CURLOPT_PORT => $this->port);
		$headers = array('Destination' => $this->url.$destPath);

		return $this->request->method('MOVE')
			->url($this->url.$srcPath)
			->headers($headers)
			->options($options)
			->send();
	}

	/**
	 * Make a directory on the WebDAV server.
	 *
	 * @param string $path The directory path.
	 * @access public
	 * @return \Ngmy\L4Dav\Response Returns a Response class object.
	 */
	public function mkdir($path)
	{
		$options = array(CURLOPT_PORT => $this->port);

		return $this->request->method('MKCOL')
			->url($this->url.$path)
			->options($options)
			->send();
	}

	/**
	 * Check the existence of an item on the WebDAV server.
	 *
	 * @param string $path The path of an item.
	 * @access public
	 * @return boolean Returns true if an item exists.
	 */
	public function exists($path)
	{
		$options = array(
			CURLOPT_PORT           => $this->port,
			CURLOPT_NOBODY         => true,
			CURLOPT_RETURNTRANSFER => true,
		);

		$response = $this->request->method('GET')
			->url($this->url.$path)
			->options($options)
			->send();

		if ($response->getStatus() < 200 || $response->getStatus() > 300) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * List contents of a directory on the WebDAV server.
	 *
	 * @param string $path The directory path.
	 * @access public
	 * @return array Returns a list of contents of the directory.
	 */
	public function ls($path)
	{
		$options = array(CURLOPT_PORT => $this->port);
		$headers = array('Depth' => 1);

		$response = $this->request->method('PROPFIND')
			->url($this->url.$path)
			->headers($headers)
			->options($options)
			->send();

		if ($response->getStatus() < 200 || $response->getStatus() > 300) {
			return array();
		} else {
			$xml = simplexml_load_string($response->getBody(), 'SimpleXMLElement', 0, 'D', true);
			$list = array();
			foreach ($xml->response as $element) {
				$list[] = (string) $element->href;
			}
			return $list;
		}
	}

}
