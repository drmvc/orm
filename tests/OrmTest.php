<?php declare(strict_types=1);

namespace DrMVC\Orm\Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use DrMVC\Orm;
use DrMVC\Orm\Entity;

class OrmTest extends TestCase
{

    private static $pdo;

    private $orm;

    /**
     * Test constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $sql = 'CREATE TABLE IF NOT EXISTS `test` (
          `id` INTEGER PRIMARY KEY,
          `name` varchar(255),
          `email` varchar(255),
          `password` varchar(32)
        )';
        $this->getPDO()->exec($sql);
        $this->orm = new Orm('test', $this->getPDO());
    }

    private function getPDO(): PDO
    {
        if (!self::$pdo) {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo = $pdo;
        }
        return self::$pdo;
    }

    public function test__construct()
    {
        $this->assertInstanceOf(Orm::class, $this->orm);
    }

    public function test_saveEntity()
    {
        $entity = new Entity();

        $entity->id = null;
        $entity->name = 'Kolya';
        $entity->email = 'qweqwe';
        $entity->password = 'qwerty';

        $count = $this->orm->saveEntity($entity);
        $this->assertEquals(1, $count->rowCount());
    }

    public function test_findById()
    {
        $entity = $this->orm->findById(1);
        $this->assertInstanceOf(Entity::class, $entity);
    }

    public function test_findAll()
    {
        $entity = $this->orm->findAll();
        $this->assertInternalType('array', $entity);
        $this->assertInstanceOf(Entity::class, $entity[0]);
    }

    public function test_deleteEntity()
    {
        $entity = $this->orm->findById(1);
        $this->assertEquals(1, $this->orm->deleteEntity($entity));
    }
}
