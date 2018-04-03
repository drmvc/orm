<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new Builder('test_table');

echo 'SELECT<br>';

$builder
    ->select()
    #->where(['id' => 1, 'name' => 'Vasya'])
    ->byId(1);

echo $builder;

echo '<pre>' . print_r($builder->getPlaceholders(), true) . '</pre>';
