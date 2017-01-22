<?php
/**
 * EntityView
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\Entity\MySql\View;

use Spav\Entity\MySql\View;

abstract class EntityView extends View
{
    /**
     * (non-PHPDoc)
     *
     * @return array
     */
    abstract protected function getColumns();

    /**
     * (non-PHPDoc)
     *
     * @return array (view name and columns)
     *
     */
    abstract protected function getView();

    /**
     * (non-PHPDoc)
     *
     * @see YF_Module_Db_MySql_View::_setData()
     *
     */
    protected function setData()
    {
        $getView = $this->getView();
        $this->viewName = strval($getView[0]);
        $this->rows = $getView[1];
    }

    /**
     * (non-PHPDoc)
     *
     * @see Zend_Db_Select
     */
    public function limit($count = NULL, $offset = NULL)
    {
        if (!empty($count)) {
            $this->select->limit($count, $offset);
        }
    }

    /**
     * (non-PHPDoc)
     *
     * @see Zend_Db_Select
     */
    public function order($column = NULL, $direction = NULL)
    {
        if (!empty($column)) {
            $orderQuery = trim($column . ' ' . strtoupper(empty($direction) ? NULL : $direction));
            $this->select->order($orderQuery);
        }

        return $this;
    }

    /**
     * (non-PHPDoc)
     *
     * @return int
     */
    public function getCount()
    {
        return intval($this->rowCount);
    }

    /**
     * (non-PHPDoc)
     *
     * @return string
     */
    public function getSqlQuery()
    {
        //todo check this moment
        return $this->select->assemble();
    }

    /**
     * (non-PHPDoc)
     *
     * @param string $cond
     * @param $value
     * @param integer $type
     *
     * @return EntityView
     */
    public function where($cond, $value = NULL, $type = \PDO::PARAM_STR)
    {
        if (!empty($cond)) {
            // todo need refactoring type
            $this->select->where($cond, $value, $this->getParamType($type));
        }

        return $this;
    }

    /**
     * (non-PHPDoc)
     *
     * @return array()
     */
    public function fetchAll()
    {
        return $this->_fetchAll();
    }

    /**
     * @param mixed $id
     *
     * @return EntityView
     */
    public function whereId($id)
    {
        $primaryKey = $this->getPrimaryKey();

        if (empty($id) || empty($primaryKey)) {
            $this->where('-1 = 1');
        } else {
            $this->where($this->getPrimaryKey() . ' = ?', $id, \PDO::PARAM_INT);
        }

        return $this;
    }

    /**
     * <code>
     * $this->_getPrimaryKey(); // 'USER_ID'
     * </code>
     *
     * @return string
     */
    protected function getPrimaryKey()
    {
        return NULL;
    }

    /**
     * <code>
     * $this->getViewOrder(); //'id'
     * </code>
     *
     * <code>
     * $this->getViewOrder(); //['id']
     * </code>
     *
     * <code>
     * $this->getViewOrder(); //['id', 'desc']
     * </code>
     *
     * @return array|string|null
     */
    public function getViewOrder()
    {
        return null;
    }

    /**
     * (non-PHPDoc)
     */
    public function cacheClean()
    {
        //todo need realization cache
    }
}