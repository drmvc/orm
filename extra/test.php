<?php declare(strict_types=1);

namespace DrMVC\DoctorineORM;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new Builder('test_table');

echo 'SELECT<br>---<br>';

$builder
    ->select()
    #->where(['id' => 1, 'name' => 'Vasya'])
    ->limit(10, 10);

echo $builder;
echo '<pre>' . print_r($builder->getPlaceholders(), true) . '</pre>';

echo 'INSERT<br>---<br>';

$builder
    ->insert([
        'name' => 'Vasya',
        'age' => 18,
        'time' => time()
    ]);

echo $builder;
echo '<pre>' . print_r($builder->getPlaceholders(), true) . '</pre>';

echo 'UPDATE<br>---<br>';

$builder
    ->update([
        'name' => 'Vasya',
        'age' => 18,
        'time' => time()
    ])
    ->byId(11);

echo $builder;
echo '<pre>' . print_r($builder->getPlaceholders(), true) . '</pre>';

echo 'DELETE<br>---<br>';

$builder->delete(['name' => 'Vasya']);

echo $builder;
echo '<pre>' . print_r($builder->getPlaceholders(), true) . '</pre>';

#echo '<pre>' . print_r($builder, true) . '</pre>';
