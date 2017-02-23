<?php
/**
 * TransactionExecuteTest
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */
namespace SpavTest\Entity\MySql\StoredProcedure;

use Spav\PHPUnit\AbstractDatabaseTestCase;
use Zend\Stdlib\ArrayObject;
use PHPUnit_Extensions_Database_DataSet_ArrayDataSet;
use PHPUnit_Extensions_Database_DataSet_QueryDataSet;

use SpavTest\Bootstrap;
use Spav\Entity\MySql\AbstractStoredProcedure;
use SpavTest\EntityExample\StoredProcedure\TransactionExecuteEntity;

class TransactionExecuteTest extends AbstractDatabaseTestCase
{
    const TYPE_JSON = 1;
    const TYPE_RECORDSET = 2;
    const TYPE_TRANSACTION = 3;

    /**
     * @var TransactionExecuteEntity
     */
    protected $transactionExecuteEntity;

    /**
     * @return PHPUnit_Extensions_Database_DataSet_ArrayDataSet
     */
    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_ArrayDataSet( [
            'test_table' => [
                ['id' => 100, 'key'  => uniqid('key-'), 'name' => 'Mordecai Richler'],
                ['id' => 101, 'key'  => uniqid('key-'), 'name' => 'Farley Mowat']
            ]
        ]);
    }

    /**
     * (non-PHPDoc)
     */
    public function setUp()
    {
        $this->transactionExecuteEntity =
            new TransactionExecuteEntity(Bootstrap::getServiceManager());

        parent::setUp();
    }

    /**
     * (non-PHPDoc)
     */
    public function tearDown()
    {
        $this->transactionExecuteEntity = null;
    }

    /**
     * @see AbstractStoredProcedure::execute()
     */
    public function testExecute()
    {
        /* test of success execute */
        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $uniqKey = uniqid('key-');
        $name = uniqid('name-');

        $arrayObject->offsetSet('key', $uniqKey);
        $arrayObject->offsetSet('name', $name);
        $arrayObject->offsetSet('type', self::TYPE_TRANSACTION);

        $this->transactionExecuteEntity->setParams($arrayObject);

        $executeEntityResultSuccess = $this->transactionExecuteEntity->execute();

        $this->assertEquals(1, $executeEntityResultSuccess['success']);
        $this->assertArrayHasKey('data', $executeEntityResultSuccess);
        $this->assertArrayHasKey('id', $executeEntityResultSuccess['data']);

        $sql = sprintf('SELECT * FROM test_table where id="%s"', $executeEntityResultSuccess['data']['id']);

        $stmt = $this->getAdapter()->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch();

        $this->assertEquals($executeEntityResultSuccess['data']['id'], $result['id']);
        $this->assertEquals($uniqKey, $result['key']);
        $this->assertEquals($name, $result['name']);


        $executeEntityResultUnSuccess = $this->transactionExecuteEntity->execute();

        $this->assertEquals(0, $executeEntityResultUnSuccess['success']);
        $this->assertArrayHasKey('message', $executeEntityResultUnSuccess);


        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $uniqKey = uniqid();

        $arrayObject->offsetSet('key', $uniqKey);
        $arrayObject->offsetSet('name', 'afaf$%^&');
        $arrayObject->offsetSet('type', self::TYPE_TRANSACTION);
        $arrayObject->offsetSet('isExeption', true);

        $this->transactionExecuteEntity->setParams($arrayObject);

        $executeEntityResultUnSuccess = $this->transactionExecuteEntity->execute();

        $this->assertEquals(0, $executeEntityResultUnSuccess['success']);
        $this->assertArrayHasKey('message', $executeEntityResultUnSuccess);
        $this->assertArrayHasKey('id', $executeEntityResultUnSuccess);
    }

}