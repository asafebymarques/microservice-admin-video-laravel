<?php

namespace Core\Application\Interfaces;

interface TransactionInterface
{
    public function commit();
    public function rollback();
}
