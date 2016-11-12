<?php
/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 10/18/16
 * Time: 11:41 PM
 */

namespace RoadHome\Domain;


class VolunteerTest extends \PHPUnit_Framework_TestCase {


    public function testCreate(){
        //want to check to make sure object isn't null
        $result = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));
        $this->assertNotEquals($result,null);
    }

    public function testGetID(){
        //Because the id hasn't been set and won't be set until the DB is up and persisting
        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));

        $result = $volunteer->getId();
        $this->assertEquals($result,null);
    }

    public function testSetID(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));

        $volunteer->setId(new StringLiteral('1'));
        $result = $volunteer->getId();
        $this->assertEquals($result,'1');
    }

    public function testGetEmail(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));
        $result = $volunteer->getEmail();
        $this->assertEquals($result,'test@email.com');
    }

    public function testGetFirstname(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));

        $result = $volunteer->getFirstname();
        $this->assertEquals($result, 'firstname');
    }

    public function testGetLastname(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));

        $result = $volunteer->getLastname();
        $this->assertEquals($result,'lastname');

    }

    public function testGetOrganization(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));

        $result = $volunteer->getOrganization();
        $this->assertEquals($result,'organization');

    }

    public function testGetDepartment(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));
        $result = $volunteer->getDepartment();
        $this->assertEquals($result,'department');

    }

    public function testGetGroupnumber(){

        $volunteer = new Volunteer(new StringLiteral('test@email.com'),
            new StringLiteral('firstname'),
            new StringLiteral('lastname'),
            new StringLiteral('organization'),
            new StringLiteral('department'),
            new StringLiteral('1'),
            new StringLiteral('location'));

        $result = $volunteer->getGroupnumber();
        $this->assertEquals($result, '1');

    }

}



