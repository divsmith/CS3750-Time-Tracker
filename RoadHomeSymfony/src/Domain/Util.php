<?php
/**
 * File name: Util.php
 * Project: value-objects
 * PHP version 5
 * @category  PHP
 * @package   ValueObjects
 * @author    donbstringham <donbstringham@gmail.com>
 * @copyright 2016 © donbstringham
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @version   GIT: <git_id>
 * @link      http://donbstringham.us
 * $LastChangedDate$
 * $LastChangedBy$
 */
namespace RoadHome\Domain;
/**
 * Class Util
 * @category  PHP
 * @package   ValueObjects
 * @author    donbstringham <donbstringham@gmail.com>
 * @link      http://donbstringham.us
 */
class Util
{
    /**
     * @param mixed $objectA
     * @param mixed $objectB
     * @return bool
     */
    final public static function equals($objectA, $objectB)
    {
        return get_class($objectA) === get_class($objectB);
    }
    /**
     * @param mixed $object
     * @return string
     */
    final public static function getClassAsString($object)
    {
        return get_class($object);
    }
}