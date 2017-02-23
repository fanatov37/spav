<?php
/**
 * Bootstrap
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */

namespace SpavTest;

use Spav\PHPUnit\AbstractBootstrap;

/* todo need use autoload for it */
require_once dirname(__DIR__) .'/src/PHPUnit/AbstractBootstrap.php';

class Bootstrap extends AbstractBootstrap
{
    /**
     * (non-PHPDoc)
     *
     * You can override this method. It's for example.
     *
     * @return array
     */
    protected function getModules() : array
    {
        return [
            'Core'
        ];
    }

    /**
     * @return array
     */
    public function getNamespace() : array
    {
        return [
            __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
        ];
    }

    /**
     * @return string
     */
    protected function getConfigGlobPaths(): string
    {
        return $this->findParentPath('vendor') . '/spav/tests/config';
    }
}

Bootstrap::init();
Bootstrap::chroot();