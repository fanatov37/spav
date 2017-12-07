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

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\I18n\Translator\Translator;
use Zend\Log\Logger;
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
     * @param ServiceLocatorInterface|ContainerInterface $serviceManager
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
    protected function getServiceManager() : ServiceLocatorInterface
    {
        return $this->sm;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getService($name)
    {
        if (!$this->sm->has($name)) {
            throw new \Exception('Error. Service not found');
        }

        return $this->sm->get($name);
    }

    /**
     * @return Translator
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getTranslator() : Translator
    {
        return $this->getService('translator');
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getConfig() : array
    {
        return $this->getService('config');
    }

    /**
     * @return Logger
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getLog() : Logger
    {
        return $this->getService('log');
    }

    /**
     * @return array|null
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getIdentity() : ?array
    {
        /** @var AuthenticationService $authService */
        $authService = $this->getService(AuthenticationService::class);

        return $authService->getIdentity();
    }
}