<?php
/**
 * AbstractAdapter.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\Entity;

use Spav\ServiceManager\LocaleService;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter as ZendAdapter;
use Zend\Db\Adapter\ParameterContainer;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractAdapter
{
    const STR_PARAM = ':param';

    /**
     * @var ServiceLocatorInterface
     */
    private $_sm;

    /**
     * AbstractAdapter constructor.
     *
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->_sm = $serviceManager;
    }

    /**
     * (non-PHPDoc).
     *
     * @return ZendAdapter
     */
    protected function getAdapter(): ZendAdapter
    {
        return $this->_sm->get(ZendAdapter::class);
    }

    /**
     * (non-PHPDoc).
     *
     * @return string
     */
    protected function getScheme(): string
    {
        return $this->getAdapter()->getDriver()->getConnection()->getCurrentSchema();
    }

    /**
     * (non-PHPDoc).
     *
     * check param type
     *
     * @param $type
     *
     * @return string
     */
    protected function getParamType($type): string
    {
        switch ((string) $type) {
            case ParameterContainer::TYPE_STRING:
                return $type;

                break;

            case ParameterContainer::TYPE_INTEGER:
                return $type;

                break;

            case ParameterContainer::TYPE_LOB:
                return $type;

                break;

            case ParameterContainer::TYPE_DOUBLE:
                return $type;

                break;

            case ParameterContainer::TYPE_NULL:
                return $type;

                break;

            default:
                return ParameterContainer::TYPE_STRING;

                break;
        }
    }

    /**
     * (non-PHPDoc).
     *
     * @param array $params
     *
     * @return array
     */
    protected function initParams(array $params): array
    {
        $paramArray = [];

        foreach ($params as $key => $param) {
            foreach ($param as $k => $item) {
                $paramType = $this->getParamType($k);

                $paramArray[self::STR_PARAM.$key] = [$paramType => $item];
            }
        }

        return $paramArray;
    }

    /**
     * @see LocaleService::getCurrentLocaleId()
     *
     * @return int
     */
    protected function getCurrentLocaleId(): int
    {
        /** @var LocaleService $localeService */
        $localeService = $this->_sm->get(LocaleService::class);

        return $localeService->getCurrentLocaleId();
    }

    /**
     * @return null|int
     */
    public function getUserId(): ?int
    {
        $userId = null;

        /** @var AuthenticationService $authService */
        $authService = $this->_sm->get(AuthenticationService::class);

        $identity = $authService->getIdentity();

        if (is_array($identity) && isset($identity['userId'])) {
            $userId = $identity['userId'];
        }

        return $userId;
    }
}
