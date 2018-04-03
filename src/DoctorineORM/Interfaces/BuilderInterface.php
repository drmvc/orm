<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM\Interfaces;

interface BuilderInterface
{
    public function select(array $where = []): BuilderInterface;

    public function update(array $data, array $where = []): BuilderInterface;

    public function delete(array $where): BuilderInterface;

    public function insert(array $data): BuilderInterface;
}