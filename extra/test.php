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
$instance = $db->getInstance('PDO')->getInstance();

$orm = new Orm('test_table', $instance);

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
