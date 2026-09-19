<?php

namespace App\Repository;

use App\Database\Database;

abstract class AbstractRepository {

    protected $pdo = null;

    public function __construct(Database $database){ 
        $this->pdo = $database->getConnection();
     }

    abstract public function findAll(): array;

    abstract public function findOneBy(array $criteria): array;

    abstract public function insert($objet): bool;

    abstract public function update($objet): bool;
    abstract public function delete($objet): bool;
}