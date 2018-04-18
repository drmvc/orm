<?php

namespace DrMVC\Orm\Tests;

use PHPUnit\Framework\TestCase;
use DrMVC\Orm\Entity;

class EntityTest extends TestCase
{
    public function test__construct()
    {
        try {
            $obj = new Entity();
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(Entity::class, $obj);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function test__get()
    {
        $obj = new Entity();
        $this->assertEmpty($obj->id);
    }

    public function test__set()
    {
        $obj = new Entity();
        $obj->id = 123;
        $this->assertEquals(123, $obj->id);
    }

    public function test__call()
    {
        $obj = new Entity();
        $obj->dummy = 'dummy';
        $this->assertEquals('dummy', $obj->getDummy());
    }

    public function testGetId()
    {
        $obj = new Entity();
        $obj->id = 234;
        $this->assertEquals(234, $obj->getId());
    }

    public function test__isset()
    {
        $obj = new Entity();
        $this->assertFalse(isset($obj->id));
        $obj->id = 345;
        $this->assertTrue(isset($obj->id));
    }

    public function test__unset()
    {
        $obj = new Entity();
        $obj->id = 345;
        $this->assertNotEmpty($obj->id);

        unset($obj->id);
        $this->assertEmpty($obj->id);
    }

    public function testGetData()
    {
        $obj = new Entity();
        $obj->id = 456;
        $data = $obj->getData();

        $this->assertInternalType('array', $data);
        $this->assertEquals(456, $data['id']);
    }

}
