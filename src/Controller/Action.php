<?php
namespace Spav\Controller;

use Core\Controller\Oauth2Controller;
use Spav\ServiceManager\ServiceManager;
use Zend\Http\Request;
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
 * @package Spav\Controller
 */
abstract class Action extends AbstractActionController
{
    /**
     * @var ServiceManager
     */
    protected $service;
    /**
     * Action constructor.
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->service = $serviceManager;
    }
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
    /**
     * @return mixed
     */
    public function hasAccessAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Request $request */
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $response->setStatusCode(Response::STATUS_CODE_404);

            return false;
        }

        $hasAccess = $this->forward()->dispatch(Oauth2Controller::class, ['action' => 'hasAccess']);
        if (!$hasAccess) {
            $response->setStatusCode(Response::STATUS_CODE_401);
        }

        return $hasAccess;
    }
}