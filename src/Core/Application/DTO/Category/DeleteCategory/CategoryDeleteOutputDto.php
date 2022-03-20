<?php

namespace Core\Application\DTO\Category\DeleteCategory;

class CategoryDeleteOutputDto
{
    public function __construct(
        public bool $success
    ) { }
}