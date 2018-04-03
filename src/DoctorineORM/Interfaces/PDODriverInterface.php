<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM\Interfaces;

use PDO;

interface PDODriverInterface
{
    /**
     * Get current connection
     *
     * @return  PDO
     */
    public function getInstance(): PDO;
}