<?php

namespace Spav;

use Zend\EventManager\EventManagerInterface;
use Zend\Loader\StandardAutoloader;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module.
 *
 * @see https://fanatov37@bitbucket.org/fanatov37/hypeshare.git for the canonical source repository
 *
 * @copyright Copyright (c)
 * @license HypeShare (c)
 * @author VladFanatov
 */
class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        /** @var EventManagerInterface $eventManager */
        $eventManager = $e->getApplication()->getEventManager();

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__.'/src/',
                ],
            ],
        ];
    }
}
