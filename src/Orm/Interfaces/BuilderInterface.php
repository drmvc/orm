<?php declare(strict_types=1);

namespace DrMVC\Orm\Interfaces;

interface BuilderInterface
{
    /**
     * @param array $where
     * @return BuilderInterface
     */
    public function select(array $where = []): BuilderInterface;

    /**
     * @param array $data
     * @param array $where
     * @return BuilderInterface
     */
    public function update(array $data, array $where = []): BuilderInterface;

    /**
     * @param array $where
     * @return BuilderInterface
     */
    public function delete(array $where): BuilderInterface;

    /**
     * @param array $data
     * @return BuilderInterface
     */
    public function insert(array $data): BuilderInterface;

    /**
     * @param array $where
     * @return BuilderInterface
     */
    public function where(array $where): BuilderInterface;

    /**
     * @param int $id
     * @return BuilderInterface
     */
    public function byId(int $id): BuilderInterface;

    /**
     * @return string
     */
    public function __toString(): string;
}
