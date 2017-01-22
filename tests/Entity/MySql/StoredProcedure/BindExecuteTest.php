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
use Spav\Entity\MySql\StoredProcedure\BindExecute;
use SpavTest\EntityExample\StoredProcedure\BindExecuteEntity;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\ArrayObject;

class BindExecuteTest extends TestCase
{
    const TYPE_JSON = 1;
    const TYPE_RECORDSET = 2;

    /**
     * @var BindExecuteEntity
     */
    protected $bindExecuteEntity;

    /**
     * (non-PHPDoc)
     */
    public function setUp()
    {
        $this->bindExecuteEntity = new BindExecuteEntity(Bootstrap::getServiceManager());

        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $arrayObject->offsetSet('key', 228);
        $arrayObject->offsetSet('name', 'afaf$%^&');
        $arrayObject->offsetSet('type', self::TYPE_JSON);

        /* default params */
        $this->bindExecuteEntity->setParams($arrayObject);

        parent::setUp();
    }

    /**
     * (non-PHPDoc)
     */
    public function tearDown()
    {
        $this->bindExecuteEntity = null;
    }

    /**
     * @see BindExecute::statementExecute()
     */
    public function testStatementExecute()
    {
        $runTest = true;

        if ($runTest) {
            $statementExecute = Bootstrap::getMethod($this->bindExecuteEntity, 'statementExecute');

            $statementExecuteResult = $statementExecute->invokeArgs($this->bindExecuteEntity, []);

            $this->assertTrue(($statementExecuteResult instanceof ResultInterface));
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
            $getResult = Bootstrap::getMethod($this->bindExecuteEntity, 'getResult');

            $getResultRun = $getResult->invoke($this->bindExecuteEntity, []);

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
        $bindExecuteEntityResultSuccess = $this->bindExecuteEntity->execute();
        $this->assertEquals(1, $bindExecuteEntityResultSuccess['success']);
        $this->assertArrayHasKey('data', $bindExecuteEntityResultSuccess);

        $this->bindExecuteEntity->setParams(new ArrayObject());

        $bindExecuteEntityResultUnSuccess = $this->bindExecuteEntity->execute();
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


        $this->bindExecuteEntity->setParams($arrayObject);

        $recordset = $this->bindExecuteEntity->fetchAll();

        $this->assertEquals(1, $recordset['success']);
        $this->assertArrayHasKey('data', $recordset);
        $this->assertTrue(is_array($recordset));
    }
}