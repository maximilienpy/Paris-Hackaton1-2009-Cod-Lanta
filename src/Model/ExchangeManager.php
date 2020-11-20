<?php

namespace App\Model;
/**
 *
 */
class ExchangeManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'exchange';
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $exchangeDetail
     * @return int
     */
    public function insert(array $exchangeDetail): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . "
            (`created_at`, `owner_id`,`product_id`, `new_owner`)
            VALUES
            (:created_at, :owner_id, :product_id, :`new_owner`)"
        );
        $statement->bindValue('created_at', $exchangeDetail['created_at'], \PDO::PARAM_INT);
        $statement->bindValue('owner_id', $exchangeDetail['owner_id'], \PDO::PARAM_INT);
        $statement->bindValue('product_id', $exchangeDetail['product_id'], \PDO::PARAM_INT);
        $statement->bindValue('new_owner', $exchangeDetail['new_owner'], \PDO::PARAM_INT);
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
     * @param array $exchangeDetail
     * @return bool
     */
    public function update(array $exchangeDetail): bool
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `created_at` = :created_at,
            `owner_id,` = :owner_id,
            `product_id,` = :product_id,
            `new_owner,` = :new_owner
            WHERE id=:id"
        );
        $statement->bindValue('id', $exchangeDetail['id'], \PDO::PARAM_INT);
        $statement->bindValue('created_at', $exchangeDetail['created_at'], \PDO::PARAM_INT);
        $statement->bindValue('owner_id', $exchangeDetail['owner_id'], \PDO::PARAM_INT);
        $statement->bindValue('product_id', $exchangeDetail['product_id'], \PDO::PARAM_INT);
        $statement->bindValue('new_owner', $exchangeDetail['new_owner'], \PDO::PARAM_INT);
        
        return $statement->execute();
    }

    public function selectAllWithDetail(): array
    {
        return $this->pdo->query("SELECT
            exchange.id,
            exchange.created_at,
            exchange.owner_id,
            exchange.product_id,
            exchange.new_owner
            FROM exchange
            INNER JOIN `owner` ON exchangeDetail.exchange_id=`exchange`.id
            INNER JOIN product ON exchangeDetail.product_id=`exchange`.id")->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT
        exchangeDetail.id,
        exchangeDetail.quantity,
        exchangeDetail.total,
        `exchange`.reference as exchange_reference,
        `exchange`.created_at as exchange_created_at,
        exchangeDetail.exchange_id,
        product.name as product_name,
        product.quantity as product_quantity,
        exchangeDetail.product_id
        FROM $this->table
        INNER JOIN `exchange` ON exchangeDetail.exchange_id=`exchange`.id
        INNER JOIN product ON exchangeDetail.product_id=`exchange`.id
        WHERE exchangeDetail.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        
        return $statement->fetch();
    }
}