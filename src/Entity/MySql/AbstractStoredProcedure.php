<?php

/**
 * AbstractStoredProcedure.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\Entity\MySql;

use Spav\Entity\AbstractAdapter;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Json\Json;

abstract class AbstractStoredProcedure extends AbstractAdapter
{
    const STR_PARAM = ':param';

    /**
     * @var string
     */
    protected $templateProcedureSql = 'call %s.%s(%s)';

    /**
     * (non-PHPDoc).
     *
     * @return string
     */
    abstract protected function getProcedure(): string;

    /**
     * (non-PHPDoc).
     *
     * @return string
     */
    abstract protected function statementExecute();

    /**
     * (non-PHPDoc).
     *
     * @return array
     */
    abstract protected function getResult(): array;

    /**
     * (non-PHPDoc).
     *
     * @throws \Exception
     *
     * @return array
     */
    public function fetchAll(): array
    {
        try {
            $data = $this->getResult();

            return [
                'success' => true,
                'data' => $data,
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * todo need refactoring.
     *
     * (non-PHPDoc)
     *
     * @return array
     */
    public function execute(): array
    {
        try {
            $result = $this->getResult();

            if (!(1 === count($result) && isset($result[0]['json']))) {
                throw new \Exception('Invalid resultset');
            }

            return Json::decode($result[0]['json'], Json::TYPE_ARRAY);
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * todo need refactoring.
     *
     * need for cache
     *
     * (non-PHPDoc)
     *
     * @return string
     *
     * @deprecated need refactoring
     */
    public function getSqlQuery()
    {
        $bindParams = [];

        if ($this instanceof ParamInterface) {
            $bindParams = $this->initParams($this->getParams());
        }

        if ($this instanceof LanguageInterface) {
            $lenguageKey = self::STR_PARAM.count($bindParams);

            $bindParams[$lenguageKey] = [ParameterContainer::TYPE_INTEGER => 2];
        }

        $paramArray = [];

        foreach ($bindParams as $bindParam) {
            $currentBindParam = current($bindParam);

            if (null === $currentBindParam) {
                $paramArray[] = 'null';
            } else {
                $paramArray[] = $currentBindParam;
            }
        }

        $sql = sprintf($this->templateProcedureSql,
            $this->getScheme(),
            $this->getProcedure(),
            implode(', ', array_filter($paramArray))
        );

        return $sql;
    }
}
