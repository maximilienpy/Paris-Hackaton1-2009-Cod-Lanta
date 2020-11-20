<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class OwnerManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'owner';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $owner
     * @return int
     */
    public function insert(array $owner): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
         " (`firstname`) VALUES (:firstname)");
        $statement->bindValue('firstname', $owner['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $owner['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('description', $owner['description'], \PDO::PARAM_STR);
        $statement->bindValue('picture', $owner['picture'], \PDO::PARAM_STR);
        $statement->bindValue('castle', $owner['castle'], \PDO::PARAM_STR);


        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $owner
     * @return bool
     */
    public function update(array $owner):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE 
        . " SET `firstname` = :firstname,
        `lastname` = :lastname
        `description` = :description
        `picture` = :picture
        `castle` = :castle
        WHERE id=:id");
        $statement->bindValue('id', $owner['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $owner['title'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}

