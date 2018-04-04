<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use InvalidArgumentException;
use PDO;
use PDOStatement;

class Orm extends Builder
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Orm constructor.
     * @param string $table
     * @param PDO $pdo
     */
    public function __construct(string $table, PDO $pdo)
    {
        $this->setTable($table);
        $this->setInstance($pdo);
        parent::__construct($this->getTable());
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
            $this->exec((string)$this->insert($data), $this->getPlaceholders());
        } else {
            if ($this->findById($id)) {
                $this->exec((string)$this->update($data)->byId($id), $this->getPlaceholders());
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
            (string)$this->select()->byId($id),
            $this->getPlaceholders()
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
            (string)$this->select(),
            $this->getPlaceholders()
        );
    }
}
