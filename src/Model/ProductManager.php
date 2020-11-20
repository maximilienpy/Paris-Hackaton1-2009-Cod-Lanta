<?php

namespace App\Model;

class ProductManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'product';
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
    /**
     * @param array $product
     * @return int
     */
    public function insert(array $product): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . "
            (`name`,`function`,`quantity`,`picture`, `durability`, `owner_id`)
            VALUES
            (:name, :function, :quantity, :picture, :durability, :owner_id)"
        );
        $statement->bindValue('name', $product['name'], \PDO::PARAM_STR);
        $statement->bindValue('function', $product['function'], \PDO::PARAM_STR);
        $statement->bindValue('quantity', $product['quantity'], \PDO::PARAM_INT);
        $statement->bindValue('picture', $product['picture'], \PDO::PARAM_INT);
        $statement->bindValue('durability', $product['durability'], \PDO::PARAM_STR);
        $statement->bindValue('owner_id', $product['owner_id'], \PDO::PARAM_STR);
        if ($statement->execute()) {
            return (int) $this->pdo->lastInsertId();
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
     * @param array $product
     * @return bool
     */
    public function update(array $product): bool
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `name` = :name,
            `function` = :function,
            `quantity` = :quantity,
            `picture` = :picture,
            `durability` = :durability,
            `owner_id` = :owner_id
            WHERE id=:id"
        );
        $statement->bindValue('id', $product['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $product['name'], \PDO::PARAM_STR);
        $statement->bindValue('function', $product['function'], \PDO::PARAM_STR);
        $statement->bindValue('quantity', $product['quantity'], \PDO::PARAM_INT);
        $statement->bindValue('picture', $product['picture'], \PDO::PARAM_INT);
        $statement->bindValue('durability', $product['durability'], \PDO::PARAM_INT);
        $statement->bindValue('owner_id', $product['owner_id'], \PDO::PARAM_INT);
        return $statement->execute();
    }
    
    public function selectAllWithDetail(): array
    {
        $products = $this->pdo->query("SELECT
        product.id,
        product.name,
        product.function,
        product.quantity,
        product.picture,
        product.durability,
        product.owner_id,
        FROM $this->table
        INNER JOIN owner ON product.owner_id=owner.id")->fetchAll();
            
            return $products;
            ////INNER JOIN a voir avec ali
    }


    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT
        product.id,
        product.name,
        product.function,
        product.quantity,
        product.picture,
        product.durability,
        product.owner_id,
        FROM $this->table
        INNER JOIN owner ON product.owner_id=owner.id
        WHERE product.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $product = $statement->fetch();
        return $product;
    }

  

     // PRIVATES METHODS
}