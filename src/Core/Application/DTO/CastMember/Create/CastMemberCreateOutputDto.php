<?php

namespace Core\Application\DTO\CastMember\Create;

class CastMemberCreateOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $created_at,
    ) {}
}
