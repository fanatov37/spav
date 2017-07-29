<?php
/**
 * LocaleService
 *
 * @link https://fanatov37@bitbucket.org/fanatov37/hypeshare.git for the canonical source repository
 * @copyright Copyright (c)
 * @license HypeShare (c)
 * @author VladFanatov
 * @package Core
 */
namespace Spav\ServiceManager;;

use Zend\Http\Header\SetCookie;
use Zend\I18n\Translator\TextDomain;
use Zend\Json\Json;

class LocaleService extends ServiceManager
{
    /**
     * @return array
     */
    public function getLocaleList() : array
    {
        $config  = $this->getConfig();

        /** @var array $localeList */
        $localeList = $config['translator']['list'];

        return $localeList;
    }

    /**
     * @param $locale
     *
     * @return SetCookie
     */
    public function setLocale($locale)
    {
        /** @var array $localeList */
        $localeList = $this->getLocaleList();

        $translator = $this->getTranslator();

        if (in_array($locale, $localeList)) {
            $cookie = new SetCookie();
            $cookie->setName('locale')
                ->setValue($locale)
                ->setPath('/');

            $translator->setLocale($locale);

            return $cookie;
        }
    }

    /**
     * @return mixed
     */
    public function getCurrentLocale() : string
    {
        $translator = $this->getTranslator();

        return $translator->getLocale();
    }

    /**
     * @return mixed
     */
    public function getCurrentLocaleId() : int
    {
        $currentLocale = $this->getCurrentLocale();

        $config = $this->getService('config');
        $localeList = $config['translator']['list'];

        $currentLocaleIndex = 1;

        foreach ($localeList as $key=>$locale) {
            if ($locale === $currentLocale) {
                break;
            }

            ++ $currentLocaleIndex;
        }


        return $currentLocaleIndex;
    }

    /**
     * @param $locale
     *
     * @return bool
     */
    public function isCurrentLocale($locale) : bool
    {
        if ($locale === $this->getCurrentLocale()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
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