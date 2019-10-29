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

namespace Spav\Entity\MySql\StoredProcedure;

use Spav\Entity\MySql\LanguageInterface;
use Spav\Entity\MySql\ParamInterface;
use Spav\Entity\MySql\AbstractStoredProcedure;
use Spav\Entity\MySql\UserInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Db\ResultSet\ResultSet;

abstract class BindExecute extends AbstractStoredProcedure
{
    /**
     * (non-PHPDoc).
     *
     * @return ResultInterface
     */
    final protected function statementExecute()
    {
        $bindParamsArray = [];

        $parameterContainer = new ParameterContainer();

        if ($this instanceof ParamInterface) {
            $bindParamsArray = $this->initParams($this->getParams());
        }

        if ($this instanceof UserInterface) {
            $lenguageKey = self::STR_PARAM.count($bindParamsArray);

            $bindParamsArray[$lenguageKey] =
                [ParameterContainer::TYPE_STRING => $this->getUserId()];
        }

        if ($this instanceof LanguageInterface) {
            $lenguageKey = self::STR_PARAM.count($bindParamsArray);

            $bindParamsArray[$lenguageKey] =
                [ParameterContainer::TYPE_INTEGER => $this->getCurrentLocaleId()];
        }

        foreach ($bindParamsArray as $key => $bindParams) {
            foreach ($bindParams as $k => $bindParam) {
                $parameterContainer->offsetSet($key, $bindParam, $k);
            }
        }

        $sql = sprintf($this->templateProcedureSql,
            $this->getScheme(),
            $this->getProcedure(),
            implode(', ', array_keys($bindParamsArray))
        );

        $statement = $this->getAdapter()->query($sql);

        $execute = $statement->execute($parameterContainer);

        //todo test for PHPUnit. Need check
        $this->getAdapter()->getDriver()->getConnection()->disconnect();

        return $execute;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function getResult(): array
    {
        $result = $this->statementExecute();

        if (!($result instanceof ResultInterface && $result->isQueryResult())) {
            throw new \Exception('Execute function must return ResultInterface');
        }

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet->toArray();
    }
}
