<?php

/**
 * TransactionExecute
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\Entity\MySql\StoredProcedure;

use Zend\Json\Json;

abstract class TransactionExecute extends Execute
{
    /**
     * (non-PHPDoc)
     */
    protected function beginTransaction()
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }

    /**
     * (non-PHPDoc)
     */
    protected function rollback()
    {
        $this->getAdapter()->getDriver()->getConnection()->rollback();
    }

    /**
     * (non-PHPDoc)
     */
    protected function commit()
    {
        $this->getAdapter()->getDriver()->getConnection()->commit();
    }

    /**
     *
     * (non-PHPDoc)
     *
     * @throws \Exception
     *
     * @return array
     */
    final public function fetchAll() : array
    {
        try {
            $this->beginTransaction();

            $data = $this->getResult();

            $this->commit();

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (\Exception $exception) {

            $this->rollback();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * todo need refactoring
     *
     * (non-PHPDoc)
     *
     * @return array
     */
    final public function execute() : array
    {
        try {
            $this->beginTransaction();

            $result = $this->getResult();

            if ( !(count($result) === 1 && isset($result[0]['json']))) {
                throw new \Exception('Invalid resultset');
            }

            $result = Json::decode($result[0]['json'], Json::TYPE_ARRAY);

            if ($result['success']) {
                $this->commit();
            } else {
                $this->rollback();
            }

            return $result;

        } catch (\Exception $exception) {

            $this->rollback();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}