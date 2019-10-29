<?php
/**
 * ExecuteTest.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 */

namespace SpavTest\Entity\MySql\StoredFunction;

use PHPUnit\Framework\TestCase;
use SpavTest\Bootstrap;
use Spav\Entity\MySql\AbstractStoredFunction;
use Spav\Entity\MySql\StoredFunction\BindExecute;
use SpavTest\EntityExample\StoredFunction\ExecuteEntity;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\ArrayObject;

class ExecuteTest extends TestCase
{
    /**
     * @var ExecuteEntity
     */
    protected $executeEntity;

    /**
     * (non-PHPDoc).
     */
    public function setUp()
    {
        $this->executeEntity = $this->getMockForAbstractClass(
            ExecuteEntity::class, [Bootstrap::getServiceManager()]
        );

        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $arrayObject->offsetSet('string', 'fff@mail.ru');
        $arrayObject->offsetSet('integer', 228);

        $this->executeEntity->setParams($arrayObject);

        parent::setUp();
    }

    /**
     * (non-PHPDoc).
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
        $statementExecute = Bootstrap::getMethod($this->executeEntity, 'statementExecute');

        $statementExecuteResult = $statementExecute->invokeArgs($this->executeEntity, []);

        $this->assertTrue(($statementExecuteResult instanceof ResultSet));
    }

    /**
     * @see BindExecute::getResult()
     */
    public function testGetResult()
    {
        $getResult = Bootstrap::getMethod($this->executeEntity, 'getResult');

        $getResultRun = $getResult->invoke($this->executeEntity, []);

        $this->assertEquals(1, $getResultRun['success']);
    }

    /**
     * @see AbstractStoredFunction::execute()
     */
    public function testExecute()
    {
        $execute = Bootstrap::getMethod($this->executeEntity, 'execute');

        $executeSuccessRun = $execute->invoke($this->executeEntity, []);

        $this->assertEquals(1, $executeSuccessRun['success']);

        /* set empty params */
        $this->executeEntity->setParams(new ArrayObject());

        $executeUnSuccessRun = $execute->invoke($this->executeEntity, []);
        $this->assertEquals(0, $executeUnSuccessRun['success']);
    }
}
