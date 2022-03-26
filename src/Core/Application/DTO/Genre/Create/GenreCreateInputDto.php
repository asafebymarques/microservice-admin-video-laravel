<?php

namespace Core\Application\DTO\Genre\Create;

class GenreCreateInputDto
{
    public function __construct(
        public string $name,
        public array $categoriesId = [],
        public bool $isActive = true,
    ) { }
}
