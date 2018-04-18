<?php declare(strict_types=1);

namespace DrMVC\Orm;

/**
 * Class Entity
 * @package DrMVC\Orm
 *
 * @property int id
 * @method Entity getId(): int
 */
class Entity
{
    /**
     * Magic container
     *
     * @var array
     */
    private $data = [];

    /**
     * Get all fields
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)//: void 7.1
    {
        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name)//: void 7.1
    {
        unset($this->data[$name]);
    }
}
