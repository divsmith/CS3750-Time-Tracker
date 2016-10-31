<?php
/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 10/18/16
 * Time: 11:29 PM
 */

namespace RoadHome\Domain;


interface VolunteerRepository
{

    /**
     * @param Volunteer $volunteer
     * @return mixed
     */
    public function add(Volunteer $volunteer);

    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param StringLiteral $id
     * @return mixed
     */
    public function findById(StringLiteral $id);

    /**
     * @param StringLiteral $fragment
     * @return mixed
     */
    public function findByEmail(StringLiteral $fragment);

    /**
     * @param StringLiteral $fragment
     * @return mixed
     */
    public function findByOrganization(StringLiteral $fragment);

    /**
     * @param StringLiteral $fragment
     * @return mixed
     */
    public function findByDepartment(StringLiteral $fragment);

    /**
     * @param Volunteer $volunteer
     * @return mixed
     */
    public function update(Volunteer $volunteer);

    /**
     * @param StringLiteral $id
     * @return mixed
     */
    public function delete(StringLiteral $id);

    /**
     * @return mixed
     */
    public function save();

}