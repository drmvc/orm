<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

class Builder// implements BuilderInterface
{
    private $_sql;

    private $_placeholders = [];

    private $_table;

    private $_where = [];

    private $_limit;

    private $_offset;

    public function __construct(string $table = null)//: ?string 7.1 // null|string
    {
        $this->setTable($table);
    }

    public function __toString(): string
    {
        $this->prepareSql();
        $sql = $this->getSql();
        $this->clean();

        return $sql;
    }

    private function prepareSql()//:void 7.1
    {
        $this->setSql(
            $this->getSql()
            . $this->prepareWhere()
            . ($this->_limit ? 'LIMIT ' . $this->_limit . ' OFFSET ' . $this->_offset : '')
        );
    }

    private function setSql(string $sql)//: void 7.1
    {
        $this->_sql = $sql;
    }

    private function getSql()
    {
        return $this->_sql;
    }

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

    private function map(array $array, string $prefix): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$prefix . '_' . $key] = $value;
        }

        return $result;
    }

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

    private function setPlaceholders(array $placeholders, string $prefix)//: void 7.1
    {
        $this->_placeholders += $this->map($placeholders, $prefix);
    }

    private function clean()//: void 7.1
    {
        #$this->__table;
        $this->_sql = null;
        $this->_where = [];
        $this->_limit = null;
        $this->_offset = null;
    }

    protected function limit(int $limit, int $offset = 0): Builder
    {
        $this->_limit = $limit;
        $this->_offset = $offset;

        return $this;
    }

    protected function byId(int $id): Builder
    {
        $this->_where = ['id' => $id];

        return $this;
    }

    protected function select(array $where = []): Builder
    {
        $this->where($where);
        $this->setSql('SELECT * FROM ' . $this->getTable() . ' ');

        return $this;
    }

    protected function where(array $where): Builder
    {
        $this->_where += $where;

        return $this;
    }

    protected function getTable(): string
    {
        return $this->_table;
    }

    protected function setTable(string $table)//: void 7.1
    {
        $this->_table = $table;
    }

    protected function update(array $data, array $where = []): Builder
    {
        $this->where($where);
        $placeholders = $this->map($data, ':update');
        $this->setSql('UPDATE ' . $this->getTable() . ' SET ' . implode(', ',
                $this->keyValueFormat($data, $placeholders)) . ' ');
        $this->setPlaceholders($data, 'update');

        return $this;
    }

    protected function getPlaceholders(): array
    {
        $placeholders = $this->_placeholders;
        // clean placeholders
        $this->_placeholders = [];

        return $placeholders;
    }

    protected function insert(array $data): Builder
    {
        $placeholders = $this->map($data, ':insert');
        $this->setSql('INSERT INTO ' . $this->getTable() . ' (' . implode(', ',
                array_keys($data)) . ') VALUES (' . implode(', ', array_keys($placeholders)) . ') ');
        $this->setPlaceholders($data, 'insert');

        return $this;
    }

    protected function delete(array $where = []): Builder
    {
        $this->where($where);
        $this->setSql('DELETE FROM ' . $this->getTable() . ' ');

        return $this;
    }
}
