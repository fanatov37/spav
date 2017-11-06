<?php

/**
 * Execute
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\Entity\MySql\StoredProcedure;

use Spav\Entity\MySql\LanguageInterface;
use Spav\Entity\MySql\ParamInterface;
use Spav\Entity\MySql\AbstractStoredProcedure;
use Spav\Entity\MySql\UserInterface;

use Zend\Db\Adapter\Adapter as ZendAdapter;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Db\ResultSet\ResultSet;


abstract class Execute extends AbstractStoredProcedure
{
    /**
     * @return ResultSet
     */
    final protected function statementExecute()
    {
        $bindParams = [];

        if ($this instanceof ParamInterface) {
            $bindParams = $this->initParams($this->getParams());
        }

        if ($this instanceof UserInterface) {
            $lenguageKey = self::STR_PARAM . count($bindParams);

            $bindParamsArray[$lenguageKey] =
                [ParameterContainer::TYPE_STRING=>$this->getUserId()];
        }

        if ($this instanceof LanguageInterface) {
            $lenguageKey = self::STR_PARAM . count($bindParams);

            $bindParams[$lenguageKey] =
                [ParameterContainer::TYPE_INTEGER=>$this->getCurrentLocaleId()];
        }

        $paramArray = [];

        foreach ($bindParams as $bindParam) {

            $currentBindParam = current($bindParam);

            if ($currentBindParam === null) {
                $paramArray[] = 'null';
            } else if (is_string($currentBindParam)) {
                $paramArray[] = "'$currentBindParam'";
            } else {
                $paramArray[] = $currentBindParam;
            }
        }

        $sql = sprintf($this->templateProcedureSql,
            $this->getScheme(),
            $this->getProcedure(),
            implode(', ', array_filter($paramArray))
        );

        return $this->getAdapter()->query($sql, ZendAdapter::QUERY_MODE_EXECUTE);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function getResult() : array
    {
        $result = $this->statementExecute();

        if ( !($result instanceof ResultSet)) {
            throw new \Exception('Execute function must return ResultInterface');
        }

        return $result->toArray();
    }
}