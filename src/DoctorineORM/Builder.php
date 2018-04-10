<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use DrMVC\DoctorineORM\Interfaces\BuilderInterface;

class Builder implements BuilderInterface
{

    private $_sql;

    private $_placeholders = [];

    private $_table;

    private $_where = [];

    private $_limit;

    private $_offset;

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

        return $sql;
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
            . ($this->_limit ? 'LIMIT ' . $this->_limit . ' OFFSET ' . $this->_offset : '')
        );
    }

    /**
     * @param string $sql
     */
    private function setSql(string $sql)
    {
        $this->_sql = $sql;
    }

    /**
     * @return string
     */
    private function getSql(): string
    {
        return $this->_sql;
    }

    /**
     * @return string
     */
    private function prepareWhere(): string
    {
        if (\count($this->_where)) {
            $placeholders = $this->map($this->_where, ':where');
            $where = $this->keyValueFormat($this->_where, $placeholders);
            $this->setPlaceholders($this->_where, 'where');

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
        $this->_placeholders += $this->map($placeholders, $prefix);
    }

    /**
     * @return void
     */
    private function clean()
    {
        $this->_sql = null;
        $this->_where = [];
        $this->_limit = null;
        $this->_offset = null;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return BuilderInterface
     */
    public function limit(int $limit, int $offset = 0): BuilderInterface
    {
        $this->_limit = $limit;
        $this->_offset = $offset;

        return $this;
    }

    /**
     * Allias method for where with id argument
     *
     * @param int $id
     * @return BuilderInterface
     */
    public function byId(int $id): BuilderInterface
    {
        $this->_where = ['id' => $id];

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
        $this->_where += $where;

        return $this;
    }

    /**
     * @return string
     */
    protected function getTable(): string
    {
        return $this->_table;
    }

    /**
     * @param string $table
     * @return void
     */
    public function setTable(string $table)
    {
        $this->_table = $table;
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
        $placeholders = $this->_placeholders;
        // clean placeholders
        $this->_placeholders = [];

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
