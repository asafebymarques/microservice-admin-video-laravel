<?php

namespace Core\Application\DTO\CastMember\Update;

class CastMemberUpdateInputDto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
