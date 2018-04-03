<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

use DrMVC\DoctorineORM\Interfaces\PDODriverInterface;
use PDO;
use PDOException;

class PseudoPDOClass implements PDODriverInterface
{

    /**
     * @var PDO
     */
    private $_instance;

    public function __construct(array $config)
    {
        $this->connect($config);
    }

    private function connect(array $config)
    {
        try {
            $this->_instance = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['name'], $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    #PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                ]
            );
        } catch (PDOException $e) {
            // error
        }
    }

    public function getInstance(): PDO
    {
        return $this->_instance;
    }
}