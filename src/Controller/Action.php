<?php
namespace Spav\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\PhpEnvironment\{
    Response
};
use Zend\Mvc\MvcEvent;
/**
 * Class Action
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 *
 * @package Spav\Controller
 */
abstract class Action extends AbstractActionController
{
    /**
     * @return bool
     */
    protected function pageNotFound()
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $response->setStatusCode(Response::STATUS_CODE_404);

        return false;
    }
    /**
     * todo need check it. mb remove
     * if you wanna that your Action has controll
     * under user, you must return true
     *
     * (non-PHPDoc)
     *
     * @return bool
     */
    protected function isPreDispatch()
    {
        return false;
    }
    /**
     * (non-PHPDoc)
     *
     * @see Action::preDispatch()
     */
    private function preDispatch()
    {
        if ($this->isPreDispatch()) {
            if (!$this->identity()) {
                $this->pageNotFound();
            }
        }
    }
    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->preDispatch();

        return parent::onDispatch($e);
    }
}