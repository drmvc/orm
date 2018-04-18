[![Latest Stable Version](https://poser.pugx.org/drmvc/orm/v/stable)](https://packagist.org/packages/drmvc/orm)
[![Build Status](https://travis-ci.org/drmvc/orm.svg?branch=master)](https://travis-ci.org/drmvc/orm)
[![Total Downloads](https://poser.pugx.org/drmvc/orm/downloads)](https://packagist.org/packages/drmvc/orm)
[![License](https://poser.pugx.org/drmvc/orm/license)](https://packagist.org/packages/drmvc/orm)
[![PHP 7 ready](https://php7ready.timesplinter.ch/drmvc/orm/master/badge.svg)](https://travis-ci.org/drmvc/orm)
[![Code Climate](https://codeclimate.com/github/drmvc/orm/badges/gpa.svg)](https://codeclimate.com/github/drmvc/orm)
[![Scrutinizer CQ](https://scrutinizer-ci.com/g/drmvc/orm/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drmvc/orm/)
[![Code Coverage](https://scrutinizer-ci.com/g/drmvc/orm/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drmvc/orm/?branch=master)

# DrMVC\Orm

Simple and easy-to-use lightweight ORM.

    composer require drmvc/orm

## Install

composer require drmvc/orm

## How to use

```php

<?php

use DrMVC\Database;
use DrMVC\Config;
use DrMVC\Orm\Entity;
use DrMVC\Orm\Orm;

require_once __DIR__ . '/../vendor/autoload.php';

// Create config object and load database configuration
$config = new Config();
$config->load(__DIR__ . '/config.php');

// Open connection with database
$db = new Database($config);
$instance = $db->getInstance();

$orm = new Orm('test_table', $instance);

$entity = new Entity();

$entity->setName('Kolya');
$entity->email = 'qweqwe';
$entity->setPassword('qwerty3');

$orm->saveEntity($entity);

// if find, update data
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
```
