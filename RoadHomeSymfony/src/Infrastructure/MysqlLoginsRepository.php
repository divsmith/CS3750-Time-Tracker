<?php
/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 10/22/16
 * Time: 9:45 PM
 */

namespace RoadHome\Infrastructure;

/**
 * THIS CLASS HAS BEEN DEPRECATED AND IS NO LONGER USED
 */

use RoadHome\Domain\Date;
use RoadHome\Domain\LoginRepository;
use RoadHome\Domain\StringLiteral;
use RoadHome\Domain\Volunteer;

class MysqlLoginsRepository implements LoginRepository
{
    /**
     * @var \PDO $driver
     */
    protected $driver;

    /**
     * MysqlLoginsRepository constructor.
     * @param \PDO $driver
     */
    public function __construct(\PDO $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param Volunteer $volunteer
     * @return $this
     */
    public function add(StringLiteral $email, StringLiteral $id)
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $data = [$id,date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d H:i:s') ];

        //search the mysql DB to find the id for that email

        try{
            $this->driver->prepare(
                'INSERT INTO logins(volunteer_id, created_at, updated_at, login) VALUES(?,?,?,?)'
            )->execute($data);
        }catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * Selects all login entries in the table.
     * @return array
     */
    public function findAll()
    {
        $data = null;
        try {
            $stm = $this->driver->prepare(
                'SELECT * FROM logins'
            );

            $stm->execute();
            $data = $stm->fetchAll();
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                if ($e->getCode() === 1062) {
                    // Take some action if there is a key constraint violation, i.e. duplicate name
                } else {
                    throw $e;
                }
            }
        }
        return $data;
    }

    /**
     * @param Date $date the date you're looking for
     * @return array|null
     */
    public function findByDate(Date $date)
    {
        $data = null;
        $arrayData = [$date];

        try{
            $sql = $this->driver->prepare('SELECT * FROM logins WHERE login = ?');
            $sql->execute($arrayData);
            $data = $sql->fetchAll();
        }catch(\PDOException $e){
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $data;
    }

    /**
     * @param StringLiteral $id
     * @return array|null
     */
    public function findById(StringLiteral $id)
    {
        $data = null;
        $arrayData = [$id];

        try{
            $sql = $this->driver->prepare('SELECT * FROM logins WHERE id = ?');
            $sql->execute($arrayData);
            $data = $sql->fetchAll();

        }catch(\PDOException $e){
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $data;
    }

    /**
     * @param StringLiteral $volunteer_id
     * @return array|null
     */
    public function findByVolunteerId(StringLiteral $volunteer_id)
    {
        $data = null;
        $arrayData = [$volunteer_id];

        try{
            $sql = $this->driver->prepare('SELECT * FROM logins WHERE volunteer_id = ?');
            $sql->execute($arrayData);
            $data = $sql->fetchAll();

        }catch(\PDOException $e){
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $data;
    }

    /**
     * @param StringLiteral $id
     * @return $this
     */
    public function update(StringLiteral $id)
    {
        $date = date('Y-m-d H:i:s');
        $dateArray = [$date, $date, $id];

        try {
            $this->driver->prepare('UPDATE FROM logins SET (updated_at, logout) VALUES(?,?) WHERE id = ?')->execute($dateArray);
        }catch(\PDOException $e){
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }
        return $this;
    }

}