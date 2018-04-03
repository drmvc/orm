<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM\Interfaces;

interface BuilderInterface
{
    public function select(array $where = []): BuilderInterface;

    public function update(array $data, array $where = []): BuilderInterface;

    public function delete(array $where): BuilderInterface;

    public function insert(array $data): BuilderInterface;

    public function where(array $where): BuilderInterface;

    public function byId(int $id): BuilderInterface;

    public function setTable(string $table);

    public function limit(int $limit): BuilderInterface;



    public function __toString(): string;
}
