<?php
/**
 * BindExecuteEntity
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */
namespace SpavTest\EntityExample\StoredFunction;

use Spav\Entity\MySql\LanguageInterface;
use Spav\Entity\MySql\ParamInterface;
use Spav\Entity\MySql\StoredFunction\BindExecute;

use Zend\Db\Adapter\ParameterContainer;
use Zend\Stdlib\ArrayObject;

class BindExecuteEntity extends BindExecute implements ParamInterface, LanguageInterface
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $integer;

    /**
     * (non-PHPDoc)
     *
     * @see BindExecute::getFunction()
     *
     * @return string
     *
     */
    protected function getFunction() : string
    {
        return 'test_function';
    }

    /**
     * @param ArrayObject $paramObject
     *
     * @return self
     */
    public function setParams(ArrayObject $paramObject) : self
    {
        $this->string = $paramObject->string;
        $this->integer = $paramObject->integer;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return [
            [ParameterContainer::TYPE_STRING => $this->string],
            [ParameterContainer::TYPE_INTEGER => $this->integer]
        ];
    }
}