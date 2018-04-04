<?php
/**
 * More details about PDO-MySQL driver:
 * @link http://php.net/manual/en/ref.pdo-mysql.connection.php
 */

return [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',         // optional, default: 127.0.0.1
    'port'      => '3306',              // optional, default: 3306
    'username'  => 'admin',
    'password'  => 'admin_pass',
    'dbname'    => 'database',
    'prefix'    => 'test_',             // optional, default: is empty
    'collation' => 'utf8_unicode_ci',   // optional, default: utf8_unicode_ci
    'charset'   => 'utf8',              // optional, default: utf8
];
