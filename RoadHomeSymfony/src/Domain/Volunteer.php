<?php

/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 10/18/16
 * Time: 11:12 PM
 * @modified 11/3/2016 by Mark Richardson (added the location field and getter/setter)
 */
namespace RoadHome\Domain;

use RoadHome\Domain\ValueObject;

/**
 * Class Volunteer
 *
 */
class Volunteer
{
    /**
     * @var StringLiteral $id
     */
    protected $id;
    /**
     * @var StringLiteral $email
     */
    protected $email;
    /**
     * @var StringLiteral $firstname
     */
    protected $firstname;
    /**
     * @var StringLiteral $lastname
     */
    protected $lastname;
    /**
     * @var StringLiteral $organization
     */
    protected $organization;
    /**
     * @var StringLiteral $department
     */
    protected $department;
    /**
     * @var StringLiteral $groupnumber
     */
    protected $groupnumber;
    /**
     * @var StringLiteral $location
     */
    protected $location;

    public function __Construct(
        StringLiteral $email,
        StringLiteral $firstname,
        StringLiteral $lastname,
        StringLiteral $organization,
        StringLiteral $department,
        StringLiteral $groupnumber,
        StringLiteral $lcoation

    ){
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->organization = $organization;
        $this->department = $department;
        $this->groupnumber = $groupnumber;
        $this->location = $lcoation;
    }

    /**
     * @return mixed $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getGroupnumber()
    {
        return $this->groupnumber;
    }

    /**
     * @param mixed $groupnumber
     */
    public function setGroupnumber($groupnumber)
    {
        $this->groupnumber = $groupnumber;
    }

    /**
     * @return $location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param StringLiteral $location
     */
    public function setLocation(StringLiteral $location)
    {
        $this->location = $location;
    }

}
