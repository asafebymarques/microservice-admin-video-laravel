<?php

namespace Core\Application\DTO\CastMember\List;

class ListCastMembersInputDto
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPerPage = 15,
    ) {}
}
