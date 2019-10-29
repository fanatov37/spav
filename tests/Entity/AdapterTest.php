<?php
/**
 * AdapterTest.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 */

namespace SpavTest\Entity;

use PHPUnit\Framework\TestCase;
use SpavTest\Bootstrap;
use Spav\Entity\AbstractAdapter;
use Zend\Db\Adapter\ParameterContainer;

class AdapterTest extends TestCase
{
    /**
     * @var AbstractAdapter
     */
    private $abstractAdapterMock;

    /**
     * (non-PHPDoc).
     */
    public function setUp()
    {
        $this->abstractAdapterMock = $this->getMockForAbstractClass(
            AbstractAdapter::class, [Bootstrap::getServiceManager()]
        );

        parent::setUp();
    }

    /**
     * @see AbstractAdapter::getParamType()
     */
    public function testGetParamType()
    {
        $paramTypes = [
            [ParameterContainer::TYPE_STRING => ParameterContainer::TYPE_STRING],
            [ParameterContainer::TYPE_INTEGER => ParameterContainer::TYPE_INTEGER],
            [ParameterContainer::TYPE_LOB => ParameterContainer::TYPE_LOB],
            [ParameterContainer::TYPE_DOUBLE => ParameterContainer::TYPE_DOUBLE],
            [ParameterContainer::TYPE_NULL => ParameterContainer::TYPE_NULL],
            [ParameterContainer::TYPE_STRING => null],
            [ParameterContainer::TYPE_STRING => '36546g54g4g4g!@#$%^&'],
        ];

        $getParamType = Bootstrap::getMethod($this->abstractAdapterMock, 'getParamType');

        foreach ($paramTypes as $paramTypeArray) {
            foreach ($paramTypeArray as $key => $paramType) {
                $adapterParamType = $getParamType->invokeArgs($this->abstractAdapterMock, [$paramType]);
                $this->assertEquals($adapterParamType, $key);
            }
        }
    }

    /**
     * @see AbstractAdapter::getCurrentLocaleId()
     */
    public function testGetCurrentLocale()
    {
        $getCurrentLocale = Bootstrap::getMethod($this->abstractAdapterMock, 'getCurrentLocaleId');

        $this->assertTrue(is_int($getCurrentLocale->invokeArgs($this->abstractAdapterMock, [])));
    }

    /**
     * @see AbstractStoredFunction::initParams()
     */
    public function testInitParams()
    {
        $testParamArray = [
            [ParameterContainer::TYPE_STRING => 'fff@mail.ru'],
            [ParameterContainer::TYPE_STRING => '228'],
        ];

        $initParams = Bootstrap::getMethod($this->abstractAdapterMock, 'initParams');

        $initParamsResult = $initParams->invokeArgs($this->abstractAdapterMock, [$testParamArray]);

        $testParamsAfterInitMustBe = [
            ':param0' => [ParameterContainer::TYPE_STRING => 'fff@mail.ru'],
            ':param1' => [ParameterContainer::TYPE_STRING => '228'],
        ];

        $this->assertEquals($initParamsResult, $testParamsAfterInitMustBe);
    }
}
