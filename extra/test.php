<?php declare(strict_types=1);

namespace Test;

use DrMVC\Database;
use DrMVC\Config;
use DrMVC\DoctorineORM\Entity;
use DrMVC\DoctorineORM\Orm;

require_once __DIR__ . '/../vendor/autoload.php';

// Create config object and load database configuration
$config = new Config();
$config->load(__DIR__ . '/config.php');

// Open connection with database
$db = new Database($config);
// ORM feint - need not null argument from first instance
$instance = $db->getInstance('test_table')->getInstance();

$orm = new Orm('test_table', $instance);

$entity = new Entity();

$entity->setName('Kolya');
$entity->email = 'qweqwe';
$entity->setPassword('qwerty3');

$orm->saveEntity($entity);

if ($entity = $orm->findById(1)) {
    $entity->name = 'Pavel';
    $entity->email = 'pavel@mail.ru';
    $entity->setPassword('qwerty3');
    $orm->saveEntity($entity);
}

foreach ($orm->findAll() as $en) {
    echo '<pre>' . print_r($en->getName() . ' - ' . $en->getId(), true) . '</pre>';
    echo '<pre>' . print_r($orm->deleteEntity($en), true) . '</pre>';
}
