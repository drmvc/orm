<?php declare(strict_types=1);

namespace Test;

use DrMVC\Database;
use DrMVC\Config;
use DrMVC\Orm\Entity;
use DrMVC\Orm\Orm;
use DrMVC\Orm\Builder;

require_once __DIR__ . '/../vendor/autoload.php';

// Create config object and load database configuration
$config = new Config();
$config->load(__DIR__ . '/config.php');

// Open connection with database
$db = new Database($config);
$instance = $db->getInstance();

$orm = new Orm('test_table', $instance);

$entity = new Entity();

$entity->name = 'Kolya';
$entity->email = 'qweqwe';
$entity->password = 'qwerty3';

$orm->saveEntity($entity);

if ($entity = $orm->findById(1)) {
    $entity->name = 'Pavel';
    $entity->email = 'pavel@mail.ru';
    $entity->password = 'qwerty3';
    $orm->saveEntity($entity);
}

foreach ($orm->findAll() as $en) {
    echo '<pre>' . print_r($en->name . ' - ' . $en->id, true) . '</pre>';
    echo '<pre>' . print_r($orm->deleteEntity($en), true) . '</pre>';
}
