<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

/**
 * Class Entity
 * @package DrMVC\DoctorineORM
 *
 * @property int id
 */
class Entity
{
    /**
     * Magic container
     *
     * @var array
     */
    private $_data;

    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->_data[$name] ?? null;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)//: void 7.1
    {
        $this->_data[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->_data[$name]);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name)//: void 7.1
    {
        unset($this->_data[$name]);
    }

    /**
     * Magic setters and getters
     *
     * @param $name
     * @param $arguments
     * @return bool|mixed|null
     */
    public function __call($name, $arguments)
    {
        $action = substr($name, 0, 3);
        $property = strtolower(substr($name, 3));
        switch ($action) {
            case 'get':
                return $this->$property;
                break;

            case 'set':
                $this->$property = $arguments[0];
                break;

            default :
                return false;
        }
    }
}
