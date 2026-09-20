<?php

namespace App\Repository;

use App\Database\Database;
use App\Entity\User;
use PDO;

class UserRepository extends AbstractRepository {
    public function __construct() {

        parent::__construct(new Database());
    }

    public function findAll(): array
    {

        $statement = $this->pdo->prepare(" SELECT id, username, email, user_role, created_at FROM users");

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    public function findOneBy(array $criteria): array {

        $conditions = [];
        $parameters = [];

        foreach ($criteria as $field => $value) {
            $conditions[] = "$field = :$field";
            $parameters[$field] = $value;
        }

        $statement = $this->pdo->prepare(" SELECT id, username, email, user_role, password, created_at, updated_at FROM users WHERE " . implode(' AND ', $conditions) . "LIMIT 1");
        $statement->execute($parameters);

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function insert($objet): bool {

        $statement = $this->pdo->prepare(" INSERT INTO users (username, email, user_role ,password) VALUES (:username, :email, :role , :password)");

        return $statement->execute([
            'username' => $objet->getUsername(),
            'email' => $objet->getEmail(),
            'password' => $objet->getPassword(),
            'role' => $objet->getRole(),
        ]);
    }

    public function update($objet): bool {

        $statement = $this->pdo->prepare("UPDATE users SET username = :username, email = :email, user_role = :role ,password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id");

        return $statement->execute([
            'id' => $objet->getId(),
            'username' => $objet->getUsername(),
            'email' => $objet->getEmail(),
            'password' => $objet->getPassword(),
            'role' => $objet->getRole(),
        ]);

    }

    public function delete($objet): bool {

        $statement = $this->pdo->prepare(" DELETE FROM users WHERE id = :id");

        return $statement->execute(['id' => $objet->getId()]);
    }

    public function getUser(string $email): ?User {

        $statement = $this->pdo->prepare("SELECT * FROM users where email like :email");

        $statement->execute(["email" => $email,]);

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);

        $user = $statement->fetch();

        return $user ?: null;
    }

    
}