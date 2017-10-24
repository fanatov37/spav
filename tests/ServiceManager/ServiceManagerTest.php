<?php
/**
 * ServiceManagerTest
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */
namespace SpavTest\ServiceManager;

use PHPUnit\Framework\TestCase;
use Spav\ServiceManager\ServiceManager;
use SpavTest\Bootstrap;
use Zend\I18n\Translator\Translator;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServiceManagerTest extends TestCase
{

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * (non-PHPDoc)
     */
    public function setUp()
    {
        $this->serviceManager = $this->getMockBuilder(ServiceManager::class)
            ->setConstructorArgs([Bootstrap::getServiceManager()])
            ->getMockForAbstractClass();

        parent::setUp();
    }

    /**
     * (non-PHPDoc)
     */
    public function tearDown()
    {
    }

    /**
     * @see ServiceManager::getService()
     */
    public function testGetServiceError()
    {
        $serviceIsUndefined = false;
        try{
            $this->serviceManager->getService('UNDEFINED');
        } catch (\Exception $exception) {
            $serviceIsUndefined = true;
        }

        $this->assertTrue($serviceIsUndefined);
    }

    /**
     * @see ServiceManager::getServiceManager()
     */
    public function testGetServiceManager()
    {
        $reflector = new \ReflectionObject($this->serviceManager);
        $method = $reflector->getMethod('getServiceManager');
        $method->setAccessible(true);
        $serviceManager = $method->invoke($this->serviceManager);

        $this->assertTrue($serviceManager instanceof ServiceLocatorInterface);
    }

    /**
     * @see ServiceManager::getLog()
     */
    public function testGetLog()
    {
       $log = $this->serviceManager->getLog();

        $this->assertTrue($log instanceof Logger);
    }

    /**
     * @see ServiceManager::getConfig()
     */
    public function testGetConfig()
    {
        $config = $this->serviceManager->getConfig();

        $this->assertTrue(is_array($config));
    }

    /**
     * @see ServiceManager::getTranslator()
     */
    public function testGetTranslator()
    {
        $translator = $this->serviceManager->getTranslator();

        $this->assertTrue($translator instanceof Translator);
    }

    /**
     * @see ServiceManager::getIdentity()
     */
    public function testGetIdentity()
    {
        $identity = $this->serviceManager->getIdentity();

        $this->assertNull($identity);
    }
}