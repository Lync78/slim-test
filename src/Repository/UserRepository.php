<?php

namespace App\Repository;

use App\Database\Database;
use App\Entity\User;

class UserRepository extends AbstractRepository {
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function findAll(): array
    {
        $pdo = $this->database->getConnection();

        $statement = $pdo->prepare("
            SELECT id, username, email, created_at
            FROM users
        ");


        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    public function findOneBy(array $criteria)
    {
        $pdo = $this->database->getConnection();

        $conditions = [];
        $parameters = [];

        foreach ($criteria as $field => $value) {
            $conditions[] = "$field = :$field";
            $parameters[$field] = $value;
        }

        $sql = " SELECT id, username, email, password, created_at, updated_at FROM users WHERE " . implode(' AND ', $conditions) . "LIMIT 1";

        $statement = $pdo->prepare($sql);
        $statement->execute($parameters);

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function insert($objet): bool {
        $pdo = $this->database->getConnection();

        $statement = $pdo->prepare("
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
        ");

        return $statement->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);
    }

    public function update($objet): bool {
        $pdo = $this->database->getConnection();

        $statement = $pdo->prepare("
            UPDATE users SET username = :username, email = :email, password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id
        ");

        return $statement->execute([
            'id' => $user->getId()
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);

    }


    public function delete($objet): bool {
        $pdo = $this->database->getConnection();

        $statement = $pdo->prepare(" DELETE FROM users WHERE id = :id");

        return $statement->execute(['id' => $user->getId()]);
    }
}