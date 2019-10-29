<?php
/**
 * StoredFunctionTest.
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
use Spav\Entity\MySql\AbstractStoredFunction;

class StoredFunctionTest extends TestCase
{
    /**
     * @var StoredFunction\
     */
    private $storedFunction;

    /**
     * (non-PHPDoc).
     */
    public function setUp()
    {
        $this->storedFunction = $this->getMockForAbstractClass(
            AbstractStoredFunction::class, [Bootstrap::getServiceManager()]
        );

        parent::setUp();
    }

    /**
     * @see AbstractStoredFunction::$templateFunctionSql
     */
    public function testTemplateFunctionSql()
    {
        $templateFunctionSqlMustBe = 'select %s.%s(%s) as json from dual';

        $this->assertAttributeEquals(
            $templateFunctionSqlMustBe, 'templateFunctionSql', $this->storedFunction
        );
    }
}
