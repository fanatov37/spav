<?php

/**
 * CacheInterface.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\Entity\EntityInterface;

interface CacheInterface
{
    /**
     * unique.
     *
     * @return string
     */
    public function getCacheID();

    /**
     * get tags.
     *
     * <code>
     * $this->getCacheTags(); //array('all vendors', 'apple', 'redhat', 'mandriva');
     * </code>
     *
     * @return string|array
     */
    public function getCacheTags();

    /**
     * life time cache sec (3600 = 1hr).
     *
     * @return int
     */
    public function getCacheLifeTime();
}
