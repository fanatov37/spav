<?php
/**
 * EntityView.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\Entity\MySql\View;

use Spav\Entity\MySql\View;
use Zend\Db\Sql\Predicate\Predicate;

abstract class EntityView extends View
{
    /**
     * (non-PHPDoc).
     *
     * @return array
     */
    abstract protected function getColumns();

    /**
     * (non-PHPDoc).
     *
     * @return array (view name and columns)
     */
    abstract protected function getView();

    /**
     * (non-PHPDoc).
     *
     * @see YF_Module_Db_MySql_View::_setData()
     */
    protected function setData()
    {
        $getView = $this->getView();
        $this->viewName = (string) $getView[0];
        $this->rows = $getView[1];
    }

    /**
     * <code>
     * $this->_getPrimaryKey(); // 'USER_ID'
     * </code>.
     *
     * @return string
     */
    protected function getPrimaryKey()
    {
        return null;
    }

    /**
     * (non-PHPDoc).
     *
     * @see Zend_Db_Select
     */
    public function limit($count = null, $offset = null)
    {
        if (!empty($count)) {
            $this->select->limit($count, $offset);
        }
    }

    /**
     * (non-PHPDoc).
     *
     * @see Zend_Db_Select
     */
    public function order($column = null, $direction = null)
    {
        if (!empty($column)) {
            $orderQuery = trim($column.' '.strtoupper(empty($direction) ? null : $direction));
            $this->select->order($orderQuery);
        }

        return $this;
    }

    /**
     * (non-PHPDoc).
     *
     * @return int
     */
    public function getCount()
    {
        return (int) $this->rowCount;
    }

    /**
     * (non-PHPDoc).
     *
     * @return string
     */
    public function getSqlQuery(): string
    {
        return $this->select->getSqlString();
    }

    /**
     * (non-PHPDoc).
     *
     * @param Predicate $predicate
     *
     * @return $this|self
     */
    public function where(Predicate $predicate): self
    {
        $this->select->where($predicate);

        return $this;
    }

    /**
     * @param int $id
     *
     * @return $this|self
     */
    public function whereId(int $id): self
    {
        $predicate = new Predicate();

        $primaryKey = $this->getPrimaryKey();

        if (empty($id) || empty($primaryKey)) {
            $predicate->equalTo(-1, 1);
        } else {
            $predicate->equalTo($primaryKey, $id);
        }

        $this->where($predicate);

        return $this;
    }

    /**
     * Rememder that your query must have user_id field.
     *
     * @param int $userId
     *
     * @return $this|self
     */
    public function whereByUserId(int $userId): self
    {
        $predicate = new Predicate();

        if (empty($userId)) {
            $predicate->equalTo(-1, 1);
        } else {
            $predicate->equalTo('user_id', $userId);
        }

        $this->where($predicate);

        return $this;
    }

    /**
     * <code>
     * $this->getViewOrder(); //'id'
     * </code>.
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
     * (non-PHPDoc).
     */
    public function cacheClean()
    {
        //todo need realization cache
    }

    /**
     * (non-PHPDoc).
     *
     * @return array()
     */
    public function fetchAll()
    {
        return $this->_fetchAll();
    }
}
