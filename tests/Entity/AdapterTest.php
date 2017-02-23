<?php
/**
 * AdapterTest
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 * @package Library PHPUnit
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
    private $yfEntityAdapter;

    /**
     * (non-PHPDoc)
     */
    public function setUp()
    {
        $this->yfEntityAdapter = $this->getMockForAbstractClass(
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
            [ParameterContainer::TYPE_STRING => '36546g54g4g4g!@#$%^&']
        ];

        $getParamType = Bootstrap::getMethod($this->yfEntityAdapter, 'getParamType');

        foreach ($paramTypes as $paramTypeArray) {
            foreach ($paramTypeArray as $key=>$paramType) {
                $adapterParamType = $getParamType->invokeArgs($this->yfEntityAdapter, [$paramType]);
                $this->assertEquals($adapterParamType, $key);

            }
        }
    }

    /**
     * @see AbstractAdapter::getCurrentLocale()
     */
    public function testGetCurrentLocale()
    {
        $getCurrentLocale = Bootstrap::getMethod($this->yfEntityAdapter, 'getCurrentLocale');

        $this->assertTrue(is_string($getCurrentLocale->invokeArgs($this->yfEntityAdapter, [])));
    }

    /**
     * @see AbstractStoredFunction::initParams()
     *
     */
    public function testInitParams()
    {
        $testParamArray = [
            [ParameterContainer::TYPE_STRING => 'fff@mail.ru'],
            [ParameterContainer::TYPE_STRING => '228']
        ];

        $initParams = Bootstrap::getMethod($this->yfEntityAdapter, 'initParams');

        $initParamsResult = $initParams->invokeArgs($this->yfEntityAdapter, [$testParamArray]);

        $testParamsAfterInitMustBe = [
            ':param0' => [ParameterContainer::TYPE_STRING => 'fff@mail.ru'],
            ':param1' => [ParameterContainer::TYPE_STRING => '228']
        ];

        $this->assertEquals($initParamsResult, $testParamsAfterInitMustBe);
    }
}