<?php

/**
 * ParamInterface.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 */

namespace Spav\Entity\MySql;

use Zend\Stdlib\ArrayObject;

interface ParamInterface
{
    /**
     * @param ArrayObject|\stdClass $paramObject
     *
     * @return self
     */
    public function setParams($paramObject);

    /**
     * @return array
     */
    public function getParams(): array;
}
