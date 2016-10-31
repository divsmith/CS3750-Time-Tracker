<?php
/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 10/22/16
 * Time: 9:34 PM
 */

namespace RoadHome\Domain;

interface LoginRepository
{

    /**
     * @param $email
     * @param $id
     * @return mixed
     */
    public function add(StringLiteral $email, StringLiteral $id);

    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param Date $date
     * @return mixed
     */
    public function findByDate(Date $date);

    /**
     * @param StringLiteral $id
     * @return mixed
     */
    public function findById(StringLiteral $id);

    /**
     * @param \RoadHome\Domain\Volunteer $volunteer
     * @return mixed
     */
    public function findByVolunteerId(StringLiteral $volunteer_id);

    /**
     * @param StringLiteral $id
     * @return mixed
     */
    public function update(StringLiteral $id);

}