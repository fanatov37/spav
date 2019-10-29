<?php
/**
 * BindExecute.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\Entity\MySql\StoredFunction;

use Spav\Entity\MySql\LanguageInterface;
use Spav\Entity\MySql\ParamInterface;
use Spav\Entity\MySql\AbstractStoredFunction;
use Spav\Entity\MySql\UserInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Json\Json;

abstract class BindExecute extends AbstractStoredFunction
{
    /**
     * (non-PHPDoc).
     *
     * execute store function from mysql
     *
     * @return ResultInterface
     */
    final protected function statementExecute(): ResultInterface
    {
        $bindParamsArray = [];

        $parameterContainer = new ParameterContainer();

        if ($this instanceof ParamInterface) {
            $bindParamsArray = $this->initParams($this->getParams());
        }

        if ($this instanceof UserInterface) {
            $usereKey = self::STR_PARAM.count($bindParamsArray);

            $bindParamsArray[$usereKey] =
                [ParameterContainer::TYPE_INTEGER => $this->getUserId()];
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

        $sql = sprintf($this->templateFunctionSql,
            $this->getScheme(),
            $this->getFunction(),
            implode(', ', array_keys($bindParamsArray))
        );

        $statement = $this->getAdapter()->query($sql);

        return $statement->execute($parameterContainer);
    }

    /**
     * @return array
     */
    protected function getResult(): array
    {
        $result = $this->statementExecute()->current();

        $result = array_shift($result);

        return Json::decode($result, Json::TYPE_ARRAY);
    }
}
