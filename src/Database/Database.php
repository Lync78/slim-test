<?php 

namespace App\Database; 
use PDO; 
use PDOException; 

class Database { 
    
    private PDO $connection;

    public function __construct() { 
        $databaseUrl = $_ENV['DATABASE_URL']; 
        $database = parse_url($databaseUrl);

        $host = $database['host']; 
        $port = $database['port'] ?? 3306; 
        $username = $database['user']; 
        $password = $database['pass']; 
        $dbname = ltrim($database['path'], '/');
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4"; 
        try { 
            $this->connection = new PDO( $dsn, $username, $password, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, ] ); 
        } catch (PDOException $e) { 
            throw new PDOException( 'Impossible de se connecter à la base de données.', (int) $e->getCode(), $e ); 
        }
    } 


    public function getConnection(): PDO { 
        return $this->connection;
    } 
}