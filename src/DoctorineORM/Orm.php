<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use PDO;
use PDOStatement;

class Orm extends Builder
{
    /**
     * @var PDO
     */
    private $pdo;

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

    public function save(Entity $entity)
    {

    }

    public function findAll(): array
    {
        return $this->getEntity(
            $builder = (string)$this->select(),
            $this->getPlaceholders()
        );
    }

    private function getEntity(string $sql, array $placeholders): array
    {
        $stmt = $this->exec($sql, $placeholders);
        $result = [];
        while ($entity = $stmt->fetchObject(Entity::class, [$this->getTable()])) {
            $result[] = $entity;
        }

        return $result;
    }

    private function exec(string $sql, array $placeholders): PDOStatement
    {
        $stmt = $this->getInstance()->prepare($sql);
        $stmt->execute($placeholders);
        return $stmt;
    }

    private function getInstance(): PDO
    {
        return $this->pdo;
    }

    public function findById(int $id): array
    {
        return $this->getEntity(
            $builder = (string)$this->select()->byId($id),
            $this->getPlaceholders()
        );
    }
}
