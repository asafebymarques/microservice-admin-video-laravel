<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember;
use Core\Domain\Exception\EntityValidateException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class CastMemberUnitTest extends TestCase
{
    public function testAttribuites()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $castMember = new CastMember(
            id: new Uuid($uuid),
            name: 'Name',
            type: CastMemberType::ACTOR,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $this->assertEquals($uuid, $castMember->id());
        $this->assertEquals('Name', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function testAttribuitesNewEntity()
    {
        $castMember = new CastMember(
            name: 'Name',
            type: CastMemberType::DIRECTOR,
        );

        $this->assertNotEmpty($castMember->id());
        $this->assertEquals('Name', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function testValidation()
    {
        $this->expectException(EntityValidateException::class);

        new CastMember(
            name: 'na',
            type: CastMemberType::DIRECTOR,
        );
    }

    public function testExceptionValidate()
    {
        $this->expectException(EntityValidateException::class);

        $castMember = new CastMember(
            name: 'name',
            type: CastMemberType::DIRECTOR,
        );

        $castMember->update(
            name: 'ne'
        );

        $this->assertEquals('new name', $castMember->name);
    }

    public function testUpdate()
    {
        $castMember = new CastMember(
            name: 'name',
            type: CastMemberType::DIRECTOR,
        );

        $castMember->update(
            name: 'new name'
        );

        $this->assertEquals('new name', $castMember->name);
    }
}
