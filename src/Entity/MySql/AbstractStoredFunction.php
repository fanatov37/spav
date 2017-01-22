<?php
/**
 * AbstractStoredFunction
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\Entity\MySql;

use Spav\Entity\AbstractAdapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

abstract class AbstractStoredFunction extends AbstractAdapter
{
    /**
     * @var string
     */
    protected $templateFunctionSql = 'select %s.%s(%s) as json from dual';

    /**
     * (non-PHPDoc)
     *
     * @return ResultInterface|ResultSet
     */
    abstract protected function statementExecute();

    /**
     * (non-PHPDoc)
     *
     * @return string
     */
    abstract protected function getFunction() : string;

    /**
     * (non-PHPDoc)
     *
     * @return array
     */
    abstract protected function getResult() : array;

    /**
     * (non-PHPDoc)
     *
     * @return array
     */
    final public function execute() : array
    {
        try {
            return $this->getResult();

        } catch (\Exception $ex) {

            return [
                'success' => false,
                'message' => $ex->getMessage()
            ];
        }
    }
}