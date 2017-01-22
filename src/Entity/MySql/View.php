<?php
/**
 * View
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\Entity\MySql;

use Spav\Entity\AbstractAdapter;
use Zend\Db\Adapter\Driver\Pdo\Result;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class View extends AbstractAdapter
{
    /**
     * @var Select
     */
    protected $select;
    /**
     * @var Sql
     */
    protected $sql;
    /**
     * @var string
     */
    protected $viewName;
    /**
     * @var string
     */
    protected $rows;
    /**
     * @var integer
     */
    protected $rowCount;

    /**
     * (non-PHPDoc)
     */
    abstract protected function setData();

    /**
     * View constructor.
     *
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        parent::__construct($serviceManager);

        $adapter = $this->getAdapter();

        $sql = new Sql($adapter);

        $this->sql = $sql;
        $this->select = $sql->select();
    }

    /**
     * (non-PHPDoc)
     *
     * @return Result
     */
    private function execute()
    {
        $this->select->from($this->viewName, $this->rows);

        $statement = $this->sql->prepareStatementForSqlObject($this->select);

        $resultSet = $statement->execute();

        $this->rowCount = $resultSet->count();

        return $resultSet;
    }

    /**
     * (non-PHPDoc)
     *
     * @return array()
     */
    final protected function _fetchAll()
    {
        $this->setData();
        $result = $this->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $data = $resultSet->toArray();

        return $data;
    }
}