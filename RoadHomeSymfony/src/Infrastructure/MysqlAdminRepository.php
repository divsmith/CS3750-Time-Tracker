<?php
/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 11/12/16
 * Time: 8:32 PM
 */

namespace RoadHome\Infrastructure;


use RoadHome\Domain\StringLiteral;

class MysqlAdminRepository
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
     * This Function will return a user if they admin and password exist and are correct
     * @param StringLiteral $username
     * @param StringLiteral $passwd
     * @return array
     */
    public function verifyCredentials(StringLiteral $username, StringLiteral $passwd){

        $data = [];

        try {
            $statement = $this->driver->prepare('SELECT * FROM admins WHERE username = ?, passphrase = ?');
            $statement->execute([$username,$passwd]);
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

}