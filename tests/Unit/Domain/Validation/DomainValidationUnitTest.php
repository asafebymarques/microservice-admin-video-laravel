<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidateException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th);
        }
    }

    public function testNotNullCustomMessageException()
    {
        try {
            $value = '';
            DomainValidation::notNull($value, 'should not be null');

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th, 'should not be null');
        }
    }

    public function testStrMaxLength()
    {
        try {
            $value = 'Teste';
            DomainValidation::strMaxLength($value, 3, 'Custom Message');

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th, 'Custom Message');
        }
    }

    public function testStrMinLength()
    {
        try {
            $value = 'T';
            DomainValidation::strMinLength($value, 2, 'Custom Message');

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th, 'Custom Message');
        }
    }

    public function testStrCanNullAndMaxLength()
    {
        try {
            $value = 'teste';
            DomainValidation::strCanNullAndMaxLength($value, 3, 'Custom Message');

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidateException::class, $th, 'Custom Message');
        }
    }
}