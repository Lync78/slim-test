<?php

namespace App\Repository;

use App\Database\Database;

abstract class AbstractRepository {

    public function __construct(protected Database $database){  }

    abstract public function findAll(): array;

    abstract public function findOneBy(array $criteria): array;

    abstract public function insert($objet): bool;

    abstract public function update($objet): bool;
    abstract public function delete($objet): bool;
}