<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use DrMVC\DoctorineORM\Traits\Magic;

class Entity
{
    use Magic;

    private $_table;

    public function __construct(string $table)
    {
        $this->_table = $table;
    }
}
