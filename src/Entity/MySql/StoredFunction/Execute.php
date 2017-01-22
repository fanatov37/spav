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
namespace Spav\Entity\MySql\StoredFunction;

use Spav\Entity\MySql\LanguageInterface;
use Spav\Entity\MySql\ParamInterface;
use Spav\Entity\MySql\AbstractStoredFunction;
use Zend\Db\Adapter\Adapter as ZendAdapter;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Db\ResultSet\ResultSet;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;

abstract class Execute extends AbstractStoredFunction
{
    /**
     * (non-PHPDoc)
     *
     * execute store function from mysql
     *
     * @return ResultSet
     */
    final protected function statementExecute() : ResultSet
    {
        $bindParams = [];

        if ($this instanceof ParamInterface) {
            $bindParams = $this->initParams($this->getParams());
        }

        if ($this instanceof LanguageInterface) {
            $lenguageKey = self::STR_PARAM . count($bindParams);

            $bindParams[$lenguageKey] =
                [ParameterContainer::TYPE_STRING=>$this->getCurrentLocale()];
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

        $sql = sprintf($this->templateFunctionSql,
            $this->getScheme(),
            strval($this->getFunction()),
            implode(', ', array_filter($paramArray))
        );

        $statement = $this->getAdapter()->query($sql, ZendAdapter::QUERY_MODE_EXECUTE);

        return $statement;
    }

    /**
     * todo need refactoring
     *
     * @return array
     */
    protected function getResult() : array
    {
        $resultSet = $this->statementExecute();

        $result = ArrayUtils::iteratorToArray($resultSet);

        return Json::decode($result[0]['json'], Json::TYPE_ARRAY);
    }
}