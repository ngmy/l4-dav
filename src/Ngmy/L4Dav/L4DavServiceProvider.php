<?php namespace Ngmy\L4Dav;
/**
 * Part of the L4Dav package.
 *
 * Licensed under MIT License.
 *
 * @package    L4Dav
 * @version    0.4.0
 * @author     Ngmy <y.nagamiya@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT License
 * @copyright  (c) 2014, Ngmy <y.nagamiya@gmail.com>
 * @link       https://github.com/ngmy/l4-dav
 */

use Illuminate\Support\ServiceProvider;

/**
 * A service provider class to bootstrap the L4Dav class in Laravel 4.
 *
 * @package L4Dav
 */
class L4DavServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ngmy/l4-dav', 'ngmy/l4-dav');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['l4-dav'] = $this->app->share(function($app)
		{
			$webDavUrl = $app['config']['ngmy/l4-dav::url'];
			return new L4Dav($webDavUrl);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('l4-dav');
	}

}
