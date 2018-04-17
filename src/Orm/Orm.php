<?php declare(strict_types=1);

namespace DrMVC\Orm;

use PDO;
use PDOStatement;

class Orm
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * Orm constructor.
     * @param string $table
     * @param PDO $pdo
     */
    public function __construct(string $table, PDO $pdo)
    {
        $this->setInstance($pdo);
        $this->setBuilder(new Builder($table));
    }

    /**
     * @param PDO $instance
     */
    private function setInstance(PDO $instance)
    {
        $this->pdo = $instance;
    }

    /**
     * @param Builder $builder
     */
    private function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Save or update
     *
     * @param Entity $entity
     * @return PDOStatement
     */
    public function saveEntity(Entity $entity): PDOStatement
    {
        $data = $entity->getData();
        $id = (int)$entity->id;
        if ($id > 0 && $this->findById($id)) {
            $result = $this->exec(
                (string)$this->getBuilder()->update($data)->byId($id),
                $this->getBuilder()->getPlaceholders()
            );
        } else {
            $result = $this->exec(
                (string)$this->getBuilder()->insert($data),
                $this->getBuilder()->getPlaceholders()
            );
        }

        return $result;
    }

    /**
     * @param int $id
     * @return Entity|null
     */
    public function findById(int $id)//: ?Entity
    {
        $entity = $this->getEntity(
            (string)$this->getBuilder()->select()->byId($id),
            $this->getBuilder()->getPlaceholders()
        );

        return $entity[0] ?? null;
    }

    /**
     * @param string $sql
     * @param array $placeholders
     * @return array
     */
    private function getEntity(string $sql, array $placeholders): array
    {
        $stmt = $this->exec($sql, $placeholders);
        $result = [];
        if ($stmt) {
            while ($entity = $stmt->fetchObject(Entity::class)) {
                $result[] = $entity;
            }
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array $placeholders
     * @return PDOStatement
     */
    private function exec(string $sql, array $placeholders): PDOStatement
    {
        $stmt = $this->getInstance()->prepare($sql);
        $stmt->execute($placeholders);

        return $stmt;
    }

    /**
     * @return PDO
     */
    private function getInstance(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return Builder
     */
    private function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * @param Entity $entity
     * @return int
     */
    public function deleteEntity(Entity $entity): int
    {
        return $this->exec((string)$this->getBuilder()->delete()->byId((int)$entity->getId()),
            $this->getBuilder()->getPlaceholders())->rowCount();
    }

    /** Array Entities
     * @return array
     */
    public function findAll(): array
    {
        return $this->getEntity(
            (string)$this->getBuilder()->select(),
            $this->getBuilder()->getPlaceholders()
        );
    }
}
