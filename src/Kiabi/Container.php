<?php

namespace Kiabi;


class Container
{
	protected $services = [];

	public function get($name)
	{
		if (!isset($this->services[$name])) {
			switch ($name) {
				case 'log':
					$this->services[$name] = new \Monolog\Logger('kiabi');
					$this->services[$name]->pushHandler(
						new \Monolog\Handler\StreamHandler(
							'/app/log/error.log',
							\Monolog\Logger::ERROR
						)
					);
					$this->services[$name]->pushHandler(
						new \Monolog\Handler\StreamHandler(
							'/app/log/debug.log',
							\Monolog\Logger::DEBUG
						)
					);
					break;
				case 'connection':
					$config = new \Doctrine\DBAL\Configuration();
					$conn = [
						'dbname'	=> DB_BASE,
						'user'		=> DB_USER,
						'password'	=> DB_PASS,
						'host'		=> defined('DB_HOST') ? DB_HOST : 'localhost',
						'driver'	=> defined('DB_TYPE') ? DB_TYPE : 'pdo_mysql',
						'charset'	=> 'utf8',
						'collate'   => 'utf8_unicode_ci',
						'driverOptions' => [
							1002 => 'SET NAMES utf8'
						]
					];
					$this->services[$name] = \Doctrine\DBAL\DriverManager::getConnection($conn, $config);
					break;
				case 'cache':
					if (!defined('CACHE_DRIVER')) {
						throw new \Exception('Не настроены параметры кеширующего сервера.');
					}
					$driver = CACHE_DRIVER;
					switch ($driver) {
						case 'memcached':
							$memcached = new \Memcached();
							$memcached->addServer(defined('CACHE_HOST') ? CACHE_HOST : 'localhost', defined('CACHE_PORT') ? CACHE_PORT : 11211);
							$cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
							$cacheDriver->setMemcached($memcached);

							break;
						case 'redis':
							$redis = new \Redis();
							$redis->connect(defined('CACHE_HOST') ? CACHE_HOST : 'localhost', defined('CACHE_PORT') ? CACHE_PORT : 6379);

							$cacheDriver = new \Doctrine\Common\Cache\RedisCache();
							$cacheDriver->setRedis($redis);
							break;
						case 'file':
						default:
							$cacheDriver = new \Doctrine\Common\Cache\FilesystemCache(PRJ_DIR.'/app/cache/kiabi/', '.cmscache.data');
					}

					$cacheDriver->setNamespace('kiabi_');

					$this->services[$name] = $cacheDriver;
					break;
			}
		}

		if (!isset($this->services[$name])) {
			throw new \Exception('Cлужба "'.$name.'" не найдена');
		}

		return $this->services[$name];
	}
}