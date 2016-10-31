<?php
/**
 * File name: MatchByEmail.php
 * Project: testSilex
 * PHP version 5
 * @category  PHP
 * @package   RoadHome/Domain
 * @author    markrichardson <compynerds@gmail.com>
 * @copyright 2016 © markrichardson
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * @link
 * $LastChangedDate$ 10/8/2016
 * $LastChangedBy$ Mark Richardson
 */

namespace RoadHome\Services;

use RoadHome\Domain\ValueObject;

/**
 * Class MatchByEmail
 * @category  PHP
 * @package   slimRoadHome\Services
 * @author    markrichardson <compynerds@gmail.com>
 * @link
 */
class MatchByEmail extends Matcher
{
    public function match(ValueObject $value)
    {
        /** @var \RoadHome\Domain\StringLiteral $value */
        return $this->repo->findByEmail($value);
    }
}
