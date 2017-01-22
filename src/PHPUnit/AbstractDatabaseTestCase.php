<?php
/**
 * AbstractDatabaseTestCase
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Core PHPUnit
 */

namespace Spav\PHPUnit;

use PHPUnit_Extensions_Database_TestCase;
use PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection as
    PHPUnitDefaultDatabaseConnection;
use PDO;
use SpavTest\Bootstrap;

abstract class AbstractDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{

    /**
     * @var PDO
     */
    static private $pdo = null;

    /**
     * @var PHPUnitDefaultDatabaseConnection
     */
    private $conn = null;

    /**
     * @return array
     */
    protected function getDataBaseConfig() : array
    {
        $config = Bootstrap::getServiceManager()->get('config');

        return $config['db'];
    }

    /**
     * @return null|\PDO
     */
    final public function getAdapter() : PDO
    {
        $dataBaseConfig = $this->getDataBaseConfig();

        if (self::$pdo == null) {
            self::$pdo = new PDO(
                $dataBaseConfig['dsn'],
                $dataBaseConfig['username'],
                $dataBaseConfig['password']
            );
        }

        return self::$pdo;
    }

    /**
     * @return null|PHPUnitDefaultDatabaseConnection
     */
    final public function getConnection() : PHPUnitDefaultDatabaseConnection
    {
        if ($this->conn === null) {
            $this->conn = $this->createDefaultDBConnection($this->getAdapter());
        }

        return $this->conn;
    }
}
