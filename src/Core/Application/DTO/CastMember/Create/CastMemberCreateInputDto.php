<?php

namespace Core\Application\DTO\CastMember\Create;

class CastMemberCreateInputDto
{
    public function __construct(
        public string $name,
        public int $type
    ) {}
}
