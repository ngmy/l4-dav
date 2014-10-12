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

use Illuminate\Support\ServiceProvider;

/**
 * A service provider class to bootstrap the HTTP service.
 *
 * @package L4Dav
 */
class HttpServiceProvider extends ServiceProvider {

	public function register()
	{
		$app = $this->app;

		$app['l4-dav.httpclient'] = $app->share(function ($app)
		{
			$config = $app['config'];

			$request = new CurlHttpRequest;

			return $request;
		});

	}

}
