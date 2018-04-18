<?php declare(strict_types=1);

namespace DrMVC\Orm\Tests;

use PHPUnit\Framework\TestCase;
use DrMVC\Orm\Builder;

class BuilderTest extends TestCase
{
    private static $builder;

    private function getBuilder()
    {
        if (!self::$builder) {
            self::$builder = new Builder('test');
        }
        return self::$builder;
    }

    public function test__construct()
    {
        try {
            $this->assertInternalType('object', $this->getBuilder());
            $this->assertInstanceOf(Builder::class, $this->getBuilder());
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function test_select()
    {
        $this->assertSame('SELECT * FROM test', self::$builder->select()->__toString());
    }

    public function test_update()
    {
        $this->assertSame('UPDATE test SET productId = :update_productId, name = :update_name WHERE id = :where_id', self::$builder->update(['productId' => 11, 'name' => 'test'], ['id' => 1])->__toString());
    }

    public function test_insert()
    {
        $this->assertSame('INSERT INTO test (productId, name) VALUES (:insert_productId, :insert_name)', self::$builder->insert(['productId' => 11, 'name' => 'test'])->__toString());
    }

    public function test_delete()
    {
        $this->assertSame('DELETE FROM test WHERE productId = :where_productId AND name = :where_name', self::$builder->delete(['productId' => 11, 'name' => 'test'])->__toString());
    }

    public function test_byId()
    {
        $this->assertSame('SELECT * FROM test WHERE id = :where_id', self::$builder->select()->byId(1)->__toString());
    }

    public function test_where()
    {
        $this->assertSame('SELECT * FROM test WHERE id = :where_id', self::$builder->select()->where(['id' => 1])->__toString());
    }

    public function test_getPlaceholders()
    {
        self::$builder->getPlaceholders(); // clean placeholders
        self::$builder->update(['productId' => 11, 'name' => 'test'], ['id' => 1]);
        self::$builder->__toString(); // prepare
        $array = self::$builder->getPlaceholders();
        $this->assertInternalType('array', $array);
        $testArray = ['update_productId' => 11, 'update_name' => 'test', 'where_id' => 1];
        $this->assertEquals($testArray, $array, 'Array placeholders');
    }
}
