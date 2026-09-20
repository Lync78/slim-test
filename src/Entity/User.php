<?php 

namespace App\Entity;
use App\Enum\Role;

class User {


    private ?int $id;
    private ?string $username;
    private ?string $email;
    private ?string $password;
    private bool $error = false;
    private string $user_role = "";

    
    public function getId(): int {
        return $this->id;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setPassword($password): void {
        $this->password = password_hash($password, PASSWORD_ARGON2ID);
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setRole(Role $role): void {
        $this->user_role = $role->value;
    }

    public function getRole(): string {
        return $this->user_role;
    }

}