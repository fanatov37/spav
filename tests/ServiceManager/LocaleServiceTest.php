<?php
/**
 * LocaleServiceTest
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 * @package Library PHPUnit
 */
namespace SpavTest\ServiceManager;

use PHPUnit\Framework\TestCase;
use Spav\ServiceManager\LocaleService;
use SpavTest\Bootstrap;


class LocaleServiceTest extends TestCase
{
    const ENG_LOCALE = 'en_US';
    const RUS_LOCALE = 'ru_RU';

    /**
     * @var LocaleService
     */
    private $localService;

    /**
     * (non-PHPDoc)
     */
    public function setUp()
    {
        $this->localService = new LocaleService(Bootstrap::getServiceManager());

        parent::setUp();
    }

    /**
     * (non-PHPDoc)
     */
    public function tearDown()
    {
        $this->localService = null;
    }

    /**
     * @see LocaleService::getLocaleList()
     */
    public function testGetLocaleList()
    {
        $localeList = $this->localService->getLocaleList();

        $this->assertTrue(is_array($localeList) && !empty($localeList));
    }

    /**
     * @see LocaleService::getCurrentLocale()
     * @see LocaleService::setLocale()
     */
    public function testGetCurrentLocale()
    {
        $localService = $this->localService;
        $localeList = $localService->getLocaleList();

        $this->assertEquals(self::RUS_LOCALE, $localService->getCurrentLocale());


        /** change locale */

        $this->assertTrue(in_array(self::ENG_LOCALE, $localeList));

        $localService->setLocale(self::ENG_LOCALE);

        $this->assertEquals(self::ENG_LOCALE, $localService->getCurrentLocale());
    }

    /**
     * @see LocaleService::getCurrentLocaleId()
     */
    public function testGetCurrentLocaleId()
    {
        $engLocaleId = 1;

        $this->assertEquals($engLocaleId, $this->localService->getCurrentLocaleId());
    }

    /**
     * @see LocaleService::isCurrentLocale()
     */
    public function testIsCurrentLocale()
    {
        $this->assertTrue($this->localService->isCurrentLocale(self::ENG_LOCALE));
        $this->assertTrue(!$this->localService->isCurrentLocale(self::RUS_LOCALE));
    }
}