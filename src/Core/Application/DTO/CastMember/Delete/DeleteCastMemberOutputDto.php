<?php

namespace Core\Application\DTO\CastMember\Delete;

class DeleteCastMemberOutputDto
{
    public function __construct(
        public bool $success
    ) {}
}
