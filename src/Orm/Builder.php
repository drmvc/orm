<?php declare(strict_types=1);

namespace DrMVC\Orm;

use DrMVC\Orm\Interfaces\BuilderInterface;

class Builder implements BuilderInterface
{

    private $sql;

    private $placeholders = [];

    private $table;

    private $where = [];

    /**
     * Builder constructor.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->setTable($table);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->prepareSql();
        $sql = $this->getSql();
        $this->clean();

        return trim($sql);
    }

    /**
     * Build and set sql query
     *
     * @return void
     */
    private function prepareSql()
    {
        $this->setSql(
            $this->getSql()
            . $this->prepareWhere()
        );
    }

    /**
     * @param string $sql
     */
    private function setSql(string $sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return string
     */
    private function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return string
     */
    private function prepareWhere(): string
    {
        if (\count($this->where)) {
            $placeholders = $this->map($this->where, ':where');
            $where = $this->keyValueFormat($this->where, $placeholders);
            $this->setPlaceholders($this->where, 'where');

            return 'WHERE ' . implode(' AND ', $where) . ' ';
        }

        return '';
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     */
    private function map(array $array, string $prefix): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$prefix . '_' . $key] = $value;
        }

        return $result;
    }

    /**
     * @param array $keys
     * @param array $values
     * @return array
     */
    private function keyValueFormat(array $keys, array $values): array
    {
        return array_map(
            function (string $key, string $placeholder) {
                return sprintf('%s = %s', $key, $placeholder);
            },
            array_keys($keys),
            array_keys($values)
        );
    }

    /**
     * @param array $placeholders
     * @param string $prefix
     */
    private function setPlaceholders(array $placeholders, string $prefix)
    {
        $this->placeholders += $this->map($placeholders, $prefix);
    }

    /**
     * @return void
     */
    private function clean()
    {
        $this->sql = null;
        $this->where = [];
    }

    /**
     * Allias method for where with id argument
     *
     * @param int $id
     * @return BuilderInterface
     */
    public function byId(int $id): BuilderInterface
    {
        $this->where = ['id' => $id];

        return $this;
    }

    /**
     * @param array $where
     * @return BuilderInterface
     */
    public function select(array $where = []): BuilderInterface
    {
        $this->where($where);
        $this->setSql('SELECT * FROM ' . $this->getTable() . ' ');

        return $this;
    }

    /**
     * @param array $where
     * @return BuilderInterface
     */
    public function where(array $where): BuilderInterface
    {
        $this->where += $where;

        return $this;
    }

    /**
     * @return string
     */
    private function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return void
     */
    private function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * @param array $data
     * @param array $where
     * @return BuilderInterface
     */
    public function update(array $data, array $where = []): BuilderInterface
    {
        $this->where($where);
        $placeholders = $this->map($data, ':update');
        $this->setSql('UPDATE ' . $this->getTable() . ' SET ' . implode(', ',
                $this->keyValueFormat($data, $placeholders)) . ' ');
        $this->setPlaceholders($data, 'update');

        return $this;
    }

    /**
     * @return array
     */
    public function getPlaceholders(): array
    {
        $placeholders = $this->placeholders;
        // clean placeholders
        $this->placeholders = [];

        return $placeholders;
    }

    /**
     * @param array $data
     * @return BuilderInterface
     */
    public function insert(array $data): BuilderInterface
    {
        $placeholders = $this->map($data, ':insert');
        $this->setSql('INSERT INTO ' . $this->getTable() . ' (' . implode(', ',
                array_keys($data)) . ') VALUES (' . implode(', ', array_keys($placeholders)) . ') ');
        $this->setPlaceholders($data, 'insert');

        return $this;
    }

    /**
     * @param array $where
     * @return BuilderInterface
     */
    public function delete(array $where = []): BuilderInterface
    {
        $this->where($where);
        $this->setSql('DELETE FROM ' . $this->getTable() . ' ');

        return $this;
    }
}
