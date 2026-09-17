
<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\Database;

// Chargement du fichier .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

try {
    // Création de la base de données
    $pdo->exec("
        CREATE DATABASE IF NOT EXISTS slim-test
    ");

    // Sélection de la base
    $pdo->exec("
        USE slim-test
    ");

    // Création de la table users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(32) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ");

    echo "Migration exécutée avec succès.\n";

} catch (PDOException $e) {
    echo "Erreur lors de la migration : " . $e->getMessage() . "\n";
    exit(1);
}

