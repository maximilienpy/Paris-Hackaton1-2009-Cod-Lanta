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
            product.function
            product.quantity,
            product.picture,
            product.durability,
            product.owner_id,
            
            
            FROM product
            INNER JOIN category ON product.category_id=category.id
            INNER JOIN size ON product.size_id=size.id")->fetchAll();
            
            return $this->getProductsPictures($products);
    }


    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT
        product.id,
        product.quantity,
        product.function,
        product.name,
        product.category_id,
        product.size_id,
        product.price,
        product.activated,
        product.function,
        category.name as category_name,
        size.number as size_number
        FROM $this->table
        INNER JOIN category ON product.category_id=category.id
        INNER JOIN size ON product.size_id=size.id
        WHERE product.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $product = $statement->fetch();

        $statementImg = $this->pdo->prepare('SELECT url FROM picture WHERE product_id=:product_id');
        $statementImg->bindValue('product_id', $id, \PDO::PARAM_INT);
        $statementImg->execute();
        $pictures = $statementImg->fetchAll();
        $product['pictures'] = $pictures;
        return $product;
    }

     // SEARCH
    public function searchBycategory(int $category_id): array
    {
        $statement = $this->pdo->prepare("SELECT
        product.id,
        product.quantity,
        product.name,
        product.category_id,
        product.size_id,
        product.price,
        product.function,
        category.name as category_name,
        size.number as size_number
        FROM $this->table
        JOIN category ON product.category_id=category.id
        JOIN size ON product.size_id=size.id
        WHERE category_id = :category_id ORDER BY category_name ASC");
        $statement->bindValue('category_id', $category_id, \PDO::PARAM_INT);
        $statement->execute();
        $products = $statement->fetchAll();
 
        
        return $this->getProductsPictures($products);
    }
 
    public function searchBySize(int $size_id): array
    {
        $statement = $this->pdo->prepare("SELECT
        product.id,
        product.quantity,
        product.name,
        product.category_id,
        product.size_id,
        product.price,
        product.function,
        category.name as category_name,
        size.number as size_number
        FROM $this->table
        JOIN category ON product.category_id=category.id
        JOIN size ON product.size_id=size.id
        WHERE size_id = :size_id ORDER BY size_number ASC");
        $statement->bindValue('size_id', $size_id, \PDO::PARAM_INT);
        $statement->execute();
        $products = $statement->fetchAll();
  
        return $this->getProductsPictures($products);
    }


     // PRIVATES METHODS
    private function getPictures(array $product)
    {
        $statementImg = $this->pdo->prepare('SELECT url FROM picture WHERE product_id=:product_id');
        $statementImg->bindValue('product_id', $product['id'], \PDO::PARAM_INT);
        $statementImg->execute();

        return $statementImg->fetchAll();
    }

    private function getProductsPictures(array $products)
    {
        $result = [];
        foreach ($products as $product) {
            $statementImg = $this->pdo->prepare('SELECT 
            url FROM picture WHERE product_id=:product_id');
            $statementImg->bindValue('product_id', $product['id'], \PDO::PARAM_INT);
            $statementImg->execute();
            $pictures = $statementImg->fetchAll();
            $product['pictures'] = $pictures;
            array_push($result, $product);
        }
        return $result;
    }
}
