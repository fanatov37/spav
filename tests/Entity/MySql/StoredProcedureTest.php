<?php
/**
 * StoredProcedureTest.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 */

namespace SpavTest\Entity\MySql;

use PHPUnit\Framework\TestCase;
use SpavTest\Bootstrap;
use Spav\Entity\MySql\AbstractStoredProcedure;

class StoredProcedureTest extends TestCase
{
    /**
     * @var AbstractStoredProcedure
     */
    private $storedProcedure;

    /**
     * (non-PHPDoc).
     */
    public function setUp()
    {
        $this->storedProcedure = $this->getMockForAbstractClass(
            AbstractStoredProcedure::class, [Bootstrap::getServiceManager()]
        );

        parent::setUp();
    }

    /**
     * @see AbstractStoredProcedure::$templateProcedureSql
     */
    public function testTemplateProcedureSql()
    {
        $templateFunctionSqlMustBe = 'call %s.%s(%s)';

        $this->assertAttributeEquals(
            $templateFunctionSqlMustBe, 'templateProcedureSql', $this->storedProcedure
        );
    }
}
