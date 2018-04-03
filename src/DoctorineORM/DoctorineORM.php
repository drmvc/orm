<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use DrMVC\DoctorineORM\Interfaces\PDODriverInterface;
use DrMVC\DoctorineORM\Entity;
use PDOStatement;

class DoctorineORM implements PDODriverInterface
{
    private $_table;

    private $_builder;

    public function __construct(string $table)
    {
        $this->_table = $table;
        $this->_builder = new Builder($this->_table);
    }

    public function exec(string $sql, array $placeholders): PDOStatement
    {
        $stmt = $this->getInstance()->prepare($sql);
        $stmt->execute($placeholders);
        return $stmt;
    }

    public function getEntity(string $sql, array $placeholders, string $table): array
    {
        $stmt = $this->exec($sql, $placeholders);
        $result = [];
        while ($entity = $stmt->fetchObject('Entity', [$table])) {
            $result[] = $entity;
        }

        return $result;
    }
}
