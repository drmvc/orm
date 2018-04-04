<?php declare(strict_types=1);

namespace Test;

use DrMVC\DoctorineORM\Orm;

require_once __DIR__ . '/../vendor/autoload.php';

$config = include('config.php');

$pdo = new \PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'],
    $config['password'],
    [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        #PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
        \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
    ]
);

$orm = new Orm('test_table', $pdo);

#echo '<pre>' . print_r($orm, true) . '</pre>';

$entity = $orm->findById(1);

echo '<pre>' . print_r($entity, true) . '</pre>';

$entity = $orm->findAll();

echo '<pre>' . print_r($entity, true) . '</pre>';

