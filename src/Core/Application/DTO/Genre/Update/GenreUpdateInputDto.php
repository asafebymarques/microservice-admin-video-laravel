<?php

namespace Core\Application\DTO\Genre\Update;

class GenreUpdateInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
    ) { }
}
