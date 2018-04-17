<?php declare(strict_types=1);

namespace DrMVC\Orm;

use PDO;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{

    private $pdo;

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
        $this->assertInstanceOf(Orm::class, $this->orm);
    }

    public function getPDO(): PDO
    {
        if (!$this->pdo) {
            $pdo = new PDO('sqlite:' . __DIR__ . '/test.db');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    public function test_insert()
    {
        $entity = new Entity();

        $entity->id = null;
        $entity->setName('Kolya');
        $entity->email = 'qweqwe';
        $entity->setPassword('qwerty');

        $count = $this->orm->saveEntity($entity);
        $this->assertEquals(1, $count->rowCount());
    }

    public function test_getById()
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

    public function test_delete()
    {
        $entity = $this->orm->findById(1);
        $this->assertEquals(1, $this->orm->deleteEntity($entity));
    }
}
