<?php
/**
 * ServiceManager
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\ServiceManager;

use Zend\Authentication\AuthenticationService;
use Zend\Log\Logger;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class ServiceManager
{
    /**
     * @var ServiceLocatorInterface
     */
    private $sm;

    /**
     * ServiceManager constructor.
     *
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->sm = $serviceManager;
    }

    /**
     * @return ServiceLocatorInterface
     *
     * @deprecated
     */
    protected function getServiceManager()
    {
        return $this->sm;
    }

    /**
     * @param $name
     * @see ServiceLocatorInterface::get
     *
     * @return array|object
     */
    public function getService($name)
    {
       return $this->sm->get($name);
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->getService('translator');
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->getService('config');
    }

    /**
     *
     * This is FirePHP for return to console your custom data
     *
     * @return object
     */
    public function getFirePHP()
    {
        return $this->getService('firePHP');
    }

    /**
     * @return Logger
     */
    public function getLog()
    {
        return $this->getService('log');
    }

    /**
     * @return array
     */
    public function getIdentity() : array
    {
        /** @var AuthenticationService $authService */
        $authService = $this->getService(AuthenticationService::class);

        $identity = $authService->getIdentity();

        return $identity;
    }
}