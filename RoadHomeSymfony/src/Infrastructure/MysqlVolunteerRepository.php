<?php
/**
 * File name: MysqlUserRepository.php
 * Project: project1
 * PHP version 5
 * @category  PHP
 * @package   Project1\Infrastructure
 * @author    donbstringham <donbstringham@gmail.com>
 * @copyright 2016 © donbstringham
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * @link      http://donbstringham.us
 * $LastChangedDate$
 * $LastChangedBy$
 */
namespace RoadHome\Infrastructure;

use RoadHome\Domain\StringLiteral;
use RoadHome\Domain\Volunteer;
use RoadHome\Domain\VolunteerRepository;

/**
 * Class MysqlUserRepository
 * @category  PHP
 * @package   Project1\Infrastructure
 * @author    donbstringham <donbstringham@gmail.com>
 * @link      http://donbstringham.us
 */
class MysqlVolunteerRepository implements VolunteerRepository
{
    /** @var \PDO */
    protected $driver;
    /**
     * MysqlUserRepository constructor
     * @param \PDO $driver
     */
    public function __construct(\PDO $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param \RoadHome\Domain\Volunteer $volunteer
     * @return $this
     * @throws \PDOException
     * @modified 11/3/2016 by Mark Richardson (changed the values to reflect the DB changes)
     */
    public function add(Volunteer $volunteer)
    {
        //not sure if this return value is correct but the check needs to be done.
        $varcheck = $this->findByEmail($volunteer->getEmail());
        if(count($varcheck) > 0){
            return 0;
        }

        $currentTime = Date('Y-m-d H:i:s');
        $data = [$volunteer->getEmail(), $volunteer->getFirstname(), $volunteer->getLastname(),
        $volunteer->getOrganization(), $volunteer->getDepartment(), $volunteer->getGroupnumber(),$currentTime,null,$volunteer->getlocation()];

        try {
            $this->driver->prepare(
                'INSERT INTO volunteers(email, firstname, lastname, organization, department, groupnumber,login,logout,location) 
                  VALUES (?,?,?,?,?,?,?,?,?)'
            )->execute($data);
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param \RoadHome\Domain\StringLiteral $id
     * @return $this
     */
    public function delete(StringLiteral $id)
    {

        try {
            $this->driver->prepare(
                'DELETE FROM volunteers WHERE id = $id'
            )->execute($id);
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $this;
    }
    /**
     * @return array
     */
    public function findAll()
    {
        $data = ["Error"];
        try {
            $stm = $this->driver->prepare(
                'SELECT * FROM volunteers'
            );

            $stm->execute();
            $data = $stm->fetchAll();
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $data;
    }
    /**
     * @param StringLiteral $fragment
     * @return array
     */
    public function findByEmail(StringLiteral $fragment)
    {
        $data = null;

        try {
            $statement = $this->driver->prepare('SELECT * FROM volunteers WHERE email = ?');
            $statement->execute([$fragment]);
            $data = $statement->fetchAll();
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {
            }else{
                throw $e;
            }
        }
        return $data;
    }
    /**
     * @param StringLiteral $id
     * @return \RoadHome\Domain\Volunteer
     */
    public function findById(StringLiteral $id)
    {
        $data = null;
        try {
            $statement = $this->driver->prepare('SELECT * FROM volunteers WHERE id = ?');
            $statement->execute([$id]);
            $data = $statement->fetchAll();
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {
            }else{
                throw $e;
            }
        }
        return $data;
    }

    /**
     * TODO: this needs to be addressed for sure I believe this was used to sync reddis and mysql
     * @return bool
     */
    public function save()
    {
        return true;
    }

    /**
     * @param \RoadHome\Domain\Volunteer $volunteer
     * @modified 11/3/2016 by Mark Richardson (changed this function so update will logout)
     * @afterModification NOT TESTED
     * @return $this
     */

    public function update(Volunteer $volunteer)
    {
        $currentTime = Date('Y-m-d H:i:s');
        $data = [$currentTime,$volunteer->getEmail(), null];

        try {
            $this->driver->prepare(
                'UPDATE FROM volunteers SET logout = ? WHERE email = ? AND logout = ?'
            )->execute($data);
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param StringLiteral $fragment
     * @return $this
     */
    public function findByOrganization(StringLiteral $fragment)
    {
        $data = null;

        try {
            $data = $this->driver->prepare('SELECT * FROM volunteers WHERE organization = $fragment')->fetchAll();
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {
            }else{
                throw $e;
            }
        }
        return $data;
    }

    /**
     * @param StringLiteral $fragment
     * @return $this
     */
    public function findByDepartment(StringLiteral $fragment)
    {
        $data = null;
        try {
            $data = $this->driver->prepare('SELECT * FROM volunteers WHERE department = $fragment')->fetchAll();
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {
            }else{
                throw $e;
            }
        }
        return $data;
    }


}