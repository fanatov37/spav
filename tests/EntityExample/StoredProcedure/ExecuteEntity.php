<?php
/**
 * ExecuteEntity
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */
namespace SpavTest\EntityExample\StoredProcedure;

use Spav\Entity\MySql\LanguageInterface;
use Spav\Entity\MySql\ParamInterface;
use Spav\Entity\MySql\StoredProcedure\Execute;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Stdlib\ArrayObject;

class ExecuteEntity extends Execute implements ParamInterface, LanguageInterface
{
    /**
     * @var int
     */
    private $key;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $type;
    /**
     * @var bool
     */
    private $isExeption;

    /**
     * (non-PHPDoc)
     *
     * @see BindExecute::getFunction()
     *
     * @return string
     *
     */
    protected function getProcedure() : string
    {
        return 'test_procedure';
    }

    /**
     * @param ArrayObject $paramObject
     *
     * @return self
     */
    public function setParams($paramObject) : self
    {
        $this->key = $paramObject->key;
        $this->name = $paramObject->name;
        $this->type = $paramObject->type;
        $this->isExeption = $paramObject->isExeption;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            [ParameterContainer::TYPE_INTEGER => $this->key],
            [ParameterContainer::TYPE_STRING => $this->name],
            [ParameterContainer::TYPE_INTEGER => $this->type],
            [ParameterContainer::TYPE_INTEGER => $this->isExeption]
        ];
    }
}