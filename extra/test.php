<?php declare(strict_types=1);

namespace Test;

use DrMVC\DoctorineORM\Entity;
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

$entity = $orm->findById(1);

$entity->name = 'Pavel'; // =)
$entity->email = 'pavel@mail.ru';

$orm->saveEntity($entity);


$entity2 = new Entity();

$entity2->setName('Kolya');
//$entity2->email = '';
$entity2->setPassword('qwerty3');

$orm->saveEntity($entity2);

$entity3 = $orm->findAll();

foreach ($entity3 as $en) {
    echo '<pre>' . print_r($en->getData(), true) . '</pre>';
}
