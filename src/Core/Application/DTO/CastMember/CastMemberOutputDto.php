<?php

namespace Core\Application\DTO\CastMember;

class CastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $created_at,
    ) {}
}
