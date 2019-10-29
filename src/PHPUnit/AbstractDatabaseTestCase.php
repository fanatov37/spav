<?php
/**
 * AbstractDatabaseTestCase.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\PHPUnit;

use PDO;
use PHPUnit\DbUnit\Database\DefaultConnection;
use PHPUnit\DbUnit\TestCase;
use SpavTest\Bootstrap;

abstract class AbstractDatabaseTestCase extends TestCase
{
    /**
     * @var PDO
     */
    private static $pdo = null;

    /**
     * @var DefaultConnection
     */
    private $conn = null;

    /**
     * @return array
     */
    protected function getDataBaseConfig(): array
    {
        $config = Bootstrap::getServiceManager()->get('config');

        return $config['db'];
    }

    /**
     * @return null|\PDO
     */
    final public function getAdapter(): PDO
    {
        $dataBaseConfig = $this->getDataBaseConfig();

        if (null == self::$pdo) {
            self::$pdo = new PDO(
                $dataBaseConfig['dsn'],
                $dataBaseConfig['username'],
                $dataBaseConfig['password']
            );
        }

        return self::$pdo;
    }

    /**
     * @return null|DefaultConnection
     */
    final public function getConnection(): DefaultConnection
    {
        if (null === $this->conn) {
            $this->conn = $this->createDefaultDBConnection($this->getAdapter());
        }

        return $this->conn;
    }
}
