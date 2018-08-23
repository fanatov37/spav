<?php
namespace Spav\ServiceManager;;

use Zend\Http\Header\SetCookie;
use Zend\I18n\Translator\TextDomain;
use Zend\Json\Json;
/**
 * Class LocaleService
 *
 * @link https://fanatov37@bitbucket.org/fanatov37/hypeshare.git for the canonical source repository
 * @copyright Copyright (c)
 * @license HypeShare (c)
 * @author VladFanatov
 * @package Spav\ServiceManager
 */
class LocaleService extends ServiceManager
{
    /**
     * @return array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getLocaleList() : array
    {
        $config  = $this->getConfig();

        return $config['translator']['list'];
    }

    /**
     * @param $locale
     *
     * @return SetCookie
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function setLocale($locale) : SetCookie
    {
        /** @var array $localeList */
        $localeList = $this->getLocaleList();

        if (!in_array($locale, $localeList)) {
            throw new \Exception('Undefined local name');
        }

        $translator = $this->getTranslator();

        $cookie = new SetCookie();
        $cookie->setName('locale')
            ->setValue($locale)
            ->setPath('/');

        $translator->setLocale($locale);

        return $cookie;
    }

    /**
     * @return string
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCurrentLocale() : string
    {
        $translator = $this->getTranslator();

        return $translator->getLocale();
    }

    /**
     * @return int
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCurrentLocaleId() : int
    {
        $currentLocale = $this->getCurrentLocale();

        $config = $this->getService('config');
        $localeList = $config['translator']['list'];

        $currentLocaleIndex = 1;

        foreach ($localeList as $key=>$locale) {
            if ($locale === $currentLocale) {
                $currentLocaleIndex = $key;
            }
        }

        return $currentLocaleIndex;
    }

    /**
     * @param int $localeId
     *
     * @return string
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getLocaleNameById(int $localeId) : string
    {
        $localeList = $this->getLocaleList();

        if(!isset($localeList[$localeId])) {
            throw new \Exception('Undefined local name by this id');
        }

        return $localeList[$localeId];
    }

    /**
     * @param $locale
     *
     * @return bool
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function isCurrentLocale($locale) : bool
    {
        return $locale === $this->getCurrentLocale();
    }

    /**
     * @return string
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getAllTranslatedMessage() : string
    {
        $translator = $this->getTranslator();

        $messages = [];

        $textDomain = $translator->getAllMessages();
        $fallbackTextDomain = $translator->getAllMessages(
            'default',
            $translator->getFallbackLocale()
        );

        if ($textDomain instanceof TextDomain) {
            $messages = array_merge($messages, $textDomain->getArrayCopy());
        }

        if ($fallbackTextDomain instanceof TextDomain) {
            $messages = array_merge($messages, $fallbackTextDomain->getArrayCopy());
        }

        return Json::encode($messages);
    }
}