<?php

/**
 * ParamInterface
 *
 * @link https://github.com/fanatov37/spav.git for the canonical source repository
 * @copyright Copyright (c) 2015
 * @license YouFold (c)
 * @author VladFanatov
 * @package Library
 */
namespace Spav\Entity\MySql;

use Zend\Stdlib\ArrayObject;

Interface ParamInterface
{
    /**
     * @param ArrayObject $paramObject
     *
     * @return self
     */
    public function setParams(ArrayObject $paramObject);

    /**
     * @return array
     */
    public function getParams() : array;
}