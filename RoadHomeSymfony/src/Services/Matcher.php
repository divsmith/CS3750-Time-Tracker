<?php
/**
 * File name: Matcher.php
 * Project: testSilex
 * PHP version 5
 * @category  PHP
 * @package   slimRoadHome/Domain
 * @author    markrichardson <compynerds@gmail.com>
 * @copyright 2016 © markrichardson
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * @link
 * $LastChangedDate$ 10/8/2016
 * $LastChangedBy$ Mark Richardson
 */

namespace slimRoadHome\Services;

use RoadHome\Domain\VolunteerRepository;
use RoadHome\Domain\ValueObject;

/**
 * Class Matcher
 * @category  PHP
 * @package   slimRoadHome\Services
 * @author    markrichardson <compynerds@gmail.com>
 * @link
 */
abstract class Matcher
{
    /** @var \RoadHome\Domain\VolunteerRepository */
    protected $repo;

    abstract public function match(ValueObject $value);

    /**
     * Matcher constructor
     * @param \RoadHome\Domain\VolunteerRepository $repo
     */
    public function __construct(VolunteerRepository $repo)
    {
        $this->repo = $repo;
    }
}
