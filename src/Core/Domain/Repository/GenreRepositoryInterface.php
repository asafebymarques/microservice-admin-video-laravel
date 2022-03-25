<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Genre;

interface GenreRepositoryInterface
{
    public function insert(Genre $genre) : Genre;
    public function findById(string $id) : Genre;
    public function findAll(string $filter = '', string $order = 'DESC') : array;
    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15) : PaginationInterface;
    public function update(Genre $genre) : Genre;
    public function delete(string $id) : bool;
}
