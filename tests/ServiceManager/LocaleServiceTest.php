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
    public const ENG_LOCALE = 'en_US';
    public const ENG_LOCALE_ID = 1;

    public const RUS_LOCALE = 'ru_RU';
    public const RUS_LOCALE_ID = 2;

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
     * @see LocaleService::getLocaleList
     */
    public function testGetLocaleList()
    {
        $localeList = $this->localService->getLocaleList();

        $this->assertTrue(is_array($localeList) && !empty($localeList));
    }

    /**
     * @see LocaleService::getCurrentLocale
     * @see LocaleService::setLocale()
     *
     * @throws \Exception
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
     * @see LocaleService::getCurrentLocaleId
     *
     * @throws \Exception
     */
    public function testGetCurrentLocaleId()
    {
        $this->assertEquals(self::ENG_LOCALE_ID, $this->localService->getCurrentLocaleId());

        $this->localService->setLocale(self::RUS_LOCALE);

        $this->assertEquals(self::RUS_LOCALE_ID, $this->localService->getCurrentLocaleId());
    }

    /**
     * @see LocaleService::isCurrentLocale
     *
     * @throws \Exception
     */
    public function testIsCurrentLocale()
    {
        $this->localService->setLocale(self::ENG_LOCALE);

        $this->assertTrue($this->localService->isCurrentLocale(self::ENG_LOCALE));
        $this->assertFalse($this->localService->isCurrentLocale(self::RUS_LOCALE));
    }

    /**
     * @see LocaleService::getAllTranslatedMessage
     */
    public function testGetAllTranslatedMessage ()
    {
        $allTranslatedMessage = $this->localService->getAllTranslatedMessage();

        $this->assertTrue(is_string($allTranslatedMessage));
    }

    /**
     * @see LocaleService::setLocale
     *
     * @throws \Exception
     */
    public function testSetUndefinedLocale()
    {
        $this->expectException(\Exception::class);
        $undefinedLocale = 'UNDEFINED_LOCALE';
        $this->localService->setLocale($undefinedLocale);
        $this->assertFalse($this->localService->isCurrentLocale($undefinedLocale));
    }

    /**
     * @see LocaleService::getLocaleNameById
     *
     * @throws \Exception
     */
    public function testGetLocaleNameById()
    {
        $this->assertEquals(self::ENG_LOCALE, $this->localService->getLocaleNameById(self::ENG_LOCALE_ID));
        $this->assertEquals(self::RUS_LOCALE, $this->localService->getLocaleNameById(self::RUS_LOCALE_ID));

        $this->expectException(\Exception::class);
        $undefinedLocalId = 228;
        $this->assertEquals(self::RUS_LOCALE, $this->localService->getLocaleNameById($undefinedLocalId));
    }
}