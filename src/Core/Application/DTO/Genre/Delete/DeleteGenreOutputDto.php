<?php

namespace Core\Application\DTO\Genre\Delete;

class DeleteGenreOutputDto
{
    public function __construct(
        public bool $success
    ) { }
}
