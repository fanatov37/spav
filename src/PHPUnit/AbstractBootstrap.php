<?php
/**
 * Bootstrap
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Core PHPUnit
 */

namespace Spav\PHPUnit;

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\Mvc\Application;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use RuntimeException;
use ReflectionClass;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

abstract class AbstractBootstrap
{
    /**
     * @var ServiceManager
     */
    protected static $serviceManager;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * @return array
     */
    abstract protected function getNamespace() : array;

    /**
     * @return string
     */
    abstract protected function getConfigGlobPaths() : string;

    /**
     * default Modules
     *
     * @return array
     */
    abstract protected function getModules() : array;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        $class = get_called_class();

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }

    /**
     * @param $path
     *
     * @return bool|string
     */
    final public function findParentPath($path) : string
    {
        $dir = __DIR__;

        $previousDir = '.';

        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);

            if ($previousDir === $dir) {
                return false;
            }

            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }

    /**
     * @return string
     */
    public function getApplicationPath() : string
    {
        return dirname($this->findParentPath('module'));
    }

    /**
     * @return array
     */
    private function getZf2ModulePaths() : array
    {
        $zf2ModulePaths = [dirname(dirname(__DIR__))];

        if (($path = $this->findParentPath('vendor'))) {
            $zf2ModulePaths[] = $path;
        }

        if (($path = $this->findParentPath('module')) !== $zf2ModulePaths[0]) {
            $zf2ModulePaths[] = $path;
        }

        return $zf2ModulePaths;
    }

    /**
     * @return array
     */
    protected function getConfig() : array
    {
        $config = [
            'modules' => $this->getModules(),
            'module_listener_options' => [
                'module_paths' => $this->getZf2ModulePaths(),
                'config_glob_paths' =>
                    [$this->getConfigGlobPaths() . '/{{,*.}global,{,*.}local}.php']
            ]
        ];

        return $config;
    }

    /**
     * @return array
     */
    public static function getApplicationConfig() : array
    {
        /** @var self $instance */
        $instance = self::getInstance();

        return $instance->getConfig();
    }

    /**
     * @param mixed $class
     * @param string $name
     *
     * @return \ReflectionMethod
     */
    public static function getMethod($class, $name)
    {
        $class = new ReflectionClass($class);

        $method = $class->getMethod($name);

        $method->setAccessible(true);

        return $method;
    }

    /**
     * (non-PHPDoc)
     */
    protected function initAutoloader()
    {
        $autoloadPath = $this->getApplicationPath() . '/vendor/autoload.php';

        if (file_exists($autoloadPath)) {
            include $autoloadPath;
        }

        if (!class_exists(AutoloaderFactory::class)) {
            throw new RuntimeException(
                'Unable to load ZF2. Run `php composer.phar install`'
            );
        }

        AutoloaderFactory::factory([
            StandardAutoloader::class => [
                'autoregister_zf' => true,
                'namespaces' => array_merge($this->getNamespace(),
                    [
                        'SpavTest' =>
                        self::getApplicationPath() . '/vendor/fanatov37/spav/tests'
                    ]
                )]
        ]);
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager() : ServiceManager
    {
        return self::$serviceManager;
    }

    /**
     * (non-PHPDoc)
     */
    public static function chroot()
    {
        /** @var self $instance */
        $instance = self::getInstance();

        $rootPath = dirname($instance->findParentPath('module'));
        chdir($rootPath);
    }

    /**
     * (non-PHPDoc)
     */
    public static function init()
    {
        /** @var self $instance */
        $instance = self::getInstance();

        $instance->initAutoloader();

        $config = $instance->getConfig();

        $serviceManagerConfig = new ServiceManagerConfig($config);

        $serviceManager = new ServiceManager($serviceManagerConfig->toArray());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        self::$serviceManager = $serviceManager;
    }
}
