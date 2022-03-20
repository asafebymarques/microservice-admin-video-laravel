<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidateException;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{
    public function testAttributes() 
    {
        $category = new Category(
            name: 'New Category',
            description: 'New desc',
            isActive: true
        );

        $this->assertNotEmpty($category->id());
        $this->assertEquals('New Category', $category->name);
        $this->assertEquals('New desc', $category->description);
        $this->assertTrue($category->isActive); 
        $this->assertNotEmpty($category->createdAt());  
    }

    public function testActivated()
    {
        $category = new Category(
            name: 'New Category',
            isActive: false
        );

        $this->assertFalse($category->isActive);
        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new Category(
            name: 'New Category'
        );

        $this->assertTrue($category->isActive);
        $category->disable();
        $this->assertFalse($category->isActive);
    }

    public function testUpdate()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $category = new Category(
            id: $uuid,
            name: 'New Category',
            description: 'New desc',
            isActive: true,
            createdAt: '2023-01-01 12:12:12'
        );

        $category->update(
            name: 'New Category Name',
            description: 'New Description',
        );

        $this->assertEquals($uuid, $category->id());
        $this->assertEquals('New Category Name', $category->name);
        $this->assertEquals('New Description', $category->description);   
    }

    public function testExceptionName()
    {
        try {
            new Category(
                name: 'Na',
                description: 'New Description',
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th);
        }
    }

    public function testExceptioDescription()
    {
        try {
            new Category(
                name: 'Name Category',
                description: random_bytes(99999),
            );

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th);
        }
    }
}