<?php
/**
 * BindExecuteTest
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */
namespace SpavTest\Entity\MySql\StoredProcedure;

use PHPUnit\Framework\TestCase;
use SpavTest\Bootstrap;
use Spav\Entity\MySql\AbstractStoredProcedure;
use SpavTest\EntityExample\StoredProcedure\ExecuteEntity;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\ArrayObject;

class ExecuteTest extends TestCase
{
    const TYPE_JSON = 1;
    const TYPE_RECORDSET = 2;

    /**
     * @var ExecuteEntity
     */
    protected $executeEntity;

    /**
     * (non-PHPDoc)
     */
    public function setUp()
    {
        $this->executeEntity = new ExecuteEntity(Bootstrap::getServiceManager());

        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $arrayObject->offsetSet('key', 228);
        $arrayObject->offsetSet('name', 'afaf$%^&');
        $arrayObject->offsetSet('type', self::TYPE_JSON);

        /* default params */
        $this->executeEntity->setParams($arrayObject);

        parent::setUp();
    }

    /**
     * (non-PHPDoc)
     */
    public function tearDown()
    {
        $this->executeEntity = null;
    }

    /**
     * @see BindExecute::statementExecute()
     */
    public function testStatementExecute()
    {
        $runTest = true;

        if ($runTest) {
            $statementExecute = Bootstrap::getMethod($this->executeEntity, 'statementExecute');

            $statementExecuteResult = $statementExecute->invokeArgs($this->executeEntity, []);

            $this->assertTrue(($statementExecuteResult instanceof ResultSet));
        } else {
            $this->markTestIncomplete('Need refactoring | SQLSTATE[HY000]: General error: 2014');
        }
    }

    /**
     * @see BindExecute::getResult()
     */
    public function testGetResult()
    {
        $runTest = true;

        if ($runTest) {
            $getResult = Bootstrap::getMethod($this->executeEntity, 'getResult');

            $getResultRun = $getResult->invoke($this->executeEntity, []);

            $this->assertTrue(is_array($getResultRun));
        } else {
            $this->markTestIncomplete('Need refactoring | SQLSTATE[HY000]: General error: 2014');
        }
    }

    /**
     * @see AbstractStoredProcedure::execute()
     */
    public function testExecute()
    {
        $bindExecuteEntityResultSuccess = $this->executeEntity->execute();
        $this->assertEquals(1, $bindExecuteEntityResultSuccess['success']);
        $this->assertArrayHasKey('data', $bindExecuteEntityResultSuccess);

        $this->executeEntity->setParams(new ArrayObject());

        $bindExecuteEntityResultUnSuccess = $this->executeEntity->execute();
        $this->assertEquals(0, $bindExecuteEntityResultUnSuccess['success']);
        $this->assertArrayHasKey('message', $bindExecuteEntityResultUnSuccess);
    }

    /**
     * @see AbstractStoredProcedure::fetchAll()
     */
    public function testFetchAll()
    {
        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $arrayObject->offsetSet('type', self::TYPE_RECORDSET);


        $this->executeEntity->setParams($arrayObject);

        $recordset = $this->executeEntity->fetchAll();

        $this->assertEquals(1, $recordset['success']);
        $this->assertArrayHasKey('data', $recordset);
        $this->assertTrue(is_array($recordset));
    }
}