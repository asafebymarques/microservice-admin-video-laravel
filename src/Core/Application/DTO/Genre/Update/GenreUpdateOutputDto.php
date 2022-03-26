<?php

namespace Core\Application\DTO\Genre\Update;

class GenreUpdateOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active = true,
        public string $created_at = '',
    ) { }
}
