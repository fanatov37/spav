<?php
/**
 * BindExecuteTest.
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
use Spav\Entity\MySql\StoredFunction\BindExecute;
use SpavTest\EntityExample\StoredFunction\BindExecuteEntity;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\ArrayObject;

class BindExecuteTest extends TestCase
{
    /**
     * @var BindExecuteEntity
     */
    protected $bindExecuteEntity;

    /**
     * (non-PHPDoc).
     */
    public function setUp()
    {
        $this->bindExecuteEntity = new BindExecuteEntity(Bootstrap::getServiceManager());

        $arrayObject = new ArrayObject();
        $arrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $arrayObject->offsetSet('string', 'fff@mail.ru');
        $arrayObject->offsetSet('integer', 228);

        $this->bindExecuteEntity->setParams($arrayObject);

        parent::setUp();
    }

    /**
     * (non-PHPDoc).
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
        $statementExecute = Bootstrap::getMethod($this->bindExecuteEntity, 'statementExecute');

        $statementExecuteResult = $statementExecute->invokeArgs($this->bindExecuteEntity, []);

        $this->assertTrue(($statementExecuteResult instanceof ResultInterface));
    }

    /**
     * @see BindExecute::getResult()
     */
    public function testGetResult()
    {
        $getResult = Bootstrap::getMethod($this->bindExecuteEntity, 'getResult');

        $getResultRun = $getResult->invoke($this->bindExecuteEntity, []);

        $this->assertArrayHasKey('success', $getResultRun);
        $this->assertArrayHasKey('data', $getResultRun);

        $this->assertEquals(1, $getResultRun['success']);
        $this->assertTrue(is_array($getResultRun['data']));
    }
}
