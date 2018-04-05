<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use InvalidArgumentException;
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
     * @param Builder $builder
     */
    private function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return Builder
     */
    private function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * @param PDO $instance
     */
    private function setInstance(PDO $instance)
    {
        $this->pdo = $instance;
    }

    /** Save or update
     * @param Entity $entity
     * @throws InvalidArgumentException
     */
    public function saveEntity(Entity $entity)
    {
        $data = $entity->getData();

        $id = (int)$entity->id;
        if (0 === $id) {
            $this->exec((string)$this->getBuilder()->insert($data), $this->getBuilder()->getPlaceholders());
        } else {
            if ($this->findById($id)) {
                $this->exec((string)$this->getBuilder()->update($data)->byId($id), $this->getBuilder()->getPlaceholders());
            } else {
                throw new InvalidArgumentException('Form id does not exist');
            }
        }
    }

    public function deleteEntity(Entity $entity)
    {

    }

    public function execRaw(string $sql)
    {

    }

    /**
     * @param int $id
     * @return Entity
     */
    public function findById(int $id): Entity
    {
        $entity = $this->getEntity(
            (string)$this->getBuilder()->select()->byId($id),
            $this->getBuilder()->getPlaceholders()
        );

        return $entity[0];
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
        while ($entity = $stmt->fetchObject(Entity::class)) {
            $result[] = $entity;
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
