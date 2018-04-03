<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM\Traits;

trait Magic
{
    private $data;

    public function __set(string $name, $value)//: void 7.1
    {
        $this->data[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __unset(string $name)//: void 7.1
    {
        unset($this->data[$name]);
    }

    public function __call($name, $arguments) {
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
